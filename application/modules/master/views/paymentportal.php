<?php
/**
 * Public payment page — what an emailed payment link opens.
 *
 * Loaded after views/admin-includes/public_header.php (Bootstrap 3 + Font
 * Awesome 4), so this view uses fa/fa-* icons and BS3 classes, and closes the
 * wrapper divs the header leaves open.
 *
 * The QR block itself is the shared partial, so the customer, the cashier and
 * the meter reader all look at the same component with the same polling.
 *
 * Expects: $token, $attempt, $customer_name, $customer_id, $rows, $address, $status_label, $error
 */
$attempt = isset($attempt) && is_array($attempt) ? $attempt : array();
$rows = isset($rows) && is_array($rows) ? $rows : array();
$address = isset($address) && is_array($address) ? $address : array();
$error = isset($error) ? trim((string) $error) : '';
$token = isset($token) ? (string) $token : '';
$company = isset($address['host_name']) ? trim((string) $address['host_name']) : '';

$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
?>
<div class="container" style="max-width:640px;">

	<div class="text-center" style="margin-bottom:16px;">
		<div style="font-size:20px;font-weight:700;"><?php echo $company !== '' ? $h($company) : 'WATER DISTRICT'; ?></div>
		<div class="text-muted" style="font-size:12px;">Online payment — QR Ph</div>
	</div>

	<?php if ($error !== '') { ?>
	<div class="alert alert-danger text-center">
		<i class="fa fa-exclamation-triangle"></i>
		<?php echo $h($error); ?>
	</div>
	<?php } else { ?>

	<div class="panel panel-default">
		<div class="panel-heading">
			<strong>Bill for <?php echo $h($customer_name); ?></strong>
			<span class="pull-right text-muted"><?php echo $h($customer_id); ?></span>
		</div>
		<div class="panel-body">
			<?php if (!empty($rows)) { ?>
			<table class="table table-condensed">
				<thead>
					<tr>
						<th>Billing period</th>
						<th class="text-right">Consumed (cu.m)</th>
						<th class="text-right">Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($rows as $row) { ?>
					<tr>
						<td><?php echo $h(trim((isset($row['month_name']) ? $row['month_name'] : '') . ' ' . (isset($row['year']) ? $row['year'] : ''))); ?></td>
						<td class="text-right"><?php echo number_format(isset($row['consumed']) ? (float) $row['consumed'] : 0, 0); ?></td>
						<td class="text-right">&#8369; <?php echo number_format(isset($row['amount_due']) ? (float) $row['amount_due'] : 0, 2); ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php } ?>
		</div>
	</div>

	<?php
	// The shared QR block: same component the cashier and the meter reader use.
	$this->load->view('partials/qr_panel', array(
		'qr_attempt' => $attempt,
		'qr_status_url' => rtrim(base_url(), '/') . '/master/paymentportal/status/' . rawurlencode($token),
		'qr_receipt_url' => rtrim(base_url(), '/') . '/master/paymentportal/receipt/' . rawurlencode($token),
		'qr_print_url' => rtrim(base_url(), '/') . '/master/paymentportal/receipt/' . rawurlencode($token) . '?preview=1',
		'qr_uid' => 'pp',
	));
	?>

	<p class="text-muted text-center" style="margin-top:14px;font-size:12px;">
		This link is personal to this bill. Do not forward it.
		If you have already paid, this page will update on its own — you can also press
		<em>Check status</em>.
	</p>

	<?php } ?>
</div>

<style>
	/* The QR partial is shared with the staff screens, which use Font Awesome
	   Light. The public page loads Font Awesome 4, so the icons would be blank —
	   hide them and let the button labels carry the meaning. */
	.qrph-actions .fal { display: none; }
	.qrph-panel { border: 1px solid #ddd; border-radius: 4px; background: #fff; }
	.qrph-head { padding: 12px; background: #f7f7f9; border-bottom: 1px solid #e9ecef; text-align: center; }
	.qrph-label { font-size: 11px; letter-spacing: 1px; color: #868e96; }
	.qrph-amount { font-size: 30px; font-weight: 700; color: #3276b1; line-height: 1.1; }
	.qrph-ref { font-size: 12px; color: #6c757d; }
	.qrph-body { padding: 14px; text-align: center; }
	.qrph-image { width: 100%; max-width: 260px; height: auto; image-rendering: pixelated; }
	.qrph-hint { font-size: 13px; color: #495057; margin-top: 10px; }
	.qrph-countdown { font-size: 12px; margin-top: 6px; color: #6c757d; }
	.qrph-actions { margin-top: 14px; }
	.qrph-actions .btn { margin: 0 3px 6px 3px; }
	.qrph-paid { text-align: center; padding: 18px 10px; }
	.qrph-paid .icon { font-size: 42px; color: #28a745; }
	.qrph-paid .big { font-size: 20px; font-weight: 700; margin-top: 6px; }
	.qrph-paid .sub { color: #6c757d; font-size: 13px; margin-top: 4px; }
</style>			</div><!-- /#content -->
		</div><!-- /#main -->
	</body>
</html>