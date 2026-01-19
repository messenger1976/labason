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
                                                <input class="form-control" type="text" id="franchise_fee_percent" name="franchise_fee_percent" style="background-color:white;" readonly>
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
				//alert(op);
				$("#customerDiv").html(op);
				hideSpinner();
			}
		});
	});
	
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
			alert("An error occurred while processing data.");
		}
	});
});

$('#sc_discount').on('blur', function(evt){
	evt.preventDefault();
	var unit_price = parseFloat($('#current_bill').val().replace(/,/g, ''));
	var maintenance_fee = parseFloat($('#maintenance_fee').val().replace(/,/g, '') || 0);
	//var multiprice = parseInt(difer) * parseInt(unit_price);
	var multiprice = unit_price;
	var discount = parseFloat($(this).val().replace(/,/g, '') || 0);
	
	total_amount = multiprice - discount;
	total_amount += parseFloat(maintenance_fee);
	
	// Calculate franchise fee: 2% of bill amount, after SC deduction if SC
	var franchise_fee_percentage = 2.00; // Get from global settings or default
	var bill_amount_for_franchise;
	if($('#cust_type_id').val()==3){
		// SC: compute after SC deduction
		bill_amount_for_franchise = multiprice - discount;
	} else {
		// Non-SC: compute on unit price
		bill_amount_for_franchise = multiprice;
	}
	var franchise_fee_amount = (bill_amount_for_franchise * franchise_fee_percentage) / 100;
	total_amount += parseFloat(franchise_fee_amount);
	
	if($('#franchise_fee_percent').length) {
		$('#franchise_fee_percent').val(franchise_fee_percentage);
	}
	if($('#franchise_fee_amount').length) {
		$('#franchise_fee_amount').val(amount_formatted(franchise_fee_amount));
	}
	
	amount_total_penalty = 0;
	if($('#special_priviledge').val()==='0'){
		amount_total_penalty = (total_amount * 10)/100;
		amount_total_penalty = amount_total_penalty + total_amount;
	}else{
		amount_total_penalty = total_amount;
	}
	//$('#discount').val(amount_formatted(discount));
	$("#amount_pay").val(amount_formatted(multiprice));
	$("#total_amount").val(amount_formatted(total_amount));
	$("#penalty").val(amount_formatted(amount_total_penalty));	
});

$('#maintenance_fee').on('blur', function(evt){
	evt.preventDefault();
	var unit_price = parseFloat($('#current_bill').val().replace(/,/g, ''));
	var maintenance_fee = parseFloat($(this).val().replace(/,/g, '') || 0);
	//var multiprice = parseInt(difer) * parseInt(unit_price);
	var multiprice = unit_price;
	var discount = parseFloat($('#sc_discount').val().replace(/,/g, '') || 0);
	
	total_amount = multiprice - discount;
	total_amount += parseFloat(maintenance_fee);
	
	// Calculate franchise fee: 2% of bill amount, after SC deduction if SC
	var franchise_fee_percentage = 2.00; // Get from global settings or default
	var bill_amount_for_franchise;
	if($('#cust_type_id').val()==3){
		// SC: compute after SC deduction
		bill_amount_for_franchise = multiprice - discount;
	} else {
		// Non-SC: compute on unit price
		bill_amount_for_franchise = multiprice;
	}
	var franchise_fee_amount = (bill_amount_for_franchise * franchise_fee_percentage) / 100;
	total_amount += parseFloat(franchise_fee_amount);
	
	if($('#franchise_fee_percent').length) {
		$('#franchise_fee_percent').val(franchise_fee_percentage);
	}
	if($('#franchise_fee_amount').length) {
		$('#franchise_fee_amount').val(amount_formatted(franchise_fee_amount));
	}
	
	amount_total_penalty = 0;
	if($('#special_priviledge').val()==='0'){
		amount_total_penalty = (total_amount * 10)/100;
		amount_total_penalty = amount_total_penalty + total_amount;
	}else{
		amount_total_penalty = total_amount;
	}
	//$('#discount').val(amount_formatted(discount));
	$("#amount_pay").val(amount_formatted(multiprice));
	$("#total_amount").val(amount_formatted(total_amount));
	$("#penalty").val(amount_formatted(amount_total_penalty));	
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
				var unit_price = parseFloat($('#current_bill').val().replace(/,/g, ''));
				var maintenance_fee = parseFloat($('#maintenance_fee').val().replace(/,/g, '') || 0);
				//var multiprice = parseInt(difer) * parseInt(unit_price);
				var multiprice = unit_price;
				var discount =0;
				if($('#cust_type_id').val()==3){
					discount = (multiprice * 5)/100;
				}
				total_amount = multiprice - discount;
				total_amount += parseFloat(maintenance_fee);
				
				// Calculate franchise fee: 2% of bill amount, after SC deduction if SC
				var franchise_fee_percentage = 2.00; // Get from global settings or default
				var bill_amount_for_franchise;
				if($('#cust_type_id').val()==3){
					// SC: compute after SC deduction
					bill_amount_for_franchise = multiprice - discount;
				} else {
					// Non-SC: compute on unit price
					bill_amount_for_franchise = multiprice;
				}
				var franchise_fee_amount = (bill_amount_for_franchise * franchise_fee_percentage) / 100;
				total_amount += parseFloat(franchise_fee_amount);
				
				if($('#franchise_fee_percent').length) {
					$('#franchise_fee_percent').val(franchise_fee_percentage);
				}
				if($('#franchise_fee_amount').length) {
					$('#franchise_fee_amount').val(amount_formatted(franchise_fee_amount));
				}
				
				amount_total_penalty = 0;
				//console.log('SP:'+$('#special_priviledge').val());
				if($('#special_priviledge').val()==='0'){
					amount_total_penalty = (total_amount * 10)/100;
					amount_total_penalty = amount_total_penalty + total_amount;
				}else{
					amount_total_penalty = total_amount;
				}
				$('#sc_discount').val(amount_formatted(discount));
				$("#amount_pay").val(amount_formatted(multiprice));
				$("#total_amount").val(amount_formatted(total_amount));
				$("#penalty").val(amount_formatted(amount_total_penalty));
				

			} else {
				$('#current_bill').val(amount_formatted(0));
				var unit_price = $('#current_bill').val();
				
				$("#amount_pay").val(amount_formatted(0));
				alert("No Amount per cubic meter.");
			}
		},
		error: function () {
			alert("An error occurred while processing data.");
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