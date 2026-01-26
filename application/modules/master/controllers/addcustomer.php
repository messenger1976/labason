<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class addcustomer extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_addcustomer';	  //*****  Table name  *****//
	public $tbl_addmetercustomer = 'tbl_addmetercustomer';	  //*****  Table name  *****//
	public $table_customerreading = 'tbl_addcustomer_reading';	  //*****  Table name  *****//
	public $table_zone = 'tbl_zone';	  //*****  Table name  *****//
	public $table_meter = 'tbl_addmetercustomer';
	public $addPage  = 'addcustomer_add';	     //*****  Add page    *****//
	public $editPage = 'addcustomer_edit';     //*****  Edit page   *****//
	public $listPage = 'addcustomer';		   //*****  View page   *****//
	public $viewPage ='addcustomer_view';
	public $searchPage ='addcustomer_search';
	public $monthlysearchPage ='addcustomer_monthlysearch';
	public $metersearchPage ='addcustomer_metersearch';
	public $incomesearchPage ='addcustomer_income_reportsearch';
	public $customersearchPage ='addcustomer_generatemetercustomer_search';
	public $technicalsearchPage ='addcustomer_technicalsearch';
	public $paidsearchPage ='addcustomer_paidsearch';
	public $unpaidsearchPage ='addcustomer_unpaidsearch';
	public $addcustomerajax = 'addcustomer_search _ajax';
	public $addcustomer_monthlyajax = 'addcustomer_monthlysearch _ajax';
	public $addcustomer_meterajax = 'addcustomer_metersearch _ajax';
	public $addcustomer_incomeajax = 'addcustomer_income_reportsearch _ajax';
	public $addcustomer_monthly_incomeajax = 'addcustomer_income_reportsearch_monthly_ajax';
	public $addcustomer_technicalajax = 'addcustomer_technicalsearch _ajax';
	public $addcustomerpaidajax = 'addcustomer_paidsearch _ajax';
	public $addcustomerunpaidajax = 'addcustomer_unpaidsearch _ajax';
	public $addcustomer_customerajax = 'addcustomer_generatemetercustomersearch _ajax';
	public $customer_invoice = 'customer_invoice';
	public $printPage = 'customer-print';
	public $printpdfPage = 'print_monthly';
	public $printpdfMeterPage = 'print_meter';
	public $printpdfMonthPage = 'print_meter_month';
	public $printpdfIncomeMonthPage = 'print_income_month';
	public $printpdfIncomeMeterPage = 'print_income_meter';
	public $paidPrintCustomerpage = 'print_paid_customer';
	public $UnpaidPrintCustomerpage = 'print_unpaid_customer';
	
	public $listPage_redirect = '/master/addcustomer';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/addcustomer/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/addcustomer/edit/';  //*****  Redirect Edit  *****//
	public function __construct() {
        parent::__construct();
		$this->load->helper('common_helper');
  		$this->load->model('addcustomer_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');
		$this->load->model('addbillingperiod_model','billingperiod_model');   //*****    Model Loading     *****//		
		$this->load->library('form_validation');
		$this->load->library('ciqrcode');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }
	public function index(){ 		 //*****  View Loading  *****//
		if($this->session->userdata('usertype') == 'subadmin'){
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
			//echo '<pre>';print_r($this->head['roleResponsible']);exit;
		}else{
			$this->head['roleResponsible'] = array();
		}
		if(	array_key_exists('addcustomer',$this->head['roleResponsible']) && $this->session->userdata('usertype') == 'subadmin' ){
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['addcustomer']);
		}	
		// No longer loading all records - using server-side pagination instead
		$data['record'] = array();
		$data['zone'] = $this->my_model->get_zone();
		//$header['host'] = $this->comm_model->get_single_record();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$this->head);
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

			// Zone filter: 'all' or empty = no filter
			$zone = $this->input->post('zone');
			if($zone === '' || $zone === 'all' || $zone === null) {
				$zone = '';
			} else {
				$zone = trim($zone);
			}
			
			// Safely get order parameters
			$order_post = $this->input->post('order');
			$order_column_index = 3; // Default to Name column
			$order_dir = 'asc';
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
				0 => 'tbl_addcustomer.id',  // Checkbox column
				1 => 'tbl_addcustomer.id',  // S No
				2 => 'tbl_addcustomer.customer_id',  // Customer id
				3 => 'tbl_addcustomer.last_name',  // Name (ordered by last_name)
				4 => 'tbl_addcustomer.address',  // Address
				5 => 'tbl_addcustomer.meter_number',  // Meter Number
				6 => 'tbl_zone.zone',  // Zone
				7 => 'tbl_classification.class_name',  // Classification
				8 => 'tbl_addcustomer.status',  // Status
				9 => 'tbl_addcustomer.id'  // Action
			);
			$order_column = isset($columns[$order_column_index]) ? $columns[$order_column_index] : 'tbl_zone.zone';
			
			// Ensure model is loaded
			if(!isset($this->my_model)) {
				$this->load->model('addcustomer_model','my_model');
			}
			
			// Get filtered and paginated records
			$records = $this->my_model->get_paginated_records($start, $length, $search, $order_column, $order_dir, $zone);
			$total_records = $this->my_model->get_total_count('', $zone);
			$filtered_records = $this->my_model->get_total_count($search, $zone);
			
			// Format data for DataTables
			$data = array();
			$i = $start + 1;
			foreach($records as $row) {
				$status_html = '';
				$row_status = isset($row['status']) ? $row['status'] : 0;
				$row_id = isset($row['id']) ? $row['id'] : 0;
				
				if($row_status == 1) {
					$status_html = '<span class="label label-success arrowed-in arrowed-in-right"><a href="JavaScript:if(confirm(\'Are you sure want to Chanage the Status?\')==true){window.location=\''.ADMIN_URL.'addcustomer/status/'.$row_id.'/'.$row_status.'\';}" style="color:#FFF; text-decoration:none;">Active</a></span>';
				} elseif($row_status == 0) {
					$status_html = '<span class="label label-danger arrowed"><a href="JavaScript:if(confirm(\'Are you sure want to Chanage the Status?\')==true){window.location=\''.ADMIN_URL.'addcustomer/status/'.$row_id.'/'.$row_status.'\';}" style="color:#FFF; text-decoration:none;">De-Active</a></span>';
				} elseif($row_status == 2) {
					$status_html = '<span class="label label-danger arrowed"><a href="JavaScript:if(confirm(\'Are you sure want to Chanage the Status?\')==true){window.location=\''.ADMIN_URL.'addcustomer/status/'.$row_id.'/'.$row_status.'\';}" style="color:#FFF; text-decoration:none;">Disconnected</a></span>';
				}
				
				$action_html = '<input type="hidden" name="id_'.$i.'" id="id_'.$i.'" value="'.$row_id.'">
								<input type="hidden" name="customerid_'.$i.'" id="customerid_'.$i.'" value="'.(isset($row['customer_id']) ? $row['customer_id'] : '').'">
								<input type="hidden" name="billingplansid_'.$i.'" id="billingplansid_'.$i.'" value="'.(isset($row['billingplans']) ? $row['billingplans'] : '').'">
								<div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
									<a class="blue view-customer-btn" href="javascript:void(0);" data-customer-id="'.$row_id.'" title="View">
										<i class="fa fa-info-circle"></i>
									</a>	
									<a class="green edit-customer-btn" href="javascript:void(0);" data-customer-id="'.$row_id.'" title="Edit">
										<i class="fa fa-edit"></i>
									</a>
									<a class="orange set-password-btn" href="javascript:void(0);" data-customer-id="'.$row_id.'" data-customer-code="'.(isset($row['customer_id']) ? $row['customer_id'] : '').'" title="Set Login Password">
										<i class="fa fa-key"></i>
									</a>
									<a class="red" href="JavaScript:if(confirm(\'Confirm Delete?\')==true){window.location=\''.ADMIN_URL.'addcustomer/delete/'.$row_id.'\';}" title="Delete">
										<i class="fa fa-remove"></i>
									</a>
								</div>
								<div class="visible-xs visible-sm hidden-md hidden-lg">
									<div class="inline position-relative">
										<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
											<i class="icon-caret-down icon-only bigger-120"></i>
										</button>
										<ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
											<li>
												<a href="javascript:void(0);" class="edit-customer-btn" data-customer-id="'.$row_id.'" data-rel="tooltip" title="Edit">
													<span class="green">
														<img src="'.base_url().'images/favicon/document-edit.gif">
													</span>
												</a>
											</li>
											<li>
												<a class="blue view-customer-btn" href="javascript:void(0);" data-customer-id="'.$row_id.'" data-rel="tooltip" title="View">
													<span class="blue">
														<img src="'.base_url().'images/favicon/view_icon.gif">
													</span>
												</a>			
											</li>
											<li>
												<a href="javascript:void(0);" class="set-password-btn" data-customer-id="'.$row_id.'" data-customer-code="'.(isset($row['customer_id']) ? $row['customer_id'] : '').'" data-rel="tooltip" title="Set Login Password">
													<span class="orange">
														<i class="fa fa-key"></i>
													</span>
												</a>
											</li>
											<li>
												<a href="JavaScript:if(confirm(\'Confirm Delete?\')==true){window.location=\''.ADMIN_URL.'addcustomer/delete/'.$row_id.'\';}" class="tooltip-error" data-rel="tooltip" title="Delete">
													<span class="red">
														<img src="'.base_url().'images/favicon/delete.png">
													</span>
												</a>
											</li>
										</ul>
									</div>
								</div>';
				
				$data[] = array(
					'<input type="checkbox" class="ace" name="delete_ids[]" id="delete_ids[]" value="'.$row_id.'" />',
					$i++,
					stripslashes(isset($row['customer_id']) ? $row['customer_id'] : ''),
					stripslashes((isset($row['last_name']) ? $row['last_name'] : '').',  '.(isset($row['first_name']) ? $row['first_name'] : '').'  '.(isset($row['middle_name']) ? $row['middle_name'] : '')),
					'<span style="width:25%;">'.stripslashes(isset($row['address']) ? $row['address'] : '').'</span>',
					stripslashes(isset($row['meter_number']) ? $row['meter_number'] : ''),
					stripslashes(isset($row['zones']) ? $row['zones'] : ''),
					stripslashes(isset($row['classification_name']) ? $row['classification_name'] : ''),
					$status_html,
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
	
	/** Save Customer Login Password **/
	public function save_customer_password() {
		// Clear any previous output
		if(ob_get_level()) {
			ob_clean();
		}
		
		// Set JSON header first
		header('Content-Type: application/json');
		
		try {
			// Ensure model is loaded
			if(!isset($this->my_model)) {
				$this->load->model('addcustomer_model','my_model');
			}
			
			$customer_id = $this->input->post('customer_id');
			$password = $this->input->post('password');
			
			// Log the received data for debugging
			log_message('debug', 'Save password - Customer ID: ' . $customer_id . ', Password length: ' . strlen($password));
			
			if(empty($customer_id) || empty($password)) {
				echo json_encode(array('success' => false, 'message' => 'Customer ID and Password are required.'));
				exit;
			}
			
			// Encrypt password using MD5 (same as login)
			$encrypted_password = md5($password);
			
			// Update password in database
			$result = $this->my_model->update_customer_password($customer_id, $encrypted_password);
			
			if($result !== false) {
				echo json_encode(array('success' => true, 'message' => 'Password saved successfully.'));
			} else {
				$error_msg = 'Failed to save password. Please check the logs for details.';
				log_message('error', 'Password save returned false for customer ID: ' . $customer_id);
				echo json_encode(array('success' => false, 'message' => $error_msg));
			}
			exit;
			
		} catch(Exception $e) {
			log_message('error', 'Save Password Exception: ' . $e->getMessage());
			log_message('error', 'Save Password Trace: ' . $e->getTraceAsString());
			log_message('error', 'Save Password File: ' . $e->getFile() . ' Line: ' . $e->getLine());
			
			echo json_encode(array('success' => false, 'message' => 'An error occurred: ' . $e->getMessage()));
			exit;
		} catch(Error $e) {
			log_message('error', 'Save Password PHP Error: ' . $e->getMessage());
			log_message('error', 'Save Password Trace: ' . $e->getTraceAsString());
			
			echo json_encode(array('success' => false, 'message' => 'A PHP error occurred: ' . $e->getMessage()));
			exit;
		}
	}
	
	/** Add Function **/
	public function add(){ 
		
		$data['msg'] ='';
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
	 	$data['billing'] = $this->my_model->get_billingplans();
		$data['zone'] = $this->my_model->get_zone();
		$data['classification'] = $this->my_model->get_classification();
		$data['customer_type'] = $this->my_model->get_customer_type();

		 if($this->input->post('add') != ''){
            //echo'<pre>';print_r($_POST);
			
				$config = array(
					'upload_path'   => './images/upload',
					'allowed_types' => 'gif|jpg|png',
					'max_size'      => '10000',
					'max_width'     => '1024',
					'max_height'    => '768',
					'encrypt_name'  => false,
				);
				$this->load->library('upload', $config);
						
				$result = $this->my_model->add_record();
				//print_r($_POST);
				//exit;
				if($result){
					if ($this->upload->do_upload('userfile')) 
					{	
						$upload_data = $this->upload->data();
						$data_ary = array(
							'title'     => $upload_data['client_name'],
							'file'      => $upload_data['file_name'],
							'width'     => $upload_data['image_width'],
							'height'    => $upload_data['image_height'],
							'type'      => $upload_data['image_type'],
							'size'      => $upload_data['file_size'],
							'date'      => date('Y-m-d'),
							'customerid'=> $result
						);
						$this->load->database();
						$this->db->insert('tbl_userphotoupload', $data_ary);
					}					
					$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
					redirect($this->listPage_redirect);
				}else{
					$data['msg'] = "Not Inserted...";
				}
			}
		//$header['host'] = $this->comm_model->get_single_record();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$data['account'] = $this->my_model->accountgroup();
		$data['customer_id'] = customer_id_generate();
		
		//print_r($data['customer_id']);exit;
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->addPage,$data);
	}
	public function edit($id){
		
		$data['record'] = $this->my_model->get_single_record($id);
		$data['billing'] = $this->my_model->get_billingplans();
		$data['image'] = $this->my_model->get_customerphoto($id);
		$data['classification'] = $this->my_model->get_classification();
		$data['customer_type'] = $this->my_model->get_customer_type();

		$withimage = 1;
		if(!$data['image']){
			$data['image']['file']='a.png';
			$withimage = 0;
		}
		$data['zone'] = $this->my_model->get_zone();
		$data['msg'] ='';
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		//echo'<pre>';print_r($data['record']);exit;
		if($this->input->post('edit') != ''){
			$result = $this->my_model->update_record($id);
			$config = array(
				'upload_path'   => './images/upload',
				'allowed_types' => 'gif|jpg|png',
				'max_size'      => '10000',
				'max_width'     => '1024',
				'max_height'    => '768',
				'encrypt_name'  => false,
			);
			$this->load->library('upload', $config);
			if($result){
				if ($this->upload->do_upload('userfile')) 
				{	
					$upload_data = $this->upload->data();

					$data_ary = array(
						'title'     => $upload_data['client_name'],
						'file'      => $upload_data['file_name'],
						'width'     => $upload_data['image_width'],
						'height'    => $upload_data['image_height'],
						'type'      => $upload_data['image_type'],
						'size'      => $upload_data['file_size'],
						'date'      => date('Y-m-d')
						//'customerid'=> $id
					);
					$this->load->database();
					if($withimage==0){
						$data_ary1 = array(
							
							'customerid'=> $id
						);
						$data_ary = array_merge($data_ary,$data_ary1);
						$this->db->insert('tbl_userphotoupload', $data_ary);
					}else{
						
						$this->db->where('customerid', $id);
						$this->db->update('tbl_userphotoupload', $data_ary);
					}

					
						
				}
				//echo'<pre>';print_r($result);exit;
				$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Updated...";
			}
		}
		//$header['host'] = $this->comm_model->get_single_record();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$data['account'] = $this->my_model->accountgroup();
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->editPage,$data);

	}
	public function adminconfiguration()
	{
		//$data['record'] = $this->my_model->get_single_record($id);
		//$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_adminrecord();
		//print_r($data['record']);
		//$header['host'] = $this->comm_model->get_single_record();						
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$this->head);
		//$this->load->view($this->viewPage,$data);
		$this->load->view(adminconfiguration,$data);
	}
	public function editadminconfiguration($adminid)
	{
		$data['record'] = $this->my_model->get_adminrecord_edit($adminid);
		//print_r($data['record']);
		//$header['host'] = $this->comm_model->get_single_record();						
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$this->head);
		//$this->load->view($this->viewPage,$data);
		$this->load->view(adminconfiguration_edit,$data);
	}
	public function adminconfigurationupdate()
	{
		// Ensure database connection is available
		if (!isset($this->db) || !is_object($this->db)) {
			$this->load->database();
		}
		$this->load->library('image_lib');
		$adminid						= $this->input->post('adminid');
		$data['name'] 					= $this->input->post('name');
		$data['email'] 					= $this->input->post('email');
		$data['established'] 			= date ("Y-m-d", strtotime($this->input->post('established')));
		$data['contact1']	 			= $this->input->post('contact1');
		$data['contactperson'] 			= $this->input->post('contactperson');
		$data['contactpersonphone'] 	= $this->input->post('contactperson');
		$data['website']			 	= $this->input->post('website');
		$data['address1']			 	= $this->input->post('address1');
		$data['about']				 	= $this->input->post('about');
		$result							= $this->my_model->get_adminrecord_update($adminid,$data);
						$config = array(
										'upload_path'   => './images/logo',
										'allowed_types' => 'gif|jpg|png',
										/*'max_size'      => '10000',
										'max_width'     => '1024',
										'max_height'    => '768',*/
										'encrypt_name'  => false,
									   );
						$this->load->library('upload', $config);
						$this->image_lib->resize();
			if($result){
						if ($this->upload->do_upload('userfile')) 
							{	
							   	$upload_data = $this->upload->data();
									$data_ary = array(
														'title'     => $upload_data['client_name'],
														'file'      => $upload_data['file_name'],
														'width'     => $upload_data['image_width'],
														'height'    => $upload_data['image_height'],
														'type'      => $upload_data['image_type'],
														'size'      => $upload_data['file_size'],
														'date'      => date('Y-m-d')
													  );
														$this->db->where('adminid', $adminid);
														$this->db->update('tbl_adminlogo', $data_ary);
							}
		
						$data['record'] = $this->my_model->get_adminrecord_edit($adminid);
						$this->load->view($this->headerPage,$this->head);
						$this->load->view(adminconfiguration,$data);
					}
			else
			{
				$data['msg'] = "Not Updated...";
			}	
	}
	/** View Function **/
	public function view($id){ 
		$data['record'] = $this->my_model->get_single_record($id);
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['image'] = $this->my_model->get_customerphoto($id);
		//print_r($data['record']);
		//$header['host'] = $this->comm_model->get_single_record();						
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->viewPage,$data);
	}
	
	/** View Function for AJAX/Modal **/
	public function view_ajax($id){ 
		$data['record'] = $this->my_model->get_single_record($id);
		$data['image'] = $this->my_model->get_customerphoto($id);
		// Load only the view content without header/footer for modal
		$this->load->view('addcustomer_view_modal',$data);
	}
	
	/** Edit Function for AJAX/Modal **/
	public function edit_ajax($id){ 
		$data['record'] = $this->my_model->get_single_record($id);
		$data['billing'] = $this->my_model->get_billingplans();
		$data['image'] = $this->my_model->get_customerphoto($id);
		$data['classification'] = $this->my_model->get_classification();
		$data['customer_type'] = $this->my_model->get_customer_type();
		$data['zone'] = $this->my_model->get_zone();
		
		$withimage = 1;
		if(!$data['image']){
			$data['image']['file']='a.png';
			$withimage = 0;
		}
		$data['withimage'] = $withimage;
		
		// Load only the edit form content without header/footer for modal
		$this->load->view('addcustomer_edit_modal',$data);
	}
	
	/** Update Function for AJAX/Modal **/
	public function update_ajax(){
		// Clear any previous output
		if(ob_get_level()) {
			ob_clean();
		}
		
		// Set JSON header first
		header('Content-Type: application/json');
		
		try {
			$id = $this->input->post('id');
			if(empty($id)) {
				echo json_encode(array('success' => false, 'message' => 'Customer ID is required.'));
				exit;
			}
			
			// Get the record to check if image exists
			$record = $this->my_model->get_single_record($id);
			$image = $this->my_model->get_customerphoto($id);
			$withimage = 1;
			if(!$image){
				$withimage = 0;
			}
			
			// Update the record
			$result = $this->my_model->update_record($id);
			
			if($result){
				// Handle file upload if provided
				$config = array(
					'upload_path'   => './images/upload',
					'allowed_types' => 'gif|jpg|png',
					'max_size'      => '10000',
					'max_width'     => '1024',
					'max_height'    => '768',
					'encrypt_name'  => false,
				);
				$this->load->library('upload', $config);
				
				if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] == 0) {
					if ($this->upload->do_upload('userfile')) {
						$upload_data = $this->upload->data();
						$data_ary = array(
							'title'     => $upload_data['client_name'],
							'file'      => $upload_data['file_name'],
							'width'     => $upload_data['image_width'],
							'height'    => $upload_data['image_height'],
							'type'      => $upload_data['image_type'],
							'size'      => $upload_data['file_size'],
							'date'      => date('Y-m-d')
						);
						$this->load->database();
						if($withimage==0){
							$data_ary['customerid'] = $id;
							$this->db->insert('tbl_userphotoupload', $data_ary);
						}else{
							$this->db->where('customerid', $id);
							$this->db->update('tbl_userphotoupload', $data_ary);
						}
					}
				}
				
				echo json_encode(array('success' => true, 'message' => 'Customer updated successfully.'));
			} else {
				echo json_encode(array('success' => false, 'message' => 'Failed to update customer.'));
			}
			exit;
			
		} catch(Exception $e) {
			log_message('error', 'Update Customer Exception: ' . $e->getMessage());
			echo json_encode(array('success' => false, 'message' => 'An error occurred: ' . $e->getMessage()));
			exit;
		} catch(Error $e) {
			log_message('error', 'Update Customer PHP Error: ' . $e->getMessage());
			echo json_encode(array('success' => false, 'message' => 'A PHP error occurred: ' . $e->getMessage()));
			exit;
		}
	}
	public function Search()
	{ 
		$data['record'] = $this->my_model->get_all_records();
		//echo'<pre>';print_r($data['record']);exit;
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		//$data['customer_type'] = $this->my_model->get_all_customer_type();
		//$data['zone'] = $this->my_model->getzone(); 
		//$data['fromdate'] = $this->my_model->getfromdate(); 
		 $data['zone'] = $this->my_model->get_zone();
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->searchPage,$data);
	}
	public function paidsearch()
	{ 
		 $this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		 $data['zone'] = $this->my_model->get_zone();
		 $this->load->view($this->headerPage,$this->head);
		 $this->load->view($this->paidsearchPage,$data);
	}
	
	public function unpaidsearch()
	{ 
		
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		
		//$data['record'] = $this->my_model->get_single_record();
		$data['zone'] = $this->my_model->get_zone();
		$data['billingperiod'] = $this->billingperiod_model->get_month_billingperiod_records();
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->unpaidsearchPage,$data);
	}
	public function income_reportsearch($id)
	{ 
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		//$data['customer_type'] = $this->my_model->get_all_customer_type();
		//$data['zone'] = $this->my_model->getzone(); 
		//$data['fromdate'] = $this->my_model->getfromdate(); 
		//$data['todate'] = $this->my_model->gettodate(); 
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->incomesearchPage,$data);
	}
	public function meterincome_reportsearch($id){ 
		$data['record'] = $this->my_model->get_single_record($id);
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		//$data['customer_type'] = $this->my_model->get_all_customer_type();
		//$data['zone'] = $this->my_model->getzone(); 
		//$data['fromdate'] = $this->my_model->getfromdate(); 
		//$data['todate'] = $this->my_model->gettodate(); 
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->incomesearchPage,$data);
	}
	public function monthlysearch($id='')
	{ 
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->monthlysearchPage,$data);
	}
	public function metersearch()
	{ 
		//$data['record'] = $this->my_model->get_single_record();
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		//$data['customer_type'] = $this->my_model->get_all_customer_type();
		$data['zone'] = $this->my_model->get_zone();
		//$data['fromdate'] = $this->my_model->getfromdate(); 
		//$data['todate'] = $this->my_model->gettodate(); 
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->metersearchPage,$data);
	}
	public function technicalsearch($id){ 
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		//$data['customer_type'] = $this->my_model->get_all_customer_type();
		//$data['zone'] = $this->my_model->getzone(); 
		//$data['fromdate'] = $this->my_model->getfromdate(); 
		//$data['todate'] = $this->my_model->gettodate(); 
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->technicalsearchPage,$data);
	}
	
	public function get_customer_invoice($id){ 
		$data['record'] = $this->my_model->get_single_record($id);
		$data['address'] = $this->my_model->get_address();
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->customer_invoice,$data);
	}
	
	public function generatemetercustomer_search()
	{ 
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_zone();
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->customersearchPage,$data);
	}
	public function addcustomer_generatemetercustomer_search(){		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('mobile1'));exit;
			if($this->input->post('zone') =='' && $this->input->post('mobile1') ==''){
				$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
				echo $selBox;
			}else{
			
				$zone = $this->input->post('zone');
				$mobile1 = $this->input->post('mobile1');
				
				$data['record'] = $this->my_model->get_addcustomer_meter_records($zone,$mobile1);
                $this->load->view($this->addcustomer_customerajax,$data);
				
			}		
	}
	
	public function getaddcustomerssearch(){		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('zone'));exit;
			if($this->input->post('customer_type') ==''){
				$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
				echo $selBox;
			}
			if($this->input->post('customer_type') !=''){
				$customer_type = $this->input->post('customer_type');
				$zone = $this->input->post('zone');
				$fromdate = $this->input->post('fromdate');
				$todate = $this->input->post('todate');
				
				//$data['record'] = $this->my_model->get_addcustomer_records($customer_type,$zone,$fromdate,$todate);
				$data['record'] = $this->my_model->get_addcustomer_records($customer_type,$zone);

				$this->load->view($this->addcustomerajax,$data);
			}
			
	}
	public function getaddcustomersmetersearch()
	{		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('zone'));exit;
			if($this->input->post('customer_id') =='' && $this->input->post('zone') =='' && $this->input->post('fromdate') =='' && $this->input->post('todate') =='')
			{
				$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
				echo $selBox;
			}
			if($this->input->post('customer_id') !='' || $this->input->post('zone') !='' || $this->input->post('fromdate') !='' || $this->input->post('todate') !='')
			{
				$customer_id = $this->input->post('customer_id');
				$zone = $this->input->post('zone');
				$fromdate = $this->input->post('fromdate');	
				$todate = $this->input->post('todate');
				
				$data['record'] = $this->my_model->get_addcustomer_payment_records($customer_id,$zone,$fromdate,$todate);
              //echo'<pre>';print_r($data['record']);exit;
				$this->load->view($this->addcustomer_meterajax,$data);
			}		
	}
	public function getaddcustomerstechnicalsearch(){		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('zone'));exit;
			if($this->input->post('customer_type') ==''){
				$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
				echo $selBox;
			}
			if($this->input->post('customer_type') !=''){
				$customer_type = $this->input->post('customer_type');
				
				
				
				$data['record'] = $this->my_model->get_addcustomer_technicalrecords($customer_type);

				$this->load->view($this->addcustomer_technicalajax,$data);
			}		
	}	
	public function getaddcustomersmonthlysearch(){		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($_POST);exit;
			if($this->input->post('customer_id') =='' && $this->input->post('mobile1') =='' && $this->input->post('fromdate') =='' && $this->input->post('todate') ==''){
				$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
				echo $selBox;
			
			}else{
				$customer_id = $this->input->post('customer_id');
				$mobile1 = $this->input->post('mobile1');
				$fromdate = $this->input->post('fromdate');
				$todate = $this->input->post('todate');
			
				$data['record'] = $this->my_model->get_addcustomer_monthly_records($customer_id,$mobile1,$fromdate,$todate);
         //echo'<pre>';print_r($data['record']);exit;
				$this->load->view($this->addcustomer_monthlyajax,$data);
			}		
	}	
	
	public function getaddcustomersincome_reportsearch()
	{		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('zone'));exit;
			if($this->input->post('fromdate') =='' && $this->input->post('todate')==''){
				$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
				echo $selBox;
			}
			if($this->input->post('fromdate') !='' || $this->input->post('todate')!=''){
				
				$fromdate = $this->input->post('fromdate');
				$todate = $this->input->post('todate');
				
				
				$data['record'] = $this->my_model->get_addcustomer_income_records($fromdate,$todate);
				//$data['record'] = $this->my_model->get_addcustomer_meter_income_records($fromdate,$todate);

				$this->load->view($this->addcustomer_incomeajax,$data);
			}		
	}	
	
	
	public function getaddcustomersmeterincome_reportsearch()
	{		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('fromdate'));exit;
			if($this->input->post('fromdate') =='' && $this->input->post('todate')==''){
				$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
				echo $selBox;
			}
			if($this->input->post('fromdate') !='' || $this->input->post('todate')!=''){
				
				 $fromdate = $this->input->post('fromdate');
				 $todate = $this->input->post('todate');
				
				//$data['record'] = $this->my_model->get_addcustomer_income_records($fromdate,$todate);
				$data['record'] = $this->my_model->get_addcustomer_meter_income_records($fromdate,$todate);
				$this->load->view($this->addcustomer_monthly_incomeajax,$data);
			}	
	}
	public function getaddcustomerspaidsearch()
	{		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('zone'));exit;
			if($this->input->post('customer_type') !=''){
				if($this->input->post('customer_type') =='' && $this->input->post('zone') =='' && $this->input->post('fromdate') =='' && $this->input->post('todate') ==''){
					$selBox ='<h6><span style="color:red">Dear Admin Please select customer type option to search feilds</h6>' ;
					echo $selBox;
				}
				if($this->input->post('customer_type') !='' || $this->input->post('zone') !='' || $this->input->post('fromdate') !='' || $this->input->post('todate') !=''){
					$customer_type = $this->input->post('customer_type');
					$zone = $this->input->post('zone');
					$fromdate = $this->input->post('fromdate');
					$todate = $this->input->post('todate');
					
					
					$data['record'] = $this->my_model->get_addcustomer_paid_records($customer_type,$zone,$fromdate,$todate);
					//echo'<pre>';print_r($data['record']);exit;
					$this->load->view($this->addcustomerpaidajax,$data);
				}		
			}else{
				$selBox ='<h6><span style="color:red">Dear Admin Please select customer type option to search feilds</h6>' ;
				echo $selBox;				
			}
	}	
	
	public function getaddcustomersunpaidsearch(){		//*****  Add Search records  *****//
		$data['msg'] ='';
		//echo '<pre>'; print_r($this->input->post('zone'));exit;
		//if($this->input->post('fromdate') =='' || $this->input->post('todate') ==''){
		//	$selBox ='<h6><span style="color:red">Please Enter From-Date and To-Date</h6>' ;
		//	echo $selBox;
		//}else{
			$membership_status = $this->input->post('membership_status');

			$zone = $this->input->post('zone');
			if($this->input->post('billingperiod')!='all'){
				$billingperiod = explode(' ',$this->input->post('billingperiod'));
				$billingmonth = $billingperiod[0];
				$billingyear = $billingperiod[1];
			}else{
				$billingmonth = '';
				$billingyear = '';
			}
			
			//$todate = $this->input->post('todate');
			$data['record'] = $this->my_model->get_addcustomer_unpaid_records($membership_status,$zone,$billingmonth,$billingyear);
			$this->load->view($this->addcustomerunpaidajax,$data);
		//}
				
	}	

	public function get_customer_id_generate($class_id,$zone_id){
		
		echo customer_id_generate($class_id,$zone_id);
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
	
	public function Status($id,$status){
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
	public function fileDownloadajaxcustomer($customer_type,$zone,$fromdate,$todate)
	{
		$this->load->database();
		$this->db->select("
							tbl_addcustomer.customer_id,first_name,middle_name,last_name,gender,DOB, place_of_birth,mobile1,mobile2,email_id,address, line_number,
							(case when (customer_type = 'metercustomer') THEN 'Meter Customer' when (customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
							,(case when (status = 1) THEN 'Active' when (status = 0) THEN 'Un-Active' END) as status
		                 ");
		$this->db->from('tbl_addcustomer');
		if($customer_type !='0'){
		$this->db->where('customer_type',$customer_type);
		}if($zone !='0'){
		$this->db->where('zone',$zone);	  
		}
		if($fromdate !='0'){
		$this->db->where('create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}if($todate !='0'){
		$this->db->where('create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}
		$query = $this->db->get();
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}	
	public function fileDownloadajax($id,$zone,$fromdate,$todate)
	{
		$this->load->database();
		$this->db->select("
		tbl_addcustomer.customer_id,tbl_addcustomer.first_name,tbl_addcustomer.gender,tbl_addcustomer.mobile1,tbl_addcustomer.mobile2,tbl_addcustomer.email_id,tbl_addcustomer.address,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,tbl_addmetercustomer.oldmeter,tbl_addmetercustomer.aftermeter,tbl_addmetercustomer.amount,tbl_addmetercustomer.balance,tbl_addmetercustomer.pay_amount,tbl_addmetercustomer.total
		,(case when (tbl_addmetercustomer.status = 1) THEN 'Paid' when (tbl_addmetercustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_addcustomer');
		$this->db->join('tbl_addmetercustomer', 'tbl_addmetercustomer.customer_id = tbl_addcustomer.customer_id');
		if($id !='0'){
		$this->db->where('tbl_addcustomer.customer_id',$id);
		}if($zone !='0'){
		$this->db->where('tbl_addcustomer.zone',$zone);	  
		}//if($fromdate !='0'){
		//$this->db->where('tbl_addcustomer.create_date_time',date('Y-m-d',strtotime($fromdate)));	  
		//}if($todate !='0'){
		//$this->db->where('tbl_addcustomer.create_date_time',date('Y-m-d',strtotime($todate)));	  
		//}
		//$this->db->group_by('tbl_addmetercustomer.id');	
		$this->db->where('tbl_addcustomer.customer_type','metercustomer');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}
	public function fileDownloadMonthlyMeterprint($id,$zone)
	{
		$this->load->database();
		$this->db->select("
		tbl_addcustomer.customer_id,tbl_addcustomer.first_name,tbl_addcustomer.gender,tbl_addcustomer.mobile1,tbl_addcustomer.mobile2,tbl_addcustomer.email_id,tbl_addcustomer.address,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,tbl_addmetercustomer.oldmeter,tbl_addmetercustomer.aftermeter,tbl_addmetercustomer.amount,tbl_addmetercustomer.balance,tbl_addmetercustomer.pay_amount,tbl_addmetercustomer.total
		,(case when (tbl_addmetercustomer.status = 1) THEN 'Paid' when (tbl_addmetercustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_addcustomer');
		$this->db->join('tbl_addmetercustomer', 'tbl_addmetercustomer.customer_id = tbl_addcustomer.customer_id');
		if($id !='0')
		{
		$this->db->where('tbl_addcustomer.customer_id',$id);
		}if($zone !='0'){
		$this->db->where('tbl_addcustomer.zone',$zone);	  
		}
		$this->db->group_by('tbl_addmetercustomer.id');	
		$query = $this->db->get();
		$data['record'] = $query->result_array();
		
		$data['address'] = $this->my_model->get_address();
		//$data['result'] = array_merge($record,$address);
		
		//$this->load->view($this->headerPage);
		$this->load->view($this->printpdfMeterPage,$data);
	}

	public function fileDownloadMonthly($id)
	{
		$this->load->database();
		$this->db->select("
		tbl_addcustomer.customer_id,tbl_addcustomer.first_name,tbl_addcustomer.gender,tbl_addcustomer.mobile1,tbl_addcustomer.mobile2,tbl_addcustomer.email_id,tbl_addcustomer.address,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,tbl_monthlycustomer.amount,tbl_monthlycustomer.balance,tbl_monthlycustomer.total
		,(case when (tbl_monthlycustomer.status = 1) THEN 'Paid' when (tbl_monthlycustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_addcustomer');
		$this->db->join('tbl_monthlycustomer', 'tbl_monthlycustomer.customer_id = tbl_addcustomer.customer_id');
		if($id !='0'){
		$this->db->where('tbl_addcustomer.customer_id',$id);
		}
		$this->db->group_by('tbl_monthlycustomer.id');	
		$this->db->order_by('tbl_monthlycustomer.id','desc');	
		$query = $this->db->get();
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}	
	
	public function fileDownloadMonthlyprint($id)
	{
		$this->load->database();
		$this->db->select("
		tbl_addcustomer.customer_id,tbl_addcustomer.first_name,tbl_addcustomer.gender,tbl_addcustomer.mobile1,tbl_addcustomer.mobile2,tbl_addcustomer.email_id,tbl_addcustomer.address,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,tbl_monthlycustomer.amount,tbl_monthlycustomer.balance,tbl_monthlycustomer.total
		,(case when (tbl_monthlycustomer.status = 1) THEN 'Paid' when (tbl_monthlycustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_addcustomer');
		$this->db->join('tbl_monthlycustomer', 'tbl_monthlycustomer.customer_id = tbl_addcustomer.customer_id');
		if($id !='0'){
		$this->db->where('tbl_addcustomer.customer_id',$id);
		}
		$this->db->group_by('tbl_monthlycustomer.id');	
		$this->db->order_by('tbl_monthlycustomer.id','desc');	
		$query = $this->db->get();
		$data['record'] = $query->result_array();
	    $data['address'] = $this->my_model->get_address();
		//$this->load->view($this->headerPage);
		$this->load->view($this->printpdfPage,$data);
	}
	
	public function fileDownloadCustomer($zone,$mobile)
	{
		$this->load->database();
		$this->db->select("customer_id,first_name,gender,mobile1,mobile2,email_id,address,
						(case when (customer_type = 'metercustomer') THEN 'Meter Customer' when (customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
						");
		$this->db->from('tbl_addcustomer');
		if($mobile !='0'){
		$this->db->where('( `mobile1` = "'.$mobile.'" OR `mobile2` = "'.$mobile.'" )');
		}if($zone !='0'){
		$this->db->where('zone',$zone);	  
		}
		$query = $this->db->get();
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}
	public function filePrintCustomer($zone,$mobile)
	{
		$this->load->database();
		$this->db->select("customer_id,first_name,gender,mobile1,mobile2,email_id,address,
		(case when (customer_type = 'metercustomer') THEN 'Meter Customer' when (customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		");
		$this->db->from('tbl_addcustomer');
		if($mobile !='0'){
		$this->db->where('( `mobile1` = "'.$mobile.'" OR `mobile2` = "'.$mobile.'" )');
		}if($zone !='0'){
		$this->db->where('zone',$zone);	  
		}
		$query = $this->db->get();
		$data['record'] = $query->result_array();
	    $data['address'] = $this->my_model->get_address();
		//$this->load->view($this->headerPage);
		$this->load->view($this->printpdfMonthPage,$data);
	}
	public function fileDownloadbetween($fromdate,$todate)
	{
		$this->load->database();
		$this->db->select("
		tbl_addcustomer.customer_id
		,tbl_addcustomer.first_name,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,tbl_addmetercustomer.create_date_time,tbl_addmetercustomer.amount
		,(case when (tbl_addmetercustomer.status = 1) THEN 'Paid' when (tbl_addmetercustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_addcustomer');
		$this->db->join('tbl_addmetercustomer', 'tbl_addmetercustomer.customer_id = tbl_addcustomer.customer_id');
		if($fromdate !='0')
		{
		$this->db->where('tbl_addcustomer.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}
		if($todate !='0')
		{
		$this->db->where('tbl_addcustomer.create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}
		$this->db->where('tbl_addmetercustomer.status','1');	  
		$this->db->group_by('tbl_addmetercustomer.id');	
		$query = $this->db->get();
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}
		public function filePrintbetween($fromdate,$todate)
	{
		$this->load->database();
		$this->db->select("
		tbl_addcustomer.customer_id
		,tbl_addcustomer.first_name,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,tbl_addmetercustomer.create_date_time,tbl_addmetercustomer.amount
		,(case when (tbl_addmetercustomer.status = 1) THEN 'Paid' when (tbl_addmetercustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_addcustomer');
		$this->db->join('tbl_addmetercustomer', 'tbl_addmetercustomer.customer_id = tbl_addcustomer.customer_id');
		if($fromdate !='0')
		{
		$this->db->where('tbl_addcustomer.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}
		if($todate !='0')
		{
		$this->db->where('tbl_addcustomer.create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}
		$this->db->where('tbl_addmetercustomer.status','1');	  
		$this->db->group_by('tbl_addmetercustomer.id');	
		$query = $this->db->get();
		$data['record'] = $query->result_array();
		$data['address'] = $this->my_model->get_address();
		//$data['result'] = array_merge($record,$address);
		$this->load->view($this->printpdfIncomeMeterPage,$data);
	}

	
	
	public function fileDownloadmonthlyajax($fromdate,$todate)
	{
		$this->load->database();
		$this->db->select("
		               tbl_addcustomer.customer_id
		                 ,tbl_addcustomer.first_name,
		                 (case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		                 ,tbl_monthlycustomer.create_date_time,tbl_monthlycustomer.amount
	                 	,(case when (tbl_monthlycustomer.status = 1) THEN 'Paid' when (tbl_monthlycustomer.status = 0) THEN 'Un-Paid' END) as status
		            ");
		$this->db->from('tbl_addcustomer');
		$this->db->join('tbl_monthlycustomer', 'tbl_monthlycustomer.customer_id = tbl_addcustomer.customer_id');
		if($fromdate !='0')
		{
		$this->db->where('tbl_addcustomer.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}
		if($todate !='0')
		{
		$this->db->where('tbl_addcustomer.create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}
		$this->db->where('tbl_monthlycustomer.status','1');	  
		$this->db->group_by('tbl_monthlycustomer.id');	
		$query = $this->db->get();
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}

	public function filePrintmonthlyajax($fromdate,$todate)
	{
		$this->load->database();
		$this->db->select("
		tbl_addcustomer.customer_id
		,tbl_addcustomer.first_name,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,tbl_monthlycustomer.create_date_time,tbl_monthlycustomer.amount
		,(case when (tbl_monthlycustomer.status = 1) THEN 'Paid' when (tbl_monthlycustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_addcustomer');
		$this->db->join('tbl_monthlycustomer', 'tbl_monthlycustomer.customer_id = tbl_addcustomer.customer_id');
		if($fromdate !='0')
		{
		$this->db->where('tbl_addcustomer.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}
		if($todate !='0'){
		$this->db->where('tbl_addcustomer.create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}
		$this->db->where('tbl_monthlycustomer.status','1');	  
		$this->db->group_by('tbl_monthlycustomer.id');	
		$query = $this->db->get();
		$data['record'] = $query->result_array();
		$data['address'] = $this->my_model->get_address();
		//$data['result'] = array_merge($record,$address);
		$this->load->view($this->printpdfIncomeMonthPage,$data);
	}
	
	public function fileDownloadajaxtechnicalsearch($customer_type){
		$this->load->database();
		$this->db->select("
		tbl_addcustomer.customer_id
		,tbl_addcustomer.first_name,tbl_addcustomer.gender,tbl_addcustomer.mobile1,tbl_addcustomer.mobile2,tbl_addcustomer.email_id,tbl_addcustomer.address,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,(case when (tbl_technical.status = 1) THEN 'Solve' when (tbl_technical.status = 0) THEN 'Un-Solve' END) as status
		");
		$this->db->from('tbl_addcustomer');	
		$this->db->join('tbl_technical', 'tbl_technical.customer_id = tbl_addcustomer.customer_id');
		$this->db->group_by('tbl_technical.id');	
		if($customer_type !=''){
			$this->db->where('tbl_addcustomer.customer_type',$customer_type);
		}
		$query = $this->db->get();
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}
	public function fileDownloadpaidSerch($customer_type,$zone,$fromdate,$todate)
	{
		$this->load->database();
		if($customer_type =='monthlycustomer'){
		$this->db->select("
		tbl_addcustomer.customer_id
		,tbl_addcustomer.first_name,tbl_addcustomer.gender,tbl_addcustomer.mobile1,tbl_addcustomer.mobile2,tbl_addcustomer.email_id,tbl_addcustomer.address,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,(case when (tbl_monthlycustomer.status = 1) THEN 'Paid' when (tbl_monthlycustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_monthlycustomer');	
		$this->db->join('tbl_addcustomer', 'tbl_addcustomer.customer_id = tbl_monthlycustomer.customer_id');
		if($fromdate !='0'){
		$this->db->where('tbl_monthlycustomer.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}if($todate !='0'){
		$this->db->where('tbl_monthlycustomer.create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}
		if($customer_type !='0'){
			$this->db->where('tbl_addcustomer.customer_type',$customer_type);
		}	
		if($zone !='0'){
			$this->db->where('tbl_addcustomer.zone',$zone);
		}			
		}
		if($customer_type =='metercustomer'){
		$this->db->select("
		tbl_addcustomer.customer_id
		,tbl_addcustomer.first_name,tbl_addcustomer.gender,tbl_addcustomer.mobile1,tbl_addcustomer.mobile2,tbl_addcustomer.email_id,tbl_addcustomer.address,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,(case when (tbl_addmetercustomer.status = 1) THEN 'Paid' when (tbl_addmetercustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_addmetercustomer');	
		$this->db->join('tbl_addcustomer', 'tbl_addcustomer.customer_id = tbl_addmetercustomer.customer_id');
		if($fromdate !='0'){
		$this->db->where('tbl_addmetercustomer.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}if($todate !='0'){
		$this->db->where('tbl_addmetercustomer.create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}
		if($customer_type !='0'){
			$this->db->where('tbl_addcustomer.customer_type',$customer_type);
		}	
		if($zone !='0'){
			$this->db->where('tbl_addcustomer.zone',$zone);
		}				  
		$this->db->group_by('tbl_addmetercustomer.id');	
		}
		$query = $this->db->get();
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}
	
		public function filePrintpaidSerch($customer_type,$zone,$fromdate,$todate)
	{
		$this->load->database();
		if($customer_type =='monthlycustomer')
		{
		$this->db->select("
		tbl_addcustomer.customer_id
		,tbl_addcustomer.first_name,tbl_addcustomer.gender,tbl_addcustomer.mobile1,tbl_addcustomer.mobile2,tbl_addcustomer.email_id,tbl_addcustomer.address,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,(case when (tbl_monthlycustomer.status = 1) THEN 'Paid' when (tbl_monthlycustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_monthlycustomer');	
		$this->db->join('tbl_addcustomer', 'tbl_addcustomer.customer_id = tbl_monthlycustomer.customer_id');
		if($fromdate !='0'){
		$this->db->where('tbl_monthlycustomer.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}if($todate !='0'){
		$this->db->where('tbl_monthlycustomer.create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}
		if($customer_type !='0'){
			$this->db->where('tbl_addcustomer.customer_type',$customer_type);
		}	
		if($zone !='0'){
			$this->db->where('tbl_addcustomer.zone',$zone);
		}			
		}
		if($customer_type =='metercustomer'){
		$this->db->select("
		tbl_addcustomer.customer_id
		,tbl_addcustomer.first_name,tbl_addcustomer.gender,tbl_addcustomer.mobile1,tbl_addcustomer.mobile2,tbl_addcustomer.email_id,tbl_addcustomer.address,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,(case when (tbl_addmetercustomer.status = 1) THEN 'Paid' when (tbl_addmetercustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_addmetercustomer');	
		$this->db->join('tbl_addcustomer', 'tbl_addcustomer.customer_id = tbl_addmetercustomer.customer_id');
		if($fromdate !='0'){
		$this->db->where('tbl_addmetercustomer.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}if($todate !='0'){
		$this->db->where('tbl_addmetercustomer.create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}
		if($customer_type !='0'){
			$this->db->where('tbl_addcustomer.customer_type',$customer_type);
		}	
		if($zone !='0'){
			$this->db->where('tbl_addcustomer.zone',$zone);
		}				  
		$this->db->group_by('tbl_addmetercustomer.id');	
		}
		$query = $this->db->get();
		$data['record'] = $query->result_array();
		$data['address'] = $this->my_model->get_address();
		//$data['result'] = array_merge($record,$address);
		$this->load->view($this->paidPrintCustomerpage,$data);
	}

	
	public function fileDownloadunpaidSearch($membership_status='',$zone='',$billingmonth,$billingyear)
	{
		
		$this->load->database();

		$this->db->select($this->table_name.".customer_id,".$this->table_name.".first_name,".$this->table_name.".last_name, ".$this->table_name.".address,".$this->table_name.".meter_number,".$this->table_name.".meter_brand,".$this->table_zone.".zone as zonename,".$this->table_customerreading.".month, ".$this->table_customerreading.".year, ".$this->table_customerreading.".penalty as Amount ");
		$this->db->from($this->table_customerreading);
			
		
		$this->db->join($this->table_meter, $this->table_customerreading.'.customer_id='.$this->table_meter.'.customer_id AND '.$this->table_customerreading.'.month='.$this->table_meter.'.month AND '.$this->table_customerreading.'.year='.$this->table_meter.'.year','left');

		$this->db->join($this->table_name, $this->table_customerreading.'.customer_id='.$this->table_name.'.customer_id','left');
		$this->db->join($this->table_zone, $this->table_name.'.zone='.$this->table_zone.'.id','left');

		
		if($billingmonth !='all' && $billingyear!='all'){
			

			//$billingperiod = explode(' ',$billingperiod);
			//$billingmonth =$billingperiod[0];
			//$billingyear =$billingperiod[1];
			$this->db->where($this->table_customerreading.".month",$billingmonth);
			$this->db->where($this->table_customerreading.".year",$billingyear);
		}
		
		
		if($membership_status !='all'){
			$this->db->where($this->table_name.'.membership_status',$membership_status);
		}	
		if($zone !='all'){
			$this->db->where($this->table_name.'.zone',$zone);
		}	
		$this->db->where($this->table_meter.'.invoice_id IS NULL');
		$this->db->order_by($this->table_name.'.last_name','ASC');
		$this->db->order_by($this->table_name.'.first_name','ASC');
		$query = $this->db->get();
		
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->unpaidsearchPage.'-'.date("d-m-Y").'.csv');
	}

	public function fileDownloadunpaidSerch($customer_type,$zone,$fromdate,$todate)
	{
		$this->load->database();
		if($customer_type =='monthlycustomer'){
		$this->db->select("
		tbl_addcustomer.customer_id
		,tbl_addcustomer.first_name,tbl_addcustomer.gender,tbl_addcustomer.mobile1,tbl_addcustomer.mobile2,tbl_addcustomer.email_id,tbl_addcustomer.address,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,(case when (tbl_monthlycustomer.status = 1) THEN 'Paid' when (tbl_monthlycustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_addcustomer');	
		$this->db->join('tbl_monthlycustomer', 'tbl_monthlycustomer.customer_id = tbl_addcustomer.customer_id');
		if($fromdate !='0'){
		$this->db->where('tbl_addcustomer.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}if($todate !='0'){
		$this->db->where('tbl_addcustomer.create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}
		$this->db->where('tbl_monthlycustomer.status','0');
		$this->db->group_by('tbl_monthlycustomer.id');	
		}
		if($customer_type =='metercustomer'){
		$this->db->select("
		tbl_addcustomer.customer_id
		,tbl_addcustomer.first_name,tbl_addcustomer.gender,tbl_addcustomer.mobile1,tbl_addcustomer.mobile2,tbl_addcustomer.email_id,tbl_addcustomer.address,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,(case when (tbl_addmetercustomer.status = 1) THEN 'Paid' when (tbl_addmetercustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_addcustomer');	
		$this->db->join('tbl_addmetercustomer', 'tbl_addmetercustomer.customer_id = tbl_addcustomer.customer_id');
		if($fromdate !='0'){
		$this->db->where('tbl_addcustomer.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}if($todate !='0'){
		$this->db->where('tbl_addcustomer.create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}
		$this->db->where('tbl_addmetercustomer.status','0');	  
		$this->db->group_by('tbl_addmetercustomer.id');	
		}
		if($customer_type !='0'){
			$this->db->where('tbl_addcustomer.customer_type',$customer_type);
		}	
		if($zone !='0'){
			$this->db->where('tbl_addcustomer.zone',$zone);
		}	
		$query = $this->db->get();  
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}
	
		public function filePrintunpaidSerch($customer_type,$zone,$fromdate,$todate)
	{
		$this->load->database();
		if($customer_type =='monthlycustomer'){
		$this->db->select("
		tbl_addcustomer.customer_id
		,tbl_addcustomer.first_name,tbl_addcustomer.gender,tbl_addcustomer.mobile1,tbl_addcustomer.mobile2,tbl_addcustomer.email_id,tbl_addcustomer.address,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,(case when (tbl_monthlycustomer.status = 1) THEN 'Paid' when (tbl_monthlycustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_addcustomer');	
		$this->db->join('tbl_monthlycustomer', 'tbl_monthlycustomer.customer_id = tbl_addcustomer.customer_id');
		if($fromdate !='0'){
		$this->db->where('tbl_addcustomer.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}if($todate !='0'){
		$this->db->where('tbl_addcustomer.create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}
		$this->db->where('tbl_monthlycustomer.status','0');
		$this->db->group_by('tbl_monthlycustomer.id');	
		}
		if($customer_type =='metercustomer'){
		$this->db->select("
		tbl_addcustomer.customer_id
		,tbl_addcustomer.first_name,tbl_addcustomer.gender,tbl_addcustomer.mobile1,tbl_addcustomer.mobile2,tbl_addcustomer.email_id,tbl_addcustomer.address,
		(case when (tbl_addcustomer.customer_type = 'metercustomer') THEN 'Meter Customer' when (tbl_addcustomer.customer_type = 'monthlycustomer') THEN 'Monthly Customer' END) as customer_type
		,(case when (tbl_addmetercustomer.status = 1) THEN 'Paid' when (tbl_addmetercustomer.status = 0) THEN 'Un-Paid' END) as status
		");
		$this->db->from('tbl_addcustomer');	
		$this->db->join('tbl_addmetercustomer', 'tbl_addmetercustomer.customer_id = tbl_addcustomer.customer_id');
		if($fromdate !='0'){
		$this->db->where('tbl_addcustomer.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}if($todate !='0'){
		$this->db->where('tbl_addcustomer.create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}
		$this->db->where('tbl_addmetercustomer.status','0');	  
		$this->db->group_by('tbl_addmetercustomer.id');	
		}
		if($customer_type !='0'){
			$this->db->where('tbl_addcustomer.customer_type',$customer_type);
		}	
		if($zone !='0'){
			$this->db->where('tbl_addcustomer.zone',$zone);
		}	
		$query = $this->db->get();  
		$data['record'] = $query->result_array();
		$data['address'] = $this->my_model->get_address();
		//$data['result'] = array_merge($record,$address);
		$this->load->view($this->UnpaidPrintCustomerpage,$data);
	}

	
	
	
	public function printInvoice($id){
		$data['msg'] ='';
		$data['record'] = $this->my_model->get_single_record($id);
		$data['address'] = $this->my_model->get_address();
		//echo'<pre>';print_r($data['record']);exit;
		$this->load->view($this->printPage,$data);
	}		

	   
function tables()
{
    $this->load->library('cezpdf');

    $db_data[] = array('name' => 'Jon Doe', 'phone' => '111-222-3333', 'email' => 'jdoe@someplace.com');
    $db_data[] = array('name' => 'Jane Doe', 'phone' => '222-333-4444', 'email' => 'jane.doe@something.com');
    $db_data[] = array('name' => 'Jon Smith', 'phone' => '333-444-5555', 'email' => 'jsmith@someplacepsecial.com');

    $col_names = array(
        'name' => 'Name',
        'phone' => 'Phone Number',
        'email' => 'E-mail Address'
    );

    $this->cezpdf->ezTable($this->table_name, $col_names, 'Contact List', array('width'=>550));
    $this->cezpdf->ezStream();
}
    function check_customer_id($customer_id){
		
		$customer = trim($this->input->post('customer_id'));
		$this->db->where('customer_id', $customer);
        $roll_chk_count = $this->db->count_all_results('tbl_addcustomer');
        if ($roll_chk_count > 0) {
            echo "true";
        } else {
            echo "false";
        }
	}
	function check_customer_email(){
		
		$email = trim($this->input->post('emailid'));
		$this->db->where('email_id', $email);
        $roll_chk_count = $this->db->count_all_results('tbl_addcustomer');
        if ($roll_chk_count > 0) {
            echo "true";
        } else {
            echo "false";
        }
	}
	function check_customer_mobile_1($mobile_1){
		
		$mobile = trim($this->input->post('mobile_1'));
		$this->db->or_where('mobile1', $mobile);
		$this->db->or_where('mobile2', $mobile);
        $roll_chk_count = $this->db->count_all_results('tbl_addcustomer');
        if ($roll_chk_count > 0) {
            echo "true";
        } else {
            echo "false";
        }
	}
	function check_customer_mobile_2($mobile_1){
		
		$mobile = trim($this->input->post('mobile_1'));
		$this->db->or_where('mobile1', $mobile);
		$this->db->or_where('mobile2', $mobile);
        $roll_chk_count = $this->db->count_all_results('tbl_addcustomer');
        if ($roll_chk_count > 0) {
            echo "true";
        } else {
            echo "false";
        }
	}
public function customer_register_form($cusid,$customer) {
		
	//print_r($cusid);	print_r($customer);	
    $record = $this->my_model->get_adminrecord();
	$receiptdata = $this->my_model->get_customer_form($cusid, $customer);
	extract($receiptdata);
	extract($record);
	$todate = date('d-m-Y');
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
		 <table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td align="center" valign="top">
				
						<div class="logoDiv">
							<img id="logo" src="http://dayaxpower.com/Waterbilling/images/logo/$file" height="80">
							<h1>$name</h1>
<h3>$contact1 / $email</h3>
<h3>$address1</h3>
					      <h2>Customer Registration Form</h2>
						</div>
						
				</td>
			</tr>
			
		</table>	
					
		
		<div class="invoice-address" style="border-top: 2px double #888;margin: 20px 0; padding-top: 25px;">
		    <br/>
		    <table border="1" cellspacing="0" cellpadding="15" width="100%">
			  <tbody>	
					<tr>
						<td align="left" valign="top"><span style="font-size:11px;">Customer-Id :</span><span style="font-size:11px; font-weight: bold;"> $customer_id</span></td>
						<td valign="top"><span style="font-size:11px;">Name :</span> <span style="font-size:11px; font-weight: bold;">$first_name $middle_name $last_name</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Gender :</span><span style="font-size:11px; font-weight: bold;"> $gender</span></td>
						<td valign="top"><span style="font-size:11px;">DOB :</span><span style="font-size:11px; font-weight: bold;"> $DOB</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Place of birth :</span> <span style="font-size:11px; font-weight: bold;">$place_of_birth</span> </td>
						<td valign="top"><span style="font-size:11px;">Address :</span><span style="font-size:11px; font-weight: bold;"> $address</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">City :</span> <span style="font-size:11px; font-weight: bold;">$city</span></td>
						<td valign="top"><span style="font-size:11px;">State :</span> <span style="font-size:11px; font-weight: bold;">$state</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Mobile1 :</span> <span style="font-size:11px; font-weight: bold;">$mobile1</span></td>
						<td valign="top"><span style="font-size:11px;">Mobile2 :</span> <span style="font-size:11px; font-weight: bold;">$mobile2</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Line Number :</span> <span style="font-size:11px; font-weight: bold;">$line_number</span></td>
						<td valign="top"><span style="font-size:11px;">Email-Id :</span><span style="font-size:11px; font-weight: bold;"> $email_id</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Customer Type :</span> <span style="font-size:11px; font-weight: bold;">$customer_type</span></td>
						<td valign="top"><span style="font-size:11px;">Zone :</span> <span style="font-size:11px; font-weight: bold;">$zonena</span></td>
						
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Reference person :</span> <span style="font-size:11px; font-weight: bold;">$referenceperson</span></td>
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
    $pdf->Output($customer_id . '.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+
	
	
}


public function customer_register_form_monthly($cusid,$customer,$biliingplans) {
		
	//print_r($cusid);	print_r($customer);	
    $record = $this->my_model->get_adminrecord();
	$receiptdata = $this->my_model->customer_register_form_monthly($cusid, $customer);
	extract($receiptdata);
	extract($record);
	$todate = date('d-m-Y');
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
		 <table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td align="center" valign="top">
				
						<div class="logoDiv">
							<!--<img id="logo" src="/images/logo/$file" height="80">-->
							<h1>$name</h1>
<h3>$contact1 / $email</h3>
<h3>$address1</h3>
					      <h2>Customer Registration Form</h2>
						</div>
						
				</td>
			</tr>
		</table>	
	   		
		<div class="invoice-address" style="border-top: 2px solid #888;margin: 20px 0; padding-top: 25px;">
		    <br/>
		    <table border="1" cellspacing="0" cellpadding="15" width="100%">
			  <tbody>	
					<tr>
						<td align="left" valign="top"><span style="font-size:11px;">Customer-Id :</span><span style="font-size:11px; font-weight: bold;">$customer_id</span></td>
						<td valign="top"><span style="font-size:11px;">Name :</span><span style="font-size:11px; font-weight: bold;"> $first_name $middle_name $last_name</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Gender :</span> <span style="font-size:11px; font-weight: bold;">$gender</span></td>
						<td valign="top"><span style="font-size:11px;">DOB :</span> <span style="font-size:11px; font-weight: bold;">$DOB</span> </td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Place of birth :</span><span style="font-size:11px; font-weight: bold;"> $place_of_birth</span> </td>
						<td valign="top"><span style="font-size:11px;">Address :</span> <span style="font-size:11px; font-weight: bold;">$address</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">City :</span><span style="font-size:11px; font-weight: bold;"> $city</span></td>
						<td valign="top"><span style="font-size:11px;">State :</span> <span style="font-size:11px; font-weight: bold;">$state</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Mobile1 :</span> <span style="font-size:11px; font-weight: bold;">$mobile1</span></td>
						<td valign="top"><span style="font-size:11px;">Mobile2 :</span><span style="font-size:11px; font-weight: bold;"> $mobile2</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Line Number :</span> <span style="font-size:11px; font-weight: bold;">$line_number </span></td>
						<td valign="top"><span style="font-size:11px;">Email-Id :</span><span style="font-size:11px; font-weight: bold;"> $email_id </span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Customer Type :</span> <span style="font-size:11px; font-weight: bold;">$customer_type</span></td>
						<td valign="top"><span style="font-size:11px;">Zone :</span><span style="font-size:11px; font-weight: bold;"> $zonena </span></td>
						
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Billingplan Name :</span> <span style="font-size:11px; font-weight: bold;">$beelname</span></td>
						<td valign="top"><span style="font-size:11px;">Days :</span><span style="font-size:11px; font-weight: bold;"> $days </span></td>
						
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Amount :</span> <span style="font-size:11px; font-weight: bold;">$amountf</span></td>
						<td valign="top"><span style="font-size:11px;">Reference person :</span> <span style="font-size:11px; font-weight: bold;">$referenceperson</span></td>
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
    $pdf->Output($customer_id . '.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+
	
	
}

	public function qrcode(){ //
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		
		$this->form_validation->set_rules('qrcodedata', 'QRCode Data', 'required|xss_clean');
		

		
		if ($this->form_validation->run() === false) {
            $data['error'] = validation_errors();
	    } else {
			$unique_file = 'QRCode_'.rand();
			$data['qr_image'] = $qr_image =  $unique_file.'.png';
			$data['webar_url']=$this->input->post('qrcodedata');
			$this->generateQRCode($qr_image,$data['webar_url']);
			$redirect_uri = base_url().'master/addcustomer/qrcode';
			header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
			exit;

		}	

		 $this->load->view($this->headerPage,$this->head);
		 $this->load->view('qrcode',$data);

		
	}

	public function generateQRCode($qr_image,$webar_url){
		$this->load->library('ciqrcode');
		header("Content-Type: image/png");
		$params['savename'] = QRCODE_PATH.$qr_image;
		$params['data'] = $webar_url;
		$params['level'] = 'L';
		$params['size'] = 25;
		$this->ciqrcode->generate($params);
		return true;
	}
	
}
?>