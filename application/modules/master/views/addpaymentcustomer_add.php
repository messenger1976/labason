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
					<li><a href="<?php echo ADMIN_URL;?>addpaymentcustomer/">Meter Customer Bills</a></li>
					<li>Add</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa fa-pencil-square-o fa-fw"></i>add <span>>  Meter Customer Bills </span></h1>
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
														<legend>Meter Customer Bills -Add </legend>
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
															<!--<input type="submit" class="form-control"  id="btn_search_box" name="btn_search_box" id="btn_search_box" value="search" style=" width: auto;">-->
															<input type="submit" class="form-control"  id="btn_search_box" name="btn_search_box" value = "Search" style="width: auto; float: left;  background: #3276b1; color:#fff;" /> 
															<a href="<?php echo ADMIN_URL;?>addpaymentcustomer" id="btn_search_cancel" class="btn btn-default" name="btn_search_cancel" style="width: auto; float: left;margin-left: 10px;">Back</a> 
													</div>



													<form class="form-horizontal" role="form" name="myform" id="myform" method="POST" action="" enctype="multipart/form-data">

														<div id="names">
														</div>	
														<div class="col-xs-12" id="meterincomeDiv" style="margin-top: 13px; margin-bottom:20px;"></div> 

														<input type="hidden" name="customer_id" id="customer_id" value="">
														<input type="hidden" name="fullname" id="fullname" value="">
														<input type="hidden" name="status_id" id="status_id" value="<?php echo $this->input->post('status_id'); ?>">
														<input type="hidden" name="refno" id="refno" value="">
														<input type="hidden" name="leaking_id" id="leaking_id" value="">
														<input type="hidden" name="due_date" id="due_date" value="">
														<input type="hidden" name="special_priviledge" id="special_priviledge" value="">
														<input type="hidden" name="base_amount" id="base_amount" value="">

														<div style="clear:both"></div>
														
										<div class="modal" id="myModalPay" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														&times;
													</button>
													<h4 class="modal-title" id="myModalLabel">Cash Payment Module</h4>
												</div>
													<div class="modal-body">

														<div id="hideclass" style="display:none; padding:20px; box-shadow: 0px 0px 3px 1px rgba(0,0,0,0.75); margin: 0px;" >
															<div class="row">
																<div class="col-md-12 controls">
																	<div class="form-group" style="width: 100%;">
																		<label class="col-md-5 control-label" for="or_num" style="text-align:right;"> OR/SI # : (<span style="color:red;font-style:italic;">*</span>)</label>
																		<div class="col-md-7">
																			<input type="text" class="form-control text-input" id="or_num" name="or_num" value="<?php echo $this->input->post('or_num'); ?>" required autocomplete="off" title="Editable. Must be unique for your teller; validated when you change the value and again on save."/>
																			<small class="help-block">Suggested next number loads automatically; you may change it. Duplicates for your login are not allowed.</small>
																			<small id="or_num_feedback" class="help-block" style="color:#a94442;"></small>
																			<?php echo form_error('or_num'); ?>
																		</div>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-12 controls">
																	<div class="form-group" style=" width: 100%; ">
																		<label class="col-md-5 control-label" for="transdate" style="text-align:right;">Transaction Date : (<span style="color:red;font-style:italic;">*</span>)</label>
																		<div class="col-md-7">
																			<input  type="text"  class="form-control"  id="transdate" name="transdate"  value="<?php echo $this->input->post('transdate')!=''?$this->input->post('transdate'):Date('d-m-Y'); ?>" required/>
																			<?php echo form_error('transdate'); ?>
																		</div>
																	</div>
																</div>
															</div>

														  <div id="total_setting_1">	
															<div class="row">
																<div class="col-md-12 controls">
																	<div class="form-group" style="width: 100%;">
																		<label class="col-md-5 control-label" style="text-align:right;">Current Bill Amount : </label>
																		<div class="col-md-7">
																			<div class="test_deep"><h1>&#8369;&nbsp;&nbsp;<span id="deepmala">0</span></h1></div>
																			<input type="hidden"  class="form-control"  id="paid_total_amount" name="paid_total_amount"  value="<?php echo $this->input->post('paid_total_amount'); ?>" readonly ="readonly"/>
																			<input type="hidden"  class="form-control"  id="time_format" name="time_format"  value="WTME-<?php echo strtotime("now"); ?>" readonly ="readonly"/>
																		</div>
																	</div>
																</div>
															</div>
															<!--<div class="form-group">
																<label class="col-md-4 control-label">  Old Balance : </label>
																<div class="col-md-4">
																	<input  type="text" class="form-control"  id="balence" name="balence" value="<?php echo $this->input->post('balence'); ?>" readonly ="readonly"/>
																	<?php echo form_error('balence'); ?>
																</div>
															</div>
															<div class="form-group">
																<label class="col-md-4 control-label">Total Amount : </label>
																<div class="col-md-4">
																	<div class="test_deep"><h1>&#8377;&nbsp;&nbsp;<span id="deeksha">0</span>/-</h1></div>
																	<input type="hidden"  class="form-control"  id="final_total_amount" name="final_total_amount"  value="<?php echo $this->input->post('final_total_amount'); ?>" readonly ="readonly"/>
																</div>
															</div>-->
															<div class="row">
																<div class="col-md-12 controls">
																	<div class="form-group" style="display:none;">
																		<label class="col-md-4 control-label" style="text-align:right;"> Current Reading : </label>
																		<div class="col-md-4">
																			<input type="text"  class="form-control"  id="current_reading" name="current_reading"  value="<?php echo $this->input->post('current_reading'); ?>" readonly ="readonly"/>
																			<?php echo form_error('current_reading'); ?>
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group" style="width : 60% ;display:none;">
																<label class="col-md-4 control-label"> Old Reading : </label>
																<div class="col-md-4">
																	<input type="text"  class="form-control"  id="oldmeter" name="oldmeter"  value="<?php echo $this->input->post('oldmeter'); ?>" readonly ="readonly"/>
																	<?php echo form_error('current_reading'); ?>
																</div>
															</div>
															<div class="form-group" style="width : 60% ;display:none;">
																<label class="col-md-4 control-label"> Month : </label>
																<div class="col-md-4">
																	<input  type="text" class="form-control" id="month_name" name="month_name" value="" readonly ="readonly"/>
																	<input  type="hidden" class="form-control" id="month" name="month" value="<?php echo $this->input->post('month'); ?>" readonly ="readonly"/>
																	
																	<?php echo form_error('month'); ?>
																</div>
															</div>
															
															<div class="form-group" style="width : 60% ;display:none;">
																<label class="col-md-4 control-label">  Year : </label>
																<div class="col-md-4">
																	<input  type="text"  class="form-control"  id="year" name="year" value="<?php echo $this->input->post('year'); ?>" readonly ="readonly"/>
																	<?php echo form_error('year'); ?>
																</div>
															</div>
															
														
															<!--<div class="form-group">
																<label class="col-md-4 control-label"> Aqrin dambe : </label>
																<div class="col-md-4">
																	<input  type="text"  class="form-control" id="aftermeter" name="aftermeter" value="<?php echo $this->input->post('aftermeter'); ?>" required/>
																	<?php echo form_error('aftermeter'); ?>
																</div>
															</div>
															
															<div class="form-group" style="width : 60% ;">
																<label class="col-md-4 control-label"> Total Amount : </label>
																<div class="col-md-4">
																	<input   type="text"  class="form-control"  id="total_amount" name="total_amount"  value="<?php echo $this->input->post('total_amount'); ?>" required readonly ="readonly"/>
																	<?php echo form_error('total_amount'); ?>
																</div>
															</div>-->
													    </div>
														
                                                        													
															<div id="total_setting_2">   	
																<div class="row">
																	<div class="col-md-12 controls">
																	<div class="form-group" style="width : 100% ;">
																			<label class="col-md-5 control-label" style="text-align:right;">Total Bill Amount : </label>
																			<div class="col-md-7">
																				<div class="test_deep"><h1>&#8369;&nbsp;&nbsp;<span id="deepmala_total">0</span></h1></div>
																				<input type="hidden"  class="form-control"  id="total_total_amount" name="total_total_amount"  value="<?php echo $this->input->post('total_total_amount'); ?>" readonly ="readonly"/>
																				</div>
																		</div>
																	</div>
																</div>	
															</div>
															
															<div class="form-group" style=" width: 60%;display:none;">
															<label class="col-md-4 control-label">Ledger : </label>
															<div class="col-md-4">
																<select name="ledger_id" id="ledger_id" class="form-control" required>
																	 
                                                                     <?php foreach($ledger as $key => $value){?>
																	  <option value="<?php echo $value['id'];?>"><?php echo $value['ledgerName'];?></option>
                                                                      <?php } ?>
																</select>
																<?php echo form_error('ledger_id'); ?>
															</div>
														</div>
															<div class="form-group"style=" width: 60%; display:none;">
																		<label class="col-md-4 control-label"> Currency :</label>
																		<div class="col-md-4">
																			<select class="form-control"  id="currency" name="currency"  value="<?php echo $this->input->post('currency'); ?>" required>                               
                                                                            
                                                                            <option value="PHP" selected>PHP</option>
                                                                            
                                                                            </select>
																			<?php echo form_error('currency'); ?>
																		</div>
																	</div>

																	<div class="row" id="leaking_balance_div" style="display: none;">
																		<div class="col-md-12 controls">
																			<div class="form-group" style=" width: 100%;">
																				<label class="col-md-5 control-label" style="text-align:right;"> Add: Balance :</label>
																				<div class="col-md-7">
																					<input  type="text"  class="form-control"  id="leaking_balance" name="leaking_balance"  value="0.00"/>
																					<input type="hidden" id="leaking_balance_total" name="leaking_balance_total" value="0"/>
																					<?php echo form_error('leaking_balance_total'); ?>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="row" id="leaking_discount_div">
																		<div class="col-md-12 controls">
																			<div class="form-group" style=" width: 100%;">
																				<label class="col-md-5 control-label" style="text-align:right;">Less: Leaking Disc :</label>
																				<div class="col-md-3">
																					<div class="input-group">
																						<input  type="text" step="1" min="0" max="100" class="form-control text-input"  id="leaking_percent" name="leaking_percent"  value="0" readonly/>
																						<span class="input-group-addon" style="min-width:5px;">%</span>
																					</div>
																					
																					
																				</div>
																				<div class="col-md-3">
																					<input  type="text" class="form-control"  id="leaking_amount" name="leaking_amount"  value="0.00" readonly/>
																					<?php echo form_error('leaking_amount'); ?>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-md-12 controls">
																			<div class="form-group" style=" width: 100%;">
																				<label class="col-md-5 control-label" style="text-align:right;">Less: VAT :</label>
																				<div class="col-md-3">
																					<div class="input-group">
																						<input  type="text" step="1" min="0" max="100" class="form-control text-input"  id="vat_percent" name="vat_percent"  value="0"/>
																						<span class="input-group-addon" style="min-width:5px;">%</span>
																					</div>
																					
																					
																				</div>
																				<div class="col-md-3">
																					<input  type="text" class="form-control"  id="vat_amount" name="vat_amount"  value="0.00" readonly/>
																					<?php echo form_error('vat_amount'); ?>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="row">
																		<div class="col-md-12 controls">
																			<div class="form-group" style=" width: 100%;">
																				<label class="col-md-5 control-label" style="text-align:right;"> Grand Total :</label>
																				<div class="col-md-7">
																					<input  type="text"  class="form-control"  id="grand_total" name="grand_total"  value="<?php echo $this->input->post('grand_total'); ?>" readonly/>
																					<?php echo form_error('grand_total'); ?>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="row">
																		<div class="col-md-12 controls">
																			<div class="form-group" style=" width: 100%;">
																				<label class="col-md-5 control-label" style="text-align:right;"> Tendered Amount : (<span style="color:red;font-style:italic;">*</span>)</label>
																				<div class="col-md-7">
																					<input  type="text" class="form-control text-input"  id="pay_amount" name="pay_amount" value="0.00" required/>
																					<?php echo form_error('pay_amount'); ?>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="row">
																		<div class="col-md-12 controls">
																			<div class="form-group" style=" width: 100%;">
																				<label class="col-md-5 control-label" style="text-align:right;"> Change Amount :</label>
																				<div class="col-md-7">
																					<input  type="text"  class="form-control"  id="change_amount" name="change_amount"  value="0.00" readonly/>
																					
																				</div>
																			</div>
																		</div>
																	</div>


															<div class="pay_setting_1">
																<div class="form-actions">
																	<div class="row">
																		<div class="col-md-12">
																			<a href="<?php echo ADMIN_URL;?>addpaymentcustomer/add" class="btn btn-default btn_cancel_pay">Cancel</a>
																			<input type="submit" class="btn btn-primary" name="add" id="add" value="Add">
																		</div>
																	</div>
																</div>
															</div>	
												
															<div id="total_settin_pay">
																<div class="form-actions">
																	<div class="row">
																		<div class="col-md-12">
																			<a href="<?php echo ADMIN_URL;?>addpaymentcustomer/add" class="btn btn-default btn_cancel_pay">Cancel</a>
																			<input type="submit" class="btn btn-primary" id="total_add" name="total_add" value="Add">
																		</div>
																	</div>
																</div>
															</div>	




														</div>
													</div>
												</div>
											</div>
										</div>	    

														
														</form>	
													</fieldset>
													    
													
														
										
										
										
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
		</script>
		
			
