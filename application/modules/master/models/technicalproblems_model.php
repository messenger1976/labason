<?php 
class technicalproblems_model extends CI_Model {
	public $table_name = 'tbl_technical';
    public $table_message = 'tbl_technical_messages';
    public $table_customername = 'tbl_addcustomer';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	//public $table_amount = 'tbl_amountrate';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
	/** In Function Get all records from select table **/
    
	 public function get_all_records() {
        $this->db->select("*,(Select first_name from ".$this->table_customername." where ".$this->table_customername.".customer_id = ".$this->table_name.".customer_id) as names");
		$this->db->from($this->table_name);
		$this->db->where('deleted_rec',0);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	 
	 /*public function get_amountrate() {
        $this->db->select("*");
		$this->db->from($this->table_amount);
		$this->db->order_by('id','desc');
		$this->db->where('id','1');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->row_array();
		return $result;
    }	*/
	
	public function get_addcustomer() {
        $this->db->select("*");
		$this->db->from($this->table_customername);
		$this->db->order_by('id','desc');
		$this->db->where("customer_type",'monthlycustomer');
		//$this->db->where("customer_type",'metercustomer');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }	
	public function get_customer_info_details($customer_id){
 		$this->db->select("*");
		$this->db->from($this->table_customername);
		
		$this->db->where('customer_id',$customer_id);
		
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;			
	}
	/*public function get_addcustomer() {
        $this->db->select("*");
		$this->db->from($this->table_customername);
		$this->db->order_by('id','desc');
		//$this->db->where("customer_type",'monthlycustomer');
		$this->db->where("customer_type",'metercustomer');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }*/
	/*public function select_getoldmeter($id) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$this->db->where("customer_id",$id);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }	*/
	
	
	
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

	public function get_message_records($id='') {
        $this->db->select("*");
		$this->db->from($this->table_message);
		if($id != ''){
			$this->db->where("technical_id",$id);
			$query = $this->db->get();
			//echo $this->db->last_query();
			$result = $query->result_array();
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
	
	/*public function get_dollar_value() {
        $this->db->select("*");
		$this->db->from($this->table_amount);
		$this->db->where('id',1);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }*/
	
	/*public function get_lastdata($id) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$this->db->where("customer_id",$id);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }*/
	
  	/** In Function Add records for select table **/
	public function add_record($employee_rec){
		//echo'<pre>';print_r($add_record);exit;
		$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
		$created_date = $dt_date->format("Y-m-d H:i:s");

		$preparedby_name = $employee_rec[0]['first_name'].' '.$employee_rec[0]['middle_name'].' '.$employee_rec[0]['last_name'].' - '.$employee_rec[0]['jobtitle'];
	  $set_data = array(
			'customer_id' => $this->input->post('cust_id'),
			'lastname' => $this->input->post('lastname'),
			'firstname' => $this->input->post('firstname'),
			'middlename' => $this->input->post('middlename'),
			'meter_number' => $this->input->post('meter_number'),
			'address' => $this->input->post('address'),
			'problem_summary' => $this->input->post('problem_summary'),
			'problem_details' => $this->input->post('problem_details'),
			'reported_by_id' => $this->input->post('reportedby'),
			'reported_by_name' => $preparedby_name,
			'reported_date' => date('Y-m-d',strtotime($this->input->post('reported_date'))),
			'status' => $this->input->post('status'),
			'create_date_time' => $created_date,
			
		);
		$result = $this->db->insert($this->table_name, $set_data); 
		return $result;
	}

	public function add_messages_record($employee_rec){
		//echo'<pre>';print_r($add_record);exit;
		$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
		$created_date = $dt_date->format("Y-m-d H:i:s");

		//$preparedby_name = $employee_rec[0]['first_name'].' '.$employee_rec[0]['middle_name'].' '.$employee_rec[0]['last_name'].' - '.$employee_rec[0]['jobtitle'];
        
		$set_data1 = array(
			'status' => $this->input->post('msg_status'),
			'update_date_time' => $created_date,
		);
		$this->db->where('id',$this->input->post('technical_id'));
		$result1 = $this->db->update($this->table_name, $set_data1); 

	  	$set_data = array(
			'technical_id' => $this->input->post('technical_id'),
			'technical_msg_text' => $this->input->post('msg_logs'),
			'technical_msg_status' => $this->input->post('msg_status'),
			'technical_msg_reported_by_id' => $this->input->post('msg_reportedby'),
			'technical_msg_reported_name' => $this->input->post('reportedby_name'),
			'technical_msg_reported_date' => date('Y-m-d',strtotime($this->input->post('posted_date'))),
			'technical_msg_created_date' => $created_date,
			
		);
		$result = $this->db->insert($this->table_message, $set_data); 
		return $result;
	}
  	/** In Function Update records for select table **/
	public function update_record($id){
		$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
		$updated_date = $dt_date->format("Y-m-d H:i:s");

		
		$set_data = array(
			//'customer_id' => $this->input->post('customer_id'),
			//'name' => mysql_real_escape_string($this->input->post('name')),
			'problem_summary' => $this->input->post('problem_summary'),
			'problem_details' => $this->input->post('problem_details'),
			'status' => $this->input->post('status'),
			'reported_date' => date('Y-m-d',strtotime($this->input->post('reported_date'))),
			'update_date_time' => $updated_date,
			
		);
		//echo'<pre>';print_r($set_data);exit;	
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	
	
  	/** In Function Delete records for select table **/
	public function delete_record($id){
		$this->db->where('id',$id);
		$set_data = array(
						'deleted_rec' => 1
					);
		$result = $this->db->update($this->table_name,$set_data); 
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
		$this->db->from($this->table_customername);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	
}
?>