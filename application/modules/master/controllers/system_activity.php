<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * System Activity — append-only audit trail viewer (Admin).
 */
class system_activity extends CI_Controller {

	public $headerPage = '../../views/admin-includes/header';
	public $listPage = 'system_activity';
	public $viewPage = 'system_activity_view';
	public $listPage_redirect = '/master/system_activity';
	public $table_name = 'tbl_system_activity';

	public function __construct() {
		parent::__construct();
		$this->load->model('system_activity_model', 'my_model');
		$this->load->model('adminheader_model', 'top_model');
		$this->load->helper('system_activity');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors', 'off');

		if ($this->session->userdata('usertype') == 'subadmin') {
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
			if (!array_key_exists('system_activity', $this->head['roleResponsible'])) {
				redirect('master/page/', 'refresh');
			}
			$perm = $this->head['roleResponsible']['system_activity'];
			$allowed = ((string) $perm === '1')
				|| (is_array($perm) && (in_array('l', $perm, true) || count($perm) > 0))
				|| (is_string($perm) && $perm !== '' && $perm !== '0');
			if (!$allowed) {
				redirect('master/page/', 'refresh');
			}
		} else {
			$this->head['roleResponsible'] = array();
		}
	}

	protected function _filters_from_request() {
		return array(
			'date_from' => trim((string) $this->input->get_post('date_from')),
			'date_to' => trim((string) $this->input->get_post('date_to')),
			'user_id' => trim((string) $this->input->get_post('user_id')),
			'category' => trim((string) $this->input->get_post('category')),
			'module' => trim((string) $this->input->get_post('module')),
			'action' => trim((string) $this->input->get_post('action')),
			'reference_no' => trim((string) $this->input->get_post('reference_no')),
			'ip_address' => trim((string) $this->input->get_post('ip_address')),
			'q' => trim((string) $this->input->get_post('q')),
		);
	}

	protected function _can_list($header) {
		if ($this->session->userdata('usertype') == 'admin') {
			return true;
		}
		if (!isset($header['roleResponsible']['system_activity'])) {
			return false;
		}
		$role = $header['roleResponsible']['system_activity'];
		if (is_array($role)) {
			return in_array('l', $role, true) || in_array(1, $role, true) || (isset($role[0]) && $role[0] === 'l');
		}
		return ((string) $role === '1' || (string) $role === 'l');
	}

	public function index() {
		$header['roleResponsible'] = $this->top_model->get_responsibilities();

		if ($this->session->userdata('usertype') == 'subadmin') {
			if (isset($header['roleResponsible']['system_activity'])) {
				$roleResponsible = $header['roleResponsible']['system_activity'];
				if ($this->uri->segment(3) == '' || $this->uri->segment(3) == 'index') {
					if ((is_array($roleResponsible) && !in_array('l', $roleResponsible)) || (!is_array($roleResponsible) && (string) $roleResponsible !== '1')) {
						redirect('master/page/', 'refresh');
					}
				}
			} else {
				redirect('master/page/', 'refresh');
			}
		}

		$filters = $this->_filters_from_request();
		if ($filters['date_from'] === '' && $filters['date_to'] === '') {
			$filters['date_from'] = date('Y-m-d', strtotime('-7 days'));
			$filters['date_to'] = date('Y-m-d');
		}

		$data['filters'] = $filters;
		$data['records'] = $this->my_model->search($filters, 500);
		$data['categories'] = $this->my_model->distinct_values('category');
		$data['modules'] = $this->my_model->distinct_values('module');
		$data['users'] = $this->my_model->distinct_users();
		$data['header'] = $header;
		$data['msg'] = '';

		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage, $header);
		$this->load->view($this->listPage, $data);
	}

	public function view($id = 0) {
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		if (!$this->_can_list($header)) {
			redirect('master/page/', 'refresh');
		}
		$id = (int) $id;
		$data['record'] = $this->my_model->get_by_id($id);
		if (empty($data['record'])) {
			$this->session->set_flashdata('msg_err', 'Activity record not found.');
			redirect($this->listPage_redirect);
		}
		$data['header'] = $header;
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage, $header);
		$this->load->view($this->viewPage, $data);
	}

	public function export() {
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		if (!$this->_can_list($header)) {
			redirect('master/page/', 'refresh');
		}

		$filters = $this->_filters_from_request();
		$rows = $this->my_model->search($filters, 5000);

		$filename = 'system_activity_'.date('Y-m-d_His').'.csv';
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		$out = fopen('php://output', 'w');
		fputcsv($out, array(
			'ID', 'When', 'User', 'Username', 'Type', 'Category', 'Action', 'Module',
			'Reference', 'Amount', 'Entity', 'Entity ID', 'IP', 'Summary', 'URI'
		));
		foreach ($rows as $row) {
			fputcsv($out, array(
				$row['id'],
				$row['created_at'],
				$row['user_name'],
				$row['username'],
				$row['usertype'],
				$row['category'],
				$row['action'],
				$row['module'],
				$row['reference_no'],
				$row['amount'],
				$row['entity_type'],
				$row['entity_id'],
				$row['ip_address'],
				$row['summary'],
				$row['uri'],
			));
		}
		fclose($out);
		exit;
	}
}
