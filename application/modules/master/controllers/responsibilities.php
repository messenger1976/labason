<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin Roles Controller
 * @author		Spark
 * @copyright	Copyright (c) 2013, Sparkinfosys.com
 * @version		2.0
 * @package		Ecommerce
 * @subpackage  Admin Roles
 * @link         
 * */
class responsibilities extends CI_Controller 
{ 
	public $headerPage = '../../views/admin-includes/header';
	public $table_name = 'tbl_adminroles';
	public $listPage = 'responsibilities';
	public $addPage  = 'responsibilities-add';
	public $editPage = 'responsibilities-edit';
	public $viewPage = 'responsibilities-view';
	public $listPage_redirect = '/master/responsibilities';
	public $addPage_redirect = '/master/responsibilities/add/';
	public $editPage_redirect = '/master/responsibilities/edit/';
	
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
  		$this->load->model('responsibilities_model','my_model');// Loading the dashboard Model
		$this->load->model('common_model','comm_model'); 
		//$this->load->model('admin_common_model','count_model'); 
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
 		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 
		$this->load->model('adminheader_model','top_model');
		//monitoring admin login details
		$admin= $this->my_model->getadminuserdetails();	
		//if($admin['logout_time']!='0000-00-00 00:00:00'){
		//	redirect('/master/logout', 'refresh');
		//}
 		if($this->session->userdata('usertype') == 'subadmin'){
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		}else{
			$this->head['roleResponsible'] = array();
		}
		$this->head['record_info'] = $this->top_model->get_last_login_details(1);	
		if(	array_key_exists('admin',$this->head['roleResponsible'] ) && 
			$this->session->userdata('usertype') == 'subadmin' )
		{
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['admin']);
		}
		//$this->head['commonData'] 	= $this->count_model->get_common_data();
		
  }
	/** Lists of Data Function **/
	public function index(){ 
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_all_records();
		$data['active_record'] = $this->my_model->get_all_active_records();
		$data['deactive_record'] = $this->my_model->get_all_deactive_records();
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->listPage,$data);
		
	}
	
	public function module_name(){
		return $modules_name = array(
				'dashboard' => 'Dashboard',
				'addcustomer' => 'Addcustomer',
				'add_zone' => 'Zone Names',
				'addpaymentcustomer' => 'Meter Customer Bills',
				'amountrate' => 'Per Unit Value',
				'feesplaning' => 'Monthly Fees Plans',
				'paymentmonthlycustomer' => 'Monthly Customer Bills',
				'metersearch' => 'Meter Customer Search',
				'monthlysearch' => 'Monthly Customer Search',
				'generatemetercustomer_search' => 'Both Type of Customers',
				'income_reportsearch' => 'Income Reports Search',
				'paidsearch' => 'Paid Search',
				'unpaidsearch' => 'Un-Paid Search',
				'addemployee' => 'Add Employee',
				'payrols' => 'Payrols',
				'payrolssearch' => 'Payrols search',
				'addexpenses' => 'Add Expenses',
				'bsearch' => 'Balance Sheet Search',
				'addassets' => 'Add Assets',
				'technicalproblems' => 'Technical Problems',
				'technicalsearch' => 'Technical Problems View',
				'web_settings' => 'Admin Address',
				'admin' => 'Admin',
				'adddailyreport' => 'Daily Reports',
				'leakingentry' => 'Leaking Entry',
				'database_backup' => 'Database Backup'
				);
	}

	public function module_methods(){
		return $modules_name = array(
				'l' => 'List',
				'a' => 'Add',
				'e' => 'Edit',
				'd' => 'Delete'
			);
	}


	
	/** Add Function **/
	public function add(){ 
		$data['msg'] ='';
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['modules_name'] = $this->module_name();
		$data['module_methods'] = $this->module_methods();
		if($this->input->post('add') != ''){
			$exit_data = array(
				'role_name' => ($this->input->post('role_name'))
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
					$data['msg'] = "Already Exists...";
			}
		}
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->addPage,$data);
	}

	/** Edit View Page 				**/
	public function edit($id){
		$data['msg'] ='';
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['modules_name'] = $this->module_name();
		$data['module_methods'] = $this->module_methods();
		$data['record'] = $this->my_model->get_single_record($id);
		if($this->input->post('edit') != ''){
			$result = $this->my_model->update_record($id);
			if($result){
				$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Updated...";
			}
		}
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->editPage,$data);
	}

	/** Full View Page 				**/
	public function view($id){
		$data['msg'] ='';
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['modules_name'] = $this->module_name();
		$data['module_methods'] = $this->module_methods();
		$data['record'] = $this->my_model->get_single_record($id);
		if($this->input->post('edit') != ''){
			$result = $this->my_model->update_record($id);
			if($result){
				$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Updated...";
			}
		}
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->viewPage,$data);
	}

	public function status($id,$status,$page){		//*****  Status change *****//
		$data['msg'] ='';
		$statu = ($status == 1 ? 'Deactive' : 'Active');
		if($id){
			$result = $this->my_model->status_record($id,$status);
			if($result){
				$this->session->set_flashdata('msg_succ', $statu.' Successfully...');
				redirect($this->listPage_redirect.'/'.$page);
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

}
?>