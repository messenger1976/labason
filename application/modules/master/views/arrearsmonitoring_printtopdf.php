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
<h3 style="text-align: center;">ARREARS MONITORING REPORT</h3>
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
	</div>
</div>     
	 <div class="table-responsive" >
	 
        <table  class="table" style="font-size:smaller;" cellpadding="0">
			<thead>
				<tr>
					<th>SN #</th>
					<th>Cust Acct No.</th>
					<th>Concessionaires</th>
					<th>Meter Number</th>
					<th>Zone</th>
					<th style="text-align:right;">Aging Amount</th>
					<th style="text-align:right;">Current Billing Period Arrears</th>
				</tr>
			</thead>
			<tbody>
				<?php
                    if(count($zone) > 0){
                        $index = 0;
                        $grand_total_aging   = 0;
                        $grand_total_arrears = 0;

                        foreach($zone as $key => $row){
				?>
					<tr>
						<td></td>
						<td><b><?php echo stripslashes($row['zone']); ?></b></td>
						<td colspan="5"></td>
					</tr>
                <?php
                 $get_dailytrans = $this->report_model->get_arrears_monitoring_records($asofdate, $row['id'], $status);
                 if(!is_array($get_dailytrans)){
                     $get_dailytrans = array();
                 }

                 $zone_total_aging   = 0;
                 $zone_total_arrears = 0;

                 foreach($get_dailytrans as $key2 => $gdailytrans){
                    $index++;
                    $aging_amount = isset($gdailytrans['total_balance']) ? (float) $gdailytrans['total_balance'] : 0;
                    $current_arr  = isset($gdailytrans['current_arrears']) ? (float) $gdailytrans['current_arrears'] : 0;

                    echo '<tr>';
                    echo '<td>'.$index.'</td>
                    <td style="width:15%;">'.$gdailytrans['customer_id'].'</td>
                    <td style="width:25%;">'.$gdailytrans['last_name'].', '.$gdailytrans['first_name'].' '.$gdailytrans['middle_name'].'</td>
                    <td style="width:15%;">'.$gdailytrans['meter_number'].'</td>
                    <td>'.$gdailytrans['zone'].'</td>
                    <td align="right">'.number_format($aging_amount, 2).'</td>
                    <td align="right">'.number_format($current_arr, 2).'</td>';
                    echo '</tr>';

                    $zone_total_aging   += $aging_amount;
                    $zone_total_arrears += $current_arr;

                    $grand_total_aging   += $aging_amount;
                    $grand_total_arrears += $current_arr;
                 }
                 echo '<tr>
                 <th colspan="5" style="text-align:right">TOTAL</th>
                 <th style="text-align:right">'.number_format($zone_total_aging, 2).'</th>
                 <th style="text-align:right">'.number_format($zone_total_arrears, 2).'</th>
                 </tr>';
                }
                ?>
                <?php } ?>

                <tr>
                    <th colspan="5" style="text-align:right">GRAND TOTAL</th>
                    <th style="text-align:right"><?php echo number_format(isset($grand_total_aging) ? $grand_total_aging : 0, 2);?></th>
                    <th style="text-align:right"><?php echo number_format(isset($grand_total_arrears) ? $grand_total_arrears : 0, 2);?></th>
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
                    $preparedby_name = isset($preparedby[0]) ? $preparedby[0]['first_name'].' '.$preparedby[0]['middle_name'].' '.$preparedby[0]['last_name'] : '';
                    $verifiedby_name = isset($verifiedby[0]) ? $verifiedby[0]['first_name'].' '.$verifiedby[0]['middle_name'].' '.$verifiedby[0]['last_name'] : '';
                    $approvedby_name = isset($approvedby[0]) ? $approvedby[0]['first_name'].' '.$approvedby[0]['middle_name'].' '.$approvedby[0]['last_name'] : '';
                ?>
                <td width="30%"><span style="border-bottom: 1px solid black; "><?php echo strtoupper($preparedby_name);?></span></td><td width="20%"></td><td width="30%"><span style="border-bottom: 1px solid black;"><?php echo strtoupper($verifiedby_name);?></span></td>
            </tr>
            <tr>
                <td width="30%"><?php echo isset($preparedby[0]) ? $preparedby[0]['jobtitle'] : '';?></td><td width="20%"></td><td width="30%"><?php echo isset($verifiedby[0]) ? $verifiedby[0]['jobtitle'] : '';?></td>
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
                <td><span style="border-bottom: 1px solid black;"><?php echo strtoupper($approvedby_name);?></span></td><td></td><td>Date/Time printed: <?php echo date('Y-m-d H:i:s');?></td>
            </tr>
            <tr>
                <td width="30%"><?php echo isset($approvedby[0]) ? $approvedby[0]['jobtitle'] : '';?></td><td width="20%"></td><td width="30%"></td>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
       </table>
	</div>
</body>
</html>
