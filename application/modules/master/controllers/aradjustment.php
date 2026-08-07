<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class aradjustment extends CI_Controller {

	public $headerPage = '../../views/admin-includes/header';
	public $listPage = 'aradjustment';
	public $addPage = 'aradjustment_add';
	public $editPage = 'aradjustment_edit';
	public $viewPage = 'aradjustment_view';
	public $listPage_redirect = '/master/aradjustment';

	public function __construct() {
		parent::__construct();
		$this->load->model('aradjustment_model', 'my_model');
		$this->load->model('adminheader_model', 'top_model');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		ini_set('date.timezone', 'Asia/Manila');

		if ($this->session->userdata('usertype') == 'admin') {
			return;
		}
		if ($this->session->userdata('usertype') == 'subadmin') {
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
			if (!array_key_exists('ar_adjustment', $this->head['roleResponsible'])) {
				redirect('master/page/', 'refresh');
			}
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['ar_adjustment']);
		} else {
			redirect('master/page/', 'refresh');
		}
	}

	private function _roles() {
		return $this->top_model->get_responsibilities();
	}

	private function _can_approve() {
		if ($this->session->userdata('usertype') == 'admin') {
			return true;
		}
		$role = $this->_roles();
		return array_key_exists('ar_adjustment_approve', $role) && (int) $role['ar_adjustment_approve'] === 1;
	}

	private function _form_lookups() {
		return array(
			'ledgers' => $this->my_model->fetch_ledgers(),
			'months' => $this->my_model->fetch_months(),
			'can_approve' => $this->_can_approve(),
			'gl_defaults' => $this->my_model->recommended_gl_map(),
		);
	}

	public function index() {
		$header['roleResponsible'] = $this->_roles();
		$data = $this->_form_lookups();
		$data['records'] = $this->my_model->get_all_records();
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage, $header);
		$this->load->view($this->listPage, $data);
	}

	public function add() {
		$header['roleResponsible'] = $this->_roles();
		$data = $this->_form_lookups();
		$data['msg'] = '';

		if ($this->input->post('save_draft') != '') {
			$result = $this->my_model->add_draft();
			if (!empty($result['ok'])) {
				$this->session->set_flashdata('msg_succ', 'Draft '.$result['adj_no'].' saved successfully.');
				redirect($this->listPage_redirect);
			}
			$data['msg'] = isset($result['message']) ? $result['message'] : 'Not saved.';
		}

		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage, $header);
		$this->load->view($this->addPage, $data);
	}

	public function edit($id = 0) {
		$id = (int) $id;
		$header['roleResponsible'] = $this->_roles();
		$data = $this->_form_lookups();
		$data['record'] = $this->my_model->get_single_record($id);
		$data['msg'] = '';

		if (empty($data['record'])) {
			$this->session->set_flashdata('msg_err', 'Record not found.');
			redirect($this->listPage_redirect);
		}
		if ((int) $data['record']['status'] !== aradjustment_model::STATUS_DRAFT) {
			$this->session->set_flashdata('msg_err', 'Only draft adjustments can be edited.');
			redirect($this->listPage_redirect.'/view/'.$id);
		}

		if ($this->input->post('save_draft') != '') {
			$result = $this->my_model->update_draft($id);
			if (!empty($result['ok'])) {
				$this->session->set_flashdata('msg_succ', 'Draft updated successfully.');
				redirect($this->listPage_redirect);
			}
			$data['msg'] = isset($result['message']) ? $result['message'] : 'Not updated.';
			$data['record'] = array_merge($data['record'], $_POST);
		}

		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage, $header);
		$this->load->view($this->editPage, $data);
	}

	public function view($id = 0) {
		$id = (int) $id;
		$header['roleResponsible'] = $this->_roles();
		$data = $this->_form_lookups();
		$data['record'] = $this->my_model->get_single_record($id);
		if (empty($data['record'])) {
			$this->session->set_flashdata('msg_err', 'Record not found.');
			redirect($this->listPage_redirect);
		}
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage, $header);
		$this->load->view($this->viewPage, $data);
	}

	public function post($id = 0) {
		if (!$this->_can_approve()) {
			$this->session->set_flashdata('msg_err', 'You do not have permission to post AR Adjustments.');
			redirect($this->listPage_redirect);
		}
		$id = (int) $id;
		$result = $this->my_model->post_adjustment($id);
		if (!empty($result['ok'])) {
			$this->session->set_flashdata('msg_succ', 'Adjustment posted. SOA and GL updated.');
		} else {
			$this->session->set_flashdata('msg_err', isset($result['message']) ? $result['message'] : 'Post failed.');
		}
		redirect($this->listPage_redirect.'/view/'.$id);
	}

	public function void_entry($id = 0) {
		if (!$this->_can_approve()) {
			$this->session->set_flashdata('msg_err', 'You do not have permission to void AR Adjustments.');
			redirect($this->listPage_redirect);
		}
		$id = (int) $id;
		$result = $this->my_model->void_adjustment($id);
		if (!empty($result['ok'])) {
			$this->session->set_flashdata('msg_succ', 'Adjustment voided. Reversing GL entry posted; removed from SOA.');
		} else {
			$this->session->set_flashdata('msg_err', isset($result['message']) ? $result['message'] : 'Void failed.');
		}
		redirect($this->listPage_redirect.'/view/'.$id);
	}

	public function delete($id = 0) {
		$id = (int) $id;
		if ($this->my_model->delete_draft($id)) {
			$this->session->set_flashdata('msg_succ', 'Draft deleted.');
		} else {
			$this->session->set_flashdata('msg_err', 'Only draft adjustments can be deleted.');
		}
		redirect($this->listPage_redirect);
	}

	/** AJAX customer lookup */
	public function search_customer() {
		$q = $this->input->get_post('q');
		$rows = $this->my_model->search_customers($q, 25);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($rows ? $rows : array()));
	}
}
