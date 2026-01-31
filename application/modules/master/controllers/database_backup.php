<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Database Backup Controller 
 * @author		System
 * @copyright	Copyright (c) 2024
 * @version		1.0
 * @package		Water Billing System
 * @subpackage  Database Backup
 * */

class database_backup extends CI_Controller {

	// Declare global variable here
	public $headerPage = '../../views/admin-includes/header';
	public $listPage = 'database_backup';
	public $table_name = 'tbl_database_backups';
	
	//set Redirect page to list page
	public $listPage_redirect = '/master/database_backup';
	
	// Autoloading a system library using constructor method
	public function __construct() {
        parent::__construct();
  		$this->load->model('database_backup_model','my_model');
		$this->load->library('form_validation');
		$this->load->helper('file');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
		
		// Check permissions
		if($this->session->userdata('usertype') == 'subadmin'){
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
			if(array_key_exists('database_backup',$this->head['roleResponsible']) && $this->session->userdata('usertype') == 'subadmin' ){
				$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['database_backup']);
			}
		}else{
			$this->head['roleResponsible'] = array();
		}
    }

	/** Default Function - List all backups **/
	public function index(){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		
		// Check permission for list view
		if($this->session->userdata('usertype') == 'subadmin'){
			if(isset($header['roleResponsible']['database_backup'])){
				$roleResponsible = $header['roleResponsible']['database_backup'];
				if($this->uri->segment(3) == '' || $this->uri->segment(3) == 'index'){
					if((is_array($roleResponsible) && !in_array('l',$roleResponsible)) || (!is_array($roleResponsible))){
						redirect('master/page/', 'refresh');
					}
				}
			}
		}
		
		$data['backups'] = $this->my_model->get_all_backups();
		$data['msg'] = '';
		$data['header'] = $header; // Pass header to view for permission checks
		
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}

	/** Create Backup Function **/
	public function create(){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		
		// Check permission for create
		if($this->session->userdata('usertype') == 'subadmin'){
			if(isset($header['roleResponsible']['database_backup'])){
				$roleResponsible = $header['roleResponsible']['database_backup'];
				if($this->uri->segment(3) == 'create'){
					if((is_array($roleResponsible) && !in_array('a',$roleResponsible)) || (!is_array($roleResponsible))){
						redirect('master/page/', 'refresh');
					}
				}
			}
		}
		
		$data['msg'] = '';
		
		// Create backup directory if it doesn't exist
		$backup_dir = FCPATH . 'backups/';
		if (!is_dir($backup_dir)) {
			mkdir($backup_dir, 0755, true);
		}
		
		// Generate backup filename
		$filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
		$filepath = $backup_dir . $filename;
		
		// Get database config
		$this->load->database();
		$db_config = $this->db->database;
		$db_host = $this->db->hostname;
		$db_user = $this->db->username;
		$db_pass = $this->db->password;
		
		// Try using mysqldump first (most reliable method)
		$backup_success = false;
		
		// Method 1: Try mysqldump command (if available)
		if (function_exists('exec')) {
			$mysqldump_path = 'mysqldump'; // Try default path first
			
			// Common mysqldump paths on Windows (XAMPP)
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$possible_paths = array(
					'C:\\xampp\\mysql\\bin\\mysqldump.exe',
					'C:\\xampp3\\mysql\\bin\\mysqldump.exe',
					'C:\\wamp\\bin\\mysql\\mysql5.7.11\\bin\\mysqldump.exe',
					'mysqldump.exe'
				);
				
				foreach ($possible_paths as $path) {
					if (file_exists($path) || $path == 'mysqldump.exe') {
						$mysqldump_path = $path;
						break;
					}
				}
			}
			
			// Build mysqldump command
			// Add --no-tablespaces to avoid PROCESS privilege requirement
			// Add --single-transaction for consistent backup
			// Add --routines and --triggers to include stored procedures and triggers
			$command = escapeshellarg($mysqldump_path) . 
					   ' -h' . escapeshellarg($db_host) .
					   ' -u' . escapeshellarg($db_user);
			
			if (!empty($db_pass)) {
				$command .= ' -p' . escapeshellarg($db_pass);
			}
			
			$command .= ' --no-tablespaces' .  // Skip tablespaces to avoid PROCESS privilege error
						' --single-transaction' .  // Consistent backup
						' --routines' .  // Include stored procedures
						' --triggers' .  // Include triggers
						' --quick' .  // Faster for large tables
						' --lock-tables=false' .  // Don't lock all tables
						' ' . escapeshellarg($db_config) . 
						' > ' . escapeshellarg($filepath) . ' 2>&1';
			
			exec($command, $output, $return_var);
			
			if ($return_var === 0 && file_exists($filepath) && filesize($filepath) > 0) {
				$backup_success = true;
			}
		}
		
		// Method 2: Manual SQL backup if mysqldump failed
		if (!$backup_success) {
			$backup_content = $this->create_manual_backup();
			
			if ($backup_content !== false && write_file($filepath, $backup_content)) {
				$backup_success = true;
			}
		}
		
		// Save backup record if successful
		if ($backup_success && file_exists($filepath)) {
			$filesize = filesize($filepath);
			
			$backup_data = array(
				'filename' => $filename,
				'filepath' => $filepath,
				'filesize' => $filesize,
				'created_by' => $this->session->userdata('userid'),
				'created_at' => date('Y-m-d H:i:s'),
				'status' => 1
			);
			
			$result = $this->my_model->save_backup_record($backup_data);
			
			if($result){
				$this->session->set_flashdata('msg_succ', 'Database backup created successfully!');
			} else {
				$this->session->set_flashdata('msg_err', 'Backup file created but failed to save record.');
			}
		} else {
			$this->session->set_flashdata('msg_err', 'Failed to create backup file. Please check server permissions and MySQL configuration.');
		}
		
		redirect($this->listPage_redirect);
	}
	
	/** Manual Backup Function - Creates SQL backup manually **/
	private function create_manual_backup() {
		$this->load->database();
		
		$output = "-- Database Backup\n";
		$output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
		$output .= "-- Database: " . $this->db->database . "\n\n";
		$output .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
		$output .= "SET time_zone = \"+00:00\";\n\n";
		
		// Get all tables
		$tables = $this->db->list_tables();
		
		foreach ($tables as $table) {
			// Skip the backup table itself to avoid recursion
			if ($table == 'tbl_database_backups') {
				continue;
			}
			
			$output .= "\n-- --------------------------------------------------------\n";
			$output .= "-- Table structure for table `" . $table . "`\n";
			$output .= "-- --------------------------------------------------------\n\n";
			
			// Get table structure
			$query = $this->db->query("SHOW CREATE TABLE `" . $table . "`");
			$row = $query->row_array();
			
			if (isset($row['Create Table'])) {
				$output .= "DROP TABLE IF EXISTS `" . $table . "`;\n";
				$output .= $row['Create Table'] . ";\n\n";
			}
			
			// Get table data
			$query = $this->db->query("SELECT * FROM `" . $table . "`");
			$rows = $query->result_array();
			
			if (count($rows) > 0) {
				$output .= "-- Dumping data for table `" . $table . "`\n\n";
				
				foreach ($rows as $row) {
					$output .= "INSERT INTO `" . $table . "` VALUES(";
					$values = array();
					
					foreach ($row as $value) {
						if ($value === NULL) {
							$values[] = 'NULL';
						} else {
							// Escape the value properly
							$escaped_value = str_replace(array("\\", "'", "\n", "\r"), array("\\\\", "''", "\\n", "\\r"), $value);
							$values[] = "'" . $escaped_value . "'";
						}
					}
					
					$output .= implode(',', $values) . ");\n";
				}
				
				$output .= "\n";
			}
		}
		
		return $output;
	}

	/** Download Backup Function **/
	public function download($id){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		
		// Check permission for download (same as list view)
		if($this->session->userdata('usertype') == 'subadmin'){
			if(isset($header['roleResponsible']['database_backup'])){
				$roleResponsible = $header['roleResponsible']['database_backup'];
				if((is_array($roleResponsible) && !in_array('l',$roleResponsible)) || (!is_array($roleResponsible))){
					redirect('master/page/', 'refresh');
				}
			}
		}
		
		$backup = $this->my_model->get_backup_by_id($id);
		
		if($backup && file_exists($backup['filepath'])){
			$this->load->helper('download');
			force_download($backup['filename'], file_get_contents($backup['filepath']));
		} else {
			$this->session->set_flashdata('msg_err', 'Backup file not found.');
			redirect($this->listPage_redirect);
		}
	}

	/** Delete Backup Function **/
	public function delete($id){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		
		// Check permission for delete
		if($this->session->userdata('usertype') == 'subadmin'){
			if(isset($header['roleResponsible']['database_backup'])){
				$roleResponsible = $header['roleResponsible']['database_backup'];
				if((is_array($roleResponsible) && !in_array('d',$roleResponsible)) || (!is_array($roleResponsible))){
					redirect('master/page/', 'refresh');
				}
			}
		}
		
		$backup = $this->my_model->get_backup_by_id($id);
		
		if($backup){
			// Delete file if exists
			if(file_exists($backup['filepath'])){
				unlink($backup['filepath']);
			}
			
			// Delete record from database
			$result = $this->my_model->delete_backup($id);
			
			if($result){
				$this->session->set_flashdata('msg_succ', 'Backup deleted successfully!');
			} else {
				$this->session->set_flashdata('msg_err', 'Failed to delete backup record.');
			}
		} else {
			$this->session->set_flashdata('msg_err', 'Backup not found.');
		}
		
		redirect($this->listPage_redirect);
	}

	/**
	 * Split SQL content into individual statements.
	 * Only splits on semicolons that are outside string literals (single/double quoted or backtick),
	 * so that semicolons inside data (e.g. company name, text) do not break statements.
	 */
	private function split_sql_statements($sql) {
		$queries = array();
		$len = strlen($sql);
		$current = '';
		$in_string = false;
		$string_char = null;
		$i = 0;
		while ($i < $len) {
			$c = $sql[$i];
			if ($in_string) {
				$current .= $c;
				// Escaped quote: \' or \" or ''
				if ($c === '\\' && $i + 1 < $len && ($sql[$i + 1] === $string_char || $sql[$i + 1] === '\\')) {
					$current .= $sql[$i + 1];
					$i += 2;
					continue;
				}
				if ($c === "'" && $string_char === "'" && $i + 1 < $len && $sql[$i + 1] === "'") {
					$current .= $sql[$i + 1];
					$i += 2;
					continue;
				}
				if ($c === $string_char) {
					$in_string = false;
					$string_char = null;
				}
				$i++;
				continue;
			}
			if ($c === "'" || $c === '"' || $c === '`') {
				$in_string = true;
				$string_char = $c;
				$current .= $c;
				$i++;
				continue;
			}
			if ($c === ';') {
				$q = trim($current);
				if ($q !== '') {
					$queries[] = $q;
				}
				$current = '';
				$i++;
				continue;
			}
			$current .= $c;
			$i++;
		}
		$q = trim($current);
		if ($q !== '') {
			$queries[] = $q;
		}
		return $queries;
	}

	/** Restore Backup Function **/
	public function restore($id){ 
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		
		// Check permission for restore (same as create)
		if($this->session->userdata('usertype') == 'subadmin'){
			if(isset($header['roleResponsible']['database_backup'])){
				$roleResponsible = $header['roleResponsible']['database_backup'];
				if((is_array($roleResponsible) && !in_array('a',$roleResponsible)) || (!is_array($roleResponsible))){
					redirect('master/page/', 'refresh');
				}
			}
		}
		
		$backup = $this->my_model->get_backup_by_id($id);
		
		if($backup && file_exists($backup['filepath'])){
			// Read SQL file
			$sql = file_get_contents($backup['filepath']);
			
			// Split SQL into individual queries (only on ; outside string literals)
			$queries = $this->split_sql_statements($sql);
			
			// Execute each query
			$this->db->trans_start();
			foreach($queries as $query){
				$query = trim($query);
				if(!empty($query)){
					$this->db->query($query);
				}
			}
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE){
				$this->session->set_flashdata('msg_err', 'Failed to restore backup. Database transaction failed.');
			} else {
				$this->session->set_flashdata('msg_succ', 'Database restored successfully from backup!');
			}
		} else {
			$this->session->set_flashdata('msg_err', 'Backup file not found.');
		}
		
		redirect($this->listPage_redirect);
	}

}

?>

