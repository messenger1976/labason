<?php
session_start();
class logout extends CI_Controller{
	public $login_redirect = '/master/index';
	public function __construct(){
		parent::__construct();
        $this->load->library('session');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 
	}
    public function index() {
	//	if($this->session->userdata('username')!="") {
			if ($this->session->userdata('userid')) {
				$this->load->model('master_model', 'my_model');
				$this->my_model->admin_logout_info();
			} elseif (function_exists('log_system_activity') && $this->session->userdata('username')) {
				log_system_activity(array(
					'category' => 'auth',
					'action' => 'logout',
					'module' => 'logout',
					'controller' => 'logout',
					'method' => 'index',
					'summary' => 'User logged out',
				));
			}
			$user_data = $this->session->all_userdata();
			foreach ($user_data as $key => $value) {
				//if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
					$this->session->unset_userdata($key);
				//}
			}
			$this->session->unset_userdata('username');
			$this->session->unset_userdata('logged_in');
			
			$this->session->sess_destroy();
			session_destroy();
			redirect($this->login_redirect,'refresh');
		//}
   }
}