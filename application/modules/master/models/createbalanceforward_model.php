<?php 
class createbalanceforward_model extends CI_Model {
	public $table_name = 'tbl_zone';
    public $table_months = 'tbl_months';
    public $table_billing_period = 'tbl_billing_period';
	public $table_reading = 'tbl_addcustomer_reading';
	public $table_customer = 'tbl_addcustomer';
	public $table_zone = 'tbl_zone';
	
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
	/** In Function Get all records from select table **/
    public function get_zone_records() {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }
	
	/** In Function Get all records from select table **/
    public function get_month_billingperiod_records() {
        $this->db->select("*,(Select month_name from ".$this->table_months." where ".$this->table_months.".month_id	= ".$this->table_billing_period.".bp_period_month ) as month_name");
		$this->db->from($this->table_billing_period);
        $this->db->group_by('bp_period_month','bp_period_year');
		$this->db->order_by('bp_zone_id','asc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }
	
	public function get_zone_listing_records() {
        $this->db->select("*");
		$this->db->from($this->table_zone);
        $this->db->order_by('order_series','asc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }
	
	/** Get balance forward results **/
	public function get_balanceforward_results($bp_month, $bp_year, $zone_id) {
		$this->db->select("
			tbl_addcustomer_reading.id,
			tbl_addcustomer_reading.refno,
			tbl_addcustomer_reading.customer_id,
			tbl_addcustomer.first_name,
			tbl_addcustomer.last_name,
			tbl_addcustomer.address,
			tbl_addcustomer.status,
			tbl_zone.zone as zone_name,
			tbl_addcustomer_reading.previous_reading,
			tbl_addcustomer_reading.reading as current_reading,
			tbl_addcustomer_reading.arrears,
			tbl_addcustomer_reading.maintenance_fee,
			tbl_addcustomer_reading.month,
			tbl_addcustomer_reading.year,
			(SELECT month_name FROM ".$this->table_months." WHERE month_id = tbl_addcustomer_reading.month) as month_name
		");
		$this->db->from($this->table_reading);
		$this->db->join('tbl_addcustomer', 'tbl_addcustomer_reading.customer_id=tbl_addcustomer.customer_id','left');
		$this->db->join('tbl_zone', 'tbl_addcustomer.zone=tbl_zone.id','left');
		
		if($bp_month != '' && $bp_year != ''){
			$this->db->where("tbl_addcustomer_reading.month", $bp_month);
			$this->db->where("tbl_addcustomer_reading.year", $bp_year);
		}
		
		if($zone_id != '' && $zone_id != '0' && $zone_id != 'all' && $zone_id != null){
			$this->db->where('tbl_addcustomer.zone', $zone_id);
		}
		
		$this->db->order_by('tbl_addcustomer.last_name','ASC');
		$this->db->order_by('tbl_addcustomer.first_name','ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	/** Get member statistics by status **/
	public function get_member_statistics($bp_month, $bp_year, $zone_id) {
		$this->db->select("
			tbl_addcustomer.status,
			COUNT(DISTINCT tbl_addcustomer_reading.customer_id) as member_count
		");
		$this->db->from($this->table_reading);
		$this->db->join('tbl_addcustomer', 'tbl_addcustomer_reading.customer_id=tbl_addcustomer.customer_id','left');
		
		if($bp_month != '' && $bp_year != ''){
			$this->db->where("tbl_addcustomer_reading.month", $bp_month);
			$this->db->where("tbl_addcustomer_reading.year", $bp_year);
		}
		
		if($zone_id != '' && $zone_id != '0' && $zone_id != 'all' && $zone_id != null){
			$this->db->where('tbl_addcustomer.zone', $zone_id);
		}
		
		$this->db->group_by('tbl_addcustomer.status');
		$query = $this->db->get();
		$result = $query->result_array();
		
		// Initialize statistics
		$stats = array(
			'total' => 0,
			'active' => 0,
			'inactive' => 0,
			'deactivated' => 0
		);
		
		// Process results
		foreach($result as $row){
			$status = isset($row['status']) ? $row['status'] : '';
			$count = isset($row['member_count']) ? $row['member_count'] : 0;
			
			$stats['total'] += $count;
			
			if($status == '1' || $status === 1){
				$stats['active'] = $count;
			}else if($status == '0' || $status === 0){
				$stats['inactive'] = $count;
			}else if($status == '2' || $status === 2){
				$stats['deactivated'] = $count;
			}
		}
		
		return $stats;
	}
	
	/** Get total customer count for batch processing **/
	public function get_total_customers_count($zone_id) {
		$this->db->where('status', '1');
		if($zone_id != '' && $zone_id != '0' && $zone_id != 'all' && $zone_id != null){
			$this->db->where('zone', $zone_id);
		}
		$query = $this->db->get('tbl_addcustomer');
		return $query->num_rows();
	}
	
	/** Get customers batch for processing **/
	public function get_customers_batch($zone_id, $offset = 0, $limit = 50) {
		$this->db->where('status', '1');
		if($zone_id != '' && $zone_id != '0' && $zone_id != 'all' && $zone_id != null){
			$this->db->where('zone', $zone_id);
		}
		$this->db->limit($limit, $offset);
		$query = $this->db->get('tbl_addcustomer');
		return $query->result();
	}
	
	/** Get maintenance fee from global settings **/
	public function get_maintenance_fee() {
		$this->db->select('value');
		$this->db->from('tbl_global_settings');
		$this->db->where('code', 'MAINTENANCE_FEE');
		$query = $this->db->get();
		$result = $query->row();
		
		if($result && isset($result->value)){
			return number_format($result->value, 2, '.', '');
		}
		
		// Default fallback value if setting doesn't exist
		return '25.00';
	}
	
	/** Get franchise fee percentage from global settings **/
	public function get_franchise_fee_percentage() {
		$this->db->select('value');
		$this->db->from('tbl_global_settings');
		$this->db->where('code', 'FRANCHISE_FEE_PERCENTAGE');
		$query = $this->db->get();
		$result = $query->row();
		
		if($result && isset($result->value)){
			return floatval($result->value);
		}
		
		return 2.00;
	}
	
	/** Process single customer balance forward **/
	public function process_single_customer($customer_data, $bp_month, $bp_year, $bp_current_month, $bp_current_year) {
		// Get billing period
		$this->db->where('bp_period_month', $bp_month);
		$this->db->where('bp_period_year', $bp_year);
		$this->db->where('bp_zone_id', $customer_data->zone);
		$bp = $this->db->get('tbl_billing_period')->row();
		
		if(!$bp){
			return false;
		}
		
		$bp_id = $bp->bp_id;

		$customerinfodataInsertDetails = array( 
			'customer_id' => $customer_data->customer_id,
			'month' => $bp_month, 
			'year' => $bp_year, 
			'bp_id' => $bp_id, 
		); 
		
		$checkresult_id = check_customerbillingrecord($customer_data->customer_id,$bp_id,$bp_month,$bp_year);
		
		if(!$checkresult_id){
			$this->db->where('doc_name', 'BILLING');
			$billing_number = $this->db->get('tbl_doc_series_number')->row();
			if($billing_number){
				$doc_num = $billing_number->doc_series_num+1;
				$customerinfodataInsertDetails1 = array( 
					'refno' => $doc_num,
					'customer_status' => $customer_data->status
				);
				$customerinfodataInsertDetails = array_merge($customerinfodataInsertDetails,$customerinfodataInsertDetails1);
				$this->db->insert('tbl_addcustomer_reading', $customerinfodataInsertDetails);
				$checkresult_id = $this->db->insert_id();

				$update_counter_array = array( 
					'doc_series_num' => $doc_num
				);
				$this->db->where('doc_name', 'BILLING');
				$this->db->update('tbl_doc_series_number', $update_counter_array);
			}
		}

		$customer_current_billing_data = currentbalance_forwarding_period($customer_data->customer_id,$bp_current_month,$bp_current_year);
		
		if($customer_current_billing_data && isset($customer_current_billing_data->id)){
			if($customer_current_billing_data->invoice_id!=NULL && $customer_current_billing_data->invoice_id!=''){
				$arrears = 0;
			}else{
				$arrears = isset($customer_current_billing_data->penalty) ? $customer_current_billing_data->penalty : 0;
			}
			$maintenance_fee = $this->get_maintenance_fee();
			
			// Calculate franchise fee
			$franchise_fee_percentage = $this->get_franchise_fee_percentage();
			$unit_price = isset($customer_current_billing_data->unit_price) ? $customer_current_billing_data->unit_price : 0;
			$sc_discount = isset($customer_current_billing_data->sc_discount) ? $customer_current_billing_data->sc_discount : 0;
			
			// Get customer account type
			$this->db->select('account_type');
			$this->db->from('tbl_addcustomer');
			$this->db->where('customer_id', $customer_data->customer_id);
			$customer_query = $this->db->get();
			$customer_account = $customer_query->row();
			$account_type = isset($customer_account->account_type) ? $customer_account->account_type : 0;
			
			if($account_type == 3){
				// SC: compute after SC deduction
				$bill_amount_for_franchise = $unit_price - $sc_discount;
			} else {
				// Non-SC: compute on unit price
				$bill_amount_for_franchise = $unit_price;
			}
			$franchise_fee_amount = ($bill_amount_for_franchise * $franchise_fee_percentage) / 100;
			
			$update_counter_array1 = array( 
				'previous_reading' => $customer_current_billing_data->reading,
				'arrears' => $arrears,
				'customer_status' => $customer_data->status,
				'maintenance_fee' => $maintenance_fee,
				'franchise_fee_percent' => number_format($franchise_fee_percentage, 2, '.', ''),
				'franchise_fee_amount' => number_format($franchise_fee_amount, 2, '.', ''),
			);
			$this->db->where('id', $checkresult_id);
			$this->db->update('tbl_addcustomer_reading', $update_counter_array1);
		}
		
		return true;
	}
}
?>

