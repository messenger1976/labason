<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>reports/customer_report">Customer Reports</a></li>
		<li class="breadcrumb-item active">search</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user-friends"></i>
			Manage <span class="fw-300">Customer Report</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Customer Report <span class="fw-300"><i>Details</i></span></h2>
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
															Customer Report -Search 
															<div  class="pull-right" style="padding-right:20px;">
																<input type="submit" class="btn btn-primary" name="search" id="search" value="search" style="margin-bottom: 5px;">
																<a id="printtopdf" class="btn btn-sm btn-warning" style="margin-bottom: 5px;">Print</a>
																<a id="exporttoexcel" class="btn btn-sm btn-success" style="margin-bottom: 5px;">Export to Excel</a>
															</div>
														</legend>
														<div class="form-group col-lg-6">
                                                            <div class="col-lg-12 controls">
                                                                <div class="form-group">
                                                                <span class="input-group-addon"><i class="icon-user"></i><strong>Status : </strong></span>
                                                                    <select  class="form-control" name="status" id="status" class="col-lg-12" required>
                                                                        <option value="">--All--</option>
                                                                        <option value="1">Active</option>
                                                                        <option value="0">Inactive</option>
                                                                        <option value="2">Disconnected</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        
                                                        </div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Special Priviledge:</strong></span>
																	<input class="form-check" value="1" type="checkbox" name="special_priviledge" id="special_priviledge">
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

        $('#printtopdf').on('click',function(evt){
            evt.preventDefault();
            var zone = $("#zone").val();
            var preparedby = $("#preparedby").val();
            var verifiedby = $("#verifiedby").val();
            var approvedby = $("#approvedby").val();
            var status = $("#status").val();
			var specialPriviledge = $("#special_priviledge").is(":checked") ? 1 : 0;

            if(status===''){
                status=99;
            }
            if(zone==='' || zone===0){
                zone=0;
            }
            if(preparedby===''){
                alert("Please select Prepared by");
                return false;
            }
            if(verifiedby===''){
                alert("Please select Verified by");
                return false;
            }
            if(approvedby===''){
                alert("Please select Approved by");
                return false;
            }
            // Use current protocol to avoid mixed content issues
			var printUrl = window.location.protocol + '//' + window.location.host + '/master/reports/customerprinttopdf/'+status+'/'+zone+'/'+preparedby+'/'+verifiedby+'/'+approvedby+'/'+specialPriviledge;
            const popup = window.open(
                printUrl, // URL to display 
                "PopupWindowPrint", // Name of the window
                "width=1200,height=600,resizable=yes,scrollbars=yes" // Window settings
            );

            // Optional: Check if the popup was blocked
            if (!popup || popup.closed || typeof popup.closed == "undefined") {
                alert("Popup was blocked! Please allow popups for this site.");
            }

        });

        $('#exporttoexcel').on('click',function(evt){
            evt.preventDefault();
            var zone = $("#zone").val();
            var preparedby = $("#preparedby").val();
            var verifiedby = $("#verifiedby").val();
            var approvedby = $("#approvedby").val();
            var status = $("#status").val();
			var specialPriviledge = $("#special_priviledge").is(":checked") ? 1 : 0;

            if(status===''){
                status=99;
            }
            if(zone==='' || zone===0){
                zone=0;
            }
            
            // Redirect to export function
			window.location.href = "<?php echo ADMIN_URL;?>reports/customerexporttoexcel/"+status+'/'+zone+'/'+preparedby+'/'+verifiedby+'/'+approvedby+'/'+specialPriviledge;
        });

        $('#search').on('click', function(evt){
            evt.preventDefault();
            var zone = $("#zone").val();
            var status = $("#status").val();
			var specialPriviledge = $("#special_priviledge").is(":checked") ? 1 : 0;

            // Show loading indicator
            $("#paidcustomerDiv").html('<div class="alert alert-info">Loading...</div>');

            // Use relative URL to avoid mixed content issues
            var ajaxUrl = window.location.protocol + '//' + window.location.host + '/master/reports/getcustomerreportsearch';
            $.ajax({
                type    : "POST",
                url	    : ajaxUrl,
				data	: "zone="+zone+'&status='+status+'&special_priviledge='+specialPriviledge,
                success: function(response){
                    if(response && typeof response === 'string' && response.trim().length > 0){
                        $("#paidcustomerDiv").html(response.trim());
                    } else {
                        $("#paidcustomerDiv").html('<div class="alert alert-warning">No data found.</div>');
                    }
                },
                error: function(xhr, status, error){
                    console.error('AJAX Error:', status, error);
                    console.error('Response Status:', xhr.status);
                    console.error('Response Text:', xhr.responseText);
                    var errorMsg = 'Error loading data. Please try again.';
                    if(xhr && xhr.responseText){
                        // Try to extract error message from response
                        var responseText = xhr.responseText;
                        // If it's HTML with error, try to extract
                        if(responseText.indexOf('Fatal error') !== -1 || responseText.indexOf('Parse error') !== -1 || responseText.indexOf('Warning') !== -1){
                            errorMsg = 'Server error occurred. Please check server logs.';
                        } else if(responseText.length > 0 && responseText.length < 500){
                            // If response is short, might be an error message
                            errorMsg = responseText.substring(0, 200);
                        }
                    }
                    $("#paidcustomerDiv").html('<div class="alert alert-danger"><strong>Error:</strong> ' + errorMsg + '<br><small>Status: ' + xhr.status + '</small></div>');
                }
            });
        });
    });

</script>

