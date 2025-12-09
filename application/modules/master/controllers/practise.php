<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class practise extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_practise';	  //*****  Table name  *****//
	public $addPage  = 'practise_add';	     //*****  Add page    *****//
	public $editPage = 'practise_edit';     //*****  Edit page   *****//
	public $listPage = 'practise';		   //*****  View page   *****//
	public $viewPage ='practise-view';	
	public $listPage_redirect = '/master/practise';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/practise/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/practise/edit/';  //*****  Redirect Edit  *****//
	public function __construct() {
        parent::__construct();
  		$this->load->model('practise_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
    }
	public function index(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_all_records();	
		//$header['host'] = $this->comm_model->get_single_record();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}
	
	/** Add Function **/
	public function add(){ 
		$data['msg'] ='';
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		
		if ($this->input->post('add') != '') {
			
			//echo '<pre>';print_r($_POST);
			//echo '<pre>';print_r($_FILES);exit;
				if ($_FILES['image']['name'] != '') {
				$config['upload_path'] = './images/practise/'; /* NB! create this dir! */
				$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
				$config['max_size']  = '0';
				$config['max_width']  = '0';
				$config['max_height']  = '0';
				//$config['overwrite'] = TRUE;
				$this->load->library('upload', $config);
				
				if(! $this->upload->do_upload('image')){
					$data['msg'] = $this->upload->display_errors();
				}else{
					$data = $this->upload->data();
					//echo '<pre>';print_r($data);
					$uploadedImages['image'] = $data['file_name'];
					$ImgData = array(
						'image' => $uploadedImages['image']
					);
					$result = $this->my_model->add_record($ImgData);
					if($result){
						$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
						redirect($this->listPage_redirect);
					}else{
						$data['msg'] = "Not Updated...";
					}
				}
			}else{
					$data['msg'] = "Not Updated...";
				}
			}
		$header['host'] = $this->comm_model->get_single_record();				
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->addPage,$data);
	}

	/** Edit Function **/
	public function edit($id){
		$data['msg'] ='';
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		$old_image = $data['record']['image'];
		if ($this->input->post('edit') != '') {
				if ($_FILES['image']['name'] != '') {
				$config['upload_path'] = './images/practise/'; /* NB! create this dir! */
				$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
				$config['max_size']  = '0';
				$config['max_width']  = '0';
				$config['max_height']  = '0';
				//$config['overwrite'] = TRUE;
				$this->load->library('upload', $config);
				
				if(! $this->upload->do_upload('image')){
					$data['msg'] = $this->upload->display_errors();
				}else{
					$data = $this->upload->data();
					//echo '<pre>';print_r($data);
					$uploadedImages['image'] = $data['file_name'];
					$ImgData = array(
						'image' => $uploadedImages['image']
					);
					$result = $this->my_model->update_record($id,$ImgData);
					if($result){
						$file = $config['upload_path'].$old_image;
						if(is_file($file))
						unlink($file); // delete file
						//echo $file.'file deleted';
						$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
						redirect($this->listPage_redirect);
					}else{
						$data['msg'] = "Not Updated...";
					}
				}
			}else{
				$ImgData = array(
					'image' => $data['record']['image']
				);
				$result = $this->my_model->update_record($id,$ImgData);
				if($result){
					$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
					redirect($this->listPage_redirect);
				}else{
					$data['msg'] = "Not Updated...";
				}
			}
		}
		$header['host'] = $this->comm_model->get_single_record();				
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->editPage,$data);
	}
	/** View Function **/
	public function view($id){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		//print_r($data['record']);
		$header['host'] = $this->comm_model->get_single_record();				
		$header['record_info'] = $this->top_model->get_last_login_details(1);				
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->viewPage,$data);
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