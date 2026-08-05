<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>payrols/">Payrols</a></li>
		<li class="breadcrumb-item active">add</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-users"></i>
			Manage <span class="fw-300">Payrols Add</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Payrols Add <span class="fw-300"><i>Details</i></span></h2>
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
														<h5 class="mb-3">Payrols-Add </h5>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Name : </label>
																	<select  class="form-control" name="name" id="name" onchange="get_last_amount(this.value);" required>
																	<option value="">--Select--</option>
																	<?php foreach($addemployee as $key => $value){ ?>
																	<option value="<?php  echo $value['employee_id']; ?>"><?php  echo $value['employee_id']; ?></option>
																	<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Last Month Amount:</label>
																	<input class="form-control" type="text" id="amount" name="amount" value="<?php echo $this->input->post('amount'); ?>" required/>
																	<input type="hidden"  class="form-control"  id="time_format" name="time_format"  value="WTSE-<?php echo strtotime("now"); ?>" readonly ="readonly"/>
																	<?php echo form_error('amount'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> This Month Amount: </label>
																	<input  class="form-control" type="text" id="total" name="total" value="<?php echo $this->input->post('total'); ?>" required/>
																	<?php echo form_error('total'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Title: </label>
																	<input  class="form-control" type="text" id="title" name="title"  value="<?php echo $this->input->post('title'); ?>" required/>
																	<?php echo form_error('title'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Month: </label>
																	<select class="form-control" name="month" id="month" required>
																	<option value="">--Select--</option>
																	</select>
																</div>
															</div>
														</div>
														
													</fieldset>

													<div class="form-group mt-3">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>payrols" class="btn btn-secondary">Cancel</a>
																<input type="submit" class="btn btn-primary" name="add" id="add" value="Add">
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
		
		function customer_type_values(){
			$("#showcustomers").hide();			
			if($("#customer_type").val()=='monthlycustomer'){
				$("#showcustomers").show();
			}
			else if($("#customer_type").val()=='metercustomer'){
				$("#showcustomers").hide();
			}
		}
		
		function get_last_amount(c_id){
			$.ajax({
				type : "POST",
				url	: '<?php echo ADMIN_URL;?>payrols/last_amount',
				data	: "c_id="+c_id,
				complete: function(data){
					var op = data.responseText.trim();
					///alert(op);
					$("#amount").val(op);
				}
			});
			$.ajax({
				type : "POST",
				url	: '<?php echo ADMIN_URL;?>payrols/get_months',
				data	: "c_id="+c_id,
				complete: function(data){
					var o_p = data.responseText.trim(); 
					//alert(o_p);
					$("#month").html(o_p);
				}
			});
			
		}
	
		</script>

