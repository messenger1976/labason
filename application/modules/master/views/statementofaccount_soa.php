<?php
/**
 * Statement of Account (customer app) - master/statementofaccount/soa/{customer_id}.
 *
 * Rendered inside customer_app_header.php / customer_app_footer.php.
 * Figures come from statementofaccount_model (get_customer_ledger,
 * calculate_running_balance, get_current_balance) exactly as before; this view
 * only formats them. The shortfall rule below is the one the old index view used.
 *
 * Screen: mobile cards. Print: the original A4 report (header, customer table,
 * ledger table, signatories), shown only when printing.
 *
 * Expects: $customer_info, $ledger_entries, $current_balance, $auto_print
 */
$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
$ledger_entries = isset($ledger_entries) && is_array($ledger_entries) ? $ledger_entries : array();
$current_balance = isset($current_balance) ? (float) $current_balance : 0;
$auto_print = !empty($auto_print);
$cid = isset($customer_info['customer_id']) ? (string) $customer_info['customer_id'] : '';
$customer_name = strtoupper($customer_info['last_name'].', '.$customer_info['first_name'].' '.$customer_info['middle_name']);
$pay_url = base_url() . 'master/statementofaccount/pay/' . rawurlencode($cid);
$pdf_url = base_url() . 'master/statementofaccount/pdf/' . rawurlencode($cid);

$printed_at = date('Y-m-d H:i:s');
$prepared_name = 'MISHELLE P. MONDARTE';
$prepared_title = 'Industrial Relations Management Officer C / Billing Officer';
$verified_name = 'DARYL JAY T. VILLARIN';
$verified_title = 'Administrative/General Services Officer B / HRMO/FO/BO';
$approved_name = 'ENGR. ANASTACIA T. ROMANILLOS, CE';
$approved_title = 'General Manager';

if (isset($preparedby[0])) {
	$prepared_name = trim($preparedby[0]['first_name'].' '.$preparedby[0]['middle_name'].' '.$preparedby[0]['last_name']);
	$prepared_title = isset($preparedby[0]['jobtitle']) ? $preparedby[0]['jobtitle'] : $prepared_title;
}
if (isset($verifiedby[0])) {
	$verified_name = trim($verifiedby[0]['first_name'].' '.$verifiedby[0]['middle_name'].' '.$verifiedby[0]['last_name']);
	$verified_title = isset($verifiedby[0]['jobtitle']) ? $verifiedby[0]['jobtitle'] : $verified_title;
}
if (isset($approvedby[0])) {
	$approved_name = trim($approvedby[0]['first_name'].' '.$approvedby[0]['middle_name'].' '.$approvedby[0]['last_name']);
	$approved_title = isset($approvedby[0]['jobtitle']) ? $approvedby[0]['jobtitle'] : $approved_title;
}

if (!function_exists('soa_sign_cookie_value')) {
	function soa_sign_cookie_value($key, $default) {
		if (!isset($_COOKIE[$key])) {
			return $default;
		}
		$val = trim(rawurldecode((string) $_COOKIE[$key]));
		$val = preg_replace('/[\x00-\x1F\x7F]/', '', $val);
		if (function_exists('mb_substr')) {
			$val = mb_substr($val, 0, 120, 'UTF-8');
		} else {
			$val = substr($val, 0, 120);
		}
		return ($val !== '') ? $val : $default;
	}
}
$prepared_name = soa_sign_cookie_value('soa_sign_prepared_name', $prepared_name);
$prepared_title = soa_sign_cookie_value('soa_sign_prepared_title', $prepared_title);
$verified_name = soa_sign_cookie_value('soa_sign_verified_name', $verified_name);
$verified_title = soa_sign_cookie_value('soa_sign_verified_title', $verified_title);
$approved_name = soa_sign_cookie_value('soa_sign_approved_name', $approved_name);
$approved_title = soa_sign_cookie_value('soa_sign_approved_title', $approved_title);

