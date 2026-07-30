<?php
	$record = (isset($record) && is_array($record)) ? $record : array();
	$image = (isset($image) && is_array($image)) ? $image : array();
	$billing = (isset($billing) && is_array($billing)) ? $billing : array();
	$zone = (isset($zone) && is_array($zone)) ? $zone : array();
	$classification = (isset($classification) && is_array($classification)) ? $classification : array();
	$customer_type = (isset($customer_type) && is_array($customer_type)) ? $customer_type : array();

	$h = function($key, $fallback = '') use ($record) {
		if (!isset($record[$key]) || $record[$key] === null) {
			return htmlspecialchars((string) $fallback, ENT_QUOTES, 'UTF-8');
		}
		return htmlspecialchars((string) $record[$key], ENT_QUOTES, 'UTF-8');
	};

	$photo_file = !empty($image['file']) ? $image['file'] : 'a.png';
	$photo_url = ADMIN_IMG_URL . 'upload/' . $photo_file;
	$gender = strtolower((string) (isset($record['gender']) ? $record['gender'] : ''));
	$customer_type_val = isset($record['customer_type']) ? (string) $record['customer_type'] : '';
	$special_checked = !empty($record['special_priviledge']);
	$dob_val = (!empty($record['DOB']) && $record['DOB'] !== '0000-00-00')
		? date('d-m-Y', strtotime($record['DOB']))
		: '';
	$date_installed_val = (!empty($record['date_installed']) && $record['date_installed'] !== '0000-00-00')
		? date('d-m-Y', strtotime($record['date_installed']))
		: '';
