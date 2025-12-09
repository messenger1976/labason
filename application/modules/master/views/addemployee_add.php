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
					<li><a href="<?php echo ADMIN_URL;?>addemployee/">employee</a></li>
					<li><a href="<?php echo ADMIN_URL?>addemployee/search/">Search employee</a></li>
					<li>add</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa fa-pencil-square-o fa-fw"></i>add <span>>  employee </span></h1>
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
														<legend>Employee-Add </legend>
                                                        <!--<div class="form-group">
															<label class="col-md-4 control-label">Account Subgroup Type : </label>
															<div class="col-md-4">
																<select name="acount_group" id="acount_group" class="form-control" required>
																	 <option value="">--Select--</option>
                                                                     <?php foreach($account as $key => $value){?>
																	  <option value="<?php echo $value['id'];?>"><?php echo $value['account_name'];?></option>
                                                                      <?php } ?>
																</select>
																<?php echo form_error('acount_group'); ?>
															</div>
														</div>-->
														<input type="hidden" name="account_group" id="account_group" value="5">
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>Employee-Id : </strong></span>
																	<input class="form-control" type="text" id="employee_id" name="employee_id" value="<?php echo $this->input->post('employee_id'); ?>" required/>
																	<?php echo form_error('employee_id'); ?>
																	<span id="val_roll_img"></span>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">                                
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> First Name :</strong></span>
																	<input  class="form-control"  type="text" id="first_name" name="first_name" value="<?php echo $this->input->post('first_name'); ?>" required/>
																	<?php echo form_error('first_name'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">  
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Middle Name : </strong></span>
																	<input  class="form-control"  type="text"  id="middle_name" name="middle_name" value="<?php echo $this->input->post('middle_name'); ?>" required/>
																	<?php echo form_error('middle_name'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>Last Name : </strong></span>
																	<input  class="form-control"  type="text"  id="last_name" name="last_name" value="<?php echo $this->input->post('last_name'); ?>" required/>
																	<?php echo form_error('last_name'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> DOB: </strong></span>
																	<input  class="form-control"  type="text"  name="dob" id="dob"  placeholder="DD-MM-YYYY" required/>
																	<?php echo form_error('dob'); ?>
																</div>
															</div>
														</div>
														
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Gender </strong></span>
																	<select name="gender" id="gender" class="form-control" required>
																		<option value="">--Select--</option>
																		<option value="male">male</option>
																		<option value="female">female</option>
																	</select>
																	<?php echo form_error('gender:'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Place of birth : </strong></span>
																	<input  class="form-control"  type="text"  id="place_of_birth" name="place_of_birth" value="<?php echo $this->input->post('place_of_birth'); ?>" required/>
																	<?php echo form_error('place_of_birth'); ?>
																</div>
															</div>
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> City: </strong></span>
																	<input  class="form-control"  type="text"  id="city" name="city" value="<?php echo $this->input->post('city'); ?>" required/>
																	<?php echo form_error('city'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">	
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Address :   </strong></span>
																	<textarea class="form-control" rows="5"  id="address" name="address"><?php echo $this->input->post('address'); ?></textarea>
																	<?php echo form_error('address'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> State:</strong></span>
																	<input  class="form-control"  type="text"  id="state" name="state" value="<?php echo $this->input->post('state'); ?>" required/>
																	<?php echo form_error('state'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Mobile1:</strong></span>
																	<input class="form-control"  type="text" id="mobile1" name="mobile1" value="<?php echo $this->input->post('mobile1'); ?>" required/>
																	<?php echo form_error('mobile1'); ?>
																	<span id="val_mobile1_img"></span>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Mobile2: </strong></span>
																	<input class="form-control" type="text" id="mobile2" name="mobile2" value="<?php echo $this->input->post('mobile2'); ?>" required/>
																	<?php echo form_error('mobile2'); ?>
																	<span id="val_mobile2_img"></span>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Line Number: </strong></span>
																	<input  class="form-control" type="text" id="line_number" name="line_number" value="<?php echo $this->input->post('line_number'); ?>" required/>
																	<?php echo form_error('line_number'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Base-Salary: </strong></span>
																	<input  class="form-control" type="text" id="base_salary" name="base_salary" value="<?php echo $this->input->post('base_salary'); ?>" required/>
																	<?php echo form_error('base_salary'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Email-Id: </strong></span>
																	<input class="form-control" type="text" id="email_id" name="email_id" value="<?php echo $this->input->post('email_id'); ?>" required/>
																	<?php echo form_error('email_id'); ?>
																	<span id="var_email_img"></span>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="form-group" id="showcustomers" <?php if($record['customer_type']=='metercustomer'){ ?> style="display:none;" <?php } ?>></span>
																	<span class="input-group-addon"><i class="icon-user"></i><strong>Tax: </strong></span>
																	 <input class="form-control" type="text" id="tax" name="tax"  value="<?php echo $this->input->post('tax'); ?>" required/>
																	 <?php echo form_error('tax'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong> Job-Title: </strong></span>
																	<select name="job_title" id="job_title"  class="form-control" required>
																	<option value="">--Select--</option>
																	<?php foreach($job_title as $key => $value){?>
																	<option value="<?php echo $value['id']; ?>"><?php echo $value['job_title']; ?></option>
																	<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														
														
							
													</fieldset>

													<div class="form-actions">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>addemployee" class="btn btn-default">Cancel</a>
																<input type="submit" class="btn btn-primary" name="add" id="add" value="Add">
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
		<script type="text/javascript">
var curDate = '<?php echo date('d-m-Y') ?>';	
function fun_calendor(field){
	$("#"+field).focus();
} 
$(document).ready(function(){
	$("#dob").datepicker({
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
	
});

</script>	
<script>
$("#employee_id").on('change',function(){
	var employee = $(this).val();
	if(employee!=''){
		$('#val_roll_img').removeAttr("class").text('').append('<img title="loading" src="<?php echo base_url();?>images/favicon/loading.gif">');
		
		$.ajax({
				type: 'POST',
				url: '<?php echo ADMIN_URL; ?>addemployee/check_employee_id/',
				data: { employee_id:employee},
				success: function(data) {
								//alert(data);
								if(data=='true'){
									$("#val_roll_img").text('').attr("class","label label-warning").text( "Employee Id '"+ employee +" ' already exists try another !" );
									$("#employee_id").val('').focus();
								} else {
									$("#val_roll_img").removeAttr("class").text('').attr("class","label label-success").text( "Employee Id  '"+ employee +" ' available !" );
								}
								
							}
				  });
				  
	} else {
		$('#val_roll_img').removeAttr("class").text('');
	}
	
});
$("#email_id").on('change', function(){
	var email = $(this).val();
	if(email != ''){
		$("#var_email_img").removeAttr("class").text('').append('<img title="loading" src="<?php echo base_url();?>images/favicon/loading.gif">');
	    
		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL;?>addemployee/check_customer_email/',
			data:{ emailid:email},
			success: function(data){
				if(data=='true'){
					$("#var_email_img").text('').attr("class","label label-warning").text("Email '"+ email +" ' already exists try another !");
				    $("#email_id").val('').focus();
				}else{
					$("#var_email_img").removeAttr("class").text('').attr("class","label label-success").text( "Email  '"+ email +" ' available !" );
                }
			}
			
		});
	}
	
});
$("#mobile1").on('change', function(){
	var mobile = $(this).val();
	if(mobile != ''){
		$("#val_mobile1_img").removeAttr("class").text('').append('<img title="loading" src="<?php echo base_url();?>images/favicon/loading.gif">');
	    
		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL;?>addemployee/check_customer_mobile_1/',
			data:{ mobile_1:mobile},
			success: function(data){
				if(data=='true'){
					$("#val_mobile1_img").text('').attr("class","label label-warning").text("Mobile '"+ mobile +" ' already exists try another !");
				    $("#mobile1").val('').focus();
				}else{
					$("#val_mobile1_img").removeAttr("class").text('').attr("class","label label-success").text( "Mobile  '"+ mobile +" ' available !" );
                }
			}
			
		});
	}
	
});
$("#mobile2").on('change', function(){
	var mobile = $(this).val();
	if(mobile != ''){
		$("#val_mobile2_img").removeAttr("class").text('').append('<img title="loading" src="<?php echo base_url();?>images/favicon/loading.gif">');
	    
		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL;?>addemployee/check_customer_mobile_2/',
			data:{ mobile_1:mobile},
			success: function(data){
				if(data=='true'){
					$("#val_mobile2_img").text('').attr("class","label label-warning").text("Mobile '"+ mobile +" ' already exists try another !");
				    $("#mobile2").val('').focus();
				}else{
					$("#val_mobile2_img").removeAttr("class").text('').attr("class","label label-success").text( "Mobile  '"+ mobile +" ' available !" );
                }
			}
			
		});
	}
	
});
</script>
