<style>
	.select2-container{
		width: 100% !important;
		
	}
	.setStatus{
		cursor: pointer;
	}
	/* Fix Select2 dropdown z-index in modals */
	.select2-dropdown {
		z-index: 9999 !important;
	}
	.select2-container--open {
		z-index: 9999 !important;
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
					<li><a href="<?php echo ADMIN_URL;?>leakingentrycorrection"> Leaking Ledger Listing </a></li>
					
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> View <span>> Leaking Ledger </span></h1>
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
									<p style="padding: 5px 0 0 45px;font-size: 16px;"><strong>Manage Leaking Ledger </strong>
									</p>
								</header>
				
								<!-- widget div-->
								<div>
								
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
				                    <form method="post" action="<?php echo ADMIN_URL;?>leakingentrycorrection/multi_delete">
										<!-- widget content -->
										<div class="widget-body no-padding">
										   <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<th data-hide="phone"><input type="checkbox"/></th>
														<th data-hide="phone">S No</th>
														<th data-hide="expand">Customer Name</th>
														<th data-hide="expand">Customer ID</th>
                                                        <th data-hide="expand">Billing No.</th>
                                                        <th data-hide="expand">Billing Period</th>
                                                        <th data-hide="expand">Billing Amount</th>
                                                        <th data-hide="expand">Discount %</th>
														<th data-hide="expand">Discount Amount</th>
                                                        <th data-hide="expand">Total Amount</th>
                                                        <th data-hide="expand">Balance Amount</th>
                                                        <th data-hide="expand">Payment Date</th>
                                                        <th data-hide="expand">Status</th>
														<th data-hide="expand">Action</th>
													</tr>
												</thead>
												<tbody>
												  <?php
														if(count($record) > 0){
                                                        $i=1;
                                                        foreach($record as $key => $row){ 
															$total_payment = $this->my_model->get_total_payment($row['leaking_id'])['totalpayment'];
															$leaking_balance = $row['leaking_total_amount'] - $total_payment;
													?>   
													<tr>
														<td><input type="checkbox" class="ace" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['leaking_id'];?>" /></td>
														<td><?php echo $i; ?></td>
														<td><?php echo stripslashes($row['last_name'].', '.$row['first_name'].' '.$row['middle_name']); ?></td>
														<td><?php echo stripslashes($row['customer_id']); ?></td>
														<td><?php echo stripslashes($row['leaking_refno']); ?></td>
														<td><?php echo stripslashes(getMonthName($row['month'])[0]->month_name.' '.$row['year']); ?></td>
														<td align="right"><?php echo stripslashes(number_format($row['leaking_bill_amount'],2)); ?></td>
														<td align="right"><?php echo stripslashes($row['leaking_discount_percent']); ?></td>
														<td align="right"><?php echo stripslashes(number_format($row['leaking_discount_amount'],2)); ?></td>
														<td align="right"><?php echo stripslashes(number_format($row['leaking_total_amount'],2)); ?></td>
														<td align="right"><?php echo stripslashes(number_format($leaking_balance,2)); ?></td>
														<td>
															<?php
																$paydate = date('M j, Y',strtotime($row['leaking_date']));
																echo $paydate;
															?>
														</td>
														<td><?php 
															if($row['leaking_status']==1){
																echo '<label class="label label-danger setStatus" data-id="'.$row['leaking_id'].'" data-status="'.$row['leaking_status'].'">Pending</label>'; 
															}else if($row['leaking_status']==2){
																echo '<label class="label label-info">Approved</label>'; 

															}elseif($row['leaking_status']==4){
																echo '<label class="label label-primary">Posted</label>';
															}elseif($row['leaking_status']==5){
																echo '<label class="label label-success">Full Paid</label>';
															}else{
																echo '<label class="label label-default">Denied</label>'; 
															}
														
														?></td>
														<td>
														<a href="javascript:void(0);" class="tooltip-error btn_delete" data-rel="tooltip" title="Delete" data-leaking_id="<?php echo $row['leaking_id'];?>">
															<span class="red">
																<img src="<?php echo base_url();?>images/favicon/delete.png">
															</span>
														</a>
														
														<a href="<?php echo ADMIN_URL;?>Leakingentrycorrection/ledger/<?php echo $row['leaking_id'];?>" class="tooltip-success" data-rel="tooltip" title="Ledger">
															<span class="blue">
																<img src="<?php echo base_url();?>images/favicon/ledger.png">
															</span>
														</a>
													</td>

														
														
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
									<div class="row" id="customer_id_div">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Customer : </strong></span>
                                                <select name="customer_id" id="customer_id" placeholder="Type text to search..." required>
													<option value="">--Select--</option>	
													<?php
													
													foreach ($customer_listing as $key => $value) {
														?>
														<option value="<?php echo $value['customer_id'].'==>'.$value['special_priviledge']; ?>"> <?php echo $value['customer_id'] . ' ==> ' . $value['last_name'] . ', ' . $value['first_name'] . ' ' . $value['middle_name']; ?></option>
													<?php }
													?>
												</select>
												<input type="hidden" name="special_priviledge" id="special_priviledge" value="0"/>
												<?php echo form_error('customer_id'); ?>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row" id="billing_period_div">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Billing Period : </strong></span>
                                                <select class="form-control" type="text" id="billing_period" name="billing_period">
													<option value="">--Select--</option>
												</select>
												
                                                <?php echo form_error('billing_period'); ?>
                                            </div>
                                        </div>
                                    </div>

									<div class="row" id="customer_id_div_text">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Customer : </strong></span>
                                                <input type="text" name="customer_id_text" id="customer_id_text"  style="background-color:yellow;" class="form-control" readonly>
												
												
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row" id="billing_period_div_text">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Billing Period : </strong></span>
                                                <input type="text" name="billing_period_text" id="billing_period_text"  style="background-color:yellow;" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
									<input type="hidden" name="refno" id="refno"/>
									<input type="hidden" name="leaking_id" id="leaking_id"/>
									<input type="hidden" name="gross_amount" id="gross_amount" value="0.00"/>

<section id="leaking_option">
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Previous Reading : </strong></span>
                                                <input class="form-control" type="text" id="previous_reading" name="previous_reading" style="background-color:yellow;" value="0" readonly>
                                                <?php echo form_error('previous_reading'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Current Reading : <i style="color:red;">*</i></strong></span>
                                                <input class="form-control" type="text" id="current_reading" name="current_reading" style="background-color:yellow;" readonly>
                                                <?php echo form_error('current_reading'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Consumed : </strong></span>
                                                <input class="form-control" type="text" id="consumed" name="consumed" style="background-color:yellow;" readonly>
                                                <?php echo form_error('consumed'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Current Bill : </strong></span>
                                                <input class="form-control" type="text" id="current_bill" name="current_bill" style="background-color:yellow;" readonly>
                                                <?php echo form_error('current_bill'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>SC Discount : </strong></span>
                                                <input class="form-control" type="text" id="sc_discount" name="sc_discount" style="background-color:yellow;" readonly>
                                                <?php echo form_error('sc_discount'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Arrears : </strong></span>
                                                <input class="form-control" type="text" id="arrears" name="arrears" style="background-color:yellow;" readonly>
                                                <?php echo form_error('arrears'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Amt before due date : </strong></span>
                                                <input class="form-control" type="text" id="total_amount" name="total_amount" style="background-color:yellow;" readonly>
                                                <?php echo form_error('arrears'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Amt after due date : </strong></span>
                                                <input class="form-control" type="text" id="penalty" name="penalty" style="background-color:yellow;" readonly>
                                                <?php echo form_error('arrears'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Reading date : </strong></span>
                                                <input class="form-control" type="text" id="reading_date" name="reading_date" style="background-color:yellow;" readonly>
                                                <?php echo form_error('reading_date'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Due date : </strong></span>
                                                <input class="form-control" type="text" id="due_date" name="due_date" style="background-color:yellow;" readonly>
                                                <?php echo form_error('due_date'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Payment Date : </strong></span>
                                                <input class="form-control" type="text" id="payment_date" name="payment_date" style="background-color:white;" value="<?php echo date('d-m-Y');?>" required>
                                                <?php echo form_error('payment_date'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Leaking Disc(%) : </strong></span>
                                                <input class="form-control" type="text" id="leaking_percent" name="leaking_percent" style="background-color:white;" required>
                                                <?php echo form_error('leaking_percent'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Leaking Disc(Amt) : </strong></span>
                                                <input class="form-control" type="text" id="leaking_amount" name="leaking_amount" style="background-color:yellow;" readonly>
                                                <?php echo form_error('leaking_percent'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Bill Amount : </strong></span>
                                                <input class="form-control" type="text" id="bill_amount" name="bill_amount" style="background-color:yellow;" readonly>
                                                <?php echo form_error('bill_amount'); ?>
                                            </div>
                                        </div>
                                    </div>

</section>


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


			// Function to initialize Select2 properly for modals
			function initCustomerSelect2() {
				// Destroy existing Select2 instance if it exists
				if ($('#customer_id').hasClass('select2-hidden-accessible')) {
					$('#customer_id').select2('destroy');
				}
				// Initialize Select2 with proper configuration for modals
				$('#customer_id').select2({
					dropdownParent: $('#myModal'),
					width: '100%',
					placeholder: 'Type text to search...'
				});
			}
			
			// Initialize Select2 when modal is shown to fix search input issue
			$('#myModal').on('shown.bs.modal', function () {
				// Only initialize if customer dropdown is visible
				if ($('#customer_id_div').is(':visible')) {
					initCustomerSelect2();
				}
			});
			
			// Initialize Select2 on page load (will be reinitialized when modal opens)
			$('#customer_id').select2({
				width: '100%',
				placeholder: 'Type text to search...'
			});
			
			$('#customer_id').on('change', function(evt){
				evt.preventDefault();
				var customer_id = $(this).val().split('==>')[0];

				var $special_priviledge = $(this).val().split('==>')[1];
				$('#special_priviledge').val($special_priviledge);

				var cust_id = customer_id;
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

			$(document).on('click', '.btn_edit', function(evt){
				evt.preventDefault();
				$fullname = $(this).data('fullname');
				$billing_period = $(this).data('billing_period');
				$leaking_id = $(this).data('leaking_id');
				$special_priviledge = $(this).data('special_priviledge');
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
				$('#special_priviledge').val($special_priviledge);
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
				//var $customer_id = $(this).val().split('==>')[0];
				var customer_id = $('#customer_id').val().split('==>')[0];
				var leaking_percent = $('#leaking_percent').val();
				var payment_date = $('#payment_date').val();
				var leaking_amount = $('#leaking_amount').val();
				var bill_amount = $('#bill_amount').val();
				var gross_amount = $('#gross_amount').val();
				var leaking_id = $('#leaking_id').val();
				var due_date = $('#due_date').val();
				var btn_save = $('#btn_save').val();

				const formData = new FormData();
				formData.append("refno",refno);
				formData.append("customer_id", customer_id);
				formData.append("leaking_percent", leaking_percent);
				formData.append("payment_date", payment_date);
				formData.append("leaking_amount", leaking_amount);
				formData.append("bill_amount", bill_amount);
				formData.append("gross_amount", gross_amount);
				formData.append("due_date", due_date);
				formData.append("btn_save", btn_save);
				formData.append("leaking_status", 1);
				if(leaking_id){
					formData.append("leaking_id", leaking_id);
				}

				$.ajax({
					url: '<?php echo ADMIN_URL;?>Leakingentrycorrection/add/',
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
							window.location='<?php echo ADMIN_URL;?>Leakingentrycorrection';
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

			// Delete button handler with SweetAlert
			$(document).on('click', '.btn_delete', function(e) {
				e.preventDefault();
				var leaking_id = $(this).data('leaking_id');
				var row = $(this).closest('tr');
				
				Swal.fire({
					title: 'Are you sure?',
					text: "This will delete the leaking entry and all associated ledger details. This action cannot be undone!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#d33',
					cancelButtonColor: '#3085d6',
					confirmButtonText: 'Yes, delete it!',
					cancelButtonText: 'Cancel',
					reverseButtons: true
				}).then((result) => {
					if (result.isConfirmed) {
						// Show loading
						Swal.fire({
							title: 'Deleting...',
							text: 'Please wait while we delete the record.',
							allowOutsideClick: false,
							allowEscapeKey: false,
							showConfirmButton: false,
							willOpen: () => {
								Swal.showLoading();
							}
						});
						
						// Make AJAX call to delete
						$.ajax({
							url: '<?php echo ADMIN_URL;?>Leakingentrycorrection/delete/' + leaking_id,
							type: 'GET',
							dataType: 'json',
							success: function(response) {
								if (response.status === 'success') {
									Swal.fire({
										title: 'Deleted!',
										text: response.message,
										icon: 'success',
										confirmButtonColor: '#3085d6',
										confirmButtonText: 'OK',
										timer: 2000,
										timerProgressBar: true
									}).then((result) => {
										// Reload the page to refresh the data
										window.location.reload();
									});
								} else {
									Swal.fire({
										title: 'Error!',
										text: response.message || 'Failed to delete the record.',
										icon: 'error',
										confirmButtonColor: '#3085d6',
										confirmButtonText: 'OK'
									});
								}
							},
							error: function(xhr, status, error) {
								Swal.fire({
									title: 'Error!',
									text: 'An error occurred while deleting the record. Please try again.',
									icon: 'error',
									confirmButtonColor: '#3085d6',
									confirmButtonText: 'OK'
								});
								console.error('Delete Error: ' + error);
							}
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
				var special_priviledge = $('#special_priviledge').val();
				var date1 = parseDmyString(duedate);
				var date2 = parseDmyString(paymentdate);
				 console.log('special Previous:'+special_priviledge);
				if(date1 < date2 && special_priviledge==0){
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

		
		</script>



		