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
		return array(
				'dashboard' => 'Dashboard',
				'addcustomer' => 'Customers Listing',
				'add_zone' => 'Zone Names',
				'statementofaccountlist' => 'Statement of Account',
				'delete_customer' => 'Delete Customer',
				'addmetercustomerreading' => 'Add Meter Customers Reading',
				'addpaymentcustomer' => 'Meter Customers Bills',
				'leakingentry' => 'Leaking Entry',
				'feesplaning' => 'Monthly Fees Plans',
				'paymentmonthlycustomer' => 'Monthly Customers Bills',
				'metersearch' => 'Meter Customer Search',
				'monthlysearch' => 'Monthly Customer Search',
				'generatemetercustomer_search' => 'Both Type Of Customers',
				'income_reportsearch' => 'Income Report Search',
				'paidsearch' => 'Paid Search',
				'unpaidsearch' => 'Unpaid Search',
				'addemployee' => 'Add Employee',
				'payrols' => 'Payrolls',
				'payrolssearch' => 'Payrolls Search',
				'addexpenses' => 'Add Expenses',
				'bsearch' => 'Balance Sheet Search',
				'adddailyreport' => 'Daily Reports',
				'customerbalancemonitor' => 'Customer Balance Monitor',
				'lowtonoconsumption' => 'Low to No Consumption',
				'addassets' => 'Assets',
				'addledger' => 'Ledger',
				'ar_adjustment' => 'AR Adjustment',
				'ar_adjustment_approve' => 'AR Adjustment Approve',
				'technicalproblems' => 'Technical Problems',
				'technicalsearch' => 'Technical Problems View',
				'message_support' => 'Message Support',
				'web_settings' => 'Admin Address',
				'mobile_notifications' => 'Mobile Notifications',
				'admin' => 'Admin',
				'responsibilities' => 'Roles & Responsibilities',
				'employee_logins' => 'Employee Login',
				'system_activity' => 'System Activity',
				'manual_or_series' => 'Manual OR Series',
				'addbillingperiod' => 'Setup Schedule Billing Period',
				'createbalanceforward' => 'Create Balance Forward',
				'or_correction' => 'OR Correction',
				'meter_reading_correction' => 'Meter Reading Correction',
				'leaking_entry_correction' => 'Leaking Entry Correction',
				'database_backup' => 'Database Backup',
				'classification_category' => 'Classification Category',
				'classification' => 'Classification',
				'amountrate' => 'Per Unit Value'
				);
	}

	/**
	 * Hierarchical permission groups matching the sidebar navigation.
	 * parent_key is saved as a module[] checkbox when set (e.g. admin gate).
	 */
	public function module_groups(){
		return array(
			array(
				'id' => 'dashboard',
				'label' => 'Dashboard',
				'icon' => 'fa-home',
				'parent_key' => 'dashboard',
				'children' => array()
			),
			array(
				'id' => 'customers',
				'label' => 'Customers',
				'icon' => 'fa-user',
				'parent_key' => null,
				'children' => array('addcustomer', 'add_zone', 'statementofaccountlist', 'delete_customer')
			),
			array(
				'id' => 'finance',
				'label' => 'Finance',
				'icon' => 'fa-money',
				'parent_key' => null,
				'children' => array(
					'addmetercustomerreading',
					'addpaymentcustomer',
					'leakingentry',
					'feesplaning',
					'paymentmonthlycustomer',
					'metersearch',
					'monthlysearch',
					'generatemetercustomer_search',
					'income_reportsearch',
					'paidsearch',
					'unpaidsearch'
				)
			),
			array(
				'id' => 'employee',
				'label' => 'Employee',
				'icon' => 'fa-user',
				'parent_key' => 'addemployee',
				'children' => array('payrols', 'payrolssearch')
			),
			array(
				'id' => 'expenses',
				'label' => 'Expenses',
				'icon' => 'fa-pencil-square-o',
				'parent_key' => 'addexpenses',
				'children' => array('bsearch')
			),
			array(
				'id' => 'reports',
				'label' => 'Reports',
				'icon' => 'fa-pencil-square-o',
				'parent_key' => null,
				'children' => array('adddailyreport', 'customerbalancemonitor', 'lowtonoconsumption')
			),
			array(
				'id' => 'accounting',
				'label' => 'Accounting',
				'icon' => 'fa-book',
				'parent_key' => null,
				'children' => array('ar_adjustment', 'ar_adjustment_approve')
			),
			array(
				'id' => 'assets',
				'label' => 'Assets',
				'icon' => 'fa-pencil-square-o',
				'parent_key' => 'addassets',
				'children' => array()
			),
			array(
				'id' => 'ledger',
				'label' => 'Ledger',
				'icon' => 'fa-pencil-square-o',
				'parent_key' => 'addledger',
				'children' => array()
			),
			array(
				'id' => 'technical',
				'label' => 'Technical Problems',
				'icon' => 'fa-gavel',
				'parent_key' => 'technicalproblems',
				'children' => array('technicalsearch')
			),
			array(
				'id' => 'support',
				'label' => 'Support',
				'icon' => 'fa-comments',
				'parent_key' => 'message_support',
				'children' => array()
			),
			array(
				'id' => 'admin_address',
				'label' => 'Admin Address',
				'icon' => 'fa-location-arrow',
				'parent_key' => 'web_settings',
				'children' => array()
			),
			array(
				'id' => 'mobile_notifications',
				'label' => 'Mobile Notifications',
				'icon' => 'fa-mobile',
				'parent_key' => 'mobile_notifications',
				'children' => array()
			),
			array(
				'id' => 'admin',
				'label' => 'Admin',
				'icon' => 'fa-user',
				'parent_key' => 'admin',
				'children' => array(
					'responsibilities',
					'employee_logins',
					'system_activity',
					'manual_or_series',
					'addbillingperiod',
					'createbalanceforward',
					'or_correction',
					'meter_reading_correction',
					'leaking_entry_correction',
					'database_backup',
					'classification_category',
					'classification',
					'amountrate'
				)
			),
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
		$data['module_groups'] = $this->module_groups();
		$data['module_methods'] = $this->module_methods();
		$data['permissions_mode'] = 'add';
		if($this->input->post('add') != ''){
			$exit_data = array(
				'role_name' => ($this->input->post('role_name'))
			);				
			$exit_details = $this->my_model->exit_details($exit_data);
			if($exit_details == 0){
				$result = $this->my_model->add_record();
				if($result){
					if (function_exists('log_system_activity')) {
						log_system_activity(array(
							'category' => 'admin',
							'action' => 'create',
							'module' => 'responsibilities',
							'controller' => 'responsibilities',
							'method' => 'add',
							'entity_type' => 'role',
							'entity_id' => (string) $result,
							'reference_no' => (string) $this->input->post('role_name'),
							'summary' => 'Role created: '.$this->input->post('role_name'),
						));
					}
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
		$data['module_groups'] = $this->module_groups();
		$data['module_methods'] = $this->module_methods();
		$data['permissions_mode'] = 'edit';
		$data['record'] = $this->my_model->get_single_record($id);
		if($this->input->post('edit') != ''){
			$result = $this->my_model->update_record($id);
			if($result){
				if (function_exists('log_system_activity')) {
					log_system_activity(array(
						'category' => 'admin',
						'action' => 'update',
						'module' => 'responsibilities',
						'controller' => 'responsibilities',
						'method' => 'edit',
						'entity_type' => 'role',
						'entity_id' => (string) $id,
						'reference_no' => isset($data['record']['role_name']) ? $data['record']['role_name'] : (string) $this->input->post('role_name'),
						'summary' => 'Role permissions updated',
					));
				}
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
		$data['module_groups'] = $this->module_groups();
		$data['module_methods'] = $this->module_methods();
		$data['permissions_mode'] = 'view';
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
				if (function_exists('log_system_activity')) {
					log_system_activity(array(
						'category' => 'admin',
						'action' => 'delete',
						'module' => 'responsibilities',
						'controller' => 'responsibilities',
						'method' => 'delete',
						'entity_type' => 'role',
						'entity_id' => (string) $id,
						'summary' => 'Role deleted',
					));
				}
				$this->session->set_flashdata('msg_succ', 'Deleted Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Deleted...";
			}
		}
	}

}
?>