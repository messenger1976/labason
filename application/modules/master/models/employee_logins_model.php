<?php 
class Employee_logins_model extends CI_Model {
	public $table_name = 'tbl_responsibilities_user';	
	public $roles_table_name = 'tbl_responsibilities';
	public $admin_table_name='tbl_admin_login_users';
	public $admin_details = 'tbl_admin_details';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_customer = 'tbl_addcustomer';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
	/**Get all records from select table **/
    public function get_all_records() {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }	    
	public function get_all_active_records() {        
		$this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where('status','1');
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;   
	}
	public function get_all_deactive_records() {
		$this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where('status','0');
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;    
	}			
	/**Get all records from roles table **/
    public function get_all_role_records() {
        $this->db->select("role_name,id");
		$this->db->from($this->roles_table_name);
		$this->db->order_by('id','asc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	
	
 	/** Get single records for edit view purpose from tbl_contact table **/
    public function get_single_record($id) {
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
	
	public function add_record(){
		$roles_values = explode('**',$this->input->post('roles'));
		$role_id = $roles_values[0];
		$role_name  = $roles_values[1];
		$encryt_password	= md5($this->input->post('password'));	
		$record_data = array(
			'employee_name' 	=> $this->input->post('employee_name'),
			'username' 			=>$this->input->post('username'),
			'password' 			=> $encryt_password,
			'role_id' 			=> $role_id,
			'role_name' 		=> $role_name,
			'mobile' 			=> $this->input->post('mobile'),
			'status'			=> 1,
			'created_date_time' => date('y-m-d H:i:s')
		);
		$result = $this->db->insert($this->table_name, $record_data); 
		return $result; 
	}
	
	public function update_record($id){
		$roles_values = explode('**',$this->input->post('roles'));
		$role_id = $roles_values[0];
		$role_name  = $roles_values[1];
			//echo'<pre>';print_r($roles_values);exit;
		if($this->input->post('password')!=''){
			$encryt_password	= md5($this->input->post('password'));	
		}else{
			$info=$this->get_single_record($id);
			$encryt_password=$info['password'];  
		}
		$set_data = array(
			'employee_name' 	=> $this->input->post('emp_name'),
			'username'			=> $this->input->post('username'),
			'password' 			=> $encryt_password,
			'mobile' 			=> $this->input->post('mobile'), 
			'role_id' 			=> $role_id,
			'role_name' 		=> $role_name,
		);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}

  	/**Delete records for select table **/
	public function delete_record($id){
		$this->db->where('id',$id);
		$result = $this->db->delete($this->table_name); 
		return $result;
	}
	//admin login confirmation 	
	public function getadminuserdetails(){
			$this->db->select("*");
			$this->db->from($this->admin_table_name);
			$this->db->where('id',$this->session->userdata('login_history_id'));
			$query = $this->db->get();
			//echo $this->db->last_query();
			$result = $query->row_array();
			return $result;
	}
	
	
	public function status_record($id,$status){
		$sts = ($status == 1 ? 0 : 1);
		$set_data = array(
						'status' => $sts
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	
	
	
	public function firststatus_record($id,$status){
		$sts = ($status == 1 ? 0 : 1);
		$set_data = array(
						'firsttime_login' => $sts
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	
	public function exit_details($exit_data) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where('username',$exit_data);
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
    }	
	
	public function exit_id1($email,$id) {
		$this->db->select('id');
		$this->db->from($this->admin_details);
		$this->db->where('email',$email);
		$query = $this->db->get();
		//echo $this->db->last_query();
		if($query->num_rows() == 0){
			$this->db->select("id");
			$this->db->from($this->table_name);
			$this->db->where('username',$email);
			if($id != ''){
				$this->db->where("id != ",$id);
			}
			$query = $this->db->get();
			$result = $query->num_rows();
		}else{
            $result = $query->num_rows();
		}

		return $result;
    }	
	
	public function exit_id($exit_data,$local_id) {
		$this->db->select("id");
		$this->db->from($this->table_name);
		$this->db->where($exit_data);
		if ($local_id != '') {
			$this->db->where('id !=', $local_id);
		}
		$query = $this->db->get();
		return $query->num_rows();
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
	
	
}
?>