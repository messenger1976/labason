<?php 
class Report_model extends CI_Model {
	public $table_name = 'tbl_addcustomer';
	public $table_billing = 'tbl_feesplaning';
	public $table_meter = 'tbl_addmetercustomer';
    public $table_meter_reading = 'tbl_addcustomer_reading';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_assets = 'tbl_addassets';
	public $table_users = 'tbl_responsibilities_user';
	public $table_zone = 'tbl_zone';
	public $table_employee = 'tbl_addemployee';
	public $table_jobtitle = 'tbl_jobtitle';
    public $table_classification = 'tbl_classification';
    public $table_classification_category = 'tbl_classification_category';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
	/** In Function Get all records from select table **/
    
	public function get_monthly_billing_report_records($zone,$billingperiod,$status=''){
        $billingperiod = explode(' ',$billingperiod);

		$this->db->select($this->table_name.'.customer_type, '.$this->table_name.'.first_name,'.$this->table_name.'.last_name,'.$this->table_name.'.middle_name,'.$this->table_name.'.meter_number,
		(SELECT zone FROM tbl_zone WHERE tbl_zone.id='.$this->table_name.'.zone) as zone, 
        (SELECT bp_due_date FROM tbl_billing_period WHERE tbl_billing_period.bp_id='.$this->table_meter_reading.'.bp_id) as due_date, '.
        $this->table_meter.'.invoice_id,'.
        $this->table_meter.'.date as payment_date,'.
        $this->table_meter.'.amount as payment_amount,'.
        $this->table_classification_category.'.*,'.
        $this->table_classification.'.*, 
        '.$this->table_meter_reading.'.*');
		$this->db->from($this->table_meter_reading);
		$this->db->join($this->table_name, $this->table_meter_reading.'.customer_id = '.$this->table_name.'.customer_id');

		$this->db->join($this->table_meter, $this->table_meter_reading.'.customer_id='.$this->table_meter.'.customer_id AND '.$this->table_meter_reading.'.month='.$this->table_meter.'.month AND '.$this->table_meter_reading.'.year='.$this->table_meter.'.year','left');
        $this->db->join($this->table_classification, $this->table_name.'.classification = '.$this->table_classification.'.class_id','left');
        $this->db->join($this->table_classification_category, $this->table_classification.'.class_cat_id = '.$this->table_classification_category.'.class_cat_id','left');

		$this->db->where($this->table_meter_reading.'.month',$billingperiod[0]);
        $this->db->where($this->table_meter_reading.'.year',$billingperiod[1]);
		//$this->db->where($this->table_meter_reading.'.customer_status',1);
		//$this->db->where('tbl_addmetercustomer.date <=',$to);
		if($zone!=0){
			$this->db->where('tbl_addcustomer.zone',$zone);
		}
        if($status==='1'){
            $this->db->where($this->table_meter.'.invoice_id IS NOT NULL');
			
        }elseif($status==='0'){
            $this->db->where($this->table_meter.'.invoice_id IS NULL');
			$this->db->where($this->table_meter_reading.'.customer_status',1);
			$this->db->where($this->table_meter_reading.'.consumed >= ',0);
			$this->db->where($this->table_meter_reading.'.amount > ',0);
        }elseif($status==='2'){
			$this->db->where($this->table_meter_reading.'.customer_status',$status);
        }elseif($status==='3'){
			$this->db->where($this->table_meter_reading.'.customer_status',1);
			$this->db->where($this->table_meter.'.invoice_id IS NULL');
			//$this->db->where($this->table_meter_reading.'.consumed',0);
            //$this->db->where($this->table_meter_reading.'.amount IS NULL');
			//$this->db->or_where($this->table_meter_reading.'.amount',0);
			//$this->db->where($this->table_meter_reading.'.reading IS NULL');
			$this->db->where($this->table_meter_reading.'.reading','');
		}elseif($status==='4'){
			//$this->db->where($this->table_meter.'.invoice_id IS NOT NULL');
			//$this->db->where($this->table_meter.'.invoice_id IS NULL');
			$this->db->where($this->table_meter_reading.'.customer_status',1);
			//$this->db->where($this->table_meter_reading.'.consumed > ',0);
			$this->db->where($this->table_meter_reading.'.reading IS NOT NULL');
			$this->db->where($this->table_meter_reading.'.reading <>','');
		}
		
		$this->db->order_by($this->table_meter_reading.'.update_date_time','asc');
		//$this->db->order_by($this->table_name.'.first_name','asc');
		//$this->db->group_by('invoice_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function get_monthly_billing_report_records_status3($zone,$billingperiod,$status=''){
        $billingperiod = explode(' ',$billingperiod);

		$this->db->select($this->table_name.'.customer_type, '.$this->table_name.'.first_name,'.$this->table_name.'.last_name,'.$this->table_name.'.middle_name,'.$this->table_name.'.meter_number,
		(SELECT zone FROM tbl_zone WHERE tbl_zone.id='.$this->table_name.'.zone) as zone, 
        (SELECT bp_due_date FROM tbl_billing_period WHERE tbl_billing_period.bp_id='.$this->table_meter_reading.'.bp_id) as due_date, '.
        $this->table_meter.'.invoice_id,'.
        $this->table_meter.'.date as payment_date,'.
        $this->table_meter.'.amount as payment_amount,'.
        $this->table_classification_category.'.*,'.
        $this->table_classification.'.*, '.
		$this->table_meter_reading.'.*');
		$this->db->from($this->table_meter_reading);
		$this->db->join($this->table_name, $this->table_meter_reading.'.customer_id = '.$this->table_name.'.customer_id');

		$this->db->join($this->table_meter, $this->table_meter_reading.'.customer_id='.$this->table_meter.'.customer_id AND '.$this->table_meter_reading.'.month='.$this->table_meter.'.month AND '.$this->table_meter_reading.'.year='.$this->table_meter.'.year','left');
        $this->db->join($this->table_classification, $this->table_name.'.classification = '.$this->table_classification.'.class_id','left');
        $this->db->join($this->table_classification_category, $this->table_classification.'.class_cat_id = '.$this->table_classification_category.'.class_cat_id','left');

		$this->db->where($this->table_meter_reading.'.month',$billingperiod[0]);
        $this->db->where($this->table_meter_reading.'.year',$billingperiod[1]);
		//$this->db->where($this->table_meter_reading.'.customer_status',1);
		//$this->db->where('tbl_addmetercustomer.date <=',$to);
		if($zone!=0){
			$this->db->where('tbl_addcustomer.zone',$zone);
		}
        if($status==='1'){
            $this->db->where($this->table_meter.'.invoice_id IS NOT NULL');
        }elseif($status==='0'){
            $this->db->where($this->table_meter.'.invoice_id IS NULL');
        }elseif($status==='2'){
			$this->db->where($this->table_meter_reading.'.customer_status',$status);
        }elseif($status==='3'){
			$this->db->where($this->table_meter.'.invoice_id IS NULL');
            $this->db->where($this->table_meter_reading.'.consumed',0);
			$this->db->or_where($this->table_meter_reading.'.consumed IS NULL');
        }
		
		$this->db->order_by($this->table_name.'.last_name','asc');
		$this->db->order_by($this->table_name.'.first_name','asc');
		//$this->db->group_by('invoice_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function get_customer_report_records($zone,$status='',$special_priviledge=''){
		// Use the same pattern as addcustomer_model for compatibility
		$this->db->select($this->table_name.'.customer_id, '.$this->table_name.'.first_name, '.$this->table_name.'.last_name, '.$this->table_name.'.middle_name, '.$this->table_name.'.address, '.$this->table_name.'.status, 
		(SELECT zone FROM '.$this->table_zone.' WHERE '.$this->table_zone.'.id = '.$this->table_name.'.zone) as zone_name,
		(SELECT class_name FROM '.$this->table_classification.' WHERE '.$this->table_classification.'.class_id = '.$this->table_name.'.classification) as classification_name');
		$this->db->from($this->table_name);
		
		// Convert zone to integer for comparison
		$zone = (int)$zone;
		if($zone != 0){
			$this->db->where($this->table_name.'.zone',$zone);
		}
        if($status !== '' && $status !== null && $status !== false && $status !== '99'){
			$this->db->where($this->table_name.'.status',$status);
		}
		if($special_priviledge === '1' || $special_priviledge === 1){
			$this->db->where($this->table_name.'.special_priviledge',1);
		}
		
		$this->db->order_by($this->table_name.'.last_name','asc');
		$this->db->order_by($this->table_name.'.first_name','asc');
		
		$query = $this->db->get();
		
		// Check for database errors
		if($this->db->_error_number() != 0){
			log_message('error', 'Database Error Number: ' . $this->db->_error_number());
			log_message('error', 'Database Error Message: ' . $this->db->_error_message());
			log_message('error', 'Last Query: ' . $this->db->last_query());
			return array();
		}
		
		$result = $query->result_array();
		return $result ? $result : array();
	}

	public function get_aging_ar_report_records($asofdate,$zone,$status){
		$asofdate = date('Y-m-d',strtotime($asofdate));
		$sql_query_zone ='';
        $sql_query_status ='';
		if($zone!=0){
			$sql_query_zone =' AND tbl_addcustomer.zone=?';
		}
        if($status!=''){
			$sql_query_status =' AND tbl_addcustomer.status=?';
		}
		$sql_query = "SELECT  tbl_addcustomer.customer_id,
		tbl_addcustomer.last_name,
		tbl_addcustomer.first_name,
		tbl_addcustomer.middle_name, 
		tbl_addcustomer.meter_number,
		tbl_billing_period.bp_end_date AS reading_date,
        (SELECT zone FROM tbl_zone WHERE tbl_zone.id=tbl_addcustomer.zone) AS zone, 
        
        SUM( tbl_addcustomer_reading.penalty) AS total_balance,
		SUM(CASE WHEN DATEDIFF(?, tbl_billing_period.bp_due_date) BETWEEN 0 AND 30 THEN tbl_addcustomer_reading.penalty ELSE 0 END) AS current,
        SUM(CASE WHEN DATEDIFF(?, tbl_billing_period.bp_due_date) BETWEEN 31 AND 60 THEN tbl_addcustomer_reading.penalty ELSE 0 END) AS `30-days`,
        SUM(CASE WHEN DATEDIFF(?, tbl_billing_period.bp_due_date) BETWEEN 61 AND 90 THEN tbl_addcustomer_reading.penalty ELSE 0 END) AS `60-days`,
        SUM(CASE WHEN DATEDIFF(?, tbl_billing_period.bp_due_date) BETWEEN 91 AND 120 THEN tbl_addcustomer_reading.penalty ELSE 0 END) AS `90-days`,
        SUM(CASE WHEN DATEDIFF(?, tbl_billing_period.bp_due_date) BETWEEN 121 AND 150 THEN tbl_addcustomer_reading.penalty ELSE 0 END) AS `120-days`,
        SUM(CASE WHEN DATEDIFF(?, tbl_billing_period.bp_due_date) > 151 THEN tbl_addcustomer_reading.amount ELSE 0 END) AS `150-DaysUp`
        
        FROM tbl_addcustomer_reading
		INNER JOIN tbl_addcustomer ON tbl_addcustomer_reading.customer_id = tbl_addcustomer.customer_id
		LEFT JOIN tbl_billing_period ON tbl_addcustomer_reading.bp_id = tbl_billing_period.bp_id
		LEFT JOIN tbl_addmetercustomer ON tbl_addcustomer_reading.customer_id=tbl_addmetercustomer.customer_id AND tbl_addcustomer_reading.month=tbl_addmetercustomer.month 
		AND tbl_addcustomer_reading.year=tbl_addmetercustomer.year
        LEFT JOIN tbl_classification ON tbl_addcustomer.classification = tbl_classification.class_id
        LEFT JOIN tbl_classification_category ON tbl_classification.class_cat_id = tbl_classification_category.class_cat_id
	WHERE  tbl_addmetercustomer.invoice_id IS NULL and tbl_addcustomer_reading.reading<>'' AND tbl_billing_period.bp_due_date<? $sql_query_zone $sql_query_status
        GROUP BY tbl_addcustomer.`customer_id`
		
	ORDER BY tbl_addcustomer.last_name ASC, tbl_addcustomer.first_name ASC";
	
	$query = $this->db->query($sql_query, [
		$asofdate, $asofdate, $asofdate, $asofdate,
		$asofdate, $asofdate, $asofdate,$zone,$status
	]);

		


		
		//$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	/**
	 * Arrears Monitoring report records.
	 *
	 * Returns the same customer set and Aging Amount (`total_balance`) as
	 * get_aging_ar_report_records(), plus `current_arrears` which is the
	 * `arrears` value stored on the customer's reading row for their
	 * ACTIVE billing period (latest bp_status = 1 row in their zone).
	 *
	 * Used to compare the cumulative aging total against the arrears amount
	 * carried forward into the current period.
	 */
	public function get_arrears_monitoring_records($asofdate, $zone, $status){
		$asofdate = date('Y-m-d', strtotime($asofdate));

		$sql_query_zone   = '';
		$sql_query_status = '';
		$bind = array(
			$asofdate, $asofdate, $asofdate, $asofdate,
			$asofdate, $asofdate, $asofdate
		);

		if($zone != 0){
			$sql_query_zone = ' AND tbl_addcustomer.zone=?';
			$bind[] = $zone;
		}
		if($status != ''){
			$sql_query_status = ' AND tbl_addcustomer.status=?';
			$bind[] = $status;
		}

		$sql_query = "SELECT tbl_addcustomer.customer_id,
		tbl_addcustomer.last_name,
		tbl_addcustomer.first_name,
		tbl_addcustomer.middle_name,
		tbl_addcustomer.meter_number,
		tbl_billing_period.bp_end_date AS reading_date,
		(SELECT zone FROM tbl_zone WHERE tbl_zone.id = tbl_addcustomer.zone) AS zone,

		SUM(tbl_addcustomer_reading.penalty) AS total_balance,
		SUM(CASE WHEN DATEDIFF(?, tbl_billing_period.bp_due_date) BETWEEN 0 AND 30 THEN tbl_addcustomer_reading.penalty ELSE 0 END) AS current,
		SUM(CASE WHEN DATEDIFF(?, tbl_billing_period.bp_due_date) BETWEEN 31 AND 60 THEN tbl_addcustomer_reading.penalty ELSE 0 END) AS `30-days`,
		SUM(CASE WHEN DATEDIFF(?, tbl_billing_period.bp_due_date) BETWEEN 61 AND 90 THEN tbl_addcustomer_reading.penalty ELSE 0 END) AS `60-days`,
		SUM(CASE WHEN DATEDIFF(?, tbl_billing_period.bp_due_date) BETWEEN 91 AND 120 THEN tbl_addcustomer_reading.penalty ELSE 0 END) AS `90-days`,
		SUM(CASE WHEN DATEDIFF(?, tbl_billing_period.bp_due_date) BETWEEN 121 AND 150 THEN tbl_addcustomer_reading.penalty ELSE 0 END) AS `120-days`,
		SUM(CASE WHEN DATEDIFF(?, tbl_billing_period.bp_due_date) > 151 THEN tbl_addcustomer_reading.amount ELSE 0 END) AS `150-DaysUp`,

		(
			SELECT cr2.arrears
			FROM tbl_addcustomer_reading cr2
			INNER JOIN tbl_billing_period bp2 ON bp2.bp_id = cr2.bp_id
			WHERE cr2.customer_id = tbl_addcustomer.customer_id
			  AND bp2.bp_status   = 1
			  AND bp2.bp_zone_id  = tbl_addcustomer.zone
			ORDER BY bp2.bp_period_year DESC, bp2.bp_period_month DESC
			LIMIT 1
		) AS current_arrears

		FROM tbl_addcustomer_reading
		INNER JOIN tbl_addcustomer ON tbl_addcustomer_reading.customer_id = tbl_addcustomer.customer_id
		LEFT JOIN tbl_billing_period ON tbl_addcustomer_reading.bp_id = tbl_billing_period.bp_id
		LEFT JOIN tbl_addmetercustomer ON tbl_addcustomer_reading.customer_id = tbl_addmetercustomer.customer_id
			AND tbl_addcustomer_reading.month = tbl_addmetercustomer.month
			AND tbl_addcustomer_reading.year  = tbl_addmetercustomer.year
		LEFT JOIN tbl_classification ON tbl_addcustomer.classification = tbl_classification.class_id
		LEFT JOIN tbl_classification_category ON tbl_classification.class_cat_id = tbl_classification_category.class_cat_id
		WHERE tbl_addmetercustomer.invoice_id IS NULL
		  AND tbl_addcustomer_reading.reading <> ''
		  AND tbl_billing_period.bp_due_date < ?
		  $sql_query_zone $sql_query_status
		GROUP BY tbl_addcustomer.`customer_id`
		ORDER BY tbl_addcustomer.last_name ASC, tbl_addcustomer.first_name ASC";

		$query  = $this->db->query($sql_query, $bind);
		$result = $query->result_array();
		return $result ? $result : array();
	}

	/**
	 * Update current billing period arrears for a customer using aging amount.
	 * Returns array(success, message, current_arrears).
	 */
	public function update_current_period_arrears($customer_id, $aging_amount){
		$customer_id = trim((string) $customer_id);
		if ($customer_id === '') {
			return array('success' => false, 'message' => 'Customer ID is required.');
		}

		$this->db->select('zone');
		$this->db->from('tbl_addcustomer');
		$this->db->where('customer_id', $customer_id);
		$cq = $this->db->get();
		if (!$cq || $cq->num_rows() === 0) {
			return array('success' => false, 'message' => 'Customer not found.');
		}
		$zone_id = (int) $cq->row()->zone;

		$this->db->select('bp_id');
		$this->db->from('tbl_billing_period');
		$this->db->where('bp_zone_id', $zone_id);
		$this->db->where('bp_status', 1);
		$this->db->order_by('bp_period_year', 'DESC');
		$this->db->order_by('bp_period_month', 'DESC');
		$this->db->limit(1);
		$bpq = $this->db->get();
		if (!$bpq || $bpq->num_rows() === 0) {
			return array('success' => false, 'message' => 'No active billing period found for customer zone.');
		}
		$bp_id = (int) $bpq->row()->bp_id;

		$this->db->where('customer_id', $customer_id);
		$this->db->where('bp_id', $bp_id);
		$this->db->update('tbl_addcustomer_reading', array('arrears' => (float) $aging_amount));
		if ($this->db->_error_number() != 0) {
			return array('success' => false, 'message' => 'Failed to update arrears.');
		}

		$this->db->select('arrears');
		$this->db->from('tbl_addcustomer_reading');
		$this->db->where('customer_id', $customer_id);
		$this->db->where('bp_id', $bp_id);
		$this->db->limit(1);
		$rq = $this->db->get();
		if (!$rq || $rq->num_rows() === 0) {
			return array('success' => false, 'message' => 'Updated row not found after update.');
		}

		return array(
			'success' => true,
			'message' => 'Arrears updated successfully.',
			'current_arrears' => (float) $rq->row()->arrears
		);
	}

	/**
	 * Get daily income for a given month/year (meter + monthly customers).
	 * Returns array: 'daily' => [day => amount], 'total' => float, 'days_in_month' => int
	 * Date in DB is stored as Y-m-d (see addpaymentcustomer_model insert).
	 * Meter totals align with Daily Collection Report: group by OR, leaking balance adjustment, leaking A/R.
	 */
	public function get_monthly_income_daily($month, $year) {
		$month = (int) $month;
		$year = (int) $year;
		$start_ymd = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
		$end_ymd = date('Y-m-t', strtotime($start_ymd));
		$days_in_month = (int) date('t', strtotime($start_ymd));

		$daily = array();
		for ($d = 1; $d <= 31; $d++) {
			$daily[$d] = 0;
		}

		$CI =& get_instance();
		$CI->load->model('leakingentry_model');
		$leaking_or_cache = array();

		// Meter customers: one line per OR (matches Daily Collection Report grouping).
		$this->db->select('date, or_number, customer_id, MAX(COALESCE(grand_total, pay_amount, amount, 0)) as grand_total, MAX(COALESCE(leaking_amount, 0)) as leaking_amount', FALSE);
		$this->db->from($this->table_meter);
		$this->db->where('date >=', $start_ymd);
		$this->db->where('date <=', $end_ymd);
		$this->db->group_by(array('date', 'or_number', 'customer_id'));
		$qm = $this->db->get();
		if ($qm && $qm->num_rows() > 0) {
			foreach ($qm->result_array() as $row) {
				$day = isset($row['date']) ? (int) date('j', strtotime($row['date'])) : 0;
				if ($day < 1 || $day > 31) {
					continue;
				}
				$grand = (float) $row['grand_total'];
				if ((float) $row['leaking_amount'] > 0 && !empty($row['or_number'])) {
					$or_key = sprintf('%07d', $row['or_number']);
					if (!isset($leaking_or_cache[$or_key])) {
						$ar_leaking = $CI->leakingentry_model->get_soa_statement_OR($or_key);
						$leaking_or_cache[$or_key] = ($ar_leaking && is_array($ar_leaking)) ? $ar_leaking : array();
					}
					if (isset($leaking_or_cache[$or_key]['leaking_balance'])) {
						$grand -= (float) $leaking_or_cache[$or_key]['leaking_balance'];
					}
				}
				$daily[$day] += $grand;
			}
		}

		// Leaking A/R payments (included in Daily Collection Report grand total).
		$this->db->select('leakingledgerdetails_transdate as payment_date, SUM(COALESCE(leakingledgerdetails_amount, 0)) as amt', FALSE);
		$this->db->from('tbl_leaking_ledger_details');
		$this->db->where('leakingledgerdetails_transdate >=', $start_ymd);
		$this->db->where('leakingledgerdetails_transdate <=', $end_ymd);
		$this->db->where('leakingledgerdetails_source_module', 'leaking');
		$this->db->group_by('leakingledgerdetails_transdate');
		$ql = $this->db->get();
		if ($ql && $ql->num_rows() > 0) {
			foreach ($ql->result_array() as $row) {
				$day = isset($row['payment_date']) ? (int) date('j', strtotime($row['payment_date'])) : 0;
				if ($day >= 1 && $day <= 31) {
					$daily[$day] += (float) $row['amt'];
				}
			}
		}

		// Monthly customers
		$this->db->select('DAY(date) as day_num, SUM(COALESCE(paidamount, 0)) as amt', FALSE);
		$this->db->from($this->table_monthly);
		$this->db->where('date >=', $start_ymd);
		$this->db->where('date <=', $end_ymd);
		$this->db->group_by('date');
		$qy = $this->db->get();
		if ($qy && $qy->num_rows() > 0) {
			foreach ($qy->result_array() as $row) {
				$day = isset($row['day_num']) ? (int) $row['day_num'] : 0;
				if ($day >= 1 && $day <= 31) {
					$daily[$day] += (float) $row['amt'];
				}
			}
		}

		$total = 0;
		for ($d = 1; $d <= $days_in_month; $d++) {
			$total += isset($daily[$d]) ? (float) $daily[$d] : 0;
		}

		return array(
			'daily' => $daily,
			'total' => $total,
			'days_in_month' => $days_in_month
		);
	}

	/**
	 * Inner grouped SQL: multi-period payments (same OR) with all lines strictly before active billing period.
	 * Payment posting date (p.date) must fall in the calendar month immediately before the zone's active billing period month (when an active period exists).
	 * @param int $zone 0 = all
	 * @param string $status '' or '99' = all; else tbl_addcustomer.status
	 * @return array sql string, params array
	 */
	private function _customer_payment_monitoring_inner_sql($zone, $status) {
		$zone = (int) $zone;
		$status = ($status === '99' || $status === null || $status === false) ? '' : (string) $status;
		$params = array();
		$where = array('(p.or_number IS NOT NULL AND TRIM(CAST(p.or_number AS CHAR)) <> \'\')');
		if ($zone > 0) {
			$where[] = 'c.zone = ?';
			$params[] = $zone;
		}
		if ($status !== '') {
			$where[] = 'c.status = ?';
			$params[] = $status;
		}
		$pdate = 'COALESCE(STR_TO_DATE(TRIM(CAST(p.date AS CHAR)), \'%Y-%m-%d\'), STR_TO_DATE(TRIM(CAST(p.date AS CHAR)), \'%d-%m-%Y\'))';
		$where[] = '(
			curbp.bp_id IS NULL
			OR (
				' . $pdate . ' IS NOT NULL
				AND YEAR(' . $pdate . ') = (CASE WHEN curbp.bp_period_month > 1 THEN curbp.bp_period_year ELSE curbp.bp_period_year - 1 END)
				AND MONTH(' . $pdate . ') = (CASE WHEN curbp.bp_period_month > 1 THEN curbp.bp_period_month - 1 ELSE 12 END)
			)
		)';
		$where_sql = implode(' AND ', $where);
		$sql = "SELECT
			p.customer_id,
			p.or_number,
			MAX(c.first_name) AS first_name,
			MAX(c.last_name) AS last_name,
			MAX(c.middle_name) AS middle_name,
			MAX(c.address) AS address,
			MAX(z.zone) AS zone_name,
			COUNT(DISTINCT CONCAT(CAST(p.month AS CHAR), '-', CAST(p.year AS CHAR))) AS period_count,
			GROUP_CONCAT(DISTINCT CONCAT(LPAD(CAST(p.month AS UNSIGNED), 2, '0'), '/', CAST(p.year AS UNSIGNED)) SEPARATOR ', ') AS billing_periods_paid,
			SUM(COALESCE(
				NULLIF(CAST(p.pay_amount AS DECIMAL(18,4)), 0),
				NULLIF(CAST(p.total AS DECIMAL(18,4)), 0),
				CAST(IFNULL(p.amount, 0) AS DECIMAL(18,4))
			)) AS total_paid
		FROM tbl_addmetercustomer p
		INNER JOIN tbl_addcustomer c ON c.customer_id = p.customer_id
		LEFT JOIN tbl_zone z ON z.id = c.zone
		LEFT JOIN (
			SELECT bp.bp_zone_id, bp.bp_period_month, bp.bp_period_year, bp.bp_id
			FROM tbl_billing_period bp
			INNER JOIN (
				SELECT bp_zone_id, MAX(bp_period_year * 100 + bp_period_month) AS mk
				FROM tbl_billing_period
				WHERE bp_status = 1
				GROUP BY bp_zone_id
			) mx ON mx.bp_zone_id = bp.bp_zone_id
				AND (bp.bp_period_year * 100 + bp.bp_period_month) = mx.mk
			WHERE bp.bp_status = 1
		) curbp ON curbp.bp_zone_id = c.zone
		WHERE " . $where_sql . "
		GROUP BY p.customer_id, p.or_number
		HAVING COUNT(DISTINCT CONCAT(CAST(p.month AS CHAR), '-', CAST(p.year AS CHAR))) >= 2
		AND SUM(CASE WHEN curbp.bp_id IS NULL THEN 0
			WHEN (CAST(p.year AS UNSIGNED) * 100 + CAST(p.month AS UNSIGNED)) >= (curbp.bp_period_year * 100 + curbp.bp_period_month) THEN 1
			ELSE 0 END) = 0";
		return array('sql' => $sql, 'params' => $params);
	}

	/** Sort comma-separated mm/yyyy values chronologically */
	private function _sort_billing_periods_paid_csv($csv) {
		if ($csv === null || $csv === '') {
			return '';
		}
		$parts = array_map('trim', explode(',', (string) $csv));
		$parsed = array();
		foreach ($parts as $p) {
			if ($p !== '' && preg_match('/^(\d{1,2})\/(\d{4})$/', $p, $m)) {
				$y = (int) $m[2];
				$mo = (int) $m[1];
				$parsed[] = array('k' => $y * 100 + $mo, 's' => str_pad((string) $mo, 2, '0', STR_PAD_LEFT) . '/' . $y);
			}
		}
		if (count($parsed) === 0) {
			return (string) $csv;
		}
		usort($parsed, function ($a, $b) {
			return $a['k'] - $b['k'];
		});
		return implode(', ', array_column($parsed, 's'));
	}

	public function count_customer_payment_monitoring_records($zone, $status = '') {
		$inner = $this->_customer_payment_monitoring_inner_sql($zone, $status);
		$sql = 'SELECT COUNT(*) AS cnt FROM (' . $inner['sql'] . ') t';
		$q = $this->db->query($sql, $inner['params']);
		if (!$q || $this->db->_error_number() != 0) {
			log_message('error', 'count_customer_payment_monitoring: ' . $this->db->_error_message());
			return 0;
		}
		$row = $q->row_array();
		return isset($row['cnt']) ? (int) $row['cnt'] : 0;
	}

	public function get_customer_payment_monitoring_records($zone, $status = '', $limit = 100, $offset = 0) {
		$limit = (int) $limit;
		$offset = (int) $offset;
		if ($limit < 1) {
			$limit = 100;
		}
		if ($limit > 100) {
			$limit = 100;
		}
		if ($offset < 0) {
			$offset = 0;
		}
		$inner = $this->_customer_payment_monitoring_inner_sql($zone, $status);
		$this->db->query('SET SESSION group_concat_max_len = 16384');
		$sql = 'SELECT * FROM (' . $inner['sql'] . ') t
			ORDER BY t.zone_name ASC, t.last_name ASC, t.first_name ASC, CAST(t.or_number AS UNSIGNED) ASC
			LIMIT ' . (int) $limit . ' OFFSET ' . (int) $offset;
		$params = $inner['params'];
		$q = $this->db->query($sql, $params);
		if (!$q || $this->db->_error_number() != 0) {
			log_message('error', 'get_customer_payment_monitoring: ' . $this->db->_error_message());
			return array();
		}
		$rows = $q->result_array();
		foreach ($rows as &$r) {
			if (isset($r['billing_periods_paid'])) {
				$r['billing_periods_paid'] = $this->_sort_billing_periods_paid_csv($r['billing_periods_paid']);
			}
		}
		unset($r);
		return $rows;
	}
	
}
?>
	
