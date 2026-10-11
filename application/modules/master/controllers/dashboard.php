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
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
		date_default_timezone_set('Asia/Manila');
    }

	public function index($mode =''){
		// Legacy JSON list endpoints (kept for compatibility).
		$legacy = array(
			'pagination'      => 'get_expenses_list',
			'paginationtwo'   => 'get_month_expenses_list',
			'paginationthree' => 'get_list_meter_income',
			'paginationfrst'  => 'get_customers_list',
		);
		if (isset($legacy[$mode])) {
			$fn = $legacy[$mode];
			$rows = $this->my_model->$fn();
			if (!empty($rows)) {
				echo json_encode(array('data' => $rows));
			}
			exit;
		}

		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['msg'] = '';

		$availableYears = $this->my_model->get_available_years();
		$selectedYear = (int)($this->input->get('year') ? $this->input->get('year') : date('Y'));
		if (empty($availableYears) || !in_array($selectedYear, $availableYears)) {
			$selectedYear = (int)date('Y');
		}
		$data['selected_year'] = $selectedYear;
		$data['available_years'] = $availableYears;

		$this->load->view($this->headerPage, $header);

		$data['dash'] = $this->build_dashboard($selectedYear);
		$this->load->view($this->listPage, $data);
	}

	private function build_dashboard($year) {
		$m = $this->my_model;
		$today          = date('Y-m-d');
		$monthStart     = date('Y-m-01');
		$prevMonthStart = date('Y-m-01', strtotime('first day of last month'));
		$prevMonthEnd   = date('Y-m-t', strtotime('first day of last month'));
		// Same number of days into last month, for a fair month-to-date comparison.
		$prevMonthSameDay = min(date('Y-m-d', strtotime($prevMonthStart.' +'.(date('j') - 1).' days')), $prevMonthEnd);

		$d = array();
		$d['today']        = $today;
		$d['coll_today']   = $m->get_collections_between($today, $today);
		$d['coll_mtd']     = $m->get_collections_between($monthStart, $today);
		$d['coll_prev_mtd']= $m->get_collections_between($prevMonthStart, $prevMonthSameDay);
		$d['coll_prev_month'] = $m->get_collections_between($prevMonthStart, $prevMonthEnd);
		$d['coll_ytd']     = $m->get_collections_between($year.'-01-01', $year.'-12-31');
		$d['daily']        = $m->get_daily_collections(30);
		$d['breakdown']    = $m->get_collection_breakdown($monthStart, $today);
		$d['breakdown_prev'] = $m->get_collection_breakdown($prevMonthStart, $prevMonthEnd);
		$d['by_month']     = $m->get_billing_by_month($year);
		$d['periods']      = $m->get_latest_billing_periods(2);
		$d['aging']        = $m->get_receivable_aging();
		$d['top_unpaid']   = $m->get_top_unpaid_accounts(10);
		$d['zones']        = $m->get_zone_summary();
		$d['consumption']  = $m->get_consumption_by_category($year, $d['by_month']);
		$d['mix']          = $m->get_customer_mix();
		$d['customers']    = $m->get_customer_counts($monthStart);
		$d['leaking']      = $m->get_leaking_summary();
		$d['tickets']      = $m->get_ticket_status_counts();
		$d['insights']     = $this->build_insights($d);
		return $d;
	}

	/** Plain-language findings for management, derived from the figures above. */
	private function build_insights($d) {
		$out = array();
		$months = array(1=>'January','February','March','April','May','June','July','August','September','October','November','December');

		if (!empty($d['periods'][0]) && $d['periods'][0]['billed'] > 0) {
			$p = $d['periods'][0];
			$eff = $p['paid'] / $p['billed'] * 100;
			$text = sprintf('Collection efficiency for the %s %d billing is <b>%.1f%%</b>', $months[(int)$p['m']], $p['y'], $eff);
			$tone = $eff >= 95 ? 'good' : ($eff >= 85 ? 'warn' : 'bad');
			if (!empty($d['periods'][1]) && $d['periods'][1]['billed'] > 0) {
				$q = $d['periods'][1];
				$prev = $q['paid'] / $q['billed'] * 100;
				$text .= sprintf(' (%s %.1f pts vs %s).', $eff >= $prev ? 'up' : 'down', abs($eff - $prev), $months[(int)$q['m']]);
			} else {
				$text .= '.';
			}
			$out[] = array('tone' => $tone, 'icon' => 'fa-percentage', 'text' => $text);
		}

		$a3 = $d['aging'][3];
		if ($a3['accounts'] > 0) {
			$out[] = array('tone' => 'bad', 'icon' => 'fa-user-slash', 'text' => sprintf(
				'<b>%s accounts</b> have 3 or more unpaid bills totalling <b>&#8369;%s</b>. These are candidates for a disconnection notice.',
				number_format($a3['accounts']), number_format($a3['total'], 2)));
		}

		$totalUnpaid = 0; $topZone = null;
		foreach ($d['zones'] as $z) {
			$totalUnpaid += $z['unpaid'];
			if ($topZone === null || $z['unpaid'] > $topZone['unpaid']) { $topZone = $z; }
		}
		if ($topZone && $topZone['unpaid'] > 0 && $totalUnpaid > 0) {
			$out[] = array('tone' => 'warn', 'icon' => 'fa-map-marker-alt', 'text' => sprintf(
				'<b>%s</b> carries the largest unpaid balance: &#8369;%s (%.0f%% of all receivables, %s accounts). Prioritise it for collection visits.',
				htmlspecialchars($topZone['zone']), number_format($topZone['unpaid'], 2),
				$topZone['unpaid'] / $totalUnpaid * 100, number_format($topZone['unpaid_accounts'])));
		}

		$mtd = (float)$d['coll_mtd']['total'];
		$prevMtd = (float)$d['coll_prev_mtd']['total'];
		if ($prevMtd > 0 && $mtd > 0) {
			$chg = ($mtd - $prevMtd) / $prevMtd * 100;
			$out[] = array('tone' => $chg >= 0 ? 'good' : 'warn', 'icon' => 'fa-coins', 'text' => sprintf(
				'Month-to-date collections are <b>&#8369;%s</b>, %s %.1f%% compared with the same days last month.',
				number_format($mtd, 2), $chg >= 0 ? 'up' : 'down', abs($chg)));
		} elseif ($mtd == 0 && (float)$d['coll_prev_month']['total'] > 0) {
			$out[] = array('tone' => 'info', 'icon' => 'fa-coins', 'text' => sprintf(
				'No collections posted yet this month. Last month closed at <b>&#8369;%s</b> from %s receipts.',
				number_format($d['coll_prev_month']['total'], 2), number_format($d['coll_prev_month']['receipts'])));
		}

		$best = null;
		foreach ($d['daily'] as $row) {
			if ($best === null || $row['total'] > $best['total']) { $best = $row; }
		}
		if ($best && $best['total'] > 0) {
			$out[] = array('tone' => 'info', 'icon' => 'fa-calendar-star', 'text' => sprintf(
				'Busiest cashier day in the last 30 days was <b>%s</b> with &#8369;%s from %s receipts. Plan teller staffing around due dates.',
				date('D, M j', strtotime($best['date'])), number_format($best['total'], 2), number_format($best['receipts'])));
		}

		if ((int)$d['leaking']['pending'] > 0) {
			$out[] = array('tone' => 'warn', 'icon' => 'fa-tint', 'text' => sprintf(
				'<b>%s leak-repair ledger(s)</b> are waiting for approval.', number_format($d['leaking']['pending'])));
		}
		return $out;
	}
	
}
?>
