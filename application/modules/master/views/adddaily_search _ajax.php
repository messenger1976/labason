<div class="row">
	<div class="col-lg-12 col-sm-12 col-12 col-md-12">
		<?php
            if(count($record) > 0){
                foreach($record as $key => $row){ 
					$id=$row['id'];
				}
			}
        ?>
	</div>
</div>     
	 <div class="table-responsive">
	 
        <table  class="table table-bordered">
			<thead>
				<tr>
					<th data-hide="phone">OR #</th>
					<th data-hide="phone">ZONE</th>
					<th data-hide="phone">Customer Name</th>																												
					<th data-hide="phone">Date</th>
					<th data-hide="phone">Amount </th>
					
					<th>Cashier</th>
				</tr>
			</thead>
			<tbody>
				<?php
                    if(count($record) > 0){
						$cr = 0;
                        foreach($record as $key => $row){ 
				?>                                            
					<tr>
						<td><?php echo stripslashes($row['or_number']); ?></td>
						<td><?php echo stripslashes($row['zone']); ?></td>
						<td><?php echo stripslashes($row['name']); ?></td>																												
						<!--<td><?php echo stripslashes($row['gender']); ?></td>-->
						<td><?php $date = stripslashes($row['date']); echo date('m/d/Y', strtotime($date)); ?></td>
						
						<td align='right'><?php echo stripslashes(number_format($row['grand_total'],2)); ?></td>
						<td><?php echo $row['user']!=''?$row['user']:'Admin'; ?></td>
						
						<!--<td><span <?php if($row['status']== 1){ 
						                  echo " class='badge badge-success arrowed-in arrowed-in-right'"; 
										} elseif($row['status']== 0){ 
										  echo "class='badge badge-danger arrowed'";
										} 
								  ?>>
								  <a href="JavaScript:if(confirm('Are you sure want to Chanage the Status?')==true){
									  window.location='<?php echo ADMIN_URL;?>addcustomer/status/<?php echo $row['id']?>/<?php echo $row['status'];?>/<?php echo $this->uri->segment(3);?>';
									  }" style="color:#FFF; text-decoration:none;">
									  <?php if($row['status']== 1){ 
									  echo "Paid"; 
									  } elseif($row['status']== 0){ 
									  echo "Un-Paid"; 
									  } ?>
								</a>
							</span>
							
						</td> -->   
						
							<div class="visible-xs visible-sm hidden-md hidden-lg">
								<div class="inline position-relative">
									<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
										<i class="icon-caret-down icon-only bigger-120"></i>
									</button>
								</div>
							</div>
						</td>
					</tr>
				<?php  $cr += $row['grand_total']; } ?>
                 <?php } ?>
                <?php $dr = 0; foreach($monthly as $key => $row){  ?>                                            
					<tr>
						<td><?php echo stripslashes($row['invoice_id']); ?></td>
						<td>Monthlycustomer</td>
						<td><?php echo stripslashes($row['name']); ?></td>																												
						<!--<td><?php echo stripslashes($row['gender']); ?></td>-->
						<td><?php $date = stripslashes($row['date']); echo date('m/d/Y', strtotime($date)); ?></td>
						<td>0</td>
						<td><?php echo stripslashes($row['paidamount']); ?></td>
						<td><?php echo stripslashes($row['']); ?></td>
						<td><?php if($row['status'] == 0){ echo 'Paid'; }else{ echo 'UnPaid';} ?></td>
						<!--<td><span <?php if($row['status']== 1){ 
						                  echo " class='badge badge-success arrowed-in arrowed-in-right'"; 
										} elseif($row['status']== 0){ 
										  echo "class='badge badge-danger arrowed'";
										} 
								  ?>>
								  <a href="JavaScript:if(confirm('Are you sure want to Chanage the Status?')==true){
									  window.location='<?php echo ADMIN_URL;?>addcustomer/status/<?php echo $row['id']?>/<?php echo $row['status'];?>/<?php echo $this->uri->segment(3);?>';
									  }" style="color:#FFF; text-decoration:none;">
									  <?php if($row['status']== 1){ 
									  echo "Paid"; 
									  } elseif($row['status']== 0){ 
									  echo "Un-Paid"; 
									  } ?>
								</a>
							</span>
							
						</td> -->   
						
							<div class="visible-xs visible-sm hidden-md hidden-lg">
								<div class="inline position-relative">
									<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
										<i class="icon-caret-down icon-only bigger-120"></i>
									</button>
								</div>
							</div>
						</td>
					</tr>
				<?php  $dr += $row['paidamount'];} ?>
                <?php $sal = 0; foreach($payroll as $key => $row){  ?>                                            
					<tr>
						<td><?php echo stripslashes($row['invoice_id']); ?></td>
						<td>Salary</td>
						<td><?php echo stripslashes($row['name']); ?></td>																												
						<!--<td><?php echo stripslashes($row['gender']); ?></td>-->
						<td><?php $date = stripslashes($row['date']); echo date('m/d/Y', strtotime($date)); ?></td>
						<td><?php echo stripslashes($row['amount']); ?></td>
						<td>0</td>
						<td><?php echo stripslashes($row['']); ?></td>
						<td><?php echo stripslashes($row['customer_type']); ?></td>
						<!--<td><span <?php if($row['status']== 1){ 
						                  echo " class='badge badge-success arrowed-in arrowed-in-right'"; 
										} elseif($row['status']== 0){ 
										  echo "class='badge badge-danger arrowed'";
										} 
								  ?>>
								  <a href="JavaScript:if(confirm('Are you sure want to Chanage the Status?')==true){
									  window.location='<?php echo ADMIN_URL;?>addcustomer/status/<?php echo $row['id']?>/<?php echo $row['status'];?>/<?php echo $this->uri->segment(3);?>';
									  }" style="color:#FFF; text-decoration:none;">
									  <?php if($row['status']== 1){ 
									  echo "Paid"; 
									  } elseif($row['status']== 0){ 
									  echo "Un-Paid"; 
									  } ?>
								</a>
							</span>
							
						</td> -->   
						
							<div class="visible-xs visible-sm hidden-md hidden-lg">
								<div class="inline position-relative">
									<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
										<i class="icon-caret-down icon-only bigger-120"></i>
									</button>
								</div>
							</div>
						</td>
					</tr>
				<?php  $sal += $row['amount'];} ?>
                <?php $exp = 0; 
				    foreach($expense as $key => $row){  ?>                                            
					<tr>
						<td><?php echo stripslashes($row['invoice_id']); ?></td>
						<td>Expense</td>
						<td><?php echo stripslashes($row['expensestype_name']); ?></td>																												
						<!--<td><?php echo stripslashes($row['gender']); ?></td>-->
						<td><?php $date = stripslashes($row['date']); echo date('m/d/Y', strtotime($date)); ?></td>
						<td><?php echo stripslashes($row['total']); ?></td>
						<td>0</td>
						<td><?php echo stripslashes($row['']); ?></td>
						<td><?php echo stripslashes($row['customer_type']); ?></td>
						<!--<td><span <?php if($row['status']== 1){ 
						                  echo " class='badge badge-success arrowed-in arrowed-in-right'"; 
										} elseif($row['status']== 0){ 
										  echo "class='badge badge-danger arrowed'";
										} 
								  ?>>
								  <a href="JavaScript:if(confirm('Are you sure want to Chanage the Status?')==true){
									  window.location='<?php echo ADMIN_URL;?>addcustomer/status/<?php echo $row['id']?>/<?php echo $row['status'];?>/<?php echo $this->uri->segment(3);?>';
									  }" style="color:#FFF; text-decoration:none;">
									  <?php if($row['status']== 1){ 
									  echo "Paid"; 
									  } elseif($row['status']== 0){ 
									  echo "Un-Paid"; 
									  } ?>
								</a>
							</span>
							
						</td> -->   
						
							<div class="visible-xs visible-sm hidden-md hidden-lg">
								<div class="inline position-relative">
									<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
										<i class="icon-caret-down icon-only bigger-120"></i>
									</button>
								</div>
							</div>
						</td>
					</tr>
				<?php $exp += $row['total']; } ?>
                <tr>
					<th></th>
					<th></th>
					<th></th>																												
					<th>Total</th>
					
					<th style="text-align:right"><?php echo number_format($cr+$dr,2);?></th>
					
					<th></th>
				</tr>
                
               
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
					"sDom": "<'dt-toolbar'<'col-12 col-sm-6'f><'col-sm-6 col-12 hidden-xs'l>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-12 hidden-xs'i><'col-12 col-sm-6'p>>",
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
				"sDom": "<'dt-toolbar'<'col-12 col-sm-6 hidden-xs'f><'col-sm-6 col-12 hidden-xs'<'toolbar'>>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-12 hidden-xs'i><'col-12 col-sm-6'p>>",
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
				"sDom": "<'dt-toolbar'<'col-12 col-sm-6'f><'col-sm-6 col-6 hidden-xs'C>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-12 hidden-xs'i><'col-sm-6 col-12'p>>",
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
				"sDom": "<'dt-toolbar'<'col-12 col-sm-6'f><'col-sm-6 col-6 hidden-xs'T>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-12 hidden-xs'i><'col-sm-6 col-12'p>>",
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
									