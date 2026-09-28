<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

/**
 * Mobile Statement of Account
 *
 * Staff-facing, mobile-first Statement of Account for the mobile app shell
 * (/master/mobile_statementofaccount). Linked from the mobile hamburger menu.
 *
 * Logic is intentionally identical to the public SOA
 * (application/modules/master/controllers/statementofaccount.php) because it
 * reuses the same model methods on statementofaccount_model:
 *   - get_customer_info()
 *   - get_customer_ledger()
 *   - calculate_running_balance()
 *
 * Access: admin always; sub-admin only when the "Statement of Account"
 * (statementofaccountlist) permission is granted. No customer password is
 * required because the caller is already an authenticated staff user.
 */
class mobile_statementofaccount extends CI_Controller {

	/** Mobile shell header (SmartAdmin mobile chrome + hamburger navigation) */
	public $headerPage = '../../views/admin-includes/mobile_header';
	public $login_redirect = '/master/app_login';
	public $listPage = 'mobile_statementofaccount';

	/** Fallback signature block (mirrors statementofaccount.php) */
	private $sign_defaults = array(
		'prepared_name'  => 'MISHELLE P. MONDARTE',
		'prepared_title' => 'Industrial Relations Management Officer C / Billing Officer',
		'verified_name'  => 'DARYL JAY T. VILLARIN',
		'verified_title' => 'Administrative/General Services Officer B / HRMO/FO/BO',
		'approved_name'  => 'ENGR. ANASTACIA T. ROMANILLOS, CE',
		'approved_title' => 'General Manager'
	);

	public function __construct() {
		parent::__construct();
		$this->load->helper('common');
		$this->load->model('statementofaccount_model', 'soa_model');
		$this->load->model('adminheader_model', 'top_model');
		$this->load->model('addcustomer_model', 'customer_model');
		$this->load->library('form_validation');
		ini_set('date.timezone', 'Asia/Manila');
		error_reporting(0);
		ini_set('display_errors', 'off');
	}

	/** True when the current session is an authenticated staff session. */
	private function _is_logged_in() {
		return ($this->session->userdata('logged_in') == 'ECOM')
			&& (trim((string) $this->session->userdata('username')) !== '');
	}

	/**
	 * Access rule mirrors statementofaccountlist::_require_access():
	 * admin always; sub-admin only with the statementofaccountlist permission.
	 */
	private function _has_soa_access() {
		if (!$this->_is_logged_in()) {
			return false;
		}
		$ut = $this->session->userdata('usertype');
		if ($ut === 'admin') {
			return true;
		}
		if ($ut === 'subadmin') {
			$rr = $this->top_model->get_responsibilities();
			if (is_array($rr) && array_key_exists('statementofaccountlist', $rr)
				&& (string) $rr['statementofaccountlist'] === '1') {
				return true;
			}
		}
		return false;
	}

	/** Page view. */
	public function index() {
		if (!$this->_is_logged_in()) {
			redirect($this->login_redirect);
		}
		if (!$this->_has_soa_access()) {
			$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">You do not have access to the Statement of Account.</div>');
			redirect('master/mobile_dashboard');
		}

		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$this->load->view($this->headerPage, $header);
		$this->load->view($this->listPage, array('msg' => ''));
	}

