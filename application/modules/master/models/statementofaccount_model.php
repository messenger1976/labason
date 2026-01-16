<?php 
class statementofaccount_model extends CI_Model {
	public $table_customer = 'tbl_addcustomer';
	public $table_reading = 'tbl_addcustomer_reading';
	public $table_payment = 'tbl_addmetercustomer';
	public $table_months = 'tbl_months';
	public $table_zone = 'tbl_zone';
	public $table_classification = 'tbl_classification';
	public $table_customer_type = 'tbl_customer_type';
	public $table_billing_period = 'tbl_billing_period';
	
	// Autoloading a system library using constructor method
	public function __construct() {
        parent::__construct();
		ini_set('date.timezone', 'Asia/Manila');	
    }
	
	/** Get customer information **/
	public function get_customer_info($customer_id='') {
        $this->db->select($this->table_customer.".*,".$this->table_zone.".zone,".$this->table_classification.".class_name,".$this->table_customer_type.".cust_type_name");
		$this->db->from($this->table_customer);
		$this->db->join($this->table_zone, $this->table_customer.'.zone = '.$this->table_zone.'.id', 'left');
		$this->db->join($this->table_classification, $this->table_customer.'.classification = '.$this->table_classification.'.class_id', 'left');
		$this->db->join($this->table_customer_type, $this->table_customer.'.account_type = '.$this->table_customer_type.'.cust_type_id', 'left');
		if($customer_id != ''){
			$this->db->where($this->table_customer.".customer_id",$customer_id);
			$query = $this->db->get();
			$result = $query->row_array();
		}
		return $result;
    }
	
