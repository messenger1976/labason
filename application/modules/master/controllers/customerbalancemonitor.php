<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class customerbalancemonitor extends CI_Controller {

	public $headerPage = '../../views/admin-includes/header';
	public $listPage = 'customerbalancemonitor';
	public $ajaxPage = 'customerbalancemonitor_ajax';

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('customerbalancemonitor_model', 'my_model');
		$this->load->model('common_model', 'comm_model');
		$this->load->model('addcustomer_model', 'customer_model');
		$this->load->model('adddailyreport_model', 'daily_model');
		$this->load->model('adminheader_model', 'top_model');
	}

	/** Sub-admins must have customerbalancemonitor = 1 on their role. */
	private function _require_customerbalancemonitor_access() {
		$ut = $this->session->userdata('usertype');
		if ($ut === 'admin') {
			return;
		}
		if ($ut === 'subadmin') {
			$rr = $this->top_model->get_responsibilities();
			if (array_key_exists('customerbalancemonitor', $rr) && (int) $rr['customerbalancemonitor'] === 1) {
				$this->top_model->get_responsibilities_conditions($rr['customerbalancemonitor']);
				return;
			}
		}
		redirect('master/page/', 'refresh');
	}

	public function index() {
		$this->_require_customerbalancemonitor_access();
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$header['title'] = 'Customer Balance Monitor';
		$data['zone'] = $this->customer_model->get_zone();
		$this->load->view($this->headerPage, $header);
		$this->load->view($this->listPage, $data);
	}

	private function _get_search_filters() {
		$zone = $this->input->post('zone');
		$status = $this->input->post('status');
		$special_privilege = $this->input->post('special_privilege');
		$only_with_balance = $this->input->post('only_with_balance');

		$zone = ($zone === '' || $zone === null) ? 0 : (int) $zone;
		if ($status === '99' || $status === '' || $status === null) {
			$status = '';
		}
		$special_privilege = ($special_privilege === '' || $special_privilege === null) ? 0 : (int) $special_privilege;
		$only_with_balance = ($only_with_balance === '1' || $only_with_balance === 1) ? 1 : 0;

		return array(
			'zone' => $zone,
			'status' => $status,
			'special_privilege' => $special_privilege,
			'only_with_balance' => $only_with_balance
		);
	}

	/**
	 * POST: zone, status, special_privilege, only_with_balance (0|1)
	 */
	public function search() {
		$this->_require_customerbalancemonitor_access();
		@set_time_limit(600);
		@ini_set('memory_limit', '512M');

		$data['msg'] = '';
		$filters = $this->_get_search_filters();
		$zone = $filters['zone'];
		$status = $filters['status'];
		$special_privilege = $filters['special_privilege'];
		$only_with_balance = $filters['only_with_balance'];

		$customers = $this->my_model->get_customers($zone, $status, $special_privilege);
		$customers = $this->my_model->attach_statement_balances($customers);

		if ($only_with_balance) {
			$customers = array_values(array_filter($customers, function ($row) {
				$b = isset($row['total_balance']) ? (float) $row['total_balance'] : 0;
				return abs($b) >= 0.005;
			}));
		}

		$data['record'] = $customers;
		$data['grand_total_balance'] = 0;
		foreach ($customers as $r) {
			$data['grand_total_balance'] += isset($r['total_balance']) ? (float) $r['total_balance'] : 0;
		}

		$this->load->view($this->ajaxPage, $data);
	}

	public function search_batch() {
		$this->_require_customerbalancemonitor_access();
		@set_time_limit(600);
		@ini_set('memory_limit', '512M');

		$filters = $this->_get_search_filters();
		$zone = $filters['zone'];
		$status = $filters['status'];
		$special_privilege = $filters['special_privilege'];
		$only_with_balance = $filters['only_with_balance'];

		$batch_offset = (int) $this->input->post('batch_offset');
		if ($batch_offset < 0) {
			$batch_offset = 0;
		}
		$batch_size = 100;
		$total = (int) $this->my_model->count_customers($zone, $status, $special_privilege);

		$slice_customers = $this->my_model->get_customers($zone, $status, $special_privilege, $batch_size, $batch_offset);
		$raw_count = count($slice_customers);
		$slice = $this->my_model->attach_statement_balances($slice_customers);
		if ($only_with_balance) {
			$slice = array_values(array_filter($slice, function ($row) {
				$b = isset($row['total_balance']) ? (float) $row['total_balance'] : 0;
				return abs($b) >= 0.005;
			}));
		}

		$batch_balance_sum = 0;
		$rows = array();
		foreach ($slice as $row) {
			$bal = isset($row['total_balance']) ? (float) $row['total_balance'] : 0;
			$batch_balance_sum += $bal;
			$rows[] = array(
				'customer_id' => isset($row['customer_id']) ? (string) $row['customer_id'] : '',
				'first_name' => isset($row['first_name']) ? (string) $row['first_name'] : '',
				'middle_name' => isset($row['middle_name']) ? (string) $row['middle_name'] : '',
				'last_name' => isset($row['last_name']) ? (string) $row['last_name'] : '',
				'address' => isset($row['address']) ? (string) $row['address'] : '',
				'zone_name' => isset($row['zone_name']) ? (string) $row['zone_name'] : '',
				'total_balance' => $bal,
				'statement_url' => base_url() . 'master/statementofaccount/index/' . rawurlencode(isset($row['customer_id']) ? $row['customer_id'] : '')
			);
		}

		$next_offset = $batch_offset + $raw_count;
		if ($next_offset > $total) {
			$next_offset = $total;
		}
		$done = ($next_offset >= $total);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'ok' => true,
				'total' => $total,
				'batch_offset' => $batch_offset,
				'next_offset' => $next_offset,
				'row_count_in_batch' => count($rows),
				'batch_balance_sum' => $batch_balance_sum,
				'done' => $done,
				'rows' => $rows
			)));
	}
}
