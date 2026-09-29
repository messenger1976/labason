<?php
/**
 * Online / QR Ph Payment report — AJAX result block (server-rendered).
 *
 * Expects: $record (rows), $totals, $total_count, $offset, $limit, $has_more
 */
$record = (isset($record) && is_array($record)) ? $record : array();
$totals = (isset($totals) && is_array($totals)) ? $totals : array();
$total_count = isset($total_count) ? (int) $total_count : count($record);
$offset = isset($offset) ? (int) $offset : 0;
$limit = isset($limit) ? (int) $limit : 100;
$has_more = !empty($has_more);

$paid_amount = isset($totals['paid_amount']) ? (float) $totals['paid_amount'] : 0;
$open_amount = isset($totals['open_amount']) ? (float) $totals['open_amount'] : 0;
$paid_count = isset($totals['paid_count']) ? (int) $totals['paid_count'] : 0;
$open_count = isset($totals['open_count']) ? (int) $totals['open_count'] : 0;
$collected = count($record);

$from = $collected > 0 ? $offset + 1 : 0;
$to = $offset + $collected;

$context_labels = array(
	'mobile'        => 'Mobile app',
	'desktop'       => 'Desktop (emailed link)',
	'customer_link' => 'Customer link',
);
?>
<div class="row mb-2">
	<div class="col-md-8">
		<div class="panel-tag mb-0">
			Showing <strong><?php echo $from; ?>–<?php echo $to; ?></strong>
			of <strong><?php echo number_format($total_count); ?></strong> QR Ph attempts for the selected filters.
			<span class="text-success fw-700">Collected ₱<?php echo number_format($paid_amount, 2); ?></span>
			(<?php echo $paid_count; ?> paid)
			&middot;
			<span class="text-danger fw-700">Outstanding ₱<?php echo number_format($open_amount, 2); ?></span>
			(<?php echo $open_count; ?> pending/expired/failed)
		</div>
	</div>
	<div class="col-md-4 text-md-right mt-2 mt-md-0">
		<button type="button" class="btn btn-sm btn-outline-secondary" id="op_prev" <?php echo $offset > 0 ? '' : 'disabled'; ?>>
			<i class="fal fa-chevron-left mr-1"></i> Newer
		</button>
		<button type="button" class="btn btn-sm btn-outline-secondary" id="op_next" <?php echo $has_more ? '' : 'disabled'; ?>>
			Older <i class="fal fa-chevron-right ml-1"></i>
		</button>
	</div>
</div>

<?php if (empty($record)) { ?>
	<div class="alert alert-info mb-0">No QR Ph payment attempts match the selected filters.</div>
<?php } else { ?>
	<div class="frame-wrap">
		<table id="tbl_online_payment" class="table table-bordered table-hover table-striped table-sm w-100">
			<thead class="bg-primary-600 bg-primary-gradient">
				<tr>
					<th style="width:44px;">S No</th>
					<th>Reference</th>
					<th>Customer ID</th>
					<th>Customer Name</th>
					<th>Zone</th>
					<th class="text-right">Amount</th>
					<th>Status</th>
					<th>Source</th>
					<th>Created</th>
					<th>Paid</th>
					<th>PayMongo Ref</th>
					<th>Collected by</th>
					<th>Emailed to</th>
					<th style="width:80px;">Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$i = $offset + 1;
			foreach ($record as $row) {
				$id = isset($row['id']) ? (int) $row['id'] : 0;
				$status = isset($row['status']) ? (string) $row['status'] : '';
				$status_label = $this->op_model->status_label($status);
				$status_class = 'badge-' . $this->op_model->status_class($status);

				$name = trim(
					(isset($row['last_name']) ? stripslashes($row['last_name']) : '') . ', ' .
					(isset($row['first_name']) ? stripslashes($row['first_name']) : '') . ' ' .
					(isset($row['middle_name']) ? stripslashes($row['middle_name']) : '')
				);
				$name = trim($name, ', ');

				$context = isset($row['context']) ? (string) $row['context'] : '';
				$context_label = isset($context_labels[$context]) ? $context_labels[$context] : ($context === '' ? '' : ucfirst($context));

				$collector = '';
				if (!empty($row['created_by'])) {
					$collector = $this->op_model->collector_name((int) $row['created_by']);
				}

				$created = !empty($row['create_date_time']) ? date('m/d/Y g:i A', strtotime($row['create_date_time'])) : '';
				$paid_at = !empty($row['paid_at']) ? date('m/d/Y g:i A', strtotime($row['paid_at'])) : '';
			?>
				<tr>
					<td><?php echo $i++; ?></td>
					<td><span class="badge badge-secondary"><?php echo htmlspecialchars(isset($row['reference_no']) ? $row['reference_no'] : '', ENT_QUOTES, 'UTF-8'); ?></span></td>
					<td><?php echo htmlspecialchars(isset($row['customer_id']) ? stripslashes($row['customer_id']) : '', ENT_QUOTES, 'UTF-8'); ?></td>
					<td><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></td>
					<td><?php echo htmlspecialchars(isset($row['zones']) ? $row['zones'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
					<td class="text-right fw-700"><?php echo number_format(isset($row['amount']) ? (float) $row['amount'] : 0, 2); ?></td>
					<td><span class="badge <?php echo htmlspecialchars($status_class, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($status_label, ENT_QUOTES, 'UTF-8'); ?></span></td>
					<td><?php echo htmlspecialchars($context_label, ENT_QUOTES, 'UTF-8'); ?></td>
					<td><span class="fs-sm"><?php echo htmlspecialchars($created, ENT_QUOTES, 'UTF-8'); ?></span></td>
					<td><span class="fs-sm"><?php echo htmlspecialchars($paid_at, ENT_QUOTES, 'UTF-8'); ?></span></td>
					<td><span class="fs-sm"><?php echo htmlspecialchars(isset($row['payment_id']) ? $row['payment_id'] : '', ENT_QUOTES, 'UTF-8'); ?></span></td>
					<td><?php echo htmlspecialchars($collector, ENT_QUOTES, 'UTF-8'); ?></td>
					<td><span class="fs-sm"><?php echo htmlspecialchars(isset($row['emailed_to']) ? $row['emailed_to'] : '', ENT_QUOTES, 'UTF-8'); ?></span></td>
					<td>
						<?php if ($status === 'paid') { ?>
							<a href="<?php echo ADMIN_URL; ?>onlinepayment/receipt/<?php echo $id; ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Print 80mm receipt" data-toggle="tooltip">
								<i class="fal fa-print"></i>
							</a>
						<?php } else { ?>
							<a href="<?php echo ADMIN_URL; ?>onlinepayment/manage" class="btn btn-sm btn-outline-info" title="Open the Online Payment list" data-toggle="tooltip">
								<i class="fal fa-list"></i>
							</a>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
			</tbody>
			<tfoot>
				<tr class="bg-faded">
					<th colspan="5" class="text-right">Totals (all filtered rows)</th>
					<th class="text-right"><?php echo number_format($paid_amount + $open_amount, 2); ?></th>
					<th colspan="8" class="fs-sm text-muted">
						Collected <?php echo number_format($paid_amount, 2); ?> &middot; Outstanding <?php echo number_format($open_amount, 2); ?>
					</th>
				</tr>
			</tfoot>
		</table>
	</div>
<?php } ?>
