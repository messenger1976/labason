<div class="row">
	<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
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
					<th data-hide="phone">Zone</th>		
					<th data-hide="phone">Category</th>																									
					<th data-hide="phone">Meter Number</th>
					<th data-hide="phone">Billing No. </th>
					<th data-hide="phone">Consumed </th>
					<th data-hide="phone">Metered Sales </th>
					<th data-hide="phone">Penalty Charges </th>
					<th>Due Date</th>
					<!--<th>Invoice</th>-->
					<th>Payment Date</th>
					<th>Total Amount</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php
                    if(count($record) > 0){
						$cr = 0;
						$grand_total_penalty = 0;
						$grand_total_metered_sales = 0;
						$index = 1;
						$grand_total_cubic_meter =0;
						$class_category = array();
						$no_of_customer_1 =0;
                        $no_of_customer_2 =0;
                        $no_of_customer_3 =0;
                        $no_of_customer_4 =0;

						$no_of_consumption_1 =0;
                        $no_of_consumption_2 =0;
						$no_of_consumption_3 =0;
						$no_of_consumption_4 =0;

						$metered_sales_1 =0;
						$metered_sales_2 =0;
						$metered_sales_3 =0;
						$metered_sales_4 =0;

						$penalty_1 =0;
						$penalty_2 =0;
						$penalty_3 =0;
						$penalty_4 =0;

						

                        foreach($record as $key => $row){ 
				?>                                            
					<tr>
						<td><?php echo $index; ?></td>
						<td><?php echo stripslashes(trim($row['last_name']).', '.trim($row['first_name']).' '.trim($row['middle_name'])); ?></td>
						<td><?php echo stripslashes($row['customer_id']); ?></td>	
						<td><?php echo stripslashes($row['zone']); ?></td>
						<td><?php echo stripslashes($row['class_cat_name']); ?></td>
						<td><?php echo stripslashes($row['meter_number']); ?></td>																											
						
						<td><?php echo stripslashes(sprintf('%07d',$row['refno'])); ?></td>
						<td align='center'><?php echo stripslashes($row['consumed']); ?></td>
						<td align='right'><?php echo stripslashes(number_format($row['amount'],2)); ?></td>
						<td align='right'><?php 
						$current_date = date('Y-m-d');
						$pdate = stripslashes($row['payment_date']);
						$date = stripslashes($row['due_date']);
						$penalty = 0;
						if($pdate>$date){
							$penalty = $row['penalty']-$row['amount'];
						}
						
						if($row['invoice_id']==''){
							$total_payment = $row['amount'];
							$date1 = $row['due_date'];
							if($current_date>$date1){
								$penalty = $row['penalty']-$row['amount'];
								$total_payment = $row['penalty'];
								
							}else{
								$penalty = 0;
								
								//$total_payment = $row['amount'];
							}
							
						}else{
							$total_payment = $row['payment_amount'];
						}
						echo stripslashes(number_format($penalty,2)); 
						?></td>
						<td><?php 
						 
						echo date('m/d/Y', strtotime($date)); 
						?></td>
						<!--<td><?php echo stripslashes($row['invoice_id']); ?></td>-->
						<td><?php 
						if($pdate!=''){
							echo date('m/d/Y', strtotime($pdate));  
						}else{
							//echo $current_date;
						}
						
						?></td>
						<td align='right'><?php echo stripslashes(number_format($total_payment,2)); ?></td>
						
						
						<td><span <?php if($row['invoice_id']!= ''){ 
						                  echo " class='label label-success arrowed-in arrowed-in-right'"; 
										} else{ 
										  echo "class='label label-danger arrowed'";
										} 
								  ?>>
								  <a href="#" style="color:#FFF; text-decoration:none;">
									  <?php if($row['invoice_id']!= ''){ 
									  	echo "Paid"; 
									  }elseif($row['customer_status']== '2'){
										echo "Disconnected"; 
									  }elseif($row['customer_status']== '1' && ($row['reading']=='' || is_null($row['reading']))){
										echo "No Reading"; 
									  }else{ 
									  	echo "Un-Paid"; 
									  } ?>
								</a>
							</span>
							
						</td>  
						
							<div class="visible-xs visible-sm hidden-md hidden-lg">
								<div class="inline position-relative">
									<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
										<i class="icon-caret-down icon-only bigger-120"></i>
									</button>
								</div>
							</div>
						</td>
					</tr>
				<?php 
					$cr += $total_payment;
					$grand_total_penalty+=$penalty;
					$grand_total_metered_sales+=$row['amount'];
					$grand_total_cubic_meter+=$row['consumed'];
					$index++;

					

					// Step 1: Loop through each array and search for the key
                    $found = false;

                    // Step 3: Define the key, value to search for, and the field to update
					$keyToSearch = "class_cat_id";
					$valueToFind = $row['class_cat_id'];
				//if($valueToFind!=''){
					$fieldToUpdate = "no_of_customer";
					$fieldToUpdate1 = "no_of_consumption";
					$fieldToUpdate2 = "metered_sales";
					$fieldToUpdate3 = "penalty";
					$newArray = array(
						"class_cat_id" => $row['class_cat_id'],
						"class_cat_name" => $row['class_cat_name'],
					);

					if($row['class_cat_id']=='1'){
						$no_of_customer_1++; // New value to update
						$no_of_consumption_1 +=$row['consumed'];
						$metered_sales_1 += $row['amount'];
						$penalty_1 += $penalty;
						$newArray1 = array(
							"no_of_customer" => $no_of_customer_1,
							"no_of_consumption" => $no_of_consumption_1,
							"metered_sales" => $metered_sales_1,
							"penalty" => $penalty_1
						);
						
					}elseif($row['class_cat_id']=='2'){
						$no_of_customer_2++; // New value to update
						$no_of_consumption_2 +=$row['consumed'];
						$metered_sales_2 += $row['amount'];
						$penalty_2 += $penalty;
						$newArray1 = array(
							"no_of_customer" => $no_of_customer_2,
							"no_of_consumption" => $no_of_consumption_2,
							"metered_sales" => $metered_sales_2,
							"penalty" => $penalty_2
						);
					}elseif($row['class_cat_id']=='3'){
						$no_of_customer_3++; // New value to update
						$no_of_consumption_3 +=$row['consumed'];
						$metered_sales_3 += $row['amount'];
						$penalty_3 += $penalty;
						$newArray1 = array(
							"no_of_customer" => $no_of_customer_3,
							"no_of_consumption" => $no_of_consumption_3,
							"metered_sales" => $metered_sales_3,
							"penalty" => $penalty_3
						);
					}elseif($row['class_cat_id']=='4'){
						$no_of_customer_4++; // New value to update
						$no_of_consumption_4 +=$row['consumed'];
						$metered_sales_4 += $row['amount'];
						$penalty_4 += $penalty;
						$newArray1 = array(
							"no_of_customer" => $no_of_customer_4,
							"no_of_consumption" => $no_of_consumption_4,
							"metered_sales" => $metered_sales_4,
							"penalty" => $penalty_4
						);
					}

					$newArray = array_merge($newArray,$newArray1);
                    foreach ($class_category as &$class_category_key) { // Use & to modify the original array
                        if (array_key_exists($keyToSearch, $class_category_key) && $class_category_key[$keyToSearch] === $valueToFind) {
							//echo "The value '$valueToFind' exists in the key '$keyToSearch' in one of the arrays.<br>";
					
							// Update the specified field
							if (array_key_exists($fieldToUpdate, $class_category_key)) {
								if($row['class_cat_id']=='1'){
									$class_category_key[$fieldToUpdate] = $no_of_customer_1;
								}
								if($row['class_cat_id']=='2'){
									$class_category_key[$fieldToUpdate] = $no_of_customer_2;
								}
								if($row['class_cat_id']=='3'){
									$class_category_key[$fieldToUpdate] = $no_of_customer_3;
								}
								if($row['class_cat_id']=='4'){
									$class_category_key[$fieldToUpdate] = $no_of_customer_4;
								}
								
								//echo "Updated '$fieldToUpdate' to '$newValue' in the array.<br>";
							}
							if (array_key_exists($fieldToUpdate1, $class_category_key)) {
								if($row['class_cat_id']=='1'){
									$class_category_key[$fieldToUpdate1] = $no_of_consumption_1;
								}
								if($row['class_cat_id']=='2'){
									$class_category_key[$fieldToUpdate1] = $no_of_consumption_2;
								}
								if($row['class_cat_id']=='3'){
									$class_category_key[$fieldToUpdate1] = $no_of_consumption_3;
								}
								if($row['class_cat_id']=='4'){
									$class_category_key[$fieldToUpdate1] = $no_of_consumption_4;
								}
								
								//echo "Updated '$fieldToUpdate' to '$newValue' in the array.<br>";
							}
							if(array_key_exists($fieldToUpdate2, $class_category_key)) {
								if($row['class_cat_id']=='1'){
									$class_category_key[$fieldToUpdate2] = $metered_sales_1;
								}
								if($row['class_cat_id']=='2'){
									$class_category_key[$fieldToUpdate2] = $metered_sales_2;
								}
								if($row['class_cat_id']=='3'){
									$class_category_key[$fieldToUpdate2] = $metered_sales_3;
								}
								if($row['class_cat_id']=='4'){
									$class_category_key[$fieldToUpdate2] = $metered_sales_4;
								}
								$found = true;
								//echo "Updated '$fieldToUpdate' to '$newValue' in the array.<br>";
							}
							if(array_key_exists($fieldToUpdate3, $class_category_key)) {
								if($row['class_cat_id']=='1'){
									$class_category_key[$fieldToUpdate3] = $penalty_1;
								}
								if($row['class_cat_id']=='2'){
									$class_category_key[$fieldToUpdate3] = $penalty_2;
								}
								if($row['class_cat_id']=='3'){
									$class_category_key[$fieldToUpdate3] = $penalty_3;
								}
								if($row['class_cat_id']=='4'){
									$class_category_key[$fieldToUpdate3] = $penalty_4;
								}
								
								//echo "Updated '$fieldToUpdate' to '$newValue' in the array.<br>";
							}
							$found = true;
							
						}
                    }

					

                    // Step 2: If the key is not found, insert the new array
                    if (!$found) {
                        //echo "The key '$keyToSearch' does not exist in any of the arrays. Inserting a new array.<br>";
                        $class_category[] = $newArray; // Add the new array to the collection
                    }
				//}

			} ?>
                 <?php } ?>
                
                
                
                <tr>
					<th style="text-align:right" colspan="7">GRAND TOTAL</th>
					
					<th style="text-align:center"><?php echo number_format($grand_total_cubic_meter,0);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_metered_sales,2);?></th>	
					<th style="text-align:right"><?php echo number_format($grand_total_penalty,2);?></th>
					<th></th>																									
					<th></th>
					
					<th style="text-align:right"><?php echo number_format($cr,2);?></th>
					
					<th></th>
				</tr>
                
               
			</tbody>
       </table>
		<h2>BREAKDOWN OF METERED SALES</h2>
	   <table class="table table-bordered">
			<thead>
				<tr>
					<th>CATEGORY</th>
					<th>No. of Consumer</th>
					<th>Consumption</th>
					<th>Amount</th>
					<th>Penalty</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$grand_no_of_customer = 0;
				$grand_no_of_consumption = 0;
				$grand_metered_sales = 0;
				$grand_penalty = 0;
foreach ($class_category as $person) {
	if($person["class_cat_name"]!=''){
		echo "<tr>";
		echo "<td>" . $person["class_cat_name"] . "</td>"; // Name column
		echo "<td align=center>" . $person["no_of_customer"] . "</td>";  // Age column
		echo "<td align=center>" . $person["no_of_consumption"] . "</td>";  // Age column
		echo "<td align=right>" . number_format($person["metered_sales"],2) . "</td>";  // Age column
		echo "<td align=right>" . number_format($person["penalty"],2) . "</td>";  // Age column
		echo "</tr>";
		$grand_no_of_customer +=$person["no_of_customer"];
		$grand_no_of_consumption +=$person["no_of_consumption"];
		$grand_metered_sales +=$person["metered_sales"];
		$grand_penalty +=$person["penalty"];
	}
    
}
echo '<tr><th>GRAND TOTAL</th><th align=center style="text-align: center;"> '. $grand_no_of_customer .' </th><th align=center style="text-align: center;">'. $grand_no_of_consumption .'</th><th align=right style="text-align: right;">'. number_format($grand_metered_sales,2) .'</th><th align=right style="text-align: right;">'. number_format($grand_penalty,2) .'</th></tr>';
				?>
				
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
									