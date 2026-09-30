<?php
/**
 * Online Payment (QR Ph) — desktop selection screen.
 *
 * Same workflow as the Cash Payment module: search a customer, tick the billing
 * periods to pay, then show the QR or email a payment link. Amounts come from
 * the server (onlinepayment_model::get_customer_bills), never from this page.
 *
 * Expects: $ready, $gateway_configured, $recent, $qr_attempt (optional), $qr_customer, $qr_rows
 */
$ready = !empty($ready);
$gateway_configured = !empty($gateway_configured);
$recent = isset($recent) && is_array($recent) ? $recent : array();
$customers = isset($customers) && is_array($customers) ? $customers : array();
$qr_attempt = isset($qr_attempt) && is_array($qr_attempt) ? $qr_attempt : array();
$qr_customer = isset($qr_customer) && is_array($qr_customer) ? $qr_customer : array();
$qr_rows = isset($qr_rows) && is_array($qr_rows) ? $qr_rows : array();
$flash = $this->session->flashdata('msg_succ');

$this->load->library('Paymongo');
$fee_cfg = $this->paymongo->fee_settings();

$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/select2/select2.bundle.css">
<style>
	.op-subheader { flex-wrap: wrap; }
	.op-subheader .subheader-title { flex: 1 1 260px; min-width: 0; }

	.op-cust-card { height: 100%; }
	.op-cust-card .op-cust-name { word-break: break-word; }

	.op-bills-table thead th { white-space: nowrap; vertical-align: middle; }
	.op-bills-table td { vertical-align: middle; }
	.op-bills-table td.text-right { white-space: nowrap; }
	.op-bills-table tfoot th { white-space: nowrap; }

	.op-actions { display: flex; flex-wrap: wrap; gap: .5rem; }
	.op-actions .btn { margin: 0 !important; }

	.op-recent-table td, .op-recent-table th { white-space: nowrap; vertical-align: middle; }

	/* The QR modal is rendered open without Bootstrap's body.modal-open, so allow it to scroll itself. */
	#op_qr_modal { overflow-x: hidden; overflow-y: auto; }

	/* Billing periods: 15 columns do not fit below lg, so each period becomes a card. */
	@media (max-width: 991.98px) {
		.op-bills-wrap { overflow-x: visible; }
		.table.op-bills-table { border: 0; background: transparent; }
		.op-bills-table, .op-bills-table tbody, .op-bills-table tfoot { display: block; width: 100%; }
		.op-bills-table thead { display: block; background: none !important; }
		.op-bills-table thead tr { display: flex; }
		.op-bills-table thead th { display: none; }
		.op-bills-table thead th.op-col-check { display: flex; align-items: center; width: 100% !important; border: 0; padding: 0 0 .5rem; color: #505050; }
		.op-bills-table thead th.op-col-check::after { content: 'Select all payable periods'; margin-left: .5rem; font-weight: 500; }

		.op-bills-table tbody tr { display: grid; grid-template-columns: 1fr 1fr; column-gap: 1.25rem; margin-bottom: .75rem; padding: .35rem .75rem; background: #fff; border: 1px solid rgba(0,0,0,.12); border-radius: 4px; }
		.table.op-bills-table tbody td { display: flex; justify-content: space-between; align-items: center; gap: .75rem; padding: .3rem 0; border: 0; border-bottom: 1px dashed rgba(0,0,0,.08); text-align: right; white-space: normal; }
		.op-bills-table tbody td::before { content: attr(data-label); font-weight: 600; color: #6c757d; text-align: left; white-space: nowrap; }
		.op-bills-table tbody td:not([data-label])::before { display: none; }
		.op-bills-table tbody td:not([data-label]) { grid-column: 1 / -1; justify-content: center; border-bottom: 0; }
		.op-bills-table tbody td.op-cell-check,
		.op-bills-table tbody td.op-cell-period,
		.op-bills-table tbody td.op-cell-status,
		.op-bills-table tbody td.op-cell-due { grid-column: 1 / -1; }
		.op-bills-table tbody td.op-cell-check { order: -3; justify-content: flex-start; border-bottom: 0; }
		.op-bills-table tbody td.op-cell-check::before { order: 2; }
		.op-bills-table tbody td.op-cell-period { order: -2; font-size: 1rem; font-weight: 700; }
		.op-bills-table tbody td.op-cell-status { order: -1; }
		.op-bills-table tbody td.op-cell-due { order: 99; border-bottom: 0; font-size: 1rem; }

		.op-bills-table tfoot tr { display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: .5rem .75rem; border: 1px solid rgba(0,0,0,.12); border-top: 0; }
		.op-bills-table tfoot tr:first-child { border-top: 1px solid rgba(0,0,0,.12); border-radius: 4px 4px 0 0; }
		.op-bills-table tfoot tr:last-child { border-radius: 0 0 4px 4px; }
		.table.op-bills-table tfoot th { display: block; border: 0; padding: 0; text-align: left !important; white-space: normal; }
		.op-bills-table tfoot th:empty { display: none; }
		.op-bills-table tfoot th[id] { text-align: right !important; white-space: nowrap; }
	}

	@media (max-width: 575.98px) {
		.op-bills-table tbody tr { grid-template-columns: 1fr; }
		.op-actions .btn { flex: 1 1 100%; }

		.op-recent-table thead { display: none; }
		.op-recent-table, .op-recent-table tbody, .op-recent-table tr, .op-recent-table td { display: block; width: 100%; }
		.op-recent-table tr { padding: .5rem 1rem; border-bottom: 1px solid rgba(0,0,0,.08); }
		.table.op-recent-table td { display: flex; justify-content: space-between; gap: .75rem; padding: .15rem 0; border: 0; text-align: right !important; white-space: normal; }
		.op-recent-table td::before { content: attr(data-label); font-weight: 600; color: #6c757d; text-align: left; }
	}
</style>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item active">Online Payment (QR Ph)</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader op-subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-qrcode"></i>
			Online Payment <span class="fw-300">QR Ph</span>
		</h1>
		<div class="subheader-block d-flex align-items-center">
			<a href="<?php echo ADMIN_URL; ?>onlinepayment/manage" class="btn btn-outline-primary waves-effect waves-themed">
				<i class="fal fa-list mr-1"></i> Payment History
			</a>
		</div>
	</div>

	<?php if ($flash) { ?>
	<div class="alert alert-info alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fal fa-times"></i></span></button>
		<?php echo $flash; ?>
	</div>
	<?php } ?>

	<?php if (!$ready) { ?>
	<div class="alert alert-warning" role="alert">
		<i class="fal fa-exclamation-triangle mr-1"></i>
		<strong>Not installed yet.</strong> Run <code>sql/add_online_payments.sql</code> to create the payment table.
	</div>
	<?php } elseif (!$gateway_configured) { ?>
	<div class="alert alert-warning" role="alert">
		<i class="fal fa-plug mr-1"></i>
		<strong>Online payments are not ready.</strong>
		An administrator must enable them and save a PayMongo secret key in
		<a href="<?php echo ADMIN_URL; ?>paymongo_setup">Settings → PayMongo Setup</a>.
		Cash payments are unaffected.
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-online-payment" class="panel mb-3">
				<div class="panel-hdr">
					<h2>Find customer <span class="fw-300"><i>and select the periods to pay</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<div class="row">
							<div class="col-12 col-md-8 col-xl-9">
								<label class="form-label" for="op_customer_pick">Search Customer</label>
								<select class="form-control" id="op_customer_pick" name="op_customer_pick"
									data-placeholder="Type to search customer ID or name...">
									<option value=""></option>
									<?php
									// Same suggested-search list the Cash Payment screen builds
									// (customer_model::get_all_records()), so the two match.
									foreach ((array) $customers as $value) {
										$cid = isset($value['customer_id']) ? $value['customer_id'] : '';
										$label = $cid . ' ==> '
											. (isset($value['last_name']) ? $value['last_name'] : '') . ', '
											. (isset($value['first_name']) ? $value['first_name'] : '') . ' '
											. (isset($value['middle_name']) ? $value['middle_name'] : '');
									?>
									<option value="<?php echo $h($cid); ?>"><?php echo $h($label); ?></option>
									<?php } ?>
								</select>
								<small class="form-text text-muted">Type a customer ID or name to filter the list.</small>
							</div>
							<div class="col-12 col-md-4 col-xl-3 mt-2 mt-md-0">
								<label class="form-label d-none d-md-block">&nbsp;</label>
								<button type="button" class="btn btn-secondary btn-block" id="op_reset">
									<i class="fal fa-undo mr-1"></i> Clear
								</button>
							</div>
						</div>
						<div id="op_results" class="mt-2"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row" id="op_bills_wrap" style="display:none;">
		<div class="col-xl-12">
			<div class="panel mb-3">
				<div class="panel-hdr">
					<h2>Billing periods <span class="fw-300"><i>tick what the customer is paying</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<div id="op_customer" class="mb-3"></div>
						<div class="table-responsive op-bills-wrap">
							<table class="table table-sm table-bordered table-hover mb-0 op-bills-table" id="op_bills_table">
								<thead class="bg-primary-600 bg-primary-gradient">
									<tr>
										<th class="op-col-check" style="width:34px;">
											<input type="checkbox" id="op_check_all" title="Select all payable">
										</th>
										<th>Billing period</th>
										<th>Due date</th>
										<th class="text-right">Prev</th>
										<th class="text-right">Last</th>
										<th class="text-right">Consumed</th>
										<th class="text-right">Bill amount</th>
										<th class="text-right">Discount</th>
										<th class="text-right">Penalty</th>
										<th class="text-right">WMMF</th>
										<th class="text-right">Franchise</th>
										<th>OR / Online ref</th>
										<th>Date paid</th>
										<th class="text-right">Amount due</th>
										<th style="width:80px;">Status</th>
									</tr>
								</thead>
								<tbody id="op_bills_body"></tbody>
								<tfoot>
									<tr class="bg-faded">
										<th colspan="13" class="text-right">Selected total</th>
										<th class="text-right" id="op_selected_total">0.00</th>
										<th></th>
									</tr>
									<?php if (!empty($fee_cfg['enabled'])) { ?>
									<tr class="bg-faded">
										<th colspan="13" class="text-right fw-400">
											Processing fee (<?php echo $h(rtrim(rtrim(number_format((float) $fee_cfg['percent'], 3, '.', ''), '0'), '.')); ?>% + &#8369; <?php echo number_format((float) $fee_cfg['fixed'], 2); ?>)
										</th>
										<th class="text-right fw-400" id="op_selected_fee">0.00</th>
										<th></th>
									</tr>
									<tr class="bg-faded">
										<th colspan="13" class="text-right">Customer pays (QR Ph)</th>
										<th class="text-right text-primary" id="op_selected_charged">0.00</th>
										<th></th>
									</tr>
									<?php } ?>
								</tfoot>
							</table>
						</div>
					</div>
					<div class="panel-content border-top">
						<div class="row align-items-end">
							<div class="col-lg-4 col-md-6 mb-2">
								<label class="form-label" for="op_email">Customer email (for the payment link)</label>
								<input type="email" class="form-control" id="op_email" placeholder="name@example.com">
							</div>
							<div class="col-lg-8 col-md-6 mb-2 op-actions">
								<button type="button" class="btn btn-primary waves-effect waves-themed mr-2 mb-1" id="op_generate_qr">
									<i class="fal fa-qrcode mr-1"></i> Show QR code
								</button>
								<button type="button" class="btn btn-outline-primary waves-effect waves-themed mr-2 mb-1" id="op_generate_link">
									<i class="fal fa-envelope mr-1"></i> Email payment link
								</button>
								<button type="button" class="btn btn-outline-secondary waves-effect waves-themed mb-1" id="op_generate_link_copy">
									<i class="fal fa-link mr-1"></i> Create link only
								</button>
							</div>
						</div>
						<div id="op_msg" class="mt-2"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if (empty($qr_attempt)) { ?>
	<div class="row">
		<div class="col-xl-12">
			<div class="panel mb-3">
				<div class="panel-hdr"><h2>Recent online payments</h2></div>
				<div class="panel-container show">
					<div class="panel-content p-0">
						<?php if (empty($recent)) { ?>
						<div class="p-3 text-muted">Nothing yet.</div>
						<?php } else { ?>
						<div class="table-responsive">
							<table class="table table-sm table-striped mb-0 op-recent-table">
								<thead>
									<tr>
										<th>Reference</th>
										<th>Customer</th>
										<th class="text-right">Amount</th>
										<th>Status</th>
										<th>Created</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($recent as $row) { ?>
									<tr>
										<td data-label="Reference"><code><?php echo $h($row['reference_no']); ?></code></td>
										<td data-label="Customer"><?php echo $h($row['customer_id']); ?></td>
										<td data-label="Amount" class="text-right"><?php echo number_format((float) $row['amount'], 2); ?></td>
										<td data-label="Status"><span class="badge badge-<?php echo $h($this->my_model->status_class($row['status'])); ?>"><?php echo $h($this->my_model->status_label($row['status'])); ?></span></td>
										<td data-label="Created"><?php echo $h($row['create_date_time']); ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php } ?>
					</div>
					<div class="panel-content border-top">
						<a href="<?php echo ADMIN_URL; ?>onlinepayment/manage" class="btn btn-link p-0">See all payments</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
</main>

<?php if (!empty($qr_attempt)) { ?>
<div class="modal fade show" id="op_qr_modal" tabindex="-1" role="dialog" style="display:block; background:rgba(0,0,0,.5);">
	<div class="modal-dialog modal-dialog-centered" role="document" style="max-width:420px;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<i class="fal fa-qrcode mr-1"></i>
					<?php echo $h(isset($qr_attempt['reference_no']) ? $qr_attempt['reference_no'] : ''); ?>
					<?php if (!empty($qr_customer)) { ?>
					<small class="text-muted d-block">
						<?php echo $h($this->my_model->full_name($qr_customer)); ?>
						(<?php echo $h($qr_attempt['customer_id']); ?>)
					</small>
					<?php } ?>
				</h5>
				<a href="<?php echo ADMIN_URL; ?>onlinepayment" class="close" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</a>
			</div>
			<div class="modal-body">
				<?php
				$this->load->view('partials/qr_panel', array(
					'qr_attempt' => $qr_attempt,
					'qr_status_url' => ADMIN_URL . 'onlinepayment/status/' . (int) $qr_attempt['id'],
					'qr_receipt_url' => ADMIN_URL . 'onlinepayment/receipt/' . (int) $qr_attempt['id'],
					'qr_print_url' => ADMIN_URL . 'onlinepayment/receipt/' . (int) $qr_attempt['id'] . '?preview=1',
					'qr_uid' => 'op' . (int) $qr_attempt['id'],
				));
				?>
				<?php if (!empty($qr_rows)) { ?>
				<div class="mt-3">
					<div class="fw-500 mb-1">Periods being paid</div>
					<ul class="mb-0 pl-3">
						<?php foreach ($qr_rows as $row) { ?>
						<li><?php echo $h(trim((isset($row['month_name']) ? $row['month_name'] : '') . ' ' . (isset($row['year']) ? $row['year'] : ''))); ?>
							— ₱ <?php echo number_format(isset($row['amount_due']) ? (float) $row['amount_due'] : 0, 2); ?></li>
						<?php } ?>
					</ul>
				</div>
				<?php } ?>
			</div>
			<div class="modal-footer">
				<a href="<?php echo ADMIN_URL; ?>onlinepayment" class="btn btn-secondary waves-effect waves-themed">Done</a>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<script type="text/javascript">
// jQuery (and the Select2 plugin) arrive with the shell's footer bundle, which is
// printed after this view — so wait for jQuery instead of assuming it, the same way
// statementofaccount_search.php does. Select2 is fetched only once jQuery exists.
(function () {
	'use strict';

	function boot() {
		if (typeof window.jQuery === 'undefined') {
			window.setTimeout(boot, 100);
			return;
		}
		var $ = window.jQuery;
		// $(document).ready so this also works if jQuery arrives before the DOM.
		var start = function () { $(function () { main($); }); };
		if ($.fn && $.fn.select2) { start(); return; }
		var s = document.createElement('script');
		s.src = '<?php echo base_url(); ?>sa4/js/formplugins/select2/select2.bundle.js';
		s.onload = start;
		s.onerror = start;   // a plain select still works without it
		document.body.appendChild(s);
	}

	function main($) {
	var customerId = '';
	var bills = [];

	function money(v) {
		var n = parseFloat(v || 0);
		return n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}

	var feeCfg = <?php echo json_encode(array(
		'enabled' => !empty($fee_cfg['enabled']),
		'percent' => (float) $fee_cfg['percent'],
		'fixed' => (float) $fee_cfg['fixed'],
	)); ?>;

	function esc(v) {
		return $('<div>').text(v === null || v === undefined ? '' : String(v)).html();
	}

	function message(kind, text) {
		$('#op_msg').html('<div class="alert alert-' + kind + ' mb-0">' + esc(text) + '</div>');
	}

	function clearMessage() {
		$('#op_msg').empty();
	}

	// ---- search -----------------------------------------------------------
	function runSearch() {
		var q = $.trim($('#op_search').val());
		if (q.length < 2) {
			$('#op_results').html('<div class="text-muted small">Type at least 2 characters.</div>');
			return;
		}
		$('#op_results').html('<div class="text-muted small"><i class="fal fa-spinner fa-spin mr-1"></i> Searching…</div>');
		$.ajax({
			type: 'GET',
			dataType: 'json',
			url: '<?php echo ADMIN_URL; ?>onlinepayment/search_customers',
			data: { q: q, page: 1 }
		}).done(function (json) {
			var rows = (json && json.results) ? json.results : [];
			if (!rows.length) {
				$('#op_results').html('<div class="text-muted small">No customer matched.</div>');
				return;
			}
			var html = '';
			rows.forEach(function (r) {
				html += '<button type="button" class="list-group-item list-group-item-action op_pick" data-id="' + esc(r.id) + '">'
					+ '<div class="d-flex w-100 justify-content-between">'
					+ '<span class="fw-500">' + esc(r.id) + ' — ' + esc(r.name) + '</span>'
					+ '<small class="text-muted">' + esc(r.meter_number ? ('Meter ' + r.meter_number) : '') + '</small>'
					+ '</div>'
					+ '<small class="text-muted">' + esc(r.address) + (r.zone ? (' · Zone ' + esc(r.zone)) : '') + '</small>'
					+ '</button>';
			});
			$('#op_results').html(html);
		}).fail(function () {
			$('#op_results').html('<div class="text-danger small">Search failed. Try again.</div>');
		});
	}

	// ---- suggested customer search ---------------------------------------
	// The free-text box + Search button is replaced by the same Select2 control the
	// Cash Payment screen uses, so typing filters the customer list as you go.
	$('#op_customer_pick').on('change', function () {
		// One path for picking, clearing and the Clear button: reset, then load.
		resetSelection();
		var id = $(this).val();
		if (id) { loadBills(id); }
	});

	function initCustomerPicker() {
		if (!$.fn || !$.fn.select2) { return; }   // plain select stays usable
		$('#op_customer_pick').select2({
			width: '100%',
			placeholder: $('#op_customer_pick').data('placeholder') || 'Type to search customer ID or name...',
			allowClear: true
		});
	}
	initCustomerPicker();

	function resetSelection() {
		$('#op_results').empty();
		$('#op_bills_wrap').hide();
		$('#op_bills_body').empty();
		$('#op_customer').empty();
		customerId = '';
		bills = [];
		clearMessage();
	}

	$('#op_reset').on('click', function () {
		// Clearing Select2 fires 'change', which resets the panels for us.
		$('#op_customer_pick').val(null).trigger('change');
		resetSelection();
	});

	// ---- load bills -------------------------------------------------------
	function loadBills(id) {
		$('#op_bills_wrap').hide();
		$('#op_results').html('<div class="text-muted small"><i class="fal fa-spinner fa-spin mr-1"></i> Loading billing periods…</div>');
		$.ajax({
			type: 'GET',
			dataType: 'json',
			url: '<?php echo ADMIN_URL; ?>onlinepayment/get_bills/' + encodeURIComponent(id)
		}).done(function (json) {
			$('#op_results').empty();
			if (!json || !json.ok) {
				$('#op_results').html('<div class="text-danger small">' + esc((json && json.message) || 'Unable to load this customer.') + '</div>');
				return;
			}
			customerId = json.customer.customer_id;
			bills = json.rows || [];
			renderCustomer(json.customer);
			renderBills(bills);
			$('#op_email').val(json.customer.email || '');
			$('#op_bills_wrap').show();
		}).fail(function () {
			$('#op_results').html('<div class="text-danger small">Unable to load this customer.</div>');
		});
	}

	function renderCustomer(c) {
		$('#op_customer').html(''
			+ '<div class="row">'
			+ '<div class="col-12 col-md-5 mb-2 mb-md-0"><div class="p-2 rounded border bg-faded op-cust-card">'
			+ '<div class="fw-500 op-cust-name">' + (c.name || '') + '</div>'
			+ '<div class="text-muted small">Customer ID: ' + (c.customer_id || '') + '</div>'
			+ '<div class="text-muted small">Meter: ' + (c.meter_number || '—') + '</div>'
			+ '</div></div>'
			+ '<div class="col-12 col-sm-7 col-md-4 mb-2 mb-md-0"><div class="p-2 rounded border bg-faded op-cust-card">'
			+ '<div class="text-muted small">Address</div><div>' + (c.address || '—') + '</div>'
			+ '<div class="text-muted small">Zone: ' + (c.zone || '—') + '</div>'
			+ '</div></div>'
			+ '<div class="col-12 col-sm-5 col-md-3"><div class="p-2 rounded border bg-faded text-right op-cust-card">'
			+ '<div class="text-muted small">Total payable</div>'
			+ '<div class="h4 mb-0 text-danger">&#8369; ' + money(c.balance) + '</div>'
			+ '</div></div>'
			+ '</div>');
	}

	function renderBills(rows) {
		var html = '';
		rows.forEach(function (r, idx) {
			var period = esc($.trim((r.month_name || '') + ' ' + (r.year || '')));
			var payable = !!r.is_payable;
			var statusBadge = r.is_paid
				? '<span class="badge badge-success">Paid</span>'
				: (r.has_reading ? '<span class="badge badge-danger">Unpaid</span>' : '<span class="badge badge-secondary">No reading</span>');
			html += '<tr>'
				+ '<td class="op-cell-check" data-label="' + (payable ? 'Pay this period' : 'Not payable') + '">' + (payable
					? '<input type="checkbox" class="op_check" data-idx="' + idx + '">'
					: '<input type="checkbox" disabled>') + '</td>'
				+ '<td class="op-cell-period" data-label="Billing period">' + period + '</td>'
				+ '<td data-label="Due date">' + esc(r.due_date ? r.due_date : '—') + '</td>'
				+ '<td class="text-right" data-label="Prev">' + esc(r.previous_reading) + '</td>'
				+ '<td class="text-right" data-label="Last">' + esc(r.reading) + '</td>'
				+ '<td class="text-right" data-label="Consumed">' + esc(r.consumed) + '</td>'
				+ '<td class="text-right" data-label="Bill amount">' + money(r.display_bill_amount) + '</td>'
				+ '<td class="text-right text-danger" data-label="Discount">' + money(r.discount) + '</td>'
				+ '<td class="text-right text-warning" data-label="Penalty">' + money(r.penalty) + '</td>'
				+ '<td class="text-right" data-label="WMMF">' + money(r.maintenance_fee) + '</td>'
				+ '<td class="text-right" data-label="Franchise">' + money(r.franchise_fee_amount) + '</td>'
				+ '<td data-label="OR / Online ref">' + esc(r.or_number || '—') + '</td>'
				+ '<td data-label="Date paid">' + esc(r.trans_date || '—') + '</td>'
				+ '<td class="text-right fw-700 op-cell-due ' + (r.is_paid ? 'text-success' : '') + '" data-label="Amount due">' + money(r.amount_due) + '</td>'
				+ '<td class="op-cell-status" data-label="Status">' + statusBadge + '</td>'
				+ '</tr>';
		});
		if (!html) {
			html = '<tr><td colspan="15" class="text-center text-muted">No billing periods found.</td></tr>';
		}
		$('#op_bills_body').html(html);
		recalc();
	}

	function selectedRows() {
		var out = [];
		$('.op_check:checked').each(function () {
			var idx = parseInt($(this).data('idx'), 10);
			if (bills[idx]) { out.push(bills[idx]); }
		});
		return out;
	}

	function recalc() {
		var total = 0;
		selectedRows().forEach(function (r) { total += parseFloat(r.amount_due || 0); });
		$('#op_selected_total').text(money(total));
		// Preview only: the server recomputes the fee when it creates the QR.
		var fee = 0;
		if (feeCfg.enabled && total > 0) {
			fee = Math.round((total * feeCfg.percent / 100 + feeCfg.fixed) * 100) / 100;
		}
		$('#op_selected_fee').text(money(fee));
		$('#op_selected_charged').text(money(total + fee));
	}

	$(document).on('change', '.op_check', recalc);
	$(document).on('change', '#op_check_all', function () {
		var on = $(this).is(':checked');
		$('.op_check').prop('checked', on);
		recalc();
	});

	// ---- create the QR / link --------------------------------------------
	function generate(mode) {
		var rows = selectedRows();
		if (!rows.length) {
			message('warning', 'Select at least one billing period first.');
			return;
		}
		var payload = rows.map(function (r) { return { month: r.month, year: r.year }; });
		clearMessage();
		message('info', 'Talking to PayMongo…');

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: '<?php echo ADMIN_URL; ?>onlinepayment/generate',
			data: {
				customer_id: customerId,
				rows: JSON.stringify(payload),
				mode: mode,
				email: $.trim($('#op_email').val())
			}
		}).done(function (json) {
			if (!json || !json.ok) {
				message('danger', (json && json.message) || 'Unable to create the QR code.');
				return;
			}
			if (mode === 'link') {
				// Stay on the page and show the result, including the link.
				message('success', json.message || 'Link created.');
				if (json.link) {
					$('#op_msg').append(
						'<div class="input-group mt-2">'
						+ '<input type="text" class="form-control" id="op_link_field" readonly value="' + esc(json.link) + '">'
						+ '<div class="input-group-append"><button class="btn btn-outline-secondary" type="button" id="op_copy_link"><i class="fal fa-copy"></i></button></div>'
						+ '</div>');
				}
				return;
			}
			// Showing the QR: reload so PHP renders the shared QR partial.
			window.location.href = json.show_url;
		}).fail(function () {
			message('danger', 'The request failed. Check the connection and try again.');
		});
	}

	$('#op_generate_qr').on('click', function () { generate('qr'); });
	$('#op_generate_link').on('click', function () { generate('link'); });
	$('#op_generate_link_copy').on('click', function () { generate('link'); });

	$(document).on('click', '#op_copy_link', function () {
		var field = document.getElementById('op_link_field');
		if (!field) { return; }
		field.select();
		try {
			if (navigator.clipboard) { navigator.clipboard.writeText(field.value); }
			else { document.execCommand('copy'); }
			$(this).html('<i class="fal fa-check"></i>');
		} catch (e) { window.prompt('Copy this link', field.value); }
	});
	} // main()

	boot();
})();
</script>

<?php include('footer.php'); ?>
