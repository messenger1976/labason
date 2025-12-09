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
					<li><a href="<?php echo ADMIN_URL?>addpaymentcustomer/add/">Meter Customer Bills Add</a></li>
					<li>List View</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> View <span>> Customers Payment  </span></h1>
					</div>
					<div class="col-xs-12 col-sm-10 col-md-7 col-lg-6">
											
					</div>
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
						<ul id="sparks" class="">
							<!--<li class="sparks-info">
							
								<h5> Billing Period <span class="txt-color-blue">
									
									<select>
										
										<option>January 2025</option>
										<option>December 2024</option>
									</select>
								</span></h5>
								<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>-->
							<li class="sparks-info">
								<h5> Billing Period <span class="txt-color-blue">
									
								<select  class="form-control" name="header_billingperiod" id="header_billingperiod" class="col-lg-12" required>
									<option value="">--All--</option>
									<?php
									
									foreach($billingperiod as $key =>$value){ 
										$val_val = $value['bp_period_month'].' '.$value['bp_period_year'];
										$selected_val = '';
										if($_SESSION['current_billingperiod']==$val_val){
											$selected_val = 'selected';
										}
									?>
									<option value="<?php echo $value['bp_period_month'].' '.$value['bp_period_year']; ?>" <?php echo $selected_val;?>><?php echo $value['month_name'].' '.$value['bp_period_year'];?></option>
									<?php } ?>
								</select>								
								</span></h5>
							</li>
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
				
						<!-- NEW WIDGET START -->
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
								
								<header style="height: 42px;">
									<span class="widget-icon"> <i class="fa fa-users"></i> </span>
									<p style="padding: 5px 0 0 45px;font-size: 16px;"><strong>Customer Bills Payment</strong>
									<button class="btn btn-sm btn-primary" style="float:right;"><a href="<?php echo ADMIN_URL?>addpaymentcustomer/add" style="color: #fff;"><i class="fa fa-plus"></i> Add Customer Payment</a></button>
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
				                    <form method="post" action="<?php echo ADMIN_URL;?>addpaymentcustomer/multi_delete">
										<!-- widget content -->
										<div class="widget-body no-padding">
										   <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<th data-hide="phone"><input type="checkbox" class="ace" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['id'];?>" /></th>
														<th data-hide="phone">S No</th>
														<th data-class="expand">Customer-Id</th>
														<th data-hide="expand">Name</th>
														
														<th data-hide="expand">OR #</th>
														<th data-hide="expand">Gross Amount</th>
														<th data-hide="expand">Leaking Discount</th>	
														<th data-hide="expand">VAT Discount</th>										
														<th data-hide="expand">Net Amount</th>
														
														
                                                        
														
														<th data-hide="expand">Paid Date</th>
														<th data-hide="expand">Action</th>
													</tr>
												</thead>
												<tbody>
												  <?php
                                                    if(count($record) > 0){
                                                        $i=1;
                                                        foreach($record as $key => $row){
															?>   
													<tr>
														<td><label>
																<input type="checkbox" class="ace" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['id'];?>" />
																<span class="lbl"></span>
															</label>
														</td>
														<td><?php echo $i; ?></td>
														<td><a href="<?php echo ADMIN_URL;?>addpaymentcustomer/get_monthly_customer_invoice/<?php echo $row['id']; ?>"><?php echo stripslashes($row['customer_id']); ?></a>
														</td>
													    <td><?php echo stripslashes($row['last_name'].', '.$row['first_name'].' '.$row['middle_name']); ?></td>
														<!--<td><?php echo stripslashes($row['month'].' '.$row['year']); ?></td>-->
														<td><?php echo stripslashes(sprintf('%07d',$row['or_number'])); ?></td>
                                                        <!--<td><?php echo stripslashes($row['oldmeter']); ?></td>
														<td><?php echo stripslashes($row['aftermeter']); ?></td>-->
														<!--<?php $consumed_units=$row['aftermeter']-$row['oldmeter'];?>-->
														<!--<td><?php echo stripslashes($row['consumedunits']); ?></td>-->
														<td align="right"><?php echo stripslashes(number_format($row['total'],2)); ?></td>
														<td align="right"><?php echo stripslashes(number_format($row['leaking_amount'],2)); ?></td>
														<td align="right"><?php echo stripslashes(number_format($row['vat_amount'],2)); ?></td>
														<td align="right"><?php echo stripslashes(number_format($row['grand_total'],2)); ?></td>
														
														<?php $total=$row['amount']+$row['balance']-$row['pay_amount'];?>
														
                                                        
														<!--<td><span <?php if($row['status']== 1){ echo " class='label label-success arrowed-in arrowed-in-right'"; } elseif($row['status']== 0){ echo "class='label label-danger arrowed'"; } ?>><a href="JavaScript:if(confirm('Are you sure want to Chanage the Status?')==true){window.location='<?php echo ADMIN_URL;?>addpaymentcustomer/status/<?php echo $row['id']?>/<?php echo $row['status'];?>/<?php echo $row['customer_id'];?>';}" style="color:#FFF; text-decoration:none;"><?php if($row['status']== 1){ echo "Paid"; } elseif($row['status']== 0){ echo "Partial-Paid"; } ?></a></span></td>
                                                        -->
														<!--<td><span 
														       <?php if($row['balance']== 0){ 
														                 echo " class='label label-success arrowed-in arrowed-in-right'"; 
																	} elseif($row['balance']!= 0){ 
																	     echo "class='label label-danger arrowed'"; 
																   } ?>>
																   <?php if($row['balance']== 0){?>
																	
																	   <?php if($row['balance']== 0){ 
																	           echo "Paid"; 
																	   } ?>
																  
																   <?php }else {?>
																   <a href="JavaScript:if(confirm('Are you sure want to Chanage the Status?')==true){
																	     window.location='<?php echo ADMIN_URL;?>addpaymentcustomer/status/<?php echo $row['id']?>/<?php echo $row['balance'];?>/<?php echo $row['customer_id'];?>';
																	   }" style="color:#FFF; text-decoration:none;">
																	   <?php if($row['balance']== 0){ 
																	           echo "Paid"; 
																			 }elseif($row['status']!= 0){ 
																			   echo "Partial-Paid"; 
																	    } ?>
																   </a>
														           <?php }?>
															</span>
														</td>-->
                                                        <td><?php echo date('d-m-Y',strtotime($row['date'])); ?></td>
														<td>
														    <input type="hidden" name="customerid_<?php echo $i;?>" id="customerid_<?php echo $i;?>" value = "<?php echo $row['customer_id'];?>">
															<input type="hidden" name="month_<?php echo $i;?>" id="month_<?php echo $i;?>" value = "<?php echo $row['month'];?>">
															<input type="hidden" name="year_<?php echo $i;?>" id="year_<?php echo $i;?>" value = "<?php echo $row['year'];?>">
															<input type="hidden" name="invoiceid_<?php echo $i;?>" id="invoiceid_<?php echo $i;?>" value = "<?php echo $row['invoice_id'];?>">
														    <!--<input class="print_button" id="print_button<?php echo $i;?>" data-print-val-id="<?php echo $i; ?>" type="button" name="print" value="Print">-->
															<!--<a href="#" title="Print">
																 <i class="print_button fa fa-print" id="print_button<?php echo $i;?>" data-print-val-id="<?php echo $i ?>"></i>
																 </a>&nbsp;&nbsp;&nbsp;-->
<a href="#" title="Print">
																 <i class="print_button_new1 fa fa-print" id="print_button_new1<?php echo $i;?>" data-print-val-id="<?php echo $i ?>"></i>
																 </a>&nbsp;&nbsp;&nbsp;
															<!--<a href="#" title="Print New">
																<i class="print_button_new fa fa-table" id="print_button_new<?php echo $i;?>" data-print-val-id="<?php echo $i ?>"></i>
																 </a>
																 <a href="#" title="Print New">
																<i class="print_button_new1 fa fa-table" id="print_button_new1<?php echo $i;?>" data-print-val-id="<?php echo $i ?>"></i>
																 </a>-->
																<!--<a class="red" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>addpaymentcustomer/delete/<?php echo $row['id'];?>';}" title="Delete">
																			<i class="fa fa-remove"></i>
																</a>-->
																<div class="visible-xs visible-sm hidden-md hidden-lg">
																	<div class="inline position-relative">
																		<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
																			<i class="icon-caret-down icon-only bigger-120"></i>
																		</button>
																		
																		<ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
																			<li>
																				<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>addpaymentcustomer/delete/<?php echo $row['id'];?>';}" class="tooltip-error" data-rel="tooltip" title="Delete">
																					<span class="red">
																						<img src="<?php echo base_url();?>images/favicon/delete.png">
																					</span>
																				</a>
																			</li>
																		</ul>
																	</div>
																</div></td>
													</tr>
														<?php $i++;} }?>	
												</tbody>
											</table>
											

										</div>
										<!-- end widget content -->
										<div>&nbsp;</div>
									  <!--<div class="row">
									   <div class="col-lg-12">
                                        	<input type="submit" class="btn btn-sm btn-primary" name="add" id="add" value="Delete All" onClick="return deleteAllData();" />
                                         </div>
									</div>-->
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

			$('#header_billingperiod').on('change', function(evt){
				evt.preventDefault();
				var header_billing_period = $(this).val();
				//showSpinner();
				$.ajax({
            		type : "POST",
					url	: '<?php echo ADMIN_URL;?>addbillingperiod/updated_headerbillingperiod',
					data	: "billing_period="+header_billing_period,
					complete: function(data){
						console.log(data);
						//if(data=='success'){
							location.reload();
							//window.location.replace(window.location.href);
							//window.location.href = '<?php echo ADMIN_URL;?>addbillingperiod';
						//}
					}
				});

				//alert($(this).val());
			});
		
		})

		</script>

		
