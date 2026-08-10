<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class master_model extends CI_Model {
	
	public $table_name = 'tbl_admin_details';
	public $table_admininfo = 'tbl_admininfo';
	public $web_table_name = 'tbl_websettings';
	public $emp_table_name='tbl_responsibilities_user';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
	public function getWebSettings(){
		$this->db->select('*');
		$this->db->from($this->web_table_name);
		$this->db->where('id',1);		
		$result=$this->db->get();
		return $result->row_array();
	}
	
	public function getUser($data) {
        $result = array();
        $this->db->select("*");
        $this->db->where('username', $data['username']);
        $this->db->where('password', md5($data['password']));
		$query = $this->db->get($this->table_name);
		//echo $this->db->last_query();
        if ($query->num_rows() > 0):
            $result = $query->row_array();
		else:
			$result = FALSE;
        endif;
		return $result;
    }
	public function get_admin_username_check($user_name){
		$this->db->select('id,user_type');
		$this->db->from($this->table_name);
		$this->db->where('id',1);
		$this->db->where('status',1);
		$this->db->where('username',$user_name);	
		$result=$this->db->get();
		$num_rows=$result->num_rows();
		if($num_rows == 0){
			$result=$this->get_subadmin_username_check($user_name);	
		}else{
			$result=$result->num_rows();
		}
		return $result;
	}
	
	public function get_subadmin_username_check($user_name){
		$this->db->select('id,user_type');
		$this->db->from($this->emp_table_name);
		$this->db->where('username' ,$user_name);	
		$this->db->where('status',1);
		//$this->db->where('firsttime_login',1);
		$result=$this->db->get();
		return $result->num_rows();
	}
	public function get_admin_username_pwd_check($user_name,$password){
		$this->db->select('id,user_type,username,email,password,status,username as name');
		$this->db->from($this->table_name);
		$this->db->where('id',1);
		$this->db->where('status',1);
		$this->db->where('username',$user_name);	
		$this->db->where('password',md5($password));	
		$query=$this->db->get(); 
		$num_rows=$query->num_rows();
		if($num_rows == 0){
			$result = $this->get_subadmin_username_pwd_check($user_name,$password);	
		}else{
            $result = $query->row_array();
			if($result){
				//set session values here — name is the logged-in user's display name
				$display_name = trim((string) $result['name']);
				if ($display_name === '') {
					$display_name = trim((string) $result['username']);
				}
				$this->session->set_userdata('userid', $result['id']);
				$this->session->set_userdata('name', $display_name);
				$this->session->set_userdata('usertype', $result['user_type']);
				$this->session->set_userdata('user_type', $result['user_type']);
				$this->session->set_userdata('username', $result['username']);
				$this->session->set_userdata('role_id', '');
				$this->session->set_userdata('logged_in', "ECOM");
				$this->session->set_userdata('login_state', TRUE);
				$user_data = $this->session->all_userdata();
			}
		}
		return $result;
	}
	public function get_subadmin_username_pwd_check($user_name,$password){
		$this->db->select('id,user_type,username,username as email,password,status,invalid_login_count,role_id,role_name,employee_name as name');
		$this->db->from($this->emp_table_name);
		$this->db->where('username' ,$user_name);	
		$this->db->where('password',md5($password));	
		$this->db->where('status',1);
		$query=$this->db->get();
		$result = $query->row_array();
		if($result){
			//set session values here — prefer employee_name, fall back to username
			$display_name = trim((string) $result['name']);
			if ($display_name === '') {
				$display_name = trim((string) $result['username']);
			}
			$this->session->set_userdata('userid', $result['id']);
			$this->session->set_userdata('name', $display_name);
			$this->session->set_userdata('usertype', $result['user_type']);
			$this->session->set_userdata('username', $result['username']);
			$this->session->set_userdata('role_id', $result['role_id']);
			$this->session->set_userdata('logged_in', "ECOM");
			$this->session->set_userdata('login_state', TRUE);
			$user_data = $this->session->all_userdata();
		}
		return $result;
	}
	public function getuserdetails(){
		$this->db->select('username,email,mobile');
		$this->db->from($this->table_name);
		$this->db->where('id',1);		
		$result=$this->db->get();
		//echo $this->db->last_query();
		return $result->row_array();
		
	}
	public function get_admininfo_details(){
		$this->db->select('*');
		$this->db->from($this->table_admininfo);
		$this->db->where('adminid',1);		
		$query=$this->db->get();
		
		$result = $query->row_array();
		$this->session->set_userdata('admininfo_name', $result['name']);
		$user_data = $this->session->all_userdata();
		return $result;
	}
	public function email_check(){
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('email',$this->input->post('email'));
		$this->db->where('id',1);		
		$result=$this->db->get();
		return $result->num_rows();	
	}
	
	public function change_password($password){
		$datac = array(
		    'password'=>md5($password)
		);
		$this->db->where('id',1);
        $result = $this->db->update($this->table_name, $datac);
		return $result;
	}
	
	/*public function admin_login(){
		$current_ip_address=$this->input->ip_address();
				
				/*if ($this->input->valid_ip($current_ip_address))
				{*/
			/*	$login_st_data = array(
			   'session_info'   => $this->session->userdata('session_id'),
			   'login_time'      => date('Y-m-d H:i:s'),  
               'ip_address'      => $this->input->ip_address(),
			   'browser_name' => $this->session->userdata['user_agent'],
               'current_date'	 => date('Y-m-d H:i:s'));
			   
		  	    $admin_result=$this->db->insert('tbl_admin_login_users', $login_st_data);
				
				//echo $this->db->last_query();
				$current_product_id=$this->db->insert_id();
				/*}else{
				
					echo "asdffffffffffffff";
				}
				exit;*/
			/*	return $current_product_id;
		
	}*/
	
	public function admin_login(){
		$this->db->select('id');
		$this->db->from('tbl_admin_login_users');
		$this->db->where('userid',$this->session->userdata('userid'));
		$this->db->where('LOWER(usertype)',$this->session->userdata('usertype'));
		$this->db->order_by('id','desc');
		$this->db->limit(9);
		$query1	=	$this->db->get();
		$last_ten_records = $query1->result_array();
		$attrs = array();
		$attrs=iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($last_ten_records)), 0);	
		//echo '<pre>'; print_r($attrs);
		if(!empty($attrs)){
			$this->db->where('userid',$this->session->userdata('userid'));
			$this->db->where('LOWER(usertype)',$this->session->userdata('usertype'));
			$this->db->where_not_in('id', $attrs);
			$this->db->delete('tbl_admin_login_users');		
		}
		
	 	$current_ip_address=$this->input->ip_address();
		$this->load->helper('ip2locationlite.class_helper');
		//Load the class
		$ipLite = new ip2location_lite;
		$ipLite->setKey('19e6531ffa167a2d00121eeed164c3d41ddc93e15f8a7429e9953f7d474847da');
		$locations = $ipLite->getCity($current_ip_address);
		$errors = $ipLite->getError();
		$ip_details = array(); 
		if (!empty($locations) && is_array($locations)) {
			$ip_details = $locations;
		}
		//echo '<pre>';	print_r($ip_details);exit;
		$ua=$this->getBrowser();
		$yourbrowser= $ua['name'] . "/" . $ua['version'] . " (" .$ua['platform']. ")";
		//	print_r($yourbrowser);exit;
		$login_st_data = array(
			'name'   => $this->session->userdata('name'),
			'userid'   => $this->session->userdata('userid'),
			'username'   => substr($this->session->userdata('username'), 0, -4),
			'usertype'   => $this->session->userdata('usertype'),
			'session_info'   => $this->session->userdata('session_id'),
			'login_time'      => date('Y-m-d H:i:s'),  
			'ip_address'      => $this->input->ip_address(),
			//'browser_name' => $this->session->userdata['user_agent'],
			'browser_name' => $yourbrowser,
			'current_date'	 => date('Y-m-d H:i:s')
		);
		$setdata =  array_merge($login_st_data,$ip_details);
		$admin_result=$this->db->insert('tbl_admin_login_users', $setdata);
		$current_product_id=$this->db->insert_id();
		$this->session->set_userdata('login_history_id', $current_product_id);
		if (function_exists('log_system_activity')) {
			log_system_activity(array(
				'category' => 'auth',
				'action' => 'login',
				'module' => 'master',
				'controller' => 'master',
				'method' => 'login',
				'entity_type' => 'login_session',
				'entity_id' => (string) $current_product_id,
				'summary' => 'User logged in',
				'details' => array(
					'browser' => $yourbrowser,
					'ip' => $this->input->ip_address(),
				),
			));
		}
		//$this->remove_sent_sms_email($this->session->userdata('usertype'),$this->session->userdata('userid'));
		return $current_product_id;
	}
	
	public function admin_logout_info(){
		$logout_st_data = array(
			'logout_time'      => date('Y-m-d H:i:s')
		 );
		 $this->db->where('session_info',$this->session->userdata('session_id'));
		 $this->db->where('id',$this->session->userdata('login_history_id'));
		 //$this->db->update();
		 $this->db->update('tbl_admin_login_users', $logout_st_data);
		if (function_exists('log_system_activity')) {
			log_system_activity(array(
				'category' => 'auth',
				'action' => 'logout',
				'module' => 'logout',
				'controller' => 'logout',
				'method' => 'index',
				'entity_type' => 'login_session',
				'entity_id' => (string) $this->session->userdata('login_history_id'),
				'summary' => 'User logged out',
			));
		}
	}
	public function getBrowser() {
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";

		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
	   
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}
		elseif(preg_match('/Firefox/i',$u_agent))
		{
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}
		elseif(preg_match('/Chrome/i',$u_agent))
		{
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}
		elseif(preg_match('/Safari/i',$u_agent))
		{
			$bname = 'Apple Safari';
			$ub = "Safari";
		}
		elseif(preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Opera';
			$ub = "Opera";
		}
		elseif(preg_match('/Netscape/i',$u_agent))
		{
			$bname = 'Netscape';
			$ub = "Netscape";
		}
	   
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
	   
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
	   
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
	   
		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	}
}
