<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item active">Home</li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>addcustomer">customer</a></li>
		<li class="breadcrumb-item active">Edit</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user-friends"></i>
			Manage <span class="fw-300">Addcustomer Edit</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addcustomer Edit <span class="fw-300"><i>Details</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
<section id="widget-grid" class="">

					<!-- row -->

					<div class="row">

						<!-- a blank row to get started -->
						<div class="col-sm-6 col-lg-12">
						

								<!-- your contents here -->
								<div class="panel panel-default">
									
									
				
										<form class="form-horizontal" role="form" name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
										<input type="hidden" name="original_customer_id" id="original_customer_id" value="<?php echo htmlspecialchars($record['customer_id'], ENT_QUOTES); ?>">
										  	
											<?php if($msg != ''){?>
											<div class="alert alert-block alert-success">
												<button type="button" class="close" data-dismiss="alert">
												<i class="icon-remove"></i>
												</button>
												<p>
													<i class="icon-ok"></i>
													<?php echo $msg?$msg:'';?>
												</p>
											</div>
											<?php } ?>	
											
											<fieldset>
														<legend> &nbsp Customer-Edit </legend>
                                                       <div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
															    <div class="form-group">
																	<label>User Photo</label>
																	<div class="image" style = "width:150px; height:150px;">
																		<img id="blah" src="<?php echo ADMIN_IMG_URL;?>upload/<?php echo $image['file']; ?>" style = "width:150px; height:150px;"/>
																	</div>
																	<input type='file' onchange="readURL(this);" name="userfile"/>
																</div>
																<?php /*<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>Account Subgroup Type : </strong></span>
																	<select name="acount_group" id="acount_group" class="form-control" required >
																	 <option value="">--Select--</option>
                                                                     <?php foreach($account as $key => $value){?>
																	  <option value="<?php echo $value['id'];?>" <?php if($record['account_id'] == $value['id']){echo 'selected';}?>><?php echo $value['account_name'];?></option>
                                                                      <?php } ?>
																	</select>
																	<?php echo form_error('acount_group'); ?>
																</div> */?>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Membership Status: <span style="color:red;font-weight: bold;">*</span> </strong></span>
																	<select name="membership_status" id="membership_status" class="form-control" required>
																		<option value="1" <?php if($record['membership_status']=='1'){ ?> selected <?php } ?>>Member</option>
																		<option value="0" <?php if($record['membership_status']=='0'){ ?> selected <?php } ?>>Non-Member</option>
																	</select>
																	<?php echo form_error('membership_status:'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>Customer-Id :</strong></span>
																	<input  class="form-control"  type="text" id="customer_id" name="customer_id" value="<?php echo $record['customer_id']; ?>" required/>
																	<p class="text-danger" style="font-size:12px; margin-top:5px;">
																		<strong>Note:</strong> Changing Customer-Id will also update meter readings, payment transactions, and leaking ledger records for this customer.
																	</p>
																	<?php echo form_error('customer_id'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> First Name :</strong></span>
																	<input  class="form-control"  type="text" id="first_name" name="first_name" value="<?php echo $record['first_name']; ?>" required/>
																	<?php echo form_error('first_name'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>  Middle Name : </strong></span>
																	<input  class="form-control"  type="text" id="middle_name" name="middle_name" value="<?php echo $record['middle_name']; ?>"/>
																	<?php echo form_error('middle_name'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Last Name : </strong></span>
																	<input  class="form-control"  type="text" id="last_name" name="last_name" value="<?php echo $record['last_name']; ?>" required/>
																	<?php echo form_error('last_name'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> DOB: </strong></span>
																	<input  class="form-control"  type="text" name="dob" id="dob"  placeholder="DD-MM-YYYY" value="<?php echo ($this->input->post('dob') != '')?date('d-m-Y',strtotime($this->input->post('dob'))):date('d-m-Y');?>" readonly  required/>
																	<?php echo form_error('dob'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Gender: </strong></span>
																	<select name="gender" id="gender" class="form-control" required>
																		<option value="">--Select--</option>
																		<option value="male"<?php if($record['gender']=='male'){ ?> selected <?php } ?>>Male</option>
																		<option value="Female"<?php if($record['gender']=='Female'){ ?> selected <?php } ?>>Female</option>
																	</select>
																	<?php echo form_error('gender:'); ?>
																</div>
															</div>
														</div>
														
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Place of birth :  </strong></span>
																	<input  class="form-control" type="text"  id="place_of_birth" name="place_of_birth" value="<?php echo $record['place_of_birth']; ?>" required/>
																	<?php echo form_error('place_of_birth'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>  Address :  </strong></span>
																	<textarea class="form-control" rows="5"  id="address" name="address"><?php echo $record['address']; ?></textarea>
																	<?php echo form_error('address'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Town/City:  </strong></span>
																	<input  class="form-control" type="text"  id="city" name="city" value="<?php echo $record['city']; ?>" required/>
																	<?php echo form_error('city'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Province: </strong></span>
																	<input  class="form-control" type="text"  id="state" name="state" value="<?php echo $record['state']; ?>" required/>
																	<?php echo form_error('state'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Mobile1: </strong></span>
																	<input class="form-control"  type="text" id="mobile1" name="mobile1" value="<?php echo $record['mobile1']; ?>" required/>
																	<?php echo form_error('mobile1'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Mobile2: </strong></span>
																	<input class="form-control" type="text" id="mobile2" name="mobile2" value="<?php echo $record['mobile2']; ?>"/>
																	<?php echo form_error('mobile2'); ?>
																</div>
															</div>
														</div>
													
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Line Number: </strong></span>
																	<input  class="form-control" type="text" id="line_number" name="line_number" value="<?php echo $record['line_number']; ?>" />
																	<?php echo form_error('line_number'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Customer Type: </strong></span>
																	<select class="form-control" name="customer_type" id="customer_type"required onchange="customer_type_values(this.value)">
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
																	<input class="form-control" type="text" id="email_id" name="email_id" value="<?php echo $record['email_id']; ?>"/>
																	<?php echo form_error('email_id'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Status: </strong></span>
																	<select class="form-control" name="status" id="status"  required>
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
																	<span  id="showcustomers" <?php if($record['customer_type']=='metercustomer'){ ?> style="display:none;" <?php } ?>></span>
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>Billing Plans: </strong></span>
																	<select class="form-control" name="billingplans" id="billingplans"<?php if($record['customer_type']=='monthlycustomer'){ ?> required <?php } ?>>
																		<option value="">--Select--</option>
																		<?php foreach($billing as $key => $value){ ?>
																		<option value="<?php  echo $value['id']; ?>" <?php if($record['billingplans']==$value['id']){ ?> selected <?php } ?>><?php  echo $value['name']; ?></option>
																		<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">  
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Zone: </strong></span>
																	<select class="form-control" name="zone" id="zone" required>
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
																	<input class="form-control" type="text" id="referenceperson" name="referenceperson" class="col-10 col-sm-10" value="<?php echo $record['referenceperson']; ?>"/>
																	<?php echo form_error('referenceperson'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Meter Number: <span style="color:red;font-weight: bold;">*</span> </strong></span>
																	<input class="form-control" type="text" id="meter_number" name="meter_number" value="<?php echo $record['meter_number']; ?>" required/>
																	<?php echo form_error('meter_number'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Meter Brand: <span style="color:red;font-weight: bold;">*</span></strong></span>
																	<input class="form-control" type="text" id="meter_brand" name="meter_brand" value="<?php echo $record['meter_brand']; ?>" required/>
																	<?php echo form_error('meter_brand'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Meter Size: <span style="color:red;font-weight: bold;">*</span></strong></span>
																	<input class="form-control" type="text" id="meter_size" name="meter_size" value="<?php echo $record['meter_size']; ?>" required/>
																	<?php echo form_error('meter_size'); ?>
																</div>
															</div>
														</div>
														
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>Date Installed: <span style="color:red;font-weight: bold;">*</span></strong></span>
																	<input  class="form-control"  type="text" name="date_installed" id="date_installed"  placeholder="DD-MM-YYYY" value="<?php echo ($record['date_installed'] != '') ? date('d-m-Y', strtotime($record['date_installed'])) : date('d-m-Y');?>" readonly  required/>
																	<?php echo form_error('date_installed'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">  
																	<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Classification: </strong></span>
																	<select class="form-control" name="classification" id="classification" required>
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
																	<select class="form-control" name="account_type" id="account_type" required>
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
																		<input class="form-check" value="<?php echo $record['special_priviledge']; ?>" type="checkbox" name="special_priviledge"  id="special_priviledge" <?php echo $check_sp; ?>>
																		
																	</div>
																</div>
															</div>
													</fieldset>
													
													
													
													<div class="form-actions">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>addcustomer" class="btn btn-secondary">Cancel</a>
																<input type="submit" class="btn btn-primary" name="edit" id="edit" value="Update">
															</div>
														</div>
													
										</form>
				
									</div>
								    
								
								</div>	
						</div>
					</div>
                    

						
					<!-- end row -->

				</section>
				<!-- end widget grid -->

					

				</section>
				<!-- end widget grid -->
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
</body>
</html>
<script type="text/javascript">
var curDate = '<?php echo date('d-m-Y') ?>';	
var originalCustomerSeries = '<?php
	$customer_id_parts = explode('-', $record['customer_id']);
	echo end($customer_id_parts);
?>';
function fun_calendor(field){
	$("#"+field).focus();
}
function regenerateCustomerId() {
	var member_stat = $("#membership_status").val();
	if(member_stat != 1){
		return;
	}
	var class_id = $("#classification").val();
	if(class_id === ''){
		class_id = '000';
	}
	var zone_id = $("#zone").val();
	if(zone_id === ''){
		zone_id = '000';
	}
	var date_installed = $("#date_installed").val() || '';

	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addcustomer/get_customer_id_generate/'+class_id+'/'+zone_id,
		data: {
			class_id: class_id,
			zone_id: zone_id,
			date_installed: date_installed,
			preserve_series: originalCustomerSeries
		},
		success: function(data){
			$('#customer_id').val(data);
		}
	});
}
$(document).ready(function(){
	$("#dob").datepicker({
		showAnim: null,
		dateFormat: 'dd-mm-yy',
		// showOn: 'both',
		buttonImage: '<?php echo site_url();?>images/calender.jpg',
		buttonImageOnly: true,
		firstDay: 1,
		nextText: '',
		prevText: '',
		numberOfMonths: [1, 1],
		//defaultDate: new Date(curDate),
		//minDate: curDate,
		//maxDate: ''
	});
	$("#date_installed").datepicker({
		showAnim: null,
		dateFormat: 'dd-mm-yy',
		// showOn: 'both',
		buttonImage: '<?php echo site_url();?>images/calender.jpg',
		buttonImageOnly: true,
		firstDay: 1,
		nextText: '',
		prevText: '',
		numberOfMonths: [1, 1],
		onSelect: function() {
			regenerateCustomerId();
		}
	});

	$("#classification").on('change', regenerateCustomerId);
	$("#zone").on('change', regenerateCustomerId);

	$('#myform').on('submit', function(e) {
		var newCustomerId = $.trim($('#customer_id').val());
		var oldCustomerId = $.trim($('#original_customer_id').val());

		if (newCustomerId !== oldCustomerId) {
			var confirmMsg = 'You have changed the Customer-Id from "' + oldCustomerId + '" to "' + newCustomerId + '".\n\n'
				+ 'This will update all meter readings, payment transactions, and leaking ledger records for this customer.\n\n'
				+ 'Do you want to continue?';
			if (!confirm(confirmMsg)) {
				e.preventDefault();
				return false;
			}
		}
	});
	
});
	$('#special_priviledge').on('change', function(){
	if($(this).is(':checked')){
		$(this).val(1);
	}else{
		$(this).val(0);
	}});
</script>
<script type="text/javascript">
          function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(150);
                };

                reader.readAsDataURL(input.files[0]);
            }
          }
          </script>

