<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class statementofaccount extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	public $publicHeaderPage = '../../views/admin-includes/public_header'; 
	public $viewPage = 'statementofaccount_soa';     //*****  View page   *****//
	public $listPage_redirect = '/master/statementofaccount';		  //*****  Redirect View  *****//
	
	public function __construct() {
        parent::__construct();
  		$this->load->model('statementofaccount_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 	
		ini_set('date.timezone', 'Asia/Manila');				
		$this->load->model('adminheader_model','top_model');
		$this->load->model('addcustomer_model','customer_model');
    }
	
	public $appHeaderPage = '../../views/admin-includes/customer_app_header';
	public $appFooterPage = '../../views/admin-includes/customer_app_footer';

	/** Staff signed in to the admin / mobile app (same test as mobile_payment). */
	private function _is_staff() {
		return ($this->session->userdata('logged_in') == 'ECOM')
			&& (trim((string) $this->session->userdata('username')) !== '');
	}

	/** Gate for the customer app: the signed-in customer, or any logged-in staff user. */
	private function _portal_customer_or_deny($customer_id) {
		$customer_id = trim(rawurldecode((string) $customer_id));
		$signed_in = $this->_pay_session_customer();
		if ($customer_id !== '') {
			if ($signed_in !== '' && strcasecmp($customer_id, $signed_in) === 0) {
				return $signed_in;
			}
			if ($this->_is_staff()) {
				return $customer_id;
			}
		}
		if ($this->input->is_ajax_request()) {
			$this->_pay_json(array('ok' => false, 'success' => false, 'message' => 'Your session has ended. Please sign in again.'), 403);
		}
		redirect('/master/statementofaccount/search');
		exit;
	}

	/** Shell variables for customer_app_header / customer_app_footer. */
	private function _app_shell($customer_id, $active, $title, $extra = array()) {
		$info = $this->my_model->get_customer_info($customer_id);
		$name = '';
		if (!empty($info)) {
			$name = strtoupper(trim($info['last_name'] . ', ' . $info['first_name'] . ' ' . $info['middle_name']));
		}
		$is_staff = $this->_is_staff() && strcasecmp($this->_pay_session_customer(), $customer_id) !== 0;
		return array_merge(array(
			'page_title' => $title . ' - Water Billing System',
			'app_active' => $active,
			'app_title' => $title,
			'app_customer_id' => $customer_id,
			'app_customer_name' => $name,
			'app_is_staff' => $is_staff,
		), $extra);
	}

	/** Customer dashboard (home of the customer app). */
	public function index($customer_id=''){
		if (trim((string) $customer_id) === '') {
			$signed_in = $this->_pay_session_customer();
			redirect($signed_in !== '' ? '/master/statementofaccount/index/'.rawurlencode($signed_in) : '/master/statementofaccount/search');
		}
		$customer_id = $this->_portal_customer_or_deny($customer_id);

		$customer_info = $this->my_model->get_customer_info($customer_id);
		if(empty($customer_info)){
			$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Customer not found!</div>');
			redirect('/master/statementofaccount/search');
		}

		$data = array(
			'customer_info' => $customer_info,
			'dashboard' => $this->_dashboard_payload($customer_id),
		);
		$shell = $this->_app_shell($customer_id, 'dashboard', 'Dashboard', array('app_subtitle' => 'Account overview'));
		$this->load->view($this->appHeaderPage, $shell);
		$this->load->view('statementofaccount_dashboard', $data);
		$this->load->view($this->appFooterPage, $shell);
	}

	/** Statement of Account ledger (formerly the index page). */
	public function soa($customer_id=''){
		if (trim((string) $customer_id) === '') {
			$signed_in = $this->_pay_session_customer();
			redirect($signed_in !== '' ? '/master/statementofaccount/soa/'.rawurlencode($signed_in) : '/master/statementofaccount/search');
		}
		$customer_id = $this->_portal_customer_or_deny($customer_id);

		// Get customer information
		$data['customer_info'] = $this->my_model->get_customer_info($customer_id);

		if(empty($data['customer_info'])){
			$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Customer not found!</div>');
			redirect('/master/statementofaccount/search');
		}

		// Get all ledger entries (readings and payments combined)
		$data['ledger_entries'] = $this->my_model->get_customer_ledger($customer_id);

		// Calculate running balance
		$data['ledger_entries'] = $this->my_model->calculate_running_balance($data['ledger_entries']);

		// Get current balance
		$data['current_balance'] = $this->my_model->get_current_balance($customer_id);

		$data['auto_print'] = (string) $this->input->get('print') === '1';

		$shell = $this->_app_shell($customer_id, 'soa', 'Statement of Account', array('app_subtitle' => 'Billing & payment ledger'));
		$this->load->view($this->appHeaderPage, $shell);
		$this->load->view('statementofaccount_soa', $data);
		$this->load->view($this->appFooterPage, $shell);
	}

	/** JSON: same payload the dashboard renders, for refresh. Read-only. */
	public function dashboard_data($customer_id=''){
		$customer_id = $this->_portal_customer_or_deny($customer_id);
		$info = $this->my_model->get_customer_info($customer_id);
		if (empty($info)) {
			$this->_pay_json(array('ok' => false, 'message' => 'Customer not found.'));
		}
		$this->_pay_json(array('ok' => true, 'data' => $this->_dashboard_payload($customer_id)));
	}

	/**
	 * Dashboard figures. Every amount comes from the existing models
	 * (statementofaccount_model for the ledger/balance, onlinepayment_model for
	 * what is payable now); this only groups and adds up what they returned.
	 */
	private function _dashboard_payload($customer_id) {
		$ledger = $this->my_model->get_customer_ledger($customer_id);
		$ledger = $this->my_model->calculate_running_balance($ledger);
		if (!is_array($ledger)) { $ledger = array(); }
		$balance = (float) $this->my_model->get_current_balance($customer_id);

		$total_debit = 0;
		$total_credit = 0;
		$billings = array();
		$payments = array();
		$monthly = array();
		foreach ($ledger as $e) {
			$debit = (float) $e['debit'];
			$credit = (float) $e['credit'];
			$total_debit += $debit;
			$total_credit += $credit;
			$ts = strtotime($e['date']);
			if ($ts !== false) {
				$mk = date('Y-m', $ts);
				if (!isset($monthly[$mk])) { $monthly[$mk] = array('charges' => 0, 'credits' => 0); }
				$monthly[$mk]['charges'] += $debit;
				$monthly[$mk]['credits'] += $credit;
			}
			if ($e['type'] === 'billing') {
				$raw = isset($e['raw_data']) && is_array($e['raw_data']) ? $e['raw_data'] : array();
				$billings[] = array(
					'period' => trim((isset($raw['month_name']) ? $raw['month_name'] : '') . ' ' . (isset($raw['year']) ? $raw['year'] : '')),
					'month' => isset($raw['month']) ? (int) $raw['month'] : 0,
					'year' => isset($raw['year']) ? (int) $raw['year'] : 0,
					'date' => $e['date'],
					'amount' => round($debit, 2),
					'consumed' => is_numeric($e['consumed']) ? (float) $e['consumed'] : 0,
					'due_date' => isset($e['due_date']) ? $e['due_date'] : '',
					'refno' => $e['refno'],
				);
			} elseif ($e['type'] === 'payment') {
				$payments[] = array(
					'date' => $e['date'],
					'refno' => $e['refno'],
					'amount' => round($credit, 2),
					'description' => $e['description'],
				);
			}
		}

		// Billing periods (ledger is newest first) -> chronological, last 12.
		usort($billings, function ($a, $b) {
			$ka = $a['year'] * 100 + $a['month'];
			$kb = $b['year'] * 100 + $b['month'];
			return $ka === $kb ? 0 : ($ka < $kb ? -1 : 1);
		});
		$consumption = array_slice($billings, -12);
		$latest_bill = !empty($billings) ? $billings[count($billings) - 1] : null;

		$recent6 = array_slice($billings, -6);
		$avg6 = 0;
		if (!empty($recent6)) {
			$sum = 0;
			foreach ($recent6 as $b) { $sum += $b['consumed']; }
			$avg6 = round($sum / count($recent6), 2);
		}
		$prev_bill = count($billings) >= 2 ? $billings[count($billings) - 2] : null;
		$same_last_year = null;
		if ($latest_bill) {
			foreach ($billings as $b) {
				if ($b['month'] === $latest_bill['month'] && $b['year'] === $latest_bill['year'] - 1) {
					$same_last_year = $b;
				}
			}
		}

		// Charges vs payments & credits per calendar month, last 12 months.
		$months = array();
		$start = strtotime(date('Y-m-01') . ' -11 months');
		for ($i = 0; $i < 12; $i++) {
			$mk = date('Y-m', strtotime('+' . $i . ' months', $start));
			$months[] = array(
				'key' => $mk,
				'label' => date('M y', strtotime($mk . '-01')),
				'charges' => isset($monthly[$mk]) ? round($monthly[$mk]['charges'], 2) : 0,
				'credits' => isset($monthly[$mk]) ? round($monthly[$mk]['credits'], 2) : 0,
			);
		}

		// Running balance trend, chronological, last 24 entries.
		$trend = array();
		foreach (array_reverse($ledger) as $e) {
			$trend[] = array('date' => $e['date'], 'balance' => round((float) $e['balance'], 2));
		}
		$trend = array_slice($trend, -24);

		// What can be paid online now, and which of it is past due (same rows as the Pay page).
		$online = $this->_online();
		$rows = $online->get_customer_bills($customer_id);
		$payable_total = 0;
		$payable_count = 0;
		$past_due = array();
		$today = strtotime(date('Y-m-d'));
		foreach ($rows as $row) {
			if (empty($row['is_payable'])) { continue; }
			$payable_total += (float) $row['amount_due'];
			$payable_count++;
			$due_ts = !empty($row['due_date']) ? strtotime($row['due_date']) : false;
			if ($due_ts !== false && $due_ts < $today) {
				$past_due[] = array(
					'period' => trim($row['month_name'] . ' ' . $row['year']),
					'due_date' => $row['due_date'],
					'days' => (int) floor(($today - $due_ts) / 86400),
					'amount_due' => round((float) $row['amount_due'], 2),
					'penalty' => round((float) $row['penalty'], 2),
					'consumed' => (float) $row['consumed'],
				);
			}
		}
		$past_due_total = 0;
		foreach ($past_due as $p) { $past_due_total += $p['amount_due']; }

		$last_payment = !empty($payments) ? $payments[0] : null;

		return array(
			'generated_at' => date('Y-m-d H:i:s'),
			'balance' => round($balance, 2),
			'is_settled' => $balance <= 0.009,
			'total_billed' => round($total_debit, 2),
			'total_paid' => round($total_credit, 2),
			'payable_total' => round($payable_total, 2),
			'payable_count' => $payable_count,
			'past_due' => $past_due,
			'past_due_total' => round($past_due_total, 2),
			'latest_bill' => $latest_bill,
			'prev_bill' => $prev_bill,
			'same_last_year' => $same_last_year,
			'avg_consumption_6' => $avg6,
			'last_payment' => $last_payment,
			'recent_payments' => array_slice($payments, 0, 5),
			'consumption' => $consumption,
			'months' => $months,
			'trend' => $trend,
			'entry_count' => count($ledger),
		);
	}

	/** Customer sign out. */
	public function logout() {
		$this->session->unset_userdata('soa_customer_id');
		$this->session->unset_userdata('soa_after_login');
		redirect('/master/statementofaccount/search');
	}
	
	/** Search Function - Customer ID Entry **/
	public function search(){ 
		$data['msg'] = '';
		if ($this->input->post('customer_id') == '' && $this->session->userdata('soa_after_login') === 'pay') {
			$data['msg'] = 'Please enter your Customer ID and password to continue to Online Pay.';
		}
		
		// Handle form submission
		if($this->input->post('customer_id') != '' || $this->input->post('password') != ''){
			$customer_id = $this->input->post('customer_id');
			$password = $this->input->post('password');
			
			// Validate inputs
			if(empty($customer_id)){
				$data['msg'] = '<div class="alert alert-danger">Please enter a Customer ID!</div>';
			} elseif(empty($password)){
				$data['msg'] = '<div class="alert alert-danger">Please enter your password!</div>';
			} else {
				// Get customer password from database
				$stored_password = $this->my_model->get_customer_password($customer_id);
				
				// Check if customer exists
				if($stored_password === false){
					$data['msg'] = '<div class="alert alert-danger">Customer not found!</div>';
				} else {
					// Check if password is set for this customer
					if(empty($stored_password)){
						$data['msg'] = '<div class="alert alert-danger">Password not set for this customer. Please contact administrator.</div>';
					} else {
						// Validate password - MD5 hash the input and compare with stored password
						$encrypted_password = md5($password);
						
						if($encrypted_password === $stored_password){
							$this->session->set_userdata('soa_customer_id', trim((string) $customer_id));
							if ($this->session->userdata('soa_after_login') === 'pay') {
								$this->session->unset_userdata('soa_after_login');
								redirect('/master/statementofaccount/pay/'.rawurlencode(trim((string) $customer_id)));
							}
							// Password matches - redirect to statement
							redirect('/master/statementofaccount/index/'.$customer_id);
						} else {
							// Password doesn't match
							$data['msg'] = '<div class="alert alert-danger">Invalid password! Please try again.</div>';
						}
					}
				}
			}
		}
		
		// Use public header (no authentication)
		$this->load->view($this->publicHeaderPage);
		$this->load->view('statementofaccount_search',$data);
	}
	
	/** Reset Customer Password **/
	public function reset_password() {
		// Clear any previous output
		if(ob_get_level()) {
			ob_clean();
		}
		
		// Set JSON header
		header('Content-Type: application/json');
		
		try {
			$customer_id = $this->input->post('customer_id');
			$password = $this->input->post('password');
			
			// Validate inputs
			if(empty($customer_id) || empty($password)) {
				echo json_encode(array('success' => false, 'message' => 'Customer ID and Password are required.'));
				exit;
			}

			// Only the signed-in customer (or staff) may change this customer's password.
			$signed_in = $this->_pay_session_customer();
			$is_owner = ($signed_in !== '' && strcasecmp(trim((string) $customer_id), $signed_in) === 0);
			if (!$is_owner && !$this->_is_staff()) {
				http_response_code(403);
				echo json_encode(array('success' => false, 'message' => 'Your session has ended. Please sign in again.'));
				exit;
			}
			
			// Validate password length
			if(strlen($password) < 3) {
				echo json_encode(array('success' => false, 'message' => 'Password must be at least 3 characters long.'));
				exit;
			}
			
			// Encrypt password using MD5 (same as login)
			$encrypted_password = md5($password);
			
			// Update password in database using customer_id
			$result = $this->my_model->update_customer_password_by_customer_id($customer_id, $encrypted_password);
			
			if($result !== false) {
				echo json_encode(array('success' => true, 'message' => 'Password reset successfully. Redirecting to login page...'));
			} else {
				echo json_encode(array('success' => false, 'message' => 'Failed to reset password. Please try again.'));
			}
			exit;
			
		} catch(Exception $e) {
			log_message('error', 'Reset Password Exception: ' . $e->getMessage());
			echo json_encode(array('success' => false, 'message' => 'An error occurred: ' . $e->getMessage()));
			exit;
		}
	}

	private function _soa_sign_cookie($key, $default) {
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

	/** Convert SOA to PDF (TCPDF) **/
	public function pdf($customer_id='') {
		$customer_id = $this->_portal_customer_or_deny($customer_id);

		$customer_info = $this->my_model->get_customer_info($customer_id);
		if (empty($customer_info)) {
			$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Customer not found!</div>');
			redirect('/master/statementofaccount/search');
		}

		@set_time_limit(180);
		@ini_set('memory_limit', '256M');

		$ledger_entries = $this->my_model->get_customer_ledger($customer_id);
		$ledger_entries = $this->my_model->calculate_running_balance($ledger_entries);
		$current_balance = $this->my_model->get_current_balance($customer_id);

		$data = array(
			'customer_info' => $customer_info,
			'ledger_entries' => $ledger_entries,
			'current_balance' => $current_balance,
			'printed_at' => date('Y-m-d H:i:s'),
			'prepared_name' => $this->_soa_sign_cookie('soa_sign_prepared_name', 'MISHELLE P. MONDARTE'),
			'prepared_title' => $this->_soa_sign_cookie('soa_sign_prepared_title', 'Industrial Relations Management Officer C / Billing Officer'),
			'verified_name' => $this->_soa_sign_cookie('soa_sign_verified_name', 'DARYL JAY T. VILLARIN'),
			'verified_title' => $this->_soa_sign_cookie('soa_sign_verified_title', 'Administrative/General Services Officer B / HRMO/FO/BO'),
			'approved_name' => $this->_soa_sign_cookie('soa_sign_approved_name', 'ENGR. ANASTACIA T. ROMANILLOS, CE'),
			'approved_title' => $this->_soa_sign_cookie('soa_sign_approved_title', 'General Manager')
		);

		$html = $this->load->view('statementofaccount_pdf', $data, true);

		while (ob_get_level()) {
			@ob_end_clean();
		}

		$this->load->library('Pdf');
		$pdf = new Pdf('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle('Statement of Account - '.$customer_id);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont('courier');
		$pdf->SetMargins(10, 10, 10);
		$pdf->SetAutoPageBreak(true, 12);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setFontSubsetting(false);
		$pdf->SetFont('helvetica', '', 8, '', true);
		$pdf->AddPage();
		$pdf->writeHTML($html, true, false, true, false, '');

		$safe_id = preg_replace('/[^A-Za-z0-9._-]/', '_', $customer_id);
		$pdf->Output('SOA-'.$safe_id.'.pdf', 'I');
		exit;
	}

	// ---------------------------------------------------------------------
	// Online Pay (customer self-service QR Ph)
	//
	// Only the customer who signed in on the search page (soa_customer_id in
	// the session) may pay. Amounts always come from onlinepayment_model, the
	// same computation as the mobile/desktop payment screens.
	// ---------------------------------------------------------------------

	/** tbl_online_payments.context for payments started from this page. */
	const PAY_CONTEXT = 'customer_soa';

	/**
	 * Staff id the settlement is attributed to. settle() only switches the
	 * session user when created_by is set, and tbl_addmetercustomer.userid is
	 * NOT NULL, so a webhook settling without a session needs a real id.
	 * id 1 is the main admin account (master_model::get_admin_username_pwd_check).
	 */
	const PAY_CREATED_BY = 1;

	/** Seconds between PayMongo re-checks from the browser poller. */
	const PAY_POLL_THROTTLE = 5;

	private function _online() {
		$this->load->model('onlinepayment_model', 'online_model');
		$this->load->model('addpaymentcustomer_model', 'pay_model');
		return $this->online_model;
	}

	private function _pay_session_customer() {
		return trim((string) $this->session->userdata('soa_customer_id'));
	}

	/** Gate: the requested customer must be the one signed in on the search page. */
	private function _pay_customer_or_deny($customer_id) {
		$customer_id = trim(rawurldecode((string) $customer_id));
		$signed_in = $this->_pay_session_customer();
		if ($customer_id !== '' && $signed_in !== '' && strcasecmp($customer_id, $signed_in) === 0) {
			return $signed_in;
		}
		if ($this->input->is_ajax_request()) {
			$this->_pay_json(array('ok' => false, 'paid' => false, 'message' => 'Your session has ended. Please sign in again.'), 403);
		}
		$this->session->set_userdata('soa_after_login', 'pay');
		redirect('/master/statementofaccount/search');
		exit;
	}

	/** Attempt that belongs to the signed-in customer, or deny. */
	private function _pay_attempt_or_deny($attempt_id) {
		$signed_in = $this->_pay_customer_or_deny($this->_pay_session_customer());
		$attempt = $this->_online()->get_attempt((int) $attempt_id);
		if (empty($attempt) || strcasecmp(trim((string) $attempt['customer_id']), $signed_in) !== 0) {
			if ($this->input->is_ajax_request()) {
				$this->_pay_json(array('ok' => false, 'paid' => false, 'message' => 'Payment not found.'), 403);
			}
			redirect('/master/statementofaccount/pay/'.rawurlencode($signed_in));
			exit;
		}
		return $attempt;
	}

	private function _pay_json($payload, $code = 200) {
		while (ob_get_level()) {
			@ob_end_clean();
		}
		http_response_code($code);
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		echo json_encode($payload);
		exit;
	}

	private function _pay_gateway_configured() {
		$this->load->library('Paymongo');
		return $this->paymongo->is_configured();
	}

	/** Pay page: summary + billing periods + QR overlay (?attempt=N). */
	public function pay($customer_id = '') {
		$customer_id = $this->_pay_customer_or_deny($customer_id);
		$online = $this->_online();
		$online->expire_lapsed_attempts();

		$data = array(
			'customer_id' => $customer_id,
			'ready' => $online->table_ready(),
			'gateway_configured' => $this->_pay_gateway_configured(),
			'qr_attempt' => array(),
			'qr_rows' => array(),
		);

		$attempt_id = (int) $this->input->get('attempt');
		if ($attempt_id > 0) {
			$attempt = $online->get_attempt($attempt_id);
			if (!empty($attempt) && strcasecmp(trim((string) $attempt['customer_id']), $customer_id) === 0) {
				$data['qr_attempt'] = $attempt;
				$rows = json_decode(isset($attempt['bill_rows_json']) ? (string) $attempt['bill_rows_json'] : '', true);
				$data['qr_rows'] = is_array($rows) ? $rows : array();
			}
		}

		$shell = $this->_app_shell($customer_id, 'pay', 'Online Pay', array(
			'app_subtitle' => 'Pay your bill with QR Ph',
			'app_brand_title' => 'Online Payment',
			'app_shell_class' => 'has-pay-bar',
		));
		$this->load->view($this->appHeaderPage, $shell);
		$this->load->view('statementofaccount_pay', $data);
		$this->load->view($this->appFooterPage, $shell);
	}

	/** JSON: customer header + billing periods with amounts due. */
	public function pay_bills($customer_id = '') {
		$customer_id = $this->_pay_customer_or_deny($customer_id);
		$online = $this->_online();

		$customer = $online->get_customer_info($customer_id);
		if (empty($customer)) {
			$this->_pay_json(array('ok' => false, 'message' => 'Customer not found.'));
		}

		$rows = $online->get_customer_bills($customer_id);
		$total_due = 0;
		$latest = null;
		foreach ($rows as $row) {
			if (!empty($row['is_payable'])) {
				$total_due += (float) $row['amount_due'];
			}
			if ($latest === null && !empty($row['has_reading'])) {
				$latest = $row;
			}
		}

		$this->_pay_json(array(
			'ok' => true,
			'customer' => array(
				'customer_id' => $customer_id,
				'name' => $online->full_name($customer),
				'meter_number' => isset($customer['meter_number']) ? $customer['meter_number'] : '',
				'address' => trim((string) (isset($customer['address']) ? $customer['address'] : '') . ' ' . (isset($customer['city']) ? $customer['city'] : '')),
				'zone' => isset($customer['zones']) ? $customer['zones'] : '',
				'latest_period' => $latest ? trim($latest['month_name'] . ' ' . $latest['year']) : '',
				'latest_due_date' => ($latest && !empty($latest['due_date'])) ? $latest['due_date'] : '',
			),
			'rows' => $rows,
			'total_due' => round($total_due, 2),
		));
	}

	/** JSON (POST): create the QR for the ticked periods. */
	public function pay_create_qr() {
		$customer_id = $this->_pay_customer_or_deny($this->_pay_session_customer());
		$online = $this->_online();

		$raw = $this->input->post('rows');
		$selected = array();
		$decoded = is_string($raw) ? json_decode($raw, true) : $raw;
		if (is_array($decoded)) {
			foreach ($decoded as $item) {
				if (is_array($item) && isset($item['month'], $item['year'])) {
					$selected[trim((string) $item['month']) . '|' . trim((string) $item['year'])] = true;
				}
			}
		}
		if (empty($selected)) {
			$this->_pay_json(array('ok' => false, 'message' => 'Tick at least one billing period.'));
		}

		// Re-read server-side; the browser never dictates an amount.
		$rows = array();
		foreach ($online->get_customer_bills($customer_id) as $row) {
			if (isset($selected[$row['month'] . '|' . $row['year']])) {
				$rows[] = $row;
			}
		}
		if (empty($rows)) {
			$this->_pay_json(array('ok' => false, 'message' => 'None of the ticked periods can be paid. Reload the page and try again.'));
		}

		if (!$this->_pay_gateway_configured()) {
			$this->_pay_json(array('ok' => false, 'message' => 'Online payment is not available right now. Please pay at the water district office.'));
		}

		$amount = $online->total_of_rows($rows);
		$min = $this->paymongo->min_amount_pesos();
		if ($amount < $min) {
			$this->_pay_json(array('ok' => false, 'message' => 'The amount selected (PHP ' . number_format($amount, 2) . ') is below the online payment minimum of PHP ' . number_format($min, 2) . '.'));
		}

		// Gateway error text is for administrators (Online Payment list), not customers.
		$result = $online->start_qrph($customer_id, $rows, self::PAY_CONTEXT, self::PAY_CREATED_BY);
		if (empty($result['ok'])) {
			$this->_pay_json(array('ok' => false, 'message' => 'We could not create the QR code right now. Please try again in a few minutes or pay at the water district office.'));
		}

		$attempt = $result['attempt'];
		$this->_pay_json(array(
			'ok' => true,
			'reference_no' => isset($attempt['reference_no']) ? $attempt['reference_no'] : '',
			'show_url' => base_url() . 'master/statementofaccount/pay/' . rawurlencode($customer_id) . '?attempt=' . (int) $attempt['id'],
		));
	}

	/** JSON poll: settles the payment when PayMongo says it is paid. */
	public function pay_status($id = 0) {
		$attempt = $this->_pay_attempt_or_deny($id);
		$online = $this->online_model;

		$status = (string) $attempt['status'];
		$message = '';
		$key = 'soa_pay_check_' . (int) $attempt['id'];
		$last = (int) $this->session->userdata($key);
		if ($status === 'pending' && (time() - $last) >= self::PAY_POLL_THROTTLE) {
			$this->session->set_userdata($key, time());
			$result = $online->refresh_attempt((int) $attempt['id'], 'poll');
			if (!empty($result['attempt'])) {
				$attempt = $result['attempt'];
			}
			$status = isset($attempt['status']) ? (string) $attempt['status'] : $status;
			$message = !empty($result['ok']) ? 'Waiting for your payment…' : 'Still checking with the bank. Please keep this page open.';
		}

		$paid = ($status === 'paid');
		$this->_pay_json(array(
			'ok' => true,
			'paid' => $paid,
			'status' => $status,
			'status_label' => $online->status_label($status),
			'message' => $paid ? 'Payment received. Thank you!' : $message,
			'success_url' => $paid ? (base_url() . 'master/statementofaccount/pay_success/' . (int) $attempt['id']) : '',
		));
	}

	/** Thank-you page after the money lands. */
	public function pay_success($id = 0) {
		$attempt = $this->_pay_attempt_or_deny($id);
		$rows = json_decode(isset($attempt['bill_rows_json']) ? (string) $attempt['bill_rows_json'] : '', true);

		$shell = $this->_app_shell(trim((string) $attempt['customer_id']), 'pay', 'Payment Received', array(
			'app_subtitle' => 'Online payment confirmation',
		));
		$this->load->view($this->appHeaderPage, $shell);
		$this->load->view('statementofaccount_pay_success', array(
			'attempt' => $attempt,
			'customer' => $this->online_model->get_customer_info($attempt['customer_id']),
			'rows' => is_array($rows) ? $rows : array(),
		));
		$this->load->view($this->appFooterPage, $shell);
	}

	/** 80mm receipt for the customer. */
	public function pay_receipt($id = 0) {
		$attempt = $this->_pay_attempt_or_deny($id);
		$rows = json_decode(isset($attempt['bill_rows_json']) ? (string) $attempt['bill_rows_json'] : '', true);

		$this->load->view('partials/receipt80', array(
			'attempt' => $attempt,
			'customer' => $this->online_model->get_customer_info($attempt['customer_id']),
			'rows' => is_array($rows) ? $rows : array(),
			'address' => $this->pay_model->get_address(),
			'autoprint' => (string) $this->input->get('preview') !== '1',
		));
	}

}
?>

