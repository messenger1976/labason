<?php 
class reports_model extends CI_Model {
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
		$this->db->where($this->table_name.'.status',1);
		//$this->db->where('tbl_addmetercustomer.date <=',$to);
		if($zone!=0){
			$this->db->where('tbl_addcustomer.zone',$zone);
		}
        if($status==='1'){
            $this->db->where($this->table_meter.'.invoice_id IS NOT NULL');
        }elseif($status==='0'){
            $this->db->where($this->table_meter.'.invoice_id IS NULL');
        }elseif($status==='3'){
			$this->db->where($this->table_meter.'.invoice_id IS NULL');
            $this->db->where($this->table_meter_reading.'.reading IS NULL');
        }
		
		$this->db->order_by($this->table_name.'.last_name','asc');
		$this->db->order_by($this->table_name.'.first_name','asc');
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
		$this->db->where($this->table_name.'.status',1);
		//$this->db->where('tbl_addmetercustomer.date <=',$to);
		if($zone!=0){
			$this->db->where('tbl_addcustomer.zone',$zone);
		}
        if($status==='1'){
            $this->db->where($this->table_meter.'.invoice_id IS NOT NULL');
        }elseif($status==='0'){
            $this->db->where($this->table_meter.'.invoice_id IS NULL');
        }elseif($status==='3'){
			$this->db->where($this->table_meter.'.invoice_id IS NULL');
            $this->db->where($this->table_meter_reading.'.consumed',0);
        }
		
		$this->db->order_by($this->table_name.'.last_name','asc');
		$this->db->order_by($this->table_name.'.first_name','asc');
		//$this->db->group_by('invoice_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function get_aging_ar_report_records($asofdate,$zone){
		$asofdate = date('Y-m-d',strtotime($asofdate));
		$sql_query_zone ='';
		if($zone!=0){
			$sql_query_zone =' AND tbl_addcustomer.zone=?';
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
	WHERE  tbl_addcustomer.status=1 and tbl_addmetercustomer.invoice_id IS NULL and tbl_addcustomer_reading.reading<>'' AND tbl_billing_period.bp_due_date<? $sql_query_zone
        GROUP BY tbl_addcustomer.`customer_id`
		
	ORDER BY tbl_addcustomer.last_name ASC, tbl_addcustomer.first_name ASC";
	
	$query = $this->db->query($sql_query, [
		$asofdate, $asofdate, $asofdate, $asofdate,
		$asofdate, $asofdate, $asofdate,$zone
	]);

		


		
		//$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
}
?>
	
