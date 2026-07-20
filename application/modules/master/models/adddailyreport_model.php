<?php 
    class adddailyreport_model extends CI_Model {
	public $table_name = 'tbl_addcustomer';
	public $table_billing = 'tbl_feesplaning';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_assets = 'tbl_addassets';
	public $table_users = 'tbl_responsibilities_user';
	public $table_zone = 'tbl_zone';
	public $table_employee = 'tbl_addemployee';
	public $table_jobtitle = 'tbl_jobtitle';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
	/** In Function Get all records from select table **/
    
	 public function get_metercustomer_records($from,$zone='',$grouping=1,$cashier=0){
		$this->db->select('tbl_addcustomer.customer_id, tbl_addcustomer.customer_type, tbl_addcustomer.first_name,tbl_addcustomer.last_name,tbl_addcustomer.middle_name,
		(SELECT zone FROM tbl_zone WHERE tbl_zone.id='.$this->table_name.'.zone) as zone, 
		(SELECT employee_name FROM '.$this->table_users.' WHERE '.$this->table_users.'.id='.$this->table_meter.'.userid) as user,
		tbl_addcustomer_reading.amount as reading_amount, 
		tbl_addcustomer_reading.sc_discount as sc_discount,
		tbl_billing_period.bp_due_date as due_date,
		SUM(tbl_addcustomer_reading.maintenance_fee) as total_wmmf,
		SUM(tbl_addcustomer_reading.franchise_fee_amount) as total_franchise_fee,
		SUM(CASE WHEN (tbl_addmetercustomer.amount - tbl_addcustomer_reading.unit_price - tbl_addcustomer_reading.maintenance_fee - tbl_addcustomer_reading.franchise_fee_amount) <= 0 THEN 0 ELSE tbl_addmetercustomer.amount - tbl_addcustomer_reading.unit_price - tbl_addcustomer_reading.maintenance_fee - tbl_addcustomer_reading.franchise_fee_amount END) AS total_penalty,
		SUM(
			CASE
				WHEN tbl_billing_period.bp_due_date IS NOT NULL
					AND MONTH(tbl_addmetercustomer.date) = MONTH(tbl_billing_period.bp_due_date)
					AND YEAR(tbl_addmetercustomer.date) = YEAR(tbl_billing_period.bp_due_date)
				THEN tbl_addcustomer_reading.unit_price
				WHEN tbl_billing_period.bp_due_date IS NULL
					AND (tbl_addmetercustomer.amount - tbl_addcustomer_reading.unit_price - tbl_addcustomer_reading.maintenance_fee - tbl_addcustomer_reading.franchise_fee_amount) <= 0
				THEN tbl_addcustomer_reading.unit_price
				ELSE 0
			END
		) AS current_amount,
		SUM(
			CASE
				WHEN tbl_billing_period.bp_due_date IS NOT NULL
					AND (MONTH(tbl_addmetercustomer.date) <> MONTH(tbl_billing_period.bp_due_date)
						OR YEAR(tbl_addmetercustomer.date) <> YEAR(tbl_billing_period.bp_due_date))
				THEN tbl_addcustomer_reading.unit_price
				WHEN tbl_billing_period.bp_due_date IS NULL
					AND (tbl_addmetercustomer.amount - tbl_addcustomer_reading.unit_price - tbl_addcustomer_reading.maintenance_fee - tbl_addcustomer_reading.franchise_fee_amount) > 0
				THEN tbl_addcustomer_reading.unit_price
				ELSE 0
			END
		) AS arrears_amount,
		tbl_addmetercustomer.*');
		$this->db->from('tbl_addmetercustomer');
		$this->db->join('tbl_addcustomer', 'tbl_addmetercustomer.customer_id = tbl_addcustomer.customer_id');
		$this->db->join('tbl_addcustomer_reading', 'tbl_addmetercustomer.customer_id = tbl_addcustomer_reading.customer_id and tbl_addmetercustomer.month=tbl_addcustomer_reading.month and tbl_addmetercustomer.year=tbl_addcustomer_reading.year','left');
		$this->db->join('tbl_billing_period', 'tbl_billing_period.bp_id = tbl_addcustomer_reading.bp_id', 'left');
		$this->db->where('tbl_addmetercustomer.date',$from);
		//$this->db->where('tbl_addmetercustomer.date <=',$to);
		if($zone!=0 && $zone!=''){
			$this->db->where('tbl_addcustomer.zone',$zone);
		}
		if($cashier != 0 && $cashier != ''){
			$this->db->where('tbl_addmetercustomer.userid', (int)$cashier);
		}
		
		// Order by OR number if grouping is disabled, otherwise by name
		if($grouping == 0 || $grouping == ''){
			$this->db->order_by('tbl_addmetercustomer.or_number','asc');
		} else {
			$this->db->order_by('last_name','asc');
			$this->db->order_by('first_name','asc');
		}
		$this->db->group_by('tbl_addmetercustomer.or_number, tbl_addcustomer.customer_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	public function get_metercustomer_orphan_records($from,$cashier=0,$grouping=1){
		$this->db->select('
		tbl_addmetercustomer.customer_id,
		\'Unknown\' as customer_type,
		\'\' as first_name,
		COALESCE(NULLIF(TRIM(tbl_addmetercustomer.name), \'\'), \'MISSING CUSTOMER\') as last_name,
		\'\' as middle_name,
		\'UNASSIGNED / MISSING CUSTOMER\' as zone,
		(SELECT employee_name FROM '.$this->table_users.' WHERE '.$this->table_users.'.id='.$this->table_meter.'.userid) as user,
		tbl_addcustomer_reading.amount as reading_amount,
		tbl_addcustomer_reading.sc_discount as sc_discount,
		tbl_billing_period.bp_due_date as due_date,
		SUM(tbl_addcustomer_reading.maintenance_fee) as total_wmmf,
		SUM(tbl_addcustomer_reading.franchise_fee_amount) as total_franchise_fee,
		SUM(CASE WHEN (tbl_addmetercustomer.amount - tbl_addcustomer_reading.unit_price - tbl_addcustomer_reading.maintenance_fee - tbl_addcustomer_reading.franchise_fee_amount) <= 0 THEN 0 ELSE tbl_addmetercustomer.amount - tbl_addcustomer_reading.unit_price - tbl_addcustomer_reading.maintenance_fee - tbl_addcustomer_reading.franchise_fee_amount END) AS total_penalty,
		SUM(
			CASE
				WHEN tbl_billing_period.bp_due_date IS NOT NULL
					AND MONTH(tbl_addmetercustomer.date) = MONTH(tbl_billing_period.bp_due_date)
					AND YEAR(tbl_addmetercustomer.date) = YEAR(tbl_billing_period.bp_due_date)
				THEN tbl_addcustomer_reading.unit_price
				WHEN tbl_billing_period.bp_due_date IS NULL
					AND (tbl_addmetercustomer.amount - tbl_addcustomer_reading.unit_price - tbl_addcustomer_reading.maintenance_fee - tbl_addcustomer_reading.franchise_fee_amount) <= 0
				THEN tbl_addcustomer_reading.unit_price
				ELSE 0
			END
		) AS current_amount,
		SUM(
			CASE
				WHEN tbl_billing_period.bp_due_date IS NOT NULL
					AND (MONTH(tbl_addmetercustomer.date) <> MONTH(tbl_billing_period.bp_due_date)
						OR YEAR(tbl_addmetercustomer.date) <> YEAR(tbl_billing_period.bp_due_date))
				THEN tbl_addcustomer_reading.unit_price
				WHEN tbl_billing_period.bp_due_date IS NULL
					AND (tbl_addmetercustomer.amount - tbl_addcustomer_reading.unit_price - tbl_addcustomer_reading.maintenance_fee - tbl_addcustomer_reading.franchise_fee_amount) > 0
				THEN tbl_addcustomer_reading.unit_price
				ELSE 0
			END
		) AS arrears_amount,
		tbl_addmetercustomer.*', FALSE);
		$this->db->from('tbl_addmetercustomer');
		$this->db->join('tbl_addcustomer', 'tbl_addmetercustomer.customer_id = tbl_addcustomer.customer_id', 'left');
		$this->db->join('tbl_addcustomer_reading', 'tbl_addmetercustomer.customer_id = tbl_addcustomer_reading.customer_id and tbl_addmetercustomer.month=tbl_addcustomer_reading.month and tbl_addmetercustomer.year=tbl_addcustomer_reading.year','left');
		$this->db->join('tbl_billing_period', 'tbl_billing_period.bp_id = tbl_addcustomer_reading.bp_id', 'left');
		$this->db->where('tbl_addmetercustomer.date',$from);
		$this->db->where('tbl_addcustomer.customer_id IS NULL', NULL, FALSE);
		if($cashier != 0 && $cashier != ''){
			$this->db->where('tbl_addmetercustomer.userid', (int)$cashier);
		}

		if($grouping == 0 || $grouping == ''){
			$this->db->order_by('tbl_addmetercustomer.or_number','asc');
		} else {
			$this->db->order_by('tbl_addmetercustomer.or_number','asc');
		}
		$this->db->group_by('tbl_addmetercustomer.or_number, tbl_addmetercustomer.customer_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	public function get_cashiers($cashier_id = 0){
		$this->db->select('id, employee_name, username');
		$this->db->from($this->table_users);
		$this->db->where('status', '1');
		if($cashier_id != 0){
			$this->db->where('id', (int)$cashier_id);
		}
		$this->db->order_by('employee_name', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function get_zone($zone_id=0) {
        $this->db->select("*");
		$this->db->from($this->table_zone);
		//$this->db->order_by('id','desc');
		$this->db->where('status','1');
		if($zone_id!=0){
			$this->db->where('id',$zone_id);
		}
		$this->db->order_by('order_series','asc');
		
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }

	public function get_employee($emp='') {
        $this->db->select($this->table_employee.".*,".$this->table_jobtitle.".job_title as jobtitle");
		$this->db->from($this->table_employee);
		$this->db->join($this->table_jobtitle, $this->table_employee.'.job_title = '.$this->table_jobtitle.'.id','left');
		if($emp!=''){
			$this->db->where($this->table_employee.'.id',$emp);
		}
		$this->db->order_by($this->table_employee.'.last_name','asc');
		
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }

	 public function get_monthycustomer_records($from,$to,$type){
		$this->db->select('tbl_addcustomer.customer_id, tbl_addcustomer.customer_type, tbl_monthlycustomer.*');
		$this->db->from('tbl_monthlycustomer');
		$this->db->join('tbl_addcustomer', 'tbl_monthlycustomer.customer_id = tbl_addcustomer.customer_id');
		$this->db->where('tbl_monthlycustomer.date >=',$from);
		$this->db->where('tbl_monthlycustomer.date <=',$to);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	public function get_payrol_records($from,$to,$type){
		$this->db->select('*');
		$this->db->from($this->table_payrol);
		$this->db->where('date >=',$from);
		$this->db->where('date <=',$to);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	public function get_expense_records($from,$to,$type){
		$this->db->select('tbl_expensetype.id as extid, tbl_expensetype.expensestype_name, tbl_addexpenses.*');
		$this->db->from('tbl_addexpenses');
		$this->db->join('tbl_expensetype','tbl_addexpenses.id = tbl_addexpenses.expenses_type');
		$this->db->where('date >=',$from);
		$this->db->where('date <=',$to);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	public function get_assets_records($from,$to,$type){
		$this->db->select('*');
		$this->db->from($this->table_assets);
		$this->db->where('date >=',$from);
		$this->db->where('date <=',$to);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	/*public function get_daily_records($from,$to,$type){
		$this->db->select('tbl_addmetercustomer.invoice_id as mid, tbl_addmetercustomer.customer_id as mcus_id,        tbl_addmetercustomer.pay_amount, tbl_addmetercustomer.date as mdate, tbl_addmetercustomer.status as mstat, 
		tbl_monthlycustomer.invoice_id as wid, tbl_monthlycustomer.customer_id as wcus_id, tbl_monthlycustomer.paidamount, tbl_monthlycustomer.date as wdate, tbl_monthlycustomer.status as wstat, tbl_addcustomer.*');
		$this->db->from('tbl_addcustomer');
		$this->db->join('tbl_addmetercustomer', 'tbl_addcustomer.customer_id = tbl_addmetercustomer.customer_id');
		$this->db->join('tbl_monthlycustomer', 'tbl_addcustomer.customer_id = tbl_monthlycustomer.customer_id');
		$this->db->where('tbl_addmetercustomer.date >=',$from);
		$this->db->where('tbl_addmetercustomer.date <=',$to);
		$this->db->where('tbl_monthlycustomer.date >=',$from);
		$this->db->where('tbl_monthlycustomer.dat1 <=',$to);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}*/
	
	 /*public function get_daily_records($fromdate,$todate,$type){
		$this->db->select('tbl_zone.id as zoneid, tbl_zone.zone as zonena, tbl_feesplaning.id as feesid, tbl_feesplaning.name as beelname, tbl_feesplaning.days as days, tbl_feesplaning.amount as amountf, tbl_addmetercustomer.*');
		$this->db->from('tbl_addmetercustomer');
		$this->db->join('tbl_monthlycustomer', 'tbl_addcustomer.zone = tbl_zone.id');
		$this->db->join('tbl_addexpenses', 'tbl_addcustomer.billingplans = tbl_feesplaning.id');
		$this->db->join('tbl_addassets', 'tbl_addcustomer.billingplans = tbl_feesplaning.id');
		$this->db->where('tbl_addcustomer.id',$cusid);
		$this->db->where('tbl_addcustomer.customer_id',$customer);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}*/
	public function get_customer_form($cusid, $customer){
		$this->db->select('tbl_zone.id as zoneid, tbl_zone.zone as zonena, tbl_addcustomer.*');
		$this->db->from($this->tbl_addcustomer);
		$this->db->join('tbl_zone', 'tbl_addcustomer.zone = tbl_zone.id');
		$this->db->where('tbl_addcustomer.id',$cusid);
		$this->db->where('tbl_addcustomer.customer_id',$customer);
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
		$this->db->from($this->table_name);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
}
?>
	