	/**
	 * JSON customer lookup for the searchable picker (select2 AJAX).
	 * Reuses the server-side pagination used by the desktop SOA list.
	 *
	 * GET params: q (term), page (1-based)
	 */
	public function search_customers() {
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');

		if (!$this->_has_soa_access()) {
			http_response_code(403);
			echo json_encode(array('results' => array(), 'pagination' => array('more' => false)));
			exit;
		}

		$q = $this->input->get('q');
		if ($q === null || $q === '') {
			$q = $this->input->get('term');
		}
		$q = trim((string) $q);

		$page = (int) $this->input->get('page');
		if ($page < 1) {
			$page = 1;
		}
		$limit = 20;
		$offset = ($page - 1) * $limit;

		try {
			$rows = $this->customer_model->get_paginated_records(
				$offset, $limit, $q, 'tbl_addcustomer.last_name', 'asc', ''
			);
			$total = $this->customer_model->get_total_count($q, '');

			$results = array();
			foreach ($rows as $row) {
				$name = strtoupper(trim(
					(isset($row['last_name']) ? $row['last_name'] : '') . ', ' .
					(isset($row['first_name']) ? $row['first_name'] : '') . ' ' .
					(isset($row['middle_name']) ? $row['middle_name'] : '')
				));
				$results[] = array(
					'id'            => (string) $row['customer_id'],
					'text'          => $row['customer_id'] . ' — ' . $name,
					'name'          => $name,
					'meter_number'  => isset($row['meter_number']) ? $row['meter_number'] : '',
					'address'       => isset($row['address']) ? $row['address'] : '',
					'zone'          => isset($row['zones']) ? $row['zones'] : '',
					'class_name'    => isset($row['classification_name']) ? $row['classification_name'] : '',
					'status'        => isset($row['status']) ? (int) $row['status'] : 0,
					'mobile1'       => isset($row['mobile1']) ? $row['mobile1'] : ''
				);
			}

			echo json_encode(array(
				'results'    => $results,
				'pagination' => array('more' => ($offset + $limit) < $total),
				'total'      => $total
			));
			exit;
		} catch (Exception $e) {
			log_message('error', 'Mobile SOA customer search error: ' . $e->getMessage());
			echo json_encode(array('results' => array(), 'pagination' => array('more' => false)));
			exit;
		}
	}

	/**
	 * JSON statement of account for one customer.
	 *
	 * GET /master/mobile_statementofaccount/get_soa/{customer_id}
	 * GET is used on purpose so the service worker can cache the response
	 * for offline viewing.
	 */
	public function get_soa($customer_id = '') {
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');

		if (!$this->_has_soa_access()) {
			http_response_code(403);
			echo json_encode(array('success' => false, 'message' => 'You do not have access to the Statement of Account.'));
			exit;
		}

		$customer_id = trim((string) $customer_id);
		if ($customer_id === '') {
			$customer_id = trim((string) $this->input->get('customer_id'));
		}
		if ($customer_id === '') {
			echo json_encode(array('success' => false, 'message' => 'Customer ID is required.'));
			exit;
		}

		try {
			$customer_info = $this->soa_model->get_customer_info($customer_id);
			if (empty($customer_info)) {
				echo json_encode(array('success' => false, 'message' => 'Customer not found.'));
				exit;
			}

			// Same ledger + running balance as the existing SOA.
			$entries = $this->soa_model->get_customer_ledger($customer_id);
			$entries = $this->soa_model->calculate_running_balance($entries);

			// Shortfall flags (same business rule as the desktop SOA view).
			$entries = $this->_annotate_shortfalls($entries);

			// Balance = Total Debit - Total Credit (same as get_current_balance()
			// but computed from the entries already loaded to avoid a second query).
			$total_debit = 0;
			$total_credit = 0;
			foreach ($entries as $entry) {
				$total_debit += isset($entry['debit']) ? floatval($entry['debit']) : 0;
				$total_credit += isset($entry['credit']) ? floatval($entry['credit']) : 0;
			}
			$balance = round($total_debit - $total_credit, 2);

			$summary = $this->_build_summary($customer_info, $entries, $balance, $total_debit, $total_credit);

			// Trim heavy internals before sending to the device.
			foreach ($entries as &$entry) {
				unset($entry['raw_data'], $entry['billing_periods_data'], $entry['_idx']);
			}
			unset($entry);

			echo json_encode(array(
				'success'  => true,
				'customer' => $this->_customer_dto($customer_info),
				'summary'  => $summary,
				'signatures' => $this->_signatures(),
				'ledger'   => array_values($entries)
			));
			exit;
		} catch (Exception $e) {
			log_message('error', 'Mobile SOA get_soa error: ' . $e->getMessage());
			echo json_encode(array('success' => false, 'message' => 'Unable to load the statement of account.'));
			exit;
		}
	}

