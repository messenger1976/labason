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
					<li><a href="<?php echo ADMIN_URL;?>dashboard">Home</a></li>
					<li><a href="<?php echo ADMIN_URL;?>Leakingentrycorrectioncorrection"> Leaking Ledger Listing </a></li>
					<li><a href=""> Leaking Ledger Details Listing </a></li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> View <span>> Leaking Ledger Details</span></h1>
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
				
						<!-- NEW WIDGET START -->
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
								
								<header style="height: 42px;">
									<span class="widget-icon"> <i class="fa fa-tasks"></i> </span>
									<p style="padding: 5px 0 0 45px;font-size: 16px;"><strong>Manage Leaking Ledger Details</strong>
									<?php
									if(($record_ledger['leaking_total_amount']-$total_payment)!=0){
									?>
                                    <button class="btn btn-sm btn-primary" style="float:right;" id="add_payment"  data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add Payment</button>
									<?php
									}
									?>
									<a class="btn btn-sm btn-success" style="float:right;" id="print_statement"><i class="fa fa-print"></i> Print Statement</a>
									
									<a href="<?php echo ADMIN_URL;?>Leakingentrycorrection" class="btn btn-sm btn-danger" style="float:right;"><i class="fa fa-chevron-left"></i> Back Listing</a>&nbsp;
									</p>
								</header>
				
								<!-- widget div-->
								<div class="row">
								
									<?php if($this->session->flashdata('msg_succ') != ''){?>
									
                                    <!--<div class="alert alert-block alert-success">
                                        <button type="button" class="close" data-dismiss="alert">
                                        <i class="icon-remove"></i>
                                        </button>
                                        <p>
                                            <i class="icon-ok"></i>
                                            <?php echo $this->session->flashdata('msg_succ')?$this->session->flashdata('msg_succ'):'';?>
                                        </p>
                                    </div>-->
                                    <?php } ?>
									

									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										
									</div>
									<div class="row" style="height: 100px; padding: 10px;font-size: 16px;">
										<div><b>Bill Amount:</b> <u><?php echo number_format($record_ledger['leaking_total_amount'],2);?></u></div>
										<div><b>Total Bill Payment:</b> <u><?php echo number_format($total_payment,2);?></u></div>
										<div><b>Balance:</b> <u><?php echo number_format($record_ledger['leaking_total_amount']-$total_payment,2);?></u></div>
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
				                    <form method="post" action="<?php echo ADMIN_URL;?>Leakingentrycorrection/multi_delete">
										<!-- widget content -->
										<div class="widget-body no-padding">
										   	
										   <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<!--<th data-hide="phone"><input type="checkbox"/></th>-->
														<th data-hide="phone" style="width:50px;">S No</th>
														<th data-hide="expand" style="width:100px;">Reference #</th>
														<!--<th data-hide="expand">Billing Period</th>-->
                                                        <th data-hide="expand" style="width:100px;">Payment Date</th>
                                                        <th data-hide="expand" style="width:150px;">Total Amount</th>
                                                        <th data-hide="expand">Remarks</th>
                                                        <th data-hide="expand" style="text-align:center;width: 100px;">Action</th>
													</tr>
												</thead>
												<tbody>
												  <?php
														if(count($record) > 0){
                                                        $i=1;
                                                        foreach($record as $key => $row){ 
													?>   
													<tr>
														<!--<td><input type="checkbox" class="ace" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['leakingledgerdetails_id'];?>" /></td>-->
														<td><?php echo $i; ?></td>
														<td><?php echo stripslashes($row['leakingledgerdetails_source_type'].'#'.$row['leakingledgerdetails_or_number']); ?></td>
														<!--<td><?php echo stripslashes(getMonthName($row['month'])[0]->month_name.' '.$row['year']); ?></td>-->
														<td>
															<?php
																$paydate = date('M j, Y',strtotime($row['leakingledgerdetails_transdate']));
																echo $paydate;
															?>
														</td>
														<td align="right"><?php echo stripslashes(number_format($row['leakingledgerdetails_amount'],2)); ?></td>
														<td align="left"><?php echo stripslashes($row['leakingledgerdetails_remarks']); ?></td>
														<td align="center"><a href="#" class="btn btn-outline btn-xs"><i class="fa fa-check-square-o"></i> Posted</a></td>
														
													

														
														
													</tr>
														<?php $i++;} }?>	
												</tbody>
											</table>
											

										</div>
										<!-- end widget content -->
									<!--<div>&nbsp;</div>
									  <div class="row">
									   <div class="col-lg-12">
                                        	<input type="submit" class="btn btn-sm btn-primary" name="add" id="add" value="Delete All" onClick="return deleteAllData();" />
                                         </div>
									</div>	-->
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


        


		<!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="false">
									&times;
								</button>
								<h4 class="modal-title" id="myModalLabel">Edit Customer Meter Reading</h4>
							</div>
							<div class="modal-body">
								
                                <form name="frm_update" id="frm_update" action="" method="POST">
									<div class="row" id="source_type_div">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Source Type : </strong></span>
                                                <select class="form-control" name="source_type" id="source_type" required>
													<option value="OR">OR</option>	
													<option value="SI">SI</option>	
												</select>
												
												<?php echo form_error('source_type'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Reference # : (*)</strong></span>
                                                <input class="form-control" type="text" id="refno" name="refno" style="background-color:white;" required>
                                                <?php echo form_error('refno'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Balance Amount : </strong></span>
                                                <input class="form-control text-input" type="text" id="prev_balance" name="prev_balance" value="<?php echo $record_ledger['leaking_balance'];?>" style="background-color:yellow;" readonly>
                                                <?php echo form_error('leaking_balance'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Amount Tender: (*)</strong></span>
                                                <input class="form-control text-input" type="text" id="amount_pay" name="amount_pay" value="0.00" style="background-color:white;" required>
                                                <?php echo form_error('amount_pay'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Transaction Date : (*)</strong></span>
                                                <input class="form-control text-input" type="text" id="transdate" name="transdate" value="<?php echo $this->input->post('transdate')!=''?$this->input->post('transdate'):Date('d-m-Y'); ?>" style="background-color:white;" required>
                                                <?php echo form_error('transdate'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Remarks : </strong></span>
												<textarea class="form-control text-input" type="text" id="remarks" name="remarks" rows="5"><?php echo $this->input->post('remarks')!=''?$this->input->post('remarks'):''; ?></textarea>
                                                
                                                <?php echo form_error('leakingledgerdetails_remarks'); ?>
                                            </div>
                                        </div>
                                    </div>

									

									<input type="hidden" name="leaking_id" id="leaking_id" value="<?php echo $leaking_id;?>"/>
									<input type="hidden" name="total_billing_amount" id="total_billing_amount" value="<?php echo $record_ledger['leaking_total_amount'];?>"/>
									<input type="hidden" name="balance_amount" id="balance_amount" value="<?php echo $record_ledger['leaking_total_amount']-$total_payment;?>"/>
									



								</form>
				
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
									Cancel
								</button>
								<button type="submit" class="btn btn-sm btn-primary" id="btn_save" name="btn_save" value="add">
									Add
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
					"sDom": "tip",
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


			<?php if($this->session->flashdata('msg_succ') != ''){?>
				$.smallBox({
					title : "Saving Data",
					content : "<?php echo $this->session->flashdata('msg_succ');?>",
					color : "#296191",
					timeout: 5000,
					icon : "fa fa-bell swing animated"
				});
			<?php } ?>

			
			/* END TABLETOOLS */
			var curDate = '<?php echo date('d-m-Y') ?>';	
			$("#payment_date").datepicker({
				showAnim: null,
				dateFormat: 'dd-mm-yy',
				// showOn: 'both',
				buttonImage: '<?php echo site_url();?>images/calender.jpg',
				buttonImageOnly: true,
				firstDay: 1,
				nextText: '',
				prevText: '',
				numberOfMonths: [1, 1],
				defaultDate: new Date(curDate),
				//minDate: curDate,
				//maxDate: ''
			});


			$('#customer_id').select2();
			$('#customer_id').on('change', function(evt){
				evt.preventDefault();
				var cust_id = $(this).val();
				$('#leaking_option').hide();
				if(cust_id){
					// Send an AJAX request to the backend
					$.ajax({
						url: 'Leakingentrycorrection/get_customer_meter_reading', // Backend PHP script
						type: 'POST',
						data: { customer_id: cust_id },
						dataType: 'json',
						success: function(response) {
							// Clear the child dropdown
							$('#billing_period').empty().append('<option value="">--Select--</option>');

							// Populate the child dropdown with the response data
							if (response.length > 0) {
								$.each(response, function(index, item) {
									if(item.status==0){
										$('#billing_period').append('<option value="' + item.id+' '+item.month+' '+item.year+ '">' + item.month_name+' '+item.year+ '</option>');
									}
									
								});
							}
						},
						error: function(xhr, status, error) {
							console.error('AJAX Error: ' + status + error);
						}
					});
				}else{
					// If no parent is selected, clear the child dropdown
					$('#billing_period').empty().append('<option value="">--Select--</option>');
					//$('#leaking_option').hide();
				}
			});
			
			$('#billing_period').on('change', function(evt){
				evt.preventDefault();
				var meterreading_id = $(this).val();
				if(meterreading_id){
					// Send an AJAX request to the backend
					$.ajax({
						url: 'Leakingentrycorrection/get_customer_meter_reading_detail', // Backend PHP script
						type: 'POST',
						data: { meterreading_id: meterreading_id },
						dataType: 'json',
						success: function(response) {
							
							// Populate the child dropdown with the response data
							if (response) {
								console.log(response);
								const sqlDate = response.bp_due_date;
								const parts = sqlDate.split('-');//y-m-d
								//const jsDate = new Date(parts[0], parts[1] - 1, parts[2]); // Month is 0-indexed
								const duedate = parts[2]+'-'+ parts[1]+'-'+ parts[0];
								$('#leaking_option').show();
								$('#previous_reading').val(response.previous_reading);
								$('#current_reading').val(response.reading);
								$('#consumed').val(response.consumed);
								$('#current_bill').val(response.unit_price);
								$('#sc_discount').val(response.sc_discount);
								$('#arrears').val(response.arrears);
								$('#total_amount').val(response.amount);
								$('#penalty').val(response.penalty);
								$('#reading_date').val(response.date);
								$('#due_date').val(duedate);
								$('#refno').val(response.refno);
								
							}
						},
						error: function(xhr, status, error) {
							console.error('AJAX Error: ' + status + error);
						}
					});
				}else {
					// If no parent is selected, clear the child dropdown
					//$('#billing_period').empty().append('<option value="">--Select--</option>');
					$('#leaking_option').hide();
				}
			});
			
            $('#add_payment').on('click', function(evt){
                evt.preventDefault();
				
                //$('#myModal').modal('show');
				$('#leaking_option').hide();
                $('#myModalLabel').text('Add New Payment');
				$('#remarks').val('');
                $('#refno').val('');
                $('#amount_pay').val('');
                $('#btn_save').text('Save');
				$('#btn_save').val('add');
                $('#btn_save').prop('disabled', false);

				$('#customer_id_div').show();
				$('#billing_period_div').show();

				$('#customer_id_div_text').hide();
				$('#billing_period_div_text').hide();
            });

			$(document).on('click', '.btn_edit', function(evt){
				evt.preventDefault();
				$fullname = $(this).data('fullname');
				$billing_period = $(this).data('billing_period');
				$leaking_id = $(this).data('leaking_id');
				$customer_id = $fullname.split('==>')[0];
				//$customer_name = $fullname.split('==>')[1];	
				$previous_reading = $(this).data('previous_reading');
				$current_reading = $(this).data('current_reading');
				$consumed = $(this).data('consumed');
				$current_bill = $(this).data('current_bill');
				$sc_discount = $(this).data('sc_discount');
				$arrears = $(this).data('arrears');
				$total_amount = $(this).data('total_amount');
				$penalty = $(this).data('penalty');
				$reading_date = $(this).data('reading_date');
				$bill_duedate = $(this).data('bill_duedate');
				$leaking_discount_percent = $(this).data('leaking_discount_percent');
				$leaking_discount_amount = $(this).data('leaking_discount_amount');
				$leaking_bill_amount = $(this).data('leaking_bill_amount');
				$leaking_date = $(this).data('leaking_date');

				$('#myModal').modal('show');
				$('#leaking_option').show();
				$('#myModalLabel').text('Edit Leaking Record');
				$('#btn_save').text('Update');
				$('#btn_save').val('edit');
				$('#btn_save').prop('disabled', false);

				$('#customer_id_div').hide();
				$('#billing_period_div').hide();
				$('#customer_id_div_text').show();
				$('#billing_period_div_text').show();

				$('#customer_id_text').val($fullname);
				$('#billing_period_text').val($billing_period);
				$('#leaking_id').val($leaking_id);
				$('#previous_reading').val($previous_reading);
				$('#current_reading').val($current_reading);
				$('#consumed').val($consumed);
				$('#current_bill').val($current_bill);
				$('#sc_discount').val($sc_discount);
				$('#arrears').val($arrears);
				$('#total_amount').val($total_amount);
				$('#penalty').val($penalty);
				$('#reading_date').val($reading_date);
				$('#due_date').val($bill_duedate);
				$('#leaking_percent').val($leaking_discount_percent);
				$('#leaking_amount').val($leaking_discount_amount);
				$('#bill_amount').val($leaking_bill_amount);
				$('#payment_date').val($leaking_date);

			});


			$('#payment_date').on('change', function(evt){
				evt.preventDefault();
				computeDiscount();
				
			});
			$('#leaking_percent').on('blur', function(evt){
				evt.preventDefault();
				computeDiscount();

			});

			$('#btn_save').on('click', function(evt){
				evt.preventDefault();
				var refno = $('#refno').val();
				var source_type = $('#source_type').val();
				var leaking_id = $('#leaking_id').val();
				var transdate = $('#transdate').val();
				var prev_balance = $('#prev_balance').val();
				var amount_pay = $('#amount_pay').val();
				var balance_amount = $('#balance_amount').val();
				var total_billing_amount = $('#total_billing_amount').val();
				var remarks = $('#remarks').val();
				var btn_save = $('#btn_save').val();
				

				const formData = new FormData();
				formData.append("refno",refno);
				formData.append("leaking_id", leaking_id);
				formData.append("transdate", transdate);
				formData.append("prev_balance", prev_balance);
				formData.append("amount_pay", amount_pay);
				formData.append("source_type", source_type);
				formData.append("balance_amount", balance_amount);
				formData.append("total_billing_amount", total_billing_amount);
				formData.append("remarks", remarks);
				formData.append("btn_save", btn_save);
				
				

				$.ajax({
					url: '<?php echo ADMIN_URL;?>Leakingentrycorrection/add_payment/',
					type: 'POST',
					data: formData,
					contentType: false,
					processData: false,
					beforeSend: function() {
						showSpinner(); // Call this to show the spinner
					},
					success: function (response) {
						//const result = JSON.parse(response);
						if (response=='success') {
							window.location='<?php echo ADMIN_URL;?>Leakingentrycorrection/ledger/'+leaking_id;
							/*$.smallBox({
								title : "Saving Data",
								content : "Saving Data Successfully!",
								color : "#296191",
								timeout: 5000,
								icon : "fa fa-bell swing animated"
							}, function(){
								
							});*/

						}else{
							$.smallBox({
								title : "Saving Data",
								content : "Saving Data failed!",
								color : "#296191",
								timeout: 5000,
								icon : "fa fa-bell swing animated"
							});
						}
					},
					error: function () {
						alert("An error occurred while processing data.");
						$.smallBox({
								title : "Saving Data",
								content : "An error occurred while processing data.",
								color : "#296191",
								timeout: 5000,
								icon : "fa fa-bell swing animated"
							});
					}
				});

			});

			$(".setStatus").click(function(e) {
				var getStatus = $(this).data('status');
				var id = $(this).data('id');
				if(getStatus==1){
					$.SmartMessageBox({
						title : "Approval Action",
						content : "Please select option below",
						buttons : '[Cancel][Denied][Approved]'
					}, function(ButtonPressed) {
						if (ButtonPressed === "Cancel") {
							
						}
						if (ButtonPressed === "Approved") {
			
							window.location='<?php echo ADMIN_URL;?>Leakingentrycorrection/status/'+id+'/2';
						}
						if (ButtonPressed === "Denied") {
							window.location='<?php echo ADMIN_URL;?>Leakingentrycorrection/status/'+id+'/3';
						}
						
			
					});
				}
				
				e.preventDefault();
			})
			//$('#customer_id').select2();
			
			$(document).on('click','#print_statement',function(e){
				
				
				var leaking_id = $('#leaking_id').val();
				
				var url = '<?php echo ADMIN_URL;?>Leakingentrycorrection/soa_statement/'+leaking_id;
							
				window.open( url , "popupWindowSOA", "width=1024,height=600,scrollbars=yes");	
				
				
			});

		
		})

		function parseDmyString(dateStr) {
  			const [day, month, year] = dateStr.split('-');
  			return new Date(year, month - 1, day); // month - 1 because months are 0-indexed
		}

		function computeDiscount(){
			var leakval = $('#leaking_percent').val();
			if(leakval){
				$('#btn_save').prop('disabled', false);
				var duedate = $('#due_date').val();
				var paymentdate = $('#payment_date').val();
				var date1 = parseDmyString(duedate);
				var date2 = parseDmyString(paymentdate);
				 
				if(date1 < date2){
					$('#gross_amount').val($('#penalty').val());
					var leakingdisc =($('#penalty').val() * leakval)/100;
					var billamount = $('#penalty').val() - leakingdisc;
					$('#leaking_amount').val(leakingdisc.toFixed(2));
					$('#bill_amount').val(billamount.toFixed(2));
				}else{
					$('#gross_amount').val($('#total_amount').val());
					var leakingdisc =($('#total_amount').val() * leakval)/100;
					var billamount = $('#total_amount').val() - leakingdisc;
					$('#leaking_amount').val(leakingdisc.toFixed(2));
					$('#bill_amount').val(billamount.toFixed(2));
				}
			}else{
				
				$('#btn_save').prop('disabled', true);
			}
		}

		$('.text-input').on('focus', function() {
			$(this).select();
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

		</script>



		