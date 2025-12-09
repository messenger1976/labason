<?php 
class addledger_model extends CI_Model {
	public $table_name = 'tbl_ledgers';
//	public $table_pay = 'tbl_payrols';
//	public $table_job_title = 'tbl_jobtitle';
//	public $table_meter = 'tbl_addmetercustomer';
//	public $table_monthly = 'tbl_monthlycustomer';
//	public $table_expenses = 'tbl_addexpenses';
//	public $table_payrol = 'tbl_payrols';
//	public $table_customer = 'tbl_addcustomer';
	public $table_account ='tbl_subaccountgroup';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
	/** In Function Get all records from select table **/
    public function get_all_records() {
        $this->db->select("*,(Select account_name  from ".$this->table_account." where ".$this->table_account.".id = ".$this->table_name.".account_id) as subName");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	public function accountgroup(){
		$this->db->select("*");
		$this->db->from($this->table_account);
		//$this->db->where('id','1');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }		
	
	
 	/** In Function Get single records for edit view purpose from select table **/
    public function get_single_record($id='') {
        $this->db->select("*,(Select account_name  from ".$this->table_account." where ".$this->table_account.".id = ".$this->table_name.".account_id) as subName");
		$this->db->from($this->table_name);
		if($id != ''){
			$this->db->where("id",$id);
			$query = $this->db->get();
			//echo $this->db->last_query();
			$result = $query->row_array();
		}
		return $result;
    }
	
    public function get_job_title() {
        $this->db->select("*");
		$this->db->from($this->table_job_title);
		$this->db->order_by('id','desc');
		$this->db->where("status",1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
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
		//$brcE     	= substr(rand(1,1000000),0,4); 
		//$employee_id  = 'WT'.$brcE;
		$set_data = array(
		                  'account_id' =>mysql_real_escape_string($this->input->post('acount_group')),
						  'ledgerName' =>  mysql_real_escape_string($this->input->post('ledgerName')),
						  'balance' => mysql_real_escape_string($this->input->post('balance')),
						  'create_date_time' => date('Y-m-d H:i:s'),
						  'update_date_time' => date('Y-m-d H:i:s'),
					);
					
				
		$result = $this->db->insert($this->table_name, $set_data); 
		return $result;
	}
  	/** In Function Update records for select table **/
	public function update_record($id){
		//$brcE     	= substr(rand(1,1000000),0,4); 
		//$employee_id  = 'WT'.$brcE;
		$set_data = array(
		                 'account_id' =>mysql_real_escape_string($this->input->post('acount_group')),
						  'ledgerName' =>  mysql_real_escape_string($this->input->post('ledgerName')),
						  'balance' => mysql_real_escape_string($this->input->post('balance')),
						  'update_date_time' => date('Y-m-d H:i:s'),
						
						);
						
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	
  	/** In Function Delete records for select table **/
	public function delete_record($id){
		$this->db->where('id',$id);
		$result = $this->db->delete($this->table_name); 
		return $result;
	}
	
  	/** In Function Status Update records for select table **/
	public function status_record($id,$status){
		$sts = ($status == 1 ? 0 : 1);
		$set_data = array(
						'status' => $sts
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	public function get_addledger_records($ledger_id){ 
        $this->db->select("*,(Select account_name  from ".$this->table_account." where ".$this->table_account.".id = ".$this->table_name.".account_id) as subName");
		$this->db->from($this->table_name);  
		if($ledger_id!=''){
			$this->db->where('id',$ledger_id);
		}	
		$query = $this->db->get();
		//echo $this->db->last_query();
		//exit;
		$result = $query->result_array();
		return $result;		
	}	
	/*	public function get_addemployee_payrecords($employee_id){ 
        $this->db->select("*,(Select job_title from ".$this->table_job_title." where ".$this->table_job_title.".id = ".$this->table_name.".job_title) as job_title");
		$this->db->from($this->table_name);
		$this->db->join($this->table_pay, $this->table_pay.'.employee_id ='.$this->table_name.'.employee_id');
		if($employee_id!=''){
			$this->db->where($this->table_pay.'.employee_id',$employee_id);
		}
		$this->db->group_by($this->table_pay.'.id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;		
	}	
	public function get_employee_form($empid, $employee_id){
		$this->db->select('tbl_jobtitle.id as job_id, tbl_jobtitle.job_title as jobtitle,tbl_addemployee.*');
		$this->db->from('tbl_addemployee');
		$this->db->join('tbl_jobtitle', 'tbl_addemployee.job_title = tbl_jobtitle.id');
		$this->db->where('tbl_addemployee.id',$empid);
		$this->db->where('tbl_addemployee.employee_id',$employee_id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_income_metercustomer(){
		$this->db->select('SUM(pay_amount) as total1');
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
	}*/
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