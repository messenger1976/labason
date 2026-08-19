<?php
	$h = function($s) {
		return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
	};
	$logo = FCPATH . 'images/mroxas-logo-report.jpg';
	$has_logo = is_file($logo);
	$name = strtoupper(trim(
		(isset($customer_info['last_name']) ? $customer_info['last_name'] : '') . ', ' .
		(isset($customer_info['first_name']) ? $customer_info['first_name'] : '') . ' ' .
		(isset($customer_info['middle_name']) ? $customer_info['middle_name'] : '')
	));
	$ledger_entries = (isset($ledger_entries) && is_array($ledger_entries)) ? $ledger_entries : array();
	$total_debit = 0;
	$total_credit = 0;
	foreach ($ledger_entries as $e) {
		$total_debit += isset($e['debit']) ? (float) $e['debit'] : 0;
		$total_credit += isset($e['credit']) ? (float) $e['credit'] : 0;
	}
	$bal_color = ((float) $current_balance > 0.009) ? 'color:#c0392b;' : 'color:#1e8449;';
?>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="center">
			<?php if ($has_logo) { ?>
			<img src="<?php echo $logo; ?>" height="55" alt="Logo" />
			<?php } ?>
			<div style="font-size:14pt; font-weight:bold;">STATEMENT OF ACCOUNT</div>
			<div style="font-size:9pt;">
				Customer ID: <?php echo $h($customer_info['customer_id']); ?>
				&nbsp;|&nbsp;
				Date/Time printed: <?php echo $h($printed_at); ?>
			</div>
		</td>
	</tr>
</table>
<br />

<table cellpadding="3" cellspacing="0" border="1" width="100%">
	<tr>
		<td width="50%" valign="top">
			<strong>Customer ID:</strong> <?php echo $h($customer_info['customer_id']); ?><br />
			<strong>Name:</strong> <?php echo $h($name); ?><br />
			<strong>Address:</strong> <?php echo $h(strtoupper(isset($customer_info['address']) ? $customer_info['address'] : '')); ?><br />
			<strong>Zone:</strong> <?php echo $h(strtoupper(isset($customer_info['zone']) ? $customer_info['zone'] : 'N/A')); ?>
		</td>
		<td width="50%" valign="top">
			<strong>Meter Number:</strong> <?php echo $h(isset($customer_info['meter_number']) ? $customer_info['meter_number'] : ''); ?><br />
			<strong>Classification:</strong> <?php echo $h(isset($customer_info['class_name']) ? $customer_info['class_name'] : 'N/A'); ?><br />
			<strong>Account Type:</strong> <?php echo $h(isset($customer_info['cust_type_name']) ? $customer_info['cust_type_name'] : 'N/A'); ?><br />
			<strong>Current Billing Balance:</strong>
			<span style="<?php echo $bal_color; ?> font-weight:bold;">PHP <?php echo number_format((float) $current_balance, 2); ?></span>
			<?php if ((float) $current_balance > 0.009) { ?>
			<br /><span style="color:#c0392b; font-size:8pt;">Outstanding / underpaid balance</span>
			<?php } ?>
		</td>
	</tr>
</table>
<br />

<table cellpadding="3" cellspacing="0" border="1" width="100%">
	<thead>
		<tr style="background-color:#eeeeee; font-weight:bold; text-align:center;">
			<th width="12%">Date</th>
			<th width="12%">Ref No</th>
			<th width="40%">Description</th>
			<th width="12%">Debit</th>
			<th width="12%">Credit</th>
			<th width="12%">Balance</th>
		</tr>
	</thead>
	<tbody>
	<?php if (empty($ledger_entries)) { ?>
		<tr>
			<td colspan="6" align="center">No transactions found for this customer.</td>
		</tr>
	<?php } else {
		foreach ($ledger_entries as $entry) {
			$entry_date = !empty($entry['date']) ? date('d-m-Y', strtotime($entry['date'])) : '';
			$debit = (isset($entry['debit']) && $entry['debit'] > 0) ? 'PHP '.number_format((float) $entry['debit'], 2) : '-';
			$credit = (isset($entry['credit']) && $entry['credit'] > 0) ? 'PHP '.number_format((float) $entry['credit'], 2) : '-';
			$balance = 'PHP '.number_format(isset($entry['balance']) ? (float) $entry['balance'] : 0, 2);
			$desc = isset($entry['description']) ? $entry['description'] : '';
			if (isset($entry['type']) && $entry['type'] === 'billing' && isset($entry['consumed'])) {
				$desc .= "\nReading: ".(isset($entry['previous_reading']) ? $entry['previous_reading'] : '').' - '.(isset($entry['reading']) ? $entry['reading'] : '');
				$desc .= ' (Consumed: '.$entry['consumed'].' cu.m)';
				if (isset($entry['penalty']) && (float) $entry['penalty'] > 0) {
					$desc .= ' | Penalty: PHP '.number_format((float) $entry['penalty'], 2);
				}
			}
	?>
		<tr>
			<td width="12%" align="center"><?php echo $h($entry_date); ?></td>
			<td width="12%" align="center"><?php echo $h(isset($entry['refno']) ? $entry['refno'] : ''); ?></td>
			<td width="40%" align="left"><?php echo nl2br($h($desc)); ?></td>
			<td width="12%" align="right"><?php echo $h($debit); ?></td>
			<td width="12%" align="right"><?php echo $h($credit); ?></td>
			<td width="12%" align="right"><strong><?php echo $h($balance); ?></strong></td>
		</tr>
	<?php
		}
	} ?>
	</tbody>
	<?php if (!empty($ledger_entries)) { ?>
	<tfoot>
		<tr style="background-color:#e8e8e8; font-weight:bold;">
			<td width="12%"></td>
			<td width="12%"></td>
			<td width="40%" align="right">Total:</td>
			<td width="12%" align="right">PHP <?php echo number_format($total_debit, 2); ?></td>
			<td width="12%" align="right">PHP <?php echo number_format($total_credit, 2); ?></td>
			<td width="12%" align="right" style="<?php echo $bal_color; ?>">PHP <?php echo number_format((float) $current_balance, 2); ?></td>
		</tr>
	</tfoot>
	<?php } ?>
</table>
<br /><br />

<table cellpadding="4" cellspacing="0" border="0" width="100%">
	<tr>
		<td width="33%" align="center">
			<div>Prepared by:</div>
			<br /><br /><br />
			<div style="border-top:0.5px solid #000; padding-top:4px; font-weight:bold;"><?php echo $h(strtoupper($prepared_name)); ?></div>
			<div style="font-size:8pt;"><?php echo $h($prepared_title); ?></div>
		</td>
		<td width="33%" align="center">
			<div>Verified Correct:</div>
			<br /><br /><br />
			<div style="border-top:0.5px solid #000; padding-top:4px; font-weight:bold;"><?php echo $h(strtoupper($verified_name)); ?></div>
			<div style="font-size:8pt;"><?php echo $h($verified_title); ?></div>
		</td>
		<td width="33%" align="center">
			<div>Approved:</div>
			<br /><br /><br />
			<div style="border-top:0.5px solid #000; padding-top:4px; font-weight:bold;"><?php echo $h(strtoupper($approved_name)); ?></div>
			<div style="font-size:8pt;"><?php echo $h($approved_title); ?></div>
		</td>
	</tr>
</table>