<script>
	var curDate = '<?php echo date('d-m-Y') ?>';

$(document).ready(function(){
	$('#search_box_id').select2();
	$(".hideclass").hide();
	$(".pay_setting_1").hide();
	$("#total_setting_1").hide();
	$("#total_setting_2").hide();
	$("#total_settin_pay").hide();

	$('.btn_cancel_pay').on('click',function(evt){
		evt.preventDefault();
		$('#myModalPay').modal('hide');
	});

	$('#myModalPay').on('hidden.bs.modal', function() {
		$(this).removeData('skipTransdatePenaltyRecalc');
	});
});

$('#btn_search_box').on('click', function(evt) {
	evt.preventDefault();
	
	let search_text = $("#search_box_id").val();
	const search_text_result = search_text.split("==>");
	var id = search_text_result[0];
	$('#customer_id').val(id);
	//showSpinner(); // Call this to show the spinner
	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addpaymentcustomer/get_custmer_name/',
		data: {id: id},
		//async: false, // Make the request synchronous
		beforeSend: function() {
			//showSpinner(); // Call this to show the spinner
  		},
		success: function(data) {
			$("#names").html(data);
			var fullname = document.querySelector('#first_name').value;
			$('#fullname').val(fullname);
			//console.log('fullname:'+$('#fullname').val());
						
		},
		complete: function() {
			//hideSpinner(); // Simulate loading for 3 seconds
		}
	});

	//console.log('customer id:'+id)
	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addpaymentcustomer/get_custmer_all_data/',
		data: {id: id},
		//async: false, // Make the request synchronous
		beforeSend: function() {
			showSpinner(); // Call this to show the spinner
  		},
		success: function(data) {
			$("#meterincomeDiv").html(data);		
		},
		complete: function() {
			hideSpinner(); // Simulate loading for 3 seconds
		}
	});
	//hideSpinner(); // Simulate loading for 3 seconds
	$('#or_num').val('');
	$('#deepmala').text('0.00');
	$('#deepmala_total').text('0.00');
	$('#hideclass').hide();
	
	
});	



