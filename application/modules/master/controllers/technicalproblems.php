<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class technicalproblems extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_technical';	  //*****  Table name  *****//
	public $addPage  = 'technicalproblems_add';	     //*****  Add page    *****//
	public $editPage = 'technicalproblems_edit';     //*****  Edit page   *****//
	public $listPage = 'technicalproblems';		   //*****  View page   *****//
	public $viewPage ='technicalproblems_view';
	public $searchPage ='technicalproblems_search';
	public $addcustomerajax = 'technicalproblems_search _ajax';
	
	
	
	
	public $listPage_redirect = '/master/technicalproblems';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/technicalproblems/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/technicalproblems/edit/';  //*****  Redirect Edit  *****//
	public function __construct() {
        parent::__construct();
		$this->load->model('adddailyreport_model');   //*****    Model Loading     *****//	
  		$this->load->model('technicalproblems_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');	
		$this->load->model('addcustomer_model','customer_model');	
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }
	public function index(){ 		 //*****  View Loading  *****//
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_all_records();	
		//$data['amountrate'] = $this->my_model->get_amountrate();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->listPage,$data);
	}
	
	/** Add Function **/
	public function add(){ 
		
		$data['msg'] ='';
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		 $data['addcustomer'] = $this->my_model->get_addcustomer();
		 $data['customer_listing'] = $this->customer_model->get_all_records();
		 //$data['amountrate'] = $this->my_model->get_amountrate();
		 //echo'<pre>';print_r( $data['addcustomer'] );exit;
		  //$data['feesplaning'] = $this->my_model->get_feesplaning();
		if($this->input->post('add') != ''){
            //echo'<pre>';print_r($_POST);
			//print_r($_POST);
			//exit;
			$employee_rec = $this->adddailyreport_model->get_employee($this->input->post('reportedby'));
			$result = $this->my_model->add_record($employee_rec);
			if($result){
				$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Inserted...";
			}
		}
		//$header['host'] = $this->comm_model->get_single_record();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$data['employee'] = $this->adddailyreport_model->get_employee();
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->addPage,$data);
	}

	public function add_messages(){
		if($this->input->post('btn_save') == 'add'){ 
			
			$reportedby = $this->input->post('msg_reportedby');
		
			$result = $this->my_model->add_messages_record($reportedby);
			
			if($result){
				$this->session->set_flashdata('msg_succ', 'Created Successfully...');
				echo 'success';
				//redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Created...";
				//redirect($this->listPage);
				echo 'error';
			}

		}elseif($this->input->post('btn_save') == 'edit'){
			$id = $this->input->post('leaking_id');
			$result = $this->my_model->update_record($id);
			if($result){
				$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
				echo 'success';
				//redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Updated...";
				//redirect($this->listPage);
				echo 'error';
			}
		}else{
			echo 'error update DB';
		}
	}

	public function edit($id){
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		$data['record_messages'] = $this->my_model->get_message_records($id);
		//$data['addcustomer'] = $this->my_model->get_addcustomer();
		//$data['amountrate'] = $this->my_model->get_amountrate();
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
		$data['employee'] = $this->adddailyreport_model->get_employee();
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->editPage,$data);

	}
	
	/** View Function **/
	public function view($id){ 
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		//print_r($data['record']);
		//$header['host'] = $this->comm_model->get_single_record();						
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->viewPage,$data);
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

	public function get_customer_info(){
		$customer_id = $this->input->post('customer_id');
		if($customer_id != ''){
			$result = $this->my_model->get_customer_info_details($customer_id);
			echo json_encode($result);
			
		}else{
			echo '{}';
		}
	}
	
	public function add_message(){
		if($this->input->post('btn_save') == 'add'){ 
			
		
			$result = $this->my_model->add_payment_record();
			if($result){
				$this->session->set_flashdata('msg_succ', 'Created Successfully...');
				echo 'success';
				//redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Created...";
				//redirect($this->listPage);
				echo 'error';
			}

		}elseif($this->input->post('btn_save') == 'edit'){
			$id = $this->input->post('leaking_id');
			$result = $this->my_model->update_record($id);
			if($result){
				$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
				echo 'success';
				//redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Updated...";
				//redirect($this->listPage);
				echo 'error';
			}
		}else{
			echo 'error update DB';
		}
	}
	/*public function getoldmeter(){
		$id = $this->input->post('id');
		$getData=$this->my_model->select_getoldmeter($id);
		//echo'<pre>';print_r($getData);exit;
		if(!empty($getData)){
			if($getData[0]['status']=='1'){
		$selBox = 0;
			}else{
			$selBox = $getData[0]['aftermeter'];
            
			}
			
		}if(empty($getData)){
		
			$selBox = 0;
		}
		echo $selBox;
		
		
	}*/
	
	
}
?>