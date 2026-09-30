<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Paymongo — API client for the PayMongo QR Ph online payment channel.
 *
 * This class knows nothing about customers, bills or OR numbers. It only talks
 * to PayMongo and translates the answers, so the billing code can stay readable.
 * The invoice/attempt workflow lives in onlinepayment_model.
 *
 * Flow used (Payment Intent + `qrph` payment method):
 *   1. POST /v1/payment_intents        { amount(centavos), currency: PHP, payment_method_allowed: ['qrph'] }
 *   2. POST /v1/payment_methods        { type: 'qrph' }
 *   3. POST /v1/payment_intents/{id}/attach  { payment_method: pm_... }
 *   4. Read next_action.code.image_url  -> drawn on our own page
 *      Read next_action.code.test_url   -> PayMongo simulator (sk_test_ keys only)
 *
 * Security notes
 *   - The secret key is read from tbl_paymongo_settings and never appears in a
 *     template, a log line or an error string handed to a customer.
 *   - Verifying a webhook only tells us WHICH payment to re-check. We never
 *     settle from the webhook payload; the caller re-reads the resource here.
 */
class Paymongo {

	const API_BASE = 'https://api.paymongo.com/v1';

	/** PayMongo's documented floor for QR Ph, in centavos (PHP 20.00). */
	const MIN_CENTAVOS = 2000;

	/** @var CI_Controller */
	private $CI;

	/** @var array|null Cached settings row */
	private $settings = null;

	/** @var string Last error, already made human-readable. */
	private $last_error = '';

	/** @var string 'unreachable' | 'rejected' | 'check failed' | '' */
	private $last_error_class = '';

	/** @var int Last HTTP status (0 when the request never left the server). */
	private $last_http_status = 0;

	public function __construct($params = array()) {
		$this->CI =& get_instance();
		if (isset($params['settings']) && is_array($params['settings'])) {
			$this->settings = $params['settings'];
		}
	}

	// ---------------------------------------------------------------------
	// Settings
	// ---------------------------------------------------------------------

	/**
	 * Load (and cache) the gateway settings row.
	 * Returns an empty array when the table/row is missing so callers can treat
	 * that as "not configured" instead of crashing.
	 */
	public function settings() {
		if ($this->settings !== null) {
			return $this->settings;
		}
		$this->settings = array();
		if (!isset($this->CI->db)) {
			$this->CI->load->database();
		}
		if (!$this->CI->db->table_exists('tbl_paymongo_settings')) {
			return $this->settings;
		}
		$row = $this->CI->db->get('tbl_paymongo_settings')->row_array();
		if (is_array($row)) {
			$this->settings = $row;
		}
		return $this->settings;
	}

	/** True when the switch is on AND a secret key is present. */
	public function is_configured() {
		$s = $this->settings();
		$enabled = isset($s['enabled']) && (string) $s['enabled'] === '1';
		$key = isset($s['secret_key']) ? trim((string) $s['secret_key']) : '';
		return $enabled && $key !== '';
	}

	/** True when the switch is on and the secret key looks like a key at all. */
	public function has_secret_key() {
		$s = $this->settings();
		return isset($s['secret_key']) && trim((string) $s['secret_key']) !== '';
	}

	/** True when the saved secret key is a test key (sk_test_...). */
	public function is_test_mode() {
		$s = $this->settings();
		$key = isset($s['secret_key']) ? trim((string) $s['secret_key']) : '';
		return strpos($key, 'sk_test_') === 0;
	}

	/** Minimum charge in pesos, as configured (defaults to PayMongo's own floor). */
	public function min_amount_pesos() {
		$s = $this->settings();
		$min = isset($s['min_amount']) ? (float) $s['min_amount'] : 20.00;
		if ($min < (self::MIN_CENTAVOS / 100)) {
			$min = self::MIN_CENTAVOS / 100;
		}
		return $min;
	}