$(document).on('click','.pay_button',function(e){
	// Single-row Unpaid: bill amount already includes server-side penalty; do not re-derive from transaction date
	$('#myModalPay').data('skipTransdatePenaltyRecalc', true);
	$('#myModalPay').modal('show');
	$('#hideclass').show();
	$(".pay_setting_1").show();
	$("#total_setting_1").show();
	$("#total_setting_2").hide();
	$("#total_settin_pay").hide();
	
	var buttonid = $(this).attr('id');
	var paybtnid = $(this).data('pay-val-id');
	var amount = $('#prsentamount_'+paybtnid).val();
	var reading = $('#reading_'+paybtnid).val();
	var monthid = $('#monthid_'+paybtnid).val();
	var month = $('#month_'+paybtnid).val();
	var year = $('#year_'+paybtnid).val();
	var status = $('#status_'+paybtnid).val();
	var sc_discount = $('#sc_discount_'+paybtnid).val();
	var refno = $('#refno_'+paybtnid).val();

	var balanace_name = $('#balanceid_'+paybtnid).val();
	var pay_roll_id = $('#hid_payamount_roll').val();
	var year = $('#year_'+paybtnid).val();
	var customer = $('#customer_id').val();
	var customer_id = $('#cust_id').val();
	var lastamount = $('#prsentreadingamount_'+paybtnid).val();
	var lastreading = $('#previousreading_'+paybtnid).val();
	var due_date = $('#due_date_'+paybtnid).val();
	var special_priviledge = $('#special_priviledge_'+paybtnid).val();
	var base_amount = $('#base_amount_'+paybtnid).val();
	
	var new_total = parseInt(balanace_name)+parseInt(lastamount);
	
	// Store due date and special privilege for penalty calculation
	$('#due_date').val(due_date);
	$('#special_priviledge').val(special_priviledge);
	$('#base_amount').val(base_amount);
	
	$('#deepmala').text(amount);
	$("#paid_total_amount").val(amount);
	$('#total_total_amount').val(0);
	$("#grand_total").val(amount);
	
	$("#current_reading").val(reading);
	$('#month_name').val(month);
	$('#month').val(monthid);
	$('#year').val(year);
	
	$('#customer_id').val(customer_id);
	$('#status_id').val(status);
	$('#oldmeter').val(lastreading);
	$('#balence').val(balanace_name);
	
	$('#vat_percent').val('');
	$('#vat_amount').val('0.00');
	$('#leaking_percent').val('');
	$('#leaking_amount').val('0.00');
	$('#pay_amount').val('0.00');
	$('#change_amount').val('0.00');
	$('#leaking_id').val('');
	
	// Set transaction date to current date
	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
	var yyyy = today.getFullYear();
	var currentDate = dd + '-' + mm + '-' + yyyy;
	$('#transdate').val(currentDate);

	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addpaymentcustomer/get_or_number/',
		success: function(data) {
			$('#or_num').val($.trim(data));
			runOrNumberRemoteCheck(true);
		}
	});

	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addpaymentcustomer/get_leaking_balance/',
		data: {customer_id: customer_id},
		success: function(data) {
			const resultdata = JSON.parse(data);
			var leaking_balance = resultdata[0]['leaking_balance'];
			$('#leaking_id').val(resultdata[0]['leaking_id']);
			console.log('leaking_id:'+resultdata[0]['leaking_id']);
			if(leaking_balance>0){
				$("#leaking_balance_div").show();
				$("#leaking_discount_div").hide();
				
				$('#leaking_balance').val(leaking_balance);
				$('#leaking_balance_total').val(leaking_balance);
				amount = parseFloat(amount)+parseFloat(leaking_balance);
				$("#grand_total").val(amount.toFixed(2));
			}else{
				$("#leaking_balance_div").hide();
				$("#leaking_discount_div").show();
				
				$('#leaking_balance').val('0');
				$('#leaking_balance_total').val('0');
			}
			
		}
	});


	if(refno!=''){
		$('#refno').val(refno);
		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL;?>addpaymentcustomer/chk_leakingentry/'+refno,
			data: {id: customer_id, month: month, year: year},
			success: function(data) {
				const resultdata = JSON.parse(data);
				if(resultdata[0]['leaking_id']!==undefined){
					$('#leaking_id').val(resultdata[0]['leaking_id']);
					$('#leaking_percent').val(resultdata[0]['discount_percent']);
					$('#leaking_amount').val(resultdata[0]['discount_amount']);
					$("#grand_total").val(resultdata[0]['total_amount']);
				}
				
				//console.log(resultdata);
			}
		});
	}
	

	$('#year').val(year);
	//alert(customer);
});

