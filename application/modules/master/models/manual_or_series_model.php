<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class manual_or_series_model extends CI_Model {

	public $table_doc = 'tbl_doc_series_number';
	public $table_users = 'tbl_responsibilities_user';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_customer = 'tbl_addcustomer';

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

	/**
	 * OR/SI rows (doc_name = OR): legacy doc_id=1 and per-teller rows with teller_user_id.
	 */
	public function get_or_series_rows() {
		if ($this->db->field_exists('teller_user_id', $this->table_doc)) {
			$this->db->select('d.doc_id, d.doc_name, d.doc_series_num, d.teller_user_id, u.employee_name, u.username');
			$this->db->from($this->table_doc . ' d');
			$this->db->join($this->table_users . ' u', 'u.id = d.teller_user_id', 'left');
		} else {
			$this->db->select('d.doc_id, d.doc_name, d.doc_series_num');
			$this->db->from($this->table_doc . ' d');
		}
		$this->db->where('d.doc_name', 'OR');
		$this->db->order_by('d.doc_id', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * Update last-used OR counter (next printed OR will be this + 1).
	 */
	public function update_or_series_num($doc_id, $doc_series_num) {
		$doc_id = (int) $doc_id;
		$doc_series_num = (int) $doc_series_num;
		if ($doc_id <= 0 || $doc_series_num < 0) {
			return false;
		}
		$this->db->where('doc_id', $doc_id);
		$this->db->where('doc_name', 'OR');
		return $this->db->update($this->table_doc, array('doc_series_num' => $doc_series_num));
	}
}
