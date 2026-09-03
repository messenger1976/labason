<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class addpaymentcustomer extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_addmetercustomer';	  //*****  Table name  *****//
	public $addPage  = 'addpaymentcustomer_add';	     //*****  Add page    *****//
	public $editPage = 'addpaymentcustomer_edit';     //*****  Edit page   *****//
	public $listPage = 'addpaymentcustomer';		   //*****  View page   *****//
	public $viewPage ='addpaymentcustomer_view';
	public $searchPage ='addmetercustomer_search';
	public $addcustomerajax = 'addmetercustomer_search _ajax';
	public $month_customer_invoice = 'month_customer_invoice';
	public $printPage = 'addpaymentcustomer-print';
	public $addpages_ajax = 'addpaymentcustomer_add _ajax';
	
	public $listPage_redirect = '/master/addpaymentcustomer';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/addpaymentcustomer/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/addpaymentcustomer/edit/';  //*****  Redirect Edit  *****//
	
	
	public function __construct() {
        parent::__construct();
  		$this->load->model('addpaymentcustomer_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');
		$this->load->model('addbillingperiod_model','billingperiod_model');   //*****    Model Loading     *****//		
		$this->load->model('leakingentry_model');
		$this->load->helper('common_helper');
		$this->load->library('form_validation');
		// Skip TCPDF on AJAX/list helpers — load only for receipt/print methods
		$lightweight_methods = array(
			'get_custmer_all_data', 'get_custmer_name', 'get_or_number', 'check_or_number',
			'get_leaking_balance', 'chk_leakingentry', 'get_datatable_data', 'getoldmeter',
			'tchtemailcheck', 'get_calculation', 'get_name',
		);
		if (!in_array($this->router->fetch_method(), $lightweight_methods, true)) {
			$this->load->library('Pdf');
		}
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 		
		ini_set('date.timezone', 'Asia/Manila');		
		$this->load->model('adminheader_model','top_model');
		$this->load->model('addcustomer_model','customer_model');
    }

	private function load_receipt_payment_data($customer, $month, $year, $invoice_id = null) {
		$customer = trim((string) $customer);
		$month = trim((string) $month);
		$year = trim((string) $year);
		$invoice_id = ($invoice_id !== null && $invoice_id !== '') ? trim((string) $invoice_id) : null;

		if ($invoice_id !== null) {
			$receiptdata = $this->my_model->getReceipt_Data($customer, $month, $year, $invoice_id);
		} else {
			$receiptdata = $this->my_model->getReceiptData($customer, $month, $year);
		}

		if (!is_array($receiptdata)) {
			$receiptdata = array();
		}

		$grand_total = isset($receiptdata['grand_total']) ? (float) $receiptdata['grand_total'] : 0;
		$pay_amount = isset($receiptdata['pay_amount']) ? (float) $receiptdata['pay_amount'] : 0;
		$or_number = isset($receiptdata['or_number']) ? $receiptdata['or_number'] : null;
		$receiptdata['receipt_collected_amount'] = $this->leakingentry_model->get_collected_amount_for_leaking_payment(
			$grand_total,
			$pay_amount,
			$or_number
		);

		return $receiptdata;
	}
	public function index(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();

		// Persist payment list filters in session until logout
		if (!isset($_SESSION['current_billingperiod'])) {
			$_SESSION['current_billingperiod'] = ''; // --All-- default
		}
		if (empty($_SESSION['trans_date'])) {
			$_SESSION['trans_date'] = date('m/d/Y');
		}
		// No longer loading all records - using server-side pagination instead
		$data['record'] = array();
		$data['amountrate'] = $this->my_model->get_amountrate();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$data['billingperiod'] = $this->billingperiod_model->get_month_billingperiod_records();	
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
			$length = $this->input->post('length') ? intval($this->input->post('length')) : 10;
			$draw = $this->input->post('draw') ? intval($this->input->post('draw')) : 1;
			
			// Safely get search value
			$search_post = $this->input->post('search');
			$search = '';
			if(is_array($search_post) && isset($search_post['value']) && !empty($search_post['value'])) {
				$search = trim($search_post['value']);
			}

			// Filter rules:
			// - Billing period selected (not --All--): filter by month/year; ignore transaction date
			// - Billing period --All--: filter by Paid Date (transaction_date), default today
			$billing_period = trim($this->input->post('billing_period') ?: '');
			$transaction_date = trim($this->input->post('transaction_date') ?: '');
			$paid_date_filter = '';
			if ($billing_period === '') {
				if ($transaction_date === '') {
					$transaction_date = date('m/d/Y');
				}
				$paid_ts = strtotime(str_replace('-', '/', $transaction_date));
				$paid_date_filter = $paid_ts ? date('Y-m-d', $paid_ts) : date('Y-m-d');
			}
			
			// Safely get order parameters
			$order_post = $this->input->post('order');
			$order_column_index = 1; // Default to id column (tbl_addmetercustomer.id) desc
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
				0 => 'tbl_addmetercustomer.id',  // Checkbox column
				1 => 'tbl_addmetercustomer.id',  // S No
				2 => 'tbl_addmetercustomer.customer_id',  // Customer-Id
				3 => 'tbl_addcustomer.last_name',  // Name
				4 => 'tbl_addmetercustomer.or_number',  // OR #
				5 => 'tbl_addmetercustomer.total',  // Gross Amount
				6 => 'tbl_addmetercustomer.leaking_amount',  // Leaking Discount
				7 => 'tbl_addmetercustomer.vat_amount',  // VAT Discount
				8 => 'tbl_addmetercustomer.grand_total',  // Net Amount
				9 => 'tbl_addmetercustomer.date',  // Paid Date
				10 => 'tbl_addmetercustomer.id'  // Action
			);
			$order_column = isset($columns[$order_column_index]) ? $columns[$order_column_index] : 'tbl_addmetercustomer.id';
			
			// Ensure model is loaded
			if(!isset($this->my_model)) {
				$this->load->model('addpaymentcustomer_model','my_model');
			}
			
			// Get filtered and paginated records
			$records = $this->my_model->get_paginated_records($start, $length, $search, $order_column, $order_dir, $billing_period, $paid_date_filter);
			$total_records = $this->my_model->get_total_count('', $billing_period, $paid_date_filter);
			$filtered_records = $this->my_model->get_total_count($search, $billing_period, $paid_date_filter);
			
			// Format data for DataTables (SA4-styled cells/actions)
			$data = array();
			$i = $start + 1;
			foreach($records as $row) {
				$row_id = isset($row['id']) ? $row['id'] : 0;
				$customer_id = isset($row['customer_id']) ? $row['customer_id'] : '';
				$full_name = trim((isset($row['last_name']) ? $row['last_name'] : '').', '.(isset($row['first_name']) ? $row['first_name'] : '').' '.(isset($row['middle_name']) ? $row['middle_name'] : ''));
				$or_number = isset($row['or_number']) ? sprintf('%07d', $row['or_number']) : '';
				$total = isset($row['total']) ? (float)$row['total'] : 0;
				$leaking = isset($row['leaking_amount']) ? (float)$row['leaking_amount'] : 0;
				$vat = isset($row['vat_amount']) ? (float)$row['vat_amount'] : 0;
				$grand = isset($row['grand_total']) ? (float)$row['grand_total'] : 0;
				$paid_date = (!empty($row['date']) && $row['date'] != '0000-00-00') ? date('m/d/Y', strtotime($row['date'])) : '';

				$action_html = '<input type="hidden" name="customerid_'.$i.'" id="customerid_'.$i.'" value="'.htmlspecialchars($customer_id, ENT_QUOTES, 'UTF-8').'">'
					.'<input type="hidden" name="month_'.$i.'" id="month_'.$i.'" value="'.htmlspecialchars(isset($row['month']) ? $row['month'] : '', ENT_QUOTES, 'UTF-8').'">'
					.'<input type="hidden" name="year_'.$i.'" id="year_'.$i.'" value="'.htmlspecialchars(isset($row['year']) ? $row['year'] : '', ENT_QUOTES, 'UTF-8').'">'
					.'<input type="hidden" name="invoiceid_'.$i.'" id="invoiceid_'.$i.'" value="'.htmlspecialchars(isset($row['invoice_id']) ? $row['invoice_id'] : '', ENT_QUOTES, 'UTF-8').'">'
					.'<div class="btn-group btn-group-sm" role="group">'
					.'<a href="javascript:void(0);" class="btn btn-outline-primary print_button_new1" id="print_button_new1'.$i.'" data-print-val-id="'.$i.'" title="Print Receipt" data-toggle="tooltip"><i class="fal fa-print"></i></a>'
					.'<a href="javascript:void(0);" class="btn btn-outline-info print_button_new2" id="print_button_new2'.$i.'" data-print-val-id="'.$i.'" title="Secondary Print" data-toggle="tooltip"><i class="fal fa-file-invoice"></i></a>'
					.'</div>';

				$data[] = array(
					'<input type="checkbox" class="ace" name="delete_ids[]" value="'.$row_id.'" />',
					$i++,
					'<a href="'.ADMIN_URL.'addpaymentcustomer/get_monthly_customer_invoice/'.$row_id.'" class="badge badge-primary badge-pill">'.htmlspecialchars(stripslashes($customer_id), ENT_QUOTES, 'UTF-8').'</a>',
					htmlspecialchars(stripslashes($full_name), ENT_QUOTES, 'UTF-8'),
					'<span class="badge badge-secondary">'.htmlspecialchars($or_number, ENT_QUOTES, 'UTF-8').'</span>',
					'<span class="text-right d-block">'.number_format($total, 2).'</span>',
					'<span class="text-right d-block text-danger">'.number_format($leaking, 2).'</span>',
					'<span class="text-right d-block text-warning">'.number_format($vat, 2).'</span>',
					'<span class="text-right d-block fw-700 text-success">'.number_format($grand, 2).'</span>',
					$paid_date !== '' ? '<span class="badge badge-info badge-pill">'.$paid_date.'</span>' : '',
					$action_html
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
			log_message('error', 'DataTables File: ' . $e->getFile() . ' Line: ' . $e->getLine());
			
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
			log_message('error', 'DataTables Trace: ' . $e->getTraceAsString());
			log_message('error', 'DataTables File: ' . $e->getFile() . ' Line: ' . $e->getLine());
			
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
	public function add(){ //print_r($this->input->post);exit;
		$data['msg'] ='';
		
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		
		$data['addcustomer'] = $this->my_model->get_addcustomer();
		$data['record'] = $this->customer_model->get_all_records();

		

		if($this->input->post('add') != ''){ 
			
		
			$result = $this->my_model->add_record();
			if($result){
				$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$this->session->set_flashdata('msg_succ', 'Not inserted: OR/SI # may already be used, invalid, or payment allocation failed.');
				redirect($this->listPage_redirect);
			}

		}
			
		
		if($this->input->post('total_add') != ''){
			    $insert_ids = $this->input->post('checkbox');
				$uid = (int) $this->session->userdata('userid');
				$posted_or = (int) $this->input->post('or_num');
				if ($posted_or <= 0) {
					$this->session->set_flashdata('msg_succ', 'Invalid OR/SI number.');
					redirect($this->listPage_redirect);
				}
				if ($this->my_model->is_or_number_taken($posted_or)) {
					$this->session->set_flashdata('msg_succ', 'OR/SI number already exists for this teller.');
					redirect($this->listPage_redirect);
				}
				for($i=0;$i<count($insert_ids);$i++){
					$result = $this->my_model->add_record_multiple($insert_ids[$i], $posted_or);
				}
				if($result){
					$this->my_model->sync_or_series_max_after_posted($uid, $posted_or);
					$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
					redirect($this->listPage_redirect);
				}else{
					$this->session->set_flashdata('msg_succ', 'Not inserted: one or more payment lines failed.');
					redirect($this->listPage_redirect);
				}
			}
		$data['ledger'] = $this->my_model->fetchLedger();            // fetch ledgers	
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->addPage,$data);
	}
	
	public function get_custmer_all_data()
	{		//*****  Add Search records  *****//
			
			$id = $this->input->post('id');
			//$this->load->model('addpaymentcustomer_model','my_model');
			//$data['reading'] = $this->my_model->get_addcustomer_add_all_records($id);
			$data['record'] = $this->my_model->get_meter_reading_all_records($id);
			//$data['collectinfo'] =  $this->my_model->collectinfo($id);
			//$data['month_collectinfo'] = $this->my_model->month_collectinfo($id);
			//$data['second_higest_radi'] = $this->my_model->get_second_meter($id);
				
			//$data['amountrate'] = $this->my_model->get_unitvalue();
			$this->load->view($this->addpages_ajax,$data);
			
	}
	
	public function edit($id){
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		$data['addcustomer'] = $this->my_model->get_addcustomer();
		$data['per_unit'] = $this->my_model->get_amountrate();
		$data['msg'] ='';
		//echo'<pre>';print_r($data['record']);exit;
		if($this->input->post('add') != ''){
			$result = $this->my_model->update_record($id);
			
			
	
			if($result){
				//echo'<pre>';print_r($result);exit;
				$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Updated...";
			}
		}
		//$header['host'] = $this->comm_model->get_single_record();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->editPage,$data);

	}
	
	/** View Function **/
	public function view($id){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		//print_r($data['record']);
		//$header['host'] = $this->comm_model->get_single_record();						
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->viewPage,$data);
	}
	public function addaccountmetercustomer($id)
	{
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['id'] = $id;
		$this->load->view($this->headerPage,$header);
		$this->load->view('addaccountmetercustomer',$data);
	}
	public function saveaccountmetercustomer()
	{
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['customertype']= "meter";
		$data['customerid']	 = $this->input->post('id');
		$data['accountno']	 = $this->input->post('accountno');
		$data['accountname'] = $this->input->post('accountname');
		$data['amount'] 	 = $this->input->post('amount');
		$result = $this->my_model->addaccount($data);
		$this->load->view($this->headerPage,$header);
		$this->load->view('addaccountmetercustomer',$data);
	}
	public function addaccountmonthlycustomer($id)
	{
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['id'] = $id;
		$this->load->view($this->headerPage,$header);
		$this->load->view('addaccountmonthlycustomer',$data);
	}
	public function saveaccountmonthlycustomer()
	{
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['customertype']	 = "monthly";
		$data['customerid']	 = $this->input->post('id');
		$data['accountno']	 = $this->input->post('accountno');
		$data['accountname'] = $this->input->post('accountname');
		$data['amount'] 	 = $this->input->post('amount');
		$result = $this->my_model->addaccount($data);
		$this->load->view($this->headerPage,$header);
		$this->load->view('addaccountmonthlycustomer',$data);
	}
	/** Status Change Function **/
	/*public function contactStatus($id,$status){
		$data['msg'] ='';
		//echo $status;
		$statu = ($status == 1 ? 'Status Change' : 'Status Change');
		if($id){
			$result = $this->my_model->status_record($id,$status);
			if($result){
				$this->session->set_flashdata('msg_succ', $statu.' Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = " Status Not Updated...";
			}
		}
	}	*/
	
	/*public function Status($id,$status,$customer_id){
		$data['msg'] ='';
		//echo $status;
		$statu = ($status == 1 ? 'Status Change' : 'Status Change');
		if($id){
			$result = $this->my_model->status_record($id,$status,$customer_id);
			if($result){
				$this->session->set_flashdata('msg_succ', $statu.' Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = " Status Not Updated...";
			}
		}
	}	*/
	
	public function Status($id,$balance,$customer_id){
		$data['msg'] ='';
		//print_r($balance);
		$statu = ($balance == 0 ? 'Status Change' : 'Status Change');
		if($id){
			$result = $this->my_model->status_record($id,$balance,$customer_id);
			if($result){
				$this->session->set_flashdata('msg_succ', $statu.' Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = " Status Not Updated...";
			}
		}
	}
	
	/** Delete Function **/
	public function delete($id){ 
		$data['msg'] ='';
		if($id){
			$result = $this->my_model->delete_transaction_record($id);
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
	
	public function getoldmeter(){
		$id = $this->input->post('id');
		$getData=$this->my_model->select_getoldmeter($id);
		//echo'<pre>';print_r($getData);exit;
		if(!empty($getData)){
			if($getData[0]['status']=='1'){
		$selBox = '0'.'-'.'0';
			}else{
				$pay_amount = $getData[0]['total'];
			$selBox = $getData[0]['consumedunits'].'-'.$pay_amount;
            
			}
			
		}if(empty($getData)){
		
			$selBox = '0'.'-'.'0';
		}
		echo $selBox;
		
		
	}
	
	public function get_monthly_customer_invoice($id){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		$data['address'] = $this->my_model->get_address();
		//echo'<pre>';print_r($data['record']);exit; 
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->month_customer_invoice,$data);
	}	
	
	public function printInvoice($id){
		$data['msg'] ='';
		$data['record'] = $this->my_model->get_single_record($id);
		$data['address'] = $this->my_model->get_address();
		//echo'<pre>';print_r($data['record']);exit; 
		$this->load->view($this->printPage,$data);
	}	
	public function get_name(){ 
		 $cust_id=$this->input->post('id');
         $data['record'] = $this->my_model->get_name($cust_id);	
		$selBox ='
		     <div class="form-group">
			  <label class="col-sm-4 control-label no-padding-right" for="form-field-1">Name: </label>
			  <div class="col-sm-8">		
		    <input type="text" name="first_name" id="first_name" class="col-xs-10 col-sm-10" value="'.$data['record']['first_name'].''.$data['record']['middle_name'].''.$data['record']['last_name'].'"required  readonly ></div></div>';
		
		echo $selBox;
	}
    
    function tchtemailcheck() {
		
       $id = $this->input->post('id');
       $getData = $this->my_model->select_getoldmeter($id);	
	   //print_r($getData);
	   if(!empty($getData)){
		   
		    if($getData[0]['addmetercustomer_status']=='1'){
		        //$selBox = '0'.'-'.'0';
				$pay_amount = $getData[0]['total'];
				$selBox = $getData[0]['consumedunits'].'-'.$getData[0]['balance'];
			}else{
				$pay_amount = $getData[0]['total'];
			    //$selBox = $getData[0]['consumedunits'].'-'.$pay_amount;
				$selBox = $getData[0]['consumedunits'].'-'.$getData[0]['balance'];
               
			}
			
		}if(empty($getData)){
		
			//$selBox = '0'.'-'.'0';
			$selBox = '0'.'-'.'0';
		}
		echo $selBox;
    }
	
	public function get_custmer_name(){ 
		$cust_id = $this->input->post('id');
		$data['record'] = $this->my_model->get_name($cust_id);
		$r = $data['record'];
		$full_name = trim(
			(isset($r['first_name']) ? $r['first_name'] : '') . ' ' .
			(isset($r['middle_name']) ? $r['middle_name'] : '') . ' ' .
			(isset($r['last_name']) ? $r['last_name'] : '')
		);
		$fulladdress = trim(
			(isset($r['address']) ? $r['address'] : '') . ' ' .
			(isset($r['city']) ? $r['city'] : '') . ' ' .
			(isset($r['state']) ? $r['state'] : '')
		);
		$h = function ($v) {
			return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
		};

		echo '
		<input type="hidden" name="cust_id" id="cust_id" value="'.$h($r['customer_id']).'"/>
		<input type="hidden" name="tab_id" id="tab_id" value="'.$h($r['id']).'"/>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="first_name">Name</label>
					<input class="form-control" type="text" name="first_name" id="first_name" value="'.$h($full_name).'" required readonly>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="meter_number">Meter Number</label>
					<input class="form-control" type="text" name="meter_number" id="meter_number" value="'.$h(isset($r['meter_number']) ? $r['meter_number'] : '').'" required readonly>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="meter_brand">Meter Brand</label>
					<input class="form-control" type="text" name="meter_brand" id="meter_brand" value="'.$h(isset($r['meter_brand']) ? $r['meter_brand'] : '').'" required readonly>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="customer_id_display">Customer ID</label>
					<input class="form-control" type="text" name="customer_id_display" id="customer_id_display" value="'.$h(isset($r['customer_id']) ? $r['customer_id'] : '').'" required readonly>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group mb-0">
					<label class="form-label" for="address">Address</label>
					<input class="form-control" type="text" name="address" id="address" value="'.$h($fulladdress).'" required readonly>
				</div>
			</div>
		</div>';
	}
	public function get_calculation(){ 
	
		 $id = $this->input->post('id');
		 $oldmeter = $this->input->post('oldmeter');
		 
		 $newmeter = $id - $oldmeter;
		 
		 $tb_amount =  $this->db->get('tbl_amountrate')->row_array();
		 
		 extract($tb_amount);
		 
		 echo $selBox = $newmeter * $per_unit; 
		  
	}

	public function get_or_number(){ 
		$uid = (int) $this->session->userdata('userid');
		$n = $this->my_model->get_or_preview_for_user($uid);
		echo sprintf('%07d', $n);
   	}
	   public function get_leaking_balance(){ 
	
		
		$this->db->select("*");
		$this->db->from('tbl_leaking_ledger');
		//$this->db->where("leaking_balance>0");
		$this->db->where("leaking_customer_id",$this->input->post('customer_id'));
		$this->db->where("leaking_status",4);
		$query = $this->db->get();
		$result = $query->result_array();
			
		//echo $result[0]['leaking_balance'];
		echo json_encode($result);
		exit;
		 
   	}

	   public function chk_leakingentry($bill_no){ 
	
		
		$this->db->select("leaking_id, SUM(leaking_discount_amount) as discount_amount, SUM(leaking_discount_percent) as discount_percent, SUM(leaking_total_amount) as total_amount");
		$this->db->from('tbl_leaking_ledger');
		$this->db->where("leaking_refno",$bill_no);
		$this->db->where("leaking_status",2);
		$this->db->group_by("leaking_customer_id");
		$query = $this->db->get();
		$result = $query->result_array();
		
		/*if($query->num_rows() > 0){
			$result = $query->result_array();
		}else{
			$result = array('leaking_id'=>'null');	
		}	*/
		echo json_encode($result);
		exit;
		
		//$new_or_number = $result[0]['doc_series_num'] + 1;
		//echo sprintf('%07d',$new_or_number); 
		 
   	}

public function check_or_number($or_number){ 
	$this->db->select("id");
	$this->db->from('tbl_addmetercustomer');
	$this->db->where("CAST(or_number AS UNSIGNED)=", (int) $or_number);
	$uid = $this->session->userdata('userid');
	if ($uid !== null && $uid !== '' && (int) $uid > 0) {
		$this->db->where('userid', (int) $uid);
	}
	$query = $this->db->get();
	if ($query->num_rows() > 0) {
		echo 1;
	} else {
		echo 0;
	}
}

public function monthlyreceipt($customer,$month,$year) {
		
	//print_r($customer);	print_r($month);	print_r($year);	
    $receiptdata = $this->my_model->getReceiptData(trim($customer), $month, $year);
	

	$getaddress = $this->my_model->get_address();
	$customerdetials = $this->my_model->customer_deatils(trim($customer));
	$this->load->model('addcustomer_model','my_model123');
	$record = $this->my_model123->get_adminrecord();
	extract($record);
	 
	//print_r($customerdetials);
    extract($receiptdata);
    extract($getaddress);
	extract($customerdetials);
	$first_name = strtoupper(trim($first_name));
	$last_name = strtoupper(trim($last_name));
	$middle_name = strtoupper(trim($middle_name));
	$address = strtoupper(trim($address));
	$city = strtoupper(trim($city));
	$state = strtoupper(trim($state));
	$curdate = date('Y-m-d');
	$datefor = date('d-m-Y', strtotime($tdate));
	$panalty_msg ='';
	if($amount !== $reading_amount){
		$penalty = $amount - $reading_amount;
		//$amount = $penalty;
		$panalty_msg = '<span style="font-size:9px;line-height:8px;"><br/>Penalty = 10% = '. number_format($reading_amount,2).' + '.number_format($penalty,2).'</span>';
	}

	$amountinwords = convertNumberToWordsPH($grand_total);	
	//============================================================+
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'Legal', true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);


    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
    $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetMargins(0, 0, 0);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    //$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------    
    // set default font subsetting mode
    $pdf->setFontSubsetting(false);

    // Set font
    // dejavusans is a UTF-8 Unicode font, if you only need to
    // print standard ASCII chars, you can use core fonts like
    // helvetica or times to reduce file size.
    $pdf->SetFont('helvetica', '', 9, '', true);
	//$pdf->SetBackColor(255,255,255);

    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();

    /// create some HTML content
	$base_url = site_url();
	$amount = number_format($amount,2);
	$grand_total = number_format($grand_total,2);
	$html = <<<EOD
	<br/>
	<br/>
	<br/>
	
	<table border="0" cellspacing="5" cellpadding="0" width="100%" style="font-family: "Courier New", Courier, monospace;">
	
	<tr>
	<td>
	<div class="invoice-inner">
		 
		<div class="invoice-address">
		    <div style="height: 14px;"></div>
		    <table border="0" cellspacing="5" cellpadding="0" width="100%">
			  <tbody>	
					<tr>
						<td align="left" valign="top">
							<div style="text-align: right; margin-right:15px;">
							
								<span style="font-size:0.6em;"></span>
							</div>
							<div style="padding: 0px;">
								<span style="font-size:0.8em; font-family: Arial,"Courier New", Courier, monospace;">	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$first_name $middle_name $last_name</span>
							<br/>
								<span style="font-size:0.8em;font-family: Arial,"Courier New", Courier, monospace; ">     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$address</span>
							<br/>
								<span style="font-size:0.8em;font-family: Arial,"Courier New", Courier, monospace;">      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$customer_id</span>
							</div>
						</td>
						<td width="9%">&nbsp;</td>
						<td><div style="text-align: right; margin-right:15px;">
							<span style="font-size:0.6em;"></span>
							</div>
							<div style="padding: 0px;">
								<span style="font-size:0.8em; font-family: Arial,"Courier New", Courier, monospace;">	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$first_name $middle_name $last_name</span>
							<br/>
								<span style="font-size:0.8em; font-family: Arial,"Courier New", Courier, monospace;">     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$address</span>
							<br/>
								<span style="font-size:0.8em;font-family: Arial,"Courier New", Courier, monospace;">      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$customer_id</span>
							</div>
						</td>
					</tr>
					<tr><td></td><td></td><td></td></tr>
					<tr><td></td><td></td><td></td></tr>
					<tr style="font-weight: normal;font-size:8pt;font-family: Arial,"Courier New", Courier, monospace;">
						<td valign="top" style="height:20px;">$amountinwords</td>
						<td>&nbsp;</td>
						<td valign="top" style="height:20px;">$amountinwords</td>
					</tr>
					<tr style="font-size:8pt;font-family: Arial,"Courier New", Courier, monospace;">
						<td valign="top" height="15px;">
							<br/>
							
							<table border=0 cellpadding=5 cellspacing=5 width="100%" style="font-size:9pt;font-family: Arial,"Courier New", Courier, monospace;">
								<tr>
									<td>$monthname $year $panalty_msg</td>
									<td></td>
									<td align=right valign=top>$consumedunits</td>
									<td align=right valign=top style="text-align:right; width: 70px;">$amount</td>
								</tr>
								<!--<tr>
									<td></td>
									<td></td>
									<td align=center></td>
									<td align=right style="text-align:right;"></td>
								</tr>-->
								
							</table>

							<table border=0 cellpadding=5 cellspacing=5 width="100%" style="font-size:9pt;font-family: Arial,"Courier New", Courier, monospace;">
								
								<tr>
									<td></td>
									<td></td>
									<td align=right></td>
									<td align=right style="text-align:right;"></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td align=center></td>
									<td align=right style="text-align:right;width: 70px;">$vat_amount<br/>$grand_total</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td align=center></td>
									<td align=right style="text-align:right;"></td>
								</tr>
								
							</table>
						</td>
						<td>&nbsp;</td>
						<td valign="top">
							<br/>
						
							<table border=0 cellpadding=5 cellspacing=5 width="100%" style="font-size:9pt;font-family: Arial,"Courier New", Courier, monospace;">
								<tr>
									<td>$monthname $year $panalty_msg</td>
									<td></td>
									<td align=right valign=top>$consumedunits</td>
									<td align=right valign=top style="text-align:right;width: 70px;">$amount</td>
								</tr>
								<!--<tr>
									<td></td>
									<td></td>
									<td align=center></td>
									<td align=right style="text-align:right;"></td>
								</tr>-->
								
							</table>

							<table border=0 cellpadding=5 cellspacing=5 width="100%" style="font-size:9pt;font-family: Arial,"Courier New", Courier, monospace;">
								
								<tr>
									<td></td>
									<td></td>
									<td align=right></td>
									<td align=right style="text-align:right;"></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td align=center></td>
									<td align=right style="text-align:right;width: 70px;">$vat_amount<br/>$grand_total</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td align=center></td>
									<td align=right style="text-align:right;"></td>
								</tr>
								
								
							</table>
						</td>
					</tr>
					
					
					<tr style="font-size:9pt;font-family: Arial,"Courier New", Courier, monospace;">
						<td valign="top">$datefor</td>
						<td>&nbsp;</td>
						<td valign="top">$datefor</td>
					</tr>
					<tr style="font-size:8pt;font-family: Arial,"Courier New", Courier, monospace;">
						<td valign="top"></td>
						<td>&nbsp;</td>
						<td valign="top"></td>
					</tr>
				</tbody>	
            </table>
		</div>
		<br/><br/><br/><br/>
		
	 </div>	
	</td>
	 </tr>	
	</table>
    
	<script>
	window.print();
	</script>
EOD;
	echo $html;
//output the HTML content
   //$pdf->writeHTML($html, true, false, true, false, '');

    // ---------------------------------------------------------    
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
   //$pdf->Output($customer . '.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+
}


public function monthly_receipt($customer,$month,$year,$invoice_id) {
		
	//print_r($customer);	print_r($month);	print_r($year);	
    $receiptdata = $this->load_receipt_payment_data($customer, $month, $year, $invoice_id);
	

	$getaddress = $this->my_model->get_address();
	$customerdetials = $this->my_model->customer_deatils(trim($customer));
	$this->load->model('addcustomer_model','my_model123');
	$record = $this->my_model123->get_adminrecord();
	extract($record);
	 
	//print_r($customerdetials);
    extract($receiptdata);
    extract($getaddress);
	extract($customerdetials);
	$first_name = strtoupper(trim($first_name));
	$last_name = strtoupper(trim($last_name));
	$middle_name = strtoupper(trim($middle_name));
	$address = strtoupper(trim($address));
	$city = strtoupper(trim($city));
	$state = strtoupper(trim($state));
	$curdate = date('Y-m-d');
	$datefor = date('d-m-Y', strtotime($tdate));
	$panalty_msg ='';
	if($amount !== $reading_amount){
		$penalty = $amount - $reading_amount;
		//$amount = $penalty;
		$panalty_msg = '<span style="font-size:9px;line-height:8px;"><br/>Penalty = 10% = '. number_format($reading_amount,2).' + '.number_format($penalty,2).'</span>';
	}

	$receipt_collected_amount = isset($receipt_collected_amount) ? (float) $receipt_collected_amount : 0;
	$amountinwords = convertNumberToWordsPH($receipt_collected_amount);	
	//============================================================+
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'Legal', true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);


    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
    $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetMargins(0, 0, 0);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    //$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------    
    // set default font subsetting mode
    $pdf->setFontSubsetting(false);

    // Set font
    // dejavusans is a UTF-8 Unicode font, if you only need to
    // print standard ASCII chars, you can use core fonts like
    // helvetica or times to reduce file size.
    $pdf->SetFont('helvetica', '', 9, '', true);
	//$pdf->SetBackColor(255,255,255);

    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();

    /// create some HTML content
	$base_url = site_url();
	$amount = number_format($amount,2);
	$grand_total = number_format($receipt_collected_amount,2);
	$total = number_format($receipt_collected_amount,2);

	$detailspayment = detailsbillingpayment($invoice_id);
	$html = <<<EOD
	<br/>
	<br/>
	<br/>
	
	<table border="0" cellspacing="5" cellpadding="0" width="100%" style="font-family: "Courier New", Courier, monospace;">
	
	<tr>
	<td>
	<div class="invoice-inner">
		 
		<div class="invoice-address">
		    <div style="height: 14px;"></div>
		    <table border="0" cellspacing="5" cellpadding="0" width="100%">
			  <tbody>	
					<tr>
						<td align="left" valign="top">
							<div style="text-align: right; margin-right:15px;">
							
								<span style="font-size:0.6em;"></span>
							</div>
							<div style="padding: 0px;">
								<span style="font-size:0.8em; font-family: Arial,"Courier New", Courier, monospace;">	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$first_name $middle_name $last_name</span>
							<br/>
								<span style="font-size:0.8em;font-family: Arial,"Courier New", Courier, monospace; ">     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$address</span>
							<br/>
								<span style="font-size:0.8em;font-family: Arial,"Courier New", Courier, monospace;">      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$customer_id</span>
							</div>
						</td>
						<td width="9%">&nbsp;</td>
						<td><div style="text-align: right; margin-right:15px;">
							<span style="font-size:0.6em;"></span>
							</div>
							<div style="padding: 0px;">
								<span style="font-size:0.8em; font-family: Arial,"Courier New", Courier, monospace;">	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$first_name $middle_name $last_name</span>
							<br/>
								<span style="font-size:0.8em; font-family: Arial,"Courier New", Courier, monospace;">     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$address</span>
							<br/>
								<span style="font-size:0.8em;font-family: Arial,"Courier New", Courier, monospace;">      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$customer_id</span>
							</div>
						</td>
					</tr>
					<tr><td></td><td></td><td></td></tr>
					<tr><td></td><td></td><td></td></tr>
					<tr style="font-weight: normal;font-size:8pt;font-family: Arial,"Courier New", Courier, monospace;">
						<td valign="top" style="height:20px;">$amountinwords</td>
						<td>&nbsp;</td>
						<td valign="top" style="height:20px;">$amountinwords</td>
					</tr>
					<tr style="font-size:8pt;font-family: Arial,"Courier New", Courier, monospace;">
						<td valign="top" height="15px;">
							<br/>
							
							<table border=0 cellpadding=5 cellspacing=5 width="100%" style="font-size:9pt;font-family: Arial,"Courier New", Courier, monospace;">
								$detailspayment
								<!--<tr>
									<td></td>
									<td></td>
									<td align=center></td>
									<td align=right style="text-align:right;"></td>
								</tr>-->
								
							</table>

							<table border=0 cellpadding=5 cellspacing=5 width="100%" style="font-size:9pt;font-family: Arial,"Courier New", Courier, monospace;">
								
								<tr>
									<td></td>
									<td></td>
									<td align=right></td>
									<td align=right style="text-align:right;"></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td align=center></td>
									<td align=right style="text-align:right;width: 70px;">$leaking_amount<br/>$vat_amount<br/>$grand_total</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td align=center></td>
									<td align=right style="text-align:right;"></td>
								</tr>
								
							</table>
						</td>
						<td>&nbsp;</td>
						<td valign="top">
							<br/>
						
							<table border=0 cellpadding=5 cellspacing=5 width="100%" style="font-size:9pt;font-family: Arial,"Courier New", Courier, monospace;">
								$detailspayment
								<!--<tr>
									<td></td>
									<td></td>
									<td align=center></td>
									<td align=right style="text-align:right;"></td>
								</tr>-->
								
							</table>

							<table border=0 cellpadding=5 cellspacing=5 width="100%" style="font-size:9pt;font-family: Arial,"Courier New", Courier, monospace;">
								
								<tr>
									<td></td>
									<td></td>
									<td align=right></td>
									<td align=right style="text-align:right;"></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td align=center></td>
									<td align=right style="text-align:right;width: 70px;">$leaking_amount<br/>$vat_amount<br/>$grand_total</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td align=center></td>
									<td align=right style="text-align:right;"></td>
								</tr>
								
								
							</table>
						</td>
					</tr>
					
					
					<tr style="font-size:9pt;font-family: Arial,"Courier New", Courier, monospace;">
						<td valign="top">$datefor</td>
						<td>&nbsp;</td>
						<td valign="top">$datefor</td>
					</tr>
					<tr style="font-size:8pt;font-family: Arial,"Courier New", Courier, monospace;">
						<td valign="top"></td>
						<td>&nbsp;</td>
						<td valign="top"></td>
					</tr>
				</tbody>	
            </table>
		</div>
		<br/><br/><br/><br/>
		
	 </div>	
	</td>
	 </tr>	
	</table>
    
	<script>
	window.print();
	</script>
EOD;
	echo $html;
//output the HTML content
   //$pdf->writeHTML($html, true, false, true, false, '');

    // ---------------------------------------------------------    
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
   //$pdf->Output($customer . '.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+
}

