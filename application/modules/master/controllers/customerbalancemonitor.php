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
		$header['title'] = 'Customer balance monitor';
		$data['zone'] = $this->customer_model->get_zone();
		$this->load->view($this->headerPage, $header);
		$this->load->view($this->listPage, $data);
	}

	/**
	 * POST: zone, status, special_privilege, only_with_balance (0|1)
	 */
	public function search() {
		$this->_require_customerbalancemonitor_access();
		@set_time_limit(600);
		@ini_set('memory_limit', '512M');

		$data['msg'] = '';
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
}