	/** Get all customer ledger entries (readings and payments) **/
	public function get_customer_ledger($customer_id='') {
		// Get billing entries (readings)
		$this->db->select($this->table_reading.".*,".$this->table_months.".month_name,".$this->table_billing_period.".bp_due_date");
		$this->db->from($this->table_reading);
		$this->db->join($this->table_months, $this->table_reading.'.month = '.$this->table_months.'.month_id', 'left');
		$this->db->join($this->table_billing_period, $this->table_reading.'.bp_id = '.$this->table_billing_period.'.bp_id', 'left');
		$this->db->where($this->table_reading.'.customer_id', $customer_id);
		$this->db->where($this->table_reading.'.reading !=', '0');
		$this->db->where($this->table_reading.'.reading !=', '');
		$query_readings = $this->db->get();
		$readings = $query_readings->result_array();
		
		// Get payment entries - select total column for credit amount
		$this->db->select($this->table_payment.".*,".$this->table_months.".month_name");
		$this->db->from($this->table_payment);
		$this->db->join($this->table_months, $this->table_payment.'.month = '.$this->table_months.'.month_id', 'left');
		$this->db->where($this->table_payment.'.customer_id', $customer_id);
		$query_payments = $this->db->get();
		$payments = $query_payments->result_array();
		
		// Combine and format entries
		$ledger_entries = array();
		
		// Add billing entries
		foreach($readings as $reading){
			// Handle date format - convert d-m-Y to Y-m-d if needed
			$entry_date = '';
			if(!empty($reading['date'])){
				// Check if date is in d-m-Y format
				if(strpos($reading['date'], '-') !== false && strlen($reading['date']) == 10){
					$date_parts = explode('-', $reading['date']);
					if(count($date_parts) == 3 && strlen($date_parts[0]) == 2){
						// d-m-Y format, convert to Y-m-d
						$entry_date = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
					} else {
						$entry_date = $reading['date'];
					}
				} else {
					$entry_date = $reading['date'];
				}
			} else {
				$entry_date = $reading['year'].'-'.str_pad($reading['month'], 2, '0', STR_PAD_LEFT).'-01';
			}
			
			$ledger_entries[] = array(
				'date' => $entry_date,
				'type' => 'billing',
				'refno' => isset($reading['refno']) ? $reading['refno'] : '',
				'description' => 'Billing - '.$reading['month_name'].' '.$reading['year'],
				'debit' => isset($reading['amount']) ? floatval($reading['amount']) : 0,
				'credit' => 0,
				'reading' => isset($reading['reading']) ? $reading['reading'] : '',
				'previous_reading' => isset($reading['previous_reading']) ? $reading['previous_reading'] : '',
				'consumed' => isset($reading['consumed']) ? $reading['consumed'] : '',
				'unit_price' => isset($reading['unit_price']) ? $reading['unit_price'] : '',
				'penalty' => isset($reading['penalty']) ? floatval($reading['penalty']) : 0,
				'sc_discount' => isset($reading['sc_discount']) ? $reading['sc_discount'] : '',
				'arrears' => isset($reading['arrears']) ? $reading['arrears'] : '',
				'maintenance_fee' => isset($reading['maintenance_fee']) ? $reading['maintenance_fee'] : '',
				'due_date' => isset($reading['bp_due_date']) ? $reading['bp_due_date'] : '',
				'raw_data' => $reading
			);
		}
		
		// Add payment entries
		foreach($payments as $payment){
			// Handle date format
			$entry_date = '';
			if(!empty($payment['date'])){
				// Check if date is in d-m-Y format
				if(strpos($payment['date'], '-') !== false && strlen($payment['date']) == 10){
					$date_parts = explode('-', $payment['date']);
					if(count($date_parts) == 3 && strlen($date_parts[0]) == 2){
						// d-m-Y format, convert to Y-m-d
						$entry_date = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
					} else {
						$entry_date = $payment['date'];
					}
				} else {
					$entry_date = $payment['date'];
				}
			} else {
				$entry_date = $payment['year'].'-'.str_pad($payment['month'], 2, '0', STR_PAD_LEFT).'-01';
			}
			
			$or_number = isset($payment['or_number']) && !empty($payment['or_number']) ? $payment['or_number'] : (isset($payment['invoice_id']) ? $payment['invoice_id'] : 'N/A');
			
			$ledger_entries[] = array(
				'date' => $entry_date,
				'type' => 'payment',
				'refno' => $or_number,
				'description' => 'Payment - OR# '.$or_number,
				'debit' => 0,
				'credit' => isset($payment['total']) ? floatval($payment['total']) : (isset($payment['pay_amount']) ? floatval($payment['pay_amount']) : 0),
				'raw_data' => $payment
			);
		}
		
		// Sort by date descending (newest first)
		usort($ledger_entries, function($a, $b) {
			$dateA = strtotime($a['date']);
			$dateB = strtotime($b['date']);
			
			// If date parsing fails, try to handle it
			if($dateA === false) {
				$dateA = 0;
			}
			if($dateB === false) {
				$dateB = 0;
			}
			
			if ($dateA == $dateB) {
				// If same date, put payments after billings
				if($a['type'] == 'payment' && $b['type'] == 'billing') return 1;
				if($a['type'] == 'billing' && $b['type'] == 'payment') return -1;
				return 0;
			}
			// Sort descending (newest first) - reverse the comparison
			return $dateB - $dateA;
		});
		
		return $ledger_entries;
	}
	
	/** Calculate running balance for ledger entries **/
	public function calculate_running_balance($ledger_entries) {
		$balance = 0;
		foreach($ledger_entries as &$entry){
			$balance = $balance + $entry['debit'] - $entry['credit'];
			$entry['balance'] = $balance;
		}
		return $ledger_entries;
	}
	
	/** Get current balance for customer **/
	public function get_current_balance($customer_id='') {
		// Get total billings
		$this->db->select_sum('amount');
		$this->db->from($this->table_reading);
		$this->db->where('customer_id', $customer_id);
		$this->db->where('reading !=', '0');
		$this->db->where('reading !=', '');
		$query_billings = $this->db->get();
		$total_billings = $query_billings->row()->amount ? $query_billings->row()->amount : 0;
		
		// Get total payments - use total column instead of pay_amount
		$this->db->select_sum('total');
		$this->db->from($this->table_payment);
		$this->db->where('customer_id', $customer_id);
		$query_payments = $this->db->get();
		$total_payments = $query_payments->row()->total ? $query_payments->row()->total : 0;
		
		$balance = $total_billings - $total_payments;
		return $balance;
	}
	
	/** Get customer password for validation **/
	public function get_customer_password($customer_id='') {
		if($customer_id == ''){
			return false;
		}
		$this->db->select('password');
		$this->db->from($this->table_customer);
		$this->db->where('customer_id', $customer_id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$result = $query->row_array();
			return isset($result['password']) ? $result['password'] : false;
		}
		return false;
	}
	
}
?>