public function monthly_receipt_ver1($customer,$month,$year,$invoice_id) {
		
	//print_r($customer);	print_r($month);	print_r($year);	
    $receiptdata = $this->load_receipt_payment_data($customer, $month, $year, $invoice_id);
	

	$getaddress = $this->my_model->get_address();
	$customerdetials = $this->my_model->customer_deatils(trim($customer));
	$this->load->model('addcustomer_model','my_model123');
	$record = $this->my_model123->get_adminrecord();
	extract($record);
	 
	//print_r($customerdetials);
    extract($receiptdata);
    extract($getaddress);
	extract($customerdetials);
	$first_name = strtoupper(trim($first_name));
	$last_name = strtoupper(trim($last_name));
	$middle_name = strtoupper(trim($middle_name));
	$address = strtoupper(trim($address));
	$city = strtoupper(trim($city));
	$state = strtoupper(trim($state));
	$curdate = date('Y-m-d');
	$datefor = date('d-m-Y', strtotime($tdate));
	$panalty_msg ='<span style="font-size:9px;line-height:8px;"><br/>';
	if($amount !== $reading_amount){
		if($maintenance_fee>0.00){
			$panalty_msg = 'WMMF = '. number_format($maintenance_fee,2).' + '.number_format($penalty,2).',';
			$amount -= $maintenance_fee;
		}
		$penalty = $amount - $reading_amount;
		//$amount = $penalty;
		$panalty_msg .= 'Penalty  = '. number_format($reading_amount,2).' + '.number_format($penalty,2);
	}
	$panalty_msg .='</span>';

	$receipt_collected_amount = isset($receipt_collected_amount) ? (float) $receipt_collected_amount : 0;
	$amountinwords = convertNumberToWordsPH($receipt_collected_amount);	
	

    /// create some HTML content
	$base_url = site_url();
	$amount = number_format($amount,2);
	$grand_total = number_format($receipt_collected_amount,2);
	$total = number_format($receipt_collected_amount,2);

	$detailspayment = detailsbillingpayment_ver1($invoice_id);
	$html = <<<EOD
	<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Official Water Bill Receipt</title>
    <style>
      :root {
        --blue: #2f4f9f;
        --red: #c62828;
        --line: #2f4f9f;
      }

      * {
        box-sizing: border-box;
      }

      body {
        margin: 0;
        padding: 24px;
        font-family: Arial, Helvetica, sans-serif;
        color: var(--blue);
        background: #fff;
      }

      .sheet {
        display: flex;
        gap: 24px;
        align-items: stretch;
      }

      .receipt {
        flex: 1;
        border: 2px solid var(--line);
        padding: 14px 16px 18px;
        min-height: 760px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
      }

      .header {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-bottom: 6px;
      }

      .logo {
        width: 64px;
        height: 64px;
        border: 2px solid var(--line);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-size: 9px;
        font-weight: bold;
        line-height: 1.1;
        padding: 4px;
		
      }

      .header-text {
        flex: 1;
        text-align: center;
        font-weight: bold;
        line-height: 1.2;
      }

      .header-text .title {
        font-size: 18px;
        letter-spacing: 1px;
      }

      .header-text .sub {
        font-size: 12px;
        font-weight: normal;
      }

      .receipt-meta {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin: 8px 0 6px;
        font-weight: bold;
      }

      .receipt-meta .label {
        font-size: 14px;
      }

      .receipt-meta .number {
        font-size: 22px;
        color: var(--red);
        letter-spacing: 1px;
      }

      .box {
        border: 2px solid var(--line);
        margin-top: 8px;
      }

      .box-row {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 8px;
        border-bottom: 1px solid var(--line);
      }

      .box-row:last-child {
        border-bottom: none;
      }

      .box-label {
        min-width: 120px;
        font-size: 12px;
        text-transform: uppercase;
      }

      .fill-line {
        flex: 1;
        /*border-bottom: 1px solid var(--line);*/
        height: 12px;
      }

      .amounts {
        display: flex;
        gap: 24px;
        margin-top: 10px;
      }

      .amount-col {
        flex: 1;
      }

      .amount-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 6px 0;
        font-size: 12px;
        text-transform: uppercase;
      }

      .amount-row .line {
        flex: 1;
        border-bottom: 1px solid var(--line);
        height: 12px;
      }

      .total-row {
        font-weight: bold;
      }

      .notice-row {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-top: 8px;
      }

      .notice-box {
        border: 2px solid var(--line);
        padding: 6px 10px;
        font-size: 10px;
        text-align: center;
        max-width: 210px;
        line-height: 1.3;
      }

      .signature-box {
        border: 2px solid var(--line);
        display: grid;
        grid-template-rows: repeat(3, 1fr);
        width: 260px;
      }

      .signature-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        border-bottom: 1px solid var(--line);
      }

      .signature-row:last-child {
        border-bottom: none;
      }

      .signature-label {
        padding: 4px 6px;
        font-size: 11px;
        text-transform: uppercase;
        border-right: 1px solid var(--line);
      }

      .signature-line {
        border-left: none;
      }

      .received-payment {
        margin-top: 12px;
        font-size: 12px;
        text-transform: uppercase;
      }

      .signature-lines {
        display: flex;
        justify-content: space-between;
        margin-top: 12px;
        font-size: 10px;
      }

      .signature-line-block {
        width: 44%;
        text-align: center;
      }

      .signature-line-block .line {
        border-bottom: 1px solid var(--line);
        height: 12px;
        margin-bottom: 2px;
      }

      .footer {
        font-size: 9px;
        line-height: 1.3;
        margin-top: 10px;
      }

      .footer-top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
      }

      .copy-label {
        text-align: right;
        font-weight: bold;
        margin-top: 4px;
        font-size: 10px;
        text-transform: uppercase;
      }

      @media print {
        body {
          padding: 0;
        }

        .sheet {
          gap: 12px;
        }
      }
    </style>
  </head>
  <body>
    <div class="sheet">
      <section class="receipt">
        <div>
          <div class="header">
            <div class="logo">
              <img src="{$base_url}/img/pmroxas-logo.png" alt="Logo" style="width: 100%; height: 100%;">
            </div>
            <div class="header-text">
              <div class="title">LABASON WATER DISTRICT</div>
              <div class="sub">Brgy. Imelda, Labason, Zamboanga del Norte, Philippines</div>
              <div class="sub">NON-VAT REG. TIN 269-102-147-000</div>
            </div>
          </div>

          <div class="receipt-meta">
            <div class="label">OFFICIAL WATER BILL RECEIPT</div>
            <div class="label">No. <span class="number">$or_number</span></div>
          </div>

          <div class="box">
            <div class="box-row">
              <div class="box-label">RECEIVED FROM :</div>
              <div class="fill-line">$first_name $middle_name $last_name</div>
            </div>
            <div class="box-row">
              <div class="box-label">ADDRESS :</div>
              <div class="fill-line">$address</div>
            </div>
            <div class="box-row">
              <div class="box-label">ACCOUNT NO. :</div>
              <div class="fill-line">$customer_id</div>
            </div>
          </div>

          <div class="box">
            <div class="box-row">
              <div class="box-label">AMOUNT IN WORDS :</div>
              <div class="fill-line">$amountinwords</div>
            </div>
          </div>

          <div class="amounts">
            <div class="amount-col">
              <div class="amount-row">
                CURRENT AMOUNT :
                <span class="line">$amount</span>
              </div>
              <div class="amount-row">
                ARREARS :
                <span class="line">$arrears</span>
              </div>
              <div class="amount-row">
                SURCHARGE :
                <span class="line"></span>
              </div>
              <div class="amount-row">
                SC/PWD DISCOUNT :
                <span class="line">$sc_discount</span>
              </div>
              <div class="amount-row total-row">
                TOTAL AMOUNT :
                <span class="line">$grand_total</span>
              </div>
            </div>
            <div class="amount-col">
              <div class="amount-row">
                AMOUNT TENDERED :
                <span class="line">$pay_amount</span>
              </div>
              <div class="amount-row">
                AMOUNT PAID :
                <span class="line">$total</span>
              </div>
              <div class="amount-row">
                CHANGE :
                <span class="line">$balance</span>
              </div>
            </div>
          </div>

          <div class="notice-row">
            <div class="notice-box">
              THIS RECEIPT IS NOT VALID UNLESS<br />
              SIGNED BY OUR TELLER
            </div>
            <div class="signature-box">
              <div class="signature-row">
                <div class="signature-label">SR. CITIZEN TIN</div>
                <div class="signature-line"></div>
              </div>
              <div class="signature-row">
                <div class="signature-label">OSCA/PWD ID NO.</div>
                <div class="signature-line"></div>
              </div>
              <div class="signature-row">
                <div class="signature-label">CARDHOLDER'S SIG</div>
                <div class="signature-line"></div>
              </div>
            </div>
          </div>

          <div class="received-payment">RECEIVED PAYMENT</div>
          <div class="signature-lines">
            <div class="signature-line-block">
              <div class="line">$datefor</div>
              DATE
            </div>
            <div class="signature-line-block">
              <div class="line"></div>
              TELLER
            </div>
          </div>
        </div>

        <div class="footer">
          <div class="footer-top">
            <div>
              20 Boxes (1x) 0180001-0300000
              <br />
              BIR Authority to Print No. 091AU202200000211
              <br />
              Date Issued: 06-27-2022 Valid until: 06-26-2027
            </div>
            <div>
              Edwin S. Villaver - Optima Typographics
              <br />
              Near Gaisano Grand Sitio Ibaba Basak, Lapu-Lapu City
              <br />
              Tel. No. 505 2662 Telefax No. 232 2887
              <br />
              TIN: 149-755-518-000 VAT
              <br />
              Printer's Accreditation No. 080MP201900000037
              <br />
              Date Issued 09 May 2019
              <br />
              LOOSELEAF Permit No. L00R-091-03052018-00002
            </div>
          </div>
          <div class="copy-label">OFFICE COPY</div>
          <div>
            THIS DOCUMENT IS NOT VALID FOR CLAIMING INPUT TAXES
            <br />
            THIS OFFICIAL WATER BILL RECEIPT SHALL BE VALID FOR (5) YEARS FROM THE DATE OF ATP.
          </div>
        </div>
      </section>

      <section class="receipt">
        <div>
          <div class="header">
            <div class="logo">
             <img src="{$base_url}/img/pmroxas-logo.png" alt="Logo" style="width: 100%; height: 100%;">
            </div>
            <div class="header-text">
              <div class="title">LABASON WATER DISTRICT</div>
              <div class="sub">Brgy. Imelda, Labason, Zamboanga del Norte, Philippines</div>
              <div class="sub">NON-VAT REG. TIN 269-102-147-000</div>
            </div>
          </div>

          <div class="receipt-meta">
            <div class="label">OFFICIAL WATER BILL RECEIPT</div>
            <div class="label">No. <span class="number">$or_number</span></div>
          </div>

          <div class="box">
            <div class="box-row">
              <div class="box-label">RECEIVED FROM :</div>
              <div class="fill-line">$first_name $middle_name $last_name</div>
            </div>
            <div class="box-row">
              <div class="box-label">ADDRESS :</div>
              <div class="fill-line">$address</div>
            </div>
            <div class="box-row">
              <div class="box-label">ACCOUNT NO. :</div>
              <div class="fill-line">$customer_id</div>
            </div>
          </div>

          <div class="box">
            <div class="box-row">
              <div class="box-label">AMOUNT IN WORDS :</div>
              <div class="fill-line">$amountinwords</div>
            </div>
          </div>

          <div class="amounts">
            <div class="amount-col">
              <div class="amount-row">
                CURRENT AMOUNT :
                <span class="line">$amount</span>
              </div>
              <div class="amount-row">
                ARREARS :
                <span class="line">$arrears</span>
              </div>
              <div class="amount-row">
                SURCHARGE :
                <span class="line">$surcharge</span>
              </div>
              <div class="amount-row">
                SC/PWD DISCOUNT :
                <span class="line">$sc_discount</span>
              </div>
              <div class="amount-row total-row">
                TOTAL AMOUNT :
                <span class="line">$grand_total</span>
              </div>
            </div>
            <div class="amount-col">
              <div class="amount-row">
                AMOUNT TENDERED :
                <span class="line">$pay_amount</span>
              </div>
              <div class="amount-row">
                AMOUNT PAID :
                <span class="line">$total</span>
              </div>
              <div class="amount-row">
                CHANGE :
                <span class="line">$balance</span>
              </div>
            </div>
          </div>

          <div class="notice-row">
            <div class="notice-box">
              THIS RECEIPT IS NOT VALID UNLESS<br />
              SIGNED BY OUR TELLER
            </div>
            <div class="signature-box">
              <div class="signature-row">
                <div class="signature-label">SR. CITIZEN TIN</div>
                <div class="signature-line"></div>
              </div>
              <div class="signature-row">
                <div class="signature-label">OSCA/PWD ID NO.</div>
                <div class="signature-line"></div>
              </div>
              <div class="signature-row">
                <div class="signature-label">CARDHOLDER'S SIG</div>
                <div class="signature-line"></div>
              </div>
            </div>
          </div>

          <div class="received-payment">RECEIVED PAYMENT</div>
          <div class="signature-lines">
            <div class="signature-line-block">
              <div class="line">$datefor</div>
              DATE
            </div>
            <div class="signature-line-block">
              <div class="line"></div>
              TELLER
            </div>
          </div>
        </div>

        <div class="footer">
          <div class="footer-top">
            <div>
              20 Boxes (1x) 0180001-0300000
              <br />
              BIR Authority to Print No. 091AU202200000211
              <br />
              Date Issued: 06-27-2022 Valid until: 06-26-2027
            </div>
            <div>
              Edwin S. Villaver - Optima Typographics
              <br />
              Near Gaisano Grand Sitio Ibaba Basak, Lapu-Lapu City
              <br />
              Tel. No. 505 2662 Telefax No. 232 2887
              <br />
              TIN: 149-755-518-000 VAT
              <br />
              Printer's Accreditation No. 080MP201900000037
              <br />
              Date Issued 09 May 2019
              <br />
              LOOSELEAF Permit No. L00R-091-03052018-00002
            </div>
          </div>
          <div class="copy-label">CONSUMER COPY</div>
          <div>
            THIS DOCUMENT IS NOT VALID FOR CLAIMING INPUT TAXES
            <br />
            THIS OFFICIAL WATER BILL RECEIPT SHALL BE VALID FOR (5) YEARS FROM THE DATE OF ATP.
          </div>
        </div>
      </section>
    </div>
  </body>
