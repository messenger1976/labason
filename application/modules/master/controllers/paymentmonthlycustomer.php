<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class paymentmonthlycustomer extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_monthlycustomer';	  //*****  Table name  *****//
	public $addPage  = 'paymentmonthlycustomer_add';	     //*****  Add page    *****//
	public $generatePage  = 'paymentmonthlycustomer_generate';	     //*****  Add page    *****//
	public $editPage = 'paymentmonthlycustomer_edit';     //*****  Edit page   *****//
	public $listPage = 'paymentmonthlycustomer';		   //*****  View page   *****//
	public $viewPage ='paymentmonthlycustomer_view';
	public $searchPage ='paymentmonthlycustomer_search';
	public $addcustomerajax = 'paymentmonthlycustomer_search _ajax';
	public $paymentmonthlycustomer_invoice = 'paymentmonthlycustomer_invoice';
	public $printPage = 'paymentmonthlycustomer-print';
	public $addpages_ajax = 'paymentmonthlycustomer_add _ajax';
	
	
	
	public $listPage_redirect = '/master/paymentmonthlycustomer';		  //*****  Redirect View  *****//
	public $generatePage_redirect = '/master/paymentmonthlycustomer/generate';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/paymentmonthlycustomer/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/paymentmonthlycustomer/edit/';  //*****  Redirect Edit  *****//
	public function __construct() {
        parent::__construct();
  		$this->load->model('paymentmonthlycustomer_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->load->library('Pdf');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }
	public function index(){ 		 //*****  View Loading  *****//
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_all_records();
		 //$data['billing'] = $this->my_model->get_billingplans();
		//echo'<pre>';print_r($data['record'] );exit;
        $data['addcustomer'] = $this->my_model->get_addcustomer();
	    //$data['feesplaning'] = $this->my_model->get_feesplaning();
		
		//$header['host'] = $this->comm_model->get_single_record();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}
	
	/** Add Function **/
	public function add(){ 
		$data['msg'] ='';
		 $data['addcustomer'] = $this->my_model->get_addcustomer();
		 $data['billing'] = $this->my_model->get_billingplans();
		 //echo'<pre>';print_r( $data['addcustomer'] );exit;
		  //$data['feesplaning'] = $this->my_model->get_feesplaning();
		if($this->input->post('add') != ''){
            //echo'<pre>';print_r($_POST);
				//$result = $this->my_model->add_record();
				$result = $this->my_model->add_reocrd_d();
				if($result){
					$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
					redirect($this->listPage_redirect);
				}else{
					$data['msg'] = "Not Inserted...";
				}
			}
		//$header['host'] = $this->comm_model->get_single_record();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$data['ledger'] = $this->my_model->fetchLedger();            // fetch ledgers
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->addPage,$data);
	}
	
	public function get_custmer_all_data()
	{		//*****  Add Search records  *****//
			
			$id = $this->input->post('id');
			$this->load->model('paymentmonthlycustomer_model','my_model');
			$data['monthly'] =  $this->my_model->get_addcustomer_monthy_specific_records($id);
			$data['reading'] = $this->my_model->get_monthly_billing($id) ; 
			$data['record'] = $this->my_model->get_addcustomer_add_all_records($id);
			$this->load->view($this->addpages_ajax,$data);
			
	}
	
	public function generate(){ 
		$data['msg'] ='';
		 $data['generate'] = $this->my_model->get_generate();
		if($this->input->post('add') != ''){
				$result = $this->my_model->generate_record();
				if($result){
					$this->session->set_flashdata('msg_succ', 'Bill Generated Successfully...');
					redirect($this->generatePage_redirect);
				}else{
					$data['msg'] = "Not Inserted...";
				}
			}
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->generatePage,$data);
	}
	
	public function edit($id){
		$data['record'] = $this->my_model->get_single_record($id);
		$data['addcustomer'] = $this->my_model->get_addcustomer();
		 $data['billing'] = $this->my_model->get_billingplans();
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
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$data['ledger'] = $this->my_model->fetchLedger();            // fetch ledgers
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->editPage,$data);

	}
	
	/** View Function **/
	public function view($id){ 
		$data['record'] = $this->my_model->get_single_record($id);
		//print_r($data['record']);
		//$header['host'] = $this->comm_model->get_single_record();						
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->viewPage,$data);
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
	
	public function Status($id,$balance,$customer_id){
		$data['msg'] ='';
		//echo $status;
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
	public function delete($id,$id_generate){ 
		$data['msg'] ='';
		if($id){
			$result = $this->my_model->delete_record($id,$id_generate);
			if($result){
			
				$this->session->set_flashdata('msg_succ', 'Deleted Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Deleted...";
			}
		}
	}
	
	public function delete_generate($id){ 
		$data['msg'] ='';
		if($id){
			$result = $this->my_model->delete_generate($id);
			if($result){
			
				$this->session->set_flashdata('msg_succ', 'Deleted Successfully...');
				redirect($this->generatePage_redirect);
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
	
	public function getbalance(){
		$id = $this->input->post('id');
		
		$getData=$this->my_model->select_getbalance($id);
		//echo'<pre>';print_r($getData);exit;
	//if($getData['total'] != 0){
		$selBox.='
		
		                           	<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="form-field-1">Plan-Name: </label>
										<div class="col-sm-8">
							 				<input type="text" id="plan_name" name="plan_name" class="col-xs-10 col-sm-10" value="'.$getData['billingname'].'" required/>

										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Amount : </label>
										<div class="col-sm-8">
											<input type="text" id="amount" name="amount" class="col-xs-10 col-sm-10" value="'.$getData['count_generate']*$getData['amount'].'" required readonly />

										</div>
									</div>	
									
									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Pay Amount : </label>
										<div class="col-sm-8">
											<input type="text" id="paidamount" name="paidamount" class="col-xs-10 col-sm-10" value="" required />

										</div>
									</div>										
									
										
									



	';
	/*}
	if($getData['total'] == 0){
		$selBox.='
		
		                           	<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="form-field-1">Plan-Name: </label>
										<div class="col-sm-8">
											<input type="text" id="plan_name" name="plan_name" class="col-xs-10 col-sm-10" value="'.$getData['billingname'].'" required/>

										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Amount : </label>
										<div class="col-sm-8">
											<input type="text" id="amount" name="amount" class="col-xs-10 col-sm-10" value="'.$getData['count_generate']*$getData['amount'].'" required readonly />

										</div>
									</div>	
									
									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Pay Amount : </label>
										<div class="col-sm-8">
											<input type="text" id="paidamount" name="paidamount" class="col-xs-10 col-sm-10" value="0" required  readonly />

										</div>
									</div>										
									
										
									



	';}	*/
			  	echo $selBox;
	}



    public function get_customer_invoice($id)
	{ 
		$data['record'] = $this->my_model->get_single_record($id);
		$data['address'] = $this->my_model->get_address();
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		//echo'<pre>';print_r($data['record']);exit; 
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->paymentmonthlycustomer_invoice,$data);
	}	
	
	public function printInvoice($id)
	{
		$data['msg'] ='';
		$data['record'] = $this->my_model->get_single_record($id);
		$data['address'] = $this->my_model->get_address();
		//echo'<pre>';print_r($data['record']);exit;
		$this->load->view($this->printPage,$data);
	}	

	public function get_years(){ 
		$cust_id=$this->input->post('id');		
		$selBox ='
		<div class="form-group">
			<label class="col-sm-4 control-label no-padding-right" for="form-field-1">Year: </label>
			<div class="col-sm-8">		
		<select name="years" id="years" class="col-xs-10 col-sm-10" required onchange="getcustomermonth(this.value)">
		<option value="">--Select--</option>
		<option value="2016**'.$cust_id.'">2016</option> 
		<option value="2017**'.$cust_id.'">2017</option>
		<option value="2018**'.$cust_id.'">2018</option>
		<option value="2019**'.$cust_id.'">2019</option>
		<option value="2020**'.$cust_id.'">2020</option>
		<option value="2021**'.$cust_id.'">2021</option>
		<option value="2022**'.$cust_id.'">2022</option>
		<option value="2023**'.$cust_id.'">2023</option>
		<option value="2024**'.$cust_id.'">2024</option>
		<option value="2025**'.$cust_id.'">2025</option>
		<option value="2026**'.$cust_id.'">2026</option>
		<option value="2027**'.$cust_id.'">2027</option>
		<option value="2028**'.$cust_id.'">2028</option>
		<option value="2029**'.$cust_id.'">2029</option>
		<option value="2030**'.$cust_id.'">2030</option>
		</select>
					</div>
		</div>
		';
		echo $selBox;
	}	
	
	public function get_months(){ 
		$cust_id=$this->input->post('id');
		$year=$this->input->post('year');
		$January = $this->my_model->get_months_exit($cust_id,$year,'January');
		$February = $this->my_model->get_months_exit($cust_id,$year,'February');
		$March = $this->my_model->get_months_exit($cust_id,$year,'March');
		$April = $this->my_model->get_months_exit($cust_id,$year,'April');
		$May = $this->my_model->get_months_exit($cust_id,$year,'May');
		$June = $this->my_model->get_months_exit($cust_id,$year,'June');
		$July = $this->my_model->get_months_exit($cust_id,$year,'July');
		$August = $this->my_model->get_months_exit($cust_id,$year,'August');
		$September = $this->my_model->get_months_exit($cust_id,$year,'September');
		$October = $this->my_model->get_months_exit($cust_id,$year,'October');
		$November = $this->my_model->get_months_exit($cust_id,$year,'November');
		$December = $this->my_model->get_months_exit($cust_id,$year,'December');
		$selBox ='
		<select name="month" id="month" class="col-xs-10 col-sm-10" required onchange="getcustomerbalanceamount(this.value)">';
		echo '<option value="">--Select--</option>';
		if(empty($January)){echo '<option value="January**'.$cust_id.'">January</option>';}
		if(empty($February)){echo '<option value="February**'.$cust_id.'">February</option>';}
		if(empty($March)){echo '<option value="March**'.$cust_id.'">March</option>';}
		if(empty($April)){echo '<option value="April**'.$cust_id.'">April</option>';}
		if(empty($May)){echo '<option value="May**'.$cust_id.'">May</option>';}
		if(empty($June)){echo '<option value="June**'.$cust_id.'">June</option>';}
		if(empty($July)){echo '<option value="July**'.$cust_id.'">July</option>';}
		if(empty($August)){echo '<option value="August**'.$cust_id.'">August</option>';}
		if(empty($September)){echo '<option value="September**'.$cust_id.'">September</option>';}
		if(empty($October)){echo '<option value="October**'.$cust_id.'">October</option>';}
		if(empty($November)){echo '<option value="November**'.$cust_id.'">November</option>';}
		if(empty($December)){echo '<option value="December**'.$cust_id.'">December</option>';}
		$selBox ='</select>';
		echo $selBox;
	}	
	
	public function get_months_generate(){ 
		$year=$this->input->post('year');
		$January = $this->my_model->get_months_generate_exit($year,'January');
		$February = $this->my_model->get_months_generate_exit($year,'February');
		$March = $this->my_model->get_months_generate_exit($year,'March');
		$April = $this->my_model->get_months_generate_exit($year,'April');
		$May = $this->my_model->get_months_generate_exit($year,'May');
		$June = $this->my_model->get_months_generate_exit($year,'June');
		$July = $this->my_model->get_months_generate_exit($year,'July');
		$August = $this->my_model->get_months_generate_exit($year,'August');
		$September = $this->my_model->get_months_generate_exit($year,'September');
		$October = $this->my_model->get_months_generate_exit($year,'October');
		$November = $this->my_model->get_months_generate_exit($year,'November');
		$December = $this->my_model->get_months_generate_exit($year,'December');
		$selBox ='
		<select name="month" id="month" class="col-xs-10 col-sm-10" required >';
		echo '<option value="">--Select--</option>';
		if(empty($January)){echo '<option value="January">January</option>';}
		if(empty($February)){echo '<option value="February">February</option>';}
		if(empty($March)){echo '<option value="March">March</option>';}
		if(empty($April)){echo '<option value="April">April</option>';}
		if(empty($May)){echo '<option value="May">May</option>';}
		if(empty($June)){echo '<option value="June">June</option>';}
		if(empty($July)){echo '<option value="July">July</option>';}
		if(empty($August)){echo '<option value="August">August</option>';}
		if(empty($September)){echo '<option value="September">September</option>';}
		if(empty($October)){echo '<option value="October">October</option>';}
		if(empty($November)){echo '<option value="November">November</option>';}
		if(empty($December)){echo '<option value="December">December</option>';}
		$selBox ='</select>';
		echo $selBox;
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
	
	public function get_custmer_name($id){
		 $cust_id=$this->input->post('id');
         $data['record'] = $this->my_model->get_name($cust_id);	
		 $selBox ='
		      <div class="form-group">
				  <label class="col-sm-2 control-label">Name : </label>
				  <div class="col-sm-3">		
				     <input class="form-control" type="text" name="first_name" id="first_name" value="'.$data['record']['first_name'].'&nbsp;'.$data['record']['middle_name'].'&nbsp;'.$data['record']['last_name'].'"required  readonly >
				     <input class="form-control" type="hidden" name="tab_id" id="tab_id" value="'.$data['record']['id'].'"required  readonly >
				  </div>
				  <label class="col-sm-2 control-label">Email : </label>
				  <div class="col-sm-3">		
				     <input class="form-control" type="text" name="email" id="email" value="'.$data['record']['email_id'].'"required  readonly >
				  </div>
			  </div>
			  
		      <div class="form-group">
				  <label class="col-sm-2 control-label">Mobile : </label>
				  <div class="col-sm-3">		
				     <input class="form-control" type="text" name="mobile1" id="mobile1" value="'.$data['record']['mobile1'].'"required  readonly >
				  </div>
				  <label class="col-sm-2 control-label">Address : </label>
				  <div class="col-sm-3">		
				     <input class="form-control" type="text" name="address" id="address" value="'.$data['record']['address'].'"required  readonly >
				  </div>
			  </div>';
		echo $selBox;
		
	}
	
	public function monthlyreceipt($invoice, $customer) {
		
	//print_r($customer);	print_r($month);	print_r($year);	

    $receiptdata = $this->my_model->getReceiptData($invoice,$customer);
	$getaddress = $this->my_model->get_address();
	$customerdetials = $this->my_model->customer_deatils($customer);
	$this->load->model('addcustomer_model','my_model123');
	$record = $this->my_model123->get_adminrecord();
	extract($record);
    //print_r($receiptdata);
	//print_r($getaddress);
    extract($receiptdata);
    extract($getaddress);
	extract($customerdetials);
	$startdatec = date('d M Y', strtotime($startdate));
	$enddatec = date('d M Y', strtotime($enddate));
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
		 <table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td align="center" valign="top">
				
						<div class="logoDiv">
							<img id="logo" src="http://dayaxpower.com/Waterbilling/images/logo/$file" height="80">
							<h1>$name</h1>
<h3>$contact1 / $email</h3>
<h3>$address1</h3>
<h2>Invoice Receipt</h2>
						 <p>(for Monthly Customer)</p>
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
						<td valign="top"><span style="font-size:11px;">Invoice_Id :</span> <span style="font-size:11px; font-weight: bold;">#$invoice_id</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Bill_To :</span><span style="font-size:11px; font-weight: bold;"> $first_name $middle_name $last_name</span></td>
						<td valign="top"><span style="font-size:11px;">Date :</span><span style="font-size:11px; font-weight: bold;"> $datefor</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Address :</span> <span style="font-size:11px; font-weight: bold;">$address $city <br/>$state</span> </td>
						<td valign="top"><span style="font-size:11px;">Start Date :</span><span style="font-size:11px; font-weight: bold;"> $startdatec</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">End Date :</span> <span style="font-size:11px; font-weight: bold;">$enddatec</span></td>
						<td valign="top"><span style="font-size:11px;">Plan :</span> <span style="font-size:11px; font-weight: bold;">$plname</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Days :</span> <span style="font-size:11px; font-weight: bold;">$days</span></td>
						<td valign="top"><span style="font-size:11px;">Price :</span> <span style="font-size:11px; font-weight: bold;">$amount</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Pay :</span> <span style="font-size:11px; font-weight: bold;">$paidamount  $currency</span></td>
						<td valign="top"><span style="font-size:11px;">Balance :</span><span style="font-size:11px; font-weight: bold;"> $balance</span></td>
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

	//public function monthlyreceipt_single($customer) {
	public function monthlyreceipt_single($customer,$name,$planname,$days,$amount,$startdate_n,$enddate_n,$pay_amount,$invoi_id,$address,$currency) {	
	//print_r($enddate_n);
    $bala = $amount - $pay_amount;
    $receiptdata = $this->my_model->getReceiptData_single($customer);
	$getaddress = $this->my_model->get_address();
	$customerdetials = $this->my_model->customer_deatils($customer);
	$this->load->model('addcustomer_model','my_model123');
	$record = $this->my_model123->get_adminrecord();
	extract($record);
    extract($receiptdata);
    extract($getaddress);
	extract($customerdetials);
	$startdatec = date('d M Y', strtotime($startdate_n));
	$enddatec = date('d M Y', strtotime($enddate_n));
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
		 <table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td align="center" valign="top">
				
						<div class="logoDiv">
							<img id="logo" src="http://dayaxpower.com/Waterbilling/images/logo/$file" height="80">
							<h1>$name</h1>
<h3>$contact1 / $email</h3>
<h3>$address1</h3>
					      <h2>Invoice Receipt</h2>
						 <p>(for Monthly Customer)</p>
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
						<td valign="top"><span style="font-size:11px;">Invoice_Id :</span> <span style="font-size:11px; font-weight: bold;">#$invoi_id</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Bill_To :</span><span style="font-size:11px; font-weight: bold;"> $first_name $middle_name $last_name</span></td>
						<td valign="top"><span style="font-size:11px;">Date :</span><span style="font-size:11px; font-weight: bold;"> $todate</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Address :</span> <span style="font-size:11px; font-weight: bold;">$address $city <br/>$state</span> </td>
						<td valign="top"><span style="font-size:11px;">Start Date :</span><span style="font-size:11px; font-weight: bold;"> $startdatec</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">End Date :</span> <span style="font-size:11px; font-weight: bold;">$enddatec</span></td>
						<td valign="top"><span style="font-size:11px;">Plan :</span> <span style="font-size:11px; font-weight: bold;">$planname</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Days :</span> <span style="font-size:11px; font-weight: bold;">$days</span></td>
						<td valign="top"><span style="font-size:11px;">Price :</span> <span style="font-size:11px; font-weight: bold;">$amount</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Pay :</span> <span style="font-size:11px; font-weight: bold;">$paidamount  $currency</span></td>
						<td valign="top"><span style="font-size:11px;">Balance :</span><span style="font-size:11px; font-weight: bold;"> $balance</span></td>
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