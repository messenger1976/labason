<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Login Model for users to logged into the dashboard
 * This Login Model consist a login related funtions
 * @author		Spark(Mani)
 * @copyright	Copyright (c) 2013, Sparkinfosys.com
 * @version		2.0
 * @package		Chums
 * @subpackage  Change_password 
 * @link         
 * */
class Change_password_model extends CI_Model {
	public $table_name = 'tbl_admin_details';
	public $web_table_name = 'tbl_websettings';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
  		$this->load->model('mail_template_model'); // Loading the mail Model	
    }
	
	public function password_check(){
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('password',md5($this->input->post('cur_pwd')));
		//$this->db->where('password',$this->input->post('cur_pwd'));
		$this->db->where('id',1);		
		$result=$this->db->get();
		return $result->num_rows();	
	}
	public function change_password(){
		$datac = array(
		   // 'password'=>$this->input->post('conf_pwd')
		    'password'=>md5($this->input->post('conf_pwd'))
		);
		$this->db->where('id',1);
        $result = $this->db->update($this->table_name, $datac);
		return $result;
	}
	public function username_check(){
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('username',$this->input->post('cur_username'));
		//$this->db->where('password',$this->input->post('cur_pwd'));
		$this->db->where('id',1);		
		$result=$this->db->get();
		//echo $this->db->last_query();
		return $result->num_rows();	
	}
	public function change_username(){
		$datac = array(
		   // 'password'=>$this->input->post('conf_pwd')
		    'username'=>$this->input->post('conf_username')
		);
		$this->db->where('id',1);
        $result = $this->db->update($this->table_name, $datac);
		return $result;
	}
	public function getuserdetails(){
		$this->db->select('username,email,mobile');
		$this->db->from($this->table_name);
		$this->db->where('id',1);		
		$result=$this->db->get();
		return $result->row_array();
	}
	
}
?>