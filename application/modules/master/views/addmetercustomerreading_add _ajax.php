 <div class="">
	 <div class="col-lg-12 col-sm-12 col-12 col-md-12">
			<?php
				if(count($record) > 0){
					foreach($record as $key => $row){ 
							$id=$row['id'];
					}
				}
			?>
			<div class="row"></div>
		</div>         
	<div style="padding:20px; box-shadow: 0px 0px 3px 1px rgba(0,0,0,0.75);">
		<table  class="table table-bordered table-progressive">
			<thead>
				<tr>
					<th>Customer Id</th>
					<th>Name</th>																												
					
					<th>Zone</th>
					<th>Meter #</th>
					<th>Account Type</th>
					<th>Classification</th>
					<th>Previous Reading</th>
					<th>Month</th>
					
				</tr>
				</thead>
				<tbody>
				<?php
				foreach($get_unit_price as $key => $ut_pr){
					
				}
				//print_r($get_billing_period);
				//echo $record[0]['zone_id'];
				
				if(count($record) > 0){
				  foreach($record as $key => $row){
				?>                                            
				<tr>
					<td><?php echo $row['customer_id']; ?>
					    <input  type="hidden" class="form-control"  id="customer" name="customer" value="<?php echo $row['customer_id']; ?>" />
						<input  type="hidden" class="form-control"  id="unit_pr" name="unit_pr" value="<?php echo $ut_pr['per_unit']; ?>" />
					</td>
					<td><?php echo stripslashes($row['first_name'].'&nbsp;'.$row['middle_name'].'&nbsp;'.$row['last_name']); ?></td>																												
					
					<td><?php echo stripslashes($row['zone']); ?></td>
					<td><?php echo stripslashes($row['meter_number']); ?></td>
					<td><?php echo stripslashes($row['cust_type_name']); ?>
					<input  type="hidden" class="form-control"  id="cust_type_id" name="cust_type_id" value="<?php echo $row['cust_type_id']; ?>" />
					<input  type="hidden" class="form-control"  id="special_priviledge" name="special_priviledge" value="<?php echo $row['special_priviledge']; ?>" />
					</td>
					<td><?php echo stripslashes($row['class_name']); ?></td>
					<td><?php if(count($last_reading) > 0){
						          foreach($last_reading as $key => $last_read){
									  echo stripslashes($last_read['reading']); 
									  echo '<input  type="hidden" class="form-control"  id="preview_read" name="preview_read" value="'.$last_read['reading'].'" />';
								  }
							  }else{ 
							      echo '0';
								  echo '<input  type="hidden" class="form-control"  id="preview_read" name="preview_read" value="0" />';
							  }?>
					</td>
					<td><?php if(count($last_reading) > 0){
								  $mon_id = $last_read['month']; 
								  $year = $last_read['year'];
								  $get_month = $this->my_model->get_month_name($mon_id);
								  foreach($get_month as $key => $gmon){ 
								     echo $gmon['month_name'].' '.$year;
								  }
					          }else{
								 echo 'No Month'; 
							  }	  
							  
						?></td>
					
				</tr>
				<?php  } 
				} ?>
				</tbody>
		</table>
		<table class="table table-bordered table-progressive">
				<input type="hidden" name="bp_id" id="bp_id" value="<?php echo $get_billing_period['bp_id'];?>"/>
				<input type="hidden" name="bp_period_month" id="bp_period_month" value="<?php echo $get_billing_period['bp_period_month'];?>"/>
				<input type="hidden" name="bp_period_year" id="bp_period_year" value="<?php echo $get_billing_period['bp_period_year'];?>"/>
			<?php
				//foreach($get_billing_period as $key => $billing_period_array){
					echo "<tr><th width='5%' style='background-color: lightgrey;'>ZONE:</th><td width='6%'>".$row['zone']."</td><th width='11%' style='background-color: lightgrey;'>BILLING PERIOD:</th><td width='11%'>".$get_billing_period['month_name'].' '.$get_billing_period['bp_period_year']."</td><th width='8%' style='background-color: lightgrey;'>START DATE:</th><td width='8%'>".date('M j, Y',strtotime($get_billing_period['bp_start_date']))."</td><th width='8%' style='background-color: lightgrey;'>END DATE:</th><td width='8%'>".date('M j, Y',strtotime($get_billing_period['bp_end_date']))."</td><th width='8%' style='background-color: lightgrey;'>DUE DATE:</th><td width='8%'>".date('M j, Y',strtotime($get_billing_period['bp_due_date']))."</td><th width='9%' style='background-color: lightgrey;'>DISCON DATE:</th><td>".date('M j, Y',strtotime($get_billing_period['bp_disconnection_date']))."</td></tr>";
				//}
				?>
				
					
				
		</table>
		<?php
		//if($mon_id!=$get_billing_period['bp_period_month'] && $year!=$get_billing_period['bp_period_year']){
			echo '<input class="pay_button" id="add_meter_reading"  type="button" name="add_meter_reading" value="Add Meter reading">';
		//}
		?>
		
	</div>
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

		var bp_month = '<?php echo $mon_id;?>';
		var bp_year = '<?php echo $year;?>';
		var bp_current_month = '<?php echo $get_billing_period['bp_period_month'];?>';
		var bp_current_year = '<?php echo $get_billing_period['bp_period_year'];?>';
		
		let hideclass = document.getElementById("hideclass");
		let total_setting_2 = document.getElementById("total_setting_2");
		
		if(bp_month==bp_current_month && bp_year==bp_current_year){
			//$('#hideclass').hide();
			hideclass.style.display = "none"; // Hides the element
			total_setting_2.style.display = "none"; // Hides the element
		}else{
			hideclass.style.display = "block"; // Hides the element
			total_setting_2.style.display = "block"; // Hides the element
		}

		</script>																															