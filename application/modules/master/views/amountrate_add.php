<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>amountrate">Meter-rate</a></li>
		<li class="breadcrumb-item active">Add</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-th-list"></i>
			Manage <span class="fw-300">Amountrate Add</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Amountrate Add <span class="fw-300"><i>Details</i></span></h2>
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
													<h5 class="mb-3">Generate Meter Rate Data</h5>
                                                        <div class="form-group col-lg-12">
                                                            <div class="col-lg-12 controls">
                                                                <div class="form-group">
                                                                    <label class="form-label"> Classification: <span style="color:red;font-weight: bold;">*</span></label>
                                                                    <select class="form-control" name="classification" id="classification" required>
                                                                    <option value="">--Select--</option>
                                                                    <?php foreach($classification as $key => $value){ ?>
                                                                    <option value="<?php echo $value['class_id'];?>"><?php echo $value['class_name'];?></option>
                                                                    <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<label class="form-label">Start: <span style="color:red;font-weight: bold;">*</span></label>
															<input type="number" class="form-control" id="start" name="start" min="0" required/>
															<?php echo form_error('start'); ?>
														</div>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<label class="form-label">End: <span style="color:red;font-weight: bold;">*</span></label>
															<input type="number" class="form-control" id="end" name="end" min="0" required/>
															<?php echo form_error('end'); ?>
														</div>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<label class="form-label">Rate: <span style="color:red;font-weight: bold;">*</span></label>
															<input type="number" class="form-control" id="rate" name="rate" step="0.01" placeholder="0.00" min="0" required/>
															<?php echo form_error('rate'); ?>
														</div>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<label class="form-label">Consumption Rate:</label>
															<input type="number" class="form-control" id="incre" name="incre" step="0.01" placeholder="0.00" min="0"/>
															<?php echo form_error('incre'); ?>
														</div>
													</div>
												</div>
												<div class="form-group col-lg-12">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<label class="checkbox-inline">
																<input type="checkbox" id="apply_increment" name="apply_increment" value="1" checked>
																<strong>Increment?</strong> (If checked, rate will increment by Consumption Rate for each cubic meter. If unchecked, same rate will be used for all.)
															</label>
														</div>
													</div>
												</div>		
														
											
													</fieldset>

													<!-- Progress Bar -->
													<div id="progressBarDiv" style="display: none; margin: 20px 0;">
														<div class="progress" style="height: 30px;">
															<div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
																 role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
																<span id="progressText">0%</span>
															</div>
														</div>
														<div id="progressStatus" style="text-align: center; margin-top: 10px; font-weight: bold;">
															Processing records...
														</div>
													</div>
													
													<div class="form-group mt-3">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>amountrate" class="btn btn-secondary">Cancel</a>
																<button type="button" class="btn btn-primary" id="submitBtn" name="add" value="Add">Generate</button>
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
		
		// Form submission with SweetAlert confirmation and progress bar
		$(document).ready(function() {
			// Auto-fill rate when start value changes
			$('#start').on('change blur', function() {
				var startValue = parseInt($(this).val());
				var classificationId = $('#classification').val() || '';
				
				// Allow 0 and positive values
				if(!isNaN(startValue) && startValue >= 0) {
					if(startValue === 0) {
						// If start is 0, then start-1 = -1, so set rate to 0
						$('#rate').val('0.00');
					} else {
						var searchValue = startValue - 1;
						
						// Make AJAX call to get per_unit
						$.ajax({
							url: "<?php echo ADMIN_URL;?>amountrate/get_rate_by_cubic_meter",
							type: "POST",
							headers: {
								'X-Requested-With': 'XMLHttpRequest'
							},
							data: {
								cubic_meter: searchValue,
								classification_id: classificationId
							},
							dataType: 'json',
							success: function(response) {
								if(response.success && response.per_unit > 0) {
									$('#rate').val(parseFloat(response.per_unit).toFixed(2));
								} else {
									$('#rate').val('0.00');
								}
							},
							error: function() {
								$('#rate').val('0.00');
							}
						});
					}
				}
			});
			
			// Also trigger when classification changes (to search within that classification)
			$('#classification').on('change', function() {
				var startValue = parseInt($('#start').val());
				if(startValue && startValue > 0) {
					$('#start').trigger('change');
				}
			});
			
			$('#submitBtn').on('click', function(e) {
				e.preventDefault();
				
				// Get form values
				var classification = $('#classification').val();
				var start = $('#start').val();
				var end = $('#end').val();
				var rate = $('#rate').val();
				var incre = $('#incre').val() || '';
				var applyIncrement = $('#apply_increment').is(':checked') ? 1 : 0;
				
				// Validate form - check for empty strings, null, or undefined (allow 0 values)
				// Convert to strings and trim whitespace
				var startStr = (start !== null && start !== undefined) ? String(start).trim() : '';
				var endStr = (end !== null && end !== undefined) ? String(end).trim() : '';
				var rateStr = (rate !== null && rate !== undefined) ? String(rate).trim() : '';
				
				// Debug: log values to console
				console.log('Validation check:', {
					classification: classification,
					start: startStr,
					end: endStr,
					rate: rateStr,
					startEmpty: startStr === '',
					endEmpty: endStr === '',
					rateEmpty: rateStr === '',
					classificationEmpty: !classification || classification === ''
				});
				
				// Check if fields are empty (allow 0 as valid value - "0" is not empty)
				var isClassificationEmpty = !classification || classification === '' || classification === null;
				var isStartEmpty = startStr === '' || startStr === null || startStr === undefined;
				var isEndEmpty = endStr === '' || endStr === null || endStr === undefined;
				var isRateEmpty = rateStr === '' || rateStr === null || rateStr === undefined;
				
				console.log('Empty checks:', {
					isClassificationEmpty: isClassificationEmpty,
					isStartEmpty: isStartEmpty,
					isEndEmpty: isEndEmpty,
					isRateEmpty: isRateEmpty
				});
				
				if(isClassificationEmpty || isStartEmpty || isEndEmpty || isRateEmpty) {
					var missingFields = [];
					if(isClassificationEmpty) missingFields.push('Classification');
					if(isStartEmpty) missingFields.push('Start');
					if(isEndEmpty) missingFields.push('End');
					if(isRateEmpty) missingFields.push('Rate');
					
					console.log('Missing fields:', missingFields);
					
					Swal.fire({
						icon: 'warning',
						title: 'Validation Error',
						text: 'Please fill in all required fields: ' + missingFields.join(', '),
						confirmButtonColor: '#3085d6'
					});
					return false;
				}
				
				// Convert to numbers for further validation
				start = parseFloat(startStr);
				end = parseFloat(endStr);
				rate = parseFloat(rateStr);
				
				if(parseInt(start) > parseInt(end)) {
					Swal.fire({
						icon: 'warning',
						title: 'Validation Error',
						text: 'Start value must be less than or equal to End value.',
						confirmButtonColor: '#3085d6'
					});
					return false;
				}
				
				if(parseInt(start) < 0 || parseInt(end) < 0) {
					Swal.fire({
						icon: 'warning',
						title: 'Validation Error',
						text: 'Start and End values must be 0 or positive numbers.',
						confirmButtonColor: '#3085d6'
					});
					return false;
				}
				
				// Calculate total records
				var totalRecords = Math.floor(end) - Math.floor(start) + 1;
				
				// Show confirmation dialog
				Swal.fire({
					title: 'Confirm Generation',
					html: 'Are you sure you want to generate meter rate data?<br><br>' +
						  '<strong>Classification:</strong> ' + $('#classification option:selected').text() + '<br>' +
						  '<strong>Range:</strong> ' + Math.floor(start) + ' to ' + Math.floor(end) + ' (' + totalRecords + ' records)<br>' +
						  '<strong>Initial Rate:</strong> ' + parseFloat(rate).toFixed(2) +
						  (incre ? '<br><strong>Consumption Rate:</strong> ' + parseFloat(incre).toFixed(2) : '') +
						  '<br><strong>Apply Increment:</strong> ' + (applyIncrement ? 'Yes' : 'No'),
					icon: 'question',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, Generate!',
					cancelButtonText: 'Cancel'
				}).then((result) => {
					if (result.isConfirmed) {
						// Show progress bar
						$('#progressBarDiv').show();
						$('#progressBar').css('width', '0%').attr('aria-valuenow', 0);
						$('#progressText').text('0%');
						$('#progressStatus').text('Processing records...');
						$('#submitBtn').prop('disabled', true);
						
						// Simulate progress update
						var progressInterval = setInterval(function() {
							var currentWidth = parseInt($('#progressBar').attr('aria-valuenow'));
							if(currentWidth < 90) {
								var newWidth = currentWidth + Math.random() * 10;
								if(newWidth > 90) newWidth = 90;
								$('#progressBar').css('width', newWidth + '%').attr('aria-valuenow', newWidth);
								$('#progressText').text(Math.round(newWidth) + '%');
							}
						}, 200);
						
						// Submit form via AJAX
						$.ajax({
							url: "<?php echo ADMIN_URL;?>amountrate/add",
							type: "POST",
							headers: {
								'X-Requested-With': 'XMLHttpRequest'
							},
						data: {
							classification: classification,
							start: Math.floor(start),
							end: Math.floor(end),
							rate: parseFloat(rate),
							incre: incre ? parseFloat(incre) : '',
							apply_increment: applyIncrement,
							add: 'add'
						},
							dataType: 'json',
							success: function(response) {
								clearInterval(progressInterval);
								
								// Complete progress bar
								$('#progressBar').css('width', '100%').attr('aria-valuenow', 100);
								$('#progressText').text('100%');
								
								if(response.success) {
									setTimeout(function() {
										$('#progressStatus').text('Processing completed successfully!');
										
										// Show success alert
										Swal.fire({
											icon: 'success',
											title: 'Success!',
											html: response.message + '<br><br>' +
												  '<strong>Total Records:</strong> ' + response.total + '<br>' +
												  '<strong>Inserted:</strong> ' + response.inserted + '<br>' +
												  '<strong>Updated:</strong> ' + response.updated,
											confirmButtonColor: '#3085d6',
											confirmButtonText: 'OK'
										}).then((result) => {
											// Redirect to listing page
											window.location.href = "<?php echo ADMIN_URL;?>amountrate";
										});
									}, 500);
								} else {
									$('#progressBarDiv').hide();
									$('#submitBtn').prop('disabled', false);
									
									Swal.fire({
										icon: 'error',
										title: 'Error',
										text: response.message || 'An error occurred while processing.',
										confirmButtonColor: '#3085d6'
									});
								}
							},
							error: function(xhr, status, error) {
								clearInterval(progressInterval);
								$('#progressBarDiv').hide();
								$('#submitBtn').prop('disabled', false);
								
								var errorMsg = 'An error occurred while processing the request.';
								if(xhr.responseJSON && xhr.responseJSON.message) {
									errorMsg = xhr.responseJSON.message;
								}
								
								Swal.fire({
									icon: 'error',
									title: 'Error',
									text: errorMsg,
									confirmButtonColor: '#3085d6'
								});
							}
						});
					}
				});
			});
		});
	
		</script>

