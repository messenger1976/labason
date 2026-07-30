<?php
$total_count = isset($total_count) ? (int) $total_count : 0;
$offset = isset($offset) ? (int) $offset : 0;
$limit = isset($limit) ? (int) $limit : 100;
$has_more = !empty($has_more);
$record = isset($record) && is_array($record) ? $record : array();
$shown = count($record);
$end = $total_count > 0 ? min($offset + $shown, $total_count) : 0;
?>
<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
	<p class="text-muted mb-2 mb-md-0">
		Showing <?php echo $shown > 0 ? ($offset + 1) : 0; ?>–<?php echo $end; ?> of <?php echo $total_count; ?>
		<span class="opacity-50">(<?php echo $limit; ?> per page)</span>
	</p>
	<div class="mb-2 mb-md-0">
		<button type="button" class="btn btn-secondary btn-sm" id="cpm_prev" <?php echo ($offset < 100) ? 'disabled="disabled"' : ''; ?>>
			<i class="fal fa-chevron-left mr-1"></i> Previous 100
		</button>
		<button type="button" class="btn btn-secondary btn-sm" id="cpm_next" <?php echo $has_more ? '' : 'disabled="disabled"'; ?>>
			Next 100 <i class="fal fa-chevron-right ml-1"></i>
		</button>
	</div>
</div>
<div class="table-responsive">
	<table class="table table-bordered table-hover table-striped w-100" id="tbl_payment_monitor">
		<thead class="bg-primary-600">
			<tr>
				<th>SN#</th>
				<th>Customer ID</th>
				<th>Customer Name</th>
				<th>Address</th>
				<th>Zone</th>
				<th>OR number</th>
				<th class="text-right"># periods</th>
				<th>Billing Period Paid</th>
				<th class="text-right">Total amount paid</th>
				<th class="text-right">Arrears</th>
			</tr>
		</thead>
		<tbody>
			<?php if ($shown > 0) { ?>
				<?php
				$index = $offset + 1;
				foreach ($record as $row) {
					$cust = isset($row['customer_id']) ? htmlspecialchars($row['customer_id'], ENT_QUOTES, 'UTF-8') : '';
					$name = trim(
						(isset($row['last_name']) ? $row['last_name'] : '') . ', ' .
						(isset($row['first_name']) ? $row['first_name'] : '') . ' ' .
						(isset($row['middle_name']) ? $row['middle_name'] : '')
					);
					$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
					$addr = isset($row['address']) ? htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') : '';
					$zn = isset($row['zone_name']) ? htmlspecialchars($row['zone_name'], ENT_QUOTES, 'UTF-8') : '';
					$or = isset($row['or_number']) ? htmlspecialchars((string) $row['or_number'], ENT_QUOTES, 'UTF-8') : '';
					$pc = isset($row['period_count']) ? (int) $row['period_count'] : 0;
					$bp = isset($row['billing_periods_paid']) ? htmlspecialchars($row['billing_periods_paid'], ENT_QUOTES, 'UTF-8') : '';
					$tp = isset($row['total_paid']) ? (float) $row['total_paid'] : 0;
					$ar = isset($row['arrears']) ? (float) $row['arrears'] : 0;
					?>
					<tr>
						<td><?php echo $index; ?></td>
						<td><?php echo $cust; ?></td>
						<td><?php echo $name; ?></td>
						<td><?php echo $addr; ?></td>
						<td><?php echo $zn; ?></td>
						<td><?php echo $or; ?></td>
						<td class="text-right"><?php echo $pc; ?></td>
						<td><?php echo $bp; ?></td>
						<td class="text-right"><?php echo number_format($tp, 2); ?></td>
						<td class="text-right"><?php echo number_format($ar, 2); ?></td>
					</tr>
					<?php
					$index++;
				}
				?>
			<?php } else { ?>
				<tr>
					<td colspan="10" class="text-center py-4">No records found</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
