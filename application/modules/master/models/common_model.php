<?php 
class Common_model extends CI_Model {
	
	public $table_web_settings = 'tbl_websettings';
	public $table_zone = 'tbl_zone'; //zone table
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_billing_period = 'tbl_billing_period';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_customer = 'tbl_addcustomer';
	public $table_months = 'tbl_months';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
 	/** In Function Get single records for edit view purpose from select table **/
    public function get_single_record() {
        $this->db->select("*");
		$this->db->from($this->table_web_settings);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }   

	/* Global common get zone records*/
	public function get_zone_records() {
        //$this->db->select("*");
		//$this->db->from($this->table_zone);
		$this->db->order_by($this->table_zone.'.id','desc');
		$query = $this->db->get($this->table_zone);
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }

	public function get_billingperiod_record($current_billingperiod='') {
        $this->db->select("*,(Select month_name from ".$this->table_months." where ".$this->table_months.".month_id	= ".$this->table_billing_period.".bp_period_month ) as month_name");
		$this->db->from($this->table_billing_period);
		
        $this->db->group_by('bp_period_month','bo_period_year');
		$this->db->order_by('bp_zone_id','asc');

		if($current_billingperiod!=''){
			$billing_period = explode(' ',$current_billingperiod);
			$this->db->where('bp_period_month',$billing_period[0]);
			$this->db->where('bp_period_year',$billing_period[1]);
		}else{
			$this->db->where('bp_status',1);
		}

		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
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