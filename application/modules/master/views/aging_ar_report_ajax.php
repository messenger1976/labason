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
					<th data-hide="phone">SN#</th>
					<th data-hide="phone">Customer Name</th>
					<th data-hide="phone">Customer ID</th>	
					<th data-hide="phone">Meter Number</th>
					<th data-hide="phone">Zone</th>		
					<th data-hide="phone">Current</th>	
																												
					<th data-hide="phone">30 Days</th>
					<th data-hide="phone">60 Days </th>
					<th data-hide="phone">90 Days </th>
					<th data-hide="phone">120 Days </th>
					<th data-hide="phone">150 Days Up </th>
					<th>Amount</th>
					
					
				</tr>
			</thead>
			<tbody>
				<?php
                    if(count($record) > 0){
						$cr = 0;
						
						$index = 1;
						$grand_total_current =0;
						$grand_total_30days = 0;
						$grand_total_60days = 0;
						$grand_total_90days = 0;
						$grand_total_120days = 0;
						$grand_total_150daysup = 0;
						$grand_total_amount =0;
                        foreach($record as $key => $row){ 
				?>                                            
					<tr>
						<td><?php echo $index; ?></td>
						<td><?php echo stripslashes(trim($row['last_name']).', '.trim($row['first_name']).' '.trim($row['middle_name'])); ?></td>
						<td><?php echo stripslashes($row['customer_id']); ?></td>	
						<td><?php echo stripslashes($row['meter_number']); ?></td>
						<td><?php echo stripslashes($row['zone']); ?></td>
						<td align='right'><?php echo stripslashes(number_format($row['current'],2)); ?></td>
						
						<td align='right'><?php echo stripslashes(number_format($row['30-days'],2)); ?></td>
						<td align='right'><?php echo stripslashes(number_format($row['60-days'],2)); ?></td>
						<td align='right'><?php echo stripslashes(number_format($row['90-days'],2)); ?></td>
						<td align='right'><?php echo stripslashes(number_format($row['120-days'],2)); ?></td>
						<td align='right'><?php echo stripslashes(number_format($row['150-DaysUp'],2)); ?></td>
						<td align='right'><?php echo stripslashes(number_format($row['total_balance'],2)); ?></td>

							
						
					</tr>
				<?php 
				$grand_total_current +=$row['current'];
				$grand_total_30days +=$row['30-days'];
				$grand_total_60days +=$row['60-days'];
				$grand_total_90days  +=$row['90-days'];
				$grand_total_120days  +=$row['120-days'];
				$grand_total_150daysup  +=$row['150-DaysUp'];
				$grand_total_amount +=$row['total_balance'];
					
					
					$index++;

					



			} ?>
                 <?php } ?>
                
                
                
                <tr>
					<th style="text-align:right" colspan="5">GRAND TOTAL</th>
					
					<th style="text-align:right"><?php echo number_format($grand_total_current,2);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_30days,2);?></th>	
					<th style="text-align:right"><?php echo number_format($grand_total_60days,2);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_90days,2);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_120days,2);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_150daysup,2);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_amount,2);?></th>
					
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
									