<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<title> SmartAdmin </title>
		<meta name="description" content="">
		<meta name="author" content="">
			
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- FAVICONS -->
		<link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">

		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

	</head>
	
<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">

				<span class="ribbon-button-alignment"> 
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
						<i class="fa fa-refresh"></i>
					</span> 
				</span>

				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li><a href="<?php echo ADMIN_URL;?>">Home</a></li>
					<li><a href="<?php echo ADMIN_URL;?>amountrate">Meter-rate</a></li>
					<li>Add</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa fa-pencil-square-o fa-fw"></i>add <span>>  Meter-rate </span></h1>
					</div>
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
						<ul id="sparks" class="">
							<li class="sparks-info">
							<?php 
							     $income1 = $this->my_model->get_income_metercustomer();
							     extract($income1);
								 $income2 = $this->my_model->get_income_monthlycustomer();
								 extract($income2);
								 $intotal = $total1 + $total2;
							?>
								<h5> Income <span class="txt-color-blue">PHP <?php print_r(number_format($intotal,2));?></span></h5>
								<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>
							<?php
							     $expense1 = $this->my_model->get_outcome_expenses();
							     extract($expense1);
								 $expense2 = $this->my_model->get_outcome_payroll();
								 extract($expense2);
								 $extotal = $extotal1 + $extotal2;
							?>
							<li class="sparks-info">
								<h5> Expense <span class="txt-color-purple">PHP <?php print_r(number_format($extotal,2));?></span></h5>
								<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>
							<?php 
							     $total_customer = $this->my_model->total_customer();
							     extract($total_customer); 
							?>
							<li class="sparks-info">
								<h5> Total Customer <span class="txt-color-greenDark">&nbsp;<?php print_r($count_id);?></span></h5>
								<div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>
						</ul>
					</div>
				</div>
				<!-- widget grid -->
				<section id="widget-grid" class="">

					<!-- row -->

					<div class="row">

						<!-- a blank row to get started -->
						<div class="col-sm-6 col-lg-12">
						

								<!-- your contents here -->
								<div class="panel panel-default">
									
									<div class="widget-body">
				
										<form class="form-horizontal" role="form" name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
										  	
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
													<legend>Generate Meter Rate Data</legend>
                                                        <div class="form-group col-lg-12">
                                                            <div class="col-lg-12 controls">
                                                                <div class="form-group">
                                                                    <span class="input-group-addon"><i class="icon-chevron-down"></i><strong> Classification: <span style="color:red;font-weight: bold;">*</span></strong></span>
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
															<span class="input-group-addon"><i class="icon-user"></i><strong>Start: <span style="color:red;font-weight: bold;">*</span></strong></span>
															<input type="number" class="form-control" id="start" name="start" min="0" required/>
															<?php echo form_error('start'); ?>
														</div>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<span class="input-group-addon"><i class="icon-user"></i><strong>End: <span style="color:red;font-weight: bold;">*</span></strong></span>
															<input type="number" class="form-control" id="end" name="end" min="0" required/>
															<?php echo form_error('end'); ?>
														</div>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<span class="input-group-addon"><i class="icon-user"></i><strong>Rate: <span style="color:red;font-weight: bold;">*</span></strong></span>
															<input type="number" class="form-control" id="rate" name="rate" step="0.01" placeholder="0.00" min="0" required/>
															<?php echo form_error('rate'); ?>
														</div>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<span class="input-group-addon"><i class="icon-user"></i><strong>Consumption Rate:</strong></span>
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
													
													<div class="form-actions">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>amountrate" class="btn btn-default">Cancel</a>
																<button type="button" class="btn btn-primary" id="submitBtn" name="add" value="Add">Generate</button>
															</div>
														</div>
													
										</form>
				
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
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->
		

		<?php include('footer.php');?>

	</body>

</html>

