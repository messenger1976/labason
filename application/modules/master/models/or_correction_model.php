<?php 

class or_correction_model extends CI_Model {
	public $table_name = 'tbl_zone';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_customer = 'tbl_addcustomer';
	public $table_meter_reading = 'tbl_addcustomer_reading';
	public $table_months = 'tbl_months';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
	/** In Function Get all records from select table **/
    public function get_all_records($trans_date='') {
		
        $this->db->select("*,(Select month_name from ".$this->table_months." where ".$this->table_months.".month_id	= ".$this->table_meter.".month ) as month_name");
		$this->db->from($this->table_meter);
		if($trans_date!=''){
			$dt_date = new DateTime($trans_date, new DateTimeZone("Asia/Manila"));
			$trans_date = $dt_date->format("Y-m-d");
			$this->db->where($this->table_meter.".date",$trans_date);
		}
		$this->db->group_by('or_number');
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	
 	/** In Function Get single records for edit view purpose from select table **/
    public function get_single_record($id='') {
        $this->db->select("*");
		$this->db->from($this->table_meter);
		if($id != ''){
			$this->db->where("or_number",$id);
			$this->db->group_by("or_number");
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
		$dt_date = new DateTime('now', new DateTimeZone("Asia/Manila"));
		$trans_date = $dt_date->format("Y-m-d H:i:s");
	
		$set_data = array(
		                   
						'zone' => $this->input->post('zone'),
						'status' => $this->input->post('status'),
					  'create_date_time' => $trans_date,
						
					);
		$result = $this->db->insert($this->table_name, $set_data); 
		return $result;
	}
  	/** In Function Update records for select table **/
	public function update_record($old_or){
		$dt_date = new DateTime('now', new DateTimeZone("Asia/Manila"));
		$trans_date = $dt_date->format("Y-m-d H:i:s");
		$newdate = date('Y-m-d',strtotime($this->input->post('newdate')));
		
		// Update main meter customer record, including edited OR amount
		$set_data = array(
			'date' => $newdate,
			'or_number' => sprintf('%07d',$this->input->post('or_number')),
			'grand_total' => $this->input->post('grand_total'),
			'update_date_time' => $trans_date,
		);
		$this->db->where('or_number',$old_or);
		$result = $this->db->update($this->table_meter, $set_data); 
		
		// Update related reading records (no amount change needed here)
		$set_data_reading = array(
			'date' => $newdate,
			'or_number' => sprintf('%07d',$this->input->post('or_number')),
			'update_date_time' => $trans_date,
		);
		$this->db->where('or_number',$old_or);
		$result = $this->db->update($this->table_meter_reading, $set_data_reading); 
		
		return $result;
	}
	
  	/** In Function Delete records for select table **/
	public function delete_record($or_number){
		$this->db->where('or_number',$or_number);
		$result = $this->db->delete($this->table_meter); 

		$set_data = array(
		                  
			'status' => 0,
			'or_number' => '',
		  	'update_date_time' => date('Y-m-d H:i:s'),
			'customer_billing_id' => ''
			
		);
		$this->db->where('or_number',$or_number);
		$result = $this->db->update($this->table_meter_reading,$set_data); 
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
	
}


?>