</html>
<script type="text/javascript">
  window.print();
</script>


EOD;
	echo $html;
//output the HTML content
   //$pdf->writeHTML($html, true, false, true, false, '');

    // ---------------------------------------------------------    
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
   //$pdf->Output($customer . '.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+
}

public function monthly_receipt_ver3($customer,$month,$year,$invoice_id) {
		
	//print_r($customer);	print_r($month);	print_r($year);	
    $receiptdata = $this->load_receipt_payment_data($customer, $month, $year, $invoice_id);
	

	$getaddress = $this->my_model->get_address();
	$customerdetials = $this->my_model->customer_deatils(trim($customer));
	$this->load->model('addcustomer_model','my_model123');
	$record = $this->my_model123->get_adminrecord();
	extract($record);
	 
	//print_r($customerdetials);
    extract($receiptdata);
    extract($getaddress);
	extract($customerdetials);
	$first_name = strtoupper(trim($first_name));
	$last_name = strtoupper(trim($last_name));
	$middle_name = strtoupper(trim($middle_name));
	$address = strtoupper(trim($address));
	$city = strtoupper(trim($city));
	$state = strtoupper(trim($state));
	$curdate = date('Y-m-d');
	$datefor = date('d-m-Y', strtotime($tdate));
	$panalty_msg ='<span style="font-size:9px;line-height:8px;"><br/>';
	if($amount !== $reading_amount){
		if($maintenance_fee>0.00){
			$panalty_msg = 'WMMF = '. number_format($maintenance_fee,2).' + '.number_format($penalty,2).',';
			$amount -= $maintenance_fee;
		}
		$penalty = $amount - $reading_amount;
		//$amount = $penalty;
		$panalty_msg .= 'Penalty  = '. number_format($reading_amount,2).' + '.number_format($penalty,2);
	}
	$panalty_msg .='</span>';

	$receipt_collected_amount = isset($receipt_collected_amount) ? (float) $receipt_collected_amount : 0;
	$amountinwords = convertNumberToWordsPH($receipt_collected_amount);	
	

    /// create some HTML content
	$base_url = site_url();
	$amount = number_format($amount,2);
	$grand_total = number_format($receipt_collected_amount,2);
	$total = number_format($receipt_collected_amount,2);

	$detailspayment = detailsbillingpayment_ver1($invoice_id);
	$html = <<<EOD
	<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Official Water Bill Receipt</title>
    <style>
      :root {
        --blue: #2f4f9f;
        --red: #c62828;
        --line: #2f4f9f;
      }

      * {
        box-sizing: border-box;
      }

      body {
        margin: 0;
        padding: 24px;
        font-family: Arial, Helvetica, sans-serif;
        color: var(--blue);
        background: #fff;
      }

      .sheet {
        display: flex;
        gap: 24px;
        align-items: stretch;
      }

      .receipt {
        flex: 1;
        border: 2px none var(--line);
        padding: 14px 16px 18px;
        min-height: 760px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
      }

      .header {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-bottom: 6px;
		visibility:hidden;		
      }

      .logo {
        width: 64px;
        height: 64px;
        border: 2px solid var(--line);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-size: 9px;
        font-weight: bold;
        line-height: 1.1;
        padding: 4px;
		visibility:hidden;
      }

      .header-text {
        flex: 1;
        text-align: center;
        font-weight: bold;
        line-height: 1.2;
      }

      .header-text .title {
        font-size: 18px;
        letter-spacing: 1px;
      }

      .header-text .sub {
        font-size: 12px;
        font-weight: normal;
      }

      .receipt-meta {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin: 8px 0 6px;
        font-weight: bold;
		visibility:hidden;
      }

      .receipt-meta .label {
        font-size: 14px;
		visibility:hidden;
      }

      .receipt-meta .number {
        font-size: 22px;
        color: var(--red);
        letter-spacing: 1px;
		visibility:hidden;
      }

      .box {
        border: 2px solid var(--line);
        margin-top: 8px;
		visibility:hidden;
      }

      .box-row {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 8px;
        border-bottom: 1px solid var(--line);
		visibility:hidden;
      }

      .box-row:last-child {
        border-bottom: none;
      }

      .box-label {
        min-width: 120px;
        font-size: 12px;
        text-transform: uppercase;
		visibility:hidden;
      }

      .fill-line {
        flex: 1;
        /*border-bottom: 1px solid var(--line);*/
        height: 12px;
		visibility: visible;
      }

      .amounts {
        display: flex;
        gap: 24px;
        margin-top: 10px;
      }

      .amount-col {
        flex: 1;
      }

      .amount-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 6px 0;
        font-size: 12px;
        text-transform: uppercase;
      }

      .amount-row .line {
        flex: 1;
        border-bottom: 1px solid var(--line);
        height: 12px;
      }

      .total-row {
        font-weight: bold;
      }

      .notice-row {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-top: 8px;
		visibility: hidden;
      }

      .notice-box {
        border: 2px solid var(--line);
        padding: 6px 10px;
        font-size: 10px;
        text-align: center;
        max-width: 210px;
        line-height: 1.3;
		visibility: hidden;
      }

      .signature-box {
        border: 2px solid var(--line);
        display: grid;
        grid-template-rows: repeat(3, 1fr);
        width: 260px;
      }

      .signature-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        border-bottom: 1px solid var(--line);
      }

      .signature-row:last-child {
        border-bottom: none;
      }

      .signature-label {
        padding: 4px 6px;
        font-size: 11px;
        text-transform: uppercase;
        border-right: 1px solid var(--line);
      }

      .signature-line {
        border-left: none;
      }

      .received-payment {
        margin-top: 12px;
        font-size: 12px;
        text-transform: uppercase;
      }

      .signature-lines {
        display: flex;
        justify-content: space-between;
        margin-top: 12px;
        font-size: 10px;
      }

      .signature-line-block {
        width: 44%;
        text-align: center;
      }

      .signature-line-block .line {
        border-bottom: 1px solid var(--line);
        height: 12px;
        margin-bottom: 2px;
      }

      .footer {
        font-size: 9px;
        line-height: 1.3;
        margin-top: 10px;
		visibility: hidden;
      }

      .footer-top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
		visibility: hidden;
      }

      .copy-label {
        text-align: right;
        font-weight: bold;
        margin-top: 4px;
        font-size: 10px;
        text-transform: uppercase;
      }

      @media print {
        body {
          padding: 0;
        }
		.receipt {
			border: 2px none var(--line);
			padding: 14px 16px 18px;
			min-height: 760px;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
		}

        .sheet {
          gap: 12px;
        }
      }
    </style>
  </head>
  <body>
    <div class="sheet">
      <section class="receipt">
        <div>
          <div class="header">
            <div class="logo">
              <img src="{$base_url}/img/pmroxas-logo.png" alt="Logo" style="width: 100%; height: 100%;">
            </div>
            <div class="header-text">
              <div class="title">LABASON WATER DISTRICT</div>
              <div class="sub">Brgy. Imelda, Labason, Zamboanga del Norte, Philippines</div>
              <div class="sub">NON-VAT REG. TIN 269-102-147-000</div>
            </div>
          </div>

          <div class="receipt-meta">
            <div class="label">OFFICIAL WATER BILL RECEIPT</div>
            <div class="label">No. <span class="number">$or_number</span></div>
          </div>

          <div class="box">
            <div class="box-row">
              <div class="box-label">RECEIVED FROM :</div>
              <div class="fill-line">$first_name $middle_name $last_name</div>
            </div>
            <div class="box-row">
              <div class="box-label">ADDRESS :</div>
              <div class="fill-line">$address</div>
            </div>
            <div class="box-row">
              <div class="box-label">ACCOUNT NO. :</div>
              <div class="fill-line">$customer_id</div>
            </div>
          </div>

          <div class="box">
            <div class="box-row">
              <div class="box-label">AMOUNT IN WORDS :</div>
              <div class="fill-line">$amountinwords</div>
            </div>
          </div>

          <div class="amounts">
            <div class="amount-col">
              <div class="amount-row">
                CURRENT AMOUNT :
                <span class="line">$amount</span>
              </div>
              <div class="amount-row">
                ARREARS :
                <span class="line">$arrears</span>
              </div>
              <div class="amount-row">
                SURCHARGE :
                <span class="line"></span>
              </div>
              <div class="amount-row">
                SC/PWD DISCOUNT :
                <span class="line">$sc_discount</span>
              </div>
              <div class="amount-row total-row">
                TOTAL AMOUNT :
                <span class="line">$grand_total</span>
              </div>
            </div>
            <div class="amount-col">
              <div class="amount-row">
                AMOUNT TENDERED :
                <span class="line">$pay_amount</span>
              </div>
              <div class="amount-row">
                AMOUNT PAID :
                <span class="line">$total</span>
              </div>
              <div class="amount-row">
                CHANGE :
                <span class="line">$balance</span>
              </div>
            </div>
          </div>

          <div class="notice-row">
            <div class="notice-box">
              THIS RECEIPT IS NOT VALID UNLESS<br />
              SIGNED BY OUR TELLER
            </div>
            <div class="signature-box">
              <div class="signature-row">
                <div class="signature-label">SR. CITIZEN TIN</div>
                <div class="signature-line"></div>
              </div>
              <div class="signature-row">
                <div class="signature-label">OSCA/PWD ID NO.</div>
                <div class="signature-line"></div>
              </div>
              <div class="signature-row">
                <div class="signature-label">CARDHOLDER'S SIG</div>
                <div class="signature-line"></div>
              </div>
            </div>
          </div>

          <div class="received-payment">RECEIVED PAYMENT</div>
          <div class="signature-lines">
            <div class="signature-line-block">
              <div class="line">$datefor</div>
              DATE
            </div>
            <div class="signature-line-block">
              <div class="line"></div>
              TELLER
            </div>
          </div>
        </div>

        <div class="footer">
          <div class="footer-top">
            <div>
              20 Boxes (1x) 0180001-0300000
              <br />
              BIR Authority to Print No. 091AU202200000211
              <br />
              Date Issued: 06-27-2022 Valid until: 06-26-2027
            </div>
            <div>
              Edwin S. Villaver - Optima Typographics
              <br />
              Near Gaisano Grand Sitio Ibaba Basak, Lapu-Lapu City
              <br />
              Tel. No. 505 2662 Telefax No. 232 2887
              <br />
              TIN: 149-755-518-000 VAT
              <br />
              Printer's Accreditation No. 080MP201900000037
              <br />
              Date Issued 09 May 2019
              <br />
              LOOSELEAF Permit No. L00R-091-03052018-00002
            </div>
          </div>
          <div class="copy-label">OFFICE COPY</div>
          <div>
            THIS DOCUMENT IS NOT VALID FOR CLAIMING INPUT TAXES
            <br />
            THIS OFFICIAL WATER BILL RECEIPT SHALL BE VALID FOR (5) YEARS FROM THE DATE OF ATP.
          </div>
        </div>
      </section>

      <section class="receipt">
        <div>
          <div class="header">
            <div class="logo">
             <img src="{$base_url}/img/pmroxas-logo.png" alt="Logo" style="width: 100%; height: 100%;">
            </div>
            <div class="header-text">
              <div class="title">LABASON WATER DISTRICT</div>
              <div class="sub">Brgy. Imelda, Labason, Zamboanga del Norte, Philippines</div>
              <div class="sub">NON-VAT REG. TIN 269-102-147-000</div>
            </div>
          </div>

          <div class="receipt-meta">
            <div class="label">OFFICIAL WATER BILL RECEIPT</div>
            <div class="label">No. <span class="number">$or_number</span></div>
          </div>

          <div class="box">
            <div class="box-row">
              <div class="box-label">RECEIVED FROM :</div>
              <div class="fill-line">$first_name $middle_name $last_name</div>
            </div>
            <div class="box-row">
              <div class="box-label">ADDRESS :</div>
              <div class="fill-line">$address</div>
            </div>
            <div class="box-row">
              <div class="box-label">ACCOUNT NO. :</div>
              <div class="fill-line">$customer_id</div>
            </div>
          </div>

          <div class="box">
            <div class="box-row">
              <div class="box-label">AMOUNT IN WORDS :</div>
              <div class="fill-line">$amountinwords</div>
            </div>
          </div>

          <div class="amounts">
            <div class="amount-col">
              <div class="amount-row">
                CURRENT AMOUNT :
                <span class="line">$amount</span>
              </div>
              <div class="amount-row">
                ARREARS :
                <span class="line">$arrears</span>
              </div>
              <div class="amount-row">
                SURCHARGE :
                <span class="line">$surcharge</span>
              </div>
              <div class="amount-row">
                SC/PWD DISCOUNT :
                <span class="line">$sc_discount</span>
              </div>
              <div class="amount-row total-row">
                TOTAL AMOUNT :
                <span class="line">$grand_total</span>
              </div>
            </div>
            <div class="amount-col">
              <div class="amount-row">
                AMOUNT TENDERED :
                <span class="line">$pay_amount</span>
              </div>
              <div class="amount-row">
                AMOUNT PAID :
                <span class="line">$total</span>
              </div>
              <div class="amount-row">
                CHANGE :
                <span class="line">$balance</span>
              </div>
            </div>
          </div>

          <div class="notice-row">
            <div class="notice-box">
              THIS RECEIPT IS NOT VALID UNLESS<br />
              SIGNED BY OUR TELLER
            </div>
            <div class="signature-box">
              <div class="signature-row">
                <div class="signature-label">SR. CITIZEN TIN</div>
                <div class="signature-line"></div>
              </div>
              <div class="signature-row">
                <div class="signature-label">OSCA/PWD ID NO.</div>
                <div class="signature-line"></div>
              </div>
              <div class="signature-row">
                <div class="signature-label">CARDHOLDER'S SIG</div>
                <div class="signature-line"></div>
              </div>
            </div>
          </div>

          <div class="received-payment">RECEIVED PAYMENT</div>
          <div class="signature-lines">
            <div class="signature-line-block">
              <div class="line">$datefor</div>
              DATE
            </div>
            <div class="signature-line-block">
              <div class="line"></div>
              TELLER
            </div>
          </div>
        </div>

        <div class="footer">
          <div class="footer-top">
            <div>
              20 Boxes (1x) 0180001-0300000
              <br />
              BIR Authority to Print No. 091AU202200000211
              <br />
              Date Issued: 06-27-2022 Valid until: 06-26-2027
            </div>
            <div>
              Edwin S. Villaver - Optima Typographics
              <br />
              Near Gaisano Grand Sitio Ibaba Basak, Lapu-Lapu City
              <br />
              Tel. No. 505 2662 Telefax No. 232 2887
              <br />
              TIN: 149-755-518-000 VAT
              <br />
              Printer's Accreditation No. 080MP201900000037
              <br />
              Date Issued 09 May 2019
              <br />
              LOOSELEAF Permit No. L00R-091-03052018-00002
            </div>
          </div>
          <div class="copy-label">CONSUMER COPY</div>
          <div>
            THIS DOCUMENT IS NOT VALID FOR CLAIMING INPUT TAXES
            <br />
            THIS OFFICIAL WATER BILL RECEIPT SHALL BE VALID FOR (5) YEARS FROM THE DATE OF ATP.
          </div>
        </div>
      </section>
    </div>
  </body>