	/** Slim customer DTO for the mobile UI. */
	private function _customer_dto($customer_info) {
		$name = strtoupper(trim(
			(isset($customer_info['last_name']) ? $customer_info['last_name'] : '') . ', ' .
			(isset($customer_info['first_name']) ? $customer_info['first_name'] : '') . ' ' .
			(isset($customer_info['middle_name']) ? $customer_info['middle_name'] : '')
		));

		$status = isset($customer_info['status']) ? (int) $customer_info['status'] : 0;
		if ($status === 1) {
			$status_label = 'Active';
		} elseif ($status === 2) {
			$status_label = 'Disconnected';
		} else {
			$status_label = 'De-Active';
		}

		return array(
			'customer_id'        => isset($customer_info['customer_id']) ? $customer_info['customer_id'] : '',
			'name'               => $name,
			'address'            => isset($customer_info['address']) ? strtoupper($customer_info['address']) : '',
			'zone'               => isset($customer_info['zone']) ? $customer_info['zone'] : '',
			'class_name'         => isset($customer_info['class_name']) ? $customer_info['class_name'] : '',
			'cust_type_name'     => isset($customer_info['cust_type_name']) ? $customer_info['cust_type_name'] : '',
			'meter_number'       => isset($customer_info['meter_number']) ? $customer_info['meter_number'] : '',
			'status'             => $status,
			'status_label'       => $status_label,
			'mobile1'            => isset($customer_info['mobile1']) ? $customer_info['mobile1'] : '',
			'mobile2'            => isset($customer_info['mobile2']) ? $customer_info['mobile2'] : '',
			'email_id'           => isset($customer_info['email_id']) ? $customer_info['email_id'] : '',
			'special_priviledge' => isset($customer_info['special_priviledge']) ? (string) $customer_info['special_priviledge'] : '0'
		);
	}

	/**
	 * Build the sticky-header summary.
	 *
	 * "before_due" is the outstanding balance. "after_due" adds a projected
	 * penalty on the latest bill using the same 10% rule as
	 * mobile_dashboard::compute_all(). It is a projection and is labelled as
	 * such in the UI. Senior citizens / privileged accounts are excluded.
	 */
	private function _build_summary($customer_info, $entries, $balance, $total_debit, $total_credit) {
		$latest = null;
		$latest_key = -1;
		foreach ($entries as $entry) {
			if (!isset($entry['type']) || $entry['type'] !== 'billing') {
				continue;
			}
			$raw = isset($entry['raw_data']) && is_array($entry['raw_data']) ? $entry['raw_data'] : array();
			$m = isset($raw['month']) ? (int) $raw['month'] : 0;
			$y = isset($raw['year']) ? (int) $raw['year'] : 0;
			$key = ($y * 100) + $m;
			if ($key > $latest_key) {
				$latest_key = $key;
				$latest = array(
					'period_label' => trim((isset($raw['month_name']) ? $raw['month_name'] : '') . ' ' . $y),
					'amount'       => floatval($entry['debit']),
					'due_date'     => isset($entry['due_date']) ? $entry['due_date'] : '',
					'penalty'      => isset($entry['penalty']) ? floatval($entry['penalty']) : 0,
					'sc_discount'  => isset($entry['sc_discount']) ? floatval($entry['sc_discount']) : 0
				);
			}
		}

		$is_privileged = (isset($customer_info['special_priviledge']) && (string) $customer_info['special_priviledge'] === '1');
		$penalty_rate = 10;
		$penalty_projected = false;
		$penalty_amount = 0.0;

		if (!$is_privileged && $latest !== null && $balance > 0.009) {
			$base = floatval($latest['amount']);
			if ($base > 0) {
				$penalty_amount = round(($base * $penalty_rate) / 100, 2);
				$penalty_projected = true;
			}
		}

		return array(
			'balance'           => $balance,
			'total_debit'       => round($total_debit, 2),
			'total_credit'      => round($total_credit, 2),
			'is_settled'        => ($balance <= 0.009),
			'before_due'        => $balance,
			'after_due'         => round($balance + $penalty_amount, 2),
			'penalty_rate'      => $penalty_rate,
			'penalty_amount'    => $penalty_amount,
			'penalty_projected' => $penalty_projected,
			'latest_period'     => ($latest !== null) ? $latest['period_label'] : '',
			'latest_bill'       => ($latest !== null) ? $latest['amount'] : 0,
			'latest_due_date'   => ($latest !== null) ? $latest['due_date'] : '',
			'latest_penalty'    => ($latest !== null) ? $latest['penalty'] : 0,
			'entry_count'       => count($entries),
			'generated_at'      => date('Y-m-d H:i:s')
		);
	}

