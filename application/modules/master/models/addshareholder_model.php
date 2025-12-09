<?php 
class addshareholder_model extends CI_Model {
	public $table_name = 'tbl_addshareholder';
    public $table_customername = 'tbl_addcustomer';
	public $table_amount = 'tbl_amountrate';
	public $table_customers = 'tbl_addcustomer';
	public $table_address = 'tbl_web_settings';
	public $table_profitloss = 'tbl_addprofitloss';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
	/** In Function Get all records from select table **/
    
	 public function get_all_records() {
        
        $this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }
	 
	 public function get_amountrate() {
        $this->db->select("*");
		$this->db->from($this->table_amount);
		$this->db->order_by('id','desc');
		$this->db->where('id','1');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->row_array();
		return $result;
    }	
	
	 public function get_address() {
        $this->db->select("*");
		$this->db->from($this->table_address);
		$this->db->where('id','1');
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }		
	
	public function get_addcustomer() {
        $this->db->select("*");
		$this->db->from($this->table_customername);
		$this->db->order_by('id','desc');
		$this->db->where("customer_type",'metercustomer');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }	
	public function select_getoldmeter($id) {
		
		$sql = "SELECT ac.id as addcustomer_id, ac.customer_id, ac.mobile1, ac.mobile2, ac.email_id, am.id as addmetercustomer_id,
		        am.customer_id, am.oldmeter, am.aftermeter, am.consumedunits, am.per_unit, am.amount, am.balance, am.pay_amount, 
				am.total, am.status as addmetercustomer_status, am.date 
		        FROM `tbl_addmetercustomer` am 
				LEFT JOIN `tbl_addcustomer` ac ON ac.customer_id = am.customer_id
				WHERE ac.customer_id = '$id' OR ac.mobile1 = '$id' OR ac.mobile2 = '$id' OR ac.email_id = '$id'
				ORDER BY addmetercustomer_id DESC LIMIT 1
				";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
		
		
    }	
	
	public function get_addcustomer_add_all_records($id){
		$sql = "SELECT ac.id as addcustomer_id, ac.customer_id,ac.first_name, ac.middle_name, ac.last_name, ac.mobile1, ac.mobile2, 
		        ac.email_id,ac.address, am.id as addmetercustomer_id,
		        am.customer_id, am.oldmeter, am.aftermeter, am.consumedunits, am.per_unit, am.amount, am.balance, am.pay_amount, 
				am.total, am.status as addmetercustomer_status, am.date 
		        FROM `tbl_addmetercustomer` am 
				LEFT JOIN `tbl_addcustomer` ac ON ac.customer_id = am.customer_id
				WHERE ac.customer_id = '$id' OR ac.mobile1 = '$id' OR ac.mobile2 = '$id' OR ac.email_id = '$id'
				ORDER BY addmetercustomer_id DESC
				";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	
	public function select_getoldmeter_count($id) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$this->db->where("customer_id",$id);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->num_rows();
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
	
	public function get_dollar_value() {
        $this->db->select("*");
		$this->db->from($this->table_amount);
		$this->db->where('id',1);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }
	
	public function get_lastdata($id) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$this->db->where("customer_id",$id);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }	
	
  	/** In Function Add records for select table **/
	public function add_record(){
		
		$set_data = array(
						'firstname' => $this->input->post('firstname'),
						'middlename' => $this->input->post('middlename'),
						'lastname' => $this->input->post('lastname'),
						'gender' => $this->input->post('gender'),
						'dob' => $this->input->post('dob'),
						'placeofbirth' => $this->input->post('place_of_birth'),
						'address' => $this->input->post('address'),
					    'phone_number' => $this->input->post('mobile1'),
					    'email' => $this->input->post('email_id'),
						'amounttoshare' => $this->input->post('amountofshare'),
						'comission' => $this->input->post('comission'),
						'date' => date('d-m-Y'),
						'datetime' => date('d-m-Y H:i:s'),
						'status' => 1
					);
		$result = $this->db->insert($this->table_name, $set_data); 
		return $result;
	}
  	/** In Function Update records for select table **/
	public function update_record($id){
		
		$ids = $this->input->post('name');
		$getData=$this->my_model->select_getoldmeter($ids);
		//echo'<pre>';print_r($getData);exit;
		if(!empty($getData)){
			if($getData[0]['status']=='1'){
		$balance = 0;
			}else{
			$balance = $getData[0]['total'];
            
			}
			
		}if(empty($getData)){
		
			$balance = 0;
		}	
    
		$set_data = array(
						'firstname' => $this->input->post('firstname'),
						'middlename' => $this->input->post('middlename'),
						'lastname' => $this->input->post('lastname'),
						'gender' => $this->input->post('gender'),
						'dob' => $this->input->post('dob'),
						'placeofbirth' => $this->input->post('place_of_birth'),
						'address' => $this->input->post('address'),
					    'phone_number' => $this->input->post('mobile1'),
					    'email' => $this->input->post('email_id'),
						'amounttoshare' => $this->input->post('amountofshare'),
						'comission' => $this->input->post('comission')
					);
				//echo'<pre>';print_r($set_data);exit;	
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	
	public function update_balance($id,$amount){

		$set_data = array(

					    'balance' => $amount,

						
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
	public function status_record($id,$status,$customer_id){
		$sts = ($status == 1 ? 0 : 1);
		$set_data = array(
						'status' => $sts,
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	
	public function get_name($id) {
        //$this->db->select("first_name,middle_name,last_name");
		$this->db->select('*');
		$this->db->from($this->table_customername);
		//$this->db->where('customer_id',$id);
		$this->db->or_where('customer_id',$id);
		$this->db->or_where('mobile1',$id);
		$this->db->or_where('mobile2',$id);
		$this->db->or_where('email_id',$id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }
	public function get_shareholder(){
		$this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function add_profitloss(){
		$set_data = array(
						'share_holder' => $this->input->post('share_holder'),
						'fromdate' => $this->input->post('from-date'),
						'todate' => $this->input->post('to-date'),
						'comission' => $this->input->post('comissin'),
						'amount_earn' => $this->input->post('amount_earn'),
						'datetime' => date('d-m-Y H:i:s'),
					);
		$result = $this->db->insert($this->table_profitloss, $set_data); 
		return $result;
		
	}
	public function get_profit_share(){
		$sql = "SELECT p.id, p.share_holder, p.fromdate, p.todate, p.comission, p.amount_earn, p.datetime,
		        s.id, s.firstname, s.middlename, s.lastname
		        FROM `tbl_addprofitloss` p 
				LEFT JOIN `tbl_addshareholder` s ON p.share_holder = s.id
				ORDER BY p.datetime";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	public function get_addshareholder_records($fromdate,$todate){ 
        /*$this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where('date >=', $fromdate);
        $this->db->where('date <=', $todate);	
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;		
		*/
		$sql = "SELECT * FROM `tbl_addshareholder` WHERE date BETWEEN '$fromdate' AND '$todate' ";
		$query = $this->db->query($sql);
		$result = $query->result_array();
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