<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class main_category extends CI_Controller {
	public $seoPage = '../../views/admin-includes/seo-standards';
	public $headerPage = '../../views/admin-includes/header';
	public $table_name = 'tbl_main_category';	  //*****  Table name  *****//
	public $listPage = 'main-category';			 //*****  View page   *****//
	public $addPage  = 'main-category-add';	    //*****  Add page    *****//
	public $editPage = 'main-category-edit';   //*****  Edit page   *****//
	public $viewPage = 'main-category-view';  //*****  Single View page   *****//
	public $deletePage = 'main-category-delete';  //*****  Single View page   *****//
	public $statusPage = 'main-category-status';  //*****  Single View page   *****//
	public $listPage_redirect = '/master/main_category';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/main_category/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/main_category/edit/';  //*****  Redirect Edit  *****//
	
	public function __construct() {
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 
        parent::__construct();
		$this->load->model('adminheader_model','top_model');
  		$this->load->model('main_category_model','my_model');   //*****    Model Loading     *****//	
		$this->load->library('form_validation');			   //*****  Validation Library  *****//	
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		//monitoring admin login details
		$admin= $this->my_model->getadminuserdetails();	
		if($admin['logout_time']!='0000-00-00 00:00:00'){
			redirect('/master/logout', 'refresh');
		}
	 }
	//*****  View Loading  *****//
	public function index(){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_all_records();
		$head['record_info'] = $this->top_model->get_last_login_details(1);	
		$this->load->view($this->headerPage,$header);
		$data['seo_stands'] = $this->load->view($this->seoPage, '', TRUE);
		$this->load->view($this->listPage,$data);
	}
	/** View Function **/
	public function view($id){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		$head['record_info'] = $this->top_model->get_last_login_details(1);	
		$this->load->view($this->headerPage,$header);
		$data['seo_stands'] = $this->load->view($this->seoPage, '', TRUE);
		$this->load->view($this->viewPage,$data);
	}
	/** Add Function **/
	public function add(){ 
		$data['msg'] ='';
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		if ($this->form_validation->run('main_category') == TRUE) {
			$exit_data = array(
				'name' => mysql_real_escape_string(ucwords($this->input->post('category_name')))
			);				
			$exit_details = $this->my_model->exit_details($exit_data);
			if($exit_details == 0){
				$result = $this->my_model->add_record();
				if($result){
					$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
					redirect($this->listPage_redirect);
				}else{
					$data['msg'] = "Not Inserted...";
				}
			}else{
				$data['msg'] = ucwords($this->input->post('category_name')).' Already Existed...';
			}
		}
		$head['record_info'] = $this->top_model->get_last_login_details(1);	
		$this->load->view($this->headerPage,$header);
		$data['seo_stands'] = $this->load->view($this->seoPage, '', TRUE);
		$this->load->view($this->addPage,$data);
	}
	/** Edit Function **/
	public function edit($id){
		$data['msg'] ='';
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		if($this->input->post('edit') != ''){
			$set_data = array(
					'name' => mysql_real_escape_string(ucwords($this->input->post('category_name')))
				);	
			$exit = $this->my_model->exit_id($set_data,$data['record']['id']);	
			if($exit==0){
				$result = $this->my_model->update_record($id);
				if($result){
					$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
					redirect($this->listPage_redirect);
				}else{
					$data['msg'] = "Not Updated...";
				}
			}else{
				$data['msg'] = ucwords($this->input->post('category_name')).' Already Existed...';
			}
		}
		$head['record_info'] = $this->top_model->get_last_login_details(1);	
		$this->load->view($this->headerPage,$header);
		$data['seo_stands'] = $this->load->view($this->seoPage, '', TRUE);
		$this->load->view($this->editPage,$data);
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
	/** Status Change Function **/
	public function status($id,$status){
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
	
}
