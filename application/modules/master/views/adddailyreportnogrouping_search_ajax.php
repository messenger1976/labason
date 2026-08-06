<?php
	$records = (isset($record) && is_array($record)) ? $record : array();
	$leaking_records = (isset($leaking_record) && is_array($leaking_record)) ? $leaking_record : array();
	$total_amount = 0;
	$has_rows = (count($records) > 0 || count($leaking_records) > 0);
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
			<?php if ($has_rows) { ?>
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
					// Only for payments with leaking discount: show actual amount collected on this OR
					if (isset($row['leaking_amount']) && (float) $row['leaking_amount'] > 0) {
						$amount = $this->leakingentry_model->get_collected_amount_for_leaking_payment(
							$amount,
							isset($row['pay_amount']) ? $row['pay_amount'] : 0,
							isset($row['or_number']) ? $row['or_number'] : null
						);
					}
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
				<?php foreach ($leaking_records as $lrow) {
					$src = isset($lrow['leakingledgerdetails_source_type']) && $lrow['leakingledgerdetails_source_type'] !== ''
						? $lrow['leakingledgerdetails_source_type']
						: 'OR';
					$ref = isset($lrow['leakingledgerdetails_or_number']) ? $lrow['leakingledgerdetails_or_number'] : '';
					$or_disp = $src . '#' . $ref;
					$last = isset($lrow['last_name']) ? $lrow['last_name'] : '';
					$first = isset($lrow['first_name']) ? $lrow['first_name'] : '';
					$name = trim($last . ', ' . $first);
					if ($name === ',' || $name === '') {
						$name = 'Leaking A/R Payment';
					}
					$date_raw = isset($lrow['leakingledgerdetails_transdate']) ? $lrow['leakingledgerdetails_transdate'] : '';
					$date_disp = $date_raw !== '' ? date('m/d/Y', strtotime($date_raw)) : '';
					$amount = isset($lrow['leakingledgerdetails_amount']) ? (float) $lrow['leakingledgerdetails_amount'] : 0;
					$total_amount += $amount;
				?>
				<tr>
					<td><?php echo htmlspecialchars($or_disp); ?></td>
					<td>LEAKING A/R</td>
					<td><?php echo htmlspecialchars(stripslashes($name)); ?></td>
					<td><?php echo htmlspecialchars($date_disp); ?></td>
					<td class="text-right" data-order="<?php echo htmlspecialchars((string) $amount); ?>"><?php echo number_format($amount, 2); ?></td>
					<td>Leaking Entry</td>
				</tr>
				<?php } ?>
			<?php } else { ?>
				<tr>
					<td colspan="6" class="text-center py-4">No records found for the selected date.</td>
				</tr>
			<?php } ?>
		</tbody>
		<?php if ($has_rows) { ?>
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