/*$(#pay_amount_total).on('change', function() {
	var id = $(this).val();
	//$("#pay_amount_pay").val(id);
})	;*/


$(document).on('click','.total_pay',function(e){
	
	var total = $('#checkbox_cal').val();
	
	var customer = $('#customer_id').val();
	$('#customer_id').val(customer);
	$('#deepmala_total').text(total);
	$('#total_total_amount').val(0);
	$("#paid_total_amount").val(total);
	$("#grand_total").val(total);
	$("#total_setting_1").hide();
	$("#total_setting_2").show();
	$("#total_settin_pay").show();
	$('#myModalPay').data('skipTransdatePenaltyRecalc', false);
	$('#myModalPay').modal('show');
	$("#hideclass").show();
	$(".pay_setting_1").hide();

	$('#vat_percent').val('');
	$('#vat_amount').val('0.00');
	$('#leaking_percent').val('');
	$('#leaking_amount').val('0.00');
	$('#pay_amount').val('0.00');
	$('#change_amount').val('0.00');
	
	// Set transaction date to current date
	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
	var yyyy = today.getFullYear();
	var currentDate = dd + '-' + mm + '-' + yyyy;
	$('#transdate').val(currentDate);

	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addpaymentcustomer/get_or_number/',
		success: function(data) {
			$('#or_num').val($.trim(data));
			runOrNumberRemoteCheck(true);
		}
	});

	//$('#year').val(year);
	//checkValues();
});

