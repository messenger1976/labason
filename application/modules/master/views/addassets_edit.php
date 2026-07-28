<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addassets/">Assets</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addassets/search/">Search Assets</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-boxes"></i>
			Manage <span class="fw-300">Addassets Edit</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addassets Edit <span class="fw-300"><i>Details</i></span></h2>
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
														<h5 class="mb-3">Assets-Edit </h5>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
																<label class="form-label">Asset Type : </label>
																<select class="form-control" name="asset_type" id="asset_type" required>
																   <option value="">--Select--</option>
																   <option value="computers"<?php if($record['asset_type']=='computers'){ ?> selected <?php } ?>>computers</option>
																   <option value="tables"<?php if($record['asset_type']=='tables'){ ?> selected <?php } ?>>tables</option>
																</select>
														</div>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<label class="form-label"> Quantity :</label>
																<select class="form-control" name="quantity" id="quantity" required/>
																  <option value="">--Select--</option>
																  <option value="4"<?php if($record['quantity']=='4'){ ?> selected <?php } ?>>4</option>
																  <option value="5"<?php if($record['quantity']=='5'){ ?> selected <?php } ?>>5</option>
																</select>
														</div>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<label class="form-label"> Middle Name : </label>
																<input class="form-control" type="text" id="total" name="total"  value="<?php echo $record['total']; ?>" required/>
                                                                <?php echo form_error('total'); ?>
															</div>
														</div>
													</div>
													</fieldset>
													
													<div class="form-group mt-3">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>addassets" class="btn btn-secondary">Cancel</a>
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