	/** Human description prefix used on PayMongo records. */
	public function description_prefix() {
		$s = $this->settings();
		$p = isset($s['description_prefix']) ? trim((string) $s['description_prefix']) : '';
		return $p !== '' ? $p : 'Bill Payment';
	}

	/** Receipt footnote (may be empty). */
	public function receipt_note() {
		$s = $this->settings();
		return isset($s['receipt_note']) ? trim((string) $s['receipt_note']) : '';
	}

	// ---------------------------------------------------------------------
	// Processing fee paid by the customer
	// ---------------------------------------------------------------------

	/**
	 * The fee in force: array('enabled' => bool, 'percent' => float, 'fixed' => float).
	 *
	 * Reported as off unless tbl_online_payments can record the fee as well, so a
	 * fee is never taken from a customer and then silently dropped because the
	 * migration (sql/add_paymongo_customer_fee.sql) has not run yet.
	 */
	public function fee_settings() {
		$off = array('enabled' => false, 'percent' => 0.0, 'fixed' => 0.0);
		$s = $this->settings();
		if (!isset($s['fee_enabled']) || (string) $s['fee_enabled'] !== '1') {
			return $off;
		}
		if (!$this->CI->db->table_exists('tbl_online_payments')
			|| !$this->CI->db->field_exists('fee_amount', 'tbl_online_payments')
			|| !$this->CI->db->field_exists('charged_amount', 'tbl_online_payments')) {
			return $off;
		}
		$percent = isset($s['fee_percent']) ? max(0.0, (float) $s['fee_percent']) : 0.0;
		$fixed = isset($s['fee_fixed']) ? max(0.0, (float) $s['fee_fixed']) : 0.0;
		if ($percent <= 0 && $fixed <= 0) {
			return $off;
		}
		return array('enabled' => true, 'percent' => $percent, 'fixed' => $fixed);
	}

	/**
	 * Fee for a bill, in pesos: round(bill x percent / 100 + fixed, 2).
	 * Returns array('fee', 'percent', 'fixed', 'charged'); fee is 0 when off.
	 */
	public function compute_fee($bill_pesos) {
		$bill = round((float) $bill_pesos, 2);
		$fs = $this->fee_settings();
		if (!$fs['enabled']) {
			return array('fee' => 0.0, 'percent' => 0.0, 'fixed' => 0.0, 'charged' => $bill);
		}
		$fee = round($bill * $fs['percent'] / 100 + $fs['fixed'], 2);
		return array('fee' => $fee, 'percent' => $fs['percent'], 'fixed' => $fs['fixed'], 'charged' => round($bill + $fee, 2));
	}

	// ---------------------------------------------------------------------
	// Amount helpers
	// ---------------------------------------------------------------------

	/**
	 * Pesos -> integer centavos, rounded, enforcing PayMongo's PHP 20 floor.
	 * Returns false when the amount is below the floor, so callers can hide the
	 * QR button instead of sending a request PayMongo will reject.
	 */
	public function to_centavos($pesos) {
		$centavos = (int) round(((float) $pesos) * 100);
		if ($centavos < self::MIN_CENTAVOS) {
			return false;
		}
		return $centavos;
	}

	// ---------------------------------------------------------------------
	// Error reporting
	// ---------------------------------------------------------------------

	public function last_error() {
		return $this->last_error;
	}

	public function last_error_class() {
		return $this->last_error_class;
	}

	public function last_http_status() {
		return $this->last_http_status;
	}

	/** True when the request never left this server (network / DNS / TLS / timeout). */
	public function is_transport_failure() {
		return $this->last_error_class === 'unreachable';
	}

	/** True when PayMongo read our key and refused it. */
	public function is_key_rejected() {
		return $this->last_error_class === 'rejected';
	}

