<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Public payment portal — the page an emailed payment link opens.
 *
 * No login: the caller is a customer, identified only by the signed token in the
 * URL (see onlinepayment_model::issue_link_token / verify_link_token). The token
 * is bound to one attempt and expires, so a guessed customer id cannot open
 * somebody else's bill.
 *
 * Mirrors controllers/statementofaccount.php, which is the app's existing public
 * page pattern: public header, no session, no staff permissions.
 */
class paymentportal extends CI_Controller {

	public $publicHeaderPage = '../../views/admin-includes/public_header';
	public $viewPage = 'paymentportal';
	public $donePage = 'paymentportal_done';
	public $receiptPage = 'partials/receipt80';

	/** Seconds between PayMongo re-checks from the browser poller. */
	const POLL_THROTTLE = 5;

	public function __construct() {
		parent::__construct();
		$this->load->model('onlinepayment_model', 'my_model');
		$this->load->model('addcustomer_model', 'customer_model');
		$this->load->model('addpaymentcustomer_model', 'pay_model');
		ini_set('date.timezone', 'Asia/Manila');
		error_reporting(0);
		ini_set('display_errors', 'off');
	}

	/** Amount + QR + live status. */
	public function index($token = '') {
		$attempt = $this->my_model->verify_link_token($token);
		if (empty($attempt)) {
			$this->_render_error('This payment link is not valid any more. Please ask the water district office for a new one.');
			return;
		}

		// A token that is still valid but whose QR lapsed should say so plainly.
		if ((string) $attempt['status'] === 'pending' && !empty($attempt['expires_at']) && $attempt['expires_at'] < date('Y-m-d H:i:s')) {
			$this->my_model->update_attempt($attempt['id'], array('status' => 'expired'));
			$attempt['status'] = 'expired';
		}

		if ((string) $attempt['status'] === 'paid') {
			redirect('master/paymentportal/done/' . rawurlencode($token));
			return;
		}

		$customer = $this->my_model->get_customer_info($attempt['customer_id']);
		$rows = json_decode(isset($attempt['bill_rows_json']) ? (string) $attempt['bill_rows_json'] : '', true);

		$data = array(
			'token' => $token,
			'attempt' => $attempt,
			'customer_name' => $this->my_model->full_name($customer),
			'customer_id' => $attempt['customer_id'],
			'rows' => is_array($rows) ? $rows : array(),
			'address' => $this->pay_model->get_address(),
			'status_label' => $this->my_model->status_label($attempt['status']),
			'error' => '',
		);

		$this->load->view($this->publicHeaderPage, array('page_title' => 'Online Payment (QR Ph) - Water Billing System'));
		$this->load->view($this->viewPage, $data);
	}

	/**
	 * Poll endpoint used by the page. Re-checks PayMongo and settles when paid,
	 * so a customer who has just scanned does not have to wait for a webhook
	 * (which cannot reach a local XAMPP install at all).
	 */
	public function status($token = '') {
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');

		$attempt = $this->my_model->verify_link_token($token);
		if (empty($attempt)) {
			http_response_code(403);
			echo json_encode(array('ok' => false, 'paid' => false, 'message' => 'Invalid or expired link.'));
			exit;
		}

		$status = (string) $attempt['status'];
		$message = '';

		// Throttle the gateway calls: the page polls, and each poll would
		// otherwise cost a PayMongo request.
		if ($status === 'pending' && $this->_may_check($attempt['reference_no'])) {
			$result = $this->my_model->refresh_attempt($attempt['id'], 'poll');
			$attempt = isset($result['attempt']) && !empty($result['attempt']) ? $result['attempt'] : $attempt;
			$status = isset($attempt['status']) ? (string) $attempt['status'] : $status;
			$message = isset($result['message']) ? $result['message'] : '';
			$this->_mark_checked($attempt['reference_no']);
		}

		$paid = ($status === 'paid');
		echo json_encode(array(
			'ok' => true,
			'paid' => $paid,
			'status' => $status,
			'status_label' => $this->my_model->status_label($status),
			'amount' => isset($attempt['amount']) ? (float) $attempt['amount'] : 0,
			'fee_amount' => Onlinepayment_model::fee_of($attempt),
			'charged_amount' => Onlinepayment_model::charged_of($attempt),
			'message' => $paid ? 'Payment received. Thank you!' : $message,
			'redirect' => $paid ? rtrim(base_url(), '/') . '/master/paymentportal/done/' . rawurlencode($token) : '',
		));
		exit;
	}

	/** Success page with the print option. */
	public function done($token = '') {
		$attempt = $this->my_model->verify_link_token($token);
		if (empty($attempt)) {
			$this->_render_error('This payment link is not valid any more.');
			return;
		}
		if ((string) $attempt['status'] !== 'paid') {
			redirect('master/paymentportal/index/' . rawurlencode($token));
			return;
		}

		$customer = $this->my_model->get_customer_info($attempt['customer_id']);
		$rows = json_decode(isset($attempt['bill_rows_json']) ? (string) $attempt['bill_rows_json'] : '', true);

		$data = array(
			'token' => $token,
			'attempt' => $attempt,
			'customer_name' => $this->my_model->full_name($customer),
			'rows' => is_array($rows) ? $rows : array(),
			'address' => $this->pay_model->get_address(),
		);

		$this->load->view($this->publicHeaderPage, array('page_title' => 'Payment Received - Water Billing System'));
		$this->load->view($this->donePage, $data);
	}

	/** 80mm receipt for the customer. */
	public function receipt($token = '') {
		$attempt = $this->my_model->verify_link_token($token);
		if (empty($attempt)) {
			$this->_render_error('This payment link is not valid any more.');
			return;
		}

		$customer = $this->my_model->get_customer_info($attempt['customer_id']);
		$rows = json_decode(isset($attempt['bill_rows_json']) ? (string) $attempt['bill_rows_json'] : '', true);

		$data = array(
			'attempt' => $attempt,
			'customer' => $customer,
			'rows' => is_array($rows) ? $rows : array(),
			'address' => $this->pay_model->get_address(),
			'autoprint' => (string) $this->input->get('preview') !== '1',
		);
		$this->load->view($this->receiptPage, $data);
	}

	// ---------------------------------------------------------------------

	/** Remember when we last asked PayMongo about an attempt. */
	private function _may_check($reference_no) {
		$last = $this->session->userdata('pp_last_check_' . $reference_no);
		if ($last === null || $last === false) {
			return true;
		}
		return (time() - (int) $last) >= self::POLL_THROTTLE;
	}

	private function _mark_checked($reference_no) {
		$this->session->set_userdata('pp_last_check_' . $reference_no, time());
	}

	/** One shared error screen, in the public layout. */
	private function _render_error($message) {
		$this->load->view($this->publicHeaderPage, array('page_title' => 'Online Payment (QR Ph) - Water Billing System'));
		$this->load->view($this->viewPage, array(
			'token' => '',
			'attempt' => array(),
			'customer_name' => '',
			'customer_id' => '',
			'rows' => array(),
			'address' => $this->pay_model->get_address(),
			'status_label' => '',
			'error' => $message,
		));
	}
}
