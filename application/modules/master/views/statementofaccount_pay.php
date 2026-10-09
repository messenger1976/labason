<?php
/**
 * Statement of Account — Online Pay (customer self-service QR Ph).
 *
 * Rendered inside the customer app shell (customer_app_header.php /
 * customer_app_footer.php); the shell prints the logo header and navigation.
 *
 * Layout follows mobile_payment.php (period cards, sticky total bar, QR overlay)
 * without the customer search: the page always shows the signed-in customer.
 * Amounts come from statementofaccount/pay_bills (onlinepayment_model); the
 * script only adds up the figures the server returned.
 *
 * Expects: $customer_id, $ready, $gateway_configured, $qr_attempt, $qr_rows
 */
$customer_id = isset($customer_id) ? (string) $customer_id : '';
$ready = !empty($ready);
$gateway_configured = !empty($gateway_configured);
$qr_attempt = isset($qr_attempt) && is_array($qr_attempt) ? $qr_attempt : array();
$qr_rows = isset($qr_rows) && is_array($qr_rows) ? $qr_rows : array();
$can_pay = $ready && $gateway_configured;

$this->load->library('Paymongo');
$fee_cfg = $this->paymongo->fee_settings();

$base = base_url() . 'master/statementofaccount/';
$soa_url = $base . 'soa/' . rawurlencode($customer_id);
$pay_url = $base . 'pay/' . rawurlencode($customer_id);

