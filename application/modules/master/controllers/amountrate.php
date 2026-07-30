<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class amountrate extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_amountrate';	  //*****  Table name  *****//
	public $addPage  = 'amountrate_add';	     //*****  Add page    *****//
	public $editPage = 'amountrate_edit';     //*****  Edit page   *****//
	public $listPage = 'amountrate';		   //*****  View page   *****//
	
	public $listPage_redirect = '/master/amountrate';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/amountrate/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/amountrate/edit/';  //*****  Redirect Edit  *****//
	public function __construct() {
        parent::__construct();
  		$this->load->model('amountrate_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('addcustomer_model'); 
		//$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }
	public function index(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		// No longer loading all records - using server-side pagination
		$data['record'] = array();	
		$data['classification'] = $this->addcustomer_model->get_classification();
		//$header['host'] = $this->comm_model->get_single_record();				
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}
	
	/** AJAX endpoint to get per_unit by cubic_meter **/
	public function get_rate_by_cubic_meter() {
		$cubic_meter = $this->input->post('cubic_meter') !== null ? intval($this->input->post('cubic_meter')) : -1;
		$classification_id = $this->input->post('classification_id') ? $this->input->post('classification_id') : '';
		
		// Allow 0 and positive values, but return 0 if cubic_meter is negative (e.g., when start is 0)
		if($cubic_meter < 0) {
			header('Content-Type: application/json');
			echo json_encode(array('success' => false, 'per_unit' => 0));
			exit;
		}
		
		$per_unit = $this->my_model->get_per_unit_by_cubic_meter($cubic_meter, $classification_id);
		
		header('Content-Type: application/json');
		echo json_encode(array('success' => true, 'per_unit' => floatval($per_unit)));
		exit;
	}
	
	/** AJAX endpoint for DataTables server-side processing **/
	public function get_datatable_data() {
		// Get DataTables parameters
		$start = $this->input->post('start') ? intval($this->input->post('start')) : 0;
		$length = $this->input->post('length') ? intval($this->input->post('length')) : 10;
		$search = $this->input->post('search')['value'] ? $this->input->post('search')['value'] : '';
		$order_column_index = $this->input->post('order')[0]['column'] ? intval($this->input->post('order')[0]['column']) : 2;
		$order_dir = $this->input->post('order')[0]['dir'] ? $this->input->post('order')[0]['dir'] : 'asc';
		$classification_id = $this->input->post('classification_id') ? $this->input->post('classification_id') : '';
		
		// Map column index to column name
		$columns = array(
			0 => 'tbl_amountrate.id',
			1 => 'tbl_classification.class_name',
			2 => 'tbl_amountrate.cubic_meter',
			3 => 'tbl_amountrate.per_unit',
			4 => 'tbl_amountrate.commodity_charges',
			5 => 'tbl_amountrate.status',
			6 => 'tbl_amountrate.id'
		);
		$order_column = isset($columns[$order_column_index]) ? $columns[$order_column_index] : 'tbl_amountrate.cubic_meter';
		
		// Get filtered and paginated records
		$records = $this->my_model->get_paginated_records($start, $length, $search, $order_column, $order_dir, $classification_id);
		$total_records = $this->my_model->get_total_count('', $classification_id);
		$filtered_records = $this->my_model->get_total_count($search, $classification_id);
		
		// Format data for DataTables
		$data = array();
		$i = $start + 1;
		foreach($records as $row) {
			$status_html = '';
			$row_id = isset($row['id']) ? (int) $row['id'] : 0;
			$row_status = isset($row['status']) ? (int) $row['status'] : 0;
			$status_url = ADMIN_URL.'amountrate/status/'.$row_id.'/'.$row_status;
			if($row_status == 1) {
				$status_html = '<a href="javascript:void(0);" class="badge badge-success badge-pill btn-status-toggle" data-url="'.$status_url.'" title="Click to deactivate">Active</a>';
			} else {
				$status_html = '<a href="javascript:void(0);" class="badge badge-danger badge-pill btn-status-toggle" data-url="'.$status_url.'" title="Click to activate">De-Active</a>';
			}
			
			$action_html = '<div class="btn-group btn-group-sm" role="group">'
				.'<a href="'.ADMIN_URL.'amountrate/edit/'.$row_id.'" class="btn btn-outline-success" title="Edit" data-toggle="tooltip">'
				.'<i class="fal fa-edit"></i>'
				.'</a>'
				.'</div>';
			
			$commodity_charges = isset($row['commodity_charges']) && $row['commodity_charges'] != '' ? number_format($row['commodity_charges'], 2) : '0.00';
			
			$data[] = array(
				$i++,
				htmlspecialchars(stripslashes($row['class_name'])),
				htmlspecialchars(stripslashes($row['cubic_meter'])),
				number_format((float) $row['per_unit'], 2),
				$commodity_charges,
				$status_html,
				$action_html
			);
		}
		
		// Return JSON response
		$output = array(
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => $total_records,
			"recordsFiltered" => $filtered_records,
			"data" => $data
		);
		
		header('Content-Type: application/json');
		echo json_encode($output);
		exit;
	}
	
	/** Add Function - Batch insert/update based on range **/
	public function add(){ 
		$data['msg'] ='';
	 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['classification'] = $this->addcustomer_model->get_classification();
		
		// Check if this is an AJAX request (check for X-Requested-With header or accept header)
		$is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || 
				   (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
		
		if($is_ajax && $this->input->post('add') != ''){
			// Get form values
			$classification = $this->input->post('classification');
			$start = $this->input->post('start');
			$end = $this->input->post('end');
			$rate = $this->input->post('rate');
			$incre = $this->input->post('incre') ? $this->input->post('incre') : '';
			$apply_increment = $this->input->post('apply_increment') ? intval($this->input->post('apply_increment')) : 0;
			
			// Validate inputs - allow 0 values (empty() returns true for 0, so use explicit checks)
			// Check if values are set and not empty strings (allow 0 as valid value)
			$start = trim($start);
			$end = trim($end);
			$rate = trim($rate);
			
			if(empty($classification) || 
			   $start === '' || $start === null || 
			   $end === '' || $end === null || 
			   $rate === '' || $rate === null) {
				header('Content-Type: application/json');
				echo json_encode(array('success' => false, 'message' => 'Please fill in all required fields.'));
				exit;
			}
			
			// Convert to numbers for comparison
			$start = intval($start);
			$end = intval($end);
			$rate = floatval($rate);
			
			if($start > $end) {
				header('Content-Type: application/json');
				echo json_encode(array('success' => false, 'message' => 'Start value must be less than or equal to End value.'));
				exit;
			} elseif($start < 0 || $end < 0) {
				header('Content-Type: application/json');
				echo json_encode(array('success' => false, 'message' => 'Start and End values must be 0 or positive numbers.'));
				exit;
			} else {
				// Call batch insert/update method
				$result = $this->my_model->batch_add_records($classification, $start, $end, $rate, $incre, $apply_increment);
				
				if($result['success'] > 0) {
					$msg = "Successfully processed " . $result['success'] . " record(s). ";
					if($result['inserted'] > 0) {
						$msg .= $result['inserted'] . " inserted, ";
					}
					if($result['updated'] > 0) {
						$msg .= $result['updated'] . " updated.";
					}
					
					if(!empty($result['errors'])) {
						$msg .= " Errors: " . implode(", ", $result['errors']);
					}
					
					header('Content-Type: application/json');
					echo json_encode(array(
						'success' => true, 
						'message' => $msg,
						'inserted' => $result['inserted'],
						'updated' => $result['updated'],
						'total' => $result['success']
					));
					exit;
				} else {
					header('Content-Type: application/json');
					echo json_encode(array(
						'success' => false, 
						'message' => "No records were processed. " . (!empty($result['errors']) ? implode(", ", $result['errors']) : "")
					));
					exit;
				}
			}
		}
		
		// Regular form submission (non-AJAX) - keep for backward compatibility
		if($this->input->post('add') != '' && !$is_ajax){
			// Get form values
			$classification = $this->input->post('classification');
			$start = $this->input->post('start');
			$end = $this->input->post('end');
			$rate = $this->input->post('rate');
			$incre = $this->input->post('incre') ? $this->input->post('incre') : '';
			
			// Validate inputs - allow 0 values (empty() returns true for 0, so use explicit checks)
			$start = trim($start);
			$end = trim($end);
			$rate = trim($rate);
			
			if(empty($classification) || 
			   $start === '' || $start === null || 
			   $end === '' || $end === null || 
			   $rate === '' || $rate === null) {
				$data['msg'] = "Please fill in all required fields.";
			} else {
				// Convert to numbers for comparison
				$start = intval($start);
				$end = intval($end);
				$rate = floatval($rate);
				
				if($start > $end) {
					$data['msg'] = "Start value must be less than or equal to End value.";
				} elseif($start < 0 || $end < 0) {
					$data['msg'] = "Start and End values must be 0 or positive numbers.";
				} else {
					$apply_increment = $this->input->post('apply_increment') ? intval($this->input->post('apply_increment')) : 0;
					// Call batch insert/update method
					$result = $this->my_model->batch_add_records($classification, $start, $end, $rate, $incre, $apply_increment);
					
					if($result['success'] > 0) {
						$msg = "Successfully processed " . $result['success'] . " record(s). ";
						if($result['inserted'] > 0) {
							$msg .= $result['inserted'] . " inserted, ";
						}
						if($result['updated'] > 0) {
							$msg .= $result['updated'] . " updated.";
						}
						
						if(!empty($result['errors'])) {
							$msg .= " Errors: " . implode(", ", $result['errors']);
						}
						
						$this->session->set_flashdata('msg_succ', $msg);
						redirect($this->listPage_redirect);
					} else {
						$data['msg'] = "No records were processed. " . (!empty($result['errors']) ? implode(", ", $result['errors']) : "");
					}
				}
			}
		}
		//$header['host'] = $this->comm_model->get_single_record();				
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->addPage,$data);
	}
	/** Edit Function **/
	public function edit($id){
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		$data['classification'] = $this->addcustomer_model->get_classification();
		$data['msg'] ='';
		//echo'<pre>';print_r($data['record']);exit;
		if($this->input->post('edit') != ''){
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
		$header['record_info'] = $this->top_model->get_last_login_details(1);
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
	public function Search($id){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		//print_r($data['record']);
		//$header['host'] = $this->comm_model->get_single_record();						
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->searchPage,$data);
	}
	public function getaddassetssearch(){		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('asset_type'));exit;
			if($this->input->post('asset_type') ==''){
				$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
				echo $selBox;
			}
			if($this->input->post('asset_type') !=''){
				
				$asset_type = $this->input->post('asset_type');
				
				
				$data['record'] = $this->my_model->get_addassets_records($asset_type);

			 
				$this->load->view($this->addassetsajax,$data);
			}		
	}	
	/** Export to Excel Function **/
	public function export_excel($classification_id = '0') {
		$this->load->helper('csv');
		
		// Normalize classification_id
		$classification_id = ($classification_id == '0' || $classification_id == '') ? '' : $classification_id;
		
		// Get all records based on classification filter
		$classification_id = ($classification_id == '0' || $classification_id == '') ? '' : $classification_id;
		
		// Get all records (no pagination for export)
		$records = $this->my_model->get_all_records_for_export($classification_id);
		
		// Prepare data array for CSV export
		$export_data = array();
		
		// Add header row
		$export_data[] = array(
			'S No',
			'Classification',
			'Cubic Meter',
			'Meter Rate',
			'Charges/Consumption',
			'Status'
		);
		
		// Add data rows
		$i = 1;
		foreach($records as $row) {
			$status = ($row['status'] == 1) ? 'Active' : 'De-Active';
			$commodity_charges = isset($row['commodity_charges']) && $row['commodity_charges'] != '' ? number_format($row['commodity_charges'], 2) : '0.00';
			
			$export_data[] = array(
				$i++,
				stripslashes($row['class_name']),
				stripslashes($row['cubic_meter']),
				number_format($row['per_unit'], 2),
				$commodity_charges,
				$status
			);
		}
		
		// Generate filename with date and classification
		$filename = 'amountrate';
		if($classification_id != '' && $classification_id != '0') {
			// Get classification name for filename
			$classification_name = $this->addcustomer_model->get_classification();
			foreach($classification_name as $class) {
				if($class['class_id'] == $classification_id) {
					$filename .= '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $class['class_name']);
					break;
				}
			}
		}
		$filename .= '_' . date('d-m-Y') . '.csv';
		
		// Export to CSV (Excel can open CSV files)
		array_to_csv($export_data, $filename);
		exit;
	}
	
	/** Status Change Function **/
	public function status($id,$status){
		$data['msg'] ='';
		//echo $status;
		 
		$statu = ($status == 1 ? 'Deactive' : 'Active');
		if($id){
			$result = $this->my_model->status_record($id,$status);
			if($result){
				$this->session->set_flashdata('msg_succ', 'insert Successfully...');
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
	/*public function multi_delete(){
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
	
	*/
	/*public function fileDownloadajax($id){
		$this->load->database();
		$this->db->select('id,asset_type,quantity,total,');
		$this->db->where('id',$id);
		$query = $this->db->get($this->table_name);
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}*/
}
?>