	/**
	 * Flag billing periods not fully covered by payments/discounts so the cards
	 * can show the same "Underpaid — shortfall" note as the desktop SOA view.
	 */
	private function _annotate_shortfalls($entries) {
		if (empty($entries) || !is_array($entries)) {
			return $entries;
		}

		$period_debits = array();
		$period_credits = array();
		foreach ($entries as $e) {
			$type = isset($e['type']) ? $e['type'] : '';
			if ($type === 'billing') {
				$raw = isset($e['raw_data']) && is_array($e['raw_data']) ? $e['raw_data'] : array();
				if (isset($raw['month'], $raw['year'])) {
					$pk = $raw['month'] . '_' . $raw['year'];
					$period_debits[$pk] = (isset($period_debits[$pk]) ? $period_debits[$pk] : 0) + floatval($e['debit']);
				}
			}
			if (in_array($type, array('payment', 'leaking_discount', 'adjustment'), true)) {
				$bp_data = isset($e['billing_periods_data']) && is_array($e['billing_periods_data']) ? $e['billing_periods_data'] : array();
				$signed = floatval($e['credit']) - floatval($e['debit']);
				foreach ($bp_data as $pk => $pd) {
					$period_credits[$pk] = (isset($period_credits[$pk]) ? $period_credits[$pk] : 0) + $signed;
				}
			}
		}

		$short_periods = array();
		foreach ($period_debits as $pk => $deb) {
			$cred = isset($period_credits[$pk]) ? $period_credits[$pk] : 0;
			$gap = $deb - $cred;
			if ($gap > 0.009) {
				$short_periods[$pk] = round($gap, 2);
			}
		}

		foreach ($entries as &$entry) {
			$entry['is_shortfall'] = false;
			$entry['shortfall'] = 0;
			$type = isset($entry['type']) ? $entry['type'] : '';
			if ($type === 'billing') {
				$raw = isset($entry['raw_data']) && is_array($entry['raw_data']) ? $entry['raw_data'] : array();
				if (isset($raw['month'], $raw['year'])) {
					$pk = $raw['month'] . '_' . $raw['year'];
					if (isset($short_periods[$pk])) {
						$entry['is_shortfall'] = true;
						$entry['shortfall'] = $short_periods[$pk];
					}
				}
			} elseif (in_array($type, array('payment', 'leaking_discount', 'adjustment'), true)) {
				$bp_data = isset($entry['billing_periods_data']) && is_array($entry['billing_periods_data']) ? $entry['billing_periods_data'] : array();
				foreach ($bp_data as $pk => $pd) {
					if (isset($short_periods[$pk])) {
						$entry['is_shortfall'] = true;
						$entry['shortfall'] = $short_periods[$pk];
						break;
					}
				}
			}
		}
		unset($entry);

		return $entries;
	}

	/** Signature block, honouring the shared soa_sign_* cookie overrides. */
	private function _signatures() {
		return array(
			'prepared_name'  => $this->_sign_cookie('soa_sign_prepared_name', $this->sign_defaults['prepared_name']),
			'prepared_title' => $this->_sign_cookie('soa_sign_prepared_title', $this->sign_defaults['prepared_title']),
			'verified_name'  => $this->_sign_cookie('soa_sign_verified_name', $this->sign_defaults['verified_name']),
			'verified_title' => $this->_sign_cookie('soa_sign_verified_title', $this->sign_defaults['verified_title']),
			'approved_name'  => $this->_sign_cookie('soa_sign_approved_name', $this->sign_defaults['approved_name']),
			'approved_title' => $this->_sign_cookie('soa_sign_approved_title', $this->sign_defaults['approved_title'])
		);
	}

	/** Read a sanitised signature value from a cookie (same as statementofaccount.php). */
	private function _sign_cookie($key, $default) {
		if (!isset($_COOKIE[$key])) {
			return $default;
		}
		$val = trim(rawurldecode((string) $_COOKIE[$key]));
		$val = preg_replace('/[\x00-\x1F\x7F]/', '', $val);
		if (function_exists('mb_substr')) {
			$val = mb_substr($val, 0, 120, 'UTF-8');
		} else {
			$val = substr($val, 0, 120);
		}
		return ($val !== '') ? $val : $default;
	}
}
?>
