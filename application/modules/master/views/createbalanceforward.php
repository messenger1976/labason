<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>dashboard">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>createbalanceforward">Create Balance Forward</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-th-list"></i>
			Manage <span class="fw-300">Createbalanceforward</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Createbalanceforward <span class="fw-300"><i>Details</i></span></h2>
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
                                
                                
            
                                    <div class="form-horizontal" >
                                        
                                        <?php if(isset($msg) && $msg != ''){?>
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
                                            <legend>Create Balance Forward
                                            <div  class="pull-right" style="padding-right:20px;">
                                                <button type="button" class="btn btn-sm btn-info" name="display" id="display" value="Display" style="margin-right: 5px;">Display</button>
                                                <button type="button" class="btn btn-sm btn-primary" name="process" id="process" value="Process">Process</button>
                                            </div>
                                            </legend>
                                                
                                                <div class="form-group col-lg-6">
                                                    <div class="col-lg-12 controls">
                                                        <div class="form-group">
                                                    <span class="input-group-addon"><i class="icon-user"></i><strong>Current Billing Period : </strong></span>
                                                            <select  class="form-control" name="currentbillingperiod" id="currentbillingperiod" class="col-lg-12" required>
                                                            <option value="">--Select--</option>
                                                            <?php foreach($billingperiod as $key =>$value){ ?>
                                                            <option value="<?php echo $value['bp_period_month'].' '.$value['bp_period_year']; ?>"><?php echo $value['month_name'].' '.$value['bp_period_year'];?></option>
                                                            <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <div class="col-lg-12 controls">
                                                        <div class="form-group">
                                                        <span class="input-group-addon"><i class="icon-user"></i><strong>Next Billing Period : </strong></span>
                                                            <select  class="form-control" name="forwardbillingperiod" id="forwardbillingperiod" class="col-lg-12" required>
                                                            <option value="">--Select--</option>
                                                            <?php foreach($billingperiod as $key =>$value){ ?>
                                                            <option value="<?php echo $value['bp_period_month'].' '.$value['bp_period_year']; ?>"><?php echo $value['month_name'].' '.$value['bp_period_year'];?></option>
                                                            <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <div class="col-lg-12 controls">
                                                        <div class="form-group">
                                                    <span class="input-group-addon"><i class="icon-user"></i><strong>Zone : </strong></span>
                                                            <select  class="form-control" name="zone_listing" id="zone_listing" class="col-lg-12" required>
                                                            <option value="">--All--</option>
                                                            <?php foreach($zone_listing as $key =>$value){ ?>
                                                            <option value="<?php echo $value['id']; ?>"><?php echo $value['zone'];?></option>
                                                            <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                        
                                        </fieldset>

            
                                </div>
                                
                                
                            </div>	
                        </div>
                        <div class="col-sm-6 col-lg-12" id="progressBarDiv" style="margin-top: 13px; display:none;">
                            <div class="panel panel-default">
                                
                                    <h4>Processing Balance Forward...</h4>
                                    <div class="progress" style="height: 35px;">
                                        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                            <span id="progressText" style="line-height: 35px; font-size: 14px; font-weight: bold;">0%</span>
                                        </div>
                                    </div>
                                    <p id="currentCustomerText" style="margin-top: 10px; font-weight: bold; color: #333;"></p>
                                    <p id="progressDetails" style="margin-top: 5px; color: #666; font-size: 12px;"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-12" id="balanceForwardResultsDiv" style="margin-top: 13px;"></div>	
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
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
		$(document).ready(function() {
			
			pageSetUp();
			
			$('#display').on('click', function(evt){
				evt.preventDefault();
				
				var billingperiodforward = $('#forwardbillingperiod').val();
				var zone_id = $('#zone_listing').val();
				
				if(billingperiodforward == '' || zone_id == ''){
					Swal.fire({
						icon: 'warning',
						title: 'Validation Error',
						text: 'Please select Next Billing Period and Zone to display results!',
						confirmButtonColor: '#3085d6'
					});
					return false;
				}
				
				// Load and display results without processing
				showSpinner();
				loadBalanceForwardResults();
			});
			
			$('#process').on('click', function(evt){
				evt.preventDefault();
				
				var billingperiodforward = $('#forwardbillingperiod').val();
				var currentbillingperiod = $('#currentbillingperiod').val();
				var zone_id = $('#zone_listing').val();
				
				if(billingperiodforward == '' || currentbillingperiod == '' || zone_id == ''){
					Swal.fire({
						icon: 'warning',
						title: 'Validation Error',
						text: 'Please select all required fields!',
						confirmButtonColor: '#3085d6'
					});
					return false;
				}

				Swal.fire({
					title: 'Confirm Balance Forward Processing',
					text: 'Are you sure you want to continue processing balance forward?',
					icon: 'question',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, Process it!',
					cancelButtonText: 'Cancel'
				}).then((result) => {
					if (result.isConfirmed) {
						// Initialize batch processing
						startBatchProcessing(billingperiodforward, currentbillingperiod, zone_id);
					}
				});
			});
			
			function startBatchProcessing(billingperiodforward, currentbillingperiod, zone_id){
				// Show progress bar
				$('#progressBarDiv').show();
				$('#balanceForwardResultsDiv').html('');
				updateProgressBar(0, 0, '', '');
				
				// Clear previous batch first, then initialize (avoid session race)
				$.ajax({
					url: "<?php echo base_url();?>master/createbalanceforward/clearbatch",
					type: "POST",
					dataType: 'json'
				}).always(function() {
					// Initialize batch processing after clear (success or fail)
					$.ajax({
						url: "<?php echo base_url();?>master/createbalanceforward/processbalanceforward",
						type: "POST",
						data: {
							billingperiodforward: billingperiodforward,
							currentbillingperiod: currentbillingperiod,
							zone_listing: zone_id
						},
						dataType: 'json',
						success: function(response){
							if(response && response.success){
								processNextBatch();
							} else {
								Swal.fire({
									icon: 'error',
									title: 'Error',
									text: (response && response.message) ? response.message : 'Failed to start batch processing.',
									confirmButtonColor: '#3085d6'
								});
								$('#progressBarDiv').hide();
							}
						},
						error: function(xhr, status, error){
							var msg = 'An error occurred while initializing batch processing.';
							try {
								if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
								else if (xhr.responseText) msg = xhr.responseText.substring(0, 200);
							} catch (e) {}
							Swal.fire({
								icon: 'error',
								title: 'Error',
								text: msg,
								confirmButtonColor: '#3085d6'
							});
							$('#progressBarDiv').hide();
						}
					});
				});
			}
			
			function processNextBatch(){
				$.ajax({
					url: "<?php echo base_url();?>master/createbalanceforward/processbatch",
					type: "POST",
					dataType: 'json',
					timeout: 90000, // 90 seconds per batch
					success: function(response){
						if(response.success){
							updateProgressBar(response.total, response.processed, response.current_customer, response.percentage);
							
							if(response.complete){
								// Processing complete
								Swal.fire({
									icon: 'success',
									title: 'Success!',
									text: 'Balance Forward processed successfully!',
									confirmButtonColor: '#3085d6'
								}).then(() => {
									$('#progressBarDiv').hide();
									loadBalanceForwardResults();
								});
							}else{
								// Process next batch after a short delay
								setTimeout(processNextBatch, 500);
							}
						}else{
							Swal.fire({
								icon: 'error',
								title: 'Error',
								text: response.message || 'Error processing batch',
								confirmButtonColor: '#3085d6'
							});
							$('#progressBarDiv').hide();
						}
					},
					error: function(xhr, status, error){
						console.log('Batch error:', error);
						// Retry after delay
						setTimeout(processNextBatch, 1000);
					}
				});
			}
			
			function updateProgressBar(total, processed, currentCustomer, percentage){
				if(percentage === undefined || percentage === null){
					percentage = total > 0 ? Math.round((processed / total) * 100) : 0;
				}
				
				$('#progressBar').css('width', percentage + '%');
				$('#progressBar').attr('aria-valuenow', percentage);
				$('#progressText').text(percentage + '% (' + processed + ' / ' + total + ')');
				$('#progressDetails').text('Processed: ' + processed + ' of ' + total + ' customers');
				
				if(currentCustomer){
					$('#currentCustomerText').text('Processing: ' + currentCustomer);
				}else if(processed == total && total > 0){
					$('#currentCustomerText').text('Processing completed!');
				}
			}
			
			function loadBalanceForwardResults(){
				var billingperiodforward = $('#forwardbillingperiod').val();
				var zone_id = $('#zone_listing').val();
				
				showSpinner();
				$.ajax({
					type : "POST",
					url	: '<?php echo base_url();?>master/createbalanceforward/getbalanceforwardresults',
					data	: "billingperiodforward="+billingperiodforward+"&zone_listing="+zone_id,
					complete: function(data){
						try {
							var op = data.responseText.trim();
							$("#balanceForwardResultsDiv").html(op);
						} catch (e) {
							console.error('Error loading balance forward results:', e);
							$("#balanceForwardResultsDiv").html('<div class="alert alert-danger">Failed to load results. Please try again.</div>');
						} finally {
							setTimeout(hideSpinner, 500);
						}
					}
				});
			}

		})

		</script>

