<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Online / QR Ph payments — attempt lifecycle, emailed links and settlement.
 *
 * Design notes
 * ------------
 * - One row in tbl_online_payments per QR Ph attempt. The selected billing rows
 *   are frozen into bill_rows_json at QR creation time, so a customer changing
 *   the screen (or a webhook arriving an hour later) can never alter what was
 *   charged.
 * - Settlement writes through addpaymentcustomer_model::add_record_multiple(),
 *   the exact method the cashier's Cash Payment module uses, so cash and online
 *   payments can never drift apart. The only differences are the payment channel
 *   flag, the gateway reference and the absence of an OR.
 * - Amounts always come from our frozen snapshot. Nothing from a webhook payload
 *   or a browser request is ever used as an amount.
 * - Settling is idempotent: a second webhook, a poll and the manual "Check
 *   status" button can all arrive in any order.
 *
 * See sql/add_online_payments.sql for the schema.
 */
class Onlinepayment_model extends CI_Model {

	public $table_name = 'tbl_online_payments';
	public $table_customer = 'tbl_addcustomer';
	public $table_users = 'tbl_admin_login_users';
	public $table_zone = 'tbl_zone';

	/** Statuses that mean "still waiting for money". */
	private $open_statuses = array('pending');

	/** Terminal statuses. */
	private $closed_statuses = array('paid', 'expired', 'failed', 'cancelled');

