<?php 
class feesplaning_model extends CI_Model {
	public $table_name = 'tbl_feesplaning';
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
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	
 	/** In Function Get single records for edit view purpose from select table **/
    public function get_single_record($id='') {
        $this->db->select("*");
		$this->db->from($this->table_name);
		if($id != ''){
			$this->db->where("id",$id);
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
						'name' => mysql_real_escape_string(ucwords($this->input->post('name'))),
						'details' => mysql_real_escape_string($this->input->post('details')),
						
						'days' => mysql_real_escape_string($this->input->post('days')),
						'amount' => mysql_real_escape_string($this->input->post('amount')),
					
						
					);
		$result = $this->db->insert($this->table_name, $set_data); 
		return $result;
	}
  	/** In Function Update records for select table **/
	public function update_record($id){
		$set_data = array(
						'name' => mysql_real_escape_string(ucwords($this->input->post('name'))),
						'details' => mysql_real_escape_string($this->input->post('details')),
						
						'days' => mysql_real_escape_string($this->input->post('days')),
						'amount' => mysql_real_escape_string($this->input->post('amount')),
					
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
	/*public function status_record($id,$status){
		$sts = ($status == 1 ? 0 : 1);
		$set_data = array(
						'status' => $sts
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}*/
	
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
}
?>