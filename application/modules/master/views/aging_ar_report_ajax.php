<?php
	$records = (isset($record) && is_array($record)) ? $record : array();
	$index = 1;
	$grand_total_current = 0;
	$grand_total_30days = 0;
	$grand_total_60days = 0;
	$grand_total_90days = 0;
	$grand_total_120days = 0;
	$grand_total_150daysup = 0;
	$grand_total_amount = 0;
?>
<div class="table-responsive">
	<table id="dt_aging_ar" class="table table-bordered table-hover table-striped w-100">
		<thead class="bg-primary-600">
			<tr>
				<th>SN#</th>
				<th>Customer Name</th>
				<th>Customer ID</th>
				<th>Meter Number</th>
				<th>Zone</th>
				<th class="text-right">Current</th>
				<th class="text-right">30 Days</th>
				<th class="text-right">60 Days</th>
				<th class="text-right">90 Days</th>
				<th class="text-right">120 Days</th>
				<th class="text-right">150 Days Up</th>
				<th class="text-right">Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($records) > 0) { ?>
				<?php foreach ($records as $row) {
					$name = trim(trim($row['last_name']).', '.trim($row['first_name']).' '.trim($row['middle_name']));
					$current = isset($row['current']) ? (float) $row['current'] : 0;
					$d30 = isset($row['30-days']) ? (float) $row['30-days'] : 0;
					$d60 = isset($row['60-days']) ? (float) $row['60-days'] : 0;
					$d90 = isset($row['90-days']) ? (float) $row['90-days'] : 0;
					$d120 = isset($row['120-days']) ? (float) $row['120-days'] : 0;
					$d150 = isset($row['150-DaysUp']) ? (float) $row['150-DaysUp'] : 0;
					$total = isset($row['total_balance']) ? (float) $row['total_balance'] : 0;
				?>
				<tr>
					<td><?php echo $index; ?></td>
					<td><?php echo htmlspecialchars(stripslashes($name)); ?></td>
					<td><?php echo htmlspecialchars(stripslashes($row['customer_id'])); ?></td>
					<td><?php echo htmlspecialchars(stripslashes($row['meter_number'])); ?></td>
					<td><?php echo htmlspecialchars(stripslashes($row['zone'])); ?></td>
					<td class="text-right"><?php echo number_format($current, 2); ?></td>
					<td class="text-right"><?php echo number_format($d30, 2); ?></td>
					<td class="text-right"><?php echo number_format($d60, 2); ?></td>
					<td class="text-right"><?php echo number_format($d90, 2); ?></td>
					<td class="text-right"><?php echo number_format($d120, 2); ?></td>
					<td class="text-right"><?php echo number_format($d150, 2); ?></td>
					<td class="text-right"><?php echo number_format($total, 2); ?></td>
				</tr>
				<?php
					$grand_total_current += $current;
					$grand_total_30days += $d30;
					$grand_total_60days += $d60;
					$grand_total_90days += $d90;
					$grand_total_120days += $d120;
					$grand_total_150daysup += $d150;
					$grand_total_amount += $total;
					$index++;
				} ?>
			<?php } else { ?>
				<tr>
					<td colspan="12" class="text-center py-4">No records found for the selected filters.</td>
				</tr>
			<?php } ?>
		</tbody>
		<?php if (count($records) > 0) { ?>
		<tfoot>
			<tr>
				<th colspan="5" class="text-right">GRAND TOTAL</th>
				<th class="text-right"><?php echo number_format($grand_total_current, 2); ?></th>
				<th class="text-right"><?php echo number_format($grand_total_30days, 2); ?></th>
				<th class="text-right"><?php echo number_format($grand_total_60days, 2); ?></th>
				<th class="text-right"><?php echo number_format($grand_total_90days, 2); ?></th>
				<th class="text-right"><?php echo number_format($grand_total_120days, 2); ?></th>
				<th class="text-right"><?php echo number_format($grand_total_150daysup, 2); ?></th>
				<th class="text-right"><?php echo number_format($grand_total_amount, 2); ?></th>
			</tr>
		</tfoot>
		<?php } ?>
	</table>
</div>
