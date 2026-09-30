<?php
/**
 * Mobile Payment — confirmation after a QR Ph payment settles.
 *
 * Mobile-first: shows what was recorded, then offers the 80mm receipt as
 * Preview (look at it on screen) or Print (hand off to the printer app).
 *
 * Expects: $attempt, $customer, $rows
 */
$attempt = isset($attempt) && is_array($attempt) ? $attempt : array();
$customer = isset($customer) && is_array($customer) ? $customer : array();
$rows = isset($rows) && is_array($rows) ? $rows : array();

$id = isset($attempt['id']) ? (int) $attempt['id'] : 0;
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

$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
?>
<style>
	.succ-m-wrap {
		padding: 8px 8px calc(96px + env(safe-area-inset-bottom, 0px)) 8px;
		max-width: 720px; margin: 0 auto; font-size: 14px;
	}
	.succ-m-card {
		background: #fff; border-radius: 14px;
		box-shadow: 0 2px 6px rgba(0, 0, 0, .12); margin-bottom: 10px; overflow: hidden;
	}
	.succ-m-pad { padding: 14px; }
	.succ-m-hero { text-align: center; padding: 20px 14px; }
	.succ-m-hero .icon { font-size: 54px; line-height: 1; }
	.succ-m-hero .icon.ok { color: #1f8b4c; }
	.succ-m-hero .icon.no { color: #c0392b; }
	.succ-m-hero .big { font-size: 20px; font-weight: 700; color: #24405c; margin-top: 8px; }
	.succ-m-hero .amt { font-size: 30px; font-weight: 700; color: #3276b1; margin-top: 10px; }
	.succ-m-hero .sub { color: #6c757d; font-size: 12px; margin-top: 4px; }
	.succ-m-row {
		display: flex; justify-content: space-between; gap: 10px;
		padding: 9px 0; border-bottom: 1px dashed #eef1f5; font-size: 13px;
	}
	.succ-m-row:last-child { border-bottom: 0; }
	.succ-m-row .k { color: #6c757d; }
	.succ-m-row .v { text-align: right; word-break: break-word; }
	.succ-m-title { font-weight: 700; color: #24405c; font-size: 14px; margin-bottom: 6px; }
	.succ-m-actions { display: flex; flex-direction: column; gap: 8px; margin-top: 12px; }
	.succ-m-btn {
		display: block; text-align: center; padding: 13px 16px; border-radius: 12px;
		font-weight: 700; font-size: 15px; text-decoration: none; border: 0;
	}
	.succ-m-btn.primary { background: #1f8b4c; color: #fff; }
	.succ-m-btn.secondary { background: #eef1f5; color: #24405c; }
	.succ-m-btn.ghost { background: #fff; color: #3276b1; border: 1px solid #cfd8e3; }
</style>

<!-- MAIN PANEL -->
<div id="main" role="main" style="margin-left:0px;">
	<!-- MAIN CONTENT -->
	<div id="content">

<div class="succ-m-wrap">

	<div class="succ-m-card">
		<div class="succ-m-hero">
			<div class="icon <?php echo $is_paid ? 'ok' : 'no'; ?>">
				<i class="fa <?php echo $is_paid ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
			</div>
			<div class="big"><?php echo $is_paid ? 'Payment received' : 'Payment not completed'; ?></div>
			<div class="amt">&#8369; <?php echo number_format($amount, 2); ?></div>
			<?php if ($paid_at !== '') { ?>
			<div class="sub"><?php echo $h(date('M j, Y g:i A', strtotime($paid_at))); ?></div>
			<?php } ?>
		</div>
		<div class="succ-m-pad" style="border-top:1px solid #eef1f5;">
			<div class="succ-m-row"><span class="k">Customer</span><span class="v"><?php echo $h($name); ?></span></div>
			<div class="succ-m-row"><span class="k">Customer ID</span><span class="v"><?php echo $h(isset($attempt['customer_id']) ? $attempt['customer_id'] : ''); ?></span></div>
			<div class="succ-m-row"><span class="k">Reference</span><span class="v"><?php echo $h($reference); ?></span></div>
			<div class="succ-m-row"><span class="k">Online ref (PayMongo)</span><span class="v"><?php echo $h($online_ref); ?></span></div>
			<div class="succ-m-row"><span class="k">Payment method</span><span class="v">QR Ph (online)</span></div>
		</div>
	</div>

	<?php if (!empty($rows)) { ?>
	<div class="succ-m-card">
		<div class="succ-m-pad">
			<div class="succ-m-title">Periods paid</div>
			<?php foreach ($rows as $row) { ?>
			<div class="succ-m-row">
				<span class="k"><?php echo $h(trim((isset($row['month_name']) ? $row['month_name'] : '') . ' ' . (isset($row['year']) ? $row['year'] : ''))); ?></span>
				<span class="v">&#8369; <?php echo number_format(isset($row['amount_due']) ? (float) $row['amount_due'] : 0, 2); ?></span>
			</div>
			<?php } ?>
			<?php if ($fee_amount > 0) { ?>
			<div class="succ-m-row">
				<span class="k">Processing fee</span>
				<span class="v">&#8369; <?php echo number_format($fee_amount, 2); ?></span>
			</div>
			<?php } ?>
			<div class="succ-m-row" style="border-top:1px solid #eef1f5;">
				<span class="k" style="font-weight:700;">TOTAL PAID</span>
				<span class="v" style="font-weight:700;color:#1f8b4c;">&#8369; <?php echo number_format($amount, 2); ?></span>
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="succ-m-card">
		<div class="succ-m-pad">
			<div class="succ-m-title">Receipt (80mm)</div>
			<div style="color:#6c757d;font-size:12px;">
				Preview it on screen first, then send it to the printer. A QR Ph payment is recorded against the
				PayMongo reference and does not carry an OR number.
			</div>
			<div class="succ-m-actions">
				<a class="succ-m-btn ghost" target="_blank" href="<?php echo ADMIN_URL; ?>mobile_payment/receipt/<?php echo $id; ?>?preview=1">
					<i class="fa fa-eye"></i> Preview receipt
				</a>
				<a class="succ-m-btn primary" target="_blank" href="<?php echo ADMIN_URL; ?>mobile_payment/receipt/<?php echo $id; ?>">
					<i class="fa fa-print"></i> Print receipt
				</a>
				<a class="succ-m-btn secondary" href="<?php echo ADMIN_URL; ?>mobile_payment">
					<i class="fa fa-plus"></i> Take another payment
				</a>
				<a class="succ-m-btn secondary" href="<?php echo ADMIN_URL; ?>mobile_dashboard">
					<i class="fa fa-home"></i> Back to dashboard
				</a>
			</div>
		</div>
	</div>
</div><!-- /.succ-m-wrap -->

	</div>
	<!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->

<?php include('mobile_footer.php'); ?>

</body>
</html>
