<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item active">Configuration Edit</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-cog"></i>
			Manage <span class="fw-300">Adminconfiguration Edit</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Adminconfiguration Edit <span class="fw-300"><i>Details</i></span></h2>
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
								<form class="form-horizontal" method="post" action="<?php echo ADMIN_URL ;?>addcustomer/adminconfigurationupdate" enctype="multipart/form-data">
										  	
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
											<?php if (!empty($record)){ extract($record);}?>
											<fieldset>
														<h5 class="mb-3">Edit-Configuration </h5>
                                                        <div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
															    <div class="form-group">
																	<label>Logo</label>
																	<div class="image" style = "width:150px; height:150px;">
																		<img id="blah" src="<?php echo ADMIN_IMG_URL;?>logo/<?php echo $file; ?>" style = "width:150px; height:150px;"/>
																	</div>
																	<input type='file' onchange="readURL(this);" name="userfile"/>
																</div>
																<div class="form-group">
																	<label class="form-label">Name : </label>
																	<input  class="form-control"  type="text" id="name" name="name" value="<?php echo $name; ?>" required />
																	<input  type="hidden" name="adminid" value="<?php echo $adminid; ?>"/>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Email :</label>
																	<input  class="form-control"  type="text" id="email" name="email" value="<?php echo $email; ?>" required/>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Established on : </label>
																	<input  class="form-control"  type="text" name="established" id="dob"  placeholder="DD-MM-YYYY" value="<?php echo $established ;?>" required />
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Contact :  </label>
																	<input  class="form-control" type="text"  id="contact1" name="contact1" value="<?php echo $contact1; ?>" required/>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Contact Person:  </label>
																	<input  class="form-control" type="text"  id="contactperson" name="contactperson" value="<?php echo $contactperson; ?>" required/>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Contact Person Phone:  </label>
																	<input  class="form-control" type="text"  id="contactpersonphone" name="contactpersonphone" value="<?php echo $contactpersonphone; ?>" required/>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Web Site:  </label>
																	<input  class="form-control" type="text"  id="website" name="website" value="<?php echo $website; ?>" required/>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">  Address :  </label>
																	<textarea class="form-control" rows="5"  id="address1" name="address1"><?php echo $address1; ?></textarea>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">  About :  </label>
																	<textarea class="form-control" rows="5"  id="about" name="about"><?php echo $about; ?></textarea>
																</div>
															</div>
														</div>
							
													</fieldset>
													
													
													
													<div class="form-group mt-3">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>addcustomer/adminconfiguration" class="btn btn-secondary">Cancel</a>
																<input type="submit" class="btn btn-primary" name="edit" id="edit" value="Update">
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
var curDate = '<?php echo date('d-m-Y') ?>';	
function fun_calendor(field){
	$("#"+field).focus();
} 
$(document).ready(function(){
	$("#dob").datepicker({
		showAnim: null,
		dateFormat: 'dd-mm-yy',
		// showOn: 'both',
		buttonImage: '<?php echo site_url();?>images/calender.jpg',
		buttonImageOnly: true,
		firstDay: 1,
		nextText: '',
		prevText: '',
		numberOfMonths: [1, 1],
		//defaultDate: new Date(curDate),
		//minDate: curDate,
		//maxDate: ''
	});
	
});

</script>
<script type="text/javascript">
          function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(150);
                };

                reader.readAsDataURL(input.files[0]);
            }
          }
          </script>