</html>
<script type="text/javascript">
  window.print();
</script>


EOD;
	echo $html;
//output the HTML content
   //$pdf->writeHTML($html, true, false, true, false, '');

    // ---------------------------------------------------------    
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
   //$pdf->Output($customer . '.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+
}
public function monthly_receipt_ver2($customer,$month,$year,$invoice_id) {
		
	//print_r($customer);	print_r($month);	print_r($year);	
    $receiptdata = $this->load_receipt_payment_data($customer, $month, $year, $invoice_id);
	

	$getaddress = $this->my_model->get_address();
	$customerdetials = $this->my_model->customer_deatils(trim($customer));
	$this->load->model('addcustomer_model','my_model123');
	$record = $this->my_model123->get_adminrecord();
	extract($record);
	 
	//print_r($customerdetials);
    extract($receiptdata);
    extract($getaddress);
	extract($customerdetials);
	$first_name = strtoupper(trim($first_name));
	$last_name = strtoupper(trim($last_name));
	$middle_name = strtoupper(trim($middle_name));
	$address = strtoupper(trim($address));
	$city = strtoupper(trim($city));
	$state = strtoupper(trim($state));
	$curdate = date('Y-m-d');
	$datefor = date('m/d/Y', strtotime($tdate));
	$panalty_msg ='<span style="font-size:9px;line-height:8px;"><br/>';
	if($amount !== $reading_amount){
		if($maintenance_fee>0.00){
			$panalty_msg = 'WMMF = '. number_format($maintenance_fee,2).' + '.number_format($penalty,2).',';
			$amount -= $maintenance_fee;
		}
		$penalty = $amount - $reading_amount;
		//$amount = $penalty;
		$panalty_msg .= 'Penalty  = '. number_format($reading_amount,2).' + '.number_format($penalty,2);
	}
	$panalty_msg .='</span>';

	$receipt_collected_amount = isset($receipt_collected_amount) ? (float) $receipt_collected_amount : 0;
	$amountinwords = convertNumberToWordsPH($receipt_collected_amount);	
	

    /// create some HTML content
	$base_url = site_url();
	$amount = number_format($amount,2);
	$grand_total = number_format($receipt_collected_amount,2);
	$total = number_format($receipt_collected_amount,2);

	$detailspayment = detailsbillingpayment_ver2($invoice_id);
	$html = <<<EOD
	<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Invoice with Two Receipts</title>
    <style>
        /* General Body Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0px; /* Adjust as needed for overall page margins */
            display: flex;
            justify-content: space-around;
            gap: 0px;
            width: 8.7in;
		    height: 263.5mm; 
        }

        /* Individual Receipt Column Styles */
        .receipt-column {
            width: 50%; /* Each column takes roughly half the page width */
            border: 1px solid #ccc;
            padding: 15px;
            box-sizing: border-box;
            background-color: #f9f9f9;
        }

        /* Header Styling */
        .receipt-header {
            text-align: left;
            margin-bottom: 15px;
            line-height: .4em;
        }
        .receipt-header h2 {
            margin: 0;
            color: #333;
        }

        /* Info Section Styling */
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.9em;
        }
        .receipt-info div {
            flex: 1;
        }
        .receipt-info .align-right {
            text-align: right;
        }

        /* Item Table Styling */
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .item-table th, .item-table td {
            border: 0px solid #eee;
            /*padding: 8px;*/
            text-align: left;
            font-size: 0.9em;
        }
        .item-table th {
            /*background-color: #e2e2e2;*/
        }

        /* Total Section Styling */
        .total-section {
            text-align: right;
            margin-top: 10px;
            font-size: 1em;
        }
        .total-section p {
            margin: 5px 0;
        }
        .total-section .grand-total {
            /*font-weight: bold;
            font-size: 1.1em;*/
            color: #000;
        }

        /* Notes Section Styling */
        .notes-section {
            margin-top: 20px;
            font-size: 0.8em;
            color: #555;
            border-top: 1px dashed #ccc;
            padding-top: 10px;
        }
        .amount-words{
                line-height: 1em;
                padding-left: .2in;
                height: 2em;
                margin-bottom: .8em;
            }

        /* Print-Specific Styles for Letter Paper */
        @media print {
            /* Set the page size to Letter */
            @page {
                size: Letter;  /*Specifies standard Letter paper (8.5in x 11in) */
                margin: 0in; /* Default margins for the printed page */
                width: 8.5in;
		        height: 263.5mm; 
                /* Define the bleed area */
                
            }

            body {
                margin: .1in -0.5in 0in 0in; /* Remove body margin for print to let @page margin control */
                flex-direction: row;
                justify-content: space-between;
                gap: 0;
                width: 8.5in;
		        height: 263.5mm; 
            }
            .receipt-column {
                border: none;
                padding: 0.2in;
                width: 50%; /* Slightly adjust width for print to fill space better */
                page-break-inside: avoid;
                font-size: 0.75em; /* Slightly smaller font for more content if space is tight */
            }
            .receipt-header {
                text-align: left;
                line-height: .4em;
                padding-left: .85in;
                
            }

            .amount-words{
                font-family: 'Courier New', Courier, monospace;
                line-height: 1em;
                padding-left: .2in;
                height: 3em;
                margin-bottom: .5em;
                letter-spacing: -0.5px;
            }
            .item-table {
                width: 100%;
                border: 0px solid black;
                margin-bottom: 15px;
				font-size:9pt;
            }
            /* Optional: Adjust font sizes slightly for print if needed */
            .item-table th, .item-table td, .notes-section, .receipt-info {
                font-family: 'Courier New', Courier, monospace;
				font-size:9pt;
                letter-spacing: -0.5px;
                /*font-size: 0.79em;  Slightly smaller font for more content if space is tight */
            }
            .total-section {
                position: absolute;
                top: 2.4in;
                font-weight: normal;
                font-family: 'Courier New', Courier, monospace;
                font-size: 1em;
                color: #000;
                line-height:.5em;
                letter-spacing: -0.5px;
				width: .65in;
            }
            .remark-section {
                position: absolute;
                top: 2.4in;
                font-weight: normal;
                font-family: 'Courier New', Courier, monospace;
                font-size: 1em;
                color: #000;
                line-height:1em;
                letter-spacing: -0.5px;
            }
            .date-section {
                position: absolute;
                top: 2.7in;
                font-weight: normal;
                font-family: 'Courier New', Courier, monospace;
                font-size: 1em;
                color: #000;
                line-height:1em;
                letter-spacing: -0.5px;
            }
            .cashier-section {
                position: absolute;
                top: 2.7in;
                font-weight: normal;
                font-family: 'Courier New', Courier, monospace;
                font-size: 1em;
                color: #000;
                line-height:1em;
                letter-spacing: -0.5px;
            }
        }
    </style>