	/**
	 * Answer one of three questions the operator actually needs:
	 *   unreachable   — the request never left the server; the key was not checked
	 *   rejected      — PayMongo read the key and refused it
	 *   check failed  — the key was fine but our own call could not finish
	 */
	private function classify_error($http_status, $message, $body) {
		$message = trim((string) $message);
		if ($http_status === 0) {
			$this->last_error_class = 'unreachable';
			return;
		}
		if (in_array((int) $http_status, array(401, 403), true)) {
			$this->last_error_class = 'rejected';
			return;
		}
		// PayMongo authenticates before it routes, so a bad key answers
		// api_key_not_found on every path — including the read-only probes.
		$haystack = strtolower($message . ' ' . (string) $body);
		if (strpos($haystack, 'api_key') !== false || strpos($haystack, 'api key') !== false) {
			$this->last_error_class = 'rejected';
			return;
		}
		$this->last_error_class = 'check failed';
	}

	/**
	 * Make a cURL failure speak English. libcurl returns no message when it
	 * rejects the URL outright, so fall back to the error number + the host.
	 */
	private function describe_transport_error($curl_errno, $curl_error) {
		$host = 'api.paymongo.com';
		$curl_error = trim((string) $curl_error);
		if ($curl_error !== '') {
			return 'Could not reach PayMongo (' . $host . '): ' . $curl_error;
		}
		if ((int) $curl_errno === 28) {
			return 'Could not reach PayMongo (' . $host . '): cURL error 28 - the request timed out';
		}
		if ((int) $curl_errno !== 0) {
			return 'Could not reach PayMongo (' . $host . '): cURL error ' . (int) $curl_errno;
		}
		return 'Could not reach PayMongo (' . $host . ').';
	}

	// ---------------------------------------------------------------------
	// HTTP
	// ---------------------------------------------------------------------

	/**
	 * One authenticated request. Returns array('ok' => bool, 'status' => int,
	 * 'data' => array, 'raw' => string). Never throws.
	 */
	private function request($method, $path, $payload = null) {
		$s = $this->settings();
		$secret = isset($s['secret_key']) ? trim((string) $s['secret_key']) : '';

		$this->last_error = '';
		$this->last_error_class = '';
		$this->last_http_status = 0;

		if ($secret === '') {
			$this->last_error = 'No PayMongo secret key is saved yet.';
			$this->last_error_class = 'rejected';
			return array('ok' => false, 'status' => 0, 'data' => array(), 'raw' => '');
		}

		$url = self::API_BASE . $path;
		$headers = array(
			'Accept: application/json',
			'Content-Type: application/json',
			'Authorization: Basic ' . base64_encode($secret . ':'),
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Labason-Water-District/1.0');
		if (defined('CURL_IPRESOLVE_V4')) {
			curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		}
		if (defined('CURLOPT_HTTP_VERSION') && defined('CURL_HTTP_VERSION_1_1')) {
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		}

		$method = strtoupper($method);
		if ($method === 'POST') {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
		} elseif ($method === 'GET') {
			curl_setopt($ch, CURLOPT_HTTPGET, true);
		} else {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			if ($payload !== null) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
			}
		}

		$raw = curl_exec($ch);
		$curl_errno = curl_errno($ch);
		$curl_error = curl_error($ch);
		$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($raw === false || $curl_errno !== 0) {
			$this->last_http_status = 0;
			$this->last_error = $this->describe_transport_error($curl_errno, $curl_error);
			$this->classify_error(0, $this->last_error, '');
			return array('ok' => false, 'status' => 0, 'data' => array(), 'raw' => '');
		}

		$this->last_http_status = $status;
		$data = json_decode($raw, true);
		if (!is_array($data)) {
			$data = array();
		}

		if ($status < 200 || $status >= 300) {
			$msg = $this->extract_error_message($data);
			if ($msg === '') {
				$msg = 'PayMongo answered HTTP ' . $status . '.';
			}
			$this->last_error = $msg;
			$this->classify_error($status, $msg, $raw);
			return array('ok' => false, 'status' => $status, 'data' => $data, 'raw' => $raw);
		}

		return array('ok' => true, 'status' => $status, 'data' => $data, 'raw' => $raw);
	}

