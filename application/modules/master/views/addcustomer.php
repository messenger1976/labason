<?php
	$income1 = $this->my_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->my_model->get_income_monthlycustomer();
	extract($income2);
	$intotal = $total1 + $total2;

	$expense1 = $this->my_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->my_model->get_outcome_payroll();
	extract($expense2);
	$extotal = $extotal1 + $extotal2;

	$total_customer = $this->my_model->total_customer();
	extract($total_customer);
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addcustomer/search/">Search Customer</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-users"></i>
			Manage <span class="fw-300">Customers</span>
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
					<?php echo (int) $count_id; ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#1dc9b7" sparkHeight="32px" sparkBarWidth="5px" values="2,5,3,7,4,6,3,8,5,4,6"></span>
		</div>
	</div>

				<!-- widget grid -->
				<section id="widget-grid" class="">
					<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
					<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">

					<!-- row -->
					<div class="row">
				
						<!-- NEW WIDGET START -->
						<div class="col-xl-12">
				
							<div id="panel-customers" class="panel">
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
											<div class="col-sm-6 col-md-9 col-lg-10 text-right">
												<a href="<?php echo ADMIN_URL; ?>addcustomer/add/" class="btn btn-success btn-sm waves-effect waves-themed">
													<i class="fal fa-plus mr-1"></i> Add Customer
												</a>
											</div>
										</div>
										<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
											<thead>
												<tr>
													<th style="width:28px;"><input type="checkbox" id="dt_select_all" class="ace" /></th>
													<th>S No</th>
													<th>Customer ID</th>
													<th>Name</th>
													<th>Address</th>
													<th>Meter Number</th>
													<th>Zone</th>
													<th>Classification</th>
													<th>Status</th>
													<th style="width:120px;">Action</th>
												</tr>
											</thead>
											<tbody>
												<!-- Data will be loaded via AJAX -->
											</tbody>
										</table>
									</div>
								</div>
							</div>
				
						</div>
						<!-- WIDGET END -->
				
					</div>
				
					<!-- end row -->

				</section>
				<!-- end widget grid -->

</main>
		
		<!-- Loading Modal Overlay -->
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
		
		<!-- Modal for Viewing Customer Details -->
		<div class="modal fade" id="viewCustomerModal" tabindex="-1" role="dialog" aria-labelledby="viewCustomerModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="viewCustomerModalLabel">
							<i class="fal fa-eye mr-1"></i> Customer Details
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true"><i class="fal fa-times"></i></span>
						</button>
					</div>
					<div class="modal-body" id="viewCustomerModalBody">
						<div class="text-center py-4">
							<div class="spinner-border text-primary mb-2" role="status"></div>
							<p class="mb-0 text-muted">Loading customer details...</p>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal for Editing Customer Details -->
		<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="editCustomerModalLabel">
							<i class="fal fa-edit mr-1"></i> Edit Customer
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true"><i class="fal fa-times"></i></span>
						</button>
					</div>
					<div class="modal-body" id="editCustomerModalBody">
						<div class="text-center py-4">
							<div class="spinner-border text-primary mb-2" role="status"></div>
							<p class="mb-0 text-muted">Loading customer details...</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal for Setting Customer Password -->
		<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="passwordModalLabel">
							<i class="fal fa-key mr-1"></i> Set Customer Login Password
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true"><i class="fal fa-times"></i></span>
						</button>
					</div>
					<div class="modal-body">
						<form id="passwordForm">
							<input type="hidden" id="modal_customer_id" name="customer_id" value="">
							<div class="form-group">
								<label class="form-label" for="modal_customer_code">Customer ID</label>
								<input type="text" class="form-control" id="modal_customer_code" readonly>
							</div>
							<div class="form-group">
								<label class="form-label" for="modal_password">Password <span class="text-danger">*</span></label>
								<input type="password" class="form-control" id="modal_password" name="password" required>
								<small class="form-text text-muted">Enter a password for customer login</small>
							</div>
							<div class="form-group">
								<label class="form-label" for="modal_confirm_password">Confirm Password <span class="text-danger">*</span></label>
								<input type="password" class="form-control" id="modal_confirm_password" name="confirm_password" required>
							</div>
							<div id="password_error" class="alert alert-danger" style="display:none;"></div>
							<div id="password_success" class="alert alert-success" style="display:none;"></div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary waves-effect waves-themed" id="savePasswordBtn">Save Password</button>
					</div>
				</div>
			</div>
		</div>

		<?php include('footer.php');?>
		<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
		<script src="<?php echo base_url(); ?>sa4/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

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
	/* Slim top event bar on the listing panel while processing */
	#panel-customers.panel-loading::before {
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
	#panel-customers {
		position: relative;
	}
	@keyframes dt-panel-shimmer {
		0% { background-position: -40% 0; }
		100% { background-position: 140% 0; }
	}
