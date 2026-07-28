<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>addcustomer">customer</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addcustomer/search/">Search Customer</a></li>
		<li class="breadcrumb-item active">add</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user-friends"></i>
			Manage <span class="fw-300">Addcustomer Add</span>
		</h1>
	</div>

<section id="widget-grid" class="">

					<!-- row -->

					<div class="row">

						<!-- a blank row to get started -->
						<div class="col-xl-12">
						

								<!-- your contents here -->
								<div class="panel">
									<div>
										<!-- widget edit box -->
										<div class="panel">
											<!-- This area used as dropdown edit box -->
					
										</div>

										
					
											<form class="form-horizontal" role="form" name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
												
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
															<legend> &nbsp Add Customer</legend>
															<?php /*<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>Account Subgroup Type</strong></span>
																		<select name="acount_group" id="acount_group" class="form-control" required>
																			<option value="">--Select--</option>
																			<?php //pr($account);exit; 
																			foreach($account as $key => $value){ ?>
																			<option value="<?php echo $value['id'];?>"><?php echo $value['account_name'];?></option>
																			<?php } ?>
																		</select>
																		<?php echo form_error('acount_group'); ?>
																	</div>
																</div>
															</div>
															*/ ?>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Membership Status: <span style="color:red;font-weight: bold;">*</span> </strong></span>
																		<select name="membership_status" id="membership_status" class="form-control" required>
																		<option value="1">Member</option>
																		<option value="0">Non-Member</option>
																		</select>
																		<?php echo form_error('membership_status:'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-user"></i><strong>Customer-Id : <span style="color:red;font-weight: bold;">*</span></strong></span>
																		<input  class="form-control"  type="text" id="customer_id" name="customer_id" value="<?php echo $customer_id; ?>" readonly required/>
																		<?php echo form_error('customer_id'); ?>
																		<span id="val_roll_img"></span>
																	</div>		
																</div>		
															</div>		
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> First Name :<span style="color:red;font-weight: bold;">*</span></strong></span>
																		<input  class="form-control"  type="text" id="first_name" name="first_name" value="<?php echo $this->input->post('first_name'); ?>" required/>
																		<?php echo form_error('first_name'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>Middle Name : </strong></span>
																		<input  class="form-control"  type="text" id="middle_name" name="middle_name" value="<?php echo $this->input->post('middle_name'); ?>"/>
																		<?php echo form_error('middle_name'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>Last Name : <span style="color:red;font-weight: bold;">*</span></strong></span>
																		<input  class="form-control"  type="text" id="last_name" name="last_name" value="<?php echo $this->input->post('last_name'); ?>" required/>
																		<?php echo form_error('last_name'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>DOB: <span style="color:red;font-weight: bold;">*</span></strong></span>
																		<input  class="form-control"  type="text" name="dob" id="dob"  placeholder="DD-MM-YYYY" required/>
																		<?php echo form_error('dob'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Gender: <span style="color:red;font-weight: bold;">*</span> </strong></span>
																		<select name="gender" id="gender" class="form-control" required>
																		<option value="">--Select--</option>
																		<option value="male">Male</option>
																		<option value="female">Female</option>
																		</select>
																		<?php echo form_error('gender:'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>Place of birth :  <span style="color:red;font-weight: bold;">*</span></strong></span>
																		<input  class="form-control"  type="text" id="place_of_birth" name="place_of_birth" value="<?php echo $this->input->post('place_of_birth'); ?>" required/>
																		<?php echo form_error('place_of_birth'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">		
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Address :  <span style="color:red;font-weight: bold;">*</span></strong></span>
																		<textarea class="form-control" rows="5"  id="address" name="address"><?php echo $this->input->post('address'); ?></textarea>
																		<?php echo form_error('address'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> City:<span style="color:red;font-weight: bold;">*</span></strong></span>
																		<input  class="form-control"  type="text" id="city" name="city" value="<?php echo $this->input->post('city'); ?>" required/>
																		<?php echo form_error('city'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">		
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>Province:<span style="color:red;font-weight: bold;">*</span> </strong></span>
																		<input  class="form-control"  id="state" name="state" value="<?php echo $this->input->post('state'); ?>" required/>
																		<?php echo form_error('state'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Mobile1:<span style="color:red;font-weight: bold;">*</span> </strong></span>
																		<input class="form-control"  type="text" id="mobile1" name="mobile1" value="<?php echo $this->input->post('mobile1'); ?>" required/>
																		<?php echo form_error('mobile1'); ?>
																		<span id="val_mobile1_img"></span>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>  Mobile2: </strong></span>
																		<input class="form-control" type="text" id="mobile2" name="mobile2" value="<?php echo $this->input->post('mobile2'); ?>"/>
																		<?php echo form_error('mobile2'); ?>
																		<span id="val_mobile2_img"></span>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Line Number: </strong></span>
																		<input  class="form-control" type="text" id="line_number" name="line_number" value="<?php echo $this->input->post('line_number'); ?>"/>
																		<?php echo form_error('line_number'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Payment Type: <span style="color:red;font-weight: bold;">*</span></strong></span>
																		<select class="form-control" name="customer_type" id="customer_type" onchange="customer_type_values(this.value)" required>
																		<option value="">--Select--</option>
																		<option value="monthlycustomer">monthlycustomer</option>
																		<option value="metercustomer">metercustomer</option>
																		</select>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Email-Id: </strong></span>
																		<input class="form-control" type="text" id="email_id" name="email_id" value="<?php echo $this->input->post('email_id'); ?>"/>
																		<?php echo form_error('email_id'); ?>
																		<span id="var_email_img"></span>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<?php if($record['customer_type']=='metercustomer'){ ?> style="display:none;" <?php } ?>
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>Billing Plans: </strong></span>
																		<select class="form-control" name="billingplans" id="billingplans">
																		<option value="">--Select--</option>
																			<?php foreach($billing as $key => $value){ ?>
																			<option value="<?php  echo $value['id']; ?>"><?php  echo $value['name']; ?></option>
																			<?php } ?>
																		
																		</select>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Zone: <span style="color:red;font-weight: bold;">*</span></strong></span>
																		<select class="form-control" name="zone" id="zone" required>
																		<option value="">--Select--</option>
																		<?php foreach($zone as $key => $value){ ?>
																		<option value="<?php echo $value['id'];?>"><?php echo $value['zone'];?></option>
																		<?php } ?>
																		</select>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Reference person: </strong></span>
																		<input class="form-control" type="text" id="referenceperson" name="referenceperson" value="<?php echo $this->input->post('referenceperson'); ?>"/>
																		<?php echo form_error('referenceperson'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Meter Number: <span style="color:red;font-weight: bold;">*</span> </strong></span>
																		<input class="form-control" type="text" id="meter_number" name="meter_number" value="<?php echo $this->input->post('meter_number'); ?>" required/>
																		<?php echo form_error('meter_number'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Meter Brand: <span style="color:red;font-weight: bold;">*</span></strong></span>
																		<input class="form-control" type="text" id="meter_brand" name="meter_brand" value="<?php echo $this->input->post('meter_brand'); ?>" required/>
																		<?php echo form_error('meter_brand'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Meter Size: <span style="color:red;font-weight: bold;">*</span></strong></span>
																		<input class="form-control" type="text" id="meter_size" name="meter_size" value="<?php echo $this->input->post('meter_size'); ?>" required/>
																		<?php echo form_error('meter_size'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong>Date Installed: <span style="color:red;font-weight: bold;">*</span></strong></span>
																		<input  class="form-control"  type="text" name="date_installed" id="date_installed"  placeholder="DD-MM-YYYY" required/>
																		<?php echo form_error('date_installed'); ?>
																	</div>
																</div>
															</div>
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Classification: <span style="color:red;font-weight: bold;">*</span></strong></span>
																		<select class="form-control" name="classification" id="classification" required>
																		<option value="">--Select--</option>
																		<?php foreach($classification as $key => $value){ ?>
																		<option value="<?php echo $value['class_id'];?>"><?php echo $value['class_name'];?></option>
																		<?php } ?>
																		</select>
																		<span id="val_classification_img"></span>
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
																		<option value="<?php echo $value['cust_type_id'];?>"><?php echo $value['cust_type_name'];?></option>
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
																		
																		<input class="form-check" value="<?php echo $this->input->post('special_priviledge'); ?>" type="checkbox" name="special_priviledge"  id="special_priviledge" >
																		
																	</div>
																</div>
															</div>
															

															
															<div class="form-group col-lg-6">
																<div class="col-lg-12 controls">
																	<div class="form-group">
																		<span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Status:<span style="color:red;font-weight: bold;">*</span> </strong></span>
																		<select class="form-control" name="status" id="status"  required>
																		<option value="">--Select--</option>
																		<option value="1">Active</option>
																		<option value="0">Inactive</option>
																		</select>
																		</select>
																	</div>
																	<div class="form-group">
																		<label for="inputEmail">Upload Image</label>
																		<div class="image" style="width:150px; height:150px;">	
																			<img id="blah" src="<?php echo ADMIN_IMG_URL;?>upload/a.png" style = "z-index:99; width:150px; height:150px; border: 1px solid #ddd;"/>
																		</div>			
																		<input type='file' onchange="readURL(this);" name="userfile"/>
																	</div>
																</div>
															</div>
															
								
														</fieldset>

														<div class="form-actions">
															<div class="row">
																<div class="col-md-12">
																	
																	<a href="<?php echo ADMIN_URL;?>addcustomer" class="btn btn-secondary">Cancel</a>
																	<input type="submit" class="btn btn-primary" name="add" id="add" value="Add">
																</div>
															</div>
														
											</form>
					
										</div>
								    </div>
								
								</div>	
						</div>
					</div>
                    	
					<!-- end row -->

				</section>
				<!-- end widget grid -->

					

				</section>
				<!-- end widget grid -->
</main>
<?php include('footer.php'); ?>
</body>
</html>
<script type="text/javascript">
		
		function customer_type_values(){
			$("#showcustomers").hide();			
			if($("#customer_type").val()=='monthlycustomer'){
				$("#showcustomers").show();
			}
			else if($("#customer_type").val()=='metercustomer'){
				$("#showcustomers").hide();
			}
		}
	
		</script>
<script type="text/javascript">
var curDate = '<?php echo date('d-m-Y') ?>';	
function fun_calendor(field){
	$("#"+field).focus();
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
	
});
</script>
<script>
var lockedCustomerSeries = '<?php
	$customer_id_parts = explode('-', $customer_id);
	echo end($customer_id_parts);
?>';

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
			preserve_series: lockedCustomerSeries
		},
		success: function(data){
			$('#customer_id').val(data);
		}
	});
}

$("#classification").on('change', regenerateCustomerId);
$("#zone").on('change', regenerateCustomerId);

$("#customer_id").on('change',function(){
	var customer = $(this).val();
	if(customer!=''){
		$('#val_roll_img').removeAttr("class").text('').append('<img title="loading" src="<?php echo base_url();?>images/favicon/loading.gif">');
		
		$.ajax({
				type: 'POST',
				url: '<?php echo ADMIN_URL; ?>addcustomer/check_customer_id/'+customer,
				data: { customer_id:customer},
				success: function(data) {
								//alert(data);
								if(data=='true'){
									$("#val_roll_img").text('').attr("class","label label-warning").text( "Customer Id '"+ customer +" ' already exists try another !" );
									$("#customer_id").val('').focus();
								} else {
									$("#val_roll_img").removeAttr("class").text('').attr("class","label label-success").text( "Customer Id  '"+ customer +" ' available !" );
								}
								
							}
				  });
				  
	} else {
		$('#val_roll_img').removeAttr("class").text('');
	}
	
});
$("#email_id").on('change', function(){
	var email = $(this).val();
	if(email != ''){
		$("#var_email_img").removeAttr("class").text('').append('<img title="loading" src="<?php echo base_url();?>images/favicon/loading.gif">');
	    
		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL;?>addcustomer/check_customer_email/',
			data:{ emailid:email},
			success: function(data){
				if(data=='true'){
					$("#var_email_img").text('').attr("class","label label-warning").text("Email '"+ email +" ' already exists try another !");
				    $("#email_id").val('').focus();
				}else{
					$("#var_email_img").removeAttr("class").text('').attr("class","label label-success").text( "Email  '"+ email +" ' available !" );
                }
			}
			
		});
	}
	
});

// Mobile1 validation removed per request
// $("#mobile1").on('change', function(){
// 	var mobile = $(this).val();
// 	if(mobile != ''){
// 		$("#val_mobile1_img").removeAttr("class").text('').append('<img title="loading" src="<?php echo base_url();?>images/favicon/loading.gif">');
// 	    
// 		$.ajax({
// 			type: 'POST',
// 			url: '<?php echo ADMIN_URL;?>addcustomer/check_customer_mobile_1/'+mobile,
// 			data:{ mobile_1:mobile},
// 			success: function(data){
// 				if(data=='true'){
// 					$("#val_mobile1_img").text('').attr("class","label label-warning").text("Mobile '"+ mobile +" ' already exists try another !");
// 				    $("#mobile1").val('').focus();
// 				}else{
// 					$("#val_mobile1_img").removeAttr("class").text('').attr("class","label label-success").text( "Mobile  '"+ mobile +" ' available !" );
//                 }
// 			}
// 			
// 		});
// 	}
// 	
// });
$("#mobile2").on('change', function(){
	var mobile = $(this).val();
	if(mobile != ''){
		$("#val_mobile2_img").removeAttr("class").text('').append('<img title="loading" src="<?php echo base_url();?>images/favicon/loading.gif">');
	    
		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL;?>addcustomer/check_customer_mobile_2/'+mobile,
			data:{ mobile_1:mobile},
			success: function(data){
				if(data=='true'){
					$("#val_mobile2_img").text('').attr("class","label label-warning").text("Mobile '"+ mobile +" ' already exists try another !");
				    $("#mobile2").val('').focus();
				}else{
					$("#val_mobile2_img").removeAttr("class").text('').attr("class","label label-success").text( "Mobile  '"+ mobile +" ' available !" );
                }
			}
			
		});
	}
	
});

// Membership status change handler - auto-generation removed per request
// $("#membership_status").on('change', function(evt){
// 	evt.preventDefault();
// 	var member_stat = $(this).val();
// 	$('#customer_id').val('');
// 	if(member_stat==1){
//
// 		var class_id = $("#classification").val();
//
// 		if(class_id===''){
// 			class_id='000';
// 		}
// 		var zone_id = $("#zone").val();
// 		if(zone_id===''){
// 			zone_id='000';
// 		}
// 		
// 		
// 			
// 		$.ajax({
// 			type: 'POST',
// 			url: '<?php echo ADMIN_URL;?>addcustomer/get_customer_id_generate/'+class_id+'/'+zone_id,
// 			data:{ class_id:class_id,
// 				zone_id:zone_id
// 			},
// 			success: function(data){
// 				$('#customer_id').val(data);
// 				//console.log(data);
// 			}
// 			
// 		});
// 		
// 	}
// 	
// });

$('#special_priviledge').on('change', function(){
	if($(this).is(':checked')){
		$(this).val(1);
	}else{
		$(this).val(0);
	}
	
});
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

