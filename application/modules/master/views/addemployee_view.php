<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addemployee/add/">addemployee</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addemployee/search/">Search Employee</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-users"></i>
			Manage <span class="fw-300">Addemployee View</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addemployee View <span class="fw-300"><i>Details</i></span></h2>
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
													<td style="width:25%;">Employee-Id : </td>
													<td style="width:75%"><?php echo stripslashes(str_replace('\n','',$record['employee_id'])); ?></td>
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
													<td><?php echo stripslashes(str_replace('\n','',$record['dob'])); ?></td>
												</tr>
				
												<tr>
													<td>Gender:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['gender'])); ?></td>
												</tr>
												<tr>
													<td>Province:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['state'])); ?></td>
												</tr>
												<tr>
													<td>Place of birth :</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['place_of_birth'])); ?></td>
												</tr>
				
												<tr>
													<td>Address:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['address'])); ?></td>
												</tr>
				
												<tr>
													<td>City: </td>
													<td><?php echo stripslashes(str_replace('\n','',$record['city'])); ?></td>
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
													<td>Job-Title:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['job_title'])); ?></td>
												</tr>
				
												<tr>
													<td>Base-Salary:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['base_salary'])); ?></td>
												</tr>
												
												<tr>
													<td>Tax</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['tax'])); ?></td>
												</tr>
				
											</tbody>
										</table>
				
									</div>
					</div>
				</div>
			</div>
		</div>
	
</main>
<?php include('footer.php'); ?>
</body>
</html>


