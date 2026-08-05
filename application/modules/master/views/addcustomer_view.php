<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>addcustomer">customer</a></li>
		<li class="breadcrumb-item active">view</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user-friends"></i>
			Manage <span class="fw-300">Addcustomer View</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addcustomer View <span class="fw-300"><i>Details</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
<table id="user" class="table table-bordered table-striped" style="clear: both">
											<tbody>
												<tr>
													<td>User Photo : </td>
																	<td><div class="image" style = "width:150px; height:150px;">
																		<img id="blah" src="<?php echo ADMIN_IMG_URL;?>upload/<?php echo $image['file']; ?>" style = "width:150px; height:150px;"/>
																	</div>
													</td>
												<tr>
													<td style="width:25%;">Account Subgroup Type : </td>
													<td style="width:75%"><?php echo stripslashes(str_replace('\n','',$record['subName'])); ?></td>
												</tr>
												<tr>
													<td style="width:25%;">Customer-Id : </td>
													<td style="width:75%"><?php echo stripslashes(str_replace('\n','',$record['customer_id'])); ?></td>
												</tr>
												<tr>
													<td>First Name :</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['first_name'])); ?></td>
												</tr>
												<tr>
													<td>Middle Name :</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['middle_name'])); ?></td>
												</tr>
												<tr>
													<td>Last Name :</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['last_name'])); ?></td>
												</tr>
												<tr>
													<td>DOB :</td>
													<td><?php echo $record['DOB']; ?></td>
												</tr>
				
												<tr>
													<td>Gender:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['gender'])); ?></td>
												</tr>
												<tr>
													<td>State:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['state'])); ?></td>
												</tr>
												<tr>
													<td>Place of birth :</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['place_of_birth'])); ?></td>
												</tr>
				
												<tr>
													<td>Address:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['city'])); ?></td>
												</tr>
				
												<tr>
													<td>City: </td>
													<td><?php echo stripslashes(str_replace('\n','',$record['mobile1'])); ?></td>
												</tr>
				
												<tr>
													<td>Mobile1:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['mobile1'])); ?></td>
												</tr>
												
												<tr>
													<td>Mobile2:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['mobile2'])); ?></td>
												</tr>
				
				                                <tr>
													<td> Email-Id:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['email_id'])); ?></td>
												</tr>
				
												<tr>
													<td>Line Number:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['line_number'])); ?></td>
												</tr>
												
												<tr>
													<td> Zone:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['zones'])); ?></td>
												</tr>
				
												<tr>
													<td>Customer-Type:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['customer_type'])); ?></td>
												</tr>
				
												<tr>
													<td>Reference person:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['referenceperson'])); ?></td>
												</tr>
												
												<tr>
													<td>Billing-plans:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['billingplans_name'])); ?></td>
												</tr>
				
												<tr>
													<td>Status:</td>
													<td><?php if($record['status']=='0'){ ?>Deactive <?php } if($record['status']=='1'){ ?>Active <?php } ?></td>
												</tr>
				
											</tbody>
										</table>
				
									</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
</body>
</html>


