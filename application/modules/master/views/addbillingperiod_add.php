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
	$zone = (isset($zone) && is_array($zone)) ? $zone : array();
	$month = (isset($month) && is_array($month)) ? $month : array();
	$is_edit = !empty($record['bp_zone_id']) || !empty($record['bp_id']);

	if (!function_exists('bp_date_val')) {
		function bp_date_val($posted, $db_val) {
			if ($posted !== null && $posted !== '') {
				return $posted;
			}
			if (!empty($db_val) && $db_val !== '0000-00-00') {
				return date('d-m-Y', strtotime($db_val));
			}
			return date('d-m-Y');
		}
	}
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addbillingperiod">Billing Period Listing</a></li>
		<li class="breadcrumb-item active"><?php echo $is_edit ? 'Edit' : 'Add'; ?></li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-calendar-alt"></i>
			Manage <span class="fw-300">Billing Period</span>
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
			<div class="panel">
				<div class="panel-hdr">
					<h2>Billing Period <span class="fw-300"><i><?php echo $is_edit ? 'Edit' : 'Add'; ?></i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="zone">Zone</label>
										<select class="form-control" name="zone" id="zone" required>
											<option value="">--Select--</option>
											<?php foreach ($zone as $value) { ?>
											<option value="<?php echo $value['id']; ?>" <?php echo (isset($record['bp_zone_id']) && $value['id'] == $record['bp_zone_id']) ? 'selected' : ''; ?>>
												<?php echo htmlspecialchars($value['zone']); ?>
											</option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label" for="billing_month">Billing Period (Month)</label>
										<select class="form-control" name="billing_month" id="billing_month" required>
											<option value="">--Select--</option>
											<?php foreach ($month as $value) { ?>
											<option value="<?php echo $value['month_id']; ?>" <?php echo (isset($record['bp_period_month']) && $value['month_id'] == $record['bp_period_month']) ? 'selected' : ''; ?>>
												<?php echo htmlspecialchars($value['month_name']); ?>
											</option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label" for="billing_year">Billing Period (Year)</label>
										<input type="text" class="form-control" name="billing_year" id="billing_year" value="<?php echo htmlspecialchars(isset($record['bp_period_year']) ? $record['bp_period_year'] : date('Y')); ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="start_date">Start Date</label>
										<div class="input-group">
											<input type="text" class="form-control" id="start_date" name="start_date" readonly value="<?php echo htmlspecialchars(bp_date_val($this->input->post('start_date'), isset($record['bp_start_date']) ? $record['bp_start_date'] : '')); ?>" required>
											<div class="input-group-append">
												<span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
											</div>
										</div>
										<?php echo form_error('start_date'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="end_date">End Date</label>
										<div class="input-group">
											<input type="text" class="form-control" id="end_date" name="end_date" readonly value="<?php echo htmlspecialchars(bp_date_val($this->input->post('end_date'), isset($record['bp_end_date']) ? $record['bp_end_date'] : '')); ?>" required>
											<div class="input-group-append">
												<span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
											</div>
										</div>
										<?php echo form_error('end_date'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="due_date">Due Date</label>
										<div class="input-group">
											<input type="text" class="form-control" id="due_date" name="due_date" readonly value="<?php echo htmlspecialchars(bp_date_val($this->input->post('due_date'), isset($record['bp_due_date']) ? $record['bp_due_date'] : '')); ?>" required>
											<div class="input-group-append">
												<span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
											</div>
										</div>
										<?php echo form_error('due_date'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="disconnect_date">Disconnection Date</label>
										<div class="input-group">
											<input type="text" class="form-control" id="disconnect_date" name="disconnect_date" readonly value="<?php echo htmlspecialchars(bp_date_val($this->input->post('disconnect_date'), isset($record['bp_disconnection_date']) ? $record['bp_disconnection_date'] : '')); ?>" required>
											<div class="input-group-append">
												<span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
											</div>
										</div>
										<?php echo form_error('disconnect_date'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="status">Status</label>
										<select class="form-control" name="status" id="status">
											<option value="1" <?php echo (!isset($record['bp_status']) || (int)$record['bp_status'] === 1) ? 'selected' : ''; ?>>Active</option>
											<option value="0" <?php echo (isset($record['bp_status']) && (int)$record['bp_status'] === 0) ? 'selected' : ''; ?>>Inactive</option>
										</select>
									</div>
								</div>
							</div>

							<div class="form-group mb-0">
								<a href="<?php echo ADMIN_URL; ?>addbillingperiod" class="btn btn-secondary">
									<i class="fal fa-times mr-1"></i> Cancel
								</a>
								<?php if ($is_edit) { ?>
								<button type="submit" class="btn btn-primary" name="edit" id="edit" value="Edit">
									<i class="fal fa-save mr-1"></i> Save
								</button>
								<?php } else { ?>
								<button type="submit" class="btn btn-primary" name="add" id="add" value="Add">
									<i class="fal fa-save mr-1"></i> Add
								</button>
								<?php } ?>
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
<script src="<?php echo base_url(); ?>sa4/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
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

	var controls = {
		leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
		rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
	};
	var dateOpts = {
		format: 'dd-mm-yyyy',
		todayHighlight: true,
		autoclose: true,
		orientation: 'bottom left',
		templates: controls
	};

	if ($.fn.datepicker) {
		$('#start_date, #end_date, #due_date, #disconnect_date').datepicker(dateOpts);
		$('#start_date, #end_date, #due_date, #disconnect_date').each(function() {
			var $input = $(this);
			$input.closest('.input-group').find('.input-group-text').on('click', function() {
				$input.datepicker('show');
			});
		});
	}
});
</script>
