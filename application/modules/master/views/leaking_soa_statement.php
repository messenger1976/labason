<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>LEAKING - STATEMENT OF ACCOUNT</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<style>
		@page { size: A4; margin: 12mm; }
		* { box-sizing: border-box; }
		body {
			margin: 0;
			padding: 16px;
			background: #fff;
			color: #000;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
			line-height: 1.35;
		}
		.doc { max-width: 800px; margin: 0 auto; }
		.header { text-align: center; margin-bottom: 8px; }
		.header img { max-width: 100%; height: auto; max-height: 95px; width: auto; }
		.doc-title {
			text-align: center;
			font-size: 16px;
			font-weight: bold;
			margin: 12px 0 14px;
			letter-spacing: 0.3px;
		}
		.info-table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 16px;
			font-size: 13px;
		}
		.info-table td {
			padding: 4px 8px 4px 0;
			vertical-align: top;
			width: 50%;
		}
		.info-table u { text-decoration: underline; }
		.txn-table {
			width: 100%;
			border-collapse: collapse;
			font-size: 12px;
			margin-top: 4px;
		}
		.txn-table thead th {
			border-top: 1px solid #000;
			border-bottom: 1px solid #000;
			padding: 6px 4px;
			font-weight: bold;
		}
		.txn-table tbody td {
			padding: 5px 4px;
			border-bottom: none;
		}
		.txn-table .total-row th {
			border-top: 1px solid #000;
			padding: 8px 4px;
			font-weight: bold;
		}
		.th-left { text-align: left; }
		.th-center { text-align: center; }
		.th-right { text-align: right; }
		.sig-wrap { margin-top: 48px; width: 100%; }
		.sig-table { width: 100%; border-collapse: collapse; font-size: 12px; }
		.sig-table td { vertical-align: top; padding: 0; }
		.sig-spacer { height: 56px; }
		.print-meta { text-align: right; font-size: 11px; margin-top: 8px; }
		@media print {
			body { padding: 0; }
			.no-print { display: none !important; }
		}
	</style>
</head>
<body onload="window.print()">
<?php
	$customer_name = '';
	$customer_address = '';
	$meter_number = '';
	$customer_id = isset($record['leaking_customer_id']) ? $record['leaking_customer_id'] : '';
	if (!empty($customer_info) && is_array($customer_info)) {
		$customer_name = strtoupper(trim(
			(isset($customer_info['last_name']) ? $customer_info['last_name'] : '') . ', ' .
			(isset($customer_info['first_name']) ? $customer_info['first_name'] : '')
		));
		$customer_address = strtoupper(isset($customer_info['address']) ? $customer_info['address'] : '');
		$meter_number = isset($customer_info['meter_number']) ? $customer_info['meter_number'] : '';
	}

	$billing_period = '';
	if (!empty($customer_reading['month'])) {
		$month_row = getMonthName($customer_reading['month']);
		if (!empty($month_row[0]->month_name)) {
			$billing_period = $month_row[0]->month_name . ' ' . (isset($customer_reading['year']) ? $customer_reading['year'] : '');
		}
	}

	$bill_amount = isset($record['leaking_bill_amount']) ? (float)$record['leaking_bill_amount'] : 0;
	$leaking_discount = isset($record['leaking_discount_amount']) ? (float)$record['leaking_discount_amount'] : 0;
	$header_leaking_amount = isset($record['leaking_total_amount']) ? (float)$record['leaking_total_amount'] : 0;
	$computed_leaking_amount = $bill_amount - $leaking_discount;
	$leaking_amount = $computed_leaking_amount > 0 ? $computed_leaking_amount : $header_leaking_amount;
	$leaking_balance = isset($record['leaking_balance']) ? (float)$record['leaking_balance'] : 0;
	$refno = isset($record['leaking_refno']) ? $record['leaking_refno'] : '';
	$details = (!empty($record_details) && is_array($record_details)) ? $record_details : array();

	$preparedby_name = '';
	$verifiedby_name = '';
	$approvedby_name = '';
	$preparedby_title = '';
	$verifiedby_title = '';
	$approvedby_title = '';
	if (!empty($preparedby[0]) && is_array($preparedby[0])) {
		$preparedby_name = strtoupper(trim($preparedby[0]['first_name'].' '.$preparedby[0]['middle_name'].' '.$preparedby[0]['last_name']));
		$preparedby_title = isset($preparedby[0]['jobtitle']) ? $preparedby[0]['jobtitle'] : '';
	}
	if (!empty($verifiedby[0]) && is_array($verifiedby[0])) {
		$verifiedby_name = strtoupper(trim($verifiedby[0]['first_name'].' '.$verifiedby[0]['middle_name'].' '.$verifiedby[0]['last_name']));
		$verifiedby_title = isset($verifiedby[0]['jobtitle']) ? $verifiedby[0]['jobtitle'] : '';
	}
	if (!empty($approvedby[0]) && is_array($approvedby[0])) {
		$approvedby_name = strtoupper(trim($approvedby[0]['first_name'].' '.$approvedby[0]['middle_name'].' '.$approvedby[0]['last_name']));
		$approvedby_title = isset($approvedby[0]['jobtitle']) ? $approvedby[0]['jobtitle'] : '';
	}
