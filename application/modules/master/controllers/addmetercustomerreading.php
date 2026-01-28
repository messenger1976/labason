<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class addmetercustomerreading extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_addcustomer_reading';	  //*****  Table name  *****//
	public $addPage  = 'addmetercustomerreading_add';	     //*****  Add page    *****//
	public $editPage = 'addmetercustomerreading_edit';     //*****  Edit page   *****//
	public $editSearchDetailPage = 'addmetercustomerreading_search_edit';     //*****  Search Edit page   *****//
	public $editSearchPage = 'addmetercustomerreading_search';     //*****  Search Edit page   *****//
	public $listPage = 'addmetercustomerreading';		   //*****  View page   *****//
	public $searchPage ='addmetercustomer_search';
	public $addcustomerajax = 'addmetercustomer_search _ajax';
	public $month_customer_invoice = 'month_customer_invoice';
	public $addpages_ajax = 'addmetercustomerreading_add _ajax';
	
	public $listPage_redirect = '/master/addmetercustomerreading';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/addmetercustomerreading/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/addmetercustomerreading/edit/';  //*****  Redirect Edit  *****//
	public $addcustomer_meterajax = 'addmetercustomerreading_metersearch_ajax';
	
	public function __construct() {
        parent::__construct();
  		$this->load->model('addmetercustomerreading_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 	
		ini_set('date.timezone', 'Asia/Manila');				
		$this->load->model('adminheader_model','top_model');
		$this->load->model('addcustomer_model','customer_model');
		$this->load->model('addbillingperiod_model','billingperiod_model');   //*****    Model Loading     *****//	
    }
	
	public function index(){ 		 //*****  View Loading  *****//
		$header['title'] = 'List Meter Reading';
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		if(!isset($_SESSION['current_billingperiod'])){
			$data['current_billingperiod'] = $this->comm_model->get_billingperiod_record();
			
			$_SESSION['current_billingperiod'] = $data['current_billingperiod'][0]['bp_period_month'].' '.$data['current_billingperiod'][0]['bp_period_year'];
		}
		$data['billingperiod'] = $this->billingperiod_model->get_month_billingperiod_records();	
		// No longer loading all records - using server-side pagination instead
		$data['record'] = array();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}
	
	/** AJAX endpoint for DataTables server-side processing **/
	public function get_datatable_data() {
		// Set JSON header first
		header('Content-Type: application/json');
		
		// Start output buffering to catch any errors
		ob_start();
		
		try {
			// Get DataTables parameters
			$start = $this->input->post('start') ? intval($this->input->post('start')) : 0;
			$length = $this->input->post('length') ? intval($this->input->post('length')) : 100;
			$draw = $this->input->post('draw') ? intval($this->input->post('draw')) : 1;
			
			// Safely get search value
			$search_post = $this->input->post('search');
			$search = '';
			if(is_array($search_post) && isset($search_post['value']) && !empty($search_post['value'])) {
				$search = trim($search_post['value']);
			}

			// Billing period filter: get from session
			$billing_period = isset($_SESSION['current_billingperiod']) ? $_SESSION['current_billingperiod'] : '';
			
			// Safely get order parameters
			$order_post = $this->input->post('order');
			$order_column_index = 0; // Default to ID column
			$order_dir = 'desc';
			if(is_array($order_post) && isset($order_post[0]) && is_array($order_post[0])) {
				if(isset($order_post[0]['column'])) {
					$order_column_index = intval($order_post[0]['column']);
				}
				if(isset($order_post[0]['dir'])) {
					$order_dir = $order_post[0]['dir'];
				}
			}
			
			// Map column index to column name (matching the table structure)
			$columns = array(
				0 => 'tbl_addcustomer_reading.id',  // Checkbox column
				1 => 'tbl_addcustomer_reading.id',  // S No
				2 => 'tbl_addcustomer_reading.refno',  // Billing Ref No
				3 => 'tbl_addcustomer_reading.customer_id',  // Customer-Id
				4 => 'tbl_addcustomer.last_name',  // Customer Name (ordered by last_name)
				5 => 'tbl_addcustomer_reading.previous_reading',  // Previous Reading
				6 => 'tbl_addcustomer_reading.reading',  // Current Reading
				7 => 'tbl_addcustomer_reading.consumed',  // Consumed
				8 => 'tbl_addcustomer_reading.month',  // Billing Period
				9 => 'tbl_addcustomer_reading.date'  // Reading Date
			);
			$order_column = isset($columns[$order_column_index]) ? $columns[$order_column_index] : 'tbl_addcustomer_reading.id';
			
			// Ensure model is loaded
			if(!isset($this->my_model)) {
				$this->load->model('addmetercustomerreading_model','my_model');
			}
			
			// Get filtered and paginated records
			$records = $this->my_model->get_paginated_records($start, $length, $search, $order_column, $order_dir, $billing_period);
			$total_records = $this->my_model->get_total_count('', $billing_period);
			$filtered_records = $this->my_model->get_total_count($search, $billing_period);
			
			// Format data for DataTables
			$data = array();
			$i = $start + 1;
			foreach($records as $row) {
				$row_id = isset($row['id']) ? $row['id'] : 0;
				
				// Format reading date
				$reading_date = '';
				if(isset($row['date']) && $row['date'] != '') {
					$reading_date = date('d M Y', strtotime($row['date']));
				}
				
				// Format billing period
				$billing_period_text = '';
				if(isset($row['month_name']) && isset($row['year'])) {
					$billing_period_text = $row['month_name'] . ' ' . $row['year'];
				}
				
				$data[] = array(
					'<label><input type="checkbox" class="ace" name="delete_ids[]" id="delete_ids[]" value="'.$row_id.'" /><span class="lbl"></span></label>',
					$i++,
					stripslashes(isset($row['refno']) ? $row['refno'] : ''),
					stripslashes(isset($row['customer_id']) ? $row['customer_id'] : ''),
					stripslashes((isset($row['last_name']) ? $row['last_name'] : '').', '.(isset($row['first_name']) ? $row['first_name'] : '')),
					stripslashes(isset($row['previous_reading']) ? $row['previous_reading'] : ''),
					stripslashes(isset($row['reading']) ? $row['reading'] : ''),
					stripslashes(isset($row['consumed']) ? $row['consumed'] : ''),
					$billing_period_text,
					$reading_date
				);
			}
			
			// Clear any output that might have been generated
			ob_clean();
			
			// Return JSON response
			$output = array(
				"draw" => $draw,
				"recordsTotal" => $total_records,
				"recordsFiltered" => $filtered_records,
				"data" => $data
			);
			
			echo json_encode($output);
			ob_end_flush();
			exit;
			
		} catch(Exception $e) {
			// Clear any output
			ob_clean();
			
			// Log the error for debugging
			log_message('error', 'DataTables Error: ' . $e->getMessage());
			log_message('error', 'DataTables Trace: ' . $e->getTraceAsString());
			
			// Return error response
			$draw = $this->input->post('draw') ? intval($this->input->post('draw')) : 1;
			$output = array(
				"draw" => $draw,
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => array(),
				"error" => "An error occurred: " . $e->getMessage()
			);
			
			echo json_encode($output);
			ob_end_flush();
			exit;
		} catch(Error $e) {
			// Clear any output
			ob_clean();
			
			// Catch PHP 7+ errors
			log_message('error', 'DataTables PHP Error: ' . $e->getMessage());
			
			$draw = $this->input->post('draw') ? intval($this->input->post('draw')) : 1;
			$output = array(
				"draw" => $draw,
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => array(),
				"error" => "An error occurred: " . $e->getMessage()
			);
			
			echo json_encode($output);
			ob_end_flush();
			exit;
		}
	}
	
	/** Add Function **/
	public function add(){ 
		$data['msg'] ='';
		$header['title'] = 'Add Meter Reading';
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->customer_model->get_all_records();

		$data['addmonth'] = $this->my_model->get_months();
		$data['year'] = date('Y');
		
		if($this->input->post('add') != ''){
                $result = $this->my_model->add_record();
				if($result == 1){
					
					if($result){
					$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
					$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Inserted Successfully...!</div>');
					redirect($this->listPage_redirect);
					}
					
				}elseif($result == 0){
					$data['msg'] = "Data Already available!...";
					$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Data Already available!</div>');
				
				}else{
					
					$data['msg'] = "Not Inserted...";
					$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Not Inserted...!</div>');
				
				}
				
		}
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->addPage,$data);
	}
	public function get_custmer_all_data($id){
		$this->load->model('addmetercustomerreading_model','my_model'); 
		$id = $this->input->post('id');
		$data['record'] = $this->my_model->get_customer_info($id);
		$data['last_reading'] = $this->my_model->get_last_reading($id); 
		$data['get_unit_price'] = $this->my_model->get_unit_price($data['last_reading']->consumed,$data['record']['classification']);
		//echo $data['record'][0]['zone_id'];
		$data['get_billing_period'] = $this->my_model->get_billing_period($data['record'][0]['zone_id']);
		//$data['get_billing_period'] = $data['record']['zone_id'];
		$this->load->view($this->addpages_ajax,$data);
	}

	public function get_cubic_meter_price(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$cubic_meter_reading = $_POST['cubic_meter_reading'];
			$customer_id = $_POST['customer_id'];
			$data['record'] = $this->my_model->get_cust_class_id($customer_id);
			$data['get_unit_price'] = $this->my_model->get_unit_price($cubic_meter_reading,$data['record']['classification']);
			//print_r($data['get_unit_price']); 
			echo json_encode($data['get_unit_price']);
		}
	}
	public function save_edit($id=''){
		

		//$data['addmonth'] = $this->my_model->get_months();
		if($this->input->post('edit') != ''){
			$result = $this->my_model->update_record($id);
			echo 'success';
			
		}
		
		

	}
	public function edit($id=''){
		$data['msg'] ='';
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		

		//$data['addmonth'] = $this->my_model->get_months();
		if($this->input->post('edit') != ''){
			$result = $this->my_model->update_record($id);
			if($result){
				$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Updated...";
			}
		}
		$this->load->view($this->headerPage,$header);
		if($id!=''){
			$data['record'] = $this->my_model->get_single_record($id);
			$this->load->view($this->editPage,$data);
		}else{
			$data['record'] = $this->customer_model->get_all_records();
			$data['zone'] = $this->comm_model->get_zone_records();
			$data['franchise_fee_percentage'] = $this->my_model->get_franchise_fee_percentage();
			$this->load->view($this->editSearchPage,$data);
		}
		

	}
	public function search_edit($id=''){
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		
		
		
		//$data['addmonth'] = $this->my_model->get_months();
		$data['msg'] ='';
		if($this->input->post('edit') != ''){
			$result = $this->my_model->update_record($id);
			if($result){
				$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Updated...";
			}
		}
		$this->load->view($this->headerPage,$header);
		if($id!=''){
			$data['record'] = $this->my_model->get_single_record($id);
			$this->load->view($this->editPage,$data);
		}else{
			$data['zone'] = $this->comm_model->get_zone_records();
			$data['franchise_fee_percentage'] = $this->my_model->get_franchise_fee_percentage();
			$this->load->view($this->editSearchPage,$data);
		}
		

	}
	
	
	/** Delete Function **/
	public function delete($id){ 
		$data['msg'] ='';
		if($id){
			$result = $this->my_model->delete_record($id);
			if($result){
			
				$this->session->set_flashdata('msg_succ', 'Deleted Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Deleted...";
			}
		}
	}
	/** Multiple Delete Function **/
	public function multi_delete(){
		$data['msg'] ='';
		if($this->input->post('delete_ids') != ''){
			$delete_ids = $this->input->post('delete_ids');
			for($i=0;$i<count($delete_ids);$i++){
				$result = $this->my_model->delete_record($delete_ids[$i]);
			}
			if($result){
				$this->session->set_flashdata('msg_succ', 'Deleted Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$this->session->set_flashdata('msg_succ', 'Not Deleted...');
				redirect($this->listPage_redirect);
			}
		}else{
			$this->session->set_flashdata('msg_succ', 'Select any Check Box...');
			redirect($this->listPage_redirect);
		}
	}

	public function getaddcustomersmetersearch()
	{		//*****  Add Search records  *****//
		$data['msg'] ='';
		//echo '<pre>'; print_r($this->input->post('zone'));exit;
		/*if($this->input->post('customer_id') =='' && $this->input->post('zone') =='' && $this->input->post('fromdate') =='' && $this->input->post('todate') =='')
		{
			$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
			echo $selBox;
		}*/
		//if($this->input->post('customer_id') !='' || $this->input->post('zone') !='' || $this->input->post('fromdate') !='' || $this->input->post('todate') !='')
		//{
			$customer_id = $this->input->post('customer_id');
						
			$data['record'] = $this->my_model->get_addcustomer_meterreading_records($customer_id);
			//echo'<pre>';print_r($data['record']);exit;
			$this->load->view($this->addcustomer_meterajax,$data);
		//}		
	}
	
}
?>