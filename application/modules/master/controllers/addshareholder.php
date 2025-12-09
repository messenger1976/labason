<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class addshareholder extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_addshareholder';	  //*****  Table name  *****//
	public $addPage  = 'addshareholder_add';	     //*****  Add page    *****//
	public $editPage = 'addshareholder_edit';     //*****  Edit page   *****//
	public $listPage = 'addshareholder';		   //*****  View page   *****//
	public $viewPage ='addshareholder_view';
	public $searchPage ='addshareholder_search';
	public $addcustomerajax = 'addshareholder_search _ajax';
	public $month_customer_invoice = 'month_customer_invoice';
	public $printPage = 'addpaymentcustomer-print';
	public $addpages_ajax = 'addpaymentcustomer_add _ajax';
	public $share_listPage = 'share_profitloss_view';		   //*****  View page   *****//
	public $addpage_share = 'profit_addshareholder_add';
	public $share_search = 'addshareholder_search';
	public $share_search_ajax = 'addshareholder_search_ajax';
	
	
	public $listPage_redirect = '/master/addshareholder';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/addshareholder/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/addshareholder/edit/';  //*****  Redirect Edit  *****//
	public $profitpage_redirect = '/master/addshareholder/profitloss_add';
	
	
	public function __construct() {
        parent::__construct();
  		$this->load->model('addshareholder_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }
	public function index(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_all_records();	
		$data['amountrate'] = $this->my_model->get_amountrate();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}
	
	/** Add Function **/
	public function add(){ 
		$data['msg'] ='';
		 $header['roleResponsible'] = $this->top_model->get_responsibilities();
		//$data['addcustomer'] = $this->my_model->get_addcustomer();
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
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->addPage,$data);
	}
	
	public function get_custmer_all_data()
	{		//*****  Add Search records  *****//
			
			$id = $this->input->post('id');
			$data['record'] = $this->my_model->get_addcustomer_add_all_records($id);
			$this->load->view($this->addpages_ajax,$data);
			
	}
	
	public function edit($id){
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		$data['addcustomer'] = $this->my_model->get_addcustomer();
		$data['per_unit'] = $this->my_model->get_amountrate();
		$data['msg'] ='';
		//echo'<pre>';print_r($data['record']);exit;
		if($this->input->post('add') != ''){
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
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->editPage,$data);

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
	
	public function search(){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->share_search,$data);
	}
	
	public function getshareholderssearch(){		//*****  Add Search records  *****//
			$data['msg'] ='';
			if($this->input->post('fromdate') !='' && $this->input->post('todate') !=''){
				$fromdate = $this->input->post('fromdate');
				$todate = $this->input->post('todate');
				$data['record'] = $this->my_model->get_addshareholder_records($fromdate,$todate);
				$test = $this->my_model->get_addshareholder_records($fromdate,$todate);
				$this->load->view($this->share_search_ajax,$data);
			}
			
	}
	
	
	/** Status Change Function **/
	/*public function contactStatus($id,$status){
		$data['msg'] ='';
		//echo $status;
		$statu = ($status == 1 ? 'Status Change' : 'Status Change');
		if($id){
			$result = $this->my_model->status_record($id,$status);
			if($result){
				$this->session->set_flashdata('msg_succ', $statu.' Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = " Status Not Updated...";
			}
		}
	}	*/
	
	public function Status($id,$status){
		$data['msg'] ='';
		//echo $status;
		$statu = ($status == 1 ? 'Status Change' : 'Status Change');
		if($id){
			$result = $this->my_model->status_record($id,$status);
			if($result){
				$this->session->set_flashdata('msg_succ', $statu.' Successfully...');
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
	
	public function getoldmeter(){
		$id = $this->input->post('id');
		$getData=$this->my_model->select_getoldmeter($id);
		//echo'<pre>';print_r($getData);exit;
		if(!empty($getData)){
			if($getData[0]['status']=='1'){
		$selBox = '0'.'-'.'0';
			}else{
				$pay_amount = $getData[0]['total'];
			$selBox = $getData[0]['consumedunits'].'-'.$pay_amount;
            
			}
			
		}if(empty($getData)){
		
			$selBox = '0'.'-'.'0';
		}
		echo $selBox;
		
		
	}
	
	public function get_monthly_customer_invoice($id){ 
		$data['record'] = $this->my_model->get_single_record($id);
		$data['address'] = $this->my_model->get_address();
		//echo'<pre>';print_r($data['record']);exit; 
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->month_customer_invoice,$data);
	}	
	
	public function printInvoice($id){
		$data['msg'] ='';
		$data['record'] = $this->my_model->get_single_record($id);
		$data['address'] = $this->my_model->get_address();
		//echo'<pre>';print_r($data['record']);exit; 
		$this->load->view($this->printPage,$data);
	}	
	public function get_name(){ 
		 $cust_id=$this->input->post('id');
         $data['record'] = $this->my_model->get_name($cust_id);	
		$selBox ='
		     <div class="form-group">
			  <label class="col-sm-4 control-label no-padding-right" for="form-field-1">Name: </label>
			  <div class="col-sm-8">		
		    <input type="text" name="first_name" id="first_name" class="col-xs-10 col-sm-10" value="'.$data['record']['first_name'].''.$data['record']['middle_name'].''.$data['record']['last_name'].'"required  readonly >';
		
		echo $selBox;
	}
    
    function tchtemailcheck() {
		
       $id = $this->input->post('id');
       $getData = $this->my_model->select_getoldmeter($id);	
	   //print_r($getData);
	   if(!empty($getData)){
		   
		    if($getData[0]['addmetercustomer_status']=='1'){
		        //$selBox = '0'.'-'.'0';
				$pay_amount = $getData[0]['total'];
				$selBox = $getData[0]['consumedunits'].'-'.$getData[0]['balance'];
			}else{
				$pay_amount = $getData[0]['total'];
			    //$selBox = $getData[0]['consumedunits'].'-'.$pay_amount;
				$selBox = $getData[0]['consumedunits'].'-'.$getData[0]['balance'];
               
			}
			
		}if(empty($getData)){
		
			//$selBox = '0'.'-'.'0';
			$selBox = '0'.'-'.'0';
		}
		echo $selBox;
    }
	
	public function get_custmer_name(){ 
		 $cust_id=$this->input->post('id');
         $data['record'] = $this->my_model->get_name($cust_id);	
		$selBox ='
		    
			  <label class="col-md-4 control-label">Name : </label>
			  <div class="col-md-4">		
		       <input class="form-control" type="text" name="first_name" id="first_name" value="'.$data['record']['first_name'].'&nbsp;'.$data['record']['middle_name'].'&nbsp;'.$data['record']['last_name'].'"required  readonly >
		      </div>';
		echo $selBox;
	}
	
	public function get_calculation(){ 
	
		 $id = $this->input->post('id');
		 $oldmeter = $this->input->post('oldmeter');
		 
		 $newmeter = $id - $oldmeter;
		 
		 $tb_amount =  $this->db->get('tbl_amountrate')->row_array();
		 
		 extract($tb_amount);
		 
		 echo $selBox = $newmeter * $per_unit; 
		  
	}
	
	public function profitloss(){
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_profit_share();	
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->share_listPage,$data);
	}
	
	public function profitloss_add(){
		$data['msg'] ='';
		 $header['roleResponsible'] = $this->top_model->get_responsibilities();
		if($this->input->post('add') != ''){
           
				$result = $this->my_model->add_profitloss();
				if($result){
					$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
					redirect($this->profitpage_redirect);
				}else{
					$data['msg'] = "Not Inserted...";
				}
			}
		$data['get_shareholder'] = $this->my_model->get_shareholder();	
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->addpage_share,$data);
	}

}
?>