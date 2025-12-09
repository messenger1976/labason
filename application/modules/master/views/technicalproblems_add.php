<style>
	.select2-container{
		width: 100% !important;
		
	}
	.setStatus{
		cursor: pointer;
	}
	
	

</style>
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
					<li><a href="<?php echo ADMIN_URL?>technicalproblems/">Technical Problems </a></li>
					<li>Add</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa fa-pencil-square-o fa-fw"></i>add <span>>  Technical Problems </span></h1>
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
							     $expense1 = $this->comm_model->get_outcome_expenses();
							     extract($expense1);
								 $expense2 = $this->comm_model->get_outcome_payroll();
								 extract($expense2);
								 $extotal = $extotal1 + $extotal2;
							?>
							<li class="sparks-info">
								<h5> Expense <span class="txt-color-purple">PHP <?php print_r(number_format($extotal,2));?></span></h5>
								<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>
							<?php 
							     $total_customer = $this->comm_model->total_customer();
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
												<legend>Technical Problems-Add </legend>
													<div class="col-lg-6">	
														<div class="col-lg-12 controls">
															<div class="form-group"> 
																<span class="input-group-addon"><strong>Customer : </strong></span>
																<input type="hidden" name="cust_id" id="cust_id" value="<?php echo $this->input->post('cust_id'); ?>"/>
																<input type="hidden" name="lastname" id="lastname" value="<?php echo $this->input->post('lastname'); ?>"/>
																<input type="hidden" name="firstname" id="firstname" value="<?php echo $this->input->post('firstname'); ?>"/>
																<input type="hidden" name="middlename" id="middlename" value="<?php echo $this->input->post('middlename'); ?>"/>
																<select name="customer_id" id="customer_id" placeholder="Type text to search..." required>
																	<option value="">--Select--</option>	
																	<?php
																	
																	foreach ($customer_listing as $key => $value) {
																		?>
																		<option value="<?php echo $value['customer_id'].'==>'.$value['special_priviledge']; ?>"> <?php echo $value['customer_id'] . ' ==> ' . $value['last_name'] . ', ' . $value['first_name'] . ' ' . $value['middle_name']; ?></option>
																	<?php }
																	?>
																</select>
																
																<?php echo form_error('customer_id'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Meter Number : </strong></span>
																<input class="form-control"  type="text" id="meter_number" name="meter_number" value="<?php echo $this->input->post('meter_number'); ?>" required/>
                                                                <?php echo form_error('meter_number'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-12">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Address : </strong></span>
																<input class="form-control"  type="text" id="address" name="address" value="<?php echo $this->input->post('address'); ?>" required/>
                                                                <?php echo form_error('address'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Problem Summary : </strong></span>
																<input class="form-control"  type="text" id="problem_summary" name="problem_summary" value="<?php echo $this->input->post('problem_summary'); ?>" required/>
                                                                <?php echo form_error('problem_summary'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Status : </strong></span>
																<select class="form-control" name="status" id="status" placeholder="Type text to search..." required>
																	<option value="0" id="pending">Pending</option>	
																	<option value="1" id="assigned">Assigned</option>	
																	<option value="2" id="ongoing">On Going</option>	
																	<option value="3" id="resolved">Resolved</option>
																	<option value="4" id="unresolved">UnResolved</option>
																	<option value="5" id="unresolved">Resolved - Closed</option>
																	<option value="6" id="unresolved">UnResolved - Closed</option>
																</select>
                                                                <?php echo form_error('meter_number'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Reported By : </strong></span>
																<select class="form-control" name="reportedby" id="reportedby" required>
																		
																		<?php foreach($employee as $key => $emp){ ?>
																		<option value="<?php echo $emp['id'];?>"><?php echo strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle'];?></option>
																		<?php } ?>
																	</select>
                                                                <?php echo form_error('reportedby'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Reported Date:</strong></span>
																<input class="form-control"  type="text" id="reported_date" name="reported_date"  placeholder="DD-MM-YYYY" value="<?php echo $this->input->post('reported_date')!=''?$this->input->post('reported_date'):Date('d-m-Y'); ?>" required>
															</div>
														</div>
													</div>
													<div class="col-lg-12">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Problem Details :</strong></span>
																<textarea class="form-control" rows="5" cols="25" id="problem_details" name="problem_details"><?php echo $this->input->post('problem_details'); ?></textarea>
                                                                <?php echo form_error('problem_details'); ?>
															</div>
														</div>
													</div>
														
													
											</fieldset>
											<div class="form-actions">
												<div class="row">
													<div class="col-md-12">
														
															<a href="<?php echo ADMIN_URL;?>technicalproblems" class="btn btn-default">Cancel</a>
														<input class="btn btn-primary" name="add" id="btn_add" value="add"/>
													</div>
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
		<script src="<?php echo base_url();?>js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script type="text/javascript">
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		var curDate = '<?php echo date('d-m-Y') ?>';
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



			$('#customer_id').select2();
			$('#customer_id').on('change', function(evt){
				evt.preventDefault();
				
				
				var customer_id = $(this).val().split('==>')[0];

				//var $special_priviledge = $(this).val().split('==>')[1];
				//$('#special_priviledge').val($special_priviledge);

				var cust_id = customer_id;
				$('#cust_id').val(cust_id);
				if(cust_id){
					// Send an AJAX request to the backend
					$.ajax({
						url: '<?php echo ADMIN_URL?>technicalproblems/get_customer_info', // Backend PHP script
						type: 'POST',
						data: { customer_id: cust_id },
						dataType: 'json',
						success: function(response) {
							console.log(response.address);
							$('#address').val(response.address);
							$('#meter_number').val(response.meter_number);
							$('#lastname').val(response.last_name);
							$('#firstname').val(response.first_name);
							$('#middlename').val(response.middle_name);
							
							// Clear the child dropdown
							//$('#billing_period').empty().append('<option value="">--Select--</option>');

							// Populate the child dropdown with the response data
							/*if (response.length > 0) {
								$.each(response, function(index, item) {
									if(item.status==0){
										$('#billing_period').append('<option value="' + item.id+' '+item.month+' '+item.year+ '">' + item.month_name+' '+item.year+ '</option>');
									}
									
								});
							}*/
						},
						error: function(xhr, status, error) {
							console.error('AJAX Error: ' + status + error);
						}
					});
				}else{
					// If no parent is selected, clear the child dropdown
					//$('#billing_period').empty().append('<option value="">--Select--</option>');
					//$('#leaking_option').hide();
				}
			});

			$('#btn_add').on('click', function(evt){
				evt.preventDefault();
				if($('#customer_id').val()===''){
					$.smallBox({
						title : "Select Customer",
						content : "Please select customer.",
						color : "#D30000",
						timeout: 8000,
						icon : "fa fa-exclamation-circle swing animated"
					});
				}else{
					$('#myform').submit();
				}
				
			});
		
			$("#reported_date").datepicker({
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