<?php
	$sa4_loading_label = 'Meter Rate';
	$sa4_panel_id = 'panel-amountrate';

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

	$classification = (isset($classification) && is_array($classification)) ? $classification : array();
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>amountrate">Meter Rate</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-tachometer"></i>
			Manage <span class="fw-300">Meter Rate</span>
		</h1>
		<div class="subheader-block d-lg-flex align-items-center">
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
				<span class="fw-500 fs-xl d-block color-success-500"><?php echo (int) (isset($count_id) ? $count_id : 0); ?></span>
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

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-amountrate" class="panel">
				<div class="panel-hdr">
					<h2>Meter Rate <span class="fw-300"><i>Listing</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<div class="row mb-3 align-items-end">
							<div class="col-sm-6 col-md-3 col-lg-3">
								<label class="form-label" for="filter_classification">Filter by Classification</label>
								<select id="filter_classification" class="form-control form-control-sm">
									<option value="0">All Classifications</option>
									<?php foreach ($classification as $value) { ?>
									<option value="<?php echo (int) $value['class_id']; ?>"><?php echo htmlspecialchars($value['class_name']); ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-sm-6 col-md-9 col-lg-9 text-right">
								<a href="#" id="exportExcelBtn" class="btn btn-primary btn-sm waves-effect waves-themed mr-1">
									<i class="fal fa-file-excel mr-1"></i> Export to Excel
								</a>
								<a href="<?php echo ADMIN_URL; ?>amountrate/add/" class="btn btn-success btn-sm waves-effect waves-themed">
									<i class="fal fa-plus mr-1"></i> Add Meter Rate
								</a>
							</div>
						</div>

						<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
							<thead>
								<tr>
									<th style="width:60px;">S No</th>
									<th>Classification</th>
									<th>Cubic Meter</th>
									<th>Meter Rate</th>
									<th>Charges/Consumption</th>
									<th>Status</th>
									<th style="width:90px;">Action</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php
	$sa4_loading_label = 'meter rates';
	include(__DIR__ . '/partials/sa4_dt_loading.php');
	include('footer.php');
?>
<script src="<?php echo base_url(); ?>sa4/js/datagrid/datatables/datatables.bundle.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
<script type="text/javascript">
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
		$('#dt-loading-title').text('Fetching meter rates');
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
		$('#dt-loading-subtitle').text('Rendering listing…');
		setTimeout(done, 220);
	}
	function showLoader() {
		$('#datatable-loading-modal').addClass('is-visible').show();
		$('#panel-amountrate').addClass('panel-loading');
		$('.dataTables_wrapper').addClass('processing');
		startProgress();
	}
	function hideLoader() {
		completeProgress(function() {
			$('#datatable-loading-modal').removeClass('is-visible').fadeOut(180);
			$('#panel-amountrate').removeClass('panel-loading');
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
		pageLength: 25,
		lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
		order: [[2, 'asc']],
		ajax: {
			url: "<?php echo ADMIN_URL; ?>amountrate/get_datatable_data",
			type: "POST",
			data: function(d) {
				d.classification_id = $('#filter_classification').val() || '0';
			}
		},
		columns: [
			{ data: 0, orderable: false, searchable: false, className: 'text-center' },
			{ data: 1, orderable: true },
			{ data: 2, orderable: true, className: 'text-center' },
			{ data: 3, orderable: false, className: 'text-right' },
			{ data: 4, orderable: false, className: 'text-right' },
			{ data: 5, orderable: false, className: 'text-center' },
			{ data: 6, orderable: false, searchable: false, className: 'text-center' }
		],
		dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
		language: {
			processing: '',
			search: '',
			searchPlaceholder: 'Search meter rates...',
			lengthMenu: '_MENU_',
			info: 'Showing _START_ to _END_ of _TOTAL_ rates',
			infoEmpty: 'No rates found',
			zeroRecords: 'No matching rates',
			paginate: {
				first: '<i class="fal fa-chevron-double-left"></i>',
				last: '<i class="fal fa-chevron-double-right"></i>',
				next: '<i class="fal fa-chevron-right"></i>',
				previous: '<i class="fal fa-chevron-left"></i>'
			}
		},
		buttons: [
			{
				extend: 'copyHtml5',
				text: '<i class="fal fa-copy mr-1"></i> Copy',
				className: 'btn-primary btn-sm mr-1',
				exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
			},
			{
				extend: 'excelHtml5',
				text: '<i class="fal fa-file-excel mr-1"></i> Excel',
				className: 'btn-primary btn-sm mr-1',
				exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
			},
			{
				extend: 'csvHtml5',
				text: '<i class="fal fa-file-csv mr-1"></i> CSV',
				className: 'btn-primary btn-sm mr-1',
				exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
			},
			{
				extend: 'pdfHtml5',
				text: '<i class="fal fa-file-pdf mr-1"></i> PDF',
				className: 'btn-primary btn-sm mr-1',
				exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
			},
			{
				extend: 'print',
				text: '<i class="fal fa-print mr-1"></i> Print',
				className: 'btn-primary btn-sm mr-1',
				exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
			},
			{
				text: '<i class="fal fa-sync mr-1"></i> Refresh',
				className: 'btn-primary btn-sm',
				action: function(e, dt) { dt.ajax.reload(null, false); }
			}
		],
		drawCallback: function() {
			if (isInitialLoad) {
				isInitialLoad = false;
				hideLoader();
			}
			if ($.fn.tooltip) { $('[data-toggle="tooltip"]').tooltip(); }
		}
	});

	$('#filter_classification').on('change', function() {
		table.ajax.reload();
	});

	$('#exportExcelBtn').on('click', function(e) {
		e.preventDefault();
		var classificationId = $('#filter_classification').val() || '0';
		window.location.href = "<?php echo ADMIN_URL; ?>amountrate/export_excel/" + classificationId;
	});

	$(document).on('click', '.btn-status-toggle', function(e) {
		e.preventDefault();
		var url = $(this).data('url');
		var go = function() { window.location.href = url; };
		if (typeof window.sa4ConfirmAction === 'function') {
			window.sa4ConfirmAction({
				title: 'Change status?',
				text: 'Are you sure you want to change the status of this meter rate?',
				confirmButtonText: 'Yes, change it',
				confirmButtonColor: '#3085d6'
			}).then(function(ok) { if (ok) { go(); } });
		} else if (confirm('Are you sure you want to change the status?')) {
			go();
		}
	});
});
</script>
</body>
</html>
