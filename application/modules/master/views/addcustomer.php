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
					<li><a href="<?php echo ADMIN_URL?>">Home</a></li>
					<li><a href="<?php echo ADMIN_URL?>addcustomer/search/">Search Customer</a></li>
					<li>List View</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->
           
			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> View <span>> Customer </span></h1>
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
								<h5>Expense <span class="txt-color-purple">PHP <?php print_r(number_format($extotal,2));?></span></h5>
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
								<p style="padding: 5px 0 0 45px;font-size: 16px;"><strong>Manage Customers</strong>
								<button class="btn btn-sm btn-primary" style="float:right;"><a href="<?php echo ADMIN_URL?>addcustomer/add/" style="color: #fff;"><i class="fa fa-plus"></i> Add Customer</a></button>
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
				                    <form method="post" action="<?php echo ADMIN_URL;?>addcustomer/multi_delete">
										<!-- widget content -->
										<div class="widget-body no-padding">
										   <div class="row" style="margin: 10px 10px 0 10px;">
											<div class="col-sm-3 col-md-2">
												<label for="filter_zone" style="margin-bottom: 4px;">Filter by Zone:</label>
												<select id="filter_zone" name="filter_zone" class="form-control">
													<option value="all">All</option>
													<?php if(!empty($zone)) { foreach($zone as $z) { ?>
													<option value="<?php echo $z['id']; ?>"><?php echo htmlspecialchars($z['zone']); ?></option>
													<?php } } ?>
												</select>
											</div>
										   </div>
										   <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											    <thead>			                
													<tr>
														<th><input type="checkbox" class="ace" /></th>
														<th>S No</th>
														<th>Customer id</th>
														<th>Name </th>
														<!--<th>Gender</th>-->
														<th>Address</th>
														<!--th>Mobile2</th-->
														<!--th>Address</th-->
														<th>Meter Number</th>
														<th>Zone</th>
														<th>Classification</th>
														<th>Status</th>
														<th width="100">Action</th>
													</tr>
												</thead>
												<tbody>
													<!-- Data will be loaded via AJAX -->
												</tbody>
											</table>
											

										</div>
										<!-- end widget content -->
				                    
									 <!--<div>&nbsp;</div>
									  <div class="row">
									   <div class="col-lg-12">
                                        	<input type="submit" class="btn btn-sm btn-primary" name="add" id="add" value="Delete All" onClick="return deleteAllData();" />
                                         </div>
									</div>
									 <div>&nbsp;</div>-->
									  </form> 
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
		
		<!-- Modal for Viewing Customer Details -->
		<div class="modal fade" id="viewCustomerModal" tabindex="-1" role="dialog" aria-labelledby="viewCustomerModalLabel">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="viewCustomerModalLabel">Customer Details</h4>
					</div>
					<div class="modal-body" id="viewCustomerModalBody">
						<div class="text-center">
							<i class="fa fa-spinner fa-spin fa-3x"></i>
							<p>Loading customer details...</p>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal for Editing Customer Details -->
		<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="editCustomerModalLabel">Edit Customer</h4>
					</div>
					<div class="modal-body" id="editCustomerModalBody">
						<div class="text-center">
							<i class="fa fa-spinner fa-spin fa-3x"></i>
							<p>Loading customer details...</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal for Setting Customer Password -->
		<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="passwordModalLabel">Set Customer Login Password</h4>
					</div>
					<div class="modal-body">
						<form id="passwordForm">
							<input type="hidden" id="modal_customer_id" name="customer_id" value="">
							<div class="form-group">
								<label for="modal_customer_code">Customer ID:</label>
								<input type="text" class="form-control" id="modal_customer_code" readonly>
							</div>
							<div class="form-group">
								<label for="modal_password">Password: <span class="text-danger">*</span></label>
								<input type="password" class="form-control" id="modal_password" name="password" required>
								<small class="help-block">Enter a password for customer login</small>
							</div>
							<div class="form-group">
								<label for="modal_confirm_password">Confirm Password: <span class="text-danger">*</span></label>
								<input type="password" class="form-control" id="modal_confirm_password" name="confirm_password" required>
							</div>
							<div id="password_error" class="alert alert-danger" style="display:none;"></div>
							<div id="password_success" class="alert alert-success" style="display:none;"></div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" id="savePasswordBtn">Save Password</button>
					</div>
				</div>
			</div>
		</div>

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
	
				var table = $('#dt_basic').DataTable({
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo ADMIN_URL;?>addcustomer/get_datatable_data",
						"type": "POST",
						"data": function(d) {
							d.zone = ($('#filter_zone').length ? $('#filter_zone').val() : '') || 'all';
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
						{ "data": 9, "orderable": false }
					],
					"order": [[6, 'asc'], [2, 'asc']],
					"pageLength": 100,
					"lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
					"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
					"autoWidth" : true,
			        "oLanguage": {
					    "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>',
						"sProcessing": "Loading data..."
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

				// Reload table when Zone filter changes
				$('#filter_zone').on('change', function() {
					table.ajax.reload();
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

		
<script>
$(document).ready(function() {
	// Handle View Customer Button Click
	$(document).on('click', '.view-customer-btn', function(e) {
		e.preventDefault();
		var customerId = $(this).data('customer-id');
		
		// Show loading state
		$('#viewCustomerModalBody').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i><p>Loading customer details...</p></div>');
		
		// Show modal
		$('#viewCustomerModal').modal('show');
		
		// Load customer details via AJAX
		$.ajax({
			url: '<?php echo ADMIN_URL;?>addcustomer/view_ajax/' + customerId,
			type: 'GET',
			success: function(response) {
				$('#viewCustomerModalBody').html(response);
			},
			error: function(xhr, status, error) {
				$('#viewCustomerModalBody').html('<div class="alert alert-danger">Error loading customer details. Please try again.</div>');
			}
		});
	});

	// Handle Edit Customer Button Click
	$(document).on('click', '.edit-customer-btn', function(e) {
		e.preventDefault();
		var customerId = $(this).data('customer-id');
		
		// Show loading state
		$('#editCustomerModalBody').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i><p>Loading customer details...</p></div>');
		
		// Show modal
		$('#editCustomerModal').modal('show');
		
		// Load customer edit form via AJAX
		$.ajax({
			url: '<?php echo ADMIN_URL;?>addcustomer/edit_ajax/' + customerId,
			type: 'GET',
			success: function(response) {
				$('#editCustomerModalBody').html(response);
			},
			error: function(xhr, status, error) {
				$('#editCustomerModalBody').html('<div class="alert alert-danger">Error loading customer details. Please try again.</div>');
			}
		});
	});

	// Handle Edit Customer Form Submission
	$(document).on('submit', '#editCustomerForm', function(e) {
		e.preventDefault();
		var form = $(this);
		var formData = new FormData(form[0]);
		var submitBtn = form.find('button[type="submit"]');
		var originalBtnText = submitBtn.text();
		
		// Disable submit button
		submitBtn.prop('disabled', true).text('Updating...');
		
		// Clear previous messages
		$('#edit_customer_msg').hide().html('');
		
		$.ajax({
			url: '<?php echo ADMIN_URL;?>addcustomer/update_ajax',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function(response) {
				if(response.success) {
					$('#edit_customer_msg').removeClass('alert-danger').addClass('alert alert-success').html(response.message).show();
					// Reload the DataTable after a short delay
					setTimeout(function() {
						$('#dt_basic').DataTable().ajax.reload();
						$('#editCustomerModal').modal('hide');
					}, 1500);
				} else {
					$('#edit_customer_msg').removeClass('alert-success').addClass('alert alert-danger').html(response.message).show();
					submitBtn.prop('disabled', false).text(originalBtnText);
				}
			},
			error: function(xhr, status, error) {
				var errorMsg = 'An error occurred while updating the customer.';
				if(xhr.responseJSON && xhr.responseJSON.message) {
					errorMsg = xhr.responseJSON.message;
				}
				$('#edit_customer_msg').removeClass('alert-success').addClass('alert alert-danger').html(errorMsg).show();
				submitBtn.prop('disabled', false).text(originalBtnText);
			}
		});
	});

	// Handle Set Password Button Click
	$(document).on('click', '.set-password-btn', function(e) {
		e.preventDefault();
		var customerId = $(this).data('customer-id');
		var customerCode = $(this).data('customer-code');
		
		$('#modal_customer_id').val(customerId);
		$('#modal_customer_code').val(customerCode);
		$('#modal_password').val('');
		$('#modal_confirm_password').val('');
		$('#password_error').hide().text('');
		$('#password_success').hide().text('');
		
		$('#passwordModal').modal('show');
	});

	// Handle Save Password Button
	$('#savePasswordBtn').on('click', function() {
		var customerId = $('#modal_customer_id').val();
		var password = $('#modal_password').val();
		var confirmPassword = $('#modal_confirm_password').val();
		
		// Reset messages
		$('#password_error').hide().text('');
		$('#password_success').hide().text('');
		
		// Validation
		if(!password || password.length < 3) {
			$('#password_error').text('Password must be at least 3 characters long.').show();
			return;
		}
		
		if(password !== confirmPassword) {
			$('#password_error').text('Passwords do not match.').show();
			return;
		}
		
		// Disable button during save
		$('#savePasswordBtn').prop('disabled', true).text('Saving...');
		
		// Send AJAX request
		$.ajax({
			url: '<?php echo ADMIN_URL;?>addcustomer/save_customer_password',
			type: 'POST',
			data: {
				customer_id: customerId,
				password: password
			},
			dataType: 'json',
			success: function(response) {
				if(response.success) {
					$('#password_success').text(response.message).show();
					setTimeout(function() {
						$('#passwordModal').modal('hide');
						// Optionally reload the table or show a success message
					}, 1500);
				} else {
					$('#password_error').text(response.message).show();
					$('#savePasswordBtn').prop('disabled', false).text('Save Password');
				}
			},
			error: function(xhr, status, error) {
				var errorMsg = 'An error occurred while saving the password.';
				if(xhr.responseJSON && xhr.responseJSON.message) {
					errorMsg = xhr.responseJSON.message;
				} else if(xhr.responseText) {
					try {
						var response = JSON.parse(xhr.responseText);
						if(response.message) {
							errorMsg = response.message;
						}
					} catch(e) {
						errorMsg = 'Server error: ' + xhr.status + ' ' + error;
					}
				}
				$('#password_error').text(errorMsg).show();
				$('#savePasswordBtn').prop('disabled', false).text('Save Password');
			}
		});
	});

	// Reset form when modal is closed
	$('#passwordModal').on('hidden.bs.modal', function () {
		$('#passwordForm')[0].reset();
		$('#password_error').hide().text('');
		$('#password_success').hide().text('');
		$('#savePasswordBtn').prop('disabled', false).text('Save Password');
	});
});

$(document).on('click','.print_button',function(e){
	var buttonid = $(this).attr('id');
	var paybtnid = $(this).data('print-val-id');
	var cusid = $('#id_'+paybtnid).val();
	var customer = $('#customerid_'+paybtnid).val();
	var biliingplans = $('#billingplansid_'+paybtnid).val();
	if(paybtnid != '' && customer != '' && biliingplans == ''){
				var url = '<?php echo ADMIN_URL;?>addcustomer/customer_register_form/'+cusid+'/'+customer+'/'+biliingplans;
				window.open( url , "popupWindow", "width=1024,height=600,scrollbars=yes");	
	}
	else if(paybtnid != '' && customer != '' && biliingplans != ''){
		    var url = '<?php echo ADMIN_URL;?>addcustomer/customer_register_form_monthly/'+cusid+'/'+customer+'/'+biliingplans;
			window.open( url , "popupWindow", "width=1024,height=600,scrollbars=yes");	
	}
});
</script>			