<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addshareholder/">Share Holder Add</a></li>
		<li class="breadcrumb-item active">Edit</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-chart-pie"></i>
			Manage <span class="fw-300">Addshareholder Edit</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addshareholder Edit <span class="fw-300"><i>Details</i></span></h2>
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
								<div class="form-horizontal" >
										  	
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
											<form class="form-horizontal" role="form" name="myform" id="myform" method="post" action="" enctype="multipart/form-data">

											<fieldset>
												<h5 class="mb-3">Share-Add  </h5>
													
													<div class="form-group">
														   <label class="col-sm-2 control-label">First name:</label>
															<div class="col-sm-3">
																<input class="form-control"  type="text" id="firstname" name="firstname"   value="<?php echo $record['firstname']; ?>" required>
															    <?php echo form_error('firstname:'); ?>
															</div>
															
															 <label class="col-sm-2 control-label"> Middle nale:</label>
															<div class="col-sm-3">
																<input class="form-control"  type="text" id="middlename" name="middlename"  value="<?php echo $record['middlename']; ?>" required>
															    <?php echo form_error('middlename:'); ?>
															</div>
													</div>	
													<div class="form-group">
														   <label class="col-sm-2 control-label">Last name:</label>
															<div class="col-sm-3">
																<input class="form-control"  type="text" id="lastname" name="lastname"   value="<?php echo $record['lastname']; ?>" required>
															    <?php echo form_error('lastname:'); ?>
															</div>
															
															
															 <label class="col-sm-2 control-label"> Gender:</label>
															<div class="col-sm-3">
																<select name="gender" id="gender" class="form-control" required>
																	 <option value="">--Select--</option>
																	 <option value="male"<?php if($record['gender']=='male'){ ?> selected <?php } ?>>male</option>
																	 <option value="female"<?php if($record['gender']=='female'){ ?> selected <?php } ?>>female</option>
																</select>
																<?php echo form_error('gender:'); ?>
															</div>
															
													</div>	
													<div class="form-group">
														   <label class="col-sm-2 control-label">Date Of Birth:</label>
															<div class="col-sm-3">
																<input class="form-control"  type="text" name="dob" id="dob" value="<?php echo $record['dob']; ?>" required>
															    <?php echo form_error('dob'); ?>
															</div>
															
															<label class="col-sm-2 control-label"> Place of birth :</label>
															<div class="col-sm-3">
																<input  class="form-control"  type="text" id="place_of_birth" name="place_of_birth" value="<?php echo $record['placeofbirth']; ?>" required/>
																<?php echo form_error('place_of_birth'); ?>
															</div>
															
													</div>
                                                    <div class="form-group">
														   <label class="col-sm-2 control-label">Address:</label>
															<div class="col-sm-3">
																<textarea class="form-control" rows="5"  id="address" name="address" required><?php echo $record['address']; ?></textarea>
                                                               <?php echo form_error('address'); ?>
															</div>
															
															<label class="col-sm-2 control-label"> Phone No :</label>
															<div class="col-sm-3">
																<input class="form-control"  type="text" id="mobile1" name="mobile1" value="<?php echo $record['phone_number']; ?>" required/>
																<?php echo form_error('mobile1'); ?>
															</div>
															
													</div>
                                                    <div class="form-group">
														   <label class="col-sm-2 control-label">Email:</label>
															<div class="col-sm-3">
																<input class="form-control" type="text" id="email_id" name="email_id" value="<?php echo $record['email']; ?>" required/>
																<?php echo form_error('email_id'); ?>
															</div>
															
															<label class="col-sm-2 control-label"> Amount of Share :</label>
															<div class="col-sm-3">
																<input class="form-control" type="text" id="amountofshare" name="amountofshare" value="<?php echo $record['amounttoshare']; ?>" required/>
																<?php echo form_error('amountofshare'); ?>
															</div>
															
													</div>
                                                    <div class="form-group">
														   <label class="col-sm-2 control-label">Comission:</label>
															<div class="col-sm-3">
																<input class="form-control" type="text" id="comission" name="comission" value="<?php echo $record['comission']; ?>" required/>
																<?php echo form_error('comission'); ?>
															</div>
															
													</div>													
													
											</fieldset>

													<div class="form-group mt-3">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>addshareholder" class="btn btn-secondary">Cancel</a>
																<input type="submit" class="btn btn-primary" name="add" id="add" value="Edit">
															</div>
														</div>
									</div>
								</div>
							</form>
				
									
								    
									
									
						
						
						<div class="col-12 col-lg-12" id="expensesDiv"></div>
				        </div>
								
					
					                 
					
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
		buttonImage: '/images/calender.jpg',
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

