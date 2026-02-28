<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Monthly Income Report Analytic - Print</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="<?php echo site_url();?>assets/css/bootstrap.min.css" rel="stylesheet" />
	<style>
		body { font-size: 12px; }
		.table>tbody>tr>td, .table>thead>tr>th { padding: 5px; }
		.text-right { text-align: right; }
		.total-row { font-weight: bold; }
		/* Bar chart for print - use pixel heights so print dialog shows bars */
		.print-chart-wrap {
			display: flex;
			align-items: flex-end;
			gap: 1px;
			height: 185px;
			max-width: 100%;
			padding-bottom: 20px;
			border-bottom: 1px solid #333;
			margin-bottom: 15px;
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
		}
		.print-bar-wrap {
			flex: 1;
			min-width: 8px;
			max-width: 18px;
			height: 185px;
			display: flex;
			flex-direction: column;
			justify-content: flex-end;
			align-items: center;
		}
		.print-bar {
			width: 100%;
			min-height: 1px;
			background-color: #5cb85c !important;
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
		}
		.print-bar-label { font-size: 9px; margin-top: 2px; color: #333; }
		.print-chart-title { font-weight: bold; margin-bottom: 8px; }
		@media print {
			.print-chart-wrap { height: 185px !important; }
			.print-bar-wrap { height: 185px !important; }
			.print-bar { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
		}
	</style>
</head>
<body onLoad="window.print()">
	<div style="text-align: center;"><img src="<?php echo site_url();?>images/mroxas-logo-report.jpg" height="80px" alt="" /></div>
	<h3 style="text-align: center;">MONTHLY INCOME REPORT ANALYTIC</h3>
	<h5 style="text-align: center;"><?php echo htmlspecialchars($month_name . ' ' . $year); ?></h5>
	<br/>
	<?php
		$max_val = 1;
		for ($d = 1; $d <= $days_in_month; $d++) {
			$v = isset($daily[$d]) ? (float) $daily[$d] : 0;
			if ($v > $max_val) $max_val = $v;
		}
		$chart_bar_max_px = 165; /* pixel height for tallest bar */
	?>
	<div class="print-chart-title">Daily Income (Bar Chart)</div>
	<div class="print-chart-wrap">
		<?php for ($d = 1; $d <= $days_in_month; $d++) {
			$amt = isset($daily[$d]) ? (float) $daily[$d] : 0;
			$bar_px = $max_val > 0 ? round(($amt / $max_val) * $chart_bar_max_px) : 0;
			if ($bar_px < 0) $bar_px = 0;
			if ($bar_px > $chart_bar_max_px) $bar_px = $chart_bar_max_px;
		?>
		<div class="print-bar-wrap">
			<div class="print-bar" style="height: <?php echo $bar_px; ?>px;"></div>
			<span class="print-bar-label"><?php echo $d; ?></span>
		</div>
		<?php } ?>
	</div>
	<div class="table-responsive">
		<table class="table table-bordered" style="font-size: 12px;">
			<thead>
				<tr>
					<th>Day</th>
					<th class="text-right">Income</th>
				</tr>
			</thead>
			<tbody>
				<?php for ($d = 1; $d <= $days_in_month; $d++) {
					$amt = isset($daily[$d]) ? (float) $daily[$d] : 0;
				?>
				<tr>
					<td><?php echo $d; ?></td>
					<td class="text-right"><?php echo number_format($amt, 2); ?></td>
				</tr>
				<?php } ?>
				<tr class="total-row">
					<td>TOTAL</td>
					<td class="text-right"><?php echo number_format($total, 2); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<p style="text-align: center; margin-top: 20px; font-size: 11px;">Generated on <?php echo date('Y-m-d H:i:s'); ?></p>
</body>
</html>
