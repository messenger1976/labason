<?php
/**
 * Shared QR Ph panel — the live QR block used by the desktop Online Payment page
 * and the mobile Payment page, so the two cannot drift apart.
 *
 * Expects:
 *   $qr_attempt    the attempt row (id, reference_no, amount, status, qr_image_url,
 *                  qr_test_url, expires_at, link, preview_only?)
 *   $qr_status_url URL that returns {ok, paid, status_label, receipt_url}
 *   $qr_receipt_url 80mm receipt URL
 *   $qr_print_url  80mm receipt URL with ?preview=1
 * Optional:
 *   $qr_uid        unique suffix so more than one panel can coexist
 *
 * Behaviour: shows the code, counts down its validity and polls the status
 * endpoint. When the payment lands it swaps to a success state with Print.
 */
$qr_attempt = isset($qr_attempt) && is_array($qr_attempt) ? $qr_attempt : array();
$qr_status_url = isset($qr_status_url) ? (string) $qr_status_url : '';
$qr_receipt_url = isset($qr_receipt_url) ? (string) $qr_receipt_url : '';
$qr_print_url = isset($qr_print_url) ? (string) $qr_print_url : $qr_receipt_url;
$qr_uid = isset($qr_uid) && $qr_uid !== '' ? preg_replace('/[^a-z0-9_-]/i', '', (string) $qr_uid) : 'qr';

$qr_image = isset($qr_attempt['qr_image_url']) ? (string) $qr_attempt['qr_image_url'] : '';
$qr_test = isset($qr_attempt['qr_test_url']) ? (string) $qr_attempt['qr_test_url'] : '';
$qr_amount = isset($qr_attempt['amount']) ? (float) $qr_attempt['amount'] : 0;
$qr_reference = isset($qr_attempt['reference_no']) ? (string) $qr_attempt['reference_no'] : '';
$qr_status = isset($qr_attempt['status']) ? (string) $qr_attempt['status'] : 'pending';
$qr_expires = isset($qr_attempt['expires_at']) ? (string) $qr_attempt['expires_at'] : '';
$qr_link = isset($qr_attempt['link']) ? (string) $qr_attempt['link'] : '';

$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
?>
<div class="qrph-panel" id="qrph-<?php echo $qr_uid; ?>"
	data-status-url="<?php echo $h($qr_status_url); ?>"
	data-receipt-url="<?php echo $h($qr_receipt_url); ?>"
	data-preview-url="<?php echo $h($qr_print_url); ?>"
	data-status="<?php echo $h($qr_status); ?>"
	data-expires="<?php echo $h($qr_expires); ?>">

	<div class="qrph-head text-center">
		<div class="qrph-label">AMOUNT TO PAY</div>
		<div class="qrph-amount">&#8369; <?php echo number_format($qr_amount, 2); ?></div>
		<div class="qrph-ref">Ref: <span class="js-ref"><?php echo $h($qr_reference); ?></span></div>
		<div class="qrph-status">
			<span class="badge badge-warning js-status-badge">Waiting for payment</span>
		</div>
	</div>

	<div class="qrph-body text-center">
		<div class="qrph-image-wrap js-qr-wrap">
			<?php if ($qr_image !== '') { ?>
			<img class="qrph-image js-qr-image" src="<?php echo $h($qr_image); ?>" alt="QR Ph code">
			<?php } else { ?>
			<div class="qrph-missing">No QR image was returned.</div>
			<?php } ?>
		</div>

		<div class="qrph-hint">
			Ask the customer to open any bank or e-wallet app, choose <strong>Scan / QR Ph</strong>,
			and scan this code.
		</div>

		<div class="qrph-countdown js-countdown text-muted"></div>

		<?php if ($qr_test !== '') { ?>
		<div class="alert alert-warning mt-2 mb-2 text-left">
			<strong>Test mode.</strong> This QR is not a real charge — do not pay it with a real
			bank or e-wallet app. Use the PayMongo simulator instead:
			<a href="<?php echo $h($qr_test); ?>" target="_blank" rel="noopener">open the simulator</a>,
			then choose <em>Authorize</em>.
		</div>
		<?php } ?>

		<div class="qrph-alert js-alert" style="display:none;"></div>

		<div class="qrph-actions">
			<button type="button" class="btn btn-primary btn-sm waves-effect waves-themed js-check">
				<i class="fal fa-sync mr-1"></i> Check status
			</button>
			<button type="button" class="btn btn-success btn-sm waves-effect waves-themed js-print" style="display:none;">
				<i class="fal fa-print mr-1"></i> Print receipt
			</button>
			<?php if ($qr_link !== '') { ?>
			<button type="button" class="btn btn-outline-secondary btn-sm waves-effect waves-themed js-copy">
				<i class="fal fa-link mr-1"></i> Copy link
			</button>
			<?php } ?>
		</div>
	</div>
