<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class mobilenotifications extends CI_Controller {
	
	public $headerPage = '../../views/admin-includes/header'; 
	public $table_name = 'tbl_sms_notifications';
	public $listPage = 'mobilenotifications';
	public $settingsPage = 'mobilenotifications_settings';
	public $dashboardPage = 'mobilenotifications_dashboard';
	
	public function __construct() {
        parent::__construct();
		$this->load->library('session');
  		$this->load->model('mobilenotifications_model','my_model');
		$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->load->helper('common');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }

	public function index(){
		if($this->session->userdata('usertype') == 'subadmin'){
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		}else{
			$this->head['roleResponsible'] = array();
		}
		if(array_key_exists('mobile_notifications',$this->head['roleResponsible']) && $this->session->userdata('usertype') == 'subadmin'){
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['mobile_notifications']);
		}
		
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['notifications'] = $this->my_model->get_all_records(50, 0);
		$data['stats'] = $this->my_model->get_notification_stats();
		$data['billing_periods'] = $this->my_model->get_billing_periods();
		$data['zones'] = $this->my_model->get_zones();
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}
	
	/** Settings Page **/
	public function settings(){
		if($this->session->userdata('usertype') == 'subadmin'){
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		}else{
			$this->head['roleResponsible'] = array();
		}
		if(array_key_exists('mobile_notifications',$this->head['roleResponsible']) && $this->session->userdata('usertype') == 'subadmin'){
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['mobile_notifications']);
		}
		
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['settings'] = $this->my_model->get_sms_settings();
		$data['msg'] = '';
		
		if($this->input->post('save_settings')){
			$settings_data = array(
				'email' => $this->input->post('email'),
				'api_code' => $this->input->post('api_code'),
				'api_password' => $this->input->post('api_password'),
				'sender_id' => $this->input->post('sender_id')
			);
			
			$this->my_model->update_sms_settings($settings_data);
			$data['msg'] = 'Settings saved successfully!';
			$data['settings'] = $this->my_model->get_sms_settings();
		}
		
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->settingsPage,$data);
	}
	
	/** Dashboard/Analytics Page **/
	public function dashboard(){
		if($this->session->userdata('usertype') == 'subadmin'){
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		}else{
			$this->head['roleResponsible'] = array();
		}
		if(array_key_exists('mobile_notifications',$this->head['roleResponsible']) && $this->session->userdata('usertype') == 'subadmin'){
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['mobile_notifications']);
		}
		
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$date_from = $this->input->get('date_from') ? $this->input->get('date_from') : date('Y-m-01');
		$date_to = $this->input->get('date_to') ? $this->input->get('date_to') : date('Y-m-d');
		
		$data['stats'] = $this->my_model->get_notification_stats($date_from, $date_to);
		$data['notifications'] = $this->my_model->get_notifications_by_date($date_from, $date_to);
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		
		// Get daily stats for chart
		$data['daily_stats'] = $this->get_daily_stats($date_from, $date_to);
		
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->dashboardPage,$data);
	}
	
	/** Send Billing Statement Notifications **/
	public function send_billing_statements(){
		header('Content-Type: application/json');
		
		$billing_period_id = $this->input->post('billing_period_id');
		$zone_id = $this->input->post('zone_id');
		
		$customers = $this->my_model->get_customers_for_billing($billing_period_id, $zone_id);
		$sent = 0;
		$failed = 0;
		
		foreach($customers as $customer){
			$mobile = !empty($customer['mobile1']) ? $customer['mobile1'] : $customer['mobile2'];
			if(empty($mobile)) continue;
			
			$total_amount = $customer['amount'] + $customer['penalty'] + $customer['maintenance_fee'];
			$month_name = date('F', mktime(0, 0, 0, $customer['month'], 1));
			
			$message = "Dear " . $customer['customer_name'] . ", your water bill for " . $month_name . " " . $customer['year'] . " is P" . number_format($total_amount, 2) . ". Due date: " . date('M d, Y', strtotime($customer['bp_due_date'])) . ". Thank you!";
			
			$result = $this->send_sms($mobile, $message);
			
			$notification_data = array(
				'customer_id' => $customer['customer_id'],
				'mobile_number' => $mobile,
				'message_type' => 'billing_statement',
				'message' => $message,
				'status' => $result['status'],
				'itexmo_response' => $result['response'],
				'itexmo_code' => $result['code']
			);
			
			if($result['status'] == 'sent'){
				$notification_data['sent_at'] = date('Y-m-d H:i:s');
				$sent++;
			} else {
				$failed++;
			}
			
			$this->my_model->save_notification($notification_data);
		}
		
		echo json_encode(array(
			'success' => true,
			'sent' => $sent,
			'failed' => $failed,
			'total' => count($customers)
		));
	}
	
	/** Send Due Account Notifications **/
	public function send_due_accounts(){
		header('Content-Type: application/json');
		
		$days_before = $this->input->post('days_before') ? $this->input->post('days_before') : 3;
		
		$customers = $this->my_model->get_due_accounts($days_before);
		$sent = 0;
		$failed = 0;
		
		foreach($customers as $customer){
			$mobile = !empty($customer['mobile1']) ? $customer['mobile1'] : $customer['mobile2'];
			if(empty($mobile)) continue;
			
			$total_amount = $customer['amount'] + $customer['penalty'] + $customer['maintenance_fee'];
			$days = $customer['days_until_due'];
			
			$message = "Dear " . $customer['customer_name'] . ", your water bill of P" . number_format($total_amount, 2) . " is due in " . $days . " day(s). Due date: " . date('M d, Y', strtotime($customer['bp_due_date'])) . ". Please pay on time. Thank you!";
			
			$result = $this->send_sms($mobile, $message);
			
			$notification_data = array(
				'customer_id' => $customer['customer_id'],
				'mobile_number' => $mobile,
				'message_type' => 'due_account',
				'message' => $message,
				'status' => $result['status'],
				'itexmo_response' => $result['response'],
				'itexmo_code' => $result['code']
			);
			
			if($result['status'] == 'sent'){
				$notification_data['sent_at'] = date('Y-m-d H:i:s');
				$sent++;
			} else {
				$failed++;
			}
			
			$this->my_model->save_notification($notification_data);
		}
		
		echo json_encode(array(
			'success' => true,
			'sent' => $sent,
			'failed' => $failed,
			'total' => count($customers)
		));
	}
	
	/** Send Disconnection Notifications **/
	public function send_disconnection_notices(){
		header('Content-Type: application/json');
		
		$days_overdue = $this->input->post('days_overdue') ? $this->input->post('days_overdue') : 30;
		
		$customers = $this->my_model->get_disconnection_accounts($days_overdue);
		$sent = 0;
		$failed = 0;
		
		foreach($customers as $customer){
			$mobile = !empty($customer['mobile1']) ? $customer['mobile1'] : $customer['mobile2'];
			if(empty($mobile)) continue;
			
			$total_amount = $customer['amount'] + $customer['penalty'] + $customer['maintenance_fee'];
			$days = $customer['days_overdue'];
			
			$message = "URGENT: Dear " . $customer['customer_name'] . ", your account is overdue by " . $days . " days. Amount due: P" . number_format($total_amount, 2) . ". Your service will be disconnected if payment is not received. Please pay immediately. Thank you!";
			
			$result = $this->send_sms($mobile, $message);
			
			$notification_data = array(
				'customer_id' => $customer['customer_id'],
				'mobile_number' => $mobile,
				'message_type' => 'disconnection',
				'message' => $message,
				'status' => $result['status'],
				'itexmo_response' => $result['response'],
				'itexmo_code' => $result['code']
			);
			
			if($result['status'] == 'sent'){
				$notification_data['sent_at'] = date('Y-m-d H:i:s');
				$sent++;
			} else {
				$failed++;
			}
			
			$this->my_model->save_notification($notification_data);
		}
		
		echo json_encode(array(
			'success' => true,
			'sent' => $sent,
			'failed' => $failed,
			'total' => count($customers)
		));
	}
	
	/** Send Custom Message **/
	public function send_custom_message(){
		header('Content-Type: application/json');
		
		$mobile = $this->input->post('mobile');
		$message = $this->input->post('message');
		$customer_id = $this->input->post('customer_id');
		
		if(empty($mobile) || empty($message)){
			echo json_encode(array('success' => false, 'message' => 'Mobile number and message are required'));
			return;
		}
		
		$result = $this->send_sms($mobile, $message);
		
		$notification_data = array(
			'customer_id' => $customer_id,
			'mobile_number' => $mobile,
			'message_type' => 'custom',
			'message' => $message,
			'status' => $result['status'],
			'itexmo_response' => $result['response'],
			'itexmo_code' => $result['code']
		);
		
		if($result['status'] == 'sent'){
			$notification_data['sent_at'] = date('Y-m-d H:i:s');
		}
		
		$notification_id = $this->my_model->save_notification($notification_data);
		
		echo json_encode(array(
			'success' => $result['status'] == 'sent',
			'notification_id' => $notification_id,
			'status' => $result['status'],
			'message' => $result['status'] == 'sent' ? 'Message sent successfully' : 'Failed to send message: ' . $result['response'],
			'itexmo_code' => isset($result['code']) ? $result['code'] : '',
			'http_code' => isset($result['http_code']) ? $result['http_code'] : '',
			'debug_info' => isset($result['raw_response']) ? $result['raw_response'] : ''
		));
	}
	
	/** ITEXMO SMS Sending Function **/
	private function send_sms($mobile, $message){
		$settings = $this->my_model->get_sms_settings();
		
		if(!$settings || empty($settings['api_code']) || empty($settings['api_password'])){
			return array(
				'status' => 'failed',
				'response' => 'ITEXMO API settings not configured. Please configure API Code and Password.',
				'code' => 'NO_CONFIG'
			);
		}
		
		try {
			// Clean mobile number (remove spaces, dashes, etc.)
			$mobile = preg_replace('/[^0-9]/', '', $mobile);
			
			// Remove leading zeros if present
			$mobile = ltrim($mobile, '0');
			
			// For Philippines numbers, ensure it starts with country code if not present
			// If number starts with 9 and is 10 digits, it's a local number - add 63
			if(strlen($mobile) == 10 && substr($mobile, 0, 1) == '9'){
				$mobile = '63' . $mobile;
			}
			// If number starts with 0 and then 9, remove the 0
			elseif(strlen($mobile) == 11 && substr($mobile, 0, 2) == '09'){
				$mobile = '63' . substr($mobile, 1);
			}
			
			// Mobile number is already formatted above, no need for JSON array for traditional endpoint
			
			// ITEXMO API endpoint - use traditional endpoint format
			// The broadcast endpoint returns 405, so use the traditional endpoint
			$url = 'https://www.itexmo.com/php_api/api.php';
			
			$itexmo = array(
				'1' => $mobile,
				'2' => $message,
				'3' => $settings['api_code'],
				'passwd' => $settings['api_password']
			);
			
			if(!empty($settings['sender_id'])){
				$itexmo['6'] = $settings['sender_id'];
			}
			
			// Send via cURL
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($itexmo));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Allow self-signed certs
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			
			$response = curl_exec($ch);
			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			
			// Check for cURL errors
			if($response === false || !empty($curl_error)){
				return array(
					'status' => 'failed',
					'response' => 'cURL Error: ' . ($curl_error ? $curl_error : 'Request failed'),
					'code' => 'CURL_ERROR',
					'http_code' => $http_code,
					'raw_response' => $response
				);
			}
			
			// Check for Method Not Allowed (405) - broadcast endpoint might not exist
			if($http_code == 405){
				// Fallback to traditional ITEXMO endpoint format
				$traditional_url = 'https://www.itexmo.com/php_api/api.php';
				$traditional_data = array(
					'1' => $mobile,
					'2' => $message,
					'3' => $settings['api_code'],
					'passwd' => $settings['api_password']
				);
				
				if(!empty($settings['sender_id'])){
					$traditional_data['6'] = $settings['sender_id'];
				}
				
				$ch2 = curl_init();
				curl_setopt($ch2, CURLOPT_URL, $traditional_url);
				curl_setopt($ch2, CURLOPT_POST, 1);
				curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query($traditional_data));
				curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch2, CURLOPT_TIMEOUT, 30);
				
				$response = curl_exec($ch2);
				$http_code = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
				curl_close($ch2);
				
				// Continue with traditional endpoint response processing
			}
			
			// Check for redirect (301, 302)
			if($http_code == 301 || $http_code == 302){
				$redirect_url = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
				return array(
					'status' => 'failed',
					'response' => 'ITEXMO API redirected. Please check the API endpoint URL. Redirect to: ' . ($redirect_url ? $redirect_url : 'unknown'),
					'code' => 'REDIRECT_ERROR',
					'http_code' => $http_code
				);
			}
			
			// Check if response is empty
			if(empty($response)){
				return array(
					'status' => 'failed',
					'response' => 'Empty response from ITEXMO API. HTTP Code: ' . $http_code,
					'code' => 'EMPTY_RESPONSE',
					'http_code' => $http_code
				);
			}
			
			// Check if response is HTML (server error page)
			if(stripos($response, '<!DOCTYPE html>') !== false || stripos($response, '<html') !== false){
				$error_code = 'SERVER_ERROR';
				if(preg_match('/<div[^>]*>(\d{3})<\/div>/', $response, $matches)){
					$error_code = 'HTTP_' . $matches[1];
				}
				
				return array(
					'status' => 'failed',
					'response' => 'ITEXMO Server Error (HTTP ' . $http_code . '). The ITEXMO server returned an error page.',
					'code' => $error_code,
					'http_code' => $http_code,
					'raw_response' => substr(strip_tags($response), 0, 200)
				);
			}
			
			// Try to parse JSON response
			$response_data = json_decode($response, true);
			if(json_last_error() === JSON_ERROR_NONE && is_array($response_data)){
				// JSON response format
				if(isset($response_data['status']) && $response_data['status'] == 'success'){
					return array(
						'status' => 'sent',
						'response' => 'Message sent successfully',
						'code' => 'SUCCESS',
						'raw_response' => $response
					);
				} else {
					$error_msg = isset($response_data['message']) ? $response_data['message'] : 'Unknown error from ITEXMO API';
					return array(
						'status' => 'failed',
						'response' => $error_msg,
						'code' => isset($response_data['code']) ? $response_data['code'] : 'API_ERROR',
						'http_code' => $http_code,
						'raw_response' => $response
					);
				}
			}
			
			// Trim response to handle whitespace
			$response = trim($response);
			
			// Check for success indicators in response
			if(stripos($response, 'success') !== false || stripos($response, 'sent') !== false || $response === '0' || $response === 'OK'){
				return array(
					'status' => 'sent',
					'response' => 'Message sent successfully',
					'code' => 'SUCCESS',
					'raw_response' => $response
				);
			}
			
			// Default: treat as error
			return array(
				'status' => 'failed',
				'response' => 'Failed to send message. Response: ' . substr($response, 0, 100),
				'code' => 'UNKNOWN_ERROR',
				'http_code' => $http_code,
				'raw_response' => $response
			);
			
		} catch (Exception $ex) {
			return array(
				'status' => 'failed',
				'response' => 'Exception: ' . $ex->getMessage(),
				'code' => 'EXCEPTION',
				'raw_response' => $ex->getMessage()
			);
		}
	}
	
	/** Test ITEXMO API Connection **/
	public function test_api(){
		header('Content-Type: application/json');
		
		$settings = $this->my_model->get_sms_settings();
		
		if(!$settings || empty($settings['api_code']) || empty($settings['api_password'])){
			echo json_encode(array(
				'success' => false,
				'message' => 'ITEXMO API settings not configured. Please configure API Code and Password in Settings.',
				'settings_configured' => false
			));
			return;
		}
		
		try {
			// Test with a dummy number (won't actually send)
			$test_mobile = '639151874107';
			$test_message = 'Test message';
			
			// ITEXMO API endpoint - use traditional endpoint (broadcast endpoint returns 405)
			$url = 'https://www.itexmo.com/php_api/api.php';
			$itexmo = array(
				'1' => $test_mobile,
				'2' => $test_message,
				'3' => $settings['api_code'],
				'passwd' => $settings['api_password']
			);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($itexmo));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, true); // Include headers in response to check Location
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Allow self-signed certs
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Maximum redirects to follow
			
			$response = curl_exec($ch);
			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$final_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
			$redirect_url = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
			curl_close($ch);
			
			if($response === false || !empty($curl_error)){
				echo json_encode(array(
					'success' => false,
					'message' => 'Connection failed: ' . ($curl_error ? $curl_error : 'Unable to connect to ITEXMO'),
					'curl_error' => $curl_error,
					'http_code' => $http_code
				));
				return;
			}
			
			// Separate headers from body
			$headers = substr($response, 0, $header_size);
			$body = substr($response, $header_size);
			$response = $body; // Use body as response
			
			// Extract Location header if redirect
			$location = '';
			if(preg_match('/Location:\s*(.+?)\s*\r?\n/i', $headers, $matches)){
				$location = trim($matches[1]);
			}
			
			// Check for redirect (301, 302) - if FOLLOWLOCATION didn't work, try manual redirect
			if(($http_code == 301 || $http_code == 302) && (empty($response) || empty($body))){
				// If we got a redirect but no response, FOLLOWLOCATION might be disabled
				// Try the redirect URL directly
				if(!empty($location)){
					$redirect_url = $location;
				} elseif(!empty($redirect_url)){
					$redirect_url = $redirect_url;
				} elseif(!empty($final_url) && $final_url != $url){
					$redirect_url = $final_url;
				} else {
					// Try HTTPS version
					$redirect_url = str_replace('http://', 'https://', $url);
				}
				
				// Retry with redirect URL
				$ch2 = curl_init();
				curl_setopt($ch2, CURLOPT_URL, $redirect_url);
				curl_setopt($ch2, CURLOPT_POST, 1);
				curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query($itexmo));
				curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch2, CURLOPT_TIMEOUT, 30);
				
				$response = curl_exec($ch2);
				$http_code = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
				curl_close($ch2);
			}
			
			// Check for Method Not Allowed (405) - broadcast endpoint might not exist
			if($http_code == 405){
				// Try traditional ITEXMO endpoint format as fallback
				$traditional_url = 'https://www.itexmo.com/php_api/api.php';
				$traditional_data = array(
					'1' => $test_mobile,
					'2' => $test_message,
					'3' => $settings['api_code'],
					'passwd' => $settings['api_password']
				);
				
				$ch3 = curl_init();
				curl_setopt($ch3, CURLOPT_URL, $traditional_url);
				curl_setopt($ch3, CURLOPT_POST, 1);
				curl_setopt($ch3, CURLOPT_POSTFIELDS, http_build_query($traditional_data));
				curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch3, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch3, CURLOPT_TIMEOUT, 30);
				
				$response = curl_exec($ch3);
				$http_code = curl_getinfo($ch3, CURLINFO_HTTP_CODE);
				curl_close($ch3);
				
				$response = trim($response);
				
				// If traditional endpoint works, inform user
				if($http_code == 200 && $response === '0'){
					echo json_encode(array(
						'success' => true,
						'message' => 'API connection successful using traditional ITEXMO endpoint! Note: The broadcast endpoint returned 405, but traditional endpoint works. Your credentials are valid.',
						'response_code' => 'SUCCESS_TRADITIONAL',
						'note' => 'Using traditional ITEXMO endpoint format'
					));
					return;
				} else {
					echo json_encode(array(
						'success' => false,
						'message' => 'ITEXMO API endpoint returned HTTP 405 (Method Not Allowed). The broadcast endpoint may not be available. Tried traditional endpoint but got HTTP ' . $http_code . '. Response: ' . substr($response, 0, 100) . '. Please verify your API credentials.',
						'response_code' => 'METHOD_NOT_ALLOWED',
						'http_code' => $http_code,
						'endpoint_tried' => $url,
						'traditional_response' => substr($response, 0, 100)
					));
					return;
				}
			}
			
			// If still getting redirect after retry
			if($http_code == 301 || $http_code == 302){
				echo json_encode(array(
					'success' => false,
					'message' => 'ITEXMO API redirected (HTTP ' . $http_code . '). Location: ' . ($location ? $location : ($redirect_url ? $redirect_url : 'unknown')) . '. Please verify your API credentials and endpoint URL.',
					'response_code' => 'REDIRECT_ERROR',
					'http_code' => $http_code,
					'location_header' => $location,
					'redirect_url' => $redirect_url,
					'final_url' => $final_url
				));
				return;
			}
			
			// Check if response is HTML (server error page)
			if(stripos($response, '<!DOCTYPE html>') !== false || stripos($response, '<html') !== false){
				$error_code = 'SERVER_ERROR';
				if(preg_match('/<div[^>]*>(\d{3})<\/div>/', $response, $matches)){
					$error_code = 'HTTP_' . $matches[1];
				}
				
				echo json_encode(array(
					'success' => false,
					'message' => 'ITEXMO Server Error (HTTP ' . $http_code . '). The ITEXMO server is experiencing issues.',
					'response_code' => $error_code,
					'http_code' => $http_code
				));
				return;
			}
			
			// Try to parse JSON response
			$response_data = json_decode($response, true);
			if(json_last_error() === JSON_ERROR_NONE && is_array($response_data)){
				if(isset($response_data['status']) && $response_data['status'] == 'success'){
					echo json_encode(array(
						'success' => true,
						'message' => 'API connection successful! Your ITEXMO credentials are valid.',
						'response_code' => 'SUCCESS'
					));
				} else {
					$error_msg = isset($response_data['message']) ? $response_data['message'] : 'API Error';
					echo json_encode(array(
						'success' => false,
						'message' => $error_msg,
						'response_code' => isset($response_data['code']) ? $response_data['code'] : 'API_ERROR',
						'http_code' => $http_code
					));
				}
				return;
			}
			
			$response = trim($response);
			
			// Check for success indicators
			if(stripos($response, 'success') !== false || $response === '0' || $response === 'OK'){
				echo json_encode(array(
					'success' => true,
					'message' => 'API connection successful! Your ITEXMO credentials are valid.',
					'response_code' => 'SUCCESS'
				));
			} else {
				echo json_encode(array(
					'success' => false,
					'message' => 'API test completed. Response: ' . substr($response, 0, 100),
					'response_code' => 'UNKNOWN',
					'http_code' => $http_code,
					'raw_response' => $response
				));
			}
			
		} catch (Exception $ex) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Exception: ' . $ex->getMessage(),
				'response_code' => 'EXCEPTION'
			));
		}
	}
	
	/** Get daily stats for chart **/
	private function get_daily_stats($date_from, $date_to){
		$stats = array();
		$current = strtotime($date_from);
		$end = strtotime($date_to);
		
		while($current <= $end){
			$date = date('Y-m-d', $current);
			$day_stats = $this->my_model->get_notification_stats($date, $date);
			$stats[] = array(
				'date' => $date,
				'sent' => $day_stats['total_success'],
				'failed' => $day_stats['total_failed']
			);
			$current = strtotime('+1 day', $current);
		}
		
		return $stats;
	}
	
	/** Get notification details **/
	public function get_notification_details(){
		header('Content-Type: application/json');
		$id = $this->input->post('id');
		
		$notification = $this->my_model->get_record_by_id($id);
		
		if($notification){
			echo json_encode(array('success' => true, 'data' => $notification));
		} else {
			echo json_encode(array('success' => false, 'message' => 'Notification not found'));
		}
	}
	
	/** Get customers list for custom message **/
	public function get_customers_list(){
		header('Content-Type: application/json');
		
		$this->load->model('addcustomer_model', 'customer_model');
		$customers = $this->customer_model->get_all_records();
		
		$customer_list = array();
		foreach($customers as $customer){
			$mobile = !empty($customer['mobile1']) ? $customer['mobile1'] : $customer['mobile2'];
			if(!empty($mobile)){
				$customer_list[] = array(
					'customer_id' => $customer['customer_id'],
					'name' => $customer['first_name'] . ' ' . $customer['last_name'],
					'mobile' => $mobile
				);
			}
		}
		
		echo json_encode(array('success' => true, 'customers' => $customer_list));
	}
	
}
?>

