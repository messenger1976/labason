<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class addexpenses extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_addexpenses';	  //*****  Table name  *****//
	public $addPage  = 'addexpenses_add';	     //*****  Add page    *****//
	public $editPage = 'addexpenses_edit';     //*****  Edit page   *****//
	public $listPage = 'addexpenses';		   //*****  View page   *****//
	public $viewPage ='addexpenses_view';
public $searchPage ='addexpenses_search';
public $addexpensesrajax ='addexpenses_search _ajax';	
	public $listPage_redirect = '/master/addexpenses';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/addexpenses/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/addexpenses/edit/';  //*****  Redirect Edit  *****//
	public function __construct() {
        parent::__construct();
  		$this->load->model('addexpenses_model','my_model');   //*****    Model Loading     *****//	
		//$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }
	public function index(){ 		
	//*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_all_records();	
		//$header['host'] = $this->comm_model->get_single_record();				
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}
	
	/** Add Function **/
	public function add(){ 
		$data['msg'] ='';
	 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		if($this->input->post('add') != ''){
             //echo'<pre>';print_r($_POST);
				$result = $this->my_model->add_record();
				if($result){
					$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
					redirect($this->listPage_redirect);
				}else{
					$data['msg'] = "Not Inserted...";
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
	public function getaddexpensessearch(){		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('zone'));exit;
			if($this->input->post('fromdate') =='' && $this->input->post('todate')==''){
				$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
				echo $selBox;
			}
			if($this->input->post('fromdate') !='' || $this->input->post('todate')!=''){
				
				$fromdate = $this->input->post('fromdate');
				$todate = $this->input->post('todate');
				
				
				$data['record'] = $this->my_model->get_addexpenses_records($fromdate,$todate);

				$this->load->view($this->addexpensesrajax,$data);
			}		
	}	
	/** Status Change Function **/
	/*public function status($id,$status){
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
	}*/
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
}
?>