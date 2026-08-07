<?php
class aradjustment_model extends CI_Model {

	public $table_name = 'tbl_ar_adjustment';
	public $table_customer = 'tbl_addcustomer';
	public $table_ledger = 'tbl_ledgers';
	public $table_transactions = 'tbl_transactions';
	public $table_months = 'tbl_months';
	public $table_doc_series = 'tbl_doc_series_number';

	const STATUS_DRAFT = 1;
	const STATUS_POSTED = 2;
	const STATUS_VOID = 3;

	public function __construct() {
		parent::__construct();
		ini_set('date.timezone', 'Asia/Manila');
	}

	public function get_all_records($limit = 200) {
		$this->db->select($this->table_name.'.*, CONCAT('.$this->table_customer.'.last_name, \', \', '.$this->table_customer.'.first_name) AS customer_name', false);
		$this->db->from($this->table_name);
		$this->db->join($this->table_customer, $this->table_customer.'.customer_id = '.$this->table_name.'.customer_id', 'left');
		$this->db->order_by($this->table_name.'.adj_id', 'desc');
		$this->db->limit((int) $limit);
		$query = $this->db->get();
		return ($query === false) ? array() : $query->result_array();
	}

	public function get_single_record($id) {
		$this->db->select($this->table_name.'.*, CONCAT('.$this->table_customer.'.last_name, \', \', '.$this->table_customer.'.first_name, \' \', '.$this->table_customer.'.middle_name) AS customer_name', false);
		$this->db->from($this->table_name);
		$this->db->join($this->table_customer, $this->table_customer.'.customer_id = '.$this->table_name.'.customer_id', 'left');
		$this->db->where($this->table_name.'.adj_id', (int) $id);
		$query = $this->db->get();
		if ($query === false || $query->num_rows() === 0) {
			return array();
		}
		return $query->row_array();
	}

	public function get_posted_for_customer($customer_id) {
		if ($customer_id === '' || $customer_id === null) {
			return array();
		}
		$this->db->from($this->table_name);
		$this->db->where('customer_id', $customer_id);
		$this->db->where('status', self::STATUS_POSTED);
		$this->db->order_by('adj_date', 'asc');
		$this->db->order_by('adj_id', 'asc');
		$query = $this->db->get();
		return ($query === false) ? array() : $query->result_array();
	}

	public function fetch_ledgers() {
		$this->db->select('id, ledgerName');
		$this->db->from($this->table_ledger);
		$this->db->order_by('ledgerName', 'asc');
		$query = $this->db->get();
		return ($query === false) ? array() : $query->result_array();
	}

