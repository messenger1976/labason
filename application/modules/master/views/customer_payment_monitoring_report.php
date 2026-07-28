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
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Customer Payment Monitoring Report <span class="fw-300"><i>Details</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
<div class="row">
			<div class="col-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark"><i class="glyphicon glyphicon-search"></i> Reports <span>&gt; Customer Payment Monitoring</span></h1>
			</div>
		</div>
		<section id="widget-grid" class="">
			<div class="row">
				<div class="col-sm-6 col-lg-12">
					<div class="panel panel-default">
						
							<fieldset>
								<legend>
									Multi-period payments (max 100 rows per request)
									<p class="text-muted" style="font-size:12px;margin:8px 0 0 0;font-weight:normal;">
										Only payments <strong>posted in the calendar month immediately before</strong> each customer&rsquo;s zone current billing period are included (when that zone has an active billing period).
									</p>
									<div class="pull-right" style="padding-right:20px;">
										<button type="button" class="btn btn-primary" id="search" style="margin-bottom: 5px;">Search</button>
										<button type="button" class="btn btn-success" id="exporttoexcel" style="margin-bottom: 5px;">Export to Excel</button>
									</div>
								</legend>
								<div class="form-group col-lg-6">
									<div class="col-lg-12 controls">
										<span class="input-group-addon"><i class="icon-user"></i><strong> Status: </strong></span>
										<select class="form-control" name="status" id="status">
											<option value="">--All--</option>
											<option value="1">Active</option>
											<option value="0">Inactive</option>
											<option value="2">Disconnected</option>
										</select>
									</div>
								</div>
								<div class="form-group col-lg-6">
									<div class="col-lg-12 controls">
										<span class="input-group-addon"><i class="icon-filter"></i><strong> Zone: </strong></span>
										<select class="form-control" name="zone" id="zone">
											<option value="0">--All--</option>
											<?php foreach ($zone as $key => $value) { ?>
											<option value="<?php echo (int) $value['id']; ?>"><?php echo htmlspecialchars($value['zone'], ENT_QUOTES, 'UTF-8'); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div style="clear:both"></div>
								<input type="hidden" id="list_offset" value="0">
								<div class="col-12" id="paidcustomerDiv" style="margin-top: 13px;"></div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>
<?php include 'footer.php'; ?>
<script type="text/javascript">
(function() {
	<?php
	$cpm_ajax_path = parse_url(site_url('master/reports/getcpmonsearch'), PHP_URL_PATH);
	if ($cpm_ajax_path === null || $cpm_ajax_path === '' || $cpm_ajax_path === '/') {
		$cpm_ajax_path = '/master/reports/getcpmonsearch';
	}
	$cpm_export_path = parse_url(site_url('master/reports/export_cpmon_excel'), PHP_URL_PATH);
	if ($cpm_export_path === null || $cpm_export_path === '' || $cpm_export_path === '/') {
		$cpm_export_path = '/master/reports/export_cpmon_excel';
	}
	?>
	var ajaxUrl = window.location.origin + <?php echo json_encode($cpm_ajax_path); ?>;
	var exportBase = window.location.origin + <?php echo json_encode(rtrim($cpm_export_path, '/')); ?>;

	function destroyDt($ctx) {
		var $t = ($ctx && $ctx.length) ? $ctx.find('#tbl_payment_monitor') : $('#tbl_payment_monitor');
		if (!$t.length) {
			return;
		}
		try {
			var el = $t[0];
			var inited = ($.fn.DataTable && $.fn.DataTable.isDataTable(el))
				|| ($.fn.dataTable && $.fn.dataTable.isDataTable && $.fn.dataTable.isDataTable(el));
			if (inited && typeof $t.DataTable === 'function') {
				$t.DataTable().destroy();
			}
		} catch (ignore) {}
	}

	function loadPage(goOffset) {
		var zone = $('#zone').val();
		var status = $('#status').val();
		var offset = (typeof goOffset === 'number') ? goOffset : parseInt($('#list_offset').val(), 10) || 0;
		if (offset < 0) {
			offset = 0;
		}
		$('#list_offset').val(offset);
		$('#paidcustomerDiv').html('<div class="alert alert-info">Loading…</div>');
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
				try {
					var $tbl = $('#tbl_payment_monitor');
					if ($tbl.length && typeof $.fn.DataTable === 'function') {
						$tbl.DataTable({
							paging: false,
							searching: true,
							info: false,
							order: [],
							autoWidth: true
						});
					}
				} catch (dtErr) {
					if (window.console && console.warn) {
						console.warn('DataTables init skipped:', dtErr);
					}
				}
			},
			error: function(xhr, textStatus, errorThrown) {
				var st = xhr && typeof xhr.status !== 'undefined' ? xhr.status : '(no status)';
				var msg = 'HTTP ' + st + ' — ';
				msg += (errorThrown && String(errorThrown)) || (textStatus && String(textStatus)) || 'request failed';
				msg += '<br><small>Request URL: <code>' + $('<div/>').text(ajaxUrl).html() + '</code></small>';
				if (String(st) === '0' || String(st) === '(no status)') {
					msg += '<br><small class="text-muted">Often caused by opening the site under a different host (www vs non-www), mixed http/https, a browser extension, or a firewall blocking the request. Try the exact same host as in the address bar, or another network/browser.</small>';
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
		pageSetUp();
		$('#exporttoexcel').on('click', function(e) {
			e.preventDefault();
			var zone = $('#zone').val();
			var status = $('#status').val();
			if (status === '') {
				status = '99';
			}
			window.location.href = exportBase + '/' + encodeURIComponent(zone) + '/' + encodeURIComponent(status);
		});
		$('#search').on('click', function(e) {
			e.preventDefault();
			loadPage(0);
		});
		$(document).on('click', '#cpm_prev', function(e) {
			e.preventDefault();
			if ($(this).prop('disabled')) {
				return;
			}
			var off = parseInt($('#list_offset').val(), 10) || 0;
			if (off >= 100) {
				loadPage(off - 100);
			}
		});
		$(document).on('click', '#cpm_next', function(e) {
			e.preventDefault();
			if ($(this).prop('disabled')) {
				return;
			}
			var off = parseInt($('#list_offset').val(), 10) || 0;
			loadPage(off + 100);
		});
	});
})();
</script>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
</body>
</html>
<script type="text/javascript">
(function() {
	<?php
	$cpm_ajax_path = parse_url(site_url('master/reports/getcpmonsearch'), PHP_URL_PATH);
	if ($cpm_ajax_path === null || $cpm_ajax_path === '' || $cpm_ajax_path === '/') {
		$cpm_ajax_path = '/master/reports/getcpmonsearch';
	}
	$cpm_export_path = parse_url(site_url('master/reports/export_cpmon_excel'), PHP_URL_PATH);
	if ($cpm_export_path === null || $cpm_export_path === '' || $cpm_export_path === '/') {
		$cpm_export_path = '/master/reports/export_cpmon_excel';
	}
	?>
	var ajaxUrl = window.location.origin + <?php echo json_encode($cpm_ajax_path); ?>;
	var exportBase = window.location.origin + <?php echo json_encode(rtrim($cpm_export_path, '/')); ?>;

	function destroyDt($ctx) {
		var $t = ($ctx && $ctx.length) ? $ctx.find('#tbl_payment_monitor') : $('#tbl_payment_monitor');
		if (!$t.length) {
			return;
		}
		try {
			var el = $t[0];
			var inited = ($.fn.DataTable && $.fn.DataTable.isDataTable(el))
				|| ($.fn.dataTable && $.fn.dataTable.isDataTable && $.fn.dataTable.isDataTable(el));
			if (inited && typeof $t.DataTable === 'function') {
				$t.DataTable().destroy();
			}
		} catch (ignore) {}
	}

	function loadPage(goOffset) {
		var zone = $('#zone').val();
		var status = $('#status').val();
		var offset = (typeof goOffset === 'number') ? goOffset : parseInt($('#list_offset').val(), 10) || 0;
		if (offset < 0) {
			offset = 0;
		}
		$('#list_offset').val(offset);
		$('#paidcustomerDiv').html('<div class="alert alert-info">Loading…</div>');
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
				try {
					var $tbl = $('#tbl_payment_monitor');
					if ($tbl.length && typeof $.fn.DataTable === 'function') {
						$tbl.DataTable({
							paging: false,
							searching: true,
							info: false,
							order: [],
							autoWidth: true
						});
					}
				} catch (dtErr) {
					if (window.console && console.warn) {
						console.warn('DataTables init skipped:', dtErr);
					}
				}
			},
			error: function(xhr, textStatus, errorThrown) {
				var st = xhr && typeof xhr.status !== 'undefined' ? xhr.status : '(no status)';
				var msg = 'HTTP ' + st + ' — ';
				msg += (errorThrown && String(errorThrown)) || (textStatus && String(textStatus)) || 'request failed';
				msg += '<br><small>Request URL: <code>' + $('<div/>').text(ajaxUrl).html() + '</code></small>';
				if (String(st) === '0' || String(st) === '(no status)') {
					msg += '<br><small class="text-muted">Often caused by opening the site under a different host (www vs non-www), mixed http/https, a browser extension, or a firewall blocking the request. Try the exact same host as in the address bar, or another network/browser.</small>';
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
		pageSetUp();
		$('#exporttoexcel').on('click', function(e) {
			e.preventDefault();
			var zone = $('#zone').val();
			var status = $('#status').val();
			if (status === '') {
				status = '99';
			}
			window.location.href = exportBase + '/' + encodeURIComponent(zone) + '/' + encodeURIComponent(status);
		});
		$('#search').on('click', function(e) {
			e.preventDefault();
			loadPage(0);
		});
		$(document).on('click', '#cpm_prev', function(e) {
			e.preventDefault();
			if ($(this).prop('disabled')) {
				return;
			}
			var off = parseInt($('#list_offset').val(), 10) || 0;
			if (off >= 100) {
				loadPage(off - 100);
			}
		});
		$(document).on('click', '#cpm_next', function(e) {
			e.preventDefault();
			if ($(this).prop('disabled')) {
				return;
			}
			var off = parseInt($('#list_offset').val(), 10) || 0;
			loadPage(off + 100);
		});
	});
})();
</script>

