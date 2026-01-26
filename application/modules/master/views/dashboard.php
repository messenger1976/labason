
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
					<li>Home</li><li>Dashboard</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<!--<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Dashboard <span>> My Dashboard</span></h1>
					</div>
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
						<ul id="sparks" class="">
							<li class="sparks-info">
							
								<h5> Income <span class="txt-color-blue">PHP </span></h5>
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
				</div>-->


				<div class="row">
					
					<div class="col-sm-12">
						
						<div class="well well-light">
							
							<!--<h1>Professional, <small>4 Plans</small></h1>-->
							<div class="row">
								
						        <div class="col-xs-12 col-sm-6 col-md-3">
						            <div class="panel panel-success pricing-big">
						            	
						                <div class="panel-heading">
						                    <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-lg"></i>
						                        Total Sales</h3>
						                </div>
						                <div class="panel-body no-padding text-align-center">
						                    <div class="the-price">
						                        <h1>
													<?php 
							     $income1 = $this->comm_model->get_income_metercustomer();
							     extract($income1);
								 $income2 = $this->comm_model->get_income_monthlycustomer();
								 extract($income2);
								 $intotal = $total1 + $total2;
							?>
						                            <strong>₱ <?php print_r(number_format($intotal,2));?></strong></h1>
						                    </div>
											
						                </div>
						                <div class="panel-footer text-align-center">
						                    <a href="<?php echo ADMIN_URL;?>addpaymentcustomer/add" class="btn btn-primary btn-block" role="button">Add Bills Payment <span> now!</span></a>
						                	
						                </div>
						            </div>
						        </div>
						        
						        <div class="col-xs-12 col-sm-6 col-md-3">
						            <div class="panel panel-teal pricing-big">
						            	
						                <div class="panel-heading">
						                    <h3 class="panel-title"><i class="fa fa-user fa-lg"></i>
						                        Total Customers</h3>
						                </div>
						                <div class="panel-body no-padding text-align-center">
						                    <div class="the-price">
						                        <h1>
													<?php 
							     $total_customer = $this->my_model->total_customer();
							     extract($total_customer); 
							?>
						                            <strong><?php print_r($count_id);?></strong></h1>
						                    </div>
											
						                </div>
						                <div class="panel-footer text-align-center">
						                    <a href="<?php echo ADMIN_URL;?>addcustomer" class="btn btn-primary btn-block" role="button">Customers Listing<span> now!</span></a>
						                	
						                </div>
						            </div>
						        </div>
						        
						        <div class="col-xs-12 col-sm-6 col-md-3">
						            <div class="panel panel-primary pricing-big">
						            	<img src="<?php echo ADMIN_URL;?>img/ribbon.png" class="ribbon" alt="">
						                <div class="panel-heading">
						                    <h3 class="panel-title"><i class="fa fa-money fa-lg"></i>
						                        Total Leaking Balances</h3>
						                </div>
						                <div class="panel-body no-padding text-align-center">
						                    <div class="the-price">
						                        <h1>
						                           <strong>₱ <?php print_r(number_format($leaking['total'],2));?></strong></h1>
						                    </div>
											
						                </div>
						                <div class="panel-footer text-align-center">
						                    <a href="<?php echo ADMIN_URL;?>Leakingentry" class="btn btn-primary btn-block" role="button">Leaking Listing<span> now!</span></a>
						                	
						                </div>
						            </div>
						        </div>
						        
						        <div class="col-xs-12 col-sm-6 col-md-3">
						            <div class="panel panel-darken pricing-big">
						            	
						                <div class="panel-heading">
						                    <h3 class="panel-title"><i class="fa fa-comments fa-lg"></i>
						                        Open Tickets</h3>
						                </div>
						                <div class="panel-body no-padding text-align-center">
						                    <div class="the-price">
						                        <h1>
						                            <strong><?php print_r($total_prbm);?></strong></h1>
						                    </div>
											
						                </div>
						                <div class="panel-footer text-align-center">
						                    <a href="<?php echo ADMIN_URL;?>technicalproblems" class="btn btn-primary btn-block" role="button">Tickets Listing<span> now!</span></a>
						                	
						                </div>
						            </div>
						        </div>		    	
				    		</div>
				
							
				
		
							
						</div>
						
					</div>
					
				</div>

				<!-- widget grid -->
				<section id="widget-grid" class="">




					

					<!-- row -->
					<div class="row">
				
						<!-- NEW WIDGET START -->
						<article class="col-sm-12">

							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
								<!-- widget options:
								usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

								data-widget-colorbutton="false"
								data-widget-editbutton="false"
								data-widget-togglebutton="false"
								data-widget-deletebutton="false"
								data-widget-fullscreenbutton="false"
								data-widget-custombutton="false"
								data-widget-collapsed="true"
								data-widget-sortable="false"

								-->
								<header>
									<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
									<h6>&nbsp;Sales Chart</h6>

								</header>

								<!-- widget div-->
								<div>

									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->

									</div>
									<!-- end widget edit box -->

									<!-- widget content -->
									<div class="widget-body no-padding">
										<!-- Year selector above chart -->
										<div style="padding: 15px; border-bottom: 1px solid #efefef; background-color: #fafafa;">
											<div class="row">
												<div class="col-xs-12">
													<label class="control-label" style="margin-right: 10px; font-weight: bold;">Select Year:</label>
													<select id="yearSelector" class="form-control" style="display: inline-block; width: auto; min-width: 120px; z-index: 1000; position: relative;">
														<?php if(isset($available_years) && !empty($available_years)): ?>
															<?php foreach($available_years as $year): ?>
																<option value="<?php echo $year; ?>" <?php echo (isset($selected_year) && $selected_year == $year) ? 'selected' : ''; ?>>
																	<?php echo $year; ?>
																</option>
															<?php endforeach; ?>
														<?php else: ?>
															<option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
														<?php endif; ?>
													</select>
												</div>
											</div>
										</div>
										<canvas id="barChart" height="60"></canvas>
										

									</div>
									<!-- end widget content -->

								</div>
								<!-- end widget div -->

							</div>
							<!-- end widget -->


							
				
						</article>
						<!-- WIDGET END -->

						
						<!-- NEW WIDGET START -->
						<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							
							

							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-darken" id="wid-id-3" data-widget-colorbutton="false" data-widget-fullscreenbutton="false" data-widget-editbutton="false" data-widget-sortable="false">
								<!-- widget options:
									usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
									
									data-widget-colorbutton="false"	
									data-widget-editbutton="false"
									data-widget-togglebutton="false"
									data-widget-deletebutton="false"
									data-widget-fullscreenbutton="false"
									data-widget-custombutton="false"
									data-widget-collapsed="true" 
									data-widget-sortable="false"
									
								-->
								<header>

									<h6> <i> </i>Customer by Zone Area - Chart </h6>				
									
								</header>

								<!-- widget div-->
								<div>
									
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										<input class="form-control" type="text">	
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body">
										
										<!-- this is what the user will see -->
										<canvas id="doughnutChart" height="120"></canvas>

									</div>
									<!-- end widget content -->
									
								</div>
								<!-- end widget div -->
								
							</div>
							<!-- end widget -->
						</article>
						<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-darken" id="wid-id-4" data-widget-colorbutton="false" data-widget-fullscreenbutton="false" data-widget-editbutton="false" data-widget-sortable="false">
								<!-- widget options:
									usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
									
									data-widget-colorbutton="false"	
									data-widget-editbutton="false"
									data-widget-togglebutton="false"
									data-widget-deletebutton="false"
									data-widget-fullscreenbutton="false"
									data-widget-custombutton="false"
									data-widget-collapsed="true" 
									data-widget-sortable="false"
									
								-->
								<header>

									<h6>My Tickets - Chart </h6>				
									
								</header>

								<!-- widget div-->
								<div>
									
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										<input class="form-control" type="text">	
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body">
										
										<!-- this is what the user will see -->
										<canvas id="pieChart" height="120"></canvas>

									</div>
									<!-- end widget content -->
									
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
		

		<?php include('footer.php');?>

	</body>

