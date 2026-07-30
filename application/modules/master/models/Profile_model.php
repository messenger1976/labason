<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile_model extends CI_Model {
	public $admin_table_name = 'tbl_admin_details';
	public $employee_table_name = 'tbl_responsibilities_user';
	public $default_avatar = 'assets/avatars/avatar.png';

	public function __construct() {
		parent::__construct();
	}

	public function get_logged_in_profile() {
		$user_id = (int) $this->session->userdata('userid');
		$user_type = strtolower(trim((string) $this->session->userdata('usertype')));
		$username = trim((string) $this->session->userdata('username'));
		$display_name = trim((string) $this->session->userdata('name'));

		$profile = array(
			'id' => $user_id,
			'name' => $display_name !== '' ? $display_name : $username,
			'username' => $username,
			'user_type' => $user_type,
			'role_name' => '',
			'email' => '',
			'mobile' => '',
			'status' => '',
			'created_date_time' => '',
			'avatar_relative_path' => $this->default_avatar,
			'avatar_url' => base_url($this->default_avatar)
		);

		if ($user_id <= 0) {
			return $profile;
		}

		if ($user_type === 'admin') {
			$this->ensure_admin_name_column();
			$row = $this->db->select('id, name, username, email, mobile, user_type, status')
				->from($this->admin_table_name)
				->where('id', $user_id)
				->limit(1)
				->get()
				->row_array();

			if (!empty($row)) {
				$profile['id'] = (int) $row['id'];
				$profile['username'] = trim((string) $row['username']);
				$profile['name'] = trim((string) $row['name']) !== '' ? trim((string) $row['name']) : $profile['username'];
				$profile['email'] = trim((string) $row['email']);
				$profile['mobile'] = trim((string) $row['mobile']);
				$profile['user_type'] = trim((string) $row['user_type']);
				$profile['role_name'] = 'Administrator';
				$profile['status'] = (string) $row['status'];
			}
		} else {
			$row = $this->db->select('id, employee_name, username, mobile, user_type, role_name, status, created_date_time')
				->from($this->employee_table_name)
				->where('id', $user_id)
				->limit(1)
				->get()
				->row_array();

			if (!empty($row)) {
				$profile['id'] = (int) $row['id'];
				$profile['name'] = trim((string) $row['employee_name']) !== '' ? trim((string) $row['employee_name']) : $profile['name'];
				$profile['username'] = trim((string) $row['username']);
				$profile['email'] = trim((string) $row['username']);
				$profile['mobile'] = trim((string) $row['mobile']);
				$profile['user_type'] = trim((string) $row['user_type']);
				$profile['role_name'] = trim((string) $row['role_name']);
				$profile['status'] = (string) $row['status'];
				$profile['created_date_time'] = trim((string) $row['created_date_time']);
			}
		}

		$profile['avatar_relative_path'] = $this->get_avatar_relative_path($profile['user_type'], $profile['id']);
		$profile['avatar_url'] = base_url($profile['avatar_relative_path']);
		if ($profile['name'] !== '') {
			$this->session->set_userdata('name', $profile['name']);
		}

		return $profile;
	}

	public function update_logged_in_profile($full_name, $contact) {
		$user_id = (int) $this->session->userdata('userid');
		$user_type = strtolower(trim((string) $this->session->userdata('usertype')));
		$full_name = trim((string) $full_name);
		$contact = trim((string) $contact);

		if ($user_id <= 0) {
			return false;
		}

		if ($user_type === 'admin') {
			$this->ensure_admin_name_column();
			$data = array(
				'name' => $full_name,
				'mobile' => $contact
			);
			$this->db->where('id', $user_id);
			$result = $this->db->update($this->admin_table_name, $data);
		} else {
			$data = array(
				'employee_name' => $full_name,
				'mobile' => $contact
			);
			$this->db->where('id', $user_id);
			$result = $this->db->update($this->employee_table_name, $data);
		}

		if ($result) {
			$this->session->set_userdata('name', $full_name);
		}

		return $result;
	}

	public function get_avatar_relative_path($user_type, $user_id) {
		$user_id = (int) $user_id;
		if ($user_id <= 0) {
			return $this->default_avatar;
		}

		$normalized_type = preg_replace('/[^a-z0-9_-]/i', '', strtolower((string) $user_type));
		$avatar_dir = FCPATH . 'uploads/profile/';
		$extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');

		foreach ($extensions as $extension) {
			$absolute_path = $avatar_dir . $normalized_type . '_' . $user_id . '.' . $extension;
			if (is_file($absolute_path)) {
				$mtime = @filemtime($absolute_path);
				return 'uploads/profile/' . $normalized_type . '_' . $user_id . '.' . $extension . ($mtime ? '?v=' . $mtime : '');
			}
		}

		return $this->default_avatar;
	}

	private function ensure_admin_name_column() {
		if (!$this->db->field_exists('name', $this->admin_table_name)) {
			$this->db->query("ALTER TABLE `{$this->admin_table_name}` ADD COLUMN `name` VARCHAR(150) NULL AFTER `id`");
		}
	}
}
?>
