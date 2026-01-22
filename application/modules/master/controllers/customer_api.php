<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Customer API Controller
 * 
 * RESTful API endpoints for retrieving customer information from tbl_addcustomer
 * Returns JSON responses for integration with mobile apps, third-party systems, etc.
 */
class Customer_api extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
  		$this->load->model('addcustomer_model','customer_model');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off');
		ini_set('date.timezone', 'Asia/Manila');
		
		// Set JSON header for all responses
		header('Content-Type: application/json');
    }
	
	/**
	 * Get Customer Information by Customer ID
	 * 
	 * GET /master/customer_api/{customer_id}
	 * or
	 * GET /master/customer_api/customer/{customer_id}
	 * or
	 * GET /master/customer_api?customer_id={customer_id}
	 * 
	 * @param string $customer_id Customer ID (optional if passed as query parameter)
	 * @return JSON Response with customer information
	 */
	public function index($customer_id = '') {
		// Get customer_id from URL parameter or query string
		if(empty($customer_id)) {
			$customer_id = $this->input->get('customer_id');
		}
		
		// Validate customer_id
		if(empty($customer_id)) {
			$this->_send_response(false, 'Customer ID is required', null, 400);
			return;
		}
		
		// Get customer information
		$customer_info = $this->customer_model->get_single_record_by_customer_id($customer_id);
		
		if(empty($customer_info)) {
			$this->_send_response(false, 'Customer not found', null, 404);
			return;
		}
		
		// Remove sensitive information if needed (password field)
		if(isset($customer_info['password'])) {
			// Optionally remove password from response for security
			// unset($customer_info['password']);
		}
		
		$this->_send_response(true, 'Customer information retrieved successfully', $customer_info, 200);
	}
	
	/**
	 * Get Customer Information by Customer ID (Alternative endpoint)
	 * 
	 * GET /master/customer_api/customer/{customer_id}
	 * or
	 * GET /master/customer_api/customer?customer_id={customer_id}
	 * 
	 * @param string $customer_id Customer ID
	 * @return JSON Response with customer information
	 */
	public function customer($customer_id = '') {
		// Get customer_id from URL parameter or query string
		if(empty($customer_id)) {
			$customer_id = $this->input->get('customer_id');
		}
		
		// Validate customer_id
		if(empty($customer_id)) {
			$this->_send_response(false, 'Customer ID is required', null, 400);
			return;
		}
		
		// Get customer information
		$customer_info = $this->customer_model->get_single_record_by_customer_id($customer_id);
		
		if(empty($customer_info)) {
			$this->_send_response(false, 'Customer not found', null, 404);
			return;
		}
		
		// Remove sensitive information if needed (password field)
		if(isset($customer_info['password'])) {
			// Optionally remove password from response for security
			// unset($customer_info['password']);
		}
		
		$this->_send_response(true, 'Customer information retrieved successfully', $customer_info, 200);
	}
	
	/**
	 * Send standardized JSON response
	 * 
	 * @param bool $success Success status
	 * @param string $message Response message
	 * @param mixed $data Response data
	 * @param int $http_code HTTP status code
	 */
	private function _send_response($success, $message, $data = null, $http_code = 200) {
		// Set HTTP status code
		http_response_code($http_code);
		
		$response = array(
			'success' => $success,
			'message' => $message,
			'timestamp' => date('Y-m-d H:i:s')
		);
		
		if($data !== null) {
			$response['data'] = $data;
		}
		
		echo json_encode($response, JSON_PRETTY_PRINT);
		exit;
	}
	
	/**
	 * API Information/Help Endpoint
	 * 
	 * GET /master/customer_api/info
	 * 
	 * @return JSON Response with API documentation
	 */
	public function info() {
		$info = array(
			'api_name' => 'Customer Information API',
			'version' => '1.0.0',
			'description' => 'RESTful API for retrieving customer information from tbl_addcustomer table',
			'endpoints' => array(
				'GET /master/customer_api/{customer_id}' => 'Get customer information by customer ID',
				'GET /master/customer_api/customer/{customer_id}' => 'Get customer information by customer ID (alternative)',
				'GET /master/customer_api?customer_id={customer_id}' => 'Get customer information by customer ID (query parameter)',
				'GET /master/customer_api/info' => 'API information (this endpoint)'
			),
			'response_format' => array(
				'success' => 'boolean - Indicates if request was successful',
				'message' => 'string - Human-readable message',
				'timestamp' => 'string - ISO 8601 timestamp',
				'data' => 'object - Customer information from tbl_addcustomer table'
			),
			'example_request' => '/master/customer_api/12345',
			'example_response' => array(
				'success' => true,
				'message' => 'Customer information retrieved successfully',
				'timestamp' => '2024-01-15 10:30:00',
				'data' => array(
					'id' => 1,
					'customer_id' => '12345',
					'first_name' => 'John',
					'middle_name' => 'M',
					'last_name' => 'Doe',
					'address' => '123 Main St',
					'zones' => 'Zone 1',
					'billingplans_name' => 'Standard Plan',
					// ... other customer fields
				)
			)
		);
		
		$this->_send_response(true, 'API Information', $info, 200);
	}
}

?>
