<?php 
class addbillingperiod_model extends CI_Model {
	public $table_name = 'tbl_zone';
    public $table_months = 'tbl_months';
    public $table_billing_period = 'tbl_billing_period';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_customer = 'tbl_addcustomer';
	public $table_zone = 'tbl_zone';
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
    /** In Function Get all records from select table **/
    public function get_zone_records() {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	/** In Function Get all records from select table **/
    public function get_month_records() {
        $this->db->select("*");
		$this->db->from($this->table_months);
		$this->db->order_by('month_id','asc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
    /** In Function Get all records from select table **/
    public function get_month_billingperiod_records() {
        $this->db->distinct();
        $this->db->select("bp_period_month, bp_period_year, (Select month_name from ".$this->table_months." where ".$this->table_months.".month_id	= ".$this->table_billing_period.".bp_period_month ) as month_name");
		$this->db->from($this->table_billing_period);
		$this->db->where("bp_status <> ",2);
		$this->db->order_by('bp_period_year','desc');
		$this->db->order_by('bp_period_month','asc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	public function get_zone_listing_records() {
        $this->db->select("*");
		$this->db->from($this->table_zone);
        $this->db->order_by('order_series','asc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
 	/** In Function Get single records for edit view purpose from select table **/
    public function get_single_record($id='') {
        $this->db->select("*");
		$this->db->from($this->table_billing_period);
		if($id != ''){
			$this->db->where("bp_id",$id);
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
		$start_date = strtotime($this->input->post('start_date'));
        $end_date = strtotime($this->input->post('end_date'));
        $due_date = strtotime($this->input->post('due_date'));
        $disconnect_date = strtotime($this->input->post('disconnect_date'));

		$set_data = array(
		                   
                    'bp_zone_id' => $this->input->post('zone'),
                    'bp_period_month' => $this->input->post('billing_month'),
                    'bp_period_year' => $this->input->post('billing_year'),
                    'bp_start_date' => date('Y-m-d',$start_date),
                    'bp_end_date' => date('Y-m-d',$end_date),
                    'bp_due_date' => date('Y-m-d',$due_date),
                    'bp_disconnection_date' => date('Y-m-d',$disconnect_date),
                    'bp_status' => $this->input->post('status'),
					  'create_date_time' => date('Y-m-d H:i:s'),
						
					);
		$result = $this->db->insert($this->table_billing_period, $set_data); 
		return $result;
	}
  	/** In Function Update records for select table **/
	public function update_record($id){
		$start_date = strtotime($this->input->post('start_date'));
        $end_date = strtotime($this->input->post('end_date'));
        $due_date = strtotime($this->input->post('due_date'));
        $disconnect_date = strtotime($this->input->post('disconnect_date'));
		$set_data = array(
		                  
						'bp_period_month' => $this->input->post('billing_month'),
                    'bp_period_year' => $this->input->post('billing_year'),
                    'bp_start_date' => date('Y-m-d',$start_date),
                    'bp_end_date' => date('Y-m-d',$end_date),
                    'bp_due_date' => date('Y-m-d',$due_date),
                    'bp_disconnection_date' => date('Y-m-d',$disconnect_date),
                    'bp_status' => $this->input->post('status'),
					  'updated_date_time' => date('Y-m-d H:i:s'),
						
					);
		$this->db->where('bp_id',$id);
		$result = $this->db->update($this->table_billing_period, $set_data); 
		return $result;
	}
	
  	/** In Function Delete records for select table **/
	public function delete_record($id){
		$this->db->where('bp_id',$id);
		$result = $this->db->delete($this->table_billing_period); 
		return $result;
	}
	
	
  	/** In Function Status Update records for select table **/
	public function status_record($id,$status){
		$sts = ($status == 1 ? 0 : 1);
		$set_data = array(
						'bp_status' => $sts
					);
		$this->db->where('bp_id',$id);
		$result = $this->db->update($this->table_billing_period, $set_data); 
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
    public function get_addbillingperiod_records($zone,$billingperiod)
	{ 
        $billingperiod_array = explode(" ", $billingperiod);

		$this->db->select("*,
        (Select zone from ".$this->table_name." where ".$this->table_billing_period.".bp_zone_id	= ".$this->table_name.".id ) as zone_name,
        (Select month_name from ".$this->table_months." where ".$this->table_months.".month_id	= ".$this->table_billing_period.".bp_period_month ) as month_name"
        );
		$this->db->from($this->table_billing_period);
		
		if($zone!=''){
			$this->db->where('bp_zone_id',$zone);
		}	
		if($billingperiod !=''){
			$where = '( `bp_period_month` = "'.$billingperiod_array[0].'" AND `bp_period_year` = "'.$billingperiod_array[1].'")';
			$this->db->where($where);
		}	
			
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;		
	}
    
	public function get_monthly_billing_report_records($zone,$billingperiod,$status='')
	{ 
        $billingperiod_array = explode(" ", $billingperiod);

		$this->db->select("*,
        (Select zone from ".$this->table_name." where ".$this->table_billing_period.".bp_zone_id	= ".$this->table_name.".id ) as zone_name,
        (Select month_name from ".$this->table_months." where ".$this->table_months.".month_id	= ".$this->table_billing_period.".bp_period_month ) as month_name"
        );
		$this->db->from($this->table_billing_period);
		
		if($zone!=''){
			$this->db->where('bp_zone_id',$zone);
		}	
		if($billingperiod !=''){
			$where = '( `bp_period_month` = "'.$billingperiod_array[0].'" AND `bp_period_year` = "'.$billingperiod_array[1].'")';
			$this->db->where($where);
		}	
			
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;		
	}
}


?>