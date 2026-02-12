<!DOCTYPE html>
	
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
					<li><a href="<?php echo ADMIN_URL;?>reports/customer_report">Customer Reports</a></li>
					<li>search</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="glyphicon glyphicon-search"></i>Reports <span>>  Customer Report </span></h1>
					</div>
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
						<ul id="sparks" class="">
							<li class="sparks-info">
							<?php 
							     $income1 = $this->comm_model->get_income_metercustomer();
							     extract($income1);
								 $income2 = $this->comm_model->get_income_monthlycustomer();
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
														
														<div class="col-xs-12" id="paidcustomerDiv" style="margin-top: 13px;"></div>
															
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
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->
		

		<?php 
		// Use absolute path for footer to avoid path issues on Linux
		$footerPath = APPPATH . 'modules/master/views/footer.php';
		if (file_exists($footerPath)) {
			include($footerPath);
		} else {
			log_message('error', 'Footer file not found: ' . $footerPath);
		}
		?>

	</body>

</html>

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
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
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
            var printUrl = window.location.protocol + '//' + window.location.host + '/master/reports/customerprinttopdf/'+status+'/'+zone+'/'+preparedby+'/'+verifiedby+'/'+approvedby;
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

            if(status===''){
                status=99;
            }
            if(zone==='' || zone===0){
                zone=0;
            }
            
            // Redirect to export function
            window.location.href = "<?php echo ADMIN_URL;?>reports/customerexporttoexcel/"+status+'/'+zone+'/'+preparedby+'/'+verifiedby+'/'+approvedby;
        });

        $('#search').on('click', function(evt){
            evt.preventDefault();
            var zone = $("#zone").val();
            var status = $("#status").val();

            // Show loading indicator
            $("#paidcustomerDiv").html('<div class="alert alert-info">Loading...</div>');

            // Use relative URL to avoid mixed content issues
            var ajaxUrl = window.location.protocol + '//' + window.location.host + '/master/reports/getcustomerreportsearch';
            $.ajax({
                type    : "POST",
                url	    : ajaxUrl,
                data	: "zone="+zone+'&status='+status,
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

