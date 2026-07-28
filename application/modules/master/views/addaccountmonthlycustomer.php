<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>addpaymentcustomer">Meter Customer Bills</a></li>
		<li class="breadcrumb-item active">search</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user-friends"></i>
			Manage <span class="fw-300">Addaccountmonthlycustomer</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addaccountmonthlycustomer <span class="fw-300"><i>Details</i></span></h2>
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
											
											<fieldset><?php extract($id);?>
														<legend>Add Account</legend>
														<form method="POST" action="<?php echo ADMIN_URL;?>addpaymentcustomer/saveaccountmonthlycustomer">
													<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>Account Number</strong></span>
																	<input class="form-control"  type="text" id="accountno" name="accountno"  placeholder="Account Number" value="">
																	<input type="hidden" name="id" value="<?php echo $id;?>">
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Account Name</strong></span>
																	<input class="form-control"  type="text" id="accountname" name="accountname"  placeholder="Acount Name" value="">
																</div>
															</div>
														</div>
                                                        <div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Amount </strong></span>
																	<input class="form-control"  type="text" id="amount" placeholder="Amount" name="amount"  value="" required>
																</div>
															</div>
														</div>
														<div class="col-12" id="meterincomeDiv" style="margin-top: 13px; margin-bottom:20px;"></div> 
														<div style="clear:both"></div>
														<input type="submit" value="Submit" class="btn btn-sm btn-primary" style= "float: right; margin-right: 25px;"/>
													</form>	
															
													</fieldset>
													    
												<div class="pay_setting_1">
															<div class="form-actions">
																<div class="row">
																	<div class="col-md-12">
																	
																		
																	</div>
																</div>
															</div>
													    </div>	
												
														
														
										
										
									</div>
								    
								
								</div>	
						</div>
                        <div class="col-12" id="paidcustomerDiv" style="margin-top: 13px;"></div>	
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
<script type="text/javascript">
var curDate = '<?php echo date('d-m-Y') ?>';	
function fun_calendor(field){
	$("#"+field).focus();
} 
$(document).ready(function(){
	$("#fromdate").datepicker({
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
	$("#todate").datepicker({
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
<script type="text/javascript">
	
		function getaddcustomer_paid(){
			
			var type = $("#type").val();
			var fromdate = $("#fromdate").val();
			var todate = $("#todate").val();
			
			$.ajax({
				
				type : "POST",
				url	: '<?php echo ADMIN_URL;?>adddailyreport/getadddailyreportsearch',
				//data	: "customer_type="+customer_type+"&zone="+zone+"&fromdate="+fromdate+"&todate="+todate+",
				data	: "fromdate="+fromdate+"&todate="+todate+"&type="+type,
				complete: function(data){
					var op = data.responseText.trim();
					//alert(op);
					$("#paidcustomerDiv").html(op);
				}
			});
		}
	
		</script>