// Mark billing periods where payments+discounts did not fully cover the bill
$period_debits = array();
$period_credits = array();
foreach ($ledger_entries as $e) {
	if (isset($e['type']) && $e['type'] === 'billing') {
		$raw = isset($e['raw_data']) && is_array($e['raw_data']) ? $e['raw_data'] : array();
		if (isset($raw['month']) && isset($raw['year'])) {
			$pk = $raw['month'].'_'.$raw['year'];
			$period_debits[$pk] = (isset($period_debits[$pk]) ? $period_debits[$pk] : 0) + floatval($e['debit']);
		}
	}
	if (isset($e['type']) && in_array($e['type'], array('payment', 'leaking_discount', 'adjustment'), true)) {
		$bp_data = isset($e['billing_periods_data']) && is_array($e['billing_periods_data']) ? $e['billing_periods_data'] : array();
		foreach ($bp_data as $pk => $pd) {
			// Credit adjustments reduce shortfall; debit adjustments increase period obligation
			$signed = floatval($e['credit']) - floatval($e['debit']);
			$period_credits[$pk] = (isset($period_credits[$pk]) ? $period_credits[$pk] : 0) + $signed;
		}
	}
}
$short_periods = array();
foreach ($period_debits as $pk => $deb) {
	$cred = isset($period_credits[$pk]) ? $period_credits[$pk] : 0;
	$gap = $deb - $cred;
	if ($gap > 0.009) {
		$short_periods[$pk] = $gap;
	}
}

// Per-row display data (no new amounts: only the model's figures + the shortfall flag).
$rows = array();
$counts = array('all' => 0, 'billing' => 0, 'payment' => 0, 'adjustment' => 0);
foreach ($ledger_entries as $entry) {
	$row_shortfall = false;
	$shortfall_amt = 0;
	if ($entry['type'] === 'billing') {
		$raw = isset($entry['raw_data']) && is_array($entry['raw_data']) ? $entry['raw_data'] : array();
		if (isset($raw['month']) && isset($raw['year'])) {
			$pk = $raw['month'].'_'.$raw['year'];
			if (isset($short_periods[$pk])) {
				$row_shortfall = true;
				$shortfall_amt = $short_periods[$pk];
			}
		}
	} elseif (in_array($entry['type'], array('payment', 'leaking_discount', 'adjustment'), true)) {
		$bp_data = isset($entry['billing_periods_data']) && is_array($entry['billing_periods_data']) ? $entry['billing_periods_data'] : array();
		foreach ($bp_data as $pk => $pd) {
			if (isset($short_periods[$pk])) {
				$row_shortfall = true;
				$shortfall_amt = $short_periods[$pk];
				break;
			}
		}
	}

	$type = (string) $entry['type'];
	$group = 'adjustment';
	if ($type === 'billing') { $group = 'billing'; }
	elseif ($type === 'payment' || $type === 'leaking_payment' || $type === 'leaking_discount') { $group = 'payment'; }
	$counts['all']++;
	$counts[$group]++;

	$rows[] = array(
		'entry' => $entry,
		'group' => $group,
		'short' => $row_shortfall,
		'short_amt' => $shortfall_amt,
	);
}

$total_debit = array_sum(array_column($ledger_entries, 'debit'));
$total_credit = array_sum(array_column($ledger_entries, 'credit'));

