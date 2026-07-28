<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>technicalproblems/">Technical Problems</a></li>
		<li class="breadcrumb-item active">Add</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-th-list"></i>
			Manage <span class="fw-300">Technicalproblems Add</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Technicalproblems Add <span class="fw-300"><i>Details</i></span></h2>
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
												<legend>Technical Problems-Add </legend>
													<div class="col-lg-6">	
														<div class="col-lg-12 controls">
															<div class="form-group"> 
																<span class="input-group-addon"><strong>Customer : </strong></span>
																<input type="hidden" name="cust_id" id="cust_id" value="<?php echo $this->input->post('cust_id'); ?>"/>
																<input type="hidden" name="lastname" id="lastname" value="<?php echo $this->input->post('lastname'); ?>"/>
																<input type="hidden" name="firstname" id="firstname" value="<?php echo $this->input->post('firstname'); ?>"/>
																<input type="hidden" name="middlename" id="middlename" value="<?php echo $this->input->post('middlename'); ?>"/>
																<select name="customer_id" id="customer_id" placeholder="Type text to search..." required>
																	<option value="">--Select--</option>	
																	<?php
																	
																	foreach ($customer_listing as $key => $value) {
																		?>
																		<option value="<?php echo $value['customer_id'].'==>'.$value['special_priviledge']; ?>"> <?php echo $value['customer_id'] . ' ==> ' . $value['last_name'] . ', ' . $value['first_name'] . ' ' . $value['middle_name']; ?></option>
																	<?php }
																	?>
																</select>
																
																<?php echo form_error('customer_id'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Meter Number : </strong></span>
																<input class="form-control"  type="text" id="meter_number" name="meter_number" value="<?php echo $this->input->post('meter_number'); ?>" required/>
                                                                <?php echo form_error('meter_number'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-12">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Address : </strong></span>
																<input class="form-control"  type="text" id="address" name="address" value="<?php echo $this->input->post('address'); ?>" required/>
                                                                <?php echo form_error('address'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Problem Summary : </strong></span>
																<input class="form-control"  type="text" id="problem_summary" name="problem_summary" value="<?php echo $this->input->post('problem_summary'); ?>" required/>
                                                                <?php echo form_error('problem_summary'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Status : </strong></span>
																<select class="form-control" name="status" id="status" placeholder="Type text to search..." required>
																	<option value="0" id="pending">Pending</option>	
																	<option value="1" id="assigned">Assigned</option>	
																	<option value="2" id="ongoing">On Going</option>	
																	<option value="3" id="resolved">Resolved</option>
																	<option value="4" id="unresolved">UnResolved</option>
																	<option value="5" id="unresolved">Resolved - Closed</option>
																	<option value="6" id="unresolved">UnResolved - Closed</option>
																</select>
                                                                <?php echo form_error('meter_number'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Reported By : </strong></span>
																<select class="form-control" name="reportedby" id="reportedby" required>
																		
																		<?php foreach($employee as $key => $emp){ ?>
																		<option value="<?php echo $emp['id'];?>"><?php echo strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle'];?></option>
																		<?php } ?>
																	</select>
                                                                <?php echo form_error('reportedby'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Reported Date:</strong></span>
																<input class="form-control"  type="text" id="reported_date" name="reported_date"  placeholder="DD-MM-YYYY" value="<?php echo $this->input->post('reported_date')!=''?$this->input->post('reported_date'):Date('d-m-Y'); ?>" required>
															</div>
														</div>
													</div>
													<div class="col-lg-12">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Problem Details :</strong></span>
																<textarea class="form-control" rows="5" cols="25" id="problem_details" name="problem_details"><?php echo $this->input->post('problem_details'); ?></textarea>
                                                                <?php echo form_error('problem_details'); ?>
															</div>
														</div>
													</div>
														
													
											</fieldset>
											<div class="form-actions">
												<div class="row">
													<div class="col-md-12">
														
															<a href="<?php echo ADMIN_URL;?>technicalproblems" class="btn btn-secondary">Cancel</a>
														<input class="btn btn-primary" name="add" id="btn_add" value="add"/>
													</div>
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

