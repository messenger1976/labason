    <div class="row">
        <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
                <?php
                    if(count($record) > 0){
                        foreach($record as $key => $row){ 
                        $id=$row['customer_id'];
                        $zone=$row['zone'];
                        }
                    }
                ?>

        </div>
    </div>     
	 
	                                    <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                                            <table  id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
												<thead>
													<tr>
														<th data-hide="phone">S No</th>
                                                        <th data-class="expand">ID</th>
														<th data-class="expand">Billing Period</th>
                                                        <th data-class="expand">Billing Number</th>
														<th data-class="expand">Customer id</th>
														<th data-hide="expand">Name </th>
														
														<th data-hide="expand">Previous Reading</th>
														<th data-hide="expand">Current Reading</th>
														<th data-hide="expand">Consumed</th>
														<th data-hide="expand">Bill Amount</th>
														<th data-hide="expand">WMMF</th>
														<th data-hide="expand">Franchise Tax %</th>
														<th data-hide="expand">Franchise Tax Amt</th>
														<th data-hide="expand">SC Discount</th>
														<th data-hide="expand">Total</th>
                                                        <th data-hide="expand">Penalty</th>
                                                        
                                                        <th data-hide="expand">Arrears</th>
                                                        <th data-hide="expand">Reading Date</th>
														<th data-hide="expand">Status</th>
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
														<td><?php echo $i; ?></td>
                                                        <td class="id"><?php echo stripslashes($row['id']); ?></td>
														<td class="billing_period"><?php echo stripslashes($row['month_name'].' '.$row['year']); ?></td>
                                                        <td class="refno"><?php echo stripslashes($row['refno']); ?></td>
														<td class="customer_id"><?php echo stripslashes($row['customer_id']); ?></td>
														<td class="fullname"><?php echo stripslashes($row['last_name'].', '.$row['first_name'].' '.$row['middle_name']); ?></td>																												
														<td class="previous_reading" align="center"><?php echo stripslashes($row['previous_reading']); ?></td>
														<td class="current_reading" align="center"><?php echo stripslashes($row['reading']); ?></td>
														<td class="consumed" align="center"><?php echo stripslashes($row['consumed']); ?></td>
														<td class="current_bill" align="right"><?php echo stripslashes($row['unit_price']); ?></td>
														<td class="maintenance_fee" align="right"><?php echo stripslashes(number_format($row['maintenance_fee'],2)); ?></td>
														<td class="franchise_fee_percent" align="right"><?php echo stripslashes(isset($row['franchise_fee_percent']) ? number_format($row['franchise_fee_percent'],2) : '0.00'); ?></td>
														<td class="franchise_fee_amount" align="right"><?php echo stripslashes(isset($row['franchise_fee_amount']) ? number_format($row['franchise_fee_amount'],2) : '0.00'); ?></td>
														<td class="sc_discount" align="right"><?php echo stripslashes($row['sc_discount']); ?></td>
														<td class="total_amount" align="right"><?php echo stripslashes(number_format($row['amount'],2)); ?></td>
														<td class="penalty" align="right"><?php echo stripslashes(number_format($row['penalty'],2)); ?></td>
														
                                                        <td class="arrears" align="right"><?php echo stripslashes(number_format($row['arrears'],2)); ?></td>
                                                        <td class="reading_date" align="center"><?php echo date('d-m-Y',strtotime($row['date'])); ?></td>
														<!--<?php echo ADMIN_URL;?>addmetercustomerreading/edit/<?php echo $row['id'];?>-->
														<td><?php echo stripslashes($row['customer_status']==1?'Active':'<span style="color:red;">Disconnected</span>'); ?></td>
														<td align="center">
                                                            <a class="label label-info btn_edit" 
                                                        data-id="<?php echo stripslashes($row['id']); ?>" 
														data-billing_period="<?php echo stripslashes($row['month_name'].' '.$row['year']); ?>"
                                                        data-refno="<?php echo stripslashes($row['refno']); ?>"
                                                        data-customerid="<?php echo stripslashes($row['customer_id']); ?>"
                                                        data-fullname="<?php echo stripslashes($row['last_name'].', '.$row['first_name'].' '.$row['middle_name']); ?>"
                                                        data-previous_reading="<?php echo stripslashes($row['previous_reading']); ?>"
                                                        data-current_reading="<?php echo stripslashes($row['reading']); ?>"
														data-consumed="<?php echo stripslashes($row['consumed']); ?>"
														data-current_bill="<?php echo stripslashes($row['unit_price']); ?>"
														data-sc_discount="<?php echo stripslashes($row['sc_discount']); ?>"
														data-arrears="<?php echo stripslashes($row['arrears']); ?>"
														data-total_amount="<?php echo stripslashes($row['amount']); ?>"
														data-penalty="<?php echo stripslashes($row['penalty']); ?>"
														data-maintenance_fee="<?php echo stripslashes($row['maintenance_fee']); ?>"
														data-franchise_fee_percent="<?php echo stripslashes(isset($row['franchise_fee_percent']) ? $row['franchise_fee_percent'] : '0.00'); ?>"
														data-franchise_fee_amount="<?php echo stripslashes(isset($row['franchise_fee_amount']) ? $row['franchise_fee_amount'] : '0.00'); ?>"
														data-reading_date="<?php echo date('d-m-Y',strtotime($row['date'])); ?>"
														data-account_type="<?php echo stripslashes($row['account_type']); ?>"
														data-special_priviledge="<?php echo stripslashes($row['special_priviledge']); ?>"
														data-customer_status="<?php echo stripslashes($row['customer_status']); ?>"
																			data-compute_penalty="<?php echo isset($row['compute_penalty']) ? (int)$row['compute_penalty'] : 1; ?>"
                                                        data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i> Edit</a></td>
														<div class="visible-xs visible-sm hidden-md hidden-lg">
																<div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
																		<i class="icon-caret-down icon-only bigger-120"></i>
																	</button>
																</div>
															</div></td>
													</tr>
													<?php  $i++;} ?>
                                                    <?php } ?>
												</tbody>
                                                
											</table>
										</div>
				
										
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
					"lengthMenu": [[10, 50, 100, -1], [10, 50, 100, "All"]], // Add 'All' option
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
		
           
            let selectedRow;

            $(".btn_edit").on('click',function(evt) {
                evt.preventDefault();
                let id = $(this).data("id");
				let billing_period = $(this).data("billing_period");
                let refno = $(this).data("refno");
                let email = $(this).data("email");
                let previous_reading = $(this).data("previous_reading");
                let current_reading = $(this).data("current_reading");
				let consumed = $(this).data("consumed");
				let current_bill = $(this).data("current_bill");
				let sc_discount = $(this).data("sc_discount");
				let arrears = $(this).data("arrears");
				let total_amount = $(this).data("total_amount");
				let penalty = $(this).data("penalty");
				let maintenance_fee = $(this).data("maintenance_fee");
				let franchise_fee_percent = $(this).data("franchise_fee_percent") || '2.00';
				let franchise_fee_amount = $(this).data("franchise_fee_amount") || '0.00';
				let reading_date = $(this).data("reading_date");
				let account_type = $(this).data("account_type");
				let special_priviledge = $(this).data("special_priviledge");
				let customer_status = $(this).data("customer_status");
				let compute_penalty = $(this).data("compute_penalty");
				
                // Set modal fields
				$("#record_id").val(id);
				$("#billing_period").val(billing_period);
                $("#previous_reading").val(previous_reading);
                $("#current_reading").val(current_reading);
				$("#consumed").val(consumed);
				$("#current_bill").val(current_bill);
				$("#sc_discount").val(sc_discount);
				$("#arrears").val(arrears);
				$("#total_amount").val(total_amount);
				$("#penalty").val(penalty);
				$("#maintenance_fee").val(maintenance_fee);
				$("#franchise_fee_percent").val(franchise_fee_percent);
				$("#franchise_fee_amount").val(franchise_fee_amount);
				$("#reading_date").val(reading_date);
				$("#cust_type_id").val(account_type);
				$("#special_priviledge").val(special_priviledge);
				$("#customer_status").val(customer_status);
				if(String(compute_penalty) === '0'){
					$('input[name="compute_penalty"][value="0"]').prop('checked', true);
				}else{
					$('input[name="compute_penalty"][value="1"]').prop('checked', true);
				}
                console.log($("#reading_date").val());
                // Show the modal
                //$("#editModal").modal("show");
            });
		})

		</script>
		