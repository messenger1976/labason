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

	$zone = (isset($zone) && is_array($zone)) ? $zone : array();
	$classification = (isset($classification) && is_array($classification)) ? $classification : array();
	$billing = (isset($billing) && is_array($billing)) ? $billing : array();
	$customer_type = (isset($customer_type) && is_array($customer_type)) ? $customer_type : array();
	$customer_id = isset($customer_id) ? $customer_id : '';
	$post_customer_type = (string) $this->input->post('customer_type');
	$post_membership = $this->input->post('membership_status') !== null && $this->input->post('membership_status') !== '' ? (string) $this->input->post('membership_status') : '1';
	$post_gender = (string) $this->input->post('gender');
	$post_zone = (string) $this->input->post('zone');
	$post_classification = (string) $this->input->post('classification');
	$post_account_type = (string) $this->input->post('account_type');
	$post_billing = (string) $this->input->post('billingplans');
	$post_status = (string) $this->input->post('status');
	$special_checked = ((string) $this->input->post('special_priviledge') === '1');

	$pv = function($key) {
		$CI =& get_instance();
		return htmlspecialchars((string) $CI->input->post($key), ENT_QUOTES, 'UTF-8');
	};
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addcustomer">Customers</a></li>
		<li class="breadcrumb-item active">Add</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-users"></i>
			Manage <span class="fw-300">Customers</span>
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
			<div id="panel-addcustomer-add" class="panel">
				<div class="panel-hdr">
					<h2>Customer <span class="fw-300"><i>Add</i></span></h2>
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
										<label class="form-label" for="membership_status">Membership Status <span class="text-danger">*</span></label>
										<select name="membership_status" id="membership_status" class="form-control" required>
											<option value="1" <?php echo ($post_membership === '1') ? 'selected' : ''; ?>>Member</option>
											<option value="0" <?php echo ($post_membership === '0') ? 'selected' : ''; ?>>Non-Member</option>
										</select>
										<?php echo form_error('membership_status'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="customer_id">Customer ID <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="customer_id" name="customer_id" value="<?php echo htmlspecialchars($customer_id); ?>" readonly required>
										<?php echo form_error('customer_id'); ?>
										<span id="val_roll_img"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="first_name">First Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="first_name" name="first_name" value="<?php echo $pv('first_name'); ?>" required>
										<?php echo form_error('first_name'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="middle_name">Middle Name</label>
										<input class="form-control" type="text" id="middle_name" name="middle_name" value="<?php echo $pv('middle_name'); ?>">
										<?php echo form_error('middle_name'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="last_name">Last Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="last_name" name="last_name" value="<?php echo $pv('last_name'); ?>" required>
										<?php echo form_error('last_name'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="dob">DOB <span class="text-danger">*</span></label>
										<div class="input-group">
											<input class="form-control" type="text" name="dob" id="dob" placeholder="DD-MM-YYYY" value="<?php echo $pv('dob'); ?>" required>
											<div class="input-group-append">
												<span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
											</div>
										</div>
										<?php echo form_error('dob'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="gender">Gender <span class="text-danger">*</span></label>
										<select name="gender" id="gender" class="form-control" required>
											<option value="">-- Select --</option>
											<option value="male" <?php echo ($post_gender === 'male') ? 'selected' : ''; ?>>Male</option>
											<option value="female" <?php echo ($post_gender === 'female') ? 'selected' : ''; ?>>Female</option>
										</select>
										<?php echo form_error('gender'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="place_of_birth">Place of Birth <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="place_of_birth" name="place_of_birth" value="<?php echo $pv('place_of_birth'); ?>" required>
										<?php echo form_error('place_of_birth'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="address">Address <span class="text-danger">*</span></label>
										<textarea class="form-control" rows="3" id="address" name="address" required><?php echo $pv('address'); ?></textarea>
										<?php echo form_error('address'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="city">City <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="city" name="city" value="<?php echo $pv('city'); ?>" required>
										<?php echo form_error('city'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="state">Province <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="state" name="state" value="<?php echo $pv('state'); ?>" required>
										<?php echo form_error('state'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="mobile1">Mobile 1 <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="mobile1" name="mobile1" value="<?php echo $pv('mobile1'); ?>" required>
										<?php echo form_error('mobile1'); ?>
										<span id="val_mobile1_img"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="mobile2">Mobile 2</label>
										<input class="form-control" type="text" id="mobile2" name="mobile2" value="<?php echo $pv('mobile2'); ?>">
										<?php echo form_error('mobile2'); ?>
										<span id="val_mobile2_img"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="line_number">Line Number</label>
										<input class="form-control" type="text" id="line_number" name="line_number" value="<?php echo $pv('line_number'); ?>">
										<?php echo form_error('line_number'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="customer_type">Payment Type <span class="text-danger">*</span></label>
										<select class="form-control" name="customer_type" id="customer_type" onchange="customer_type_values()" required>
											<option value="">-- Select --</option>
											<option value="monthlycustomer" <?php echo ($post_customer_type === 'monthlycustomer') ? 'selected' : ''; ?>>Monthly Customer</option>
											<option value="metercustomer" <?php echo ($post_customer_type === 'metercustomer') ? 'selected' : ''; ?>>Meter Customer</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="email_id">Email</label>
										<input class="form-control" type="email" id="email_id" name="email_id" value="<?php echo $pv('email_id'); ?>">
										<?php echo form_error('email_id'); ?>
										<span id="var_email_img"></span>
									</div>
								</div>
								<div class="col-md-6" id="showcustomers" <?php echo ($post_customer_type === 'metercustomer') ? 'style="display:none;"' : ''; ?>>
									<div class="form-group">
										<label class="form-label" for="billingplans">Billing Plans</label>
										<select class="form-control" name="billingplans" id="billingplans">
											<option value="">-- Select --</option>
											<?php foreach ($billing as $value) { ?>
											<option value="<?php echo (int) $value['id']; ?>" <?php echo ($post_billing === (string) $value['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($value['name']); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="zone">Zone <span class="text-danger">*</span></label>
										<select class="form-control" name="zone" id="zone" required>
											<option value="">-- Select --</option>
											<?php foreach ($zone as $value) { ?>
											<option value="<?php echo (int) $value['id']; ?>" <?php echo ($post_zone === (string) $value['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($value['zone']); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="referenceperson">Reference Person</label>
										<input class="form-control" type="text" id="referenceperson" name="referenceperson" value="<?php echo $pv('referenceperson'); ?>">
										<?php echo form_error('referenceperson'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="meter_number">Meter Number <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="meter_number" name="meter_number" value="<?php echo $pv('meter_number'); ?>" required>
										<?php echo form_error('meter_number'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="meter_brand">Meter Brand <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="meter_brand" name="meter_brand" value="<?php echo $pv('meter_brand'); ?>" required>
										<?php echo form_error('meter_brand'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="meter_size">Meter Size <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="meter_size" name="meter_size" value="<?php echo $pv('meter_size'); ?>" required>
										<?php echo form_error('meter_size'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="date_installed">Date Installed <span class="text-danger">*</span></label>
										<div class="input-group">
											<input class="form-control" type="text" name="date_installed" id="date_installed" placeholder="DD-MM-YYYY" value="<?php echo $pv('date_installed'); ?>" required>
											<div class="input-group-append">
												<span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
											</div>
										</div>
										<?php echo form_error('date_installed'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="classification">Classification <span class="text-danger">*</span></label>
										<select class="form-control" name="classification" id="classification" required>
											<option value="">-- Select --</option>
											<?php foreach ($classification as $value) { ?>
											<option value="<?php echo (int) $value['class_id']; ?>" <?php echo ($post_classification === (string) $value['class_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($value['class_name']); ?></option>
											<?php } ?>
										</select>
										<span id="val_classification_img"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="account_type">Account Type <span class="text-danger">*</span></label>
										<select class="form-control" name="account_type" id="account_type" required>
											<option value="">-- Select --</option>
											<?php foreach ($customer_type as $value) { ?>
											<option value="<?php echo (int) $value['cust_type_id']; ?>" <?php echo ($post_account_type === (string) $value['cust_type_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($value['cust_type_name']); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="status">Status <span class="text-danger">*</span></label>
										<select class="form-control" name="status" id="status" required>
											<option value="">-- Select --</option>
											<option value="1" <?php echo ($post_status === '1') ? 'selected' : ''; ?>>Active</option>
											<option value="0" <?php echo ($post_status === '0') ? 'selected' : ''; ?>>Inactive</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label d-block">Special Privilege</label>
										<div class="custom-control custom-checkbox mt-2">
											<input type="checkbox" class="custom-control-input" name="special_priviledge" id="special_priviledge" value="<?php echo $special_checked ? '1' : '0'; ?>" <?php echo $special_checked ? 'checked' : ''; ?>>
											<label class="custom-control-label" for="special_priviledge">Enable special privilege</label>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="userfile">Upload Image</label>
										<div class="mb-2">
											<img id="blah" class="img-fluid border border-faded p-1" style="max-height:150px; object-fit:contain;" src="<?php echo ADMIN_IMG_URL; ?>upload/a.png" alt="Preview">
										</div>
										<div class="custom-file">
											<input type="file" class="custom-file-input" id="userfile" name="userfile" accept="image/*" onchange="readURL(this);">
											<label class="custom-file-label" for="userfile">Choose image…</label>
										</div>
									</div>
								</div>
							</div>

							<div class="row mt-3">
								<div class="col-md-12">
									<a href="<?php echo ADMIN_URL; ?>addcustomer" class="btn btn-secondary waves-effect waves-themed">
										<i class="fal fa-times mr-1"></i> Cancel
									</a>
									<button type="submit" class="btn btn-primary waves-effect waves-themed" name="add" id="add" value="Add">
										<i class="fal fa-plus mr-1"></i> Add
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
<script src="<?php echo base_url(); ?>sa4/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">
function customer_type_values() {
	if ($("#customer_type").val() == 'monthlycustomer') {
		$("#showcustomers").show();
	} else if ($("#customer_type").val() == 'metercustomer') {
		$("#showcustomers").hide();
	} else {
		$("#showcustomers").show();
	}
}

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

var lockedCustomerSeries = '<?php
	$customer_id_parts = explode('-', $customer_id);
	echo end($customer_id_parts);
?>';

function regenerateCustomerId() {
	var member_stat = $("#membership_status").val();
	if (member_stat != 1) { return; }
	var class_id = $("#classification").val() || '000';
	var zone_id = $("#zone").val() || '000';
	var date_installed = $("#date_installed").val() || '';
	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL; ?>addcustomer/get_customer_id_generate/' + class_id + '/' + zone_id,
		data: {
			class_id: class_id,
			zone_id: zone_id,
			date_installed: date_installed,
			preserve_series: lockedCustomerSeries
		},
		success: function(data) { $('#customer_id').val(data); }
	});
}

$(document).ready(function() {
	if (typeof pageSetUp === 'function') { pageSetUp(); }
	customer_type_values();

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
	if ($.fn.datepicker) {
		$('#dob, #date_installed').datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose: true,
			orientation: 'bottom left',
			templates: controls
		});
		$('#dob, #date_installed').closest('.input-group').find('.input-group-text').on('click', function() {
			$(this).closest('.input-group').find('input').datepicker('show');
		});
		$('#date_installed').on('changeDate', function() { regenerateCustomerId(); });
	}

	$("#classification, #zone").on('change', regenerateCustomerId);

	$("#customer_id").on('change', function() {
		var customer = $(this).val();
		if (customer != '') {
			$('#val_roll_img').removeAttr("class").text('').append('<i class="fal fa-spinner fa-spin"></i>');
			$.ajax({
				type: 'POST',
				url: '<?php echo ADMIN_URL; ?>addcustomer/check_customer_id/' + customer,
				data: { customer_id: customer },
				success: function(data) {
					if (data == 'true') {
						$("#val_roll_img").attr("class", "badge badge-warning").text("Customer Id '" + customer + "' already exists try another !");
						$("#customer_id").val('').focus();
					} else {
						$("#val_roll_img").attr("class", "badge badge-success").text("Customer Id '" + customer + "' available !");
					}
				}
			});
		} else {
			$('#val_roll_img').removeAttr("class").text('');
		}
	});

	$("#email_id").on('change', function() {
		var email = $(this).val();
		if (email != '') {
			$("#var_email_img").removeAttr("class").text('').append('<i class="fal fa-spinner fa-spin"></i>');
			$.ajax({
				type: 'POST',
				url: '<?php echo ADMIN_URL; ?>addcustomer/check_customer_email/',
				data: { emailid: email },
				success: function(data) {
					if (data == 'true') {
						$("#var_email_img").attr("class", "badge badge-warning").text("Email '" + email + "' already exists try another !");
						$("#email_id").val('').focus();
					} else {
						$("#var_email_img").attr("class", "badge badge-success").text("Email '" + email + "' available !");
					}
				}
			});
		}
	});

	$("#mobile2").on('change', function() {
		var mobile = $(this).val();
		if (mobile != '') {
			$("#val_mobile2_img").removeAttr("class").text('').append('<i class="fal fa-spinner fa-spin"></i>');
			$.ajax({
				type: 'POST',
				url: '<?php echo ADMIN_URL; ?>addcustomer/check_customer_mobile_2/' + mobile,
				data: { mobile_1: mobile },
				success: function(data) {
					if (data == 'true') {
						$("#val_mobile2_img").attr("class", "badge badge-warning").text("Mobile '" + mobile + "' already exists try another !");
						$("#mobile2").val('').focus();
					} else {
						$("#val_mobile2_img").attr("class", "badge badge-success").text("Mobile '" + mobile + "' available !");
					}
				}
			});
		}
	});

	$('#special_priviledge').on('change', function() {
		$(this).val($(this).is(':checked') ? 1 : 0);
	});
});
</script>
</body>
</html>
