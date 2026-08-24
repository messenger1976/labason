<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class adddailyreport extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	
	public $listPage = 'adddailyreport_add';		   //*****  View page   *****//
	public $searchPage ='adddaily_search _ajax';
	public $printtopdfPage ='adddailyreport_printtopdf';
	public $downloadPdf = false;
	public $pdfFilename = 'Daily_Collection_Report.pdf';

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
		$this->load->helper('pdf');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }
	public function index(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$data['employee'] = $this->my_model->get_employee();
		$data['cashier_list'] = $this->my_model->get_cashiers();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}

	public function printtopdf($trans_date,$zone='',$preparedby='',$verifiedby='',$approvedby='',$grouping=1,$cashier=0){
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		// URL date is dd-mm-yyyy (e.g. 06-08-2026)
		$date_parts = explode('-', $trans_date);
		if(count($date_parts) == 3 && strlen($date_parts[2]) == 4){
			$trans_date_mysql = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
		} else {
			$trans_date_mysql = date('Y-m-d', strtotime($trans_date));
		}
		$grouping = (int)$grouping;
		$cashier = (int)$cashier;
		$data['zone'] = $this->my_model->get_zone($zone);
		$data['trans_date'] = date('M d, Y', strtotime($trans_date_mysql));
		$data['trans_date_mysql'] = $trans_date_mysql;
		$data['preparedby'] = $this->my_model->get_employee($preparedby);
		$data['verifiedby'] = $this->my_model->get_employee($verifiedby);
		$data['approvedby'] = $this->my_model->get_employee($approvedby);
		$data['grouping'] = $grouping;
		$data['cashier'] = $cashier;
		$data['cashier_info'] = $this->my_model->get_cashiers($cashier);

		// Performance only: preload once; display math stays in the print view.
		$leaking_data = $this->leakingentry_model->get_leaking_ar_data_for_daily_report($trans_date_mysql);
		$data['leaking_record'] = $leaking_data['records'];
		$data['leaking_ar_lookup'] = $leaking_data['lookup'];

		$all_transactions = $this->my_model->get_metercustomer_records($trans_date_mysql, $zone, $grouping, $cashier);
		$data['transactions_by_zone'] = $this->my_model->group_metercustomer_records_by_zone($all_transactions);
		$data['orphan_record'] = $this->my_model->get_metercustomer_orphan_records($trans_date_mysql, $cashier, $grouping);
		send_print_or_pdf($this->printtopdfPage, $data);
	}

	public function exporttopdf($trans_date,$zone='',$preparedby='',$verifiedby='',$approvedby='',$grouping=1,$cashier=0){
		$this->downloadPdf = true;
		$safe_date = preg_replace('/[^0-9-]/', '', $trans_date);
		$this->pdfFilename = 'Daily_Collection_Report_'.$safe_date.'.pdf';
		$this->printtopdf($trans_date,$zone,$preparedby,$verifiedby,$approvedby,$grouping,$cashier);
	}

	public function exporttoexcel($trans_date,$zone='',$preparedby='',$verifiedby='',$approvedby='',$grouping=1,$cashier=0){
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
		
		// Get zone data
		$zones = $this->my_model->get_zone($zone);
		$trans_date_formatted = date('M d, Y', strtotime($trans_date_mysql));
		$mysql_transdate = $trans_date_mysql;
		$current_billing_period_year = date('Y', strtotime($trans_date_mysql));
		
		// Prepare export data array
		$export_data = array();
		
		// Add header rows
		$export_data[] = array('DAILY COLLECTION REPORT');
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
		$leaking_data = $this->leakingentry_model->get_leaking_ar_data_for_daily_report($mysql_transdate);
		$leaking_ar_lookup = $leaking_data['lookup'];
		$get_all_leaking = $leaking_data['records'];

		$orphan_dailytrans = $this->my_model->get_metercustomer_orphan_records($mysql_transdate, $cashier, $grouping);
		
		// Process each zone
		if(count($zones) > 0){
			foreach($zones as $key => $row){
				// Get daily transactions for this zone
				$get_dailytrans = $this->my_model->get_metercustomer_records($mysql_transdate, $row['id'], $grouping, $cashier);

				// Skip zones with no collection records
				if(empty($get_dailytrans) || count($get_dailytrans) == 0){
					continue;
				}

				// Add zone header
				$export_data[] = array('', stripslashes($row['zone']), '', '', '', '', '', '', '', '', '', '', '', '');
				
				// Initialize zone totals
				$total_grand_zone = 0;
				$total_current_zone = 0;
				$total_arrears_zone = 0;
				$total_penalty_zone = 0;
				$total_wmmf_zone = 0;
				$total_vat_zone = 0;
				$total_leaking_zone = 0;
				$total_ar_leaking_zone = 0;
				$total_ar_leaking_balance_zone = 0;
				$total_sc_zone = 0;
				$total_franchise_fee_zone = 0;
				
				// Process each transaction
				foreach($get_dailytrans as $key => $gdailytrans){
					$prev_year = get_customer_unpaid_records($gdailytrans['customer_id'],'12',$current_billing_period_year-1);
					$ar_leaking = array('leaking_total_amount' => 0, 'leaking_balance' => 0);
					if(isset($gdailytrans['leaking_amount']) && $gdailytrans['leaking_amount'] > 0){
						$ornumber_search = sprintf('%07d', $gdailytrans['or_number']);
						$leaking_detail = isset($leaking_ar_lookup[$ornumber_search]) ? $leaking_ar_lookup[$ornumber_search] : null;
						$ar_leaking = $this->leakingentry_model->resolve_ar_leaking_for_daily_report(
							$gdailytrans['or_number'],
							isset($gdailytrans['customer_id']) ? $gdailytrans['customer_id'] : null,
							$leaking_ar_lookup
						);
						$gdailytrans['grand_total'] = $this->leakingentry_model->get_collected_amount_for_leaking_payment(
							$gdailytrans['grand_total'],
							isset($gdailytrans['pay_amount']) ? $gdailytrans['pay_amount'] : 0,
							isset($gdailytrans['or_number']) ? $gdailytrans['or_number'] : null,
							$leaking_detail
						);
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
					
					// Accumulate zone totals
					$total_grand_zone += $gdailytrans['grand_total'];
					$total_current_zone += $gdailytrans['current_amount'];
					$total_arrears_zone += $display_arrears_amount;
					$total_wmmf_zone += $gdailytrans['total_wmmf'];
					$total_penalty_zone += $display_penalty_amount;
					$total_vat_zone += isset($gdailytrans['vat_amount']) ? $gdailytrans['vat_amount'] : 0;
					$total_leaking_zone += isset($gdailytrans['leaking_amount']) ? $gdailytrans['leaking_amount'] : 0;
					$total_sc_zone += isset($gdailytrans['sc_discount']) ? $gdailytrans['sc_discount'] : 0;
					$total_ar_leaking_zone += isset($ar_leaking['leaking_total_amount']) ? $ar_leaking['leaking_total_amount'] : 0;
					$total_ar_leaking_balance_zone += isset($ar_leaking['leaking_balance']) ? $ar_leaking['leaking_balance'] : 0;
					$total_franchise_fee_zone += isset($gdailytrans['total_franchise_fee']) ? $gdailytrans['total_franchise_fee'] : 0;
				}
				
				// Add zone total row
				$export_data[] = array(
					'',
					'TOTAL',
					number_format($total_grand_zone, 2),
					number_format($total_current_zone, 2),
					number_format($total_arrears_zone, 2),
					'0.00',
					number_format($total_wmmf_zone, 2),
					number_format($total_penalty_zone, 2),
					number_format($total_sc_zone, 2),
					number_format($total_leaking_zone, 2),
					number_format($total_ar_leaking_zone, 2),
					number_format($total_ar_leaking_balance_zone, 2),
					number_format($total_vat_zone, 2),
					number_format($total_franchise_fee_zone, 2)
				);
				
				// Accumulate grand totals
				$grand_total_collected += $total_grand_zone;
				$grand_total_current += $total_current_zone;
				$grand_total_arrears += $total_arrears_zone;
				$grand_total_wmmf += $total_wmmf_zone;
				$grand_total_penalty += $total_penalty_zone;
				$grand_total_vat += $total_vat_zone;
				$grand_total_leaking += $total_leaking_zone;
				$grand_total_sc += $total_sc_zone;
				$grand_total_ar_leaking += $total_ar_leaking_zone;
				$grand_total_ar_leaking_balance += $total_ar_leaking_balance_zone;
				$grand_total_franchise_fee += $total_franchise_fee_zone;
				
				// Add empty row between zones
				$export_data[] = array('');
			}
		}

		if(count($orphan_dailytrans) > 0){
			$export_data[] = array('', 'UNASSIGNED / MISSING CUSTOMER', '', '', '', '', '', '', '', '', '', '', '', '');
			$total_grand_zone = 0;
			$total_current_zone = 0;
			$total_arrears_zone = 0;
			$total_penalty_zone = 0;
			$total_wmmf_zone = 0;
			$total_vat_zone = 0;
			$total_leaking_zone = 0;
			$total_ar_leaking_zone = 0;
			$total_ar_leaking_balance_zone = 0;
			$total_sc_zone = 0;
			$total_franchise_fee_zone = 0;

			foreach($orphan_dailytrans as $key => $gdailytrans){
				$prev_year = 0;
				$ar_leaking = array('leaking_total_amount' => 0, 'leaking_balance' => 0);
				if(isset($gdailytrans['leaking_amount']) && $gdailytrans['leaking_amount'] > 0){
					$ornumber_search = sprintf('%07d', $gdailytrans['or_number']);
					$leaking_detail = isset($leaking_ar_lookup[$ornumber_search]) ? $leaking_ar_lookup[$ornumber_search] : null;
					$ar_leaking = $this->leakingentry_model->resolve_ar_leaking_for_daily_report(
						$gdailytrans['or_number'],
						isset($gdailytrans['customer_id']) ? $gdailytrans['customer_id'] : null,
						$leaking_ar_lookup
					);
					$gdailytrans['grand_total'] = $this->leakingentry_model->get_collected_amount_for_leaking_payment(
						$gdailytrans['grand_total'],
						isset($gdailytrans['pay_amount']) ? $gdailytrans['pay_amount'] : 0,
						isset($gdailytrans['or_number']) ? $gdailytrans['or_number'] : null,
						$leaking_detail
					);
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

				$export_data[] = array(
					sprintf('%07d', $gdailytrans['or_number']),
					trim($gdailytrans['last_name'] . ', ' . $gdailytrans['first_name'] . ' ' . $gdailytrans['middle_name']) . ' [' . $gdailytrans['customer_id'] . ']',
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

				$total_grand_zone += $gdailytrans['grand_total'];
				$total_current_zone += $gdailytrans['current_amount'];
				$total_arrears_zone += $display_arrears_amount;
				$total_wmmf_zone += $gdailytrans['total_wmmf'];
				$total_penalty_zone += $display_penalty_amount;
				$total_vat_zone += isset($gdailytrans['vat_amount']) ? $gdailytrans['vat_amount'] : 0;
				$total_leaking_zone += isset($gdailytrans['leaking_amount']) ? $gdailytrans['leaking_amount'] : 0;
				$total_sc_zone += isset($gdailytrans['sc_discount']) ? $gdailytrans['sc_discount'] : 0;
				$total_ar_leaking_zone += isset($ar_leaking['leaking_total_amount']) ? $ar_leaking['leaking_total_amount'] : 0;
				$total_ar_leaking_balance_zone += isset($ar_leaking['leaking_balance']) ? $ar_leaking['leaking_balance'] : 0;
				$total_franchise_fee_zone += isset($gdailytrans['total_franchise_fee']) ? $gdailytrans['total_franchise_fee'] : 0;
			}

			$export_data[] = array(
				'',
				'TOTAL',
				number_format($total_grand_zone, 2),
				number_format($total_current_zone, 2),
				number_format($total_arrears_zone, 2),
				'0.00',
				number_format($total_wmmf_zone, 2),
				number_format($total_penalty_zone, 2),
				number_format($total_sc_zone, 2),
				number_format($total_leaking_zone, 2),
				number_format($total_ar_leaking_zone, 2),
				number_format($total_ar_leaking_balance_zone, 2),
				number_format($total_vat_zone, 2),
				number_format($total_franchise_fee_zone, 2)
			);
			$export_data[] = array('');

			$grand_total_collected += $total_grand_zone;
			$grand_total_current += $total_current_zone;
			$grand_total_arrears += $total_arrears_zone;
			$grand_total_wmmf += $total_wmmf_zone;
			$grand_total_penalty += $total_penalty_zone;
			$grand_total_vat += $total_vat_zone;
			$grand_total_leaking += $total_leaking_zone;
			$grand_total_sc += $total_sc_zone;
			$grand_total_ar_leaking += $total_ar_leaking_zone;
			$grand_total_ar_leaking_balance += $total_ar_leaking_balance_zone;
			$grand_total_franchise_fee += $total_franchise_fee_zone;
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
		$filename = 'Daily_Collection_Report_' . date('d-m-Y', strtotime($trans_date_mysql)) . '.xls';
		
		// Export to Excel (exit is handled in array_to_excel function)
		array_to_excel($export_data, $filename);
	}
	
	public function getadddailyreportsearch()
	{		//*****  Add Search records  *****//
			$data['msg'] ='';
			//echo '<pre>'; print_r($this->input->post('zone'));exit;
			if($this->input->post('fromdate') !=''){
				if($this->input->post('fromdate') ==''){
					$selBox ='<h6><span style="color:red">Dear Admin Please select customer type option to search feilds</h6>' ;
					echo $selBox;
				}
				if($this->input->post('fromdate') !=''){
					$zone = $this->input->post('zone');
					$cashier = (int)$this->input->post('cashier');
					$fromdate = $this->input->post('fromdate');
					$todate = $this->input->post('todate');
					$grouping = $this->input->post('grouping');
					// fromdate is dd-mm-yyyy
					$date_parts = explode('-', $fromdate);
					if(count($date_parts) == 3 && strlen($date_parts[2]) == 4){
						$from = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
					} else {
						$from = date('Y-m-d', strtotime($fromdate));
					}
					
					$data['record'] = array_merge(
						$this->my_model->get_metercustomer_records($from,$zone,$grouping,$cashier),
						$this->my_model->get_metercustomer_orphan_records($from,$cashier,$grouping)
					);
					// Payments added from Leaking Ledger Details (source_module = leaking)
					$leaking_ar = $this->leakingentry_model->get_soa_statement_transdate($from);
					$data['leaking_record'] = is_array($leaking_ar) ? $leaking_ar : array();
					$this->load->view($this->searchPage,$data);
				}		
			}else{
				$selBox ='<h6><span style="color:red">Dear Admin Please select from-date and to-date</h6>' ;
				echo $selBox;				
			}
	}	
	
}
?>