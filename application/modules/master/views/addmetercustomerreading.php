<?php
	$income1 = $this->my_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->my_model->get_income_monthlycustomer();
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
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addmetercustomerreading/add/">Meter Customer Reading Add</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-tachometer-alt"></i>
			View <span class="fw-300">Meter Customer Reading</span>
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
		<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
		<strong>Success!</strong> <?php echo $this->session->flashdata('msg_succ'); ?>
	</div>
	<?php } ?>

	<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
	<div class="row">
		<div class="col-xl-12">
			<div id="panel-meter-reading" class="panel">
				<div class="panel-hdr">
					<h2>Meter Customer <span class="fw-300"><i>Reading</i></span></h2>
					<div class="panel-toolbar">
						<a href="<?php echo ADMIN_URL; ?>addmetercustomerreading/add" class="btn btn-primary btn-sm waves-effect waves-themed mr-2">
							<i class="fal fa-plus mr-1"></i> Add Meter Reading
						</a>
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form method="post" action="<?php echo ADMIN_URL; ?>addmetercustomerreading/multi_delete" id="reading-list-form">
							<div class="row mb-3">
								<div class="col-12">
									<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
										<i class="fal fa-trash-alt mr-1"></i> Delete Selected
									</button>
								</div>
							</div>
							<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
								<thead>
									<tr>
										<th style="width:28px;"><input type="checkbox" id="dt_select_all" class="ace" /></th>
										<th>S No</th>
										<th>Billing Ref No</th>
										<th>Customer-Id</th>
										<th>Customer Name</th>
										<th>Previous Reading</th>
										<th>Current Reading</th>
										<th>Consumed</th>
										<th>Billing Period</th>
										<th>Reading Date</th>
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
</main>

<?php
	$sa4_loading_label = 'Readings';
	include(__DIR__ . '/partials/sa4_dt_loading.php');
	include('footer.php');
?>
<script src="<?php echo base_url(); ?>sa4/js/datagrid/datatables/datatables.bundle.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
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
		$('#dt-loading-title').text('Fetching meter reading records');
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
		setTimeout(done, 220);
	}
	function showLoader() {
		$('#datatable-loading-modal').addClass('is-visible').show();
		$('#panel-meter-reading').addClass('panel-loading');
		$('.dataTables_wrapper').addClass('processing');
		startProgress();
	}
	function hideLoader() {
		completeProgress(function() {
			$('#datatable-loading-modal').removeClass('is-visible').fadeOut(180);
			$('#panel-meter-reading').removeClass('panel-loading');
			$('.dataTables_wrapper').removeClass('processing');
			setTimeout(function() { setProgress(8); }, 250);
		});
	}

	showLoader();

	var table = $('#dt_basic').DataTable({
		processing: true,
		serverSide: true,
		responsive: true,
		pageLength: 100,
		lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
		order: [[0, 'desc']],
		searchDelay: 999999,
		ajax: {
			url: "<?php echo ADMIN_URL; ?>addmetercustomerreading/get_datatable_data",
			type: "POST",
			data: function(d) {
				d.billing_period = $('#header_billingperiod').val() || '';
				return d;
			}
		},
		columns: [
			{ data: 0, orderable: false, searchable: false, className: 'text-center' },
			{ data: 1, orderable: false },
			{ data: 2, orderable: true },
			{ data: 3, orderable: true },
			{ data: 4, orderable: true },
			{ data: 5, orderable: true },
			{ data: 6, orderable: true },
			{ data: 7, orderable: true },
			{ data: 8, orderable: true },
			{ data: 9, orderable: true }
		],
		dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
		language: {
			processing: '',
			search: '',
			searchPlaceholder: 'Search readings...',
			info: 'Showing _START_ to _END_ of _TOTAL_ readings',
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
			{ extend: 'print', text: '<i class="fal fa-print mr-1"></i> Print', className: 'btn-primary btn-sm mr-1', exportOptions: { columns: [1,2,3,4,5,6,7,8,9] } },
			{ text: '<i class="fal fa-sync mr-1"></i> Refresh', className: 'btn-primary btn-sm', action: function(e, dt) { dt.ajax.reload(null, false); } }
		],
		drawCallback: function() {
			if (isInitialLoad) {
				isInitialLoad = false;
				hideLoader();
			}
		}
	});

	function setupCustomSearch() {
		var searchInput = $('.dataTables_filter input');
		if (searchInput.length && !searchInput.data('custom-search-bound')) {
			searchInput.off('keyup.DT input.DT');
			searchInput.on('keypress.custom', function(e) {
				if (e.which === 13) {
					e.preventDefault();
					table.search($(this).val()).draw();
					return false;
				}
			});
			searchInput.on('blur.custom', function() {
				table.search($(this).val()).draw();
			});
			searchInput.data('custom-search-bound', true);
		}
	}
	setupCustomSearch();
	table.on('draw.dt', function() {
		$('.dataTables_filter input').removeData('custom-search-bound');
		setupCustomSearch();
	});

	table.on('processing.dt', function(e, settings, processing) {
		if (processing) {
			if (!$('#datatable-loading-modal').hasClass('is-visible')) showLoader();
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
</body>
</html>