</head>
<body>

    <div class="receipt-column">
        <div class="receipt-header">
            <p>$first_name $middle_name $last_name</p>
            <p>$address</p>
            <p>$customer_id</p>
        </div>
        <div class="amount-words">
            <p>$amountinwords</p>
        </div>
        


       <table class="item-table">
           

                    <td style="width: 50%;text-align: right;">$amount</td>                 
                    
                    <td style="text-align: right;">$pay_amount</td>
                </tr>
                <tr>
                    <td style="width: 50%;text-align: right;">$arrears</td>
                   
                    
                    <td style="text-align: right;">$total</td>
                </tr>
                <tr>
                    <td style="width: 50%;text-align: right;">$leaking_amount</td>
                    
                    
                    <td style="text-align: right;">$balance</td>
                </tr>
				<tr>
                    <td style="width: 50%;text-align: right;">$sc_discount</td>
                    
                    
                    <td style="text-align: right;"></td>
                </tr>
				<tr>
                    <td style="width: 50%;text-align: right;">$grand_total</td>
                    
                    
                    <td style="text-align: right;"></td>
                </tr>
				
            
        </table>

        <div class="total-section" style="left:3.4in;">
            <p></p>
            <p></p>
            <p></p>
            <p class="grand-total"></p>
        </div>

        <div class="remark-section">
            
            
        </div>
        <div class="date-section" style="left:.55in;">
            $datefor            
        </div>
        <div class="cashier-section" style="left:1.5in;">
            <!--Herald Felisilda-->            
        </div>
    </div>

    <div class="receipt-column">
        <div class="receipt-header">
            <p>$first_name $middle_name $last_name</p>
            <p>$address</p>
            <p>$customer_id</p>
        </div>
        <div class="amount-words" style="padding-right: .2in;">
            <p>$amountinwords</p>
        </div>
        

        <table class="item-table" style="width: 95%;">
            
            <tbody>
			<td style="width: 50%;text-align: right;">$amount</td>                 
                    
                    <td style="text-align: right;">$pay_amount</td>
                </tr>
                <tr>
                    <td style="width: 50%;text-align: right;">$arrears</td>
                   
                    
                    <td style="text-align: right;">$total</td>
                </tr>
                <tr>
                    <td style="width: 50%;text-align: right;">$leaking_amount</td>
                    
                    
                    <td style="text-align: right;">$balance</td>
                </tr>
				<tr>
                    <td style="width: 50%;text-align: right;">$sc_discount</td>
                    
                    
                    <td style="text-align: right;"></td>
                </tr>
				<tr>
                    <td style="width: 50%;text-align: right;">$grand_total</td>
                    
                    
                    <td style="text-align: right;"></td>
                </tr>
            </tbody>
        </table>

        
        <div class="total-section" style="left:7.45in;">
            <p></p>
            <p></p>
            <p></p>
            <p class="grand-total"></p>
        </div>
        <div class="remark-section">
           
            
        </div>
        <div class="date-section">
            $datefor            
        </div>
        <div class="cashier-section" style="left:5.5in;">
            <!--Herald Felisilda   -->         
        </div>
        
    </div>
