<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| Message Support plugin — per-company settings.
| Copy to application/config/message_support.php and edit.
*/

$config['ms_company_code'] = 'LABASON';
$config['ms_company_name'] = 'Labason Water District';
$config['ms_ticket_prefix'] = 'LAB';
$ms_host = isset($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : '';
$config['ms_hub_url'] = preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/', $ms_host)
	? 'http://localhost/wd-support-hub/'
	: 'https://support-hub.bohollander.com/';
$config['ms_api_token'] = 'labason-ms-7f3c9a2e1b4d6805c8e0';
$config['ms_poll_seconds'] = 8;
$config['ms_max_upload_kb'] = 4096;
