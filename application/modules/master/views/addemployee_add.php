<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>addemployee/">employee</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addemployee/search/">Search employee</a></li>
		<li class="breadcrumb-item active">add</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-users"></i>
			Manage <span class="fw-300">Addemployee Add</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addemployee Add <span class="fw-300"><i>Details</i></span></h2>
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
								<form class="form-horizontal" role="form" name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
										  	
											<?php if($msg != ''){?>
											<div class="alert alert-success alert-dismissible fade show">
												<button type="button" class="close" data-dismiss="alert">
												<i class="fal fa-times"></i>
												</button>
												<p>
													<i class="fal fa-check"></i>
													<?php echo $msg?$msg:'';?>
												</p>
											</div>
											<?php } ?>	
											
											<fieldset>
														<h5 class="mb-3">Employee-Add </h5>
                                                        <!--<div class="form-group">
															<label class="col-md-4 control-label">Account Subgroup Type : </label>
															<div class="col-md-4">
																<select name="acount_group" id="acount_group" class="form-control" required>
																	 <option value="">--Select--</option>
                                                                     <?php foreach($account as $key => $value){?>
																	  <option value="<?php echo $value['id'];?>"><?php echo $value['account_name'];?></option>
                                                                      <?php } ?>
																</select>
																<?php echo form_error('acount_group'); ?>
															</div>
														</div>-->
														<input type="hidden" name="account_group" id="account_group" value="5">
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Employee-Id : </label>
																	<input class="form-control" type="text" id="employee_id" name="employee_id" value="<?php echo $this->input->post('employee_id'); ?>" required/>
																	<?php echo form_error('employee_id'); ?>
																	<span id="val_roll_img"></span>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">                                
																<div class="form-group">
																	<label class="form-label"> First Name :</label>
																	<input  class="form-control"  type="text" id="first_name" name="first_name" value="<?php echo $this->input->post('first_name'); ?>" required/>
																	<?php echo form_error('first_name'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">  
																<div class="form-group">
																	<label class="form-label"> Middle Name : </label>
																	<input  class="form-control"  type="text"  id="middle_name" name="middle_name" value="<?php echo $this->input->post('middle_name'); ?>" required/>
																	<?php echo form_error('middle_name'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Last Name : </label>
																	<input  class="form-control"  type="text"  id="last_name" name="last_name" value="<?php echo $this->input->post('last_name'); ?>" required/>
																	<?php echo form_error('last_name'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> DOB: </label>
																	<input  class="form-control"  type="text"  name="dob" id="dob"  placeholder="DD-MM-YYYY" required/>
																	<?php echo form_error('dob'); ?>
																</div>
															</div>
														</div>
														
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Gender </label>
																	<select name="gender" id="gender" class="form-control" required>
																		<option value="">--Select--</option>
																		<option value="male">male</option>
																		<option value="female">female</option>
																	</select>
																	<?php echo form_error('gender:'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Place of birth : </label>
																	<input  class="form-control"  type="text"  id="place_of_birth" name="place_of_birth" value="<?php echo $this->input->post('place_of_birth'); ?>" required/>
																	<?php echo form_error('place_of_birth'); ?>
																</div>
															</div>
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> City: </label>
																	<input  class="form-control"  type="text"  id="city" name="city" value="<?php echo $this->input->post('city'); ?>" required/>
																	<?php echo form_error('city'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">	
																	<label class="form-label"> Address :   </label>
																	<textarea class="form-control" rows="5"  id="address" name="address"><?php echo $this->input->post('address'); ?></textarea>
																	<?php echo form_error('address'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> State:</label>
																	<input  class="form-control"  type="text"  id="state" name="state" value="<?php echo $this->input->post('state'); ?>" required/>
																	<?php echo form_error('state'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Mobile1:</label>
																	<input class="form-control"  type="text" id="mobile1" name="mobile1" value="<?php echo $this->input->post('mobile1'); ?>" required/>
																	<?php echo form_error('mobile1'); ?>
																	<span id="val_mobile1_img"></span>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Mobile2: </label>
																	<input class="form-control" type="text" id="mobile2" name="mobile2" value="<?php echo $this->input->post('mobile2'); ?>" required/>
																	<?php echo form_error('mobile2'); ?>
																	<span id="val_mobile2_img"></span>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Line Number: </label>
																	<input  class="form-control" type="text" id="line_number" name="line_number" value="<?php echo $this->input->post('line_number'); ?>" required/>
																	<?php echo form_error('line_number'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Base-Salary: </label>
																	<input  class="form-control" type="text" id="base_salary" name="base_salary" value="<?php echo $this->input->post('base_salary'); ?>" required/>
																	<?php echo form_error('base_salary'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Email-Id: </label>
																	<input class="form-control" type="text" id="email_id" name="email_id" value="<?php echo $this->input->post('email_id'); ?>" required/>
																	<?php echo form_error('email_id'); ?>
																	<span id="var_email_img"></span>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="form-group" id="showcustomers" <?php if($record['customer_type']=='metercustomer'){ ?> style="display:none;" <?php } ?>></span>
																	<label class="form-label">Tax: </label>
																	 <input class="form-control" type="text" id="tax" name="tax"  value="<?php echo $this->input->post('tax'); ?>" required/>
																	 <?php echo form_error('tax'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Job-Title: </label>
																	<select name="job_title" id="job_title"  class="form-control" required>
																	<option value="">--Select--</option>
																	<?php foreach($job_title as $key => $value){?>
																	<option value="<?php echo $value['id']; ?>"><?php echo $value['job_title']; ?></option>
																	<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														
														
							
													</fieldset>

													<div class="form-group mt-3">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>addemployee" class="btn btn-secondary">Cancel</a>
																<input type="submit" class="btn btn-primary" name="add" id="add" value="Add">
															</div>
														</div>
									</div>
								</div>
							</form>
								    
								
									
						
					</div>
                    

						
					<!-- end row -->

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
	
});

</script>
<script>
$("#employee_id").on('change',function(){
	var employee = $(this).val();
	if(employee!=''){
		$('#val_roll_img').removeAttr("class").text('').append('<img title="loading" src="<?php echo base_url();?>images/favicon/loading.gif">');
		
		$.ajax({
				type: 'POST',
				url: '<?php echo ADMIN_URL; ?>addemployee/check_employee_id/',
				data: { employee_id:employee},
				success: function(data) {
								//alert(data);
								if(data=='true'){
									$("#val_roll_img").text('').attr("class","badge badge-warning").text( "Employee Id '"+ employee +" ' already exists try another !" );
									$("#employee_id").val('').focus();
								} else {
									$("#val_roll_img").removeAttr("class").text('').attr("class","badge badge-success").text( "Employee Id  '"+ employee +" ' available !" );
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
			url: '<?php echo ADMIN_URL;?>addemployee/check_customer_email/',
			data:{ emailid:email},
			success: function(data){
				if(data=='true'){
					$("#var_email_img").text('').attr("class","badge badge-warning").text("Email '"+ email +" ' already exists try another !");
				    $("#email_id").val('').focus();
				}else{
					$("#var_email_img").removeAttr("class").text('').attr("class","badge badge-success").text( "Email  '"+ email +" ' available !" );
                }
			}
			
		});
	}
	
});
$("#mobile1").on('change', function(){
	var mobile = $(this).val();
	if(mobile != ''){
		$("#val_mobile1_img").removeAttr("class").text('').append('<img title="loading" src="<?php echo base_url();?>images/favicon/loading.gif">');
	    
		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL;?>addemployee/check_customer_mobile_1/',
			data:{ mobile_1:mobile},
			success: function(data){
				if(data=='true'){
					$("#val_mobile1_img").text('').attr("class","badge badge-warning").text("Mobile '"+ mobile +" ' already exists try another !");
				    $("#mobile1").val('').focus();
				}else{
					$("#val_mobile1_img").removeAttr("class").text('').attr("class","badge badge-success").text( "Mobile  '"+ mobile +" ' available !" );
                }
			}
			
		});
	}
	
});
$("#mobile2").on('change', function(){
	var mobile = $(this).val();
	if(mobile != ''){
		$("#val_mobile2_img").removeAttr("class").text('').append('<img title="loading" src="<?php echo base_url();?>images/favicon/loading.gif">');
	    
		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL;?>addemployee/check_customer_mobile_2/',
			data:{ mobile_1:mobile},
			success: function(data){
				if(data=='true'){
					$("#val_mobile2_img").text('').attr("class","badge badge-warning").text("Mobile '"+ mobile +" ' already exists try another !");
				    $("#mobile2").val('').focus();
				}else{
					$("#val_mobile2_img").removeAttr("class").text('').attr("class","badge badge-success").text( "Mobile  '"+ mobile +" ' available !" );
                }
			}
			
		});
	}
	
});
</script>