	/** Pull a readable reason out of PayMongo's error envelope. */
	private function extract_error_message($data) {
		if (!is_array($data)) {
			return '';
		}
		if (isset($data['errors']) && is_array($data['errors'])) {
			$first = reset($data['errors']);
			if (is_array($first)) {
				if (!empty($first['detail'])) {
					return (string) $first['detail'];
				}
				if (!empty($first['code'])) {
					return (string) $first['code'];
				}
			}
			if (is_string($first) && $first !== '') {
				return $first;
			}
		}
		if (!empty($data['message'])) {
			return (string) $data['message'];
		}
		return '';
	}

	// ---------------------------------------------------------------------
	// QR Ph
	// ---------------------------------------------------------------------

	/**
	 * Create a pending QR Ph payment.
	 *
	 * @param float  $amount_pesos
	 * @param string $description   Shown on the PayMongo record
	 * @param array  $metadata      Non-empty values only (blank ones are dropped)
	 * @return array|false  array(
	 *     intent_id, payment_id, source_id, qr_image_url, test_url, expires_at
	 * )
	 */
	public function create_qrph($amount_pesos, $description, $metadata = array()) {
		$centavos = $this->to_centavos($amount_pesos);
		if ($centavos === false) {
			$this->last_error = 'The amount is below PayMongo\'s minimum of PHP ' . number_format(self::MIN_CENTAVOS / 100, 2) . '.';
			$this->last_error_class = 'check failed';
			return false;
		}

		// PayMongo validates the whole body before it looks at anything else and
		// rejects empty strings, so drop blanks instead of sending "".
		$clean_meta = array();
		foreach ((array) $metadata as $k => $v) {
			if ($v === null) {
				continue;
			}
			$v = is_scalar($v) ? trim((string) $v) : '';
			if ($v !== '') {
				$clean_meta[(string) $k] = $v;
			}
		}

		$intent_body = array(
			'data' => array(
				'attributes' => array(
					'amount' => $centavos,
					'currency' => 'PHP',
					'payment_method_allowed' => array('qrph'),
					'capture_type' => 'automatic',
					'description' => (string) $description,
				),
			),
		);
		if (!empty($clean_meta)) {
			$intent_body['data']['attributes']['metadata'] = $clean_meta;
		}

		$intent_res = $this->request('POST', '/payment_intents', $intent_body);
		if (!$intent_res['ok']) {
			return false;
		}
		$intent = $this->dig($intent_res['data'], array('data'));
		$intent_id = isset($intent['id']) ? (string) $intent['id'] : '';
		if ($intent_id === '') {
			$this->last_error = 'PayMongo did not return a payment intent id.';
			$this->last_error_class = 'check failed';
			return false;
		}

		// A qrph-only intent may already carry the QR on next_action.
		$qr = $this->extract_qr($intent);
		if ($qr['image_url'] !== '') {
			return array(
				'intent_id' => $intent_id,
				'payment_id' => $qr['payment_id'],
				'source_id' => $qr['source_id'],
				'qr_image_url' => $qr['image_url'],
				'test_url' => $qr['test_url'],
				'expires_at' => $qr['expires_at'],
			);
		}

		// Otherwise create the payment method and attach it.
		$pm_res = $this->request('POST', '/payment_methods', array(
			'data' => array('attributes' => array('type' => 'qrph')),
		));
		if (!$pm_res['ok']) {
			return false;
		}
		$pm = $this->dig($pm_res['data'], array('data'));
		$pm_id = isset($pm['id']) ? (string) $pm['id'] : '';
		if ($pm_id === '') {
			$this->last_error = 'PayMongo did not return a payment method id.';
			$this->last_error_class = 'check failed';
			return false;
		}

		$attach_res = $this->request('POST', '/payment_intents/' . rawurlencode($intent_id) . '/attach', array(
			'data' => array('attributes' => array('payment_method' => $pm_id)),
		));
		if (!$attach_res['ok']) {
			return false;
		}
		$intent2 = $this->dig($attach_res['data'], array('data'));
		$qr2 = $this->extract_qr($intent2);
		if ($qr2['image_url'] === '') {
			// Give the caller something actionable; the intent id is safe to show an admin.
			$this->last_error = 'PayMongo accepted the payment but returned no QR image for intent ' . $intent_id . '.';
			$this->last_error_class = 'check failed';
			return false;
		}

		return array(
			'intent_id' => $intent_id,
			'payment_id' => $qr2['payment_id'],
			'source_id' => $qr2['source_id'],
			'qr_image_url' => $qr2['image_url'],
			'test_url' => $qr2['test_url'],
			'expires_at' => $qr2['expires_at'],
		);
	}

