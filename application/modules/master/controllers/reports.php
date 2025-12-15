<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class reports extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $listPage = 'adddailyreport_add';
	public $agingARreportPage = 'aging_ar_report';
    public $leakingARreportPage = 'leaking_ar_report';
    public $monthlyBillingReportPage = 'monthly_billing_report';		   //*****  View page   *****//
    public $customerReportPage = 'customer_report';		   //*****  View page   *****//
	public $searchPage ='adddaily_search _ajax';
    public $monthlybillingreport_ajaxPage ='monthly_billing_report_ajax';
    public $customerreport_ajaxPage ='customer_report_ajax';
	public $agingARreport_ajaxPage ='aging_ar_report_ajax';
	public $printtopdfPage ='monthlybillingreport_printtopdf';
	public $customerprinttopdfPage ='customerreport_printtopdf';
	public $agingprinttopdfPage ='agingarreport_printtopdf';
	public $leakingarreport ='leakingarreport_printtopdf';
	public function __construct() {
        parent::__construct();
        $this->load->model('addbillingperiod_model','billingperiod_model');   //*****    Model Loading     *****//	
  		$this->load->model('adddailyreport_model','my_model');   //*****    Model Loading     *****//	
        
        // Load Report_model - handle case sensitivity for Linux/Windows compatibility
        // MX Loader converts model names to lowercase when searching for files
        // On Linux, it looks for 'report_model.php' but file is 'Report_model.php'
        $model_file_lower = APPPATH . 'modules/master/models/report_model.php';
        $model_file_upper = APPPATH . 'modules/master/models/Report_model.php';
        
        if (file_exists($model_file_upper)) {
            // File exists with uppercase R, manually load it to handle case sensitivity
            require_once($model_file_upper);
            if (class_exists('Report_model')) {
                $this->report_model = new Report_model();
            } else {
                log_message('error', 'Report_model class not found after loading file');
                show_error('Unable to load Report_model. Class not found.');
            }
        } elseif (file_exists($model_file_lower)) {
            // File exists with lowercase name, use standard loading
            $this->load->model('report_model','report_model');
        } else {
            // Try standard loading as fallback
            $this->load->model('Report_model','report_model');
        }
        
        $this->load->model('common_model','comm_model');
		$this->load->model('leakingentry_model');
		$this->load->model('addcustomer_model','customer_model');	
		$this->load->model('addmetercustomerreading_model','meterreading_model'); 
        $this->load->helper('common');
		$this->load->library('form_validation');
		$this->load->library('Pdf');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		// Error reporting: log errors but don't display them on production
		error_reporting(E_ALL);
		ini_set('display_errors','off');
		ini_set('log_errors','on');
		// Log errors to CodeIgniter's log file
		log_message('debug', 'Reports controller initialized'); 				
		$this->load->model('adminheader_model','top_model');
    }
	public function index(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$data['employee'] = $this->my_model->get_employee();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}

    public function monthly_billing_report(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
        $data['billingperiod'] = $this->billingperiod_model->get_month_billingperiod_records();	
		$data['employee'] = $this->my_model->get_employee();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->monthlyBillingReportPage,$data);
	}

	public function customer_report(){ 		 //*****  View Loading  *****//
		try {
			$header['roleResponsible'] = $this->top_model->get_responsibilities();
			$data['zone'] = $this->customer_model->get_zone();
			$data['employee'] = $this->my_model->get_employee();
			//$header['record_info'] = $this->top_model->get_last_login_details(1);
			
			// Verify header file exists
			$headerPath = APPPATH . 'views/admin-includes/header.php';
			if (!file_exists($headerPath)) {
				log_message('error', 'Header file not found: ' . $headerPath);
				show_error('Header file not found. Please contact administrator.');
				return;
			}
			
			// Verify view file exists
			$viewPath = APPPATH . 'modules/master/views/' . $this->customerReportPage . '.php';
			if (!file_exists($viewPath)) {
				log_message('error', 'View file not found: ' . $viewPath);
				show_error('View file not found. Please contact administrator.');
				return;
			}
			
			$this->load->view($this->headerPage,$header);
			$this->load->view($this->customerReportPage,$data);
		} catch (Exception $e) {
			log_message('error', 'Error in customer_report: ' . $e->getMessage());
			log_message('error', 'Stack trace: ' . $e->getTraceAsString());
			show_error('An error occurred while loading the customer report. Please check the error logs.');
		}
	}

    public function aging_ar_report(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$data['employee'] = $this->my_model->get_employee();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->agingARreportPage,$data);
	}

	public function leaking_ar_report(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$data['employee'] = $this->my_model->get_employee();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->leakingARreportPage,$data);
	}

	public function printtopdf($billingperiod,$status,$zone='',$preparedby='',$verifiedby='',$approvedby=''){
		//$header['roleResponsible'] = $this->top_model->get_responsibilities();
		//$data['zone'] = $this->my_model->get_zone($zone);
		$data['zone'] = $this->my_model->get_zone($zone);
		$billing_period = explode(' ',urldecode($billingperiod));
		$data['billingperiod_month'] = $billing_period[0];
        $data['billingperiod_year'] = $billing_period[1];
        $data['billingperiod_month_name'] = getMonthName($billing_period[0])[0]->month_name;
        $data['billingperiod'] = $billingperiod;
        $data['status'] = ($status=='99')?'':$status;
		$data['preparedby'] = $this->my_model->get_employee($preparedby);
		$data['verifiedby'] = $this->my_model->get_employee($verifiedby);
		$data['approvedby'] = $this->my_model->get_employee($approvedby);


        //$zone = $this->input->post('zone');
            //$billingperiod = $this->input->post('billingperiod');
            
            //$status = $this->input->post('status');
            
        //$data['record'] = $this->reports_model->get_monthly_billing_report_records($zone,$billingperiod,$status);

		//$this->load->view($this->headerPage,$header);
		$this->load->view($this->printtopdfPage,$data);
	}

	public function customerprinttopdf($status,$zone='',$preparedby='',$verifiedby='',$approvedby=''){
		//$header['roleResponsible'] = $this->top_model->get_responsibilities();
		//$data['zone'] = $this->my_model->get_zone($zone);
		$data['zone'] = $this->my_model->get_zone($zone);
        $data['status'] = ($status=='99')?'':$status;
		$data['record'] = $this->report_model->get_customer_report_records($zone,$data['status']);
		$data['preparedby'] = $this->my_model->get_employee($preparedby);
		$data['verifiedby'] = $this->my_model->get_employee($verifiedby);
		$data['approvedby'] = $this->my_model->get_employee($approvedby);

		//$this->load->view($this->headerPage,$header);
		$this->load->view($this->customerprinttopdfPage,$data);
	}

	public function agingprinttopdf($asofdate,$zone,$status,$preparedby='',$verifiedby='',$approvedby=''){
		//$header['roleResponsible'] = $this->top_model->get_responsibilities();
		//$data['zone'] = $this->my_model->get_zone($zone);
		$data['zone'] = $this->my_model->get_zone($zone);
		//$billing_period = explode(' ',urldecode($billingperiod));
		
        $data['asofdate'] = $asofdate;
        $data['status'] = ($status=='99')?'':$status;
		$data['preparedby'] = $this->my_model->get_employee($preparedby);
		$data['verifiedby'] = $this->my_model->get_employee($verifiedby);
		$data['approvedby'] = $this->my_model->get_employee($approvedby);


        //$zone = $this->input->post('zone');
            //$billingperiod = $this->input->post('billingperiod');
            
            //$status = $this->input->post('status');
            
        //$data['record'] = $this->reports_model->get_monthly_billing_report_records($zone,$billingperiod,$status);

		//$this->load->view($this->headerPage,$header);
		$this->load->view($this->agingprinttopdfPage,$data);
	}
	
	public function getmonthlyreportsearch()
	{		//*****  Add Search records  *****//
			$data['msg'] ='';
			
			
            $zone = $this->input->post('zone');
            $billingperiod = $this->input->post('billingperiod');
            
            $status = $this->input->post('status');
            //if($status==3){
			//	$data['record'] = $this->report_model->get_monthly_billing_report_records_status3($zone,$billingperiod,$status);
			//}else{
				$data['record'] = $this->report_model->get_monthly_billing_report_records($zone,$billingperiod,$status);
			//}
            
            
            $this->load->view($this->monthlybillingreport_ajaxPage,$data);
				
	}
	public function getcustomerreportsearch()
	{		//*****  Add Search records  *****//
			try {
				$data['msg'] ='';
				
				$zone = $this->input->post('zone');
				$status = $this->input->post('status');
				
				// Convert zone to integer, default to 0 if empty
				$zone = ($zone === '' || $zone === null) ? 0 : (int)$zone;
				
				// Ensure status is empty string if not set, handle '99' as 'All'
				if($status === '99' || $status === '' || $status === null){
					$status = '';
				}
				
				// Verify model is loaded
				if (!isset($this->report_model) || !is_object($this->report_model)) {
					log_message('error', 'Report_model not loaded in getcustomerreportsearch');
					echo '<div class="alert alert-danger">Error: Model not loaded. Please contact administrator.</div>';
					return;
				}
				
				// Get records
				$data['record'] = $this->report_model->get_customer_report_records($zone,$status);
				
				// If no records, set empty array
				if(!isset($data['record']) || !is_array($data['record'])){
					$data['record'] = array();
				}
				
				// Verify view file exists
				$viewPath = APPPATH . 'modules/master/views/' . $this->customerreport_ajaxPage . '.php';
				if (!file_exists($viewPath)) {
					log_message('error', 'AJAX view file not found: ' . $viewPath);
					echo '<div class="alert alert-danger">Error: View file not found. Please contact administrator.</div>';
					return;
				}
				
				// Load the view
				$this->load->view($this->customerreport_ajaxPage,$data);
			} catch (Exception $e) {
				log_message('error', 'Error in getcustomerreportsearch: ' . $e->getMessage());
				log_message('error', 'Stack trace: ' . $e->getTraceAsString());
				echo '<div class="alert alert-danger">An error occurred while loading data. Please check the error logs.</div>';
			}
	}	
	public function getagingARreportsearch()
	{		//*****  Add Search records  *****//
			$data['msg'] ='';
			
			
            $zone = $this->input->post('zone');
            $asofdate = $this->input->post('asofdate');
            $status = $this->input->post('status');

            //$status = $this->input->post('status');
            
            $data['record'] = $this->report_model->get_aging_ar_report_records($asofdate,$zone,$status);
            
            $this->load->view($this->agingARreport_ajaxPage,$data);
				
	}
	
}
?>