<?php
/**
 * Public payment success page.
 *
 * Reached after the QR Ph payment settles. Confirms the amount, the PayMongo
 * reference and the periods paid, and offers the 80mm receipt for printing.
 *
 * Loaded after views/admin-includes/public_header.php, so it uses
 * Bootstrap 3 / Font Awesome 4 classes and closes the header's wrapper divs.
 *
 * Expects: $token, $attempt, $customer_name, $rows, $address
 */
$attempt = isset($attempt) && is_array($attempt) ? $attempt : array();
$rows = isset($rows) && is_array($rows) ? $rows : array();
$address = isset($address) && is_array($address) ? $address : array();
$token = isset($token) ? (string) $token : '';
$company = isset($address['host_name']) ? trim((string) $address['host_name']) : '';

$reference = isset($attempt['reference_no']) ? (string) $attempt['reference_no'] : '';
$online_ref = isset($attempt['payment_id']) && trim((string) $attempt['payment_id']) !== ''
	? (string) $attempt['payment_id']
	: $reference;
$amount = isset($attempt['amount']) ? (float) $attempt['amount'] : 0;
$paid_at = !empty($attempt['paid_at']) ? $attempt['paid_at'] : (isset($attempt['settled_at']) ? $attempt['settled_at'] : '');

$receipt_url = rtrim(base_url(), '/') . '/master/paymentportal/receipt/' . rawurlencode($token);
$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
?>
<div class="container" style="max-width:640px;">

	<div class="text-center" style="margin-bottom:16px;">
		<div style="font-size:20px;font-weight:700;"><?php echo $company !== '' ? $h($company) : 'WATER DISTRICT'; ?></div>
		<div class="text-muted" style="font-size:12px;">Online payment — QR Ph</div>
	</div>

	<div class="text-center" style="background:#fff;border:1px solid #ddd;border-radius:4px;padding:26px 18px;">
		<div style="font-size:52px;color:#28a745;line-height:1;"><i class="fa fa-check-circle"></i></div>
		<div style="font-size:22px;font-weight:700;margin-top:8px;">Payment received</div>
		<div class="text-muted" style="margin-top:4px;">
			Thank you<?php echo $customer_name !== '' ? ', ' . $h($customer_name) : ''; ?>. Your bill has been updated.
		</div>

		<div style="font-size:32px;font-weight:700;color:#3276b1;margin-top:16px;">
			&#8369; <?php echo number_format($amount, 2); ?>
		</div>
		<?php if ($paid_at !== '') { ?>
		<div class="text-muted" style="font-size:12px;">Paid on <?php echo $h(date('M j, Y g:i A', strtotime($paid_at))); ?></div>
		<?php } ?>

		<table class="table table-condensed" style="margin-top:16px;text-align:left;">
			<tbody>
				<tr>
					<th style="width:45%;">Reference</th>
					<td><?php echo $h($reference); ?></td>
				</tr>
				<tr>
					<th>Online ref (PayMongo)</th>
					<td style="word-break:break-all;"><?php echo $h($online_ref); ?></td>
				</tr>
				<tr>
					<th>Payment method</th>
					<td>QR Ph (online)</td>
				</tr>
			</tbody>
		</table>

		<?php if (!empty($rows)) { ?>
		<div style="text-align:left;">
			<strong>Periods paid</strong>
			<ul class="list-unstyled" style="margin-top:6px;">
				<?php foreach ($rows as $row) { ?>
				<li>
					<?php echo $h(trim((isset($row['month_name']) ? $row['month_name'] : '') . ' ' . (isset($row['year']) ? $row['year'] : ''))); ?>
					— &#8369; <?php echo number_format(isset($row['amount_due']) ? (float) $row['amount_due'] : 0, 2); ?>
				</li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>

		<div style="margin-top:18px;">
			<a class="btn btn-primary" target="_blank" href="<?php echo $h($receipt_url); ?>">
				<i class="fa fa-print"></i> Print receipt
			</a>
			<a class="btn btn-default" target="_blank" href="<?php echo $h($receipt_url); ?>?preview=1">
				<i class="fa fa-file-text-o"></i> Preview
			</a>
		</div>
	</div>

	<p class="text-muted text-center" style="margin-top:14px;font-size:12px;">
		A QR Ph payment is recorded against the PayMongo reference above and does not carry an OR number.
		Keep this page or the printed receipt for your records.
	</p>
</div>
			</div><!-- /#content -->
		</div><!-- /#main -->
	</body>
</html>