$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
?>
<style>
	.soa-pay-wrap {
		padding: 0 0 calc(96px + env(safe-area-inset-bottom, 0px)) 0;
		max-width: 720px;
		margin: 0 auto;
		font-size: 14px;
		-webkit-text-size-adjust: 100%;
	}
	.soa-pay-top { display: flex; justify-content: space-between; align-items: center; gap: 8px; margin-bottom: 10px; }
	.soa-pay-top a { font-size: 13px; }
	.soa-pay-brand { text-align: center; margin-bottom: 10px; }
	.soa-pay-brand img { max-width: 100%; height: auto; max-height: 80px; }
	.soa-pay-brand .t { font-weight: 700; font-size: 16px; color: #24405c; margin-top: 6px; letter-spacing: .5px; }
	.soa-pay-card {
		background: #fff;
		border: 1px solid #dfe7ee;
		border-radius: .75rem;
		box-shadow: 0 1px 2px rgba(6, 40, 66, .05), 0 2px 8px rgba(6, 40, 66, .05);
		margin-bottom: 12px;
		overflow: hidden;
	}
	.soa-pay-pad { padding: 12px; }
	.soa-pay-title {
		display: flex; align-items: center; gap: 8px;
		font-weight: 700; color: #24405c; font-size: 15px; margin-bottom: 8px;
	}
	.soa-pay-title i { color: #0a6ba3; }

	.soa-pay-cust { display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
	.soa-pay-cust .name { font-weight: 700; color: #24405c; font-size: 15px; }
	.soa-pay-cust .meta { color: #6c757d; font-size: 12px; margin-top: 3px; }
	.soa-pay-cust .due { text-align: right; }
	.soa-pay-cust .due .lbl { font-size: 11px; color: #6c757d; letter-spacing: .5px; }
	.soa-pay-cust .due .val { font-size: 20px; font-weight: 700; color: #c0392b; }
	.soa-pay-cust .due .val.ok { color: #1f8b4c; }
	.soa-pay-cust .due .sub { font-size: 11px; color: #6c757d; margin-top: 2px; }

	.soa-pay-period {
		display: flex; align-items: flex-start; gap: 10px;
		padding: 12px; border-bottom: 1px solid #eef1f5;
	}
	.soa-pay-period:last-child { border-bottom: 0; }
	.soa-pay-period.is-paid { background: #f6fbf7; }
	.soa-pay-period.is-locked { opacity: .72; }
	.soa-pay-period input[type="checkbox"] { width: 22px; height: 22px; margin: 2px 0 0 0; flex: 0 0 auto; }
	.soa-pay-period .p-body { flex: 1; min-width: 0; }
	.soa-pay-period .p-top { display: flex; justify-content: space-between; gap: 8px; }
	.soa-pay-period .p-period { font-weight: 700; color: #24405c; }
	.soa-pay-period .p-amount { font-weight: 700; white-space: nowrap; }
	.soa-pay-period .p-sub { color: #6c757d; font-size: 12px; margin-top: 3px; line-height: 1.5; }
	.soa-pay-badge {
		display: inline-block; padding: 2px 8px; border-radius: 999px;
		font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px;
	}
	.soa-pay-badge.unpaid { background: #fdecea; color: #c0392b; }
	.soa-pay-badge.paid { background: #e6f6ea; color: #1f8b4c; }
	.soa-pay-badge.noreading { background: #eef1f5; color: #6c757d; }

	.soa-pay-bar {
		position: fixed; left: 0; right: 0; bottom: 0; z-index: 40;
		background: #fff; border-top: 1px solid #e3e9f0;
		padding: 10px 12px calc(10px + env(safe-area-inset-bottom, 0px)) 12px;
		display: flex; align-items: center; gap: 10px;
		box-shadow: 0 -3px 12px rgba(0, 0, 0, .08);
	}
	.soa-pay-bar .total { flex: 1; min-width: 0; }
	.soa-pay-bar .total .lbl { font-size: 11px; color: #6c757d; letter-spacing: .5px; }
	.soa-pay-bar .total .val { font-size: 20px; font-weight: 700; color: #1f8b4c; }
	.soa-pay-bar .total .fee { font-size: 11px; color: #6c757d; }
	.soa-pay-bar button.pay {
		height: 46px; padding: 0 18px; border: 0; border-radius: 12px;
		background: #1f8b4c; color: #fff; font-weight: 700; font-size: 15px; white-space: nowrap;
	}
	.soa-pay-bar button.pay:disabled { opacity: .55; }

	.soa-pay-alert { padding: 10px 12px; border-radius: 10px; font-size: 13px; margin-bottom: 10px; }
	.soa-pay-alert.danger { background: #fdecea; color: #a5281b; }
	.soa-pay-alert.warning { background: #fff8e1; color: #8a6d1f; }
	.soa-pay-alert.info { background: #eaf3fb; color: #24557f; }
	.soa-pay-empty { color: #6c757d; font-size: 13px; text-align: center; padding: 12px; }
	.soa-pay-progress { display: flex; align-items: center; gap: 8px; color: #24557f; font-size: 13px; padding: 12px; }
	.soa-pay-spin {
		width: 16px; height: 16px; border: 2px solid #cfe0f0; border-top-color: #3276b1;
		border-radius: 50%; animation: soa-pay-rot .8s linear infinite;
	}
	@keyframes soa-pay-rot { to { transform: rotate(360deg); } }

	.soa-pay-overlay {
		position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 60; background: rgba(20, 30, 45, .62);
		display: flex; align-items: flex-start; justify-content: center;
		padding: 12px; overflow-y: auto;
	}
	.soa-pay-overlay .sheet { width: 100%; max-width: 460px; background: #fff; border-radius: 16px; overflow: hidden; margin: auto; }
	.soa-pay-overlay .sheet-hd {
		display: flex; align-items: center; justify-content: space-between;
		padding: 12px 14px; border-bottom: 1px solid #eef1f5;
	}
	.soa-pay-overlay .sheet-hd .t { font-weight: 700; color: #24405c; }
	.soa-pay-overlay .sheet-hd a { color: #6c757d; font-size: 22px; line-height: 1; text-decoration: none; }
	.soa-pay-overlay .sheet-bd { padding: 12px; }
	.soa-pay-link {
		display: block; text-align: center; padding: 11px 12px; border-radius: 10px;
		background: #eef1f5; color: #24405c; font-weight: 600; margin-top: 8px; text-decoration: none;
	}
	.soa-pay-link.primary { background: #1f8b4c; color: #fff; }
</style>

<div class="soa-pay-wrap" id="soa_pay_root"
	data-bills-url="<?php echo $h($base . 'pay_bills/' . rawurlencode($customer_id)); ?>"
	data-create-url="<?php echo $h($base . 'pay_create_qr'); ?>">

	<?php if (!$can_pay) { ?>
	<div class="soa-pay-alert warning">
		<strong>Online payment is not available right now.</strong>
		Please pay at the water district office. Your billing periods are shown below.
	</div>
	<?php } ?>

	<div class="soa-pay-card">
		<div class="soa-pay-pad soa-pay-cust" id="soa_pay_customer">
			<div class="soa-pay-progress"><span class="soa-pay-spin"></span> Loading your account…</div>
		</div>
	</div>

	<div class="soa-pay-card" id="soa_pay_periods_card" style="display:none;">
		<div class="soa-pay-pad" style="padding-bottom:6px;">
			<div class="soa-pay-title"><i class="fa fa-list-alt"></i> Billing periods</div>
			<label style="display:flex;align-items:center;gap:9px;font-size:13px;color:#24405c;font-weight:400;">
				<input type="checkbox" id="soa_pay_all" style="width:22px;height:22px;margin:0;" <?php echo $can_pay ? '' : 'disabled'; ?>>
				<span>Select all unpaid periods</span>
			</label>
		</div>
		<div id="soa_pay_periods"></div>
	</div>

	<div id="soa_pay_msg"></div>

	<div class="soa-pay-alert info">
		Pay using any bank or e-wallet app that supports <strong>QR Ph</strong>. The bill is updated as soon as the payment is confirmed.
	</div>

	<a href="<?php echo $h($soa_url); ?>" class="soa-pay-link"><i class="fa fa-file-text-o"></i> View full Statement of Account</a>
</div>

<div class="soa-pay-bar" id="soa_pay_bar" style="display:none;">
	<div class="total">
		<div class="lbl">SELECTED TOTAL</div>
		<div class="val" id="soa_pay_total">&#8369; 0.00</div>
		<div class="fee" id="soa_pay_fee" style="display:none;"></div>
	</div>
	<button type="button" class="pay" id="soa_pay_btn" disabled>
		<i class="fa fa-qrcode"></i> Pay with QR Ph
	</button>
</div>

<?php if (!empty($qr_attempt)) { ?>
<div class="soa-pay-overlay" id="soa_pay_overlay">
	<div class="sheet">
		<div class="sheet-hd">
			<div class="t">QR Ph payment</div>
			<a href="<?php echo $h($pay_url); ?>" title="Close">&times;</a>
		</div>
		<div class="sheet-bd">
			<?php
			$this->load->view('partials/qr_panel', array(
				'qr_attempt' => $qr_attempt,
				'qr_status_url' => $base . 'pay_status/' . (int) $qr_attempt['id'],
				'qr_receipt_url' => $base . 'pay_receipt/' . (int) $qr_attempt['id'],
				'qr_print_url' => $base . 'pay_receipt/' . (int) $qr_attempt['id'] . '?preview=1',
				'qr_uid' => 'soa' . (int) $qr_attempt['id'],
			));
			?>

			<?php if (!empty($qr_rows)) { ?>
			<div style="margin-top:12px;">
				<div style="font-weight:700;color:#24405c;font-size:13px;margin-bottom:6px;">Periods being paid</div>
				<?php foreach ($qr_rows as $row) { ?>
				<div style="display:flex;justify-content:space-between;font-size:13px;padding:4px 0;border-bottom:1px dashed #eef1f5;">
					<span><?php echo $h(trim((isset($row['month_name']) ? $row['month_name'] : '') . ' ' . (isset($row['year']) ? $row['year'] : ''))); ?></span>
					<span>&#8369; <?php echo number_format(isset($row['amount_due']) ? (float) $row['amount_due'] : 0, 2); ?></span>
				</div>
				<?php } ?>
			</div>
			<?php } ?>

			<div style="margin-top:12px;">
				<?php if (isset($qr_attempt['status']) && $qr_attempt['status'] === 'paid') { ?>
				<a href="<?php echo $h($base . 'pay_success/' . (int) $qr_attempt['id']); ?>" class="soa-pay-link primary">
					View payment &amp; print receipt
				</a>
				<?php } ?>
				<a href="<?php echo $h($pay_url); ?>" class="soa-pay-link">Close</a>
			</div>
		</div>
	</div>
</div>
<script>
	(function () {
		'use strict';
		var successUrl = '<?php echo $base; ?>pay_success/<?php echo (int) $qr_attempt['id']; ?>';
		var statusUrl = '<?php echo $base; ?>pay_status/<?php echo (int) $qr_attempt['id']; ?>';
		var timer = setInterval(function () {
			fetch(statusUrl, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (json) {
					if (json && json.paid) {
						clearInterval(timer);
						window.location.href = successUrl;
					}
				})
				.catch(function () {});
		}, 7000);
	})();
</script>
<?php } ?>

<script>
(function () {
	'use strict';

	var root = document.getElementById('soa_pay_root');
	if (!root) { return; }

	var BILLS_URL = root.getAttribute('data-bills-url');
	var CREATE_URL = root.getAttribute('data-create-url');
	var CAN_PAY = <?php echo $can_pay ? 'true' : 'false'; ?>;

	var el = {
		cust: document.getElementById('soa_pay_customer'),
		periodsCard: document.getElementById('soa_pay_periods_card'),
		periods: document.getElementById('soa_pay_periods'),
		all: document.getElementById('soa_pay_all'),
		msg: document.getElementById('soa_pay_msg'),
		bar: document.getElementById('soa_pay_bar'),
		total: document.getElementById('soa_pay_total'),
		fee: document.getElementById('soa_pay_fee'),
		pay: document.getElementById('soa_pay_btn')
	};
	// Preview only: the server recomputes the fee when it creates the QR.
	var feeCfg = <?php echo json_encode(array(
		'enabled' => !empty($fee_cfg['enabled']),
		'percent' => (float) $fee_cfg['percent'],
		'fixed' => (float) $fee_cfg['fixed'],
	)); ?>;

	var bills = [];

	function peso(v) {
		var n = parseFloat(v || 0);
		return '\u20B1 ' + n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}

	function text(v) {
		if (v === null || v === undefined) { return ''; }
		return String(v).replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}

	function note(kind, html) {
		el.msg.innerHTML = '<div class="soa-pay-alert ' + kind + '">' + html + '</div>';
	}

	function clearNote() {
		el.msg.innerHTML = '';
	}

	function load() {
		fetch(BILLS_URL, {
			headers: { 'X-Requested-With': 'XMLHttpRequest' },
			credentials: 'same-origin'
		})
		.then(function (r) {
			if (r.status === 403) {
				window.location.href = '<?php echo base_url(); ?>master/statementofaccount/search';
				return null;
			}
			return r.json();
		})
		.then(function (json) {
			if (json === null) { return; }
			if (!json || !json.ok) {
				el.cust.innerHTML = '';
				note('danger', text((json && json.message) || 'Unable to load your account.'));
				return;
			}
			bills = json.rows || [];
			renderCustomer(json.customer, json.total_due);
			renderPeriods(bills);
			el.periodsCard.style.display = '';
			el.bar.style.display = (CAN_PAY && bills.length) ? '' : 'none';
			recalc();
		})
		.catch(function () {
			el.cust.innerHTML = '';
			note('danger', 'Unable to load your account. Check your connection and reload the page.');
		});
	}

	function renderCustomer(c, totalDue) {
		var due = parseFloat(totalDue || 0);
		el.cust.innerHTML = ''
			+ '<div style="min-width:0;">'
			+ '<div class="name">' + text(c.name) + '</div>'
			+ '<div class="meta">Customer ID: ' + text(c.customer_id) + '</div>'
			+ '<div class="meta">Meter: ' + (c.meter_number ? text(c.meter_number) : '—') + '</div>'
			+ (c.zone ? '<div class="meta">Zone: ' + text(c.zone) + '</div>' : '')
			+ (c.address ? '<div class="meta">' + text(c.address) + '</div>' : '')
			+ '</div>'
			+ '<div class="due">'
			+ '<div class="lbl">AMOUNT DUE</div>'
			+ '<div class="val' + (due > 0.009 ? '' : ' ok') + '">' + peso(due) + '</div>'
			+ (c.latest_period ? '<div class="sub">Latest bill: ' + text(c.latest_period) + '</div>' : '')
			+ (c.latest_due_date ? '<div class="sub">Due ' + text(c.latest_due_date) + '</div>' : '')
			+ '</div>';
	}

	function renderPeriods(rows) {
		var html = '';
		rows.forEach(function (r, idx) {
			var period = (text(r.month_name) + ' ' + text(r.year)).trim();
			var badge = r.is_paid
				? '<span class="soa-pay-badge paid">Paid</span>'
				: (r.has_reading ? '<span class="soa-pay-badge unpaid">Unpaid</span>' : '<span class="soa-pay-badge noreading">No reading</span>');

			var cls = 'soa-pay-period';
			if (r.is_paid) { cls += ' is-paid'; }
			if (!r.is_payable) { cls += ' is-locked'; }

			html += '<div class="' + cls + '">'
				+ ((r.is_payable && CAN_PAY)
					? '<input type="checkbox" class="js-check" data-idx="' + idx + '">'
					: '<input type="checkbox" disabled>')
				+ '<div class="p-body">'
				+ '<div class="p-top">'
				+ '<span class="p-period">' + period + '</span>'
				+ '<span class="p-amount">' + peso(r.amount_due) + '</span>'
				+ '</div>'
				+ '<div class="p-sub">'
				+ badge
				+ (r.due_date ? ' &nbsp;Due ' + text(r.due_date) : '')
				+ '<br>Consumed ' + text(r.consumed) + ' cu.m'
				+ (parseFloat(r.penalty || 0) >= 0.005 ? ' &middot; includes penalty ' + peso(r.penalty) : '')
				+ (parseFloat(r.discount || 0) >= 0.005 ? ' &middot; discount ' + peso(r.discount) : '')
				+ (r.is_paid && r.or_number ? '<br>OR ' + text(r.or_number) : '')
				+ (r.is_paid && r.trans_date ? ' &middot; ' + text(r.trans_date) : '')
				+ '</div>'
				+ '</div>'
				+ '</div>';
		});
		if (!html) {
			html = '<div class="soa-pay-empty">No billing periods found for this account.</div>';
		}
		el.periods.innerHTML = html;
		el.all.checked = false;
	}

	function checked() {
		var out = [];
		var boxes = el.periods.querySelectorAll('.js-check:checked');
		for (var i = 0; i < boxes.length; i++) {
			var idx = parseInt(boxes[i].getAttribute('data-idx'), 10);
			if (bills[idx]) { out.push(bills[idx]); }
		}
		return out;
	}

	function recalc() {
		var total = 0;
		checked().forEach(function (r) { total += parseFloat(r.amount_due || 0); });
		var fee = 0;
		if (feeCfg.enabled && total > 0) {
			fee = Math.round((total * feeCfg.percent / 100 + feeCfg.fixed) * 100) / 100;
		}
		el.total.textContent = peso(total + fee);
		el.fee.style.display = fee > 0 ? 'block' : 'none';
		el.fee.textContent = fee > 0 ? ('Bill ' + peso(total) + ' + processing fee ' + peso(fee)) : '';
		el.pay.disabled = !(CAN_PAY && total > 0);
	}

	el.periods.addEventListener('change', function (e) {
		if (e.target && e.target.classList && e.target.classList.contains('js-check')) { recalc(); }
	});

	el.all.addEventListener('change', function () {
		var on = el.all.checked;
		var boxes = el.periods.querySelectorAll('.js-check');
		for (var i = 0; i < boxes.length; i++) { boxes[i].checked = on; }
		recalc();
	});

	el.pay.addEventListener('click', function () {
		var rows = checked();
		if (!rows.length) {
			note('warning', 'Tick at least one billing period.');
			return;
		}
		var payload = rows.map(function (r) { return { month: r.month, year: r.year }; });

		el.pay.disabled = true;
		el.pay.innerHTML = 'Generating…';
		clearNote();

		var body = new URLSearchParams();
		body.append('rows', JSON.stringify(payload));

		fetch(CREATE_URL, {
			method: 'POST',
			headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			credentials: 'same-origin',
			body: body.toString()
		})
		.then(function (r) { return r.json(); })
		.then(function (json) {
			el.pay.disabled = false;
			el.pay.innerHTML = '<i class="fa fa-qrcode"></i> Pay with QR Ph';
			if (!json || !json.ok) {
				note('danger', text((json && json.message) || 'Unable to create the QR code.'));
				return;
			}
			window.location.href = json.show_url;
		})
		.catch(function () {
			el.pay.disabled = false;
			el.pay.innerHTML = '<i class="fa fa-qrcode"></i> Pay with QR Ph';
			note('danger', 'The request failed. Check your connection and try again.');
		});
	});

	load();
})();
</script>
