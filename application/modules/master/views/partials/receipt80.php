<?php
/**
 * 80mm POS receipt for a settled online / QR Ph payment.
 *
 * Expects: $attempt, $customer, $rows, $address, $autoprint
 *
 * Printed size is 80mm wide. Works from a Bluetooth thermal printer driven by a
 * native app (RawBT / vendor app) or a network printer reachable from the phone:
 * the page renders, then hands off to the print dialog. `?preview=1` renders
 * without auto-printing so the operator can see it on screen first.
 *
 * The OR field carries the PayMongo reference labelled "Online Ref", because an
 * online payment is deliberately recorded without an OR.
 */
$address = isset($address) && is_array($address) ? $address : array();
$attempt = isset($attempt) && is_array($attempt) ? $attempt : array();
$customer = isset($customer) && is_array($customer) ? $customer : array();
$rows = isset($rows) && is_array($rows) ? $rows : array();
$autoprint = !isset($autoprint) || $autoprint;

$company = isset($address['host_name']) ? trim((string) $address['host_name']) : '';
$address_html = isset($address['content']) ? (string) $address['content'] : '';
$logo = isset($address['logo']) ? trim((string) $address['logo']) : '';

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
$customer_id = isset($attempt['customer_id']) ? $attempt['customer_id'] : (isset($customer['customer_id']) ? $customer['customer_id'] : '');

