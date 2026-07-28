<?php
	// Income via common model (original behavior)
	$income1 = $this->comm_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->comm_model->get_income_monthlycustomer();
	extract($income2);
	$intotal = (isset($total1) ? (float)$total1 : 0) + (isset($total2) ? (float)$total2 : 0);

	$expense1 = $this->my_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->my_model->get_outcome_payroll();
	extract($expense2);
	$extotal = (isset($extotal1) ? (float)$extotal1 : 0) + (isset($extotal2) ? (float)$extotal2 : 0);

	$total_customer = $this->my_model->total_customer();
	extract($total_customer);
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addpaymentcustomer/add/">Meter Customer Bills Add</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-wallet"></i>
			View <span class="fw-300">Customers Payment</span>
		</h1>
		<div class="subheader-block d-lg-flex align-items-center">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>BILLING PERIOD</small></span>
				<select class="form-control form-control-sm" name="header_billingperiod" id="header_billingperiod" style="min-width:160px;">
					<option value="">--All--</option>
					<?php if (!empty($billingperiod)) { foreach ($billingperiod as $value) {
						$val_val = $value['bp_period_month'] . ' ' . $value['bp_period_year'];
						$selected_val = (isset($_SESSION['current_billingperiod']) && $_SESSION['current_billingperiod'] == $val_val) ? 'selected' : '';
					?>
					<option value="<?php echo htmlspecialchars($val_val); ?>" <?php echo $selected_val; ?>><?php echo htmlspecialchars($value['month_name'] . ' ' . $value['bp_period_year']); ?></option>
					<?php } } ?>
				</select>
			</div>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>INCOME</small></span>
				<span class="fw-500 fs-xl d-block color-primary-500">₱ <?php echo number_format($intotal, 2); ?></span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#886ab5" sparkHeight="32px" sparkBarWidth="5px" values="3,4,3,6,7,3,3,6,2,6,4"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>EXPENSE</small></span>
				<span class="fw-500 fs-xl d-block color-danger-500">₱ <?php echo number_format($extotal, 2); ?></span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#fe6bb0" sparkHeight="32px" sparkBarWidth="5px" values="1,4,3,6,5,3,9,6,5,9,7"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>TOTAL CUSTOMER</small></span>
				<span class="fw-500 fs-xl d-block color-success-500"><?php echo (int) $count_id; ?></span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#1dc9b7" sparkHeight="32px" sparkBarWidth="5px" values="2,5,3,7,4,6,3,8,5,4,6"></span>
		</div>
	</div>

	<?php if ($this->session->flashdata('msg_succ')) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<strong>Success!</strong> <?php echo $this->session->flashdata('msg_succ'); ?>
	</div>
	<?php } ?>

	<section id="widget-grid" class="">
		<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
		<div class="row">
			<div class="col-xl-12">
				<div id="panel-payments" class="panel">
					<div class="panel-hdr">
						<h2>Customer Bills <span class="fw-300"><i>Payment</i></span></h2>
						<div class="panel-toolbar">
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<div class="row mb-3 align-items-end">
								<div class="col-12 text-right">
									<a href="<?php echo ADMIN_URL; ?>addpaymentcustomer/add" class="btn btn-success btn-sm waves-effect waves-themed">
										<i class="fal fa-plus mr-1"></i> Add Customer Payment
									</a>
								</div>
							</div>
							<form method="post" action="<?php echo ADMIN_URL; ?>addpaymentcustomer/multi_delete" id="payment-list-form">
								<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
									<thead>
										<tr>
											<th style="width:28px;"><input type="checkbox" id="dt_select_all" class="ace" /></th>
											<th style="width:60px;">S No</th>
											<th>Customer-Id</th>
											<th>Name</th>
											<th>OR #</th>
											<th class="text-right">Gross Amount</th>
											<th class="text-right">Leaking Discount</th>
											<th class="text-right">VAT Discount</th>
											<th class="text-right">Net Amount</th>
											<th class="text-center">Paid Date</th>
											<th style="width:140px;" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<!-- Loading Modal Overlay -->
