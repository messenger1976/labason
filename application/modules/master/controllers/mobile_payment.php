<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mobile Payment (QR Ph) — the meter reader's collection screen.
 *
 * Mirrors mobile_statementofaccount (2026-09-28): same mobile shell, same
 * customer picker shape, same permission model. A collector searches a customer,
 * sees every billing period with its paid/unpaid state, ticks what is being paid
 * and generates a QR Ph code for the customer to scan. Settlement reuses
 * onlinepayment_model::settle(), so the record written here is identical to the
 * backend Cash Payment module — minus the OR, plus the PayMongo reference.
 *
 * Access: admin always; sub-admin only with tbl_responsibilities.mobile_payment.
 */
class mobile_payment extends CI_Controller {

	public $headerPage = '../../views/admin-includes/mobile_header';
	public $listPage = 'mobile_payment';
	public $successPage = 'mobile_payment_success';
	public $receiptPage = 'partials/receipt80';
	public $login_redirect = '/master/app_login';
	public $listPage_redirect = '/master/mobile_payment';

	public function __construct() {
		parent::__construct();
		$this->load->helper('common');
		$this->load->model('onlinepayment_model', 'my_model');
		$this->load->model('addcustomer_model', 'customer_model');
		$this->load->model('addpaymentcustomer_model', 'pay_model');
		$this->load->model('adminheader_model', 'top_model');
		$this->load->library('form_validation');
		ini_set('date.timezone', 'Asia/Manila');
		error_reporting(0);
		ini_set('display_errors', 'off');
	}

	/** True when the session is an authenticated staff session. */
	private function _is_logged_in() {
		return ($this->session->userdata('logged_in') == 'ECOM')
			&& (trim((string) $this->session->userdata('username')) !== '');
	}

	/** Admin always; sub-admin only with the mobile_payment key. */
	private function _has_access() {
		if (!$this->_is_logged_in()) {
			return false;
		}
		$ut = $this->session->userdata('usertype');
		if ($ut === 'admin') {
			return true;
		}
		if ($ut === 'subadmin') {
			$rr = $this->top_model->get_responsibilities();
			if (is_array($rr) && array_key_exists('mobile_payment', $rr)
				&& (string) $rr['mobile_payment'] === '1') {
				return true;
			}
		}
		return false;
	}

	/** Gate, answering JSON for the AJAX endpoints. */
	private function _require_access() {
		if (!$this->_is_logged_in()) {
			$this->_deny('Please sign in again.');
		}
		if (!$this->_has_access()) {
			$this->_deny('You do not have access to the Payment module.');
		}
		return true;
	}

	private function _deny($message) {
		if ($this->input->is_ajax_request()) {
			http_response_code(403);
			header('Content-Type: application/json');
			echo json_encode(array('ok' => false, 'message' => $message));
			exit;
		}
		$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>');
		redirect('master/mobile_dashboard');
		return false;
	}

	private function json($payload) {
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		echo json_encode($payload);
		exit;
	}

	// ---------------------------------------------------------------------
	// Screen
	// ---------------------------------------------------------------------

	public function index() {
		if (!$this->_is_logged_in()) {
			redirect($this->login_redirect);
			return;
		}
		if (!$this->_has_access()) {
			$this->_deny('You do not have access to the Payment module.');
			return;
		}

		$this->my_model->expire_lapsed_attempts();

		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data = array(
			'ready' => $this->my_model->table_ready(),
			'gateway_configured' => $this->_gateway_configured(),
			'qr_attempt' => array(),
			'qr_customer' => array(),
			'qr_rows' => array(),
		);

		// Creating a QR reloads here so PHP renders the shared QR partial.
		$attempt_id = (int) $this->input->get('attempt');
		if ($attempt_id > 0) {
			$attempt = $this->my_model->get_attempt($attempt_id);
			if (!empty($attempt)) {
				$data['qr_attempt'] = $attempt;
				$data['qr_customer'] = $this->my_model->get_customer_info($attempt['customer_id']);
				$rows = json_decode(isset($attempt['bill_rows_json']) ? (string) $attempt['bill_rows_json'] : '', true);
				$data['qr_rows'] = is_array($rows) ? $rows : array();
			}
		}

		if (empty($header['roleResponsible'])) {
			$header['roleResponsible'] = array();
		}

		$this->load->view($this->headerPage, $header);
		$this->load->view($this->listPage, $data);
	}

