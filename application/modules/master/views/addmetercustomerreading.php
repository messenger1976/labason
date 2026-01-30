
	
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
					<li><a href="<?php echo ADMIN_URL?>">Home</a></li>
					<li><a href="<?php echo ADMIN_URL?>addmetercustomerreading/add/">Meter Customer Reading Add</a></li>
					<li>List View</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> View <span>> Meter Customer Reading  </span>						</h1>
					</div>
					<div class="col-xs-12 col-sm-10 col-md-7 col-lg-6">
											
											</div>
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
						<ul id="sparks" class="">
							<li class="sparks-info">
								<h5> Billing Period <span class="txt-color-blue">
									
								<select  class="form-control" name="header_billingperiod" id="header_billingperiod" class="col-lg-12" required>
									<option value="">--All--</option>
									<?php
									
									foreach($billingperiod as $key =>$value){ 
										$val_val = $value['bp_period_month'].' '.$value['bp_period_year'];
										$selected_val = '';
										if($_SESSION['current_billingperiod']==$val_val){
											$selected_val = 'selected';
										}
									?>
									<option value="<?php echo $value['bp_period_month'].' '.$value['bp_period_year']; ?>" <?php echo $selected_val;?>><?php echo $value['month_name'].' '.$value['bp_period_year'];?></option>
									<?php } ?>
								</select>								
								</span></h5>
							</li>
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
				
						<!-- NEW WIDGET START -->
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
								
								<header style="height: 42px;">
									<span class="widget-icon"> <i class="fa fa-users"></i> </span>
									<p style="padding: 5px 0 0 45px;font-size: 16px;"><strong>Meter Customer Reading</strong>
									<button class="btn btn-sm btn-primary" style="float:right;"><a href="<?php echo ADMIN_URL?>addmetercustomerreading/add" style="color: #fff;"><i class="fa fa-plus"></i> Add Meter Reading</a></button>
									</p>
								</header>
				
								<!-- widget div-->
								<div>
				
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
				
									</div>
									<!-- end widget edit box -->
									<script type="text/javascript">
                                        function deleteAllData(){ 
                                            var checked_num = $('input[name="delete_ids[]"]:checked').length;
                                            if (checked_num == 0) {
                                                alert('Select Atleast One Check Box... ');
                                                return false;
                                            }else if (checked_num > 0){ 
                                                if(confirm('Confirm Delete?')==true){
                                                    //$('#careers').submit();
                                                    return true;
                                                }else{
													return false;
												}
                                            }
                                        }
                                    </script>
				                    <form method="post" action="<?php echo ADMIN_URL;?>addmetercustomerreading/multi_delete">
										<!-- widget content -->
										<div class="widget-body no-padding">
										   <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<th data-hide="phone"><input type="checkbox" class="ace" /></th>
														<th data-hide="phone">S No</th>
														<th data-class="expand">Billing Ref No</th>
														<th data-class="expand">Customer-Id</th>
														<th data-class="expand">Customer Name</th>
														<th data-hide="expand">Previous Reading</th>
														<th data-hide="expand">Current Reading</th>
														<th data-hide="expand">Consumed</th>
														<th data-hide="expand">Billing Period</th>
														<th data-hide="expand">Reading Date</th>
														<!--<th data-hide="expand">Action</th>-->
													</tr>
												</thead>
												<tbody>
													<!-- Data will be loaded via AJAX -->
												</tbody>
											</table>
											

										</div>
										<!-- end widget content -->
										<div>&nbsp;</div>
									  <div class="row">
									   <div class="col-lg-12">
                                        	<input type="submit" class="btn btn-sm btn-primary" name="add" id="add" value="Delete All" onClick="return deleteAllData();" />
                                         </div>
									</div>
				                    </form>  
									 
									 <div>&nbsp;</div>
								</div>
								<!-- end widget div -->
				
							</div>
							<!-- end widget -->
				
						</article>
						<!-- WIDGET END -->
				
					</div>
				
					<!-- end row -->

					

				</section>
				<!-- end widget grid -->

			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->
		
		<!-- Loading Modal Overlay -->
		<div id="datatable-loading-modal" style="display: none;">
			<div class="loading-overlay">
				<div class="loading-content">
					<div class="loading-spinner">
						<i class="fa fa-spinner fa-spin fa-4x"></i>
					</div>
					<div class="loading-text">
						<h3>Loading data...</h3>
						<p>Please wait while we fetch the records</p>
					</div>
				</div>
			</div>
		</div>

		<?php include('footer.php');?>

	</body>

</html>
<style>
	/* Loading Modal Styles */
	#datatable-loading-modal {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 9999;
		background-color: rgba(0, 0, 0, 0.7);
		backdrop-filter: blur(2px);
		display: none; /* Hidden by default, shown via JavaScript */
	}
	
	.loading-overlay {
		display: flex;
		justify-content: center;
		align-items: center;
		width: 100%;
		height: 100%;
		min-height: 100vh;
	}
	
	.loading-content {
		background: #ffffff;
		border-radius: 10px;
		padding: 40px 60px;
		box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
		text-align: center;
		min-width: 300px;
		border: 3px solid #3498db;
	}
	
	.loading-spinner {
		margin-bottom: 20px;
		color: #3498db;
	}
	
	.loading-spinner .fa-spinner {
		animation: spin 1s linear infinite;
	}
	
	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}
	
	.loading-text h3 {
		color: #2c3e50;
		margin: 0 0 10px 0;
		font-size: 24px;
		font-weight: bold;
	}
	
	.loading-text p {
		color: #7f8c8d;
		margin: 0;
		font-size: 14px;
	}
	
	/* Ensure table is visible but dimmed when loading */
	.dataTables_wrapper {
		position: relative;
	}
	
	.dataTables_wrapper.processing {
		opacity: 0.5;
		pointer-events: none;
	}
