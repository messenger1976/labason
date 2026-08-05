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
	if (!empty($record)) { extract($record); }

	$v = function($key, $default = '') use ($record) {
		if (isset($record[$key]) && $record[$key] !== '') { return $record[$key]; }
		return $default;
	};

	$established_val = $v('established');
	if ($established_val && strpos($established_val, '-') !== false && preg_match('/^\d{4}-\d{2}-\d{2}/', $established_val)) {
		$established_val = date('d-m-Y', strtotime($established_val));
	}
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addcustomer/adminconfiguration">Admin Configuration</a></li>
		<li class="breadcrumb-item active">Edit</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-cog"></i>
			Manage <span class="fw-300">Admin Configuration</span>
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

	<?php if ($this->session->flashdata('msg_err')) { ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<?php echo $this->session->flashdata('msg_err'); ?>
	</div>
	<?php } elseif (!empty($msg)) { ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<?php echo $msg; ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-adminconfiguration-edit" class="panel">
				<div class="panel-hdr">
					<h2>Admin Configuration <span class="fw-300"><i>Edit</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<?php if (empty($record)) { ?>
						<div class="alert alert-warning mb-0" role="alert">
							Record not found. <a href="<?php echo ADMIN_URL; ?>addcustomer/adminconfiguration">Back</a>
						</div>
						<?php } else { ?>
						<form method="post" action="<?php echo ADMIN_URL; ?>addcustomer/adminconfigurationupdate" enctype="multipart/form-data">
							<input type="hidden" name="adminid" value="<?php echo (int) $v('adminid'); ?>">

							<div class="row">
								<div class="col-md-4 mb-3">
									<label class="form-label">Logo</label>
									<div class="text-center mb-2">
										<img id="blah" class="img-fluid border border-faded p-2" style="max-height:180px; object-fit:contain;"
											src="<?php echo ADMIN_IMG_URL; ?>logo/<?php echo htmlspecialchars($v('file')); ?>"
											alt="Logo preview">
									</div>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="userfile" name="userfile" accept="image/*" onchange="readURL(this);">
										<label class="custom-file-label" for="userfile">Choose logo file…</label>
									</div>
								</div>
								<div class="col-md-8">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="name">Name</label>
												<input class="form-control" type="text" id="name" name="name" value="<?php echo htmlspecialchars($v('name')); ?>" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="email">Email</label>
												<input class="form-control" type="email" id="email" name="email" value="<?php echo htmlspecialchars($v('email')); ?>" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="dob">Established on</label>
												<div class="input-group">
													<input class="form-control" type="text" name="established" id="dob" placeholder="DD-MM-YYYY" value="<?php echo htmlspecialchars($established_val); ?>" required>
													<div class="input-group-append">
														<span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="contact1">Contact</label>
												<input class="form-control" type="text" id="contact1" name="contact1" value="<?php echo htmlspecialchars($v('contact1')); ?>" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="contactperson">Contact Person</label>
												<input class="form-control" type="text" id="contactperson" name="contactperson" value="<?php echo htmlspecialchars($v('contactperson')); ?>" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="contactpersonphone">Contact Person Phone</label>
												<input class="form-control" type="text" id="contactpersonphone" name="contactpersonphone" value="<?php echo htmlspecialchars($v('contactpersonphone')); ?>" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="website">Website</label>
												<input class="form-control" type="text" id="website" name="website" value="<?php echo htmlspecialchars($v('website')); ?>" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-label" for="address1">Address</label>
												<textarea class="form-control" rows="3" id="address1" name="address1"><?php echo htmlspecialchars($v('address1')); ?></textarea>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label class="form-label" for="about">About</label>
												<textarea class="form-control" rows="4" id="about" name="about"><?php echo htmlspecialchars($v('about')); ?></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row mt-3">
								<div class="col-md-12">
									<a href="<?php echo ADMIN_URL; ?>addcustomer/adminconfiguration" class="btn btn-secondary waves-effect waves-themed">
										<i class="fal fa-times mr-1"></i> Cancel
									</a>
									<button type="submit" class="btn btn-primary waves-effect waves-themed" name="edit" id="edit" value="Update">
										<i class="fal fa-check mr-1"></i> Update
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
<script src="<?php echo base_url(); ?>sa4/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">
function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			$('#blah').attr('src', e.target.result);
		};
		reader.readAsDataURL(input.files[0]);
		if (input.files[0].name) {
			$(input).next('.custom-file-label').text(input.files[0].name);
		}
	}
}

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
	if ($.fn.datepicker && $('#dob').length) {
		$('#dob').datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose: true,
			orientation: 'bottom left',
			templates: controls
		});
		$('#dob').closest('.input-group').find('.input-group-text').on('click', function() {
			$('#dob').datepicker('show');
		});
	}
});
</script>
</body>
</html>