	public function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		$this->load->model('addpaymentcustomer_model', 'pay_model');
		$this->load->model('leakingentry_model', 'leaking_model');
	}

	/** Datetime string in Philippines timezone. */
	private function now() {
		return (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s');
	}

	/** True when sql/add_online_payments.sql has been run. */
	public function table_ready() {
		return $this->db->table_exists($this->table_name);
	}

	public static function status_label($status) {
		switch ((string) $status) {
			case 'paid':      return 'Paid';
			case 'pending':   return 'Pending';
			case 'expired':   return 'Expired';
			case 'failed':    return 'Failed';
			case 'cancelled': return 'Cancelled';
			default:          return ucfirst((string) $status);
		}
	}

	/** Badge colour used by the views. */
	public static function status_class($status) {
		switch ((string) $status) {
			case 'paid':      return 'success';
			case 'pending':   return 'warning';
			case 'expired':   return 'secondary';
			case 'failed':    return 'danger';
			case 'cancelled': return 'dark';
			default:          return 'secondary';
		}
	}

	// =====================================================================
	// Bill rows (the single server-side amount due calculation)
	// =====================================================================

	/**
	 * Billing rows for a customer with the amount due computed server-side.
	 *
	 * This is a deliberate port of the per-row maths in
	 * views/addpaymentcustomer_add _ajax.php (the Cash Payment screen), so the
	 * mobile Payment page, the desktop Online Payment page and the public link
	 * page all agree on what is owed. The cash screen keeps its own JS copy.
	 *
	 * @return array rows in the order they should be shown (newest first)
	 */
	public function get_customer_bills($customer_id) {
		$customer_id = trim((string) $customer_id);
		if ($customer_id === '') {
			return array();
		}

		$raw = $this->pay_model->get_meter_reading_all_records($customer_id);
		if (!is_array($raw)) {
			return array();
		}

		$today = date('Y-m-d');
		$rows = array();
		$seen = array();

		foreach ($raw as $row) {
			$month = isset($row['month']) ? $row['month'] : '';
			$year  = isset($row['year']) ? $row['year'] : '';

			// One row per billing period. A duplicate reading for the same
			// month/year would otherwise let the same bill be paid twice.
			$key = $month . '-' . $year;
			if ($key !== '-' && isset($seen[$key])) {
				continue;
			}
			$seen[$key] = true;

			$unit_price   = (float) (isset($row['unit_price']) ? $row['unit_price'] : 0);
			$amount       = (float) (isset($row['amount']) ? $row['amount'] : 0);
			$penalty_col  = (float) (isset($row['penalty']) ? $row['penalty'] : 0);
			$maintenance  = (float) (isset($row['maintenance_fee']) ? $row['maintenance_fee'] : 0);
			$franchise    = (float) (isset($row['franchise_fee_amount']) ? $row['franchise_fee_amount'] : 0);
			$discount     = (float) (isset($row['sc_discount']) ? $row['sc_discount'] : 0);
			$consumed     = isset($row['consumed']) ? (float) $row['consumed'] : 0;
			$special      = isset($row['special_priviledge']) ? (int) $row['special_priviledge'] : 0;
			$compute_pen  = isset($row['compute_penalty']) ? (int) $row['compute_penalty'] : 1;
			$due_date     = isset($row['bp_due_date']) ? $row['bp_due_date'] : '';
			$is_paid      = !empty($row['payment_id']);
			$has_reading  = isset($row['reading']) && trim((string) $row['reading']) !== '';

			$display_bill_amount = $unit_price;
			$penalty = 0;

			if (!$is_paid) {
				// Same rule as the cash screen: past the due date the penalty
				// column already holds the amount due.
				if ($consumed >= 0 && $special === 0 && $compute_pen === 1) {
					if ($due_date !== '' && $today > $due_date) {
						$balance = $penalty_col;
						$penalty = $penalty_col - $amount;
					} else {
						$balance = $amount;
						$penalty = 0;
					}
				} else {
					$balance = $amount;
					$penalty = 0;
				}
				if ($penalty < 0) {
					$penalty = 0;
				}
				$trans_date = '';
				$or_number  = '';
			} else {
				// Already paid: mirror the cash screen so the row reads the same.
				$paid_grand = isset($row['paid_grand_total']) ? (float) $row['paid_grand_total'] : 0;
				$paid_tend  = isset($row['paid_pay_amount']) ? (float) $row['paid_pay_amount'] : 0;
				$balance = $this->leaking_model->get_collected_amount_for_leaking_payment(
					$paid_grand,
					$paid_tend,
					isset($row['paid_or_number']) ? $row['paid_or_number'] : null
				);
				$discount += isset($row['paid_leaking_amount']) ? (float) $row['paid_leaking_amount'] : 0;

				$due_ts = $due_date !== '' ? strtotime($due_date) : false;
				$raw_trans = !empty($row['paid_create_date_time']) ? $row['paid_create_date_time'] : (isset($row['trans_date']) ? $row['trans_date'] : '');
				$trans_ts = $raw_trans !== '' ? strtotime($raw_trans) : false;
				if ($due_ts && $trans_ts && $trans_ts > $due_ts) {
					$penalty = $balance - $unit_price - $maintenance - $franchise;
					if ($penalty < 0) {
						$penalty = 0;
					}
				}
				if ($raw_trans !== '') {
					try {
						$dt = new DateTime($raw_trans, new DateTimeZone('Asia/Manila'));
						$trans_date = $dt->format('M j, Y h:i:s A');
					} catch (Exception $e) {
						$trans_date = $raw_trans;
					}
				} else {
					$trans_date = '';
				}
				$or_number = isset($row['paid_or_number']) ? $row['paid_or_number'] : '';
			}

			$rows[] = array(
				'refno'               => isset($row['refno']) ? $row['refno'] : '',
				'month'               => $month,
				'month_name'          => isset($row['month_name']) ? trim((string) $row['month_name']) : '',
				'year'                => $year,
				'bp_id'               => isset($row['bp_id']) ? $row['bp_id'] : '',
				'due_date'            => $due_date,
				'previous_reading'    => isset($row['previous_reading']) ? $row['previous_reading'] : '',
				'reading'             => isset($row['reading']) ? $row['reading'] : '',
				'consumed'            => $consumed,
				'unit_price'          => $unit_price,
				'display_bill_amount' => $display_bill_amount,
				'discount'            => $discount,
				'penalty'             => $penalty,
				'maintenance_fee'     => $maintenance,
				'franchise_fee_amount'=> $franchise,
				'amount_due'          => round((float) $balance, 2),
				'or_number'           => $or_number,
				'trans_date'          => $trans_date,
				'status'              => isset($row['status']) ? $row['status'] : 0,
				'special_priviledge'  => $special,
				'compute_penalty'     => $compute_pen,
				'is_paid'             => $is_paid,
				'has_reading'         => $has_reading,
				'is_payable'          => (!$is_paid && $has_reading),
				'period_label'        => trim((isset($row['month_name']) ? $row['month_name'] : '') . ' ' . $year),
			);
		}

		return $rows;
	}

	/** Customer header fields for the payment pages. */
	public function get_customer_info($customer_id) {
		$customer_id = trim((string) $customer_id);
		if ($customer_id === '') {
			return array();
		}
		$this->db->select('*');
		$this->db->from($this->table_customer);
		$this->db->where('customer_id', $customer_id);
		$row = $this->db->get()->row_array();
		return is_array($row) ? $row : array();
	}

	/** "LASTNAME, FIRSTNAME MIDDLE" in upper case. */
	public static function full_name($customer) {
		if (!is_array($customer)) {
			return '';
		}
		return strtoupper(trim(
			(isset($customer['last_name']) ? $customer['last_name'] : '') . ', ' .
			(isset($customer['first_name']) ? $customer['first_name'] : '') . ' ' .
			(isset($customer['middle_name']) ? $customer['middle_name'] : '')
		));
	}

	/**
	 * Reduce computed rows to the frozen snapshot stored on the attempt.
	 * Only the fields settlement needs are kept.
	 */
	public function freeze_rows($rows) {
		$frozen = array();
		foreach ((array) $rows as $row) {
			$frozen[] = array(
				'month'            => isset($row['month']) ? $row['month'] : '',
				'month_name'       => isset($row['month_name']) ? $row['month_name'] : '',
				'year'             => isset($row['year']) ? $row['year'] : '',
				'refno'            => isset($row['refno']) ? $row['refno'] : '',
				'previous_reading' => isset($row['previous_reading']) ? $row['previous_reading'] : '',
				'reading'          => isset($row['reading']) ? $row['reading'] : '',
				'consumed'         => isset($row['consumed']) ? $row['consumed'] : 0,
				'unit_price'       => isset($row['unit_price']) ? $row['unit_price'] : 0,
				'amount_due'       => isset($row['amount_due']) ? $row['amount_due'] : 0,
				'status'           => isset($row['status']) ? $row['status'] : 0,
			);
		}
		return $frozen;
	}

	/** Selected rows -> the total the customer must pay. */
	public function total_of_rows($rows) {
		$total = 0;
		foreach ((array) $rows as $row) {
			$total += isset($row['amount_due']) ? (float) $row['amount_due'] : 0;
		}
		return round($total, 2);
	}

	/**
	 * Stable fingerprint of a selection, so an existing live QR can be reused
	 * only when it charges exactly the same rows for the same amount.
	 */
	public function selection_key($rows) {
		$parts = array();
		foreach ((array) $rows as $row) {
			$parts[] = (isset($row['month']) ? $row['month'] : '') . '/' . (isset($row['year']) ? $row['year'] : '');
		}
		sort($parts);
		return implode(',', $parts);
	}

	// =====================================================================
	// Attempts
	// =====================================================================

	/** Next internal reference for today: ONL-YYYYMMDD-0001. */
	public function generate_reference() {
		$prefix = 'ONL-' . date('Ymd') . '-';
		$this->db->select('reference_no');
		$this->db->from($this->table_name);
		$this->db->like('reference_no', $prefix, 'after');
		$this->db->order_by('reference_no', 'desc');
		$this->db->limit(1);
		$row = $this->db->get()->row_array();
		$next = 1;
		if (is_array($row) && !empty($row['reference_no'])) {
			$tail = substr((string) $row['reference_no'], strlen($prefix));
			$next = ((int) $tail) + 1;
		}
		return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
	}

	/** Insert an attempt and return its id (0 on failure). */
	public function create_attempt($data) {
		if (!$this->table_ready()) {
			return 0;
		}
		$data['create_date_time'] = $this->now();
		$data['update_date_time'] = $this->now();
		if (empty($data['status'])) {
			$data['status'] = 'pending';
		}
		if (!$this->db->insert($this->table_name, $data)) {
			return 0;
		}
		return (int) $this->db->insert_id();
	}

	public function get_attempt($id) {
		if (!$this->table_ready()) {
			return array();
		}
		$this->db->where('id', (int) $id);
		$row = $this->db->get($this->table_name)->row_array();
		return is_array($row) ? $row : array();
	}

	public function get_attempt_by_reference($reference_no) {
		if (!$this->table_ready()) {
			return array();
		}
		$this->db->where('reference_no', trim((string) $reference_no));
		$row = $this->db->get($this->table_name)->row_array();
		return is_array($row) ? $row : array();
	}

	public function get_attempt_by_intent($intent_id) {
		$intent_id = trim((string) $intent_id);
		if (!$this->table_ready() || $intent_id === '') {
			return array();
		}
		$this->db->where('intent_id', $intent_id);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$row = $this->db->get($this->table_name)->row_array();
		return is_array($row) ? $row : array();
	}

	public function get_attempt_by_payment($payment_id) {
		$payment_id = trim((string) $payment_id);
		if (!$this->table_ready() || $payment_id === '') {
			return array();
		}
		$this->db->where('payment_id', $payment_id);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$row = $this->db->get($this->table_name)->row_array();
		return is_array($row) ? $row : array();
	}

	public function update_attempt($id, $data) {
		if (!$this->table_ready()) {
			return false;
		}
		$data['update_date_time'] = $this->now();
		$this->db->where('id', (int) $id);
		return $this->db->update($this->table_name, $data);
	}

	/**
	 * Cancel every still-open attempt for a customer (optionally excluding one).
	 * Called before a new QR is created so the screens never show two live codes.
	 */
	public function cancel_open_attempts($customer_id, $except_id = 0) {
		if (!$this->table_ready()) {
			return false;
		}
		$this->db->where('customer_id', trim((string) $customer_id));
		$this->db->where_in('status', $this->open_statuses);
		if ((int) $except_id > 0) {
			$this->db->where('id !=', (int) $except_id);
		}
		return $this->db->update($this->table_name, array(
			'status' => 'cancelled',
			'update_date_time' => $this->now(),
		));
	}

	/**
	 * A still-live pending attempt that charges exactly this selection, or array().
	 * Reusing it means refreshing the page does not churn through QR codes.
	 */
	public function find_reusable_attempt($customer_id, $amount, $selection_key, $fee_amount = 0) {
		if (!$this->table_ready()) {
			return array();
		}
		$now = $this->now();
		$this->db->where('customer_id', trim((string) $customer_id));
		$this->db->where('status', 'pending');
		$this->db->where('selection_key', $selection_key);
		$this->db->where('amount', number_format((float) $amount, 2, '.', ''));
		// A QR made under different fee settings charges a different total.
		if ($this->db->field_exists('fee_amount', $this->table_name)) {
			$this->db->where('fee_amount', number_format((float) $fee_amount, 2, '.', ''));
		}
		$this->db->where('qr_image_url IS NOT NULL', null, false);
		$this->db->where('(expires_at IS NULL OR expires_at > ' . $this->db->escape($now) . ')', null, false);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$row = $this->db->get($this->table_name)->row_array();
		return is_array($row) ? $row : array();
	}

	/** Mark attempts whose QR has lapsed. Cheap housekeeping before listing. */
	public function expire_lapsed_attempts() {
		if (!$this->table_ready() || !$this->db->field_exists('expires_at', $this->table_name)) {
			return false;
		}
		$this->db->where('status', 'pending');
		$this->db->where('expires_at IS NOT NULL', null, false);
		$this->db->where('expires_at <', $this->now());
		return $this->db->update($this->table_name, array(
			'status' => 'expired',
			'update_date_time' => $this->now(),
		));
	}

	// =====================================================================
	// Emailed payment links (signed token, no login)
	// =====================================================================

	/**
	 * Secret used to sign emailed payment links.
	 * Prefers the app's encryption_key; falls back to a value derived from the
	 * install so links cannot be forged by guessing a customer id.
	 */
	private function token_secret() {
		$key = (string) $this->config->item('encryption_key');
		if ($key === '') {
			$key = 'lwd-online-payment|' . (string) $this->db->database . '|' . base_url();
		}
		return hash('sha256', $key);
	}

	/**
	 * Issue a link token for an attempt: "<reference>.<expiry>.<signature>"
	 * base64url-encoded. Only the SHA-256 of the finished token is stored, so a
	 * database read cannot hand someone a working link.
	 *
	 * @return string the token to put in the URL
	 */
	public function issue_link_token($attempt_id, $reference_no, $expires_ts = null) {
		if ($expires_ts === null) {
			$expires_ts = time() + 3600;
		}
		$reference_no = (string) $reference_no;
		$expires_ts = (int) $expires_ts;
		$sig = substr(hash_hmac('sha256', $reference_no . '|' . $expires_ts, $this->token_secret()), 0, 40);
		$token = $this->base64url_encode($reference_no . '.' . $expires_ts . '.' . $sig);

		if ($this->table_ready()) {
			$this->update_attempt($attempt_id, array(
				'link_token_hash' => hash('sha256', $token),
				'link_expires_at' => date('Y-m-d H:i:s', $expires_ts),
			));
		}
		return $token;
	}

	/**
	 * Verify a token from a URL. Returns the attempt row, or array() when the
	 * token is malformed, tampered with, expired or not the one we issued.
	 */
	public function verify_link_token($token) {
		$token = trim((string) $token);
		if ($token === '' || !$this->table_ready()) {
			return array();
		}
		$decoded = $this->base64url_decode($token);
		if ($decoded === false) {
			return array();
		}
		$parts = explode('.', $decoded);
		if (count($parts) !== 3) {
			return array();
		}
		list($reference_no, $expires_ts, $sig) = $parts;
		$expires_ts = (int) $expires_ts;

		$expected = substr(hash_hmac('sha256', $reference_no . '|' . $expires_ts, $this->token_secret()), 0, 40);
		if (!hash_equals($expected, (string) $sig)) {
			return array();
		}
		if ($expires_ts > 0 && $expires_ts < time()) {
			return array();
		}

		$attempt = $this->get_attempt_by_reference($reference_no);
		if (empty($attempt)) {
			return array();
		}
		$stored = isset($attempt['link_token_hash']) ? (string) $attempt['link_token_hash'] : '';
		if ($stored === '' || !hash_equals($stored, hash('sha256', $token))) {
			return array();
		}
		return $attempt;
	}

	private function base64url_encode($value) {
		return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
	}

	private function base64url_decode($value) {
		$value = strtr(trim((string) $value), '-_', '+/');
		$pad = strlen($value) % 4;
		if ($pad > 0) {
			$value .= str_repeat('=', 4 - $pad);
		}
		$decoded = base64_decode($value, true);
		return $decoded === false ? false : $decoded;
	}

	// =====================================================================
	// Settlement
	// =====================================================================

	/**
	 * Write a confirmed online payment into the books.
	 *
	 * Idempotent: an attempt already marked paid returns success without writing
	 * anything again, so the webhook, the poller and the manual check can all
	 * land in whatever order they like.
	 *
	 * @param int    $attempt_id
	 * @param string $payment_id PayMongo pay_... (optional)
	 * @param string $source     'webhook' | 'poll' | 'manual' — recorded for audit
	 * @return array array('ok' => bool, 'already' => bool, 'message' => string, 'ids' => array)
	 */
	public function settle($attempt_id, $payment_id = '', $source = 'manual') {
		if (!$this->table_ready()) {
			return array('ok' => false, 'message' => 'Run sql/add_online_payments.sql before settling payments.');
		}

		$this->db->trans_begin();

		$q = $this->db->query('SELECT * FROM ' . $this->table_name . ' WHERE id = ? FOR UPDATE', array((int) $attempt_id));
		$attempt = $q ? $q->row_array() : null;
		if (!is_array($attempt)) {
			$this->db->trans_rollback();
			return array('ok' => false, 'message' => 'Payment attempt not found.');
		}
		if ((string) $attempt['status'] === 'paid') {
			$this->db->trans_rollback();
			return array('ok' => true, 'already' => true, 'message' => 'This payment was already recorded.', 'ids' => array());
		}
		if (in_array((string) $attempt['status'], array('cancelled', 'failed', 'expired'), true)) {
			$this->db->trans_rollback();
			return array('ok' => false, 'message' => 'This QR is ' . $attempt['status'] . ' and can no longer be settled. Create a new one.');
		}

		$rows = json_decode(isset($attempt['bill_rows_json']) ? (string) $attempt['bill_rows_json'] : '', true);
		if (!is_array($rows) || empty($rows)) {
			$this->db->trans_rollback();
			return array('ok' => false, 'message' => 'This attempt has no billing rows stored.');
		}

		$customer_id = trim((string) $attempt['customer_id']);
		$customer = $this->get_customer_info($customer_id);
		if (empty($customer)) {
			$this->db->trans_rollback();
			return array('ok' => false, 'message' => 'Customer ' . $customer_id . ' no longer exists.');
		}

		$amount = round((float) $attempt['amount'], 2);
		$invoice_id = 'WTMQ-' . (string) $attempt['reference_no'];
		$trans_date = date('d-m-Y');
		$reference = trim((string) $attempt['payment_id']) !== '' ? (string) $attempt['payment_id'] : (string) $attempt['reference_no'];
		if ($payment_id !== '' && trim((string) $payment_id) !== '') {
			$reference = trim((string) $payment_id);
		}

		// The cashier's form posts a ledger; online uses the same default the
		// cash form shows (the first ledger), so the GL debit lands in the usual
		// place.
		$ledger_id = $this->default_ledger_id();

		// The write path reads the cashier's form, so build that form exactly.
		// add_record_multiple is the single implementation used by both
		// channels, which is what keeps cash and online from drifting apart.
		$saved_post = $_POST;
		$prev_user = $this->session->userdata('userid');
		$prev_username = $this->session->userdata('username');

		$ids = array();
		$_POST = array();
		try {
			$_POST['customer_id_next'] = $customer_id;
			$_POST['fullname'] = self::full_name($customer);
			$_POST['currency'] = 'PHP';
			$_POST['ledger_id'] = $ledger_id;
			$_POST['time_format'] = $invoice_id;
			$_POST['transdate'] = $trans_date;
			$_POST['trans_date'] = $trans_date;
			$_POST['paid_total_amount'] = $amount;
			$_POST['pay_amount'] = $amount;      // paid in full — no change
			$_POST['change_amount'] = '0.00';
			$_POST['grand_total'] = $amount;
			$_POST['vat_percent'] = '';
			$_POST['vat_amount'] = '0.00';
			$_POST['leaking_percent'] = '';
			$_POST['leaking_amount'] = '0.00';
			$_POST['status_id'] = isset($customer['status']) ? $customer['status'] : 1;

			// Attribute the collection to whoever started the QR when we are not
			// in that user's session (webhook / polling from another device).
			if (!empty($attempt['created_by'])) {
				$this->session->set_userdata('userid', (int) $attempt['created_by']);
			}

			$index = 0;
			$_POST['checkbox'] = array();
			foreach ($rows as $row) {
				$index++;
				$_POST['checkbox'][$index] = $index;
				$_POST['previousreading_' . $index] = isset($row['previous_reading']) ? $row['previous_reading'] : '';
				$_POST['reading_' . $index] = isset($row['reading']) ? $row['reading'] : '';
				$_POST['consumedunit_' . $index] = isset($row['consumed']) ? $row['consumed'] : 0;
				$_POST['unit_price_' . $index] = isset($row['unit_price']) ? $row['unit_price'] : 0;
				$_POST['monthid_' . $index] = isset($row['month']) ? $row['month'] : '';
				$_POST['year_' . $index] = isset($row['year']) ? $row['year'] : '';
				$_POST['status_' . $index] = isset($row['status']) ? $row['status'] : 1;
				$_POST['prsentamount_' . $index] = isset($row['amount_due']) ? $row['amount_due'] : 0;

				$written = $this->pay_model->add_record_multiple($index, null, array(
					'channel' => 'qrph',
					'online_payment_id' => (int) $attempt['id'],
					'online_reference' => $reference,
				));
				if (!$written) {
					throw new Exception('Writing billing row ' . $index . ' failed.');
				}
			}

			// Collect the rows we just created via the batch's invoice id.
			$created = $this->db->query(
				'SELECT id FROM ' . $this->pay_model->table_name . ' WHERE customer_id = ? AND invoice_id = ? ORDER BY id ASC',
				array($customer_id, $invoice_id)
			);
			if ($created) {
				foreach ($created->result_array() as $r) {
					$ids[] = (int) $r['id'];
				}
			}
			if (empty($ids)) {
				throw new Exception('The payment rows could not be confirmed after writing.');
			}
		} catch (Exception $e) {
			$_POST = $saved_post;
			$this->session->set_userdata('userid', $prev_user);
			$this->session->set_userdata('username', $prev_username);
			$this->db->trans_rollback();
			return array('ok' => false, 'message' => $e->getMessage());
		}

		$_POST = $saved_post;
		$this->session->set_userdata('userid', $prev_user);
		$this->session->set_userdata('username', $prev_username);

		$this->db->where('id', (int) $attempt['id']);
		$this->db->update($this->table_name, array(
			'status' => 'paid',
			'payment_id' => $reference,
			'paid_at' => $this->now(),
			'settled_at' => $this->now(),
			'settled_billing_ids' => json_encode($ids),
			'update_date_time' => $this->now(),
		));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return array('ok' => false, 'message' => 'The payment could not be saved. Nothing was recorded.');
		}
		$this->db->trans_commit();

		// Activity trail (best effort, never blocks the money).
		$this->log_settlement($attempt, $ids, $reference, $source);

		return array('ok' => true, 'already' => false, 'message' => 'Payment recorded.', 'ids' => $ids);
	}

	/** Best-effort audit line; skipped when the helper or table is absent. */
	private function log_settlement($attempt, $ids, $reference, $source) {
		if (!function_exists('log_system_activity')) {
			return;
		}
		if (!$this->db->table_exists('tbl_system_activity')) {
			return;
		}
		try {
			log_system_activity(array(
				'user_id' => isset($attempt['created_by']) ? (int) $attempt['created_by'] : null,
				'username' => '',
				'category' => 'payment',
				'action' => 'settle',
				'module' => 'onlinepayment',
				'controller' => 'onlinepayment',
				'method' => $source,
				'entity_type' => 'online_payment',
				'entity_id' => isset($attempt['id']) ? (int) $attempt['id'] : null,
				'reference_no' => $reference,
				'amount' => isset($attempt['amount']) ? (float) $attempt['amount'] : 0,
				'status_after' => 'paid',
				'summary' => 'QR Ph payment recorded for customer ' . (isset($attempt['customer_id']) ? $attempt['customer_id'] : ''),
				'details_json' => json_encode(array('billing_ids' => $ids, 'source' => $source)),
			));
		} catch (Exception $e) {
			// Never let an audit failure break a completed payment.
		}
	}

	/** First ledger id — the same default the Cash Payment form shows. */
	private function default_ledger_id() {
		$ledgers = $this->pay_model->fetchLedger();
		if (is_array($ledgers) && isset($ledgers[0]['id'])) {
			return (int) $ledgers[0]['id'];
		}
		return 0;
	}

	// =====================================================================
	// Starting a QR Ph payment
	// =====================================================================

	/**
	 * Create (or reuse) a QR Ph attempt for the selected billing rows.
	 *
	 * Both the mobile Payment page and the desktop Online Payment page call
	 * this, so the two can never behave differently.
	 *
	 * @param string $customer_id
	 * @param array  $rows        computed rows from get_customer_bills() that the
	 *                            user actually selected
	 * @param string $context     'mobile' | 'desktop'
	 * @param int    $created_by  staff user id
	 * @param bool   $reuse       reuse a live QR for an identical selection
	 * @return array array('ok'=>bool,'message'=>string,'attempt'=>array)
	 */
	public function start_qrph($customer_id, $rows, $context, $created_by, $reuse = true) {
		if (!$this->table_ready()) {
			return array('ok' => false, 'message' => 'Run sql/add_online_payments.sql first.', 'attempt' => array());
		}

		$this->load->library('Paymongo');
		if (!$this->paymongo->is_configured()) {
			return array(
				'ok' => false,
				'message' => 'Online payments are not ready. An administrator must enable them and save a PayMongo secret key in Settings → PayMongo Setup.',
				'attempt' => array(),
			);
		}

		$rows = is_array($rows) ? $rows : array();
		if (empty($rows)) {
			return array('ok' => false, 'message' => 'Select at least one billing period to pay.', 'attempt' => array());
		}
		foreach ($rows as $row) {
			if (empty($row['is_payable'])) {
				return array('ok' => false, 'message' => 'One of the selected periods is already paid or has no meter reading.', 'attempt' => array());
			}
		}

		$customer_id = trim((string) $customer_id);
		$amount = $this->total_of_rows($rows);
		$selection_key = $this->selection_key($rows);
		$min = $this->paymongo->min_amount_pesos();
		if ($amount < $min) {
			return array(
				'ok' => false,
				'message' => 'The amount due (₱ ' . number_format($amount, 2) . ') is below the online payment minimum of ₱ ' . number_format($min, 2) . '.',
				'attempt' => array(),
			);
		}

		// The processing fee rides on top of the bill; only the bill is credited
		// to the water account when the payment settles.
		$fee = $this->paymongo->compute_fee($amount);
		$charged = $fee['charged'];

		$centavos = $this->paymongo->to_centavos($charged);
		if ($centavos === false) {
			return array('ok' => false, 'message' => 'That amount is too small for PayMongo.', 'attempt' => array());
		}

		// Reuse a live code rather than churning through new ones on refresh.
		if ($reuse) {
			$existing = $this->find_reusable_attempt($customer_id, $amount, $selection_key, $fee['fee']);
			if (!empty($existing)) {
				return array('ok' => true, 'message' => 'Reusing the live QR for this selection.', 'attempt' => $existing, 'reused' => true);
			}
		}

		// A customer can only have one live code at a time.
		$this->cancel_open_attempts($customer_id);

		$customer = $this->get_customer_info($customer_id);
		$reference_no = $this->generate_reference();
		$description = $this->paymongo_description($customer_id, $customer, $rows, $fee['fee']);
		$fee_fields = $this->fee_fields($fee);

		$qr = $this->paymongo->create_qrph($charged, $description, array(
			'reference_no' => $reference_no,
			'customer_id' => $customer_id,
			'context' => (string) $context,
		));

		if ($qr === false) {
			$message = $this->paymongo->last_error();
			if ($message === '') {
				$message = 'PayMongo could not create a QR code.';
			}
			// Keep the reason where an operator can find it (file logging is off
			// on these installs).
			$this->load->model('paymongo_settings_model', 'gateway_settings');
			$this->gateway_settings->record_last_error($message, $this->paymongo->last_error_class());

			$attempt_id = $this->create_attempt(array(
				'reference_no' => $reference_no,
				'customer_id' => $customer_id,
				'channel' => 'qrph',
				'context' => (string) $context,
				'amount' => $amount,
				'amount_centavos' => $centavos,
				'bill_rows_json' => json_encode($this->freeze_rows($rows)),
				'selection_key' => $selection_key,
				'status' => 'failed',
				'last_error' => $message,
				'created_by' => (int) $created_by,
			) + $fee_fields);
			return array('ok' => false, 'message' => $message, 'attempt' => $attempt_id ? $this->get_attempt($attempt_id) : array());
		}

		$expires_at = !empty($qr['expires_at']) ? $qr['expires_at'] : date('Y-m-d H:i:s', time() + 3600);

		$attempt_id = $this->create_attempt(array(
			'reference_no' => $reference_no,
			'customer_id' => $customer_id,
			'channel' => 'qrph',
			'context' => (string) $context,
			'intent_id' => $qr['intent_id'],
			'payment_id' => '',
			'source_id' => $qr['source_id'],
			'qr_image_url' => $qr['qr_image_url'],
			'qr_test_url' => $qr['test_url'],
			'amount' => $amount,
			'amount_centavos' => $centavos,
			'bill_rows_json' => json_encode($this->freeze_rows($rows)),
			'selection_key' => $selection_key,
			'status' => 'pending',
			'expires_at' => $expires_at,
			'created_by' => (int) $created_by,
		) + $fee_fields);

		if (!$attempt_id) {
			return array('ok' => false, 'message' => 'The QR was created but could not be saved. Nothing was charged.', 'attempt' => array());
		}

		// A successful creation clears any stale failure banner.
		$this->load->model('paymongo_settings_model', 'gateway_settings');
		$this->gateway_settings->clear_last_error();

		return array('ok' => true, 'message' => 'QR code ready.', 'attempt' => $this->get_attempt($attempt_id));
	}

	/**
	 * Billing periods being paid, oldest first: "August 2026" or
	 * "July 2026, August 2026, September 2026". More than three periods collapse
	 * to "January 2026 to June 2026 (6 periods)" so the description stays short.
	 */
	public static function period_label($rows) {
		$periods = array();
		foreach ((array) $rows as $row) {
			$year = isset($row['year']) ? (int) $row['year'] : 0;
			$month = isset($row['month']) ? (int) $row['month'] : 0;
			$name = isset($row['month_name']) ? trim((string) $row['month_name']) : '';
			if ($name === '' && $month >= 1 && $month <= 12) {
				$name = date('F', mktime(0, 0, 0, $month, 1, 2000));
			}
			$periods[] = array('key' => $year * 100 + $month, 'label' => trim($name . ' ' . ($year > 0 ? $year : '')));
		}
		usort($periods, function ($a, $b) { return $a['key'] - $b['key']; });
		$labels = array();
		foreach ($periods as $p) {
			if ($p['label'] !== '') {
				$labels[] = $p['label'];
			}
		}
		if (count($labels) > 3) {
			return $labels[0] . ' to ' . $labels[count($labels) - 1] . ' (' . count($labels) . ' periods)';
		}
		return implode(', ', $labels);
	}

	/**
	 * Description shown on the PayMongo record, e.g.
	 * "LWD Bill Payment — 1234 — DELA CRUZ, JUAN P — August 2026 (incl. PHP 17.50 fee)".
	 */
	private function paymongo_description($customer_id, $customer, $rows, $fee_amount) {
		$parts = array($this->paymongo->description_prefix(), $customer_id);
		$name = trim(self::full_name($customer), ' ,');
		if ($name !== '') {
			$parts[] = $name;
		}
		$periods = self::period_label($rows);
		if ($periods !== '') {
			$parts[] = $periods;
		}
		$description = implode(' — ', $parts);
		if ($fee_amount > 0) {
			$description .= ' (incl. PHP ' . number_format($fee_amount, 2) . ' fee)';
		}
		// Kept well inside PayMongo's description length limit.
		if (function_exists('mb_substr')) {
			return mb_substr($description, 0, 255, 'UTF-8');
		}
		return substr($description, 0, 255);
	}

	/**
	 * Fee columns for a new attempt, or array() before
	 * sql/add_paymongo_customer_fee.sql has run (the fee is then always 0).
	 */
	private function fee_fields($fee) {
		if (!$this->db->field_exists('fee_amount', $this->table_name)) {
			return array();
		}
		return array(
			'fee_amount'     => number_format((float) $fee['fee'], 2, '.', ''),
			'fee_percent'    => $fee['fee'] > 0 ? number_format((float) $fee['percent'], 3, '.', '') : null,
			'fee_fixed'      => $fee['fee'] > 0 ? number_format((float) $fee['fixed'], 2, '.', '') : null,
			'charged_amount' => number_format((float) $fee['charged'], 2, '.', ''),
		);
	}

	/**
	 * What the customer pays (bill + fee) for an attempt row. Rows created
	 * before the fee existed charged exactly the bill.
	 */
	public static function charged_of($attempt) {
		if (isset($attempt['charged_amount']) && $attempt['charged_amount'] !== null && $attempt['charged_amount'] !== '') {
			return (float) $attempt['charged_amount'];
		}
		return isset($attempt['amount']) ? (float) $attempt['amount'] : 0.0;
	}

	/** The processing fee on an attempt row (0 for rows before the fee existed). */
	public static function fee_of($attempt) {
		return isset($attempt['fee_amount']) ? (float) $attempt['fee_amount'] : 0.0;
	}

	/**
	 * Ask PayMongo whether an attempt has been paid yet, and settle it if so.
	 * This is the single re-check used by polling, the manual button and the
	 * webhook. Returns array('ok','paid','attempt','message').
	 */
	public function refresh_attempt($attempt_id, $source = 'manual') {
		$attempt = $this->get_attempt($attempt_id);
		if (empty($attempt)) {
			return array('ok' => false, 'paid' => false, 'attempt' => array(), 'message' => 'Payment attempt not found.');
		}
		if ((string) $attempt['status'] === 'paid') {
			return array('ok' => true, 'paid' => true, 'attempt' => $attempt, 'message' => 'Already paid.');
		}
		if (trim((string) $attempt['intent_id']) === '') {
			return array('ok' => false, 'paid' => false, 'attempt' => $attempt, 'message' => 'This attempt has no PayMongo intent to check.');
		}

		$this->load->library('Paymongo');
		$intent = $this->paymongo->get_intent($attempt['intent_id']);
		if ($intent === false) {
			return array('ok' => false, 'paid' => false, 'attempt' => $attempt, 'message' => $this->paymongo->last_error());
		}

		if (!$this->paymongo->intent_is_paid($intent)) {
			return array('ok' => true, 'paid' => false, 'attempt' => $attempt, 'message' => 'Not paid yet.');
		}

		$payment_id = $this->paymongo->extract_paid_payment_id($intent);
		$result = $this->settle($attempt['id'], $payment_id, $source);
		$fresh = $this->get_attempt($attempt['id']);
		return array(
			'ok' => !empty($result['ok']),
			'paid' => !empty($result['ok']),
			'attempt' => $fresh,
			'message' => isset($result['message']) ? $result['message'] : '',
		);
	}

	/**
	 * Work out which of our attempts a webhook payload refers to.
	 *
	 * Only identifiers are taken from the payload — never an amount or a status.
	 * Tries, in order: the intent id, the payment id, our own reference in the
	 * metadata.
	 *
	 * @param array $resource the event's data.attributes.data resource
	 * @return array the attempt row, or array()
	 */
	public function resolve_attempt_from_payload($resource) {
		if (!is_array($resource) || empty($resource)) {
			return array();
		}
		$attrs = isset($resource['attributes']) && is_array($resource['attributes'])
			? $resource['attributes']
			: $resource;

		$intent_id = '';
		foreach (array('payment_intent_id', 'payment_intent') as $key) {
			if (!empty($attrs[$key]) && is_string($attrs[$key])) {
				$intent_id = $attrs[$key];
				break;
			}
		}
		if ($intent_id !== '') {
			$attempt = $this->get_attempt_by_intent($intent_id);
			if (!empty($attempt)) {
				return $attempt;
			}
		}

		if (!empty($resource['id']) && is_string($resource['id']) && strpos($resource['id'], 'pay_') === 0) {
			$attempt = $this->get_attempt_by_payment($resource['id']);
			if (!empty($attempt)) {
				return $attempt;
			}
		}

		if (!empty($attrs['metadata']) && is_array($attrs['metadata']) && !empty($attrs['metadata']['reference_no'])) {
			$attempt = $this->get_attempt_by_reference($attrs['metadata']['reference_no']);
			if (!empty($attempt)) {
				return $attempt;
			}
		}

		return array();
	}

	// =====================================================================
	// Listing (manage screens + report)
	// =====================================================================

	/**
	 * Attempts matching a filter, newest first.
	 * $filters: date_from, date_to, status, context, customer_id, created_by, q
	 */
	public function get_attempts($filters = array(), $limit = 25, $offset = 0) {
		if (!$this->table_ready()) {
			return array();
		}
		$this->_apply_filters($filters);
		$this->db->order_by('id', 'desc');
		if ((int) $limit > 0) {
			$this->db->limit((int) $limit, (int) $offset);
		}
		return $this->db->get($this->table_name)->result_array();
	}

	public function count_attempts($filters = array()) {
		if (!$this->table_ready()) {
			return 0;
		}
		// Joined so a zone filter can be honoured for the report pager.
		$this->db->from($this->table_name . ' op');
		$this->db->join($this->table_customer . ' c', 'c.customer_id = op.customer_id', 'left');
		$this->_apply_filters($filters, 'op.', 'c.');
		return (int) $this->db->count_all_results();
	}

	/**
	 * Applies report/list filters.
	 *
	 * @param string $alias          table alias prefix (e.g. 'op.') when the caller aliased the table
	 * @param string $customer_alias customer join prefix (e.g. 'c.'); when empty, filters that live
	 *                               on tbl_addcustomer (zone) are skipped because nothing is joined
	 */
	private function _apply_filters($filters, $alias = '', $customer_alias = '') {
		$filters = is_array($filters) ? $filters : array();
		$p = $alias;

		if (!empty($filters['status'])) {
			if ($filters['status'] === 'open') {
				$this->db->where_in($p . 'status', $this->open_statuses);
			} elseif ($filters['status'] !== 'all') {
				$this->db->where($p . 'status', (string) $filters['status']);
			}
		}
		if (!empty($filters['context']) && $filters['context'] !== 'all') {
			$this->db->where($p . 'context', (string) $filters['context']);
		}
		if (!empty($filters['customer_id'])) {
			$this->db->where($p . 'customer_id', trim((string) $filters['customer_id']));
		}
		if (!empty($filters['created_by'])) {
			$this->db->where($p . 'created_by', (int) $filters['created_by']);
		}
		if (!empty($filters['zone']) && $customer_alias !== '') {
			$zone = (int) $filters['zone'];
			if ($zone > 0) {
				// Filter on the stored id, numerically — the same comparison the app's own
				// `tbl_zone.id = tbl_addcustomer.zone` joins rely on.
				$this->db->where($customer_alias . 'zone', $zone);
			}
		}
		if (!empty($filters['date_from'])) {
			$ts = strtotime(str_replace('-', '/', (string) $filters['date_from']));
			if ($ts) {
				$this->db->where($p . 'create_date_time >=', date('Y-m-d 00:00:00', $ts));
			}
		}
		if (!empty($filters['date_to'])) {
			$ts = strtotime(str_replace('-', '/', (string) $filters['date_to']));
			if ($ts) {
				$this->db->where($p . 'create_date_time <=', date('Y-m-d 23:59:59', $ts));
			}
		}
		if (!empty($filters['q'])) {
			$q = trim((string) $filters['q']);
			$this->db->group_start();
			$this->db->like($p . 'reference_no', $q);
			$this->db->or_like($p . 'customer_id', $q);
			$this->db->or_like($p . 'payment_id', $q);
			$this->db->or_like($p . 'intent_id', $q);
			$this->db->group_end();
		}
	}

	/**
	 * Report rows joined to the customer so the report can show a name and zone.
	 */
	public function get_report_rows($filters = array(), $limit = 0, $offset = 0) {
		if (!$this->table_ready()) {
			return array();
		}
		// `tbl_addcustomer` has no `zones` column — it stores `zone` (the zone id), and the
		// rest of the app surfaces the zone NAME through a `zones` alias. Selecting `c.zones`
		// raised MySQL 1054 and a HTTP 500 on this report. Keep the alias so the report still
		// prints the name.
		$this->db->select(
			'op.*, c.first_name, c.middle_name, c.last_name, c.address, c.meter_number,'
			. ' (SELECT z.zone FROM ' . $this->table_zone . ' z WHERE z.id = c.zone) AS zones,'
			. ' c.customer_type',
			false
		);
		$this->db->from($this->table_name . ' op');
		$this->db->join($this->table_customer . ' c', 'c.customer_id = op.customer_id', 'left');
		$this->_apply_filters($filters, 'op.', 'c.');
		$this->db->order_by('op.id', 'desc');
		if ((int) $limit > 0) {
			$this->db->limit((int) $limit, (int) $offset);
		}
		return $this->db->get()->result_array();
	}

	/**
	 * Totals for the report KPI cards: counted, paid and still outstanding.
	 * Returned in pesos.
	 */
	public function get_report_totals($filters = array()) {
		if (!$this->table_ready()) {
			return array('count' => 0, 'paid_count' => 0, 'paid_amount' => 0.0, 'paid_fee' => 0.0, 'paid_charged' => 0.0, 'open_count' => 0, 'open_amount' => 0.0, 'attempted_amount' => 0.0);
		}
		$fee_sql = $this->db->field_exists('fee_amount', $this->table_name)
			? " SUM(CASE WHEN op.status = 'paid' THEN op.fee_amount ELSE 0 END) AS paid_fee,"
			: ' 0 AS paid_fee,';
		$this->db->select(
			'COUNT(*) AS count,'
			. $fee_sql
			. " SUM(CASE WHEN op.status = 'paid' THEN 1 ELSE 0 END) AS paid_count,"
			. " SUM(CASE WHEN op.status = 'paid' THEN op.amount ELSE 0 END) AS paid_amount,"
			. " SUM(CASE WHEN op.status IN ('pending','expired','failed') THEN 1 ELSE 0 END) AS open_count,"
			. " SUM(CASE WHEN op.status IN ('pending','expired','failed') THEN op.amount ELSE 0 END) AS open_amount,"
			. ' SUM(op.amount) AS attempted_amount',
			false
		);
		$this->db->from($this->table_name . ' op');
		$this->db->join($this->table_customer . ' c', 'c.customer_id = op.customer_id', 'left');
		$this->_apply_filters($filters, 'op.', 'c.');
		$row = $this->db->get()->row_array();
		if (!is_array($row)) {
			$row = array();
		}
		return array(
			'count'            => isset($row['count']) ? (int) $row['count'] : 0,
			'paid_count'       => isset($row['paid_count']) ? (int) $row['paid_count'] : 0,
			'paid_amount'      => isset($row['paid_amount']) ? (float) $row['paid_amount'] : 0.0,
			'paid_fee'         => isset($row['paid_fee']) ? (float) $row['paid_fee'] : 0.0,
			'paid_charged'     => (isset($row['paid_amount']) ? (float) $row['paid_amount'] : 0.0) + (isset($row['paid_fee']) ? (float) $row['paid_fee'] : 0.0),
			'open_count'       => isset($row['open_count']) ? (int) $row['open_count'] : 0,
			'open_amount'      => isset($row['open_amount']) ? (float) $row['open_amount'] : 0.0,
			'attempted_amount' => isset($row['attempted_amount']) ? (float) $row['attempted_amount'] : 0.0,
		);
	}

	/** Attempts that still owe money, for the "chase an unpaid link" list. */
	public function get_open_attempts($limit = 50, $offset = 0) {
		return $this->get_attempts(array('status' => 'open'), $limit, $offset);
	}

	/**
	 * Collector display name for a user id.
	 *
	 * created_by is the session 'userid', which is tbl_responsibilities_user.id
	 * (the same key tbl_addmetercustomer.userid and tbl_doc_series_number.teller_user_id use).
	 */
	public function collector_name($user_id) {
		$user_id = (int) $user_id;
		if ($user_id <= 0 || !$this->db->table_exists($this->table_users)) {
			return '';
		}
		$this->db->select('*');
		$this->db->from($this->table_users);
		$this->db->where('id', $user_id);
		$row = $this->db->get()->row_array();
		if (!is_array($row)) {
			return '';
		}
		foreach (array('employee_name', 'name', 'full_name', 'fullname', 'username') as $key) {
			if (!empty($row[$key])) {
				return (string) $row[$key];
			}
		}
		return '';
	}
}
