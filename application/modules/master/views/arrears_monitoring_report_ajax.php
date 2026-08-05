<?php
	$records = (isset($record) && is_array($record)) ? $record : array();
	$index = 1;
	$grand_total_aging = 0;
	$grand_total_arrears = 0;
?>
<div class="table-responsive">
	<table id="dt_arrears_monitoring" class="table table-bordered table-hover table-striped w-100">
		<thead class="bg-primary-600">
			<tr>
				<th>SN#</th>
				<th>Customer ID</th>
				<th>Customer Name</th>
				<th>Meter Number</th>
				<th>Zone</th>
				<th class="text-right">Aging Amount</th>
				<th class="text-right">Current Billing Period Arrears</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($records) > 0) { ?>
				<?php foreach ($records as $row) {
					$aging_amount = isset($row['total_balance']) ? (float) $row['total_balance'] : 0;
					$current_arr = isset($row['current_arrears']) ? (float) $row['current_arrears'] : 0;
					$is_mismatch = abs($aging_amount - $current_arr) > 0.009;
					$name = trim(trim($row['last_name']).', '.trim($row['first_name']).' '.trim($row['middle_name']));
				?>
				<tr class="<?php echo $is_mismatch ? 'arrears-mismatch' : ''; ?>">
					<td><?php echo $index; ?></td>
					<td><?php echo htmlspecialchars(stripslashes($row['customer_id'])); ?></td>
					<td><?php echo htmlspecialchars(stripslashes($name)); ?></td>
					<td><?php echo htmlspecialchars(stripslashes($row['meter_number'])); ?></td>
					<td><?php echo htmlspecialchars(stripslashes($row['zone'])); ?></td>
					<td class="text-right"><?php echo number_format($aging_amount, 2); ?></td>
					<td class="text-right current-arrears-cell"><?php echo number_format($current_arr, 2); ?></td>
					<td class="text-center">
						<?php if ($is_mismatch) { ?>
						<button
							type="button"
							class="btn btn-sm btn-danger btn-update-arrears"
							data-customer-id="<?php echo htmlspecialchars($row['customer_id'], ENT_QUOTES, 'UTF-8'); ?>"
							data-aging-amount="<?php echo number_format($aging_amount, 2, '.', ''); ?>"
						>
							<i class="fal fa-sync mr-1"></i> Update Arrears
						</button>
						<?php } else { ?>
						—
						<?php } ?>
					</td>
				</tr>
				<?php
					$grand_total_aging += $aging_amount;
					$grand_total_arrears += $current_arr;
					$index++;
				} ?>
			<?php } ?>
			<?php /* Leave tbody empty when no rows — DataTables requires one <td> per column (colspan breaks init). */ ?>
		</tbody>
		<?php if (count($records) > 0) { ?>
		<tfoot>
			<tr>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th class="text-right">GRAND TOTAL</th>
				<th class="text-right"><?php echo number_format($grand_total_aging, 2); ?></th>
				<th class="text-right"><?php echo number_format($grand_total_arrears, 2); ?></th>
				<th></th>
			</tr>
		</tfoot>
		<?php } ?>
	</table>
</div>
