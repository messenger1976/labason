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