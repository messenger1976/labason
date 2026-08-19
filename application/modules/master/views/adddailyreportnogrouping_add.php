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
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>adddailyreportnogrouping/">Daily Report - No Grouping</a></li>
		<li class="breadcrumb-item active">Search</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-chart-bar"></i>
			Manage <span class="fw-300">Daily Report - No Grouping</span>
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

	<?php if (!empty($msg)) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<strong>Success!</strong> <?php echo $msg; ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-daily-report-nogroup" class="panel">
				<div class="panel-hdr">
					<h2>Daily Report <span class="fw-300"><i>No Grouping</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form name="daily_report_nogroup_form" id="daily_report_nogroup_form" method="post" action="javascript:void(0);">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="fromdate">Transaction Date</label>
										<div class="input-group">
											<input type="text" class="form-control" id="fromdate" name="fromdate" readonly placeholder="Select date" required>
											<div class="input-group-append">
												<span class="input-group-text fs-xl">
													<i class="fal fa-calendar"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="preparedby">Prepared by</label>
										<select class="form-control" name="preparedby" id="preparedby" required>
											<?php foreach ($employee as $key => $emp) { ?>
											<option value="<?php echo $emp['id']; ?>"><?php echo strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="verifiedby">Checked / Verified by</label>
										<select class="form-control" name="verifiedby" id="verifiedby" required>
											<?php foreach ($employee as $key => $emp) { ?>
											<option value="<?php echo $emp['id']; ?>"><?php echo strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="approvedby">Approved by</label>
										<select class="form-control" name="approvedby" id="approvedby" required>
											<?php foreach ($employee as $key => $emp) { ?>
											<option value="<?php echo $emp['id']; ?>"><?php echo strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>

							<div class="form-group mb-0">
								<button type="button" class="btn btn-primary" name="search" id="search" onclick="getaddcustomer_paid();">
									<i class="fal fa-search mr-1"></i> Search
								</button>
								<a href="javascript:void(0);" id="printtopdf" class="btn btn-warning">
									<i class="fal fa-print mr-1"></i> Print
								</a>
								<a href="javascript:void(0);" id="exporttopdf" class="btn btn-danger">
									<i class="fal fa-file-pdf mr-1"></i> Export to PDF
								</a>
								<a href="javascript:void(0);" id="exporttoexcel" class="btn btn-success">
									<i class="fal fa-file-excel mr-1"></i> Export to Excel
								</a>
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
<script src="<?php echo base_url(); ?>sa4/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/datagrid/datatables/datatables.bundle.js"></script>
</body>
</html>
<script type="text/javascript">
var curDate = '<?php echo date('d-m-Y') ?>';
$(document).ready(function(){
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

	var controls = {
		leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
		rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
	};

	if ($.fn.datepicker) {
		$('#fromdate').datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose: true,
			orientation: 'bottom left',
			templates: controls
		});

		$('#fromdate').closest('.input-group').find('.input-group-text').on('click', function() {
			$('#fromdate').datepicker('show');
		});
	}

	$('#printtopdf').on('click',function(evt){
		evt.preventDefault();
		var preparedby = $("#preparedby").val();
		var verifiedby = $("#verifiedby").val();
		var approvedby = $("#approvedby").val();
		var fromdate = $("#fromdate").val();

		if (fromdate == '') {
			alert('Please select a transaction date first.');
			return;
		}

		const popup = window.open(
			"adddailyreportnogrouping/printtopdf/"+fromdate+'/'+preparedby+'/'+verifiedby+'/'+approvedby,
			"PopupWindowPrint",
			"width=1200,height=600,resizable=yes,scrollbars=yes"
		);

		if (!popup || popup.closed || typeof popup.closed == "undefined") {
			alert("Popup was blocked! Please allow popups for this site.");
		}
	});

	$('#exporttopdf').on('click',function(evt){
		evt.preventDefault();
		var preparedby = $("#preparedby").val();
		var verifiedby = $("#verifiedby").val();
		var approvedby = $("#approvedby").val();
		var fromdate = $("#fromdate").val();

		if (fromdate == '') {
			alert('Please select a transaction date first.');
			return;
		}

		if (typeof openReportPdfPreview === 'function') {
			openReportPdfPreview({
				printUrl: "<?php echo ADMIN_URL;?>adddailyreportnogrouping/printtopdf/"+fromdate+'/'+preparedby+'/'+verifiedby+'/'+approvedby,
				filename: 'Daily_Collection_Report_NoGrouping.pdf'
			});
			return;
		}
		window.location.href = "<?php echo ADMIN_URL;?>adddailyreportnogrouping/exporttopdf/"+fromdate+'/'+preparedby+'/'+verifiedby+'/'+approvedby;
	});

	$('#exporttoexcel').on('click',function(evt){
		evt.preventDefault();
		var preparedby = $("#preparedby").val();
		var verifiedby = $("#verifiedby").val();
		var approvedby = $("#approvedby").val();
		var fromdate = $("#fromdate").val();

		if(fromdate == ''){
			alert("Please select a transaction date first.");
			return;
		}

		window.location.href = "<?php echo ADMIN_URL;?>adddailyreportnogrouping/exporttoexcel/"+fromdate+'/'+preparedby+'/'+verifiedby+'/'+approvedby;
	});
});
</script>
<script type="text/javascript">
	function initDailyReportTable() {
		if (!$.fn.DataTable || !$('#dt_daily_report').length) { return; }
		if ($.fn.DataTable.isDataTable('#dt_daily_report')) {
			$('#dt_daily_report').DataTable().destroy();
		}
		$('#dt_daily_report').DataTable({
			responsive: true,
			pageLength: 25,
			lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
			order: [[0, 'asc']],
			columnDefs: [
				{ targets: 4, className: 'text-right' }
			],
			dom: "<'row mb-3'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-8 d-flex justify-content-end flex-wrap'B>>" +
				"<'row mb-2'<'col-sm-12'f>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			language: {
				search: '',
				searchPlaceholder: 'Search daily report...',
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

	function getaddcustomer_paid(){
		var fromdate = $("#fromdate").val();

		if (fromdate == '') {
			alert('Please select a transaction date first.');
			return;
		}

		if ($.fn.DataTable && $.fn.DataTable.isDataTable('#dt_daily_report')) {
			$('#dt_daily_report').DataTable().destroy();
		}

		$("#paidcustomerDiv").html('<div class="text-center py-4 text-muted"><i class="fal fa-spinner fa-spin fa-2x mb-2"></i><div>Loading results…</div></div>');

		$.ajax({
			type : "POST",
			url	: '<?php echo ADMIN_URL;?>adddailyreportnogrouping/getadddailyreportsearch',
			data	: "fromdate="+fromdate,
			complete: function(data){
				var op = data.responseText.trim();
				$("#paidcustomerDiv").html(op);
				initDailyReportTable();
			}
		});
	}
</script>
