<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class addexpenses extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_addexpenses';	  //*****  Table name  *****//
	public $addPage  = 'addexpenses_add';	     //*****  Add page    *****//
	public $editPage = 'addexpenses_edit';     //*****  Edit page   *****//
	public $listPage = 'addexpenses';		   //*****  View page   *****//
	public $viewPage ='addexpenses_view';
	public $searchPage ='addexpenses_search';
	public $bsearchPage ='addexpenses_bsearch';
	public $addexpensesrajax ='addexpenses_search _ajax';
	public $addexpensesr_bajax ='addexpenses_bsearch _ajax';
	public $customer_invoice = 'addexpenses_invoice';
	public $printPage = 'addexpenses-print';	
	public $listPage_redirect = '/master/addexpenses';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/addexpenses/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/addexpenses/edit/';  //*****  Redirect Edit  *****//
	public function __construct() {
        parent::__construct();
  		$this->load->model('addexpenses_model','my_model');
		//*****    Model Loading     *****//	
		//$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->load->library('Pdf');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }
	public function index(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_all_records();	
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
             //echo'<pre>';print_r($_POST); exit;
				$result = $this->my_model->add_record();
				if($result){
					$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
					redirect($this->listPage_redirect);
				}else{
					$data['msg'] = "Not Inserted...";
				}
			}
		//$header['host'] = $this->comm_model->get_single_record();				
		$data['expenses'] = $this->my_model->get_extype(); 
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		//$data['account'] = $this->my_model->accountgroup();           // fetch Account sub Group
		$rand     	=  substr(rand(1,1000000),0,4); 
		$expenses_id  = 'EXP'.$rand;              
		$data['exp'] = $expenses_id ;         //print_r($data['exp']);exit;
		$data['ledger'] = $this->my_model->fetchLedger();            // fetch ledgers
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
		$data['expenses'] = $this->my_model->get_extype();
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		//$data['account'] = $this->my_model->accountgroup();
		$data['ledger'] = $this->my_model->fetchLedger();
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->editPage,$data);

	}
	/** View Function **/
	public function view($id){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		//print_r($data['record']);
		$header['host'] = $this->comm_model->get_single_record();				
		$header['record_info'] = $this->top_model->get_last_login_details(1);				
		//$data['account'] = $this->my_model->accountgroup();
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
	public function bsearch($id){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		//print_r($data['record']);
		//$header['host'] = $this->comm_model->get_single_record();						
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->searchPage,$data);
	}
	public function getaddexpensessearch(){		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('zone'));exit;
			if($this->input->post('fromdate') =='' && $this->input->post('todate')==''){
				$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
				echo $selBox;
			}
			if($this->input->post('fromdate') !='' || $this->input->post('todate')!=''){
				
				$fromdate = $this->input->post('fromdate');
				$todate = $this->input->post('todate');
				
				
				$data['record'] = $this->my_model->get_addexpenses_records($fromdate,$todate);
                 //echo'<pre>';print_r($data['record']);exit;
				$this->load->view($this->addexpensesrajax,$data);
			}		
	}	
	public function getaddexpensesbsearch(){		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('zone'));exit;
			if($this->input->post('fromdate') =='' && $this->input->post('todate')==''){
				$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
				echo $selBox;
			}
			if($this->input->post('fromdate') !='' || $this->input->post('todate')!=''){
				
				$fromdate = $this->input->post('fromdate');
				$todate = $this->input->post('todate');
				
				
				$data['record'] = $this->my_model->get_addexpenses_balance_records($fromdate,$todate);
				$data['payrols'] = $this->my_model->get_addpayrols_balance_records($fromdate,$todate);
				$data['meter'] = $this->my_model->get_addmeter_balance_records($fromdate,$todate);
				$data['monthly'] = $this->my_model->get_addmonthly_balance_records($fromdate,$todate);
                //echo'<pre>';print_r($data);exit;
				$this->load->view($this->addexpensesr_bajax,$data);
			}		
	}	
	/** Status Change Function **/
	/*public function status($id,$status){
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
	}*/
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
	
	
	public function fileDownloadajax($fromdate,$todate){
		$this->load->database();
		$this->db->select('expenses_id,expenses_type,quantity,amount,total');
		if($fromdate !=''){
			$this->db->where("date >= ",date('Y-m-d', strtotime($fromdate)));
		}
		if($todate !=''){
			$this->db->where("date <= ",date('Y-m-d', strtotime($todate)));
		}
		$query = $this->db->get('tbl_addexpenses');
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}
	public function fileDownloadajaxbsearch($fromdate,$todate){
		$this->load->database();
		$this->db->select("expenses_type,sum(amount) as debit");
		if($fromdate !=''){
			$this->db->where("date >= ",date('Y-m-d', strtotime($fromdate)));
		}
		if($todate !=''){
			$this->db->where("date <= ",date('Y-m-d', strtotime($todate)));
		}
		$this->db->group_by('date');
		$query = $this->db->get('tbl_addexpenses');
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}
	public function fileDownloadajaxbpayrols($fromdate,$todate){
		$this->load->database();
		$this->db->select("title as expenses_type,sum(amount) as debit");
		if($fromdate !=''){
			$this->db->where("date >= ",date('Y-m-d', strtotime($fromdate)));
		}
		if($todate !=''){
			$this->db->where("date <= ",date('Y-m-d', strtotime($todate)));
		}
		$this->db->group_by('date');
		$query = $this->db->get('tbl_payrols');
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}
	public function fileDownloadajaxbmeter($fromdate,$todate){
		$this->load->database();
		$this->db->select("sum(total) as credit");
		if($fromdate !=''){
			$this->db->where("date >= ",date('Y-m-d', strtotime($fromdate)));
		}
		if($todate !=''){
			$this->db->where("date <= ",date('Y-m-d', strtotime($todate)));
		}
		$this->db->group_by('date');
		$query = $this->db->get('tbl_addmetercustomer');
		//echo $this->db->last_query();exit;
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}
	public function fileDownloadajaxbmonthly($fromdate,$todate){
		$this->load->database();
		$this->db->select("sum(total) as credit");
		if($fromdate !=''){
			$this->db->where("date >= ",date('Y-m-d', strtotime($fromdate)));
		}
		if($todate !=''){
			$this->db->where("date <= ",date('Y-m-d', strtotime($todate)));
		}
		$this->db->group_by('date');
		$query = $this->db->get('tbl_monthlycustomer');
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}
	
	/*public function getoldmeter(){
		$id = $this->input->post('id');
		$getData=$this->my_model->select_getoldmeter($id);
		//echo'<pre>';print_r($getData);exit;
		if(!empty($getData)){
			if($getData[0]['status']=='1'){
		$selBox = 0;
			}else{
			$selBox = $getData[0]['aftermeter'];
            
			}
			
		}if(empty($getData)){
		
			$selBox = 0;
		}
		echo $selBox;
		
		
	}*/
	
	public function get_expences_invoice($id){ 
		$data['record'] = $this->my_model->get_single_record($id);
		$data['address'] = $this->my_model->get_address();
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		//echo'<pre>';print_r($data['record']);exit;
		$this->load->view($this->headerPage,$this->head);
		$this->load->view($this->customer_invoice,$data);
	}
	
	public function printInvoice($id){
		$data['msg'] ='';
		$data['record'] = $this->my_model->get_single_record($id);
		$data['address'] = $this->my_model->get_address();
		//echo'<pre>';print_r($data['record']);exit;
		$this->load->view($this->printPage,$data);
	}	
    
    public function invoicereceipt($invoiceid) {
		
	//print_r($invoiceid);	//print_r($month);	print_r($year);	
	$this->load->model('addcustomer_model','my_model123');
	$record = $this->my_model123->get_adminrecord();
    $receiptdata = $this->my_model->getReceiptData($invoiceid);
	//print_r($receiptdata);
    extract($receiptdata);
    extract($record);
	$datefor = date('d-m-Y', strtotime($date));
	$todate = date('d-m-Y');
    
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
							<h2>Monthly Expense</h2>
						</div>
						
				</td>
			</tr>
			
		</table>	
		<div class="invoice-address" style="border-top: 2px double #888;margin: 20px 0; padding-top: 25px;">
		    <br/>
		    <table border="1" cellspacing="0" cellpadding="15" width="100%">
			  <tbody>	
					<tr>
						<td valign="top"><span style="font-size:11px;">Expense Id :</span> </td>
						<td align="left" valign="top"><span style="font-size:11px; font-weight: bold;"> $expenses_id</span></td>
					</tr>
					<tr>	
						<td valign="top"><span style="font-size:11px;">Invoice :</span> </td>
						<td valign="top"><span style="font-size:11px; font-weight: bold;">#$invoice_id</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Date :</span> </td>
						<td valign="top"><span style="font-size:11px; font-weight: bold;"> $datefor</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Expenses Type :</span> </td>
						<td valign="top"><span style="font-size:11px; font-weight: bold;">$expenses_type </span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Quantity : </span></td>
						<td valign="top"><span style="font-size:11px; font-weight: bold;">$quantity</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Amount :</span></td>
						<td valign="top"><span style="font-size:11px; font-weight: bold;">$amount</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Total Price :</span></td>
						<td valign="top"><span style="font-size:11px; font-weight: bold;"> $total</span></td>
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

	//public function invoicereceipt_expense($expenseid) {
	public function invoicereceipt_expense($expenseid, $expensestype, $quantity, $date, $invoi_id,$amount) {	
	$totalam = $quantity * $amount ;
    $receiptdata = $this->my_model->getReceiptData_expense($expenseid);
    extract($receiptdata);
	$this->load->model('addcustomer_model','my_model123');
	$record = $this->my_model123->get_adminrecord();
	extract($record);
	
	$datefor = date('d-m-Y', strtotime($date));
	$todate = date('d-m-Y');
    
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
							<h2>Monthly Expense</h2>
						</div>
						
				</td>
			</tr>
			
		</table>	
		<div class="invoice-address" style="border-top: 2px double #888;margin: 20px 0; padding-top: 25px;">
		    <br/>
		    <table border="1" cellspacing="0" cellpadding="15" width="100%">
			  <tbody>	
					<tr>
						<td valign="top"><span style="font-size:11px;">Expense Id :</span> </td>
						<td align="left" valign="top"><span style="font-size:11px; font-weight: bold;"> $expenses_id</span></td>
					</tr>
					<tr>	
						<td valign="top"><span style="font-size:11px;">Invoice :</span> </td>
						<td valign="top"><span style="font-size:11px; font-weight: bold;">#$invoice_id</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Date :</span> </td>
						<td valign="top"><span style="font-size:11px; font-weight: bold;"> $datefor</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Expenses Type :</span> </td>
						<td valign="top"><span style="font-size:11px; font-weight: bold;">$expenses_type </span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Quantity : </span></td>
						<td valign="top"><span style="font-size:11px; font-weight: bold;">$quantity</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Amount :</span></td>
						<td valign="top"><span style="font-size:11px; font-weight: bold;">$amount</span></td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;">Total Price :</span></td>
						<td valign="top"><span style="font-size:11px; font-weight: bold;"> $total</span></td>
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