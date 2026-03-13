<?php 
class addmetercustomerreading_model extends CI_Model {
	public $table_name = 'tbl_addcustomer_reading';
	public $table_name_monthly_dummy = 'tbl_monthlycustomer_dummy';
    public $table_customername = 'tbl_addcustomer';
	public $table_billing = 'tbl_feesplaning';
	public $table_address = 'tbl_web_settings';
	public $table_months = 'tbl_months';
	public $table_generate_customer = 'tbl_generate_customer';
	public $table_zone = 'tbl_zone';
	public $tbl_amount = 'tbl_amountrate';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_customer_type = 'tbl_customer_type';
	public $table_billing_period = 'tbl_billing_period';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
		ini_set('date.timezone', 'Asia/Manila');	
    }
	
	/** In Function Get all records from select table **/
    public function get_all_records($billing_period='') {
        $this->db->select($this->table_name.".*,".$this->table_months.".*,".$this->table_customer_type.".*,".$this->table_customername.".*,".$this->table_name.".customer_id as customer_id,".$this->table_name.".id as id, 
		(Select id  from ".$this->table_generate_customer." where ".$this->table_generate_customer.".insert_month_id = ".$this->table_name.".id) as id_generate");
		$this->db->from($this->table_name);
		$this->db->join($this->table_customername, $this->table_customername.".customer_id = ".$this->table_name.".customer_id", 'left');
		$this->db->join($this->table_months, $this->table_name.".month = ".$this->table_months.".month_id", 'left');
		$this->db->join($this->table_customer_type, $this->table_customername.".account_type = ".$this->table_customer_type.".cust_type_id", 'left');
		if($billing_period!=''){
			$billperiod = explode(' ',$billing_period);
			$this->db->where($this->table_name.'.month',$billperiod[0]);
			$this->db->where($this->table_name.'.year',$billperiod[1]);
		}
		$this->db->order_by($this->table_name.'.id','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	
	/** Get paginated records for server-side DataTables **/
	public function get_paginated_records($start = 0, $length = 100, $search = '', $order_column = 'tbl_addcustomer_reading.id', $order_dir = 'desc', $billing_period = '') {
		$this->db->select($this->table_name.".*,".$this->table_months.".*,".$this->table_customer_type.".*,".$this->table_customername.".*,".$this->table_name.".customer_id as customer_id,".$this->table_name.".id as id, 
		(Select id  from ".$this->table_generate_customer." where ".$this->table_generate_customer.".insert_month_id = ".$this->table_name.".id) as id_generate");
		$this->db->from($this->table_name);
		$this->db->join($this->table_customername, $this->table_customername.".customer_id = ".$this->table_name.".customer_id", 'left');
		$this->db->join($this->table_months, $this->table_name.".month = ".$this->table_months.".month_id", 'left');
		$this->db->join($this->table_customer_type, $this->table_customername.".account_type = ".$this->table_customer_type.".cust_type_id", 'left');
		
		// Apply billing period filter (from $_SESSION['current_billingperiod']) - reduces rows scanned for performance
		if($billing_period != ''){
			$billperiod = explode(' ', trim($billing_period));
			if(count($billperiod) == 2) {
				$this->db->where($this->table_name.'.month', $billperiod[0]);
				$this->db->where($this->table_name.'.year', $billperiod[1]);
			}
		}
		
		// Apply search filter
		if(!empty($search)) {
			$search_escaped = $this->db->escape_like_str($search);
			$this->db->where("(
				".$this->table_name.".refno LIKE '%".$search_escaped."%' OR
				".$this->table_name.".customer_id LIKE '%".$search_escaped."%' OR
				".$this->table_customername.".first_name LIKE '%".$search_escaped."%' OR
				".$this->table_customername.".last_name LIKE '%".$search_escaped."%' OR
				".$this->table_name.".previous_reading LIKE '%".$search_escaped."%' OR
				".$this->table_name.".reading LIKE '%".$search_escaped."%' OR
				".$this->table_name.".consumed LIKE '%".$search_escaped."%'
			)", NULL, FALSE);
		}
		
		// Apply ordering
		$this->db->order_by($order_column, $order_dir);
		
		// Apply pagination
		if($length > 0) {
			$this->db->limit($length, $start);
		}
		
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/** Get total count of records (with optional filters) **/
	public function get_total_count($search = '', $billing_period = '') {
		$this->db->select('COUNT('.$this->table_name.'.id) as total');
		$this->db->from($this->table_name);
		$this->db->join($this->table_customername, $this->table_customername.".customer_id = ".$this->table_name.".customer_id", 'left');
		$this->db->join($this->table_months, $this->table_name.".month = ".$this->table_months.".month_id", 'left');
		$this->db->join($this->table_customer_type, $this->table_customername.".account_type = ".$this->table_customer_type.".cust_type_id", 'left');
		
		// Apply billing period filter (from $_SESSION['current_billingperiod']) - reduces rows scanned for performance
		if($billing_period != ''){
			$billperiod = explode(' ', trim($billing_period));
			if(count($billperiod) == 2) {
				$this->db->where($this->table_name.'.month', $billperiod[0]);
				$this->db->where($this->table_name.'.year', $billperiod[1]);
			}
		}
		
		// Apply search filter
		if(!empty($search)) {
			$search_escaped = $this->db->escape_like_str($search);
			$this->db->where("(
				".$this->table_name.".refno LIKE '%".$search_escaped."%' OR
				".$this->table_name.".customer_id LIKE '%".$search_escaped."%' OR
				".$this->table_customername.".first_name LIKE '%".$search_escaped."%' OR
				".$this->table_customername.".last_name LIKE '%".$search_escaped."%' OR
				".$this->table_name.".previous_reading LIKE '%".$search_escaped."%' OR
				".$this->table_name.".reading LIKE '%".$search_escaped."%' OR
				".$this->table_name.".consumed LIKE '%".$search_escaped."%'
			)", NULL, FALSE);
		}
		
		$query = $this->db->get();
		$result = $query->row_array();
		return isset($result['total']) ? intval($result['total']) : 0;
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
		
		// Default fallback value if setting doesn't exist
		return 2.00;
	}
	
	public function add_record(){
		$get_date = $this->input->post('date');
		$parts = explode('-', $get_date);
		$year = $parts[2];
		if($this->input->post('year')!=''){
			$year = $this->input->post('year');
		}
		
        
		$customer_read = $this->input->post('customer_id');
		$month_name = $this->input->post('month');
		
		
		$this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where('customer_id',$customer_read);
		$this->db->where('month',$month_name);
		$this->db->where('year',$year);
		$query = $this->db->get();
		$count = $query->num_rows();
		if($count == 0){
			
			
			$this->db->where('doc_name', 'BILLING');
			$billing_number = $this->db->get('tbl_doc_series_number')->row();
			$doc_num = $billing_number->doc_series_num+1;
			
			// Get maintenance fee from global settings or use posted value if available
			$maintenance_fee = $this->input->post('maintenance_fee');
			if(empty($maintenance_fee) || $maintenance_fee == ''){
				$maintenance_fee = $this->get_maintenance_fee();
			}
			
			// Calculate franchise fee
			$customerinfo = $this->get_customer_info($this->input->post('customer_id'));
			$unit_price = $this->input->post('unit_price');
			$sc_discount = $this->input->post('discount');
			$account_type = isset($customerinfo[0]['account_type']) ? $customerinfo[0]['account_type'] : 0;
			
			$franchise_fee_percentage = $this->get_franchise_fee_percentage();
			if($account_type == 3){
				// SC: compute after SC deduction
				$bill_amount_for_franchise = $unit_price - $sc_discount;
			} else {
				// Non-SC: compute on unit price
				$bill_amount_for_franchise = $unit_price;
			}
			$franchise_fee_amount = ($bill_amount_for_franchise * $franchise_fee_percentage) / 100;

			$set_data = array(
				'customer_id' => trim($this->input->post('customer_id')),
				'previous_reading' => $this->input->post('preview'),
				'reading' => $this->input->post('current_meter'),
				'consumed' => $this->input->post('different'),
				'unit_price' => $this->input->post('unit_price'),
				'sc_discount' => $this->input->post('discount'),
				'penalty' => $this->input->post('amount_total_penalty'),
				'amount' => $this->input->post('total_amount'),
				'arrears' => $this->input->post('prev_balance'),
				'month' => $this->input->post('month'),
				'year' =>  $year,
				'date' => $this->input->post('date'),
				'bp_id' => $this->input->post('billing_period_id'),
				'userid' => $this->session->userdata('userid'),
				'username' => $this->session->userdata('username'),
				'refno' =>  $doc_num,
				'maintenance_fee' => $maintenance_fee,
				'franchise_fee_percent' => number_format($franchise_fee_percentage, 2, '.', ''),
				'franchise_fee_amount' => number_format($franchise_fee_amount, 2, '.', ''),
			);
			$result = $this->db->insert($this->table_name, $set_data); 
			$update_counter_array = array( 
				'doc_series_num' => $doc_num
			);
			
			$this->db->where('doc_name', 'BILLING');
			$this->db->update('tbl_doc_series_number', $update_counter_array);
			return $result;
		}
		else{
			$result = '0';
			return $result;
		}
	}
	
	/** In Function Update records for select table **/
	public function update_record($id){
		$compute_penalty = $this->input->post('compute_penalty') == '0' ? 0 : 1;
		
		// Get franchise fee values from posted form data (user may have edited them)
		$franchise_fee_percent = $this->input->post('franchise_fee_percent');
		$franchise_fee_amount = $this->input->post('franchise_fee_amount');
		
		// If franchise fee values are not posted, calculate them (fallback)
		if(empty($franchise_fee_percent) || $franchise_fee_percent == ''){
			$customerinfo = $this->get_customer_info($this->input->post('customer_id'));
			$unit_price = $this->input->post('current_bill');
			$sc_discount = $this->input->post('sc_discount');
			$account_type = isset($customerinfo[0]['account_type']) ? $customerinfo[0]['account_type'] : 0;
			
			$franchise_fee_percentage = $this->get_franchise_fee_percentage();
			if($account_type == 3){
				// SC: compute after SC deduction
				$bill_amount_for_franchise = $unit_price - $sc_discount;
			} else {
				// Non-SC: compute on unit price
				$bill_amount_for_franchise = $unit_price;
			}
			$franchise_fee_amount = ($bill_amount_for_franchise * $franchise_fee_percentage) / 100;
			$franchise_fee_percent = $franchise_fee_percentage;
		}
		
		// Clean and format the values
		$franchise_fee_percent = number_format(floatval(str_replace(',', '', $franchise_fee_percent)), 2, '.', '');
		$franchise_fee_amount = number_format(floatval(str_replace(',', '', $franchise_fee_amount)), 2, '.', '');
		
		$set_data = array(
						'customer_id' => trim($this->input->post('customer_id')),
						'previous_reading' => $this->input->post('previous_reading'),
						'reading' => $this->input->post('current_reading'),
						'consumed' => $this->input->post('consumed'),
						'unit_price' => $this->input->post('current_bill'),
						'sc_discount' => $this->input->post('sc_discount'),
						'arrears' => $this->input->post('arrears'),
						'amount' => $this->input->post('total_amount'),
						'penalty' => ($compute_penalty == 1 ? $this->input->post('penalty') : $this->input->post('total_amount')),
						'maintenance_fee' => $this->input->post('maintenance_fee'),
						'franchise_fee_percent' => $franchise_fee_percent,
						'franchise_fee_amount' => $franchise_fee_amount,
						'date' => $this->input->post('reading_date'),
						'customer_status' => $this->input->post('customer_status'),
					);
		if($this->db->field_exists('compute_penalty', $this->table_name)){
			$set_data['compute_penalty'] = $compute_penalty;
		}
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
	
	public function get_months(){
		$this->db->select("*");
		$this->db->from($this->table_months);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
		
	public function get_single_record($id='') {
        $this->db->select($this->table_name.".*,".$this->table_billing_period.".bp_due_date");
		$this->db->from($this->table_name);
		$this->db->join($this->table_billing_period, $this->table_name.'.bp_id = '.$this->table_billing_period.'.bp_id');
		if($id != ''){
			$this->db->where("id",$id);
			$query = $this->db->get();
			$result = $query->row_array();
		}
		return $result;
    }

	public function get_billing_period($zone_id='') {
        $this->db->select("*");
		$this->db->from($this->table_billing_period);
		$this->db->join($this->table_months, $this->table_months.'.month_id = '.$this->table_billing_period.'.bp_period_month');
		//if($zone != ''){
			$this->db->where("bp_zone_id",$zone_id);
			$this->db->where("bp_status",1);
			$query = $this->db->get();
			$result = $query->row_array();
		//}
		return $result;
    }
	
	public function get_billing_period_id($zone='',$bp_month='',$bp_year='') {
        $this->db->select("*");
		$this->db->from($this->table_billing_period);
		//$this->db->join($this->table_months, $this->table_months.'.month_id = '.$this->table_billing_period.'.bp_period_month');
		//if($zone != ''){
			$this->db->where("bp_period_month",$bp_month);
			$this->db->where("bp_period_year",$bp_year);
			$this->db->where("bp_zone_id",$zone);
			$query = $this->db->get()->row();
			//$result = $query->row();
		//}
		return $query;
    }
	public function get_customer_info($id){
		
		$this->db->select('
			tbl_addcustomer.special_priviledge,
			tbl_addcustomer.customer_id, 
			tbl_addcustomer.first_name, 
			tbl_addcustomer.middle_name, 
			tbl_addcustomer.last_name,
			tbl_addcustomer.email_id, 
			tbl_addcustomer.mobile1, 
			tbl_addcustomer.mobile2, 
			tbl_addcustomer.customer_type,
			tbl_addcustomer.account_type,
			tbl_addcustomer.classification,
			tbl_addcustomer.zone as zone_id, 
			tbl_zone.id, 
			tbl_zone.zone, 
			tbl_classification.class_name, 
			tbl_addcustomer.meter_number, 
			tbl_customer_type.*'
			);
		$this->db->from('tbl_addcustomer');
		$this->db->join('tbl_zone', 'tbl_addcustomer.zone = tbl_zone.id','left');
		$this->db->join('tbl_classification', 'tbl_addcustomer.classification = tbl_classification.class_id','left');
		$this->db->join('tbl_customer_type', 'tbl_addcustomer.account_type = tbl_customer_type.cust_type_id','left');
		//$this->db->where('tbl_addcustomer.customer_type','metercustomer');
		$this->db->where('tbl_addcustomer.customer_id',$id);
		//$this->db->or_where('tbl_addcustomer.email_id',$id);
		//$this->db->or_where('tbl_addcustomer.mobile1',$id);
		//$this->db->or_where('tbl_addcustomer.mobile2',$id);
        $query = $this->db->get();
		$result = $query->result_array();
		return $result;
		
	}

	public function get_addcustomer_meterreading_records($customer_id,$bp_month='',$bp_year='')
	{ 
		$this->db->select($this->table_customername.".customer_id,".$this->table_customername.".first_name,".$this->table_customername.".last_name,".$this->table_customername.".middle_name,".$this->table_customername.".gender,".$this->table_customername.".address,".$this->table_customername.".mobile1,".$this->table_customername.".mobile2,".$this->table_customername.".email_id,".$this->table_customername.".customer_type,".$this->table_name.".bp_id,".$this->table_name.".previous_reading,".$this->table_name.".reading,".$this->table_name.".consumed,".$this->table_name.".unit_price,".$this->table_name.".sc_discount,".$this->table_name.".penalty,".$this->table_name.".arrears,".$this->table_name.".amount,".$this->table_name.".month,".$this->table_name.".year,".$this->table_name.".maintenance_fee,".$this->table_name.".franchise_fee_percent,".$this->table_name.".franchise_fee_amount,".$this->table_name.".compute_penalty,".$this->table_name.".date,".$this->table_name.".refno,".$this->table_name.".id,".$this->table_months.".month_name,".$this->table_customername.".account_type,".$this->table_customername.".special_priviledge,".$this->table_name.".status,".$this->table_name.".customer_status");
		$this->db->from($this->table_customername);
		$this->db->join($this->table_name,$this->table_customername.'.customer_id='.$this->table_name.'.customer_id');
		$this->db->join($this->table_months,$this->table_name.'.month='.$this->table_months.'.month_id');
		if($customer_id!='')
		{
			$this->db->where($this->table_customername.'.customer_id',$customer_id);
		}
		if($bp_month!='' && $bp_year!='')
		{
			$this->db->where($this->table_name.'.month',$bp_month);
			$this->db->where($this->table_name.'.year',$bp_year);
		}
						
		$this->db->order_by($this->table_name.'.date','DESC');
		
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;			
	}	

	public function get_cust_class_id($cust_id='') {
        $this->db->select("*");
		$this->db->from($this->table_customername);
		if($cust_id != ''){
			$this->db->where("customer_id",$cust_id);
			$query = $this->db->get();
			$result = $query->row_array();
		}
		return $result;
    }
	public function get_last_reading($id){
		$this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where('customer_id', $id);
		$this->db->order_by("id", "desc");
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
		
	}
	public function get_single_record_refno($refno){
		$this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where('refno', $refno);
		$query = $this->db->get();
		$result = $query->row();
		return $result;
		
	}
	public function get_unit_price($last_reading=0, $class_id){
		$this->db->select("*");
		$this->db->from($this->tbl_amount);
		$this->db->where('cubic_meter', $last_reading);
		$this->db->where('classification_id', $class_id);
		$result = $this->db->get()->row();
		//$result = $query->result_array();
		return $result;
		
	}
	public function get_month_name($mon_id){
		$this->db->select("*");
		$this->db->from($this->table_months);
		$this->db->where('month_id', $mon_id);
		$query = $this->db->get();
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
		$this->db->from($this->table_customername);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	/**
	 * Update Meter Reading
	 * 
	 * Function: update_meterreading
	 * Last Modified: February 6, 2026
	 * 
	 * Purpose: Updates customer meter reading and recalculates billing amounts including:
	 *          - Consumption calculation (current_reading - previous_reading)
	 *          - Unit price based on consumption and classification
	 *          - Senior citizen discount (5% if account_type == 3 AND consumption <= 30 cubic meters)
	 *          - Franchise fee: ALWAYS based on current bill; SC discount applied when computing total
	 *          - Penalty calculation (10% if no special privilege)
	 * 
	 * @param array $data Contains: refno, customer_id, previous_reading, current_reading, billing_month, billing_year, reading_date
	 * @return string JSON response with success message
	 */
	public function update_meterreading($data){
		if(($data['refno']!='' && $data['refno']!=null) && ($data['customer_id']!='' && $data['customer_id']!=null) && ($data['current_reading']!='0' && $data['current_reading']!='' && $data['current_reading']!=null) && ($data['previous_reading']!='0' && $data['previous_reading']!='' && $data['previous_reading']!=null)){
			$customerinfo = $this->get_customer_info($data['customer_id']);
			$bp = $this->get_billing_period_id($customerinfo[0]['zone_id'],$data['billing_month'],$data['billing_year']);
			
			$consumed = $data['current_reading']-$data['previous_reading'];
			$cubicmeter_rate = $this->get_unit_price($consumed,$customerinfo[0]['classification']); // Get unit price
			$discount = 0;
			/**
			 * Senior Citizen Discount Calculation
			 * Date Modified: January 29, 2026
			 * Modified By: AI Assistant
			 * 
			 * Purpose: Apply senior citizen discount (5%) only if the customer is a senior citizen (account_type == 3)
			 *          AND the consumption (difference between current_reading and previous_reading) is 30 cubic meters or less.
			 * 
			 * Reason: Business rule requirement - Senior citizens cannot avail discount if their consumption exceeds 30 cubic meters.
			 *         This prevents abuse of the senior citizen discount privilege for excessive water consumption.
			 * 
			 * Previous Logic: Discount was applied to all senior citizens regardless of consumption amount.
			 * New Logic: Discount is only applied when consumption <= 30 cubic meters.
			 */
			if($customerinfo[0]['account_type']==3 && $consumed <= 30){
				$discount = ($cubicmeter_rate->per_unit * 5)/100;
			}
			
			$total_amount = $cubicmeter_rate->per_unit - $discount;
			$maintenance_fee = $this->get_single_record_refno($data['refno'])->maintenance_fee;
			$total_amount += $maintenance_fee;
			
			/**
			 * Franchise Fee Calculation - Based on Current Bill
			 * Date Modified: February 6, 2026
			 * 
			 * Business Rule: Franchise tax is ALWAYS computed on the current bill amount (unit_price).
			 *               Senior citizen discount (if applicable) is applied to the bill when
			 *               calculating the total, but franchise tax is based on the current bill
			 *               before any discount.
			 * 
			 * Total = current_bill - senior_citizen_discount + maintenance_fee + franchise_fee_amount
			 */
			$franchise_fee_percentage = $this->get_franchise_fee_percentage();
			$bill_amount_for_franchise = $cubicmeter_rate->per_unit;  // Always use current bill for franchise tax
			$franchise_fee_amount = ($bill_amount_for_franchise * $franchise_fee_percentage) / 100;
			$total_amount += $franchise_fee_amount;
			
			// Penalty: 10% applied to (current_bill - sc_discount) only, then add maintenance + franchise
			$amount_total_penalty = 0;
			if($customerinfo[0]['special_priviledge']==='0'){
				$penalty_base = $cubicmeter_rate->per_unit - $discount;  // current_bill - sc_discount
				$amount_total_penalty = ($penalty_base * 10) / 100;
				$amount_total_penalty = $amount_total_penalty + $penalty_base + $maintenance_fee + $franchise_fee_amount;
			}else{
				$amount_total_penalty = $total_amount;
			}
			$reading_date = date('d-m-Y',strtotime($data['reading_date']));

			$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
			
			$updated_date = $dt_date->format("Y-m-d H:i:s");


			//echo 'account type:'.$customerinfo[0]['special_priviledge'].' '.$data['billing_month'];
			//print_r($customerinfo);
			//exit;
		
			$sql = "UPDATE tbl_addcustomer_reading 
            SET reading = ?, consumed = ?, sc_discount = ?, amount = ?, unit_price = ?, penalty = ?, franchise_fee_percent = ?, franchise_fee_amount = ?, date = ? , bp_id = ?, update_date_time = ?
            WHERE refno = ? AND (reading ='' OR reading = 0)";
    		$this->db->query($sql, [$data['current_reading'],  $consumed, $discount, number_format($total_amount,2,".",""), $cubicmeter_rate->per_unit,number_format($amount_total_penalty,2,".",""), number_format($franchise_fee_percentage,2,".",""), number_format($franchise_fee_amount,2,".",""), $reading_date, $bp->bp_id,$updated_date,$data['refno']]);
			return '[{"msg":"success"}]';
		}
    	
	}
	
}
?>