	/** Receipt / thank-you page after the money lands. */
	public function success($id = 0) {
		$this->_require_access();

		$attempt = $this->my_model->get_attempt($id);
		if (empty($attempt)) {
			redirect($this->listPage_redirect);
			return;
		}

		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$rows = json_decode(isset($attempt['bill_rows_json']) ? (string) $attempt['bill_rows_json'] : '', true);

		$data = array(
			'attempt' => $attempt,
			'customer' => $this->my_model->get_customer_info($attempt['customer_id']),
			'rows' => is_array($rows) ? $rows : array(),
		);

		$this->load->view($this->headerPage, $header);
		$this->load->view($this->successPage, $data);
	}

	/** 80mm receipt (mobile-friendly: preview first, then print). */
	public function receipt($id = 0) {
		$this->_require_access();

		$attempt = $this->my_model->get_attempt($id);
		if (empty($attempt)) {
			show_404();
			return;
		}
		$rows = json_decode(isset($attempt['bill_rows_json']) ? (string) $attempt['bill_rows_json'] : '', true);

		$this->load->view($this->receiptPage, array(
			'attempt' => $attempt,
			'customer' => $this->my_model->get_customer_info($attempt['customer_id']),
			'rows' => is_array($rows) ? $rows : array(),
			'address' => $this->pay_model->get_address(),
			'autoprint' => (string) $this->input->get('preview') !== '1',
		));
	}

	// ---------------------------------------------------------------------
	// JSON
	// ---------------------------------------------------------------------

	/** Customer picker: name, customer id or meter number. */
	public function search_customers() {
		$this->_require_access();

		$q = $this->input->get('q');
		if ($q === null || $q === '') {
			$q = $this->input->get('term');
		}
		$q = trim((string) $q);

		try {
			$rows = $this->customer_model->get_paginated_records(0, 20, $q, 'tbl_addcustomer.last_name', 'asc', '');
			$results = array();
			foreach ($rows as $row) {
				$name = strtoupper(trim(
					(isset($row['last_name']) ? $row['last_name'] : '') . ', ' .
					(isset($row['first_name']) ? $row['first_name'] : '') . ' ' .
					(isset($row['middle_name']) ? $row['middle_name'] : '')
				));
				$results[] = array(
					'id' => (string) $row['customer_id'],
					'name' => $name,
					'meter_number' => isset($row['meter_number']) ? $row['meter_number'] : '',
					'address' => isset($row['address']) ? $row['address'] : '',
					'zone' => isset($row['zones']) ? $row['zones'] : '',
				);
			}
			$this->json(array('ok' => true, 'results' => $results));
		} catch (Exception $e) {
			log_message('error', 'Mobile payment search error: ' . $e->getMessage());
			$this->json(array('ok' => false, 'message' => 'Search failed.', 'results' => array()));
		}
	}

