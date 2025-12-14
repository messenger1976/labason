<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class dashboard extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/dashboard_header'; 
	
	public $listPage = 'dashboard'; 
	public function __construct() {
        parent::__construct();
  		$this->load->model('dashboard_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
		/*if($this->session->userdata('usertype') == 'subadmin'){
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
			//echo '<pre>';print_r($this->head['roleResponsible']);exit;
		}else{
			$this->head['roleResponsible'] = array();
		}
		if(	array_key_exists('dashboard',$this->head['roleResponsible']) && $this->session->userdata('usertype') == 'subadmin' ){
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['dashboard']);
		}*/
		
    }
	public function index($mode =''){
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['msg'] ='';
		//*****  View Loading  *****//
        $yearrep = 2025;
		//$header['host'] = $this->comm_model->get_single_record();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		
		$data['customers'] = $this->my_model->get_total_customers();	
		$data['meter'] = $this->my_model->get_total_meter_customers();	
		$data['monthly'] = $this->my_model->get_total_monthly_customers();	
		$data['total_expenses'] = $this->my_model->get_total_expenses();	
		$data['meter_pay'] = $this->my_model->get_total_meter_pay();	
		$data['monthly_pay'] = $this->my_model->get_total_monthly_pay();	
		$data['total_prbm'] = $this->my_model->get_total_technical_problems();	
		$data['solved_prbm'] = $this->my_model->get_total_solved_problems();
		$data['customer'] = $this->my_model->get_customers_list();
		$data['expenses'] = $this->my_model->get_expenses_list();
		$data['leaking'] = $this->my_model->get_total_leaking_balance();
		
		// Load SMS notification statistics
		$this->load->model('mobilenotifications_model','sms_model');
		$data['sms_stats'] = $this->sms_model->get_notification_stats();

		$data['JanTotal'] = $this->my_model->get_total_sales(1,$yearrep,99);
		$data['FebTotal'] = $this->my_model->get_total_sales(2,$yearrep,99);
		$data['MarchTotal'] = $this->my_model->get_total_sales(3,$yearrep,99);
		$data['AprilTotal'] = $this->my_model->get_total_sales(4,$yearrep,99);
		$data['MayTotal'] = $this->my_model->get_total_sales(5,$yearrep,99);
		$data['JuneTotal'] = $this->my_model->get_total_sales(6,$yearrep,99);
		$data['JulyTotal'] = $this->my_model->get_total_sales(7,$yearrep,99);
		$data['AugTotal'] = $this->my_model->get_total_sales(8,$yearrep,99);
		$data['SepTotal'] = $this->my_model->get_total_sales(9,$yearrep,99);
		$data['OctTotal'] = $this->my_model->get_total_sales(10,$yearrep,99);
		$data['NovTotal'] = $this->my_model->get_total_sales(11,$yearrep,99);
		$data['DecTotal'] = $this->my_model->get_total_sales(12,$yearrep,99);
		
		$data['JanTotalPaid'] = $this->my_model->get_total_sales(1,$yearrep,1);
		$data['FebTotalPaid'] = $this->my_model->get_total_sales(2,$yearrep,1);
		$data['MarchTotalPaid'] = $this->my_model->get_total_sales(3,$yearrep,1);
		$data['AprilTotalPaid'] = $this->my_model->get_total_sales(4,$yearrep,1);
		$data['MayTotalPaid'] = $this->my_model->get_total_sales(5,$yearrep,1);
		$data['JuneTotalPaid'] = $this->my_model->get_total_sales(6,$yearrep,1);
		$data['JulyTotalPaid'] = $this->my_model->get_total_sales(7,$yearrep,1);
		$data['AugTotalPaid'] = $this->my_model->get_total_sales(8,$yearrep,1);
		$data['SepTotalPaid'] = $this->my_model->get_total_sales(9,$yearrep,1);
		$data['OctTotalPaid'] = $this->my_model->get_total_sales(10,$yearrep,1);
		$data['NovTotalPaid'] = $this->my_model->get_total_sales(11,$yearrep,1);
		$data['DecTotalPaid'] = $this->my_model->get_total_sales(12,$yearrep,1);
		
		$data['JanTotalUnpaid'] = $this->my_model->get_total_sales(1,$yearrep,0);
		$data['FebTotalUnpaid'] = $this->my_model->get_total_sales(2,$yearrep,0);
		$data['MarchTotalUnpaid'] = $this->my_model->get_total_sales(3,$yearrep,0);
		$data['AprilTotalUnpaid'] = $this->my_model->get_total_sales(4,$yearrep,0);
		$data['MayTotalUnpaid'] = $this->my_model->get_total_sales(5,$yearrep,0);
		$data['JuneTotalUnpaid'] = $this->my_model->get_total_sales(6,$yearrep,0);
		$data['JulyTotalUnpaid'] = $this->my_model->get_total_sales(7,$yearrep,0);
		$data['AugTotalUnpaid'] = $this->my_model->get_total_sales(8,$yearrep,0);
		$data['SepTotalUnpaid'] = $this->my_model->get_total_sales(9,$yearrep,0);
		$data['OctTotalUnpaid'] = $this->my_model->get_total_sales(10,$yearrep,0);
		$data['NovTotalUnpaid'] = $this->my_model->get_total_sales(11,$yearrep,0);
		$data['DecTotalUnpaid'] = $this->my_model->get_total_sales(12,$yearrep,0);

		// Get all zones with customer counts dynamically
		$data['zones'] = $this->my_model->get_all_zones_with_customer_counts();
		
		// Keep old zone variables for backward compatibility if needed
		$data['zone1'] = $this->my_model->get_cust_by_zone(1);
		$data['zone2'] = $this->my_model->get_cust_by_zone(2);
		$data['zone3'] = $this->my_model->get_cust_by_zone(3);
		$data['zone4'] = $this->my_model->get_cust_by_zone(4);
		
		$data['ticket0'] = $this->my_model->get_mytickets_chart(0);
		$data['ticket1'] = $this->my_model->get_mytickets_chart(1);
		$data['ticket2'] = $this->my_model->get_mytickets_chart(2);
		$data['ticket3'] = $this->my_model->get_mytickets_chart(3);
		$data['ticket4'] = $this->my_model->get_mytickets_chart(4);
		$data['ticket5'] = $this->my_model->get_mytickets_chart(5);
		$data['ticket6'] = $this->my_model->get_mytickets_chart(6);
		
		//print_r($data['JanTotal']);
		//$data['this_expenses'] = $this->my_model->get_month_expenses_list();
		//$data['monthly_income'] = $this->my_model->get_list_monthly_income();
		//$data['meter_income'] = $this->my_model->get_list_meter_income();
		//echo '<pre>';print_r($data);exit;
		$config = array();
		$config["base_url"] = base_url() . "index.php/master/dashboard/index";		
		$config["total_rows"] = $this->my_model->record_count();
        $config["per_page"] = 2;
        $config["uri_segment"] = 3;		
		$this->pagination->initialize($config);		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;  		 
		// $data['expenses'] = $this->my_model->get_expenses_list($config["per_page"], $page);	
		// $str_links = $this->pagination->create_links();
		// $data["links"] = explode('&nbsp;',$str_links );
		
		if($mode == 'pagination'){
			if($details = $this->my_model->get_expenses_list()){				
					if(count($details) > 0){
				$response = array("data"=>$details);			
				echo json_encode($response);				
				exit;
				} 
			}
		}
		
		if($mode == 'paginationtwo'){
			if($detailss = $this->my_model->get_month_expenses_list()){				
					if(count($detailss) > 0){
				$responses = array("data"=>$detailss);			
				echo json_encode($responses);				
				exit;
				} 
			}
		}
		
		if($mode == 'paginationthree'){
			if($detai = $this->my_model->get_list_meter_income()){				
					if(count($detai) > 0){
				$respo = array("data"=>$detai);			
				echo json_encode($respo);				
				exit;
				} 
			}
		} 
		
		if($mode == 'paginationfrst'){
			if($detai = $this->my_model->get_customers_list()){				
					if(count($detai) > 0){
				$respo = array("data"=>$detai);			
				echo json_encode($respo);				
				exit;
				} 
			}
		} 
		
		
		
		$data['total_rows'] = $config["total_rows"];
		//$data['this_expenses'] = $this->my_model->get_month_expenses_list();
		//$data['monthly_income'] = $this->my_model->get_list_monthly_income();
		 //$data['meter_income'] = $this->my_model->get_list_meter_income();
		//echo '<pre>';print_r($data);exit;
		$this->load->view($this->listPage,$data);
	}
	
}
?>