</style>
<!-- PAGE RELATED PLUGIN(S) -->
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
				$('#panel-customers').addClass('panel-loading');
				$('.dataTables_wrapper').addClass('processing');
				startProgress();
			}

			function hideLoader() {
				completeProgress(function() {
					$('#datatable-loading-modal').removeClass('is-visible').fadeOut(180);
					$('#panel-customers').removeClass('panel-loading');
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
				order: [[6, 'asc'], [2, 'asc']],
				searchDelay: 999999,
				ajax: {
					url: "<?php echo ADMIN_URL; ?>addcustomer/get_datatable_data",
					type: "POST",
					data: function(d) {
						d.zone = ($('#filter_zone').length ? $('#filter_zone').val() : '') || 'all';
					}
				},
				columns: [
					{ data: 0, orderable: false, searchable: false, className: 'text-center' },
					{ data: 1, orderable: false, className: 'text-center' },
					{ data: 2, orderable: true },
					{ data: 3, orderable: true },
					{ data: 4, orderable: true },
					{ data: 5, orderable: true },
					{ data: 6, orderable: true },
					{ data: 7, orderable: true },
					{ data: 8, orderable: true, className: 'text-center' },
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
						exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] }
					},
					{
						extend: 'excelHtml5',
						text: '<i class="fal fa-file-excel mr-1"></i> Excel',
						className: 'btn-primary btn-sm mr-1',
						exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] }
					},
					{
						extend: 'csvHtml5',
						text: '<i class="fal fa-file-csv mr-1"></i> CSV',
						className: 'btn-primary btn-sm mr-1',
						exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] }
					},
					{
						extend: 'pdfHtml5',
						text: '<i class="fal fa-file-pdf mr-1"></i> PDF',
						className: 'btn-primary btn-sm mr-1',
						exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] }
					},
					{
						extend: 'print',
						text: '<i class="fal fa-print mr-1"></i> Print',
						className: 'btn-primary btn-sm mr-1',
						exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] }
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
				}
			});

			// Search only on Enter or blur (keeps previous UX)
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

			$('#dt_select_all').on('change', function() {
				var checked = $(this).is(':checked');
				$('#dt_basic tbody input[name="delete_ids[]"]').prop('checked', checked);
			});
		});
		</script>

		
