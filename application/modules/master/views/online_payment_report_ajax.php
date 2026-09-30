<?php
/**
 * Online / QR Ph Payment report — AJAX result block (server-rendered).
 *
 * Expects: $record (rows), $totals, $total_count, $offset, $limit, $has_more
 *
 * Column order is fixed: the page script's DataTables Responsive setup reads each header's
 * data-priority (lower = kept longer on narrow screens). Column 0 is the expand control, so it
 * must stay priority 1. Every column has its own footer cell (no colspan) so Responsive can hide
 * footer cells together with their column.
 */
$record = (isset($record) && is_array($record)) ? $record : array();
$totals = (isset($totals) && is_array($totals)) ? $totals : array();
$total_count = isset($total_count) ? (int) $total_count : count($record);
$offset = isset($offset) ? (int) $offset : 0;
$limit = isset($limit) ? (int) $limit : 100;
$has_more = !empty($has_more);

$paid_amount = isset($totals['paid_amount']) ? (float) $totals['paid_amount'] : 0;
$paid_fee = isset($totals['paid_fee']) ? (float) $totals['paid_fee'] : 0;
$paid_fee_qrph = isset($totals['paid_fee_qrph']) ? (float) $totals['paid_fee_qrph'] : 0;
$paid_fee_fixed = isset($totals['paid_fee_fixed']) ? (float) $totals['paid_fee_fixed'] : 0;
$paid_charged = isset($totals['paid_charged']) ? (float) $totals['paid_charged'] : ($paid_amount + $paid_fee);
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

$summary = array(
	array('label' => 'Collected (bills)', 'value' => $paid_amount, 'class' => 'color-success-600', 'note' => $paid_count . ' paid'),
	array('label' => 'QR Ph fee', 'value' => $paid_fee_qrph, 'class' => 'color-info-600', 'note' => 'Percentage part, paid'),
	array('label' => 'Fixed fee', 'value' => $paid_fee_fixed, 'class' => 'color-info-600', 'note' => 'Flat part, paid'),
	array('label' => 'Total fees', 'value' => $paid_fee, 'class' => 'color-primary-600', 'note' => 'QR Ph + fixed'),
	array('label' => 'Total via PayMongo', 'value' => $paid_charged, 'class' => 'color-primary-700', 'note' => 'Bills + fees, paid'),
	array('label' => 'Outstanding', 'value' => $open_amount, 'class' => 'color-danger-600', 'note' => $open_count . ' pending/expired/failed'),
);
?>
<div class="op-summary row no-gutters mb-3">
	<?php foreach ($summary as $card) { ?>
	<div class="col-6 col-md-4 col-xl-2 p-1">
		<div class="op-summary-card h-100">
			<div class="op-summary-label"><?php echo htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8'); ?></div>
			<div class="op-summary-value <?php echo $card['class']; ?>">&#8369; <?php echo number_format($card['value'], 2); ?></div>
			<div class="op-summary-note"><?php echo htmlspecialchars($card['note'], ENT_QUOTES, 'UTF-8'); ?></div>
		</div>
	</div>
	<?php } ?>
</div>