	/**
	 * Resolve ledger id by exact name (case-insensitive).
	 */
	public function ledger_id_by_name($name) {
		$name = trim((string) $name);
		if ($name === '') {
			return 0;
		}
		$this->db->select('id');
		$this->db->from($this->table_ledger);
		$this->db->where('ledgerName', $name);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query === false || $query->num_rows() === 0) {
			return 0;
		}
		$row = $query->row_array();
		return isset($row['id']) ? (int) $row['id'] : 0;
	}

	/**
	 * Recommended Dr/Cr ledger pairs for AR Adjustment types (standard water utility COA).
	 * Keys: credit_note | write_off | billing_correction | debit_memo
	 */
	public function recommended_gl_map() {
		$ar = $this->ledger_id_by_name('Accounts Receivable - Water Customers');
		$sales_returns = $this->ledger_id_by_name('Sales Returns and Allowances - Billing Credits');
		$bad_debt = $this->ledger_id_by_name('Bad Debts Expense - AR Write-off');
		$adj_income = $this->ledger_id_by_name('Billing Adjustment Income');
		$water_sales = $this->ledger_id_by_name('Water Sales - Metered Billing');

		// Debit memo prefers Billing Adjustment Income; fall back to Water Sales
		$debit_cr = $adj_income > 0 ? $adj_income : $water_sales;

		return array(
			'credit_note' => array(
				'dr' => $sales_returns,
				'cr' => $ar,
				'hint' => 'Dr Sales Returns & Allowances · Cr Accounts Receivable (reduce SOA + sales)',
			),
			'billing_correction' => array(
				'dr' => $sales_returns,
				'cr' => $ar,
				'hint' => 'Dr Sales Returns & Allowances · Cr Accounts Receivable (billing error / shortfall correction)',
			),
			'write_off' => array(
				'dr' => $bad_debt,
				'cr' => $ar,
				'hint' => 'Dr Bad Debts Expense · Cr Accounts Receivable (uncollectible write-off)',
			),
			'debit_memo' => array(
				'dr' => $ar,
				'cr' => $debit_cr,
				'hint' => 'Dr Accounts Receivable · Cr Billing Adjustment Income (increase SOA)',
			),
		);
	}

	public function fetch_months() {
		$this->db->select('month_id, month_name');
		$this->db->from($this->table_months);
		$this->db->order_by('month_id', 'asc');
		$query = $this->db->get();
		return ($query === false) ? array() : $query->result_array();
	}

	public function search_customers($q, $limit = 25) {
		$q = trim((string) $q);
		$this->db->select('customer_id, first_name, middle_name, last_name, meter_number');
		$this->db->from($this->table_customer);
		if ($q !== '') {
			// CI 2 Active Record has no group_start()/group_end()
			$esc = $this->db->escape_like_str($q);
			$this->db->where("(
				customer_id LIKE '%".$esc."%' OR
				last_name LIKE '%".$esc."%' OR
				first_name LIKE '%".$esc."%' OR
				middle_name LIKE '%".$esc."%' OR
				meter_number LIKE '%".$esc."%'
			)", NULL, FALSE);
		}
		$this->db->order_by('last_name', 'asc');
		$this->db->limit((int) $limit);
		$query = $this->db->get();
		return ($query === false) ? array() : $query->result_array();
	}

	public function customer_exists($customer_id) {
		$this->db->from($this->table_customer);
		$this->db->where('customer_id', $customer_id);
		return $this->db->count_all_results() > 0;
	}

	public function direction_for_type($adj_type) {
		$adj_type = strtolower(trim((string) $adj_type));
		if ($adj_type === 'debit_memo') {
			return 'debit';
		}
		// credit_note, write_off, billing_correction
		return 'credit';
	}

	public function type_label($adj_type) {
		$map = array(
			'credit_note' => 'Credit Note',
			'debit_memo' => 'Debit Memo',
			'write_off' => 'Write-off',
			'billing_correction' => 'Billing Correction',
		);
		$adj_type = strtolower(trim((string) $adj_type));
		return isset($map[$adj_type]) ? $map[$adj_type] : $adj_type;
	}

	public function status_label($status) {
		$status = (int) $status;
		if ($status === self::STATUS_POSTED) {
			return 'Posted';
		}
		if ($status === self::STATUS_VOID) {
			return 'Void';
		}
		return 'Draft';
	}

	private function _next_adj_no() {
		$this->db->trans_start();
		$q = $this->db->query(
			'SELECT doc_id, doc_series_num FROM '.$this->table_doc_series.' WHERE doc_name = ? FOR UPDATE',
			array('AR_ADJ')
		);
		if ($q === false || $q->num_rows() === 0) {
			$this->db->insert($this->table_doc_series, array(
				'doc_name' => 'AR_ADJ',
				'doc_series_num' => 0,
			));
			$num = 1;
			$this->db->where('doc_name', 'AR_ADJ');
			$this->db->update($this->table_doc_series, array('doc_series_num' => 1));
		} else {
			$row = $q->row();
			$num = ((int) $row->doc_series_num) + 1;
			$this->db->where('doc_id', (int) $row->doc_id);
			$this->db->update($this->table_doc_series, array('doc_series_num' => $num));
		}
		$this->db->trans_complete();
		return 'ADJ-'.sprintf('%05d', $num);
	}

	private function _posted_input() {
		$adj_type = strtolower(trim((string) $this->input->post('adj_type')));
		$allowed = array('credit_note', 'debit_memo', 'write_off', 'billing_correction');
		if (!in_array($adj_type, $allowed, true)) {
			$adj_type = 'credit_note';
		}
		$amount = (float) str_replace(',', '', (string) $this->input->post('adj_amount'));
		$date_raw = $this->input->post('adj_date');
		$adj_date = date('Y-m-d', strtotime($date_raw ? $date_raw : 'now'));
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		return array(
			'customer_id' => trim((string) $this->input->post('customer_id')),
			'adj_type' => $adj_type,
			'adj_direction' => $this->direction_for_type($adj_type),
			'adj_amount' => round($amount, 2),
			'adj_date' => $adj_date,
			'month' => ($month !== '' && $month !== null) ? (int) $month : null,
			'year' => ($year !== '' && $year !== null) ? (int) $year : null,
			'reading_refno' => trim((string) $this->input->post('reading_refno')),
			'reason' => trim((string) $this->input->post('reason')),
			'remarks' => trim((string) $this->input->post('remarks')),
			'dr_ledger_id' => (int) $this->input->post('dr_ledger_id'),
			'cr_ledger_id' => (int) $this->input->post('cr_ledger_id'),
		);
	}

	public function validate_draft_input($data) {
		if ($data['customer_id'] === '' || !$this->customer_exists($data['customer_id'])) {
			return 'Valid Customer ID is required.';
		}
		if ($data['adj_amount'] <= 0) {
			return 'Amount must be greater than zero.';
		}
		if ($data['reason'] === '') {
			return 'Reason is required.';
		}
		if ((int) $data['dr_ledger_id'] <= 0 || (int) $data['cr_ledger_id'] <= 0) {
			return 'Debit and Credit GL ledgers are required.';
		}
		if ((int) $data['dr_ledger_id'] === (int) $data['cr_ledger_id']) {
			return 'Debit and Credit ledgers must be different.';
		}
		return '';
	}

	public function add_draft() {
		$data = $this->_posted_input();
		$err = $this->validate_draft_input($data);
		if ($err !== '') {
			return array('ok' => false, 'message' => $err);
		}

		$now = date('Y-m-d H:i:s');
		$adj_no = $this->_next_adj_no();
		$row = array_merge($data, array(
			'adj_no' => $adj_no,
			'status' => self::STATUS_DRAFT,
			'created_by' => (int) $this->session->userdata('userid'),
			'created_by_name' => (string) $this->session->userdata('username'),
			'create_date_time' => $now,
			'update_date_time' => $now,
		));
		$ok = $this->db->insert($this->table_name, $row);
		if (!$ok) {
			return array('ok' => false, 'message' => 'Failed to save draft.');
		}
		return array('ok' => true, 'adj_id' => (int) $this->db->insert_id(), 'adj_no' => $adj_no);
	}

	public function update_draft($id) {
		$existing = $this->get_single_record($id);
		if (empty($existing) || (int) $existing['status'] !== self::STATUS_DRAFT) {
			return array('ok' => false, 'message' => 'Only draft adjustments can be edited.');
		}
		$data = $this->_posted_input();
		$err = $this->validate_draft_input($data);
		if ($err !== '') {
			return array('ok' => false, 'message' => $err);
		}
		$data['update_date_time'] = date('Y-m-d H:i:s');
		$this->db->where('adj_id', (int) $id);
		$this->db->where('status', self::STATUS_DRAFT);
		$ok = $this->db->update($this->table_name, $data);
		return $ok
			? array('ok' => true)
			: array('ok' => false, 'message' => 'Update failed.');
	}

	public function post_adjustment($id) {
		$existing = $this->get_single_record($id);
		if (empty($existing) || (int) $existing['status'] !== self::STATUS_DRAFT) {
			return array('ok' => false, 'message' => 'Only draft adjustments can be posted.');
		}
		if ((float) $existing['adj_amount'] <= 0) {
			return array('ok' => false, 'message' => 'Invalid amount.');
		}
		if ((int) $existing['dr_ledger_id'] <= 0 || (int) $existing['cr_ledger_id'] <= 0) {
			return array('ok' => false, 'message' => 'Debit and Credit ledgers are required before posting.');
		}

		$now = date('Y-m-d H:i:s');
		$userid = (int) $this->session->userdata('userid');
		$username = (string) $this->session->userdata('username');
		$amount = round((float) $existing['adj_amount'], 2);
		$voucher = $existing['adj_no'];
		$tx_date = $existing['adj_date'];

		$this->db->trans_begin();

		$dr = array(
			'tableName' => 'ar_adjustment',
			'voucherNo' => $voucher,
			'transaction_id' => (int) $existing['adj_id'],
			'ledger_id' => (int) $existing['dr_ledger_id'],
			'ledger_id_for' => 'ledger_id',
			'debit' => $amount,
			'credit' => '',
			'date' => $tx_date,
			'create_date_time' => $now,
			'update_date_time' => $now,
		);
		$cr = array(
			'tableName' => 'ar_adjustment',
			'voucherNo' => $voucher,
			'transaction_id' => (int) $existing['adj_id'],
			'ledger_id' => (int) $existing['cr_ledger_id'],
			'ledger_id_for' => 'ledger_id',
			'debit' => '',
			'credit' => $amount,
			'date' => $tx_date,
			'create_date_time' => $now,
			'update_date_time' => $now,
		);
		$this->db->insert($this->table_transactions, $dr);
		$this->db->insert($this->table_transactions, $cr);

		$this->db->where('adj_id', (int) $id);
		$this->db->where('status', self::STATUS_DRAFT);
		$this->db->update($this->table_name, array(
			'status' => self::STATUS_POSTED,
			'posted_by' => $userid,
			'posted_by_name' => $username,
			'posted_date_time' => $now,
			'update_date_time' => $now,
		));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return array('ok' => false, 'message' => 'Posting failed.');
		}
		$this->db->trans_commit();
		return array('ok' => true);
	}

	public function void_adjustment($id) {
		$existing = $this->get_single_record($id);
		if (empty($existing) || (int) $existing['status'] !== self::STATUS_POSTED) {
			return array('ok' => false, 'message' => 'Only posted adjustments can be voided.');
		}

		$now = date('Y-m-d H:i:s');
		$userid = (int) $this->session->userdata('userid');
		$username = (string) $this->session->userdata('username');
		$amount = round((float) $existing['adj_amount'], 2);
		$voucher = $existing['adj_no'].'-V';
		$tx_date = date('Y-m-d');

		$this->db->trans_begin();

		// Reversing GL entry (swap sides)
		$dr = array(
			'tableName' => 'ar_adjustment',
			'voucherNo' => $voucher,
			'transaction_id' => (int) $existing['adj_id'],
			'ledger_id' => (int) $existing['cr_ledger_id'],
			'ledger_id_for' => 'ledger_id',
			'debit' => $amount,
			'credit' => '',
			'date' => $tx_date,
			'create_date_time' => $now,
			'update_date_time' => $now,
		);
		$cr = array(
			'tableName' => 'ar_adjustment',
			'voucherNo' => $voucher,
			'transaction_id' => (int) $existing['adj_id'],
			'ledger_id' => (int) $existing['dr_ledger_id'],
			'ledger_id_for' => 'ledger_id',
			'debit' => '',
			'credit' => $amount,
			'date' => $tx_date,
			'create_date_time' => $now,
			'update_date_time' => $now,
		);
		$this->db->insert($this->table_transactions, $dr);
		$this->db->insert($this->table_transactions, $cr);

		$this->db->where('adj_id', (int) $id);
		$this->db->where('status', self::STATUS_POSTED);
		$this->db->update($this->table_name, array(
			'status' => self::STATUS_VOID,
			'voided_by' => $userid,
			'voided_by_name' => $username,
			'voided_date_time' => $now,
			'update_date_time' => $now,
		));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return array('ok' => false, 'message' => 'Void failed.');
		}
		$this->db->trans_commit();
		return array('ok' => true);
	}

	public function delete_draft($id) {
		$existing = $this->get_single_record($id);
		if (empty($existing) || (int) $existing['status'] !== self::STATUS_DRAFT) {
			return false;
		}
		$this->db->where('adj_id', (int) $id);
		$this->db->where('status', self::STATUS_DRAFT);
		return $this->db->delete($this->table_name);
	}
}
