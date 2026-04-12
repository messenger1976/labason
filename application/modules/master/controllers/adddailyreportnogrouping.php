<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class adddailyreportnogrouping extends CI_Controller {
	// Declare globle variable here
	//This is updated for livesite
	public $headerPage = '../../views/admin-includes/header'; 
	
	
	public $listPage = 'adddailyreportnogrouping_add';		   //*****  View page   *****//
	public $searchPage ='adddailyreportnogrouping_search_ajax';
	public $printtopdfPage ='adddailyreportnogrouping_printtopdf';

	public function __construct() {
        parent::__construct();
  		$this->load->model('adddailyreport_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');
		$this->load->model('addcustomer_model','customer_model');	
		$this->load->model('leakingentry_model');	
		$this->load->model('addmetercustomerreading_model','meterreading_model'); 
		$this->load->library('form_validation');
		$this->load->library('Pdf');
		$this->load->helper('common');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }
	public function index(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		// No zone dropdown needed - always get all zones
		$data['employee'] = $this->my_model->get_employee();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}

	public function printtopdf($trans_date,$preparedby='',$verifiedby='',$approvedby=''){
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		
		// Convert date format from dd-mm-yyyy to Y-m-d for database query
		$date_parts = explode('-', $trans_date);
		if(count($date_parts) == 3){
			$trans_date_mysql = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
		} else {
			$trans_date_mysql = date('Y-m-d', strtotime($trans_date));
		}
		
		// Always set grouping to 0 (no grouping)
		$grouping = 0;
		$data['grouping'] = $grouping;
		$data['trans_date'] = date('M d, Y', strtotime($trans_date_mysql));
		$data['preparedby'] = $this->my_model->get_employee($preparedby);
		$data['verifiedby'] = $this->my_model->get_employee($verifiedby);
		$data['approvedby'] = $this->my_model->get_employee($approvedby);
		// Get all records without zone filter, sorted by or_number
		$data['record'] = $this->my_model->get_metercustomer_records($trans_date_mysql, '', $grouping);
		//$this->load->view($this->headerPage,$header);
		$this->load->view($this->printtopdfPage,$data);
	}

	public function exporttoexcel($trans_date,$preparedby='',$verifiedby='',$approvedby=''){
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
		
		// Convert date format from dd-mm-yyyy to Y-m-d for database query
		$date_parts = explode('-', $trans_date);
		if(count($date_parts) == 3){
			$trans_date_mysql = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
		} else {
			$trans_date_mysql = date('Y-m-d', strtotime($trans_date));
		}
		
		$trans_date_formatted = date('M d, Y', strtotime($trans_date_mysql));
		$mysql_transdate = $trans_date_mysql;
		$current_billing_period_year = date('Y', strtotime($trans_date_mysql));
		
		// Always set grouping to 0 (no grouping)
		$grouping = 0;
		
		// Prepare export data array
		$export_data = array();
		
		// Add header rows
		$export_data[] = array('DAILY COLLECTION REPORT - NO GROUPING');
		$export_data[] = array($trans_date_formatted);
		$export_data[] = array(''); // Empty row
		
		// Add column headers
		$export_data[] = array(
			'OR #',
			'Concessionaires',
			'Total Amount Collected',
			'Current',
			'Arrears',
			'Previous Year',
			'WMMF',
			'Penalty',
			'SC Disc',
			'Leaking Disc',
			'A/R-Leaking',
			'A/R-Leaking Balance',
			'VAT',
			'Franchise Tax'
		);
		
		// Initialize grand totals
		$grand_total_collected = 0;
		$grand_total_current = 0;
		$grand_total_arrears = 0;
		$grand_total_wmmf = 0;
		$grand_total_penalty = 0;
		$grand_total_vat = 0;
		$grand_total_leaking = 0;
		$grand_total_sc = 0;
		$grand_total_ar_leaking = 0;
		$grand_total_ar_leaking_balance = 0;
		$grand_total_franchise_fee = 0;
		
		// Pre-load all leaking A/R data for the transaction date to avoid N+1 queries
		$leaking_ar_lookup = array();
		$get_all_leaking = array();
		try {
			$get_all_leaking = $this->leakingentry_model->get_soa_statement_transdate($mysql_transdate);
			if (!is_array($get_all_leaking)) {
				$get_all_leaking = array();
			}
			foreach($get_all_leaking as $leaking_record){
				if(isset($leaking_record['leakingledgerdetails_or_number'])){
					$or_key = sprintf('%07d', $leaking_record['leakingledgerdetails_or_number']);
					$leaking_ar_lookup[$or_key] = $leaking_record;
				}
			}
		} catch (Exception $e) {
			$get_all_leaking = array();
		}
		
		// Get all records without grouping, sorted by or_number
		$get_dailytrans = $this->my_model->get_metercustomer_records($mysql_transdate, '', $grouping);
		
		// Process each transaction
		foreach($get_dailytrans as $key => $gdailytrans){
			$prev_year = get_customer_unpaid_records($gdailytrans['customer_id'],'12',$current_billing_period_year-1);
			$ar_leaking = array('leaking_total_amount' => 0, 'leaking_balance' => 0);
			if(isset($gdailytrans['leaking_amount']) && $gdailytrans['leaking_amount'] > 0){
				$ornumber_search = sprintf('%07d', $gdailytrans['or_number']);
				// Use pre-loaded lookup instead of querying database
				if(isset($leaking_ar_lookup[$ornumber_search])){
					$ar_leaking = $leaking_ar_lookup[$ornumber_search];
					if(isset($ar_leaking['leaking_balance'])){
						$gdailytrans['grand_total'] = $gdailytrans['grand_total'] - $ar_leaking['leaking_balance'];
					}
				} else {
					// Fallback to direct query if not in lookup
					$ar_leaking = $this->leakingentry_model->get_soa_statement_OR($ornumber_search);
					if(isset($ar_leaking['leaking_balance'])){
						$gdailytrans['grand_total'] = $gdailytrans['grand_total'] - $ar_leaking['leaking_balance'];
					}
				}
			}

			$penalty_val = (float)$gdailytrans['total_penalty'];
			$arrears_val = (float)$gdailytrans['arrears_amount'];
			$is_billing_period_arrears = false;
			if($penalty_val > 0 && $arrears_val > 0){
				$is_billing_period_arrears = true;
			} elseif($penalty_val > 0 && isset($gdailytrans['due_date']) && $gdailytrans['due_date'] != ''){
				$pay_ts = strtotime($gdailytrans['date']);
				$due_ts = strtotime($gdailytrans['due_date']);
				if($pay_ts && $due_ts){
					$is_billing_period_arrears = (date('m', $pay_ts) != date('m', $due_ts)) || (date('Y', $pay_ts) != date('Y', $due_ts));
				}
			}

			$display_arrears_amount = $gdailytrans['arrears_amount'];
			$display_penalty_amount = $gdailytrans['total_penalty'];
			if($is_billing_period_arrears){
				$display_arrears_amount = $display_arrears_amount + $display_penalty_amount;
				$display_penalty_amount = 0;
			}
			
			// Add transaction row
			$export_data[] = array(
				sprintf('%07d', $gdailytrans['or_number']),
				$gdailytrans['last_name'] . ', ' . $gdailytrans['first_name'] . ' ' . $gdailytrans['middle_name'],
				number_format($gdailytrans['grand_total'], 2),
				number_format($gdailytrans['current_amount'], 2),
				number_format($display_arrears_amount, 2),
				number_format($prev_year, 2),
				number_format($gdailytrans['total_wmmf'], 2),
				number_format($display_penalty_amount, 2),
				number_format(isset($gdailytrans['sc_discount']) ? $gdailytrans['sc_discount'] : 0, 2),
				number_format(isset($gdailytrans['leaking_amount']) ? $gdailytrans['leaking_amount'] : 0, 2),
				number_format(isset($ar_leaking['leaking_total_amount']) ? $ar_leaking['leaking_total_amount'] : 0, 2),
				number_format(isset($ar_leaking['leaking_balance']) ? $ar_leaking['leaking_balance'] : 0, 2),
				number_format(isset($gdailytrans['vat_amount']) ? $gdailytrans['vat_amount'] : 0, 2),
				number_format(isset($gdailytrans['total_franchise_fee']) ? $gdailytrans['total_franchise_fee'] : 0, 2)
			);
			
			// Accumulate grand totals
			$grand_total_collected += $gdailytrans['grand_total'];
			$grand_total_current += $gdailytrans['current_amount'];
			$grand_total_arrears += $display_arrears_amount;
			$grand_total_wmmf += $gdailytrans['total_wmmf'];
			$grand_total_penalty += $display_penalty_amount;
			$grand_total_vat += isset($gdailytrans['vat_amount']) ? $gdailytrans['vat_amount'] : 0;
			$grand_total_leaking += isset($gdailytrans['leaking_amount']) ? $gdailytrans['leaking_amount'] : 0;
			$grand_total_sc += isset($gdailytrans['sc_discount']) ? $gdailytrans['sc_discount'] : 0;
			$grand_total_ar_leaking += isset($ar_leaking['leaking_total_amount']) ? $ar_leaking['leaking_total_amount'] : 0;
			$grand_total_ar_leaking_balance += isset($ar_leaking['leaking_balance']) ? $ar_leaking['leaking_balance'] : 0;
			$grand_total_franchise_fee += isset($gdailytrans['total_franchise_fee']) ? $gdailytrans['total_franchise_fee'] : 0;
		}
		
		// Add LEAKING A/R PAYMENT REPORT section
		$export_data[] = array('', 'LEAKING A/R PAYMENT REPORT', '', '', '', '', '', '', '', '', '', '', '', '');
		
		// Use already loaded leaking data (no need to query again)
		$get_dailytrans1 = $get_all_leaking;
		$total_leaking_ar = 0;
		
		foreach($get_dailytrans1 as $key => $gdailytrans1){
			$export_data[] = array(
				$gdailytrans1['leakingledgerdetails_source_type'] . '#' . $gdailytrans1['leakingledgerdetails_or_number'],
				$gdailytrans1['last_name'] . ', ' . $gdailytrans1['first_name'],
				number_format($gdailytrans1['leakingledgerdetails_amount'], 2),
				'',
				'',
				'',
				'',
				'',
				'',
				number_format($gdailytrans1['leaking_total_amount'], 2),
				number_format($gdailytrans1['leakingledgerdetails_balance'], 2),
				'',
				'',
				''
			);
			$total_leaking_ar += $gdailytrans1['leakingledgerdetails_amount'];
		}
		
		// Add leaking A/R total
		$export_data[] = array(
			'',
			'TOTAL',
			number_format($total_leaking_ar, 2),
			'',
			'',
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
		
		$grand_total_collected += $total_leaking_ar;
		
		// Add empty row
		$export_data[] = array('');
		
		// Add Grand Total row
		$export_data[] = array(
			'',
			'Grand Total',
			number_format($grand_total_collected, 2),
			number_format($grand_total_current, 2),
			number_format($grand_total_arrears, 2),
			'0.00',
			number_format($grand_total_wmmf, 2),
			number_format($grand_total_penalty, 2),
			number_format($grand_total_sc, 2),
			number_format($grand_total_leaking, 2),
			number_format($grand_total_ar_leaking, 2),
			number_format($grand_total_ar_leaking_balance, 2),
			number_format($grand_total_vat, 2),
			number_format($grand_total_franchise_fee, 2)
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
		$filename = 'Daily_Collection_Report_No_Grouping_' . date('d-m-Y', strtotime($trans_date_mysql)) . '.xls';
		
		// Export to Excel (exit is handled in array_to_excel function)
		array_to_excel($export_data, $filename);
	}
	
	public function getadddailyreportsearch()
	{		//*****  Add Search records  *****//
			$data['msg'] ='';
			if($this->input->post('fromdate') !=''){
				if($this->input->post('fromdate') ==''){
					$selBox ='<h6><span style="color:red">Dear Admin Please select customer type option to search feilds</h6>' ;
					echo $selBox;
				}
				if($this->input->post('fromdate') !=''){
					$fromdate = $this->input->post('fromdate');
					$from = date('Y-m-d', strtotime($fromdate));
					
					// Always set grouping to 0 (no grouping) and no zone filter
					$grouping = 0;
					$data['grouping'] = $grouping;
					$data['record'] = $this->my_model->get_metercustomer_records($from, '', $grouping);
					$this->load->view($this->searchPage,$data);
				}		
			}else{
				$selBox ='<h6><span style="color:red">Dear Admin Please select from-date and to-date</h6>' ;
				echo $selBox;				
			}
	}	
	
}
?>
