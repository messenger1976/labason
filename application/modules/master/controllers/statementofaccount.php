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
	
}
?>

