<?php 
class leakingentry_model extends CI_Model {
	public $table_name = 'tbl_leaking_ledger';
	public $table_leaking_ledger_details = 'tbl_leaking_ledger_details';
	public $table_leaking_ledger = 'tbl_leaking_ledger';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_customer = 'tbl_addcustomer';
	public $table_customer_meter_reading = 'tbl_addcustomer_reading';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
	/** In Function Get all records from select table **/
    public function get_all_records() {
		$this->db->select($this->table_name.".*,".$this->table_customer.".*,".$this->table_customer_meter_reading.".month, ".$this->table_customer_meter_reading.".year, ".$this->table_customer_meter_reading.".amount, ".$this->table_customer_meter_reading.".previous_reading, ".$this->table_customer_meter_reading.".reading, ".$this->table_customer_meter_reading.".refno as meter_refno, ".$this->table_customer_meter_reading.".consumed, ".$this->table_customer_meter_reading.".sc_discount, ".$this->table_customer_meter_reading.".arrears, ".$this->table_customer_meter_reading.".unit_price, ".$this->table_customer_meter_reading.".penalty, ".$this->table_customer_meter_reading.".maintenance_fee, ".$this->table_customer_meter_reading.".franchise_fee_percent, ".$this->table_customer_meter_reading.".franchise_fee_amount, ".$this->table_customer_meter_reading.".date");
		$this->db->from($this->table_name);
		$this->db->join($this->table_customer, $this->table_name.".leaking_customer_id = ".$this->table_customer.".customer_id", 'left');
		$this->db->join($this->table_customer_meter_reading, $this->table_name.".leaking_refno = ".$this->table_customer_meter_reading.".refno", 'left');
		$this->db->order_by($this->table_name.'.leaking_id','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }

	public function get_ledger_details_records($leaking_id) {
        $this->db->select('*');
		$this->db->from($this->table_leaking_ledger_details);
		$this->db->where($this->table_leaking_ledger_details.'.leaking_id',$leaking_id);
		$this->db->order_by($this->table_leaking_ledger_details.'.leakingledgerdetails_id','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }

	public function get_ledger_records($leaking_id) {
        $this->db->select('*');
		$this->db->from($this->table_leaking_ledger);
		$this->db->where($this->table_leaking_ledger.'.leaking_id',$leaking_id);
		//$this->db->order_by($this->table_leaking_ledger.'.leakingledgerdetails_id','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	
 	/** In Function Get single records for edit view purpose from select table **/
    public function get_single_record($id='') {
        $this->db->select("*");
		$this->db->from($this->table_name);
		if($id != ''){
			$this->db->where("leaking_id",$id);
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
		
		$paymentdate = date('Y-m-d',strtotime($this->input->post('payment_date')));
		$duedate = date('Y-m-d',strtotime($this->input->post('due_date')));

		$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
		
		$created_date = $dt_date->format("Y-m-d H:i:s");

		$set_data = array(
		                   
			'leaking_refno' => $this->input->post('refno'),
			'leaking_customer_id' => $this->input->post('customer_id'),
			'leaking_discount_percent' => $this->input->post('leaking_percent'),
			'leaking_discount_amount' => $this->input->post('leaking_amount'),
			'leaking_date' => $paymentdate,
			'leaking_bill_duedate' => $duedate,
			'leaking_total_amount' => $this->input->post('bill_amount'),
			'leaking_bill_amount' => $this->input->post('gross_amount'),
			'leaking_status' => $this->input->post('leaking_status'),
			'leaking_created_datetime' => $created_date
			
		);
		$result = $this->db->insert($this->table_name, $set_data); 
		return $result;
	}

	/** In Function Add Payment records for select table leaking ledger details**/
	public function add_payment_record(){
		
		$paymentdate = date('Y-m-d',strtotime($this->input->post('transdate')));
		
		$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
		
		$created_date = $dt_date->format("Y-m-d H:i:s");

		$set_data = array(
		    'leaking_id' => $this->input->post('leaking_id'),
			'leakingledgerdetails_or_number' => $this->input->post('refno'),
			'leakingledgerdetails_source_type' => $this->input->post('source_type'),
			'leakingledgerdetails_amount' => $this->input->post('amount_pay'),
			'leakingledgerdetails_prev_balance' => $this->input->post('prev_balance'),
			'leakingledgerdetails_balance' => $this->input->post('balance_amount')-$this->input->post('amount_pay'),
			'leakingledgerdetails_remarks' => $this->input->post('remarks'),
			'leakingledgerdetails_transdate' => $paymentdate,
			'leakingledgerdetails_source_module' => 'leaking',
			//'leaking_total_amount' => $this->input->post('bill_amount'),
			//'leaking_bill_amount' => $this->input->post('gross_amount'),
			//'leaking_status' => $this->input->post('leaking_status'),
			'leakingledgerdetails_created_datetime' => $created_date
			
		);
		$result = $this->db->insert($this->table_leaking_ledger_details, $set_data); 

		$total_pay = $this->input->post('balance_amount')-$this->input->post('amount_pay');

		$set_data = array(
		                  
			'leaking_balance' => $total_pay,
			'leaking_updated_datetime' => $created_date
		);
		if($total_pay==0){
			$set_data1 = array(
		    	'leaking_status' => 5,
			);
			$set_data = array_merge($set_data,$set_data1);
		}
		$this->db->where('leaking_id',$this->input->post('leaking_id'));
		$result = $this->db->update($this->table_name, $set_data); 

		return $result;
	}
  	/** In Function Update records for select table **/
	public function update_record($id){
		$paymentdate = date('Y-m-d',strtotime($this->input->post('payment_date')));
		$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
		
		$updated_date = $dt_date->format("Y-m-d H:i:s");

		$set_data = array(
		                  
			'leaking_discount_percent' => $this->input->post('leaking_percent'),
			'leaking_discount_amount' => $this->input->post('leaking_amount'),
			'leaking_date' => $paymentdate,
			'leaking_total_amount' => $this->input->post('bill_amount'),
			'leaking_bill_amount' => $this->input->post('gross_amount'),
			'leaking_status' => $this->input->post('leaking_status'),
			'leaking_created_datetime' => $updated_date
						
					);
		$this->db->where('leaking_id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	
  	/** In Function Delete records for select table **/
	public function delete_record($id){
		// Start transaction
		$this->db->trans_start();
		
		// Delete ledger details first (foreign key constraint)
		$this->db->where('leaking_id',$id);
		$this->db->delete($this->table_leaking_ledger_details);
		
		// Delete main leaking entry
		$this->db->where('leaking_id',$id);
		$result = $this->db->delete($this->table_name);
		
		// Complete transaction
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			return false;
		}
		
		return $result;
	}
	
  	/** In Function Status Update records for select table **/
	public function status_record($id,$status){
		//$sts = ($status == 1 ? 0 : 1);
		$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
		
		$updated_date = $dt_date->format("Y-m-d H:i:s");
		$set_data = array(
			'leaking_status' => $status,
			'leaking_updated_datetime' => $updated_date
		);
		$this->db->where('leaking_id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	public function get_total_payment($leaking_id){
		$this->db->select('SUM(leakingledgerdetails_amount) as totalpayment');
		$this->db->from($this->table_leaking_ledger_details);
		$this->db->where($this->table_leaking_ledger_details.'.leaking_id',$leaking_id);
		$query = $this->db->get();
		$result = $query->row_array();
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

	public function get_soa_header_statement($leaking_id){
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('leaking_id',$leaking_id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_soa_statement($leaking_id){
		$this->db->select('*');
		$this->db->from($this->table_leaking_ledger_details);
		$this->db->where('leaking_id',$leaking_id);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	public function get_soa_statement_OR($orno){
		$this->db->select('*');
		$this->db->from($this->table_leaking_ledger_details);
		$this->db->join($this->table_leaking_ledger,$this->table_leaking_ledger_details.'.leaking_id = '.$this->table_leaking_ledger.'.leaking_id','left');
		$this->db->where('leakingledgerdetails_or_number',$orno);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	public function get_soa_statement_transdate($transdate){
		$this->db->select('*');
		$this->db->from($this->table_leaking_ledger_details);
		$this->db->join($this->table_leaking_ledger,$this->table_leaking_ledger_details.'.leaking_id = '.$this->table_leaking_ledger.'.leaking_id','left');
		$this->db->join($this->table_customer,$this->table_leaking_ledger.'.leaking_customer_id = '.$this->table_customer.'.customer_id','left');
		$this->db->where('leakingledgerdetails_transdate',$transdate);
		$this->db->where('leakingledgerdetails_source_module','leaking');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	public function get_meterreading_refno($refno){
		$this->db->select('*');
		$this->db->from($this->table_customer_meter_reading);
		$this->db->where('refno',$refno);
		$result = $this->db->get()->row_array();
		return $result;
	}

	/** Last N billing periods before the given period (excludes current; for print forms). */
	public function get_recent_billing_records($customer_id, $limit = 3, $end_year = null, $end_month = null) {
		$this->db->select($this->table_customer_meter_reading.'.*, tbl_months.month_name');
		$this->db->from($this->table_customer_meter_reading);
		$this->db->join('tbl_months', $this->table_customer_meter_reading.'.month = tbl_months.month_id', 'left');
		$this->db->where($this->table_customer_meter_reading.'.customer_id', $customer_id);
		$this->db->where($this->table_customer_meter_reading.'.reading !=', '0');
		$this->db->where($this->table_customer_meter_reading.'.reading !=', '');
		if ($end_year !== null && $end_month !== null) {
			$this->db->where('('.$this->table_customer_meter_reading.'.year < '.(int)$end_year
				.' OR ('.$this->table_customer_meter_reading.'.year = '.(int)$end_year
				.' AND '.$this->table_customer_meter_reading.'.month < '.(int)$end_month.'))', null, false);
		}
		$this->db->order_by($this->table_customer_meter_reading.'.year', 'DESC');
		$this->db->order_by($this->table_customer_meter_reading.'.month', 'DESC');
		$this->db->limit((int)$limit);
		$query = $this->db->get();
		$result = $query->result_array();
		return array_reverse($result);
	}
	
}


?>