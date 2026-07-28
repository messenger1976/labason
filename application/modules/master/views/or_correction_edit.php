<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>add_zone">OR Correction</a></li>
		<li class="breadcrumb-item active">Edit</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-th-list"></i>
			Manage <span class="fw-300">Or Correction Edit</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Or Correction Edit <span class="fw-300"><i>Details</i></span></h2>
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
														<legend>OR Transaction-Edit </legend>
														<div class="form-group col-lg-12" style="display: none;">
															<div class="col-lg-6 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>Transaction ID : </strong></span>
																	<input  class="form-control"  id="id" name="id" value="<?php echo $record['id']; ?>" readonly/>
																	<?php echo form_error('id'); ?>
																</div>
															</div>
														</div>
                                                        <div class="form-group col-lg-12">
															<div class="col-lg-6 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>Transaction Date : </strong></span>
																	<input  class="form-control"  id="date" name="date" value="<?php echo date('d-m-Y',strtotime($record['date'])); ?>" readonly/>
																	<?php echo form_error('date'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-12">
															<div class="col-lg-6 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>New Transaction Date : </strong></span>
																	<input  class="form-control"  id="newdate" name="newdate" value="<?php echo ($this->input->post('newdate') != '')?date('d-m-Y',strtotime($this->input->post('newdate'))):date('d-m-Y',strtotime($record['date']));?>" />
																	<?php echo form_error('newdate'); ?>
																</div>
															</div>
														</div>
                                                        <div class="form-group col-lg-12">
															<div class="col-lg-6 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>Customer ID : </strong></span>
																	<input  class="form-control"  id="customer_id" name="customer_id" value="<?php echo $record['customer_id']; ?>" readonly/>
																	<?php echo form_error('customer_id'); ?>
																</div>
															</div>
														</div>
                                                        <div class="form-group col-lg-12">
															<div class="col-lg-6 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>Customer Name : </strong></span>
																	<input  class="form-control"  id="custname" name="custname" value="<?php echo $record['name']; ?>" readonly/>
																	<?php echo form_error('custname'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-12">
															<div class="col-lg-6 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>OR Amount : </strong></span>
																	<input  class="form-control"  id="grand_total" name="grand_total" value="<?php echo $record['grand_total']; ?>" />
																	<?php echo form_error('grand_total'); ?>
																</div>
															</div>
														</div>
                                                        <div class="form-group col-lg-12">
															<div class="col-lg-6 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>Old OR # : </strong></span>
																	<input  class="form-control"  id="old_or" name="old_or" value="<?php echo $record['or_number']; ?>" readonly/>
																	<?php echo form_error('old_or'); ?>
																</div>
															</div>
														</div>
                                                        <div class="form-group col-lg-12">
															<div class="col-lg-6 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>New OR # : </strong></span>
																	<input  class="form-control"  id="or_number" name="or_number" value="<?php echo $record['or_number']; ?>" required/>
																	<?php echo form_error('or_number'); ?>
																</div>
															</div>
														</div>
														
											</fieldset>
													
													<div class="form-actions">
														<div class="row">
															<div class="col-md-12">
																<a href="<?php echo ADMIN_URL;?>or_correction" class="btn btn-secondary">Cancel</a>
																<input type="submit" class="btn btn-primary" name="edit" id="edit" value="Edit">
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


