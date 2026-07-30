<?php 
class createbalanceforward_model extends CI_Model {
	public $table_name = 'tbl_zone';
    public $table_months = 'tbl_months';
    public $table_billing_period = 'tbl_billing_period';
	public $table_reading = 'tbl_addcustomer_reading';
	public $table_customer = 'tbl_addcustomer';
	public $table_zone = 'tbl_zone';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
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
        $this->db->distinct();
        $this->db->select("bp_period_month, bp_period_year, (Select month_name from ".$this->table_months." where ".$this->table_months.".month_id	= ".$this->table_billing_period.".bp_period_month ) as month_name");
		$this->db->from($this->table_billing_period);
		$this->db->where("bp_status <> ",2);
		$this->db->order_by('bp_period_year','desc');
		$this->db->order_by('bp_period_month','asc');
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
			/**
			 * ARREARS COMPUTATION LOGIC
			 * 
			 * Arrears represent the outstanding balance that needs to be carried forward
			 * to the next billing period. The computation depends on payment status:
			 * 
			 * CASE 1: INVOICE IS PAID (invoice_id exists and is not empty)
			 *   - If the invoice has been paid, there are no outstanding balances
			 *   - Set arrears to 0 (no balance to forward)
			 * 
			 * CASE 2: INVOICE IS UNPAID (invoice_id is NULL or empty)
			 *   - If the invoice is unpaid, we need to forward the outstanding balance
			 *   - Arrears = Current Period Penalty + Previous Period Arrears
			 *   - This ensures cumulative arrears are properly tracked across billing periods
			 */
			
			if($customer_current_billing_data->invoice_id!=NULL && $customer_current_billing_data->invoice_id!=''){
				/**
				 * INVOICE PAID - No arrears to forward
				 * 
				 * When an invoice is paid, all outstanding balances are cleared.
				 * Therefore, no arrears should be carried forward to the next period.
				 */
				$arrears = 0;
			}else{
				/**
				 * INVOICE UNPAID - Calculate cumulative arrears
				 * 
				 * For unpaid invoices, we need to forward the total outstanding balance
				 * which includes:
				 * 
				 * 1. Previous Arrears: Outstanding balance from previous billing periods
				 *    - Retrieved from the 'arrears' field in the current billing period data
				 *    - This represents cumulative unpaid amounts from earlier periods
				 * 
				 * 2. Current Penalty: Penalty amount for the current billing period
				 *    - Retrieved from the 'penalty' field in the current billing period data
				 *    - This represents the penalty applied to the current period's bill
				 * 
				 * Total Arrears = Current Penalty + Previous Arrears
				 * 
				 * This ensures that:
				 * - Previous unpaid balances are not lost
				 * - Current period penalties are included
				 * - Cumulative arrears are accurately tracked
				 */
				
				// Get previous arrears from the current billing period
				// This represents outstanding balances from previous periods
				$previous_arrears = isset($customer_current_billing_data->arrears) ? floatval($customer_current_billing_data->arrears) : 0;
				
				// Get current penalty for the current billing period
				// This represents the penalty amount applied to the current period's bill
				$current_penalty = isset($customer_current_billing_data->penalty) ? floatval($customer_current_billing_data->penalty) : 0;
				
				// Calculate total arrears: current penalty + previous arrears
				// This total will be forwarded to the new billing period
				$arrears = $current_penalty + $previous_arrears;
			}
			/**
			 * STEP 4: CALCULATE MAINTENANCE FEE
			 * 
			 * Retrieve the maintenance fee from global settings.
			 * This fee is applied to all customer bills regardless of consumption.
			 */
			$maintenance_fee = $this->get_maintenance_fee();
			
			/**
			 * STEP 5: CALCULATE FRANCHISE FEE
			 * 
			 * Franchise fee is calculated as a percentage of the bill amount.
			 * The calculation differs for Senior Citizen (SC) accounts vs regular accounts:
			 * 
			 * BUSINESS RULE: Senior citizen discount (5%) only applies if consumption <= 30 cubic meters
			 * 
			 * - SC Accounts (account_type == 3) WITH consumption <= 30 cu.m.:
			 *   Franchise fee is calculated on the bill amount AFTER senior citizen discount is applied
			 * - SC Accounts (account_type == 3) WITH consumption > 30 cu.m.:
			 *   Franchise fee is calculated on the full unit price (no discount applied)
			 * - Regular Accounts: Franchise fee is calculated on the full unit price
			 * 
			 * Formula: Franchise Fee Amount = (Bill Amount × Franchise Fee Percentage) / 100
			 */
			
			// Get franchise fee percentage from global settings
			$franchise_fee_percentage = $this->get_franchise_fee_percentage();
			
			// Get unit price and senior citizen discount from current billing data
			$unit_price = isset($customer_current_billing_data->unit_price) ? floatval($customer_current_billing_data->unit_price) : 0;
			$sc_discount = isset($customer_current_billing_data->sc_discount) ? floatval($customer_current_billing_data->sc_discount) : 0;
			
			// Calculate consumption from current billing period
			// Consumption = Current Reading - Previous Reading
			$current_reading = isset($customer_current_billing_data->reading) ? floatval($customer_current_billing_data->reading) : 0;
			$previous_reading = isset($customer_current_billing_data->previous_reading) ? floatval($customer_current_billing_data->previous_reading) : 0;
			$consumption = $current_reading - $previous_reading;
			
			// Get customer account type
			$this->db->select('account_type');
			$this->db->from('tbl_addcustomer');
			$this->db->where('customer_id', $customer_data->customer_id);
			$customer_query = $this->db->get();
			$customer_account = $customer_query->row();
			$account_type = isset($customer_account->account_type) ? $customer_account->account_type : 0;
			
			// Determine the bill amount base for franchise fee calculation
			// Check if customer is Senior Citizen AND consumption is 30 cubic meters or below
			if($account_type == 3 && $consumption <= 30){
				/**
				 * SENIOR CITIZEN ACCOUNT WITH CONSUMPTION <= 30 CUBIC METERS
				 * 
				 * For SC accounts with consumption <= 30 cu.m., franchise fee is calculated
				 * on the bill amount AFTER the senior citizen discount has been applied.
				 * This ensures the franchise fee is calculated on the actual amount billed
				 * after the discount.
				 * 
				 * Business Rule: Senior citizens can only avail the 5% discount if their
				 * consumption does not exceed 30 cubic meters per billing period.
				 */
				$bill_amount_for_franchise = $unit_price - $sc_discount;
			} else {
				/**
				 * REGULAR ACCOUNT OR SENIOR CITIZEN WITH CONSUMPTION > 30 CUBIC METERS
				 * 
				 * For regular accounts OR senior citizens exceeding 30 cu.m. consumption,
				 * franchise fee is calculated on the full unit price without any discount
				 * adjustments.
				 * 
				 * Note: Senior citizens exceeding 30 cu.m. are treated as regular accounts
				 * for franchise fee calculation purposes.
				 */
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

