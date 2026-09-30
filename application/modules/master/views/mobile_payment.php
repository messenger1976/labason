<?php
/**
 * Mobile Payment (QR Ph) — mobile-first.
 *
 * Rendered inside the mobile shell (mobile_header + mobile_navigation), so it
 * uses the same card language as mobile_statementofaccount: thumb-friendly rows,
 * no horizontal scroll, and a sticky action bar.
 *
 * Written in plain JavaScript on purpose so the screen still works if a plugin
 * fails to load; the shared QR partial is vanilla too. The shell's own JS
 * (jQuery + app.min.js, which drives the hamburger menu, logout and fullscreen)
 * is loaded by mobile_footer.php, included at the end of this view.
 *
 * Expects: $ready, $gateway_configured, $qr_attempt, $qr_customer, $qr_rows
 */
$ready = !empty($ready);
$gateway_configured = !empty($gateway_configured);
$qr_attempt = isset($qr_attempt) && is_array($qr_attempt) ? $qr_attempt : array();
$qr_customer = isset($qr_customer) && is_array($qr_customer) ? $qr_customer : array();
$qr_rows = isset($qr_rows) && is_array($qr_rows) ? $qr_rows : array();

$this->load->library('Paymongo');
$fee_cfg = $this->paymongo->fee_settings();

