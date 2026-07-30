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

	$customer_listing = (isset($customer_listing) && is_array($customer_listing)) ? $customer_listing : array();
	$employee = (isset($employee) && is_array($employee)) ? $employee : array();
?>
<style>
	.select2-container { width: 100% !important; }
</style>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/select2/select2.bundle.css">
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>technicalproblems/">Technical Problems</a></li>
		<li class="breadcrumb-item active">Add</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-tools"></i>
			Manage <span class="fw-300">Technical Problems</span>
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
			<div id="panel-technicalproblems-add" class="panel">
				<div class="panel-hdr">
					<h2>Technical Problems <span class="fw-300"><i>Add</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form class="needs-validation" name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
							<input type="hidden" name="cust_id" id="cust_id" value="<?php echo htmlspecialchars((string) $this->input->post('cust_id')); ?>">
							<input type="hidden" name="lastname" id="lastname" value="<?php echo htmlspecialchars((string) $this->input->post('lastname')); ?>">
							<input type="hidden" name="firstname" id="firstname" value="<?php echo htmlspecialchars((string) $this->input->post('firstname')); ?>">
							<input type="hidden" name="middlename" id="middlename" value="<?php echo htmlspecialchars((string) $this->input->post('middlename')); ?>">

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="customer_id">Customer</label>
										<select class="form-control" name="customer_id" id="customer_id" required>
											<option value="">-- Select Customer --</option>
											<?php foreach ($customer_listing as $value) { ?>
											<option value="<?php echo htmlspecialchars($value['customer_id'].'==>'.$value['special_priviledge']); ?>">
												<?php echo htmlspecialchars($value['customer_id'].' ==> '.$value['last_name'].', '.$value['first_name'].' '.$value['middle_name']); ?>
											</option>
											<?php } ?>
										</select>
										<?php echo form_error('customer_id'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="meter_number">Meter Number</label>
										<input class="form-control" type="text" id="meter_number" name="meter_number" value="<?php echo htmlspecialchars((string) $this->input->post('meter_number')); ?>" required>
										<?php echo form_error('meter_number'); ?>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label class="form-label" for="address">Address</label>
										<input class="form-control" type="text" id="address" name="address" value="<?php echo htmlspecialchars((string) $this->input->post('address')); ?>" required>
										<?php echo form_error('address'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="problem_summary">Problem Summary</label>
										<input class="form-control" type="text" id="problem_summary" name="problem_summary" value="<?php echo htmlspecialchars((string) $this->input->post('problem_summary')); ?>" required>
										<?php echo form_error('problem_summary'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="status">Status</label>
										<select class="form-control" name="status" id="status" required>
											<option value="0">Pending</option>
											<option value="1">Assigned</option>
											<option value="2">On Going</option>
											<option value="3">Resolved</option>
											<option value="4">UnResolved</option>
											<option value="5">Resolved - Closed</option>
											<option value="6">UnResolved - Closed</option>
										</select>
										<?php echo form_error('status'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="reportedby">Reported By</label>
										<select class="form-control" name="reportedby" id="reportedby" required>
											<?php foreach ($employee as $emp) { ?>
											<option value="<?php echo $emp['id']; ?>"><?php echo strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle']; ?></option>
											<?php } ?>
										</select>
										<?php echo form_error('reportedby'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="reported_date">Reported Date</label>
										<div class="input-group">
											<input type="text" class="form-control" id="reported_date" name="reported_date" readonly placeholder="Select date" value="<?php echo $this->input->post('reported_date') != '' ? htmlspecialchars($this->input->post('reported_date')) : date('d-m-Y'); ?>" required>
											<div class="input-group-append">
												<span class="input-group-text fs-xl">
													<i class="fal fa-calendar"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label class="form-label" for="problem_details">Problem Details</label>
										<textarea class="form-control" rows="5" id="problem_details" name="problem_details"><?php echo htmlspecialchars((string) $this->input->post('problem_details')); ?></textarea>
										<?php echo form_error('problem_details'); ?>
									</div>
								</div>
							</div>

							<div class="form-group mb-0">
								<a href="<?php echo ADMIN_URL; ?>technicalproblems" class="btn btn-secondary">
									<i class="fal fa-times mr-1"></i> Cancel
								</a>
								<button type="submit" class="btn btn-primary" name="add" id="btn_add" value="add">
									<i class="fal fa-save mr-1"></i> Add
								</button>
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
<script src="<?php echo base_url(); ?>sa4/js/formplugins/select2/select2.bundle.js"></script>
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

	if ($.fn.select2) {
		$('#customer_id').select2({
			width: '100%',
			placeholder: 'Type text to search...',
			allowClear: true
		});
	}

	var controls = {
		leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
		rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
	};

	if ($.fn.datepicker) {
		$('#reported_date').datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose: true,
			orientation: 'bottom left',
			templates: controls
		});
		$('#reported_date').closest('.input-group').find('.input-group-text').on('click', function() {
			$('#reported_date').datepicker('show');
		});
	}

	$('#customer_id').on('change', function() {
		var raw = $(this).val() || '';
		var customerId = raw.split('==>')[0] || '';
		if (!customerId) {
			$('#cust_id, #lastname, #firstname, #middlename, #meter_number, #address').val('');
			return;
		}

		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL; ?>technicalproblems/get_customer_info',
			data: { customer_id: customerId },
			dataType: 'json',
			success: function(res) {
				if (!res || typeof res !== 'object') { return; }
				$('#cust_id').val(res.customer_id || customerId);
				$('#lastname').val(res.last_name || '');
				$('#firstname').val(res.first_name || '');
				$('#middlename').val(res.middle_name || '');
				$('#meter_number').val(res.meter_number || '');
				$('#address').val(res.address || '');
			}
		});
	});
});
</script>
