<?php
class customerbalancemonitor_model extends CI_Model {
	public $table_name = 'tbl_addcustomer';
	public $table_zone = 'tbl_zone';
	public $table_classification = 'tbl_classification';

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Customer list with same filters as Customer Report (zone, status, special privilege).
	 */
	public function get_customers($zone, $status = '', $special_privilege = 0) {
		$this->db->select($this->table_name.'.customer_id, '.$this->table_name.'.zone, '.$this->table_name.'.first_name, '.$this->table_name.'.last_name, '.$this->table_name.'.middle_name, '.$this->table_name.'.address, '.$this->table_name.'.status,
		(SELECT zone FROM '.$this->table_zone.' WHERE '.$this->table_zone.'.id = '.$this->table_name.'.zone) as zone_name,
		(SELECT class_name FROM '.$this->table_classification.' WHERE '.$this->table_classification.'.class_id = '.$this->table_name.'.classification) as classification_name');
		$this->db->from($this->table_name);

		$zone = (int) $zone;
		if ($zone != 0) {
			$this->db->where($this->table_name.'.zone', $zone);
		}
		if ($status !== '' && $status !== null && $status !== false && $status !== '99') {
			$this->db->where($this->table_name.'.status', $status);
		}
		if ((int) $special_privilege === 1) {
			$this->db->where($this->table_name.'.special_priviledge', 1);
		}

		$this->db->order_by($this->table_name.'.last_name', 'asc');
		$this->db->order_by($this->table_name.'.first_name', 'asc');

		$query = $this->db->get();
		if ($this->db->_error_number() != 0) {
			log_message('error', 'customerbalancemonitor_model get_customers: ' . $this->db->_error_message());
			return array();
		}
		$result = $query->result_array();
		return $result ? $result : array();
	}

	/**
	 * Append balance per customer: same rules as Statement of Account, but excluding
	 * the active billing period for the customer's zone (see statementofaccount_model).
	 */
	public function attach_statement_balances($rows) {
		$this->load->model('statementofaccount_model', 'soa_model');
		$out = array();
		foreach ($rows as $row) {
			$cid = isset($row['customer_id']) ? $row['customer_id'] : '';
			if ($cid === '') {
				continue;
			}
			$row['total_balance'] = (float) $this->soa_model->get_current_balance_excluding_active_billing_period($cid);
			$out[] = $row;
		}
		return $out;
	}
}
