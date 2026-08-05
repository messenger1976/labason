<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addshareholder/">Share Holder</a></li>
		<li class="breadcrumb-item active">Add</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-chart-pie"></i>
			Manage <span class="fw-300">Profit Addshareholder Add</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Profit Addshareholder Add <span class="fw-300"><i>Details</i></span></h2>
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
														   <label class="col-sm-2 control-label">Share Holder:</label>
															<div class="col-sm-3">
																<select name="share_holder" id="share_holder" class="form-control" required>
																	 <option value="">--Select--</option>
																	  <?php foreach($get_shareholder as $key=>$row){?>
																	  <option value="<?php echo $row['id']; ?>"><?php echo $row['firstname'].'&nbsp;'.$row['middlename'].'&nbsp;'.$row['lastname'];?></option>
																	  <?php } ?>
																</select>
															    <?php echo form_error('share_holder:'); ?>
															</div>
															
													</div>	
													
													<div class="form-group">
														   <label class="col-sm-2 control-label">From Date:</label>
															<div class="col-sm-3">
																<input class="form-control"  type="text" name="from-date" id="from-date"  placeholder="DD-MM-YYYY" required>
															    <?php echo form_error('dob'); ?>
															</div>
															
															<label class="col-sm-2 control-label">To Date:</label>
															<div class="col-sm-3">
																<input class="form-control"  type="text" name="to-date" id="to-date"  placeholder="DD-MM-YYYY" required>
															    <?php echo form_error('dob'); ?>
															</div>
															
													</div>
                                                    <div class="form-group">
														   <label class="col-sm-2 control-label">Comission % :</label>
															<div class="col-sm-3">
																<input class="form-control"  type="text" id="comissin" name="comissin" value="<?php echo $this->input->post('comissin'); ?>" required/>
                                                                <?php echo form_error('address'); ?>
															</div>
															
															<label class="col-sm-2 control-label"> Amount Earn:</label>
															<div class="col-sm-3">
																<input class="form-control"  type="text" id="amount_earn" name="amount_earn" value="<?php echo $this->input->post('amount_earn'); ?>" required/>
																<?php echo form_error('amount_earn'); ?>
															</div>
															
													</div>
                                                    
											</fieldset>

													<div class="form-group mt-3">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>addcustomer" class="btn btn-secondary">Cancel</a>
																<input type="submit" class="btn btn-primary" name="add" id="add" value="Add">
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
	$("#from-date").datepicker({
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
$(document).ready(function(){
	$("#to-date").datepicker({
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

