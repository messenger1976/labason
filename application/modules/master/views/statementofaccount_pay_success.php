<?php
/**
 * Statement of Account — Online Pay confirmation (customer).
 *
 * Loaded after views/admin-includes/public_header.php and closes the wrapper
 * divs the header leaves open. Layout follows mobile_payment_success.php.
 *
 * Expects: $attempt, $customer, $rows
 */
$attempt = isset($attempt) && is_array($attempt) ? $attempt : array();
$customer = isset($customer) && is_array($customer) ? $customer : array();
$rows = isset($rows) && is_array($rows) ? $rows : array();

$id = isset($attempt['id']) ? (int) $attempt['id'] : 0;
$customer_id = isset($attempt['customer_id']) ? (string) $attempt['customer_id'] : '';
$reference = isset($attempt['reference_no']) ? (string) $attempt['reference_no'] : '';
$online_ref = isset($attempt['payment_id']) && trim((string) $attempt['payment_id']) !== ''
	? (string) $attempt['payment_id']
	: $reference;
$bill_amount = isset($attempt['amount']) ? (float) $attempt['amount'] : 0;
$fee_amount = isset($attempt['fee_amount']) ? (float) $attempt['fee_amount'] : 0;
$amount = (isset($attempt['charged_amount']) && $attempt['charged_amount'] !== null && $attempt['charged_amount'] !== '')
	? (float) $attempt['charged_amount']
	: $bill_amount;
$paid_at = !empty($attempt['paid_at']) ? $attempt['paid_at'] : (isset($attempt['settled_at']) ? $attempt['settled_at'] : '');
$name = '';
if (!empty($customer)) {
	$name = strtoupper(trim(
		(isset($customer['last_name']) ? $customer['last_name'] : '') . ', ' .
		(isset($customer['first_name']) ? $customer['first_name'] : '') . ' ' .
		(isset($customer['middle_name']) ? $customer['middle_name'] : '')
	));
}
$is_paid = isset($attempt['status']) && $attempt['status'] === 'paid';

$base = base_url() . 'master/statementofaccount/';

