<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class system_activity_model extends CI_Model {

	public $table_name = 'tbl_system_activity';

	public function __construct() {
		parent::__construct();
	}

	public function get_by_id($id) {
		$this->db->from($this->table_name);
		$this->db->where('id', (int) $id);
		$q = $this->db->get();
		return $q->row_array();
	}

	public function search($filters = array(), $limit = 500) {
		if (!$this->db->table_exists($this->table_name)) {
			return array();
		}
		$this->db->from($this->table_name);
		$this->_apply_filters($filters);
		$this->db->order_by('id', 'DESC');
		$this->db->limit((int) $limit);
		$q = $this->db->get();
		return $q->result_array();
	}

	public function distinct_values($column) {
		$allowed = array('category', 'module', 'action');
		if (!in_array($column, $allowed, true) || !$this->db->table_exists($this->table_name)) {
			return array();
		}
		$this->db->distinct();
		$this->db->select($column);
		$this->db->from($this->table_name);
		$this->db->where($column.' !=', '');
		$this->db->order_by($column, 'ASC');
		$q = $this->db->get();
		$out = array();
		foreach ($q->result_array() as $row) {
			if (isset($row[$column]) && $row[$column] !== '') {
				$out[] = $row[$column];
			}
		}
		return $out;
	}

	public function distinct_users() {
		if (!$this->db->table_exists($this->table_name)) {
			return array();
		}
		$this->db->select('user_id, username, user_name, usertype');
		$this->db->from($this->table_name);
		$this->db->where('user_id >', 0);
		$this->db->group_by('user_id');
		$this->db->order_by('user_name', 'ASC');
		$q = $this->db->get();
		return $q->result_array();
	}

	protected function _apply_filters($filters) {
		if (!empty($filters['date_from'])) {
			$this->db->where('created_at >=', $filters['date_from'].' 00:00:00');
		}
		if (!empty($filters['date_to'])) {
			$this->db->where('created_at <=', $filters['date_to'].' 23:59:59');
		}
		if (isset($filters['user_id']) && $filters['user_id'] !== '') {
			$this->db->where('user_id', (int) $filters['user_id']);
		}
		if (!empty($filters['category'])) {
			$this->db->where('category', $filters['category']);
		}
		if (!empty($filters['module'])) {
			$this->db->where('module', $filters['module']);
		}
		if (!empty($filters['action'])) {
			$this->db->like('action', $filters['action']);
		}
		if (!empty($filters['reference_no'])) {
			$this->db->like('reference_no', $filters['reference_no']);
		}
		if (!empty($filters['ip_address'])) {
			$this->db->like('ip_address', $filters['ip_address']);
		}
		if (!empty($filters['q'])) {
			$esc = $this->db->escape_like_str($filters['q']);
			$this->db->where("(
				summary LIKE '%".$esc."%' OR
				username LIKE '%".$esc."%' OR
				user_name LIKE '%".$esc."%' OR
				reference_no LIKE '%".$esc."%' OR
				uri LIKE '%".$esc."%'
			)", NULL, FALSE);
		}
	}
}