<script type="text/javascript">
		window.print();
	</script>
</body>
</html>
EOD;
	echo $html;
//output the HTML content
   //$pdf->writeHTML($html, true, false, true, false, '');

    // ---------------------------------------------------------    
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
   //$pdf->Output($customer . '.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+
}

    //public function monthlyreceipt_single($customer,$month,$year) {
	public function monthlyreceipt_single($customer,$month,$year,$name,$current_reading,$oldmeter,$unit,$pay_amount,$invoice_ids,$address,$currency='PHP') {	
	//print_r($customer);	print_r($month);	print_r($year);	
	//exit;
	$consume = $current_reading - $oldmeter;
	$name = urldecode($name);
	$address = urldecode($address);
	$amo = $unit;
	$bal = $pay_amount;
    $receiptdata = $this->my_model->getReceiptData($customer, $month, $year);
	$getaddress = $this->my_model->get_address();
	$customerdetials = $this->my_model->customer_deatils($customer);
	$intint = 1;
    //print_r($receiptdata);
	//print_r($customerdetials);
	//exit;
	$record = $this->customer_model->get_adminrecord();
	//print_r($record);
	//exit;
	extract($record);
    extract($receiptdata);
    extract($getaddress);
	extract($customerdetials);
	$todate = date('d-m-Y');
	$datefor = date('d-m-Y', strtotime($tdate));
    //============================================================+
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);


    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
    $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------    
    // set default font subsetting mode
    $pdf->setFontSubsetting(false);

    // Set font
    // dejavusans is a UTF-8 Unicode font, if you only need to
    // print standard ASCII chars, you can use core fonts like
    // helvetica or times to reduce file size.
    $pdf->SetFont('helvetica', '', 9, '', true);
	//$pdf->SetBackColor(255,255,255);

    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();

    /// create some HTML content

