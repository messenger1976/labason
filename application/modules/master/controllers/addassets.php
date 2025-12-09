<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class addassets extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_addassets';	  //*****  Table name  *****//
	public $addPage  = 'addassets_add';	     //*****  Add page    *****//
	public $editPage = 'addassets_edit';     //*****  Edit page   *****//
	public $listPage = 'addassets';		   //*****  View page   *****//
	public $viewPage ='addassets_view';
	public $searchPage ='addassets_search';
	public $addassetsajax ='addassets_search _ajax';	
	public $listPage_redirect = '/master/addassets';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/addassets/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/addassets/edit/';  //*****  Redirect Edit  *****//
	public function __construct() {
        parent::__construct();
  		$this->load->model('addassets_model','my_model');   //*****    Model Loading     *****//	
		//$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }
	public function index(){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		
	//*****  View Loading  *****//
		$data['record'] = $this->my_model->get_all_records();	
		//$header['host'] = $this->comm_model->get_single_record();				
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}
	
	/** Add Function **/
	public function add(){ 
		$data['msg'] ='';
	 
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
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->addPage,$data);
	}
	/** Edit Function **/
	public function edit($id){
		$data['record'] = $this->my_model->get_single_record($id);
		$data['msg'] ='';
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
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
	
	
	public function fileDownloadajax($asset_type){
		$this->load->database();
		$this->db->select("id,asset_type,quantity,total");
		$this->db->from('tbl_addassets');
		//$this->db->join('tbl_addmetercustomer', 'tbl_addmetercustomer.customer_id = tbl_addcustomer.customer_id');
		if($asset_type !='0'){
		$this->db->where('tbl_addassets.asset_type',$asset_type);
		}
		//$this->db->group_by('tbl_addmetercustomer.id');	
		$query = $this->db->get();
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}	

}
?>