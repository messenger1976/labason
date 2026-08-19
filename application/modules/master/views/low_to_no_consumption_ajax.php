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
	$index = 1;
?>
<div class="alert alert-info">
	<strong>No consumption:</strong> <?php echo (int) $count_no; ?>
	&nbsp;|&nbsp;
	<strong>Low:</strong> <?php echo (int) $count_low; ?>
	&nbsp;|&nbsp;
	<strong>Total:</strong> <?php echo (int) ($count_no + $count_low); ?>
</div>
<div class="table-responsive">
	<table id="dt_low_to_no" class="table table-bordered table-hover table-striped w-100">
		<thead class="bg-primary-600">
			<tr>
				<th>SN#</th>
				<th>Customer ID</th>
				<th>Customer Name</th>
				<th>Zone</th>
				<th>Classification</th>
				<th>Meter No.</th>
				<th class="text-right">Previous</th>
				<th class="text-right">Current</th>
				<th class="text-right">Consumed</th>
				<th class="text-right">Amount</th>
				<th>Ref No.</th>
				<th>Flag</th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($records) > 0) { ?>
				<?php foreach ($records as $row) {
					$consumed = isset($row['consumed']) ? (float) $row['consumed'] : 0;
					$flag = ($consumed == 0) ? 'NO CONSUMPTION' : 'LOW';
					$flag_class = ($consumed == 0) ? 'badge-danger' : 'badge-warning';
					$name = trim($row['last_name']).', '.trim($row['first_name']).' '.trim($row['middle_name']);
				?>
				<tr>
					<td><?php echo $index; ?></td>
					<td><?php echo htmlspecialchars(stripslashes($row['customer_id'])); ?></td>
					<td><?php echo htmlspecialchars(stripslashes($name)); ?></td>
					<td><?php echo htmlspecialchars(isset($row['zone']) ? stripslashes($row['zone']) : ''); ?></td>
					<td><?php echo htmlspecialchars(isset($row['class_name']) ? stripslashes($row['class_name']) : ''); ?></td>
					<td><?php echo htmlspecialchars(isset($row['meter_number']) ? stripslashes($row['meter_number']) : ''); ?></td>
					<td class="text-right"><?php echo htmlspecialchars(isset($row['previous_reading']) ? $row['previous_reading'] : ''); ?></td>
					<td class="text-right"><?php echo htmlspecialchars(isset($row['reading']) ? $row['reading'] : ''); ?></td>
					<td class="text-right"><?php echo number_format($consumed, 2); ?></td>
					<td class="text-right"><?php echo number_format(isset($row['amount']) ? (float) $row['amount'] : 0, 2); ?></td>
					<td><?php echo htmlspecialchars(isset($row['refno']) ? $row['refno'] : ''); ?></td>
					<td><span class="badge <?php echo $flag_class; ?>"><?php echo $flag; ?></span></td>
				</tr>
				<?php
					$index++;
				} ?>
			<?php } ?>
		</tbody>
	</table>
</div>