</style>
<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url();?>js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script type="text/javascript">
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
		$(document).ready(function() {
			
			pageSetUp();
			
			// Show loading modal immediately on page load
			$('#datatable-loading-modal').show();
			$('.dataTables_wrapper').addClass('processing');
			
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
	
			/* BASIC - Server-side Processing */
				var responsiveHelper_dt_basic = undefined;
				var responsiveHelper_datatable_fixed_column = undefined;
				var responsiveHelper_datatable_col_reorder = undefined;
				var responsiveHelper_datatable_tabletools = undefined;
				
				var breakpointDefinition = {
					tablet : 1024,
					phone : 480
				};
				
				var isInitialLoad = true;
	
				var table = $('#dt_basic').DataTable({
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo ADMIN_URL;?>addmetercustomerreading/get_datatable_data",
						"type": "POST",
						"data": function(d) {
							// Pass selected billing period with every request for filtering
							d.billing_period = $('#header_billingperiod').val() || '';
							return d;
						}
					},
					"columns": [
						{ "data": 0, "orderable": false },
						{ "data": 1, "orderable": false },
						{ "data": 2, "orderable": true },
						{ "data": 3, "orderable": true },
						{ "data": 4, "orderable": true },
						{ "data": 5, "orderable": true },
						{ "data": 6, "orderable": true },
						{ "data": 7, "orderable": true },
						{ "data": 8, "orderable": true },
						{ "data": 9, "orderable": true }
					],
					"order": [[0, 'desc']],
					"pageLength": 100,
					"lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
					"searchDelay": 999999, // Disable auto-search on typing
					"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
					"autoWidth" : true,
			        "oLanguage": {
					    "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>',
						"sProcessing": ""
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
						// Hide loading modal after first data load
						if (isInitialLoad) {
							isInitialLoad = false;
							setTimeout(function() {
								$('#datatable-loading-modal').fadeOut(200);
								$('.dataTables_wrapper').removeClass('processing');
							}, 300);
						}
					},
					"initComplete": function(settings, json) {
						setupCustomSearch();
					}
				});
				
				// Function to set up custom search handlers
				function setupCustomSearch() {
					var searchInput = $('.dataTables_filter input');
					
					if(searchInput.length > 0 && !searchInput.data('custom-search-bound')) {
						// Remove DataTables default search event handlers
						searchInput.off('keyup.DT input.DT');
						
						// Handle Enter key press
						searchInput.on('keypress.custom', function(e) {
							if (e.which === 13) { // Enter key
								e.preventDefault();
								var searchValue = $(this).val();
								table.search(searchValue).draw();
								return false;
							}
						});
						
						// Handle blur event (when input loses focus)
						searchInput.on('blur.custom', function() {
							var searchValue = $(this).val();
							table.search(searchValue).draw();
						});
						
						// Mark as bound to prevent duplicate bindings
						searchInput.data('custom-search-bound', true);
					}
				}
				
				// Set up handlers after table is drawn (in case search input is recreated)
				table.on('draw.dt', function() {
					// Reset the bound flag so we can rebind if input is recreated
					$('.dataTables_filter input').removeData('custom-search-bound');
					setupCustomSearch();
				});
				
				// Show/hide loading modal based on processing state
				table.on('processing.dt', function(e, settings, processing) {
					if (processing) {
						// Only fade in if not already visible (to avoid flicker on initial load)
						if (!$('#datatable-loading-modal').is(':visible')) {
							$('#datatable-loading-modal').fadeIn(200);
						}
						$('.dataTables_wrapper').addClass('processing');
					} else {
						// Only fade out if it's not the initial load
						if (!isInitialLoad) {
							$('#datatable-loading-modal').fadeOut(200);
							$('.dataTables_wrapper').removeClass('processing');
						}
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

			$('#header_billingperiod').on('change', function(evt){
				evt.preventDefault();
				var header_billing_period = $(this).val();
				// Show loading modal
				$('#datatable-loading-modal').fadeIn(200);
				$('.dataTables_wrapper').addClass('processing');
				
				$.ajax({
            		type : "POST",
					url	: '<?php echo ADMIN_URL;?>addbillingperiod/updated_headerbillingperiod',
					data	: "billing_period="+header_billing_period,
					complete: function(data){
						console.log(data);
						// Reload the DataTable after billing period change
						if(typeof table !== 'undefined') {
							table.ajax.reload(null, false); // false = don't reset pagination
						} else {
							location.reload();
						}
					},
					error: function() {
						// Hide loading modal on error
						$('#datatable-loading-modal').fadeOut(200);
						$('.dataTables_wrapper').removeClass('processing');
					}
				});

				//alert($(this).val());
			});
		
		})

		</script>

		