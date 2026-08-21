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
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addcustomer">Customers</a></li>
		<li class="breadcrumb-item active">Statement of Account</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-file-alt"></i>
			Statement of <span class="fw-300">Account</span>
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

	<section id="widget-grid" class="">
		<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">

		<div class="row">
			<div class="col-xl-12">
				<div id="panel-soa-customers" class="panel">
					<div class="panel-hdr">
						<h2>
							Customer <span class="fw-300"><i>Listing</i></span>
						</h2>
						<div class="panel-toolbar">
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<div class="row mb-3 align-items-end">
								<div class="col-sm-6 col-md-3 col-lg-2">
									<label class="form-label" for="filter_zone">Filter by Zone</label>
									<select id="filter_zone" name="filter_zone" class="form-control form-control-sm">
										<option value="all">All</option>
										<?php if(!empty($zone)) { foreach($zone as $z) { ?>
										<option value="<?php echo $z['id']; ?>"><?php echo htmlspecialchars($z['zone']); ?></option>
										<?php } } ?>
									</select>
								</div>
							</div>
							<?php
								$soa_employees = (isset($employee) && is_array($employee)) ? $employee : array();
							?>
							<div class="row mb-3">
								<div class="col-md-4">
									<label class="form-label" for="soa_sign_prepared">Prepared By</label>
									<select id="soa_sign_prepared" name="soa_sign_prepared" class="form-control form-control-sm">
										<option value="">-- Select --</option>
										<?php foreach ($soa_employees as $emp) {
											$emp_name = strtoupper(trim((isset($emp['first_name']) ? $emp['first_name'] : '').' '.(isset($emp['middle_name']) ? $emp['middle_name'] : '').' '.(isset($emp['last_name']) ? $emp['last_name'] : '')));
											$emp_title = isset($emp['jobtitle']) ? $emp['jobtitle'] : '';
											$emp_label = $emp_name.($emp_title !== '' ? ' - '.$emp_title : '');
										?>
										<option value="<?php echo (int) $emp['id']; ?>" data-name="<?php echo htmlspecialchars($emp_name, ENT_QUOTES, 'UTF-8'); ?>" data-title="<?php echo htmlspecialchars($emp_title, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($emp_label, ENT_QUOTES, 'UTF-8'); ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-4">
									<label class="form-label" for="soa_sign_verified">Verified Correct</label>
									<select id="soa_sign_verified" name="soa_sign_verified" class="form-control form-control-sm">
										<option value="">-- Select --</option>
										<?php foreach ($soa_employees as $emp) {
											$emp_name = strtoupper(trim((isset($emp['first_name']) ? $emp['first_name'] : '').' '.(isset($emp['middle_name']) ? $emp['middle_name'] : '').' '.(isset($emp['last_name']) ? $emp['last_name'] : '')));
											$emp_title = isset($emp['jobtitle']) ? $emp['jobtitle'] : '';
											$emp_label = $emp_name.($emp_title !== '' ? ' - '.$emp_title : '');
										?>
										<option value="<?php echo (int) $emp['id']; ?>" data-name="<?php echo htmlspecialchars($emp_name, ENT_QUOTES, 'UTF-8'); ?>" data-title="<?php echo htmlspecialchars($emp_title, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($emp_label, ENT_QUOTES, 'UTF-8'); ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-4">
									<label class="form-label" for="soa_sign_approved">Approved</label>
									<select id="soa_sign_approved" name="soa_sign_approved" class="form-control form-control-sm">
										<option value="">-- Select --</option>
										<?php foreach ($soa_employees as $emp) {
											$emp_name = strtoupper(trim((isset($emp['first_name']) ? $emp['first_name'] : '').' '.(isset($emp['middle_name']) ? $emp['middle_name'] : '').' '.(isset($emp['last_name']) ? $emp['last_name'] : '')));
											$emp_title = isset($emp['jobtitle']) ? $emp['jobtitle'] : '';
											$emp_label = $emp_name.($emp_title !== '' ? ' - '.$emp_title : '');
										?>
										<option value="<?php echo (int) $emp['id']; ?>" data-name="<?php echo htmlspecialchars($emp_name, ENT_QUOTES, 'UTF-8'); ?>" data-title="<?php echo htmlspecialchars($emp_title, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($emp_label, ENT_QUOTES, 'UTF-8'); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
								<thead>
									<tr>
										<th>S No</th>
										<th>Customer ID</th>
										<th>Name</th>
										<th>Address</th>
										<th>Meter Number</th>
										<th>Zone</th>
										<th>Classification</th>
										<th>Status</th>
										<th class="text-right">SOA Balance</th>
										<th style="width:90px;">Action</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<div id="datatable-loading-modal" class="dt-loading-modal" style="display: none;" aria-live="polite" aria-busy="true">
	<div class="dt-loading-backdrop"></div>
	<div class="dt-loading-card panel shadow-3">
		<div class="panel-hdr bg-primary-600 bg-primary-gradient">
			<h2 class="text-white">
				Loading <span class="fw-300">Customers</span>
			</h2>
		</div>
		<div class="panel-container show">
			<div class="panel-content text-center py-4 px-4">
				<div class="mb-3">
					<div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
						<span class="sr-only">Loading...</span>
					</div>
				</div>
				<h5 class="mb-1 fw-500" id="dt-loading-title">Fetching customer records</h5>
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

<?php include('footer.php');?>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
</body>
</html>
<style>
	.dt-loading-modal {
		position: fixed;
		inset: 0;
		z-index: 1055;
		display: none;
		align-items: center;
		justify-content: center;
	}
	.dt-loading-modal.is-visible {
		display: flex !important;
	}
	.dt-loading-backdrop {
		position: absolute;
		inset: 0;
		background: rgba(33, 37, 41, 0.45);
		backdrop-filter: blur(3px);
	}
	.dt-loading-card {
		position: relative;
		z-index: 1;
		width: min(420px, calc(100vw - 2rem));
		margin: 0;
		border: 0;
		overflow: hidden;
	}
	.dt-loading-card .panel-hdr {
		border-bottom: 0;
	}
	.dt-loading-card .progress {
		height: 1rem;
		border-radius: 999px;
		background: rgba(136, 106, 181, 0.15);
		overflow: hidden;
	}
	.dt-loading-card .progress-bar {
		transition: width 0.25s ease;
		border-radius: 999px;
	}
	.dataTables_wrapper {
		position: relative;
	}
	.dataTables_wrapper.processing {
		opacity: 0.55;
		pointer-events: none;
		filter: grayscale(0.15);
	}
	#panel-soa-customers.panel-loading::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		height: 3px;
		width: 100%;
		z-index: 5;
		background: linear-gradient(90deg, transparent, var(--theme-primary, #886ab5), transparent);
		background-size: 40% 100%;
		animation: dt-panel-shimmer 1.1s linear infinite;
	}
	#panel-soa-customers {
		position: relative;
	}
	@keyframes dt-panel-shimmer {
		0% { background-position: -40% 0; }
		100% { background-position: 140% 0; }
	}
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
		$('#dt-loading-progress-bar')
			.css('width', progressValue + '%')
			.attr('aria-valuenow', Math.round(progressValue));
		$('#dt-loading-percent').text(Math.round(progressValue) + '%');
	}

	function startProgress() {
		clearInterval(progressTimer);
		setProgress(8);
		$('#dt-loading-title').text('Fetching customer records');
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
		$('#dt-loading-subtitle').text('Rendering customer listing…');
		setTimeout(done, 220);
	}

	function showLoader() {
		$('#datatable-loading-modal').addClass('is-visible').show();
		$('#panel-soa-customers').addClass('panel-loading');
		$('.dataTables_wrapper').addClass('processing');
		startProgress();
	}

	function hideLoader() {
		completeProgress(function() {
			$('#datatable-loading-modal').removeClass('is-visible').fadeOut(180);
			$('#panel-soa-customers').removeClass('panel-loading');
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
		order: [[5, 'asc'], [1, 'asc']],
		searchDelay: 999999,
		ajax: {
			url: "<?php echo ADMIN_URL; ?>statementofaccountlist/get_datatable_data",
			type: "POST",
			timeout: 120000,
			data: function(d) {
				d.zone = ($('#filter_zone').length ? $('#filter_zone').val() : '') || 'all';
			},
			error: function(xhr, error, thrown) {
				hideLoader();
				console.error('SOA list DataTables error', error, thrown);
			}
		},
		columns: [
			{ data: 0, orderable: false, className: 'text-center' },
			{ data: 1, orderable: true },
			{ data: 2, orderable: true },
			{ data: 3, orderable: true },
			{ data: 4, orderable: true },
			{ data: 5, orderable: true },
			{ data: 6, orderable: true },
			{ data: 7, orderable: true, className: 'text-center' },
			{ data: 8, orderable: false, searchable: false, className: 'text-right' },
			{ data: 9, orderable: false, searchable: false, className: 'text-center' }
		],
		dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
		language: {
			processing: '',
			search: '',
			searchPlaceholder: 'Search customers...',
			lengthMenu: '_MENU_',
			info: 'Showing _START_ to _END_ of _TOTAL_ customers',
			infoEmpty: 'No customers found',
			zeroRecords: 'No matching customers',
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
				exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] }
			},
			{
				extend: 'excelHtml5',
				text: '<i class="fal fa-file-excel mr-1"></i> Excel',
				className: 'btn-primary btn-sm mr-1',
				exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] }
			},
			{
				extend: 'csvHtml5',
				text: '<i class="fal fa-file-csv mr-1"></i> CSV',
				className: 'btn-primary btn-sm mr-1',
				exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] }
			},
			{
				extend: 'pdfHtml5',
				text: '<i class="fal fa-file-pdf mr-1"></i> PDF',
				className: 'btn-primary btn-sm mr-1',
				exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] }
			},
			{
				extend: 'print',
				text: '<i class="fal fa-print mr-1"></i> Print',
				className: 'btn-primary btn-sm mr-1',
				exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] }
			},
			{
				text: '<i class="fal fa-sync mr-1"></i> Refresh',
				className: 'btn-primary btn-sm',
				action: function(e, dt) {
					dt.ajax.reload(null, false);
				}
			}
		],
		drawCallback: function() {
			if (isInitialLoad) {
				isInitialLoad = false;
				hideLoader();
			}
			loadSoaBalances();
		}
	});

	function formatSoaBalance(bal) {
		var n = parseFloat(bal);
		if (isNaN(n)) {
			return '<span class="text-muted">—</span>';
		}
		var cls = (n > 0.005) ? 'text-danger' : ((n < -0.005) ? 'text-success' : '');
		return '<span class="' + cls + '">' + n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</span>';
	}

	function loadSoaBalances() {
		var ids = [];
		$('#dt_basic .js-soa-bal').each(function() {
			var cid = $.trim($(this).attr('data-cid') || '');
			if (cid && $.inArray(cid, ids) === -1) {
				ids.push(cid);
			}
		});
		if (!ids.length) {
			return;
		}
		var chunkSize = 10;
		var i = 0;
		function nextChunk() {
			if (i >= ids.length) {
				return;
			}
			var chunk = ids.slice(i, i + chunkSize);
			i += chunkSize;
			$.ajax({
				url: "<?php echo ADMIN_URL; ?>statementofaccountlist/get_balances",
				type: 'POST',
				dataType: 'json',
				timeout: 120000,
				data: { customer_ids: chunk }
			}).done(function(res) {
				if (res && res.balances) {
					$('#dt_basic .js-soa-bal').each(function() {
						var $el = $(this);
						var cid = $.trim($el.attr('data-cid') || '');
						if (cid && Object.prototype.hasOwnProperty.call(res.balances, cid)) {
							$el.replaceWith(formatSoaBalance(res.balances[cid]));
						}
					});
				}
			}).always(function() {
				nextChunk();
			});
		}
		nextChunk();
	}

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

	$('#filter_zone').on('change', function() {
		table.ajax.reload();
	});

	var soaSignFields = ['soa_sign_prepared', 'soa_sign_verified', 'soa_sign_approved'];
	var soaSignCookieDays = 365;

	function soaGetCookie(name) {
		var match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()[\]\\/+^])/g, '\\$1') + '=([^;]*)'));
		return match ? decodeURIComponent(match[1]) : '';
	}

	function soaSetCookie(name, value) {
		var expires = new Date();
		expires.setTime(expires.getTime() + (soaSignCookieDays * 24 * 60 * 60 * 1000));
		document.cookie = name + '=' + encodeURIComponent(value) + ';expires=' + expires.toUTCString() + ';path=/;SameSite=Lax';
	}

	function soaSaveSignCookies() {
		$.each(soaSignFields, function(_, fieldId) {
			var $sel = $('#' + fieldId);
			var $opt = $sel.find('option:selected');
			soaSetCookie(fieldId, $.trim($sel.val()));
			soaSetCookie(fieldId + '_name', $.trim($opt.data('name') || ''));
			soaSetCookie(fieldId + '_title', $.trim($opt.data('title') || ''));
		});
	}

	$.each(soaSignFields, function(_, fieldId) {
		var savedId = soaGetCookie(fieldId);
		if (savedId) {
			$('#' + fieldId).val(savedId);
		}
		$('#' + fieldId).on('change', soaSaveSignCookies);
	});
	soaSaveSignCookies();

	$(document).on('click', '#dt_basic a[title="Open SOA"]', soaSaveSignCookies);
});
</script>