$('#aftermeter').on('blur', function() {
	
	var id = $(this).val();
	var oldmeter = $("#oldmeter").val();
	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addpaymentcustomer/get_calculation/',
		data: {id: id, oldmeter: oldmeter},
		success: function(data) {
			//alert(data);
			$("#total_amount").val(data);
			
		}
	});

});	

$('#vat_percent').on('blur', function() {
	var leaking_balance = $('#leaking_balance').val()==''?0:$('#leaking_balance').val();
	var taxpercent = $('#vat_percent').val()==''?0:$('#vat_percent').val();
	var total_total_amount = $('#total_total_amount').val();
	var paid_total_amount = parseFloat($("#paid_total_amount").val() || 0);
	var leaking_amount = parseFloat($("#leaking_amount").val() || 0);
	var taxdeduct = 0;
	
	// Recalculate penalty if needed based on transaction date
	recalculatePenaltyIfNeeded();

	if(total_total_amount!=0){
		if(leaking_amount>0){
			total_total_amount-=leaking_amount;
		}
		taxdeduct = (total_total_amount * taxpercent)/100;
		$('#vat_amount').val(taxdeduct.toFixed(2));
		var grand_total =  total_total_amount - taxdeduct + parseFloat(leaking_balance);
		$("#grand_total").val(grand_total.toFixed(2));

	}else{
		// Use updated paid_total_amount (which may include penalty)
		paid_total_amount = parseFloat($("#paid_total_amount").val() || 0);
		if(leaking_amount>0){
			paid_total_amount-=leaking_amount;
		}
		taxdeduct = (paid_total_amount * taxpercent)/100;
		$('#vat_amount').val(taxdeduct.toFixed(2));
		var grand_total =  paid_total_amount - taxdeduct + parseFloat(leaking_balance);
		$("#grand_total").val(grand_total.toFixed(2));
	}
	
});	