?>
<div class="customer-edit-modal">
	<div id="edit_customer_msg" style="display:none;"></div>
	<div class="alert alert-info alert-dismissible fade show" role="alert">
		<strong>Note:</strong> If the Customer ID is changed, related meter readings, payments, and leaking ledger records will be updated to stay in sync.
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
	</div>

	<form name="editCustomerForm" id="editCustomerForm" method="post" enctype="multipart/form-data">
		<input type="hidden" name="id" id="edit_customer_id" value="<?php echo (int) $record['id']; ?>">
		<input type="hidden" name="original_customer_id" id="original_customer_id_modal" value="<?php echo $h('customer_id'); ?>">

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="userfile_modal">User Photo</label>
					<div class="mb-2">
						<img id="blah_modal" class="img-fluid border border-faded p-1 rounded" style="max-height:120px; object-fit:contain;" src="<?php echo htmlspecialchars($photo_url, ENT_QUOTES, 'UTF-8'); ?>" alt="Preview" onerror="this.src='<?php echo ADMIN_IMG_URL; ?>upload/a.png';">
					</div>
					<div class="custom-file">
						<input type="file" class="custom-file-input" id="userfile_modal" name="userfile" accept="image/*" onchange="readURLModal(this);">
						<label class="custom-file-label" for="userfile_modal">Choose image…</label>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="membership_status_modal">Membership Status <span class="text-danger">*</span></label>
					<select name="membership_status" id="membership_status_modal" class="form-control" required>
						<option value="1" <?php echo ((string) $record['membership_status'] === '1') ? 'selected' : ''; ?>>Member</option>
						<option value="0" <?php echo ((string) $record['membership_status'] === '0') ? 'selected' : ''; ?>>Non-Member</option>
					</select>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="customer_id_modal">Customer ID <span class="text-danger">*</span></label>
					<input class="form-control" type="text" id="customer_id_modal" name="customer_id" value="<?php echo $h('customer_id'); ?>" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="first_name_modal">First Name <span class="text-danger">*</span></label>
					<input class="form-control" type="text" id="first_name_modal" name="first_name" value="<?php echo $h('first_name'); ?>" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="middle_name_modal">Middle Name</label>
					<input class="form-control" type="text" id="middle_name_modal" name="middle_name" value="<?php echo $h('middle_name'); ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="last_name_modal">Last Name <span class="text-danger">*</span></label>
					<input class="form-control" type="text" id="last_name_modal" name="last_name" value="<?php echo $h('last_name'); ?>" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="dob_modal">DOB <span class="text-danger">*</span></label>
					<div class="input-group">
						<input class="form-control" type="text" name="dob" id="dob_modal" placeholder="DD-MM-YYYY" value="<?php echo htmlspecialchars($dob_val, ENT_QUOTES, 'UTF-8'); ?>" required>
						<div class="input-group-append">
							<span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="gender_modal">Gender <span class="text-danger">*</span></label>
					<select name="gender" id="gender_modal" class="form-control" required>
						<option value="">-- Select --</option>
						<option value="male" <?php echo ($gender === 'male') ? 'selected' : ''; ?>>Male</option>
						<option value="female" <?php echo ($gender === 'female') ? 'selected' : ''; ?>>Female</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="place_of_birth_modal">Place of Birth <span class="text-danger">*</span></label>
					<input class="form-control" type="text" id="place_of_birth_modal" name="place_of_birth" value="<?php echo $h('place_of_birth'); ?>" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="address_modal">Address</label>
					<textarea class="form-control" rows="3" id="address_modal" name="address"><?php echo $h('address'); ?></textarea>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="city_modal">City <span class="text-danger">*</span></label>
					<input class="form-control" type="text" id="city_modal" name="city" value="<?php echo $h('city'); ?>" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="state_modal">Province <span class="text-danger">*</span></label>
					<input class="form-control" type="text" id="state_modal" name="state" value="<?php echo $h('state'); ?>" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="mobile1_modal">Mobile 1 <span class="text-danger">*</span></label>
					<input class="form-control" type="text" id="mobile1_modal" name="mobile1" value="<?php echo $h('mobile1'); ?>" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="mobile2_modal">Mobile 2</label>
					<input class="form-control" type="text" id="mobile2_modal" name="mobile2" value="<?php echo $h('mobile2'); ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="line_number_modal">Line Number</label>
					<input class="form-control" type="text" id="line_number_modal" name="line_number" value="<?php echo $h('line_number'); ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="customer_type_modal">Payment Type <span class="text-danger">*</span></label>
					<select class="form-control" name="customer_type" id="customer_type_modal" required onchange="customer_type_values_modal(this.value)">
						<option value="">-- Select --</option>
						<option value="monthlycustomer" <?php echo ($customer_type_val === 'monthlycustomer') ? 'selected' : ''; ?>>Monthly Customer</option>
						<option value="metercustomer" <?php echo ($customer_type_val === 'metercustomer') ? 'selected' : ''; ?>>Meter Customer</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="email_id_modal">Email</label>
					<input class="form-control" type="email" id="email_id_modal" name="email_id" value="<?php echo $h('email_id'); ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="status_modal">Status <span class="text-danger">*</span></label>
					<select class="form-control" name="status" id="status_modal" required>
						<option value="">-- Select --</option>
						<option value="1" <?php echo ((string) $record['status'] === '1') ? 'selected' : ''; ?>>Active</option>
						<option value="0" <?php echo ((string) $record['status'] === '0') ? 'selected' : ''; ?>>Inactive</option>
						<option value="2" <?php echo ((string) $record['status'] === '2') ? 'selected' : ''; ?>>Disconnected</option>
					</select>
				</div>
			</div>
			<div class="col-md-6" id="showcustomers_modal" <?php echo ($customer_type_val === 'metercustomer') ? 'style="display:none;"' : ''; ?>>
				<div class="form-group">
					<label class="form-label" for="billingplans_modal">Billing Plans</label>
					<select class="form-control" name="billingplans" id="billingplans_modal" <?php echo ($customer_type_val === 'monthlycustomer') ? 'required' : ''; ?>>
						<option value="">-- Select --</option>
						<?php foreach ($billing as $value) { ?>
						<option value="<?php echo (int) $value['id']; ?>" <?php echo ((string) $record['billingplans'] === (string) $value['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8'); ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="zone_modal">Zone <span class="text-danger">*</span></label>
					<select class="form-control" name="zone" id="zone_modal" required>
						<option value="">-- Select --</option>
						<?php foreach ($zone as $value) { ?>
						<option value="<?php echo (int) $value['id']; ?>" <?php echo ((string) $value['id'] === (string) $record['zone']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($value['zone'], ENT_QUOTES, 'UTF-8'); ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="referenceperson_modal">Reference Person</label>
					<input class="form-control" type="text" id="referenceperson_modal" name="referenceperson" value="<?php echo $h('referenceperson'); ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="meter_number_modal">Meter Number <span class="text-danger">*</span></label>
					<input class="form-control" type="text" id="meter_number_modal" name="meter_number" value="<?php echo $h('meter_number'); ?>" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="meter_brand_modal">Meter Brand <span class="text-danger">*</span></label>
					<input class="form-control" type="text" id="meter_brand_modal" name="meter_brand" value="<?php echo $h('meter_brand'); ?>" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="meter_size_modal">Meter Size <span class="text-danger">*</span></label>
					<input class="form-control" type="text" id="meter_size_modal" name="meter_size" value="<?php echo $h('meter_size'); ?>" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="date_installed_modal">Date Installed <span class="text-danger">*</span></label>
					<div class="input-group">
						<input class="form-control" type="text" name="date_installed" id="date_installed_modal" placeholder="DD-MM-YYYY" value="<?php echo htmlspecialchars($date_installed_val, ENT_QUOTES, 'UTF-8'); ?>" required>
						<div class="input-group-append">
							<span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="classification_modal">Classification <span class="text-danger">*</span></label>
					<select class="form-control" name="classification" id="classification_modal" required>
						<option value="">-- Select --</option>
						<?php foreach ($classification as $value) { ?>
						<option value="<?php echo (int) $value['class_id']; ?>" <?php echo ((string) $value['class_id'] === (string) $record['classification']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($value['class_name'], ENT_QUOTES, 'UTF-8'); ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="account_type_modal">Account Type <span class="text-danger">*</span></label>
					<select class="form-control" name="account_type" id="account_type_modal" required>
						<option value="">-- Select --</option>
						<?php foreach ($customer_type as $value) { ?>
						<option value="<?php echo (int) $value['cust_type_id']; ?>" <?php echo ((string) $value['cust_type_id'] === (string) $record['account_type']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($value['cust_type_name'], ENT_QUOTES, 'UTF-8'); ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label d-block">Special Privilege</label>
					<div class="custom-control custom-checkbox mt-2">
						<input type="checkbox" class="custom-control-input" name="special_priviledge" id="special_priviledge_modal" value="<?php echo $special_checked ? '1' : '0'; ?>" <?php echo $special_checked ? 'checked' : ''; ?>>
						<label class="custom-control-label" for="special_priviledge_modal">Enable special privilege</label>
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-2">
			<div class="col-md-12">
				<button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal">
					<i class="fal fa-times mr-1"></i> Cancel
				</button>
				<button type="submit" class="btn btn-primary waves-effect waves-themed" name="edit" id="edit_modal_btn" value="Update">
					<i class="fal fa-save mr-1"></i> Update
				</button>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
(function($) {
	var controls = {
		leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
		rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
	};

	setTimeout(function() {
		if ($.fn.datepicker) {
			$('#dob_modal, #date_installed_modal').datepicker({
				format: 'dd-mm-yyyy',
				todayHighlight: true,
				autoclose: true,
				orientation: 'bottom left',
				templates: controls
			});
			$('#dob_modal, #date_installed_modal').closest('.input-group').find('.input-group-text').on('click', function() {
				$(this).closest('.input-group').find('input').datepicker('show');
			});
			$('#date_installed_modal').on('changeDate', function() {
				regenerateCustomerIdModal();
			});
		}

		$('#special_priviledge_modal').off('change').on('change', function() {
			$(this).val($(this).is(':checked') ? 1 : 0);
		});

		$('#classification_modal, #zone_modal').off('change.regen').on('change.regen', regenerateCustomerIdModal);
		customer_type_values_modal($('#customer_type_modal').val());
	}, 50);
})(jQuery);

var originalCustomerSeriesModal = '<?php
	$customer_id_parts = explode('-', isset($record['customer_id']) ? $record['customer_id'] : '');
	echo htmlspecialchars((string) end($customer_id_parts), ENT_QUOTES, 'UTF-8');
?>';

function regenerateCustomerIdModal() {
	var member_stat = $("#membership_status_modal").val();
	if (member_stat != 1) { return; }
	var class_id = $("#classification_modal").val() || '000';
	var zone_id = $("#zone_modal").val() || '000';
	var date_installed = $("#date_installed_modal").val() || '';

	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL; ?>addcustomer/get_customer_id_generate/' + class_id + '/' + zone_id,
		data: {
			class_id: class_id,
			zone_id: zone_id,
			date_installed: date_installed,
			preserve_series: originalCustomerSeriesModal
		},
		success: function(data) {
			$('#customer_id_modal').val(data);
		}
	});
}

function readURLModal(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			$('#blah_modal').attr('src', e.target.result);
		};
		reader.readAsDataURL(input.files[0]);
		if (input.files[0].name) {
			$(input).next('.custom-file-label').text(input.files[0].name);
		}
	}
}

function customer_type_values_modal(val) {
	if (val == 'monthlycustomer') {
		$('#showcustomers_modal').show();
		$('#billingplans_modal').attr('required', 'required');
	} else if (val == 'metercustomer') {
		$('#showcustomers_modal').hide();
		$('#billingplans_modal').removeAttr('required');
	} else {
		$('#showcustomers_modal').show();
		$('#billingplans_modal').removeAttr('required');
	}
}
</script>
