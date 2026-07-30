<?php
	$records = (isset($record) && is_array($record)) ? $record : array();
	$index = 1;
?>
<div class="table-responsive">
	<table id="dt_customer_report" class="table table-bordered table-hover table-striped w-100">
		<thead class="bg-primary-600">
			<tr>
				<th>SN#</th>
				<th>Customer ID</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Address</th>
				<th>Zone</th>
				<th>Classification</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($records) > 0) { ?>
				<?php foreach ($records as $row) {
					$status_val = isset($row['status']) ? $row['status'] : '';
					if ($status_val == '1' || $status_val === 1) {
						$status_label = 'Active';
						$status_class = 'badge-success';
					} elseif ($status_val == '0' || $status_val === 0) {
						$status_label = 'Inactive';
						$status_class = 'badge-warning';
					} elseif ($status_val == '2' || $status_val === 2) {
						$status_label = 'Disconnected';
						$status_class = 'badge-danger';
					} else {
						$status_label = 'Unknown';
						$status_class = 'badge-secondary';
					}
				?>
				<tr>
					<td><?php echo $index; ?></td>
					<td><?php echo isset($row['customer_id']) ? htmlspecialchars($row['customer_id'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
					<td><?php echo isset($row['first_name']) ? htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
					<td><?php echo isset($row['last_name']) ? htmlspecialchars($row['last_name'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
					<td><?php echo isset($row['address']) ? htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
					<td><?php echo isset($row['zone_name']) ? htmlspecialchars($row['zone_name'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
					<td><?php echo isset($row['classification_name']) ? htmlspecialchars($row['classification_name'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
					<td><span class="badge <?php echo $status_class; ?>"><?php echo $status_label; ?></span></td>
				</tr>
				<?php $index++; } ?>
			<?php } else { ?>
				<tr>
					<td colspan="8" class="text-center py-4">No records found</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
