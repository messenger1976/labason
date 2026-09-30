<?php
/**
 * Online / QR Ph Payment report (search + filter page).
 *
 * Deliberately covers every attempt status, not only the paid ones: a pending row is an emailed
 * link the customer has not paid yet, and an expired row is one that lapsed — both are things the
 * cashier has to follow up.
 *
 * QRPH rows carry no OR and are excluded from the Daily Collection Report, so this report (plus
 * the payment list's Channel filter) is the only place their money is reported.
 */
$op_ajax_path = parse_url(site_url('master/reports/getonlinepaymentreportsearch'), PHP_URL_PATH);
if ($op_ajax_path === null || $op_ajax_path === '' || $op_ajax_path === '/') {
	$op_ajax_path = '/master/reports/getonlinepaymentreportsearch';
}
$op_export_path = parse_url(site_url('master/reports/exportonlinepaymentexcel'), PHP_URL_PATH);
if ($op_export_path === null || $op_export_path === '' || $op_export_path === '/') {
	$op_export_path = '/master/reports/exportonlinepaymentexcel';
}

$totals = (isset($totals) && is_array($totals)) ? $totals : array();
$paid_amount = isset($totals['paid_amount']) ? (float) $totals['paid_amount'] : 0;
$open_amount = isset($totals['open_amount']) ? (float) $totals['open_amount'] : 0;
$paid_count = isset($totals['paid_count']) ? (int) $totals['paid_count'] : 0;
$open_count = isset($totals['open_count']) ? (int) $totals['open_count'] : 0;
$attempt_count = isset($totals['count']) ? (int) $totals['count'] : 0;

