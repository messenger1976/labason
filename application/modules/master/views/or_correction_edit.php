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
	$cur_date = !empty($record['date']) ? date('d-m-Y', strtotime($record['date'])) : date('d-m-Y');
	$newdate_val = ($this->input->post('newdate') != '')
		? date('d-m-Y', strtotime($this->input->post('newdate')))
		: $cur_date;
	$or_amount = $this->input->post('grand_total') != ''
		? $this->input->post('grand_total')
		: (isset($record['grand_total']) ? $record['grand_total'] : '');
	$new_or = $this->input->post('or_number') != ''
		? $this->input->post('or_number')
		: (isset($record['or_number']) ? $record['or_number'] : '');
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<style>
	.orc-readonly { background-color: #fff8dc !important; }
</style>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>or_correction">OR Correction</a></li>
		<li class="breadcrumb-item active">Edit</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-receipt"></i>
			Manage <span class="fw-300">OR Correction</span>
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
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<?php echo $msg; ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-or-correction-edit" class="panel">
				<div class="panel-hdr">
					<h2>OR Transaction <span class="fw-300"><i>Edit</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<?php if (empty($record)) { ?>
						<div class="alert alert-warning mb-0" role="alert">
							Record not found. <a href="<?php echo ADMIN_URL; ?>or_correction">Back to listing</a>
						</div>
						<?php } else { ?>
						<form name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
							<input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars(isset($record['id']) ? $record['id'] : ''); ?>">

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="date">Transaction Date</label>
										<div class="input-group">
											<input class="form-control orc-readonly" type="text" id="date" name="date" value="<?php echo htmlspecialchars($cur_date); ?>" readonly>
											<div class="input-group-append">
												<span class="input-group-text fs-xl">
													<i class="fal fa-calendar"></i>
												</span>
											</div>
										</div>
										<?php echo form_error('date'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="newdate">New Transaction Date <span class="text-danger">*</span></label>
										<div class="input-group">
											<input class="form-control" type="text" id="newdate" name="newdate" value="<?php echo htmlspecialchars($newdate_val); ?>" readonly required>
											<div class="input-group-append">
												<span class="input-group-text fs-xl">
													<i class="fal fa-calendar"></i>
												</span>
											</div>
										</div>
										<?php echo form_error('newdate'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="customer_id">Customer ID</label>
										<input class="form-control orc-readonly" type="text" id="customer_id" name="customer_id" value="<?php echo htmlspecialchars(isset($record['customer_id']) ? $record['customer_id'] : ''); ?>" readonly>
										<?php echo form_error('customer_id'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="custname">Customer Name</label>
										<input class="form-control orc-readonly" type="text" id="custname" name="custname" value="<?php echo htmlspecialchars(isset($record['name']) ? $record['name'] : ''); ?>" readonly>
										<?php echo form_error('custname'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="grand_total">OR Amount <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="grand_total" name="grand_total" value="<?php echo htmlspecialchars($or_amount); ?>" required>
										<?php echo form_error('grand_total'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="old_or">Old OR #</label>
										<input class="form-control orc-readonly" type="text" id="old_or" name="old_or" value="<?php echo htmlspecialchars(isset($record['or_number']) ? $record['or_number'] : ''); ?>" readonly>
										<?php echo form_error('old_or'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="or_number">New OR # <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="or_number" name="or_number" value="<?php echo htmlspecialchars($new_or); ?>" required>
										<?php echo form_error('or_number'); ?>
									</div>
								</div>
							</div>

							<div class="form-group mb-0">
								<a href="<?php echo ADMIN_URL; ?>or_correction" class="btn btn-secondary">
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

	if ($.fn.datepicker && $('#newdate').length) {
		$('#newdate').datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose: true,
			orientation: 'bottom left',
			templates: controls
		});
		$('#newdate').closest('.input-group').find('.input-group-text').on('click', function() {
			$('#newdate').datepicker('show');
		});
	}
});
</script>
