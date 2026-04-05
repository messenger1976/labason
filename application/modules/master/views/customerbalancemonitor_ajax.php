<div class="row">
	<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
		<?php if (isset($grand_total_balance)) { ?>
		<p class="text-right" style="font-size:15px; margin-bottom:10px;">
			<strong>Sum of displayed balances (excl. active billing period):</strong>
			<span class="txt-color-blueDark"><?php echo number_format((float) $grand_total_balance, 2); ?></span>
		</p>
		<?php } ?>
	</div>
</div>
<div class="table-responsive">
	<table id="balance_monitor_table" class="table table-bordered table-striped">
		<thead>
			<tr>
				<th data-hide="phone">#</th>
				<th data-hide="phone">Customer ID</th>
				<th data-hide="phone">Name</th>
				<th data-hide="phone">Address</th>
				<th data-hide="phone">Zone</th>
				<th class="text-right">Balance (SOA excl. current period)</th>
				<th data-hide="phone">Statement</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if (isset($record) && is_array($record) && count($record) > 0) {
				$index = 1;
				foreach ($record as $row) {
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
					<a href="<?php echo htmlspecialchars($stmt_url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener" class="btn btn-xs btn-default">Open SOA</a>
				</td>
			</tr>
			<?php
					$index++;
				}
			} else {
			?>
			<tr>
				<td colspan="7" class="text-center">No records found</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
