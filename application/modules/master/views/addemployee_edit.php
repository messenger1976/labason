<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addemployee/">employee</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addemployee/search/">Search Employee</a></li>
		<li class="breadcrumb-item active">edit</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-users"></i>
			Manage <span class="fw-300">Addemployee Edit</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addemployee Edit <span class="fw-300"><i>Details</i></span></h2>
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
														<h5 class="mb-3">Employee-Edit </h5>
                                                        <!--<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Account Subgroup Type :</label>
																		<select name="acount_group" id="acount_group" class="form-control" required >
																			<option value="">--Select--</option>
																			<?php foreach($account as $key => $value){?>
																			<option value="<?php echo $value['id'];?>" <?php if($record['account_id'] == $value['id']){echo 'selected';}?>><?php echo $value['account_name'];?></option>
																			<?php } ?>
																		</select>
																		<?php echo form_error('acount_group'); ?>
																</div>
															</div>
														</div>-->
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Customer-Id : </label>
																	<input class="form-control"  type="text" id="customer_id" name="employee_id" value="<?php echo $record['employee_id']; ?>" required/>
																	<?php echo form_error('employee_id'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> First Name :</label>
																	<input  class="form-control"  type="text" id="first_name" name="first_name" value="<?php echo $record['first_name']; ?>" required/>
																	<?php echo form_error('first_name'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Middle Name : </label>
																	<input  class="form-control"  type="text" id="middle_name" name="middle_name" value="<?php echo $record['middle_name']; ?>"/>
																	<?php echo form_error('middle_name'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Last Name : </label>
																	<input  class="form-control" type="text" id="last_name" name="last_name" value="<?php echo $record['last_name']; ?>" required/>
																	<?php echo form_error('last_name'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> DOB: </label>
																	<input  class="form-control"  type="text" name="dob" id="dob"  placeholder="DD-MM-YYYY" value="<?php echo ($this->input->post('dob') != '')?date('d-m-Y',strtotime($this->input->post('dob'))):date('d-m-Y');?>"  required/>
																	<?php echo form_error('dob'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">  Gender: </label>
																	<select name="gender" id="gender" class="form-control" required>
																		<option value="">--Select--</option>
																		<option value="male"<?php if($record['gender']=='male'){ ?> selected <?php } ?>>male</option>
																		<option value="female"<?php if($record['gender']=='female'){ ?> selected <?php } ?>>female</option>
																	</select>
																	<?php echo form_error('gender:'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Province: </label>
																	<input  class="form-control"  type="text" id="state" name="state" value="<?php echo $record['state']; ?>" required/>
																	<?php echo form_error('state'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Address :  </label>
																	<textarea class="form-control" rows="5"  id="address" name="address"><?php echo $record['address']; ?></textarea>
																	<?php echo form_error('address'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Place of birth : </label>
																	<input  class="form-control"  type="text" id="place_of_birth" name="place_of_birth" value="<?php echo $record['place_of_birth']; ?>" required/>
																	<?php echo form_error('place_of_birth'); ?>
																</div>
															</div>
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> City: </label>
																	<input  class="form-control"  type="text" id="city" name="city" value="<?php echo $record['city']; ?>" required/>
																	<?php echo form_error('city'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Mobile1: </label>
																	<input class="form-control"  type="text" id="mobile1" name="mobile1" value="<?php echo $record['mobile2']; ?>"/>
																	<?php echo form_error('mobile1'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Mobile2: </label>
																	<input class="form-control" type="text" id="mobile2" name="mobile2" value="<?php echo $record['mobile1']; ?>"/>
																	<?php echo form_error('mobile2'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Line Number: </label>
																	<input  class="form-control" type="text" id="line_number" name="line_number" value="<?php echo $record['line_number']; ?>"/>
																	<?php echo form_error('line_number'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Base-salary:  </label>
																	<input class="form-control" type="text" id="base_salary" name="base_salary"  value="<?php echo $record['base_salary']; ?>"/>
																	<?php echo form_error('base_salary'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Email-Id: </label>
																	<input class="form-control" type="text" id="email_id" name="email_id" value="<?php echo $record['email_id']; ?>"/>
																	<?php echo form_error('email_id'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Tax: </label>
																	<input class="form-control" type="text" id="tax" name="tax" value="<?php echo $record['tax']; ?>"/>
																	<?php echo form_error('tax'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Job-Title: </label>
																	<select  class="form-control" name="job_title" id="job_title" required>
																		<option value="">--Select--</option>
																		<?php foreach($job_title as $key => $value){?>
																		<option value="<?php echo $value['id']; ?>" <?php if($value['id']==$record['job_title']){ ?> selected <?php } ?>> <?php echo $value['job_title']; ?> </option>
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
																<input type="submit" class="btn btn-primary" name="edit" id="edit" value="Edit">
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

