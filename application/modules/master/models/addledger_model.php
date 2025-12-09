<?php 
class addledger_model extends CI_Model {
	public $table_name = 'tbl_ledgers';
	public $table_pay = 'tbl_payrols';
//	public $table_job_title = 'tbl_jobtitle';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_customer = 'tbl_addcustomer';
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
    /** In Function Add records for select table **/
	public function add_record(){
		//$brcE     	= substr(rand(1,1000000),0,4); 
		//$employee_id  = 'WT'.$brcE;
		$set_data = array(
		                  'account_id' =>$this->input->post('acount_group'),
						  'ledgerName' =>  $this->input->post('ledgerName'),
						  'balance' => $this->input->post('balance'),
						  'accounttype' => $this->input->post('accounttype'),
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
		                 'account_id' =>$this->input->post('acount_group'),
						  'ledgerName' =>  $this->input->post('ledgerName'),
						  'balance' => $this->input->post('balance'),
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
	public function get_report_display()
	{

		$this->db->select('tableName,sum(credit) Credit,sum(debit) Debit,create_date_time');
		$this->db->from('tbl_transactions');
		//$this->db->join('tbl_addexpenses e', 'e.id = t.transaction_id');
		//$this->db->join('tbl_expensetype et', 'et.id = e.expenses_type');
		//$this->db->join('tbl_monthlycustomer mo', 'mo.id = t.transaction_id');
                //$this->db->join('tbl_addmetercustomer am', 'am.id = t.transaction_id');
		//$this->db->where('create_date_time = create_date_time);
		$this->db->where('create_date_time BETWEEN "'.'2016-08-01'.'" and "'.date('Y-m-d').'"');				
		$this->db->group_by('date(create_date_time)');	
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function get_report_display_search($fromdate,$todate)
	{
		$this->db->select('tableName,sum(credit) Credit,sum(debit) Debit,create_date_time');
		$this->db->from('tbl_transactions');
		//$this->db->join('tbl_addexpenses e', 'e.id = t.transaction_id');
		//$this->db->join('tbl_expensetype et', 'et.id = e.expenses_type');
		//$this->db->join('tbl_monthlycustomer mo', 'mo.id = t.transaction_id');
		//$this->db->join('tbl_addmetercustomer am', 'am.id = t.transaction_id');
		if($fromdate !='' || $todate !=''){
		$this->db->where('create_date_time BETWEEN "'. date('Y-m-d', strtotime($fromdate)). '" and "'. date('Y-m-d', strtotime($todate)).'"');
		}
			
		//$this->db->where('create_date_time BETWEEN "'.'2016-07-01'.'" and "'.date('2016-08-30').'"');
		$this->db->group_by('date(create_date_time)');	
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;	
	}
}
?>