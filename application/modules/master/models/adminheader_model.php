<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class adminheader_model extends CI_Model {
    public $admin_login_users='tbl_admin_login_users';	
	public $responsibilities = 'tbl_responsibilities';
	public $responsibilities_user = 'tbl_responsibilities_user';
	
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 
		ini_set('date.timezone', 'Asia/Manila');	
		if($this->session->userdata("browser_session_id") == ''){
			$uniqueId = uniqid($_SERVER['REMOTE_ADDR'], TRUE);		
			$this->session->set_userdata("browser_session_id", md5($uniqueId));
		}
    }
	public function get_responsibilities_conditions($roleResponsible){
		//echo'<pre>';print_r($roleResponsible);exit;
		if($roleResponsible != 1 )	{ 
			redirect('master/page/', 'refresh');
		}
	}	

	/** Module names Array here 	**/
	public function module_name(){
		return array(
				'dashboard' => 'Dashboard',
				'addcustomer' => 'Customers Listing',
				'add_zone' => 'Zone Names',
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
				'addassets' => 'Assets',
				'addledger' => 'Ledger',
				'technicalproblems' => 'Technical Problems',
				'technicalsearch' => 'Technical Problems View',
				'web_settings' => 'Admin Address',
				'mobile_notifications' => 'Mobile Notifications',
				'admin' => 'Admin',
				'responsibilities' => 'Roles & Responsibilities',
				'employee_logins' => 'Employee Login',
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

	/** Page names Array here 			**/
	public function module_methods(){
		return $modules_name = array(
				'l' => 'List',
				'a' => 'Add',
				'e' => 'Edit',
				'd' => 'Delete'
			);
	}

	public function get_responsibilities_conditions1($roleResponsible){
		echo $roleResponsible;
		exit;

		if($this->uri->segment(3) == '' || $this->uri->segment(3) == 'index' || $this->uri->segment(3) == 'view'){
			if( (	is_array($roleResponsible) && 
					!in_array('l',$roleResponsible)  
				)	|| 
				( 	!is_array($roleResponsible) )	  
				)
			{ 
				redirect('master/page/', 'refresh');
			}
		}else if($this->uri->segment(3) == 'add'){
			if( (	is_array($roleResponsible) && 
					!in_array('a',$roleResponsible) 
				)	|| 
				( 	!is_array($roleResponsible) )	  
				)
			{ 
				redirect('master/page/', 'refresh');
			}
		}else if($this->uri->segment(3) == 'edit'){
			if( (	is_array($roleResponsible) && 
					!in_array('e',$roleResponsible) 
				)	|| 
				( 	!is_array($roleResponsible) )	  
				)
			{ 
				redirect('master/page/', 'refresh');
			}
		}else if($this->uri->segment(3) == 'delete' || $this->uri->segment(3) == 'deleteConfirm' || $this->uri->segment(3) == 'multi_delete' ){
			if( (	is_array($roleResponsible) && 
					!in_array('d',$roleResponsible) 
				)	|| 
				( 	!is_array($roleResponsible) )	  
				)
			{ 
				redirect('master/page/', 'refresh');
			}
		}

	}	
	
	public function module_name1(){
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
				'addassets' => 'Add Assetss',
				'technicalproblems' => 'Technical Problems',
				'technicalsearch' => 'Technical Problems View',
				'web_settings' => 'Admin Address',
				'admin' => 'Admin',
				'manual_or_series' => 'Manual OR Series',
			);
	}

	public function module_methods1(){
		return $modules_name = array(
				'l' => 'List',
				'a' => 'Add',
				'e' => 'Edit',
				'd' => 'Delete'
			);
	}

 	/** In Function Get single records for edit view purpose from select table **/
    public function get_responsibilities_user() {
        $this->db->select("*");
		$this->db->from($this->responsibilities_user);
		$this->db->where("id",$this->session->userdata('userid'));
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }

 	/** In Function Get single records for edit view purpose from select table **/
    public function get_responsibilities() {
		$res = $this->get_responsibilities_user();
		//$idArr = @explode(',',$res['role_id']);
        $this->db->select("*");
		$this->db->from($this->responsibilities);
		$this->db->where_in('id', $res['role_id']);
		$query = $this->db->get();
		$roles = $query->result_array();
		//echo $this->db->last_query();
		//echo '<pre>';print_r($roles);
		//exit;
		$CRows = array();
		$modules_name = $this->module_name();
		for ($i = 0; $i < count($roles); $i++) {
			foreach($modules_name as $key => $value){
				if( !array_key_exists( $roles[$i][$key], $CRows ) == true){
					//$CRows[$key][] = explode(',',$roles[$i][$key]);
					//if($roles[$i][$key] == 1){
						$CRows[$key] = $roles[$i][$key];
				//	}
				}
			}
		}
		//echo '<pre>';print_r($CRows);
		/*$finalArr = array();
		foreach($CRows as $key => $value){
			$newArr =  new RecursiveIteratorIterator(new RecursiveArrayIterator($value));
			$final = array_values(array_unique(array_filter(iterator_to_array($newArr, false))));
			if(!empty($final)){
				$finalArr[$key] = $final;
			}else{
				$finalArr[$key] = '';
			}
			
		}*/
		//echo '<pre>';print_r($finalArr);
		//exit;
		return $CRows;
    }



	public function get_last_login_details(){
        $this->db->select("*");
		$this->db->from($this->admin_login_users);
		$this->db->order_by('id','DESC');
		$this->db->limit(1,1);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	public function get_default_responsibilities(){
		$modules = array(
			'dashboard' => array(
				'0' => 'l'
			),
			'attribute_group' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'attribute_names' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'attribute_values' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'role_creation' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'role_assign' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'admin_login_history' => array(
				'0' => 'l',
				'1' => 'd'
			),
			'business_types' => array(
				'0' => 'l',
				'1' => 'e', 
			),
			'business_names' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'brands' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'customer_controllers' => array(
				'0' => 'l',
				'1' => 'e',
			
			),
			'admin_controllers' => array(
				'0' => 'l',
				'1' => 'e',
			
			),
			'vendor_controllers' => array(
				'0' => 'l',
				'1' => 'e',
			
			),
			'main_category' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'sub_category' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'child_category' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'contact_details' => array(
				'0' => 'l',
				'1' => 'e',
			),
			'customers' => array(
				'0' => 'l',
				'1' => 'e',
				'2' => 'd'
			),
			'customer_groups' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'customer_order_groups' => array(
				'0' => 'l'
			
			),
			'country_management' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'contact_us' => array(
				'0' => 'l',
				'1' => 'd'
			),
			'careers' => array(
				'0' => 'l',
				'1' => 'd'
			),
			'cms' => array(
				'0' => 'l',
				'1' => 'e'
			),
			'city_ads' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'city_banners' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'default_ads' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'default_banners' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'admin_email_history' => array(
				'0' => 'l'
			),
			'customer_email_history' => array(
				'0' => 'l'
			),
			'vendor_email_history' => array(
				'0' => 'l'
			),
			'email_templates' => array(
				'0' => 'l',
				'1' => 'e'
			),
			'email_compose' => array(
				'0' => 'l',
				'1' => 'a'
			),
			'faqs' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'product_reviews' => array(
				'0' => 'l',
				'1' => 'd'
			),
			'order_feedback' => array(
				'0' => 'l'
			),
			'testimonials' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'admin_help_box' => array(
				'0' => 'l',
				'1' => 'e'
			),
			'vendor_help_box' => array(
				'0' => 'l',
				'1' => 'e'
			),
			'customer_help_box' => array(
				'0' => 'l',
				'1' => 'e'
			),
			'inbox' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'd'
			),
			'inventory' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'total_business' => array(
				'0' => 'l'
			),
			'products_business' => array(
				'0' => 'l'
			),
			'vendor_business' => array(
				'0' => 'l'
			),
			'customers_business' => array(
				'0' => 'l'
			),
			'register_customers' => array(
				'0' => 'l'
			),
			'measurements' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'news_letter_subscription' => array(
				'0' => 'l',
				'1' => 'd'
			),
			'orders' => array(
				'0' => 'l'
			),
			'products' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'payment_gateways' => array(
				'0' => 'l',
				'1' => 'e'
			),
			'reward_points' => array(
				'0' => 'l',
				'1' => 'e'
			),
			'admin_sms_history' => array(
				'0' => 'l',
				'1' => 'd'
			),
			'vendor_sms_history' => array(
				'0' => 'l',
				'1' => 'd'
			),
			'customer_sms_history' => array(
				'0' => 'l',
				'1' => 'd'
			),
			'sms_templates' => array(
				'0' => 'l',
				'1' => 'e'
			),
			'sms_compose' => array(
				'0' => 'l',
				'1' => 'a'
			),
			'seasonal_offers' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'sms_details' => array(
				'0' => 'l',
				'1' => 'e'
			),
			'vendors' => array(
				'0' => 'l',
				'1' => 'a',
				'2' => 'e',
				'3' => 'd'
			),
			'vendors_enquiry' => array(
				'0' => 'l',
				'1' => 'd'
			),
			'vendors_search' => array(
				'0' => 'l'
			),
			'web_settings' => array(
				'0' => 'l',
				'1' => 'e'
			),
		);
		return $modules;
	}	
	
}
