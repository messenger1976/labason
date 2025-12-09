<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class addledger extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_ledgers';	  //*****  Table name  *****//
	public $addPage  = 'addledger_add';	     //*****  Add page    *****//
	public $editPage = 'addledger_edit';     //*****  Edit page   *****//
	public $listPage = 'addledger';		   //*****  View page   *****//
	public $viewPage ='addledger_view';
	public $searchPage ='addledger_search';
//	public $payrolssearchPage ='addemployee_payrolssearch';
	public $addledgerajax = 'addledger_search _ajax';
//	public $addemployee_payrolsajax = 'addemployee_payrolssearch _ajax';	
	public $listPage_redirect = '/master/addledger';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/addledger/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/addledger/edit/';  //*****  Redirect Edit  *****//
	public function __construct() {
        parent::__construct();
  		$this->load->model('addledger_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }
	public function index(){ 		
	//*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_all_records();	
		$header['host'] = $this->comm_model->get_single_record();				
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}
	
	/** Add Function **/
	public function add(){ 
		$data['msg'] ='';
	 	 //$data['payrols'] = $this->my_model->get_payrols();
	//	 $data['job_title'] = $this->my_model->get_job_title();
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
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$data['account'] = $this->my_model->accountgroup();
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->addPage,$data);
	}
	public function edit($id){
		$data['record'] = $this->my_model->get_single_record($id);
	//	 $data['job_title'] = $this->my_model->get_job_title();
		//$data['payrols'] = $this->my_model->get_payrols();
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
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
		$data['account'] = $this->my_model->accountgroup();
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
	public function Search($id){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		//print_r($data['record']);
		//$header['host'] = $this->comm_model->get_single_record();						
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->searchPage,$data);
	}
	
	
	public function getaddledgersearch(){		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('ledgerName'));exit;
			if($this->input->post('ledgerName') ==''){
				$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
				echo $selBox;
			}
			if($this->input->post('ledgerName') !=''){
				$ledgerName = $this->input->post('ledgerName');
				
				
				$data['record'] = $this->my_model->get_addledger_records($ledgerName);

				$this->load->view($this->addledgerajax,$data);
			}		
	}	
	
	public function getaddemployeepayrolrssearch(){		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('employee_id'));exit;
			if($this->input->post('employee_id') ==''){
				$selBox ='<h6><span style="color:red">Dear Admin Please select atleast one option to search feilds</h6>' ;
				echo $selBox;
			}
			if($this->input->post('employee_id') !=''){
				$employee_id = $this->input->post('employee_id');
				
				
				$data['record'] = $this->my_model->get_addemployee_payrecords($employee_id);

				$this->load->view($this->addemployee_payrolsajax,$data);
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
				redirect('master/dashboard');
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
	function check_ledgerName($ledgerName){
		
		$ledgerName = trim($this->input->post('ledgerName'));
		$this->db->where('ledgerName', $ledgerName);
        $roll_chk_count = $this->db->count_all_results('tbl_ledgers');
        if ($roll_chk_count > 0) {
            echo "true";
        } else {
            echo "false";
        }
	}
	function check_customer_email($emailid){
		
		$email = trim($this->input->post('emailid'));
		$this->db->where('email_id', $email);
        $roll_chk_count = $this->db->count_all_results('tbl_addemployee');
        if ($roll_chk_count > 0) {
            echo "true";
        } else {
            echo "false";
        }
	}
	function check_customer_mobile_1($mobile_1){
		
		$mobile = trim($this->input->post('mobile_1'));
		$this->db->or_where('mobile1', $mobile);
		$this->db->or_where('mobile2', $mobile);
        $roll_chk_count = $this->db->count_all_results('tbl_addemployee');
        if ($roll_chk_count > 0) {
            echo "true";
        } else {
            echo "false";
        }
	}
	function check_customer_mobile_2($mobile_1){
		
		$mobile = trim($this->input->post('mobile_1'));
		$this->db->or_where('mobile1', $mobile);
		$this->db->or_where('mobile2', $mobile);
        $roll_chk_count = $this->db->count_all_results('tbl_addemployee');
        if ($roll_chk_count > 0) {
            echo "true";
        } else {
            echo "false";
        }
	}
	/*public function fileDownloadajax($id){
		$this->load->database();
		$this->db->select('employee_id,first_name,middle_name,last_name,gender,mobile1,mobile2,address,email_id,job_title,');
		$this->db->where('id',$id);
		$query = $this->db->get($this->table_name);
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}*/	
	
	public function payrolssearch(){ 
	//echo'dd';exit;
	$data['msg'] ='';
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		//$data['record'] = $this->my_model->get_single_record($id);
		//print_r($data['record']);
		//$header['host'] = $this->comm_model->get_single_record();						
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->payrolssearchPage,$data);
	}	
	public function fileDownloadajax($employee_id){
		$this->load->database();
		$this->db->select("employee_id,first_name,gender,mobile1,mobile2,email_id,address,
		(Select job_title from tbl_jobtitle where tbl_jobtitle.id = tbl_addemployee.job_title) as job_title");
		$this->db->from('tbl_addemployee');
		//$this->db->join('tbl_addmetercustomer', 'tbl_addmetercustomer.customer_id = tbl_addcustomer.customer_id');
		if($employee_id !='0'){
		$this->db->where('tbl_addemployee.employee_id',$employee_id);
		}
		//$this->db->group_by('tbl_addmetercustomer.id');	
		$query = $this->db->get();
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}	
	public function fileDownloadajax1($employee_id){
		$this->load->database();
		$this->db->select("
		tbl_addemployee.employee_id,tbl_addemployee.first_name,tbl_addemployee.gender,tbl_addemployee.mobile1,tbl_addemployee.mobile2,tbl_addemployee.email_id,tbl_addemployee.address,
		(Select job_title from tbl_jobtitle where tbl_jobtitle.id = tbl_addemployee.job_title) as job_title
		,tbl_addemployee.tax,tbl_payrols.amount,tbl_payrols.total,tbl_payrols.title
		,(case when (tbl_payrols.status = 1) THEN 'Paid' when (tbl_payrols.status = 0) THEN 'Un-Paid' END) as status
		");
		
		$this->db->from('tbl_addemployee');
		$this->db->join('tbl_payrols', 'tbl_payrols.employee_id = tbl_addemployee.employee_id');
		if($employee_id!='0'){$this->db->where('tbl_payrols.employee_id',$employee_id);}
		$this->db->group_by('tbl_payrols.id');	
		$query = $this->db->get(); 
		$this->load->helper('csv');
		query_to_csv($query, TRUE, $this->listPage.'-'.date("d-m-Y").'.csv');
	}
    public function employee_register_form($empid,$employee_id) {
		
	//print_r($empid);	print_r($employee_id);	

    $receiptdata = $this->my_model->get_employee_form($empid, $employee_id);
	extract($receiptdata);
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
	<div class="invoice-inner" style="margin: 0 15px; padding: 0px 0; color:#888;">
		 <table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td align="center" valign="top">
				
						<div class="logoDiv">
							<img id="logo" src="http://dayaxpower.com/images/favicon/logo.jpg" height="80">
							<h1>Company Name</h1>
					        <h2>Employee Registration Form</h2>
						</div>
						
				</td>
			</tr>
        </table>
		<table width="100%" >
			 <tr>
				<td align="right" valign="top">Address: Electicity Department<br/>
						           Near takli Road<br/>
								   Nagpur-440012</td>
			 </tr>
		</table> 
		<br/>	
   	
		<div class="invoice-address" style="border-top: 3px double #888; margin: 20px 0; padding-top: 25px;">
		    <br/>
		    <table border="0" cellspacing="0" cellpadding="15" width="100%">
			  <tbody>	
					<tr>
						<td align="left" valign="top"><span style="font-size:11px;text-transform: uppercase;">Employee-Id :</span> $employee_id</td>
						<td valign="top"><span style="font-size:11px;text-transform: uppercase;">Name :</span> $first_name $middle_name $last_name</td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;text-transform: uppercase;">Gender :</span> $gender</td>
						<td valign="top"><span style="font-size:11px;text-transform: uppercase;">DOB :</span> $dob </td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;text-transform: uppercase;">Place of birth :</span> $place_of_birth </td>
						<td valign="top"><span style="font-size:11px;text-transform: uppercase;">Address :</span> $address</td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;text-transform: uppercase;">City :</span> $city</td>
						<td valign="top"><span style="font-size:11px;text-transform: uppercase;">State :</span> $state</td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;text-transform: uppercase;">Mobile1 :</span> $mobile1</td>
						<td valign="top"><span style="font-size:11px;text-transform: uppercase;">Mobile2 :</span> $mobile2</td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;text-transform: uppercase;">Line Number :</span> $line_number </td>
						<td valign="top"><span style="font-size:11px;text-transform: uppercase;">Email-Id :</span> $email_id </td>
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;text-transform: uppercase;">Base Salary :</span> $base_salary</td>
						<td valign="top"><span style="font-size:11px;text-transform: uppercase;">Job Title :</span> $jobtitle </td>
						
					</tr>
					<tr>
						<td valign="top"><span style="font-size:11px;text-transform: uppercase;">Tax :</span>$tax</td>
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
    $pdf->Output($employee_id . '.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+
}

	
}
?>