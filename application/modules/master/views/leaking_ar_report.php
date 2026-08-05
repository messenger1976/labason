<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>reports/leaking_ar_report/">Leaking A/R Report</a></li>
		<li class="breadcrumb-item active">search</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-chart-bar"></i>
			Manage <span class="fw-300">Leaking Ar Report</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Leaking Ar Report <span class="fw-300"><i>Details</i></span></h2>
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
											
											<fieldset>
														<legend>
															Leaking A/R Report -Search 
															<div  class="pull-right" style="padding-right:20px;">
																<button type="submit" class="btn btn-sm btn-primary" name="display" id="display" style="margin-bottom: 5px;">Display</button>
																<button id="printtopdf" class="btn btn-sm btn-warning" style="margin-bottom: 5px;">Print</button>
															
															</div>
														</legend>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>Transaction Date:</strong></span>
																	<input class="form-control"  type="text" id="asofdate" name="asofdate"  placeholder="DD-MM-YYYY" value="" required>
																</div>
															</div>
														</div>
														<!--<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>To-Date:</strong></span>
																	<input class="form-control"  type="text" id="todate" name="todate"  placeholder="DD-MM-YYYY" value="" required>
																</div>
															</div>
														</div>-->
                                                        <div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Zone:</strong></span>
																	<select class="form-control" name="zone" id="zone" required>
																		<option value="0">--All--</option>
																		<?php foreach($zone as $key => $value){ ?>
																		<option value="<?php echo $value['id'];?>"><?php echo $value['zone'];?></option>
																		<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														<!--<div class="form-group col-lg-6">
                                                            <div class="col-lg-12 controls">
                                                                <div class="form-group">
                                                                <span class="input-group-addon"><i class="icon-user"></i><strong>Status : </strong></span>
                                                                    <select  class="form-control" name="status" id="status" required>
                                                                        <option value="">--All--</option>
                                                                        <option value="1">Active</option>
                                                                        <option value="0">Inactive</option>
																		<option value="2">Disconnected</option>
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        
                                                        </div>-->
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Prepared by:</strong></span>
																	<select class="form-control" name="preparedby" id="preparedby" required>
																		
																		<?php foreach($employee as $key => $emp){ ?>
																		<option value="<?php echo $emp['id'];?>"><?php echo strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle'];?></option>
																		<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Checked/Verified by:</strong></span>
																	<select class="form-control" name="verifiedby" id="verifiedby" required>
																		
																		<?php foreach($employee as $key => $emp){ ?>
																		<option value="<?php echo $emp['id'];?>"><?php echo strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle'];?></option>
																		<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Approved by:</strong></span>
																	<select class="form-control" name="approvedby" id="approvedby" required>
																		
																		<?php foreach($employee as $key => $emp){ ?>
																		<option value="<?php echo $emp['id'];?>"><?php echo strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle'];?></option>
																		<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														<div style="clear:both"></div>
														
														<div class="col-12" id="paidcustomerDiv" style="margin-top: 13px;"></div>
															
													</fieldset>
													
												</div> 
														
													    
															
												
														
														
														
										
									</div>
								    
								
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
<script type="text/javascript">
var curDate = '<?php echo date('d-m-Y') ?>';	
function fun_calendor(field){
	$("#"+field).focus();
} 

$(document).ready(function(){
	$("#asofdate").datepicker({
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
	

	$('#printtopdf').on('click',function(evt){
		evt.preventDefault();
		var zone = $("#zone").val();
		var status = $("#status").val();
		var preparedby = $("#preparedby").val();
		var verifiedby = $("#verifiedby").val();
		var approvedby = $("#approvedby").val();
		var asofdate = $("#asofdate").val();
		const popup = window.open(
			"agingprinttopdf/"+asofdate+'/'+zone+'/'+status+'/'+preparedby+'/'+verifiedby+'/'+approvedby, // URL to display 
			"PopupWindowPrint", // Name of the window
			"width=1200,height=600,resizable=yes,scrollbars=yes" // Window settings
		);

		// Optional: Check if the popup was blocked
		if (!popup || popup.closed || typeof popup.closed == "undefined") {
			alert("Popup was blocked! Please allow popups for this site.");
		}

	});

    $('#display').on('click', function(evt){
        evt.preventDefault();
       
        var asofdate = $("#asofdate").val();
        var zone = $("#zone").val();
		var status = $("#status").val();
        showSpinner();
        
        $.ajax({
            
            type : "POST",
            url	: '<?php echo ADMIN_URL;?>reports/getagingARreportsearch',
            
            data	: "asofdate="+asofdate+"&zone="+zone+"&status="+status,
            complete: function(data){
                var op = data.responseText.trim();
                //alert(op);
                $("#paidcustomerDiv").html(op);
                hideSpinner();
            }
        });
    });
});

</script>