$type_meta = array(
	'billing' => array('Billing', 'billing', 'fa-tint'),
	'payment' => array('Payment', 'payment', 'fa-check'),
	'leaking_payment' => array('Leaking A/R payment', 'payment', 'fa-check'),
	'leaking_discount' => array('Leaking discount', 'discount', 'fa-percent'),
	'adjustment' => array('AR adjustment', 'adjustment', 'fa-sliders'),
);
$peso = function ($v) { return '&#8369; ' . number_format((float) $v, 2); };
?>
<style>
	.soa-cust-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .5rem 1rem; margin-top: .85rem; }
	.soa-cust-grid .k { font-size: .66rem; text-transform: uppercase; letter-spacing: .06em; opacity: .75; }
	.soa-cust-grid .v { font-size: .82rem; font-weight: 600; word-break: break-word; }
	.soa-sig { display: grid; grid-template-columns: minmax(0, 1fr); gap: .75rem; }
	.soa-sig .lbl { font-size: .7rem; color: var(--app-muted); text-transform: uppercase; letter-spacing: .05em; }
	.soa-sig .nm { font-weight: 700; font-size: .82rem; color: var(--app-head); }
	.soa-sig .tt { font-size: .74rem; color: var(--app-muted); }
	@media (min-width: 768px) { .soa-sig { grid-template-columns: repeat(3, minmax(0, 1fr)); } .soa-cust-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); } }

	/* ---- Print report (unchanged A4 layout of the old statement page) ---- */
	.report-header { text-align: center; margin: 0 0 15px 0; }
	.report-logo { margin-bottom: 5px; }
	.report-title { font-size: 16px; font-weight: bold; letter-spacing: 0.5px; }
	.report-subtitle { font-size: 12px; color: #555; margin-top: 2px; }
	.signature-block { margin-top: 25px; padding-top: 10px; border-top: 1px solid #ddd; }
	.sig-row { display: flex; gap: 20px; justify-content: space-between; flex-wrap: wrap; }
	.sig-item { flex: 1 1 250px; min-width: 250px; }
	.sig-label { font-size: 12px; margin-bottom: 28px; }
	.sig-line { border-top: 1px solid #000; padding-top: 3px; font-weight: bold; font-size: 12px; text-align: center; }
	.sig-title { font-size: 11px; text-align: center; margin-top: 2px; }

	@media print {
		@page { size: A4 portrait; margin: 0.5cm 0.6cm; }
		html, body { background: white !important; color: black !important; font-size: 9pt; line-height: 1.3; width: 100% !important; margin: 0 !important; padding: 0 !important; }
		.soa-screen { display: none !important; }
		* { box-shadow: none !important; text-shadow: none !important; }

		.report-header { margin: 0 0 6px 0 !important; }
		.report-logo img { height: 48px !important; width: auto !important; }
		.report-title { font-size: 12pt !important; }
		.report-subtitle { font-size: 8pt !important; color: #000 !important; }

		.soa-print .panel { border: 1px solid #000 !important; margin-bottom: 8px !important; page-break-inside: avoid; }
		.soa-print .panel-heading { background: #f5f5f5 !important; border-bottom: 1px solid #000 !important; padding: 4px 8px !important; font-weight: bold; font-size: 9pt; }
		.soa-print .panel-body { padding: 6px !important; }
		.soa-print .table { width: 100% !important; border-collapse: collapse !important; font-size: 8pt; margin-bottom: 6px !important; }
		.soa-print .table-bordered { border: 2px solid #000 !important; }
		.soa-print .table-bordered td { border: 1px solid #000 !important; padding: 3px 5px !important; font-size: 8pt; text-align: left; vertical-align: top; }
		.soa-print .text-danger { color: #000 !important; font-weight: bold; }
		.soa-print .text-success { color: #000 !important; }
		.soa-print .text-muted { color: #666 !important; }

		.soa-balance-alert { background: #ffe3e8 !important; border-left: 3px solid #e35d6a; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
		.soa-balance-alert-note { color: #a33b46; font-weight: 600; }

		#ledger_table_print.soa-ledger-print { min-width: 0 !important; max-width: 100% !important; width: 100% !important; table-layout: fixed !important; border-collapse: collapse !important; font-size: 7pt; }
		#ledger_table_print col.col-date { width: 11%; }
		#ledger_table_print col.col-ref { width: 9%; }
		#ledger_table_print col.col-desc { width: 40%; }
		#ledger_table_print col.col-amt { width: 13.33%; }
		#ledger_table_print thead { display: table-header-group; }
		#ledger_table_print tfoot { display: table-footer-group; }
		#ledger_table_print th, #ledger_table_print td { border: 1px solid #999 !important; padding: 2px 3px !important; vertical-align: top; line-height: 1.15; white-space: normal !important; word-break: break-word; overflow-wrap: break-word; box-sizing: border-box !important; }
		#ledger_table_print thead th { background: #f0f0f0 !important; font-size: 6.5pt; font-weight: bold; text-align: center !important; }
		#ledger_table_print thead th:nth-child(3) { text-align: left !important; }
		#ledger_table_print tbody td:nth-child(1), #ledger_table_print tbody td:nth-child(2) { text-align: center !important; font-size: 6.5pt; }
		#ledger_table_print tbody td:nth-child(3) { text-align: left !important; font-size: 6.5pt; }
		#ledger_table_print tbody td:nth-child(4), #ledger_table_print tbody td:nth-child(5), #ledger_table_print tbody td:nth-child(6), #ledger_table_print tfoot th { text-align: right !important; font-size: 6pt; }
		#ledger_table_print tbody tr:nth-child(odd) td { background: #f5f5f5 !important; }
		#ledger_table_print tbody tr.soa-row-shortfall td { background: #ffe8ec !important; }
		#ledger_table_print tbody tr.soa-row-shortfall td.soa-balance-cell-alert { background: #ffd0d8 !important; font-weight: bold; }
		#ledger_table_print tfoot th { background: #e8e8e8 !important; font-weight: bold; border-top: 2px solid #666 !important; }
		#ledger_table_print tfoot th:first-child { text-align: right !important; }
		#ledger_table_print td:nth-child(3) small.text-muted, #ledger_table_print td:nth-child(3) small { display: block; font-size: 5.5pt !important; line-height: 1.1; color: #555 !important; }
		#ledger_table_print .soa-shortfall-note { color: #a33b46 !important; font-weight: 600; }

		.signature-block { border-top: 1px solid #000 !important; margin-top: 12px !important; padding-top: 6px !important; page-break-inside: avoid; }
		.sig-label { margin-bottom: 22px !important; color: #000 !important; }
		.sig-line { border-top: 1px solid #000 !important; }
		.sig-item { min-width: 0 !important; flex: 1 1 30% !important; }
		-webkit-print-color-adjust: exact !important;
		print-color-adjust: exact !important;
	}
</style>

<div class="soa-screen">
	<!-- Account summary -->
	<div class="app-card app-hero">
		<div class="app-card-body">
			<div class="hello">Statement of Account</div>
			<div class="name"><?php echo $h($customer_name); ?></div>
			<div class="meta">Customer ID <?php echo $h($cid); ?> &middot; Meter <?php echo $h($customer_info['meter_number']); ?></div>
			<div class="bal-lbl">Current billing balance</div>
			<div class="bal"><?php echo $peso($current_balance); ?></div>
			<div class="bal-sub">
				<?php if ($current_balance > 0.009) { ?>
					<span class="pill bad">Outstanding / underpaid balance</span>
				<?php } else { ?>
					<span class="pill ok">Fully paid</span>
				<?php } ?>
			</div>
			<div class="soa-cust-grid">
				<div><div class="k">Address</div><div class="v"><?php echo $h(strtoupper($customer_info['address'])); ?></div></div>
				<div><div class="k">Zone</div><div class="v"><?php echo $h(isset($customer_info['zone']) ? strtoupper($customer_info['zone']) : 'N/A'); ?></div></div>
				<div><div class="k">Classification</div><div class="v"><?php echo $h(isset($customer_info['class_name']) ? $customer_info['class_name'] : 'N/A'); ?></div></div>
				<div><div class="k">Account type</div><div class="v"><?php echo $h(isset($customer_info['cust_type_name']) ? $customer_info['cust_type_name'] : 'N/A'); ?></div></div>
			</div>
			<div class="app-hero-actions">
				<?php if ($current_balance > 0.009) { ?>
				<a class="app-btn app-btn-light" href="<?php echo $h($pay_url); ?>"><i class="fa fa-qrcode"></i> Pay online</a>
				<?php } ?>
				<button type="button" class="app-btn app-btn-ghost js-soa-print"><i class="fa fa-print"></i> Print</button>
				<a class="app-btn app-btn-ghost" href="<?php echo $h($pdf_url); ?>" target="_blank" rel="noopener"><i class="fa fa-file-pdf-o"></i> PDF</a>
			</div>
		</div>
	</div>

	<!-- Totals -->
	<div class="app-kpis">
		<div class="app-kpi tone-bad">
			<div class="lbl">Total billed</div>
			<div class="val"><?php echo $peso($total_debit); ?></div>
			<div class="hint">Debits (billing)</div>
		</div>
		<div class="app-kpi tone-ok">
			<div class="lbl">Total paid</div>
			<div class="val"><?php echo $peso($total_credit); ?></div>
			<div class="hint">Credits (payments)</div>
		</div>
		<div class="app-kpi <?php echo $current_balance > 0.009 ? 'tone-warn' : 'tone-ok'; ?>">
			<div class="lbl">Balance</div>
			<div class="val <?php echo $current_balance > 0.009 ? 'bad' : 'ok'; ?>"><?php echo $peso($current_balance); ?></div>
			<div class="hint">Total billed &minus; total paid</div>
		</div>
		<div class="app-kpi tone-info">
			<div class="lbl">Transactions</div>
			<div class="val"><?php echo (int) $counts['all']; ?></div>
			<div class="hint"><?php echo count($short_periods); ?> underpaid period<?php echo count($short_periods) === 1 ? '' : 's'; ?></div>
		</div>
	</div>

	<!-- Ledger -->
	<div class="app-card">
		<div class="app-card-head">
			<h2><i class="fa fa-list-alt"></i> Transactions</h2>
			<small>Newest first</small>
		</div>
		<div class="app-card-body" style="padding-bottom:.25rem;">
			<div class="app-search">
				<i class="fa fa-search"></i>
				<input type="search" id="soa_filter_text" placeholder="Search period, OR number, description" autocomplete="off">
			</div>
			<div class="app-chips" id="soa_chips">
				<button type="button" class="app-chip active" data-filter="all">All<span class="c"><?php echo (int) $counts['all']; ?></span></button>
				<button type="button" class="app-chip" data-filter="billing">Billing<span class="c"><?php echo (int) $counts['billing']; ?></span></button>
				<button type="button" class="app-chip" data-filter="payment">Payments<span class="c"><?php echo (int) $counts['payment']; ?></span></button>
				<button type="button" class="app-chip" data-filter="adjustment">Adjustments<span class="c"><?php echo (int) $counts['adjustment']; ?></span></button>
				<?php if (!empty($short_periods)) { ?>
				<button type="button" class="app-chip" data-filter="short">Underpaid<span class="c"><?php echo count($short_periods); ?></span></button>
				<?php } ?>
			</div>
		</div>
		<div id="soa_list">
			<?php if (empty($rows)) { ?>
			<div class="app-empty"><i class="fa fa-inbox"></i>No transactions found for this customer.</div>
			<?php } ?>
			<?php foreach ($rows as $r) {
				$entry = $r['entry'];
				$meta = isset($type_meta[$entry['type']]) ? $type_meta[$entry['type']] : array(ucfirst($entry['type']), 'muted', 'fa-circle-o');
				$is_debit = (float) $entry['debit'] > 0;
				$amount = $is_debit ? (float) $entry['debit'] : (float) $entry['credit'];
				$ts = strtotime($entry['date']);
				$date_label = $ts ? date('M j, Y', $ts) : $entry['date'];
				$search = strtolower($entry['description'] . ' ' . $entry['refno'] . ' ' . $date_label);
			?>
			<div class="soa-entry t-<?php echo $h($entry['type']); ?><?php echo $r['short'] ? ' is-short' : ''; ?>"
				data-group="<?php echo $h($r['group']); ?>" data-short="<?php echo $r['short'] ? '1' : '0'; ?>" data-search="<?php echo $h($search); ?>">
				<div class="soa-entry-main" role="button" tabindex="0" aria-expanded="false">
					<span class="soa-entry-ic"><i class="fa <?php echo $meta[2]; ?>"></i></span>
					<div class="soa-entry-body">
						<div class="soa-entry-top">
							<div class="soa-entry-title"><?php echo $h($entry['description']); ?></div>
							<div class="soa-entry-amt <?php echo $is_debit ? 'dr' : 'cr'; ?>"><?php echo ($is_debit ? '+ ' : '&minus; ') . $peso($amount); ?></div>
						</div>
						<div class="soa-entry-meta">
							<span><span class="app-badge <?php echo $meta[1]; ?>"><?php echo $h($meta[0]); ?></span> <?php echo $h($date_label); ?><?php echo $entry['refno'] !== '' ? ' &middot; Ref ' . $h($entry['refno']) : ''; ?></span>
							<span class="bal">Bal <?php echo $peso($entry['balance']); ?></span>
						</div>
						<?php if ($r['short']) { ?>
						<div class="soa-short-note"><i class="fa fa-exclamation-circle"></i>
							<?php if ($entry['type'] === 'payment') { ?>
								Underpaid &mdash; shortfall <?php echo $peso($r['short_amt']); ?> carried as balance
							<?php } else { ?>
								Underpayment / shortfall for this billing period: <?php echo $peso($r['short_amt']); ?>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="soa-entry-detail">
					<?php if ($entry['type'] === 'billing') { ?>
						<?php if (isset($entry['consumed'])) { ?>
						<div class="app-kv"><span class="k">Reading</span><span class="v"><?php echo $h($entry['previous_reading']); ?> &rarr; <?php echo $h($entry['reading']); ?></span></div>
						<div class="app-kv"><span class="k">Consumed</span><span class="v"><?php echo $h($entry['consumed']); ?> cu.m</span></div>
						<?php } ?>
						<?php if (isset($entry['unit_price']) && $entry['unit_price'] !== '') { ?>
						<div class="app-kv"><span class="k">Water charge</span><span class="v"><?php echo $peso($entry['unit_price']); ?></span></div>
						<?php } ?>
						<?php if (isset($entry['sc_discount']) && (float) $entry['sc_discount'] > 0) { ?>
						<div class="app-kv"><span class="k">Senior citizen discount</span><span class="v"><?php echo $peso($entry['sc_discount']); ?></span></div>
						<?php } ?>
						<?php if (isset($entry['maintenance_fee']) && (float) $entry['maintenance_fee'] > 0) { ?>
						<div class="app-kv"><span class="k">Maintenance fee</span><span class="v"><?php echo $peso($entry['maintenance_fee']); ?></span></div>
						<?php } ?>
						<?php if (isset($entry['penalty']) && $entry['penalty'] > 0) { ?>
						<div class="app-kv"><span class="k">Amount if paid after due date<?php echo !empty($entry['penalty_included']) ? ' (charged)' : ''; ?></span><span class="v"><?php echo $peso($entry['penalty']); ?></span></div>
						<?php } ?>
						<?php if (isset($entry['arrears']) && floatval($entry['arrears']) > 0) { ?>
						<div class="app-kv"><span class="k">Arrears on bill</span><span class="v"><?php echo $peso($entry['arrears']); ?></span></div>
						<?php } ?>
						<?php if (!empty($entry['due_date'])) { ?>
						<div class="app-kv"><span class="k">Due date</span><span class="v"><?php echo $h(strtotime($entry['due_date']) ? date('M j, Y', strtotime($entry['due_date'])) : $entry['due_date']); ?></span></div>
						<?php } ?>
						<div class="app-kv"><span class="k">Amount billed</span><span class="v"><?php echo $peso($entry['debit']); ?></span></div>
					<?php } elseif ($entry['type'] === 'leaking_discount') { ?>
						<div class="app-kv"><span class="k">Note</span><span class="v">Approved leaking discount applied to bill</span></div>
					<?php } elseif ($entry['type'] === 'leaking_payment') { ?>
						<div class="app-kv"><span class="k">Note</span><span class="v">Payment posted from Leaking Entry A/R</span></div>
					<?php } elseif ($entry['type'] === 'adjustment') { ?>
						<div class="app-kv"><span class="k">Note</span><span class="v">Posted AR Adjustment (Accounting)</span></div>
					<?php } ?>
					<?php if ($entry['type'] !== 'billing') { ?>
						<div class="app-kv"><span class="k"><?php echo $is_debit ? 'Debit' : 'Credit'; ?></span><span class="v"><?php echo $peso($amount); ?></span></div>
					<?php } ?>
					<div class="app-kv"><span class="k">Running balance</span><span class="v"><?php echo $peso($entry['balance']); ?></span></div>
				</div>
			</div>
			<?php } ?>
			<div class="app-empty" id="soa_no_match" style="display:none;"><i class="fa fa-search"></i>No transactions match your filter.</div>
		</div>
		<button type="button" class="soa-more" id="soa_more" style="display:none;">Show older transactions</button>
		<?php if (!empty($rows)) { ?>
		<div class="app-card-foot">
			<div class="app-kv"><span class="k">Total debit (billing)</span><span class="v"><?php echo $peso($total_debit); ?></span></div>
			<div class="app-kv"><span class="k">Total credit (payment)</span><span class="v"><?php echo $peso($total_credit); ?></span></div>
			<div class="app-kv"><span class="k">Balance</span><span class="v" style="color:<?php echo $current_balance > 0.009 ? 'var(--app-bad)' : 'var(--app-ok)'; ?>"><?php echo $peso($current_balance); ?></span></div>
		</div>
		<?php } ?>
	</div>

	<!-- Signatories -->
	<div class="app-card">
		<div class="app-card-head"><h2><i class="fa fa-pencil-square-o"></i> Signatories</h2></div>
		<div class="app-card-body soa-sig">
			<div><div class="lbl">Prepared by</div><div class="nm js-sig-prepared"><?php echo $h(strtoupper($prepared_name)); ?></div><div class="tt js-sig-prepared-title"><?php echo $h($prepared_title); ?></div></div>
			<div><div class="lbl">Verified correct</div><div class="nm js-sig-verified"><?php echo $h(strtoupper($verified_name)); ?></div><div class="tt js-sig-verified-title"><?php echo $h($verified_title); ?></div></div>
			<div><div class="lbl">Approved</div><div class="nm js-sig-approved"><?php echo $h(strtoupper($approved_name)); ?></div><div class="tt js-sig-approved-title"><?php echo $h($approved_title); ?></div></div>
		</div>
	</div>
</div>

<!-- Print-only A4 report -->
<div class="app-print-only soa-print">
	<div class="report-header">
		<div class="report-logo">
			<img src="<?php echo site_url();?>images/mroxas-logo-report.jpg" height="80px" alt="Labason Water District Logo">
		</div>
		<div class="report-title">STATEMENT OF ACCOUNT</div>
		<div class="report-subtitle">
			Customer ID: <?php echo $h($cid); ?>
			&nbsp; | &nbsp; Date/Time printed: <?php echo $h($printed_at); ?>
		</div>
	</div>

	<div class="panel">
		<div class="panel-heading"><strong>Customer Information</strong></div>
		<div class="panel-body">
			<table class="table table-bordered">
				<tr><td width="40%"><strong>Customer ID:</strong></td><td><?php echo $h($cid); ?></td></tr>
				<tr><td><strong>Name:</strong></td><td><?php echo $h($customer_name); ?></td></tr>
				<tr><td><strong>Address:</strong></td><td><?php echo $h(strtoupper($customer_info['address'])); ?></td></tr>
				<tr><td><strong>Zone:</strong></td><td><?php echo $h(isset($customer_info['zone']) ? strtoupper($customer_info['zone']) : 'N/A'); ?></td></tr>
			</table>
			<table class="table table-bordered">
				<tr><td width="40%"><strong>Meter Number:</strong></td><td><?php echo $h($customer_info['meter_number']); ?></td></tr>
				<tr><td><strong>Classification:</strong></td><td><?php echo $h(isset($customer_info['class_name']) ? $customer_info['class_name'] : 'N/A'); ?></td></tr>
				<tr><td><strong>Account Type:</strong></td><td><?php echo $h(isset($customer_info['cust_type_name']) ? $customer_info['cust_type_name'] : 'N/A'); ?></td></tr>
				<tr>
					<td><strong>Current Billing Balance:</strong></td>
					<td class="<?php echo ($current_balance > 0.009) ? 'soa-balance-alert' : ''; ?>">
						<strong class="<?php echo ($current_balance > 0.009) ? 'text-danger' : 'text-success'; ?>">PHP <?php echo number_format($current_balance, 2); ?></strong>
						<?php if ($current_balance > 0.009) { ?><br><small class="soa-balance-alert-note">Outstanding / underpaid balance</small><?php } ?>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<table class="soa-ledger-print" id="ledger_table_print">
		<colgroup><col class="col-date"><col class="col-ref"><col class="col-desc"><col class="col-amt"><col class="col-amt"><col class="col-amt"></colgroup>
		<thead>
			<tr><th>Date</th><th>Ref</th><th>Description</th><th>Debit</th><th>Credit</th><th>Balance</th></tr>
		</thead>
		<tbody>
			<?php if (empty($rows)) { ?>
			<tr><td colspan="6" style="text-align:center;">No transactions found for this customer.</td></tr>
			<?php } ?>
			<?php foreach ($rows as $r) {
				$entry = $r['entry'];
				$debit = $entry['debit'] > 0 ? number_format($entry['debit'], 2) : '';
				$credit = $entry['credit'] > 0 ? number_format($entry['credit'], 2) : '';
			?>
			<tr class="<?php echo $r['short'] ? 'soa-row-shortfall' : ''; ?>">
				<td><?php echo $h(date('d-m-Y', strtotime($entry['date']))); ?></td>
				<td><?php echo $h($entry['refno']); ?></td>
				<td>
					<?php echo $h($entry['description']); ?>
					<?php if ($r['short'] && $entry['type'] === 'payment') { ?>
						<br><small class="soa-shortfall-note">Underpaid — shortfall PHP <?php echo number_format($r['short_amt'], 2); ?> carried as balance</small>
					<?php } ?>
					<?php if ($entry['type'] == 'billing' && isset($entry['consumed'])) { ?>
						<br><small class="text-muted">
							Reading: <?php echo $h($entry['previous_reading']); ?> - <?php echo $h($entry['reading']); ?>
							(Consumed: <?php echo $h($entry['consumed']); ?> cu.m)
							<?php if (isset($entry['penalty']) && $entry['penalty'] > 0) { ?>
								| Penalty: PHP <?php echo number_format($entry['penalty'], 2); ?>
							<?php } ?>
							<?php if (isset($entry['arrears']) && floatval($entry['arrears']) > 0) { ?>
								| Arrears on bill: PHP <?php echo number_format((float) $entry['arrears'], 2); ?>
							<?php } ?>
						</small>
					<?php } elseif ($entry['type'] == 'leaking_discount') { ?>
						<br><small class="text-muted">Approved leaking discount applied to bill</small>
					<?php } elseif ($entry['type'] == 'leaking_payment') { ?>
						<br><small class="text-muted">Payment posted from Leaking Entry A/R</small>
					<?php } elseif ($entry['type'] == 'adjustment') { ?>
						<br><small class="text-muted">Posted AR Adjustment (Accounting)</small>
					<?php } ?>
				</td>
				<td><?php echo $debit ? 'PHP '.$debit : '-'; ?></td>
				<td><?php echo $credit ? 'PHP '.$credit : '-'; ?></td>
				<td class="<?php echo $r['short'] ? 'soa-balance-cell-alert' : ''; ?>"><strong>PHP <?php echo number_format($entry['balance'], 2); ?></strong></td>
			</tr>
			<?php } ?>
		</tbody>
		<?php if (!empty($rows)) { ?>
		<tfoot>
			<tr>
				<th colspan="3"><strong>Total:</strong></th>
				<th><strong>PHP <?php echo number_format($total_debit, 2); ?></strong></th>
				<th><strong>PHP <?php echo number_format($total_credit, 2); ?></strong></th>
				<th class="<?php echo $current_balance > 0.009 ? 'text-danger soa-balance-cell-alert' : 'text-success'; ?>"><strong>PHP <?php echo number_format($current_balance, 2); ?></strong></th>
			</tr>
		</tfoot>
		<?php } ?>
	</table>

	<div class="signature-block">
		<div class="sig-row">
			<div class="sig-item">
				<div class="sig-label">Prepared by:</div>
				<div class="sig-line js-sig-prepared"><?php echo $h(strtoupper($prepared_name)); ?></div>
				<div class="sig-title js-sig-prepared-title"><?php echo $h($prepared_title); ?></div>
			</div>
			<div class="sig-item">
				<div class="sig-label">Verified Correct:</div>
				<div class="sig-line js-sig-verified"><?php echo $h(strtoupper($verified_name)); ?></div>
				<div class="sig-title js-sig-verified-title"><?php echo $h($verified_title); ?></div>
			</div>
			<div class="sig-item">
				<div class="sig-label">Approved:</div>
				<div class="sig-line js-sig-approved"><?php echo $h(strtoupper($approved_name)); ?></div>
				<div class="sig-title js-sig-approved-title"><?php echo $h($approved_title); ?></div>
			</div>
		</div>
	</div>
</div>

<script>
(function () {
	'use strict';

	// Signatory names saved in this browser (same cookies as the old statement page).
	function getCookie(name) {
		var match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()[\]\\/+^])/g, '\\$1') + '=([^;]*)'));
		return match ? decodeURIComponent(match[1]) : '';
	}
	[['prepared', 'name'], ['prepared', 'title'], ['verified', 'name'], ['verified', 'title'], ['approved', 'name'], ['approved', 'title']].forEach(function (p) {
		var v = (getCookie('soa_sign_' + p[0] + '_' + p[1]) || '').trim();
		if (!v) { return; }
		var sel = '.js-sig-' + p[0] + (p[1] === 'title' ? '-title' : '');
		Array.prototype.forEach.call(document.querySelectorAll(sel), function (el) {
			el.textContent = p[1] === 'name' ? v.toUpperCase() : v;
		});
	});

	// Expand / collapse a transaction.
	var list = document.getElementById('soa_list');
	function toggle(main) {
		var item = main.parentNode;
		var open = !item.classList.contains('open');
		item.classList.toggle('open', open);
		main.setAttribute('aria-expanded', open ? 'true' : 'false');
	}
	list.addEventListener('click', function (e) {
		var main = e.target.closest ? e.target.closest('.soa-entry-main') : null;
		if (main) { toggle(main); }
	});
	list.addEventListener('keydown', function (e) {
		if ((e.key === 'Enter' || e.key === ' ') && e.target.classList.contains('soa-entry-main')) {
			e.preventDefault();
			toggle(e.target);
		}
	});

	// Filter chips + text filter + paging.
	var PAGE = 20;
	var limit = PAGE;
	var filter = 'all';
	var items = Array.prototype.slice.call(list.querySelectorAll('.soa-entry'));
	var more = document.getElementById('soa_more');
	var noMatch = document.getElementById('soa_no_match');
	var text = document.getElementById('soa_filter_text');

	function apply() {
		var q = (text.value || '').toLowerCase().trim();
		var shown = 0;
		var matched = 0;
		items.forEach(function (el) {
			var ok = filter === 'all'
				|| (filter === 'short' ? el.getAttribute('data-short') === '1' : el.getAttribute('data-group') === filter);
			if (ok && q) { ok = el.getAttribute('data-search').indexOf(q) !== -1; }
			if (ok) { matched++; }
			var vis = ok && shown < limit;
			if (vis) { shown++; }
			el.style.display = vis ? '' : 'none';
		});
		more.style.display = matched > shown ? '' : 'none';
		noMatch.style.display = (items.length && matched === 0) ? '' : 'none';
	}
	Array.prototype.forEach.call(document.querySelectorAll('#soa_chips .app-chip'), function (chip) {
		chip.addEventListener('click', function () {
			Array.prototype.forEach.call(document.querySelectorAll('#soa_chips .app-chip'), function (c) { c.classList.remove('active'); });
			chip.classList.add('active');
			filter = chip.getAttribute('data-filter');
			limit = PAGE;
			apply();
		});
	});
	text.addEventListener('input', function () { limit = PAGE; apply(); });
	more.addEventListener('click', function () { limit += PAGE; apply(); });
	apply();

	// Print uses the print-only report, which always lists every transaction.
	Array.prototype.forEach.call(document.querySelectorAll('.js-soa-print'), function (b) {
		b.addEventListener('click', function () { window.print(); });
	});
	<?php if ($auto_print) { ?>
	window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 300); });
	<?php } ?>
})();
</script>
