<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Login Model for users to logged into the dashboard
 * This Login Model consist a login related funtions
 * @author		Spark(Mani)
 * @copyright	Copyright (c) 2013, Sparkinfosys.com
 * @version		2.0
 * @package		Chums
 * @subpackage  SignIn 
 * @link         
 * */
class mail_template_model extends CI_Model {
	public $web_table_name = 'tbl_websettings';
	public $sl_table_name = 'tbl_social_media_links';
	public $email_template_table = 'tbl_email_templates';
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
	public function getSocialLinks(){
        $this->db->select("*");
		$this->db->from($this->sl_table_name);
		$this->db->where('id',1);		
		$this->db->where('status = ', 1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->row_array();
		return $result;
	}
	
	public function get_web_settings(){
		$this->db->select('*');
		$this->db->from($this->web_table_name);
		$this->db->where('id',1);		
		$result=$this->db->get();
		return $result->row_array();
	}
	
	public function get_social_links(){
        $this->db->select("*");
		$this->db->from($this->sl_table_name);
		$this->db->where('id',1);		
		$this->db->where('status = ', 1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->row_array();
		return $result;
	}
	
	public function mail_send($to,$message){
		$web = $this->getWebSettings();
		$header = $this->getMailHeader();
		$subject=$message['subject'];
		$content = $message['content'];
		$footer = $this->getMailFooter();
		$template = $header.$content.$footer;
		// mail library load and config set here
	   /* $this->load->library('email');
	    $config['mailtype'] = 'html';
		$this->email->initialize($config);
		//from email
     	$this->email->from($web['contact_email'], $web['host_name']);
		//to email
	    $this->email->to($to);
		//Subject
   		$this->email->subject($message['subject']);
  		//to mail content
 	    $this->email->message($template);
		if ( ! $this->email->send()){
			// Generate error
			$this->email->print_debugger();
			//return 'Mail not Sent...';
			return FALSE;
		}else{
			//return 'Mail Sent Successfully...';
			return TRUE;
		}*/
		$headers  = 'MIME-Version: 1.0' . "\r\n"; 
		$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";  
		$headers .= 'Reply-To: '.$web['contact_email'].'' . "\r\n" ;
			// Additional headers host_name 
		$headers .= 'From: <'.$web['contact_email'].'>' . "\r\n"; 
			
		$result=	mail($to, $subject, $template, $headers); 
			if($result){
				
				return TRUE;
			}
	}
	
	public function mail_send_inline($to,$message,$subject){
		$web = $this->getWebSettings();
		$header = $this->getMailHeader();
		$content = $message;
		$footer = $this->getMailFooter();
		$template = $header.$content.$footer;
		// mail library load and config set here
	    $this->load->library('email');
	    $config['mailtype'] = 'html';
		$this->email->initialize($config);
		//from email
     	$this->email->from($web['contact_email'], $web['host_name']);
		//to email
	    $this->email->to($to);
		//Subject
   		$this->email->subject($subject);
  		//to mail content
 	    $this->email->message($template);
		if ( ! $this->email->send()){
			// Generate error
			$this->email->print_debugger();
			//return 'Mail not Sent...';
			return FALSE;
		}else{
			//return 'Mail Sent Successfully...';
			return TRUE;
		}
	}
	
	public function setMailHeaderTemplate(){
		$this->db->select('content');
		$this->db->from($this->email_template_table);
		$this->db->where('status',2);		
		$result=$this->db->get();
		$result=$result->row_array();
		return $result['content'];
	}
	
	public function setMailBodyTemplate($id){
		$this->db->select('subject, content');
		$this->db->from($this->email_template_table);
		$this->db->where('id',$id);		
		$result=$this->db->get();
		//$result=$result->row_array();
		return $result->row_array();
	}
	
	public function setMailFooterTemplate(){
		$this->db->select('content');
		$this->db->from($this->email_template_table);
		$this->db->where('status',3);		
		$result=$this->db->get();
		$result=$result->row_array();
		return $result['content'];
	}
	
	public function getMailHeader(){
		$mailHead	= 	$this->setMailHeaderTemplate();
		$webSetting	= 	$this->getWebSettings();
		$website	=	str_replace("{WEBSITE}",site_url(),$mailHead);
		$folder		=	str_replace("{FOLDER}",'images',$website);
		$logo		=	str_replace("{LOGO}",$webSetting['logo'],$folder);
		$email		=	str_replace("{EMAIL}",$webSetting['contact_email'],$logo);
		$phone		=	str_replace("{PHONE}",$webSetting['contact_mobile'],$email);
		$result = $phone;
		//print_r($result);
		return $result;
	}
	
	public function getMailFooter(){
		$mailFoot		= 	$this->setMailFooterTemplate();
		$webSetting		= 	$this->getSocialLinks();
		$thanks_regards	=	str_replace("{ADDRESS}",$_SERVER['SERVER_NAME'],$mailFoot);
		$website		=	str_replace("{WEBSITE}",site_url(),$thanks_regards);
		$folder			=	str_replace("{FOLDER}",'images',$website);
		$facebook_link	=	str_replace("{FACEBOOK}",$webSetting['facebook_url'],$folder);
		$twitter_link	=	str_replace("{TWITTER}",$webSetting['twitter_url'],$facebook_link);
		//$googleplus_link=	str_replace("{GOOGLEPLUS}",$webSetting['googleplus_url'],$twitter_link);
		//$youtube_link	=	str_replace("{YOUTUBE}",$webSetting['youtube_url'],$googleplus_link);
		$linkedin_link	=	str_replace("{LINKEDIN}",$webSetting['linkedin_url'],$twitter_link);
		$blog_link		=	str_replace("{BLOG}",$webSetting['blog_url'],$linkedin_link);
		$result = $blog_link;
		//print_r($result);
		return $result;
	}
	public function getRegisterMail($themeid,$name,$username,$password){
		$mailBody	= 	$this->setMailBodyTemplate($themeid);
		$url = 'login';		
		$name		=	str_replace("{NAME}",$name,$mailBody);
		$username	=	str_replace("{USERNAME}",$username,$name);
		$password	=	str_replace("{PASSWORD}",$password,$username);
		$ip_add		=	str_replace("{IP_AD}",$_SERVER['REMOTE_ADDR'],$password);
		$url		=	str_replace("{URL}",$url,$ip_add);
		$message	=	str_replace("{WEBSITE}",site_url(),$url);
		$result = $message;
		return $message;
	}
	
	public function getEmailVerifyMail($themeid,$name,$url){
		$mailBody	= 	$this->setMailBodyTemplate($themeid);
		$name		=	str_replace("{NAME}",$name,$mailBody);
		$url		=	str_replace("{URL}",$url,$name);
		$message	=	str_replace("{WEBSITE}",site_url(),$url);
		$result = $message;
		return $message;
	}
	public function getForgotPwdMail($themeid,$name,$username,$password){
		$mailBody	= 	$this->setMailBodyTemplate($themeid);
		$url = 'master';		
		$name		=	str_replace("{NAME}",$name,$mailBody);
		$username	=	str_replace("{USERNAME}",$username,$name);
		$password	=	str_replace("{PASSWORD}",$password,$username);
		$ip_add		=	str_replace("{IP_AD}",$_SERVER['REMOTE_ADDR'],$password);
		$url		=	str_replace("{URL}",$url,$ip_add);
		$message	=	str_replace("{WEBSITE}",site_url(),$url);
		$result = $message;
		return $message;
	}
	public function getChangePwdMail($themeid,$name,$username,$password){
		$mailBody	= 	$this->setMailBodyTemplate($themeid);
		$url = 'master';		
		$name		=	str_replace("{NAME}",$name,$mailBody);
		$username	=	str_replace("{USERNAME}",$username,$name);
		$password	=	str_replace("{PASSWORD}",$password,$username);
		$ip_add		=	str_replace("{IP_AD}",$_SERVER['REMOTE_ADDR'],$password);
		$url		=	str_replace("{URL}",$url,$ip_add);
		$message	=	str_replace("{WEBSITE}",site_url(),$url);
		$result = $message;
		return $message;
	}
	public function getContactUsMail($themeid,$name){
		$mailBody	= 	$this->setMailBodyTemplate($themeid);
		$name		=	str_replace("{NAME}",$name,$mailBody);
		return $name;
	}
	public function getAdminContactUsMail($themeid,$name,$email,$mobile,$message){
		$mailBody	= 	$this->setMailBodyTemplate($themeid);
		$name		=	str_replace("{NAME}",$name,$mailBody);
		$email		=	str_replace("{EMAIL}",$email,$name);
		$mobile		=	str_replace("{MOBILE}",$mobile,$email);
		$message	=	str_replace("{MESSAGE}",$message,$mobile);
		return $message;
	}
	public function getSubscribeMail($themeid,$name){
		$mailBody	= 	$this->setMailBodyTemplate($themeid);
		$name		=	str_replace("{NAME}",$name,$mailBody);
		return $name;
	}
	public function getUnSubscribeMail($themeid,$name){
		$mailBody	= 	$this->setMailBodyTemplate($themeid);
		$name		=	str_replace("{NAME}",$name,$mailBody);
		return $name;
	}
		
}
?>