	/** Customer header + billing periods with amounts due. */
	public function get_bills($customer_id = '') {
		$this->_require_access();

		$customer_id = trim((string) $customer_id);
		if ($customer_id === '') {
			$customer_id = trim((string) $this->input->get('customer_id'));
		}
		if ($customer_id === '') {
			$this->json(array('ok' => false, 'message' => 'Customer ID is required.'));
		}

		$customer = $this->my_model->get_customer_info($customer_id);
		if (empty($customer)) {
			$this->json(array('ok' => false, 'message' => 'Customer not found.'));
		}

		$rows = $this->my_model->get_customer_bills($customer_id);
		$total_due = 0;
		foreach ($rows as $row) {
			if (!empty($row['is_payable'])) {
				$total_due += (float) $row['amount_due'];
			}
		}

		$this->json(array(
			'ok' => true,
			'customer' => array(
				'customer_id' => $customer_id,
				'name' => $this->my_model->full_name($customer),
				'meter_number' => isset($customer['meter_number']) ? $customer['meter_number'] : '',
				'address' => trim((string) (isset($customer['address']) ? $customer['address'] : '') . ' ' . (isset($customer['city']) ? $customer['city'] : '')),
				'zone' => isset($customer['zones']) ? $customer['zones'] : '',
				'email' => isset($customer['email_id']) ? $customer['email_id'] : '',
				'mobile' => isset($customer['mobile1']) ? $customer['mobile1'] : '',
				// Same figure the desktop Online Payment page shows: the sum of the unpaid
				// periods that can actually be collected here. `tbl_addcustomer` has no
				// `balance` column, so reading one renders 0.00 on the card.
				'balance' => round($total_due, 2),
			),
			'rows' => $rows,
			'total_due' => round($total_due, 2),
		));
	}

	/** Create the QR for the ticked periods. */
	public function create_qr() {
		$this->_require_access();

		$customer_id = trim((string) $this->input->post('customer_id'));
		if ($customer_id === '') {
			$this->json(array('ok' => false, 'message' => 'Customer ID is required.'));
		}

		$raw = $this->input->post('rows');
		$selected = array();
		$decoded = is_string($raw) ? json_decode($raw, true) : $raw;
		if (is_array($decoded)) {
			foreach ($decoded as $item) {
				if (is_array($item) && isset($item['month'], $item['year'])) {
					$selected[trim((string) $item['month']) . '|' . trim((string) $item['year'])] = true;
				} elseif (is_string($item) && $item !== '') {
					$selected[trim($item)] = true;
				}
			}
		}
		if (empty($selected)) {
			$this->json(array('ok' => false, 'message' => 'Tick at least one billing period.'));
		}

		// Re-read server-side; the device never dictates an amount.
		$all = $this->my_model->get_customer_bills($customer_id);
		$rows = array();
		foreach ($all as $row) {
			if (isset($selected[$row['month'] . '|' . $row['year']])) {
				$rows[] = $row;
			}
		}
		if (empty($rows)) {
			$this->json(array('ok' => false, 'message' => 'None of the ticked periods are payable. Reload and try again.'));
		}

		$result = $this->my_model->start_qrph(
			$customer_id,
			$rows,
			'mobile',
			(int) $this->session->userdata('userid')
		);

		if (empty($result['ok'])) {
			$this->json(array('ok' => false, 'message' => $result['message']));
		}

		$attempt = $result['attempt'];
		$this->json(array(
			'ok' => true,
			'message' => $result['message'],
			'amount' => isset($attempt['amount']) ? (float) $attempt['amount'] : 0,
			'reference_no' => isset($attempt['reference_no']) ? $attempt['reference_no'] : '',
			'show_url' => ADMIN_URL . 'mobile_payment?attempt=' . (int) $attempt['id'],
		));
	}

	/** Poll: settles the payment when PayMongo says it is paid. */
	public function status($id = 0) {
		$this->_require_access();

		$result = $this->my_model->refresh_attempt((int) $id, 'poll');
		$attempt = isset($result['attempt']) ? $result['attempt'] : array();
		$paid = !empty($result['paid']);
		$status = isset($attempt['status']) ? (string) $attempt['status'] : 'pending';

		$this->json(array(
			'ok' => !empty($result['ok']),
			'paid' => $paid,
			'status' => $status,
			'status_label' => $this->my_model->status_label($status),
			'message' => isset($result['message']) ? $result['message'] : '',
			'amount' => isset($attempt['amount']) ? (float) $attempt['amount'] : 0,
			'success_url' => $paid ? (ADMIN_URL . 'mobile_payment/success/' . (int) $id) : '',
		));
	}

	// ---------------------------------------------------------------------

	private function _gateway_configured() {
		$this->load->library('Paymongo');
		return $this->paymongo->is_configured();
	}
}
