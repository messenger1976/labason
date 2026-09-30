<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * PayMongo gateway settings (single row in tbl_paymongo_settings).
 *
 * Every method tolerates the migration not having run yet, so the module can be
 * deployed before the SQL without fatal errors (the app's usual live-drift
 * guard). See sql/add_paymongo_settings.sql.
 *
 * SECRETS: values are read for the API client and written from the Setup screen
 * only. They are never returned to a view unmasked — the Setup controller masks
 * them before rendering.
 */
class Paymongo_settings_model extends CI_Model {

	public $table_name = 'tbl_paymongo_settings';

	/** Field defaults used when the table or row is missing. */
	private $defaults = array(
		'id' => 1,
		'enabled' => '0',
		'mode' => 'test',
		'public_key' => '',
		'secret_key' => '',
		'webhook_secret' => '',
		'min_amount' => '20.00',
		'link_expiry_minutes' => 60,
		'description_prefix' => 'LWD Bill Payment',
		'receipt_note' => '',
		'fee_enabled' => '0',
		'fee_percent' => '0.000',
		'fee_fixed' => '10.00',
		'updated_by' => null,
		'update_date_time' => null,
	);

	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
	}

	/** True when sql/add_paymongo_settings.sql has been run. */
	public function table_ready() {
		return $this->db->table_exists($this->table_name);
	}

	/**
	 * The settings row, always complete: missing columns fall back to defaults
	 * so callers never have to null-check.
	 */
	public function get_settings() {
		$settings = $this->defaults;
		if (!$this->table_ready()) {
			return $settings;
		}
		$row = $this->db->get($this->table_name)->row_array();
		if (is_array($row)) {
			foreach ($row as $key => $value) {
				if ($value !== null) {
					$settings[$key] = $value;
				}
			}
		}
		return $settings;
	}

	/** True when sql/add_paymongo_customer_fee.sql has been run. */
	public function fee_ready() {
		return $this->table_ready()
			&& $this->db->field_exists('fee_enabled', $this->table_name)
			&& $this->db->table_exists('tbl_online_payments')
			&& $this->db->field_exists('fee_amount', 'tbl_online_payments');
	}

	/** True when the gateway is switched on and has a secret key. */
	public function is_configured() {
		$s = $this->get_settings();
		return (string) $s['enabled'] === '1' && trim((string) $s['secret_key']) !== '';
	}

	/** True when the saved secret key is a test key. */
	public function is_test_mode() {
		$s = $this->get_settings();
		return strpos(trim((string) $s['secret_key']), 'sk_test_') === 0;
	}

	/**
	 * Save the settings row.
	 *
	 * Blank secret fields keep the stored value (so an operator can edit the
	 * minimum without retyping keys); $clear names fields to explicitly wipe.
	 */
	public function save_settings($post, $user_id, $clear = array()) {
		if (!$this->table_ready()) {
			return false;
		}

		$current = $this->get_settings();
		$secret_fields = array('public_key', 'secret_key', 'webhook_secret');

		$data = array(
			'enabled' => (isset($post['enabled']) && (string) $post['enabled'] === '1') ? '1' : '0',
			'mode' => (isset($post['mode']) && $post['mode'] === 'live') ? 'live' : 'test',
			'min_amount' => isset($post['min_amount']) ? number_format((float) $post['min_amount'], 2, '.', '') : $current['min_amount'],
			'link_expiry_minutes' => isset($post['link_expiry_minutes']) ? max(5, (int) $post['link_expiry_minutes']) : (int) $current['link_expiry_minutes'],
			'description_prefix' => isset($post['description_prefix']) ? trim((string) $post['description_prefix']) : $current['description_prefix'],
			'receipt_note' => isset($post['receipt_note']) ? trim((string) $post['receipt_note']) : $current['receipt_note'],
			'updated_by' => (int) $user_id,
			'update_date_time' => date('Y-m-d H:i:s'),
		);

		if ($this->db->field_exists('fee_enabled', $this->table_name)) {
			$data['fee_enabled'] = (isset($post['fee_enabled']) && (string) $post['fee_enabled'] === '1') ? '1' : '0';
			$percent = isset($post['fee_percent']) && $post['fee_percent'] !== '' ? (float) $post['fee_percent'] : (float) $current['fee_percent'];
			$data['fee_percent'] = number_format(min(20, max(0, $percent)), 3, '.', '');
			$fixed = isset($post['fee_fixed']) && $post['fee_fixed'] !== '' ? (float) $post['fee_fixed'] : (float) $current['fee_fixed'];
			$data['fee_fixed'] = number_format(max(0, $fixed), 2, '.', '');
		}

		if ($data['description_prefix'] === '') {
			$data['description_prefix'] = 'LWD Bill Payment';
		}

		foreach ($secret_fields as $field) {
			$posted = isset($post[$field]) ? trim((string) $post[$field]) : '';
			if (in_array($field, (array) $clear, true)) {
				$data[$field] = '';
			} elseif ($posted !== '') {
				$data[$field] = $posted;
			} else {
				$data[$field] = $current[$field];
			}
		}

		$exists = $this->db->get($this->table_name)->num_rows() > 0;
		if ($exists) {
			$this->db->where('id', 1);
			return $this->db->update($this->table_name, $data);
		}
		$data['id'] = 1;
		return $this->db->insert($this->table_name, $data);
	}

	/** Record the last gateway failure for the Setup screen (no secrets inside). */
	public function record_last_error($message, $class) {
		if (!$this->table_ready()) {
			return false;
		}
		$prefix = $class !== '' ? ($class . ': ') : '';
		// Kept in the row rather than a log file because log_threshold is 0 on
		// these installs and config.php is not deployed.
		$this->db->where('id', 1);
		return $this->db->update($this->table_name, array(
			'last_error' => $prefix . $message,
			'last_error_at' => date('Y-m-d H:i:s'),
		));
	}

	/** Clear the stored failure after a successful test. */
	public function clear_last_error() {
		if (!$this->table_ready()) {
			return false;
		}
		$this->db->where('id', 1);
		return $this->db->update($this->table_name, array(
			'last_error' => null,
			'last_error_at' => null,
		));
	}
}