	/**
	 * Re-read a payment intent. Used by the poller, the manual "Check status"
	 * button and the webhook handler before any settlement.
	 */
	public function get_intent($intent_id) {
		$intent_id = trim((string) $intent_id);
		if ($intent_id === '') {
			$this->last_error = 'No payment intent id given.';
			$this->last_error_class = 'check failed';
			return false;
		}
		$res = $this->request('GET', '/payment_intents/' . rawurlencode($intent_id));
		if (!$res['ok']) {
			return false;
		}
		$intent = $this->dig($res['data'], array('data'));
		if (empty($intent)) {
			$this->last_error = 'PayMongo returned an empty payment intent.';
			$this->last_error_class = 'check failed';
			return false;
		}
		return $intent;
	}

	/** Re-read a payment resource (pay_...). */
	public function get_payment($payment_id) {
		$payment_id = trim((string) $payment_id);
		if ($payment_id === '') {
			$this->last_error = 'No payment id given.';
			$this->last_error_class = 'check failed';
			return false;
		}
		$res = $this->request('GET', '/payments/' . rawurlencode($payment_id));
		if (!$res['ok']) {
			return false;
		}
		$payment = $this->dig($res['data'], array('data'));
		if (empty($payment)) {
			$this->last_error = 'PayMongo returned an empty payment.';
			$this->last_error_class = 'check failed';
			return false;
		}
		return $payment;
	}

	/**
	 * Is this intent actually paid?
	 * Only the gateway's own answer counts — never our database, never a payload.
	 */
	public function intent_is_paid($intent) {
		if (!is_array($intent)) {
			return false;
		}
		$attrs = isset($intent['attributes']) && is_array($intent['attributes'])
			? $intent['attributes']
			: $intent;
		$status = isset($attrs['status']) ? strtolower((string) $attrs['status']) : '';
		if ($status !== 'succeeded') {
			return false;
		}
		// An intent can only settle if it actually captured a payment.
		if (isset($attrs['payments']) && is_array($attrs['payments'])) {
			foreach ($attrs['payments'] as $p) {
				$pa = isset($p['attributes']) && is_array($p['attributes']) ? $p['attributes'] : $p;
				$ps = isset($pa['status']) ? strtolower((string) $pa['status']) : '';
				if ($ps === 'paid') {
					return true;
				}
			}
			return false;
		}
		return true;
	}

	/** Pull a payment id out of an intent, if PayMongo included one. */
	public function extract_paid_payment_id($intent) {
		if (!is_array($intent)) {
			return '';
		}
		$attrs = isset($intent['attributes']) && is_array($intent['attributes'])
			? $intent['attributes']
			: $intent;
		if (isset($attrs['payments']) && is_array($attrs['payments'])) {
			foreach ($attrs['payments'] as $p) {
				$pa = isset($p['attributes']) && is_array($p['attributes']) ? $p['attributes'] : $p;
				$ps = isset($pa['status']) ? strtolower((string) $pa['status']) : '';
				if ($ps === 'paid' && !empty($p['id'])) {
					return (string) $p['id'];
				}
			}
		}
		return '';
	}