<div class="op-toolbar d-flex flex-wrap align-items-center justify-content-between mb-2">
	<div class="text-muted fs-sm mr-3 mb-2">
		Showing <strong><?php echo $from; ?>–<?php echo $to; ?></strong>
		of <strong><?php echo number_format($total_count); ?></strong> QR Ph attempts for the selected filters.
		Totals cover the whole filtered set.
	</div>
	<div class="btn-group mb-2" role="group" aria-label="Pages">
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
	<div class="op-table-wrap">
		<table id="tbl_online_payment" class="table table-bordered table-hover table-striped table-sm w-100 op-table">
			<thead class="bg-primary-600 bg-primary-gradient">
				<tr>
					<th data-priority="1" class="op-col-sn">#</th>
					<th data-priority="2">Reference</th>
					<th data-priority="7">Customer ID</th>
					<th data-priority="3">Customer Name</th>
					<th data-priority="13">Zone</th>
					<th data-priority="5" class="text-right">Bill amount</th>
					<th data-priority="9" class="text-right">QR Ph fee</th>
					<th data-priority="10" class="text-right">Fixed fee</th>
					<th data-priority="8" class="text-right">Total fee</th>
					<th data-priority="4" class="text-right">Total charged</th>
					<th data-priority="4">Status</th>
					<th data-priority="14">Source</th>
					<th data-priority="11">Created</th>
					<th data-priority="12">Paid</th>
					<th data-priority="15">PayMongo Ref</th>
					<th data-priority="15">Collected by</th>
					<th data-priority="16">Emailed to</th>
					<th data-priority="6" class="text-center op-col-action">Action</th>
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
					<td class="op-col-sn"><?php echo $i++; ?></td>
					<td class="text-nowrap"><span class="badge badge-secondary"><?php echo htmlspecialchars(isset($row['reference_no']) ? $row['reference_no'] : '', ENT_QUOTES, 'UTF-8'); ?></span></td>
					<td class="text-nowrap"><?php echo htmlspecialchars(isset($row['customer_id']) ? stripslashes($row['customer_id']) : '', ENT_QUOTES, 'UTF-8'); ?></td>
					<td class="op-col-name"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></td>
					<td><?php echo htmlspecialchars(isset($row['zones']) ? $row['zones'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
					<td class="text-right text-nowrap fw-700"><?php echo number_format(isset($row['amount']) ? (float) $row['amount'] : 0, 2); ?></td>
					<td class="text-right text-nowrap"><?php echo number_format(Onlinepayment_model::fee_qrph_of($row), 2); ?></td>
					<td class="text-right text-nowrap"><?php echo number_format(Onlinepayment_model::fee_fixed_of($row), 2); ?></td>
					<td class="text-right text-nowrap"><?php echo number_format(Onlinepayment_model::fee_of($row), 2); ?></td>
					<td class="text-right text-nowrap fw-700"><?php echo number_format(Onlinepayment_model::charged_of($row), 2); ?></td>
					<td class="text-nowrap"><span class="badge <?php echo htmlspecialchars($status_class, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($status_label, ENT_QUOTES, 'UTF-8'); ?></span></td>
					<td><?php echo htmlspecialchars($context_label, ENT_QUOTES, 'UTF-8'); ?></td>
					<td class="text-nowrap fs-sm"><?php echo htmlspecialchars($created, ENT_QUOTES, 'UTF-8'); ?></td>
					<td class="text-nowrap fs-sm"><?php echo htmlspecialchars($paid_at, ENT_QUOTES, 'UTF-8'); ?></td>
					<td class="op-col-break fs-sm"><?php echo htmlspecialchars(isset($row['payment_id']) ? $row['payment_id'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
					<td><?php echo htmlspecialchars($collector, ENT_QUOTES, 'UTF-8'); ?></td>
					<td class="op-col-break fs-sm"><?php echo htmlspecialchars(isset($row['emailed_to']) ? $row['emailed_to'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
					<td class="text-center op-col-action">
						<?php if ($status === 'paid') { ?>
							<a href="<?php echo ADMIN_URL; ?>onlinepayment/receipt/<?php echo $id; ?>" target="_blank" class="btn btn-xs btn-outline-primary btn-icon" title="Print 80mm receipt" data-toggle="tooltip">
								<i class="fal fa-print"></i>
							</a>
						<?php } else { ?>
							<a href="<?php echo ADMIN_URL; ?>onlinepayment/manage" class="btn btn-xs btn-outline-info btn-icon" title="Open the Online Payment list" data-toggle="tooltip">
								<i class="fal fa-list"></i>
							</a>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
			</tbody>
			<tfoot>
				<tr class="bg-faded">
					<th class="op-col-sn"></th>
					<th class="text-nowrap">Totals</th>
					<th></th>
					<th class="fs-xs text-muted fw-400">All filtered rows</th>
					<th></th>
					<th class="text-right text-nowrap"><?php echo number_format($paid_amount + $open_amount, 2); ?><div class="fs-xs text-muted fw-400">paid + outstanding</div></th>
					<th class="text-right text-nowrap"><?php echo number_format($paid_fee_qrph, 2); ?><div class="fs-xs text-muted fw-400">paid</div></th>
					<th class="text-right text-nowrap"><?php echo number_format($paid_fee_fixed, 2); ?><div class="fs-xs text-muted fw-400">paid</div></th>
					<th class="text-right text-nowrap"><?php echo number_format($paid_fee, 2); ?><div class="fs-xs text-muted fw-400">paid</div></th>
					<th class="text-right text-nowrap"><?php echo number_format($paid_charged, 2); ?><div class="fs-xs text-muted fw-400">paid</div></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th class="op-col-action"></th>
				</tr>
			</tfoot>
		</table>
	</div>
<?php } ?>