?>
	<div class="doc">
		<div class="header">
			<img src="<?php echo site_url(); ?>images/mroxas-logo-report.jpg" alt="Labason Water District" height="80" />
		</div>

		<div class="doc-title">LEAKING - STATEMENT OF ACCOUNT</div>

		<table class="info-table">
			<tr>
				<td>CUSTOMER NAME: <u><?php echo htmlspecialchars($customer_name); ?></u></td>
				<td>CUSTOMER ID: <u><?php echo htmlspecialchars($customer_id); ?></u></td>
			</tr>
			<tr>
				<td>ADDRESS: <u><?php echo htmlspecialchars($customer_address); ?></u></td>
				<td>METER #: <u><?php echo htmlspecialchars($meter_number); ?></u></td>
			</tr>
			<tr>
				<td>BILLING PERIOD: <u><?php echo htmlspecialchars($billing_period); ?></u></td>
				<td>BILLING AMOUNT: <u><?php echo number_format($bill_amount, 2); ?></u></td>
			</tr>
			<tr>
				<td>LEAKING DISCOUNT: <u><?php echo number_format($leaking_discount, 2); ?></u></td>
				<td>LEAKING AMOUNT: <u><?php echo number_format($leaking_amount, 2); ?></u></td>
			</tr>
			<tr>
				<td>LEAKING BALANCE: <u><?php echo number_format($leaking_balance, 2); ?></u></td>
				<td>REFNO: <u><?php echo htmlspecialchars($refno); ?></u></td>
			</tr>
		</table>

		<table class="txn-table">
			<thead>
				<tr>
					<th class="th-left" style="width:8%;">SN #</th>
					<th class="th-left" style="width:16%;">Date</th>
					<th class="th-center" style="width:18%;">OR/SI #</th>
					<th class="th-left">Remarks</th>
					<th class="th-right" style="width:16%;">Amount</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$index = 0;
				$total_amount = 0;
				foreach ($details as $gdailytrans) {
					$index++;
					$amt = isset($gdailytrans['leakingledgerdetails_amount']) ? (float)$gdailytrans['leakingledgerdetails_amount'] : 0;
					$total_amount += $amt;
					$or_si = stripslashes(
						(isset($gdailytrans['leakingledgerdetails_source_type']) ? $gdailytrans['leakingledgerdetails_source_type'] : '') .
						'#' .
						(isset($gdailytrans['leakingledgerdetails_or_number']) ? $gdailytrans['leakingledgerdetails_or_number'] : '')
					);
					$remarks = isset($gdailytrans['leakingledgerdetails_remarks']) ? stripslashes($gdailytrans['leakingledgerdetails_remarks']) : '';
					$transdate = isset($gdailytrans['leakingledgerdetails_transdate']) ? $gdailytrans['leakingledgerdetails_transdate'] : '';
			?>
				<tr>
					<td><?php echo $index; ?></td>
					<td><?php echo htmlspecialchars($transdate); ?></td>
					<td class="th-center"><?php echo htmlspecialchars($or_si); ?></td>
					<td><?php echo htmlspecialchars($remarks); ?></td>
					<td class="th-right"><?php echo number_format($amt, 2); ?></td>
				</tr>
			<?php } ?>
				<tr class="total-row">
					<th colspan="3"></th>
					<th class="th-right">TOTAL</th>
					<th class="th-right"><?php echo number_format($total_amount, 2); ?></th>
				</tr>
			</tbody>
		</table>

		<div class="sig-wrap">
			<table class="sig-table">
				<tr>
					<td style="width:40%;">Prepared by:</td>
					<td style="width:20%;"></td>
					<td style="width:40%;">Verified by:</td>
				</tr>
				<tr><td colspan="3" class="sig-spacer">&nbsp;</td></tr>
				<tr>
					<td style="font-weight:bold; border-bottom:1px solid #000; text-align:center;">
						<?php echo htmlspecialchars($preparedby_name); ?>&nbsp;
					</td>
					<td></td>
					<td style="font-weight:bold; border-bottom:1px solid #000; text-align:center;">
						<?php echo htmlspecialchars($verifiedby_name); ?>&nbsp;
					</td>
				</tr>
				<tr>
					<td style="text-align:center;"><?php echo htmlspecialchars($preparedby_title); ?></td>
					<td></td>
					<td style="text-align:center;"><?php echo htmlspecialchars($verifiedby_title); ?></td>
				</tr>
				<tr><td colspan="3" class="sig-spacer">&nbsp;</td></tr>
				<tr>
					<td>Approved by:</td>
					<td></td>
					<td></td>
				</tr>
				<tr><td colspan="3" class="sig-spacer">&nbsp;</td></tr>
				<tr>
					<td style="font-weight:bold; border-bottom:1px solid #000; text-align:center;">
						<?php echo htmlspecialchars($approvedby_name); ?>&nbsp;
					</td>
					<td></td>
					<td class="print-meta">Date/Time printed: <?php echo date('Y-m-d H:i:s'); ?></td>
				</tr>
				<tr>
					<td style="text-align:center;"><?php echo htmlspecialchars($approvedby_title); ?></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>
