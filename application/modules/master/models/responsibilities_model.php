<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin Roles Controller
 * @author		Spark
 * @copyright	Copyright (c) 2013, Sparkinfosys.com
 * @version		2.0
 * @package		Ecommerce
 * @subpackage  Admin Roles
 * @link         
 * */
class responsibilities_model extends CI_Model {
	
	public $table_name = 'tbl_responsibilities';
	public $table_customer = 'tbl_addcustomer';
	public $adminroles = 'tbl_adminroles';
	public $admin_table_name='tbl_admin_login_users';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';

	public $responsibilities_user = 'tbl_responsibilities_user';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }

 	/** In Function Get single records for edit view purpose from select table **/
    public function get_responsibilities_user($id) {
        $this->db->select("*");
		$this->db->from($this->responsibilities_user);
		$this->db->where("id",$id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }

 	/** In Function Get single records for edit view purpose from select table **/
    public function get_responsibilities($id) {
		$idArr = @explode(',',$id);
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where_in('id', $idArr);
		$query = $this->db->get();
		$result = $query->result_array();
		//echo $this->db->last_query();
		return $result;
    }


	
	/** In Function Get all records from select table **/
    public function get_all_records() {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
 	/** In Function Get single records for edit view purpose from select table **/
    public function get_single_record($id='') {
        $this->db->select("*");
		$this->db->from($this->table_name);
		if($id != ''){
			$this->db->where("id",$id);
			$query = $this->db->get();
			$result = $query->row_array();
		}
		return $result;
    }
	
  	/** In Function Add Check Exits records for select table **/
	public function exit_details($exit_data) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where($exit_data);
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
    }
	
  	/** In Function Edit Check Exits records  for select table**/
	public function exit_id($exit_data,$local_id) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where($exit_data);
		$query = $this->db->get();
		$result = $query->num_rows();
		if($result > 0){
			$this->db->select("*");
			$this->db->from($this->table_name);
			$this->db->where($exit_data);
			$query = $this->db->get();
			$result = $query->row_array();
			if($result['id'] == $local_id){
				$result ='0';
			}
		}else{
			$result =1;
		}
		return $result;
    }

  	/** In Function Add records for select table **/
	public function add_record(){
		$roles_data = array();
		$posted = $this->input->post('module');
		if(is_array($posted)){
			foreach($posted as $key => $value){
				$roles_data[$value]=  1;
			}
		}

		$set_data = array(
			'role_name' => ucfirst($this->input->post('role_name')),
			'status' => 1,
			'create_date_time' => date('Y-m-d H:i:s') 
		);
		$full_data = array_merge($set_data,$roles_data);
		$result = $this->db->insert($this->table_name, $full_data); 
		return $result;
	}

	/** Module names Array here 	**/
	public function module_name(){
		return array(
				'dashboard' => '0',
				'addcustomer' => '0',
				'add_zone' => '0',
				'addmetercustomerreading' => '0',
				'addpaymentcustomer' => '0',
				'leakingentry' => '0',
				'feesplaning' => '0',
				'paymentmonthlycustomer' => '0',
				'metersearch' => '0',
				'monthlysearch' => '0',
				'generatemetercustomer_search' => '0',
				'income_reportsearch' => '0',
				'paidsearch' => '0',
				'unpaidsearch' => '0',
				'addemployee' => '0',
				'payrols' => '0',
				'payrolssearch' => '0',
				'addexpenses' => '0',
				'bsearch' => '0',
				'adddailyreport' => '0',
				'customerbalancemonitor' => '0',
				'addassets' => '0',
				'addledger' => '0',
				'technicalproblems' => '0',
				'technicalsearch' => '0',
				'web_settings' => '0',
				'mobile_notifications' => '0',
				'admin' => '0',
				'responsibilities' => '0',
				'employee_logins' => '0',
				'manual_or_series' => '0',
				'addbillingperiod' => '0',
				'createbalanceforward' => '0',
				'or_correction' => '0',
				'meter_reading_correction' => '0',
				'leaking_entry_correction' => '0',
				'database_backup' => '0',
				'classification_category' => '0',
				'classification' => '0',
				'amountrate' => '0'
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

 
  	/** In Function Update records for select table **/
  	public function update_record($id){

		$modules_name = $this->module_name();
		$roles_data = array();
		$posted = $this->input->post('module');
		if(is_array($posted)){
			foreach($posted as $key => $value){
				$roles_data[$value]=  1;
			}
		}
		
		$update_empty = array_diff_key($modules_name, $roles_data);
		$newArr = array_merge($roles_data,$update_empty);
		$set_data = array(
			'role_name' => ucfirst($this->input->post('role_name')),
		);
		$full_data = array_merge($set_data,$newArr);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $full_data); 
		return $result;
	}

	public function status_record($id,$status){		//**** status change ****//
		$sts = ($status == 1 ? 0 : 1);
		$set_data = array(
						'status' => $sts
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}	
	
  	/**  Delete records for select table **/
	public function delete_record($id){
		$this->db->where('id',$id);
		$result = $this->db->delete($this->table_name); 
		return $result;
	}
	
	//admin login confirmation 	
	public function getadminuserdetails(){
		$this->db->select("*");
		$this->db->from($this->admin_table_name);
		$this->db->where('id',$this->session->userdata('login_history_id'));
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->row_array();
		return $result;
	}		   
	public function get_all_active_records() {        
	    $this->db->select("*");		
		$this->db->from($this->table_name);		
		$this->db->where('status','1');		
		$this->db->order_by('id','desc');		
		$query = $this->db->get();		
		$result = $query->num_rows();		
		return $result;    
	}	   
	public function get_all_deactive_records() {       
    	$this->db->select("*");		
		$this->db->from($this->table_name);		
		$this->db->where('status','0');		
		$this->db->order_by('id','desc');		
		$query = $this->db->get();		
		$result = $query->num_rows();		
		return $result;   
	}	
	
	public function get_income_metercustomer(){
		$this->db->select('SUM(grand_total) as total1');
		$this->db->from($this->table_meter);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_income_monthlycustomer(){
		$this->db->select('SUM(paidamount) as total2');
		$this->db->from($this->table_monthly);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_outcome_expenses(){
		$this->db->select('SUM(total) as extotal1');
		$this->db->from($this->table_expenses);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_outcome_payroll(){
		$this->db->select('SUM(total) as extotal2');
		$this->db->from($this->table_payrol);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function total_customer(){
		$this->db->select('COUNT(id) as count_id');
		$this->db->from($this->table_customer);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}


}
?>