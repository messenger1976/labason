<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Master extends CI_Controller {
	// Declare globle variable here
	public $table_name = 'tbl_admin_details';
	//set header page
	//public $headerPage = '../../views/admin-includes/header';
	//set footer page
	public $footerPage = '';
	//set List page
	public $listPage = 'login';
	public $forgot_pwd_Page = 'forgot-password';
	//set Add page
	//set View page
	public $viewPage = '';
	//set Redirect page to list page
	//public $listPage_redirect = '/master/addcustomer';
	//public $listPage_redirect = '/master/dashboard';
	public $listPage_redirect = '/master/dashboard';
	public $listMobilePage_redirect = '/master/mobile_dashboard';
	public $forgot_redirect = '/master/forgot_password';
	//public $listPage_redirect_subadmin = '/master/change_password';
	public $listPage_redirect_subadmin = '/master/dashboard';
	public $login_redirect = '/master/index';
	public $loginMobile_redirect = '/master/app_login';
	//set Redirect page to add page
	public $addPage_redirect = '';
	//set Redirect page to edit page
	public $editPage_redirect = '';
	
	public function __construct() {
        parent::__construct();
		$this->load->library('session');
        if ($this->session->userdata('username')!="") { // checking the memberid in the session
            redirect($this->listPage_redirect, 'refresh'); // if member id is in the session then redirect to dashboard page
        }
  		$this->load->model('master_model','my_model'); // Loading the Master_model Model
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		$this->load->helper('url');
		//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	//	error_reporting(0);
	//	ini_set('display_errors','off'); 
    }
	public function index(){	
	/**View pages Load here**/
		if($this->input->post('submit')!=''){
			if ($this->form_validation->run('loginform') == TRUE){
				$checkUser = $this->my_model->get_admin_username_check($this->input->post('username'));
				if($checkUser == 0){
					if (function_exists('log_system_activity')) {
						log_system_activity(array(
							'category' => 'auth',
							'action' => 'failed_login',
							'module' => 'master',
							'controller' => 'master',
							'method' => 'index',
							'username' => (string) $this->input->post('username'),
							'user_name' => '',
							'user_id' => 0,
							'usertype' => '',
							'summary' => 'Failed login: invalid username',
							'details' => array('reason' => 'invalid_username'),
						));
					}
					$this->session->set_flashdata( 'message', 'Invalid User Name...' );
					redirect($this->login_redirect);
				}else{
					//Username And Password Check here
					$user_details = $this->my_model->get_admin_username_pwd_check($this->input->post('username'),$this->input->post('password'));
					if(empty($user_details) && count($user_details) == 0){
						if (function_exists('log_system_activity')) {
							log_system_activity(array(
								'category' => 'auth',
								'action' => 'failed_login',
								'module' => 'master',
								'controller' => 'master',
								'method' => 'index',
								'username' => (string) $this->input->post('username'),
								'user_name' => '',
								'user_id' => 0,
								'usertype' => '',
								'summary' => 'Failed login: invalid password',
								'details' => array('reason' => 'invalid_password'),
							));
						}
						//Username And Password Not match here today once
						$this->session->set_flashdata( 'message', 'Invalid Password...' );
						redirect($this->login_redirect);
					}else{
						$val=$this->my_model->admin_login();
						$this->my_model->get_admininfo_details();
						if($this->session->userdata('usertype') == 'admin'){
						    redirect($this->listPage_redirect);
						}else{
							redirect($this->listPage_redirect_subadmin);
						}
					}
				}			
			}
		}
		$this->load->view($this->listPage);
	}
	public function app_login(){	
		/**View pages Load here**/
			if($this->input->post('submit')!=''){
				if ($this->form_validation->run('loginform') == TRUE){
					$checkUser = $this->my_model->get_admin_username_check($this->input->post('username'));
					if($checkUser == 0){
						$this->session->set_flashdata( 'message', 'Invalid User Name...' );
						redirect($this->login_redirect);
					}else{
						//Username And Password Check here
						$user_details = $this->my_model->get_admin_username_pwd_check($this->input->post('username'),$this->input->post('password'));
						if(empty($user_details) && count($user_details) == 0){
							//Username And Password Not match here today once
							$this->session->set_flashdata( 'message', 'Invalid Password...' );
							redirect($this->login_redirect);
						}else{
							$val=$this->my_model->admin_login();
							if($this->session->userdata('usertype') == 'admin'){
								redirect($this->listMobilePage_redirect);
							}else{
								redirect($this->listPage_redirect_subadmin);
							}
						}
					}			
				}
			}
			$this->load->view($this->listPage);
		}	
	public function forgot_password(){	
		$data['msg'] = '';
		
		if ($this->form_validation->run('forgot_password') == TRUE) {
			if($this->my_model->email_check() != 0){ 
				$pwddigt  =  substr(rand(1,1000000),0,4); 
				$pwdchar  =   substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUXYVWZ"), 0, 4); 
				$password =   $pwdchar.$pwddigt;
				$changepass=$this->my_model->change_password($password);
				if($changepass){
					$subject = 'Password was Reset Successfully...';
					$userdetails=$this->my_model->getuserdetails();
					$username=$userdetails['username'];
					$to=$userdetails['email'];
					
					$this->load->model('mail_template_model','mail_model'); // Loading the mail template Model	
						$admin_no=$this->my_model->getWebSettings();
						$admin_mobile_number=$admin_no['contact_mobile'];
						
						
						$sms_controllers=$this->mail_model->sms_controllers();
						//echo "<pre>";print_r($sms_controllers);   
						if($sms_controllers['admin_forgot_password']==1){
						$reason = 'Forgot Password ';
						$mobile = $admin_mobile_number;
						$sms_message = 'Admin login details are username : '.$username.'  password : '.$password;
						$record_details = $this->mail_model->send_sms($mobile,$sms_message,$reason,'admin','admin','admin');
						
						}
						
						
					
					$email_controlles=$this->mail_model->email_controllers();
					if($email_controlles['admin_forgot_password']==1){
						$message = $this->mail_model->getForgotPwdMail(5,'Admin',$username,$password);
						$reason='Admin forgot password';
						if($this->mail_model->mail_send($to,$message,'admin','admin',$reason)){
							$this->session->set_flashdata('message', 'Password Sent To Your Email Id ...');
							redirect($this->login_redirect);
						}else{
							$this->session->set_flashdata('message', 'Sorry! Try Again ...');
							redirect($this->forgot_redirect);
						}
					}else{
								
						$this->session->set_flashdata('messages', 'Password Sent To Your Mobile No ...');
						redirect($this->login_redirect);
					}
				
				}
			}else{		
				$this->session->set_flashdata( 'message', 'Invalid Email Id ...');
				redirect($this->forgot_redirect);
			}
		}
		$this->load->view($this->forgot_pwd_Page);
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */