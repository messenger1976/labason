<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>technicalproblems/">Technical Problems</a></li>
		<li class="breadcrumb-item active">Edit</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-th-list"></i>
			Manage <span class="fw-300">Technicalproblems Edit</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Technicalproblems Edit <span class="fw-300"><i>Details</i></span></h2>
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
														<legend>Technical Problems-Edit </legend>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Customer-Id : </strong></span>
																<input class="form-control" type="text" id="customer_id" name="customer_id" value="<?php echo $record['customer_id']; ?>" readonly style="background-color: yellow;"/>
                                                                <?php echo form_error('customer_id'); ?>
															</div>
														</div>
													</div>
													<input type="hidden" name="technical_id" id="technical_id" value="<?php echo $record['id']; ?>">
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Customer Name : </strong></span>
																<input class="form-control" type="text" id="customer_name" name="customer_name" value="<?php echo $record['lastname'].', '.$record['firstname'].' '.$record['middlename']; ?>" readonly style="background-color: yellow;"/>
                                                                <?php echo form_error('customer_name'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Meter Number : </strong></span>
																<input class="form-control" type="text" id="meter_number" name="meter_number" value="<?php echo $record['meter_number']; ?>" readonly style="background-color: yellow;"/>
                                                                <?php echo form_error('meter_number'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Address : </strong></span>
																<input class="form-control" type="text" id="address" name="address" value="<?php echo $record['address']; ?>" readonly style="background-color: yellow;"/>
                                                                <?php echo form_error('address'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Problem Summary : </strong></span>
																<input class="form-control" type="text" id="problem_summary" name="problem_summary" value="<?php echo $record['problem_summary']; ?>" readonly style="background-color: yellow;"/>
                                                                <?php echo form_error('problem_summary'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Status : </strong></span>
																<select class="form-control" name="status" id="status" placeholder="Type text to search..." readonly style="background-color: yellow;">
																	<option value="0" id="pending" <?php echo $record['status']==0?'selected':''; ?>>Pending</option>	
																	<option value="1" id="assigned" <?php echo $record['status']==1?'selected':''; ?>>Assigned</option>	
																	<option value="2" id="ongoing" <?php echo $record['status']==2?'selected':''; ?>>On Going</option>	
																	<option value="3" id="resolved" <?php echo $record['status']==3?'selected':''; ?>>Resolved</option>
																	<option value="4" id="unresolved" <?php echo $record['status']==4?'selected':''; ?>>Un-Resolved</option>
																	<option value="5" id="resolved-closed" <?php echo $record['status']==5?'selected':''; ?>>Resolved - Closed</option>
																	<option value="6" id="unresolved-closed" <?php echo $record['status']==6?'selected':''; ?>>UnResolved - Closed</option>
																</select>
                                                                <?php echo form_error('status'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Reported By : </strong></span>
																<select class="form-control" name="reportedby" id="reportedby" readonly style="background-color: yellow;">
																		
																		<?php foreach($employee as $key => $emp){ ?>
																		<option value="<?php echo $emp['id'];?>" <?php echo $emp['id']==$record['reported_by_id']?'selected':''; ?>><?php echo strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle'];?></option>
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
																<input class="form-control"  type="text" id="reported_date" name="reported_date" readonly placeholder="DD-MM-YYYY" value="<?php echo $record['reported_date']!=''?date('d-m-Y',strtotime($record['reported_date'])):Date('d-m-Y'); ?>" readonly style="background-color: yellow;">
															</div>
														</div>
													</div>
													<div class="col-lg-12">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Problem Details:</strong></span>
																<!--<input type="text" id="address" name="address" class="col-10 col-sm-10" value="<?php echo $record['problem_details']; ?>" required/>-->
																<textarea class="form-control" rows="5" cols="25" id="problem_details" name="problem_details" readonly style="background-color: yellow;"><?php echo $record['problem_details']; ?></textarea>
																<?php echo form_error('problem_details'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-12">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<button class="btn btn-primary btn-xs" id="btn_messages"  data-toggle="modal" data-target="#myModal">Add Message</button>
															</div>
														</div>
													</div>

													<div class="col-lg-12">
														<table class="table table-striped table-bordered table-hover" width="100%">
															<thead>
																<tr>
																	<th width="10%">Date</th>
																	<th>Message</th>
																	<th width="10%">Status</th>
																	<th width="20%">Reported by</th>
																</tr>
															</thead>
															<tbody>
															<?php
															if(count($record_messages) > 0){
																$i=1;
																foreach($record_messages as $key => $row1){
																	?>
																	<tr>
																		<td><?php echo date('m/d/Y',strtotime($row1['technical_msg_reported_date'])); ?></td>
																		<td><?php echo $row1['technical_msg_text']; ?></td>
																		<td><span <?php 
														if($row1['technical_msg_status']== 1){ 
															echo " class='label bg-color-orange '"; 
														} elseif($row1['technical_msg_status']== 0){ 
															echo "class='badge badge-danger '"; 
														} elseif($row1['technical_msg_status']== 2){ 
															echo "class='label bg-color-green '"; 
														}elseif($row1['technical_msg_status']== 3){ 
															echo "class='label  label-success '"; 
														}elseif($row1['technical_msg_status']== 4){ 
															echo "class='label bg-color-pink '"; 
														}elseif($row1['technical_msg_status']== 5){ 
															echo "class='label bg-color-blue '"; 
														}elseif($row1['technical_msg_status']== 6){ 
															echo "class='label bg-color-redLight '"; 
														} ?>>
														<?php
																		if($row1['technical_msg_status']==0){
																			echo 'Pending';
																		}elseif($row1['technical_msg_status']==1){
																			echo 'Assigned';
																		}elseif($row1['technical_msg_status']==2){
																			echo 'On going';
																		}elseif($row1['technical_msg_status']==3){
																			echo 'Resolved';
																		}elseif($row1['technical_msg_status']==4){
																			echo 'Un-Resolved';
																		}elseif($row1['technical_msg_status']==5){
																			echo 'Resolved - Closed';
																		}elseif($row1['technical_msg_status']==6){
																			echo 'UnResolved - Closed';
																		}
																		echo '</span>';
//																		echo $row1['technical_msg_status']; 
																		?></td>
																		<td><?php echo $row1['technical_msg_reported_name']; ?></td>
																	</tr>
																	<?php
																}
															}else{
																echo '<tr><td colspan=4><center>No record found</center></td></tr>';
															}
															?>

																
																
															</tbody>
														</table>
													</div>
													</fieldset>
													
													
													
													<div class="form-actions">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>technicalproblems" class="btn btn-secondary">Cancel</a>
																<input type="submit" class="btn btn-primary" name="add" id="add" value="Edit">
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


