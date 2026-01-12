<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Global Settings Model
 * This model handles global settings CRUD operations
 */
class global_settings_model extends CI_Model {
	
	public $table_name = 'tbl_global_settings';
	
	// Autoloading a system library using constructor method
	public function __construct() {
        parent::__construct();
		date_default_timezone_set('Asia/Manila');
    }
	
	/** Get all records from select table **/
    public function get_all_records() {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','asc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }
	
 	/** Get single records for edit view purpose from select table **/
    public function get_single_record($id='') {
        $this->db->select("*");
		$this->db->from($this->table_name);
		if($id != ''){
			$this->db->where("id",$id);
			$query = $this->db->get();
			$result = $query->row_array();
		}
		return $result;
    }
	
	/** Get setting by code **/
    public function get_setting_by_code($code='') {
        $this->db->select("*");
		$this->db->from($this->table_name);
		if($code != ''){
			$this->db->where("code",$code);
			$query = $this->db->get();
			$result = $query->row();
		}
		return $result;
    }
	
  	/** Add Check Exits records for select table **/
	public function exit_details($exit_data) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where($exit_data);
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
    }
	
  	/** Edit Check Exits records for select table **/
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
	
  	/** Add records for select table **/
	public function add_record(){
		
		$set_data = array(
					'code' => trim(strtoupper($this->input->post('code'))),
					'description' => $this->input->post('description'),
					'value' => $this->input->post('value'),
				);
		$result = $this->db->insert($this->table_name, $set_data); 
		return $result;
	}
 
  	/** Update records for select table **/
	public function update_record($id){
		$set_data = array(
					'code' => trim(strtoupper($this->input->post('code'))),
					'description' => $this->input->post('description'),
					'value' => $this->input->post('value'),
				);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
 
  	/** Delete records for select table **/
	public function delete_record($id){
		$this->db->where('id',$id);
		$result = $this->db->delete($this->table_name); 
		return $result;
	}
}
?>
