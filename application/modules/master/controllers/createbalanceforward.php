<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class createbalanceforward extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_billing_period';	  //*****  Table name  *****//
	public $listPage = 'createbalanceforward';		   //*****  View page   *****//
	public $createbalanceforward_search_ajax = 'createbalanceforward_ajax';

	public $listPage_redirect = '/master/createbalanceforward';		  //*****  Redirect View  *****//
	
	public function __construct() {
        parent::__construct();
		$this->load->library('session');
  		$this->load->model('createbalanceforward_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->load->helper('common');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }

	public function index(){ 		 
		if($this->session->userdata('usertype') == 'subadmin'){
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		}else{
			$this->head['roleResponsible'] = array();
		}
		if(	array_key_exists('createbalanceforward',$this->head['roleResponsible']) && $this->session->userdata('usertype') == 'subadmin' ){
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['createbalanceforward']);
		}
		//*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->my_model->get_zone_records();
		$data['billingperiod'] = $this->my_model->get_month_billingperiod_records();	
		$data['zone_listing'] = $this->my_model->get_zone_listing_records();	
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}

	public function processbalanceforward(){
		// Initialize batch processing
		header('Content-Type: application/json');
		
		$billperiodforward_str = $this->input->post('billingperiodforward');
		$currentbillingperiod_str = $this->input->post('currentbillingperiod');
		$zone_listing = $this->input->post('zone_listing');
		
		$billperiodforward = $billperiodforward_str ? explode(' ', trim($billperiodforward_str), 2) : array();
		$currentbillingperiod = $currentbillingperiod_str ? explode(' ', trim($currentbillingperiod_str), 2) : array();
		
		if (count($billperiodforward) < 2 || count($currentbillingperiod) < 2 || $zone_listing === null || $zone_listing === '') {
			echo json_encode(array('success' => false, 'message' => 'Invalid or missing billing period or zone. Please select Current Billing Period, Next Billing Period, and Zone.'));
			return;
		}
		
		$billperiodforward_month = $billperiodforward[0];
		$billperiodforward_year = $billperiodforward[1];
		$currentbillingperiod_month = $currentbillingperiod[0];
		$currentbillingperiod_year = $currentbillingperiod[1];
		
		// Get total count
		$total = $this->my_model->get_total_customers_count($zone_listing);
		
		// Store processing parameters in session
		$process_data = array(
			'bp_month' => $billperiodforward_month,
			'bp_year' => $billperiodforward_year,
			'bp_current_month' => $currentbillingperiod_month,
			'bp_current_year' => $currentbillingperiod_year,
			'zone_id' => $zone_listing,
			'total' => $total,
			'processed' => 0,
			'current_customer' => ''
		);
		$this->session->set_userdata('balanceforward_batch', $process_data);
		
		$data['success'] = true;
		$data['message'] = 'Batch processing initialized';
		$data['total'] = $total;
		$data['batch_size'] = 50;
		$data['processed'] = 0;
		
		echo json_encode($data);
	}
	
	public function processbatch(){
		header('Content-Type: application/json');
		
		@ini_set('memory_limit', '512M');
		@set_time_limit(60); // 60 seconds per batch
		
		$batch_data = $this->session->userdata('balanceforward_batch');
		
		if(!$batch_data){
			echo json_encode(array('success' => false, 'message' => 'Batch processing not initialized'));
			return;
		}
		
		$offset = isset($batch_data['processed']) ? $batch_data['processed'] : 0;
		$batch_size = 50; // Process 50 customers per batch
		
		// Get batch of customers
		$customers = $this->my_model->get_customers_batch($batch_data['zone_id'], $offset, $batch_size);
		
		$processed_in_batch = 0;
		$current_customer_name = '';
		
		foreach($customers as $customer){
			try {
				$result = $this->my_model->process_single_customer(
					$customer,
					$batch_data['bp_month'],
					$batch_data['bp_year'],
					$batch_data['bp_current_month'],
					$batch_data['bp_current_year']
				);
				
				if($result){
					$processed_in_batch++;
					$current_customer_name = $customer->first_name . ' ' . $customer->last_name . ' (' . $customer->customer_id . ')';
				}
			} catch (Exception $e) {
				// Continue with next customer on error
				continue;
			}
		}
		
		// Update session
		$batch_data['processed'] = $offset + count($customers);
		$batch_data['current_customer'] = $current_customer_name;
		$this->session->set_userdata('balanceforward_batch', $batch_data);
		
		$percentage = $batch_data['total'] > 0 ? round(($batch_data['processed'] / $batch_data['total']) * 100) : 0;
		$is_complete = $batch_data['processed'] >= $batch_data['total'];
		
		$response = array(
			'success' => true,
			'processed' => $batch_data['processed'],
			'total' => $batch_data['total'],
			'percentage' => $percentage,
			'current_customer' => $current_customer_name,
			'complete' => $is_complete,
			'message' => $is_complete ? 'Processing completed!' : 'Processing batch...'
		);
		
		echo json_encode($response);
	}
	
	public function clearbatch(){
		$this->session->unset_userdata('balanceforward_batch');
		header('Content-Type: application/json');
		echo json_encode(array('success' => true));
	}

	public function getbalanceforwardresults(){
		$data['msg'] = '';
		
		$billperiodforward = explode(' ',$this->input->post('billingperiodforward'));
		$zone_listing = $this->input->post('zone_listing');
		$billperiodforward_month = $billperiodforward[0];
		$billperiodforward_year = $billperiodforward[1];
		
		$data['record'] = $this->my_model->get_balanceforward_results($billperiodforward_month, $billperiodforward_year, $zone_listing);
		$data['statistics'] = $this->my_model->get_member_statistics($billperiodforward_month, $billperiodforward_year, $zone_listing);
		$this->load->view($this->createbalanceforward_search_ajax,$data);
	}
	
}
?>

