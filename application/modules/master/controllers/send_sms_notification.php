 <?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class send_sms_notification extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('mobilenotifications_model', 'sms_model');
    }
    
    public function index() {
        // Use traditional ITEXMO endpoint (broadcast endpoint returns 405 Method Not Allowed)
        // The broadcast API endpoint doesn't work, so we use the traditional endpoint
        $endpoint = 'https://www.itexmo.com/php_api/api.php';
        
        // Get SMS settings from database
        $settings = $this->sms_model->get_sms_settings();
        
        if(!$settings || empty($settings['api_code']) || empty($settings['api_password'])){
            echo json_encode(array(
                'success' => false,
                'message' => 'ITEXMO API settings not configured. Please configure API Code and Password in Settings.',
                'debug' => array(
                    'settings_found' => !empty($settings),
                    'has_api_code' => !empty($settings['api_code']),
                    'has_api_password' => !empty($settings['api_password'])
                )
            ));
            exit;
        }
        
        // Debug mode - check if debug parameter is passed
        $debug_mode = $this->input->get('debug') == '1' || $this->input->post('debug') == '1';
        
        // Example data - modify these values as needed or get from POST/GET parameters
        $mobile = $this->input->post('mobile') ?: $this->input->get('mobile') ?: '639151874107';
        $message = $this->input->post('message') ?: $this->input->get('message') ?: 'Test message';
        
        // Clean mobile number (remove spaces, dashes, etc.)
        $mobile = preg_replace('/[^0-9]/', '', $mobile);
        
        // Remove leading zeros if present
        $mobile = ltrim($mobile, '0');
        
        // For Philippines numbers, ensure it starts with country code if not present
        if(strlen($mobile) == 10 && substr($mobile, 0, 1) == '9'){
            $mobile = '63' . $mobile;
        }
        elseif(strlen($mobile) == 11 && substr($mobile, 0, 2) == '09'){
            $mobile = '63' . substr($mobile, 1);
        }
        
        $itexmo = array(
            '1' => $mobile,
            '2' => $message,
            '3' => $settings['api_code'],
            'passwd' => $settings['api_password']
        );
        
        if(!empty($settings['sender_id'])){
            $itexmo['6'] = $settings['sender_id'];
        }
        
        // Build POST data
        $post_data = http_build_query($itexmo);
        
        // Debug output before request
        if($debug_mode){
            echo json_encode(array(
                'debug' => true,
                'endpoint' => $endpoint,
                'post_data' => $post_data,
                'post_data_array' => array(
                    '1' => $itexmo['1'],
                    '2' => substr($itexmo['2'], 0, 50) . '...',
                    '3' => substr($itexmo['3'], 0, 3) . '***',
                    'passwd' => '***hidden***',
                    '6' => isset($itexmo['6']) ? $itexmo['6'] : 'not set'
                ),
                'mobile_formatted' => $mobile,
                'message_length' => strlen($message)
            ));
            exit;
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, 1); // Set POST method (this was missing!)
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); // Send POST data
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response instead of outputting
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Allow self-signed certs
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'); // Some APIs require User-Agent
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));
        
        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Check for cURL errors
        if($response === false || !empty($curl_error)){
            echo json_encode(array(
                'success' => false,
                'message' => 'cURL Error: ' . ($curl_error ? $curl_error : 'Request failed'),
                'http_code' => $http_code,
                'code' => 'CURL_ERROR'
            ));
            exit;
        }
        
        // Check for server errors (4xx, 5xx) - retry without sender_id if needed
        if($http_code >= 400){
            // If we have sender_id and got a 500 error, try again without sender_id
            if(isset($itexmo['6']) && $http_code == 500){
                unset($itexmo['6']);
                $post_data = http_build_query($itexmo);
                
                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL, $endpoint);
                curl_setopt($ch2, CURLOPT_POST, 1);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch2, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch2, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded'
                ));
                
                $response = curl_exec($ch2);
                $http_code = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
                curl_close($ch2);
                
                // If retry succeeded, continue to success handling below
                if($http_code < 400 && !empty(trim($response))){
                    // Fall through to success handling
                }
            }
            
            // If still an error after retry, show error message
            if($http_code >= 400){
                $error_message = 'ITEXMO API Error (HTTP ' . $http_code . ')';
                
                // Check if response is HTML (server error page)
                if(stripos($response, '<!DOCTYPE html>') !== false || stripos($response, '<html') !== false){
                    $error_message .= ' Server returned an error page.';
                    // Try to extract error message from HTML
                    if(preg_match('/<title[^>]*>(.*?)<\/title>/is', $response, $matches)){
                        $error_message .= ' Error: ' . strip_tags($matches[1]);
                    }
                    // Try to extract body text
                    if(preg_match('/<body[^>]*>(.*?)<\/body>/is', $response, $body_matches)){
                        $body_text = strip_tags($body_matches[1]);
                        $body_text = preg_replace('/\s+/', ' ', $body_text);
                        if(strlen($body_text) > 0 && strlen($body_text) < 500){
                            $error_message .= ' Details: ' . trim($body_text);
                        }
                    }
                } else {
                    $error_message .= ' Response: ' . trim($response);
                }
                
                $debug_info = array(
                    'api_code' => substr($settings['api_code'], 0, 3) . '***',
                    'api_code_length' => strlen($settings['api_code']),
                    'api_password_length' => strlen($settings['api_password']),
                    'endpoint' => $endpoint,
                    'post_data_keys' => array_keys($itexmo),
                    'mobile' => $mobile,
                    'mobile_length' => strlen($mobile),
                    'message_length' => strlen($message),
                    'response_length' => strlen($response),
                    'response_preview' => substr(strip_tags($response), 0, 200),
                    'tried_without_sender_id' => !isset($itexmo['6'])
                );
                
                // Check if API code/password might be invalid
                if(empty(trim($settings['api_code'])) || empty(trim($settings['api_password']))){
                    $error_message .= ' Warning: API Code or Password appears to be empty or invalid.';
                }
                
                // Most common causes of HTTP 500 from ITEXMO
                if($http_code == 500){
                    $error_message .= ' Common causes: Invalid API Code/Password, Expired API Code, or Account Issues.';
                }
                
                echo json_encode(array(
                    'success' => false,
                    'message' => $error_message,
                    'http_code' => $http_code,
                    'response' => trim($response),
                    'code' => 'HTTP_ERROR',
                    'mobile' => $mobile,
                    'debug' => $debug_info,
                    'troubleshooting' => array(
                        '1' => 'Verify your ITEXMO API Code and Password are correct in Settings',
                        '2' => 'Check if your ITEXMO account has sufficient credits',
                        '3' => 'Ensure your API Code is not expired (check ITEXMO dashboard)',
                        '4' => 'Log into your ITEXMO account and verify API credentials',
                        '5' => 'Add ?debug=1 to URL to see request details (without exposing password)',
                        '6' => 'Try removing sender_id from settings if configured'
                    )
                ));
                exit;
            }
        }
        
        // Check if response is empty
        if(empty(trim($response))){
            echo json_encode(array(
                'success' => false,
                'message' => 'Empty response from ITEXMO API. HTTP Code: ' . $http_code,
                'http_code' => $http_code,
                'code' => 'EMPTY_RESPONSE'
            ));
            exit;
        }
        
        // ITEXMO returns numeric codes: 0 = success, other numbers = error codes
        $response_trimmed = trim($response);
        $itexmo_codes = array(
            '0' => 'Message sent successfully',
            '1' => 'Invalid number',
            '2' => 'Number prefix not supported',
            '3' => 'Invalid API Code',
            '4' => 'Maximum message per day reached',
            '5' => 'Maximum allowed characters for message reached',
            '6' => 'System Offline',
            '7' => 'Expired API Code',
            '8' => 'iTexMo Error',
            '9' => 'Invalid function parameters',
            '10' => 'Recipient\'s number is blocked',
            '11' => 'Recipient\'s number is invalid',
            '12' => 'Invalid sender ID',
            '13' => 'Sender ID not allowed'
        );
        
        $status_message = isset($itexmo_codes[$response_trimmed]) ? $itexmo_codes[$response_trimmed] : 'Unknown response code: ' . $response_trimmed;
        $is_success = ($response_trimmed === '0');
        
        echo json_encode(array(
            'success' => $is_success,
            'http_code' => $http_code,
            'itexmo_code' => $response_trimmed,
            'message' => $status_message,
            'response' => $response_trimmed,
            'mobile' => $mobile,
            'message_sent' => $message
        ));
        exit;
        //$this->load->view('send_sms_notification');
    }
}


?>