<!-- PAGE RELATED PLUGIN(S) -->
		<!-- SweetAlert2 -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script type="text/javascript">
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
		$(document).ready(function() {
			
			pageSetUp();
			
			/* // DOM Position key index //
		
			l - Length changing (dropdown)
			f - Filtering input (search)
			t - The Table! (datatable)
			i - Information (records)
			p - Pagination (paging)
			r - pRocessing 
			< and > - div elements
			<"#id" and > - div with an id
			<"class" and > - div with a class
			<"#id.class" and > - div with an id and class
			
			Also see: http://legacy.datatables.net/usage/features
			*/	
	
			/* BASIC ;*/
				var responsiveHelper_dt_basic = undefined;
				var responsiveHelper_datatable_fixed_column = undefined;
				var responsiveHelper_datatable_col_reorder = undefined;
				var responsiveHelper_datatable_tabletools = undefined;
				
				var breakpointDefinition = {
					tablet : 1024,
					phone : 480
				};
	
				$('#dt_basic').dataTable({
					"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
					"autoWidth" : true,
			        "oLanguage": {
					    "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
					},
					"preDrawCallback" : function() {
						// Initialize the responsive datatables helper once.
						if (!responsiveHelper_dt_basic) {
							responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
						}
					},
					"rowCallback" : function(nRow) {
						responsiveHelper_dt_basic.createExpandIcon(nRow);
					},
					"drawCallback" : function(oSettings) {
						responsiveHelper_dt_basic.respond();
					}
				});
	
			/* END BASIC */
			
			/* COLUMN FILTER  */
		    var otable = $('#datatable_fixed_column').DataTable({
		    	//"bFilter": false,
		    	//"bInfo": false,
		    	//"bLengthChange": false
		    	//"bAutoWidth": false,
		    	//"bPaginate": false,
		    	//"bStateSave": true // saves sort state using localStorage
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
				"autoWidth" : true,
				"oLanguage": {
					"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
				},
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_fixed_column) {
						responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_fixed_column.respond();
				}		
			
		    });
		    
		    // custom toolbar
		    $("div.toolbar").html('<div class="text-right"><img src="img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');
		    	   
		    // Apply the filter
		    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
		    	
		        otable
		            .column( $(this).parent().index()+':visible' )
		            .search( this.value )
		            .draw();
		            
		    } );
		    /* END COLUMN FILTER */   
	    
			/* COLUMN SHOW - HIDE */
			$('#datatable_col_reorder').dataTable({
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
				"autoWidth" : true,
				"oLanguage": {
					"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
				},
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_col_reorder) {
						responsiveHelper_datatable_col_reorder = new ResponsiveDatatablesHelper($('#datatable_col_reorder'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_col_reorder.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_col_reorder.respond();
				}			
			});
			
			/* END COLUMN SHOW - HIDE */
	
			/* TABLETOOLS */
			$('#datatable_tabletools').dataTable({
				
				// Tabletools options: 
				//   https://datatables.net/extensions/tabletools/button_options
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
				"oLanguage": {
					"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
				},		
		        "oTableTools": {
		        	 "aButtons": [
		             "copy",
		             "csv",
		             "xls",
		                {
		                    "sExtends": "pdf",
		                    "sTitle": "SmartAdmin_PDF",
		                    "sPdfMessage": "SmartAdmin PDF Export",
		                    "sPdfSize": "letter"
		                },
		             	{
	                    	"sExtends": "print",
	                    	"sMessage": "Generated by SmartAdmin <i>(press Esc to close)</i>"
	                	}
		             ],
		            "sSwfPath": "js/plugin/datatables/swf/copy_csv_xls_pdf.swf"
		        },
				"autoWidth" : true,
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_tabletools) {
						responsiveHelper_datatable_tabletools = new ResponsiveDatatablesHelper($('#datatable_tabletools'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_tabletools.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_tabletools.respond();
				}
			});
			
			/* END TABLETOOLS */
		
		})

		</script>
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