<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class manual_or_series extends CI_Controller {

	public $headerPage = '../../views/admin-includes/header';
	public $listPage = 'manual_or_series';
	public $listPage_redirect = '/master/manual_or_series';

	public function __construct() {
		parent::__construct();
		$this->load->model('manual_or_series_model', 'my_model');
		$this->load->library('form_validation');
		$this->load->model('adminheader_model', 'top_model');

		if ($this->session->userdata('usertype') == 'admin') {
			return;
		}
		if ($this->session->userdata('usertype') == 'subadmin') {
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
			if (!array_key_exists('manual_or_series', $this->head['roleResponsible'])) {
				redirect('master/page/', 'refresh');
			}
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['manual_or_series']);
		} else {
			redirect('master/page/', 'refresh');
		}
	}

	public function index() {
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['can_edit'] = $this->_can_edit();
		$data['rows'] = $this->my_model->get_or_series_rows();

		if ($this->input->post('save_series') != '') {
			if (!$data['can_edit']) {
				$this->session->set_flashdata('msg_err', 'You do not have permission to update OR series.');
				redirect($this->listPage_redirect);
			}
			$doc_id = (int) $this->input->post('doc_id');
			$num = $this->input->post('doc_series_num');
			if ($doc_id <= 0 || $num === null || $num === '') {
				$this->session->set_flashdata('msg_err', 'Invalid document or number.');
			} else {
				$n = (int) $num;
				if ($n < 0) {
					$this->session->set_flashdata('msg_err', 'Value must be zero or positive.');
				} else {
					if ($this->my_model->update_or_series_num($doc_id, $n)) {
						$this->session->set_flashdata('msg_succ', 'Saved. Next OR/SI issued will be ' . sprintf('%07d', $n + 1) . ' (unless another teller saves first).');
					} else {
						$this->session->set_flashdata('msg_err', 'Update failed.');
					}
				}
			}
			redirect($this->listPage_redirect);
		}

		$this->load->view($this->headerPage, $header);
		$this->load->view($this->listPage, $data);
	}

	private function _can_edit() {
		if ($this->session->userdata('usertype') == 'admin') {
			return true;
		}
		$role = $this->top_model->get_responsibilities();
		if (!isset($role['manual_or_series'])) {
			return false;
		}
		$rr = $role['manual_or_series'];
		if (is_array($rr)) {
			return in_array('e', $rr, true);
		}
		return (int) $rr === 1;
	}
}
