<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Statement of Account API Controller
 * 
 * RESTful API endpoints for retrieving customer statement of account information
 * Returns JSON responses for integration with mobile apps, third-party systems, etc.
 */
class Statementofaccount_api extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
  		$this->load->model('statementofaccount_model','my_model');
		$this->load->model('common_model','comm_model');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off');
		ini_set('date.timezone', 'Asia/Manila');
		
		// Set JSON header for all responses
		header('Content-Type: application/json');
    }
	
	/**
	 * Get Statement of Account for a Customer
	 * 
	 * GET /master/statementofaccount_api/get/{customer_id}
	 * or
	 * GET /master/statementofaccount_api/get?customer_id={customer_id}
	 * 
	 * @param string $customer_id Customer ID (optional if passed as query parameter)
	 * @return JSON Response with customer info, ledger entries, and current balance
	 */
	public function get($customer_id = '') {
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
		$customer_info = $this->my_model->get_customer_info($customer_id);
		
		if(empty($customer_info)) {
			$this->_send_response(false, 'Customer not found', null, 404);
			return;
		}
		
		// Get all ledger entries (readings and payments combined)
		$ledger_entries = $this->my_model->get_customer_ledger($customer_id);
		
		// Calculate running balance
		$ledger_entries = $this->my_model->calculate_running_balance($ledger_entries);
		
		// Get current balance
		$current_balance = $this->my_model->get_current_balance($customer_id);
		
		// Prepare response data
		$data = array(
			'customer_info' => $customer_info,
			'ledger_entries' => $ledger_entries,
			'current_balance' => floatval($current_balance),
			'entry_count' => count($ledger_entries),
			'generated_at' => date('Y-m-d H:i:s')
		);
		
		$this->_send_response(true, 'Statement of account retrieved successfully', $data, 200);
	}
	
	/**
	 * Get Customer Information Only
	 * 
	 * GET /master/statementofaccount_api/customer/{customer_id}
	 * or
	 * GET /master/statementofaccount_api/customer?customer_id={customer_id}
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
		$customer_info = $this->my_model->get_customer_info($customer_id);
		
		if(empty($customer_info)) {
			$this->_send_response(false, 'Customer not found', null, 404);
			return;
		}
		
		$this->_send_response(true, 'Customer information retrieved successfully', $customer_info, 200);
	}
	
	/**
	 * Get Ledger Entries Only
	 * 
	 * GET /master/statementofaccount_api/ledger/{customer_id}
	 * or
	 * GET /master/statementofaccount_api/ledger?customer_id={customer_id}
	 * 
	 * @param string $customer_id Customer ID
	 * @return JSON Response with ledger entries
	 */
	public function ledger($customer_id = '') {
		// Get customer_id from URL parameter or query string
		if(empty($customer_id)) {
			$customer_id = $this->input->get('customer_id');
		}
		
		// Validate customer_id
		if(empty($customer_id)) {
			$this->_send_response(false, 'Customer ID is required', null, 400);
			return;
		}
		
		// Verify customer exists
		$customer_info = $this->my_model->get_customer_info($customer_id);
		if(empty($customer_info)) {
			$this->_send_response(false, 'Customer not found', null, 404);
			return;
		}
		
		// Get all ledger entries
		$ledger_entries = $this->my_model->get_customer_ledger($customer_id);
		
		// Calculate running balance
		$ledger_entries = $this->my_model->calculate_running_balance($ledger_entries);
		
		$data = array(
			'ledger_entries' => $ledger_entries,
			'entry_count' => count($ledger_entries),
			'generated_at' => date('Y-m-d H:i:s')
		);
		
		$this->_send_response(true, 'Ledger entries retrieved successfully', $data, 200);
	}
	
	/**
	 * Get Current Balance Only
	 * 
	 * GET /master/statementofaccount_api/balance/{customer_id}
	 * or
	 * GET /master/statementofaccount_api/balance?customer_id={customer_id}
	 * 
	 * @param string $customer_id Customer ID
	 * @return JSON Response with current balance
	 */
	public function balance($customer_id = '') {
		// Get customer_id from URL parameter or query string
		if(empty($customer_id)) {
			$customer_id = $this->input->get('customer_id');
		}
		
		// Validate customer_id
		if(empty($customer_id)) {
			$this->_send_response(false, 'Customer ID is required', null, 400);
			return;
		}
		
		// Verify customer exists
		$customer_info = $this->my_model->get_customer_info($customer_id);
		if(empty($customer_info)) {
			$this->_send_response(false, 'Customer not found', null, 404);
			return;
		}
		
		// Get current balance
		$current_balance = $this->my_model->get_current_balance($customer_id);
		
		$data = array(
			'customer_id' => $customer_id,
			'current_balance' => floatval($current_balance),
			'balance_formatted' => number_format($current_balance, 2),
			'generated_at' => date('Y-m-d H:i:s')
		);
		
		$this->_send_response(true, 'Current balance retrieved successfully', $data, 200);
	}
	
	/**
	 * Search Customer by ID
	 * 
	 * GET /master/statementofaccount_api/search?customer_id={customer_id}
	 * POST /master/statementofaccount_api/search (with customer_id in body)
	 * 
	 * @return JSON Response indicating if customer exists
	 */
	public function search() {
		// Get customer_id from POST or GET
		$customer_id = $this->input->post('customer_id');
		if(empty($customer_id)) {
			$customer_id = $this->input->get('customer_id');
		}
		
		// Validate customer_id
		if(empty($customer_id)) {
			$this->_send_response(false, 'Customer ID is required', null, 400);
			return;
		}
		
		// Get customer information
		$customer_info = $this->my_model->get_customer_info($customer_id);
		
		if(empty($customer_info)) {
			$this->_send_response(false, 'Customer not found', array('exists' => false), 404);
			return;
		}
		
		// Return minimal customer info for search
		$search_result = array(
			'exists' => true,
			'customer_id' => $customer_info['customer_id'],
			'customer_name' => isset($customer_info['customer_name']) ? $customer_info['customer_name'] : '',
			'zone' => isset($customer_info['zone']) ? $customer_info['zone'] : '',
			'address' => isset($customer_info['address']) ? $customer_info['address'] : ''
		);
		
		$this->_send_response(true, 'Customer found', $search_result, 200);
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
	 * GET /master/statementofaccount_api/
	 * GET /master/statementofaccount_api/info
	 * 
	 * @return JSON Response with API documentation
	 */
	public function index() {
		$info = array(
			'api_name' => 'Statement of Account API',
			'version' => '1.0.0',
			'description' => 'RESTful API for retrieving customer statement of account information',
			'endpoints' => array(
				'GET /master/statementofaccount_api/get/{customer_id}' => 'Get complete statement of account',
				'GET /master/statementofaccount_api/customer/{customer_id}' => 'Get customer information only',
				'GET /master/statementofaccount_api/ledger/{customer_id}' => 'Get ledger entries only',
				'GET /master/statementofaccount_api/balance/{customer_id}' => 'Get current balance only',
				'GET /master/statementofaccount_api/search?customer_id={customer_id}' => 'Search customer by ID',
				'GET /master/statementofaccount_api/' => 'API information (this endpoint)'
			),
			'response_format' => array(
				'success' => 'boolean - Indicates if request was successful',
				'message' => 'string - Human-readable message',
				'timestamp' => 'string - ISO 8601 timestamp',
				'data' => 'object/array - Response data (if applicable)'
			),
			'example_request' => '/master/statementofaccount_api/get/12345',
			'example_response' => array(
				'success' => true,
				'message' => 'Statement of account retrieved successfully',
				'timestamp' => '2024-01-15 10:30:00',
				'data' => array(
					'customer_info' => array(/* customer details */),
					'ledger_entries' => array(/* ledger entries */),
					'current_balance' => 1500.00,
					'entry_count' => 25,
					'generated_at' => '2024-01-15 10:30:00'
				)
			)
		);
		
		$this->_send_response(true, 'API Information', $info, 200);
	}
	
	/**
	 * Alias for index() - API information
	 */
	public function info() {
		$this->index();
	}
}

?>
