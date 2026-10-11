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

	/* ------------------------------------------------------------------
	 * Analytics dashboard (one grouped query per widget).
	 * Bills: tbl_addcustomer_reading (status 1 = paid, 0 = unpaid; `amount`
	 * is the period's bill only — carried arrears live in `arrears`).
	 * Collections: tbl_addmetercustomer.grand_total (pay_amount is cash
	 * tendered and can include change).
	 * ------------------------------------------------------------------ */

	/** Billed / collected / m³ per billing month for one year. */
	public function get_billing_by_month($year) {
		$sql = "SELECT CAST(month AS UNSIGNED) AS m,
				COUNT(*) AS bills,
				SUM(amount + 0) AS billed,
				SUM(CASE WHEN status = 1 THEN amount + 0 ELSE 0 END) AS paid,
				SUM(CASE WHEN status = 0 THEN amount + 0 ELSE 0 END) AS unpaid,
				SUM(consumed + 0) AS consumed
			FROM ".$this->table_customer_reading."
			WHERE year = ? AND CAST(month AS UNSIGNED) BETWEEN 1 AND 12
			GROUP BY CAST(month AS UNSIGNED)";
		$rows = $this->db->query($sql, array((string)$year))->result_array();
		$out = array();
		for ($i = 1; $i <= 12; $i++) {
			$out[$i] = array('bills' => 0, 'billed' => 0, 'paid' => 0, 'unpaid' => 0, 'consumed' => 0);
		}
		foreach ($rows as $r) {
			$out[(int)$r['m']] = array(
				'bills'    => (int)$r['bills'],
				'billed'   => (float)$r['billed'],
				'paid'     => (float)$r['paid'],
				'unpaid'   => (float)$r['unpaid'],
				'consumed' => (float)$r['consumed'],
			);
		}
		return $out;
	}

	/** Latest two billing periods that actually have bills, newest first. */
	public function get_latest_billing_periods($limit = 2) {
		$sql = "SELECT CAST(year AS UNSIGNED) AS y, CAST(month AS UNSIGNED) AS m,
				COUNT(*) AS bills,
				SUM(amount + 0) AS billed,
				SUM(CASE WHEN status = 1 THEN amount + 0 ELSE 0 END) AS paid,
				SUM(consumed + 0) AS consumed
			FROM ".$this->table_customer_reading."
			WHERE CAST(month AS UNSIGNED) BETWEEN 1 AND 12
			GROUP BY CAST(year AS UNSIGNED), CAST(month AS UNSIGNED)
			HAVING bills >= 10
			ORDER BY y DESC, m DESC
			LIMIT ".(int)$limit;
		return $this->db->query($sql)->result_array();
	}

	/** Collections between two dates (inclusive). */
	public function get_collections_between($from, $to) {
		$sql = "SELECT COUNT(*) AS receipts, COALESCE(SUM(grand_total), 0) AS total
			FROM ".$this->table_meter_customer."
			WHERE status = '1' AND date BETWEEN ? AND ?";
		return $this->db->query($sql, array($from, $to))->row_array();
	}

	/** Daily collections for the last N days (zero-filled). */
	public function get_daily_collections($days = 30) {
		$to   = date('Y-m-d');
		$from = date('Y-m-d', strtotime('-'.((int)$days - 1).' days'));
		$sql = "SELECT date AS d, COUNT(*) AS receipts, SUM(grand_total) AS total
			FROM ".$this->table_meter_customer."
			WHERE status = '1' AND date BETWEEN ? AND ?
			GROUP BY date";
		$rows = $this->db->query($sql, array($from, $to))->result_array();
		$map = array();
		foreach ($rows as $r) { $map[$r['d']] = $r; }
		$out = array();
		for ($t = strtotime($from); $t <= strtotime($to); $t += 86400) {
			$d = date('Y-m-d', $t);
			$out[] = array(
				'date'     => $d,
				'receipts' => isset($map[$d]) ? (int)$map[$d]['receipts'] : 0,
				'total'    => isset($map[$d]) ? (float)$map[$d]['total'] : 0,
			);
		}
		return $out;
	}

	/** Collections by payment channel and by cashier within a date range. */
	public function get_collection_breakdown($from, $to) {
		$channels = $this->db->query(
			"SELECT payment_channel AS label, COUNT(*) AS receipts, SUM(grand_total) AS total
			FROM ".$this->table_meter_customer."
			WHERE status = '1' AND date BETWEEN ? AND ?
			GROUP BY payment_channel ORDER BY total DESC", array($from, $to))->result_array();
		$cashiers = $this->db->query(
			"SELECT COALESCE(NULLIF(username, ''), 'Unknown') AS label, COUNT(*) AS receipts, SUM(grand_total) AS total
			FROM ".$this->table_meter_customer."
			WHERE status = '1' AND date BETWEEN ? AND ?
			GROUP BY label ORDER BY total DESC", array($from, $to))->result_array();
		return array('channels' => $channels, 'cashiers' => $cashiers);
	}

	/** Unpaid-bill aging by number of unpaid bills per account. */
	public function get_receivable_aging() {
		$sql = "SELECT CASE WHEN n = 1 THEN 1 WHEN n = 2 THEN 2 ELSE 3 END AS bucket,
				COUNT(*) AS accounts, SUM(amt) AS total
			FROM (
				SELECT customer_id, COUNT(*) AS n, SUM(amount + 0) AS amt
				FROM ".$this->table_customer_reading."
				WHERE status = 0 AND amount + 0 > 0
				GROUP BY customer_id
			) x
			GROUP BY bucket ORDER BY bucket";
		$out = array(1 => array('accounts' => 0, 'total' => 0), 2 => array('accounts' => 0, 'total' => 0), 3 => array('accounts' => 0, 'total' => 0));
		foreach ($this->db->query($sql)->result_array() as $r) {
			$out[(int)$r['bucket']] = array('accounts' => (int)$r['accounts'], 'total' => (float)$r['total']);
		}
		return $out;
	}

	/** Accounts with the largest unpaid bills. */
	public function get_top_unpaid_accounts($limit = 10) {
		$sql = "SELECT x.customer_id, x.bills, x.amt,
				CONCAT(c.last_name, ', ', c.first_name) AS full_name, z.zone
			FROM (
				SELECT customer_id, COUNT(*) AS bills, SUM(amount + 0) AS amt
				FROM ".$this->table_customer_reading."
				WHERE status = 0 AND amount + 0 > 0
				GROUP BY customer_id
				ORDER BY amt DESC
				LIMIT ".(int)$limit."
			) x
			LEFT JOIN ".$this->table_customer." c ON c.customer_id = x.customer_id
			LEFT JOIN ".$this->table_zone." z ON z.id = c.zone
			ORDER BY x.amt DESC";
		return $this->db->query($sql)->result_array();
	}

	/** Connections and unpaid bills per active zone. */
	public function get_zone_summary() {
		$sql = "SELECT z.id, z.zone,
				COALESCE(cc.connections, 0) AS connections,
				COALESCE(ar.unpaid, 0) AS unpaid,
				COALESCE(ar.accounts, 0) AS unpaid_accounts
			FROM ".$this->table_zone." z
			LEFT JOIN (
				SELECT zone, COUNT(*) AS connections
				FROM ".$this->table_customer."
				WHERE status = 1
				GROUP BY zone
			) cc ON cc.zone = z.id
			LEFT JOIN (
				SELECT c.zone, SUM(r.amount + 0) AS unpaid, COUNT(DISTINCT r.customer_id) AS accounts
				FROM ".$this->table_customer_reading." r
				JOIN ".$this->table_customer." c ON c.customer_id = r.customer_id
				WHERE r.status = 0 AND r.amount + 0 > 0
				GROUP BY c.zone
			) ar ON ar.zone = z.id
			WHERE z.status = 1
			ORDER BY z.order_series ASC, z.id ASC";
		return $this->db->query($sql)->result_array();
	}

	/**
	 * Water consumption (m³) per billing month by classification category.
	 * Pass $monthTotals (from get_billing_by_month) so readings whose customer
	 * record is missing are still counted, as UNCLASSIFIED.
	 */
	public function get_consumption_by_category($year, $monthTotals = array()) {
		// Driving from customers into the reading index is ~4x faster than the reverse.
		$sql = "SELECT x.m, COALESCE(cc.class_cat_name, 'UNCLASSIFIED') AS cat, SUM(x.consumed) AS consumed
			FROM (
				SELECT STRAIGHT_JOIN CAST(r.month AS UNSIGNED) AS m, c.classification AS cls, SUM(r.consumed + 0) AS consumed
				FROM ".$this->table_customer." c
				JOIN ".$this->table_customer_reading." r ON r.customer_id = c.customer_id
				WHERE r.year = ? AND CAST(r.month AS UNSIGNED) BETWEEN 1 AND 12
				GROUP BY m, cls
			) x
			LEFT JOIN tbl_classification cl ON cl.class_id = x.cls
			LEFT JOIN tbl_classification_category cc ON cc.class_cat_id = cl.class_cat_id
			GROUP BY x.m, cat";
		$out = array();
		$matched = array_fill(1, 12, 0);
		foreach ($this->db->query($sql, array((string)$year))->result_array() as $r) {
			if (!isset($out[$r['cat']])) { $out[$r['cat']] = array_fill(1, 12, 0); }
			$out[$r['cat']][(int)$r['m']] += (float)$r['consumed'];
			$matched[(int)$r['m']] += (float)$r['consumed'];
		}
		foreach ($monthTotals as $mo => $row) {
			$gap = (float)$row['consumed'] - $matched[$mo];
			if ($gap > 0.5) {
				if (!isset($out['UNCLASSIFIED'])) { $out['UNCLASSIFIED'] = array_fill(1, 12, 0); }
				$out['UNCLASSIFIED'][$mo] += $gap;
			}
		}
		return $out;
	}

	/** Active customers grouped by classification category and by account type. */
	public function get_customer_mix() {
		$category = $this->db->query(
			"SELECT COALESCE(cc.class_cat_name, 'UNCLASSIFIED') AS label, COUNT(*) AS total
			FROM ".$this->table_customer." c
			LEFT JOIN tbl_classification cl ON cl.class_id = c.classification
			LEFT JOIN tbl_classification_category cc ON cc.class_cat_id = cl.class_cat_id
			WHERE c.status = 1
			GROUP BY label ORDER BY total DESC")->result_array();
		$type = $this->db->query(
			"SELECT COALESCE(t.cust_type_name, 'Unspecified') AS label, COUNT(*) AS total
			FROM ".$this->table_customer." c
			LEFT JOIN tbl_customer_type t ON t.cust_type_id = c.account_type
			WHERE c.status = 1
			GROUP BY label ORDER BY total DESC")->result_array();
		return array('category' => $category, 'type' => $type);
	}

	/** Customer counts: active, inactive, added since a date. */
	public function get_customer_counts($since) {
		$sql = "SELECT
				SUM(status = 1) AS active,
				SUM(status <> 1) AS inactive,
				SUM(status = 1 AND create_date >= ?) AS new_since
			FROM ".$this->table_customer;
		return $this->db->query($sql, array($since))->row_array();
	}

	/** Leak-repair ledgers: outstanding = approved totals + posted balances. */
	public function get_leaking_summary() {
		$sql = "SELECT
				SUM(leaking_status = 1) AS pending,
				SUM(CASE WHEN leaking_status = 2 THEN leaking_total_amount
				         WHEN leaking_status = 4 THEN leaking_balance ELSE 0 END) AS outstanding,
				SUM(leaking_status IN (2, 4)) AS open_accounts,
				SUM(CASE WHEN leaking_status = 5 THEN leaking_total_amount ELSE 0 END) AS collected
			FROM ".$this->table_leaking_ledger;
		return $this->db->query($sql)->row_array();
	}

	/** Service tickets by status (excluding deleted). */
	public function get_ticket_status_counts() {
		$out = array_fill(0, 7, 0);
		$rows = $this->db->query(
			"SELECT status, COUNT(*) AS total FROM ".$this->table_technical."
			WHERE deleted_rec = 0 GROUP BY status")->result_array();
		foreach ($rows as $r) {
			$s = (int)$r['status'];
			if ($s >= 0 && $s <= 6) { $out[$s] = (int)$r['total']; }
		}
		return $out;
	}
	
}
?>