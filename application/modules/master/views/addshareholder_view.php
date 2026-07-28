<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addshareholder/">Share Holder Add</a></li>
		<li class="breadcrumb-item active">View</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-chart-pie"></i>
			Manage <span class="fw-300">Addshareholder View</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addshareholder View <span class="fw-300"><i>Details</i></span></h2>
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
													<td style="width:25%;">First Name :</td>
													<td style="width:75%"><?php echo stripslashes(str_replace('\n','',$record['firstname'])); ?></td>
												</tr>
												<tr>
													<td>Middle Name :</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['middlename'])); ?></td>
												</tr>
												<tr>
													<td>Last Name :</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['lastname'])); ?></td>
												</tr>
												<tr>
													<td>Gender:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['gender'])); ?></td>
												</tr>
												<tr>
													<td>DOB :</td>
													<td><?php echo $record['dob']; ?></td>
												</tr>
												<tr>
													<td>Place of birth :</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['placeofbirth'])); ?></td>
												</tr>
												<tr>
													<td>Address:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['address'])); ?></td>
												</tr>
				                                <tr>
													<td>Phone Number:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['phone_number'])); ?></td>
												</tr>
												
												<tr>
													<td>Email:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['email'])); ?></td>
												</tr>
				
				                                <tr>
													<td> Amount of Share:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['amounttoshare'])); ?></td>
												</tr>
				
												<tr>
													<td>Comission:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['comission'])); ?></td>
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


