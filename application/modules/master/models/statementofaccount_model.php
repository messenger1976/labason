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

	/**
	 * Normalize payment dates stored as d-m-Y or Y-m-d.
	 */
	private function _normalize_ledger_date($date_raw, $year = null, $month = null) {
		$entry_date = '';
		if (!empty($date_raw)) {
			if (strpos($date_raw, '-') !== false && strlen($date_raw) == 10) {
				$date_parts = explode('-', $date_raw);
				if (count($date_parts) == 3 && strlen($date_parts[0]) == 2) {
					$entry_date = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
				} else {
					$entry_date = $date_raw;
				}
			} else {
				$entry_date = $date_raw;
			}
		} elseif ($year !== null && $month !== null) {
			$entry_date = $year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-01';
		}
		return $entry_date;
	}

	/**
	 * Resolve meter-payment cash credit + leaking discount credit for SOA.
	 * Meter payment `total` is the bill amount (paid_total_amount) and remains the
	 * settlement source of truth. When a leaking discount exists, split that total
	 * into cash + discount for display without changing the net credit.
	 */
	private function _resolve_payment_credits($payment) {
		$total = isset($payment['total']) ? floatval($payment['total']) : 0;
		$pay_amount = isset($payment['pay_amount']) ? floatval($payment['pay_amount']) : 0;
		$grand_total = isset($payment['grand_total']) ? floatval($payment['grand_total']) : 0;
		$leaking_amount = isset($payment['leaking_amount']) ? floatval($payment['leaking_amount']) : 0;
		$leaking_payment = isset($payment['leaking_payment']) ? floatval($payment['leaking_payment']) : 0;

		if ($total <= 0 && $pay_amount > 0) {
			$total = $pay_amount;
		}

		$cash_credit = $total;
		$discount_credit = 0;

		if ($leaking_amount > 0.009) {
			$discount_credit = $leaking_amount;
			if ($total > 0) {
				// Keep net credit == payment.total (historical SOA behavior)
				$cash_credit = max(0, $total - $leaking_amount);
			} elseif ($grand_total > 0) {
				$cash_credit = ($pay_amount > 0) ? min($pay_amount, $grand_total) : $grand_total;
			} else {
				$cash_credit = max(0, $pay_amount);
			}
		} elseif ($leaking_payment > 0.009 && $grand_total > ($total + 0.009)) {
			// Paying open leaking A/R together with the current bill (bundled on one OR)
			$cash_credit = ($pay_amount > 0) ? min($pay_amount, $grand_total) : $grand_total;
		}

		return array(
			'cash_credit' => $cash_credit,
			'discount_credit' => $discount_credit,
			'leaking_amount' => $leaking_amount,
			'leaking_payment' => $leaking_payment,
		);
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
			$arrears_amount = isset($reading['arrears']) ? floatval($reading['arrears']) : 0;

			$billing_description = 'Billing - '.$reading['month_name'].' '.$reading['year'];
			if ($arrears_amount > 0.009) {
				$billing_description .= ' (includes arrears note: PHP '.number_format($arrears_amount, 2).')';
			}
			
			$ledger_entries[] = array(
				'date' => $entry_date,
				'type' => 'billing',
				'refno' => isset($reading['refno']) ? $reading['refno'] : '',
				'description' => $billing_description,
				'debit' => $debit_amount,
				'credit' => 0,
				'reading' => isset($reading['reading']) ? $reading['reading'] : '',
				'previous_reading' => isset($reading['previous_reading']) ? $reading['previous_reading'] : '',
				'consumed' => isset($reading['consumed']) ? $reading['consumed'] : '',
				'unit_price' => isset($reading['unit_price']) ? $reading['unit_price'] : '',
				'penalty' => $penalty_amount,
				'penalty_included' => ($use_penalty_amount && $penalty_amount > 0) ? true : false,
				'sc_discount' => isset($reading['sc_discount']) ? $reading['sc_discount'] : '',
				'arrears' => $arrears_amount,
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
				$entry_date = $this->_normalize_ledger_date(
					isset($payment['date']) ? $payment['date'] : '',
					isset($payment['year']) ? $payment['year'] : null,
					isset($payment['month']) ? $payment['month'] : null
				);

				$credits = $this->_resolve_payment_credits($payment);
			
				$payment_groups[$or_number] = array(
					'date' => $entry_date,
					'refno' => $or_number,
					'credit' => $credits['cash_credit'],
					'discount_credit' => $credits['discount_credit'],
					'leaking_amount' => $credits['leaking_amount'],
					'leaking_payment' => $credits['leaking_payment'],
					'billing_periods' => array(),
					'billing_periods_data' => array(),
					'raw_data' => $payment
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
			if (!empty($group['leaking_payment']) && $group['leaking_payment'] > 0.009) {
				$payment_description .= ' [incl. leaking A/R PHP '.number_format($group['leaking_payment'], 2).']';
			}
			
			$ledger_entries[] = array(
				'date' => $group['date'],
				'type' => 'payment',
				'refno' => $group['refno'],
				'description' => $payment_description,
				'debit' => 0,
				'credit' => $group['credit'],
				'raw_data' => $group['raw_data'],
				'billing_periods_data' => $group['billing_periods_data']
			);

			// Explicit leaking discount credit (bill was reduced by approved leaking entry)
			if (!empty($group['discount_credit']) && $group['discount_credit'] > 0.009) {
				$disc_desc = 'Leaking Discount - OR# '.$group['refno'];
				if (!empty($group['billing_periods'])) {
					$disc_desc .= ' ('.implode(', ', $group['billing_periods']).')';
				}
				$disc_desc .= ' PHP '.number_format($group['discount_credit'], 2);
				$ledger_entries[] = array(
					'date' => $group['date'],
					'type' => 'leaking_discount',
					'refno' => $group['refno'],
					'description' => $disc_desc,
					'debit' => 0,
					'credit' => $group['discount_credit'],
					'raw_data' => $group['raw_data'],
					'billing_periods_data' => $group['billing_periods_data']
				);
			}
		}

		// Posted AR Adjustments (credit notes / write-offs / debit memos)
		$this->load->model('aradjustment_model');
		$ar_adjustments = $this->aradjustment_model->get_posted_for_customer($customer_id);
		if (!empty($ar_adjustments)) {
			foreach ($ar_adjustments as $adj) {
				$adj_date = !empty($adj['adj_date']) ? $adj['adj_date'] : date('Y-m-d');
				$amount = isset($adj['adj_amount']) ? floatval($adj['adj_amount']) : 0;
				if ($amount <= 0) {
					continue;
				}
				$direction = isset($adj['adj_direction']) ? strtolower($adj['adj_direction']) : 'credit';
				$type_label = $this->aradjustment_model->type_label(isset($adj['adj_type']) ? $adj['adj_type'] : '');
				$desc = 'AR Adjustment '.$adj['adj_no'].' - '.$type_label;
				if (!empty($adj['month']) && !empty($adj['year'])) {
					$desc .= ' (Period '.$adj['month'].'/'.$adj['year'].')';
				}
				if (!empty($adj['reason'])) {
					$reason_short = mb_substr(trim($adj['reason']), 0, 80);
					$desc .= ' — '.$reason_short;
				}

				$bp_data = array();
				if (!empty($adj['month']) && !empty($adj['year'])) {
					$pk = $adj['month'].'_'.$adj['year'];
					$bp_data[$pk] = array('month' => (int) $adj['month'], 'year' => (int) $adj['year']);
				}

				$ledger_entries[] = array(
					'date' => $adj_date,
					'type' => 'adjustment',
					'refno' => isset($adj['adj_no']) ? $adj['adj_no'] : '',
					'description' => $desc,
					'debit' => ($direction === 'debit') ? $amount : 0,
					'credit' => ($direction === 'credit') ? $amount : 0,
					'raw_data' => $adj,
					'billing_periods_data' => $bp_data
				);
			}
		}

		// Leaking Entry A/R collections have their own Leaking SOA.
		// Do not add them here: meter payment `total` already settles the water bill,
		// and adding these credits would double-count and inflate/deflate balance.
		
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
				// Credits before billings when same date (newest-first display)
				$credit_types = array('payment', 'leaking_discount', 'leaking_payment', 'adjustment');
				$a_credit = in_array($a['type'], $credit_types, true);
				$b_credit = in_array($b['type'], $credit_types, true);
				if($a_credit && $b['type'] == 'billing') return -1;
				if($a['type'] == 'billing' && $b_credit) return 1;
				return 0;
			}
			// Sort descending (newest first) - reverse the comparison
			return $dateB - $dateA;
		});
		
		return $ledger_entries;
	}
	
	/** Calculate running balance for ledger entries **/
	public function calculate_running_balance($ledger_entries) {
		if (empty($ledger_entries) || !is_array($ledger_entries)) {
			return $ledger_entries;
		}

		// Preserve original positions so balances map reliably after sorting
		foreach ($ledger_entries as $i => &$entry) {
			$entry['_idx'] = $i;
		}
		unset($entry);

		$entries_chronological = $ledger_entries;
		usort($entries_chronological, function($a, $b) {
			$dateA = strtotime($a['date']);
			$dateB = strtotime($b['date']);
			
			if($dateA === false) $dateA = 0;
			if($dateB === false) $dateB = 0;
			
			if ($dateA == $dateB) {
				// Billings before credits on the same date
				$a_credit = in_array($a['type'], array('payment', 'leaking_discount', 'leaking_payment', 'adjustment'), true);
				$b_credit = in_array($b['type'], array('payment', 'leaking_discount', 'leaking_payment', 'adjustment'), true);
				if($a['type'] == 'billing' && $b_credit) return -1;
				if($a_credit && $b['type'] == 'billing') return 1;
				// Stable tie-break
				return (isset($a['_idx']) ? $a['_idx'] : 0) - (isset($b['_idx']) ? $b['_idx'] : 0);
			}
			return $dateA - $dateB; // Ascending order (oldest first)
		});
		
		$balance = 0;
		$balance_by_idx = array();
		foreach ($entries_chronological as $entry) {
			$balance = $balance + floatval($entry['debit']) - floatval($entry['credit']);
			$idx = isset($entry['_idx']) ? $entry['_idx'] : null;
			if ($idx !== null) {
				$balance_by_idx[$idx] = $balance;
			}
		}
		
		foreach ($ledger_entries as $i => &$entry) {
			$entry['balance'] = isset($balance_by_idx[$i]) ? $balance_by_idx[$i] : 0;
			unset($entry['_idx']);
		}
		unset($entry);
		
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

	/**
	 * Active billing period for a zone (same idea as meter reading: bp_status = 1).
	 * If multiple rows exist, uses latest year then month.
	 */
	public function get_active_billing_period_for_zone($zone_id) {
		$zone_id = (int) $zone_id;
		if ($zone_id <= 0) {
			return null;
		}
		$this->db->select('bp_id, bp_period_month, bp_period_year');
		$this->db->from($this->table_billing_period);
		$this->db->where('bp_zone_id', $zone_id);
		$this->db->where('bp_status', 1);
		$this->db->order_by('bp_period_year', 'desc');
		$this->db->order_by('bp_period_month', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() === 0) {
			return null;
		}
		return $query->row_array();
	}

	/**
	 * True if ledger period key "month_year" matches bp_period_month / bp_period_year.
	 */
	private function _ledger_period_key_matches($key, $period_month, $period_year) {
		if ($key === '' || $key === null) {
			return false;
		}
		$parts = explode('_', (string) $key, 2);
		if (count($parts) !== 2) {
			return false;
		}
		return (int) $parts[0] === (int) $period_month && (int) $parts[1] === (int) $period_year;
	}

	/**
	 * Statement-of-account balance but omitting the customer's current (active) billing period:
	 * - Drops billing lines for that period (month/year of active bp for customer's zone).
	 * - Drops payment lines that apply only to that period (single OR line item for that month/year).
	 * If no active period is found for the zone, returns full get_current_balance().
	 */
	public function get_current_balance_excluding_active_billing_period($customer_id = '') {
		if ($customer_id === '') {
			return 0;
		}
		$this->db->select('zone');
		$this->db->from($this->table_customer);
		$this->db->where('customer_id', $customer_id);
		$cq = $this->db->get();
		if ($cq->num_rows() === 0) {
			return 0;
		}
		$zone_id = (int) $cq->row()->zone;
		$bp = $this->get_active_billing_period_for_zone($zone_id);
		if ($bp === null) {
			return $this->get_current_balance($customer_id);
		}
		$cur_m = (int) $bp['bp_period_month'];
		$cur_y = (int) $bp['bp_period_year'];

		$ledger_entries = $this->get_customer_ledger($customer_id);
		$total_debit = 0;
		$total_credit = 0;

		foreach ($ledger_entries as $entry) {
			$type = isset($entry['type']) ? $entry['type'] : '';
			if ($type === 'billing') {
				$raw = isset($entry['raw_data']) && is_array($entry['raw_data']) ? $entry['raw_data'] : array();
				$m = isset($raw['month']) ? (int) $raw['month'] : 0;
				$y = isset($raw['year']) ? (int) $raw['year'] : 0;
				if ($m === $cur_m && $y === $cur_y) {
					continue;
				}
			} elseif ($type === 'payment' || $type === 'leaking_discount' || $type === 'adjustment') {
				$bp_data = isset($entry['billing_periods_data']) && is_array($entry['billing_periods_data']) ? $entry['billing_periods_data'] : array();
				if (count($bp_data) === 1) {
					$only_key = key($bp_data);
					if ($this->_ledger_period_key_matches($only_key, $cur_m, $cur_y)) {
						continue;
					}
				}
			} elseif ($type === 'leaking_payment') {
				// Leaking A/R collections are always part of outstanding balance calc
			}
			$total_debit += isset($entry['debit']) ? floatval($entry['debit']) : 0;
			$total_credit += isset($entry['credit']) ? floatval($entry['credit']) : 0;
		}

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

