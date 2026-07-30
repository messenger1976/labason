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

	$record = (isset($record) && is_array($record)) ? $record : array();
	$addmonth = (isset($addmonth) && is_array($addmonth)) ? $addmonth : array();
?>
<style>
	.mcr-readonly { background-color: #fff8dc !important; }
</style>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addmetercustomerreading">Meter Reading</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addmetercustomerreading/edit">Edit Search</a></li>
		<li class="breadcrumb-item active">Edit</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-tachometer"></i>
			Manage <span class="fw-300">Meter Customer Reading</span>
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
			<div id="panel-mcr-edit" class="panel">
				<div class="panel-hdr">
					<h2>Meter Customer Reading <span class="fw-300"><i>Edit</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<?php if (empty($record)) { ?>
						<div class="alert alert-warning mb-0">
							Record not found. <a href="<?php echo ADMIN_URL; ?>addmetercustomerreading/edit">Back to search</a>
						</div>
						<?php } else { ?>
						<form name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="customer_id">Customer-Id</label>
										<input class="form-control" type="text" id="customer_id" name="customer_id" value="<?php echo htmlspecialchars($record['customer_id']); ?>" required>
										<?php echo form_error('customer_id'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="month">Month</label>
										<?php if (!empty($addmonth)) { ?>
										<select name="month" id="month" class="form-control" required>
											<option value="">--Select--</option>
											<?php foreach ($addmonth as $value) { ?>
											<option value="<?php echo $value['month_id']; ?>" <?php echo ($record['month'] == $value['month_id']) ? 'selected' : ''; ?>>
												<?php echo htmlspecialchars($value['month_name']); ?>
											</option>
											<?php } ?>
										</select>
										<?php } else { ?>
										<input class="form-control mcr-readonly" type="text" id="month" name="month" value="<?php echo htmlspecialchars($record['month']); ?>" readonly>
										<?php } ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="previous_reading">Previous Reading</label>
										<input class="form-control mcr-readonly" type="text" id="previous_reading" name="previous_reading" value="<?php echo htmlspecialchars($record['previous_reading']); ?>" readonly>
										<?php echo form_error('previous_reading'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="reading">Current Reading <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="reading" name="reading" value="<?php echo htmlspecialchars($record['reading']); ?>" required>
										<?php echo form_error('reading'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="consumed">Difference</label>
										<input class="form-control mcr-readonly" type="text" id="consumed" name="consumed" value="<?php echo htmlspecialchars($record['consumed']); ?>" readonly>
										<?php echo form_error('consumed'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="amount">Amount</label>
										<input class="form-control mcr-readonly" type="text" id="amount" name="amount" value="<?php echo htmlspecialchars($record['amount']); ?>" readonly>
										<?php echo form_error('amount'); ?>
									</div>
								</div>
							</div>
							<div class="form-group mb-0">
								<a href="<?php echo ADMIN_URL; ?>addmetercustomerreading/edit" class="btn btn-secondary">
									<i class="fal fa-times mr-1"></i> Cancel
								</a>
								<button type="submit" class="btn btn-primary" name="edit" id="edit" value="Edit">
									<i class="fal fa-save mr-1"></i> Save
								</button>
							</div>
						</form>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php include('footer.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
</body>
</html>
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

	$('#reading').on('change', function() {
		var current_meter = $(this).val();
		var preview = $('#previous_reading').val();
		var differences = parseInt(current_meter, 10) - parseInt(preview, 10);
		$('#consumed').val(differences);
		var formData = new FormData();
		formData.append('cubic_meter_reading', $('#consumed').val());

		$.ajax({
			url: '<?php echo ADMIN_URL; ?>addmetercustomerreading/get_cubic_meter_price/',
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			success: function(response) {
				var result = JSON.parse(response);
				if (result.per_unit) {
					$('#amount').val(amount_formatted(result.per_unit));
				} else {
					$('#amount').val(amount_formatted(0));
					alert('No Amount per cubic meter.');
				}
			},
			error: function() {
				alert('An error occurred while processing data.');
			}
		});
	});
});

function amount_formatted(amount) {
	return new Intl.NumberFormat('en-US', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2,
		useGrouping: false
	}).format(amount);
}
</script>
