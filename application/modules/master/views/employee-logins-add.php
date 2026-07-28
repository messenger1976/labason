<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>employee_logins/">Employee Logins</a></li>
		<li class="breadcrumb-item active">add</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-users"></i>
			Manage <span class="fw-300">Employee Logins Add</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Employee Logins Add <span class="fw-300"><i>Details</i></span></h2>
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
														<h5 class="mb-3">Employee Logins-Add </h5>
														<div class="form-group col-lg-12">
															<div class="col-lg-6 controls">
																<div class="form-group">
																	<label class="form-label">Employee Name : </label>
																	
																	<input class="form-control" type="text" id="employee_name" placeholder="Employee Name" name="employee_name" value="<?php echo $this->input->post('employee_name'); ?>"  required/>
																	<?php echo form_error('employee_name'); ?>
																	
																	<span id="val_roll_img"></span>
																</div>
															</div>
														</div>
														
														<div class="form-group col-lg-12">
															<div class="col-lg-6 controls">
																<div class="form-group">
																	<label class="form-label">Username : </label>
																
																	<input class="form-control" type="text" id="username" placeholder="User Name" name="username" value="<?php echo $this->input->post('username'); ?>"  required/>
																	<?php echo form_error('username'); ?>
																
																</div>
															</div>
														</div>
														
														<div class="form-group col-lg-12">
															<div class="col-lg-6 controls">
																<div class="form-group">
																	<label class="form-label">Password : </label>
																	
																	<input class="form-control" type="password" id="password" placeholder="Password"  name="password" value="<?php echo $this->input->post('password'); ?>" />
																	<?php echo form_error('password'); ?>
																	
																</div>
															</div>
														</div>
														
														<div class="form-group col-lg-12">
															<div class="col-lg-6 controls">
																<div class="form-group">
																	<label class="form-label">Mobile : </label>
														
														
																	<input class="form-control"  type="text" id="mobile" placeholder="Mobile" name="mobile" value="<?php echo $this->input->post('mobile'); ?>" required  maxlength="10" />
																	<?php echo form_error('mobile'); ?>
																	
																</div>
															</div>
														</div>
														
														<div class="form-group col-lg-12">
															<div class="col-lg-6 controls">
																<div class="form-group">
																	<label class="form-label">Roles : </label>
															
															
																	<select class="form-control" name="roles" id="roles"  required> 
																		<option value="" <?php echo ($this->input->post('roles') == '')?'selected':''; ?> />Select</option>
																		<?PHP foreach($roles as $record){ ?>
																		<option value="<?PHP echo $record['id']."**".$record['role_name']; ?>" <?php echo ($this->input->post('roles') == $record['id']."**".$record['role_name'])?'selected':''; ?> /> <?PHP echo $record['role_name'];?>  </option>
																		<?PHP } ?> 
																	</select>
																	<?php echo form_error('roles'); ?>
															
																</div>
															</div>
														</div>
														
													</fieldset>

													<div class="form-group mt-3">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>employee_logins/" class="btn btn-secondary">Cancel</a>
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