$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
?>
<style>
	.soa-succ-wrap { max-width: 720px; margin: 0 auto; font-size: 14px; }
	.soa-succ-card {
		background: #fff; border-radius: 14px;
		box-shadow: 0 2px 6px rgba(0, 0, 0, .12); margin-bottom: 10px; overflow: hidden;
	}
	.soa-succ-pad { padding: 14px; }
	.soa-succ-hero { text-align: center; padding: 20px 14px; }
	.soa-succ-hero .icon { font-size: 54px; line-height: 1; }
	.soa-succ-hero .icon.ok { color: #1f8b4c; }
	.soa-succ-hero .icon.no { color: #c0392b; }
	.soa-succ-hero .big { font-size: 20px; font-weight: 700; color: #24405c; margin-top: 8px; }
	.soa-succ-hero .amt { font-size: 30px; font-weight: 700; color: #3276b1; margin-top: 10px; }
	.soa-succ-hero .sub { color: #6c757d; font-size: 12px; margin-top: 4px; }
	.soa-succ-row {
		display: flex; justify-content: space-between; gap: 10px;
		padding: 9px 0; border-bottom: 1px dashed #eef1f5; font-size: 13px;
	}
	.soa-succ-row:last-child { border-bottom: 0; }
	.soa-succ-row .k { color: #6c757d; }
	.soa-succ-row .v { text-align: right; word-break: break-word; }
	.soa-succ-title { font-weight: 700; color: #24405c; font-size: 14px; margin-bottom: 6px; }
	.soa-succ-actions { display: flex; flex-direction: column; gap: 8px; margin-top: 12px; }
	.soa-succ-btn {
		display: block; text-align: center; padding: 13px 16px; border-radius: 12px;
		font-weight: 700; font-size: 15px; text-decoration: none; border: 0;
	}
	.soa-succ-btn:hover, .soa-succ-btn:focus { text-decoration: none; }
	.soa-succ-btn.primary { background: #1f8b4c; color: #fff; }
	.soa-succ-btn.secondary { background: #eef1f5; color: #24405c; }
	.soa-succ-btn.ghost { background: #fff; color: #3276b1; border: 1px solid #cfd8e3; }
</style>

<div class="soa-succ-wrap">

	<div class="soa-succ-card">
		<div class="soa-succ-hero">
			<div class="icon <?php echo $is_paid ? 'ok' : 'no'; ?>">
				<i class="fa <?php echo $is_paid ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
			</div>
			<div class="big"><?php echo $is_paid ? 'Payment received. Thank you!' : 'Payment not completed'; ?></div>
			<div class="amt">&#8369; <?php echo number_format($amount, 2); ?></div>
			<?php if ($paid_at !== '') { ?>
			<div class="sub"><?php echo $h(date('M j, Y g:i A', strtotime($paid_at))); ?></div>
			<?php } ?>
		</div>
		<div class="soa-succ-pad" style="border-top:1px solid #eef1f5;">
			<div class="soa-succ-row"><span class="k">Customer</span><span class="v"><?php echo $h($name); ?></span></div>
			<div class="soa-succ-row"><span class="k">Customer ID</span><span class="v"><?php echo $h($customer_id); ?></span></div>
			<div class="soa-succ-row"><span class="k">Reference</span><span class="v"><?php echo $h($reference); ?></span></div>
			<div class="soa-succ-row"><span class="k">Online ref (PayMongo)</span><span class="v"><?php echo $h($online_ref); ?></span></div>
			<div class="soa-succ-row"><span class="k">Payment method</span><span class="v">QR Ph (online)</span></div>
		</div>
	</div>

	<?php if (!empty($rows)) { ?>
	<div class="soa-succ-card">
		<div class="soa-succ-pad">
			<div class="soa-succ-title">Periods paid</div>
			<?php foreach ($rows as $row) { ?>
			<div class="soa-succ-row">
				<span class="k"><?php echo $h(trim((isset($row['month_name']) ? $row['month_name'] : '') . ' ' . (isset($row['year']) ? $row['year'] : ''))); ?></span>
				<span class="v">&#8369; <?php echo number_format(isset($row['amount_due']) ? (float) $row['amount_due'] : 0, 2); ?></span>
			</div>
			<?php } ?>
			<?php if ($fee_amount > 0) { ?>
			<div class="soa-succ-row">
				<span class="k">Processing fee</span>
				<span class="v">&#8369; <?php echo number_format($fee_amount, 2); ?></span>
			</div>
			<?php } ?>
			<div class="soa-succ-row" style="border-top:1px solid #eef1f5;">
				<span class="k" style="font-weight:700;">TOTAL PAID</span>
				<span class="v" style="font-weight:700;color:#1f8b4c;">&#8369; <?php echo number_format($amount, 2); ?></span>
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="soa-succ-card">
		<div class="soa-succ-pad">
			<?php if ($is_paid) { ?>
			<div class="soa-succ-title">Receipt</div>
			<div style="color:#6c757d;font-size:12px;">
				An online payment is recorded against the PayMongo reference and does not carry an OR number.
			</div>
			<?php } ?>
			<div class="soa-succ-actions">
				<?php if ($is_paid) { ?>
				<a class="soa-succ-btn ghost" target="_blank" href="<?php echo $h($base . 'pay_receipt/' . $id . '?preview=1'); ?>">
					<i class="fa fa-eye"></i> Preview receipt
				</a>
				<a class="soa-succ-btn primary" target="_blank" href="<?php echo $h($base . 'pay_receipt/' . $id); ?>">
					<i class="fa fa-print"></i> Print receipt
				</a>
				<?php } else { ?>
				<a class="soa-succ-btn primary" href="<?php echo $h($base . 'pay/' . rawurlencode($customer_id)); ?>">
					<i class="fa fa-qrcode"></i> Back to Online Pay
				</a>
				<?php } ?>
				<a class="soa-succ-btn secondary" href="<?php echo $h($base . 'index/' . rawurlencode($customer_id)); ?>">
					<i class="fa fa-file-text-o"></i> Back to Statement
				</a>
			</div>
		</div>
	</div>
</div>
			</div><!-- /#content -->
		</div><!-- /#main -->
	</body>
</html>
