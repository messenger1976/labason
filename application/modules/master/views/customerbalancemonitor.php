<?php
	$income1 = $this->daily_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->daily_model->get_income_monthlycustomer();
	extract($income2);
	$intotal = (isset($total1) ? (float) $total1 : 0) + (isset($total2) ? (float) $total2 : 0);

	$expense1 = $this->daily_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->daily_model->get_outcome_payroll();
	extract($expense2);
	$extotal = (isset($extotal1) ? (float) $extotal1 : 0) + (isset($extotal2) ? (float) $extotal2 : 0);

	$total_customer = $this->daily_model->total_customer();
	extract($total_customer);
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>customerbalancemonitor">Customer Balance Monitor</a></li>
		<li class="breadcrumb-item active">Search</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-balance-scale"></i>
			Manage <span class="fw-300">Customer Balance Monitor</span>
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
			<div id="panel-balance-monitor" class="panel">
				<div class="panel-hdr">
					<h2>Customer Balance Monitor <span class="fw-300"><i>Search</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<p class="text-muted mb-3">
							Balance uses the same rules as <strong>Statement of Account</strong> (billings minus payments) but <strong>excludes the active billing period</strong> for the customer&rsquo;s zone (<code>bp_status = 1</code>, latest month/year). That period&rsquo;s bill is omitted; a payment that applies <em>only</em> to that period is omitted too. Mixed-period receipts are left as in SOA. Large zones may take a minute to load.
						</p>

						<form name="balance_monitor_form" id="balance_monitor_form" method="post" action="javascript:void(0);">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="status">Status</label>
										<select class="form-control" name="status" id="status" required>
											<option value="">--All--</option>
											<option value="1">Active</option>
											<option value="0">Inactive</option>
											<option value="2">Disconnected</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="special_privilege">Special Privilege</label>
										<div class="custom-control custom-checkbox mt-2">
											<input type="checkbox" class="custom-control-input" id="special_privilege" name="special_privilege" value="1">
											<label class="custom-control-label" for="special_privilege">Show only customers with special privilege</label>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="zone">Zone</label>
										<select class="form-control" name="zone" id="zone" required>
											<option value="0">--All--</option>
											<?php foreach ($zone as $key => $value) { ?>
											<option value="<?php echo $value['id']; ?>"><?php echo htmlspecialchars($value['zone']); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="only_with_balance">Filter</label>
										<div class="custom-control custom-checkbox mt-2">
											<input type="checkbox" class="custom-control-input" id="only_with_balance" name="only_with_balance" value="1">
											<label class="custom-control-label" for="only_with_balance">Show only customers with a non-zero balance</label>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group mb-0">
								<button type="button" class="btn btn-primary" id="search">
									<i class="fal fa-search mr-1"></i> Display
								</button>
							</div>
						</form>

						<div id="resultDiv" class="mt-3"></div>
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
(function($) {
	var batchUrl = '<?php echo ADMIN_URL; ?>customerbalancemonitor/search_batch';
	var rowIndex = 0;
	var grandTotal = 0;
	var loading = false;

	function destroyTable() {
		if ($.fn.DataTable && $.fn.DataTable.isDataTable('#balance_monitor_table')) {
			$('#balance_monitor_table').DataTable().destroy();
		}
	}

	function initTable() {
		if (!$.fn.DataTable || !$('#balance_monitor_table').length) { return; }
		destroyTable();
		$('#balance_monitor_table').DataTable({
			responsive: true,
			pageLength: 25,
			lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
			order: [[0, 'asc']],
			dom: "<'row mb-3'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-8 d-flex justify-content-end flex-wrap'B>>" +
				"<'row mb-2'<'col-sm-12'f>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			language: {
				search: '',
				searchPlaceholder: 'Search balances...',
				lengthMenu: '_MENU_',
				info: 'Showing _START_ to _END_ of _TOTAL_ records',
				infoEmpty: 'No records found',
				zeroRecords: 'No matching records'
			},
			buttons: [
				{ extend: 'copyHtml5', text: '<i class="fal fa-copy mr-1"></i> Copy', className: 'btn-primary btn-sm mr-1 mb-1' },
				{ extend: 'excelHtml5', text: '<i class="fal fa-file-excel mr-1"></i> Excel', className: 'btn-primary btn-sm mr-1 mb-1' },
				{ extend: 'csvHtml5', text: '<i class="fal fa-file-csv mr-1"></i> CSV', className: 'btn-primary btn-sm mr-1 mb-1' },
				{ extend: 'print', text: '<i class="fal fa-print mr-1"></i> Print', className: 'btn-primary btn-sm mr-1 mb-1' }
			]
		});
	}

	function esc(s) {
		return $('<div/>').text(s == null ? '' : String(s)).html();
	}

	function formatMoney(n) {
		return Number(n || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}

	function ensureTableShell() {
		if ($('#balance_monitor_table').length) { return; }
		var html = ''
			+ '<div id="cbm_progress" class="mb-3">'
			+ '  <div class="d-flex justify-content-between align-items-center mb-1">'
			+ '    <span class="text-muted" id="cbm_progress_label">Loading…</span>'
			+ '    <strong id="cbm_running_total">₱ 0.00</strong>'
			+ '  </div>'
			+ '  <div class="progress progress-sm">'
			+ '    <div id="cbm_progress_bar" class="progress-bar bg-primary-500" role="progressbar" style="width:0%"></div>'
			+ '  </div>'
			+ '</div>'
			+ '<p class="text-right mb-2" id="cbm_sum_line" style="display:none;">'
			+ '  <strong>Sum of displayed balances (excl. active billing period):</strong> '
			+ '  <span class="color-primary-500" id="cbm_sum_value">0.00</span>'
			+ '</p>'
			+ '<div class="table-responsive">'
			+ '  <table id="balance_monitor_table" class="table table-bordered table-hover table-striped w-100">'
			+ '    <thead class="bg-primary-600">'
			+ '      <tr>'
			+ '        <th>#</th>'
			+ '        <th>Customer ID</th>'
			+ '        <th>Name</th>'
			+ '        <th>Address</th>'
			+ '        <th>Zone</th>'
			+ '        <th class="text-right">Balance (SOA excl. current period)</th>'
			+ '        <th>Statement</th>'
			+ '      </tr>'
			+ '    </thead>'
			+ '    <tbody></tbody>'
			+ '  </table>'
			+ '</div>';
		$('#resultDiv').html(html);
	}

	function appendRows(rows) {
		var $tbody = $('#balance_monitor_table tbody');
		for (var i = 0; i < rows.length; i++) {
			var r = rows[i];
			rowIndex++;
			var bal = parseFloat(r.total_balance) || 0;
			grandTotal += bal;
			var balClass = (bal > 0.005) ? 'text-danger' : ((bal < -0.005) ? 'text-success' : '');
			var name = $.trim((r.first_name || '') + ' ' + (r.middle_name || '') + ' ' + (r.last_name || ''));
			var stmt = r.statement_url || '#';
			$tbody.append(
				'<tr>'
				+ '<td>' + rowIndex + '</td>'
				+ '<td>' + esc(r.customer_id) + '</td>'
				+ '<td>' + esc(name) + '</td>'
				+ '<td>' + esc(r.address) + '</td>'
				+ '<td>' + esc(r.zone_name) + '</td>'
				+ '<td class="text-right ' + balClass + '">' + formatMoney(bal) + '</td>'
				+ '<td><a href="' + esc(stmt) + '" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">Open SOA</a></td>'
				+ '</tr>'
			);
		}
		$('#cbm_running_total').text('₱ ' + formatMoney(grandTotal));
		$('#cbm_sum_value').text(formatMoney(grandTotal));
	}

	function setProgress(doneCount, total) {
		var pct = total > 0 ? Math.min(100, Math.round((doneCount / total) * 100)) : 0;
		$('#cbm_progress_bar').css('width', pct + '%');
		$('#cbm_progress_label').text('Processed ' + doneCount + ' of ' + total + ' customers…');
	}

	function finishLoad() {
		loading = false;
		$('#search').prop('disabled', false);
		$('#cbm_progress').hide();
		$('#cbm_sum_line').show();
		if (rowIndex === 0) {
			$('#balance_monitor_table tbody').html('<tr><td colspan="7" class="text-center py-4">No records found</td></tr>');
			return;
		}
		initTable();
	}

	function fetchBatch(offset) {
		$.ajax({
			type: 'POST',
			url: batchUrl,
			dataType: 'json',
			timeout: 300000,
			data: {
				zone: $('#zone').val(),
				status: $('#status').val(),
				special_privilege: $('#special_privilege').is(':checked') ? 1 : 0,
				only_with_balance: $('#only_with_balance').is(':checked') ? 1 : 0,
				batch_offset: offset
			},
			success: function(resp) {
				if (!resp || !resp.ok) {
					$('#resultDiv').html('<div class="alert alert-danger">Failed to load balance data.</div>');
					loading = false;
					$('#search').prop('disabled', false);
					return;
				}
				appendRows(resp.rows || []);
				setProgress(resp.next_offset || 0, resp.total || 0);
				if (resp.done) {
					finishLoad();
				} else {
					fetchBatch(resp.next_offset || 0);
				}
			},
			error: function(xhr) {
				loading = false;
				$('#search').prop('disabled', false);
				var msg = 'Error loading data.';
				if (xhr && xhr.responseText) {
					msg += '<br><small>' + $('<div/>').text(xhr.responseText.substring(0, 300)).html() + '</small>';
				}
				$('#resultDiv').html('<div class="alert alert-danger">' + msg + '</div>');
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

		$('#search').on('click', function(e) {
			e.preventDefault();
			if (loading) { return; }
			loading = true;
			rowIndex = 0;
			grandTotal = 0;
			destroyTable();
			$('#search').prop('disabled', true);
			ensureTableShell();
			$('#balance_monitor_table tbody').empty();
			$('#cbm_progress').show();
			$('#cbm_sum_line').hide();
			setProgress(0, 0);
			fetchBatch(0);
		});
	});
})(jQuery);
</script>