$('#leaking_percent').on('blur', function() {
	var leakingpercent = $('#leaking_percent').val();
	var total_total_amount = $('#total_total_amount').val();
	var paid_total_amount = parseFloat($("#paid_total_amount").val() || 0);
	var vat_amount = parseFloat($('#vat_amount').val() || 0);
	
	// Recalculate penalty if needed based on transaction date
	recalculatePenaltyIfNeeded();
	paid_total_amount = parseFloat($("#paid_total_amount").val() || 0);
	
	var leakingdeduct = 0;
	if(total_total_amount!=0){
		leakingdeduct = (total_total_amount * leakingpercent)/100;
		$('#leaking_amount').val(leakingdeduct.toFixed(2));
		var grand_total =  total_total_amount - leakingdeduct;
		$("#grand_total").val(grand_total.toFixed(2));

	}else{
		leakingdeduct = (paid_total_amount * leakingpercent)/100;
		$('#leaking_amount').val(leakingdeduct.toFixed(2));
		var grand_total =  paid_total_amount - leakingdeduct - vat_amount;
		$("#grand_total").val(grand_total.toFixed(2));
	}
	
});	

$('#leaking_balance').on('blur', function() {
	var leaking_balance = parseFloat($('#leaking_balance').val() || 0);
	var total_total_amount = parseFloat($('#total_total_amount').val() || 0);
	var paid_total_amount = parseFloat($("#paid_total_amount").val() || 0);
	var vat_amount = parseFloat($('#vat_amount').val() || 0);
	var leaking_amount = parseFloat($('#leaking_amount').val() || 0);
	
	// Recalculate penalty if needed based on transaction date
	recalculatePenaltyIfNeeded();
	paid_total_amount = parseFloat($("#paid_total_amount").val() || 0);

	if(total_total_amount!=0){
		var grand_total = total_total_amount + leaking_balance - vat_amount - leaking_amount;
		$("#grand_total").val(Number.isNaN(grand_total.toFixed(2))? 0 : grand_total.toFixed(2));
	}else{
		var grand_total = paid_total_amount + leaking_balance - vat_amount - leaking_amount;
		$("#grand_total").val(Number.isNaN(grand_total.toFixed(2))? 0 : grand_total.toFixed(2));
	}	

});



function parseOrSiDigits(val) {
	var d = String(val || '').replace(/\D/g, '');
	var n = parseInt(d, 10);
	return (isNaN(n) || n < 1) ? 0 : n;
}

function markOrNumValidity(ok, msg) {
	var $g = $('#or_num').closest('.form-group');
	var $fb = $('#or_num_feedback');
	$('#or_num').data('or-valid', ok ? '1' : '0');
	$fb.text(msg || '');
	$g.removeClass('has-error');
	if (!ok && msg) {
		$g.addClass('has-error');
	}
}

function runOrNumberRemoteCheck(asyncFlag) {
	var n = parseOrSiDigits($('#or_num').val());
	if (n <= 0) {
		markOrNumValidity(false, 'Enter a valid OR/SI number (digits only).');
		return $.Deferred().reject().promise();
	}
	return $.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL; ?>addpaymentcustomer/check_or_number/' + n,
		async: asyncFlag !== false
	}).done(function(data) {
		if (String(data) === '1') {
			markOrNumValidity(false, 'This OR/SI # is already used for your teller account.');
		} else {
			markOrNumValidity(true, '');
		}
	}).fail(function() {
		markOrNumValidity(false, 'Could not validate OR/SI. Try again.');
	});
}

$(document).on('blur', '#or_num', function() {
	runOrNumberRemoteCheck(true);
});

$('#pay_amount').on('blur', function() {
	var change_amount = $("#grand_total").val() - $(this).val();
	$('#change_amount').val(change_amount.toFixed(2));
	/*if($(this).val()>0){
		$("#add").prop("disabled", false);
		$("#total_add").prop("disabled", false);
	}else{
		$("#add").prop("disabled", true);
		$("#total_add").prop("disabled", true);
	}*/
});	


