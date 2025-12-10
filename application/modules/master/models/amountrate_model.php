<?php 
class amountrate_model extends CI_Model {
	public $table_name = 'tbl_amountrate';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_customer = 'tbl_addcustomer';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
	/** In Function Get all records from select table **/
    public function get_all_records() {
        $this->db->select("tbl_amountrate.*,tbl_classification.*");
		$this->db->from($this->table_name);
		$this->db->join('tbl_classification', 'tbl_amountrate.classification_id = tbl_classification.class_id');
		$this->db->order_by('cubic_meter','asc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	
 	/** In Function Get single records for edit view purpose from select table **/
    public function get_single_record($id='') {
        $this->db->select("tbl_amountrate.*,tbl_classification.*");
		$this->db->from($this->table_name);
		$this->db->join('tbl_classification', 'tbl_amountrate.classification_id = tbl_classification.class_id');
		if($id != ''){
			$this->db->where("tbl_amountrate.id",$id);
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
		
	
		$set_data = array(
		                'classification_id' => $this->input->post('classification'),
						'cubic_meter' => $this->input->post('cubic_meter'),   
						'per_unit' => $this->input->post('amountrate'),
						'status' => $this->input->post('status'),
					  'create_date_time' => date('Y-m-d H:i:s'),
						
					);
		$result = $this->db->insert($this->table_name, $set_data); 
		return $result;
	}
  	/** In Function Update records for select table **/
	public function update_record($id){
		
		$set_data = array(
		                  
						'per_unit' => $this->input->post('amountrate'),
						'status' => $this->input->post('status'),
					  'create_date_time' => date('Y-m-d H:i:s'),
						
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	
  	/** In Function Delete records for select table **/
	public function delete_record($id){
		$this->db->where('id',$id);
		$result = $this->db->delete($this->table_name); 
		return $result;
	}
	
  	/** In Function Status Update records for select table **/
	public function status_record($id,$status){
		$sts = ($status == 1 ? 0 : 1);
		$set_data = array(
						'status' => $sts
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	public function get_income_metercustomer(){
		$this->db->select('SUM(pay_amount) as total1');
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
	
	/** Server-side pagination: Get paginated records with filtering **/
	public function get_paginated_records($start = 0, $length = 10, $search = '', $order_column = 'cubic_meter', $order_dir = 'asc', $classification_id = '') {
		$this->db->select("tbl_amountrate.*,tbl_classification.*");
		$this->db->from($this->table_name);
		$this->db->join('tbl_classification', 'tbl_amountrate.classification_id = tbl_classification.class_id');
		
		// Apply classification filter
		if($classification_id != '' && $classification_id != '0') {
			$this->db->where('tbl_amountrate.classification_id', $classification_id);
		}
		
		// Apply search filter
		if($search != '') {
			$this->db->group_start();
			$this->db->like('tbl_classification.class_name', $search);
			$this->db->or_like('tbl_amountrate.cubic_meter', $search);
			$this->db->or_like('tbl_amountrate.per_unit', $search);
			$this->db->group_end();
		}
		
		// Order by
		$this->db->order_by($order_column, $order_dir);
		
		// Limit and offset
		$this->db->limit($length, $start);
		
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	/** Server-side pagination: Get total count with filtering **/
	public function get_total_count($search = '', $classification_id = '') {
		$this->db->select("COUNT(tbl_amountrate.id) as total");
		$this->db->from($this->table_name);
		$this->db->join('tbl_classification', 'tbl_amountrate.classification_id = tbl_classification.class_id');
		
		// Apply classification filter
		if($classification_id != '' && $classification_id != '0') {
			$this->db->where('tbl_amountrate.classification_id', $classification_id);
		}
		
		// Apply search filter
		if($search != '') {
			$this->db->group_start();
			$this->db->like('tbl_classification.class_name', $search);
			$this->db->or_like('tbl_amountrate.cubic_meter', $search);
			$this->db->or_like('tbl_amountrate.per_unit', $search);
			$this->db->group_end();
		}
		
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['total'];
	}
	
	/** Batch insert/update records based on range (from import_data.php logic) **/
	public function batch_add_records($classification_id, $start, $end, $rate, $incre = '') {
		$results = array(
			'success' => 0,
			'updated' => 0,
			'inserted' => 0,
			'errors' => array()
		);
		
		$current_rate = floatval($rate);
		
		for ($i = $start; $i <= $end; $i++) {
			// Apply incremental rate if provided (same logic as import_data.php)
			if($incre != '' && $incre != '0') {
				$current_rate += floatval($incre);
			}
			
			// Check if record exists
			$this->db->select('id');
			$this->db->from($this->table_name);
			$this->db->where('classification_id', $classification_id);
			$this->db->where('cubic_meter', $i);
			$query = $this->db->get();
			
			if($query->num_rows() > 0) {
				// Update existing record
				$record = $query->row_array();
				$update_data = array(
					'per_unit' => $current_rate,
					'status' => 1,
					'create_date_time' => date('Y-m-d H:i:s')
				);
				$this->db->where('id', $record['id']);
				if($this->db->update($this->table_name, $update_data)) {
					$results['updated']++;
					$results['success']++;
				} else {
					$results['errors'][] = "Error updating record for cubic_meter: $i";
				}
			} else {
				// Insert new record
				$insert_data = array(
					'classification_id' => $classification_id,
					'cubic_meter' => $i,
					'per_unit' => $current_rate,
					'status' => 1,
					'create_date_time' => date('Y-m-d H:i:s')
				);
				if($this->db->insert($this->table_name, $insert_data)) {
					$results['inserted']++;
					$results['success']++;
				} else {
					$results['errors'][] = "Error inserting record for cubic_meter: $i";
				}
			}
		}
		
		return $results;
	}
	
}


?>