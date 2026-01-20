<?php 
class addcustomer_model extends CI_Model {
	public $table_name = 'tbl_addcustomer';
	public $table_billing = 'tbl_feesplaning';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_zone = 'tbl_zone';
	public $table_classification = 'tbl_classification';
	public $table_customer_type = 'tbl_customer_type';
	public $table_address = 'tbl_web_settings';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_metercustomer = 'tbl_addmetercustomer';
	public $table_customerreading = 'tbl_addcustomer_reading';
	public $table_account ='tbl_subaccountgroup';
	public $tbl_admininfo = 'tbl_admininfo';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
	/** In Function Get all records from select table **/
	
    public function get_all_records() {
        $this->db->select("*,(Select zone from ".$this->table_zone." where ".$this->table_zone.".id	= ".$this->table_name.".zone ) as zones,
		(Select class_name from tbl_classification where tbl_classification.class_id	= ".$this->table_name.".classification ) as classification_name,
		(Select cust_type_name from tbl_customer_type where tbl_customer_type.cust_type_id	= ".$this->table_name.".account_type ) as type_name,
		(Select name from ".$this->table_billing." where ".$this->table_billing.".id = ".$this->table_name.".billingplans ) as billingplans_name");
		$this->db->from($this->table_name);
		$this->db->order_by('last_name','asc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	
	public function accountgroup(){
		$this->db->select("*");
		$this->db->from($this->table_account);
		//$this->db->where('id','1');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }		
		
	 public function get_address()
	 {
        $this->db->select("*");
		$this->db->from($this->table_address);
		$this->db->where('id','1');
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }		
	
	public function get_zone() {
        $this->db->select("*");
		$this->db->from($this->table_zone);
		$this->db->order_by('id','desc');
		$this->db->where('status','1');
		$this->db->order_by('order_series','desc');
		
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }	
	public function get_classification() {
        $this->db->select("*");
		$this->db->from($this->table_classification);
		$this->db->order_by('class_id','asc');
		//$this->db->where('status','1');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }	
	public function get_customer_type() {
        $this->db->select("*");
		$this->db->from($this->table_customer_type);
		$this->db->order_by('cust_type_id','asc');
		//$this->db->where('status','1');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }	
	
	public function get_billingplans() {
        $this->db->select("*");
		$this->db->from($this->table_billing);
		$this->db->order_by('id','desc');
		//$this->db->where('id','1');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	
 	/** In Function Get single records for edit view purpose from select table **/
    public function get_single_record($id='') {
        $this->db->select("*,(Select zone from ".$this->table_zone." where ".$this->table_zone.".id	= ".$this->table_name.".zone ) as zones,
		(Select name from ".$this->table_billing." where ".$this->table_billing.".id	= ".$this->table_name.".billingplans ) as billingplans_name,(Select account_name  from ".$this->table_account." where ".$this->table_account.".id = ".$this->table_name.".account_id) as subName
		"); 
		$this->db->from($this->table_name);
		if($id != ''){
			$this->db->where("id",$id);
			$query = $this->db->get();
			//echo $this->db->last_query();
			$result = $query->row_array();
		}
		return $result;
    }
	public function get_single_record_by_customer_id($customer_id='') {
        $this->db->select("*,(Select zone from ".$this->table_zone." where ".$this->table_zone.".id	= ".$this->table_name.".zone ) as zones,
		(Select name from ".$this->table_billing." where ".$this->table_billing.".id	= ".$this->table_name.".billingplans ) as billingplans_name,(Select account_name  from ".$this->table_account." where ".$this->table_account.".id = ".$this->table_name.".account_id) as subName
		"); 
		$this->db->from($this->table_name);
		if($customer_id != ''){
			$this->db->where($this->table_name.".customer_id",$customer_id);
			$query = $this->db->get();
			//echo $this->db->last_query();
			$result = $query->row_array();
		}
		return $result;
    }
	
	public function get_customerphoto($id)
	{
		$this->db->select("*");
		$this->db->from('tbl_userphotoupload');
		$this->db->where('customerid',$id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_adminrecord()
	{	
		$this->db->select('a.*,ai.*');
		$this->db->from('tbl_admininfo a,tbl_adminlogo ai');
		$this->db->where('a.adminid = ai.adminid');
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;

	}
	public function get_adminrecord_edit($adminid)
	{
		$this->db->select('a.*,ai.file,ai.adminid');
		$this->db->from('tbl_admininfo a,tbl_adminlogo ai');
		$this->db->where('a.adminid',$adminid);
		$this->db->where('a.adminid = ai.adminid');
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_adminrecord_update($adminid,$data)
	{
		$this->db->where('adminid',$adminid);
		$result = $this->db->update($this->tbl_admininfo, $data); 
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
		//$brcE     	= substr(rand(1,1000000),0,4); 
		//$customer_id  = 'WT'.$brcE;
		$date_installed = strtotime($this->input->post('date_installed'));

		$doc_num = explode('-',$this->input->post('customer_id'));
		$update_counter_array = array( 
			'doc_series_num' => $doc_num[2]
		);
		$C5 = &get_instance();
		$C5->db->where('doc_name', 'MEMBER');
		$C5->db->update('tbl_doc_series_number', $update_counter_array);

		$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
		$created_date = $dt_date->format("Y-m-d H:i:s");

		$set_data = array(
						'account_id' =>'0',
						'customer_id' =>trim($this->input->post('customer_id')),
						'first_name' => trim($this->input->post('first_name')),
						'middle_name' => trim($this->input->post('middle_name')),
						'last_name' => trim($this->input->post('last_name')),
						'gender' => $this->input->post('gender'),
						'DOB' => $this->input->post('dob'),
						'place_of_birth' => $this->input->post('place_of_birth'),
						'city' => $this->input->post('city'),
						'state' => $this->input->post('state'),
						'address' =>$this->input->post('address'),
						'mobile1' => $this->input->post('mobile1'),
						'mobile2' => $this->input->post('mobile2'),
						'line_number' => $this->input->post('line_number'),
						'email_id' => $this->input->post('email_id'),
						'customer_type' => $this->input->post('customer_type'),
						'zone' => $this->input->post('zone'),
						'billingplans' => $this->input->post('billingplans'),
						'referenceperson' => $this->input->post('referenceperson'),
						'meter_number' => $this->input->post('meter_number'),
						'meter_brand' => $this->input->post('meter_brand'),
						'meter_size' => $this->input->post('meter_size'),
						'date_installed' => date('Y-m-d', $date_installed),
						'status' => $this->input->post('status'),
						'classification' => $this->input->post('classification'),
						'account_type' => $this->input->post('account_type'),
						'membership_status' => $this->input->post('membership_status'),
						'special_priviledge' => $this->input->post('special_priviledge'),
						'create_date' => $dt_date->format('Y-m-d'),
						'create_date_time' => $created_date,
						
					);
		$this->db->insert($this->table_name, $set_data); 
		$result = $this->db->insert_id();
		return $result;
	}
  	/** In Function Update records for select table **/
	public function update_record($id){
				//$brcE     	= substr(rand(1,1000000),0,4); 
		//$customer_id  = 'WT'.$brcE;
		$date_installed = strtotime($this->input->post('date_installed'));
		$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
		$update_date = $dt_date->format("Y-m-d H:i:s");

		$set_data = array(
						//'account_id' =>mysql_real_escape_string($this->input->post('acount_group')),
						'customer_id' =>trim($this->input->post('customer_id')),
						'first_name' => trim($this->input->post('first_name')),
						'middle_name' => trim($this->input->post('middle_name')),
						'last_name' => trim($this->input->post('last_name')),
						'gender' => $this->input->post('gender'),
						'DOB' => $this->input->post('dob'),
						'place_of_birth' => $this->input->post('place_of_birth'),
						'city' => $this->input->post('city'),
						'state' => $this->input->post('state'),
						'address' => $this->input->post('address'),
						'mobile1' => $this->input->post('mobile1'),
						'mobile2' => $this->input->post('mobile2'),
						'line_number' =>$this->input->post('line_number'),
						'email_id' => $this->input->post('email_id'),
						'customer_type' => $this->input->post('customer_type'),
						'zone' => $this->input->post('zone'),
						'billingplans' => $this->input->post('billingplans'),
						'referenceperson' => $this->input->post('referenceperson'),
						'meter_number' => $this->input->post('meter_number'),
						'meter_brand' => $this->input->post('meter_brand'),
						'meter_size' => $this->input->post('meter_size'),
						'date_installed' => date('Y-m-d', $date_installed),
						'status' => $this->input->post('status'),
						'classification' => $this->input->post('classification'),
						'account_type' => $this->input->post('account_type'),
						'special_priviledge' => $this->input->post('special_priviledge'),
						'membership_status' => $this->input->post('membership_status'),
						'update_date_time' => $update_date,
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
	public function status_record($id,$balance){
		//$sts = ($status == 0 ? 1 : 0);
		$set_data = array(
						'balance' => 0
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	public function get_addcustomer_records($customer_type,$zone){ 
        $this->db->select("*");
		$this->db->from($this->table_name);
		if($customer_type!=''){
			$this->db->where('customer_type',$customer_type);
		}	
		if($zone!=''){
			$this->db->where('zone',$zone);
		}
		/*if($fromdate!=''){
			$this->db->where("create_date_time >= ",date('Y-m-d', strtotime($fromdate)));
		}		
		if($todate !=''){
			$this->db->where("create_date_time <= ",date('Y-m-d', strtotime($todate)));
		}	*/	
		$query = $this->db->get();
		//echo $this->db->last_query();
		//exit;
		$result = $query->result_array();
		return $result;		
	}	
	
	public function get_addcustomer_payment_records($customer_id,$zone,$fromdate,$todate)
	{ 
        $this->db->select($this->table_name.".customer_id,".$this->table_name.".first_name,".$this->table_name.".last_name,".$this->table_name.".middle_name,".$this->table_name.".gender,".$this->table_name.".address,".$this->table_name.".mobile1,".$this->table_name.".mobile2,".$this->table_name.".email_id,".$this->table_name.".customer_type,".$this->table_meter.".oldmeter,".$this->table_meter.".aftermeter,".$this->table_meter.".amount,".$this->table_meter.".balance,".$this->table_meter.".pay_amount,".$this->table_meter.".status,".$this->table_meter.".total");
		$this->db->from($this->table_name);
		$this->db->join($this->table_meter,$this->table_meter.'.customer_id='.$this->table_name.'.customer_id');
		if($customer_id!='')
		{
			$this->db->where($this->table_name.'.customer_id',$customer_id);
		}
		if($zone!='')
		{
			$this->db->where($this->table_name.'.zone',$zone);	
		}
		if($fromdate!=''){
			$this->db->where($this->table_meter.'.create_date >=',date('Y-m-d', strtotime($fromdate)));
		}		
		if($todate !=''){
			$this->db->where($this->table_meter.'.create_date <=',date('Y-m-d', strtotime($todate)));
		}				
		$this->db->order_by($this->table_meter.'.id','ASC');
		$this->db->where($this->table_name.'.customer_type','metercustomer');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;			
	}	
	public function get_addcustomer_meter_records($zone,$mobile)
	{ 
		$this->db->select("*,(Select amount from ".$this->table_meter." where ".$this->table_meter.".id = ".$this->table_name.".customer_id) as amount");
		$this->db->from($this->table_name);
		
		if($zone!=''){
			$this->db->where('zone',$zone);
		}	
		if($mobile !=''){
			$where = '( `mobile1` = "'.$mobile.'" OR `mobile2` = "'.$mobile.'" )';
			$this->db->where($where);
		}	
			
		$query = $this->db->get();;
		$result = $query->result_array();
		return $result;		
	}	
	public function get_addcustomer_income_records1($fromdate,$todate){ 
        $this->db->select("*,(Select amount from ".$this->table_meter." where ".$this->table_meter.".customer_id = ".$this->table_name.".customer_id) as amount");
		$this->db->from($this->table_name);
		

		if($fromdate && $todate !=''){
			$this->db->where("create_date_time >= ",date('Y-m-d', strtotime($fromdate)));
			$this->db->where("create_date_time <= ",date('Y-m-d', strtotime($todate)));
		}	
             //$this->db->where('status',1);		
			$query = $this->db->get();
		//echo $this->db->last_query();
		//exit;
		$result = $query->result_array();
		return $result;		
	}	
		
		public function get_addcustomer_technicalrecords($customer_type){ 
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->join('tbl_technical', 'tbl_technical.customer_id = tbl_addcustomer.customer_id');
		$this->db->group_by('tbl_technical.id');
		if($customer_type!=''){
			$this->db->where('customer_type',$customer_type);
		}	
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;		
	}	
	public function get_addcustomer_monthly_records1($customer_id,$mobile1,$fromdate,$todate){ 
		$this->db->select($this->table_name.".customer_id,".$this->table_name.".first_name,".$this->table_name.".gender,".$this->table_name.".address,".$this->table_name.".mobile1,".$this->table_name.".mobile2,".$this->table_name.".email_id,".$this->table_name.".customer_type,".$this->table_monthly.".amount,".$this->table_monthly.".paidamount,".$this->table_monthly.".balance,".$this->table_monthly.".month,".$this->table_monthly.".total,".$this->table_monthly.".status");
		$this->db->from($this->table_name);
		$this->db->join($this->table_monthly,$this->table_monthly.'.customer_id='.$this->table_name.'.customer_id');
		if($customer_id!=''){
		$this->db->where($this->table_monthly.'.customer_id',$customer_id);
		}
		if($mobile1 !=''){
			$where = '( `mobile1` = "'.$mobile1.'" OR `mobile2` = "'.$mobile1.'" )';
			$this->db->where($where);
		}	
		if($fromdate !='0'){
		$this->db->where($this->table_monthly.'.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}if($todate !='0'){
		$this->db->where($this->table_monthly.'.create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}		
		
		$this->db->order_by($this->table_monthly.'.id','desc');
		$this->db->group_by($this->table_monthly.'.id');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;	
}


	public function get_addcustomer_monthly_records($customer_id,$mobile1,$fromdate,$todate){ 
		$this->db->select($this->table_name.".customer_id,".$this->table_name.".first_name,".$this->table_name.".gender,".$this->table_name.".address,".$this->table_name.".mobile1,".$this->table_name.".mobile2,".$this->table_name.".email_id,".$this->table_name.".customer_type,".$this->table_monthly.".amount,".$this->table_monthly.".paidamount,".$this->table_monthly.".balance,".$this->table_monthly.".month,".$this->table_monthly.".total,".$this->table_monthly.".status");
		$this->db->from($this->table_name);
		$this->db->join($this->table_monthly,$this->table_monthly.'.customer_id='.$this->table_name.'.customer_id');
		if($customer_id!=''){
		$this->db->where($this->table_monthly.'.customer_id',$customer_id);
		}
		if($mobile1 !=''){
			$where = '( `mobile1` = "'.$mobile1.'" OR `mobile2` = "'.$mobile1.'" )';
			$this->db->where($where);
		}	
		if($fromdate !=''){
		$this->db->where($this->table_monthly.'.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}if($todate !=''){
		$this->db->where($this->table_monthly.'.create_date_time <=',date('Y-m-d',strtotime($todate)));	  
		}		
		
		$this->db->order_by($this->table_monthly.'.id','desc');
		$this->db->group_by($this->table_monthly.'.id');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;	
}



	public function get_addcustomer_paid_records($customer_type,$zone,$fromdate,$todate){ 
		
		if($customer_type =='monthlycustomer'){
			 $this->db->select("*");
			$this->db->join($this->table_name,$this->table_name.'.customer_id='.$this->table_monthly.'.customer_id');
			$this->db->from($this->table_monthly);
			$this->db->group_by($this->table_monthly.'.id');
			if($fromdate !=''){
			    $this->db->where($this->table_monthly.".create_date_time >= ",date('Y-m-d', strtotime($fromdate)));
			}		
			if($todate !=''){
				$this->db->where($this->table_monthly.".create_date_time <= ",date('Y-m-d', strtotime($todate)));
			}
			if($customer_type !=''){
			    $this->db->where($this->table_name.'.customer_type',$customer_type);
			}
			if($zone !=''){
			    $this->db->where($this->table_name.'.zone',$zone);
			}
			$this->db->where($this->table_monthly.'.status',0);
		}	
		if($customer_type =='metercustomer'){
			$this->db->select("*");
			$this->db->join($this->table_name,$this->table_name.'.customer_id='.$this->table_meter.'.customer_id');
			$this->db->from($this->table_meter);
			$this->db->group_by($this->table_meter.'.id');
			if($fromdate !=''){
				$this->db->where($this->table_meter.".date >= ",date('Y-m-d', strtotime($fromdate)));
			}		
			if($todate !=''){
				$this->db->where($this->table_meter.".date <= ",date('Y-m-d', strtotime($todate)));
			}
			/*if($customer_type !=''){
				$this->db->where($this->table_name.'.customer_type',$customer_type);
			}*/
			if($zone !=''){
				$this->db->where($this->table_name.'.zone',$zone);
			}
			$this->db->where($this->table_meter.".status",1);
		}		
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;		
	}	
	
	public function get_addcustomer_unpaid_records($membership_status='',$zone='',$billingmonth='',$billingyear=''){ 
        $this->db->select($this->table_name.".address,".$this->table_name.".customer_id,".$this->table_name.".first_name,".$this->table_name.".last_name, ".$this->table_customerreading.".*, ".$this->table_zone.".zone as zonename");
		$this->db->from($this->table_customerreading);
			
		
		$this->db->join($this->table_meter, $this->table_customerreading.'.customer_id='.$this->table_meter.'.customer_id AND '.$this->table_customerreading.'.month='.$this->table_meter.'.month AND '.$this->table_customerreading.'.year='.$this->table_meter.'.year','left');

		$this->db->join($this->table_name, $this->table_customerreading.'.customer_id='.$this->table_name.'.customer_id','left');
		$this->db->join($this->table_zone, $this->table_name.'.zone='.$this->table_zone.'.id','left');

		//$this->db->group_by($this->table_meter.'.id');
		/*if($fromdate !=''){
		$this->db->where($this->table_meter.".create_date_time >= ",date('Y-m-d', strtotime($fromdate)));
		}		
		if($todate !=''){
			$this->db->where($this->table_meter.".create_date_time <= ",date('Y-m-d', strtotime($todate)));
		}*/
		if($billingmonth !='' && $billingyear !=''){
			$this->db->where($this->table_customerreading.".month",$billingmonth);
			$this->db->where($this->table_customerreading.".year",$billingyear);
		}
		
		
		if($membership_status !='all'){
			$this->db->where($this->table_name.'.membership_status',$membership_status);
		}	
		if($zone !='all'){
			$this->db->where($this->table_name.'.zone',$zone);
		}	
		$this->db->where($this->table_meter.'.invoice_id IS NULL');
		$this->db->order_by($this->table_name.'.last_name','ASC');
		$this->db->order_by($this->table_name.'.first_name','ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;		
	}	
	
	public function get_addcustomer_income_records($fromdate,$todate){ 
		$this->db->select($this->table_name.".first_name,".$this->table_name.".customer_type,".$this->table_name.".customer_id,".$this->table_meter.".create_date_time,".$this->table_meter.".amount");
		$this->db->from($this->table_name);
		$this->db->join($this->table_meter,$this->table_meter.'.customer_id='.$this->table_name.'.customer_id');
		//$this->db->join($this->table_monthly,$this->table_monthly.'.customer_id='.$this->table_name.'.customer_id');
		$this->db->where($this->table_meter.'.status',1);
		if($fromdate !=''){
			$this->db->where($this->table_meter.'.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}
		if($todate !=''){
			$this->db->where($this->table_meter.'.create_date_time <=',date('Y-m-d',strtotime($todate)));
		}$query = $this->db->get();
		$result = $query->result_array();
		//echo '<pre>';print_r($result);exit;
		return $result;	
	}
	public function get_addcustomer_meter_income_records($fromdate,$todate)
	{ 
		$this->db->select($this->table_name.".first_name,".$this->table_name.".customer_type,".$this->table_name.".customer_id,".$this->table_monthly.".create_date_time,".$this->table_monthly.".amount,".$this->table_monthly.".status");
		$this->db->from($this->table_name);
		//$this->db->join($this->table_meter,$this->table_meter.'.customer_id='.$this->table_name.'.customer_id');
		$this->db->join($this->table_monthly,$this->table_monthly.'.customer_id='.$this->table_name.'.customer_id');
		if($fromdate !='')
		{
			$this->db->where($this->table_monthly.'.create_date_time >=',date('Y-m-d',strtotime($fromdate)));
		}
		if($todate !='')
		{
			$this->db->where($this->table_monthly.'.create_date_time <=',date('Y-m-d',strtotime($todate)));
		}
		
		$this->db->where($this->table_monthly.'.status',1);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;	
	}
	public function customer_register_form_monthly($cusid, $customer){
		$this->db->select('tbl_zone.id as zoneid, tbl_zone.zone as zonena, tbl_feesplaning.id as feesid, tbl_feesplaning.name as beelname, tbl_feesplaning.days as days, tbl_feesplaning.amount as amountf, tbl_addcustomer.*');
		$this->db->from($this->tbl_addcustomer);
		$this->db->join('tbl_zone', 'tbl_addcustomer.zone = tbl_zone.id');
		$this->db->join('tbl_feesplaning', 'tbl_addcustomer.billingplans = tbl_feesplaning.id');
		$this->db->where('tbl_addcustomer.id',$cusid);
		$this->db->where('tbl_addcustomer.customer_id',$customer);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_customer_form($cusid, $customer){
		$this->db->select('tbl_zone.id as zoneid, tbl_zone.zone as zonena, tbl_addcustomer.*');
		$this->db->from($this->tbl_addcustomer);
		$this->db->join('tbl_zone', 'tbl_addcustomer.zone = tbl_zone.id');
		$this->db->where('tbl_addcustomer.id',$cusid);
		$this->db->where('tbl_addcustomer.customer_id',$customer);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
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
		$this->db->from($this->table_name);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	/** Update customer password **/
	public function update_customer_password($customer_id, $password) {
		try {
			// First check if password column exists
			$query = $this->db->query("SHOW COLUMNS FROM `".$this->table_name."` LIKE 'password'");
			if($query->num_rows() == 0) {
				// Column doesn't exist, try to create it
				$alter_query = "ALTER TABLE `".$this->table_name."` ADD COLUMN `password` VARCHAR(255) NULL";
				$alter_result = $this->db->query($alter_query);
				
				// Check if ALTER was successful by checking for errors
				if(!$alter_result) {
					log_message('error', 'Failed to create password column. Query: ' . $alter_query);
					// Column creation failed, but continue anyway - might already exist or permission issue
				}
			}
			
			// Now update the password
			$this->db->where('id', $customer_id);
			$data = array('password' => $password);
			$result = $this->db->update($this->table_name, $data);
			
			// Log the query for debugging
			log_message('debug', 'Update password query: ' . $this->db->last_query());
			log_message('debug', 'Customer ID: ' . $customer_id . ', Affected rows: ' . $this->db->affected_rows());
			
			// Return true if update executed (affected_rows can be 0 if password was the same)
			return $result !== false;
			
		} catch(Exception $e) {
			log_message('error', 'Exception in update_customer_password: ' . $e->getMessage());
			log_message('error', 'Exception trace: ' . $e->getTraceAsString());
			return false;
		}
	}
	
	/** Server-side pagination: Get paginated records with filtering **/
	public function get_paginated_records($start = 0, $length = 10, $search = '', $order_column = 'last_name', $order_dir = 'asc', $zone = '') {
		$this->db->select($this->table_name.".*,".$this->table_zone.".zone as zones,
		tbl_classification.class_name as classification_name,
		tbl_customer_type.cust_type_name as type_name,
		".$this->table_billing.".name as billingplans_name");
		$this->db->from($this->table_name);
		$this->db->join($this->table_zone, $this->table_zone.'.id = '.$this->table_name.'.zone', 'left');
		$this->db->join('tbl_classification', 'tbl_classification.class_id = '.$this->table_name.'.classification', 'left');
		$this->db->join('tbl_customer_type', 'tbl_customer_type.cust_type_id = '.$this->table_name.'.account_type', 'left');
		$this->db->join($this->table_billing, $this->table_billing.'.id = '.$this->table_name.'.billingplans', 'left');

		// Apply zone filter (empty or 'all' = show all)
		if($zone != '') {
			$this->db->where($this->table_name.'.zone', $zone);
		}
		
		// Apply search filter
		if($search != '') {
			$search_escaped = $this->db->escape_like_str($search);
			$this->db->where("(
				".$this->table_name.".customer_id LIKE '%".$search_escaped."%' OR
				".$this->table_name.".first_name LIKE '%".$search_escaped."%' OR
				".$this->table_name.".middle_name LIKE '%".$search_escaped."%' OR
				".$this->table_name.".last_name LIKE '%".$search_escaped."%' OR
				".$this->table_name.".address LIKE '%".$search_escaped."%' OR
				".$this->table_name.".meter_number LIKE '%".$search_escaped."%' OR
				".$this->table_name.".mobile1 LIKE '%".$search_escaped."%' OR
				".$this->table_name.".mobile2 LIKE '%".$search_escaped."%' OR
				".$this->table_zone.".zone LIKE '%".$search_escaped."%' OR
				tbl_classification.class_name LIKE '%".$search_escaped."%'
			)", NULL, FALSE);
		}
		
		// Order by - use the order_column directly as it already contains table.column format
		$this->db->order_by($order_column, $order_dir);
		
		// Limit and offset
		$this->db->limit($length, $start);
		
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	/** Server-side pagination: Get total count with filtering **/
	public function get_total_count($search = '', $zone = '') {
		$this->db->select("COUNT(".$this->table_name.".id) as total");
		$this->db->from($this->table_name);
		$this->db->join($this->table_zone, $this->table_zone.'.id = '.$this->table_name.'.zone', 'left');
		$this->db->join('tbl_classification', 'tbl_classification.class_id = '.$this->table_name.'.classification', 'left');
		$this->db->join('tbl_customer_type', 'tbl_customer_type.cust_type_id = '.$this->table_name.'.account_type', 'left');
		$this->db->join($this->table_billing, $this->table_billing.'.id = '.$this->table_name.'.billingplans', 'left');

		// Apply zone filter (empty or 'all' = show all)
		if($zone != '') {
			$this->db->where($this->table_name.'.zone', $zone);
		}
		
		// Apply search filter
		if($search != '') {
			$search_escaped = $this->db->escape_like_str($search);
			$this->db->where("(
				".$this->table_name.".customer_id LIKE '%".$search_escaped."%' OR
				".$this->table_name.".first_name LIKE '%".$search_escaped."%' OR
				".$this->table_name.".middle_name LIKE '%".$search_escaped."%' OR
				".$this->table_name.".last_name LIKE '%".$search_escaped."%' OR
				".$this->table_name.".address LIKE '%".$search_escaped."%' OR
				".$this->table_name.".meter_number LIKE '%".$search_escaped."%' OR
				".$this->table_name.".mobile1 LIKE '%".$search_escaped."%' OR
				".$this->table_name.".mobile2 LIKE '%".$search_escaped."%' OR
				".$this->table_zone.".zone LIKE '%".$search_escaped."%' OR
				tbl_classification.class_name LIKE '%".$search_escaped."%'
			)", NULL, FALSE);
		}
		
		$query = $this->db->get();
		$result = $query->row_array();
		return isset($result['total']) ? intval($result['total']) : 0;
	}
	
}
?>