$html = <<<EOD
	<table border="1" cellspacing="5" cellpadding="15" width="100%">
	<tr>
	<td>
	<div class="invoice-inner" style="margin: 0 15px; padding: 0px 0; color:#000;">
		 <table border="0" cellspacing="0" cellpadding="0" width="100%" style="width: 100%; height: 300px; background-image: url('images/orwaterbilling.jpg'); background-size: cover; background-repeat: no-repeat; background-position: center;">
			<tr>
				<td align="center" valign="top">
				
						<div class="logoDiv">
							<!--<img id="logo" src="http://dayaxpower.com/Waterbilling/images/logo/$file" height="80">-->
							<h1>$name</h1>
							<h3>$contact1 / $email</h3>
							<h3>$address1</h3>
							<h2>Service Invoice</h2>
						 <p>(for Meter Customer)</p>
						</div>
						
				</td>
			</tr>
			
		</table>	
		<div class="invoice-address" style="border-top: 2px double #888;margin: 20px 0; padding-top: 25px;">
		    <br/>
		    <table border="1" cellspacing="0" cellpadding="15" width="100%">
			  <tbody>	
					<tr>
						<td align="left" valign="top"><span style="font-size:11px;">Customer-Id :</span><span style="font-size:11px; font-weight: bold;"> $customer</span></td>
						<td valign="top"><span style="font-size:11px;">Invoice :</span> <span style="font-size:11px; font-weight: bold;">#$invoice_ids</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Bill To :</span><span style="font-size:11px; font-weight: bold;"> $first_name, $last_name $middle_name</span></td>
						<td valign="top"><span style="font-size:11px;">Date :</span><span style="font-size:11px; font-weight: bold;"> $todate</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Address :</span> <span style="font-size:11px; font-weight: bold;">$address $city <br/>$state</span> </td>
						<td valign="top"><span style="font-size:11px;">Unit Rate:</span><span style="font-size:11px; font-weight: bold;"> 1</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Month : </span> <span style="font-size:11px; font-weight: bold;">$monthname $year</span></td>
						<td valign="top"><span style="font-size:11px;">Reading :</span> <span style="font-size:11px; font-weight: bold;">$current_reading</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Consumed Unit :</span> <span style="font-size:11px; font-weight: bold;">$consume</span></td>
						<td valign="top"><span style="font-size:11px;">Price :</span> <span style="font-size:11px; font-weight: bold;">$amo</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Balance Amount :</span> <span style="font-size:11px; font-weight: bold;">$bal</span></td>
						<td valign="top"><span style="font-size:11px;">Pay :</span><span style="font-size:11px; font-weight: bold;"> $pay_amount  $currency</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Status :</span> <span style="font-size:11px; font-weight: bold;">Paid</span></td>
					</tr>
				</tbody>	
            </table>
		</div>
		<br/><br/><br/><br/>
		<table>
		  <tr>
		     <td align="left"><span style="padding-left:20px;">Date : $todate</span></td>
			 <td align="right"><span style="padding-right:50px;">Signature</span></td>
		  </tr>
		</table>
	 </div>	
	</td>
	 </tr>	
	</table>	
	
    

EOD;

// output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');

    // ---------------------------------------------------------    
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $pdf->Output($customer . '.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+
}


}
?>