<script>
$(document).ready(function() {
	// Handle View Customer Button Click
	$(document).on('click', '.view-customer-btn', function(e) {
		e.preventDefault();
		var customerId = $(this).data('customer-id');
		
		// Show loading state
		$('#viewCustomerModalBody').html('<div class="text-center py-4"><div class="spinner-border text-primary mb-2" role="status"></div><p class="mb-0 text-muted">Loading customer details...</p></div>');
		
		// Show modal
		$('#viewCustomerModal').modal('show');
		
		// Load customer details via AJAX
		$.ajax({
			url: '<?php echo ADMIN_URL;?>addcustomer/view_ajax/' + customerId,
			type: 'GET',
			success: function(response) {
				$('#viewCustomerModalBody').html(response);
			},
			error: function(xhr, status, error) {
				$('#viewCustomerModalBody').html('<div class="alert alert-danger">Error loading customer details. Please try again.</div>');
			}
		});
	});

	// Handle Edit Customer Button Click
	$(document).on('click', '.edit-customer-btn', function(e) {
		e.preventDefault();
		var customerId = $(this).data('customer-id');
		
		// Show loading state
		$('#editCustomerModalBody').html('<div class="text-center py-4"><div class="spinner-border text-primary mb-2" role="status"></div><p class="mb-0 text-muted">Loading customer details...</p></div>');
		
		// Show modal
		$('#editCustomerModal').modal('show');
		
		// Load customer edit form via AJAX
		$.ajax({
			url: '<?php echo ADMIN_URL;?>addcustomer/edit_ajax/' + customerId,
			type: 'GET',
			success: function(response) {
				$('#editCustomerModalBody').html(response);
			},
			error: function(xhr, status, error) {
				$('#editCustomerModalBody').html('<div class="alert alert-danger">Error loading customer details. Please try again.</div>');
			}
		});
	});

	// Handle Edit Customer Form Submission
	$(document).on('submit', '#editCustomerForm', function(e) {
		e.preventDefault();
		var form = $(this);
		var newCustomerId = $.trim($('#customer_id_modal').val());
		var oldCustomerId = $.trim($('#original_customer_id_modal').val());

		if (newCustomerId !== oldCustomerId) {
			var confirmMsg = 'You have changed the Customer-Id from "' + oldCustomerId + '" to "' + newCustomerId + '".\n\n'
				+ 'This will update all meter readings, payment transactions, and leaking ledger records for this customer.\n\n'
				+ 'Do you want to continue?';
			if (!confirm(confirmMsg)) {
				return false;
			}
		}

		var formData = new FormData(form[0]);
		var submitBtn = form.find('button[type="submit"]');
		var originalBtnText = submitBtn.text();
		
		// Disable submit button
		submitBtn.prop('disabled', true).text('Updating...');
		
		// Clear previous messages
		$('#edit_customer_msg').hide().html('');
		
		$.ajax({
			url: '<?php echo ADMIN_URL;?>addcustomer/update_ajax',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function(response) {
				if(response.success) {
					$('#edit_customer_msg').removeClass('alert-danger').addClass('alert alert-success').html(response.message).show();
					// Reload the DataTable after a short delay
					setTimeout(function() {
						$('#dt_basic').DataTable().ajax.reload();
						$('#editCustomerModal').modal('hide');
					}, 1500);
				} else {
					$('#edit_customer_msg').removeClass('alert-success').addClass('alert alert-danger').html(response.message).show();
					submitBtn.prop('disabled', false).text(originalBtnText);
				}
			},
			error: function(xhr, status, error) {
				var errorMsg = 'An error occurred while updating the customer.';
				if(xhr.responseJSON && xhr.responseJSON.message) {
					errorMsg = xhr.responseJSON.message;
				}
				$('#edit_customer_msg').removeClass('alert-success').addClass('alert alert-danger').html(errorMsg).show();
				submitBtn.prop('disabled', false).text(originalBtnText);
			}
		});
	});

	// Handle Set Password Button Click
	$(document).on('click', '.set-password-btn', function(e) {
		e.preventDefault();
		var customerId = $(this).data('customer-id');
		var customerCode = $(this).data('customer-code');
		
		$('#modal_customer_id').val(customerId);
		$('#modal_customer_code').val(customerCode);
		$('#modal_password').val('');
		$('#modal_confirm_password').val('');
		$('#password_error').hide().text('');
		$('#password_success').hide().text('');
		
		$('#passwordModal').modal('show');
	});

	// Handle Save Password Button
	$('#savePasswordBtn').on('click', function() {
		var customerId = $('#modal_customer_id').val();
		var password = $('#modal_password').val();
		var confirmPassword = $('#modal_confirm_password').val();
		
		// Reset messages
		$('#password_error').hide().text('');
		$('#password_success').hide().text('');
		
		// Validation
		if(!password || password.length < 3) {
			$('#password_error').text('Password must be at least 3 characters long.').show();
			return;
		}
		
		if(password !== confirmPassword) {
			$('#password_error').text('Passwords do not match.').show();
			return;
		}
		
		// Disable button during save
		$('#savePasswordBtn').prop('disabled', true).text('Saving...');
		
		// Send AJAX request
		$.ajax({
			url: '<?php echo ADMIN_URL;?>addcustomer/save_customer_password',
			type: 'POST',
			data: {
				customer_id: customerId,
				password: password
			},
			dataType: 'json',
			success: function(response) {
				if(response.success) {
					$('#password_success').text(response.message).show();
					setTimeout(function() {
						$('#passwordModal').modal('hide');
						// Optionally reload the table or show a success message
					}, 1500);
				} else {
					$('#password_error').text(response.message).show();
					$('#savePasswordBtn').prop('disabled', false).text('Save Password');
				}
			},
			error: function(xhr, status, error) {
				var errorMsg = 'An error occurred while saving the password.';
				if(xhr.responseJSON && xhr.responseJSON.message) {
					errorMsg = xhr.responseJSON.message;
				} else if(xhr.responseText) {
					try {
						var response = JSON.parse(xhr.responseText);
						if(response.message) {
							errorMsg = response.message;
						}
					} catch(e) {
						errorMsg = 'Server error: ' + xhr.status + ' ' + error;
					}
				}
				$('#password_error').text(errorMsg).show();
				$('#savePasswordBtn').prop('disabled', false).text('Save Password');
			}
		});
	});

	// Reset form when modal is closed
	$('#passwordModal').on('hidden.bs.modal', function () {
		$('#passwordForm')[0].reset();
		$('#password_error').hide().text('');
		$('#password_success').hide().text('');
		$('#savePasswordBtn').prop('disabled', false).text('Save Password');
	});
});

$(document).on('click','.print_button',function(e){
	var buttonid = $(this).attr('id');
	var paybtnid = $(this).data('print-val-id');
	var cusid = $('#id_'+paybtnid).val();
	var customer = $('#customerid_'+paybtnid).val();
	var biliingplans = $('#billingplansid_'+paybtnid).val();
	if(paybtnid != '' && customer != '' && biliingplans == ''){
				var url = '<?php echo ADMIN_URL;?>addcustomer/customer_register_form/'+cusid+'/'+customer+'/'+biliingplans;
				window.open( url , "popupWindow", "width=1024,height=600,scrollbars=yes");	
	}
	else if(paybtnid != '' && customer != '' && biliingplans != ''){
		    var url = '<?php echo ADMIN_URL;?>addcustomer/customer_register_form_monthly/'+cusid+'/'+customer+'/'+biliingplans;
			window.open( url , "popupWindow", "width=1024,height=600,scrollbars=yes");	
	}
});
</script>			