$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Online Payment Receipt — <?php echo $h($reference); ?></title>
<meta name="viewport" content="width=80mm, initial-scale=1">
<style>
	@page { size: 80mm auto; margin: 0; }

	* { box-sizing: border-box; }

	body {
		margin: 0;
		padding: 0;
		background: #f2f2f2;
		font-family: "Courier New", Consolas, monospace;
		color: #000;
	}

	.receipt {
		width: 80mm;
		max-width: 80mm;
		margin: 0 auto;
		background: #fff;
		padding: 3mm 3mm 5mm 3mm;
		font-size: 11px;
		line-height: 1.35;
	}

	.center { text-align: center; }
	.right { text-align: right; }
	.bold { font-weight: bold; }

	.logo { max-width: 44mm; height: auto; display: block; margin: 0 auto 1mm auto; }

	.company { font-size: 13px; font-weight: bold; letter-spacing: 0.3px; }
	.address { font-size: 9px; white-space: pre-line; }
	.address p { margin: 0; }

	.title {
		margin: 2mm 0;
		font-size: 12px;
		font-weight: bold;
		border-top: 1px dashed #000;
		border-bottom: 1px dashed #000;
		padding: 1mm 0;
	}

	.meta { width: 100%; border-collapse: collapse; }
	.meta td { padding: 0.4mm 0; vertical-align: top; font-size: 10px; }
	.meta td.k { width: 34%; }

	table.items { width: 100%; border-collapse: collapse; margin-top: 1.5mm; }
	table.items th {
		font-size: 9px;
		text-align: left;
		border-bottom: 1px solid #000;
		padding: 0.6mm 0;
	}
	table.items td { font-size: 10px; padding: 0.8mm 0; border-bottom: 1px dotted #bbb; }
	table.items td.num, table.items th.num { text-align: right; }

	.totals { margin-top: 2mm; border-top: 1px solid #000; padding-top: 1mm; }
	.totals .line { display: flex; justify-content: space-between; font-size: 11px; padding: 0.5mm 0; }
	.totals .grand { font-size: 14px; font-weight: bold; border-top: 1px dashed #000; margin-top: 1mm; padding-top: 1mm; }

	.online-ref {
		margin-top: 2mm;
		border: 1px solid #000;
		padding: 1.2mm;
		font-size: 10px;
	}
	.online-ref .label { font-size: 8px; letter-spacing: 0.5px; }
	.online-ref .value { font-size: 11px; font-weight: bold; word-break: break-all; }

	.foot { margin-top: 3mm; font-size: 9px; text-align: center; }
	.foot .note { white-space: pre-line; margin-bottom: 1.5mm; }

	.screen-actions { width: 80mm; margin: 4mm auto; text-align: center; font-family: Arial, sans-serif; }
	.screen-actions button {
		font-size: 14px; padding: 10px 18px; margin: 0 4px 6px 4px;
		border: 0; border-radius: 4px; background: #3276b1; color: #fff; cursor: pointer;
	}
	.screen-actions button.secondary { background: #6c757d; }

	@media print {
		body { background: #fff; }
		.receipt { margin: 0; padding: 0 2mm; width: 80mm; }
		.screen-actions { display: none !important; }
	}
</style>
</head>
<body<?php echo $autoprint ? ' onload="window.print();"' : ''; ?>>

<div class="receipt">

	<?php if ($logo !== '') { ?>
	<img class="logo" src="<?php echo base_url('images/' . rawurlencode($logo)); ?>" alt="<?php echo $h($company); ?>">
	<?php } ?>

	<div class="center">
		<div class="company"><?php echo $company !== '' ? $h($company) : 'WATER DISTRICT'; ?></div>
		<?php if ($address_html !== '') { ?>
		<div class="address"><?php echo strip_tags($address_html, '<br><p><div><span>'); ?></div>
		<?php } ?>
	</div>

	<div class="center title">ONLINE PAYMENT RECEIPT</div>

	<table class="meta">
		<tr>
			<td class="k">Date paid</td>
			<td><?php echo $paid_at !== '' ? $h(date('M j, Y g:i A', strtotime($paid_at))) : $h(date('M j, Y g:i A')); ?></td>
		</tr>
		<tr>
			<td class="k">Customer ID</td>
			<td class="bold"><?php echo $h($customer_id); ?></td>
		</tr>
		<tr>
			<td class="k">Name</td>
			<td><?php echo $h($name); ?></td>
		</tr>
		<?php if (!empty($customer['meter_number'])) { ?>
		<tr>
			<td class="k">Meter no.</td>
			<td><?php echo $h($customer['meter_number']); ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="k">Payment</td>
			<td class="bold">QR PH (online)</td>
		</tr>
		<tr>
			<td class="k">Reference</td>
			<td><?php echo $h($reference); ?></td>
		</tr>
	</table>

	<?php if (!empty($rows)) { ?>
	<table class="items">
		<thead>
			<tr>
				<th>Billing period</th>
				<th>Consumed</th>
				<th class="num">Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) {
				$period = trim((isset($row['month_name']) ? $row['month_name'] : '') . ' ' . (isset($row['year']) ? $row['year'] : ''));
			?>
			<tr>
				<td><?php echo $h($period); ?></td>
				<td><?php echo isset($row['consumed']) ? $h(number_format((float) $row['consumed'], 0)) : '0'; ?> cu.m</td>
				<td class="num"><?php echo number_format(isset($row['amount_due']) ? (float) $row['amount_due'] : 0, 2); ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php } ?>

	<div class="totals">
		<?php if ($fee_amount > 0) { ?>
		<div class="line">
			<span>Bill paid</span>
			<span>&#8369; <?php echo number_format($bill_amount, 2); ?></span>
		</div>
		<div class="line">
			<span>Processing fee</span>
			<span>&#8369; <?php echo number_format($fee_amount, 2); ?></span>
		</div>
		<?php } ?>
		<div class="line grand">
			<span>TOTAL PAID</span>
			<span>&#8369; <?php echo number_format($amount, 2); ?></span>
		</div>
	</div>

	<div class="online-ref">
		<div class="label">ONLINE REF (PAYMONGO)</div>
		<div class="value"><?php echo $h($online_ref); ?></div>
	</div>

	<div class="foot">
		<?php
		$this->load->library('Paymongo');
		$note = $this->paymongo->receipt_note();
		if ($note !== '') { ?>
		<div class="note"><?php echo $h($note); ?></div>
		<?php } ?>
		<div>Paid online via QR Ph. No OR number is issued for this payment.</div>
		<div style="margin-top:1.5mm;">Thank you for paying on time.</div>
	</div>
</div>

<div class="screen-actions">
	<button type="button" onclick="window.print();">Print receipt</button>
	<button type="button" class="secondary" onclick="window.close();">Close</button>
</div>

</body>
</html>