$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
?>
<style>
	/* ------------------------------------------------ Mobile Payment (QR Ph)
	   Scoped styles, prefix .pay-m- . Card layout, thumb-sized targets.
	   ---------------------------------------------------------------- */
	:root { --pay-m-top: 49px; }

	.pay-m-wrap {
		padding: 8px 8px calc(96px + env(safe-area-inset-bottom, 0px)) 8px;
		max-width: 720px;
		margin: 0 auto;
		font-size: 14px;
		-webkit-text-size-adjust: 100%;
	}
	.pay-m-card {
		background: #fff;
		border-radius: 14px;
		box-shadow: 0 2px 6px rgba(0, 0, 0, .12);
		margin-bottom: 10px;
		overflow: hidden;
	}
	.pay-m-pad { padding: 12px; }
	.pay-m-title {
		display: flex; align-items: center; gap: 8px;
		font-weight: 700; color: #24405c; font-size: 15px; margin-bottom: 8px;
	}
	.pay-m-title i { color: #3276b1; }

	.pay-m-search-row { display: flex; gap: 8px; }
	.pay-m-search-row input {
		flex: 1; min-width: 0; height: 44px; padding: 0 12px;
		border: 1px solid #cfd8e3; border-radius: 10px; font-size: 15px;
	}
	.pay-m-search-row button {
		height: 44px; padding: 0 16px; border: 0; border-radius: 10px;
		background: #3276b1; color: #fff; font-weight: 600; font-size: 14px;
	}
	.pay-m-search-row button:disabled { opacity: .6; }
	.pay-m-hint { color: #6c757d; font-size: 12px; margin-top: 6px; }

	.pay-m-results { margin-top: 8px; }
	.pay-m-result {
		display: block; width: 100%; text-align: left;
		background: #f7f9fc; border: 1px solid #e3e9f0; border-radius: 10px;
		padding: 10px 12px; margin-bottom: 6px; font-size: 13px;
	}
	.pay-m-result .r1 { font-weight: 700; color: #24405c; }
	.pay-m-result .r2 { color: #6c757d; font-size: 12px; margin-top: 2px; }

	.pay-m-cust { display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
	.pay-m-cust .name { font-weight: 700; color: #24405c; font-size: 15px; }
	.pay-m-cust .meta { color: #6c757d; font-size: 12px; margin-top: 3px; }
	.pay-m-cust .due { text-align: right; }
	.pay-m-cust .due .lbl { font-size: 11px; color: #6c757d; letter-spacing: .5px; }
	.pay-m-cust .due .val { font-size: 20px; font-weight: 700; color: #c0392b; }

	/* ---- period rows ---- */
	.pay-m-period {
		display: flex; align-items: flex-start; gap: 10px;
		padding: 12px; border-bottom: 1px solid #eef1f5;
	}
	.pay-m-period:last-child { border-bottom: 0; }
	.pay-m-period.is-paid { background: #f6fbf7; }
	.pay-m-period.is-locked { opacity: .72; }
	.pay-m-period input[type="checkbox"] {
		width: 22px; height: 22px; margin: 2px 0 0 0; flex: 0 0 auto;
	}
	.pay-m-period .p-body { flex: 1; min-width: 0; }
	.pay-m-period .p-top { display: flex; justify-content: space-between; gap: 8px; }
	.pay-m-period .p-period { font-weight: 700; color: #24405c; }
	.pay-m-period .p-amount { font-weight: 700; white-space: nowrap; }
	.pay-m-period .p-sub { color: #6c757d; font-size: 12px; margin-top: 3px; line-height: 1.5; }
	.pay-m-badge {
		display: inline-block; padding: 2px 8px; border-radius: 999px;
		font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px;
	}
	.pay-m-badge.unpaid { background: #fdecea; color: #c0392b; }
	.pay-m-badge.paid { background: #e6f6ea; color: #1f8b4c; }
	.pay-m-badge.noreading { background: #eef1f5; color: #6c757d; }

	/* ---- sticky action bar ---- */
	.pay-m-bar {
		position: fixed; left: 0; right: 0; bottom: 0; z-index: 40;
		background: #fff; border-top: 1px solid #e3e9f0;
		padding: 10px 12px calc(10px + env(safe-area-inset-bottom, 0px)) 12px;
		display: flex; align-items: center; gap: 10px;
		box-shadow: 0 -3px 12px rgba(0, 0, 0, .08);
	}
	.pay-m-bar .total { flex: 1; min-width: 0; }
	.pay-m-bar .total .lbl { font-size: 11px; color: #6c757d; letter-spacing: .5px; }
	.pay-m-bar .total .val { font-size: 20px; font-weight: 700; color: #1f8b4c; }
	.pay-m-bar .total .fee { font-size: 11px; color: #6c757d; }
	.pay-m-bar button.pay {
		height: 46px; padding: 0 18px; border: 0; border-radius: 12px;
		background: #1f8b4c; color: #fff; font-weight: 700; font-size: 15px; white-space: nowrap;
	}
	.pay-m-bar button.pay:disabled { opacity: .55; }

	.pay-m-alert { padding: 10px 12px; border-radius: 10px; font-size: 13px; margin-bottom: 10px; }
	.pay-m-alert.danger { background: #fdecea; color: #a5281b; }
	.pay-m-alert.warning { background: #fff8e1; color: #8a6d1f; }
	.pay-m-alert.info { background: #eaf3fb; color: #24557f; }
	.pay-m-alert.success { background: #e6f6ea; color: #1f8b4c; }

	.pay-m-empty { color: #6c757d; font-size: 13px; text-align: center; padding: 12px; }

	/* QR overlay */
	.pay-m-overlay {
		position: fixed; inset: 0; z-index: 60; background: rgba(20, 30, 45, .62);
		display: flex; align-items: flex-start; justify-content: center;
		padding: 12px; overflow-y: auto;
	}
	.pay-m-overlay .sheet {
		width: 100%; max-width: 460px; background: #fff; border-radius: 16px;
		overflow: hidden; margin: auto;
	}
	.pay-m-overlay .sheet-hd {
		display: flex; align-items: center; justify-content: space-between;
		padding: 12px 14px; border-bottom: 1px solid #eef1f5;
	}
	.pay-m-overlay .sheet-hd .t { font-weight: 700; color: #24405c; }
	.pay-m-overlay .sheet-hd a { color: #6c757d; font-size: 22px; line-height: 1; text-decoration: none; }
	.pay-m-overlay .sheet-bd { padding: 12px; }

	.pay-m-progress { display: flex; align-items: center; gap: 8px; color: #24557f; font-size: 13px; }
	.pay-m-spin {
		width: 16px; height: 16px; border: 2px solid #cfe0f0; border-top-color: #3276b1;
		border-radius: 50%; animation: pay-m-rot .8s linear infinite;
	}
	@keyframes pay-m-rot { to { transform: rotate(360deg); } }
</style>

<!-- MAIN PANEL -->
<div id="main" role="main" style="margin-left:0px;">
	<!-- MAIN CONTENT -->
	<div id="content">

<div class="pay-m-wrap" id="paym_root"
	data-search-url="<?php echo ADMIN_URL; ?>mobile_payment/search_customers"
	data-bills-url="<?php echo ADMIN_URL; ?>mobile_payment/get_bills/"
	data-create-url="<?php echo ADMIN_URL; ?>mobile_payment/create_qr">

	<?php if (!$ready) { ?>
	<div class="pay-m-alert warning">
		<strong>Not installed.</strong> Ask the administrator to run <code>sql/add_online_payments.sql</code>.
	</div>
	<?php } elseif (!$gateway_configured) { ?>
	<div class="pay-m-alert warning">
		<strong>Online payment is not ready.</strong> An administrator must enable it and save a PayMongo key in
		Settings &rarr; PayMongo Setup. Cash payment is unaffected.
	</div>
	<?php } ?>

	<div class="pay-m-card">
		<div class="pay-m-pad">
			<div class="pay-m-title"><i class="fa fa-search"></i> Find customer</div>
			<div class="pay-m-search-row">
				<input type="search" id="paym_q" inputmode="search" autocomplete="off"
					placeholder="Name, customer ID or meter no.">
				<button type="button" id="paym_search">Search</button>
			</div>
			<div class="pay-m-hint">You can also scan the customer's bill or type the meter number.</div>
			<div class="pay-m-results" id="paym_results"></div>
		</div>
	</div>

	<div id="paym_customer_card" style="display:none;">
		<div class="pay-m-card">
			<div class="pay-m-pad pay-m-cust" id="paym_customer"></div>
		</div>
	</div>

	<div id="paym_periods_card" style="display:none;">
		<div class="pay-m-card">
			<div class="pay-m-pad" style="padding-bottom:6px;">
				<div class="pay-m-title"><i class="fa fa-list-alt"></i> Billing periods</div>
				<label style="display:flex;align-items:center;gap:9px;font-size:13px;color:#24405c;">
					<input type="checkbox" id="paym_all" style="width:22px;height:22px;">
					<span>Select all unpaid periods</span>
				</label>
			</div>
			<div id="paym_periods"></div>
		</div>
	</div>

	<div id="paym_msg"></div>
</div><!-- /.pay-m-wrap -->

	</div>
	<!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->

<div class="pay-m-bar" id="paym_bar" style="display:none;">
	<div class="total">
		<div class="lbl">SELECTED TOTAL</div>
		<div class="val" id="paym_total">&#8369; 0.00</div>
		<div class="fee" id="paym_fee" style="display:none;"></div>
	</div>
	<button type="button" class="pay" id="paym_pay" disabled>
		<i class="fa fa-qrcode"></i> Pay with QR Ph
	</button>
</div>

<?php if (!empty($qr_attempt)) { ?>
<div class="pay-m-overlay" id="paym_overlay">
	<div class="sheet">
		<div class="sheet-hd">
			<div class="t">
				QR Ph payment
				<?php if (!empty($qr_customer)) { ?>
				<small style="display:block;font-weight:400;color:#6c757d;font-size:12px;">
					<?php echo $h($this->my_model->full_name($qr_customer)); ?>
					&middot; <?php echo $h($qr_attempt['customer_id']); ?>
				</small>
				<?php } ?>
			</div>
			<a href="<?php echo ADMIN_URL; ?>mobile_payment" title="Close">&times;</a>
		</div>
		<div class="sheet-bd">
			<?php
			$this->load->view('partials/qr_panel', array(
				'qr_attempt' => $qr_attempt,
				'qr_status_url' => ADMIN_URL . 'mobile_payment/status/' . (int) $qr_attempt['id'],
				'qr_receipt_url' => ADMIN_URL . 'mobile_payment/receipt/' . (int) $qr_attempt['id'],
				'qr_print_url' => ADMIN_URL . 'mobile_payment/receipt/' . (int) $qr_attempt['id'] . '?preview=1',
				'qr_uid' => 'mp' . (int) $qr_attempt['id'],
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
				<a href="<?php echo ADMIN_URL; ?>mobile_payment" class="pay-m-result" style="text-align:center;background:#eef1f5;">
					Close and take another payment
				</a>
				<?php if (isset($qr_attempt['status']) && $qr_attempt['status'] === 'paid') { ?>
				<a href="<?php echo ADMIN_URL; ?>mobile_payment/success/<?php echo (int) $qr_attempt['id']; ?>" class="pay-m-result"
					style="text-align:center;background:#1f8b4c;color:#fff;border-color:#1f8b4c;">
					View payment &amp; print receipt
				</a>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
	// When the QR panel reports success inside the overlay, send the collector to
	// the confirmation page so they can print the 80mm receipt.
	// NB: keep the attempt id *inside* the quotes. `'…/success/' . 5` is PHP
	// concatenation and is a JavaScript syntax error ("Unexpected number").
	(function () {
		'use strict';
		var successUrl = '<?php echo ADMIN_URL; ?>mobile_payment/success/<?php echo (int) $qr_attempt['id']; ?>';
		var statusUrl = '<?php echo ADMIN_URL; ?>mobile_payment/status/<?php echo (int) $qr_attempt['id']; ?>';
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

	var root = document.getElementById('paym_root');
	if (!root) { return; }

	var SEARCH_URL = root.getAttribute('data-search-url');
	var BILLS_URL = root.getAttribute('data-bills-url');
	var CREATE_URL = root.getAttribute('data-create-url');

	var el = {
		q: document.getElementById('paym_q'),
		search: document.getElementById('paym_search'),
		results: document.getElementById('paym_results'),
		custCard: document.getElementById('paym_customer_card'),
		cust: document.getElementById('paym_customer'),
		periodsCard: document.getElementById('paym_periods_card'),
		periods: document.getElementById('paym_periods'),
		all: document.getElementById('paym_all'),
		msg: document.getElementById('paym_msg'),
		bar: document.getElementById('paym_bar'),
		total: document.getElementById('paym_total'),
		fee: document.getElementById('paym_fee'),
		pay: document.getElementById('paym_pay')
	};
	// Preview only: the server recomputes the fee when it creates the QR.
	var feeCfg = <?php echo json_encode(array(
		'enabled' => !empty($fee_cfg['enabled']),
		'percent' => (float) $fee_cfg['percent'],
		'fixed' => (float) $fee_cfg['fixed'],
	)); ?>;

	var customerId = '';
	var bills = [];

	function peso(v) {
		var n = parseFloat(v || 0);
		return '\u20B1 ' + n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}

	function text(v) {
		return v === null || v === undefined ? '' : String(v);
	}

	function note(kind, html) {
		el.msg.innerHTML = '<div class="pay-m-alert ' + kind + '">' + html + '</div>';
	}

	function clearNote() {
		el.msg.innerHTML = '';
	}

	// ---- search -----------------------------------------------------------
	function search() {
		var q = (el.q.value || '').trim();
		if (q.length < 2) {
			el.results.innerHTML = '<div class="pay-m-hint">Type at least 2 characters.</div>';
			return;
		}
		el.results.innerHTML = '<div class="pay-m-progress"><span class="pay-m-spin"></span> Searching…</div>';

		fetch(SEARCH_URL + '?q=' + encodeURIComponent(q), {
			headers: { 'X-Requested-With': 'XMLHttpRequest' },
			credentials: 'same-origin'
		})
		.then(function (r) { return r.json(); })
		.then(function (json) {
			var rows = (json && json.results) ? json.results : [];
			if (!rows.length) {
				el.results.innerHTML = '<div class="pay-m-hint">No customer matched.</div>';
				return;
			}
			var html = '';
			rows.forEach(function (r) {
				html += '<button type="button" class="pay-m-result js-pick" data-id="' + text(r.id) + '">'
					+ '<div class="r1">' + text(r.name) + '</div>'
					+ '<div class="r2">' + text(r.id)
					+ (r.meter_number ? ' &middot; Meter ' + text(r.meter_number) : '')
					+ '</div>'
					+ (r.address ? '<div class="r2">' + text(r.address) + (r.zone ? ' &middot; ' + text(r.zone) : '') + '</div>' : '')
					+ '</button>';
			});
			el.results.innerHTML = html;
		})
		.catch(function () {
			el.results.innerHTML = '<div class="pay-m-alert danger">Search failed. Check the signal and try again.</div>';
		});
	}

	el.search.addEventListener('click', search);
	el.q.addEventListener('keydown', function (e) {
		if (e.key === 'Enter') { e.preventDefault(); search(); }
	});

	// ---- load bills -------------------------------------------------------
	document.addEventListener('click', function (e) {
		var btn = e.target.closest ? e.target.closest('.js-pick') : null;
		if (!btn) { return; }
		loadBills(btn.getAttribute('data-id'));
	});

	function loadBills(id) {
		clearNote();
		el.results.innerHTML = '<div class="pay-m-progress"><span class="pay-m-spin"></span> Loading billing periods…</div>';
		el.custCard.style.display = 'none';
		el.periodsCard.style.display = 'none';
		el.bar.style.display = 'none';

		fetch(BILLS_URL + encodeURIComponent(id), {
			headers: { 'X-Requested-With': 'XMLHttpRequest' },
			credentials: 'same-origin'
		})
		.then(function (r) { return r.json(); })
		.then(function (json) {
			if (!json || !json.ok) {
				el.results.innerHTML = '';
				note('danger', text((json && json.message) || 'Unable to load this customer.'));
				return;
			}
			customerId = json.customer.customer_id;
			bills = json.rows || [];
			el.results.innerHTML = '';
			renderCustomer(json.customer);
			renderPeriods(bills);
			el.custCard.style.display = '';
			el.periodsCard.style.display = '';
			el.bar.style.display = bills.length ? '' : 'none';
			recalc();
		})
		.catch(function () {
			el.results.innerHTML = '';
			note('danger', 'Unable to load this customer.');
		});
	}

	function renderCustomer(c) {
		el.cust.innerHTML = ''
			+ '<div style="min-width:0;">'
			+ '<div class="name">' + text(c.name) + '</div>'
			+ '<div class="meta">Customer ID: ' + text(c.customer_id) + '</div>'
			+ '<div class="meta">Meter: ' + (c.meter_number || '—') + '</div>'
			+ (c.zone ? '<div class="meta">Zone: ' + text(c.zone) + '</div>' : '')
			+ (c.address ? '<div class="meta">' + text(c.address) + '</div>' : '')
			+ '</div>'
			+ '<div class="due">'
			+ '<div class="lbl">TOTAL DUE</div>'
			+ '<div class="val">' + peso(c.balance) + '</div>'
			+ '</div>';
	}

	function renderPeriods(rows) {
		var html = '';
		rows.forEach(function (r, idx) {
			var period = (text(r.month_name) + ' ' + text(r.year)).trim();
			var badge = r.is_paid
				? '<span class="pay-m-badge paid">Paid</span>'
				: (r.has_reading ? '<span class="pay-m-badge unpaid">Unpaid</span>' : '<span class="pay-m-badge noreading">No reading</span>');

			var cls = 'pay-m-period';
			if (r.is_paid) { cls += ' is-paid'; }
			if (!r.is_payable) { cls += ' is-locked'; }

			html += '<div class="' + cls + '">'
				+ (r.is_payable
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
				+ (parseFloat(r.penalty || 0) > 0 ? ' &middot; includes penalty ' + peso(r.penalty) : '')
				+ (parseFloat(r.discount || 0) > 0 ? ' &middot; discount ' + peso(r.discount) : '')
				+ (r.is_paid && r.or_number ? '<br>OR ' + text(r.or_number) : '')
				+ (r.is_paid && r.trans_date ? ' &middot; ' + text(r.trans_date) : '')
				+ '</div>'
				+ '</div>'
				+ '</div>';
		});
		if (!html) {
			html = '<div class="pay-m-empty">No billing periods found for this customer.</div>';
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
		if (el.fee) {
			el.fee.style.display = fee > 0 ? 'block' : 'none';
			el.fee.textContent = fee > 0 ? ('Bill ' + peso(total) + ' + fee ' + peso(fee)) : '';
		}
		el.pay.disabled = !(total > 0);
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

	// ---- create the QR ---------------------------------------------------
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
		body.append('customer_id', customerId);
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
			note('danger', 'The request failed. Check the signal and try again.');
		});
	});
})();
</script>

<?php include('mobile_footer.php'); ?>

</body>
</html>
