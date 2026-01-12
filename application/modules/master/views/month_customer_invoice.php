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
					<li><a href="<?php echo ADMIN_URL?>addcustomer/add/">Add Customer</a></li>
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
				
						<div class="col-sm-6 col-lg-12">

							<!-- your contents here -->
							<div class="panel panel-default">
								<div class="panel-body status">
									<div class="who clearfix">
										Pay Status 	: 	<b><?php
															if($record['status'] == '1'){
																echo "Paid";
															}if($record['status'] == '0'){
																echo "Un-Paid";
															}
															?></b><br/><br/>
										<span class="name">Paid Date : 	<?php echo date("M d, Y ", strtotime($record['date'])); ?></span>
										<span class="name">Cashier/Teller : 	<?php echo strtoupper($record['employee_name']); ?></span><br/>
										<span class="name">Company Address : 	<?php echo (stripslashes(str_replace('\n','',$address['content']))); ?></span>
									</div>
									
									<div class="image">
									
									        <table width="100%" border="0" cellpadding="5" cellspacing="5" bgcolor="#dcdcdc">
												  <thead>
													<tr class="table-heading">
													  <th width="6%" align="left" valign="middle" bgcolor="#ececec">OR #</th>
													  <th width="22%" align="left" valign="middle" bgcolor="#ececec">Customer Info</th>
													  <th width="16%" align="left" valign="middle" bgcolor="#ececec">Meter Details</th>
													  <th width="12%" align="left" valign="middle" class="center" bgcolor="#ececec">Current Bill</th>	
													  		  
													  <th width="8%" align="left" valign="middle" bgcolor="#ececec">Gross Amount</th>
													 
													  <th width="8%" align="left" valign="middle" class="center" bgcolor="#ececec">Leaking Disc</th>
													  <th width="8%" align="left" valign="middle" class="center" bgcolor="#ececec">VAT</th>
													  <th width="9%" align="left" valign="middle" class="center" bgcolor="#ececec">Total</th>
													  
													  <th width="9%" align="left" valign="middle" class="center" bgcolor="#ececec">Transaction Date</th>
													 </tr>
													</thead>
												  <tbody>  
													<tr class="odd gradeX">
													  <td align="left" valign="middle" bgcolor="#FFFFFF"><?php echo stripslashes(sprintf('%07d',$record['or_number'])); ?></td>
													  <td height="30" align="left" valign="middle" bgcolor="#FFFFFF" class="forgotpassword">
														<b>Customer-id: </b><?php echo stripslashes($record['customer_id']); ?><br/>
														<b>Customer Name: </b><?php echo stripslashes(strtoupper($record['name'])); ?><br/>
														<b>Zone: </b><?php echo stripslashes(strtoupper($record['zonename'])); ?><br/>
													  </td>
													  <td height="30" align="left" valign="middle" bgcolor="#FFFFFF" class="forgotpassword">
														<b>Previous Reading : </b><?php echo stripslashes($record['oldmeter']); ?><br/>
														<b>Current Reading : </b> <?php echo stripslashes($record['aftermeter']); ?><br/>
														<b>Consumed : </b> <?php echo stripslashes($record['consumedunits']); ?><br/>
													  </td>			
													  <?php
													  $penaltyamount = $record['per_unit']-$record['amount'];
													  ?>	  
													  <td align="left" valign="middle" bgcolor="#FFFFFF" >
														<b>Amount :</b> <?php echo stripslashes(number_format($record['unit_price'],2)); ?><br/>
														<b>SC Discount :</b> (<?php echo stripslashes(number_format($record['sc_discount'],2)); ?>)<br/>
														<b>Maintenance Fee :</b> <?php echo stripslashes(isset($record['maintenance_fee']) ? number_format($record['maintenance_fee'],2) : '0.00'); ?><br/>
														<b>Franchise Fee (<?php echo stripslashes(isset($record['franchise_fee_percent']) ? number_format($record['franchise_fee_percent'],2) : '0.00'); ?>%) :</b> <?php echo stripslashes(isset($record['franchise_fee_amount']) ? number_format($record['franchise_fee_amount'],2) : '0.00'); ?><br/>	
														<b>Plus Penalty :</b> <?php echo stripslashes(number_format($penaltyamount,2)); ?><br/>					
													  </td>
													  
													  <td align="left" valign="middle" bgcolor="#FFFFFF" class="right">   
														<?php echo stripslashes(number_format($record['amount']+$penaltyamount,2)); ?>
													  </td>
													  
													  <td align="left" valign="middle" bgcolor="#FFFFFF" class="center">
													    (<?php echo stripslashes(number_format($record['leaking_amount'],2)); ?>)
													   </td>
									                   <td align="left" valign="middle" bgcolor="#FFFFFF" class="center">
													    (<?php echo stripslashes(number_format($record['vat_amount'],2)); ?>)
													   </td>				  
													  <td align="left" valign="middle" bgcolor="#FFFFFF" class="right">
														<?php echo number_format($record['grand_total'],2); ?><br>			  
													  </td>
													 
													  <td align="left" valign="middle" bgcolor="#FFFFFF" class="right">
														<b>Transaction Date:</b> <?php echo date("M d, Y ", strtotime($record['date'])) ;?><br/>
														<b>Due Date:</b> <?php echo isset($record['bp_due_date']) && $record['bp_due_date'] != '' ? date("M d, Y ", strtotime($record['bp_due_date'])) : 'N/A'; ?>			  
													  </td>
													  </tr>  
													</tbody>
												  </table>
									
                                    </div>									 
							

					</div>
					
					<!-- end row -->

				</section>
				<!-- end widget grid -->
				<a href="<?php echo ADMIN_URL;?>addpaymentcustomer" class="btn btn-primary">Back</a>

			</div>
			
				
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
		
		})

		</script>

		