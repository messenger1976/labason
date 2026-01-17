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
		
		// Get payment entries - select total column for credit amount and join with billing period to get due dates
		$this->db->select($this->table_payment.".*,".$this->table_months.".month_name,".$this->table_reading.".bp_id,".$this->table_billing_period.".bp_due_date");
		$this->db->from($this->table_payment);
		$this->db->join($this->table_months, $this->table_payment.'.month = '.$this->table_months.'.month_id', 'left');
		$this->db->join($this->table_reading, $this->table_payment.'.customer_id = '.$this->table_reading.'.customer_id AND '.$this->table_payment.'.month = '.$this->table_reading.'.month AND '.$this->table_payment.'.year = '.$this->table_reading.'.year', 'left');
		$this->db->join($this->table_billing_period, $this->table_reading.'.bp_id = '.$this->table_billing_period.'.bp_id', 'left');
		$this->db->where($this->table_payment.'.customer_id', $customer_id);
		$query_payments = $this->db->get();
		$payments = $query_payments->result_array();
		
		// Combine and format entries
		$ledger_entries = array();
		
		// Get current date for due date comparison
		$current_date = date('Y-m-d');
		
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
			
			// Get billing amount
			$billing_amount = isset($reading['amount']) ? floatval($reading['amount']) : 0;
			$penalty_amount = isset($reading['penalty']) ? floatval($reading['penalty']) : 0;
			$due_date = isset($reading['bp_due_date']) ? $reading['bp_due_date'] : '';
			
			// Check if there's a payment for this billing period and if payment is after due date
			$use_penalty_amount = false;
			
			if(!empty($due_date)) {
				$due_date_obj = strtotime($due_date);
				
				if($due_date_obj !== false) {
					// Check if there's a payment for this specific billing period (by month and year)
					foreach($payments as $payment) {
						$payment_month = isset($payment['month']) ? $payment['month'] : null;
						$payment_year = isset($payment['year']) ? $payment['year'] : null;
						
						// Match by month and year
						if($payment_month == $reading['month'] && $payment_year == $reading['year']) {
							// Payment exists for this billing period, check if payment date is after due date
							$payment_date = '';
							if(!empty($payment['date'])) {
								// Handle date format - convert d-m-Y to Y-m-d if needed
								if(strpos($payment['date'], '-') !== false && strlen($payment['date']) == 10) {
									$date_parts = explode('-', $payment['date']);
									if(count($date_parts) == 3 && strlen($date_parts[0]) == 2) {
										// d-m-Y format, convert to Y-m-d
										$payment_date = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
									} else {
										$payment_date = $payment['date'];
									}
								} else {
									$payment_date = $payment['date'];
								}
							} else {
								$payment_date = $payment['year'].'-'.str_pad($payment['month'], 2, '0', STR_PAD_LEFT).'-01';
							}
							
							$payment_date_obj = strtotime($payment_date);
							if($payment_date_obj !== false && $payment_date_obj > $due_date_obj) {
								// Payment was made after due date - use penalty amount
								$use_penalty_amount = true;
								break;
							}
						}
					}
				}
			}
			
			// Calculate debit amount - use penalty if payment is after due date, otherwise use amount
			$debit_amount = $use_penalty_amount && $penalty_amount > 0 ? $penalty_amount : $billing_amount;
			
			$ledger_entries[] = array(
				'date' => $entry_date,
				'type' => 'billing',
				'refno' => isset($reading['refno']) ? $reading['refno'] : '',
				'description' => 'Billing - '.$reading['month_name'].' '.$reading['year'],
				'debit' => $debit_amount,
				'credit' => 0,
				'reading' => isset($reading['reading']) ? $reading['reading'] : '',
				'previous_reading' => isset($reading['previous_reading']) ? $reading['previous_reading'] : '',
				'consumed' => isset($reading['consumed']) ? $reading['consumed'] : '',
				'unit_price' => isset($reading['unit_price']) ? $reading['unit_price'] : '',
				'penalty' => $penalty_amount,
				'penalty_included' => ($use_penalty_amount && $penalty_amount > 0) ? true : false,
				'sc_discount' => isset($reading['sc_discount']) ? $reading['sc_discount'] : '',
				'arrears' => isset($reading['arrears']) ? $reading['arrears'] : '',
				'maintenance_fee' => isset($reading['maintenance_fee']) ? $reading['maintenance_fee'] : '',
				'due_date' => $due_date,
				'raw_data' => $reading
			);
		}
		
		// Group payments by OR number (refno) to combine multiple billing periods
		$payment_groups = array();
		foreach($payments as $payment){
			$or_number = isset($payment['or_number']) && !empty($payment['or_number']) ? $payment['or_number'] : (isset($payment['invoice_id']) ? $payment['invoice_id'] : 'N/A');
			
			// Use OR number as key for grouping
			if(!isset($payment_groups[$or_number])) {
				// Handle date format for the first payment in group
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
				
			// Get credit amount for first payment (don't sum)
			$payment_credit = isset($payment['total']) ? floatval($payment['total']) : (isset($payment['pay_amount']) ? floatval($payment['pay_amount']) : 0);
			
			$payment_groups[$or_number] = array(
				'date' => $entry_date,
				'refno' => $or_number,
				'credit' => $payment_credit, // Use first payment's amount, don't sum
				'billing_periods' => array(), // For display
				'billing_periods_data' => array(), // For matching: array of ['month' => X, 'year' => Y]
				'raw_data' => $payment // Keep first payment's raw data
			);
		}
		
		// Collect billing period info
		if(isset($payment['month_name']) && !empty($payment['month_name']) && isset($payment['year']) && !empty($payment['year'])) {
			$period_key = $payment['month_name'].' '.$payment['year'];
			if(!in_array($period_key, $payment_groups[$or_number]['billing_periods'])) {
				$payment_groups[$or_number]['billing_periods'][] = $period_key;
			}
			// Store month/year for matching
			$period_data_key = $payment['month'].'_'.$payment['year'];
			if(!isset($payment_groups[$or_number]['billing_periods_data'][$period_data_key])) {
				$payment_groups[$or_number]['billing_periods_data'][$period_data_key] = array(
					'month' => $payment['month'],
					'year' => $payment['year']
				);
			}
		} elseif(isset($payment['month']) && !empty($payment['month']) && isset($payment['year']) && !empty($payment['year'])) {
			$period_key = 'Month '.$payment['month'].' '.$payment['year'];
			if(!in_array($period_key, $payment_groups[$or_number]['billing_periods'])) {
				$payment_groups[$or_number]['billing_periods'][] = $period_key;
			}
			// Store month/year for matching
			$period_data_key = $payment['month'].'_'.$payment['year'];
			if(!isset($payment_groups[$or_number]['billing_periods_data'][$period_data_key])) {
				$payment_groups[$or_number]['billing_periods_data'][$period_data_key] = array(
					'month' => $payment['month'],
					'year' => $payment['year']
				);
			}
		}
		}
		
		// Add grouped payment entries to ledger
		foreach($payment_groups as $group) {
			// Build payment description with all billing periods
			$payment_description = 'Payment - OR# '.$group['refno'];
			if(!empty($group['billing_periods'])) {
				if(count($group['billing_periods']) == 1) {
					$payment_description .= ' (Billing Period: '.$group['billing_periods'][0].')';
				} else {
					$payment_description .= ' (Billing Periods: '.implode(', ', $group['billing_periods']).')';
				}
			}
			
			$ledger_entries[] = array(
				'date' => $group['date'],
				'type' => 'payment',
				'refno' => $group['refno'],
				'description' => $payment_description,
				'debit' => 0,
				'credit' => $group['credit'], // Use first payment's amount, not summed
				'raw_data' => $group['raw_data'],
				'billing_periods_data' => $group['billing_periods_data'] // Store for matching logic
			);
		}
		
		// Keep original billing amounts - do not adjust based on payments
		
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
				// If same date, put payments before billings
				if($a['type'] == 'payment' && $b['type'] == 'billing') return -1;
				if($a['type'] == 'billing' && $b['type'] == 'payment') return 1;
				return 0;
			}
			// Sort descending (newest first) - reverse the comparison
			return $dateB - $dateA;
		});
		
		// Second pass: Check if due billings are paid and add penalty to debit if needed
		// Process chronologically (oldest first) to check payment status accurately
		$ledger_entries_chronological = $ledger_entries;
		usort($ledger_entries_chronological, function($a, $b) {
			$dateA = strtotime($a['date']);
			$dateB = strtotime($b['date']);
			
			if($dateA === false) $dateA = 0;
			if($dateB === false) $dateB = 0;
			
			if ($dateA == $dateB) {
				if($a['type'] == 'billing' && $b['type'] == 'payment') return -1;
				if($a['type'] == 'payment' && $b['type'] == 'billing') return 1;
				return 0;
			}
			return $dateA - $dateB; // Ascending order (oldest first)
		});
		
		// Keep original billing amounts - no second pass modifications
		
		return $ledger_entries;
	}
	
	/** Calculate running balance for ledger entries **/
	public function calculate_running_balance($ledger_entries) {
		// Sort chronologically (oldest first) for correct balance calculation
		$entries_chronological = $ledger_entries;
		usort($entries_chronological, function($a, $b) {
			$dateA = strtotime($a['date']);
			$dateB = strtotime($b['date']);
			
			if($dateA === false) $dateA = 0;
			if($dateB === false) $dateB = 0;
			
			if ($dateA == $dateB) {
				// If same date, put billings before payments for chronological calculation
				if($a['type'] == 'billing' && $b['type'] == 'payment') return -1;
				if($a['type'] == 'payment' && $b['type'] == 'billing') return 1;
				return 0;
			}
			return $dateA - $dateB; // Ascending order (oldest first)
		});
		
		// Calculate running balance chronologically (oldest to newest)
		$balance = 0;
		foreach($entries_chronological as &$entry){
			$balance = $balance + $entry['debit'] - $entry['credit'];
			$entry['balance'] = $balance;
		}
		
		// Create a map of balance by entry index for quick lookup
		$balance_map = array();
		foreach($entries_chronological as $index => $entry) {
			// Use a unique key based on type, date, refno, and description
			$key = $entry['type'].'_'.$entry['date'].'_'.(isset($entry['refno']) ? $entry['refno'] : '').'_'.md5($entry['description']);
			$balance_map[$key] = $entry['balance'];
		}
		
		// Apply balances to original order (newest first)
		foreach($ledger_entries as &$entry) {
			$key = $entry['type'].'_'.$entry['date'].'_'.(isset($entry['refno']) ? $entry['refno'] : '').'_'.md5($entry['description']);
			if(isset($balance_map[$key])) {
				$entry['balance'] = $balance_map[$key];
			} else {
				// Fallback: calculate on the fly if key not found
				$entry['balance'] = 0;
			}
		}
		
		return $ledger_entries;
	}
	
	/** Get current billing balance for customer (Total Debit - Total Credit) **/
	public function get_current_balance($customer_id='') {
		// Get ledger entries to calculate total debit and total credit
		$ledger_entries = $this->get_customer_ledger($customer_id);
		
		// Calculate total debit and total credit
		$total_debit = 0;
		$total_credit = 0;
		
		foreach($ledger_entries as $entry) {
			$total_debit += isset($entry['debit']) ? floatval($entry['debit']) : 0;
			$total_credit += isset($entry['credit']) ? floatval($entry['credit']) : 0;
		}
		
		// Current billing balance = Total Debit - Total Credit
		return $total_debit - $total_credit;
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
	
	/** Update customer password by customer_id **/
	public function update_customer_password_by_customer_id($customer_id, $password) {
		try {
			// First check if password column exists
			$query = $this->db->query("SHOW COLUMNS FROM `".$this->table_customer."` LIKE 'password'");
			if($query->num_rows() == 0) {
				// Column doesn't exist, try to create it
				$alter_query = "ALTER TABLE `".$this->table_customer."` ADD COLUMN `password` VARCHAR(255) NULL";
				$alter_result = $this->db->query($alter_query);
				
				// Check if ALTER was successful by checking for errors
				if(!$alter_result) {
					log_message('error', 'Failed to create password column. Query: ' . $alter_query);
					// Column creation failed, but continue anyway - might already exist or permission issue
				}
			}
			
			// Now update the password using customer_id
			$this->db->where('customer_id', $customer_id);
			$data = array('password' => $password);
			$result = $this->db->update($this->table_customer, $data);
			
			// Log the query for debugging
			log_message('debug', 'Update password query: ' . $this->db->last_query());
			log_message('debug', 'Customer ID: ' . $customer_id . ', Affected rows: ' . $this->db->affected_rows());
			
			// Return true if update executed (affected_rows can be 0 if password was the same)
			return $result !== false;
			
		} catch(Exception $e) {
			log_message('error', 'Exception in update_customer_password_by_customer_id: ' . $e->getMessage());
			log_message('error', 'Exception trace: ' . $e->getTraceAsString());
			return false;
		}
	}
	
}
?>

