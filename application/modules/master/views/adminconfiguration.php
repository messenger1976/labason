<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item active">Admin-COnfiguration</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-cog"></i>
			Manage <span class="fw-300">Adminconfiguration</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Adminconfiguration <span class="fw-300"><i>Details</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
<?php if (!empty($record)){ extract($record);}?>
																	<div class="image" style = "width:320px; height:250px; float:left;">
																		<img id="blah" src="<?php echo ADMIN_IMG_URL;?>logo/<?php echo $file ; ?>" style = "width:320px; height:250px;"/>
																	</div>
										<table id="user" class="table table-bordered table-striped" style="float: right; width:70%;">
											<tbody>
												<tr>
													<td style="width:25%;">Name : </td>
													<td style="width:75%"><?php echo stripslashes(str_replace('\n','',$name)); ?></td>
												</tr>
												<tr>
													<td style="width:25%;">Info Email: </td>
													<td style="width:75%"><?php echo stripslashes(str_replace('\n','',$email)); ?></td>
												</tr>
												<tr>
													<td>Contact Email :</td>
													<td><?php echo stripslashes(str_replace('\n','',$email)); ?></td>
												</tr>
												<tr>
													<td>Established on :</td>
													<td><?php echo stripslashes(str_replace('\n','',$established)); ?></td>
												</tr>
												<tr>
													<td>Phone Number  :</td>
													<td><?php echo stripslashes(str_replace('\n','',$contact1)); ?></td>
												</tr>
												<tr>
													<td>Contact Person</td>
													<td><?php echo stripslashes(str_replace('\n','',$contactperson)); ?></td>
												</tr>
												<tr>
													<td>Contact Person Mobile</td>
													<td><?php echo stripslashes(str_replace('\n','',$contactpersonphone)); ?></td>
												</tr>
												<tr>
													<td>Website</td>
													<td><?php echo stripslashes(str_replace('\n','',$website)); ?></td>
												</tr>
				
												<tr>
													<td>Address  :</td>
													<td><?php echo stripslashes(str_replace('\n','',$address1)); ?></td>
												</tr>
												<tr>
													<td>About :</td>
													<td><?php echo stripslashes(str_replace('\n','',$about)); ?></td>
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


