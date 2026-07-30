<?php
	$records = (isset($record) && is_array($record)) ? $record : array();
	$index = 1;
?>
<?php if (isset($grand_total_balance)) { ?>
<p class="text-right mb-3">
	<strong>Sum of displayed balances (excl. active billing period):</strong>
	<span class="color-primary-500"><?php echo number_format((float) $grand_total_balance, 2); ?></span>
</p>
<?php } ?>
<div class="table-responsive">
	<table id="balance_monitor_table" class="table table-bordered table-hover table-striped w-100">
		<thead class="bg-primary-600">
			<tr>
				<th>#</th>
				<th>Customer ID</th>
				<th>Name</th>
				<th>Address</th>
				<th>Zone</th>
				<th class="text-right">Balance (SOA excl. current period)</th>
				<th>Statement</th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($records) > 0) { ?>
				<?php foreach ($records as $row) {
					$cid = isset($row['customer_id']) ? htmlspecialchars($row['customer_id'], ENT_QUOTES, 'UTF-8') : '';
					$bal = isset($row['total_balance']) ? (float) $row['total_balance'] : 0;
					$bal_class = ($bal > 0.005) ? 'text-danger' : (($bal < -0.005) ? 'text-success' : '');
					$name = trim(
						(isset($row['first_name']) ? $row['first_name'] : '') . ' ' .
						(isset($row['middle_name']) ? $row['middle_name'] : '') . ' ' .
						(isset($row['last_name']) ? $row['last_name'] : '')
					);
					$stmt_url = base_url() . 'master/statementofaccount/index/' . rawurlencode(isset($row['customer_id']) ? $row['customer_id'] : '');
				?>
				<tr>
					<td><?php echo $index; ?></td>
					<td><?php echo $cid; ?></td>
					<td><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></td>
					<td><?php echo isset($row['address']) ? htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
					<td><?php echo isset($row['zone_name']) ? htmlspecialchars($row['zone_name'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
					<td class="text-right <?php echo $bal_class; ?>"><?php echo number_format($bal, 2); ?></td>
					<td>
						<a href="<?php echo htmlspecialchars($stmt_url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">Open SOA</a>
					</td>
				</tr>
				<?php
					$index++;
				} ?>
			<?php } else { ?>
				<tr>
					<td colspan="7" class="text-center py-4">No records found</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