	/** Walk a nested array by key path, returning array() when absent. */
	private function dig($data, $path) {
		$node = $data;
		foreach ((array) $path as $key) {
			if (!is_array($node) || !array_key_exists($key, $node)) {
				return array();
			}
			$node = $node[$key];
		}
		return is_array($node) ? $node : array();
	}

	/**
	 * Find the QR details in whatever shape PayMongo returned.
	 * Handles the documented next_action.code.* shape plus the older sources shape.
	 */
	private function extract_qr($resource) {
		$out = array('image_url' => '', 'test_url' => '', 'expires_at' => '', 'payment_id' => '', 'source_id' => '');

		if (!is_array($resource)) {
			return $out;
		}
		$attrs = isset($resource['attributes']) && is_array($resource['attributes'])
			? $resource['attributes']
			: $resource;

		if (!empty($resource['id']) && strpos((string) $resource['id'], 'pi_') === 0) {
			// intent id kept by the caller
		}
		if (!empty($attrs['payments'][0]['id'])) {
			$out['payment_id'] = (string) $attrs['payments'][0]['id'];
		}

		$candidates = array();
		if (isset($attrs['next_action'])) {
			$candidates[] = $attrs['next_action'];
		}
		if (isset($attrs['next_action']['code'])) {
			$candidates[] = $attrs['next_action']['code'];
		}
		if (isset($attrs['source'])) {
			$candidates[] = $attrs['source'];
		}
		if (isset($attrs['source']['attributes'])) {
			$candidates[] = $attrs['source']['attributes'];
		}

		foreach ($candidates as $c) {
			if (!is_array($c)) {
				continue;
			}
			if ($out['image_url'] === '') {
				foreach (array('image_url', 'qr_image_url', 'code_image_url') as $k) {
					if (!empty($c[$k]) && is_string($c[$k])) {
						$out['image_url'] = $c[$k];
						break;
					}
				}
			}
			if ($out['test_url'] === '') {
				foreach (array('test_url', 'simulator_url') as $k) {
					if (!empty($c[$k]) && is_string($c[$k])) {
						$out['test_url'] = $c[$k];
						break;
					}
				}
			}
			if ($out['expires_at'] === '') {
				foreach (array('expires_at', 'expiry', 'expires_on') as $k) {
					if (empty($c[$k])) {
						continue;
					}
					if (is_numeric($c[$k])) {
						// Already unix seconds.
						$out['expires_at'] = date('Y-m-d H:i:s', (int) $c[$k]);
					} else {
						// PayMongo answers in ISO-8601 **UTC** (…Z / +00:00) while this
						// app stores local time. Copying the string verbatim made every
						// expiry 8 hours stale, so an attempt looked expired the second
						// it was created: no countdown, polling switched off, and it
						// could never be settled. Parse, then render in the local zone.
						$ts = strtotime((string) $c[$k]);
						$out['expires_at'] = ($ts !== false && $ts > 0) ? date('Y-m-d H:i:s', $ts) : '';
					}
					if ($out['expires_at'] !== '') {
						break;
					}
				}
			}
			if ($out['source_id'] === '' && !empty($c['id']) && is_string($c['id'])) {
				$out['source_id'] = $c['id'];
			}
		}

		// Some Sandbox answers nest the code one level deeper.
		if ($out['image_url'] === '' && isset($attrs['next_action']['code']['image_url'])) {
			$out['image_url'] = (string) $attrs['next_action']['code']['image_url'];
		}

		return $out;
	}

	// ---------------------------------------------------------------------
	// Webhooks
	// ---------------------------------------------------------------------