<div id="datatable-loading-modal" class="dt-loading-modal" style="display: none;" aria-live="polite" aria-busy="true">
	<div class="dt-loading-backdrop"></div>
	<div class="dt-loading-card panel shadow-3">
		<div class="panel-hdr bg-primary-600 bg-primary-gradient">
			<h2 class="text-white">Loading <span class="fw-300">Payments</span></h2>
		</div>
		<div class="panel-container show">
			<div class="panel-content text-center py-4 px-4">
				<div class="mb-3">
					<div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
						<span class="sr-only">Loading...</span>
					</div>
				</div>
				<h5 class="mb-1 fw-500" id="dt-loading-title">Fetching payment records</h5>
				<p class="text-muted mb-3 fs-sm" id="dt-loading-subtitle">Please wait while we prepare the listing…</p>
				<div class="progress progress-lg mb-2">
					<div id="dt-loading-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary-500" role="progressbar" style="width: 8%;" aria-valuenow="8" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex justify-content-between fs-xs text-muted">
					<span>Event progress</span>
					<span id="dt-loading-percent">8%</span>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include('footer.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
</body>
</html>
<style>
	.dt-loading-modal { position: fixed; inset: 0; z-index: 1055; display: none; align-items: center; justify-content: center; }
	.dt-loading-modal.is-visible { display: flex !important; }
	.dt-loading-backdrop { position: absolute; inset: 0; background: rgba(33, 37, 41, 0.45); backdrop-filter: blur(3px); }
	.dt-loading-card { position: relative; z-index: 1; width: min(420px, calc(100vw - 2rem)); margin: 0; border: 0; overflow: hidden; }
	.dt-loading-card .panel-hdr { border-bottom: 0; }
	.dt-loading-card .progress { height: 1rem; border-radius: 999px; background: rgba(136, 106, 181, 0.15); overflow: hidden; }
	.dt-loading-card .progress-bar { transition: width 0.25s ease; border-radius: 999px; }
	.dataTables_wrapper { position: relative; }
	.dataTables_wrapper.processing { opacity: 0.55; pointer-events: none; filter: grayscale(0.15); }
	#panel-payments { position: relative; }
	#panel-payments.panel-loading::before {
		content: ''; position: absolute; top: 0; left: 0; height: 3px; width: 100%; z-index: 5;
		background: linear-gradient(90deg, transparent, var(--theme-primary, #886ab5), transparent);
		background-size: 40% 100%; animation: dt-panel-shimmer 1.1s linear infinite;
	}
	@keyframes dt-panel-shimmer { 0% { background-position: -40% 0; } 100% { background-position: 140% 0; } }
	#dt_basic td .btn-group .btn { min-width: 2rem; }
</style>
<script src="<?php echo base_url(); ?>sa4/js/datagrid/datatables/datatables.bundle.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	pageSetUp();

	var isInitialLoad = true;
	var progressTimer = null;
	var progressValue = 8;

	function setProgress(pct) {
		progressValue = Math.max(0, Math.min(100, pct));
		$('#dt-loading-progress-bar').css('width', progressValue + '%').attr('aria-valuenow', Math.round(progressValue));
		$('#dt-loading-percent').text(Math.round(progressValue) + '%');
	}
	function startProgress() {
		clearInterval(progressTimer);
		setProgress(8);
		$('#dt-loading-title').text('Fetching payment records');
		$('#dt-loading-subtitle').text('Please wait while we prepare the listing…');
		progressTimer = setInterval(function() {
			if (progressValue < 90) {
				setProgress(progressValue + Math.max(0.6, (90 - progressValue) * 0.08));
			}
		}, 180);
	}
	function completeProgress(done) {
		clearInterval(progressTimer);
		setProgress(100);
		$('#dt-loading-title').text('Almost done');
		$('#dt-loading-subtitle').text('Rendering payment listing…');
		setTimeout(done, 220);
	}
	function showLoader() {
		$('#datatable-loading-modal').addClass('is-visible').show();
		$('#panel-payments').addClass('panel-loading');
		$('.dataTables_wrapper').addClass('processing');
		startProgress();
	}
	function hideLoader() {
		completeProgress(function() {
			$('#datatable-loading-modal').removeClass('is-visible').fadeOut(180);
			$('#panel-payments').removeClass('panel-loading');
			$('.dataTables_wrapper').removeClass('processing');
			setTimeout(function() { setProgress(8); }, 250);
		});
	}

	showLoader();

	var table = $('#dt_basic').DataTable({
		processing: true,
		serverSide: true,
		responsive: true,
		stateSave: false,
		pageLength: 100,
		lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
		order: [[1, 'desc']],
		searchDelay: 999999,
		ajax: {
			url: "<?php echo ADMIN_URL; ?>addpaymentcustomer/get_datatable_data",
			type: "POST",
			data: function(d) {
				d.billing_period = $('#header_billingperiod').val() || '';
			}
		},
		columns: [
			{ data: 0, orderable: false, searchable: false, className: 'text-center' },
			{ data: 1, orderable: true, className: 'text-center' },
			{ data: 2, orderable: true, className: 'text-center' },
			{ data: 3, orderable: true },
			{ data: 4, orderable: true, className: 'text-center' },
			{ data: 5, orderable: true, className: 'text-right' },
			{ data: 6, orderable: true, className: 'text-right' },
			{ data: 7, orderable: true, className: 'text-right' },
			{ data: 8, orderable: true, className: 'text-right' },
			{ data: 9, orderable: true, className: 'text-center' },
			{ data: 10, orderable: false, searchable: false, className: 'text-center' }
		],
		dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
		language: {
			processing: '',
			search: '',
			searchPlaceholder: 'Search payments...',
			lengthMenu: '_MENU_',
			info: 'Showing _START_ to _END_ of _TOTAL_ payments',
			infoEmpty: 'No payments found',
			zeroRecords: 'No matching payments',
			paginate: {
				first: '<i class="fal fa-chevron-double-left"></i>',
				last: '<i class="fal fa-chevron-double-right"></i>',
				next: '<i class="fal fa-chevron-right"></i>',
				previous: '<i class="fal fa-chevron-left"></i>'
			}
		},
		buttons: [
			{ extend: 'copyHtml5', text: '<i class="fal fa-copy mr-1"></i> Copy', className: 'btn-primary btn-sm mr-1', exportOptions: { columns: [1,2,3,4,5,6,7,8,9] } },
			{ extend: 'excelHtml5', text: '<i class="fal fa-file-excel mr-1"></i> Excel', className: 'btn-primary btn-sm mr-1', exportOptions: { columns: [1,2,3,4,5,6,7,8,9] } },
			{ extend: 'csvHtml5', text: '<i class="fal fa-file-csv mr-1"></i> CSV', className: 'btn-primary btn-sm mr-1', exportOptions: { columns: [1,2,3,4,5,6,7,8,9] } },
			{ extend: 'pdfHtml5', text: '<i class="fal fa-file-pdf mr-1"></i> PDF', className: 'btn-primary btn-sm mr-1', exportOptions: { columns: [1,2,3,4,5,6,7,8,9] } },
			{ extend: 'print', text: '<i class="fal fa-print mr-1"></i> Print', className: 'btn-primary btn-sm mr-1', exportOptions: { columns: [1,2,3,4,5,6,7,8,9] } },
			{ text: '<i class="fal fa-sync mr-1"></i> Refresh', className: 'btn-primary btn-sm', action: function(e, dt) { dt.ajax.reload(null, false); } }
		],
		drawCallback: function() {
			if (isInitialLoad) {
				isInitialLoad = false;
				hideLoader();
			}
			if ($.fn.tooltip) { $('[data-toggle="tooltip"]').tooltip(); }
		}
	});

	var searchInput = $('.dataTables_filter input');
	searchInput.off('keyup.DT search.DT input.DT paste.DT cut.DT');
	searchInput.on('keypress', function(e) {
		if (e.which === 13) {
			e.preventDefault();
			table.search($(this).val()).draw();
		}
	});
	searchInput.on('blur', function() {
		table.search($(this).val()).draw();
	});

	table.on('processing.dt', function(e, settings, processing) {
		if (processing) {
			if (!$('#datatable-loading-modal').hasClass('is-visible')) {
				showLoader();
			}
		} else if (!isInitialLoad) {
			hideLoader();
		}
	});

	$('#header_billingperiod').on('change', function(evt) {
		evt.preventDefault();
		var header_billing_period = $(this).val();
		showLoader();
		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL; ?>addbillingperiod/updated_headerbillingperiod',
			data: 'billing_period=' + encodeURIComponent(header_billing_period),
			complete: function() {
				table.ajax.reload(null, false);
			},
			error: function() { hideLoader(); }
		});
	});

	$('#dt_select_all').on('change', function() {
		$('#dt_basic tbody input[name="delete_ids[]"]').prop('checked', $(this).is(':checked'));
	});
});
</script>
<script>
$(document).on('click','.print_button',function(e){
	e.preventDefault();
	var paybtnid = $(this).data('print-val-id');
	var customer = $('#customerid_'+paybtnid).val();
	var month = $('#month_'+paybtnid).val();
	var year = $('#year_'+paybtnid).val();
	if(customer != '' && month != '' && year != ''){
		window.open('<?php echo ADMIN_URL;?>addpaymentcustomer/monthlyreceipt/'+customer+'/'+month+'/'+year, 'popupWindow', 'width=1024,height=600,scrollbars=yes');
	}
});
$(document).on('click','.print_button_new',function(e){
	e.preventDefault();
	var paybtnid = $(this).data('print-val-id');
	var customer = $('#customerid_'+paybtnid).val();
	var month = $('#month_'+paybtnid).val();
	var year = $('#year_'+paybtnid).val();
	var invoice_id = $('#invoiceid_'+paybtnid).val();
	if(customer != '' && month != '' && year != ''){
		window.open('<?php echo ADMIN_URL;?>addpaymentcustomer/monthly_receipt/'+customer+'/'+month+'/'+year+'/'+invoice_id, 'popupWindow', 'width=1024,height=600,scrollbars=yes');
	}
});
$(document).on('click','.print_button_new1',function(e){
	e.preventDefault();
	var paybtnid = $(this).data('print-val-id');
	var customer = $('#customerid_'+paybtnid).val();
	var month = $('#month_'+paybtnid).val();
	var year = $('#year_'+paybtnid).val();
	var invoice_id = $('#invoiceid_'+paybtnid).val();
	if(customer != '' && month != '' && year != ''){
		window.open('<?php echo ADMIN_URL;?>addpaymentcustomer/monthly_receipt_ver1/'+customer+'/'+month+'/'+year+'/'+invoice_id, 'popupWindow', 'width=1800,height=600,scrollbars=yes');
	}
});
$(document).on('click','.print_button_new2',function(e){
	e.preventDefault();
	var paybtnid = $(this).data('print-val-id');
	var customer = $('#customerid_'+paybtnid).val();
	var month = $('#month_'+paybtnid).val();
	var year = $('#year_'+paybtnid).val();
	var invoice_id = $('#invoiceid_'+paybtnid).val();
	if(customer != '' && month != '' && year != ''){
		window.open('<?php echo ADMIN_URL;?>addpaymentcustomer/monthly_receipt_ver2/'+customer+'/'+month+'/'+year+'/'+invoice_id, 'popupWindow', 'width=1800,height=600,scrollbars=yes');
	}
});
</script>
