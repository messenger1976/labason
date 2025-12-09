

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
					<li><a href="<?php echo ADMIN_URL?>addmetercustomerreading_add/">Meter Customer Reading </a></li>
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
											 <?php echo $this->session->flashdata('msg'); ?>
											
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
													<input type="submit" class="form-control"  id="btn_search_box" name="btn_search_box" value = "Search" style="width: auto; float: left;  background: #3276b1; color:#fff;" />      
													<a href="<?php echo ADMIN_URL;?>addmetercustomerreading" id="btn_search_cancel" class="btn btn-default" name="btn_search_cancel" style="width: auto; float: left;margin-left: 10px;">Cancel</a>      
													                                                                                          
															
														</div>
														<div class="col-xs-12" id="meterincomeDiv" style="margin-top: 13px; margin-bottom:20px;"></div> 
														<div style="clear:both"></div>
														<div id="hideclass" style="display:none; padding:20px; box-shadow: 0px 0px 3px 1px rgba(0,0,0,0.75);    margin: 14px;" >
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">  Previous Reading : </label>
																<div class="col-md-4">
																	<input  type="text" class="form-control text-input"  id="preview" name="preview" value="<?php echo $this->input->post('preview'); ?>" readonly ="readonly"/>
																	<input  type="hidden" class="form-control"  id="customer_id" name="customer_id" value="<?php echo $this->input->post('customer_id'); ?>" readonly ="readonly"/>
																	<?php echo form_error('preview'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">Current Reading : </label>
																<div class="col-md-4">
																	<input type="text text-input" step="1" class="form-control text-input"  id="current_meter" name="current_meter"  value="<?php echo $this->input->post('current_meter'); ?>" required/>
																</div>
															</div>
															<div class="form-group"style=" width: 60%;">
																<label class="col-md-4 control-label"> Cu. M. Consumed : </label>
																<div class="col-md-4">
																	<input type="text"  class="form-control"  id="different" name="different"  value="<?php echo $this->input->post('different'); ?>" readonly ="readonly"/>
																	<?php echo form_error('different'); ?>
																</div>
															</div>
															
															<div class="form-group" style=" width: 60%; display: none;">
																<label class="col-md-4 control-label"> Unit price : </label>
																<div class="col-md-4">
																	<input type="text" style="text-align:right;" class="form-control"  id="unit_price" name="unit_price"  value="<?php echo $this->input->post('unit_price'); ?>" readonly ="readonly"/>
																	<?php echo form_error('unit_price'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">  Current Bill : </label>
																<div class="col-md-4">
																	<input  type="text"  style="text-align:right;" class="form-control"  id="amount_pay" name="amount_pay" value="<?php echo $this->input->post('amount_pay'); ?>" readonly ="readonly"/>
																	<?php echo form_error('amount_pay'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">  Discount : </label>
																<div class="col-md-4">
																	<input  type="text"  style="text-align:right;" class="form-control text-input"  id="discount" name="discount" value="<?php echo $this->input->post('discount'); ?>"/>
																	<?php echo form_error('discount'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">  Previous Balance/Arrears : </label>
																<div class="col-md-4">
																	<input  type="text" style="text-align:right;" class="form-control text-input"  id="prev_balance" name="prev_balance" value="<?php echo $this->input->post('prev_balance'); ?>"/>
																	<?php echo form_error('prev_balance'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">  Amount Due If Paid on or before due date : </label>
																<div class="col-md-4">
																	<input  type="text"  style="text-align:right;" class="form-control"  id="total_amount" name="total_amount" value="<?php echo $this->input->post('total_amount'); ?>" readonly/>
																	<?php echo form_error('total_amount'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%; display: none;">
																<label class="col-md-4 control-label">Due Date :</label>
																<div class="col-md-4">
																	<input  type="text"  class="form-control"  id="due_date" name="due_date"  value="<?php echo $this->input->post('due_date'); ?>"/>
																	<?php echo form_error('due_date'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">  Amount Due If Paid after due date : </label>
																<div class="col-md-4">
																	<input  type="text"  style="text-align:right;" class="form-control"  id="amount_total_penalty" name="amount_total_penalty" value="<?php echo $this->input->post('amount_total_penalty'); ?>" readonly/>
																	<?php echo form_error('amount_total_penalty'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%; display:none;">
																<label class="col-md-4 control-label"> Billing Period : </label>
																<div class="col-md-2">
																	 <!--<select class="form-control" name="month" id="month">
																	<option value="">--Select--</option>
																		<?php foreach($addmonth as $key => $value){ ?>
																		 <option value="<?php  echo $value['month_id']; ?>"><?php  echo $value['month_name']; ?></option>
																		<?php } ?>
																	</select>-->
																	<input type="text" class="form-control" name="month" id="month" value="<?php echo $month; ?>"/>
																	<?php echo form_error('month'); ?>
																</div>
																<div class="col-md-2">
																<input type="text" class="form-control" name="year" id="year" value="<?php echo $year; ?>"/>
																</div>
															</div>
															<div class="form-group" style=" width: 60%; ">
																<label class="col-md-4 control-label">Reading Date :</label>
																<div class="col-md-4">
																	<input  type="text"  class="form-control"  id="date" name="date"  value="<?php echo $this->input->post('date'); ?>" required/>
																	<?php echo form_error('date'); ?>
																</div>
															</div>
															<input type="hidden" name="billing_period_id" id="billing_period_id"/>
													</fieldset>
													<div id="total_setting_2">
														<div class="form-actions">
															<div class="row">
																<div class="col-md-12">
																	
																	 <a href="<?php echo ADMIN_URL;?>addmetercustomerreading" class="btn btn-default">Cancel</a>
																	<input type="submit" class="btn btn-primary" id="add_button" name="add" id="add" value="Add">
																</div>
															</div>
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
$(document).ready(function(){
	$('#search_box_id').select2();
	$('#total_setting_2').hide();
});

$('#btn_search_box').on('click', function(event) {
	event.preventDefault();
	showSpinner(); // Call this to show the spinner
	let search_text = $("#search_box_id").val();
	const search_text_result = search_text.split("==>");
	var id = search_text_result[0];
	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addmetercustomerreading/get_custmer_all_data/'+id,
		data: {id: id},
		success: function(data) {
			//console.log(data);
			$("#meterincomeDiv").html(data);
			//$('#hideclass').show();
			//$("#total_setting_2").show();
			$('#unit_price').val(amount_formatted(0));
			var unit_price = $('#unit_price').val();
				
			$("#amount_pay").val(amount_formatted(0));
			$("#different").val(0);
			$('#current_meter').val(0);
			
			
		}
	});
	setTimeout(hideSpinner, 1000); // Simulate loading for 3 seconds
	
});	
$(document).on('click','.pay_button',function(e){
	
	var cust = $('#customer').val();
	var pre = $('#preview_read').val();
	var unit = $('#unit_pr').val();
	var bp_id = $('#bp_id').val();
	var bp_period_month = $('#bp_period_month').val();
	var bp_period_year = $('#bp_period_year').val();

	$('#customer_id').val(cust);
	$('#preview').val(pre);
	$('#unit_price').val(unit);

	$('#billing_period_id').val(bp_id);
	$('#month').val(bp_period_month);
	$('#year').val(bp_period_year);
	//alert($('#billing_period_id').val());
	if(pre==0){
		$('#preview').removeAttr('readonly');
		//console.log('Wala:'+pre);
	}
	
});

$('#discount').on('blur', function(evt){
	evt.preventDefault();
	var unit_price = $('#unit_price').val();
	//var multiprice = parseInt(difer) * parseInt(unit_price);
	var multiprice = parseFloat(unit_price);
	var discount =$(this).val();
	
	total_amount = multiprice - discount;
	amount_total_penalty = 0;
	if($('#special_priviledge').val()==='0'){
		amount_total_penalty = (total_amount * 10)/100;
		amount_total_penalty = amount_total_penalty + total_amount;
	}else{
		amount_total_penalty = total_amount;
	}
	$('#discount').val(amount_formatted(discount));
	$("#amount_pay").val(amount_formatted(multiprice));
	$("#total_amount").val(amount_formatted(total_amount));
	$("#amount_total_penalty").val(amount_formatted(amount_total_penalty));	
});
$('#current_meter').on('blur', function() {
	var current_meter = $(this).val();
	var preview = $('#preview').val();
	var differances = parseFloat(current_meter) - parseFloat(preview);
	$("#different").val(differances);
	var difer = $("#different").val();
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
				
				$('#unit_price').val(amount_formatted(result.per_unit));
				var unit_price = $('#unit_price').val();
				//var multiprice = parseInt(difer) * parseInt(unit_price);
				var multiprice = parseFloat(unit_price);
				var discount =0;
				if($('#cust_type_id').val()==3){
					discount = (multiprice * 5)/100;
				}
				total_amount = multiprice - discount;
				amount_total_penalty = 0;
				//console.log('SP:'+$('#special_priviledge').val());
				if($('#special_priviledge').val()==='0'){
					amount_total_penalty = (total_amount * 10)/100;
					amount_total_penalty = amount_total_penalty + total_amount;
				}else{
					amount_total_penalty = total_amount;
				}
				$('#discount').val(amount_formatted(discount));
				$("#amount_pay").val(amount_formatted(multiprice));
				$("#total_amount").val(amount_formatted(total_amount));
				$("#amount_total_penalty").val(amount_formatted(amount_total_penalty));
				

			} else {
				$('#unit_price').val(amount_formatted(0));
				var unit_price = $('#unit_price').val();
				
				$("#amount_pay").val(amount_formatted(0));
				alert("No Amount per cubic meter.");
			}
		},
		error: function () {
			alert("An error occurred while processing data.");
		}
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
var curDate = '<?php echo date('d-m-Y') ?>';	
function fun_calendor(field){
	$("#"+field).focus();
} 
$(document).ready(function(){
	$("#date").datepicker({
		showAnim: null,
		dateFormat: 'dd-mm-yy',
		// showOn: 'both',
		buttonImage: '<?php echo site_url();?>images/calender.jpg',
		buttonImageOnly: true,
		firstDay: 1,
		nextText: '',
		prevText: '',
		numberOfMonths: [1, 1],
		//defaultDate: new Date(curDate),
		//minDate: curDate,
		//maxDate: ''
	});
	$("#due_date").datepicker({
		showAnim: null,
		dateFormat: 'dd-mm-yy',
		// showOn: 'both',
		buttonImage: '<?php echo site_url();?>images/calender.jpg',
		buttonImageOnly: true,
		firstDay: 1,
		nextText: '',
		prevText: '',
		numberOfMonths: [1, 1],
		//defaultDate: new Date(curDate),
		//minDate: curDate,
		//maxDate: ''
	});

	$('.text-input').on('focus', function() {
  		$(this).select();
	});

});
</script>	
