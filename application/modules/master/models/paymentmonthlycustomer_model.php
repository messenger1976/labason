<?php 
class paymentmonthlycustomer_model extends CI_Model {
	public $table_name = 'tbl_monthlycustomer';
	public $table_name_monthly_dummy = 'tbl_monthlycustomer_dummy';
    public $table_customername = 'tbl_addcustomer';
	public $table_billing = 'tbl_feesplaning';
	public $table_address = 'tbl_web_settings';
	public $table_generate = 'tbl_generate';
	public $table_generate_customer = 'tbl_generate_customer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_ledger ='tbl_ledgers';
	public $table_transactions ='tbl_transactions';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	public function fetchLedger(){
		$this->db->select("*");
		$this->db->from($this->table_ledger);
		//$this->db->where('id','1');
		//$this->db->where('account_id','5');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }	
	/** In Function Get all records from select table **/
    public function get_all_records() {
        
		$this->db->select('tbl_feesplaning.id as planid, tbl_feesplaning.name as plname, tbl_feesplaning.days as days, tbl_feesplaning.days as amount, tbl_monthlycustomer.*');				   
		$this->db->from('tbl_monthlycustomer');
		$this->db->join('tbl_feesplaning','tbl_monthlycustomer.plan_name = tbl_feesplaning.id');
		$this->db->order_by('tbl_monthlycustomer.id','DESC');
		$query = $this->db->get();
		return $query->result_array();
		
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
	 public function get_addcustomer() {
        $this->db->select("*");
		$this->db->from($this->table_customername);
		$this->db->order_by('id','desc');
		$this->db->where("customer_type",'monthlycustomer');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }	
	
	public function get_single_record_desc($id='') {
        $this->db->select("*");
		$this->db->from($this->table_name);
		if($id != ''){
			$this->db->where("customer_id",$id);
			$this->db->order_by('id','desc');
			$query = $this->db->get();
			//echo $this->db->last_query();
			$result = $query->result_array();
		}
		return $result;
    }	
	
	public function get_single_record_desc_month($id='') {
        $this->db->select("*");
		$this->db->from($this->table_name);
		if($id != ''){
			$this->db->where("customer_id",$id);
			//$this->db->where("month",$month);
			$this->db->order_by('id','desc');
			$query = $this->db->get();
			//echo $this->db->last_query();
			$result = $query->result_array();
		}
		return $result;
    }		
	
	public function select_getbalance($id) {
		$getData = $this->get_single_record_desc_month($id);
		//echo'<pre>';print_r($getData);exit;
		if(!empty($getData)){
			if($getData[0]['status']=='1'){
				/*$this->db->select("*,(Select amount from ".$this->table_billing." where ".$this->table_billing.".id = ".$this->table_customername.".billingplans) as amount,
				(Select name from ".$this->table_billing." where ".$this->table_billing.".id = ".$this->table_customername.".billingplans) as billingname
				");
				$this->db->from($this->table_customername);
				$this->db->order_by('id','desc');
				$this->db->where("customer_id",$id);
				$query = $this->db->get();
				//echo $this->db->last_query();
				$result = $query->row_array();*/
			$this->db->select("*,(Select amount from ".$this->table_billing." where ".$this->table_billing.".id = ".$this->table_customername.".billingplans) as amount,
			(Select name from ".$this->table_billing." where ".$this->table_billing.".id = ".$this->table_customername.".billingplans) as billingname
			");
			$this->db->from($this->table_customername);
			$this->db->order_by('id','desc');
			$this->db->where("customer_id",$id);
			$query = $this->db->get();
			//echo $this->db->last_query();
			$result1 = $query->row_array();
			
			if($result1){
				$this->db->select("year_month_id");
				$this->db->from($this->table_generate_customer);
				$this->db->where("customer_id",$id);
				$query1 = $this->db->get();
				$result2 = $query1->result_array();	
				$flat = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($result2)), 0);		
			}
				
				$this->db->select("*");
				$this->db->from($this->table_generate);
				$this->db->where_not_in("year_month_id",$flat);
				$query2 = $this->db->get();
				$result3 = $query2->num_rows();	
					$result5 = array('count_generate' => $result3);
				
			 
			$result = array_merge($result1,$result2,$result5);			
			}else{
				$this->db->select("*,total as amount,plan_name as billingname
				");
				$this->db->from($this->table_name);
				$this->db->order_by('id','desc');
				$this->db->where("customer_id",$id);
				
				$query = $this->db->get();
				//echo $this->db->last_query();
				$result1 = $query->result_array();
				
			$this->db->select("*,(Select amount from ".$this->table_billing." where ".$this->table_billing.".id = ".$this->table_customername.".billingplans) as amount_billing,
			(Select name from ".$this->table_billing." where ".$this->table_billing.".id = ".$this->table_customername.".billingplans) as billingname
			");
			$this->db->from($this->table_customername);
			$this->db->order_by('id','desc');
			$this->db->where("customer_id",$id);
			$query6 = $this->db->get();
			$result6 = $query6->row_array();				
				
				
				
			if($result1){
				$this->db->select("year_month_id");
				$this->db->from($this->table_generate_customer);
				$this->db->where("customer_id",$id);
				$query1 = $this->db->get();
				$result2 = $query1->result_array();	
				$flat = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($result2)), 0);		
			}					
			
				$this->db->select("*");
				$this->db->from($this->table_generate);
				$this->db->where_not_in("year_month_id",$flat);
				$query2 = $this->db->get();
				$result3 = $query2->num_rows();	
				if($result3 =='0'){
					$total =  $result1[0]['amount'];
					$result5 = array('count_generate' => 1);
				}else{
					$total =  $result1[0]['amount']+$result6['amount_billing'];
					$result5 = array('count_generate' => $result3);	
				}
				$result4 = array(
				   'billingname' => $result1[0]['billingname'],
				   'amount' => $total,
				   'total' => $result1[0]['amount']
				);
				//$total=$result4['amount']+$result6['amount_billing'];
				$result = array_merge($result2,$result4,$result5);		
			}	
		}	
		if(empty($getData)){
			$this->db->select("*,(Select amount from ".$this->table_billing." where ".$this->table_billing.".id = ".$this->table_customername.".billingplans) as amount,
			(Select name from ".$this->table_billing." where ".$this->table_billing.".id = ".$this->table_customername.".billingplans) as billingname
			");
			$this->db->from($this->table_customername);
			$this->db->order_by('id','desc');
			$this->db->where("customer_id",$id);
			$query = $this->db->get();
			$result1 = $query->row_array();
			if($result1){
				$date= date('d-M-Y',strtotime($result1['create_date_time']));
				$this->db->select("*");
				$this->db->from($this->table_generate);
				$this->db->order_by('id','desc');
				$query1 = $this->db->get();
				$result2 = $query1->num_rows();				
			}
			$result3 = array('count_generate' => $result2,
			'total' => 1
			);
			$result = array_merge($result1,$result3);
		}		
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

	public function get_generate() {
        $this->db->select("*");
		$this->db->from($this->table_generate);
		$this->db->order_by('id','desc');
		//$this->db->where('id','1');
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
		$id = $this->input->post('name');
		$explodes = @explode('**',$this->input->post('month'));
		$getData=$this->my_model->get_single_record_desc_month($id,$explodes[0]);
		//echo'<pre>';print_r($id);
		//echo'<pre>';print_r($getData);exit;
		if(!empty($getData)){
			if($getData[0]['status']=='1'){

		$balance = $this->input->post('amount')-$this->input->post('paidamount');
		$balance_amount=$getData[0]['total']+$this->input->post('paidamount');
		$total=$this->input->post('amount')-$this->input->post('paidamount');
		
		
			}else{
				$explode = @explode('**',$this->input->post('years'));
				$explodes = @explode('**',$this->input->post('month'));
				if($getData[0]['year'] == $explode[0] && $getData[0]['month'] == $explodes[0]){
					$balance = $this->input->post('amount')-$this->input->post('paidamount');
					$total=$this->input->post('amount')-$this->input->post('paidamount');
				}else{
			$balance = $this->input->post('amount')-$this->input->post('paidamount');
			$balance_amount=$this->input->post('amount')-$this->input->post('paidamount');
            $total=$this->input->post('amount')-$this->input->post('paidamount');
				}
			}
			
		}if(empty($getData)){
							$explode = @explode('**',$this->input->post('years'));
				$explodes = @explode('**',$this->input->post('month'));
			$balance = $this->input->post('amount')-$this->input->post('paidamount');
			$total=$this->input->post('amount')-$this->input->post('paidamount');
			
		}		
		
		if($total == 0){
			$status = 1;
			
		$month_year = $this->get_generate();
		foreach($month_year as $key => $value){
			$set_data = array(
							'customer_id' => $this->input->post('name'),
							'year' => $value['year'],
							'month' => $value['month'],
							'year_month_id' => $value['year_month_id'],
							'create_date_time' => date('Y-m-d H:i:s'),
							
						);
			$results = $this->db->insert($this->table_generate_customer, $set_data); 	
			$last_id_status = $this->db->insert_id();
		}
			
			
		}else{
			$status = 0;
			
		$month_year = $this->get_generate();
		foreach($month_year as $key => $value){
			$set_data = array(
							'customer_id' => $this->input->post('name'),
							'year' => $value['year'],
							'month' => $value['month'],
							'year_month_id' => $value['year_month_id'],
							'create_date_time' => date('Y-m-d H:i:s'),
							
						);
			$results = $this->db->insert($this->table_generate_customer, $set_data);
			$last_id_status = $this->db->insert_id();
		}			
			
		}
		
		
       //echo'<pre>';print_r($add_record);exit;
		$set_data = array(
						'customer_id' => $this->input->post('name'),
						'name' => mysql_real_escape_string($this->input->post('name')),
						'plan_name' => mysql_real_escape_string($this->input->post('plan_name')),
						'amount' => mysql_real_escape_string($this->input->post('amount')),
						'paidamount' => mysql_real_escape_string($this->input->post('paidamount')),
						'balance' => $balance,
						 'total' =>$total,
						//'year' => $this->input->post('years'),
						//'month' => $explodes[0],
						'status' => $status,
						'create_date_time' => date('Y-m-d H:i:s'),
						
					);
		$result = $this->db->insert($this->table_name, $set_data);  
		$last_id = $this->db->insert_id();
		if($result){
			$set_datas = array(
							'insert_month_id'=>$last_id,
						);
			$this->db->where('id',$last_id_status);			
			$results = $this->db->update($this->table_generate_customer, $set_datas);		
		}
		
		if($result){
			$set_data = array(
							'customer_id' => $this->input->post('name'),
							'name' => mysql_real_escape_string($this->input->post('name')),
							'plan_name' => mysql_real_escape_string($this->input->post('plan_name')),
							'amount' => mysql_real_escape_string($this->input->post('amount')),
							'paidamount' => mysql_real_escape_string($this->input->post('paidamount')),
							'balance' => $balance,
							 'total' =>$total,
							//'year' => $this->input->post('years'),
							//'month' => $explodes[0],
							'status' => $status,
							'create_date_time' => date('Y-m-d H:i:s'),
							
						);
			$result = $this->db->insert($this->table_name_monthly_dummy, $set_data);  			
			
		}
	
		return $result;
	}
	/** In Function Add records for transaction table **/
	public function add_transaction($id){
		
		        // save data(first entry) on transaction table
		$sql = "SELECT * FROM tbl_addmetercustomer WHERE id =(SELECT MAX(id) FROM tbl_addmetercustomer)";
		$query = $this->db->query($sql);
		$result = $query->result_array();  //print_r($result[0]['id']);exit;
		$lastId = $result[0]['id'];
		//$lastId = $this->db->insert_id(); 
		$set_data2 = array(
						'tableName' => 'monthlycustomer',
						'transaction_id' => $lastId,
						'ledger_id' => $this->input->post('customer_id'),
						'ledger_id_for' => 'customer_id',
						'debit' => mysql_real_escape_string($this->input->post('pay_amount')),//$allamount,
					    'create_date_time' => date('Y-m-d H:i:s'),
					    'update_date_time' => date('Y-m-d H:i:s'),
					);
		$result2 = $this->db->insert($this->table_transactions, $set_data2); //print_r($result2); //exit;
		// save data(second entry) on transaction table
		/*$set_data3 = array(
						'tableName' => 'monthlycustomer',
						'transaction_id' => $lastId,
						'ledger_id' => $this->input->post('ledger_id'),
						'ledger_id_for' => 'ledger_id',
		                'credit' => mysql_real_escape_string($this->input->post('pay_amount')),//$allamount,
					    'create_date_time' => date('Y-m-d H:i:s'),
					    'update_date_time' => date('Y-m-d H:i:s'),
					);
		$result3 = $this->db->insert($this->table_transactions, $set_data3); //print_r($result3); exit;*/
		return $result;
		
	}
	public function add_reocrd_d(){
		$balance = mysql_real_escape_string($this->input->post('final_total_amount'));
		$pay_amount = mysql_real_escape_string($this->input->post('pay_amount'));
		$balance_input = $balance - $pay_amount;
		$amount = mysql_real_escape_string($this->input->post('input_amount'));
		$pay_am = mysql_real_escape_string($this->input->post('pay_amount'));
		$unpaid = $amount - $pay_am;
		$set_data = array(
						'customer_id' => $this->input->post('name'),
						'ledger_id' => $this->input->post('ledger_id'),
						'invoice_id' => $this->input->post('time_format'),
						'name' => mysql_real_escape_string($this->input->post('first_name')),
						'plan_name' => mysql_real_escape_string($this->input->post('plan_name')),
						'amount' => mysql_real_escape_string($this->input->post('input_amount')),
						'paidamount' => mysql_real_escape_string($this->input->post('pay_amount')),
						'unpaidamount' => $unpaid,
						'balance' => $unpaid,
						'total' => mysql_real_escape_string($this->input->post('final_total_amount')),
						'currency' => $this->input->post('currency'),
						'startdate' => mysql_real_escape_string($this->input->post('startdate')),
						'enddate' => mysql_real_escape_string($this->input->post('enddate')),
						'year' => date('Y'),
						'month' => date('M'),
						'status' => mysql_real_escape_string($this->input->post('status')),
						'date' => date('Y-m-d'),
						'create_date_time' => date('Y-m-d H:i:s'),
						'update_date_time' => date('Y-m-d H:i:s'),
					);
		$result = $this->db->insert($this->table_name, $set_data);  
			// save data(first entry) on transaction table
		$lastId = $this->db->insert_id(); 
		$set_data2 = array(
						'tableName' => 'monthlycustomer',
						'transaction_id' => $lastId,
						'ledger_id' => $this->input->post('tab_id'), //$this->input->post('cusomer_tam'),
						//$this->input->post('customer_id'),
						'ledger_id_for' => 'customer_id',
						'debit' => mysql_real_escape_string($this->input->post('pay_amount')),//$allamount,
					    'date' => date('Y-m-d',strtotime($this->input->post('date'))),
					    'create_date_time' => date('Y-m-d H:i:s'),
					    'update_date_time' => date('Y-m-d H:i:s'),
					);
		$result2 = $this->db->insert($this->table_transactions, $set_data2); //print_r($result2); //exit;
		// save data(second entry) on transaction table
		/*$set_data3 = array(
						'tableName' => 'monthlycustomer',
						'transaction_id' => $lastId,
						'ledger_id' => $this->input->post('ledger_id'),
						'ledger_id_for' => 'ledger_id',
		                'credit' => mysql_real_escape_string($this->input->post('pay_amount')),//$allamount,
					    'date' => date('Y-m-d',strtotime($this->input->post('date'))),
					    'create_date_time' => date('Y-m-d H:i:s'),
					    'update_date_time' => date('Y-m-d H:i:s'),
					);
		$result3 = $this->db->insert($this->table_transactions, $set_data3); //print_r($result3); exit;*/
		//print_r($result3);
		return $result2;
		//return $result;
	}
  	/** In Function Update records for select table **/
	public function update_record($id){
        $id = $this->input->post('id');
		$getData=$this->my_model->select_getbalance($id);
		//echo'<pre>';print_r($getData);exit;
		if(!empty($getData)){
			if($getData[0]['status']=='1'){
		$balance = 0;
			}else{
			$balance = $getData[0]['amount'];
            
			}
			
		}if(empty($getData)){
		
			$balance = 0;
		}		
		$total=$this->input->post('amount')+$balance;
		$set_data = array(
						'customer_id' => $this->input->post('name'),
						'name' => mysql_real_escape_string($this->input->post('name')),
						'plan_name' => mysql_real_escape_string($this->input->post('plan_name')),
						'amount' => mysql_real_escape_string($this->input->post('amount')),
						'paidamount' => mysql_real_escape_string($this->input->post('paidamount')),
						'balance' => $balance,
						 'total' =>$total,
						
						'status' => 0,
						'create_date_time' => date('Y-m-d H:i:s'),
						
					);
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
  
    public function generate_record(){
		
		$brcE     =  substr(rand(1,1000000),0,3);
		$brchrE  =   substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUXYVWZ"), 0, 3);
		$pass_id =   'RAND'.$brchrE.$brcE;

		$set_data = array(
						
						'year' => $this->input->post('years'),
					    'month' => $this->input->post('month'),
						'year_month_id' => $pass_id,
						'status' => 1,
						'create_date_time' => date('Y-m-d H:i:s'),
						
					);
					
		$result = $this->db->insert($this->table_generate, $set_data); 
		return $result;
	} 
  	/** In Function Delete records for select table **/
	public function delete_record($id,$id_generate){
		$this->db->where('id',$id);
		$result = $this->db->delete($this->table_name); 
		if($result){
		$this->db->where('insert_month_id',$id_generate);
		$result = $this->db->delete($this->table_generate_customer); 			
		}
		return $result;
	}
	
	public function delete_generate($id,$year_month_id){
		$this->db->where('id',$id);
		$result = $this->db->delete($this->table_generate); 
		if($result){
		$this->db->where('insert_month_id',$id_generate);
		$result = $this->db->delete($this->table_generate_customer); 			
		}		
		return $result;
	}	
	
  	/** In Function Status Update records for select table **/
	public function status_record($id,$balance,$customer_id){
		$sts = ($balance == 0 ? 1 : 0);
		$set_data = array(
						'balance' => 0,
						'date' => date('Y-m-d'),
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		if($result){
			$set_data1 = array(
				'paid_status' => $sts
			);
			$this->db->where('customer_id',$customer_id);
			$result = $this->db->update($this->table_customername, $set_data1); 
		}
		return $result;
	}

    public function get_name($id) {
		$this->db->select("*");
		$this->db->from($this->table_customername);
		//$this->db->where('customer_type','monthlycustomer');
		$this->db->or_where('customer_id',$id);
		$this->db->or_where('mobile1',$id);
		$this->db->or_where('mobile2',$id);
		$this->db->or_where('email_id',$id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
		
		/*$this->db->where('LastName', 'Svendson');
		$this->db->where('Age', 12);
		$this->db->where("(FirstName='Tove' OR FirstName='Ola' OR Gender='M' OR Country='India')", NULL, FALSE);
		$query = $this->db->get('Persons');
		return $query->result();
		*/
	}	

    public function get_years_exit($cust_id,$year) {
		$this->db->select("year");
		$this->db->from($this->table_name);
		//$this->db->where('year',$year);
		//$this->db->where('customer_id',$cust_id);
		//$this->db->where('status',1);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}	

	 public function get_months_exit($cust_id,$year,$month) {
		$this->db->select("month");
		$this->db->from($this->table_name);
		$this->db->where('month',$month);
		$this->db->where('year',$year);
		$this->db->where('customer_id',$cust_id);
		$this->db->where('status',1);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}	
	
	 public function get_months_generate_exit($year,$month) {
		$this->db->select("month");
		$this->db->from($this->table_generate);
		$this->db->where('month',$month);
		$this->db->where('year',$year);
		$this->db->where('status',1);
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}	
	
	public function get_monthly_billing($id){
		$sql = "SELECT ac.customer_id, ac.customer_type, ac.mobile1, ac.mobile2, ac.status,
		        ac.email_id,  ac.billingplans,tf.id as plan_id, tf.name as tfname, tf.days, tf.amount, ac.update_date_time
				FROM `tbl_addcustomer` ac 
				LEFT JOIN `tbl_feesplaning` tf ON ac.billingplans  = tf.id
				WHERE ac.customer_type =  'monthlycustomer'
				AND ac.customer_id = '$id' 
				OR ac.mobile1 = '$id' OR ac.mobile2 = '$id' 
				OR ac.email_id = '$id'
                ORDER BY ac.id DESC";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	
	public function get_addcustomer_add_all_records($id){
		$sql = "SELECT am.id, am.customer_id, ac.customer_id, ac.customer_type, ac.mobile1, ac.mobile2, ac.status,
		        ac.email_id, am.plan_name,am.balance,tf.id as plan_id, ac.billingplans,tf.id, tf.name as tfname, tf.days, tf.amount, 
				ac.update_date_time,am.startdate, am.enddate,
				tf.name as plan_namedu
				FROM  `tbl_monthlycustomer` am
				LEFT JOIN  `tbl_addcustomer` ac ON am.customer_id = ac.customer_id
				LEFT JOIN `tbl_feesplaning` tf ON ac.billingplans  = tf.id
				WHERE ac.customer_type =  'monthlycustomer'
				AND ac.customer_id = '$id' 
				OR ac.mobile1 = '$id' OR ac.mobile2 = '$id' 
				OR ac.email_id = '$id'
                ORDER BY am.id DESC LIMIT 0,5";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	
	public function get_addcustomer_monthy_specific_records($id){
		$sql = "SELECT am.id, am.customer_id, ac.customer_id, ac.customer_type, ac.mobile1, ac.mobile2, ac.status,
		        ac.email_id, am.plan_name,am.balance,tf.id as plan_id, ac.billingplans,tf.id, tf.name as tfname, tf.days, tf.amount, 
				ac.update_date_time,am.startdate, am.enddate,
				tf.name as plan_namedu
				FROM  `tbl_monthlycustomer` am
				LEFT JOIN  `tbl_addcustomer` ac ON am.customer_id = ac.customer_id
				LEFT JOIN `tbl_feesplaning` tf ON ac.billingplans  = tf.id
				WHERE ac.customer_type =  'monthlycustomer'
				AND ac.customer_id = '$id' 
				OR ac.mobile1 = '$id' OR ac.mobile2 = '$id' 
				OR ac.email_id = '$id'
                ORDER BY am.id ASC";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	
	function getReceiptData($invoice, $customer){
		
		$this->db->select('tbl_feesplaning.id as planid, tbl_feesplaning.name as plname, tbl_feesplaning.days as days, tbl_feesplaning.days as amount, tbl_monthlycustomer.date as tdate, tbl_monthlycustomer.*');				   
		$this->db->from('tbl_monthlycustomer');
		$this->db->join('tbl_feesplaning','tbl_monthlycustomer.plan_name = tbl_feesplaning.id');
		$this->db->where('customer_id',$customer);
		$this->db->where('tbl_monthlycustomer.id',$invoice);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	
	function getReceiptData_single($customer){
		$this->db->select('tbl_feesplaning.id as planid, tbl_feesplaning.name as plname, tbl_feesplaning.days as days, tbl_feesplaning.days as amount, tbl_monthlycustomer.date as tdate,tbl_monthlycustomer.id as montid, tbl_monthlycustomer.*');				   
		$this->db->from('tbl_monthlycustomer');
		$this->db->join('tbl_feesplaning','tbl_monthlycustomer.plan_name = tbl_feesplaning.id');
		$this->db->where('customer_id',$customer);
		$this->db->order_by('id', desc);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row_array();
		
	}
	
	function last_record_id(){
		$this->db->select('id as last_id');
		$this->db->from($this->table_customername);
		$this->db->order_by("id", "desc");
        $this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	function customer_deatils($customer){
		$this->db->select('*');
		$this->db->from($this->table_customername);
		$this->db->or_where('customer_id',$customer);
		$query = $this->db->get();
		$result = $query->row_array();
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
		$this->db->from($this->table_name);
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