$('#add').on('click',function(evt){
	var pay_amount = parseFloat($('#pay_amount').val());
	var leaking_id = $('#leaking_id').val();

	if(leaking_id!='' && pay_amount<=0){
		evt.preventDefault();
		$.smallBox({
			title : "TENDER AMOUNT field required",
			content : "Tender amount should be greater then zero.",
			color : "#D30000",
			timeout: 8000,
			icon : "fa fa-exclamation-circle swing animated"
		});
		
	}
	if(leaking_id=='' && pay_amount<=0){
		evt.preventDefault();
		//alert('Tender amount should be greater then zero.');
		$.smallBox({
			title : "TENDER AMOUNT field required",
			content : "Tender amount should be greater then zero.",
			color : "#D30000",
			timeout: 8000,
			icon : "fa fa-exclamation-circle swing animated"
		});
		
	}

	var orN = parseOrSiDigits($('#or_num').val());
	if (orN <= 0) {
		evt.preventDefault();
		markOrNumValidity(false, 'Enter a valid OR/SI number (digits only).');
		$.smallBox({
			title : "OR/SI #",
			content : "Enter a valid OR/SI number.",
			color : "#D30000",
			timeout: 8000,
			icon : "fa fa-exclamation swing animated"
		});
		return false;
	}
	var orDup = false;
	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL; ?>addpaymentcustomer/check_or_number/' + orN,
		async: false,
		success: function(data) { orDup = (String(data) === '1'); }
	});
	if (orDup) {
		evt.preventDefault();
		markOrNumValidity(false, 'This OR/SI # is already used for your teller account.');
		$.smallBox({
			title : "OR/SI NUMBER",
			content : "This OR/SI number is already used for your teller account.",
			color : "#D30000",
			timeout: 8000,
			icon : "fa fa-exclamation swing animated"
		});
		return false;
	}
	markOrNumValidity(true, '');

	
	
	
	
	/*var name = $('#fullname').val();
	var address = $('#address').val();
	var current_reading = $('#current_reading').val();
	var oldmeter = $('#oldmeter').val();
	var customer = $('#customer_id').val();
	var month = $('#month').val();
	var year = $('#year').val();
	var unit = $('#unit_d').val();
	var pay_amount = $('#pay_amount').val();
	var invoi_id = $('#time_format').val();
	var currency = $('#currency').val(); 
	if(customer != '' && currency != '' && pay_amount != '' && month !='' && year !=''){
		var url = '<?php echo ADMIN_URL;?>addpaymentcustomer/monthlyreceipt_single/'+customer+'/'+month+'/'+year+'/'+name+'/'+current_reading+'/'+oldmeter+'/'+unit+'/'+pay_amount+'/'+invoi_id+'/'+address+'/'+currency;
				//var url = '<?php echo ADMIN_URL;?>addpaymentcustomer/monthlyreceipt/'+customer;
				//window.open( url , "popupWindow", "width=1024,height=600,scrollbars=yes");	
	}*/
	//$('#myform').submit();
});


$('#total_add').on('click',function(evt){
	//evt.preventDefault();
	
	/*var name = $('#fullname').val();
	var address = $('#address').val();
	var current_reading = $('#current_reading').val();
	var oldmeter = $('#oldmeter').val();
	var customer = $('#customer_id').val();
	var month = $('#month').val();
	var year = $('#year').val();
	var unit = $('#unit_d').val();
	var pay_amount = $('#pay_amount').val();
	var invoi_id = $('#time_format').val();
	var currency = $('#currency').val(); 

	//$('#myform').append($("#meterincomeDiv").html());
	//alert(customer); 
	//alert(currency); 
	//alert(pay_amount);
	if(customer != '' && currency != '' && pay_amount != '' ){
				var url = '<?php echo ADMIN_URL;?>addpaymentcustomer/monthlyreceipt_single/'+customer+'/'+month+'/'+year+'/'+name+'/'+current_reading+'/'+oldmeter+'/'+unit+'/'+pay_amount+'/'+invoi_id+'/'+address+'/'+currency;
				//var url = '<?php echo ADMIN_URL;?>addpaymentcustomer/monthlyreceipt/'+customer;
				//window.open( url , "popupWindow", "width=1024,height=600,scrollbars=yes");	
	}
	//$('#myform').submit();
	*/

	var pay_amount = parseFloat($('#pay_amount').val());

	if(pay_amount<=0){
		
		//alert('Tender amount should be greater then zero.');
		$.smallBox({
			title : "TENDER AMOUNT field required",
			content : "Tender amount should be greater then zero.",
			color : "#D30000",
			timeout: 8000,
			icon : "fa fa-exclamation-circle swing animated"
		});
		evt.preventDefault();
	}

	var orNb = parseOrSiDigits($('#or_num').val());
	if (orNb <= 0) {
		evt.preventDefault();
		markOrNumValidity(false, 'Enter a valid OR/SI number (digits only).');
		$.smallBox({
			title : "OR/SI #",
			content : "Enter a valid OR/SI number.",
			color : "#D30000",
			timeout: 8000,
			icon : "fa fa-exclamation swing animated"
		});
		return false;
	}
	var orDupB = false;
	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL; ?>addpaymentcustomer/check_or_number/' + orNb,
		async: false,
		success: function(data) { orDupB = (String(data) === '1'); }
	});
	if (orDupB) {
		evt.preventDefault();
		markOrNumValidity(false, 'This OR/SI # is already used for your teller account.');
		$.smallBox({
			title : "OR/SI NUMBER",
			content : "This OR/SI number is already used for your teller account.",
			color : "#D30000",
			timeout: 8000,
			icon : "fa fa-exclamation swing animated"
		});
		return false;
	}
	markOrNumValidity(true, '');

});