</html>

<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url();?>js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatable-responsive/datatables.responsive.min.js"></script>



		<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
		<script src="<?php echo base_url();?>js/plugin/flot/jquery.flot.cust.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/flot/jquery.flot.resize.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/flot/jquery.flot.fillbetween.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/flot/jquery.flot.orderBar.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/flot/jquery.flot.pie.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/flot/jquery.flot.time.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/flot/jquery.flot.tooltip.min.js"></script>

		<!-- EASY PIE CHARTS -->
		<script src="<?php echo base_url();?>js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
    	<script src="<?php echo base_url();?>js/plugin/chartjs/chart.min.js"></script>

		<script type="text/javascript">
		
		/* chart colors default */
			var chrt_border_color = "#efefef";
			var chrt_grid_color = "#DDD";
			var chrt_main = "#E24913";
			/* red       */
			var chrt_second = "#6595b4ff";
			/* blue      */
			var chrt_third = "#FF9F01";
			/* orange    */
			var chrt_fourth = "rgba(126, 157, 58, 1)";
			/* green     */
			var chrt_fifth = "#BD362F";
			/* dark red  */
			var chrt_mono = "#000";

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


			    // BAR CHART

			    var barOptions = {
				    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
				    scaleBeginAtZero : true,
				    //Boolean - Whether grid lines are shown across the chart
				    scaleShowGridLines : true,
				    //String - Colour of the grid lines
				    scaleGridLineColor : "rgba(0,0,0,.05)",
				    //Number - Width of the grid lines
				    scaleGridLineWidth : 1,
				    //Boolean - If there is a stroke on each bar
				    barShowStroke : true,
				    //Number - Pixel width of the bar stroke
				    barStrokeWidth : 1,
				    //Number - Spacing between each of the X value sets
				    barValueSpacing : 5,
				    //Number - Spacing between data sets within X values
				    barDatasetSpacing : 1,
				    //Boolean - Re-draw chart on page resize
			        responsive: true,
				    //String - A legend template
				    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
			    }
				// Always show all 12 months to match the data arrays
				var month_array = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
				var JanTotal = parseFloat('<?php echo isset($JanTotal['total']) ? $JanTotal['total'] : 0;?>') || 0;
				var FebTotal = parseFloat('<?php echo isset($FebTotal['total']) ? $FebTotal['total'] : 0;?>') || 0;
				var MarTotal = parseFloat('<?php echo isset($MarchTotal['total']) ? $MarchTotal['total'] : 0;?>') || 0;
				var AprTotal = parseFloat('<?php echo isset($AprilTotal['total']) ? $AprilTotal['total'] : 0;?>') || 0;
				var MayTotal = parseFloat('<?php echo isset($MayTotal['total']) ? $MayTotal['total'] : 0;?>') || 0;
				var JunTotal = parseFloat('<?php echo isset($JuneTotal['total']) ? $JuneTotal['total'] : 0;?>') || 0;
				var JulTotal = parseFloat('<?php echo isset($JulyTotal['total']) ? $JulyTotal['total'] : 0;?>') || 0;
				var AugTotal = parseFloat('<?php echo isset($AugTotal['total']) ? $AugTotal['total'] : 0;?>') || 0;
				var SepTotal = parseFloat('<?php echo isset($SepTotal['total']) ? $SepTotal['total'] : 0;?>') || 0;
				var OctTotal = parseFloat('<?php echo isset($OctTotal['total']) ? $OctTotal['total'] : 0;?>') || 0;
				var NovTotal = parseFloat('<?php echo isset($NovTotal['total']) ? $NovTotal['total'] : 0;?>') || 0;
				var DecTotal = parseFloat('<?php echo isset($DecTotal['total']) ? $DecTotal['total'] : 0;?>') || 0;
			    var barData = {
			        labels: month_array,
			         datasets: [
				        {
				            label: "Unpaid",
							fillColor: "rgba(101, 149, 180, 1)",
				            strokeColor: "rgba(220,220,220,0.8)",
				            highlightFill: "rgba(220,220,220,0.75)",
				            highlightStroke: "rgba(220,220,220,1)",
							backgroundColor:'rgba(255, 99, 132, 0.5)',  // Red with 50% opacity
							borderColor: 'rgba(255, 99, 132, 1)',   // Solid Red
				            data: [
								<?php echo number_format(isset($JanTotalUnpaid['total']) ? $JanTotalUnpaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($FebTotalUnpaid['total']) ? $FebTotalUnpaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($MarchTotalUnpaid['total']) ? $MarchTotalUnpaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($AprilTotalUnpaid['total']) ? $AprilTotalUnpaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($MayTotalUnpaid['total']) ? $MayTotalUnpaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($JuneTotalUnpaid['total']) ? $JuneTotalUnpaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($JulyTotalUnpaid['total']) ? $JulyTotalUnpaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($AugTotalUnpaid['total']) ? $AugTotalUnpaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($SepTotalUnpaid['total']) ? $SepTotalUnpaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($OctTotalUnpaid['total']) ? $OctTotalUnpaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($NovTotalUnpaid['total']) ? $NovTotalUnpaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($DecTotalUnpaid['total']) ? $DecTotalUnpaid['total'] : 0,0,'.','');?>
							]
				        },
						{
				            label: "Total Collection",
				            fillColor: "rgba(102, 102, 102, 1)",
				            strokeColor: "rgba(151,187,205,0.8)",
				            highlightFill: "rgba(151,187,205,0.75)",
				            highlightStroke: "rgba(151,187,205,1)",
							backgroundColor:'rgba(255, 206, 86, 0.5)',  // Red with 50% opacity
							borderColor: 'rgba(255, 206, 86, 1)',   // Solid Red
				            data: [
								<?php echo number_format(isset($JanTotalPaid['total']) ? $JanTotalPaid['total'] : 0,0,'.','');?>, 
								<?php echo number_format(isset($FebTotalPaid['total']) ? $FebTotalPaid['total'] : 0,0,'.','');?>, 
								<?php echo number_format(isset($MarchTotalPaid['total']) ? $MarchTotalPaid['total'] : 0,0,'.','');?>, 
								<?php echo number_format(isset($AprilTotalPaid['total']) ? $AprilTotalPaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($MayTotalPaid['total']) ? $MayTotalPaid['total'] : 0,0,'.','');?>, 
								<?php echo number_format(isset($JuneTotalPaid['total']) ? $JuneTotalPaid['total'] : 0,0,'.','');?>, 
								<?php echo number_format(isset($JulyTotalPaid['total']) ? $JulyTotalPaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($AugTotalPaid['total']) ? $AugTotalPaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($SepTotalPaid['total']) ? $SepTotalPaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($OctTotalPaid['total']) ? $OctTotalPaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($NovTotalPaid['total']) ? $NovTotalPaid['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($DecTotalPaid['total']) ? $DecTotalPaid['total'] : 0,0,'.','');?>
							]
				        },
				        {
				            label: "Total Collectables",
							// Change the background color of the bars
							/* backgroundColor: [
								'rgba(255, 99, 132, 0.5)',  // Red with 50% opacity
								'rgba(54, 162, 235, 0.5)',   // Blue with 50% opacity
								'rgba(255, 206, 86, 0.5)',   // Yellow with 50% opacity
								'rgba(75, 192, 192, 0.5)',   // Green with 50% opacity
								'rgba(153, 102, 255, 0.5)',  // Purple with 50% opacity
								'rgba(255, 159, 64, 0.5)'    // Orange with 50% opacity
								],
								// Change the border color of the bars
								borderColor: [
								'rgba(255, 99, 132, 1)',   // Solid Red
								'rgba(54, 162, 235, 1)',    // Solid Blue
								'rgba(255, 206, 86, 1)',    // Solid Yellow
								'rgba(75, 192, 192, 1)',    // Solid Green
								'rgba(153, 102, 255, 1)',   // Solid Purple
								'rgba(255, 159, 64, 1)'     // Solid Orange
								],*/
    						borderWidth: 1, // You can also control the border width
				            fillColor: "rgba(126, 157, 58, 1)",
				            strokeColor: "rgba(151,187,205,0.8)",
				            highlightFill: "rgba(151,187,205,0.75)",
				            highlightStroke: "rgba(151,187,205,1)",
							backgroundColor:'rgba(54, 162, 235, 0.5)',  // Red with 50% opacity
							borderColor: 'rgba(54, 162, 235, 1)',   // Solid Red
				            data: [
								<?php echo number_format(isset($JanTotal['total']) ? $JanTotal['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($FebTotal['total']) ? $FebTotal['total'] : 0,0,'.','');?>, 
								<?php echo number_format(isset($MarchTotal['total']) ? $MarchTotal['total'] : 0,0,'.','');?>, 
								<?php echo number_format(isset($AprilTotal['total']) ? $AprilTotal['total'] : 0,0,'.','');?>, 
								<?php echo number_format(isset($MayTotal['total']) ? $MayTotal['total'] : 0,0,'.','');?>, 
								<?php echo number_format(isset($JuneTotal['total']) ? $JuneTotal['total'] : 0,0,'.','');?>, 
								<?php echo number_format(isset($JulyTotal['total']) ? $JulyTotal['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($AugTotal['total']) ? $AugTotal['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($SepTotal['total']) ? $SepTotal['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($OctTotal['total']) ? $OctTotal['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($NovTotal['total']) ? $NovTotal['total'] : 0,0,'.','');?>,
								<?php echo number_format(isset($DecTotal['total']) ? $DecTotal['total'] : 0,0,'.','');?>
							]
				        }
				    ]
			    };

			    // render chart
				// Configuration for the chart
				const config = {
					type: 'bar', // Type of chart (e.g., 'bar', 'line', 'pie', 'doughnut')
					data: barData,
					options: {
						responsive: true,
						scales: {
							y: {
								beginAtZero: true
							}
						}
					}
				};
			    var ctx = document.getElementById("barChart").getContext("2d");
			    //var myNewChart = new Chart(ctx).Bar(barData, barOptions);
				const myChart = new Chart(ctx, config);
			    // END BAR CHART

				
				

				// Sample data for the pie chart
				const data1 = {
				labels: ['Pending', 'Assigned', 'On going', 'Resolved','Un-Resolved','Resolved - Closed','UnResolved - Closed'],
				datasets: [{
					label: 'My Tickets',
					data: [<?php echo $ticket0['count_id'];?>, <?php echo $ticket1['count_id'];?>, <?php echo $ticket2['count_id'];?>,<?php echo $ticket3['count_id'];?>,<?php echo $ticket4['count_id'];?>,<?php echo $ticket5['count_id'];?>,<?php echo $ticket6['count_id'];?>],
					backgroundColor: [
					'#ff6384ff',
					'#36a2ebff',
					'#5959ccff',
					'#ffcd56ff',
					'#236c35ff',
					'#661e52ff',
					'#3cc0e1ff'					
					],
					hoverOffset: 4
				}]
				};
				// Configuration for the chart
				const config1 = {
				type: 'pie', // Type of chart (e.g., 'pie', 'doughnut')
				data: data1,
				options: {
					responsive: true,
					plugins: {
					legend: {
						position: 'top',
					},
					title: {
						display: true,
						text: 'My Tickets Chart'
					}
					}
				}
				};
			    var ctx1 = document.getElementById("pieChart").getContext("2d");
			    //var myNewChart = new Chart(ctx).Bar(barData, barOptions);
				const myChart1 = new Chart(ctx1, config1);

				// Dynamic data for the doughnut chart - shows all zones
				<?php
				$zoneLabels = array();
				$zoneData = array();
				$zoneColors = array();
				$colorPalette = array(
					'rgb(255, 99, 132)',
					'rgb(54, 162, 235)',
					'rgb(255, 205, 86)',
					'rgba(60, 28, 89, 1)',
					'rgb(75, 192, 192)',
					'rgb(153, 102, 255)',
					'rgb(255, 159, 64)',
					'rgb(199, 199, 199)',
					'rgb(83, 102, 255)',
					'rgb(255, 99, 255)',
					'rgb(99, 255, 132)',
					'rgb(255, 205, 86)',
					'rgb(54, 162, 235)',
					'rgb(255, 99, 132)',
					'rgb(153, 102, 255)'
				);
				
				if(isset($zones) && !empty($zones)) {
					foreach($zones as $index => $zone) {
						$zoneLabels[] = $zone['zone'];
						$zoneData[] = $zone['count_id'];
						$zoneColors[] = $colorPalette[$index % count($colorPalette)];
					}
				}
				?>
				const data2 = {
				labels: <?php echo json_encode($zoneLabels); ?>,
				datasets: [{
					label: 'Customer Zone Area',
					data: <?php echo json_encode($zoneData); ?>,
					backgroundColor: <?php echo json_encode($zoneColors); ?>,
					hoverOffset: 8
				}]
				};

				// Configuration for the chart
				const config2 = {
				type: 'doughnut', // Type of chart is set to 'doughnut'
				data: data2,
				options: {
					responsive: true,
					plugins: {
					legend: {
						position: 'top',
					},
					title: {
						display: true,
						text: 'Zone Chart'
					}
					}
				}
				};
				var ctx2 = document.getElementById("doughnutChart").getContext("2d");
			    //var myNewChart = new Chart(ctx).Bar(barData, barOptions);
				const myChart2 = new Chart(ctx2, config2);





		
		})



		</script>

		
		<script>				
		/*$(document).ready(function() {
			$('#expensesmore').dataTable().fnDestroy();
			table = $('#expensesmore').dataTable( {
				"ajax": {
					url: "<?php echo base_url();?>master/dashboard/index/pagination",
					data: {},
					type: "POST",
				},
				"columns": [
			{ "data": "id" },
			{ "data": "expenses_id" },
			{ "data": "expenses_type" },
			{ "data": "total" },			
			],
			"language": {
            "infoEmpty": "No Indent Available.",
				sProcessing: '<i class="fa fa-spinner fa-pulse  fa-4x" ></i>'
						},
					processing : true
				});
		});	*/
		</script>
				
		<script>				
		$(document).ready(function() {
			$('#monthexp').dataTable().fnDestroy();
			table = $('#monthexp').dataTable( {
				"ajax": {
					url: "<?php echo base_url();?>master/dashboard/index/paginationtwo",
					data: {},
					type: "POST",
				},
				"columns": [
			{ "data": "id" },
			{ "data": "expenses_id" },
			{ "data": "expenses_type" },
			{ "data": "total" },			
			],
			"language": {
            "infoEmpty": "No Indent Available.",
				sProcessing: '<i class="fa fa-spinner fa-pulse  fa-4x" ></i>'
						},
					processing : true
				});
	});	
				</script>
				
	<script>				
					$(document).ready(function() {
					var f = 'Meter';
					var s = 'Monthly';
			$('#firstpagei').dataTable().fnDestroy();
			table = $('#firstpagei').dataTable( {
				"ajax": {
					url: "<?php echo base_url();?>index.php/master/dashboard/index/paginationfrst",
					data: {},
					type: "POST",
				},
				"columns": [
			{ "data": "id" },
			{ "data": "customer_id" },
			{ "data": "full_name" },
			{ "data": "customer_type",
				render: function ( data, type, row ) {
                        if(data == 'monthlycustomer'){ return 'monthly'; };
                        if(data == 'metercustomer'){ return 'meter'; };
				},
			},		
			],
			"language": {
            "infoEmpty": "No Data Available.",
				sProcessing: '<i class="fa fa-spinner fa-pulse  fa-4x" ></i>'
						},
					processing : true
				});
	});	
				</script>
				
		<script>
		// Year selector change handler - reload page with selected year
		$(document).ready(function() {
			$('#yearSelector').on('change', function() {
				var selectedYear = $(this).val();
				var currentUrl = window.location.href.split('?')[0];
				window.location.href = currentUrl + '?year=' + selectedYear;
			});
		});
		</script>
