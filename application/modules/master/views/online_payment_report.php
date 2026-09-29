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
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>reports/online_payment_report">Online / QR Ph Payment</a></li>
		<li class="breadcrumb-item active">Search</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-qrcode"></i>
			Manage <span class="fw-300">Online / QR Ph Payment Report</span>
		</h1>
		<div class="subheader-block d-lg-flex align-items-center">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>COLLECTED</small></span>
				<span class="fw-500 fs-xl d-block color-success-500">₱ <?php echo number_format($paid_amount, 2); ?></span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#1dc9b7" sparkHeight="32px" sparkBarWidth="5px" values="2,5,3,7,4,6,3,8,5,4,6"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>OUTSTANDING</small></span>
				<span class="fw-500 fs-xl d-block color-danger-500">₱ <?php echo number_format($open_amount, 2); ?></span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#fe6bb0" sparkHeight="32px" sparkBarWidth="5px" values="1,4,3,6,5,3,9,6,5,9,7"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>PAID / OPEN</small></span>
				<span class="fw-500 fs-xl d-block color-primary-500"><?php echo $paid_count; ?> / <?php echo $open_count; ?></span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#886ab5" sparkHeight="32px" sparkBarWidth="5px" values="3,4,3,6,7,3,3,6,2,6,4"></span>
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
						<p class="text-muted mb-3">
							Every QR Ph attempt, including the ones that were never paid. Amounts here are the
							<strong>QR Ph channel only</strong> — these payments carry no OR, so they are excluded from the
							Daily Collection Report. Loaded newest first, 100 rows per page; totals below are for the whole
							filtered set.
						</p>

						<form name="op_form" id="op_form" method="post" action="javascript:void(0);">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label" for="date_from">Date from</label>
										<input type="date" class="form-control" name="date_from" id="date_from" value="<?php echo date('Y-m-d', strtotime('-30 days')); ?>">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label" for="date_to">Date to</label>
										<input type="date" class="form-control" name="date_to" id="date_to" value="<?php echo date('Y-m-d'); ?>">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label" for="status">Status</label>
										<select class="form-control" name="status" id="status">
											<?php foreach ($statuses as $value => $label) { ?>
											<option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label" for="context">Source</label>
										<select class="form-control" name="context" id="context">
											<?php foreach ($contexts as $value => $label) { ?>
											<option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-3">
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
								<div class="col-md-3">
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
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label" for="q">Search</label>
										<input type="text" class="form-control" name="q" id="q" placeholder="Reference, customer ID, pay_…" autocomplete="off">
									</div>
								</div>
							</div>

							<input type="hidden" id="list_offset" value="0">

							<div class="form-group mb-0">
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
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
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
				paging: false,
				searching: true,
				info: false,
				order: [],
				autoWidth: true,
				dom: "<'row mb-2'<'col-sm-12'f>>" + "<'row'<'col-sm-12'tr>>",
				language: { search: '', searchPlaceholder: 'Search these results…' }
			});
		} catch (dtErr) {
			if (window.console && console.warn) { console.warn('DataTables init skipped:', dtErr); }
		}
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
		if ($.fn.sparkline) {
			$('.sparklines').each(function() {
				var $el = $(this);
				$el.sparkline('html', {
					type: $el.attr('sparkType') || 'bar',
					barColor: $el.attr('sparkBarColor') || '#886ab5',
					height: $el.attr('sparkHeight') || '32px',
					barWidth: $el.attr('sparkBarWidth') || '5px'
				});
			});
		}

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
