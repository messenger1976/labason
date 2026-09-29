<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Online Payment (QR Ph) — desktop / cashier screen.
 *
 * Same workflow as the Cash Payment module: search a customer, see every billing
 * period with its paid/unpaid state, tick the periods to pay, then either show
 * the QR on screen or email the customer a signed payment link. Settlement runs
 * through onlinepayment_model::settle(), which writes exactly what the cashier's
 * Cash Payment module writes — the only differences are the channel flag, the
 * PayMongo reference and the absence of an OR.
 *
 * Access: admin always; sub-admin only with tbl_responsibilities.onlinepayment.
 */
class onlinepayment extends CI_Controller {

	public $headerPage = '../../views/admin-includes/header';
	public $listPage = 'onlinepayment';
	public $managePage = 'onlinepayment_manage';
	public $receiptPage = 'partials/receipt80';
	public $login_redirect = '/master/app_login';

	public $listPage_redirect = '/master/onlinepayment';
	public $manage_redirect = '/master/onlinepayment/manage';

	public function __construct() {
		parent::__construct();
		$this->load->model('onlinepayment_model', 'my_model');
		$this->load->model('addcustomer_model', 'customer_model');
		$this->load->model('addpaymentcustomer_model', 'pay_model');
		$this->load->model('mail_template_model', 'mail_model');
		$this->load->model('adminheader_model', 'top_model');
		$this->load->library('form_validation');
		$this->load->helper('common_helper');
		ini_set('date.timezone', 'Asia/Manila');
		error_reporting(0);
		ini_set('display_errors', 'off');

		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
	}

	/** Gate every entry point (admin bypasses). */
	private function _require_access() {
		$ut = $this->session->userdata('usertype');
		if ($ut === 'admin') {
			return true;
		}
		if ($ut === 'subadmin') {
			$rr = $this->head['roleResponsible'];
			if (is_array($rr) && array_key_exists('onlinepayment', $rr) && (string) $rr['onlinepayment'] === '1') {
				return true;
			}
		}
		if ($this->input->is_ajax_request()) {
			http_response_code(403);
			header('Content-Type: application/json');
			echo json_encode(array('ok' => false, 'message' => 'You do not have access to Online Payment.'));
			exit;
		}
		$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">You do not have access to Online Payment.</div>');
		redirect('master/dashboard');
		return false;
	}

	/** JSON envelope. */
	private function json($payload) {
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		echo json_encode($payload);
		exit;
	}

	// ---------------------------------------------------------------------
	// Screens
	// ---------------------------------------------------------------------

	/** Customer search + bill selection. */
	public function index() {
		$this->_require_access();

		$this->my_model->expire_lapsed_attempts();

		$data['msg'] = '';
		$data['ready'] = $this->my_model->table_ready();
		$data['gateway_configured'] = $this->_gateway_configured();
		$data['recent'] = $this->my_model->get_attempts(array(), 10, 0);

		// After a QR is created the page reloads with ?attempt=<id> so the shared
		// QR partial is rendered by PHP, exactly as the mobile page renders it.
		$attempt_id = (int) $this->input->get('attempt');
		$data['qr_attempt'] = array();
		if ($attempt_id > 0) {
			$attempt = $this->my_model->get_attempt($attempt_id);
			if (!empty($attempt)) {
				$attempt['link'] = $this->_issue_link($attempt);
				$data['qr_attempt'] = $attempt;
				$data['qr_customer'] = $this->my_model->get_customer_info($attempt['customer_id']);
				$data['qr_rows'] = json_decode(isset($attempt['bill_rows_json']) ? (string) $attempt['bill_rows_json'] : '', true);
				$data['qr_rows'] = is_array($data['qr_rows']) ? $data['qr_rows'] : array();
			}
		}

		$this->load->view($this->headerPage, $this->head);
		$this->load->view($this->listPage, $data);
	}

	/** Attempt history, including pending and lapsed codes. */
	public function manage() {
		$this->_require_access();

		$this->my_model->expire_lapsed_attempts();

		// CI 2 returns FALSE (not NULL, not '') for a missing GET key, so normalise
		// every filter to a string before use.
		$status = trim((string) $this->input->get('status'));
		$filters = array(
			'date_from' => trim((string) $this->input->get('date_from')),
			'date_to'   => trim((string) $this->input->get('date_to')),
			'status'    => $status !== '' ? $status : 'all',
			'context'   => trim((string) $this->input->get('context')),
			'q'         => trim((string) $this->input->get('q')),
		);

		$limit = 25;
		$page = (int) $this->input->get('page');
		if ($page < 1) {
			$page = 1;
		}
		$offset = ($page - 1) * $limit;

		$data['msg'] = '';
		$data['ready'] = $this->my_model->table_ready();
		$data['filters'] = $filters;
		$data['rows'] = $this->my_model->get_attempts($filters, $limit, $offset);
		$data['total'] = $this->my_model->count_attempts($filters);
		$data['page'] = $page;
		$data['limit'] = $limit;
		$data['totals'] = $this->my_model->get_report_totals($filters);

		$this->load->view($this->headerPage, $this->head);
		$this->load->view($this->managePage, $data);
	}

