<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Leakingentry extends CI_Controller{
    public $headerPage = '../../views/admin-includes/header';  //Header template

    public $listPage = 'leakingentry';
	public $leakingledgerPage = 'leakingentry_ledger';
	public $leaking_soa_statement = 'leaking_soa_statement';
	public $listPage_redirect ='master/Leakingentry';
    public function __construct(){
        parent::__construct();
		$this->load->helper('date');
		$this->load->helper('common');
        $this->load->model('leakingentry_model','my_model');   //*****    Model Loading     *****//		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 	
        $this->load->model('common_model','comm_model');			
		$this->load->model('adminheader_model','top_model');
		$this->load->model('addcustomer_model','customer_model');
		$this->load->model('addmetercustomerreading_model','meterreading_model');
    }

    public function index(){
        //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
        $header['record_info'] = $this->top_model->get_last_login_details(1);
        $data['record'] = $this->my_model->get_all_records();
		$data['customer_listing'] = $this->customer_model->get_all_records();
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
        
    }

	public function ledger($leaking_id){
        //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
        $header['record_info'] = $this->top_model->get_last_login_details(1);
        $data['record_ledger'] = $this->my_model->get_single_record($leaking_id);
		$data['record'] = $this->my_model->get_ledger_details_records($leaking_id);
		$data['total_payment'] = $this->my_model->get_total_payment($leaking_id)['totalpayment'];
		//$data['customer_listing'] = $this->customer_model->get_all_records();
		$data['leaking_id'] = $leaking_id;
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->leakingledgerPage,$data);
        
    }

	public function add(){
		if($this->input->post('btn_save') == 'add'){ 
			
		
			$result = $this->my_model->add_record();
			if($result){
				$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');
				echo 'success';
				//redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Inserted...";
				//redirect($this->listPage);
				echo 'error';
			}

		}elseif($this->input->post('btn_save') == 'edit'){
			$id = $this->input->post('leaking_id');
			$result = $this->my_model->update_record($id);
			if($result){
				$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
				echo 'success';
				//redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Updated...";
				//redirect($this->listPage);
				echo 'error';
			}
		}else{
			echo 'error';
		}
	}
	public function add_payment(){
		if($this->input->post('btn_save') == 'add'){ 
			
		
			$result = $this->my_model->add_payment_record();
			if($result){
				$this->session->set_flashdata('msg_succ', 'Created Successfully...');
				echo 'success';
				//redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Created...";
				//redirect($this->listPage);
				echo 'error';
			}

		}elseif($this->input->post('btn_save') == 'edit'){
			$id = $this->input->post('leaking_id');
			$result = $this->my_model->update_record($id);
			if($result){
				$this->session->set_flashdata('msg_succ', 'Updated Successfully...');
				echo 'success';
				//redirect($this->listPage_redirect);
			}else{
				$data['msg'] = "Not Updated...";
				//redirect($this->listPage);
				echo 'error';
			}
		}else{
			echo 'error update DB';
		}
	}
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
	
	/** Status Change Function **/
	public function status($id,$status){
		$data['msg'] ='';
		//echo $id.' '.$status;
		//exit;
		//$status = ($status == 1 ? 'Deactive' : 'Active');
		if($id){
			$result = $this->my_model->status_record($id,$status);
			if($result){
				$this->session->set_flashdata('msg_succ', 'Status Updated Successfully...');
				redirect($this->listPage_redirect);
			}else{
				$data['msg'] = " Status Not Updated...";
			}
		}
	}
	public function get_customer_meter_reading(){
		$customer_id = $this->input->post('customer_id');
		if($customer_id != ''){
			$result = $this->meterreading_model->get_addcustomer_meterreading_records($customer_id);
			echo json_encode($result);
			
		}else{
			echo '{}';
		}
	}

	public function get_customer_meter_reading_detail(){
		$meterreading_id = $this->input->post('meterreading_id');
		if($meterreading_id != ''){
			$result = $this->meterreading_model->get_single_record($meterreading_id);
			echo json_encode($result);
		}else{
			echo '{}';
		}
	}

	public function soa_statement($leaking_id){
		$data['record'] = $this->my_model->get_soa_header_statement($leaking_id);
		$data['record_details'] = $this->my_model->get_soa_statement($leaking_id);
		$data['customer_info'] = $this->customer_model->get_single_record_by_customer_id($data['record']['leaking_customer_id']);
		$data['customer_reading'] = $this->my_model->get_meterreading_refno($data['record']['leaking_refno']);
		//echo $data['record']['leaking_customer_id'];
		//print_r($data['record_details']);
		//exit;
		$this->load->view($this->leaking_soa_statement,$data);
	}

	/** Open Billing Adjustment / Labason forms in new window and trigger print dialog */
	public function print_forms($leaking_id = null){
		if (empty($leaking_id)) {
			show_error('Invalid leaking entry.');
		}
		$data['record'] = $this->my_model->get_soa_header_statement($leaking_id);
		if (empty($data['record'])) {
			show_error('Leaking entry not found.');
		}
		$data['customer_info'] = $this->customer_model->get_single_record_by_customer_id($data['record']['leaking_customer_id']);
		$data['customer_reading'] = $this->my_model->get_meterreading_refno($data['record']['leaking_refno']);
		$end_year = !empty($data['customer_reading']['year']) ? $data['customer_reading']['year'] : null;
		$end_month = !empty($data['customer_reading']['month']) ? $data['customer_reading']['month'] : null;
		$data['billing_history'] = $this->my_model->get_recent_billing_records(
			$data['record']['leaking_customer_id'],
			3,
			$end_year,
			$end_month
		);
		$data['form_no'] = str_pad($leaking_id, 5, '0', STR_PAD_LEFT);
		$data['ledger_details'] = $this->my_model->get_ledger_details_records($leaking_id);
		$this->load->view('leakingentry_print_forms', $data);
	}
}