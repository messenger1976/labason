<?php 
class addjournalvoucher_model extends CI_Model {
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
	public $table_employee = 'tbl_addemployee';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
    // to get account sub group
	public function accountgroup(){
		$this->db->select("*");
		$this->db->from($this->table_account);
		//$this->db->where('id','1');
		//$this->db->where('account_id','5');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }	
    // to get all ledgers
	public function fetchLedger(){
		$this->db->select("id as id, ledgerName as ledgerName");
		$this->db->from($this->table_ledger);
		//$this->db->where('id','1');
		//$this->db->where('account_id','5');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }	
     // to get all customers
	public function fetchCustomer(){
		//$this->db->select("id as id, CONCAT(first_name,'',last_name) as ledgerName");
		//$this->db->from($this->table_customer);
		//$query = $this->db->get();
		$sql = "SELECT id as id, CONCAT(first_name,' ',last_name) as ledgerName FROM tbl_addcustomer";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
    }	
     // to get all employee
	public function fetchEmployee(){
		//$this->db->select("id as id, CONCAT(first_name,'',last_name) as ledgerName");
		//$this->db->from($this->table_employee);
		$sql = "SELECT id as id, CONCAT(first_name,' ',last_name) as ledgerName FROM tbl_addemployee";
		$query = $this->db->query($sql);
		//$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }	
     // to get all expense type
	public function fetchExpenseType(){
		$this->db->select("id as id, expensestype_name as ledgerName");
		$this->db->from($this->table_extype);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }	
     // Next JV series number (JV-####), independent of payment transaction ids
	public function fetchTransaction(){
		$sql = "SELECT MAX(CAST(SUBSTRING(voucherNo, 4) AS UNSIGNED)) AS n
			FROM tbl_transactions
			WHERE voucherNo LIKE 'JV-%'";
		$query = $this->db->query($sql);
		$row = ($query && $query->num_rows() > 0) ? $query->row_array() : array();
		$n = isset($row['n']) ? (int) $row['n'] : 0;
		return $n;
	}

	public function next_voucher_no() {
		return 'JV-' . sprintf('%04d', $this->fetchTransaction() + 1);
	}

	public function has_particulars_column() {
		return $this->db->field_exists('particulars', $this->table_transactions);
	}

	public function has_voucher_for_column() {
		return $this->db->field_exists('voucher_for', $this->table_transactions);
	}

	/**
	 * Resolve display name for a ledger_id + ledger_id_for pair (no mysql_*).
	 */
	public function party_label($ledger_id, $ledger_id_for) {
		$ledger_id = trim((string) $ledger_id);
		$ledger_id_for = trim((string) $ledger_id_for);
		if ($ledger_id === '') {
			return '';
		}
		if ($ledger_id_for === 'employee_id') {
			$q = $this->db->query(
				"SELECT CONCAT(first_name,' ',last_name) AS nm FROM tbl_addemployee WHERE id = ? LIMIT 1",
				array((int) $ledger_id)
			);
		} elseif ($ledger_id_for === 'expenses_type') {
			$q = $this->db->query(
				"SELECT expensestype_name AS nm FROM tbl_expensetype WHERE id = ? LIMIT 1",
				array((int) $ledger_id)
			);
		} elseif ($ledger_id_for === 'customer_id') {
			$q = $this->db->query(
				"SELECT CONCAT(first_name,' ',last_name) AS nm FROM tbl_addcustomer WHERE id = ? LIMIT 1",
				array((int) $ledger_id)
			);
		} else {
			$q = $this->db->query(
				"SELECT ledgerName AS nm FROM tbl_ledgers WHERE id = ? LIMIT 1",
				array((int) $ledger_id)
			);
		}
		if (!$q || $q->num_rows() === 0) {
			return $ledger_id;
		}
		$row = $q->row_array();
		return isset($row['nm']) ? $row['nm'] : $ledger_id;
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
	/** Journal voucher lines only (not meter/expense auto postings) **/
    public function get_all_records() {
        $this->db->select("*");
		$this->db->from($this->table_transactions);
		$this->db->where('tableName', 'transactions');
		$this->db->like('voucherNo', 'JV-', 'after');
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;

		/*$this->db->select('tbl_expensetype.id as extid, tbl_expensetype.expensestype_name, tbl_addexpenses.*');
		$this->db->from('tbl_addexpenses');
		$this->db->join('tbl_expensetype','tbl_addexpenses.id = tbl_addexpenses.expenses_type');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;*/
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
        $this->db->select("*");
	    $this->db->from($this->table_transactions);
		if($id != ''){
			//$this->db->select('tbl_subaccountgroup.id as subid, tbl_subaccountgroup.account_name as subName, tbl_ledgers.id as lid, tbl_ledgers.ledgerName as ledgerName,tbl_addexpenses.*');
	//		$this->db->select('tbl_ledgers.id as id, tbl_ledgers.ledgerName as ledgerName,tbl_transactions.*');
	//		$this->db->from('tbl_transactions');
			//$this->db->join('tbl_subaccountgroup','tbl_subaccountgroup.id = tbl_addexpenses.account_id');
	//		$this->db->join('tbl_ledgers','tbl_ledgers.id = tbl_transactions.transaction_id');
	//		$this->db->where("tbl_transactions.id",$id);
			$this->db->where("id",$id);
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
		$tId = trim((string) $this->input->post('transaction_id'));
		$lId = trim((string) $this->input->post('ledger_id'));
		$amount = (float) $this->input->post('credit');
		$particulars = trim((string) $this->input->post('particulars'));
		$voucher = trim((string) $this->input->post('voucherNo'));
		$date_raw = trim((string) $this->input->post('date'));

		if ($voucher === '' || $tId === '' || $lId === '' || $amount <= 0) {
			return false;
		}
		if ($tId === $lId) {
			return false;
		}

		$piece = explode(' / ', $tId);
		$piece2 = explode(' / ', $lId);
		if (count($piece) < 2 || count($piece2) < 2) {
			return false;
		}

		$dt = DateTime::createFromFormat('d-m-Y', str_replace('/', '-', $date_raw));
		$date_ymd = ($dt instanceof DateTime) ? $dt->format('Y-m-d') : date('Y-m-d');
		$now = date('Y-m-d H:i:s');
		$has_particulars = $this->has_particulars_column();
		$has_voucher_for = $this->has_voucher_for_column();

		$set_data = array(
			'tableName' => 'transactions',
			'voucherNo' => $voucher,
			'transaction_id' => '',
			'ledger_id' => $piece[0],
			'ledger_id_for' => $piece[1],
			'debit' => number_format($amount, 2, '.', ''),
			'credit' => '',
			'date' => $date_ymd,
			'create_date_time' => $now,
			'update_date_time' => $now,
		);
		if ($has_voucher_for) {
			$set_data['voucher_for'] = 'debit';
		}
		if ($has_particulars) {
			$set_data['particulars'] = $particulars;
		}

		$set_data2 = array(
			'tableName' => 'transactions',
			'voucherNo' => $voucher,
			'transaction_id' => '',
			'ledger_id' => $piece2[0],
			'ledger_id_for' => $piece2[1],
			'debit' => '',
			'credit' => number_format($amount, 2, '.', ''),
			'date' => $date_ymd,
			'create_date_time' => $now,
			'update_date_time' => $now,
		);
		if ($has_voucher_for) {
			$set_data2['voucher_for'] = 'credit';
		}
		if ($has_particulars) {
			$set_data2['particulars'] = $particulars;
		}

		$this->db->trans_start();
		$this->db->insert($this->table_transactions, $set_data);
		$this->db->insert($this->table_transactions, $set_data2);
		$this->db->trans_complete();
		return $this->db->trans_status() !== false;
	}
  	/** In Function Update records for select table **/
	public function update_record($id){
		//$brcE     	= substr(rand(1,1000000),0,4); 
		//$expenses_id  = 'WT'.$brcE;
		$total=$this->input->post('quantity')*$this->input->post('amount');
		$set_data = array(
						'tableName' => 'transactions',
						'voucherNo' => $this->input->post('voucherNo'),
						//'transaction_id' => $this->input->post('transaction_id'),
						'ledger_id' => $this->input->post('transaction_id'),
						'ledger_id_for' => 'transaction_id',
						'debit' => $this->input->post('credit'),
						'date' => date('Y-m-d',strtotime($this->input->post('date'))),
					    'create_date_time' => date('Y-m-d H:i:s'),
					    'update_date_time' => date('Y-m-d H:i:s'),
					);
		$this->db->where('id',$id);
		$result = $this->db->insert($this->table_transactions, $set_data);
		$set_data2 = array(
						'tableName' => 'transactions',
						'voucherNo' => $this->input->post('voucherNo'),
						//'transaction_id' => $this->input->post('transaction_id'),
						'ledger_id' => $this->input->post('ledger_id'),
						'ledger_id_for' => 'ledger_id',
						'credit' => $this->input->post('credit'),
						'date' => date('Y-m-d',strtotime($this->input->post('date'))),
					    'update_date_time' => date('Y-m-d H:i:s'),
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_transactions, $set_data2); 
		return $result;
	}
	
  	/** In Function Delete records for select table **/
	public function delete_record($id){
		$this->db->where('id',$id);
		$result = $this->db->delete($this->table_transactions);  // add table name manually for voucher
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