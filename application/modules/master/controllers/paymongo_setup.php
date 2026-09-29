<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * PayMongo Setup — Settings > PayMongo Setup.
 *
 * Stores the credentials and switches for the Online / QR Ph payment module:
 * enable/disable, test vs live, public key, secret key, webhook secret, the
 * minimum charge, the emailed-link lifetime, the description prefix and the
 * 80mm receipt note. Also shows the webhook URL to paste into PayMongo and a
 * read-only "Test connection" probe.
 *
 * Access: admin always; sub-admin only when tbl_responsibilities.paymongo_setup
 * is ticked (see sql/add_paymongo_permissions.sql).
 *
 * Secrets are read from the model and rendered masked. A blank field keeps the
 * stored value, so an operator can change the minimum without retyping a key.
 */
class paymongo_setup extends CI_Controller {

	public $headerPage = '../../views/admin-includes/header';
	public $listPage = 'paymongo_setup';
	public $editPage = 'paymongo_setup-edit';

	public $listPage_redirect = '/master/paymongo_setup';

	public function __construct() {
		parent::__construct();
		$this->load->model('paymongo_settings_model', 'settings_model');
		$this->load->model('adminheader_model', 'top_model');
		$this->load->library('form_validation');
		$this->load->library('Paymongo');
		$this->load->helper('common_helper');
		ini_set('date.timezone', 'Asia/Manila');
		error_reporting(0);
		ini_set('display_errors', 'off');

		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
	}

	/** True when the signed-in staff member may open this module. */
	private function _require_access() {
		$ut = $this->session->userdata('usertype');
		if ($ut === 'admin') {
			return true;
		}
		if ($ut === 'subadmin') {
			$rr = $this->head['roleResponsible'];
			if (is_array($rr) && array_key_exists('paymongo_setup', $rr)
				&& (string) $rr['paymongo_setup'] === '1') {
				return true;
			}
		}
		$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">You do not have access to PayMongo Setup.</div>');
		redirect('master/dashboard');
		return false;
	}

	/** Status + webhook URL + last recorded failure. */
	public function index() {
		$this->_require_access();

		$settings = $this->settings_model->get_settings();
		$data['settings'] = $settings;
		$data['table_ready'] = $this->settings_model->table_ready();
		$data['is_configured'] = $this->settings_model->is_configured();
		$data['is_test_mode'] = $this->settings_model->is_test_mode();
		$data['webhook_url'] = $this->paymongo->webhook_url();
		$data['masked'] = array(
			'public_key' => Paymongo::mask(isset($settings['public_key']) ? $settings['public_key'] : ''),
			'secret_key' => Paymongo::mask(isset($settings['secret_key']) ? $settings['secret_key'] : ''),
			'webhook_secret' => Paymongo::mask(isset($settings['webhook_secret']) ? $settings['webhook_secret'] : ''),
		);
		$data['last_error'] = isset($settings['last_error']) ? $settings['last_error'] : '';
		$data['last_error_at'] = isset($settings['last_error_at']) ? $settings['last_error_at'] : '';

		$this->load->view($this->headerPage, $this->head);
		$this->load->view($this->listPage, $data);
	}

	/** The settings form. */
	public function edit() {
		$this->_require_access();

		$settings = $this->settings_model->get_settings();
		$data['settings'] = $settings;
		$data['table_ready'] = $this->settings_model->table_ready();
		$data['masked'] = array(
			'public_key' => Paymongo::mask(isset($settings['public_key']) ? $settings['public_key'] : ''),
			'secret_key' => Paymongo::mask(isset($settings['secret_key']) ? $settings['secret_key'] : ''),
			'webhook_secret' => Paymongo::mask(isset($settings['webhook_secret']) ? $settings['webhook_secret'] : ''),
		);

		// CodeIgniter 2 returns FALSE (not NULL) for a missing post key, so test the
		// request method instead. Comparing to NULL made this branch run on a plain
		// GET, which saved empty values and bounced straight back to the status page.
		if (strtoupper((string) $this->input->server('REQUEST_METHOD')) === 'POST') {
			$clear = array();
			foreach (array('public_key', 'secret_key', 'webhook_secret') as $field) {
				if ((string) $this->input->post('clear_' . $field) === '1') {
					$clear[] = $field;
				}
			}
			$result = $this->settings_model->save_settings(
				$this->input->post(),
				(int) $this->session->userdata('userid'),
				$clear
			);
			if ($result) {
				$this->session->set_flashdata('msg_succ', 'PayMongo settings saved.');
			} else {
				$this->session->set_flashdata('msg_succ', 'Not saved: run sql/add_paymongo_settings.sql first.');
			}
			redirect($this->listPage_redirect);
			return;
		}

		$this->load->view($this->headerPage, $this->head);
		$this->load->view($this->editPage, $data);
	}

	/**
	 * Read-only connection probe. Creates nothing on the PayMongo account.
	 * Answers JSON for the AJAX button, or redirects with a flash when posted
	 * without JavaScript.
	 */
	public function test_connection() {
		$this->_require_access();

		if (!$this->settings_model->table_ready()) {
			$out = array('ok' => false, 'class' => 'check failed', 'message' => 'Run sql/add_paymongo_settings.sql first.');
		} else {
			$out = $this->paymongo->test_connection();
			if (!empty($out['ok'])) {
				$this->settings_model->clear_last_error();
			} else {
				$this->settings_model->record_last_error($out['message'], isset($out['class']) ? $out['class'] : '');
			}
		}

		if ($this->input->is_ajax_request()) {
			header('Content-Type: application/json');
			echo json_encode($out);
			exit;
		}

		$this->session->set_flashdata('msg_succ',
			($out['ok'] ? '<span class="text-success">' : '<span class="text-danger">')
			. htmlspecialchars($out['message'], ENT_QUOTES, 'UTF-8') . '</span>');
		redirect($this->listPage_redirect);
	}
}
