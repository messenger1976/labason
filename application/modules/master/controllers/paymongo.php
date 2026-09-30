<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * PayMongo webhook receiver (public endpoint).
 *
 * The rule this class follows: **a webhook tells us WHICH payment, never THAT it
 * is paid.** Whatever the payload claims, we re-read the resource from PayMongo
 * with the secret key and settle from that answer. Settling is idempotent, so a
 * retry, a replay or a poll arriving at the same time cannot double-post.
 *
 * Answers:
 *   200 — handled (or deliberately ignored; PayMongo retries non-2xx)
 *   401 — signature did not verify
 *   400 — body could not be parsed
 *
 * Register the URL shown in Settings → PayMongo Setup, subscribed to payment.paid.
 */
class paymongo extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('onlinepayment_model', 'my_model');
		ini_set('date.timezone', 'Asia/Manila');
		error_reporting(0);
		ini_set('display_errors', 'off');
	}

	/** Health check — lets an operator confirm the URL is reachable at all. */
	public function index() {
		header('Content-Type: application/json');
		echo json_encode(array('ok' => true, 'message' => 'PayMongo webhook endpoint. POST events here.'));
		exit;
	}

	public function webhook() {
		header('Content-Type: application/json');

		$raw = file_get_contents('php://input');
		if ($raw === false || trim((string) $raw) === '') {
			http_response_code(400);
			echo json_encode(array('ok' => false, 'message' => 'Empty body.'));
			exit;
		}

		$this->load->library('Paymongo');

		$signature = $this->input->server('HTTP_PAYMONGO_SIGNATURE');
		if (!$this->paymongo->verify_webhook_signature($raw, $signature)) {
			http_response_code(401);
			echo json_encode(array('ok' => false, 'message' => 'Signature verification failed.'));
			exit;
		}

		$event = json_decode($raw, true);
		if (!is_array($event)) {
			http_response_code(400);
			echo json_encode(array('ok' => false, 'message' => 'Body is not valid JSON.'));
			exit;
		}

		$type = isset($event['data']['attributes']['type']) ? (string) $event['data']['attributes']['type'] : '';
		if ($type === '') {
			http_response_code(400);
			echo json_encode(array('ok' => false, 'message' => 'Event type missing.'));
			exit;
		}

		// Only paid / failed events concern us. Everything else is acknowledged
		// and ignored so PayMongo stops retrying.
		if ($type !== 'payment.paid' && $type !== 'payment.failed') {
			echo json_encode(array('ok' => true, 'message' => 'Ignored: ' . $type));
			exit;
		}

		$resource = isset($event['data']['attributes']['data']) && is_array($event['data']['attributes']['data'])
			? $event['data']['attributes']['data']
			: array();

		$attempt = $this->my_model->resolve_attempt_from_payload($resource);
		if (empty($attempt)) {
			// Nothing of ours — acknowledge so PayMongo does not keep retrying.
			echo json_encode(array('ok' => true, 'message' => 'No matching payment attempt.'));
			exit;
		}

		// Record that we heard from PayMongo (useful when debugging locally).
		$this->my_model->update_attempt($attempt['id'], array('webhook_received_at' => date('Y-m-d H:i:s')));

		if ($type === 'payment.failed') {
			if ((string) $attempt['status'] === 'pending') {
				$this->my_model->update_attempt($attempt['id'], array(
					'status' => 'failed',
					'last_error' => 'PayMongo reported the payment as failed.',
				));
			}
			echo json_encode(array('ok' => true, 'message' => 'Payment marked failed.'));
			exit;
		}

		// payment.paid — re-read from the gateway before believing it.
		$result = $this->my_model->refresh_attempt($attempt['id'], 'webhook');
		echo json_encode(array(
			'ok' => !empty($result['ok']),
			'paid' => !empty($result['paid']),
			'message' => isset($result['message']) ? $result['message'] : '',
		));
		exit;
	}
}
