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
			4 => 'tbl_amountrate.status',
			5 => 'tbl_amountrate.id'
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
			if($row['status'] == 1) {
				$status_html = '<span class="label label-success arrowed-in arrowed-in-right"><a href="JavaScript:if(confirm(\'Are you sure want to Chanage the Status?\')==true){window.location=\''.ADMIN_URL.'amountrate/status/'.$row['id'].'/'.$row['status'].'\';}" style="color:#FFF; text-decoration:none;">Active</a></span>';
			} else {
				$status_html = '<span class="label label-danger arrowed"><a href="JavaScript:if(confirm(\'Are you sure want to Chanage the Status?\')==true){window.location=\''.ADMIN_URL.'amountrate/status/'.$row['id'].'/'.$row['status'].'\';}" style="color:#FFF; text-decoration:none;">De-Active</a></span>';
			}
			
			$action_html = '<div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
								<a class="green" href="'.ADMIN_URL.'amountrate/edit/'.$row['id'].'" title="Edit">
									<i class="fa fa-edit"></i>
								</a>
							</div>
							<div class="visible-xs visible-sm hidden-md hidden-lg">
								<div class="inline position-relative">
									<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
										<i class="icon-caret-down icon-only bigger-120"></i>
									</button>
									<ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
										<li>
											<a href="'.ADMIN_URL.'amountrate/edit/'.$row['id'].'" class="tooltip-success" data-rel="tooltip" title="Edit">
												<span class="green">
													<img src="'.base_url().'images/favicon/document-edit.gif">
												</span>
											</a>
										</li>
									</ul>
								</div>
							</div>';
			
			$data[] = array(
				$i++,
				stripslashes($row['class_name']),
				stripslashes($row['cubic_meter']),
				'<div align="right">'.stripslashes(number_format($row['per_unit'],2)).'</div>',
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
		if($this->input->post('add') != ''){
			// Get form values
			$classification = $this->input->post('classification');
			$start = $this->input->post('start');
			$end = $this->input->post('end');
			$rate = $this->input->post('rate');
			$incre = $this->input->post('incre') ? $this->input->post('incre') : '';
			
			// Validate inputs
			if(empty($classification) || empty($start) || empty($end) || empty($rate)) {
				$data['msg'] = "Please fill in all required fields.";
			} elseif($start > $end) {
				$data['msg'] = "Start value must be less than or equal to End value.";
			} elseif($start < 0 || $end < 0) {
				$data['msg'] = "Start and End values must be positive numbers.";
			} else {
				// Call batch insert/update method
				$result = $this->my_model->batch_add_records($classification, $start, $end, $rate, $incre);
				
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