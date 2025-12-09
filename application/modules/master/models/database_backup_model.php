<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Database Backup Model
 * @author		System
 * @copyright	Copyright (c) 2024
 * @version		1.0
 * @package		Water Billing System
 * @subpackage  Database Backup Model
 * */
class database_backup_model extends CI_Model {
	
	public $table_name = 'tbl_database_backups';
	
	// Autoloading a system library using constructor method
	public function __construct() {
        parent::__construct();
		date_default_timezone_set('Asia/Manila');
    }
	
	/** Get all backup records from table **/
    public function get_all_backups() {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('created_at','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }
	
 	/** Get single backup record by ID **/
    public function get_backup_by_id($id='') {
        $this->db->select("*");
		$this->db->from($this->table_name);
		if($id != ''){
			$this->db->where("id",$id);
			$query = $this->db->get();
			$result = $query->row_array();
		}
		return $result;
    }
	
  	/** Save backup record to database **/
	public function save_backup_record($data){
		$result = $this->db->insert($this->table_name, $data); 
		return $result;
	}
 
  	/** Delete backup record from database **/
	public function delete_backup($id){
		$this->db->where('id',$id);
		$result = $this->db->delete($this->table_name); 
		return $result;
	}
	
	/** Get backup count **/
	public function get_backup_count() {
		$this->db->select('COUNT(id) as count');
		$this->db->from($this->table_name);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['count'];
	}
	
	/** Get total backup size **/
	public function get_total_backup_size() {
		$this->db->select('SUM(filesize) as total_size');
		$this->db->from($this->table_name);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['total_size'] ? $result['total_size'] : 0;
	}
	
	/** Format file size **/
	public function format_file_size($bytes) {
		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} elseif ($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} elseif ($bytes > 1) {
			$bytes = $bytes . ' bytes';
		} elseif ($bytes == 1) {
			$bytes = $bytes . ' byte';
		} else {
			$bytes = '0 bytes';
		}
		return $bytes;
	}
}

?>

