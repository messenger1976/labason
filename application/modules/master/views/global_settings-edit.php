<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>global_settings/">Global Settings</a></li>
		<li class="breadcrumb-item active">Edit</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-cog"></i>
			Manage <span class="fw-300">Global Settings Edit</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Global Settings Edit <span class="fw-300"><i>Details</i></span></h2>
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
										<div class="alert alert-block alert-danger">
											<button type="button" class="close" data-dismiss="alert">
											<i class="fal fa-times"></i>
											</button>
											<p>
												<i class="icon-warning"></i>
												<?php echo $msg?$msg:'';?>
											</p>
										</div>
										<?php } ?>	
										
										<fieldset>
											<h5 class="mb-3">Global Settings - Edit</h5>
											
											<div class="form-group">
												<label class="col-md-2 control-label">Code: <span class="text-danger">*</span></label>
												<div class="col-md-8">
													<input type="text" id="code" name="code" class="form-control" value="<?php echo isset($record['code']) ? stripslashes($record['code']) : ''; ?>" required readonly>
													<span class="help-block">Setting code (cannot be changed)</span>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-md-2 control-label">Description: <span class="text-danger">*</span></label>
												<div class="col-md-8">
													<textarea id="description" name="description" class="form-control" rows="3" required><?php echo isset($record['description']) ? stripslashes($record['description']) : ''; ?></textarea>
													<span class="help-block">Description of this setting</span>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-md-2 control-label">Value: <span class="text-danger">*</span></label>
												<div class="col-md-8">
													<input type="number" id="value" name="value" class="form-control" step="0.01" min="0" value="<?php echo isset($record['value']) ? $record['value'] : '0'; ?>" required>
													<span class="help-block">Numeric value for this setting</span>
												</div>
											</div>

										</fieldset>
										
										<div class="form-group mt-3">
											<div class="row">
												<div class="col-md-12">
													<a href="<?php echo ADMIN_URL;?>global_settings" class="btn btn-secondary">Cancel</a>
													<input type="submit" class="btn btn-primary" name="add" id="add" value="Save">
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
								
						
					
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


