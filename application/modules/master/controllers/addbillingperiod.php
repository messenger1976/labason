<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class addbillingperiod extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_billing_period';	  //*****  Table name  *****//
	public $addPage  = 'addbillingperiod_add';	     //*****  Add page    *****//
	public $editPage = 'addbillingperiod_edit';     //*****  Edit page   *****//
	public $listPage = 'addbillingperiod';		   //*****  View page   *****//
	public $excelfilename = 'billingperiod';		   //*****  View page   *****//
	public $addbillingperiod_search_ajax = 'addbillingperiod_ajax';
	public $addbillingperiod_import = 'addbillingperiod_import';

	public $listPage_redirect = '/master/addbillingperiod';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/addbillingperiod/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/addbillingperiod/edit/';  //*****  Redirect Edit  *****//
	public $importPage_redirect = '/master/addbillingperiod/import/';  //*****  Redirect import  *****//
	public function __construct() {
        parent::__construct();
		$this->load->library('session');
  		$this->load->model('addbillingperiod_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');	
		$this->load->model('addmetercustomerreading_model','meterreading_model');	
		$this->load->library('form_validation');
		$this->load->helper('common');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }

	public function index(){ 		 
		if($this->session->userdata('usertype') == 'subadmin'){
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
			//echo '<pre>';print_r($this->head['roleResponsible']);exit;
		}else{
			$this->head['roleResponsible'] = array();
		}
		if(	array_key_exists('addbillingperiod',$this->head['roleResponsible']) && $this->session->userdata('usertype') == 'subadmin' ){
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['addbillingperiod']);
		}
		//*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->my_model->get_zone_records();
		
		//if(!$this->session->userdata('current_billingperiod')){
		if(!isset($_SESSION['current_billingperiod'])){
			$data['current_billingperiod'] = $this->comm_model->get_billingperiod_record();
			//print_r($data['current_billingperiod']);
			//echo 'session: '.$this->session->userdata('current_billingperiod');
			//exit;
			//$this->session->set_userdata('current_billingperiod', $data['current_billingperiod'][0]['bp_period_month'].' '.$data['current_billingperiod'][0]['bp_period_year']);
			$_SESSION['current_billingperiod'] = $data['current_billingperiod'][0]['bp_period_month'].' '.$data['current_billingperiod'][0]['bp_period_year'];
		}else{
			//$sess_data = explode('-',$this->session->userdata('current_billingperiod'));
			//$sess_data = explode('-',$_SESSION['current_billingperiod']);
			
			//$data['current_billingperiod'] = $this->comm_model->get_billingperiod_record($sess_data[0].' '.$sess_data[1]);
			//$this->session->set_userdata('current_billingperiod', $data['current_billingperiod'][0]['bp_period_month'].' '.$data['current_billingperiod'][0]['bp_period_year']);
			//$_SESSION['current_billingperiod'] = $data['current_billingperiod'][0]['bp_period_month'].' '.$data['current_billingperiod'][0]['bp_period_year'];
			//echo 'session2: '.$sess_data;
			//exit;
		}
		//$data['current_billingperiod'] = $this->comm_model->get_billingperiod_record();
		//print_r($this->session->userdata('current_billingperiod'));
		//exit;
		$data['billingperiod'] = $this->my_model->get_month_billingperiod_records();	
		$data['zone_listing'] = $this->my_model->get_zone_listing_records();	
		//$header['host'] = $this->comm_model->get_single_record();				
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}
	
	/** Add Function **/
	public function add(){ 
		$data['msg'] ='';
	 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		if($this->input->post('add') != ''){
             //echo'<pre>';print_r($_POST);
				$result = $this->my_model->add_record();
				if($result){
					$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
					redirect($this->listPage_redirect);
				}else{
					$data['msg'] = "Not Inserted...";
				}
			}
		//$header['host'] = $this->comm_model->get_single_record();				
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$data['zone'] = $this->my_model->get_zone_records();
		$data['month'] = $this->my_model->get_month_records();	
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->addPage,$data);
	}
	/** Edit Function **/
	public function edit($id){
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		$data['msg'] ='';
		//echo'<pre>';print_r($data['record']);exit;
		if($this->input->post('edit') != ''){
			$result = $this->my_model->update_record($id);
	
			if($result){
				//echo'<pre>';print_r($result);exit;
				$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Updated...";
			}
		}
		//$header['host'] = $this->comm_model->get_single_record();				
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$data['zone'] = $this->my_model->get_zone_records();
		$data['month'] = $this->my_model->get_month_records();
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->addPage,$data);

	}
	/** View Function **/
	public function view($id){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		//print_r($data['record']);
		//$header['host'] = $this->comm_model->get_single_record();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);				
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->viewPage,$data);
	}
	public function Search($id){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		//print_r($data['record']);
		//$header['host'] = $this->comm_model->get_single_record();						
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->searchPage,$data);
	}
	public function getaddassetssearch(){		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('asset_type'));exit;
			if($this->input->post('asset_type') ==''){
				$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
				echo $selBox;
			}
			if($this->input->post('asset_type') !=''){
				
				$asset_type = $this->input->post('asset_type');
				
				
				$data['record'] = $this->my_model->get_addassets_records($asset_type);

			 
				$this->load->view($this->addassetsajax,$data);
			}		
	}	
	/** Status Change Function **/
	public function status($id,$status){
		$data['msg'] ='';
		//echo $status;
		 
		$statu = ($status == 1 ? 'Deactive' : 'Active');
		if($id){
			$result = $this->my_model->status_record($id,$status);
			if($result){
				$this->session->set_flashdata('msg_succ', 'insert Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = " Status Not Updated...";
			}
		}
	}
	/** Delete Function **/
      public function delete($id){ 
		$data['msg'] ='';
		if($id){
			$result = $this->my_model->delete_record($id);
			if($result){
				$this->session->set_flashdata('msg_succ', 'Deleted Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Deleted...";
			}
		}
	}
	/** Multiple Delete Function **/
	public function multi_delete(){
		$data['msg'] ='';
		if($this->input->post('delete_ids') != ''){
			$delete_ids = $this->input->post('delete_ids');
			for($i=0;$i<count($delete_ids);$i++){
				$result = $this->my_model->delete_record($delete_ids[$i]);
			}
			if($result){
				$this->session->set_flashdata('msg_succ', 'Deleted Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$this->session->set_flashdata('msg_succ', 'Not Deleted...');
				redirect($this->listPage_redirect);
			}
		}else{
			$this->session->set_flashdata('msg_succ', 'Select any Check Box...');
			redirect($this->listPage_redirect);
		}
	}
	
	/** Multiple close Function **/
	public function multi_close(){
		$data['msg'] ='';
		if($this->input->post('delete_ids') != ''){
			$delete_ids = $this->input->post('delete_ids');
			for($i=0;$i<count($delete_ids);$i++){
				$result = $this->my_model->status_record($delete_ids[$i],1);
			}
			if($result){
				echo 'Close Successfully...';
				//redirect($this->listPage_redirect);
			}else{
				echo 'Not Close...';
				//redirect($this->listPage_redirect);
			}
		}else{
			echo 'Select any Check Box...';
			//redirect($this->listPage_redirect);
		}
	}
	public function multi_open(){
		$data['msg'] ='';
		if($this->input->post('delete_ids') != ''){
			$delete_ids = $this->input->post('delete_ids');
			for($i=0;$i<count($delete_ids);$i++){
				$result = $this->my_model->status_record($delete_ids[$i],0);
			}
			if($result){
				echo 'Close Successfully...';
				//redirect($this->listPage_redirect);
			}else{
				echo 'Not Close...';
				//redirect($this->listPage_redirect);
			}
		}else{
			echo 'Select any Check Box...';
			//redirect($this->listPage_redirect);
		}
	}

	public function billingforwardposting(){
		$data['msg'] ='';

		
		ini_set('memory_limit', '512M'); // or '512M' if needed
		
		$billperiodforward = explode(' ',$this->input->post('billingperiodforward'));
		$currentbillingperiod = explode(' ',$this->input->post('currentbillingperiod'));
		$zone_listing = $this->input->post('zone_listing');
		$billperiodforward_month = $billperiodforward[0];
		$billperiodforward_year = $billperiodforward[1];
		//$billperiodforward_id = $billperiodforward[2];

		$currentbillingperiod_month = $currentbillingperiod[0];
		$currentbillingperiod_year = $currentbillingperiod[1];
		customerbillingperiod($billperiodforward_month,$billperiodforward_year,$currentbillingperiod_month,$currentbillingperiod_year,$zone_listing);

		/*if($this->input->post('delete_ids') != ''){
			$delete_ids = $this->input->post('delete_ids');
			for($i=0;$i<count($delete_ids);$i++){
				$result = $this->my_model->status_record($delete_ids[$i],0);
			}
			if($result){
				echo 'Close Successfully...';
				//redirect($this->listPage_redirect);
			}else{
				echo 'Not Close...';
				//redirect($this->listPage_redirect);
			}
		}else{
			echo 'Select any Check Box...';
			//redirect($this->listPage_redirect);
		}*/
	}

	public function addbillingperiod_search(){		//*****  Add Search records  *****//
		$data['msg'] ='';
		//echo '<pre>'; print_r($this->input->post('billingperiod'));exit;
		//if($this->input->post('billingperiod') ==''){
		//	$selBox ='<h6><span style="color:red">Dear Admin Please select billing period field</h6>' ;
		//	echo $selBox;
		//}else{
		
			$zone = $this->input->post('zone');
			$billingperiod = $this->input->post('billingperiod');
			
			$data['record'] = $this->my_model->get_addbillingperiod_records($zone,$billingperiod);
			$this->load->view($this->addbillingperiod_search_ajax,$data);
			
		//}		
	}	

	public function fileDownloadBillingPeriodMobileSearch($zone='',$billingmonth,$billingyear)
	{
		
		$this->load->database();



		//$CI = &get_instance();

		$this->db->select("
		tbl_addcustomer_reading.refno as billing_refno,
        tbl_addcustomer.customer_id as Account_Number,
        tbl_addcustomer.first_name,
        tbl_addcustomer.last_name, 
        tbl_addcustomer.address,
		tbl_zone.zone as zonename,
		tbl_addcustomer.account_type,
		tbl_classification.class_name as classification,
		tbl_addcustomer.meter_number,
		tbl_addcustomer.meter_brand,
        tbl_addcustomer_reading.previous_reading,
		tbl_addcustomer_reading.reading as current_reading,
		tbl_addcustomer_reading.arrears,
		tbl_addcustomer_reading.month as billing_month,
		tbl_addcustomer_reading.year as billing_year,
		tbl_addcustomer_reading.maintenance_fee
       
        ");
		$this->db->from("tbl_addcustomer_reading");
			
		
		$this->db->join("tbl_addmetercustomer", 'tbl_addcustomer_reading.customer_id=tbl_addmetercustomer.customer_id AND tbl_addcustomer_reading.month=tbl_addmetercustomer.month AND tbl_addcustomer_reading.year=tbl_addmetercustomer.year','left');

		$this->db->join('tbl_addcustomer', 'tbl_addcustomer_reading.customer_id=tbl_addcustomer.customer_id','left');
		$this->db->join('tbl_zone', 'tbl_addcustomer.zone=tbl_zone.id','left');
		$this->db->join('tbl_classification', 'tbl_addcustomer.classification=tbl_classification.class_id','left');
		
		if($billingmonth !='all' && $billingyear !='all'){
			$this->db->where("tbl_addcustomer_reading.month",$billingmonth);
			$this->db->where("tbl_addcustomer_reading.year",$billingyear);
		}
		
		
			
		
		
		$this->db->order_by('tbl_addcustomer.last_name','ASC');
		$this->db->order_by('tbl_addcustomer.first_name','ASC');
		$query = $this->db->get();
		




		
		
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->excelfilename.'-'.date("d-m-Y").'.csv');
	}

	public function sync_export($billingmonth,$billingyear)
	{
		// Specify the allowed origin(s). Use '*' to allow all origins (not recommended for production).
		header("Access-Control-Allow-Origin: *");

		// Specify the allowed HTTP methods (e.g., GET, POST, PUT, DELETE).
		header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

		// Specify the allowed headers that the client can send.
		header("Access-Control-Allow-Headers: Content-Type, Authorization");
		header('Content-Type: application/json');
		
		$this->load->database();



		//$CI = &get_instance();

		$this->db->select("
		tbl_addcustomer_reading.refno as billing_refno,
        tbl_addcustomer.customer_id as Account_Number,
        tbl_addcustomer.first_name,
        tbl_addcustomer.last_name, 
        tbl_addcustomer.address,
		tbl_zone.zone as zonename,
		tbl_addcustomer.account_type,
		tbl_classification.class_name as classification,
		tbl_addcustomer.meter_number,
		tbl_addcustomer.meter_brand,
        tbl_addcustomer_reading.previous_reading,
		tbl_addcustomer_reading.reading as current_reading,
		tbl_addcustomer_reading.arrears,
		tbl_addcustomer_reading.month as billing_month,
		tbl_addcustomer_reading.year as billing_year,
		tbl_addcustomer_reading.maintenance_fee
       
        ");
		$this->db->from("tbl_addcustomer_reading");
			
		
		$this->db->join("tbl_addmetercustomer", 'tbl_addcustomer_reading.customer_id=tbl_addmetercustomer.customer_id AND tbl_addcustomer_reading.month=tbl_addmetercustomer.month AND tbl_addcustomer_reading.year=tbl_addmetercustomer.year','left');

		$this->db->join('tbl_addcustomer', 'tbl_addcustomer_reading.customer_id=tbl_addcustomer.customer_id','left');
		$this->db->join('tbl_zone', 'tbl_addcustomer.zone=tbl_zone.id','left');
		$this->db->join('tbl_classification', 'tbl_addcustomer.classification=tbl_classification.class_id','left');
		
		if($billingmonth !='all' && $billingyear !='all'){
			$this->db->where("tbl_addcustomer_reading.month",$billingmonth);
			$this->db->where("tbl_addcustomer_reading.year",$billingyear);
		}
		//$this->db->where("tbl_addmetercustomer.status",1);
		
		
			
		
		
		$this->db->order_by('tbl_addcustomer.last_name','ASC');
		$this->db->order_by('tbl_addcustomer.first_name','ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		


		echo json_encode($result);
		//echo $result;
		exit;
		
		//$this->load->helper('csv');
		//query_to_csv($query, TRUE, $this->excelfilename.'-'.date("d-m-Y").'.csv');
	}

	public function sync_import($refno,$customer_id,$previous_reading,$current_reading,$billing_month,$billing_year,$reading_date){
		
		$readingData = array(
			'refno' => (int)$refno,
			'customer_id' => $customer_id,
			'previous_reading' => (int) $previous_reading,
			'current_reading' => (int) $current_reading,
			'billing_month' => (int) $billing_month,
			'billing_year' => (int) $billing_year,
			'reading_date' => $reading_date
		);
		$result_array = $this->meterreading_model->update_meterreading($readingData);
        echo $result_array;        
		exit;	
        
    
	}
	public function import(){
		$data['msg'] ='';
	 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();	
		$header['record_info'] = $this->top_model->get_last_login_details(1);

		$data['zone'] = $this->my_model->get_zone_records();
		$data['month'] = $this->my_model->get_month_records();			
			
			
		$this->load->view($this->headerPage,$header);

		$this->load->view($this->addbillingperiod_import,$data);
	}

	public function upload(){
		$data['msg'] ='';
		if (isset($_FILES["csv_file"]["name"])) {
            $file = $_FILES["csv_file"]["tmp_name"];
			$reading_date = $this->input->post('reading_date');
            if (($handle = fopen($file, "r")) !== FALSE) {
                fgetcsv($handle); // Skip header row

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $readingData = array(
                        'refno' => (int)$data[0],
                        'customer_id' => $data[1],
                        'previous_reading' => (int) $data[10],
						'current_reading' => (int) $data[11],
						'billing_month' => (int) $data[13],
						'billing_year' => (int) $data[14],
                        'reading_date' => $data[15],
                    );
                    $this->meterreading_model->update_meterreading($readingData);
                }
                fclose($handle);

                $this->session->set_flashdata('msg_succ', 'CSV file imported successfully!');
            } else {
                $this->session->set_flashdata('msg_succ', 'Error opening file!');
            }
        } else {
            $this->session->set_flashdata('msg_succ', 'Please select a file.');
        }

		//$redirect_uri = ADMIN_URL.'addbillingperiod/import';
		redirect($this->importPage_redirect);
		//header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
		exit;	
        
    
	}

	public function updated_headerbillingperiod($billing_period=''){
		if($billing_period!=''){
			$_SESSION['current_billingperiod']=urldecode($billing_period);
		}else{
			$_SESSION['current_billingperiod']=$this->input->post('billing_period');
		}
		

		/*if(isset($_SESSION['current_billingperiod'])){
			$data['current_billingperiod'] = $this->comm_model->get_billingperiod_record();
			
			$_SESSION['current_billingperiod'] = $data['current_billingperiod'][0]['bp_period_month'].' '.$data['current_billingperiod'][0]['bp_period_year'];
		}else{
			$sess_data = explode('-',$_SESSION['current_billingperiod']);
			
			$data['current_billingperiod'] = $this->comm_model->get_billingperiod_record($sess_data[0].' '.$sess_data[1]);
			
			$_SESSION['current_billingperiod'] = $data['current_billingperiod'][0]['bp_period_month'].' '.$data['current_billingperiod'][0]['bp_period_year'];
			
		}*/
		//redirect($this->listPage_redirect);
		
		echo 'success';
	}

	public function updated_headertransdate($trans_date=''){
		if($trans_date!=''){
			$_SESSION['trans_date']=urldecode($trans_date);
		}else{
			$_SESSION['trans_date']=$this->input->post('trans_date');
		}
		

		
		
		echo 'success';
	}
	public function display_session(){
		echo $_SESSION['current_billingperiod'].'<br/>';
		echo $_SESSION['trans_date'].'<br/>';
		exit;
	}
	
}
?>