	/** 80mm receipt for a settled online payment. */
	public function receipt($id) {
		$this->_require_access();

		$attempt = $this->my_model->get_attempt($id);
		if (empty($attempt)) {
			show_404();
			return;
		}

		$data['attempt'] = $attempt;
		$data['customer'] = $this->my_model->get_customer_info($attempt['customer_id']);
		$data['rows'] = json_decode(isset($attempt['bill_rows_json']) ? (string) $attempt['bill_rows_json'] : '', true);
		$data['rows'] = is_array($data['rows']) ? $data['rows'] : array();
		$data['address'] = $this->pay_model->get_address();
		$data['autoprint'] = (string) $this->input->get('preview') !== '1';
		$this->load->view($this->receiptPage, $data);
	}

	// ---------------------------------------------------------------------
	// JSON: search, bills, QR
	// ---------------------------------------------------------------------

	/** Select2 customer lookup (same shape as the mobile SOA picker). */
	public function search_customers() {
		$this->_require_access();

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
			$rows = $this->customer_model->get_paginated_records($offset, $limit, $q, 'tbl_addcustomer.last_name', 'asc', '');
			$total = $this->customer_model->get_total_count($q, '');

			$results = array();
			foreach ($rows as $row) {
				$name = strtoupper(trim(
					(isset($row['last_name']) ? $row['last_name'] : '') . ', ' .
					(isset($row['first_name']) ? $row['first_name'] : '') . ' ' .
					(isset($row['middle_name']) ? $row['middle_name'] : '')
				));
				$results[] = array(
					'id' => (string) $row['customer_id'],
					'text' => $row['customer_id'] . ' — ' . $name,
					'name' => $name,
					'meter_number' => isset($row['meter_number']) ? $row['meter_number'] : '',
					'address' => isset($row['address']) ? $row['address'] : '',
					'zone' => isset($row['zones']) ? $row['zones'] : '',
					'status' => isset($row['status']) ? (int) $row['status'] : 0,
				);
			}
			$this->json(array(
				'results' => $results,
				'pagination' => array('more' => ($offset + $limit) < $total),
				'total' => $total,
			));
		} catch (Exception $e) {
			log_message('error', 'Online payment customer search error: ' . $e->getMessage());
			$this->json(array('results' => array(), 'pagination' => array('more' => false)));
		}
	}

	/** Customer header + every billing period with its amount due. */
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
		$payable = 0;
		$total_due = 0;
		foreach ($rows as $row) {
			if (!empty($row['is_payable'])) {
				$payable++;
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
				'balance' => round($total_due, 2),
			),
			'rows' => $rows,
			'payable_count' => $payable,
			'total_due' => round($total_due, 2),
		));
	}

	/**
	 * Create a QR Ph attempt for the selected rows.
	 * POST: customer_id, rows (JSON array of {month,year}), mode ('qr'|'link'), email (optional)
	 */
	public function generate() {
		$this->_require_access();

		$customer_id = trim((string) $this->input->post('customer_id'));
		if ($customer_id === '') {
			$this->json(array('ok' => false, 'message' => 'Customer ID is required.'));
		}

		$selected = $this->_selected_rows_from_post();
		if (empty($selected)) {
			$this->json(array('ok' => false, 'message' => 'Select at least one billing period.'));
		}

		// Re-read the bills server-side and keep only what was selected, so a
		// browser can never dictate an amount.
		$all = $this->my_model->get_customer_bills($customer_id);
		$rows = array();
		foreach ($all as $row) {
			if (isset($selected[$row['month'] . '|' . $row['year']])) {
				$rows[] = $row;
			}
		}
		if (empty($rows)) {
			$this->json(array('ok' => false, 'message' => 'None of the selected periods are payable. Reload and try again.'));
		}

		$result = $this->my_model->start_qrph(
			$customer_id,
			$rows,
			'desktop',
			(int) $this->session->userdata('userid')
		);

		if (empty($result['ok'])) {
			$this->json(array('ok' => false, 'message' => $result['message']));
		}

		$attempt = $result['attempt'];
		$payload = array(
			'ok' => true,
			'message' => $result['message'],
			'reused' => !empty($result['reused']),
			'show_url' => ADMIN_URL . 'onlinepayment?attempt=' . (isset($attempt['id']) ? (int) $attempt['id'] : 0),
			'attempt' => array(
				'id' => isset($attempt['id']) ? (int) $attempt['id'] : 0,
				'reference_no' => isset($attempt['reference_no']) ? $attempt['reference_no'] : '',
				'amount' => isset($attempt['amount']) ? (float) $attempt['amount'] : 0,
				'status' => isset($attempt['status']) ? $attempt['status'] : 'pending',
				'qr_image_url' => isset($attempt['qr_image_url']) ? $attempt['qr_image_url'] : '',
				'qr_test_url' => isset($attempt['qr_test_url']) ? $attempt['qr_test_url'] : '',
				'expires_at' => isset($attempt['expires_at']) ? $attempt['expires_at'] : '',
			),
			'receipt_url' => ADMIN_URL . 'onlinepayment/receipt/' . (isset($attempt['id']) ? (int) $attempt['id'] : 0),
			'print_preview_url' => ADMIN_URL . 'onlinepayment/receipt/' . (isset($attempt['id']) ? (int) $attempt['id'] : 0) . '?preview=1',
		);

		if ($this->input->post('mode') === 'link') {
			$email = trim((string) $this->input->post('email'));
			$link = $this->_issue_link($attempt);
			$payload['link'] = $link;
			if ($email !== '') {
				$sent = $this->_send_link_email($attempt, $link, $email);
				$payload['emailed'] = $sent;
				$payload['message'] = $sent
					? 'Payment link emailed to ' . $email . '.'
					: 'The link was created, but the email could not be sent. Copy the link and send it manually.';
			}
		}

		$this->json($payload);
	}

	/** Poll one attempt; settles it when PayMongo says it is paid. */
	public function status($id = 0) {
		$this->_require_access();

		$result = $this->my_model->refresh_attempt((int) $id, 'poll');
		$attempt = isset($result['attempt']) ? $result['attempt'] : array();
		$is_paid = !empty($result['paid']);
		$attempt_id = isset($attempt['id']) ? (int) $attempt['id'] : (int) $id;

		$this->json(array(
			'ok' => !empty($result['ok']),
			'paid' => $is_paid,
			'status' => isset($attempt['status']) ? $attempt['status'] : 'pending',
			'status_label' => $this->my_model->status_label(isset($attempt['status']) ? $attempt['status'] : 'pending'),
			'message' => isset($result['message']) ? $result['message'] : '',
			'amount' => isset($attempt['amount']) ? (float) $attempt['amount'] : 0,
			'receipt_url' => ADMIN_URL . 'onlinepayment/receipt/' . $attempt_id,
		));
	}

	/** Manual "Check status" — the localhost substitute for a webhook. */
	public function check_status($id = 0) {
		$this->_require_access();

		$result = $this->my_model->refresh_attempt((int) $id, 'manual');
		if ($this->input->is_ajax_request()) {
			$this->json(array(
				'ok' => !empty($result['ok']),
				'paid' => !empty($result['paid']),
				'message' => isset($result['message']) ? $result['message'] : '',
				'receipt_url' => ADMIN_URL . 'onlinepayment/receipt/' . (int) $id,
			));
		}
		$this->session->set_flashdata('msg_succ', isset($result['message']) ? $result['message'] : 'Checked.');
		redirect($this->manage_redirect);
	}

	/** Cancel a live attempt so the customer's code stops working. */
	public function cancel($id = 0) {
		$this->_require_access();

		$attempt = $this->my_model->get_attempt($id);
		if (!empty($attempt) && (string) $attempt['status'] === 'pending') {
			$this->my_model->update_attempt($id, array('status' => 'cancelled'));
			$this->session->set_flashdata('msg_succ', 'Payment ' . $attempt['reference_no'] . ' cancelled.');
		} else {
			$this->session->set_flashdata('msg_succ', 'Only a pending payment can be cancelled.');
		}
		redirect($this->manage_redirect);
	}

	/** Re-issue the link and email it again. */
	public function resend($id = 0) {
		$this->_require_access();

		$attempt = $this->my_model->get_attempt($id);
		if (empty($attempt)) {
			$this->session->set_flashdata('msg_succ', 'Payment not found.');
			redirect($this->manage_redirect);
		}
		if (in_array((string) $attempt['status'], array('paid', 'cancelled'), true)) {
			$this->session->set_flashdata('msg_succ', 'A ' . $attempt['status'] . ' payment cannot be re-sent.');
			redirect($this->manage_redirect);
		}

		$link = $this->_issue_link($attempt);
		$email = trim((string) $this->input->post('email'));
		if ($email === '') {
			$customer = $this->my_model->get_customer_info($attempt['customer_id']);
			$email = isset($customer['email_id']) ? trim((string) $customer['email_id']) : '';
		}
		if ($email === '') {
			$this->session->set_flashdata('msg_succ', 'This customer has no email address. Copy the link and send it manually.');
			redirect($this->manage_redirect);
		}

		$sent = $this->_send_link_email($attempt, $link, $email);
		$this->session->set_flashdata('msg_succ', $sent
			? 'Payment link emailed to ' . $email . '.'
			: 'The email could not be sent. Copy the link and send it manually.');
		redirect($this->manage_redirect);
	}

	/** The public link for an attempt (JSON), for the copy button. */
	public function link($id = 0) {
		$this->_require_access();

		$attempt = $this->my_model->get_attempt($id);
		if (empty($attempt)) {
			$this->json(array('ok' => false, 'message' => 'Payment not found.'));
		}
		$this->json(array('ok' => true, 'link' => $this->_issue_link($attempt)));
	}

	// ---------------------------------------------------------------------
	// Helpers
	// ---------------------------------------------------------------------

	/** Selected rows posted as JSON [{month,year}] or as month|year strings. */
	private function _selected_rows_from_post() {
		$raw = $this->input->post('rows');
		$out = array();

		if (is_string($raw) && $raw !== '') {
			$decoded = json_decode($raw, true);
			if (is_array($decoded)) {
				foreach ($decoded as $item) {
					if (is_array($item) && isset($item['month'], $item['year'])) {
						$out[trim((string) $item['month']) . '|' . trim((string) $item['year'])] = true;
					} elseif (is_string($item) && $item !== '') {
						$out[trim($item)] = true;
					}
				}
			}
		} elseif (is_array($raw)) {
			foreach ($raw as $item) {
				if (is_array($item) && isset($item['month'], $item['year'])) {
					$out[trim((string) $item['month']) . '|' . trim((string) $item['year'])] = true;
				} elseif (is_string($item) && $item !== '') {
					$out[trim($item)] = true;
				}
			}
		}
		return $out;
	}

	/** True when the gateway is switched on with a key saved. */
	private function _gateway_configured() {
		$this->load->library('Paymongo');
		return $this->paymongo->is_configured();
	}

	/** Issue (or re-issue) the signed public link for an attempt. */
	private function _issue_link($attempt) {
		$minutes = 60;
		$this->load->library('Paymongo');
		$settings = $this->paymongo->settings();
		if (isset($settings['link_expiry_minutes'])) {
			$minutes = max(5, (int) $settings['link_expiry_minutes']);
		}
		$token = $this->my_model->issue_link_token(
			isset($attempt['id']) ? (int) $attempt['id'] : 0,
			isset($attempt['reference_no']) ? $attempt['reference_no'] : '',
			time() + ($minutes * 60)
		);
		return rtrim(base_url(), '/') . '/master/paymentportal/index/' . rawurlencode($token);
	}

	/** Email the customer their payment link. */
	private function _send_link_email($attempt, $link, $email) {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		}
		$customer = $this->my_model->get_customer_info($attempt['customer_id']);
		$name = $this->my_model->full_name($customer);
		$amount = number_format((float) $attempt['amount'], 2);
		$reference = htmlspecialchars((string) $attempt['reference_no'], ENT_QUOTES, 'UTF-8');
		$link_h = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
		$expires = !empty($attempt['expires_at']) ? $attempt['expires_at'] : '';

		$body = ''
			. '<p>Good day' . ($name !== '' ? ', <strong>' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</strong>' : '') . '.</p>'
			. '<p>You can now pay your water bill online by scanning a QR Ph code with any bank or e-wallet app.</p>'
			. '<table cellpadding="6" cellspacing="0" style="border-collapse:collapse;">'
			. '<tr><td><strong>Reference</strong></td><td>' . $reference . '</td></tr>'
			. '<tr><td><strong>Amount due</strong></td><td>&#8369; ' . $amount . '</td></tr>'
			. ($expires !== '' ? '<tr><td><strong>Valid until</strong></td><td>' . htmlspecialchars($expires, ENT_QUOTES, 'UTF-8') . '</td></tr>' : '')
			. '</table>'
			. '<p><a href="' . $link_h . '" style="display:inline-block;padding:10px 18px;background:#3276b1;color:#fff;text-decoration:none;border-radius:4px;">Open my QR code and pay</a></p>'
			. '<p>Or copy this link into your browser:<br>' . $link_h . '</p>'
			. '<p>If you did not request this, you can ignore this email.</p>';

		$subject = 'Pay your water bill online — ' . $reference;
		$sent = $this->mail_model->mail_send_inline($email, $body, $subject);

		if ($sent) {
			$this->my_model->update_attempt($attempt['id'], array(
				'emailed_to' => $email,
				'emailed_at' => date('Y-m-d H:i:s'),
				'email_count' => (int) (isset($attempt['email_count']) ? $attempt['email_count'] : 0) + 1,
			));
		}
		return (bool) $sent;
	}
}
