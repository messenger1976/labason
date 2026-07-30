<?php
	$this->load->model('common_model', 'kpi_model');
	$income1 = $this->kpi_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->kpi_model->get_income_monthlycustomer();
	extract($income2);
	$intotal = (isset($total1) ? (float) $total1 : 0) + (isset($total2) ? (float) $total2 : 0);

	$expense1 = $this->kpi_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->kpi_model->get_outcome_payroll();
	extract($expense2);
	$extotal = (isset($extotal1) ? (float) $extotal1 : 0) + (isset($extotal2) ? (float) $extotal2 : 0);

	$total_customer = $this->kpi_model->total_customer();
	extract($total_customer);

	$record = (isset($record) && is_array($record)) ? $record : array();
	$code = $this->input->post('code') != ''
		? $this->input->post('code')
		: (isset($record['code']) ? $record['code'] : '');
	$description = $this->input->post('description') != ''
		? $this->input->post('description')
		: (isset($record['description']) ? $record['description'] : '');
	$value = $this->input->post('value') != ''
		? $this->input->post('value')
		: (isset($record['value']) ? $record['value'] : '0');
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>global_settings">Global Settings</a></li>
		<li class="breadcrumb-item active">Edit</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-cog"></i>
			Manage <span class="fw-300">Global Settings</span>
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
			<div id="panel-global-settings-edit" class="panel">
				<div class="panel-hdr">
					<h2>Global Settings <span class="fw-300"><i>Edit</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<?php if (empty($record)) { ?>
						<div class="alert alert-warning mb-0" role="alert">
							Record not found. <a href="<?php echo ADMIN_URL; ?>global_settings">Back to listing</a>
						</div>
						<?php } else { ?>
						<form name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="code">Code <span class="text-danger">*</span></label>
										<input type="text" class="form-control" id="code" name="code" value="<?php echo htmlspecialchars(stripslashes($code)); ?>" required readonly>
										<small class="form-text text-muted">Setting code (cannot be changed)</small>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="value">Value <span class="text-danger">*</span></label>
										<input type="number" class="form-control" id="value" name="value" step="0.01" min="0" value="<?php echo htmlspecialchars($value); ?>" required>
										<small class="form-text text-muted">Numeric value for this setting</small>
										<?php echo form_error('value'); ?>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label class="form-label" for="description">Description <span class="text-danger">*</span></label>
										<textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars(stripslashes($description)); ?></textarea>
										<small class="form-text text-muted">Description of this setting</small>
										<?php echo form_error('description'); ?>
									</div>
								</div>
							</div>

							<div class="row mt-3">
								<div class="col-md-12">
									<a href="<?php echo ADMIN_URL; ?>global_settings" class="btn btn-secondary waves-effect waves-themed">
										<i class="fal fa-times mr-1"></i> Cancel
									</a>
									<button type="submit" class="btn btn-primary waves-effect waves-themed" name="add" id="add" value="Save">
										<i class="fal fa-check mr-1"></i> Save
									</button>
								</div>
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
});
</script>
</body>
</html>
