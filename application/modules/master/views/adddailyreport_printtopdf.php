<!-- build: penalty-in-arrears 2026-04-12 -->
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
            @media print {
                /* Long bond paper: 8.5" x 13" */
                @page { size: 8.5in 13in portrait; margin: 8mm; }
                html, body { width: 100%; }
                body { margin: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            }
            body { font-size: 9px; }
            h3 { margin: 6px 0 2px; }
            h6 { margin: 2px 0; }
            .table-responsive { overflow: visible !important; }
            table.table { width: 100%; table-layout: fixed; }
            table.table th, table.table td { padding: 2px 3px; vertical-align: top; }
            table.table th { font-size: 9px; }
            table.table td { font-size: 9px; }
            /* Wrap headers; keep numeric cells aligned; allow names to wrap */
            table.table th { white-space: normal; word-break: break-word; line-height: 1.1; }
            table.table td { word-break: break-word; }
            table.table td:nth-child(2) { white-space: normal; }
            table.table td:not(:nth-child(2)) { white-space: nowrap; }
            .table>tbody>tr>td{
                padding: 5px;
            }
        </style>
	</head>
<body class="color" onLoad="window.print()">
    <div style="text-align: center;"><img src="<?php echo site_url();?>images/mroxas-logo-report.jpg" height="80px"/></div>
<h3 style="text-align: center;">DAILY COLLECTION REPORT</h3>
<h6 style="text-align: center;"><?php echo $trans_date;?></h6>
<?php if(isset($cashier) && (int)$cashier > 0){ ?>
<?php
	$cashier_label = 'Filtered by Cashier/User';
	if(isset($cashier_info[0]['employee_name']) && $cashier_info[0]['employee_name'] != ''){
		$cashier_label = 'Filtered by Cashier/User: '.strtoupper($cashier_info[0]['employee_name']);
		if(isset($cashier_info[0]['username']) && $cashier_info[0]['username'] != ''){
			$cashier_label .= ' ('.$cashier_info[0]['username'].')';
		}
	}
?>
<h6 style="text-align: center;"><?php echo $cashier_label; ?></h6>
<?php } ?>
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
            <colgroup>
                <col style="width:10%">
                <col style="width:17%">
                <col style="width:8%">
                <col style="width:8%">
                <col style="width:8%">
                <col style="width:7%">
                <col style="width:7%">
                <col style="width:7%">
                <col style="width:7%">
                <col style="width:7%">
                <col style="width:7%">
                <col style="width:7%">
            </colgroup>
			<thead>
				<tr>
					<th data-hide="phone">OR #</th>
					
					<th data-hide="phone">Concessionaires</th>																												
					<th  style="text-align:right;" data-hide="phone">Total Amount Collected</th>
					<th  style="text-align:right;" data-hide="phone">Current</th>
					<th  style="text-align:right;" data-hide="phone">Arrears</th>
					<th style="text-align:right;">WMMF</th>
                    <th style="text-align:right;">Penalty</th>
                    <th style="text-align:right;">SC Disc</th>
                    <th style="text-align:right;">Leaking Disc</th>
                    <th style="text-align:right;">A/R-Leaking</th>
                    <th style="text-align:right;">A/R-Leaking Balance</th>
                    <th style="text-align:right;">Franchise Tax</th>
				</tr>
			</thead>
			<tbody>
				<?php
                    if(count($zone) > 0){
						$cr = 0;
                        $grand_total_current = 0;
                        $grand_total_penalty =0;
						$grand_total_wmmf =0;
                        $grand_total_leaking =0;
                        $grand_total_ar_leaking =0;
                        $grand_total_ar_leaking_balance =0;
                        $grand_total_sc =0;
                        $grand_total_franchise_fee =0;
                        foreach($zone as $key => $row){ 
				?>                                            
					<tr>
						<td></td>
						<td><b><?php echo stripslashes($row['zone']); ?></b></td>
						<td colspan="10"></td>
					</tr>
                <?php
                $mysql_transdate = date('Y-m-d',strtotime($trans_date));
				$current_billing_period_year = date('Y',strtotime($trans_date));
                 $get_dailytrans = $this->my_model->get_metercustomer_records($mysql_transdate,$row['id'], isset($grouping) ? (int)$grouping : 1, isset($cashier) ? (int)$cashier : 0);
                 $total_grand_zone = 0;
                 $total_current_zone = 0;
				 $total_arrears_zone = 0;
                 $total_penalty_zone = 0;
                 $total_wmmf_zone = 0;
                 $total_leaking_zone = 0;
                 $total_ar_leaking_zone = 0;
                 $total_ar_leaking_balance_zone = 0;
                 $total_sc_zone = 0;
                 $total_franchise_fee_zone = 0;

                 foreach($get_dailytrans as $key => $gdailytrans){ 
                    //$gross_total = $gdailytrans['grand_total'] + $gdailytrans['vat_amount'];
                    //$penalty = $gross_total - $gdailytrans['reading_amount'];
					$penalty = $gdailytrans['amount']-$gdailytrans['per_unit'];
					$current = 0;
					$arrears = 0;
                    if($penalty<=0){
                        $penalty = 0;
						$current = $gdailytrans['per_unit'];
                    }else{
						$arrears = $gdailytrans['per_unit'];
					}

					$prev_year = get_customer_unpaid_records($gdailytrans['customer_id'],'12',$current_billing_period_year-1);
                    //$prev_year = 600;
					// If billing period is already arrears, do not break down penalty:
					// move penalty into arrears and show 0 on penalty column.
					// Use arrears_amount from the query when > 0 (matches SQL; works if due_date is NULL on some servers).
					$penalty_val = (float)$gdailytrans['total_penalty'];
					$arrears_val = (float)$gdailytrans['arrears_amount'];
					$is_billing_period_arrears = false;
					if($penalty_val > 0 && $arrears_val > 0){
						$is_billing_period_arrears = true;
					} elseif($penalty_val > 0 && isset($gdailytrans['due_date']) && $gdailytrans['due_date'] != ''){
						$pay_ts = strtotime($gdailytrans['date']);
						$due_ts = strtotime($gdailytrans['due_date']);
						if($pay_ts && $due_ts){
							$is_billing_period_arrears = (date('m', $pay_ts) != date('m', $due_ts)) || (date('Y', $pay_ts) != date('Y', $due_ts));
						}
					}
					$display_arrears_amount = $gdailytrans['arrears_amount'];
					$display_penalty_amount = $gdailytrans['total_penalty'];
					if($is_billing_period_arrears){
						$display_arrears_amount = $display_arrears_amount + $display_penalty_amount;
						$display_penalty_amount = 0;
					}
					$ar_leaking = 0;
                    if($gdailytrans['leaking_amount']>0){
						$ornumber_search = sprintf('%07d',$gdailytrans['or_number']);
						$ar_leaking = $this->leakingentry_model->get_soa_statement_OR($ornumber_search);

						$gdailytrans['grand_total'] = $gdailytrans['grand_total']-$ar_leaking['leaking_balance'];
					}
					//print_r($ar_leaking['leaking_total_amount']);
                    echo '<tr>';
                    echo '<td>'.sprintf('%07d',$gdailytrans['or_number']).'</td><td>'.$gdailytrans['last_name'].', '.$gdailytrans['first_name'].' '.$gdailytrans['middle_name'].'</td>
                    <td align="right">'.number_format($gdailytrans['grand_total'],2).'</td>
                    <td align="right">'.number_format( $gdailytrans['current_amount'],2).'</td>
                    <td align="right">'.number_format($display_arrears_amount,2).'</td>
                    <td align="right">'. number_format($gdailytrans['total_wmmf'],2).'</td>
                    <td align="right">'. number_format($display_penalty_amount,2).'</td>
                    <td align="right">'.number_format($gdailytrans['sc_discount'],2).'</td>
                    <td align="right">'.number_format($gdailytrans['leaking_amount'],2).'</td>
                    <td align="right">'.number_format($ar_leaking['leaking_total_amount'],2).'</td>
                    <td align="right">'.number_format($ar_leaking['leaking_balance'],2).'</td>
                    <td align="right">'.number_format(isset($gdailytrans['total_franchise_fee']) ? $gdailytrans['total_franchise_fee'] : 0,2).'</td>
                    ';
                    echo '</tr>';
                    $total_grand_zone += $gdailytrans['grand_total'];
                    $total_current_zone += $gdailytrans['current_amount'];
					$total_arrears_zone += $display_arrears_amount;
                    $total_wmmf_zone += $gdailytrans['total_wmmf'];
                    $total_penalty_zone += $display_penalty_amount;
                    $total_leaking_zone +=$gdailytrans['leaking_amount'];
                    $total_sc_zone +=$gdailytrans['sc_discount'];
					$total_ar_leaking_zone+=$ar_leaking['leaking_total_amount'];
					$total_ar_leaking_balance_zone+=$ar_leaking['leaking_balance'];
					$total_franchise_fee_zone += isset($gdailytrans['total_franchise_fee']) ? $gdailytrans['total_franchise_fee'] : 0;
                 }
                 echo '<tr><td></td><th>TOTAL</th><th style="text-align:right">'.number_format($total_grand_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_current_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_arrears_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_wmmf_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_penalty_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_sc_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_leaking_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_ar_leaking_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_ar_leaking_balance_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_franchise_fee_zone,2).'</th>
                 </tr>';
                ?>
				<?php  
                
                    $cr += $total_grand_zone; 
                    $grand_total_current += $total_current_zone; 
                    $grand_total_arrears += $total_arrears_zone; 
                    $grand_total_wmmf += $total_wmmf_zone;
                    $grand_total_penalty += $total_penalty_zone;
                    $grand_total_leaking += $total_leaking_zone;
                    $grand_total_sc += $total_sc_zone;
            		$grand_total_ar_leaking += $total_ar_leaking_zone;
					$grand_total_ar_leaking_balance+=$total_ar_leaking_balance_zone;
					$grand_total_franchise_fee += $total_franchise_fee_zone;
                } 
                ?>
                <?php
					$orphan_dailytrans = isset($orphan_record) && is_array($orphan_record) ? $orphan_record : array();
					if(count($orphan_dailytrans) > 0){
				?>
					<tr>
						<td></td>
						<td><b>UNASSIGNED / MISSING CUSTOMER</b></td>
						<td colspan="10"></td>
					</tr>
				<?php
						$total_grand_zone = 0;
						$total_current_zone = 0;
						$total_arrears_zone = 0;
						$total_penalty_zone = 0;
						$total_wmmf_zone = 0;
						$total_leaking_zone = 0;
						$total_ar_leaking_zone = 0;
						$total_ar_leaking_balance_zone = 0;
						$total_sc_zone = 0;
						$total_franchise_fee_zone = 0;

						foreach($orphan_dailytrans as $key => $gdailytrans){
							$display_arrears_amount = $gdailytrans['arrears_amount'];
							$display_penalty_amount = $gdailytrans['total_penalty'];
							$is_billing_period_arrears = false;
							$penalty_val = (float)$gdailytrans['total_penalty'];
							$arrears_val = (float)$gdailytrans['arrears_amount'];
							if($penalty_val > 0 && $arrears_val > 0){
								$is_billing_period_arrears = true;
							} elseif($penalty_val > 0 && isset($gdailytrans['due_date']) && $gdailytrans['due_date'] != ''){
								$pay_ts = strtotime($gdailytrans['date']);
								$due_ts = strtotime($gdailytrans['due_date']);
								if($pay_ts && $due_ts){
									$is_billing_period_arrears = (date('m', $pay_ts) != date('m', $due_ts)) || (date('Y', $pay_ts) != date('Y', $due_ts));
								}
							}
							if($is_billing_period_arrears){
								$display_arrears_amount = $display_arrears_amount + $display_penalty_amount;
								$display_penalty_amount = 0;
							}

							$ar_leaking = 0;
							if($gdailytrans['leaking_amount']>0){
								$ornumber_search = sprintf('%07d',$gdailytrans['or_number']);
								$ar_leaking = $this->leakingentry_model->get_soa_statement_OR($ornumber_search);
								$gdailytrans['grand_total'] = $gdailytrans['grand_total']-$ar_leaking['leaking_balance'];
							}

							echo '<tr>';
							echo '<td>'.sprintf('%07d',$gdailytrans['or_number']).'</td><td>'.$gdailytrans['last_name'].', '.$gdailytrans['first_name'].' '.$gdailytrans['middle_name'].' ['.$gdailytrans['customer_id'].']</td>
							<td align="right">'.number_format($gdailytrans['grand_total'],2).'</td>
							<td align="right">'.number_format($gdailytrans['current_amount'],2).'</td>
							<td align="right">'.number_format($display_arrears_amount,2).'</td>
							<td align="right">'. number_format($gdailytrans['total_wmmf'],2).'</td>
							<td align="right">'. number_format($display_penalty_amount,2).'</td>
							<td align="right">'.number_format($gdailytrans['sc_discount'],2).'</td>
							<td align="right">'.number_format($gdailytrans['leaking_amount'],2).'</td>
							<td align="right">'.number_format($ar_leaking['leaking_total_amount'],2).'</td>
							<td align="right">'.number_format($ar_leaking['leaking_balance'],2).'</td>
							<td align="right">'.number_format(isset($gdailytrans['total_franchise_fee']) ? $gdailytrans['total_franchise_fee'] : 0,2).'</td>
							';
							echo '</tr>';

							$total_grand_zone += $gdailytrans['grand_total'];
							$total_current_zone += $gdailytrans['current_amount'];
							$total_arrears_zone += $display_arrears_amount;
							$total_wmmf_zone += $gdailytrans['total_wmmf'];
							$total_penalty_zone += $display_penalty_amount;
							$total_leaking_zone +=$gdailytrans['leaking_amount'];
							$total_sc_zone +=$gdailytrans['sc_discount'];
							$total_ar_leaking_zone+=$ar_leaking['leaking_total_amount'];
							$total_ar_leaking_balance_zone+=$ar_leaking['leaking_balance'];
							$total_franchise_fee_zone += isset($gdailytrans['total_franchise_fee']) ? $gdailytrans['total_franchise_fee'] : 0;
						}

						echo '<tr><td></td><th>TOTAL</th><th style="text-align:right">'.number_format($total_grand_zone,2).'</th>
						<th style="text-align:right">'.number_format($total_current_zone,2).'</th>
						<th style="text-align:right">'.number_format($total_arrears_zone,2).'</th>
						<th style="text-align:right">'.number_format($total_wmmf_zone,2).'</th>
						<th style="text-align:right">'.number_format($total_penalty_zone,2).'</th>
						<th style="text-align:right">'.number_format($total_sc_zone,2).'</th>
						<th style="text-align:right">'.number_format($total_leaking_zone,2).'</th>
						<th style="text-align:right">'.number_format($total_ar_leaking_zone,2).'</th>
						<th style="text-align:right">'.number_format($total_ar_leaking_balance_zone,2).'</th>
						<th style="text-align:right">'.number_format($total_franchise_fee_zone,2).'</th>
						</tr>';

						$cr += $total_grand_zone;
						$grand_total_current += $total_current_zone;
						$grand_total_arrears += $total_arrears_zone;
						$grand_total_wmmf += $total_wmmf_zone;
						$grand_total_penalty += $total_penalty_zone;
						$grand_total_leaking += $total_leaking_zone;
						$grand_total_sc += $total_sc_zone;
						$grand_total_ar_leaking += $total_ar_leaking_zone;
						$grand_total_ar_leaking_balance += $total_ar_leaking_balance_zone;
						$grand_total_franchise_fee += $total_franchise_fee_zone;
					}
				?>
                 <?php } ?>


                
                <tr>
					<td></td>
					<td><b>LEAKING A/R PAYMENT REPORT</b></td>
					<td colspan="10"></td>
				</tr>
				

				
                <?php
				$mysql_transdate1 = date('Y-m-d',strtotime($trans_date));
				$get_dailytrans1 = $this->leakingentry_model->get_soa_statement_transdate($mysql_transdate1);
				$total_leaking_ar = 0;
				//print_r($get_dailytrans1);
				foreach($get_dailytrans1 as $key => $gdailytrans1){
					
					echo '<tr>
					<td>'.$gdailytrans1['leakingledgerdetails_source_type'].'#'.$gdailytrans1['leakingledgerdetails_or_number'].'</td>
					<td>'.$gdailytrans1['last_name'].', '.$gdailytrans1['first_name'].'</td>
					<td  style="text-align:right">'.number_format($gdailytrans1['leakingledgerdetails_amount'],2).'</td>
					<td colspan="6"></td>
					<td style="text-align:right">'.number_format($gdailytrans1['leaking_total_amount'],2).'</td>
					<td style="text-align:right">'.number_format($gdailytrans1['leakingledgerdetails_balance'],2).'</td>
					<td></td>
					</tr>';
					$total_leaking_ar+=$gdailytrans1['leakingledgerdetails_amount'];
				}

				echo '<tr><td></td><th>TOTAL</th><th style="text-align:right">'.number_format($total_leaking_ar,2).'</th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 </tr>';
				 $cr+=$total_leaking_ar;
				?>
				
                <tr>
					<th></th>
					<th>Grand Total</th>
                    <th style="text-align:right"><?php echo number_format($cr,2);?></th>
                    <th style="text-align:right"><?php echo number_format($grand_total_current,2);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_arrears,2);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_wmmf,2);?></th>
					
					<th style="text-align:right"><?php echo number_format($grand_total_penalty,2);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_sc,2);?></th>
                    <th style="text-align:right"><?php echo number_format($grand_total_leaking,2);?></th>
                    <th style="text-align:right"><?php echo number_format($grand_total_ar_leaking,2);?></th>
                    <th style="text-align:right"><?php echo number_format($grand_total_ar_leaking_balance,2);?></th>
                    <th style="text-align:right"><?php echo number_format($grand_total_franchise_fee,2);?></th>
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
									