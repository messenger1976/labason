<?php 
class addexpenses_model extends CI_Model {
	public $table_name = 'tbl_addexpenses';
	public $table_extype = 'tbl_expensetype';
	public $table_meter = 'tbl_addmetercustomer';
	public $tbl_payrols = 'tbl_payrols';
	public $table_address = 'tbl_web_settings';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_customer = 'tbl_addcustomer';
	//public $table_account ='tbl_subaccountgroup';
	public $table_ledger ='tbl_ledgers';
	public $table_transactions ='tbl_transactions';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
    // to get account sub group
	/*public function accountgroup(){
		$this->db->select("*");
		$this->db->from($this->table_account);
		//$this->db->where('id','1');
		//$this->db->where('account_id','5');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }	*/
    // to get all ledgers
	public function fetchLedger(){
		$this->db->select("*");
		$this->db->from($this->table_ledger);
		//$this->db->where('id','1');
		//$this->db->where('account_id','5');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }	
	/** In Function Get all records from select table **/
    public function get_extype() {
        $this->db->select("*");
		$this->db->from($this->table_extype);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }
	/** In Function Get all records from select table **/
    public function get_all_records() {
       /* $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;*/
		$this->db->select('tbl_expensetype.id as extid, tbl_expensetype.expensestype_name, tbl_addexpenses.*');
		$this->db->from('tbl_addexpenses');
		$this->db->join('tbl_expensetype','tbl_expensetype.id = tbl_addexpenses.expenses_type');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }
	 public function get_address() {
        $this->db->select("*");
		$this->db->from($this->table_address);
		$this->db->where('id','1');
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }	
	
	
 	/** In Function Get single records for edit view purpose from select table **/
    public function get_single_record($id='') {
       // $this->db->select("*");
		//$this->db->from($this->table_name);
		if($id != ''){
			//$this->db->select('tbl_subaccountgroup.id as subid, tbl_subaccountgroup.account_name as subName, tbl_ledgers.id as lid, tbl_ledgers.ledgerName as ledgerName,tbl_addexpenses.*');
			$this->db->select('tbl_ledgers.id as lid, tbl_ledgers.ledgerName as ledgerName,tbl_addexpenses.*');
			$this->db->from('tbl_addexpenses');
			//$this->db->join('tbl_subaccountgroup','tbl_subaccountgroup.id = tbl_addexpenses.account_id');
			$this->db->join('tbl_ledgers','tbl_ledgers.id = tbl_addexpenses.ledger_id');
			$this->db->where("tbl_addexpenses.id",$id);
			$query = $this->db->get();
			//echo $this->db->last_query();
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
		
		//$brcE     	= substr(rand(1,1000000),0,4); 
		//$expenses_id  = 'WT'.$brcE;
		$total = $this->input->post('quantity')*$this->input->post('amount');
		$set_data = array(
						'ledger_id' => $this->input->post('ledger_id'),
					//	'account_id' => $this->input->post('account_id'),
		                'expenses_id' => $this->input->post('expenses_id'),
						'invoice_id' => $this->input->post('time_format'),
						'expenses_type' => $this->input->post('expenses_type'),
						'quantity' => mysql_real_escape_string($this->input->post('quantity')),
						'amount' => mysql_real_escape_string($this->input->post('amount')),
						'date' => date('Y-m-d',strtotime($this->input->post('date'))),
					    'total' => $total,
					    'create_date_time' => date('Y-m-d H:i:s'),
					    'update_date_time' => date('Y-m-d H:i:s'),
					);
		
		$result = $this->db->insert($this->table_name, $set_data);  
		// save data(first entry) on transaction table
		$lastId = $this->db->insert_id(); 
		$set_data2 = array(
						'tableName' => 'addexpenses',
						'transaction_id' => $lastId,
						'ledger_id' => $this->input->post('expenses_type'),
						'ledger_id_for' => 'expenses_type',
						'credit' => $total,
					    'date' => date('Y-m-d',strtotime($this->input->post('date'))),
					    'create_date_time' => date('Y-m-d H:i:s'),
					    'update_date_time' => date('Y-m-d H:i:s'),
					);
		$result2 = $this->db->insert($this->table_transactions, $set_data2);
		// save data(second entry) on transaction table
		/*$set_data3 = array(
						'tableName' => 'addexpenses',
						'transaction_id' => $lastId,
						'ledger_id' => $this->input->post('ledger_id'),
						'ledger_id_for' => 'ledger_id',
		                'debit' => $total,
					    'date' => date('Y-m-d',strtotime($this->input->post('date'))),
					    'create_date_time' => date('Y-m-d H:i:s'),
					    'update_date_time' => date('Y-m-d H:i:s'),
					);
		$result3 = $this->db->insert($this->table_transactions, $set_data3);*/
		//print_r($result3);
		return $result2;
	}
  	/** In Function Update records for select table **/
	public function update_record($id){
		//$brcE     	= substr(rand(1,1000000),0,4); 
		//$expenses_id  = 'WT'.$brcE;
		$total=$this->input->post('quantity')*$this->input->post('amount');
		$set_data = array(
							'ledger_id' => $this->input->post('ledger_id'),
							//'account_id' => $this->input->post('account_id'),
		                    'expenses_id' => $this->input->post('expenses_id'),
							'expenses_type' => $this->input->post('expenses_type'),
							'quantity' => mysql_real_escape_string($this->input->post('quantity')),
						
							'amount' => mysql_real_escape_string($this->input->post('amount')),
							'date' => date('Y-m-d',strtotime($this->input->post('date'))),
					    	//'debit' => mysql_real_escape_string($this->input->post('debit')),
									
							//'credit' => mysql_real_escape_string($this->input->post('credit')),
						
							'update_date_time' => date('Y-m-d H:i:s'),
							'total' => $total,
					
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
	/*public function status_record($id,$status){
		$sts = ($status == 1 ? 0 : 1);
		$set_data = array(
						'status' => $sts
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}*/
	public function get_addexpenses_records($fromdate,$todate){ 
        $this->db->select("*");
		$this->db->from($this->table_name);
		if($fromdate !=''){
			$this->db->where("date >= ",date('Y-m-d', strtotime($fromdate)));
		}
		if($todate !=''){
			$this->db->where("date <= ",date('Y-m-d', strtotime($todate)));
		}	
		$query = $this->db->get();
		//echo $this->db->last_query();
		//exit;
		$result = $query->result_array();
		return $result;		
	}
	public function get_addexpenses_balance_records($fromdate,$todate){ 
		$this->db->select("date,expenses_type,sum(amount) as expenses_debit");
		//,(Select sum(total) from ".$this->tbl_payrols." where ".$this->tbl_payrols.".date = ".$this->table_name.".date and ".$this->tbl_payrols.".status=1 limit 1) as employee_debit
		//,(Select sum(total) from ".$this->table_meter." where ".$this->table_meter.".date = ".$this->table_name.".date and ".$this->table_meter.".status=1 limit 1) as meter_cradit
		//,(Select sum(total) from ".$this->table_monthly." where ".$this->table_monthly.".date = ".$this->table_name.".date and ".$this->table_monthly.".status=1 limit 1) as monthly_cradit
		
		$this->db->from($this->table_name); 
		if($fromdate !=''){$this->db->where("date >= ",date('Y-m-d', strtotime($fromdate)));}
		if($todate !=''){$this->db->where("date <= ",date('Y-m-d', strtotime($todate)));}
		//$this->db->where("status","1");
		$this->db->group_by('date');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;	
		
	}	
	public function get_addpayrols_balance_records($fromdate,$todate){ 
		$this->db->select("date,title as expenses_type,sum(amount) as expenses_debit");
		$this->db->from($this->tbl_payrols); 
		if($fromdate !=''){$this->db->where("date >= ",date('Y-m-d', strtotime($fromdate)));}
		if($todate !=''){$this->db->where("date <= ",date('Y-m-d', strtotime($todate)));}
		$this->db->where("status","1");
		$this->db->group_by('date');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;	
	}	
	public function get_addmeter_balance_records($fromdate,$todate){ 
		$this->db->select("date,sum(total) as expenses_cradit");
		$this->db->from($this->table_meter); 
		if($fromdate !=''){$this->db->where("date >= ",date('Y-m-d', strtotime($fromdate)));}
		if($todate !=''){$this->db->where("date <= ",date('Y-m-d', strtotime($todate)));}
		$this->db->where("status","1");
		$this->db->group_by('date');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;	
	}	
	public function get_addmonthly_balance_records($fromdate,$todate){ 
		$this->db->select("date,sum(total) as expenses_cradit");
		$this->db->from($this->table_monthly); 
		if($fromdate !=''){$this->db->where("date >= ",date('Y-m-d', strtotime($fromdate)));}
		if($todate !=''){$this->db->where("date <= ",date('Y-m-d', strtotime($todate)));}
		$this->db->where("status","1");
		$this->db->group_by('date');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;	
	}	
	
	/*public function get_addexpenses_balance_records($fromdate,$todate) {
		$months = array('1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
		$credit=array();
		foreach($credit as $key => $value)
		{		
			$datetocheck = date('Y-'.$key.'-01');
			$lastday = date('t',strtotime($datetocheck));
			$month_start = strtotime(date('Y-'.$key.'-01'));
			$month_end = strtotime(date('Y-'.$key.'-'.$lastday));
	
			$this->db->select("table_name, sum(final_price) as total_amount");
			$this->db->from($this->orders);
			$this->db->where("order_status",'Confirmed');
			$this->db->where("order_date >= ",date('Y-m-d', $month_start));
			$this->db->where("order_date <= ",date('Y-m-d', $month_end));
			$this->db->order_by('id','desc');
			$query = $this->db->get();
			$result = $query->row_array();
			
			if($result['total'] != '')
			{
				$total[$value]=$result['month'];
			}else
			{
				$total[$value]=0;
			}
		}
		return $total;
    }*/
	
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
		$this->db->from($this->table_name);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_outcome_payroll(){
		$this->db->select('SUM(total) as extotal2');
		$this->db->from($this->tbl_payrols);
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
	public function getReceiptData($invoiceid){
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('id', $invoiceid);
		$query = $this->db->get();
		return $query->row_array();
		
	}
	public function getReceiptData_expense($expenseid){
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('expenses_id', $expenseid);
		$this->db->order_by('id', desc);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row_array();
		
	}
}


?>