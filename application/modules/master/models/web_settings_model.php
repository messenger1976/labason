<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Content_model  
 * This content_model consist a cms pages related funtions
 * @author		Spark(yasin)
 * @copyright	Copyright (c) 2013, Sparkinfosys.com
 * @version		2.0
 * @package		Ecommerce
 * @subpackage  content_model
 * @link         
 * */
class web_settings_model extends CI_Model {
	
	public $table_name = 'tbl_web_settings';
	public $admin_table_name='tbl_admin_login_users';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_customer = 'tbl_addcustomer';
	
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
		date_default_timezone_set('Asia/Manila');
    }
	
	/** Get all records from select table **/
    public function get_all_records() {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where("status",1);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	
 	/** Get single records for edit view purpose from select table **/
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
	
  	/** Add Check Exits records for select table **/
	public function exit_details($exit_data) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where($exit_data);
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
    }
	
  	/** Edit Check Exits records  for select table**/
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
  	/** Add records for select table **/
	public function add_record(){
		
		$set_data = array(
						'content' => $this->input->post('content'),
						'status' => 1,
						'create_date_time' => date('Y-m-d H:i:s')
					);
		$result = $this->db->insert($this->table_name, $set_data); 
		return $result;
	}
 
  	/** Update records for select table **/
	public function update_record($id){
		$set_data = array(
						'content' => $this->input->post('content'),
						'status' => 1,
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
 
  	/** Delete records for select table **/
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