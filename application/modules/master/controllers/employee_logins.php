<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class employee_logins extends CI_Controller {
	
	public $seoPage 			= '../../views/admin-includes/seo-standards';
	public $headerPage 			= '../../views/admin-includes/header';

	public $listPage 			= 'employee-logins';			/**  List View Page  **/
	public $addPage  			= 'employee-logins-add';		/**  Add View Page   **/
	public $editPage 			= 'employee-logins-edit';  		/**  Edit View Page  **/
	public $viewPage 			= 'employee-logins-view';  		/**  Full View Page  **/

	public $listPage_redirect 	= '/master/employee_logins';		/**  Redirect View   **/
	public $addPage_redirect 	= '/master/employee_logins/add/';	/**  Redirect Add    **/
	public $editPage_redirect 	= '/master/employee_logins/edit/';  /**  Redirect Edit   **/

	/** Construct Load here 		**/
	public function __construct() { 
        parent::__construct();
		$this->load->model('adminheader_model','top_model');
		/** Subadmin login  Check here **/
		/*if($this->session->userdata('usertype') == 'subadmin'){
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		}else{
			$this->head['roleResponsible'] = array();
		}
		if(	array_key_exists('admin',$this->head['roleResponsible'] ) && $this->session->userdata('usertype') == 'subadmin' ){
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['admin']);
		}
 		/** Error Display Functions **/	
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 
		/** Model Loading **/	
		//$this->load->model('admin_common_model','common_model');
 		$this->load->model('employee_logins_model','my_model');   		
		//$this->head['commonData'] 	= $this->common_model->get_common_data();
		/** Validation Library **/	
		$this->load->library('form_validation');			   
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
	}
	
	/** List View Page 				**/
	public function index(){ 
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$this->data['record'] = $this->my_model->get_all_records();
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->listPage,$this->data);
	}

	/** Add View Page 				**/
	public function add(){ 		 
	  $this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		 if($this->input->post('add')!=""){
			$exit_detail = $this->my_model->exit_details($this->input->post('username'));
			if($exit_detail == 0){			
				$result = $this->my_model->add_record();
				if($result){
					$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
					redirect($this->listPage_redirect);
				}else{
					$data['msg'] = "Not Inserted...";
				}
			}else{
				$data['msg'] = "Already Exists...";
			}
	   }
		$data['roles'] = $this->my_model->get_all_role_records();
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->addPage,$data);
	} 
	
	/** Edit View Page 				**/
	public function edit($id){
		$data['msg'] ='';
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		if($this->input->post('edit') != ''){
			
				$exit_data = array(
						'username' => $this->input->post('username'),
						
					);			
			$exit_detail = $this->my_model->exit_id($exit_data,$id);
			if($exit_detail == 0){			
				$result = $this->my_model->update_record($id);
				if($result){
					$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
					redirect($this->listPage_redirect);
				}else{
					$data['msg'] = "Not Updated...";
				}
			}else{
				$data['msg'] = "Already Exists...";
			}
		}
		$data['roles'] = $this->my_model->get_all_role_records();
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->editPage,$data);
	}
	
	/** View Function **/
	public function view($id){ 
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->viewPage,$data);
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
	
	public function register(){ 		 //*****  View Loading  *****//
	   $this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		if($this->input->post('add')!=""){
		  if ($this->form_validation->run('employee-form') == TRUE) {
			$add_record=$this->my_model->add_record();
			if($add_record)
			{
				$this->session->set_flashdata('msg_succ', 'Addes Successfully...');
				redirect($this->listPage_redirect);
			}
	     }
	   }
		$data['roles'] = $this->my_model->get_all_role_records();
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->regaddPage,$data);
	}
	
	public function login_status($id,$status){
		$data['msg'] ='';
		//echo $status;
		$statu = ($status == 1 ? 'Deactive' : 'Active');
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
	
	public function firststatus($id,$status){
		$data['msg'] ='';
		//echo $status;
		$statu = ($status == 1 ? 'Deactive' : 'Active');
		if($id){
			$result = $this->my_model->firststatus_record($id,$status);
			if($result){
				$this->session->set_flashdata('msg_succ', $statu.' Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = " Status Not Updated...";
			}
		}
	}
	
	
}
?>