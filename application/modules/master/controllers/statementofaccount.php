<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class statementofaccount extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	public $publicHeaderPage = '../../views/admin-includes/public_header'; 
	public $viewPage = 'statementofaccount';     //*****  View page   *****//
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
	
	public function index($customer_id=''){ 		 //*****  View Loading  *****//
		// Use public header - no authentication required
		
		// Require customer ID - redirect to search if not provided
		if($customer_id == ''){
			redirect('/master/statementofaccount/search');
		}
		
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
		
		// Use public header (no authentication)
		$this->load->view($this->publicHeaderPage);
		$this->load->view($this->viewPage,$data);
	}
	
	/** Search Function - Customer ID Entry **/
	public function search(){ 
		$data['msg'] = '';
		
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
		if ($customer_id == '') {
			redirect('/master/statementofaccount/search');
		}

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

}
?>

