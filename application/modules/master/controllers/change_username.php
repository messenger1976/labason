<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Change_username extends CI_Controller {
	/**
	 * Login Controller for users to logged into the dashboard
	 * @author		Spark(Mani)
	 * @copyright	Copyright (c) 2013, Sparkinfosys.com
	 * @version		2.0
	 * @package		Chums
	 * @subpackage   Login
	 * @link         
	
	 * */
	public $headerPage = '../../views/admin-includes/header';
	
	public $footerPage = '';
	public $listPage = 'change-username';
	//set Redirect page to list page
	public $listPage_redirect = '/master/change_username';
	public $login_redirect = '/master/index';
	public function __construct() {
        parent::__construct();
  		$this->load->model('Change_password_model','my_model'); // Loading the dashboard Model	
		$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
 		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 
   }
	public function index()	{
          
		$data['msg'] = '';
		
		if ($this->form_validation->run('change_username') == TRUE) {
			if($this->my_model->username_check() != 0){ 
				$changepass=$this->my_model->change_username();
				if($changepass){
					$subject = 'Username Changed Successfully...';
					$userdetails=$this->my_model->getuserdetails();
					$username=$userdetails['username'];
					$to=$userdetails['email'];
					
					$this->load->model('mail_template_model','mail_model'); // Loading the mail template Model	
					$message = $this->mail_model->getChangePwdMail(6,'Admin',$username,$this->input->post('new_pwd'));
					if($this->mail_template_model->mail_send($to,$message)){
						
						//here session destroy
						$user_data = $this->session->all_userdata();
						foreach ($user_data as $key => $value) {
						 if($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
								$this->session->unset_userdata($key);
						 }
						}
						$this->session->unset_userdata('username');
						$this->session->unset_userdata('logged_in');
						$this->session->sess_destroy();
					
						$this->session->set_flashdata('message', 'User Name Has Been Changed Sucessfully...');
						redirect($this->login_redirect);
						//redirect($this->listPage_orredirect,'refresh');
						
					}else{
						$this->session->set_flashdata('msg_succ', 'Sorry! Try Again');
						redirect($this->listPage_redirect);
					}
				}
			}else{		
				$data['msg'] = 'Current User Name Wrong...';
			}
		}
		$header['host'] = $this->comm_model->get_single_record();
		$this->load->view($this->headerPage,$header);		
		$this->load->view($this->listPage,$data);
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>