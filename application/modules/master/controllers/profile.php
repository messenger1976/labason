<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {
	public $headerPage = '../../views/admin-includes/header';
	public $listPage = 'profile';
	public $listPage_redirect = '/master/profile';

	public function __construct() {
		parent::__construct();
		$this->load->model('Profile_model', 'my_model');
		$this->load->model('common_model', 'comm_model');
		$this->load->model('adminheader_model', 'top_model');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors', 'off');
	}

	public function index() {
		if (strtoupper((string) $this->input->server('REQUEST_METHOD')) === 'POST' && $this->input->post('upload_avatar')) {
			$this->handle_avatar_upload();
		}
		if (strtoupper((string) $this->input->server('REQUEST_METHOD')) === 'POST' && $this->input->post('save_profile')) {
			$this->handle_profile_update();
		}

		$header['host'] = $this->comm_model->get_single_record();
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$header['title'] = 'Profile';

		$data['profile'] = $this->my_model->get_logged_in_profile();

		$this->load->view($this->headerPage, $header);
		$this->load->view($this->listPage, $data);
	}

	private function handle_avatar_upload() {
		$user_id = (int) $this->session->userdata('userid');
		$user_type = strtolower(trim((string) $this->session->userdata('usertype')));

		if ($user_id <= 0 || $user_type === '') {
			$this->session->set_flashdata('msg_error', 'Unable to identify the logged-in user.');
			redirect($this->listPage_redirect);
			return;
		}

		if (empty($_FILES['avatar_file']['name'])) {
			$this->session->set_flashdata('msg_error', 'Please choose an image to upload.');
			redirect($this->listPage_redirect);
			return;
		}

		$upload_dir = FCPATH . 'uploads/profile/';
		if (!is_dir($upload_dir)) {
			@mkdir($upload_dir, 0777, true);
		}

		$safe_user_type = preg_replace('/[^a-z0-9_-]/i', '', $user_type);
		$file_base = $safe_user_type . '_' . $user_id;
		foreach (glob($upload_dir . $file_base . '.*') as $old_avatar) {
			@unlink($old_avatar);
		}

		$config = array(
			'upload_path' => $upload_dir,
			'allowed_types' => 'gif|jpg|jpeg|png|webp',
			'max_size' => '4096',
			'max_width' => '2048',
			'max_height' => '2048',
			'overwrite' => true,
			'file_name' => $file_base
		);

		$this->load->library('upload');
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('avatar_file')) {
			$this->session->set_flashdata('msg_error', strip_tags($this->upload->display_errors('', '')));
			redirect($this->listPage_redirect);
			return;
		}

		$this->session->set_flashdata('msg_succ', 'Profile image updated successfully.');
		redirect($this->listPage_redirect);
	}

	private function handle_profile_update() {
		$full_name = trim((string) $this->input->post('full_name'));
		$contact = trim((string) $this->input->post('contact'));

		if ($full_name === '') {
			$this->session->set_flashdata('msg_error', 'Full Name is required.');
			redirect($this->listPage_redirect);
			return;
		}

		if ($this->my_model->update_logged_in_profile($full_name, $contact)) {
			$this->session->set_flashdata('msg_succ', 'Profile details updated successfully.');
		} else {
			$this->session->set_flashdata('msg_error', 'Unable to update profile details.');
		}

		redirect($this->listPage_redirect);
	}
}
?>
