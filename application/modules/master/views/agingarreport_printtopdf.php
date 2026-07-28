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
<h3 style="text-align: center;">AGING OF ACCOUNT RECEIVABLE REPORT</h3>
<h6 style="text-align: center;"><?php

echo 'As of '.$asofdate;?><br/>
<?php 
if($status==='99' || $status===''){
	echo 'All Members';
}elseif($status==='1'){
	echo 'Active Members';
}elseif($status==='2'){
	echo 'Disconnected Members';
}elseif($status==='0'){
	echo 'Inactive Members';
}


?>
</h6>
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
					<th style="width:10px;">Meter Number</th>																										
					<th  style="text-align:right;">Current</th>
					<th  style="text-align:right;">30 Days</th>
					<th  style="text-align:right;">60 Days</th>
                    <th  style="text-align:right;">90 Days</th>
					<th style="text-align:right;">120 Days</th>
                    <th style="text-align:right;">150 Days Up</th>
                    <th style="text-align:right;">Amount</th>
				</tr>
			</thead>
			<tbody>
				<?php
                    if(count($zone) > 0){
                        $index = 0;
						
                        $grand_total_30days = 0;
						$grand_total_60days = 0;
						$grand_total_90days = 0;
						$grand_total_120days = 0;
						$grand_total_150daysup = 0;
						$grand_total_amount =0;

                        
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
                 $get_dailytrans = $this->report_model->get_aging_ar_report_records($asofdate,$row['id'],$status);
                 
				 $grand_total_30days_zone = 0;
				 $grand_total_60days_zone = 0;
				 $grand_total_90days_zone = 0;
				 $grand_total_120days_zone = 0;
				 $grand_total_150daysup_zone = 0;
				 $grand_total_amount_zone =0;
				
                
                
                 foreach($get_dailytrans as $key => $gdailytrans){
                    
                    $index++;
                    echo '<tr>';
                    echo '<td>'.$index.'</td>
                    <td style="width:25%;">'.$gdailytrans['last_name'].', '.$gdailytrans['first_name'].' '.$gdailytrans['middle_name'].'</td>
                    <td style="width:15%;">'.$gdailytrans['customer_id'].'</td>
					<td style="width:15%;">'.$gdailytrans['meter_number'].'</td>
                    <td align="right">'. number_format($gdailytrans['current'],2).'</td>
					<td align="right">'. number_format($gdailytrans['30-days'],2).'</td>
					<td align="right">'. number_format($gdailytrans['60-days'],2).'</td>
					<td align="right">'. number_format($gdailytrans['90-days'],2).'</td>
					<td align="right">'. number_format($gdailytrans['120-days'],2).'</td>
					<td align="right">'. number_format($gdailytrans['150-DaysUp'],2).'</td>
					<td align="right">'. number_format($gdailytrans['total_balance'],2).'</td>

                    
                    ';
                    echo '</tr>';
                    

					$grand_total_current_zone +=$gdailytrans['current'];
					$grand_total_30days_zone +=$gdailytrans['30-days'];
					$grand_total_60days_zone +=$gdailytrans['60-days'];
					$grand_total_90days_zone  +=$gdailytrans['90-days'];
					$grand_total_120days_zone  +=$gdailytrans['120-days'];
					$grand_total_150daysup_zone  +=$gdailytrans['150-DaysUp'];
					$grand_total_amount_zone +=$gdailytrans['total_balance'];
                                    

					$grand_total_current +=$gdailytrans['current'];
					$grand_total_30days +=$gdailytrans['30-days'];
					$grand_total_60days +=$gdailytrans['60-days'];
					$grand_total_90days  +=$gdailytrans['90-days'];
					$grand_total_120days  +=$gdailytrans['120-days'];
					$grand_total_150daysup  +=$gdailytrans['150-DaysUp'];
					$grand_total_amount +=$gdailytrans['total_balance'];
                    
                }
                 echo '<tr>
                 <th colspan="4" style="text-align:right">TOTAL</th>
                 <th style="text-align:right">'.number_format($grand_total_current_zone,2).'</th>
                 <th style="text-align:right">'.number_format($grand_total_30days_zone,2).'</th>
                 <th style="text-align:right">'.number_format($grand_total_60days_zone,2).'</th>
                  <th style="text-align:right">'.number_format($grand_total_90days_zone,2).'</th>
				  <th style="text-align:right">'.number_format($grand_total_120days_zone,2).'</th>
				  <th style="text-align:right">'.number_format($grand_total_150daysup_zone,2).'</th>
				  <th style="text-align:right">'.number_format($grand_total_amount_zone,2).'</th>
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
					
                    <th colspan="4" style="text-align:right">GRAND TOTAL</th>
                    <th style="text-align:right"><?php echo number_format($grand_total_current,2);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_30days,2);?></th>
                    <th style="text-align:right"><?php echo number_format($grand_total_60days,2);?></th>
                    <th style="text-align:right"><?php echo number_format($grand_total_90days,2);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_120days,2);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_150daysup,2);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_amount,2);?></th>
                    <th></th>
				</tr>
                
               
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
									