	/**
	 * Verify the Paymongo-Signature header.
	 *
	 * Header shape: t=<ts>,te=<test signature>,li=<live signature>
	 * Signed payload: "<t>.<raw body>", HMAC-SHA256 with the webhook secret.
	 *
	 * Returns TRUE when no webhook secret is configured (matching the sibling
	 * implementations) — the caller must still re-read the resource before
	 * settling, so skipping this step cannot create money.
	 */
	public function verify_webhook_signature($raw_body, $signature_header) {
		$s = $this->settings();
		$secret = isset($s['webhook_secret']) ? trim((string) $s['webhook_secret']) : '';
		if ($secret === '') {
			return true;
		}

		$signature_header = trim((string) $signature_header);
		if ($signature_header === '') {
			return false;
		}

		$parts = array();
		foreach (explode(',', $signature_header) as $piece) {
			$kv = explode('=', $piece, 2);
			if (count($kv) === 2) {
				$parts[trim($kv[0])] = trim($kv[1]);
			}
		}
		if (empty($parts['t'])) {
			return false;
		}

		$expected = hash_hmac('sha256', $parts['t'] . '.' . (string) $raw_body, $secret);
		$candidates = array();
		if (!empty($parts['te'])) {
			$candidates[] = $parts['te'];
		}
		if (!empty($parts['li'])) {
			$candidates[] = $parts['li'];
		}
		foreach ($candidates as $candidate) {
			if (hash_equals($expected, $candidate)) {
				return true;
			}
		}
		return false;
	}

	/** Build the webhook URL to paste into the PayMongo dashboard. */
	public function webhook_url() {
		$base = $this->CI->config->base_url();
		return rtrim($base, '/') . '/master/paymongo/webhook';
	}

	// ---------------------------------------------------------------------
	// Diagnostics
	// ---------------------------------------------------------------------

	/**
	 * Read-only probe. Tries the documented collection endpoints and stops at
	 * the first that answers, so it creates nothing on the PayMongo account.
	 * Deliberately does NOT touch /payment_intents.
	 *
	 * Returns array('ok' => bool, 'message' => string, 'class' => string).
	 */
	public function test_connection() {
		if (!$this->has_secret_key()) {
			return array(
				'ok' => false,
				'class' => 'rejected',
				'message' => 'No secret key is saved yet. Save a key first, then test again.',
			);
		}

		$probes = array('/payments?limit=1', '/payment_links?limit=1', '/customers?limit=1');
		$last_message = '';
		foreach ($probes as $path) {
			$res = $this->request('GET', $path);
			if ($res['ok']) {
				$mode = $this->is_test_mode() ? 'test mode (sk_test_)' : 'live mode (sk_live_)';
				return array(
					'ok' => true,
					'class' => '',
					'message' => 'PayMongo accepted the saved key — ' . $mode . '.',
				);
			}
			$last_message = $this->last_error;

			// A rejected or unreachable key fails on every path, so there is no
			// point trying the rest.
			if ($this->is_key_rejected() || $this->is_transport_failure()) {
				break;
			}
		}

		if ($this->is_transport_failure()) {
			return array(
				'ok' => false,
				'class' => 'unreachable',
				'message' => 'This server could not reach PayMongo, so the saved key was NOT checked. '
					. 'Outbound HTTPS (port 443) from this account to api.paymongo.com must be allowed. '
					. 'Reason: ' . $last_message,
			);
		}
		if ($this->is_key_rejected()) {
			return array(
				'ok' => false,
				'class' => 'rejected',
				'message' => 'PayMongo did not accept the saved key: ' . $last_message,
			);
		}
		return array(
			'ok' => false,
			'class' => 'check failed',
			'message' => 'PayMongo answered, so the saved key was NOT rejected — but this check could not be completed: '
				. $last_message,
		);
	}

	/**
	 * Mask a secret for display: keeps a 4-character prefix and suffix so an
	 * operator can tell two keys apart without exposing either.
	 */
	public static function mask($value) {
		$value = trim((string) $value);
		if ($value === '') {
			return '';
		}
		$len = strlen($value);
		if ($len <= 10) {
			return str_repeat('*', $len);
		}
		return substr($value, 0, 4) . str_repeat('*', max(4, $len - 8)) . substr($value, -4);
	}
}
