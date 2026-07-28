<html lang="en">
<head>
		<meta charset="utf-8" />
		<title></title>
		<meta name="description" content="Static &amp; Dynamic Tables" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!-- basic styles -->
		<link href="<?php echo site_url();?>/assets/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/font-awesome.min.css" />
        <link rel="stylesheet" href="<?php echo site_url();?>/assets/css/style.css" />
        
		<!--[if IE 7]>
		  <link rel="stylesheet" href="<?php echo site_url();?>/assets/css/font-awesome-ie7.min.css" />
		<![endif]-->
		<!-- page specific plugin styles -->
		<!-- fonts -->
		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/ace-fonts.css" />
		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/ace.min.css" />
		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/ace-rtl.min.css" />
		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/ace-skins.min.css" />
		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="<?php echo site_url();?>/assets/css/ace-ie.min.css" />
		<![endif]-->
		<!-- inline styles related to this page -->
		<!-- ace settings handler -->
		<script src="<?php echo site_url();?>/assets/js/ace-extra.min.js"></script>
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="<?php echo site_url();?>/assets/js/html5shiv.js"></script>
		<script src="<?php echo site_url();?>/assets/js/respond.min.js"></script>
		<![endif]-->
        <style>
            .table>tbody>tr>td{
                padding: 5px;
            }
        </style>
	</head>
<body class="color" onLoad="window.print()">
    <div style="text-align: center;"><img src="<?php echo site_url();?>images/mroxas-logo-report.jpg" height="80px"/></div>