$statuses = array(
	''          => 'All statuses',
	'pending'   => 'Pending',
	'paid'      => 'Paid',
	'expired'   => 'Expired',
	'failed'    => 'Failed',
	'cancelled' => 'Cancelled',
);
$contexts = array(
	''              => 'All sources',
	'mobile'        => 'Mobile app',
	'desktop'       => 'Desktop (emailed link)',
	'customer_link' => 'Customer link',
);
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<style>
	.op-subheader { flex-wrap: wrap; }
	.op-subheader .subheader-title { flex: 1 1 280px; min-width: 0; }
	.op-kpis { display: flex; flex-wrap: wrap; align-items: stretch; gap: .5rem 0; }
	.op-kpi { display: flex; flex-direction: column; justify-content: center; padding: 0 1rem; border-left: 1px solid rgba(0,0,0,.09); }
	.op-kpi:first-child { border-left: 0; padding-left: 0; }
	.op-kpi-label { font-size: .6875rem; font-weight: 300; text-transform: uppercase; letter-spacing: .04em; opacity: .6; }
	.op-kpi-value { font-size: 1.125rem; font-weight: 500; white-space: nowrap; }

	.op-actions { display: flex; flex-wrap: wrap; gap: .5rem; }

	.op-summary-card { background: #fff; border: 1px solid rgba(0,0,0,.09); border-radius: 4px; padding: .5rem .75rem; }
	.op-summary-label { font-size: .6875rem; text-transform: uppercase; letter-spacing: .04em; color: #6c757d; }
	.op-summary-value { font-size: 1.05rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
	.op-summary-note { font-size: .6875rem; color: #909090; }

	.op-table-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
	.op-table { margin-bottom: 0 !important; }
	.op-table th, .op-table td { vertical-align: middle; }
	.op-table thead th { white-space: nowrap; }
	.op-table .op-col-sn { width: 44px; white-space: nowrap; }
	.op-table .op-col-action { width: 64px; white-space: nowrap; }
	.op-table .op-col-name { min-width: 160px; }
	.op-table .op-col-break { word-break: break-all; min-width: 120px; }
	.op-table tfoot th { vertical-align: top; }
	/* Responsive child row: label/value pairs stay readable on phones. */
	.op-table li[data-dtr-index] { display: flex; justify-content: space-between; gap: .75rem; padding: .25rem 0; border-bottom: 1px dashed rgba(0,0,0,.08); }
	.op-table li[data-dtr-index]:last-child { border-bottom: 0; }
	.op-table .dtr-title { font-weight: 600; min-width: 110px; }
	.op-table .dtr-data { text-align: right; word-break: break-word; }
	#onlinepaymentDiv .dataTables_filter { text-align: left; }
	#onlinepaymentDiv .dataTables_filter label, #onlinepaymentDiv .dataTables_filter input { width: 100%; max-width: 360px; margin-left: 0; }

	@media (max-width: 575.98px) {
		.op-kpi { padding: 0 .75rem; }
		.op-kpi-value { font-size: 1rem; }
		.op-actions .btn { flex: 1 1 auto; }
	}

	@media print {
		.page-sidebar, .page-header, .page-footer, .page-breadcrumb, .panel-hdr, #op_form, .op-toolbar .btn-group,
		#onlinepaymentDiv .dataTables_filter, .op-intro { display: none !important; }
		.page-content, .panel, .panel-container, .panel-content { padding: 0 !important; margin: 0 !important; border: 0 !important; box-shadow: none !important; }
		/* Print every column, including the ones Responsive collapsed on screen. */
		.op-table th, .op-table td { display: table-cell !important; font-size: 9px; padding: 2px 3px !important; }
		.op-table tr.child { display: none !important; }
		.op-table td.dtr-control::before, .op-table th.dtr-control::before { display: none !important; }
		.op-table-wrap { overflow: visible; }
		.op-summary .col-xl-2 { flex: 0 0 16.666%; max-width: 16.666%; }
		@page { size: landscape; margin: 8mm; }
	}
</style>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>reports/online_payment_report">Online / QR Ph Payment</a></li>
		<li class="breadcrumb-item active">Search</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader op-subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-qrcode"></i>
			Manage <span class="fw-300">Online / QR Ph Payment Report</span>
		</h1>
		<div class="op-kpis">
			<div class="op-kpi">
				<span class="op-kpi-label">Collected</span>
				<span class="op-kpi-value color-success-500">₱ <?php echo number_format($paid_amount, 2); ?></span>
			</div>
			<div class="op-kpi">
				<span class="op-kpi-label">Outstanding</span>
				<span class="op-kpi-value color-danger-500">₱ <?php echo number_format($open_amount, 2); ?></span>
			</div>
			<div class="op-kpi">
				<span class="op-kpi-label">Paid / Open</span>
				<span class="op-kpi-value color-primary-500"><?php echo $paid_count; ?> / <?php echo $open_count; ?></span>
			</div>
		</div>
	</div>

	<?php if (empty($table_ready)) { ?>
	<div class="alert alert-warning">
		<strong>Not installed yet.</strong>
		Run <code>sql/add_online_payments.sql</code> on this database, then reload this page.
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-online-payment" class="panel">
				<div class="panel-hdr">
					<h2>Online / QR Ph Payment <span class="fw-300"><i>Search</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<p class="text-muted mb-3 op-intro">
							Every QR Ph attempt, including the ones that were never paid. Amounts here are the
							<strong>QR Ph channel only</strong> — these payments carry no OR, so they are excluded from the
							Daily Collection Report. Loaded newest first, 100 rows per page; totals below are for the whole
							filtered set. The processing fee is split into the <strong>QR Ph fee</strong> (percentage of the
							bill) and the <strong>Fixed fee</strong> (flat amount); together they make the Total fee.
						</p>

						<form name="op_form" id="op_form" method="post" action="javascript:void(0);">
							<div class="row">
								<div class="col-12 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="form-label" for="date_from">Date from</label>
										<input type="date" class="form-control" name="date_from" id="date_from" value="<?php echo date('Y-m-d', strtotime('-30 days')); ?>">
									</div>
								</div>
								<div class="col-12 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="form-label" for="date_to">Date to</label>
										<input type="date" class="form-control" name="date_to" id="date_to" value="<?php echo date('Y-m-d'); ?>">
									</div>
								</div>
								<div class="col-12 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="form-label" for="status">Status</label>
										<select class="form-control" name="status" id="status">
											<?php foreach ($statuses as $value => $label) { ?>
											<option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-12 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="form-label" for="context">Source</label>
										<select class="form-control" name="context" id="context">
											<?php foreach ($contexts as $value => $label) { ?>
											<option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-12 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="form-label" for="zone">Zone</label>
										<select class="form-control" name="zone" id="zone">
											<option value="0">--All--</option>
											<?php foreach ((array) $zone as $value) { ?>
											<option value="<?php echo (int) $value['id']; ?>"><?php echo htmlspecialchars($value['zone'], ENT_QUOTES, 'UTF-8'); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-12 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="form-label" for="created_by">Collected by</label>
										<select class="form-control" name="created_by" id="created_by">
											<option value="0">--All--</option>
											<?php foreach ((array) $users as $value) {
												$label = !empty($value['employee_name']) ? $value['employee_name'] : (isset($value['username']) ? $value['username'] : '');
											?>
											<option value="<?php echo (int) $value['id']; ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-12 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="form-label" for="q">Search</label>
										<input type="text" class="form-control" name="q" id="q" placeholder="Reference, customer ID, pay_…" autocomplete="off">
									</div>
								</div>
							</div>

							<input type="hidden" id="list_offset" value="0">

							<div class="op-actions">
								<button type="button" class="btn btn-primary" id="search">
									<i class="fal fa-search mr-1"></i> Search
								</button>
								<button type="button" class="btn btn-secondary" id="btn_print">
									<i class="fal fa-print mr-1"></i> Print
								</button>
								<button type="button" class="btn btn-success" id="exporttoexcel">
									<i class="fal fa-file-excel mr-1"></i> Export to Excel
								</button>
							</div>
						</form>

						<div id="onlinepaymentDiv" class="mt-3"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/datagrid/datatables/datatables.bundle.js"></script>
</body>
</html>
<script type="text/javascript">
(function() {
	var ajaxUrl = window.location.origin + <?php echo json_encode($op_ajax_path); ?>;
	var exportUrl = window.location.origin + <?php echo json_encode($op_export_path); ?>;

	function currentFilters() {
		return {
			date_from: $('#date_from').val() || '',
			date_to: $('#date_to').val() || '',
			status: $('#status').val() || '',
			context: $('#context').val() || '',
			zone: $('#zone').val() || 0,
			created_by: $('#created_by').val() || 0,
			q: $.trim($('#q').val() || '')
		};
	}

	function destroyDt($ctx) {
		var $t = ($ctx && $ctx.length) ? $ctx.find('#tbl_online_payment') : $('#tbl_online_payment');
		if (!$t.length) { return; }
		try {
			var el = $t[0];
			var inited = ($.fn.DataTable && $.fn.DataTable.isDataTable(el))
				|| ($.fn.dataTable && $.fn.dataTable.isDataTable && $.fn.dataTable.isDataTable(el));
			if (inited && typeof $t.DataTable === 'function') {
				$t.DataTable().destroy();
			}
		} catch (ignore) {}
	}

	function initOpTable() {
		var $tbl = $('#tbl_online_payment');
		if (!$tbl.length || typeof $.fn.DataTable !== 'function') { return; }
		try {
			$tbl.DataTable({
				responsive: true,
				columnDefs: [
					{ orderable: false, targets: -1 }
				],
				paging: false,
				searching: true,
				info: false,
				order: [],
				autoWidth: false,
				dom: "<'row mb-2'<'col-12'f>>" + "<'row'<'col-12'tr>>",
				language: { search: '', searchPlaceholder: 'Search these results…' }
			});
		} catch (dtErr) {
			if (window.console && console.warn) { console.warn('DataTables init skipped:', dtErr); }
		}
		if ($.fn.tooltip) {
			$('#onlinepaymentDiv [data-toggle="tooltip"]').tooltip();
		}
	}

	function recalcOpTable() {
		var $tbl = $('#tbl_online_payment');
		if (!$tbl.length || !$.fn.DataTable || !$.fn.DataTable.isDataTable($tbl[0])) { return; }
		try {
			var dt = $tbl.DataTable();
			dt.columns.adjust();
			if (dt.responsive) { dt.responsive.recalc(); }
		} catch (ignore) {}
	}

	function loadPage(goOffset) {
		var offset = (typeof goOffset === 'number') ? goOffset : 0;
		if (offset < 0) { offset = 0; }
		$('#list_offset').val(offset);

		var data = currentFilters();
		data.offset = offset;
		data._ = (new Date()).getTime();

		destroyDt($('#onlinepaymentDiv'));
		$('#onlinepaymentDiv').html('<div class="text-center py-4 text-muted"><i class="fal fa-spinner fa-spin fa-2x mb-2"></i><div>Loading results…</div></div>');

		$.ajax({
			type: 'GET',
			url: ajaxUrl,
			data: data,
			dataType: 'text',
			cache: false,
			timeout: 300000,
			success: function(html) {
				var op = (html && typeof html === 'string') ? html.trim() : '';
				destroyDt($('#onlinepaymentDiv'));
				$('#onlinepaymentDiv').html(op || '<div class="alert alert-warning">No data.</div>');
				initOpTable();
			},
			error: function(xhr, textStatus, errorThrown) {
				var st = xhr && typeof xhr.status !== 'undefined' ? xhr.status : '(no status)';
				var msg = 'HTTP ' + st + ' — ';
				msg += (errorThrown && String(errorThrown)) || (textStatus && String(textStatus)) || 'request failed';
				if (String(st) === '0' || String(st) === '(no status)') {
					msg += '<br><small class="text-muted">Often caused by opening the site under a different host (www vs non-www), mixed http/https, a browser extension, or a firewall blocking the request.</small>';
				}
				$('#onlinepaymentDiv').html('<div class="alert alert-danger">Error loading report.<br>' + msg + '</div>');
			}
		});
	}

	$(document).ready(function() {
		if (typeof pageSetUp === 'function') { pageSetUp(); }

		// Panel fullscreen/collapse and the sidebar toggle change the table width without a window resize.
		$(document).on('click', '#panel-online-payment [data-action], [data-action="toggle"]', function() {
			setTimeout(recalcOpTable, 350);
		});

		$('#search').on('click', function(e) { e.preventDefault(); loadPage(0); });

		$('#exporttoexcel').on('click', function(e) {
			e.preventDefault();
			window.location.href = exportUrl + '?' + $.param(currentFilters());
		});

		$('#btn_print').on('click', function(e) {
			e.preventDefault();
			window.print();
		});

		$(document).on('click', '#op_prev', function(e) {
			e.preventDefault();
			if ($(this).prop('disabled')) { return; }
			var off = parseInt($('#list_offset').val(), 10) || 0;
			loadPage(Math.max(0, off - 100));
		});

		$(document).on('click', '#op_next', function(e) {
			e.preventDefault();
			if ($(this).prop('disabled')) { return; }
			var off = parseInt($('#list_offset').val(), 10) || 0;
			loadPage(off + 100);
		});

		// First load.
		loadPage(0);
	});
})();
</script>