<script>
$(document).on('click','.print_button',function(e){
	var buttonid = $(this).attr('id');
	var paybtnid = $(this).data('print-val-id');
	
    var customer = $('#customerid_'+paybtnid).val();
	var month = $('#month_'+paybtnid).val();
	var year = $('#year_'+paybtnid).val();
	if(customer != '' && month != '' && year != ''){
				var url = '<?php echo ADMIN_URL;?>addpaymentcustomer/monthlyreceipt/'+customer+'/'+month+'/'+year;
				//var url = '<?php echo ADMIN_URL;?>addpaymentcustomer/monthlyreceipt/'+customer;
				window.open( url , "popupWindow", "width=1024,height=600,scrollbars=yes");	
	}
	
});
$(document).on('click','.print_button_new',function(e){
	var buttonid = $(this).attr('id');
	var paybtnid = $(this).data('print-val-id');
	
    var customer = $('#customerid_'+paybtnid).val();
	var month = $('#month_'+paybtnid).val();
	var year = $('#year_'+paybtnid).val();
	var invoice_id = $('#invoiceid_'+paybtnid).val();
	if(customer != '' && month != '' && year != ''){
				var url = '<?php echo ADMIN_URL;?>addpaymentcustomer/monthly_receipt/'+customer+'/'+month+'/'+year+'/'+invoice_id;
				//var url = '<?php echo ADMIN_URL;?>addpaymentcustomer/monthlyreceipt/'+customer;
				window.open( url , "popupWindow", "width=1024,height=600,scrollbars=yes");	
	}
	
});

$(document).on('click','.print_button_new1',function(e){
	var buttonid = $(this).attr('id');
	var paybtnid = $(this).data('print-val-id');
	
    var customer = $('#customerid_'+paybtnid).val();
	var month = $('#month_'+paybtnid).val();
	var year = $('#year_'+paybtnid).val();
	var invoice_id = $('#invoiceid_'+paybtnid).val();
	if(customer != '' && month != '' && year != ''){
				var url = '<?php echo ADMIN_URL;?>addpaymentcustomer/monthly_receipt_ver1/'+customer+'/'+month+'/'+year+'/'+invoice_id;
				//var url = '<?php echo ADMIN_URL;?>addpaymentcustomer/monthlyreceipt/'+customer;
				window.open( url , "popupWindow", "width=1024,height=600,scrollbars=yes");	
	}
	
});
</script>		