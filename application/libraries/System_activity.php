<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Append-only System Activity / Audit Trail logger.
 * Never throws to the caller — logging failures are swallowed.
 */
class System_activity {

	protected $CI;
	protected $table = 'tbl_system_activity';
	/** @var bool Set true after an explicit log in this request (hook skips duplicate mutation log). */
	public $logged_this_request = false;
	protected $sensitive_keys = array(
		'password', 'password2', 'old_password', 'new_password', 'confirm_password',
		'pwd', 'pass', 'token', 'api_key', 'apikey', 'secret', 'csrf_test_name'
	);

	public function __construct() {
		$this->CI =& get_instance();
		if (!isset($this->CI->db)) {
			$this->CI->load->database();
		}
	}

	/**
	 * @param array $payload
	 * @return int|false insert id or false
	 */
	public function log($payload = array()) {
		try {
			if (!is_array($payload)) {
				return false;
			}
			if (!$this->_table_ready()) {
				return false;
			}

			$actor = $this->_actor_from_session();
			$details = isset($payload['details']) ? $payload['details'] : (isset($payload['details_json']) ? $payload['details_json'] : null);
			$details_json = $this->_encode_details($details);

			$row = array(
				'user_id' => isset($payload['user_id']) ? (int) $payload['user_id'] : $actor['user_id'],
				'username' => $this->_clip(isset($payload['username']) ? $payload['username'] : $actor['username'], 150),
				'user_name' => $this->_clip(isset($payload['user_name']) ? $payload['user_name'] : $actor['user_name'], 150),
				'usertype' => $this->_clip(isset($payload['usertype']) ? $payload['usertype'] : $actor['usertype'], 30),
				'category' => $this->_clip(isset($payload['category']) ? $payload['category'] : 'admin', 40),
				'action' => $this->_clip(isset($payload['action']) ? $payload['action'] : '', 60),
				'module' => $this->_clip(isset($payload['module']) ? $payload['module'] : '', 80),
				'controller' => $this->_clip(isset($payload['controller']) ? $payload['controller'] : '', 80),
				'method' => $this->_clip(isset($payload['method']) ? $payload['method'] : '', 80),
				'uri' => $this->_clip(isset($payload['uri']) ? $payload['uri'] : $this->_current_uri(), 500),
				'http_method' => $this->_clip(isset($payload['http_method']) ? $payload['http_method'] : $this->_http_method(), 10),
				'entity_type' => $this->_clip(isset($payload['entity_type']) ? $payload['entity_type'] : '', 80),
				'entity_id' => $this->_clip(isset($payload['entity_id']) ? $payload['entity_id'] : '', 80),
				'reference_no' => $this->_clip(isset($payload['reference_no']) ? $payload['reference_no'] : '', 120),
				'amount' => $this->_nullable_amount(isset($payload['amount']) ? $payload['amount'] : null),
				'status_before' => $this->_clip(isset($payload['status_before']) ? $payload['status_before'] : '', 60),
				'status_after' => $this->_clip(isset($payload['status_after']) ? $payload['status_after'] : '', 60),
				'summary' => $this->_clip(isset($payload['summary']) ? $payload['summary'] : '', 500),
				'details_json' => $details_json,
				'ip_address' => $this->_clip(isset($payload['ip_address']) ? $payload['ip_address'] : $this->_ip(), 64),
				'user_agent' => $this->_clip(isset($payload['user_agent']) ? $payload['user_agent'] : $this->_ua(), 255),
				'session_id' => $this->_clip(isset($payload['session_id']) ? $payload['session_id'] : $this->_session_id(), 128),
				'created_at' => isset($payload['created_at']) ? $payload['created_at'] : date('Y-m-d H:i:s'),
			);

			$ok = $this->CI->db->insert($this->table, $row);
			if (!$ok) {
				return false;
			}
			$this->logged_this_request = true;
			return (int) $this->CI->db->insert_id();
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * Infer category/action from a controller method name.
	 */
	public function infer_from_method($method) {
		$method = strtolower((string) $method);
		$map = array(
			'add' => array('create', 'create'),
			'create' => array('create', 'create'),
			'register' => array('create', 'create'),
			'edit' => array('update', 'update'),
			'update' => array('update', 'update'),
			'save' => array('update', 'save'),
			'delete' => array('delete', 'delete'),
			'multi_delete' => array('delete', 'multi_delete'),
			'post' => array('accounting', 'post'),
			'void' => array('accounting', 'void'),
			'void_entry' => array('accounting', 'void'),
			'restore' => array('admin', 'restore'),
			'processbalanceforward' => array('accounting', 'balance_forward_start'),
			'processbatch' => array('accounting', 'balance_forward_batch'),
		);
		if (isset($map[$method])) {
			return array('category' => $map[$method][0], 'action' => $map[$method][1]);
		}
		if (strpos($method, 'delete') !== false) {
			return array('category' => 'delete', 'action' => $method);
		}
		if (strpos($method, 'add') !== false || strpos($method, 'create') !== false) {
			return array('category' => 'create', 'action' => $method);
		}
		if (strpos($method, 'edit') !== false || strpos($method, 'update') !== false) {
			return array('category' => 'update', 'action' => $method);
		}
		return array('category' => 'admin', 'action' => $method !== '' ? $method : 'action');
	}

	public function sanitize_post_keys($post = null) {
		if ($post === null) {
			$post = $this->CI->input->post(null, true);
		}
		if (!is_array($post)) {
			return array();
		}
		$out = array();
		foreach ($post as $key => $value) {
			$lk = strtolower((string) $key);
			if (in_array($lk, $this->sensitive_keys, true)) {
				$out[$key] = '[redacted]';
				continue;
			}
			if (is_array($value)) {
				$out[$key] = array_keys($value);
			} else {
				$str = (string) $value;
				if (strlen($str) > 120) {
					$str = substr($str, 0, 117) . '...';
				}
				$out[$key] = $str;
			}
		}
		return $out;
	}

	protected function _table_ready() {
		static $ready = null;
		if ($ready !== null) {
			return $ready;
		}
		try {
			$ready = $this->CI->db->table_exists($this->table);
		} catch (Exception $e) {
			$ready = false;
		}
		return $ready;
	}

	protected function _actor_from_session() {
		$CI = $this->CI;
		return array(
			'user_id' => (int) $CI->session->userdata('userid'),
			'username' => (string) $CI->session->userdata('username'),
			'user_name' => (string) ($CI->session->userdata('name') ? $CI->session->userdata('name') : $CI->session->userdata('username')),
			'usertype' => (string) $CI->session->userdata('usertype'),
		);
	}

	protected function _encode_details($details) {
		if ($details === null || $details === '') {
			return null;
		}
		if (is_string($details)) {
			$trim = trim($details);
			if ($trim === '') {
				return null;
			}
			// Already JSON?
			json_decode($trim);
			if (json_last_error() === JSON_ERROR_NONE) {
				return $trim;
			}
			return json_encode(array('note' => $this->_clip($trim, 1000)));
		}
		if (is_array($details)) {
			$clean = $this->_redact_array($details);
			return json_encode($clean);
		}
		return null;
	}

	protected function _redact_array($arr) {
		$out = array();
		foreach ($arr as $k => $v) {
			$lk = strtolower((string) $k);
			if (in_array($lk, $this->sensitive_keys, true)) {
				$out[$k] = '[redacted]';
			} elseif (is_array($v)) {
				$out[$k] = $this->_redact_array($v);
			} else {
				$out[$k] = $v;
			}
		}
		return $out;
	}

	protected function _nullable_amount($amount) {
		if ($amount === null || $amount === '') {
			return null;
		}
		if (!is_numeric($amount)) {
			return null;
		}
		return round((float) $amount, 2);
	}

	protected function _clip($value, $len) {
		$value = (string) $value;
		if (function_exists('mb_substr')) {
			return mb_substr($value, 0, $len);
		}
		return substr($value, 0, $len);
	}

	protected function _current_uri() {
		try {
			return (string) $this->CI->uri->uri_string();
		} catch (Exception $e) {
			return isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
		}
	}

	protected function _http_method() {
		$method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
		return $method;
	}

	protected function _ip() {
		try {
			return (string) $this->CI->input->ip_address();
		} catch (Exception $e) {
			return isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
		}
	}

	protected function _ua() {
		$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		return $ua;
	}

	protected function _session_id() {
		$sid = $this->CI->session->userdata('session_id');
		if ($sid) {
			return (string) $sid;
		}
		if (function_exists('session_id')) {
			return (string) @session_id();
		}
		return '';
	}
}