$("#transdate").datepicker({
	showAnim: null,
	dateFormat: 'dd-mm-yy',
	// showOn: 'both',
	buttonImage: '<?php echo site_url();?>images/calender.jpg',
	buttonImageOnly: true,
	firstDay: 1,
	nextText: '',
	prevText: '',
	numberOfMonths: [1, 1],
	//setDate: new Date(),
	//minDate: curDate,
	//maxDate: ''
});

// Function to recalculate penalty based on transaction date
function recalculatePenaltyIfNeeded() {
	if ($('#myModalPay').data('skipTransdatePenaltyRecalc')) {
		return parseFloat($("#paid_total_amount").val() || 0);
	}
	var trans_date = $('#transdate').val();
	var due_date = $('#due_date').val();
	var special_priviledge = $('#special_priviledge').val();
	var base_amount = parseFloat($('#base_amount').val()) || 0;
	
	if(trans_date && due_date && base_amount > 0) {
		// Convert dates to comparable format (YYYY-MM-DD)
		var trans_date_parts = trans_date.split('-');
		var trans_date_formatted = trans_date_parts[2] + '-' + trans_date_parts[1] + '-' + trans_date_parts[0];
		var due_date_formatted = due_date.split(' ')[0]; // Already in YYYY-MM-DD format
		
		var penalty = 0;
		var new_amount = base_amount;
		
		// Check if transaction date is greater than due date and special privilege is 0
		if(special_priviledge == 0 && trans_date_formatted > due_date_formatted) {
			// Apply 10% penalty
			penalty = (base_amount * 10) / 100;
			new_amount = base_amount + penalty;
		} else {
			// No penalty
			new_amount = base_amount;
		}
		
		// Update the amount display
		$('#deepmala').text(new_amount.toFixed(2));
		$("#paid_total_amount").val(new_amount.toFixed(2));
		
		return new_amount;
	}
	return parseFloat($("#paid_total_amount").val() || 0);
}

// Recalculate penalty when transaction date changes
$('#transdate').on('blur change', function() {
	if ($('#myModalPay').data('skipTransdatePenaltyRecalc')) {
		return;
	}
	var leaking_balance = parseFloat($('#leaking_balance').val() || 0);
	var vat_amount = parseFloat($('#vat_amount').val() || 0);
	var leaking_amount = parseFloat($('#leaking_amount').val() || 0);
	
	// Recalculate penalty
	var new_amount = recalculatePenaltyIfNeeded();
	
	// Recalculate grand total
	var grand_total = new_amount;
	
	// Apply leaking discount if applicable
	if(leaking_amount > 0) {
		grand_total = grand_total - leaking_amount;
	}
	
	// Apply VAT if applicable
	if(vat_amount > 0) {
		grand_total = grand_total - vat_amount;
	}
	
	// Add leaking balance if applicable
	if(leaking_balance > 0) {
		grand_total = grand_total + leaking_balance;
	}
	
	$("#grand_total").val(grand_total.toFixed(2));
	
	// Update change amount if pay amount is already entered
	var pay_amount = parseFloat($('#pay_amount').val() || 0);
	if(pay_amount > 0) {
		var change_amount = pay_amount - grand_total;
		$('#change_amount').val(change_amount.toFixed(2));
	}
});

	$('.text-input').on('focus', function() {
  		$(this).select();
	});

	function checkValues() {
		// Get all inputs with the name 'myArray[]'
		const inputs = document.querySelectorAll('input[name="checkbox[]"]');
		
		// Collect their values into an array
		const values = Array.from(inputs).map(input => input.value);
		
		// Log the values
		console.log(values);
		
		// Display values on the page
		alert("Array values: " + values.join(", "));
	}

	function check_or_number(ornumber){
		var returnorval=0;
		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL;?>addpaymentcustomer/check_or_number/'+ornumber,
			success: function(data) {
				returnorval=data;
				console.log('return val:'+returnorval);
				return returnorval;
			}
		});
		
		return returnorval;
	}

	function compute_all(){

	}
</script>

<style>
#names .form-group{ width: 70%;}
</style>