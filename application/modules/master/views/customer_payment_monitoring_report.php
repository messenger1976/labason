<?php
	$income1 = $this->my_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->my_model->get_income_monthlycustomer();
	extract($income2);
	$intotal = (isset($total1) ? (float) $total1 : 0) + (isset($total2) ? (float) $total2 : 0);

	$expense1 = $this->my_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->my_model->get_outcome_payroll();
	extract($expense2);
	$extotal = (isset($extotal1) ? (float) $extotal1 : 0) + (isset($extotal2) ? (float) $extotal2 : 0);

	$total_customer = $this->my_model->total_customer();
	extract($total_customer);

	$cpm_ajax_path = parse_url(site_url('master/reports/getcpmonsearch'), PHP_URL_PATH);
	if ($cpm_ajax_path === null || $cpm_ajax_path === '' || $cpm_ajax_path === '/') {
		$cpm_ajax_path = '/master/reports/getcpmonsearch';
	}
	$cpm_export_path = parse_url(site_url('master/reports/export_cpmon_excel'), PHP_URL_PATH);
	if ($cpm_export_path === null || $cpm_export_path === '' || $cpm_export_path === '/') {
		$cpm_export_path = '/master/reports/export_cpmon_excel';
	}
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>reports/customer_payment_monitoring_report">Customer Payment Monitoring</a></li>
		<li class="breadcrumb-item active">Search</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-wallet"></i>
			Manage <span class="fw-300">Customer Payment Monitoring Report</span>
		</h1>
		<div class="subheader-block d-lg-flex align-items-center">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>INCOME</small>
				</span>
				<span class="fw-500 fs-xl d-block color-primary-500">
					₱ <?php echo number_format($intotal, 2); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#886ab5" sparkHeight="32px" sparkBarWidth="5px" values="3,4,3,6,7,3,3,6,2,6,4"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>EXPENSE</small>
				</span>
				<span class="fw-500 fs-xl d-block color-danger-500">
					₱ <?php echo number_format($extotal, 2); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#fe6bb0" sparkHeight="32px" sparkBarWidth="5px" values="1,4,3,6,5,3,9,6,5,9,7"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>TOTAL CUSTOMER</small>
				</span>
				<span class="fw-500 fs-xl d-block color-success-500">
					<?php echo (int) (isset($count_id) ? $count_id : 0); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#1dc9b7" sparkHeight="32px" sparkBarWidth="5px" values="2,5,3,7,4,6,3,8,5,4,6"></span>
		</div>
	</div>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-cpm" class="panel">
				<div class="panel-hdr">
					<h2>Customer Payment Monitoring <span class="fw-300"><i>Search</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<p class="text-muted mb-3">
							Multi-period payments (max 100 rows per request). Only payments <strong>posted in the calendar month immediately before</strong> each customer&rsquo;s zone current billing period are included (when that zone has an active billing period).
						</p>

						<form name="cpm_form" id="cpm_form" method="post" action="javascript:void(0);">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="status">Status</label>
										<select class="form-control" name="status" id="status">
											<option value="">--All--</option>
											<option value="1">Active</option>
											<option value="0">Inactive</option>
											<option value="2">Disconnected</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="zone">Zone</label>
										<select class="form-control" name="zone" id="zone">
											<option value="0">--All--</option>
											<?php foreach ($zone as $key => $value) { ?>
											<option value="<?php echo (int) $value['id']; ?>"><?php echo htmlspecialchars($value['zone'], ENT_QUOTES, 'UTF-8'); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>

							<input type="hidden" id="list_offset" value="0">

							<div class="form-group mb-0">
								<button type="button" class="btn btn-primary" id="search">
									<i class="fal fa-search mr-1"></i> Search
								</button>
								<button type="button" class="btn btn-success" id="exporttoexcel">
									<i class="fal fa-file-excel mr-1"></i> Export to Excel
								</button>
							</div>
						</form>

						<div id="paidcustomerDiv" class="mt-3"></div>
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
	var ajaxUrl = window.location.origin + <?php echo json_encode($cpm_ajax_path); ?>;
	var exportBase = window.location.origin + <?php echo json_encode(rtrim($cpm_export_path, '/')); ?>;

	function destroyDt($ctx) {
		var $t = ($ctx && $ctx.length) ? $ctx.find('#tbl_payment_monitor') : $('#tbl_payment_monitor');
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

	function initCpmTable() {
		var $tbl = $('#tbl_payment_monitor');
		if (!$tbl.length || typeof $.fn.DataTable !== 'function') { return; }
		try {
			$tbl.DataTable({
				paging: false,
				searching: true,
				info: false,
				order: [],
				autoWidth: true,
				dom: "<'row mb-2'<'col-sm-12'f>>" +
					"<'row'<'col-sm-12'tr>>",
				language: {
					search: '',
					searchPlaceholder: 'Search payment monitoring...'
				}
			});
		} catch (dtErr) {
			if (window.console && console.warn) {
				console.warn('DataTables init skipped:', dtErr);
			}
		}
	}

	function loadPage(goOffset) {
		var zone = $('#zone').val();
		var status = $('#status').val();
		var offset = (typeof goOffset === 'number') ? goOffset : parseInt($('#list_offset').val(), 10) || 0;
		if (offset < 0) { offset = 0; }
		$('#list_offset').val(offset);
		destroyDt($('#paidcustomerDiv'));
		$('#paidcustomerDiv').html('<div class="text-center py-4 text-muted"><i class="fal fa-spinner fa-spin fa-2x mb-2"></i><div>Loading results…</div></div>');

		$.ajax({
			type: 'GET',
			url: ajaxUrl,
			data: { zone: zone, status: status, offset: offset, _: (new Date()).getTime() },
			dataType: 'text',
			cache: false,
			timeout: 300000,
			success: function(html) {
				var op = (html && typeof html === 'string') ? html.trim() : '';
				destroyDt($('#paidcustomerDiv'));
				$('#paidcustomerDiv').html(op || '<div class="alert alert-warning">No data.</div>');
				initCpmTable();
			},
			error: function(xhr, textStatus, errorThrown) {
				var st = xhr && typeof xhr.status !== 'undefined' ? xhr.status : '(no status)';
				var msg = 'HTTP ' + st + ' — ';
				msg += (errorThrown && String(errorThrown)) || (textStatus && String(textStatus)) || 'request failed';
				msg += '<br><small>Request URL: <code>' + $('<div/>').text(ajaxUrl).html() + '</code></small>';
				if (String(st) === '0' || String(st) === '(no status)') {
					msg += '<br><small class="text-muted">Often caused by opening the site under a different host (www vs non-www), mixed http/https, a browser extension, or a firewall blocking the request.</small>';
				}
				if (xhr && xhr.responseText && xhr.responseText.length > 0) {
					var rt = xhr.responseText.length > 4000 ? xhr.responseText.substring(0, 4000) + '…' : xhr.responseText;
					msg += '<pre style="white-space:pre-wrap;font-size:11px;margin-top:8px;max-height:240px;overflow:auto;">' + $('<div/>').text(rt).html() + '</pre>';
				}
				$('#paidcustomerDiv').html('<div class="alert alert-danger">Error loading report.<br>' + msg + '</div>');
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

		$('#exporttoexcel').on('click', function(e) {
			e.preventDefault();
			var zone = $('#zone').val();
			var status = $('#status').val();
			if (status === '') { status = '99'; }
			window.location.href = exportBase + '/' + encodeURIComponent(zone) + '/' + encodeURIComponent(status);
		});

		$('#search').on('click', function(e) {
			e.preventDefault();
			loadPage(0);
		});

		$(document).on('click', '#cpm_prev', function(e) {
			e.preventDefault();
			if ($(this).prop('disabled')) { return; }
			var off = parseInt($('#list_offset').val(), 10) || 0;
			if (off >= 100) { loadPage(off - 100); }
		});

		$(document).on('click', '#cpm_next', function(e) {
			e.preventDefault();
			if ($(this).prop('disabled')) { return; }
			var off = parseInt($('#list_offset').val(), 10) || 0;
			loadPage(off + 100);
		});
	});
})();
</script>
