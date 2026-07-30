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

	$classification = (isset($classification) && is_array($classification)) ? $classification : array();
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>amountrate">Meter Rate</a></li>
		<li class="breadcrumb-item active">Add</li>
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

	<?php if (!empty($msg)) { ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<?php echo $msg; ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-amountrate-add" class="panel">
				<div class="panel-hdr">
					<h2>Meter Rate <span class="fw-300"><i>Generate</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="form-label" for="classification">Classification <span class="text-danger">*</span></label>
										<select class="form-control" name="classification" id="classification" required>
											<option value="">-- Select --</option>
											<?php foreach ($classification as $value) { ?>
											<option value="<?php echo (int) $value['class_id']; ?>"><?php echo htmlspecialchars($value['class_name']); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="start">Start <span class="text-danger">*</span></label>
										<input type="number" class="form-control" id="start" name="start" min="0" required>
										<?php echo form_error('start'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="end">End <span class="text-danger">*</span></label>
										<input type="number" class="form-control" id="end" name="end" min="0" required>
										<?php echo form_error('end'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="rate">Rate <span class="text-danger">*</span></label>
										<input type="number" class="form-control" id="rate" name="rate" step="0.01" placeholder="0.00" min="0" required>
										<?php echo form_error('rate'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="incre">Consumption Rate</label>
										<input type="number" class="form-control" id="incre" name="incre" step="0.01" placeholder="0.00" min="0">
										<?php echo form_error('incre'); ?>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="apply_increment" name="apply_increment" value="1" checked>
											<label class="custom-control-label" for="apply_increment">
												<strong>Increment?</strong> If checked, rate increments by Consumption Rate for each cubic meter. If unchecked, the same rate is used for all.
											</label>
										</div>
									</div>
								</div>
							</div>

							<div id="progressBarDiv" class="mb-3" style="display:none;">
								<div class="progress progress-lg">
									<div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
										role="progressbar" style="width:0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
										<span id="progressText">0%</span>
									</div>
								</div>
								<div id="progressStatus" class="text-center mt-2 fw-500">Processing records...</div>
							</div>

							<div class="row mt-3">
								<div class="col-md-12">
									<a href="<?php echo ADMIN_URL; ?>amountrate" class="btn btn-secondary waves-effect waves-themed">
										<i class="fal fa-times mr-1"></i> Cancel
									</a>
									<button type="button" class="btn btn-primary waves-effect waves-themed" id="submitBtn" name="add" value="Add">
										<i class="fal fa-cogs mr-1"></i> Generate
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
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

	function swalConfirmed(result) {
		if (typeof sa4SwalConfirmed === 'function') { return sa4SwalConfirmed(result); }
		return !!(result && (result.isConfirmed === true || result.value === true));
	}

	$('#start').on('change blur', function() {
		var startValue = parseInt($(this).val(), 10);
		var classificationId = $('#classification').val() || '';
		if (isNaN(startValue) || startValue < 0) { return; }
		if (startValue === 0) {
			$('#rate').val('0.00');
			return;
		}
		$.ajax({
			url: "<?php echo ADMIN_URL; ?>amountrate/get_rate_by_cubic_meter",
			type: "POST",
			headers: { 'X-Requested-With': 'XMLHttpRequest' },
			data: { cubic_meter: startValue - 1, classification_id: classificationId },
			dataType: 'json',
			success: function(response) {
				if (response.success && response.per_unit > 0) {
					$('#rate').val(parseFloat(response.per_unit).toFixed(2));
				} else {
					$('#rate').val('0.00');
				}
			},
			error: function() { $('#rate').val('0.00'); }
		});
	});

	$('#classification').on('change', function() {
		var startValue = parseInt($('#start').val(), 10);
		if (startValue && startValue > 0) { $('#start').trigger('change'); }
	});

	$('#submitBtn').on('click', function(e) {
		e.preventDefault();

		var classification = $('#classification').val();
		var start = $('#start').val();
		var end = $('#end').val();
		var rate = $('#rate').val();
		var incre = $('#incre').val() || '';
		var applyIncrement = $('#apply_increment').is(':checked') ? 1 : 0;

		var startStr = (start !== null && start !== undefined) ? String(start).trim() : '';
		var endStr = (end !== null && end !== undefined) ? String(end).trim() : '';
		var rateStr = (rate !== null && rate !== undefined) ? String(rate).trim() : '';

		var isClassificationEmpty = !classification || classification === '';
		var isStartEmpty = startStr === '';
		var isEndEmpty = endStr === '';
		var isRateEmpty = rateStr === '';

		if (isClassificationEmpty || isStartEmpty || isEndEmpty || isRateEmpty) {
			var missingFields = [];
			if (isClassificationEmpty) missingFields.push('Classification');
			if (isStartEmpty) missingFields.push('Start');
			if (isEndEmpty) missingFields.push('End');
			if (isRateEmpty) missingFields.push('Rate');
			Swal.fire({
				icon: 'warning',
				title: 'Validation Error',
				text: 'Please fill in all required fields: ' + missingFields.join(', '),
				confirmButtonColor: '#3085d6'
			});
			return false;
		}

		start = parseFloat(startStr);
		end = parseFloat(endStr);
		rate = parseFloat(rateStr);

		if (parseInt(start, 10) > parseInt(end, 10)) {
			Swal.fire({
				icon: 'warning',
				title: 'Validation Error',
				text: 'Start value must be less than or equal to End value.',
				confirmButtonColor: '#3085d6'
			});
			return false;
		}
		if (parseInt(start, 10) < 0 || parseInt(end, 10) < 0) {
			Swal.fire({
				icon: 'warning',
				title: 'Validation Error',
				text: 'Start and End values must be 0 or positive numbers.',
				confirmButtonColor: '#3085d6'
			});
			return false;
		}

		var totalRecords = Math.floor(end) - Math.floor(start) + 1;

		Swal.fire({
			title: 'Confirm Generation',
			html: 'Are you sure you want to generate meter rate data?<br><br>' +
				'<strong>Classification:</strong> ' + $('#classification option:selected').text() + '<br>' +
				'<strong>Range:</strong> ' + Math.floor(start) + ' to ' + Math.floor(end) + ' (' + totalRecords + ' records)<br>' +
				'<strong>Initial Rate:</strong> ' + parseFloat(rate).toFixed(2) +
				(incre ? '<br><strong>Consumption Rate:</strong> ' + parseFloat(incre).toFixed(2) : '') +
				'<br><strong>Apply Increment:</strong> ' + (applyIncrement ? 'Yes' : 'No'),
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Generate!',
			cancelButtonText: 'Cancel'
		}).then(function(result) {
			if (!swalConfirmed(result)) { return; }

			$('#progressBarDiv').show();
			$('#progressBar').css('width', '0%').attr('aria-valuenow', 0);
			$('#progressText').text('0%');
			$('#progressStatus').text('Processing records...');
			$('#submitBtn').prop('disabled', true);

			var progressInterval = setInterval(function() {
				var currentWidth = parseInt($('#progressBar').attr('aria-valuenow'), 10) || 0;
				if (currentWidth < 90) {
					var newWidth = currentWidth + Math.random() * 10;
					if (newWidth > 90) newWidth = 90;
					$('#progressBar').css('width', newWidth + '%').attr('aria-valuenow', newWidth);
					$('#progressText').text(Math.round(newWidth) + '%');
				}
			}, 200);

			$.ajax({
				url: "<?php echo ADMIN_URL; ?>amountrate/add",
				type: "POST",
				headers: { 'X-Requested-With': 'XMLHttpRequest' },
				data: {
					classification: classification,
					start: Math.floor(start),
					end: Math.floor(end),
					rate: parseFloat(rate),
					incre: incre ? parseFloat(incre) : '',
					apply_increment: applyIncrement,
					add: 'add'
				},
				dataType: 'json',
				success: function(response) {
					clearInterval(progressInterval);
					$('#progressBar').css('width', '100%').attr('aria-valuenow', 100);
					$('#progressText').text('100%');

					if (response.success) {
						setTimeout(function() {
							$('#progressStatus').text('Processing completed successfully!');
							Swal.fire({
								icon: 'success',
								title: 'Success!',
								html: response.message + '<br><br>' +
									'<strong>Total Records:</strong> ' + response.total + '<br>' +
									'<strong>Inserted:</strong> ' + response.inserted + '<br>' +
									'<strong>Updated:</strong> ' + response.updated,
								confirmButtonColor: '#3085d6',
								confirmButtonText: 'OK'
							}).then(function() {
								window.location.href = "<?php echo ADMIN_URL; ?>amountrate";
							});
						}, 500);
					} else {
						$('#progressBarDiv').hide();
						$('#submitBtn').prop('disabled', false);
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: response.message || 'An error occurred while processing.',
							confirmButtonColor: '#3085d6'
						});
					}
				},
				error: function(xhr) {
					clearInterval(progressInterval);
					$('#progressBarDiv').hide();
					$('#submitBtn').prop('disabled', false);
					var errorMsg = 'An error occurred while processing the request.';
					if (xhr.responseJSON && xhr.responseJSON.message) {
						errorMsg = xhr.responseJSON.message;
					}
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: errorMsg,
						confirmButtonColor: '#3085d6'
					});
				}
			});
		});
	});
});
</script>
</body>
</html>
