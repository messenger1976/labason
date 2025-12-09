<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class payrols extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_payrols';	  //*****  Table name  *****//
	public $addPage  = 'payrols_add';	     //*****  Add page    *****//
	public $editPage = 'payrols_edit';     //*****  Edit page   *****//
	public $listPage = 'payrols';		   //*****  View page   *****//
	public $viewPage ='payrols_view';
	public $searchPage ='payrols_search';
	public $invoicePage ='order-invoice';
	public $addemployeeajax = 'payrols_search _ajax';	
	public $listPage_redirect = '/master/payrols';		  //*****  Redirect View  *****//
	public $addPage_redirect = '/master/payrols/add/';	 //*****  Redirect Add   *****//
	public $editPage_redirect = '/master/payrols/edit/';  //*****  Redirect Edit  *****//
	public $printPage = 'payroles-print';
	
	public function __construct() {
        parent::__construct();
  		$this->load->model('payrols_model','my_model');   //*****    Model Loading     *****//	
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
		//$header['host'] = $this->comm_model->get_single_record();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}
	
	/** Add Function **/
	public function add(){ 
		$data['msg'] ='';
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['addemployee'] = $this->my_model->get_addemployee();
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
	public function edit($id){
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		$data['addemployee'] = $this->my_model->get_addemployee();
		$data['msg'] ='';
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
	public function payment($id){
		$data['msg'] ='';
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		$data['address'] = $this->my_model->get_address();
		//echo'<pre>';print_r($data['record']);exit;
		//$header['host'] = $this->comm_model->get_single_record();						
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage);
		$this->load->view($this->invoicePage,$data);
	}
	public function last_amount(){ 
		$employee_id=$this->input->post('c_id');
		$amount = $this->my_model->get_last_months_amount($employee_id);
		if(!empty($amount)){
			echo $amount['total'];
		}else{
			echo 0;
		}
	}
	public function get_months(){ 
		$employee_id=$this->input->post('c_id');
		$January = $this->my_model->get_months_exit($employee_id,'January');
		$February = $this->my_model->get_months_exit($employee_id,'February');
		$March = $this->my_model->get_months_exit($employee_id,'March');
		$April = $this->my_model->get_months_exit($employee_id,'April');
		$May = $this->my_model->get_months_exit($employee_id,'May');
		$June = $this->my_model->get_months_exit($employee_id,'June');
		$July = $this->my_model->get_months_exit($employee_id,'July');
		$August = $this->my_model->get_months_exit($employee_id,'August');
		$September = $this->my_model->get_months_exit($employee_id,'September');
		$October = $this->my_model->get_months_exit($employee_id,'October');
		$November = $this->my_model->get_months_exit($employee_id,'November');
		$December = $this->my_model->get_months_exit($employee_id,'December');
		$selBox ='
		<select name="month" id="month" class="col-xs-10 col-sm-10" required>';
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
	
	public function pay($id,$status){
		$data['msg'] ='';
		//echo $status;
		$statu = ($status == 1 ? 'Status Change' : 'Status Change');
		if($id){
			$result = $this->my_model->status_record($id,$status);
			if($result){
				$this->session->set_flashdata('msg_succ', $statu.' Successfully...');
				redirect('master/payrols');
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
	
	public function printInvoice($id){
		$data['msg'] ='';
		$data['record'] = $this->my_model->get_single_record($id);
		$data['address'] = $this->my_model->get_address();
		//echo'<pre>';print_r($data['record']);exit;
		$this->load->view($this->printPage,$data);
	}		
	
}
?>