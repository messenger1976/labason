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
		$data['upload_max_label'] = $this->php_upload_limit_label();
		
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
				if (function_exists('log_system_activity')) {
					log_system_activity(array(
						'category' => 'admin',
						'action' => 'backup_create',
						'module' => 'database_backup',
						'controller' => 'database_backup',
						'method' => 'create',
						'entity_type' => 'database_backup',
						'entity_id' => '',
						'reference_no' => $filename,
						'summary' => 'Database backup created: '.$filename,
						'details' => array('filesize' => $filesize),
					));
				}
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
		$filepath = $this->resolve_backup_filepath($backup);
		
		if($backup && $filepath !== ''){
			$this->load->helper('download');
			force_download($backup['filename'], file_get_contents($filepath));
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
			$filepath = $this->resolve_backup_filepath($backup);
			if($filepath !== ''){
				unlink($filepath);
			}
			
			// Delete record from database
			$result = $this->my_model->delete_backup($id);
			
			if($result){
				if (function_exists('log_system_activity')) {
					log_system_activity(array(
						'category' => 'admin',
						'action' => 'backup_delete',
						'module' => 'database_backup',
						'controller' => 'database_backup',
						'method' => 'delete',
						'entity_type' => 'database_backup',
						'entity_id' => (string) $id,
						'reference_no' => isset($backup['filename']) ? $backup['filename'] : '',
						'summary' => 'Database backup deleted',
					));
				}
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
		$filepath = $this->resolve_backup_filepath($backup);
		
		if($backup && $filepath !== ''){
			@set_time_limit(0);
			@ini_set('memory_limit', '1024M');

			$catalog = array();
			if ($this->db->table_exists($this->table_name)) {
				$catalog = $this->my_model->get_all_backups();
			}

			$previous_db_debug = $this->db->db_debug;
			$previous_save_queries = $this->db->save_queries;
			$this->db->db_debug = FALSE;
			$this->db->save_queries = FALSE;

			$success = false;
			$error_detail = '';

			$mysql_path = $this->find_mysql_client();
			if ($mysql_path) {
				$cli_result = $this->restore_via_mysql_cli($filepath, $mysql_path);
				$success = !empty($cli_result['success']);
				if (!$success && !empty($cli_result['error'])) {
					$error_detail = $cli_result['error'];
				}
			}

			if (!$success) {
				$php_result = $this->restore_via_php($filepath);
				$success = !empty($php_result['success']);
				if (!$success && !empty($php_result['error'])) {
					$error_detail = $php_result['error'];
				}
			}

			@$this->db->query('UNLOCK TABLES');
			@$this->db->query('SET FOREIGN_KEY_CHECKS=1');
			@$this->db->query('SET UNIQUE_CHECKS=1');
			$this->db->db_debug = $previous_db_debug;
			$this->db->save_queries = $previous_save_queries;

			if ($success) {
				$this->restore_backup_catalog($catalog);
				if (function_exists('log_system_activity')) {
					log_system_activity(array(
						'category' => 'admin',
						'action' => 'backup_restore',
						'module' => 'database_backup',
						'controller' => 'database_backup',
						'method' => 'restore',
						'entity_type' => 'database_backup',
						'entity_id' => (string) $id,
						'reference_no' => isset($backup['filename']) ? $backup['filename'] : '',
						'summary' => 'Database restored from backup',
					));
				}
				$this->session->set_flashdata('msg_succ', 'Database restored successfully from backup!');
			} else {
				$message = 'Failed to restore backup.';
				if ($error_detail !== '') {
					$message .= ' '.$error_detail;
				}
				$this->session->set_flashdata('msg_err', $message);
			}
		} else {
			$this->session->set_flashdata('msg_err', 'Backup file not found. The list record exists, but the .sql file is missing from the backups folder.');
		}
		
		redirect($this->listPage_redirect);
	}

	/** Upload Backup Function - store a previously downloaded .sql dump (does not restore) **/
	public function upload_backup(){
		$header['roleResponsible'] = $this->top_model->get_responsibilities();

		if($this->session->userdata('usertype') == 'subadmin'){
			if(isset($header['roleResponsible']['database_backup'])){
				$roleResponsible = $header['roleResponsible']['database_backup'];
				if((is_array($roleResponsible) && !in_array('a',$roleResponsible)) || (!is_array($roleResponsible))){
					redirect('master/page/', 'refresh');
				}
			}
		}

		$request_method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : '';
		if ($request_method !== 'POST') {
			$this->session->set_flashdata('msg_err', 'Please choose a .sql backup file to upload.');
			redirect($this->listPage_redirect);
			return;
		}

		$content_length = isset($_SERVER['CONTENT_LENGTH']) ? (int) $_SERVER['CONTENT_LENGTH'] : 0;
		if ($content_length > 0 && empty($_FILES) && empty($_POST)) {
			$this->session->set_flashdata('msg_err', 'The backup file is too large. Maximum upload size is '.$this->php_upload_limit_label().'.');
			redirect($this->listPage_redirect);
			return;
		}

		if (!isset($_FILES['backup_file']) || !is_array($_FILES['backup_file'])) {
			$this->session->set_flashdata('msg_err', 'Please choose a .sql backup file to upload.');
			redirect($this->listPage_redirect);
			return;
		}

		$file = $_FILES['backup_file'];
		$error = isset($file['error']) ? (int) $file['error'] : UPLOAD_ERR_NO_FILE;

		if ($error === UPLOAD_ERR_NO_FILE) {
			$this->session->set_flashdata('msg_err', 'Please choose a .sql backup file to upload.');
			redirect($this->listPage_redirect);
			return;
		}

		if ($error === UPLOAD_ERR_INI_SIZE || $error === UPLOAD_ERR_FORM_SIZE) {
			$this->session->set_flashdata('msg_err', 'The backup file is too large. Maximum upload size is '.$this->php_upload_limit_label().'.');
			redirect($this->listPage_redirect);
			return;
		}

		if ($error !== UPLOAD_ERR_OK) {
			$this->session->set_flashdata('msg_err', 'Failed to upload backup file. Please try again.');
			redirect($this->listPage_redirect);
			return;
		}

		$tmp_name = isset($file['tmp_name']) ? $file['tmp_name'] : '';
		$orig_name = isset($file['name']) ? $file['name'] : '';
		if ($tmp_name === '' || !is_uploaded_file($tmp_name)) {
			$this->session->set_flashdata('msg_err', 'Invalid upload. Please try again.');
			redirect($this->listPage_redirect);
			return;
		}

		$ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
		if ($ext !== 'sql') {
			$this->session->set_flashdata('msg_err', 'Only .sql backup files are allowed.');
			redirect($this->listPage_redirect);
			return;
		}

		if (!$this->looks_like_sql_backup($tmp_name)) {
			$this->session->set_flashdata('msg_err', 'The uploaded file does not look like a SQL backup.');
			redirect($this->listPage_redirect);
			return;
		}

		$backup_dir = $this->backup_dir();
		if (!is_dir($backup_dir)) {
			mkdir($backup_dir, 0755, true);
		}
		if (!is_writable($backup_dir)) {
			$this->session->set_flashdata('msg_err', 'Failed to save the uploaded backup. The backups folder is not writable.');
			redirect($this->listPage_redirect);
			return;
		}

		$orig_base = pathinfo($orig_name, PATHINFO_FILENAME);
		$orig_base = preg_replace('/[^A-Za-z0-9._-]/', '_', $orig_base);
		$orig_base = trim($orig_base, '._-');
		if ($orig_base === '') {
			$orig_base = 'backup';
		}
		$filename = 'upload_' . date('Y-m-d_H-i-s') . '_' . $orig_base . '.sql';
		$filepath = $backup_dir . $filename;

		$moved = false;
		if (is_uploaded_file($tmp_name)) {
			$moved = @move_uploaded_file($tmp_name, $filepath);
			if (!$moved) {
				$moved = @copy($tmp_name, $filepath);
				if ($moved) {
					@unlink($tmp_name);
				}
			}
		}

		if (!$moved) {
			$this->session->set_flashdata('msg_err', 'Failed to save the uploaded backup. Please check folder permissions.');
			redirect($this->listPage_redirect);
			return;
		}

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
		if ($result) {
			if (function_exists('log_system_activity')) {
				log_system_activity(array(
					'category' => 'admin',
					'action' => 'backup_upload',
					'module' => 'database_backup',
					'controller' => 'database_backup',
					'method' => 'upload_backup',
					'entity_type' => 'database_backup',
					'entity_id' => '',
					'reference_no' => $filename,
					'summary' => 'Database backup uploaded: '.$filename,
					'details' => array('filesize' => $filesize, 'original_name' => $orig_name),
				));
			}
			$this->session->set_flashdata('msg_succ', 'Backup uploaded successfully. You can restore it from the list when needed.');
		} else {
			$this->session->set_flashdata('msg_err', 'Backup file saved but failed to save record.');
		}

		redirect($this->listPage_redirect);
	}

	private function php_size_to_bytes($value) {
		$value = trim((string) $value);
		if ($value === '') {
			return 0;
		}
		$unit = strtolower(substr($value, -1));
		$num = (float) $value;
		switch ($unit) {
			case 'g':
				$num *= 1024;
			case 'm':
				$num *= 1024;
			case 'k':
				$num *= 1024;
		}
		return (int) $num;
	}

	private function php_upload_limit_label() {
		$upload = $this->php_size_to_bytes(ini_get('upload_max_filesize'));
		$post = $this->php_size_to_bytes(ini_get('post_max_size'));
		$limit = $upload;
		if ($post > 0 && ($limit <= 0 || $post < $limit)) {
			$limit = $post;
		}
		return $this->my_model->format_file_size($limit);
	}

	private function looks_like_sql_backup($path) {
		$fh = @fopen($path, 'rb');
		if (!$fh) {
			return false;
		}
		$chunk = fread($fh, 16384);
		fclose($fh);
		if ($chunk === false || trim($chunk) === '') {
			return false;
		}
		if (substr($chunk, 0, 3) === "\xEF\xBB\xBF") {
			$chunk = substr($chunk, 3);
		}
		$start = ltrim($chunk);
		if (stripos($start, '<?php') === 0 || stripos($start, '<? ') === 0 || stripos($start, '<html') === 0 || stripos($start, '<!doctype') === 0) {
			return false;
		}
		return (bool) preg_match('/(CREATE\s+TABLE|INSERT\s+INTO|DROP\s+TABLE|--\s+(MySQL|MariaDB)\s+dump|--\s+Dump|--\s+phpMyAdmin|mysqldump|SET\s+NAMES|SET\s+@OLD_SQL_MODE|SET\s+SQL_MODE)/i', $chunk);
	}

	private function backup_dir() {
		return rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . 'backups' . DIRECTORY_SEPARATOR;
	}

	private function resolve_backup_filepath($backup) {
		if (!is_array($backup) || empty($backup['filename'])) {
			return '';
		}
		$filename = basename($backup['filename']);
		$candidates = array(
			$this->backup_dir() . $filename,
			isset($backup['filepath']) ? $backup['filepath'] : '',
		);
		foreach ($candidates as $path) {
			if ($path !== '' && file_exists($path) && is_file($path)) {
				return $path;
			}
		}
		return '';
	}

	private function restore_backup_catalog($catalog) {
		if (!is_array($catalog) || !$this->db->table_exists($this->table_name)) {
			return;
		}
		$this->db->truncate($this->table_name);
		foreach ($catalog as $row) {
			$this->db->insert($this->table_name, $row);
		}
	}

	private function find_mysql_client() {
		$possible_paths = array(
			'C:\\xampp\\mysql\\bin\\mysql.exe',
			'C:\\xampp3\\mysql\\bin\\mysql.exe',
			'C:\\wamp\\bin\\mysql\\mysql5.7.11\\bin\\mysql.exe',
			'/usr/bin/mysql',
			'/usr/local/bin/mysql',
			'/usr/local/mysql/bin/mysql',
			'mysql'
		);

		foreach ($possible_paths as $path) {
			if ($path !== 'mysql' && file_exists($path)) {
				return $path;
			}
		}

		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			exec('where mysql', $output, $return_var);
		} else {
			exec('which mysql', $output, $return_var);
		}

		if ($return_var === 0 && !empty($output[0]) && file_exists($output[0])) {
			return $output[0];
		}

		return false;
	}

	private function restore_via_mysql_cli($file_path, $mysql_path) {
		$db_name = $this->db->database;
		$db_user = $this->db->username;
		$db_pass = $this->db->password;
		$db_host = $this->db->hostname;
		$port_arg = '';
		if (!empty($this->db->port)) {
			$port_arg = ' --port='.(int) $this->db->port;
		}

		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$command = sprintf(
				'cmd /c ""%s" --user=%s --password=%s --host=%s%s --default-character-set=utf8mb4 --max_allowed_packet=512M %s < "%s""',
				$mysql_path,
				$db_user,
				$db_pass,
				$db_host,
				$port_arg,
				$db_name,
				$file_path
			);
		} else {
			$command = sprintf(
				'"%s" --user=%s --password=%s --host=%s%s --default-character-set=utf8mb4 --max_allowed_packet=512M %s < %s 2>&1',
				$mysql_path,
				escapeshellarg($db_user),
				escapeshellarg($db_pass),
				escapeshellarg($db_host),
				$port_arg,
				escapeshellarg($db_name),
				escapeshellarg($file_path)
			);
		}

		$output = array();
		$return_var = 1;
		exec($command, $output, $return_var);

		if ($return_var === 0) {
			return array('success' => true, 'error' => '');
		}

		$error = trim(implode(' ', $output));
		if ($error === '') {
			$error = 'mysql client restore failed (exit code '.$return_var.').';
		}
		return array('success' => false, 'error' => $error);
	}

	private function restore_via_php($file_path) {
		$handle = @fopen($file_path, 'r');
		if (!$handle) {
			return array('success' => false, 'error' => 'Could not open backup file for reading.');
		}

		@$this->db->query('SET FOREIGN_KEY_CHECKS=0');
		@$this->db->query('SET UNIQUE_CHECKS=0');
		@$this->db->query('SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO"');
		@$this->db->query('UNLOCK TABLES');

		$statement = '';
		$line_number = 0;
		$error = '';

		while (($line = fgets($handle)) !== false) {
			$line_number++;
			$trim = trim($line);

			if ($trim === '' || strpos($trim, '--') === 0 || strpos($trim, '#') === 0) {
				continue;
			}

			if (preg_match('/^(LOCK\s+TABLES|UNLOCK\s+TABLES)/i', $trim)) {
				continue;
			}

			$statement .= $line;
			if (substr(rtrim($line), -1) !== ';') {
				continue;
			}

			$sql = trim($statement);
			$statement = '';
			if ($sql === '' || $sql === ';') {
				continue;
			}
			if (preg_match('/^(LOCK\s+TABLES|UNLOCK\s+TABLES)/i', $sql)) {
				continue;
			}

			$sql = rtrim($sql, " \t\n\r\0\x0B;");
			if ($sql === '') {
				continue;
			}

			$result = $this->db->query($sql);
			if ($result === FALSE) {
				$error = 'SQL error near line '.$line_number;
				if (method_exists($this->db, '_error_message')) {
					$db_error = $this->db->_error_message();
					if ($db_error) {
						$error .= ': '.$db_error;
					}
				}
				break;
			}
		}

		fclose($handle);
		@$this->db->query('UNLOCK TABLES');
		@$this->db->query('SET FOREIGN_KEY_CHECKS=1');
		@$this->db->query('SET UNIQUE_CHECKS=1');

		if ($error !== '') {
			return array('success' => false, 'error' => $error);
		}

		return array('success' => true, 'error' => '');
	}

}

?>

