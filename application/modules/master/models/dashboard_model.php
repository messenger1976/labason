<?php 
class dashboard_model extends CI_Model {
	
	public $table_customer = 'tbl_addcustomer';
	public $table_customer_reading = 'tbl_addcustomer_reading';
	public $table_expenses = 'tbl_addexpenses';
	public $table_technical = 'tbl_technical';
	public $table_meter_customer = 'tbl_addmetercustomer';
	public $table_monthly_customer = 'tbl_monthlycustomer';
	public $table_payrol = 'tbl_payrols';
	public $table_zone = 'tbl_zone';
	public $table_leaking_ledger = 'tbl_leaking_ledger';
	public $table_sms_notifications = 'tbl_sms_notifications';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
 	/** In Function Get count from select table **/
    public function get_total_customers() {
        $this->db->select("*");
		$this->db->from($this->table_customer);
		//$this->db->group_by('customer_id');
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
    }   
	/** In Function Get count from select table **/
    public function get_total_meter_customers() {
        $this->db->select("*");
		$this->db->from($this->table_customer);
		$this->db->where('customer_type','metercustomer');
		//$this->db->group_by('customer_id');
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
    }   
	/** In Function Get count from select table **/
    public function get_total_monthly_customers() {
        $this->db->select("*");
		$this->db->from($this->table_customer);
		$this->db->where('customer_type','monthlycustomer');
		//$this->db->group_by('customer_id');
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
    }   
	/** In Function Get count from select table **/
    public function get_total_expenses() {
        $this->db->select("*");
		$this->db->from($this->table_expenses);
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
    }   
	/** In Function Get count from select table **/
    public function get_total_meter_pay() {
        $this->db->select("sum(total) as total");
		$this->db->from($this->table_meter_customer);
		$this->db->where('status','1');
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }   
	/** In Function Get count from select table **/
    public function get_total_monthly_pay() {
        $this->db->select("sum(amount) as total");
		$this->db->from($this->table_monthly_customer);
		$this->db->where('status','1');
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }   
	/** In Function Get count from select table **/
    public function get_total_leaking_balance() {
        $this->db->select("sum(leaking_total_amount) as total");
		$this->db->from($this->table_leaking_ledger);
		$this->db->where('leaking_status',5);
		$this->db->or_where('leaking_status',2);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    } 
	/** In Function Get count from select table **/
    public function get_total_technical_problems() {
        $this->db->select("*");
		$this->db->from($this->table_technical);
		$this->db->where('status !=',5);
		$this->db->where('status !=',6);
		$this->db->where('deleted_rec',0);
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
    }   
	/** In Function Get count from select table **/
    public function get_total_solved_problems() {
        $this->db->select("*");
		$this->db->from($this->table_technical);
		$this->db->where('status','1');
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
    } 
	/** In Function Get count from select table **/
    public function get_customers_list() {
		//$sql = "SELECT CONCAT(first_name, ' ', last_name) AS full_name FROM ".$this->table_customer." WHERE status = ?";
		//$query = $this->db->query($sql, array('active'));
		$sql = "SELECT cust.*,CONCAT(cust.last_name,', ', cust.first_name) AS full_name FROM ".$this->table_customer." as cust ORDER BY cust.last_name, cust.first_name";
		$query = $this->db->query($sql);
		//$result = $query->result();

        //$this->db->select("*,CONCAT(last_name,` `,first_name) as fullname");
		//$this->db->from($this->table_customer);
		//$this->db->order_by('id','desc');
		//$this->db->group_by('customer_id');
		//$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    } 
	/** In Function Get count from select table **/
    public function get_expenses_list() {
        $this->db->select("*");
		$this->db->from($this->table_expenses);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }
		 public function record_count() {
        return $this->db->count_all($this->table_expenses);
    }
	/** In Function Get count from select table **/
    public function get_month_expenses_list() {
		$date = date('Y-m-d', strtotime('today - 30 days'));
        $this->db->select("*");
		$this->db->from($this->table_expenses);
		$this->db->where('create_date_time >',$date);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }   
	/** In Function Get count from select table **/
    public function get_list_monthly_income() {
		$date = date('Y-m-d', strtotime('today - 30 days'));
        $this->db->select("*,
		(Select first_name from ".$this->table_customer." where ".$this->table_customer.".customer_id = ".$this->table_monthly_customer.".customer_id) as name
		");
		$this->db->from($this->table_monthly_customer);
		$this->db->where('create_date_time >',$date);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }   
	/** In Function Get count from select table **/
    public function get_list_meter_income() {
		$date = date('Y-m-d', strtotime('today - 30 days'));
        $this->db->select("*,
		(Select first_name from ".$this->table_customer." where ".$this->table_customer.".customer_id = ".$this->table_meter_customer.".customer_id) as name
		");
		$this->db->from($this->table_meter_customer);
		$this->db->where('create_date_time >',$date);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }  
    public function get_income_metercustomer(){
		$this->db->select('SUM(grand_total) as total1');
		$this->db->from($this->table_meter_customer);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_income_monthlycustomer(){
		$this->db->select('SUM(paidamount) as total2');
		$this->db->from($this->table_monthly_customer);
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
	public function get_total_sales($monthrep,$yearrep,$status='') {
        $this->db->select('SUM(amount) as total');
		$this->db->from($this->table_customer_reading);
		$this->db->where('month',$monthrep);
		$this->db->where('year',$yearrep);
		if($status==1){
			$this->db->where('status = 1');
		}else if($status==0){
			$this->db->where('status', 0);
		}
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }
	public function get_cust_by_zone($zone){
		$this->db->select('COUNT(id) as count_id');
		$this->db->from($this->table_customer);
		
		if($zone==1){
			$this->db->where('zone',12);
		}else if($zone==2){
			$this->db->where('zone',10);
		}else if($zone==3){
			$this->db->where('zone',9);
		}
		else if($zone==4){
			$this->db->where('zone',7);
		}
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	// Get all zones with customer counts dynamically
	public function get_all_zones_with_customer_counts(){
		$this->db->select('z.id, z.zone, COUNT(c.id) as count_id');
		$this->db->from($this->table_zone . ' as z');
		$this->db->join($this->table_customer . ' as c', 'z.id = c.zone', 'left');
		$this->db->where('z.status', '1');
		$this->db->group_by('z.id, z.zone');
		$this->db->order_by('z.order_series', 'asc');
		$this->db->order_by('z.id', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function get_mytickets_chart($status){
		$this->db->select('COUNT(id) as count_id');
		$this->db->from($this->table_technical);
		
		if($status==0){
			$this->db->where('status',0);
		}else if($status==1){
			$this->db->where('status',1);
		}else if($status==2){
			$this->db->where('status',2);
		}else if($status==3){
			$this->db->where('status',3);
		}else if($status==4){
			$this->db->where('status',4);
		}else if($status==5){
			$this->db->where('status',5);
		}else if($status==6){
			$this->db->where('status',6);
		}
		$this->db->where('deleted_rec',0);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	/** Get distinct years from sales data **/
	public function get_available_years() {
		// Get all distinct years - handle both integer and string year columns
		$this->db->select('year');
		$this->db->from($this->table_customer_reading);
		$this->db->where('year IS NOT NULL');
		$this->db->group_by('year');
		$query = $this->db->get();
		$result = $query->result_array();
		
		// Extract years into a simple array and convert to integers for consistency
		$years = array();
		foreach($result as $row) {
			if(isset($row['year'])) {
				$year = trim($row['year']);
				// Handle both string and integer years
				if(is_numeric($year)) {
					$yearInt = (int)$year;
					// Only add valid years (between 1900 and 2100) and not zero
					if($yearInt >= 1900 && $yearInt <= 2100 && !in_array($yearInt, $years)) {
						$years[] = $yearInt;
					}
				}
			}
		}
		
		// Add current year if not already in the list
		$currentYear = (int)date('Y');
		if(!in_array($currentYear, $years)) {
			$years[] = $currentYear;
		}
		
		// Sort descending and remove duplicates
		$years = array_unique($years);
		rsort($years);
		
		return $years;
	}
	
}
?>