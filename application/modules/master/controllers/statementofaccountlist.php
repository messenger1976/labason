<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class statementofaccountlist extends CI_Controller {

	public $headerPage = '../../views/admin-includes/header';
	public $listPage = 'statementofaccountlist';

	public function __construct() {
		parent::__construct();
		$this->head = array();
		$this->load->helper('common_helper');
		$this->load->model('addcustomer_model', 'my_model');
		$this->load->model('statementofaccount_model', 'soa_model');
		$this->load->model('common_model', 'comm_model');
		$this->load->model('adminheader_model', 'top_model');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors', 'off');
	}

	/** Admin always; sub-admin only when Statement of Account permission is granted. */
	private function _require_access() {
		$ut = $this->session->userdata('usertype');
		if ($ut === 'admin') {
			return;
		}
		if ($ut === 'subadmin') {
			$rr = $this->top_model->get_responsibilities();
			if (is_array($rr) && array_key_exists('statementofaccountlist', $rr) && (string) $rr['statementofaccountlist'] === '1') {
				$this->top_model->get_responsibilities_conditions($rr['statementofaccountlist']);
				return;
			}
		}
		redirect('master/page/', 'refresh');
	}

	public function index() {
		$this->_require_access();
		if ($this->session->userdata('usertype') == 'subadmin') {
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		} else {
			$this->head['roleResponsible'] = array();
		}
		$this->head['title'] = 'Statement of Account';
		$data['record'] = array();
		$data['zone'] = $this->my_model->get_zone();
		$this->load->model('adddailyreport_model', 'daily_model');
		$data['employee'] = $this->daily_model->get_employee();
		$this->load->view($this->headerPage, $this->head);
		$this->load->view($this->listPage, $data);
	}

	/** AJAX endpoint for DataTables server-side processing */
	public function get_datatable_data() {
		$this->_require_access();
		header('Content-Type: application/json');
		ob_start();

		try {
			@set_time_limit(180);
			$start = $this->input->post('start') ? intval($this->input->post('start')) : 0;
			$length = $this->input->post('length') ? intval($this->input->post('length')) : 100;
			$draw = $this->input->post('draw') ? intval($this->input->post('draw')) : 1;

			$search_post = $this->input->post('search');
			$search = '';
			if (is_array($search_post) && isset($search_post['value']) && $search_post['value'] !== '') {
				$search = trim($search_post['value']);
			}

			$zone = $this->input->post('zone');
			if ($zone === '' || $zone === 'all' || $zone === null) {
				$zone = '';
			} else {
				$zone = trim($zone);
			}

			$order_post = $this->input->post('order');
			$order_column_index = 2;
			$order_dir = 'asc';
			if (is_array($order_post) && isset($order_post[0]) && is_array($order_post[0])) {
				if (isset($order_post[0]['column'])) {
					$order_column_index = intval($order_post[0]['column']);
				}
				if (isset($order_post[0]['dir'])) {
					$order_dir = $order_post[0]['dir'];
				}
			}

			$columns = array(
				0 => 'tbl_addcustomer.id',
				1 => 'tbl_addcustomer.customer_id',
				2 => 'tbl_addcustomer.last_name',
				3 => 'tbl_addcustomer.address',
				4 => 'tbl_addcustomer.meter_number',
				5 => 'tbl_zone.zone',
				6 => 'tbl_classification.class_name',
				7 => 'tbl_addcustomer.status',
				8 => 'tbl_addcustomer.id',
				9 => 'tbl_addcustomer.id'
			);
			$order_column = isset($columns[$order_column_index]) ? $columns[$order_column_index] : 'tbl_zone.zone';

			$records = $this->my_model->get_paginated_records($start, $length, $search, $order_column, $order_dir, $zone);
			$total_records = $this->my_model->get_total_count('', $zone);
			$filtered_records = $this->my_model->get_total_count($search, $zone);

			$data = array();
			$i = $start + 1;
			foreach ($records as $row) {
				$row_status = isset($row['status']) ? $row['status'] : 0;
				$customer_code = isset($row['customer_id']) ? $row['customer_id'] : '';
				$soa_url = base_url() . 'master/statementofaccount/index/' . rawurlencode($customer_code);

				if ($row_status == 1) {
					$status_html = '<span class="badge badge-success badge-pill">Active</span>';
				} elseif ($row_status == 2) {
					$status_html = '<span class="badge badge-danger badge-pill">Disconnected</span>';
				} else {
					$status_html = '<span class="badge badge-warning badge-pill">De-Active</span>';
				}

				$action_html = '<a class="btn btn-sm btn-outline-primary" href="'.htmlspecialchars($soa_url, ENT_QUOTES, 'UTF-8').'" target="_blank" rel="noopener" title="Open SOA" data-toggle="tooltip">'
					. '<i class="fal fa-file-alt"></i>'
					. '</a>';

				$balance_html = '<span class="js-soa-bal text-muted" data-cid="'.htmlspecialchars($customer_code, ENT_QUOTES, 'UTF-8').'" title="Loading balance…">…</span>';

				$data[] = array(
					$i++,
					stripslashes($customer_code),
					stripslashes((isset($row['last_name']) ? $row['last_name'] : '').',  '.(isset($row['first_name']) ? $row['first_name'] : '').'  '.(isset($row['middle_name']) ? $row['middle_name'] : '')),
					stripslashes(isset($row['address']) ? $row['address'] : ''),
					stripslashes(isset($row['meter_number']) ? $row['meter_number'] : ''),
					stripslashes(isset($row['zones']) ? $row['zones'] : ''),
					stripslashes(isset($row['classification_name']) ? $row['classification_name'] : ''),
					$status_html,
					$balance_html,
					$action_html
				);
			}

			ob_clean();
			echo json_encode(array(
				'draw' => $draw,
				'recordsTotal' => $total_records,
				'recordsFiltered' => $filtered_records,
				'data' => $data
			));
			ob_end_flush();
			exit;
		} catch (Exception $e) {
			ob_clean();
			log_message('error', 'SOA list DataTables Error: ' . $e->getMessage());
			$draw = $this->input->post('draw') ? intval($this->input->post('draw')) : 1;
			echo json_encode(array(
				'draw' => $draw,
				'recordsTotal' => 0,
				'recordsFiltered' => 0,
				'data' => array(),
				'error' => 'An error occurred: ' . $e->getMessage()
			));
			ob_end_flush();
			exit;
		} catch (Error $e) {
			ob_clean();
			log_message('error', 'SOA list DataTables PHP Error: ' . $e->getMessage());
			$draw = $this->input->post('draw') ? intval($this->input->post('draw')) : 1;
			echo json_encode(array(
				'draw' => $draw,
				'recordsTotal' => 0,
				'recordsFiltered' => 0,
				'data' => array(),
				'error' => 'An error occurred: ' . $e->getMessage()
			));
			ob_end_flush();
			exit;
		}
	}

	/**
	 * AJAX: SOA balances for visible list rows (chunked from the browser).
	 * Keeps the main DataTables request fast on live hosts with short proxy timeouts.
	 */
	public function get_balances() {
		$this->_require_access();
		header('Content-Type: application/json');
		@set_time_limit(120);

		$ids = $this->input->post('customer_ids');
		if ( ! is_array($ids)) {
			$ids = array();
		}
		$clean = array();
		foreach ($ids as $id) {
			$id = trim((string) $id);
			if ($id !== '' && ! in_array($id, $clean, TRUE)) {
				$clean[] = $id;
			}
		}
		$clean = array_slice($clean, 0, 15);

		$balances = array();
		foreach ($clean as $customer_code) {
			try {
				$balances[$customer_code] = round((float) $this->soa_model->get_current_balance($customer_code), 2);
			} catch (Exception $e) {
				$balances[$customer_code] = null;
			} catch (Error $e) {
				$balances[$customer_code] = null;
			}
		}

		echo json_encode(array('ok' => TRUE, 'balances' => $balances));
		exit;
	}
}
