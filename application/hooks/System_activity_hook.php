<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Captures authenticated page views and mutating requests into tbl_system_activity.
 */
class System_activity_hook {

	protected $skip_controllers = array(
		'system_activity',
		'master', // login
		'logout',
		'assets',
	);

	protected $skip_methods = array(
		'search_customer',
		'processbatch', // high-frequency; completion logged explicitly
		'getbalanceforwardresults',
	);

	protected $mutating_methods = array(
		'add', 'edit', 'update', 'delete', 'save', 'post', 'void', 'void_entry',
		'create', 'restore', 'multi_delete', 'register', 'status', 'login_status',
		'firststatus', 'processbalanceforward', 'save_draft'
	);

	public function capture() {
		try {
			$CI =& get_instance();
			if (!isset($CI->session) || !$CI->session->userdata('userid')) {
				return;
			}

			$controller = strtolower((string) $CI->router->fetch_class());
			$method = strtolower((string) $CI->router->fetch_method());
			$module = '';
			if (isset($CI->router->fetch_module) || method_exists($CI->router, 'fetch_module')) {
				$module = strtolower((string) $CI->router->fetch_module());
			}
			if ($module === '') {
				$module = 'master';
			}

			if (in_array($controller, $this->skip_controllers, true)) {
				return;
			}
			if (in_array($method, $this->skip_methods, true)) {
				return;
			}

			// Skip AJAX/datatable noise for page views
			$is_ajax = $this->_is_ajax($CI);
			$http = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';

			$CI->load->library('system_activity');
			$CI->load->helper('system_activity');

			$is_mutating_method = in_array($method, $this->mutating_methods, true)
				|| strpos($method, 'delete') !== false
				|| strpos($method, 'save') !== false;

			if ($http === 'GET' && !$is_mutating_method) {
				if ($is_ajax) {
					return;
				}
				log_system_activity(array(
					'category' => 'page_view',
					'action' => 'view',
					'module' => $controller,
					'controller' => $controller,
					'method' => $method,
					'summary' => 'Viewed '.$controller.($method && $method !== 'index' ? '/'.$method : ''),
				));
				return;
			}

			// Mutations: POST or known mutating URI methods (GET delete links exist in this app)
			if ($http === 'POST' || $is_mutating_method) {
				if (!empty($CI->system_activity->logged_this_request)) {
					return; // explicit enrichment already wrote a richer row
				}
				$inferred = $CI->system_activity->infer_from_method($method);
				$details = array(
					'post_keys' => $CI->system_activity->sanitize_post_keys(),
					'segments' => $CI->uri->segment_array(),
				);
				$entity_id = '';
				if ($CI->uri->segment(3) && !in_array(strtolower($CI->uri->segment(3)), array('index', 'add', 'edit', 'view', 'delete'), true)) {
					// URI like master/foo/edit/12
				}
				$seg3 = $CI->uri->segment(3);
				$seg4 = $CI->uri->segment(4);
				if ($seg4 !== false && $seg4 !== null && $seg4 !== '') {
					$entity_id = (string) $seg4;
				} elseif ($seg3 !== false && is_numeric($seg3)) {
					$entity_id = (string) $seg3;
				} elseif ($seg3 && in_array(strtolower((string) $seg3), array('edit', 'view', 'delete', 'post', 'void_entry', 'download', 'restore'), true) && $seg4) {
					$entity_id = (string) $seg4;
				}

				$category = $inferred['category'];
				// Prefer accounting category for known finance controllers
				$finance = array('aradjustment', 'addpaymentcustomer', 'paymentmonthlycustomer', 'or_correction', 'createbalanceforward', 'addledger', 'addmetercustomerreading', 'leakingentry');
				if (in_array($controller, $finance, true) && in_array($category, array('create', 'update', 'delete', 'admin'), true)) {
					if (in_array($method, array('post', 'void', 'void_entry', 'processbalanceforward'), true)) {
						$category = 'accounting';
					}
				}
				$admin_mods = array('responsibilities', 'employee_logins', 'database_backup', 'web_settings');
				if (in_array($controller, $admin_mods, true)) {
					$category = 'admin';
				}

				log_system_activity(array(
					'category' => $category,
					'action' => $inferred['action'],
					'module' => $controller,
					'controller' => $controller,
					'method' => $method,
					'entity_type' => $controller,
					'entity_id' => $entity_id,
					'summary' => strtoupper($inferred['action']).' on '.$controller.($entity_id !== '' ? ' #'.$entity_id : ''),
					'details' => $details,
				));
			}
		} catch (Exception $e) {
			// never break the request
			return;
		}
	}

	protected function _is_ajax($CI) {
		if (method_exists($CI->input, 'is_ajax_request') && $CI->input->is_ajax_request()) {
			return true;
		}
		$xrw = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';
		return ($xrw === 'xmlhttprequest');
	}
}
