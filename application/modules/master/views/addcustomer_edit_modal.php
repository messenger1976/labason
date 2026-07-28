<div class="customer-edit-modal">
	<div id="edit_customer_msg" style="display:none;"></div>
	<p class="text-danger" style="font-size:12px; margin-bottom:10px;">
		<strong>Note:</strong> If the Customer-Id is changed, all meter readings, payment transactions, and leaking ledger records for this customer will be updated automatically to keep the ledger in sync.
	</p>
	<form class="form-horizontal" role="form" name="editCustomerForm" id="editCustomerForm" method="post" enctype="multipart/form-data">
		<input type="hidden" name="id" id="edit_customer_id" value="<?php echo $record['id']; ?>">
		<input type="hidden" name="original_customer_id" id="original_customer_id_modal" value="<?php echo htmlspecialchars($record['customer_id'], ENT_QUOTES); ?>">
		<fieldset>
			<legend> &nbsp Customer-Edit </legend>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<label>User Photo</label>
						<div class="image" style="width:150px; height:150px;">
							<img id="blah_modal" src="<?php echo ADMIN_IMG_URL;?>upload/<?php echo $image['file']; ?>" style="width:150px; height:150px;"/>
						</div>
						<input type='file' onchange="readURLModal(this);" name="userfile"/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Membership Status: <span style="color:red;font-weight: bold;">*</span> </strong></span>
						<select name="membership_status" id="membership_status_modal" class="form-control" required>
							<option value="1" <?php if($record['membership_status']=='1'){ ?> selected <?php } ?>>Member</option>
							<option value="0" <?php if($record['membership_status']=='0'){ ?> selected <?php } ?>>Non-Member</option>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>Customer-Id :</strong></span>
						<input class="form-control" type="text" id="customer_id_modal" name="customer_id" value="<?php echo $record['customer_id']; ?>" required/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> First Name :</strong></span>
						<input class="form-control" type="text" id="first_name_modal" name="first_name" value="<?php echo $record['first_name']; ?>" required/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>  Middle Name : </strong></span>
						<input class="form-control" type="text" id="middle_name_modal" name="middle_name" value="<?php echo $record['middle_name']; ?>"/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Last Name : </strong></span>
						<input class="form-control" type="text" id="last_name_modal" name="last_name" value="<?php echo $record['last_name']; ?>" required/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> DOB: </strong></span>
						<input class="form-control" type="text" name="dob" id="dob_modal" placeholder="DD-MM-YYYY" value="<?php echo ($record['DOB'] != '' && $record['DOB'] != '0000-00-00') ? htmlspecialchars(date("d-m-Y", strtotime($record['DOB'])), ENT_QUOTES) : ''; ?>" required/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Gender: </strong></span>
						<select name="gender" id="gender_modal" class="form-control" required>
							<option value="">--Select--</option>
							<option value="male"<?php if($record['gender']=='male'){ ?> selected <?php } ?>>Male</option>
							<option value="Female"<?php if($record['gender']=='Female'){ ?> selected <?php } ?>>Female</option>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Place of birth :  </strong></span>
						<input class="form-control" type="text" id="place_of_birth_modal" name="place_of_birth" value="<?php echo $record['place_of_birth']; ?>" required/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>  Address :  </strong></span>
						<textarea class="form-control" rows="5" id="address_modal" name="address"><?php echo $record['address']; ?></textarea>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Town/City:  </strong></span>
						<input class="form-control" type="text" id="city_modal" name="city" value="<?php echo $record['city']; ?>" required/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Province: </strong></span>
						<input class="form-control" type="text" id="state_modal" name="state" value="<?php echo $record['state']; ?>" required/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Mobile1: </strong></span>
						<input class="form-control" type="text" id="mobile1_modal" name="mobile1" value="<?php echo $record['mobile1']; ?>" required/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Mobile2: </strong></span>
						<input class="form-control" type="text" id="mobile2_modal" name="mobile2" value="<?php echo $record['mobile2']; ?>"/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Line Number: </strong></span>
						<input class="form-control" type="text" id="line_number_modal" name="line_number" value="<?php echo $record['line_number']; ?>" />
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Customer Type: </strong></span>
						<select class="form-control" name="customer_type" id="customer_type_modal" required onchange="customer_type_values_modal(this.value)">
							<option value="">--Select--</option>
							<option value="monthlycustomer"<?php if($record['customer_type']=='monthlycustomer'){ ?> selected <?php } ?>>monthlycustomer</option>
							<option value="metercustomer"<?php if($record['customer_type']=='metercustomer'){ ?> selected <?php } ?>>metercustomer</option>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Email-Id: </strong></span>
						<input class="form-control" type="text" id="email_id_modal" name="email_id" value="<?php echo $record['email_id']; ?>"/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Status: </strong></span>
						<select class="form-control" name="status" id="status_modal" required>
							<option value="">--Select--</option>
							<option value="1"<?php if($record['status']=='1'){ ?> selected <?php } ?>>Active</option>
							<option value="0"<?php if($record['status']=='0'){ ?> selected <?php } ?>>Inactive</option>
							<option value="2"<?php if($record['status']=='2'){ ?> selected <?php } ?>>Disconnected</option>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span id="showcustomers_modal" <?php if($record['customer_type']=='metercustomer'){ ?> style="display:none;" <?php } ?>></span>
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>Billing Plans: </strong></span>
						<select class="form-control" name="billingplans" id="billingplans_modal"<?php if($record['customer_type']=='monthlycustomer'){ ?> required <?php } ?>>
							<option value="">--Select--</option>
							<?php foreach($billing as $key => $value){ ?>
							<option value="<?php echo $value['id']; ?>" <?php if($record['billingplans']==$value['id']){ ?> selected <?php } ?>><?php echo $value['name']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">  
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Zone: </strong></span>
						<select class="form-control" name="zone" id="zone_modal" required>
							<option value="">--Select--</option>
							<?php foreach($zone as $key => $value){ ?>
							<option value="<?php echo $value['id'];?>" <?php if($value['id']==$record['zone']){ ?> selected <?php } ?>><?php echo $value['zone'];?></option>
							<?php } ?>											  
						</select>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Reference person: </strong></span>
						<input class="form-control" type="text" id="referenceperson_modal" name="referenceperson" value="<?php echo $record['referenceperson']; ?>"/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Meter Number: <span style="color:red;font-weight: bold;">*</span> </strong></span>
						<input class="form-control" type="text" id="meter_number_modal" name="meter_number" value="<?php echo $record['meter_number']; ?>" required/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Meter Brand: <span style="color:red;font-weight: bold;">*</span></strong></span>
						<input class="form-control" type="text" id="meter_brand_modal" name="meter_brand" value="<?php echo $record['meter_brand']; ?>" required/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Meter Size: <span style="color:red;font-weight: bold;">*</span></strong></span>
						<input class="form-control" type="text" id="meter_size_modal" name="meter_size" value="<?php echo $record['meter_size']; ?>" required/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>Date Installed: <span style="color:red;font-weight: bold;">*</span></strong></span>
						<input class="form-control" type="text" name="date_installed" id="date_installed_modal" placeholder="DD-MM-YYYY" value="<?php echo (isset($record['date_installed']) && $record['date_installed'] != '' && $record['date_installed'] != '0000-00-00') ? htmlspecialchars(date("d-m-Y", strtotime($record['date_installed'])), ENT_QUOTES) : ''; ?>" required/>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">  
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Classification: </strong></span>
						<select class="form-control" name="classification" id="classification_modal" required>
							<option value="">--Select--</option>
							<?php foreach($classification as $key => $value){ ?>
							<option value="<?php echo $value['class_id'];?>" <?php if($value['class_id']==$record['classification']){ ?> selected <?php } ?>><?php echo $value['class_name'];?></option>
							<?php } ?>											  
						</select>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Account Type: <span style="color:red;font-weight: bold;">*</span></strong></span>
						<select class="form-control" name="account_type" id="account_type_modal" required>
							<option value="">--Select--</option>
							<?php foreach($customer_type as $key => $value){ ?>
							<option value="<?php echo $value['cust_type_id'];?>" <?php if($value['cust_type_id']==$record['account_type']){ ?> selected <?php } ?>><?php echo $value['cust_type_name'];?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<div class="col-lg-12 controls">
					<div class="form-group">
						<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Special Priviledge: </strong></span>
						<i>&nbsp;</i>
						<?php
						  $check_sp = $record['special_priviledge']?'checked="checked"':'';
						?>
						<input class="form-check" value="<?php echo $record['special_priviledge']; ?>" type="checkbox" name="special_priviledge" id="special_priviledge_modal" <?php echo $check_sp; ?>>
					</div>
				</div>
			</div>
		</fieldset>
		
		<div class="form-actions">
			<div class="row">
				<div class="col-md-12">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary" name="edit" id="edit_modal_btn">Update</button>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
(function() {
	// Initialize datepickers after modal content is loaded
	setTimeout(function() {
		if($("#dob_modal").length) {
			$("#dob_modal").datepicker({
				showAnim: null,
				dateFormat: 'dd-mm-yy',
				buttonImage: '<?php echo htmlspecialchars(site_url(), ENT_QUOTES); ?>images/calender.jpg',
				buttonImageOnly: true,
				firstDay: 1,
				nextText: '',
				prevText: '',
				numberOfMonths: [1, 1],
			});
		}
		if($("#date_installed_modal").length) {
			$("#date_installed_modal").datepicker({
				showAnim: null,
				dateFormat: 'dd-mm-yy',
				buttonImage: '<?php echo htmlspecialchars(site_url(), ENT_QUOTES); ?>images/calender.jpg',
				buttonImageOnly: true,
				firstDay: 1,
				nextText: '',
				prevText: '',
				numberOfMonths: [1, 1],
				onSelect: function() {
					regenerateCustomerIdModal();
				}
			});
		}
		
		if($('#special_priviledge_modal').length) {
			$('#special_priviledge_modal').on('change', function(){
				if($(this).is(':checked')){
					$(this).val(1);
				}else{
					$(this).val(0);
				}
			});
		}

		if($("#classification_modal").length) {
			$("#classification_modal").off('change').on('change', regenerateCustomerIdModal);
		}

		if($("#zone_modal").length) {
			$("#zone_modal").off('change').on('change', regenerateCustomerIdModal);
		}
	}, 100);
})();

var originalCustomerSeriesModal = '<?php
	$customer_id_parts = explode('-', $record['customer_id']);
	echo end($customer_id_parts);
?>';

function regenerateCustomerIdModal() {
	var member_stat = $("#membership_status_modal").val();
	if(member_stat != 1){
		return;
	}
	var class_id = $("#classification_modal").val();
	if(class_id === ''){
		class_id = '000';
	}
	var zone_id = $("#zone_modal").val();
	if(zone_id === ''){
		zone_id = '000';
	}
	var date_installed = $("#date_installed_modal").val() || '';

	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addcustomer/get_customer_id_generate/'+class_id+'/'+zone_id,
		data: {
			class_id: class_id,
			zone_id: zone_id,
			date_installed: date_installed,
			preserve_series: originalCustomerSeriesModal
		},
		success: function(data){
			$('#customer_id_modal').val(data);
		}
	});
}

function fun_calendor_modal(field){
	$("#"+field).focus();
}

function readURLModal(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('#blah_modal')
				.attr('src', e.target.result)
				.width(150)
				.height(150);
		};
		reader.readAsDataURL(input.files[0]);
	}
}

function customer_type_values_modal(val){
	if(val == 'metercustomer'){
		$('#showcustomers_modal').show();
		$('#billingplans_modal').removeAttr('required');
	} else if(val == 'monthlycustomer'){
		$('#showcustomers_modal').hide();
		$('#billingplans_modal').attr('required', 'required');
	}
}
</script>

