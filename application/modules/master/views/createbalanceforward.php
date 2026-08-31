<?php
	$income1 = $this->comm_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->comm_model->get_income_monthlycustomer();
	extract($income2);
	$intotal = (isset($total1) ? (float) $total1 : 0) + (isset($total2) ? (float) $total2 : 0);

	$expense1 = $this->comm_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->comm_model->get_outcome_payroll();
	extract($expense2);
	$extotal = (isset($extotal1) ? (float) $extotal1 : 0) + (isset($extotal2) ? (float) $extotal2 : 0);

	$total_customer = $this->comm_model->total_customer();
	extract($total_customer);

	$billingperiod = (isset($billingperiod) && is_array($billingperiod)) ? $billingperiod : array();
	$zone_listing = (isset($zone_listing) && is_array($zone_listing)) ? $zone_listing : array();
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>createbalanceforward">Create Balance Forward</a></li>
		<li class="breadcrumb-item active">Process</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-exchange"></i>
			Manage <span class="fw-300">Create Balance Forward</span>
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

	<?php if (!empty($msg)) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<?php echo $msg; ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-balanceforward" class="panel">
				<div class="panel-hdr">
					<h2>Create Balance Forward <span class="fw-300"><i>Process</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<div class="row mb-3">
							<div class="col-md-12 text-md-right">
								<button type="button" class="btn btn-info btn-sm mb-1" id="display">
									<i class="fal fa-eye mr-1"></i> Display
								</button>
								<button type="button" class="btn btn-primary btn-sm mb-1" id="process">
									<i class="fal fa-play mr-1"></i> Process
								</button>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label" for="currentbillingperiod">Current Billing Period</label>
									<select class="form-control" name="currentbillingperiod" id="currentbillingperiod" required>
										<option value="">--Select--</option>
										<?php foreach ($billingperiod as $value) { ?>
										<option value="<?php echo htmlspecialchars($value['bp_period_month'].' '.$value['bp_period_year']); ?>">
											<?php echo htmlspecialchars($value['month_name'].' '.$value['bp_period_year']); ?>
										</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label" for="forwardbillingperiod">Next Billing Period</label>
									<select class="form-control" name="forwardbillingperiod" id="forwardbillingperiod" required>
										<option value="">--Select--</option>
										<?php foreach ($billingperiod as $value) { ?>
										<option value="<?php echo htmlspecialchars($value['bp_period_month'].' '.$value['bp_period_year']); ?>">
											<?php echo htmlspecialchars($value['month_name'].' '.$value['bp_period_year']); ?>
										</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label" for="zone_listing">Zone</label>
									<select class="form-control" name="zone_listing" id="zone_listing" required>
										<option value="">--All--</option>
										<?php foreach ($zone_listing as $value) { ?>
										<option value="<?php echo $value['id']; ?>"><?php echo htmlspecialchars($value['zone']); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>

						<div id="progressBarDiv" class="mt-3" style="display:none;">
							<div class="p-3 border rounded bg-faded">
								<h5 class="mb-3">Processing Balance Forward...</h5>
								<div class="progress progress-lg mb-2" style="height: 1.75rem;">
									<div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
										<span id="progressText">0%</span>
									</div>
								</div>
								<p id="currentCustomerText" class="mb-1 fw-500"></p>
								<p id="progressDetails" class="mb-0 text-muted fs-sm"></p>
							</div>
						</div>

						<div id="balanceForwardResultsDiv" class="mt-3"></div>
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

	function initResultsTable() {
		if (!$.fn.DataTable || !$('#dt_basic').length) { return; }
		if ($.fn.DataTable.isDataTable('#dt_basic')) {
			$('#dt_basic').DataTable().destroy();
		}
		$('#dt_basic').DataTable({
			responsive: true,
			pageLength: 25,
			lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
			language: {
				search: '',
				searchPlaceholder: 'Search results...',
				lengthMenu: '_MENU_',
				info: 'Showing _START_ to _END_ of _TOTAL_ records',
				zeroRecords: 'No matching records'
			}
		});
	}

	$('#display').on('click', function(evt) {
		evt.preventDefault();
		var billingperiodforward = $('#forwardbillingperiod').val();
		var zone_id = $('#zone_listing').val();

		if (billingperiodforward == '' || zone_id == '') {
			Swal.fire({
				icon: 'warning',
				title: 'Validation Error',
				text: 'Please select Next Billing Period and Zone to display results!',
				confirmButtonColor: '#3085d6'
			});
			return false;
		}
		if (typeof showSpinner === 'function') { showSpinner(); }
		loadBalanceForwardResults();
	});

	$('#process').on('click', function(evt) {
		evt.preventDefault();
		var billingperiodforward = $('#forwardbillingperiod').val();
		var currentbillingperiod = $('#currentbillingperiod').val();
		var zone_id = $('#zone_listing').val();

		if (billingperiodforward == '' || currentbillingperiod == '' || zone_id == '') {
			Swal.fire({
				icon: 'warning',
				title: 'Validation Error',
				text: 'Please select all required fields!',
				confirmButtonColor: '#3085d6'
			});
			return false;
		}

		Swal.fire({
			title: 'Confirm Balance Forward Processing',
			text: 'Are you sure you want to continue processing balance forward?',
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Process it!',
			cancelButtonText: 'Cancel'
		}).then(function(result) {
			if (typeof window.sa4SwalConfirmed === 'function' ? window.sa4SwalConfirmed(result) : (result && (result.isConfirmed || result.value))) {
				startBatchProcessing(billingperiodforward, currentbillingperiod, zone_id);
			}
		});
	});

	function startBatchProcessing(billingperiodforward, currentbillingperiod, zone_id) {
		$('#progressBarDiv').show();
		$('#balanceForwardResultsDiv').html('');
		updateProgressBar(0, 0, '', 0);

		$.ajax({
			url: <?php echo json_encode(base_url() . 'master/createbalanceforward/clearbatch'); ?>,
			type: 'POST',
			dataType: 'json'
		}).always(function() {
			$.ajax({
				url: <?php echo json_encode(base_url() . 'master/createbalanceforward/processbalanceforward'); ?>,
				type: 'POST',
				data: {
					billingperiodforward: billingperiodforward,
					currentbillingperiod: currentbillingperiod,
					zone_listing: zone_id
				},
				dataType: 'json',
				success: function(response) {
					if (response && response.success) {
						processNextBatch();
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: (response && response.message) ? response.message : 'Failed to start batch processing.',
							confirmButtonColor: '#3085d6'
						});
						$('#progressBarDiv').hide();
					}
				},
				error: function(xhr) {
					var msg = 'An error occurred while initializing batch processing.';
					try {
						if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
						else if (xhr.responseText) msg = xhr.responseText.substring(0, 200);
					} catch (e) {}
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: msg,
						confirmButtonColor: '#3085d6'
					});
					$('#progressBarDiv').hide();
				}
			});
		});
	}

	function processNextBatch() {
		$.ajax({
			url: <?php echo json_encode(base_url() . 'master/createbalanceforward/processbatch'); ?>,
			type: 'POST',
			dataType: 'json',
			timeout: 90000,
			success: function(response) {
				if (response.success) {
					updateProgressBar(response.total, response.processed, response.current_customer, response.percentage);
					if (response.complete) {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: 'Balance Forward processed successfully!',
							confirmButtonColor: '#3085d6'
						}).then(function() {
							$('#progressBarDiv').hide();
							loadBalanceForwardResults();
						});
					} else {
						setTimeout(processNextBatch, 500);
					}
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: response.message || 'Error processing batch',
						confirmButtonColor: '#3085d6'
					});
					$('#progressBarDiv').hide();
				}
			},
			error: function() {
				setTimeout(processNextBatch, 1000);
			}
		});
	}

	function updateProgressBar(total, processed, currentCustomer, percentage) {
		if (percentage === undefined || percentage === null) {
			percentage = total > 0 ? Math.round((processed / total) * 100) : 0;
		}
		$('#progressBar').css('width', percentage + '%').attr('aria-valuenow', percentage);
		$('#progressText').text(percentage + '% (' + processed + ' / ' + total + ')');
		$('#progressDetails').text('Processed: ' + processed + ' of ' + total + ' customers');
		if (currentCustomer) {
			$('#currentCustomerText').text('Processing: ' + currentCustomer);
		} else if (processed == total && total > 0) {
			$('#currentCustomerText').text('Processing completed!');
		}
	}

	function loadBalanceForwardResults() {
		var billingperiodforward = $('#forwardbillingperiod').val();
		var zone_id = $('#zone_listing').val();
		if (typeof showSpinner === 'function') { showSpinner(); }
		$.ajax({
			type: 'POST',
			url: <?php echo json_encode(base_url() . 'master/createbalanceforward/getbalanceforwardresults'); ?>,
			data: {
				billingperiodforward: billingperiodforward,
				zone_listing: zone_id
			},
			complete: function(data) {
				try {
					$('#balanceForwardResultsDiv').html($.trim(data.responseText));
					initResultsTable();
				} catch (e) {
					$('#balanceForwardResultsDiv').html('<div class="alert alert-danger">Failed to load results. Please try again.</div>');
				} finally {
					if (typeof hideSpinner === 'function') {
						setTimeout(hideSpinner, 500);
					}
				}
			}
		});
	}
})(jQuery);
</script>
