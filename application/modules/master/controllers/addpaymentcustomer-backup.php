<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class addpaymentcustomer extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_addmetercustomer';	  //*****  Table name  *****//
	public $addPage  = 'addpaymentcustomer_add';	     //*****  Add page    *****//
	public $editPage = 'addpaymentcustomer_edit';     //*****  Edit page   *****//
	public $listPage = 'addpaymentcustomer';		   //*****  View page   *****//
	public $viewPage ='addpaymentcustomer_view';
	public $searchPage ='addmetercustomer_search';
	public $addcustomerajax = 'addmetercustomer_search _ajax';
	public $month_customer_invoice = 'month_customer_invoice';
	public $printPage = 'addpaymentcustomer-print';
	public $addpages_ajax = 'addpaymentcustomer_add _ajax';
	
	public $listPage_redirect = '/master/addpaymentcustomer';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/addpaymentcustomer/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/addpaymentcustomer/edit/';  //*****  Redirect Edit  *****//
	
	
	public function __construct() {
        parent::__construct();
  		$this->load->model('addpaymentcustomer_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->load->library('Pdf');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
		$this->load->model('addcustomer_model','customer_model');
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
	public function add(){ //print_r($this->input->post);exit;
		$data['msg'] ='';
		
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		
		$data['addcustomer'] = $this->my_model->get_addcustomer();
		$data['record'] = $this->customer_model->get_all_records();

		if($this->input->post('add') != ''){ 
			//echo 'Hello';
		//print_r($_POST);
		//exit;
				$result = $this->my_model->add_record();
				if($result){
					$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
					redirect($this->listPage_redirect);
				}else{
					$data['msg'] = "Not Inserted...";
					redirect($this->listPage_redirect);
				}

			}
			
		
		if($this->input->post('total_add') != ''){
			    $insert_ids = $this->input->post('checkbox');
				//echo 'Hello';
				//print_r($_POST);
				for($i=0;$i<count($insert_ids);$i++){
					
					$result = $this->my_model->add_record_multiple($insert_ids[$i]);
				}
				
				//exit;
				//$result = $this->my_model->add_transaction();
				if($result){
					$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
					redirect($this->listPage_redirect);
				}else{
					$data['msg'] = "Not Inserted...";
					redirect($this->listPage_redirect);
				}
			}
		$data['ledger'] = $this->my_model->fetchLedger();            // fetch ledgers	
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->addPage,$data);
	}
	
	public function get_custmer_all_data()
	{		//*****  Add Search records  *****//
			
			$id = $this->input->post('id');
			$this->load->model('addpaymentcustomer_model','my_model');
			$data['reading'] = $this->my_model->get_addcustomer_add_all_records($id);
			$data['record'] = $this->my_model->get_meter_reading_all_records($id);
			$data['collectinfo'] =  $this->my_model->collectinfo($id);
			$data['month_collectinfo'] = $this->my_model->month_collectinfo($id);
			$data['second_higest_radi'] = $this->my_model->get_second_meter($id);
				
			$data['amountrate'] = $this->my_model->get_unitvalue();
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
	public function addaccountmetercustomer($id)
	{
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['id'] = $id;
		$this->load->view($this->headerPage,$header);
		$this->load->view('addaccountmetercustomer',$data);
	}
	public function saveaccountmetercustomer()
	{
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['customertype']= "meter";
		$data['customerid']	 = $this->input->post('id');
		$data['accountno']	 = $this->input->post('accountno');
		$data['accountname'] = $this->input->post('accountname');
		$data['amount'] 	 = $this->input->post('amount');
		$result = $this->my_model->addaccount($data);
		$this->load->view($this->headerPage,$header);
		$this->load->view('addaccountmetercustomer',$data);
	}
	public function addaccountmonthlycustomer($id)
	{
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['id'] = $id;
		$this->load->view($this->headerPage,$header);
		$this->load->view('addaccountmonthlycustomer',$data);
	}
	public function saveaccountmonthlycustomer()
	{
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['customertype']	 = "monthly";
		$data['customerid']	 = $this->input->post('id');
		$data['accountno']	 = $this->input->post('accountno');
		$data['accountname'] = $this->input->post('accountname');
		$data['amount'] 	 = $this->input->post('amount');
		$result = $this->my_model->addaccount($data);
		$this->load->view($this->headerPage,$header);
		$this->load->view('addaccountmonthlycustomer',$data);
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
	
	/*public function Status($id,$status,$customer_id){
		$data['msg'] ='';
		//echo $status;
		$statu = ($status == 1 ? 'Status Change' : 'Status Change');
		if($id){
			$result = $this->my_model->status_record($id,$status,$customer_id);
			if($result){
				$this->session->set_flashdata('msg_succ', $statu.' Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = " Status Not Updated...";
			}
		}
	}	*/
	
	public function Status($id,$balance,$customer_id){
		$data['msg'] ='';
		//print_r($balance);
		$statu = ($balance == 0 ? 'Status Change' : 'Status Change');
		if($id){
			$result = $this->my_model->status_record($id,$balance,$customer_id);
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
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
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
		$fulladdress = $data['record']['address'].' '.$data['record']['city'].' '.$data['record']['state'];
		$selBox ='
		      <input type="hidden" name="cust_id" id="cust_id" value="'.$data['record']['customer_id'].'"/>
		      <div class="form-group">
				  <label class="col-sm-1 control-label" style="width: 12%;">Name : </label>
				  <div class="col-sm-3">		
				     <input class="form-control" type="text" name="first_name" id="first_name" value="'.$data['record']['first_name'].'&nbsp;'.$data['record']['middle_name'].'&nbsp;'.$data['record']['last_name'].'"required  readonly >
				     <input class="form-control" type="hidden" name="tab_id" id="tab_id" value="'.$data['record']['id'].'"required  readonly >
				  </div>
				  <label class="col-sm-1 control-label" style="width: 12%;">Meter Brand : </label>
				  <div class="col-sm-2">		
				     <input class="form-control" type="text" name="meter_number" id="meter_number" value="'.$data['record']['meter_number'].'"required  readonly >
				  </div>
				  <label class="col-sm-1 control-label" style="width: 12%;">Meter Brand : </label>
				  <div class="col-sm-2">		
				     <input class="form-control" type="text" name="meter_brand" id="meter_brand" value="'.$data['record']['meter_brand'].'"required  readonly >
				  </div>
			  </div>
		     <div class="form-group">
				  <label class="col-sm-1 control-label" style="width: 12%;">Customer ID : </label>
				  <div class="col-sm-3">		
				     <input class="form-control" type="text" name="customer_id_display" id="customer_id_display" value="'.$data['record']['customer_id'].'"required  readonly >
				  </div>
		          <label class="col-sm-1 control-label" style="width: 12%;">Address : </label>
				  <div class="col-sm-5">		
				     <input class="form-control" type="text" name="address" id="address" value="'.$fulladdress.'"required  readonly >
				  </div>
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
	public function monthlyreceipt($customer,$month,$year) {
		
	//print_r($customer);	print_r($month);	print_r($year);	
    $receiptdata = $this->my_model->getReceiptData($customer, $month, $year);
	$getaddress = $this->my_model->get_address();
	$customerdetials = $this->my_model->customer_deatils($customer);
	$this->load->model('addcustomer_model','my_model123');
	$record = $this->my_model123->get_adminrecord();
	extract($record);
   // print_r($receiptdata);
	//print_r($getaddress);
    extract($receiptdata);
    extract($getaddress);
	extract($customerdetials);
	$todate = date('d-m-Y');
	$datefor = date('d-m-Y', strtotime($tdate));
    //============================================================+
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);


    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
    $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------    
    // set default font subsetting mode
    $pdf->setFontSubsetting(false);

    // Set font
    // dejavusans is a UTF-8 Unicode font, if you only need to
    // print standard ASCII chars, you can use core fonts like
    // helvetica or times to reduce file size.
    $pdf->SetFont('helvetica', '', 9, '', true);
	//$pdf->SetBackColor(255,255,255);

    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();

    /// create some HTML content

$html = <<<EOD
	

	<div style="position: relative; display: inline-block;">
        <img src="http://localhost/waterbilling/images/orwaterbilling.jpg" alt="Interactive Image" style="display: block; max-width: 100%; height: auto;">
        
        <!-- Text overlays with inline styles -->
        <div style="position: absolute; top: 5%; left:5%; color: black; font-size: 16px; font-weight: bold; padding: 5px;">
            Region 1
        </div>
        <div style="position: absolute; top: 50%; left: 70%; color: white; font-size: 16px; font-weight: bold; background-color: rgba(0, 0, 0, 0.5); padding: 5px; border-radius: 4px;">
            Region 2
        </div>
        <div style="position: absolute; bottom: 10%; right: 10%; color: white; font-size: 16px; font-weight: bold; background-color: rgba(0, 0, 0, 0.5); padding: 5px; border-radius: 4px;">
            Region 3
        </div>
    </div>

	<!--<table border="1" cellspacing="5" cellpadding="15" width="100%">
	<tr>
	<td>
	<div class="invoice-inner" style="margin: 0 15px; padding: 0px 0;">
		 <table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td align="center" valign="top">
				
						<div class="logoDiv">
							<!--<img id="logo" src="http://dayaxpower.com/Waterbilling/images/logo/$file" height="80">-->
							<h1>$name</h1>
							<h3>$contact1 / $email</h3>
							<h3>$address1</h3>
							<h2>Invoice Receipt</h2>
						 <p>(for Meter Customer)</p>
						</div>
						
				</td>
			</tr>
			
		</table>	
		<div class="invoice-address" style="border-top: 2px double #888;margin: 20px 0; padding-top: 25px;">
		    <br/>
		    <table border="1" cellspacing="0" cellpadding="15" width="100%">
			  <tbody>	
					<tr>
						<td align="left" valign="top"><span style="font-size:11px;">Customer-Id :</span><span style="font-size:11px; font-weight: bold;"> $customer_id</span></td>
						<td valign="top"><span style="font-size:11px;">Invoice :</span> <span style="font-size:11px; font-weight: bold;">#$invoice_ids</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Bill To :</span><span style="font-size:11px; font-weight: bold;"> $last_name, $first_name $middle_name</span></td>
						<td valign="top"><span style="font-size:11px;">Date :</span><span style="font-size:11px; font-weight: bold;"> $datefor</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Address :</span> <span style="font-size:11px; font-weight: bold;">$address $city <br/>$state</span> </td>
						<td valign="top"><span style="font-size:11px;">Unit Rate:</span><span style="font-size:11px; font-weight: bold;"> 1</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Month : </span> <span style="font-size:11px; font-weight: bold;">$monthname $year</span></td>
						<td valign="top"><span style="font-size:11px;">Reading :</span> <span style="font-size:11px; font-weight: bold;">$aftermeter</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Consumed Unit :</span> <span style="font-size:11px; font-weight: bold;">$consumedunits</span></td>
						<td valign="top"><span style="font-size:11px;">Price :</span> <span style="font-size:11px; font-weight: bold;">$amount</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Balance Amount :</span> <span style="font-size:11px; font-weight: bold;">$balance</span></td>
						<td valign="top"><span style="font-size:11px;">Pay :</span><span style="font-size:11px; font-weight: bold;"> $pay_amount  $currency</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Status :</span> <span style="font-size:11px; font-weight: bold;">Paid</span></td>
					</tr>
				</tbody>	
            </table>
		</div>
		<br/><br/><br/><br/>
		<table>
		  <tr>
		     <td align="left"><span style="padding-left:20px;">Date : $todate</span></td>
			 <td align="right"><span style="padding-right:50px;">Signature</span></td>
		  </tr>
		</table>
	 </div>	
	</td>
	 </tr>	
	</table>	-->
	
    

EOD;
	echo $html;
// output the HTML content
    //$pdf->writeHTML($html, true, false, true, false, '');

    // ---------------------------------------------------------    
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    //$pdf->Output($customer . '.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+
}

    //public function monthlyreceipt_single($customer,$month,$year) {
	public function monthlyreceipt_single($customer,$month,$year,$name,$current_reading,$oldmeter,$unit,$pay_amount,$invoice_ids,$address,$currency='PHP') {	
	//print_r($customer);	print_r($month);	print_r($year);	
	//exit;
	$consume = $current_reading - $oldmeter;
	$name = urldecode($name);
	$address = urldecode($address);
	$amo = $unit;
	$bal = $pay_amount;
    $receiptdata = $this->my_model->getReceiptData($customer, $month, $year);
	$getaddress = $this->my_model->get_address();
	$customerdetials = $this->my_model->customer_deatils($customer);
	$intint = 1;
    //print_r($receiptdata);
	//print_r($customerdetials);
	//exit;
	$record = $this->customer_model->get_adminrecord();
	//print_r($record);
	//exit;
	extract($record);
    extract($receiptdata);
    extract($getaddress);
	extract($customerdetials);
	$todate = date('d-m-Y');
	$datefor = date('d-m-Y', strtotime($tdate));
    //============================================================+
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);


    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
    $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------    
    // set default font subsetting mode
    $pdf->setFontSubsetting(false);

    // Set font
    // dejavusans is a UTF-8 Unicode font, if you only need to
    // print standard ASCII chars, you can use core fonts like
    // helvetica or times to reduce file size.
    $pdf->SetFont('helvetica', '', 9, '', true);
	//$pdf->SetBackColor(255,255,255);

    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();

    /// create some HTML content

$html = <<<EOD
	<table border="1" cellspacing="5" cellpadding="15" width="100%">
	<tr>
	<td>
	<div class="invoice-inner" style="margin: 0 15px; padding: 0px 0; color:#000;">
		 <table border="0" cellspacing="0" cellpadding="0" width="100%" style="width: 100%; height: 300px; background-image: url('images/orwaterbilling.jpg'); background-size: cover; background-repeat: no-repeat; background-position: center;">
			<tr>
				<td align="center" valign="top">
				
						<div class="logoDiv">
							<!--<img id="logo" src="http://dayaxpower.com/Waterbilling/images/logo/$file" height="80">-->
							<h1>$name</h1>
							<h3>$contact1 / $email</h3>
							<h3>$address1</h3>
							<h2>Service Invoice</h2>
						 <p>(for Meter Customer)</p>
						</div>
						
				</td>
			</tr>
			
		</table>	
		<div class="invoice-address" style="border-top: 2px double #888;margin: 20px 0; padding-top: 25px;">
		    <br/>
		    <table border="1" cellspacing="0" cellpadding="15" width="100%">
			  <tbody>	
					<tr>
						<td align="left" valign="top"><span style="font-size:11px;">Customer-Id :</span><span style="font-size:11px; font-weight: bold;"> $customer</span></td>
						<td valign="top"><span style="font-size:11px;">Invoice :</span> <span style="font-size:11px; font-weight: bold;">#$invoice_ids</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Bill To :</span><span style="font-size:11px; font-weight: bold;"> $first_name, $last_name $middle_name</span></td>
						<td valign="top"><span style="font-size:11px;">Date :</span><span style="font-size:11px; font-weight: bold;"> $todate</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Address :</span> <span style="font-size:11px; font-weight: bold;">$address $city <br/>$state</span> </td>
						<td valign="top"><span style="font-size:11px;">Unit Rate:</span><span style="font-size:11px; font-weight: bold;"> 1</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Month : </span> <span style="font-size:11px; font-weight: bold;">$monthname $year</span></td>
						<td valign="top"><span style="font-size:11px;">Reading :</span> <span style="font-size:11px; font-weight: bold;">$current_reading</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Consumed Unit :</span> <span style="font-size:11px; font-weight: bold;">$consume</span></td>
						<td valign="top"><span style="font-size:11px;">Price :</span> <span style="font-size:11px; font-weight: bold;">$amo</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Balance Amount :</span> <span style="font-size:11px; font-weight: bold;">$bal</span></td>
						<td valign="top"><span style="font-size:11px;">Pay :</span><span style="font-size:11px; font-weight: bold;"> $pay_amount  $currency</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Status :</span> <span style="font-size:11px; font-weight: bold;">Paid</span></td>
					</tr>
				</tbody>	
            </table>
		</div>
		<br/><br/><br/><br/>
		<table>
		  <tr>
		     <td align="left"><span style="padding-left:20px;">Date : $todate</span></td>
			 <td align="right"><span style="padding-right:50px;">Signature</span></td>
		  </tr>
		</table>
	 </div>	
	</td>
	 </tr>	
	</table>	
	
    

EOD;

// output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');

    // ---------------------------------------------------------    
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $pdf->Output($customer . '.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+
}


}
?>