<h3 style="text-align: center;">MONTHLY BILLING REPORT</h3>
<h6 style="text-align: center;"><?php
$billingperiod1 = explode(' ',urldecode($billingperiod));
$billingperiod = urldecode($billingperiod);
echo $billingperiod_month_name.' '.$billingperiod1[1];?></h6>
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
	 <div class="table-responsive" >
	 
        <table  class="table" style="font-size:smaller;" cellpadding="0">
			<thead>
				<tr>
					<th>SN #</th>
					
					<th>Concessionaires</th>
                    <th>Cust Acct No.</th>																												
					<th  style="text-align:left;">Meter No.</th>
					<th  style="text-align:left;">Bill No.</th>
					<th  style="text-align:right;">Consumed</th>
                    <th  style="text-align:right;">Metered Sales</th>
					<th style="text-align:right;">Penalty Charges</th>
                    <th style="text-align:right;">Total Amount</th>
                    <th style="text-align:center;">Status</th>
				</tr>
			</thead>
			<tbody>
				<?php
                    if(count($zone) > 0){
                        $index = 0;
						$cr = 0;
                        $grand_total_amount = 0;
                        $grand_total_penalty =0;
                        $grand_total_reading =0;
                        $grand_total_billamount =0;

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
                        foreach($zone as $key => $row){ 
				?>                                            
					<tr>
						<td></td>
						<td><b><?php echo stripslashes($row['zone']); ?></b></td>
						<td colspan="8"></td>
					</tr>
                <?php
                //$mysql_transdate = date('Y-m-d',strtotime($trans_date));
                 //$get_dailytrans = $this->my_model->get_metercustomer_records($mysql_transdate,$row['id']);
				 //if($status=='3'){
				//	$get_dailytrans = $this->report_model->get_monthly_billing_report_records_status3($row['id'],$billingperiod,$status);
				// }else{
					$get_dailytrans = $this->report_model->get_monthly_billing_report_records($row['id'],$billingperiod,$status);
				// }
                 
                 
                 $total_amount_zone = 0;
                 $total_penalty_zone = 0;
                 $total_reading_zone = 0;
                 $total_billamount_zone = 0;
                
                
                 foreach($get_dailytrans as $key => $gdailytrans){
                    $gross_total = $gdailytrans['grand_total'] + $gdailytrans['vat_amount'];

                    $current_date = date('Y-m-d');
                    $pdate = stripslashes($gdailytrans['payment_date']);
                    $date = stripslashes($gdailytrans['due_date']);
                    $penalty = 0;
                    if($pdate>$date){
                        $penalty = $gdailytrans['penalty']-$gdailytrans['amount'];
                    }
                    if($gdailytrans['invoice_id']==''){
                        $total_payment = $gdailytrans['amount'];
                        $date1 = $gdailytrans['due_date'];
                        if($current_date>$date1){
                            $penalty = $gdailytrans['penalty']-$gdailytrans['amount'];
                            $total_payment = $gdailytrans['penalty'];
                            
                        }else{
                            $penalty = 0;
                        }
                        
                    }else{
                        $total_payment = $gdailytrans['payment_amount'];
                    }

                    if($gdailytrans['invoice_id']!= ''){ 
                        $status_msg= "Paid"; 
                    }elseif($gdailytrans['customer_status']=='2'){
						$status_msg=  "Disconnected"; 
					}elseif($gdailytrans['customer_status']=='1' && ($gdailytrans['reading']== '' || is_null($gdailytrans['reading']))){
						$status_msg=  "No Reading"; 
					}else{ 
                        $status_msg=  "Unpaid"; 
                    }
                    $index++;
                    echo '<tr>';
                    echo '<td>'.$index.'</td>
                    <td style="width:25%;">'.$gdailytrans['last_name'].', '.$gdailytrans['first_name'].' '.$gdailytrans['middle_name'].'</td>
                    <td style="width:15%;">'.$gdailytrans['customer_id'].'</td>
                    <td align="left">'.$gdailytrans['meter_number'].'</td>
                    <td align="left">'.sprintf('%07d',$gdailytrans['refno']).'</td>
                    <td align="right" style="width:5%;">'. number_format($gdailytrans['consumed'],0).'</td>
                    <td align="right">'.stripslashes(number_format($gdailytrans['amount'],2)).'</td>
                    <td align="right">'. number_format($penalty,2).'</td>
                    <td align="right">'.number_format($total_payment,2).'</td>
                    <td align="right">'.$status_msg.'</td>
                    ';
                    echo '</tr>';
                    $total_amount_zone += $gdailytrans['amount'];
                    
                    $total_penalty_zone += $penalty;
                    $total_reading_zone += $gdailytrans['consumed'];
                    $total_billamount_zone +=$total_payment;


                    // Step 1: Loop through each array and search for the key
                    $found = false;

                    // Step 3: Define the key, value to search for, and the field to update
					$keyToSearch = "class_cat_id";
					$valueToFind = $gdailytrans['class_cat_id'];

					$fieldToUpdate = "no_of_customer";
					$fieldToUpdate1 = "no_of_consumption";
					$fieldToUpdate2 = "metered_sales";
					$fieldToUpdate3 = "penalty";
					$newArray = array(
						"class_cat_id" => $gdailytrans['class_cat_id'],
						"class_cat_name" => $gdailytrans['class_cat_name'],
					);

					if($gdailytrans['class_cat_id']=='1'){
						$no_of_customer_1++; // New value to update
						$no_of_consumption_1 +=$gdailytrans['consumed'];
						$metered_sales_1 += $gdailytrans['amount'];
						$penalty_1 += $penalty;
						$newArray1 = array(
							"no_of_customer" => $no_of_customer_1,
							"no_of_consumption" => $no_of_consumption_1,
							"metered_sales" => $metered_sales_1,
							"penalty" => $penalty_1
						);
						
					}elseif($gdailytrans['class_cat_id']=='2'){
						$no_of_customer_2++; // New value to update
						$no_of_consumption_2 +=$gdailytrans['consumed'];
						$metered_sales_2 += $gdailytrans['amount'];
						$penalty_2 += $penalty;
						$newArray1 = array(
							"no_of_customer" => $no_of_customer_2,
							"no_of_consumption" => $no_of_consumption_2,
							"metered_sales" => $metered_sales_2,
							"penalty" => $penalty_2
						);
					}elseif($gdailytrans['class_cat_id']=='3'){
						$no_of_customer_3++; // New value to update
						$no_of_consumption_3 +=$gdailytrans['consumed'];
						$metered_sales_3 += $gdailytrans['amount'];
						$penalty_3 += $penalty;
						$newArray1 = array(
							"no_of_customer" => $no_of_customer_3,
							"no_of_consumption" => $no_of_consumption_3,
							"metered_sales" => $metered_sales_3,
							"penalty" => $penalty_3
						);
					}elseif($gdailytrans['class_cat_id']=='4'){
						$no_of_customer_4++; // New value to update
						$no_of_consumption_4 +=$gdailytrans['consumed'];
						$metered_sales_4 += $gdailytrans['amount'];
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
								if($gdailytrans['class_cat_id']=='1'){
									$class_category_key[$fieldToUpdate] = $no_of_customer_1;
								}
								if($gdailytrans['class_cat_id']=='2'){
									$class_category_key[$fieldToUpdate] = $no_of_customer_2;
								}
								if($gdailytrans['class_cat_id']=='3'){
									$class_category_key[$fieldToUpdate] = $no_of_customer_3;
								}
								if($gdailytrans['class_cat_id']=='4'){
									$class_category_key[$fieldToUpdate] = $no_of_customer_4;
								}
								//echo "Updated '$fieldToUpdate' to '$newValue' in the array.<br>";
							}
							if (array_key_exists($fieldToUpdate1, $class_category_key)) {
								if($gdailytrans['class_cat_id']=='1'){
									$class_category_key[$fieldToUpdate1] = $no_of_consumption_1;
								}
								if($gdailytrans['class_cat_id']=='2'){
									$class_category_key[$fieldToUpdate1] = $no_of_consumption_2;
								}
								if($gdailytrans['class_cat_id']=='3'){
									$class_category_key[$fieldToUpdate1] = $no_of_consumption_3;
								}
								if($gdailytrans['class_cat_id']=='4'){
									$class_category_key[$fieldToUpdate1] = $no_of_consumption_4;
								}
								//echo "Updated '$fieldToUpdate' to '$newValue' in the array.<br>";
							}
							if (array_key_exists($fieldToUpdate2, $class_category_key)) {
								if($gdailytrans['class_cat_id']=='1'){
									$class_category_key[$fieldToUpdate2] = $metered_sales_1;
								}
								if($gdailytrans['class_cat_id']=='2'){
									$class_category_key[$fieldToUpdate2] = $metered_sales_2;
								}
								if($gdailytrans['class_cat_id']=='3'){
									$class_category_key[$fieldToUpdate2] = $metered_sales_3;
								}
								if($gdailytrans['class_cat_id']=='4'){
									$class_category_key[$fieldToUpdate2] = $metered_sales_4;
								}
								//echo "Updated '$fieldToUpdate' to '$newValue' in the array.<br>";
							}
							if (array_key_exists($fieldToUpdate3, $class_category_key)) {
								if($gdailytrans['class_cat_id']=='1'){
									$class_category_key[$fieldToUpdate3] = $penalty_1;
								}
								if($gdailytrans['class_cat_id']=='2'){
									$class_category_key[$fieldToUpdate3] = $penalty_2;
								}
								if($gdailytrans['class_cat_id']=='3'){
									$class_category_key[$fieldToUpdate3] = $penalty_3;
								}
								if($gdailytrans['class_cat_id']=='4'){
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


                    
                }
                 echo '<tr>
                 <th colspan="5" style="text-align:right">TOTAL</th>
                 <th style="text-align:right">'.number_format($total_reading_zone,0).'</th>
                 <th style="text-align:right">'.number_format($total_amount_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_penalty_zone,2).'</th>
                  <th style="text-align:right">'.number_format($total_billamount_zone,2).'</th>
                  <th></th>
                 </tr>';
                
                
                    
                    $grand_total_amount += $total_amount_zone; 
                    $grand_total_penalty += $total_penalty_zone;
                    $grand_total_reading += $total_reading_zone;
                    $grand_total_billamount += $total_billamount_zone;
                    

            
                } 
                ?>
                 <?php } ?>


                
                
                
                <tr>
					
                    <th colspan="5" style="text-align:right">GRAND TOTAL</th>
                    <th style="text-align:right"><?php echo number_format($grand_total_reading,0);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_amount,2);?></th>
                    <th style="text-align:right"><?php echo number_format($grand_total_penalty,2);?></th>
                    <th style="text-align:right"><?php echo number_format($grand_total_billamount,2);?></th>
                    <th></th>
				</tr>
                
               
			</tbody>
       </table>


       <center><h2>BREAKDOWN OF METERED SALES</h2></center>
	   <table class="table" style="font-size:smaller;" cellpadding="0">
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

       <table width="100%"  style="font-size:smaller;" cellspacing="5" cellpadding="5">
            <tr>
                <th colspan="3">&nbsp;</th>
            </tr>
            <tr>
                <td width="30%">Prepared by:</td><td width="20%"></td><td width="30%">Verified by:</td>
            </tr>
            <tr>
                <th colspan="3">&nbsp;</th>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
            <tr style="font-weight: bold;">
                <?php
                    $preparedby_name = $preparedby[0]['first_name'].' '.$preparedby[0]['middle_name'].' '.$preparedby[0]['last_name'];
                    $verifiedby_name = $verifiedby[0]['first_name'].' '.$verifiedby[0]['middle_name'].' '.$verifiedby[0]['last_name'];
                    $approvedby_name = $approvedby[0]['first_name'].' '.$approvedby[0]['middle_name'].' '.$approvedby[0]['last_name'];
                ?>
                <td width="30%"><span style="border-bottom: 1px solid black; "><?php echo strtoupper($preparedby_name);?></span></td><td width="20%"></td><td width="30%"><span style="border-bottom: 1px solid black;"><?php echo strtoupper($verifiedby_name);?></span></td>
            </tr>
            <tr>
                <td width="30%"><?php echo $preparedby[0]['jobtitle'];?></td><td width="20%"></td><td width="30%"><?php echo $verifiedby[0]['jobtitle'];?></td>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
            <tr>
                <td width="30%">Approved by:</td><td width="20%"></td><td width="30%"></td>
            </tr>
            <tr>
                <th colspan="3">&nbsp;</th>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
            <tr style="font-weight: bold;">
                <td><span style="border-bottom: 1px solid black;"><?php echo strtoupper($approvedby_name);?></span></td><td></td><td>Date/Time printed: <?php echo date('Y-m-d H:m:s');?></td>
            </tr>
            <tr>
                <td width="30%"><?php echo $approvedby[0]['jobtitle'];?></td><td width="20%"></td><td width="30%"></td>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
       </table>
	</div>
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
									