<?php
	$records = (isset($record) && is_array($record)) ? $record : array();
	$count_no = 0;
	$count_low = 0;
	foreach ($records as $row) {
		$consumed = isset($row['consumed']) ? (float) $row['consumed'] : 0;
		if ($consumed == 0) {
			$count_no++;
		} else {
			$count_low++;
		}
	}
	$usage_type = isset($usage_type) ? $usage_type : 'both';
	$max_cu = isset($max_cu) ? $max_cu : 10;
	$usage_label = 'No consumption and low (0 to '.$max_cu.' cu.m)';
	if ($usage_type === 'no') {
		$usage_label = 'No consumption (0 cu.m)';
	} elseif ($usage_type === 'low') {
		$usage_label = 'Low consumption (1 to '.$max_cu.' cu.m)';
	}
	$zone_label = 'All zones';
	if (isset($zone) && is_array($zone) && count($zone) == 1 && isset($zone[0]['zone'])) {
		$zone_label = $zone[0]['zone'];
	}
	$emp_name = function ($rows) {
		if (!isset($rows[0])) {
			return array('name' => '', 'title' => '');
		}
		return array(
			'name' => strtoupper(trim($rows[0]['first_name'].' '.$rows[0]['middle_name'].' '.$rows[0]['last_name'])),
			'title' => isset($rows[0]['jobtitle']) ? $rows[0]['jobtitle'] : ''
		);
	};
	$prep = $emp_name(isset($preparedby) ? $preparedby : array());
	$ver = $emp_name(isset($verifiedby) ? $verifiedby : array());
	$app = $emp_name(isset($approvedby) ? $approvedby : array());
	$index = 1;
?>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Low to No Consumption</title>
	<link href="<?php echo site_url(); ?>/assets/css/bootstrap.min.css" rel="stylesheet" />
	<style>
		body { font-size: 12px; }
		.table > tbody > tr > td, .table > thead > tr > th { padding: 4px; }
		.sig { margin-top: 50px; }
		.sig div { display: inline-block; width: 32%; text-align: center; }
	</style>
</head>
<body onLoad="window.print()">
	<div style="text-align: center;"><img src="<?php echo site_url(); ?>images/mroxas-logo-report.jpg" height="80px" alt="" /></div>
	<h3 style="text-align: center;">LOW TO NO CONSUMPTION REPORT</h3>
	<h6 style="text-align: center;">
		<?php echo htmlspecialchars(isset($billingperiod_month_name) ? $billingperiod_month_name : ''); ?>
		<?php echo htmlspecialchars(isset($billingperiod_year) ? $billingperiod_year : ''); ?>
	</h6>
	<p style="text-align: center;">
		<?php echo htmlspecialchars($usage_label); ?>
		&nbsp;|&nbsp; Zone: <?php echo htmlspecialchars($zone_label); ?>
	</p>
	<table class="table table-bordered" style="font-size: smaller;" cellpadding="0">
		<thead>
			<tr>
				<th>SN#</th>
				<th>Customer ID</th>
				<th>Name</th>
				<th>Zone</th>
				<th>Classification</th>
				<th>Meter No.</th>
				<th>Previous</th>
				<th>Current</th>
				<th>Consumed</th>
				<th>Amount</th>
				<th>Ref No.</th>
				<th>Flag</th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($records) > 0) { ?>
				<?php foreach ($records as $row) {
					$consumed = isset($row['consumed']) ? (float) $row['consumed'] : 0;
					$flag = ($consumed == 0) ? 'NO CONSUMPTION' : 'LOW';
					$name = trim($row['last_name']).', '.trim($row['first_name']).' '.trim($row['middle_name']);
				?>
			<tr>
				<td><?php echo $index; ?></td>
				<td><?php echo htmlspecialchars(stripslashes($row['customer_id'])); ?></td>
				<td><?php echo htmlspecialchars(stripslashes($name)); ?></td>
				<td><?php echo htmlspecialchars(isset($row['zone']) ? stripslashes($row['zone']) : ''); ?></td>
				<td><?php echo htmlspecialchars(isset($row['class_name']) ? stripslashes($row['class_name']) : ''); ?></td>
				<td><?php echo htmlspecialchars(isset($row['meter_number']) ? stripslashes($row['meter_number']) : ''); ?></td>
				<td><?php echo htmlspecialchars(isset($row['previous_reading']) ? $row['previous_reading'] : ''); ?></td>
				<td><?php echo htmlspecialchars(isset($row['reading']) ? $row['reading'] : ''); ?></td>
				<td style="text-align:right;"><?php echo number_format($consumed, 2); ?></td>
				<td style="text-align:right;"><?php echo number_format(isset($row['amount']) ? (float) $row['amount'] : 0, 2); ?></td>
				<td><?php echo htmlspecialchars(isset($row['refno']) ? $row['refno'] : ''); ?></td>
				<td><?php echo $flag; ?></td>
			</tr>
				<?php
					$index++;
				} ?>
			<?php } else { ?>
			<tr><td colspan="12" style="text-align:center;">No records found.</td></tr>
			<?php } ?>
			<tr>
				<td colspan="8" style="text-align:right;"><strong>No consumption / Low / Total</strong></td>
				<td colspan="4"><strong><?php echo (int) $count_no; ?> / <?php echo (int) $count_low; ?> / <?php echo (int) ($count_no + $count_low); ?></strong></td>
			</tr>
		</tbody>
	</table>
	<div class="sig">
		<div>
			Prepared by:<br /><br /><br />
			<strong><?php echo htmlspecialchars($prep['name']); ?></strong><br />
			<?php echo htmlspecialchars($prep['title']); ?>
		</div>
		<div>
			Verified Correct:<br /><br /><br />
			<strong><?php echo htmlspecialchars($ver['name']); ?></strong><br />
			<?php echo htmlspecialchars($ver['title']); ?>
		</div>
		<div>
			Approved:<br /><br /><br />
			<strong><?php echo htmlspecialchars($app['name']); ?></strong><br />
			<?php echo htmlspecialchars($app['title']); ?>
		</div>
	</div>
	<p style="margin-top:20px; font-size:11px;">Date/Time printed: <?php echo date('Y-m-d H:i:s'); ?></p>
</body>
</html>
