<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Global Settings Controller 
 * Handles global settings management
 */
class global_settings extends CI_Controller {

	// Declare global variable here
	public $headerPage = '../../views/admin-includes/header';
	public $seoPage = '../../views/admin-includes/seo-standards';
	public $table_name = 'tbl_global_settings';

	//set List page
	public $listPage = 'global_settings';

	//set Add page
	public $addPage  = 'global_settings-add';

	//set Edit page
	public $editPage = 'global_settings-edit';

	//set View page
	public $viewPage = 'global_settings-view';

	//set Redirect page to list page
	public $listPage_redirect = '/master/global_settings';

	//set Redirect page to add page
	public $addPage_redirect = '/master/global_settings/add/';

	//set Redirect page to edit page
	public $editPage_redirect = '/master/global_settings/edit/';

	// Autoloading a system library using constructor method
	public function __construct() {
        parent::__construct();
  		$this->load->model('global_settings_model','my_model');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off');
		$this->load->model('adminheader_model','top_model');
		
		// Check if user is admin
		if($this->session->userdata('usertype') != 'admin'){
			redirect('master/page/', 'refresh');
		}
    }

	/** Default Function **/
	public function index(){ 
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_all_records();
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->listPage,$data);
	}

	/** View Function **/
	public function view($id){ 
		$data['msg'] ='';
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->viewPage,$data);
	}

	/** Add Function **/
	public function add(){ 
		$data['msg'] ='';
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		
		if($this->input->post('add') != ''){
			$exit_data = array(
				'code' => trim(strtoupper($this->input->post('code')))
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
				$data['msg'] = "Code Already Exists...";
			}
		}
		$this->load->view($this->headerPage,$this->head);
		$data['seo_stands'] = $this->load->view($this->seoPage, '', TRUE);
		$this->load->view($this->addPage,$data);
	}

	/** Edit Function **/
	public function edit($id){ 
		$data['msg'] ='';
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);

		if($this->input->post('add') != ''){
			$exit_data = array(
				'code' => trim(strtoupper($this->input->post('code')))
			);
			$exit_details = $this->my_model->exit_id($exit_data, $id);

			if($exit_details == 0 || $exit_details == 1){
				$result = $this->my_model->update_record($id);

				if($result){
					$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
					redirect($this->listPage_redirect);
				}else{
					$data['msg'] = "Not Updated...";
				}
			}else{
				$data['msg'] = "Code Already Exists...";
			}
		}
		$this->load->view($this->headerPage,$this->head);
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
}
?>
