<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>reports/arrears_monitoring_report/">Arrears Monitoring</a></li>
		<li class="breadcrumb-item active">search</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-chart-bar"></i>
			Manage <span class="fw-300">Arrears Monitoring Report</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Arrears Monitoring Report <span class="fw-300"><i>Details</i></span></h2>
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
															Arrears Monitoring -Search 
															<div  class="pull-right" style="padding-right:20px;">
																<button type="submit" class="btn btn-sm btn-primary" name="display" id="display" style="margin-bottom: 5px;">Display</button>
															</div>
														</legend>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>As of Date:</strong></span>
																	<input class="form-control"  type="text" id="asofdate" name="asofdate"  placeholder="DD-MM-YYYY" value="" required>
																</div>
															</div>
														</div>
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
														<div class="form-group col-lg-6">
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
		buttonImage: '/images/calender.jpg',
		buttonImageOnly: true,
		firstDay: 1,
		nextText: '',
		prevText: '',
		numberOfMonths: [1, 1],
	});

    $('#display').on('click', function(evt){
        evt.preventDefault();
       
        var asofdate = $("#asofdate").val();
        var zone = $("#zone").val();
		var status = $("#status").val();

		if(asofdate === ''){
			alert("Please select As of Date");
			return false;
		}

        showSpinner();
        
        $.ajax({
            
            type : "POST",
            url	: '<?php echo ADMIN_URL;?>reports/getarrearsmonitoringsearch',
            
            data	: "asofdate="+asofdate+"&zone="+zone+"&status="+status,
            complete: function(data){
                var op = data.responseText.trim();
                $("#paidcustomerDiv").html(op);
                hideSpinner();
            }
        });
    });
});

</script>

