<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * One-line wrapper for System Activity logging.
 *
 * @param array $payload
 * @return int|false
 */
if (!function_exists('log_system_activity')) {
	function log_system_activity($payload = array()) {
		$CI =& get_instance();
		if (!isset($CI->system_activity)) {
			$CI->load->library('system_activity');
		}
		return $CI->system_activity->log($payload);
	}
}
