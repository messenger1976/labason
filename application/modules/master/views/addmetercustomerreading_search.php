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
					<li><a href="<?php echo ADMIN_URL;?>addcustomer">customer</a></li>
					<li>search</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="glyphicon glyphicon-search"></i>&nbsp;search <span>>  Meter Customer-Search  </span></h1>
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
				
										<div class="form-horizontal" >
										  	
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
												<legend>Meter Customer-Search
												        <div class="pull-right" style="padding-right:20px;">
															
															
														</div>
												</legend>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<span class="input-group-addon"><i class="icon-user"></i><strong>Search : </strong></span>
															<select class="form-control"  id="search_box_id" name="search_box_id" id="search_box_id" placeholder="Type text to search..." required>
																	
																	<?php
																	$selected = $member_id;
																	foreach ($record as $key => $value) {
																		?>
																		<option <?php echo ($selected ? ($selected == $value->member_id ? 'selected="selected"' : '') : ''); ?> value="<?php echo $value['customer_id']; ?>"> <?php echo $value['customer_id'] . ' ==> ' . $value['last_name'] . ', ' . $value['first_name'] . ' ' . $value['middle_name']; ?></option>
																	<?php }
																	?>
																</select>

																<!--<input  class="form-control"  id="search_box_id" name="name" id="name" required/>-->
															<?php echo form_error('search_box_id'); ?>
														</div>
													</div>
												</div>
												<input type="submit" class="btn btn-primary" name="search" id="search" value="search" style="margin-bottom: 5px;">
												<input type="button" class="btn btn-success" name="add_billing_period" id="add_billing_period" value="Add" style="margin-bottom: 5px; background-color:green; display:none;">
												<input type="hidden" name="record_id"	id="record_id"/>
												<input type="hidden" name="customer_id"	id="customer_id"/>
												<input type="hidden" name="cust_type_id" id="cust_type_id"/>
												<input type="hidden" name="special_priviledge" id="special_priviledge"/>
															
												
													
											</fieldset>

												
				
									</div>
								    
									
								</div>	
						</div>
						
						<div class="col-sm-6 col-lg-12" id="customerDiv" style="margin-top: 13px;"></div>	
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
		



        				<!-- Modal -->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									&times;
								</button>
								<h4 class="modal-title" id="myModalLabel">Edit Customer Meter Reading</h4>
							</div>
							<div class="modal-body">
								
                                <form name="frm_update" id="frm_update" action="" method="POST">
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Billing Period : </strong></span>
                                                <input class="form-control" type="text" id="billing_period" name="billing_period" style="background-color:yellow;" readonly>
                                                <?php echo form_error('billing_period'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Previous Reading : </strong></span>
                                                <input class="form-control" type="text" id="previous_reading" name="previous_reading" style="background-color:white;" value="0" required>
                                                <?php echo form_error('previous_reading'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Current Reading : <i style="color:red;">*</i></strong></span>
                                                <input class="form-control" type="text" id="current_reading" name="current_reading" required>
                                                <?php echo form_error('current_reading'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Consumed : </strong></span>
                                                <input class="form-control" type="text" id="consumed" name="consumed" style="background-color:white;">
                                                <?php echo form_error('consumed'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Current Bill : </strong></span>
                                                <input class="form-control" type="text" id="current_bill" name="current_bill" style="background-color:white;">
                                                <?php echo form_error('current_bill'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>WM Maintenance Fee : </strong></span>
                                                <input class="form-control" type="text" id="maintenance_fee" name="maintenance_fee" style="background-color:white;">
                                                <?php echo form_error('maintenance_fee'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Franchise Tax % : </strong></span>
                                                <input class="form-control" type="text" id="franchise_fee_percent" name="franchise_fee_percent" style="background-color:white;">
                                                <?php echo form_error('franchise_fee_percent'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Franchise Tax Amount : </strong></span>
                                                <input class="form-control" type="text" id="franchise_fee_amount" name="franchise_fee_amount" style="background-color:white;" readonly>
                                                <?php echo form_error('franchise_fee_amount'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>SC Discount : </strong></span>
                                                <input class="form-control" type="text" id="sc_discount" name="sc_discount" style="background-color:white;">
                                                <?php echo form_error('sc_discount'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Arrears : </strong></span>
                                                <input class="form-control" type="text" id="arrears" name="arrears" style="background-color:white;">
                                                <?php echo form_error('arrears'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Amt before due date : </strong></span>
                                                <input class="form-control" type="text" id="total_amount" name="total_amount" style="background-color:white;">
                                                <?php echo form_error('arrears'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Amt after due date : </strong></span>
                                                <input class="form-control" type="text" id="penalty" name="penalty" style="background-color:white;">
                                                <?php echo form_error('arrears'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Reading date : </strong></span>
                                                <input class="form-control" type="text" id="reading_date" name="reading_date" style="background-color:white;">
                                                <?php echo form_error('reading_date'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Customer Status : </strong></span>
                                                <input class="form-control" type="text" id="customer_status" name="customer_status" style="background-color:white;">
                                                <?php echo form_error('customer_status'); ?>
                                            </div>
                                        </div>
                                    </div>
								</form>
				
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">
									Cancel
								</button>
								<button type="button" class="btn btn-primary" id="btn_save" data-dismiss="modal">
									Update
								</button>
							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->

				<!-- Add Billing Period Modal -->
				<div class="modal fade" id="addBillingModal" tabindex="-1" role="dialog" aria-labelledby="addBillingLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									&times;
								</button>
								<h4 class="modal-title" id="addBillingLabel">Add Billing Period</h4>
								<button type="button" class="btn btn-xs btn-warning pull-right" id="btn_edit_modal" style="margin-right:10px;">Edit</button>
							</div>
							<div class="modal-body">
								<form id="frm_add_billing" name="frm_add_billing" action="" method="POST" class="form-horizontal">
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>Billing Period : </strong></span>
												<select id="billing_period_select" name="billing_period_select" class="form-control" required>
													<option value="">-- Select Billing Period --</option>
													<?php
														// show last 6 months as default options
														for ($i = 0; $i < 6; $i++) {
															$val = date('Y-m', strtotime("-$i month"));
															$label = date('F Y', strtotime("-$i month"));
															echo "<option value=\"{$val}\">{$label}</option>";
														}
													?>
												</select>
												<?php echo form_error('billing_period_select'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>Previous Reading : </strong></span>
												<input class="form-control" type="text" id="previous_reading_add" name="previous_reading_add" value="" required />
												<?php echo form_error('previous_reading_add'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>Current Reading : <i style="color:red;">*</i></strong></span>
												<input class="form-control" type="text" id="current_reading_add" name="current_reading_add" required />
												<?php echo form_error('current_reading_add'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>Consumed : </strong></span>
												<input class="form-control" type="text" id="consumed_add" name="consumed_add" style="background-color:white;" required>
												<?php echo form_error('consumed_add'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>Current Bill : </strong></span>
												<input class="form-control" type="text" id="current_bill_add" name="current_bill_add" style="background-color:white;" required>
												<?php echo form_error('current_bill_add'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>WM Maintenance Fee : </strong></span>
												<input class="form-control" type="text" id="maintenance_fee_add" name="maintenance_fee_add" style="background-color:white;" required>
												<?php echo form_error('maintenance_fee_add'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>Franchise Tax % : </strong></span>
												<input class="form-control" type="text" id="franchise_fee_percent_add" name="franchise_fee_percent_add" style="background-color:white;" required>
												<?php echo form_error('franchise_fee_percent_add'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>Franchise Tax Amount : </strong></span>
												<input class="form-control" type="text" id="franchise_fee_amount_add" name="franchise_fee_amount_add" style="background-color:white;" readonly required>
												<?php echo form_error('franchise_fee_amount_add'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>SC Discount : </strong></span>
												<input class="form-control" type="text" id="sc_discount_add" name="sc_discount_add" style="background-color:white;" required>
												<?php echo form_error('sc_discount_add'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>Arrears : </strong></span>
												<input class="form-control" type="text" id="arrears_add" name="arrears_add" style="background-color:white;" required>
												<?php echo form_error('arrears_add'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>Amt before due date : </strong></span>
												<input class="form-control" type="text" id="total_amount_add" name="total_amount_add" style="background-color:white;" required>
												<?php echo form_error('total_amount_add'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>Amt after due date : </strong></span>
												<input class="form-control" type="text" id="penalty_add" name="penalty_add" style="background-color:white;" required>
												<?php echo form_error('penalty_add'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>Reading date : </strong></span>
												<input class="form-control" type="text" id="reading_date_add" name="reading_date_add" style="background-color:white;" required>
												<?php echo form_error('reading_date_add'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>Customer Status : </strong></span>
												<input class="form-control" type="text" id="customer_status_add" name="customer_status_add" style="background-color:white;" required>
												<?php echo form_error('customer_status_add'); ?>
											</div>
										</div>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								<button type="button" class="btn btn-primary" id="save_billing_period">Save</button>
							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->



		<?php include('footer.php');?>

	</body>

</html>

<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url();?>js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<!-- SweetAlert2 for nicer alerts -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
					"pageLength": -1, // Show all rows by default
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
	
		</script>
		
		<script type="text/javascript">
var curDate = '<?php echo date('d-m-Y') ?>';	
function fun_calendor(field){
	$("#"+field).focus();
} 
$(document).ready(function(){
	$('#search_box_id').select2();

	// Hide the Add Billing Period button and the datatable whenever the selected search item changes
	$('#search_box_id').on('change', function(){
		$('#add_billing_period').hide();
		// remove any existing datatable HTML and hide the container
		$('#customerDiv').empty().hide();
	});

	$('#search').on('click', function(evt){
		evt.preventDefault();
		let search_text = $("#search_box_id").val();
		const search_text_result = search_text.split("==>");
		var id = search_text_result[0];
		$('#customer_id').val(id);

		$.ajax({
			beforeSend: function() {
				showSpinner(); // Call this to show the spinner
			},
			type : "POST",
			url	: '<?php echo ADMIN_URL;?>addmetercustomerreading/getaddcustomersmetersearch',
			data	: "customer_id="+id,
			complete: function(data){
				var op = data.responseText.trim();
				// insert returned HTML into the container and ensure it's visible
				$("#customerDiv").html(op).show();

				// hide spinner now that content is inserted
				hideSpinner();

				// If the returned content contains any table element (datatable),
				// show the Add Billing Period button; otherwise hide it.
				if ($("#customerDiv").find("table").length > 0) {
					$("#add_billing_period").show();
				} else {
					$("#add_billing_period").hide();
				}
			}
		});
	});
	
/**
 * Reusable function to calculate franchise fee and totals
 * Date Modified: February 6, 2026
 * 
 * Purpose: Calculate franchise fee (based on % of current bill) and total amounts including maintenance fee and penalty.
 *          Franchise tax is ALWAYS based on the current bill. Senior citizen discount (if any) is applied
 *          when computing the total: total = current_bill - sc_discount + maintenance_fee + franchise_fee_amount.
 */
function recalculateFranchiseFeeAndTotals() {
	var unit_price = parseFloat($('#current_bill').val().replace(/,/g, '') || 0);
	var maintenance_fee = parseFloat($('#maintenance_fee').val().replace(/,/g, '') || 0);
	var multiprice = unit_price;
	var discount = parseFloat($('#sc_discount').val().replace(/,/g, '') || 0);
	
	// Get consumption value to check senior citizen discount eligibility
	var consumed = parseFloat($('#consumed').val() || 0);
	
	// Get franchise fee percentage from input field, or use default from global settings
	var franchise_fee_percentage = parseFloat($('#franchise_fee_percent').val().replace(/,/g, '') || 0);
	if (!franchise_fee_percentage || franchise_fee_percentage <= 0) {
		franchise_fee_percentage = <?php echo isset($franchise_fee_percentage) ? floatval($franchise_fee_percentage) : '2.00'; ?>;
		$('#franchise_fee_percent').val(franchise_fee_percentage);
	}
	
	/**
	 * Franchise Tax Calculation - Based on Current Bill
	 * Date Modified: February 6, 2026
	 * 
	 * Business Rule: Franchise tax is ALWAYS computed on the current bill amount.
	 *               Senior citizen discount (if applicable) is applied to the bill
	 *               when calculating the total, but franchise tax is based on the
	 *               current bill before any discount.
	 */
	var bill_amount_for_franchise = multiprice;  // Always use current bill for franchise tax
	var franchise_fee_amount = (bill_amount_for_franchise * franchise_fee_percentage) / 100;
	
	// Total: current bill - senior citizen discount (if any) + maintenance + franchise tax
	var total_amount = multiprice - discount;
	total_amount += parseFloat(maintenance_fee);
	total_amount += parseFloat(franchise_fee_amount);
	
	// Update franchise fee fields
	if($('#franchise_fee_percent').length) {
		$('#franchise_fee_percent').val(franchise_fee_percentage);
	}
	if($('#franchise_fee_amount').length) {
		$('#franchise_fee_amount').val(amount_formatted(franchise_fee_amount));
	}
	
	// Calculate penalty
	var amount_total_penalty = 0;
	if($('#special_priviledge').val()==='0'){
		amount_total_penalty = (total_amount * 10)/100;
		amount_total_penalty = amount_total_penalty + total_amount;
	}else{
		amount_total_penalty = total_amount;
	}
	
	// Update total amount and penalty fields
	if($('#total_amount').length) {
		$('#total_amount').val(amount_formatted(total_amount));
	}
	if($('#penalty').length) {
		$('#penalty').val(amount_formatted(amount_total_penalty));
	}
	if($('#amount_pay').length) {
		$('#amount_pay').val(amount_formatted(multiprice));
	}
}

$('#btn_save').on('click', function(evt){
	evt.preventDefault();
	var record_id = $('#record_id').val();
	const formData = new FormData();
	formData.append("customer_id", $('#customer_id').val());
	formData.append("previous_reading", $('#previous_reading').val());
	formData.append("current_reading", $('#current_reading').val());
	formData.append("consumed", $('#consumed').val());
	formData.append("current_bill", $('#current_bill').val());
	formData.append("sc_discount", $('#sc_discount').val());
	formData.append("arrears", $('#arrears').val());
	formData.append("total_amount", $('#total_amount').val());
	formData.append("penalty", $('#penalty').val());
	formData.append("maintenance_fee", $('#maintenance_fee').val());
	formData.append("franchise_fee_percent", $('#franchise_fee_percent').val());
	formData.append("franchise_fee_amount", $('#franchise_fee_amount').val());
	formData.append("reading_date", $('#reading_date').val());
	formData.append("customer_status", $('#customer_status').val());
	formData.append("edit", 'edit');

	$.ajax({
		url: '<?php echo ADMIN_URL;?>addmetercustomerreading/save_edit/'+record_id,
		type: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		success: function (response) {
			
			if (response=='success') {
				$('#search').trigger('click');
				//alert('Successfully Save');
				

			} 
		},
		error: function () {
			if (typeof Swal !== 'undefined') {
				Swal.fire({icon:'error', title:'Error', text: 'An error occurred while processing data.'});
			} else {
				alert("An error occurred while processing data.");
			}
		}
	});
});

$('#sc_discount').on('blur', function(evt){
	evt.preventDefault();
	recalculateFranchiseFeeAndTotals();
});

// Event listener for franchise_fee_percent field changes
$('#franchise_fee_percent').on('blur', function(evt){
	evt.preventDefault();
	recalculateFranchiseFeeAndTotals();
});

$('#maintenance_fee').on('blur', function(evt){
	evt.preventDefault();
	recalculateFranchiseFeeAndTotals();
});

// Recalculate with new logic when Edit modal is shown (after row data is populated)
$(document).on('shown.bs.modal', '#myModal', function(){
	if ($('#current_bill').length && $('#frm_update').length) {
		recalculateFranchiseFeeAndTotals();
	}
});

$('#current_reading').on('blur', function() {
	var current_meter = $(this).val();
	var previous_reading = $('#previous_reading').val();
	var differences = parseFloat(current_meter) - parseFloat(previous_reading);
	$("#consumed").val(differences);
	var difer = $("#consumed").val();
	const formData = new FormData();
	formData.append("cubic_meter_reading", difer);
	formData.append("customer_id", $('#customer_id').val());

	

	$.ajax({
		url: '<?php echo ADMIN_URL;?>addmetercustomerreading/get_cubic_meter_price/',
		type: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		success: function (response) {
			const result = JSON.parse(response);
			if (result.per_unit) {
				
				$('#current_bill').val(amount_formatted(result.per_unit));
				
				// Set initial franchise fee percentage if not set
				if(!$('#franchise_fee_percent').val() || $('#franchise_fee_percent').val() == '') {
					var default_franchise_fee_percentage = <?php echo isset($franchise_fee_percentage) ? floatval($franchise_fee_percentage) : '2.00'; ?>;
					$('#franchise_fee_percent').val(default_franchise_fee_percentage);
				}
				
				/**
				 * Senior Citizen Discount Calculation
				 * Date Modified: January 29, 2026
				 * Modified By: AI Assistant
				 * 
				 * Purpose: Apply senior citizen discount (5%) only if the customer is a senior citizen 
				 *          (account_type == 3) AND the consumption (difference between current_reading 
				 *          and previous_reading) is 30 cubic meters or less.
				 * 
				 * Reason: Business rule requirement - Senior citizens cannot avail discount if their 
				 *         consumption exceeds 30 cubic meters. This prevents abuse of the senior 
				 *         citizen discount privilege for excessive water consumption.
				 * 
				 * Previous Logic: Discount was applied to all senior citizens regardless of consumption amount.
				 * New Logic: Discount is only applied when consumption <= 30 cubic meters.
				 */
				var unit_price = parseFloat($('#current_bill').val().replace(/,/g, ''));
				var multiprice = unit_price;
				var consumed = parseFloat($('#consumed').val() || 0);
				if($('#cust_type_id').val()==3 && consumed <= 30){
					var discount = (multiprice * 5)/100;
					$('#sc_discount').val(amount_formatted(discount));
				} else {
					// Clear discount if consumption exceeds 30 cubic meters or not a senior citizen
					$('#sc_discount').val(amount_formatted(0));
				}
				
				// Recalculate franchise fee and totals
				recalculateFranchiseFeeAndTotals();
				

			} else {
				$('#current_bill').val(amount_formatted(0));
				var unit_price = $('#current_bill').val();
				
				$("#amount_pay").val(amount_formatted(0));
				if (typeof Swal !== 'undefined') {
					Swal.fire({icon:'warning', title:'No Amount', text: 'No Amount per cubic meter.'});
				} else {
					alert("No Amount per cubic meter.");
				}
			}
		},
		error: function () {
			if (typeof Swal !== 'undefined') {
				Swal.fire({icon:'error', title:'Error', text: 'An error occurred while processing data.'});
			} else {
				alert("An error occurred while processing data.");
			}
		}
	});

	
});



	$("#reading_date").datepicker({
		showAnim: null,
		dateFormat: 'dd-mm-yy',
		// showOn: 'both',
		buttonImage: '/images/calender.jpg',
		buttonImageOnly: true,
		firstDay: 1,
		nextText: '',
		prevText: '',
		numberOfMonths: [1, 1],
		defaultDate: new Date(curDate),
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
function amount_formatted(amount){
	const formatted = new Intl.NumberFormat('en-US', {
  		minimumFractionDigits: 2,
  		maximumFractionDigits: 2,
  		useGrouping: false, // No thousands separator
	}).format(amount);
	return formatted;
}
</script>	
<script type="text/javascript">
$(document).ready(function(){
	// show Add Billing Period modal when button clicked
	$('#add_billing_period').on('click', function(){
		// clear previous values and enable inputs
		$('#frm_add_billing')[0].reset();
		$('#frm_add_billing').find('input,select').prop('disabled', false);
		$('#btn_edit_modal').text('Edit');
		$('#addBillingModal').modal('show');
	});

	// Reusable calculation for Add modal (same rules as edit)
	function recalculateFranchiseFeeAndTotalsAdd() {
		var unit_price = parseFloat($('#current_bill_add').val().replace(/,/g, '') || 0);
		var maintenance_fee = parseFloat($('#maintenance_fee_add').val().replace(/,/g, '') || 0);
		var multiprice = unit_price;
		var discount = parseFloat($('#sc_discount_add').val().replace(/,/g, '') || 0);
		var consumed = parseFloat($('#consumed_add').val() || 0);
		var franchise_fee_percentage = parseFloat($('#franchise_fee_percent_add').val().replace(/,/g, '') || 0);
		if (!franchise_fee_percentage || franchise_fee_percentage <= 0) {
			franchise_fee_percentage = <?php echo isset($franchise_fee_percentage) ? floatval($franchise_fee_percentage) : '2.00'; ?>;
			$('#franchise_fee_percent_add').val(franchise_fee_percentage);
		}
		// Franchise tax is always based on current bill; senior citizen discount applied to total
		var bill_amount_for_franchise = multiprice;
		var franchise_fee_amount = (bill_amount_for_franchise * franchise_fee_percentage) / 100;
		var total_amount = multiprice - discount;
		total_amount += parseFloat(maintenance_fee);
		total_amount += parseFloat(franchise_fee_amount);
		if($('#franchise_fee_amount_add').length) {
			$('#franchise_fee_amount_add').val(amount_formatted(franchise_fee_amount));
		}
		if($('#total_amount_add').length) {
			$('#total_amount_add').val(amount_formatted(total_amount));
		}
		var amount_total_penalty = 0;
		if($('#special_priviledge').val()==='0'){
			amount_total_penalty = (total_amount * 10)/100;
			amount_total_penalty = amount_total_penalty + total_amount;
		}else{
			amount_total_penalty = total_amount;
		}
		if($('#penalty_add').length) {
			$('#penalty_add').val(amount_formatted(amount_total_penalty));
		}
	}

	// Bind events on Add modal fields to trigger recalculation
	$('#sc_discount_add, #franchise_fee_percent_add, #maintenance_fee_add').on('blur', function(){
		recalculateFranchiseFeeAndTotalsAdd();
	});

	// When current reading in Add modal loses focus, compute consumed and get unit price
	$('#current_reading_add').on('blur', function() {
		var current_meter = $(this).val();
		var previous_reading = $('#previous_reading_add').val() || 0;
		var differences = parseFloat(current_meter || 0) - parseFloat(previous_reading || 0);
		$("#consumed_add").val(differences);

		const formData = new FormData();
		formData.append("cubic_meter_reading", differences);
		formData.append("customer_id", $('#customer_id').val());

		$.ajax({
			url: '<?php echo ADMIN_URL;?>addmetercustomerreading/get_cubic_meter_price/',
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			success: function (response) {
				const result = JSON.parse(response);
				if (result.per_unit) {
					$('#current_bill_add').val(amount_formatted(result.per_unit));
					if(!$('#franchise_fee_percent_add').val() || $('#franchise_fee_percent_add').val() == '') {
						var default_franchise_fee_percentage = <?php echo isset($franchise_fee_percentage) ? floatval($franchise_fee_percentage) : '2.00'; ?>;
						$('#franchise_fee_percent_add').val(default_franchise_fee_percentage);
					}
					var unit_price = parseFloat($('#current_bill_add').val().replace(/,/g, ''));
					var multiprice = unit_price;
					var consumed = parseFloat($('#consumed_add').val() || 0);
					if($('#cust_type_id').val()==3 && consumed <= 30){
						var discount = (multiprice * 5)/100;
						$('#sc_discount_add').val(amount_formatted(discount));
					} else {
						$('#sc_discount_add').val(amount_formatted(0));
					}
					recalculateFranchiseFeeAndTotalsAdd();
				} else {
					$('#current_bill_add').val(amount_formatted(0));
					$("#amount_pay").val(amount_formatted(0));
					if (typeof Swal !== 'undefined') {
						Swal.fire({icon:'warning', title:'No Amount', text: 'No Amount per cubic meter.'});
					} else {
						alert("No Amount per cubic meter.");
					}
				}
			},
			error: function () {
				if (typeof Swal !== 'undefined') {
					Swal.fire({icon:'error', title:'Error', text: 'An error occurred while processing data.'});
				} else {
					alert("An error occurred while processing data.");
				}
			}
		});
	});

	// Save button handler - insert into tbl_addcustomer_reading via controller
	$('#save_billing_period').on('click', function(){
		// Validate required inputs first (reuse earlier check)
		var missingField = null;
		$('#frm_add_billing').find('[required]').each(function(){
			var $el = $(this);
			var val = $el.val();
			if (val === null || $.trim(val) === '') {
				missingField = $el;
				return false;
			}
		});
		if (missingField) {
			var msg = 'Please fill the "' + (missingField.prev('.input-group-addon').text().trim() || missingField.attr('name')) + '" field.';
			if (typeof Swal !== 'undefined') {
				Swal.fire({icon:'warning', title:'Validation', text: msg});
			} else {
				alert(msg);
			}
			missingField.focus();
			return;
		}

		// Prepare FormData for insertion
		const data = new FormData();
		data.append('customer_id', $('#customer_id').val());
		data.append('billing_period', $('#billing_period_select').val());
		data.append('previous_reading', $('#previous_reading_add').val());
		data.append('current_reading', $('#current_reading_add').val());
		data.append('consumed', $('#consumed_add').val());
		data.append('current_bill', $('#current_bill_add').val());
		data.append('sc_discount', $('#sc_discount_add').val());
		data.append('arrears', $('#arrears_add').val());
		data.append('total_amount', $('#total_amount_add').val());
		data.append('penalty', $('#penalty_add').val());
		data.append('maintenance_fee', $('#maintenance_fee_add').val());
		data.append('franchise_fee_percent', $('#franchise_fee_percent_add').val());
		data.append('franchise_fee_amount', $('#franchise_fee_amount_add').val());
		data.append('reading_date', $('#reading_date_add').val());
		data.append('customer_status', $('#customer_status_add').val());
		data.append('add', 'add');

		$.ajax({
			url: '<?php echo ADMIN_URL;?>addmetercustomerreading/save_add',
			type: 'POST',
			data: data,
			contentType: false,
			processData: false,
			success: function(response){
				// expecting 'success' on successful insert
				if (response && response.trim() === 'success') {
					if (typeof Swal !== 'undefined') {
						Swal.fire({icon:'success', title:'Saved', text: 'Billing period successfully added.'});
					}
					$('#addBillingModal').modal('hide');
					$('#search').trigger('click');
				} else {
					var msg = response || 'An error occurred while saving.';
					if (typeof Swal !== 'undefined') {
						Swal.fire({icon:'error', title:'Error', text: msg});
					} else {
						alert(msg);
					}
				}
			},
			error: function(){
				if (typeof Swal !== 'undefined') {
					Swal.fire({icon:'error', title:'Error', text: 'An error occurred while processing data.'});
				} else {
					alert("An error occurred while processing data.");
				}
			}
		});
	});

	// Optional Edit button inside modal (toggles editable state)
	$('#btn_edit_modal').on('click', function(){
		// toggle disabled state of inputs
		var inputs = $('#frm_add_billing').find('input, select');
		var disabled = inputs.prop('disabled');
		inputs.prop('disabled', !disabled);
		$(this).text(disabled ? 'Edit' : 'Lock');
	});
});
</script>