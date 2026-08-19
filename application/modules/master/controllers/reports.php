<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class reports extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $listPage = 'adddailyreport_add';
	public $agingARreportPage = 'aging_ar_report';
    public $leakingARreportPage = 'leaking_ar_report';
    public $monthlyBillingReportPage = 'monthly_billing_report';		   //*****  View page   *****//
    public $customerReportPage = 'customer_report';		   //*****  View page   *****//
    public $monthlyIncomeReportAnalyticPage = 'monthly_income_report_analytic';  //*****  View page   *****//
    public $monthlyIncomeReportPrintPage = 'monthly_income_report_printtopdf';
	public $searchPage ='adddaily_search _ajax';
    public $monthlybillingreport_ajaxPage ='monthly_billing_report_ajax';
    public $customerreport_ajaxPage ='customer_report_ajax';
	public $agingARreport_ajaxPage ='aging_ar_report_ajax';
	public $customerPaymentMonitoringPage = 'customer_payment_monitoring_report';
	public $customerpaymentmonitoring_ajaxPage = 'customer_payment_monitoring_report_ajax';
	public $printtopdfPage ='monthlybillingreport_printtopdf';
	public $customerprinttopdfPage ='customerreport_printtopdf';
	public $agingprinttopdfPage ='agingarreport_printtopdf';
	public $leakingarreport ='leakingarreport_printtopdf';
	public $arrearsMonitoringPage = 'arrears_monitoring_report';
	public $arrearsmonitoring_ajaxPage = 'arrears_monitoring_report_ajax';
	public $arrearsmonitoringprinttopdfPage = 'arrearsmonitoring_printtopdf';
	public $lowToNoConsumptionPage = 'low_to_no_consumption';
	public $lowtonoconsumption_ajaxPage = 'low_to_no_consumption_ajax';
	public $lowtonoconsumptionprinttopdfPage = 'low_to_no_consumption_printtopdf';
	public $downloadPdf = false;
	public $pdfFilename = 'report.pdf';
	public $pdfOptions = array();
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
		$this->load->helper('pdf');
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
		$this->load->model('statementofaccount_model', 'soa_model');
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

	public function arrears_monitoring_report(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$data['employee'] = $this->my_model->get_employee();
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->arrearsMonitoringPage,$data);
	}

	public function customer_payment_monitoring_report() {
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$this->load->view($this->headerPage, $header);
		$this->load->view($this->customerPaymentMonitoringPage, $data);
	}

	public function leaking_ar_report(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$data['employee'] = $this->my_model->get_employee();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->leakingARreportPage,$data);
	}

	public function monthly_income_report_analytic(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['employee'] = $this->my_model->get_employee();
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->monthlyIncomeReportAnalyticPage,$data);
	}

	public function getmonthlyincomereportanalytic(){
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		if (empty($month) || empty($year)) {
			$days_in_month = (int) date('t', mktime(0, 0, 0, (int)$month, 1, (int)$year));
			if ($days_in_month < 28) $days_in_month = 31;
			$this->_json_response(array('daily' => array(), 'total' => 0, 'days_in_month' => $days_in_month));
			return;
		}
		$result = $this->report_model->get_monthly_income_daily($month, $year);
		$this->_json_response($result);
	}

	private function _json_response($data) {
		while (ob_get_level()) { @ob_end_clean(); }
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data, JSON_NUMERIC_CHECK);
	}

	/** Print/PDF: Monthly Income Report Analytic - opens in new window for printing */
	public function monthly_income_report_printtopdf($month, $year) {
		$month = (int) $month;
		$year = (int) $year;
		if ($month < 1 || $month > 12 || $year < 2000 || $year > 2100) {
			show_error('Invalid month or year.');
			return;
		}
		$data = $this->report_model->get_monthly_income_daily($month, $year);
		$mn = getMonthName($month);
		$data['month_name'] = isset($mn[0]) ? $mn[0]->month_name : date('F', mktime(0,0,0,$month,1));
		$data['month'] = $month;
		$data['year'] = $year;
		send_print_or_pdf($this->monthlyIncomeReportPrintPage, $data);
	}

	/**
	 * Capture the print-preview HTML from the Export to PDF modal and return a PDF.
	 */
	public function downloadpreviewpdf() {
		$html = $this->input->post('html', false);
		$filename = $this->input->post('filename', true);
		if ($html === false || $html === null || trim((string) $html) === '') {
			show_error('Missing report HTML.', 400);
			return;
		}
		output_report_html_pdf($html, $filename ? $filename : 'report.pdf');
	}

	/** Export to PDF: Monthly Income Report Analytic */
	public function monthly_income_exporttopdf($month, $year) {
		$this->downloadPdf = true;
		$month = (int) $month;
		$year = (int) $year;
		$mn = getMonthName($month);
		$month_name = isset($mn[0]) ? $mn[0]->month_name : $month;
		$this->pdfFilename = 'Monthly_Income_Report_'.$month_name.'_'.$year.'.pdf';
		$this->pdfOptions = array('font_size' => 9);
		$this->monthly_income_report_printtopdf($month, $year);
	}

	/** Export to Excel: Monthly Income Report Analytic */
	public function monthly_income_exporttoexcel($month, $year) {
		$month = (int) $month;
		$year = (int) $year;
		if ($month < 1 || $month > 12 || $year < 2000 || $year > 2100) {
			show_error('Invalid month or year.');
			return;
		}
		@ini_set('display_errors', 0);
		error_reporting(0);
		while (ob_get_level()) { @ob_end_clean(); }
		if (headers_sent($file, $line)) {
			die("Headers already sent in $file on line $line.");
		}
		$this->load->helper('excel');
		$result = $this->report_model->get_monthly_income_daily($month, $year);
		$daily = $result['daily'];
		$total = (float) $result['total'];
		$days_in_month = (int) $result['days_in_month'];
		$mn = getMonthName($month);
		$month_name = isset($mn[0]) ? $mn[0]->month_name : date('F', mktime(0,0,0,$month,1));
		$export_data = array();
		$export_data[] = array('MONTHLY INCOME REPORT ANALYTIC');
		$export_data[] = array($month_name . ' ' . $year);
		$export_data[] = array('');
		$export_data[] = array('Day', 'Income');
		for ($d = 1; $d <= $days_in_month; $d++) {
			$amt = isset($daily[$d]) ? (float) $daily[$d] : 0;
			$export_data[] = array($d, number_format($amt, 2));
		}
		$export_data[] = array('');
		$export_data[] = array('TOTAL', number_format($total, 2));
		$filename = 'Monthly_Income_Report_' . $month_name . '_' . $year . '_' . date('d-m-Y') . '.xls';
		array_to_excel($export_data, $filename);
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

		send_print_or_pdf($this->printtopdfPage,$data);
	}

	public function exporttopdf($billingperiod,$status,$zone='',$preparedby='',$verifiedby='',$approvedby=''){
		$this->downloadPdf = true;
		$bp = str_replace(' ', '_', urldecode($billingperiod));
		$bp = preg_replace('/[^A-Za-z0-9_-]/', '', $bp);
		$this->pdfFilename = 'Monthly_Billing_Report_'.$bp.'.pdf';
		$this->printtopdf($billingperiod,$status,$zone,$preparedby,$verifiedby,$approvedby);
	}

	public function customerprinttopdf($status,$zone='',$preparedby='',$verifiedby='',$approvedby='',$special_priviledge=''){
		//$header['roleResponsible'] = $this->top_model->get_responsibilities();
		//$data['zone'] = $this->my_model->get_zone($zone);
		$data['zone'] = $this->my_model->get_zone($zone);
        $data['status'] = ($status=='99')?'':$status;
		$data['record'] = $this->report_model->get_customer_report_records($zone,$data['status'],$special_priviledge);
		$data['preparedby'] = $this->my_model->get_employee($preparedby);
		$data['verifiedby'] = $this->my_model->get_employee($verifiedby);
		$data['approvedby'] = $this->my_model->get_employee($approvedby);

		send_print_or_pdf($this->customerprinttopdfPage,$data);
	}

	public function customerexporttopdf($status,$zone='',$preparedby='',$verifiedby='',$approvedby='',$special_priviledge=''){
		$this->downloadPdf = true;
		$this->pdfFilename = 'Customer_Report_'.date('Y-m-d').'.pdf';
		$this->customerprinttopdf($status,$zone,$preparedby,$verifiedby,$approvedby,$special_priviledge);
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

		send_print_or_pdf($this->agingprinttopdfPage,$data);
	}

	public function agingexporttopdf($asofdate,$zone,$status,$preparedby='',$verifiedby='',$approvedby=''){
		$this->downloadPdf = true;
		$safe_date = preg_replace('/[^0-9-]/', '', $asofdate);
		$this->pdfFilename = 'Aging_AR_Report_'.$safe_date.'.pdf';
		$this->pdfOptions = array('orientation' => 'L', 'page_format' => 'LEGAL', 'font_size' => 7);
		$this->agingprinttopdf($asofdate,$zone,$status,$preparedby,$verifiedby,$approvedby);
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

	public function exporttoexcel($billingperiod='',$status='',$zone='',$preparedby='',$verifiedby='',$approvedby=''){
		// Suppress error display to prevent output before headers
		@ini_set('display_errors', 0);
		error_reporting(0);
		
		// Increase execution time and memory limit for large exports
		set_time_limit(600); // 10 minutes
		ini_set('memory_limit', '512M');
		
		// Clean any previous output to prevent corruption
		while (ob_get_level()) {
			@ob_end_clean();
		}
		
		// Prevent any output before headers
		if (headers_sent($file, $line)) {
			die("Headers already sent in $file on line $line. Cannot send Excel file.");
		}
		
		$this->load->helper('excel');
		
		// Decode billing period if URL encoded
		$billingperiod = urldecode($billingperiod);
		
		// Handle status - convert '99' to empty string for 'All'
		if($status == '99' || $status === ''){
			$status = '';
		}
		
		// Handle billing period - convert '0' to empty string for 'All'
		if($billingperiod == '0' || $billingperiod === ''){
			$billingperiod = '';
		}
		
		// Get records
		$records = $this->report_model->get_monthly_billing_report_records($zone, $billingperiod, $status);
		
		// Get zone data
		$zones = $this->my_model->get_zone($zone);
		$zone_name = '';
		if(count($zones) > 0 && isset($zones[0]['zone'])){
			$zone_name = $zones[0]['zone'];
		}
		
		// Format billing period for display
		$billing_period_display = 'All Periods';
		if($billingperiod != '' && $billingperiod != '0'){
			$billing_period_parts = explode(' ', $billingperiod);
			if(count($billing_period_parts) == 2){
				$month_num = $billing_period_parts[0];
				$year = $billing_period_parts[1];
				$month_name_result = getMonthName($month_num);
				if(isset($month_name_result[0]) && isset($month_name_result[0]->month_name)){
					$billing_period_display = $month_name_result[0]->month_name . ' ' . $year;
				} else {
					$billing_period_display = $billingperiod;
				}
			} else {
				$billing_period_display = $billingperiod;
			}
		}
		
		// Prepare export data array
		$export_data = array();
		
		// Add header rows
		$export_data[] = array('MONTHLY BILLING REPORT');
		$export_data[] = array('Billing Period: ' . $billing_period_display);
		if($zone_name != ''){
			$export_data[] = array('Zone: ' . $zone_name);
		}
		$export_data[] = array(''); // Empty row
		
		// Add column headers
		$export_data[] = array(
			'SN#',
			'Customer Name',
			'Customer ID',
			'Zone',
			'Category',
			'Meter Number',
			'Billing No.',
			'Consumed',
			'Metered Sales',
			'Penalty Charges',
			'Due Date',
			'Payment Date',
			'Total Amount',
			'Status'
		);
		
		// Initialize totals
		$grand_total_penalty = 0;
		$grand_total_metered_sales = 0;
		$grand_total_cubic_meter = 0;
		$grand_total_amount = 0;
		$index = 1;
		
		// Category breakdown arrays
		$class_category = array();
		$no_of_customer_1 = 0;
		$no_of_customer_2 = 0;
		$no_of_customer_3 = 0;
		$no_of_customer_4 = 0;
		
		$no_of_consumption_1 = 0;
		$no_of_consumption_2 = 0;
		$no_of_consumption_3 = 0;
		$no_of_consumption_4 = 0;
		
		$metered_sales_1 = 0;
		$metered_sales_2 = 0;
		$metered_sales_3 = 0;
		$metered_sales_4 = 0;
		
		$penalty_1 = 0;
		$penalty_2 = 0;
		$penalty_3 = 0;
		$penalty_4 = 0;
		
		$current_date = date('Y-m-d');
		
		// Process each record
		if(count($records) > 0){
			foreach($records as $key => $row){
				// Calculate penalty
				$pdate = stripslashes($row['payment_date']);
				$date = stripslashes($row['due_date']);
				$penalty = 0;
				
				if($pdate > $date){
					$penalty = $row['penalty'] - $row['amount'];
				}
				
				if($row['invoice_id'] == ''){
					$total_payment = $row['amount'];
					$date1 = $row['due_date'];
					if($current_date > $date1){
						$penalty = $row['penalty'] - $row['amount'];
						$total_payment = $row['penalty'];
					} else {
						$penalty = 0;
					}
				} else {
					$total_payment = $row['payment_amount'];
				}
				
				// Determine status
				$status_text = 'Un-Paid';
				if($row['invoice_id'] != ''){
					$status_text = 'Paid';
				} elseif($row['customer_status'] == '2'){
					$status_text = 'Disconnected';
				} elseif($row['customer_status'] == '1' && ($row['reading'] == '' || is_null($row['reading']))){
					$status_text = 'No Reading';
				}
				
				// Format dates
				$due_date_formatted = ($date != '') ? date('m/d/Y', strtotime($date)) : '';
				$payment_date_formatted = ($pdate != '') ? date('m/d/Y', strtotime($pdate)) : '';
				
				// Add row to export
				$export_data[] = array(
					$index,
					stripslashes(trim($row['last_name']) . ', ' . trim($row['first_name']) . ' ' . trim($row['middle_name'])),
					stripslashes($row['customer_id']),
					stripslashes($row['zone']),
					stripslashes($row['class_cat_name']),
					stripslashes($row['meter_number']),
					sprintf('%07d', $row['refno']),
					$row['consumed'],
					number_format($row['amount'], 2),
					number_format($penalty, 2),
					$due_date_formatted,
					$payment_date_formatted,
					number_format($total_payment, 2),
					$status_text
				);
				
				// Accumulate totals
				$grand_total_penalty += $penalty;
				$grand_total_metered_sales += $row['amount'];
				$grand_total_cubic_meter += $row['consumed'];
				$grand_total_amount += $total_payment;
				$index++;
				
				// Category breakdown calculations
				$cat_id = $row['class_cat_id'];
				if($cat_id == '1'){
					$no_of_customer_1++;
					$no_of_consumption_1 += $row['consumed'];
					$metered_sales_1 += $row['amount'];
					$penalty_1 += $penalty;
				} elseif($cat_id == '2'){
					$no_of_customer_2++;
					$no_of_consumption_2 += $row['consumed'];
					$metered_sales_2 += $row['amount'];
					$penalty_2 += $penalty;
				} elseif($cat_id == '3'){
					$no_of_customer_3++;
					$no_of_consumption_3 += $row['consumed'];
					$metered_sales_3 += $row['amount'];
					$penalty_3 += $penalty;
				} elseif($cat_id == '4'){
					$no_of_customer_4++;
					$no_of_consumption_4 += $row['consumed'];
					$metered_sales_4 += $row['amount'];
					$penalty_4 += $penalty;
				}
				
				// Build category array for breakdown
				$found = false;
				foreach($class_category as &$cat_item){
					if(isset($cat_item['class_cat_id']) && $cat_item['class_cat_id'] == $cat_id){
						$found = true;
						break;
					}
				}
				
				if(!$found){
					$class_category[] = array(
						'class_cat_id' => $cat_id,
						'class_cat_name' => $row['class_cat_name'],
						'no_of_customer' => 0,
						'no_of_consumption' => 0,
						'metered_sales' => 0,
						'penalty' => 0
					);
				}
			}
			
			// Update category totals
			foreach($class_category as &$cat_item){
				$cat_id = $cat_item['class_cat_id'];
				if($cat_id == '1'){
					$cat_item['no_of_customer'] = $no_of_customer_1;
					$cat_item['no_of_consumption'] = $no_of_consumption_1;
					$cat_item['metered_sales'] = $metered_sales_1;
					$cat_item['penalty'] = $penalty_1;
				} elseif($cat_id == '2'){
					$cat_item['no_of_customer'] = $no_of_customer_2;
					$cat_item['no_of_consumption'] = $no_of_consumption_2;
					$cat_item['metered_sales'] = $metered_sales_2;
					$cat_item['penalty'] = $penalty_2;
				} elseif($cat_id == '3'){
					$cat_item['no_of_customer'] = $no_of_customer_3;
					$cat_item['no_of_consumption'] = $no_of_consumption_3;
					$cat_item['metered_sales'] = $metered_sales_3;
					$cat_item['penalty'] = $penalty_3;
				} elseif($cat_id == '4'){
					$cat_item['no_of_customer'] = $no_of_customer_4;
					$cat_item['no_of_consumption'] = $no_of_consumption_4;
					$cat_item['metered_sales'] = $metered_sales_4;
					$cat_item['penalty'] = $penalty_4;
				}
			}
		}
		
		// Add grand total row
		$export_data[] = array(
			'',
			'',
			'',
			'',
			'',
			'',
			'GRAND TOTAL',
			number_format($grand_total_cubic_meter, 0),
			number_format($grand_total_metered_sales, 2),
			number_format($grand_total_penalty, 2),
			'',
			'',
			number_format($grand_total_amount, 2),
			''
		);
		
		// Add empty row
		$export_data[] = array('');
		
		// Add breakdown section header
		$export_data[] = array('BREAKDOWN OF METERED SALES');
		$export_data[] = array('');
		
		// Add breakdown headers
		$export_data[] = array(
			'CATEGORY',
			'No. of Consumer',
			'Consumption',
			'Amount',
			'Penalty'
		);
		
		// Add breakdown rows
		$grand_no_of_customer = 0;
		$grand_no_of_consumption = 0;
		$grand_metered_sales = 0;
		$grand_penalty = 0;
		
		foreach($class_category as $person){
			if(isset($person['class_cat_name']) && $person['class_cat_name'] != ''){
				$export_data[] = array(
					$person['class_cat_name'],
					$person['no_of_customer'],
					$person['no_of_consumption'],
					number_format($person['metered_sales'], 2),
					number_format($person['penalty'], 2)
				);
				
				$grand_no_of_customer += $person['no_of_customer'];
				$grand_no_of_consumption += $person['no_of_consumption'];
				$grand_metered_sales += $person['metered_sales'];
				$grand_penalty += $person['penalty'];
			}
		}
		
		// Add breakdown grand total
		$export_data[] = array(
			'GRAND TOTAL',
			$grand_no_of_customer,
			$grand_no_of_consumption,
			number_format($grand_metered_sales, 2),
			number_format($grand_penalty, 2)
		);
		
		// Add signature section
		$preparedby_data = $this->my_model->get_employee($preparedby);
		$verifiedby_data = $this->my_model->get_employee($verifiedby);
		$approvedby_data = $this->my_model->get_employee($approvedby);
		
		$export_data[] = array('');
		$export_data[] = array('Prepared by:', '', 'Verified by:');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		if(isset($preparedby_data[0]) && isset($verifiedby_data[0])){
			$preparedby_name = strtoupper($preparedby_data[0]['first_name'] . ' ' . $preparedby_data[0]['middle_name'] . ' ' . $preparedby_data[0]['last_name']);
			$verifiedby_name = strtoupper($verifiedby_data[0]['first_name'] . ' ' . $verifiedby_data[0]['middle_name'] . ' ' . $verifiedby_data[0]['last_name']);
			$export_data[] = array($preparedby_name, '', $verifiedby_name);
			$export_data[] = array($preparedby_data[0]['jobtitle'], '', $verifiedby_data[0]['jobtitle']);
		}
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('Approved by:');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		if(isset($approvedby_data[0])){
			$approvedby_name = strtoupper($approvedby_data[0]['first_name'] . ' ' . $approvedby_data[0]['middle_name'] . ' ' . $approvedby_data[0]['last_name']);
			$export_data[] = array($approvedby_name, '', 'Date/Time printed: ' . date('Y-m-d H:i:s'));
			$export_data[] = array($approvedby_data[0]['jobtitle']);
		}
		
		// Generate filename
		$filename = 'Monthly_Billing_Report_' . ($billing_period_display != 'All Periods' ? str_replace(' ', '_', $billing_period_display) : 'All') . '_' . date('d-m-Y') . '.xls';
		
		// Export to Excel (exit is handled in array_to_excel function)
		array_to_excel($export_data, $filename);
	}
	public function getcustomerreportsearch()
	{		//*****  Add Search records  *****//
			try {
				$data['msg'] ='';
				
				$zone = $this->input->post('zone');
				$status = $this->input->post('status');
				$special_priviledge = $this->input->post('special_priviledge');
				
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
				$data['record'] = $this->report_model->get_customer_report_records($zone,$status,$special_priviledge);
				
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

	public function customerexporttoexcel($status='',$zone='',$preparedby='',$verifiedby='',$approvedby='',$special_priviledge=''){
		// Suppress error display to prevent output before headers
		@ini_set('display_errors', 0);
		error_reporting(0);
		
		// Increase execution time and memory limit for large exports
		set_time_limit(600); // 10 minutes
		ini_set('memory_limit', '512M');
		
		// Clean any previous output to prevent corruption
		while (ob_get_level()) {
			@ob_end_clean();
		}
		
		// Prevent any output before headers
		if (headers_sent($file, $line)) {
			die("Headers already sent in $file on line $line. Cannot send Excel file.");
		}
		
		$this->load->helper('excel');
		
		// Handle status - convert '99' to empty string for 'All'
		if($status == '99' || $status === ''){
			$status = '';
		}
		
		// Handle zone - convert '0' to empty string for 'All'
		if($zone == '0' || $zone === ''){
			$zone = 0;
		} else {
			$zone = (int)$zone;
		}
		
		// Get records
		$records = $this->report_model->get_customer_report_records($zone, $status, $special_priviledge);
		
		// Get zone data
		$zones = $this->my_model->get_zone($zone);
		$zone_name = 'All Zones';
		if($zone > 0 && count($zones) > 0 && isset($zones[0]['zone'])){
			$zone_name = $zones[0]['zone'];
		}
		
		// Format status for display
		$status_display = 'All Statuses';
		if($status == '1'){
			$status_display = 'Active';
		} elseif($status == '0'){
			$status_display = 'Inactive';
		} elseif($status == '2'){
			$status_display = 'Disconnected';
		}
		$special_display = ($special_priviledge === '1' || $special_priviledge === 1) ? 'Yes' : 'All';
		
		// Prepare export data array
		$export_data = array();
		
		// Add header rows
		$export_data[] = array('CUSTOMER REPORT');
		$export_data[] = array('Zone: ' . $zone_name);
		$export_data[] = array('Status: ' . $status_display);
		$export_data[] = array('Special Priviledge: ' . $special_display);
		$export_data[] = array(''); // Empty row
		
		// Add column headers
		$export_data[] = array(
			'SN#',
			'Customer ID',
			'First Name',
			'Last Name',
			'Address',
			'Zone',
			'Classification',
			'Status'
		);
		
		// Process each record
		$index = 1;
		if(count($records) > 0){
			foreach($records as $key => $row){
				// Determine status text
				$status_val = isset($row['status']) ? $row['status'] : '';
				$status_text = 'Unknown';
				if($status_val == '1' || $status_val === 1){
					$status_text = 'Active';
				} elseif($status_val == '0' || $status_val === 0){
					$status_text = 'Inactive';
				} elseif($status_val == '2' || $status_val === 2){
					$status_text = 'Disconnected';
				}
				
				// Add row to export
				$export_data[] = array(
					$index,
					isset($row['customer_id']) ? stripslashes($row['customer_id']) : '',
					isset($row['first_name']) ? stripslashes($row['first_name']) : '',
					isset($row['last_name']) ? stripslashes($row['last_name']) : '',
					isset($row['address']) ? stripslashes($row['address']) : '',
					isset($row['zone_name']) ? stripslashes($row['zone_name']) : '',
					isset($row['classification_name']) ? stripslashes($row['classification_name']) : '',
					$status_text
				);
				
				$index++;
			}
		} else {
			$export_data[] = array('No records found');
		}
		
		// Add signature section
		$preparedby_data = $this->my_model->get_employee($preparedby);
		$verifiedby_data = $this->my_model->get_employee($verifiedby);
		$approvedby_data = $this->my_model->get_employee($approvedby);
		
		$export_data[] = array('');
		$export_data[] = array('Prepared by:', '', 'Verified by:');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		if(isset($preparedby_data[0]) && isset($verifiedby_data[0])){
			$preparedby_name = strtoupper($preparedby_data[0]['first_name'] . ' ' . $preparedby_data[0]['middle_name'] . ' ' . $preparedby_data[0]['last_name']);
			$verifiedby_name = strtoupper($verifiedby_data[0]['first_name'] . ' ' . $verifiedby_data[0]['middle_name'] . ' ' . $verifiedby_data[0]['last_name']);
			$export_data[] = array($preparedby_name, '', $verifiedby_name);
			$export_data[] = array($preparedby_data[0]['jobtitle'], '', $verifiedby_data[0]['jobtitle']);
		}
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('Approved by:');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		if(isset($approvedby_data[0])){
			$approvedby_name = strtoupper($approvedby_data[0]['first_name'] . ' ' . $approvedby_data[0]['middle_name'] . ' ' . $approvedby_data[0]['last_name']);
			$export_data[] = array($approvedby_name, '', 'Date/Time printed: ' . date('Y-m-d H:i:s'));
			$export_data[] = array($approvedby_data[0]['jobtitle']);
		}
		
		// Generate filename
		$filename = 'Customer_Report_' . str_replace(' ', '_', $zone_name) . '_' . str_replace(' ', '_', $status_display) . '_' . date('d-m-Y') . '.xls';
		
		// Export to Excel (exit is handled in array_to_excel function)
		array_to_excel($export_data, $filename);
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

	/**
	 * AJAX search handler for the Arrears Monitoring report.
	 * Same filters as Aging A/R Report; renders the slim 6-column ajax view.
	 */
	public function getarrearsmonitoringsearch()
	{
		$data['msg'] = '';

		$zone     = $this->input->post('zone');
		$asofdate = $this->input->post('asofdate');
		$status   = $this->input->post('status');

		$data['record'] = $this->report_model->get_arrears_monitoring_records($asofdate, $zone, $status);

		$this->load->view($this->arrearsmonitoring_ajaxPage, $data);
	}

	public function updatearrearsmonitoring()
	{
		while (ob_get_level()) { @ob_end_clean(); }
		header('Content-Type: application/json; charset=utf-8');

		$customer_id = $this->input->post('customer_id');
		$aging_amount = $this->input->post('aging_amount');

		$result = $this->report_model->update_current_period_arrears($customer_id, $aging_amount);
		echo json_encode($result, JSON_NUMERIC_CHECK);
	}

	/**
	 * AJAX: Customer Payment Monitoring search (neutral URL — paths containing "payment" are often blocked by extensions, yielding HTTP 0).
	 */
	public function getcpmonsearch() {
		$this->_customer_payment_monitoring_search_ajax();
	}

	/** Legacy alias; prefer getcpmonsearch for new clients. */
	public function getcustomerpaymentmonitoringsearch() {
		$this->_customer_payment_monitoring_search_ajax();
	}

	private function _customer_payment_monitoring_search_ajax() {
		try {
			$zone = $this->input->get_post('zone');
			$status = $this->input->get_post('status');
			$offset = $this->input->get_post('offset');
			$zone = ($zone === '' || $zone === null) ? 0 : (int) $zone;
			if ($status === '99' || $status === '' || $status === null) {
				$status = '';
			}
			$offset = ($offset === '' || $offset === null) ? 0 : (int) $offset;
			if ($offset < 0) {
				$offset = 0;
			}
			$limit = 100;
			$total = $this->report_model->count_customer_payment_monitoring_records($zone, $status);
			$rows = $this->report_model->get_customer_payment_monitoring_records($zone, $status, $limit, $offset);
			$arrears_cache = array();
			foreach ($rows as &$row) {
				$cid = isset($row['customer_id']) ? $row['customer_id'] : '';
				if ($cid === '') {
					$row['arrears'] = 0;
					continue;
				}
				if (!array_key_exists($cid, $arrears_cache)) {
					$arrears_cache[$cid] = (float) $this->soa_model->get_current_balance_excluding_active_billing_period($cid);
				}
				$row['arrears'] = $arrears_cache[$cid];
			}
			unset($row);
			$data['record'] = $rows;
			$data['total_count'] = $total;
			$data['offset'] = $offset;
			$data['limit'] = $limit;
			$data['has_more'] = ($offset + count($rows)) < $total;
			$this->load->view($this->customerpaymentmonitoring_ajaxPage, $data);
		} catch (Throwable $e) {
			log_message('error', 'customer_payment_monitoring_search_ajax: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
			$this->output->set_status_header(500);
			echo '<div class="alert alert-danger"><strong>Report could not load.</strong><br>'
				. htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
				. '<br><small>Details are in the application log.</small></div>';
		}
	}

	/**
	 * Export CPM report (neutral URL; delegates to export_customer_payment_monitoring_excel).
	 */
	public function export_cpmon_excel($zone = 0, $status = '99') {
		$this->export_customer_payment_monitoring_excel($zone, $status);
	}

	/**
	 * Export full Customer Payment Monitoring report (same filters as on-screen; batched DB reads of 100 rows).
	 * URL: reports/export_customer_payment_monitoring_excel/{zone}/{status} — use status 99 for All.
	 */
	public function export_customer_payment_monitoring_excel($zone = 0, $status = '99') {
		@ini_set('display_errors', 0);
		error_reporting(0);
		set_time_limit(600);
		ini_set('memory_limit', '512M');
		while (ob_get_level()) {
			@ob_end_clean();
		}
		if (headers_sent($file, $line)) {
			die("Headers already sent in $file on line $line. Cannot send Excel file.");
		}
		$this->load->helper('excel');
		$zone = (int) $zone;
		$status_filter = ($status === '99' || $status === '' || $status === null) ? '' : (string) $status;
		$zones = $this->my_model->get_zone($zone);
		$zone_name = 'All Zones';
		if ($zone > 0 && is_array($zones) && count($zones) > 0 && isset($zones[0]['zone'])) {
			$zone_name = $zones[0]['zone'];
		}
		$status_display = 'All statuses';
		if ($status_filter === '1') {
			$status_display = 'Active';
		} elseif ($status_filter === '0') {
			$status_display = 'Inactive';
		} elseif ($status_filter === '2') {
			$status_display = 'Disconnected';
		}
		$export_data = array();
		$export_data[] = array('CUSTOMER PAYMENT MONITORING REPORT');
		$export_data[] = array('Payments included: posted date in the calendar month immediately before the zone active billing period (when set).');
		$export_data[] = array('Zone: ' . $zone_name);
		$export_data[] = array('Customer status: ' . $status_display);
		$export_data[] = array('Exported: ' . date('Y-m-d H:i:s'));
		$export_data[] = array('');
		$export_data[] = array(
			'SN #',
			'Customer ID',
			'Customer Name',
			'Address',
			'Zone',
			'OR number',
			'# billing periods',
			'Billing Period Paid',
			'Total Amount Paid',
			'Arrears'
		);
		$arrears_cache = array();
		$index = 0;
		$offset = 0;
		$page_size = 100;
		while (true) {
			$batch = $this->report_model->get_customer_payment_monitoring_records($zone, $status_filter, $page_size, $offset);
			if (!is_array($batch) || count($batch) === 0) {
				break;
			}
			foreach ($batch as $row) {
				$index++;
				$cid = isset($row['customer_id']) ? $row['customer_id'] : '';
				if ($cid !== '' && !array_key_exists($cid, $arrears_cache)) {
					$arrears_cache[$cid] = (float) $this->soa_model->get_current_balance_excluding_active_billing_period($cid);
				}
				$ar = ($cid !== '' && array_key_exists($cid, $arrears_cache)) ? $arrears_cache[$cid] : 0;
				$name = trim(
					(isset($row['last_name']) ? stripslashes($row['last_name']) : '') . ', ' .
					(isset($row['first_name']) ? stripslashes($row['first_name']) : '') . ' ' .
					(isset($row['middle_name']) ? stripslashes($row['middle_name']) : '')
				);
				$export_data[] = array(
					$index,
					isset($row['customer_id']) ? stripslashes($row['customer_id']) : '',
					$name,
					isset($row['address']) ? stripslashes($row['address']) : '',
					isset($row['zone_name']) ? stripslashes($row['zone_name']) : '',
					isset($row['or_number']) ? stripslashes((string) $row['or_number']) : '',
					isset($row['period_count']) ? (int) $row['period_count'] : 0,
					isset($row['billing_periods_paid']) ? stripslashes($row['billing_periods_paid']) : '',
					isset($row['total_paid']) ? number_format((float) $row['total_paid'], 2, '.', '') : '0.00',
					number_format($ar, 2, '.', '')
				);
			}
			$offset += $page_size;
			if (count($batch) < $page_size) {
				break;
			}
		}
		if ($index === 0) {
			$export_data[] = array('No records found for the selected filters.');
		}
		$filename = 'Customer_Payment_Monitoring_' . preg_replace('/[^A-Za-z0-9_-]+/', '_', $zone_name) . '_' . date('Y-m-d') . '.xls';
		array_to_excel($export_data, $filename);
	}

	public function agingexporttoexcel($asofdate='',$zone='',$status='',$preparedby='',$verifiedby='',$approvedby=''){
		// Suppress error display to prevent output before headers
		@ini_set('display_errors', 0);
		error_reporting(0);
		
		// Increase execution time and memory limit for large exports
		set_time_limit(600); // 10 minutes
		ini_set('memory_limit', '512M');
		
		// Clean any previous output to prevent corruption
		while (ob_get_level()) {
			@ob_end_clean();
		}
		
		// Prevent any output before headers
		if (headers_sent($file, $line)) {
			die("Headers already sent in $file on line $line. Cannot send Excel file.");
		}
		
		$this->load->helper('excel');
		
		// Handle status - convert '99' to empty string for 'All' (matching print function exactly)
		$status = ($status=='99')?'':$status;
		
		// Get zones - matching print function logic exactly
		$zones = $this->my_model->get_zone($zone);
		
		// Ensure zones is an array
		if(!is_array($zones)){
			$zones = array();
		}
		
		// Format status for display (matching print view exactly - lines 46-54)
		$status_display = '';
		if($status==='99' || $status===''){
			$status_display = 'All Members';
		}elseif($status==='1'){
			$status_display = 'Active Members';
		}elseif($status==='2'){
			$status_display = 'Disconnected Members';
		}elseif($status==='0'){
			$status_display = 'Inactive Members';
		}
		
		// Prepare export data array
		$export_data = array();
		
		// Add header rows (matching print view exactly - lines 41-58)
		$export_data[] = array('AGING OF ACCOUNT RECEIVABLE REPORT');
		$export_data[] = array('As of ' . $asofdate);
		$export_data[] = array($status_display);
		$export_data[] = array(''); // Empty row
		
		// Add column headers (matching print view)
		$export_data[] = array(
			'SN #',
			'Concessionaires',
			'Cust Acct No.',
			'Meter Number',
			'Current',
			'30 Days',
			'60 Days',
			'90 Days',
			'120 Days',
			'150 Days Up',
			'Amount'
		);
		
		// Initialize grand totals (matching print view exactly - lines 96-101)
		$grand_total_current = 0;
		$grand_total_30days = 0;
		$grand_total_60days = 0;
		$grand_total_90days = 0;
		$grand_total_120days = 0;
		$grand_total_150daysup = 0;
		$grand_total_amount = 0;
		$index = 0; // Matching print view - starts at 0, increments inside loop
		
		// Process zones - matching print view logic exactly (lines 93-110)
		if(count($zones) > 0){
			foreach($zones as $key => $zone_row){
				// Add zone header row (matching print view line 106-110)
				// Empty first cell, zone name in second, then 8 empty cells (colspan="8")
				$export_data[] = array(
					'',
					stripslashes($zone_row['zone']),
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					''
				);
				
				// Get records for this zone - matching print view call exactly (line 114)
				// Pass date as-is (dd-mm-yy format), model will convert it
				$get_dailytrans = $this->report_model->get_aging_ar_report_records($asofdate, $zone_row['id'], $status);
				
				// Check if records exist
				if(!is_array($get_dailytrans)){
					$get_dailytrans = array();
				}
				
				// Initialize zone totals (matching print view lines 116-121)
				$grand_total_current_zone = 0;
				$grand_total_30days_zone = 0;
				$grand_total_60days_zone = 0;
				$grand_total_90days_zone = 0;
				$grand_total_120days_zone = 0;
				$grand_total_150daysup_zone = 0;
				$grand_total_amount_zone = 0;
				
				// Process records for this zone (matching print view lines 125-163)
				foreach($get_dailytrans as $key => $gdailytrans){
					$index++; // Increment index first (matching print view line 127)
					
					$export_data[] = array(
						$index,
						stripslashes($gdailytrans['last_name'] . ', ' . $gdailytrans['first_name'] . ' ' . $gdailytrans['middle_name']),
						stripslashes($gdailytrans['customer_id']),
						stripslashes($gdailytrans['meter_number']),
						number_format($gdailytrans['current'], 2),
						number_format($gdailytrans['30-days'], 2),
						number_format($gdailytrans['60-days'], 2),
						number_format($gdailytrans['90-days'], 2),
						number_format($gdailytrans['120-days'], 2),
						number_format($gdailytrans['150-DaysUp'], 2),
						number_format($gdailytrans['total_balance'], 2)
					);
					
					// Accumulate zone totals (matching print view lines 146-152)
					$grand_total_current_zone += $gdailytrans['current'];
					$grand_total_30days_zone += $gdailytrans['30-days'];
					$grand_total_60days_zone += $gdailytrans['60-days'];
					$grand_total_90days_zone += $gdailytrans['90-days'];
					$grand_total_120days_zone += $gdailytrans['120-days'];
					$grand_total_150daysup_zone += $gdailytrans['150-DaysUp'];
					$grand_total_amount_zone += $gdailytrans['total_balance'];
					
					// Accumulate grand totals (matching print view lines 155-161)
					$grand_total_current += $gdailytrans['current'];
					$grand_total_30days += $gdailytrans['30-days'];
					$grand_total_60days += $gdailytrans['60-days'];
					$grand_total_90days += $gdailytrans['90-days'];
					$grand_total_120days += $gdailytrans['120-days'];
					$grand_total_150daysup += $gdailytrans['150-DaysUp'];
					$grand_total_amount += $gdailytrans['total_balance'];
				}
				
				// Add zone total row (matching print view lines 164-174)
				// colspan="4" means first 4 columns, so "TOTAL" goes in column 4
				$export_data[] = array(
					'',
					'',
					'',
					'TOTAL',
					number_format($grand_total_current_zone, 2),
					number_format($grand_total_30days_zone, 2),
					number_format($grand_total_60days_zone, 2),
					number_format($grand_total_90days_zone, 2),
					number_format($grand_total_120days_zone, 2),
					number_format($grand_total_150daysup_zone, 2),
					number_format($grand_total_amount_zone, 2),
					'' // Extra empty cell to match colspan
				);
			}
		}
		
		// Add grand total row (matching print view lines 193-204)
		// colspan="4" means first 4 columns, so "GRAND TOTAL" goes in column 4
		$export_data[] = array(
			'',
			'',
			'',
			'GRAND TOTAL',
			number_format($grand_total_current, 2),
			number_format($grand_total_30days, 2),
			number_format($grand_total_60days, 2),
			number_format($grand_total_90days, 2),
			number_format($grand_total_120days, 2),
			number_format($grand_total_150daysup, 2),
			number_format($grand_total_amount, 2),
			'' // Extra empty cell to match colspan
		);
		
		// Add signature section (matching print view lines 213-270 exactly)
		$preparedby_data = $this->my_model->get_employee($preparedby);
		$verifiedby_data = $this->my_model->get_employee($verifiedby);
		$approvedby_data = $this->my_model->get_employee($approvedby);
		
		$export_data[] = array('');
		$export_data[] = array('Prepared by:', '', 'Verified by:', '', '', '', '', '', '', '', '');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		if(isset($preparedby_data[0]) && isset($verifiedby_data[0])){
			// Matching print view lines 231-235 - build name first, then uppercase
			$preparedby_name = $preparedby_data[0]['first_name'] . ' ' . $preparedby_data[0]['middle_name'] . ' ' . $preparedby_data[0]['last_name'];
			$verifiedby_name = $verifiedby_data[0]['first_name'] . ' ' . $verifiedby_data[0]['middle_name'] . ' ' . $verifiedby_data[0]['last_name'];
			$export_data[] = array(strtoupper($preparedby_name), '', strtoupper($verifiedby_name), '', '', '', '', '', '', '', '');
			$export_data[] = array($preparedby_data[0]['jobtitle'], '', $verifiedby_data[0]['jobtitle'], '', '', '', '', '', '', '', '');
		}
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('Approved by:', '', '', '', '', '', '', '', '', '', '');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		if(isset($approvedby_data[0])){
			// Matching print view lines 262-265 - note: print uses H:m:s (incorrect format but matching exactly)
			$approvedby_name = $approvedby_data[0]['first_name'] . ' ' . $approvedby_data[0]['middle_name'] . ' ' . $approvedby_data[0]['last_name'];
			$export_data[] = array(strtoupper($approvedby_name), '', 'Date/Time printed: ' . date('Y-m-d H:m:s'), '', '', '', '', '', '', '', '');
			$export_data[] = array($approvedby_data[0]['jobtitle'], '', '', '', '', '', '', '', '', '', '');
		}
		$export_data[] = array('');
		
		// Generate filename
		$filename = 'Aging_AR_Report_' . str_replace([' ', '/'], '_', $asofdate) . '_' . date('d-m-Y') . '.xls';
		
		// Export to Excel (exit is handled in array_to_excel function)
		array_to_excel($export_data, $filename);
	}

	/**
	 * Print/PDF view for the Arrears Monitoring report.
	 * URL: reports/arrearsmonitoringprinttopdf/{asofdate}/{zone}/{status}/{preparedby}/{verifiedby}/{approvedby}
	 */
	public function arrearsmonitoringprinttopdf($asofdate, $zone, $status, $preparedby = '', $verifiedby = '', $approvedby = ''){
		$data['zone']        = $this->my_model->get_zone($zone);
		$data['asofdate']    = $asofdate;
		$data['status']      = ($status == '99') ? '' : $status;
		$data['preparedby']  = $this->my_model->get_employee($preparedby);
		$data['verifiedby']  = $this->my_model->get_employee($verifiedby);
		$data['approvedby']  = $this->my_model->get_employee($approvedby);

		send_print_or_pdf($this->arrearsmonitoringprinttopdfPage, $data);
	}

	public function arrearsmonitoringexporttopdf($asofdate, $zone, $status, $preparedby = '', $verifiedby = '', $approvedby = ''){
		$this->downloadPdf = true;
		$safe_date = preg_replace('/[^0-9-]/', '', $asofdate);
		$this->pdfFilename = 'Arrears_Monitoring_Report_'.$safe_date.'.pdf';
		$this->arrearsmonitoringprinttopdf($asofdate, $zone, $status, $preparedby, $verifiedby, $approvedby);
	}

	/**
	 * Excel export for the Arrears Monitoring report.
	 * URL: reports/arrearsmonitoringexporttoexcel/{asofdate}/{zone}/{status}/{preparedby}/{verifiedby}/{approvedby}
	 */
	public function arrearsmonitoringexporttoexcel($asofdate = '', $zone = '', $status = '', $preparedby = '', $verifiedby = '', $approvedby = ''){
		@ini_set('display_errors', 0);
		error_reporting(0);
		set_time_limit(600);
		ini_set('memory_limit', '512M');

		while (ob_get_level()) {
			@ob_end_clean();
		}

		if (headers_sent($file, $line)) {
			die("Headers already sent in $file on line $line. Cannot send Excel file.");
		}

		$this->load->helper('excel');

		$status = ($status == '99') ? '' : $status;

		$zones = $this->my_model->get_zone($zone);
		if (!is_array($zones)) {
			$zones = array();
		}

		$status_display = '';
		if ($status === '99' || $status === '') {
			$status_display = 'All Members';
		} elseif ($status === '1') {
			$status_display = 'Active Members';
		} elseif ($status === '2') {
			$status_display = 'Disconnected Members';
		} elseif ($status === '0') {
			$status_display = 'Inactive Members';
		}

		$export_data = array();
		$export_data[] = array('ARREARS MONITORING REPORT');
		$export_data[] = array('As of ' . $asofdate);
		$export_data[] = array($status_display);
		$export_data[] = array('');

		$export_data[] = array(
			'SN #',
			'Customer ID',
			'Customer Name',
			'Meter Number',
			'Zone',
			'Aging Amount',
			'Current Billing Period Arrears'
		);

		$grand_total_aging   = 0;
		$grand_total_arrears = 0;
		$index = 0;

		if (count($zones) > 0) {
			foreach ($zones as $key => $zone_row) {
				$export_data[] = array(
					'',
					stripslashes($zone_row['zone']),
					'', '', '', '', ''
				);

				$rows = $this->report_model->get_arrears_monitoring_records($asofdate, $zone_row['id'], $status);
				if (!is_array($rows)) {
					$rows = array();
				}

				$zone_total_aging   = 0;
				$zone_total_arrears = 0;

				foreach ($rows as $key2 => $row) {
					$index++;
					$customer_name = stripslashes(trim($row['last_name']) . ', ' . trim($row['first_name']) . ' ' . trim($row['middle_name']));
					$aging_amount  = isset($row['total_balance']) ? (float) $row['total_balance'] : 0;
					$current_arr   = isset($row['current_arrears']) ? (float) $row['current_arrears'] : 0;

					$export_data[] = array(
						$index,
						stripslashes($row['customer_id']),
						$customer_name,
						stripslashes($row['meter_number']),
						stripslashes($row['zone']),
						number_format($aging_amount, 2),
						number_format($current_arr, 2)
					);

					$zone_total_aging   += $aging_amount;
					$zone_total_arrears += $current_arr;

					$grand_total_aging   += $aging_amount;
					$grand_total_arrears += $current_arr;
				}

				$export_data[] = array(
					'', '', '',
					'TOTAL',
					'',
					number_format($zone_total_aging, 2),
					number_format($zone_total_arrears, 2)
				);
			}
		}

		$export_data[] = array(
			'', '', '',
			'GRAND TOTAL',
			'',
			number_format($grand_total_aging, 2),
			number_format($grand_total_arrears, 2)
		);

		// Signature section (same layout as Aging A/R export)
		$preparedby_data = $this->my_model->get_employee($preparedby);
		$verifiedby_data = $this->my_model->get_employee($verifiedby);
		$approvedby_data = $this->my_model->get_employee($approvedby);

		$export_data[] = array('');
		$export_data[] = array('Prepared by:', '', 'Verified by:', '', '', '', '');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		if (isset($preparedby_data[0]) && isset($verifiedby_data[0])) {
			$preparedby_name = $preparedby_data[0]['first_name'] . ' ' . $preparedby_data[0]['middle_name'] . ' ' . $preparedby_data[0]['last_name'];
			$verifiedby_name = $verifiedby_data[0]['first_name'] . ' ' . $verifiedby_data[0]['middle_name'] . ' ' . $verifiedby_data[0]['last_name'];
			$export_data[] = array(strtoupper($preparedby_name), '', strtoupper($verifiedby_name), '', '', '', '');
			$export_data[] = array($preparedby_data[0]['jobtitle'], '', $verifiedby_data[0]['jobtitle'], '', '', '', '');
		}
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('Approved by:', '', '', '', '', '', '');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		if (isset($approvedby_data[0])) {
			$approvedby_name = $approvedby_data[0]['first_name'] . ' ' . $approvedby_data[0]['middle_name'] . ' ' . $approvedby_data[0]['last_name'];
			$export_data[] = array(strtoupper($approvedby_name), '', 'Date/Time printed: ' . date('Y-m-d H:i:s'), '', '', '', '');
			$export_data[] = array($approvedby_data[0]['jobtitle'], '', '', '', '', '', '');
		}
		$export_data[] = array('');

		$filename = 'Arrears_Monitoring_Report_' . str_replace([' ', '/'], '_', $asofdate) . '_' . date('d-m-Y') . '.xls';
		array_to_excel($export_data, $filename);
	}

	/** Sub-admins need lowtonoconsumption = 1. Admins always pass. */
	private function _require_lowtonoconsumption_access() {
		$ut = $this->session->userdata('usertype');
		if ($ut === 'admin') {
			return;
		}
		if ($ut === 'subadmin') {
			$rr = $this->top_model->get_responsibilities();
			if (array_key_exists('lowtonoconsumption', $rr) && (int) $rr['lowtonoconsumption'] === 1) {
				$this->top_model->get_responsibilities_conditions($rr['lowtonoconsumption']);
				return;
			}
		}
		redirect('master/page/', 'refresh');
	}

	public function low_to_no_consumption() {
		$this->_require_lowtonoconsumption_access();
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$data['billingperiod'] = $this->billingperiod_model->get_month_billingperiod_records();
		$data['employee'] = $this->my_model->get_employee();
		$this->load->view($this->headerPage, $header);
		$this->load->view($this->lowToNoConsumptionPage, $data);
	}

	public function getlowtonoconsumptionsearch() {
		$this->_require_lowtonoconsumption_access();
		$zone = $this->input->post('zone');
		$billingperiod = $this->input->post('billingperiod');
		$usage_type = $this->input->post('usage_type');
		$max_cu = $this->input->post('max_cu');
		if ($usage_type === '' || $usage_type === null) {
			$usage_type = 'both';
		}
		if ($max_cu === '' || $max_cu === null) {
			$max_cu = 10;
		}
		$data['record'] = $this->report_model->get_low_to_no_consumption_records($zone, $billingperiod, $usage_type, $max_cu);
		$data['usage_type'] = $usage_type;
		$data['max_cu'] = $max_cu;
		$this->load->view($this->lowtonoconsumption_ajaxPage, $data);
	}

	public function lowtonoconsumptionprinttopdf($billingperiod, $usage_type = 'both', $max_cu = 10, $zone = '', $preparedby = '', $verifiedby = '', $approvedby = '') {
		$this->_require_lowtonoconsumption_access();
		$billingperiod = urldecode($billingperiod);
		$usage_type = urldecode($usage_type);
		$data['zone'] = $this->my_model->get_zone($zone);
		$billing_period = explode(' ', $billingperiod);
		$data['billingperiod_month'] = isset($billing_period[0]) ? $billing_period[0] : '';
		$data['billingperiod_year'] = isset($billing_period[1]) ? $billing_period[1] : '';
		$month_name_row = ($data['billingperiod_month'] !== '') ? getMonthName($data['billingperiod_month']) : array();
		$data['billingperiod_month_name'] = (isset($month_name_row[0]) && isset($month_name_row[0]->month_name)) ? $month_name_row[0]->month_name : $data['billingperiod_month'];
		$data['billingperiod'] = $billingperiod;
		$data['usage_type'] = $usage_type;
		$data['max_cu'] = $max_cu;
		$data['record'] = $this->report_model->get_low_to_no_consumption_records($zone, $billingperiod, $usage_type, $max_cu);
		$data['preparedby'] = $this->my_model->get_employee($preparedby);
		$data['verifiedby'] = $this->my_model->get_employee($verifiedby);
		$data['approvedby'] = $this->my_model->get_employee($approvedby);
		send_print_or_pdf($this->lowtonoconsumptionprinttopdfPage, $data);
	}

	public function lowtonoconsumptionexporttopdf($billingperiod, $usage_type = 'both', $max_cu = 10, $zone = '', $preparedby = '', $verifiedby = '', $approvedby = '') {
		$this->downloadPdf = true;
		$bp = str_replace(' ', '_', urldecode($billingperiod));
		$bp = preg_replace('/[^A-Za-z0-9_-]/', '', $bp);
		$this->pdfFilename = 'Low_to_No_Consumption_'.$bp.'.pdf';
		$this->lowtonoconsumptionprinttopdf($billingperiod, $usage_type, $max_cu, $zone, $preparedby, $verifiedby, $approvedby);
	}

	public function lowtonoconsumptionexporttoexcel($billingperiod = '', $usage_type = 'both', $max_cu = 10, $zone = '', $preparedby = '', $verifiedby = '', $approvedby = '') {
		$this->_require_lowtonoconsumption_access();
		@ini_set('display_errors', 0);
		error_reporting(0);
		set_time_limit(600);
		ini_set('memory_limit', '512M');
		while (ob_get_level()) {
			@ob_end_clean();
		}
		$this->load->helper('excel');
		$billingperiod = urldecode($billingperiod);
		$usage_type = urldecode($usage_type);
		if ($usage_type === '' || $usage_type === '0') {
			$usage_type = 'both';
		}
		if ($max_cu === '' || $max_cu === null) {
			$max_cu = 10;
		}
		$records = $this->report_model->get_low_to_no_consumption_records($zone, $billingperiod, $usage_type, $max_cu);
		$zones = $this->my_model->get_zone($zone);
		$zone_name = '';
		if (count($zones) > 0 && isset($zones[0]['zone'])) {
			$zone_name = $zones[0]['zone'];
		}
		$billing_period_display = $billingperiod;
		$billing_period_parts = explode(' ', $billingperiod);
		if (count($billing_period_parts) == 2) {
			$month_name_result = getMonthName($billing_period_parts[0]);
			if (isset($month_name_result[0]) && isset($month_name_result[0]->month_name)) {
				$billing_period_display = $month_name_result[0]->month_name . ' ' . $billing_period_parts[1];
			}
		}
		$usage_label = 'No consumption and low (0 to ' . $max_cu . ' cu.m)';
		if ($usage_type === 'no') {
			$usage_label = 'No consumption (0 cu.m)';
		} elseif ($usage_type === 'low') {
			$usage_label = 'Low consumption (1 to ' . $max_cu . ' cu.m)';
		}
		$export_data = array();
		$export_data[] = array('LOW TO NO CONSUMPTION REPORT');
		$export_data[] = array('Billing Period: ' . $billing_period_display);
		$export_data[] = array('Usage: ' . $usage_label);
		if ($zone_name != '') {
			$export_data[] = array('Zone: ' . $zone_name);
		}
		$export_data[] = array('');
		$export_data[] = array('SN#', 'Customer ID', 'Customer Name', 'Zone', 'Classification', 'Meter No.', 'Previous', 'Current', 'Consumed (cu.m)', 'Amount', 'Ref No.', 'Flag');
		$index = 1;
		$count_no = 0;
		$count_low = 0;
		if (count($records) > 0) {
			foreach ($records as $row) {
				$consumed = isset($row['consumed']) ? (float) $row['consumed'] : 0;
				$flag = ($consumed == 0) ? 'NO CONSUMPTION' : 'LOW';
				if ($consumed == 0) {
					$count_no++;
				} else {
					$count_low++;
				}
				$export_data[] = array(
					$index,
					stripslashes($row['customer_id']),
					stripslashes(trim($row['last_name']) . ', ' . trim($row['first_name']) . ' ' . trim($row['middle_name'])),
					stripslashes(isset($row['zone']) ? $row['zone'] : ''),
					stripslashes(isset($row['class_name']) ? $row['class_name'] : ''),
					stripslashes(isset($row['meter_number']) ? $row['meter_number'] : ''),
					isset($row['previous_reading']) ? $row['previous_reading'] : '',
					isset($row['reading']) ? $row['reading'] : '',
					number_format($consumed, 2),
					number_format(isset($row['amount']) ? (float) $row['amount'] : 0, 2),
					isset($row['refno']) ? $row['refno'] : '',
					$flag
				);
				$index++;
			}
		}
		$export_data[] = array('');
		$export_data[] = array('No consumption', $count_no);
		$export_data[] = array('Low consumption', $count_low);
		$export_data[] = array('Total rows', $count_no + $count_low);
		$preparedby_data = $this->my_model->get_employee($preparedby);
		$verifiedby_data = $this->my_model->get_employee($verifiedby);
		$approvedby_data = $this->my_model->get_employee($approvedby);
		$export_data[] = array('');
		$export_data[] = array('Prepared by:', '', 'Verified by:');
		$export_data[] = array('');
		$export_data[] = array('');
		if (isset($preparedby_data[0]) && isset($verifiedby_data[0])) {
			$preparedby_name = strtoupper($preparedby_data[0]['first_name'] . ' ' . $preparedby_data[0]['middle_name'] . ' ' . $preparedby_data[0]['last_name']);
			$verifiedby_name = strtoupper($verifiedby_data[0]['first_name'] . ' ' . $verifiedby_data[0]['middle_name'] . ' ' . $verifiedby_data[0]['last_name']);
			$export_data[] = array($preparedby_name, '', $verifiedby_name);
			$export_data[] = array($preparedby_data[0]['jobtitle'], '', $verifiedby_data[0]['jobtitle']);
		}
		$export_data[] = array('');
		$export_data[] = array('Approved by:');
		$export_data[] = array('');
		if (isset($approvedby_data[0])) {
			$approvedby_name = strtoupper($approvedby_data[0]['first_name'] . ' ' . $approvedby_data[0]['middle_name'] . ' ' . $approvedby_data[0]['last_name']);
			$export_data[] = array($approvedby_name, '', 'Date/Time printed: ' . date('Y-m-d H:i:s'));
			$export_data[] = array($approvedby_data[0]['jobtitle']);
		}
		$filename = 'Low_to_No_Consumption_' . str_replace(' ', '_', $billing_period_display) . '_' . date('d-m-Y') . '.xls';
		array_to_excel($export_data, $filename);
	}

}
?>