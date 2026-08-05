<?php
	$records = (isset($record) && is_array($record)) ? $record : array();
	$total_amount = 0;
?>
<div class="table-responsive">
	<table id="dt_daily_report" class="table table-bordered table-hover table-striped w-100">
		<thead class="bg-primary-600">
			<tr>
				<th>OR #</th>
				<th>Zone</th>
				<th>Customer Name</th>
				<th>Date</th>
				<th class="text-right">Amount</th>
				<th>Cashier</th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($records) > 0) { ?>
				<?php foreach ($records as $row) {
					$or_number = isset($row['or_number']) ? $row['or_number'] : '';
					$zone = isset($row['zone']) ? $row['zone'] : '';
					$last = isset($row['last_name']) ? $row['last_name'] : '';
					$first = isset($row['first_name']) ? $row['first_name'] : '';
					$middle = isset($row['middle_name']) ? $row['middle_name'] : '';
					$name = trim($last . ', ' . $first . ' ' . $middle);
					if ($name === ',') {
						$name = isset($row['name']) ? $row['name'] : '';
					}
					$date_raw = isset($row['date']) ? $row['date'] : '';
					$date_disp = $date_raw !== '' ? date('m/d/Y', strtotime($date_raw)) : '';
					$amount = isset($row['grand_total']) ? (float) $row['grand_total'] : 0;
					$total_amount += $amount;
					$cashier = (isset($row['user']) && $row['user'] !== '') ? $row['user'] : 'Admin';
				?>
				<tr>
					<td><?php echo htmlspecialchars(sprintf('%07d', (int) $or_number)); ?></td>
					<td><?php echo htmlspecialchars(stripslashes($zone)); ?></td>
					<td><?php echo htmlspecialchars(stripslashes($name)); ?></td>
					<td><?php echo htmlspecialchars($date_disp); ?></td>
					<td class="text-right" data-order="<?php echo htmlspecialchars((string) $amount); ?>"><?php echo number_format($amount, 2); ?></td>
					<td><?php echo htmlspecialchars($cashier); ?></td>
				</tr>
				<?php } ?>
			<?php } else { ?>
				<tr>
					<td colspan="6" class="text-center py-4">No records found for the selected date.</td>
				</tr>
			<?php } ?>
		</tbody>
		<?php if (count($records) > 0) { ?>
		<tfoot>
			<tr>
				<th colspan="4" class="text-right">Total</th>
				<th class="text-right"><?php echo number_format($total_amount, 2); ?></th>
				<th></th>
			</tr>
		</tfoot>
		<?php } ?>
	</table>
</div>