</div>

<style>
	.qrph-panel { border: 1px solid #e3e3e3; border-radius: 6px; background: #fff; overflow: hidden; }
	.qrph-head { padding: 12px; background: #f7f7f9; border-bottom: 1px solid #e9ecef; }
	.qrph-label { font-size: 11px; letter-spacing: 1px; color: #868e96; }
	.qrph-amount { font-size: 30px; font-weight: 700; color: #3276b1; line-height: 1.1; }
	.qrph-ref { font-size: 12px; color: #6c757d; }
	.qrph-body { padding: 14px; }
	.qrph-image-wrap { display: flex; align-items: center; justify-content: center; min-height: 200px; }
	.qrph-image { width: 100%; max-width: 260px; height: auto; image-rendering: pixelated; }
	.qrph-missing { color: #dc3545; font-size: 13px; }
	.qrph-hint { font-size: 13px; color: #495057; margin-top: 10px; }
	.qrph-countdown { font-size: 12px; margin-top: 6px; }
	.qrph-actions { margin-top: 14px; display: flex; flex-wrap: wrap; gap: 6px; justify-content: center; }
	.qrph-alert { margin-top: 10px; text-align: left; }
	.qrph-paid { text-align: center; padding: 18px 10px; }
	.qrph-paid .icon { font-size: 46px; color: #28a745; line-height: 1; }
	.qrph-paid .big { font-size: 20px; font-weight: 700; margin-top: 6px; }
	.qrph-paid .sub { color: #6c757d; font-size: 13px; margin-top: 4px; }
</style>

<script>
(function () {
	'use strict';

	var panel = document.getElementById('qrph-<?php echo $qr_uid; ?>');
	if (!panel) { return; }

	var statusUrl = panel.getAttribute('data-status-url');
	var receiptUrl = panel.getAttribute('data-receipt-url');
	var previewUrl = panel.getAttribute('data-preview-url');
	var expiresAt = panel.getAttribute('data-expires');
	var badge = panel.querySelector('.js-status-badge');
	var alertBox = panel.querySelector('.js-alert');
	var btnCheck = panel.querySelector('.js-check');
	var btnPrint = panel.querySelector('.js-print');
	var btnCopy = panel.querySelector('.js-copy');
	var countdown = panel.querySelector('.js-countdown');
	var pollTimer = null;
	var done = false;

	function showAlert(kind, text) {
		if (!alertBox) { return; }
		alertBox.style.display = 'block';
		alertBox.className = 'qrph-alert js-alert alert alert-' + kind;
		alertBox.innerHTML = text;
	}

	function markPaid(reference) {
		if (done) { return; }
		done = true;
		if (pollTimer) { clearInterval(pollTimer); }
		var body = panel.querySelector('.qrph-body');
		if (body) {
			body.innerHTML = ''
				+ '<div class="qrph-paid">'
				+ '<div class="icon"><i class="fal fa-check-circle"></i></div>'
				+ '<div class="big">Payment received</div>'
				+ '<div class="sub">Reference ' + (reference || '') + ' — the bill has been updated.</div>'
				+ '<div class="qrph-actions">'
				+ '<a class="btn btn-success btn-sm waves-effect waves-themed" target="_blank" href="' + receiptUrl + '">'
				+ '<i class="fal fa-print mr-1"></i> Print receipt</a>'
				+ '<a class="btn btn-outline-secondary btn-sm waves-effect waves-themed" target="_blank" href="' + previewUrl + '">'
				+ 'Preview</a>'
				+ '</div></div>';
		}
		if (badge) {
			badge.className = 'badge badge-success js-status-badge';
			badge.textContent = 'Paid';
		}
	}

	function applyStatus(json) {
		if (!json || !json.ok) {
			if (json && json.message) { showAlert('warning', json.message); }
			return;
		}
		if (badge && json.status_label) {
			badge.textContent = json.status_label;
		}
		if (json.paid) {
			markPaid(json.reference || '');
			return;
		}
		if (json.status === 'expired') {
			if (badge) { badge.className = 'badge badge-secondary js-status-badge'; }
			showAlert('danger', 'This QR code has expired. Create a new one to take payment.');
			if (pollTimer) { clearInterval(pollTimer); }
		} else if (json.status === 'failed' || json.status === 'cancelled') {
			if (badge) { badge.className = 'badge badge-dark js-status-badge'; }
			showAlert('danger', 'This payment is ' + json.status + '. Create a new one to take payment.');
			if (pollTimer) { clearInterval(pollTimer); }
		}
	}

	function check(manual) {
		if (done || !statusUrl) { return; }
		if (manual) {
			showAlert('info', 'Checking with PayMongo…');
		}
		fetch(statusUrl, {
			method: 'POST',
			headers: { 'X-Requested-With': 'XMLHttpRequest' },
			credentials: 'same-origin'
		})
		.then(function (r) { return r.json(); })
		.then(function (json) {
			if (alertBox && !json.paid && json.status !== 'expired' && json.status !== 'failed' && json.status !== 'cancelled') {
				alertBox.style.display = 'block';
				alertBox.className = 'qrph-alert js-alert alert alert-' + (manual ? 'info' : 'light');
				alertBox.textContent = (json.message ? json.message : '') + (manual ? '' : '');
			}
			applyStatus(json);
		})
		.catch(function () {
			if (manual) { showAlert('warning', 'The check could not be completed. Try again in a moment.'); }
		});
	}

	if (btnCheck) {
		btnCheck.addEventListener('click', function () { check(true); });
	}
	if (btnCopy && '<?php echo $qr_link !== '' ? '1' : '0'; ?>' === '1') {
		btnCopy.addEventListener('click', function () {
			var link = '<?php echo $qr_link !== '' ? addslashes($qr_link) : ''; ?>';
			try {
				if (navigator.clipboard) { navigator.clipboard.writeText(link); }
				btnCopy.innerHTML = '<i class="fal fa-check mr-1"></i> Copied';
			} catch (e) {
				window.prompt('Copy this link', link);
			}
		});
	}

	// Countdown to expiry.
	if (countdown && expiresAt) {
		var end = new Date(expiresAt.replace(' ', 'T')).getTime();
		var tick = function () {
			if (done) { return; }
			var left = end - Date.now();
			if (isNaN(left) || left <= 0) {
				countdown.textContent = 'Expired';
				return;
			}
			var mins = Math.floor(left / 60000);
			var secs = Math.floor((left % 60000) / 1000);
			countdown.textContent = 'Valid for ' + mins + 'm ' + (secs < 10 ? '0' : '') + secs + 's';
		};
		tick();
		setInterval(tick, 1000);
	}

	// Poll while pending, so a customer who has just scanned is picked up even
	// without a webhook (localhost cannot receive one).
	if (panel.getAttribute('data-status') === 'pending' && statusUrl) {
		pollTimer = setInterval(function () { check(false); }, 6000);
		check(false);
	}
})();
</script>
