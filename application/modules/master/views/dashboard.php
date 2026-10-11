<?php
$d = isset($dash) ? $dash : array();
$monthNames = array(1=>'January','February','March','April','May','June','July','August','September','October','November','December');
$monthShort = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

if (!function_exists('lwd_peso')) {
	function lwd_peso($v, $dec = 2) { return '&#8369;' . number_format((float)$v, $dec); }
	function lwd_peso_short($v) {
		$v = (float)$v;
		if (abs($v) >= 1000000) return '&#8369;' . number_format($v / 1000000, 2) . 'M';
		if (abs($v) >= 1000)    return '&#8369;' . number_format($v / 1000, 1) . 'K';
		return '&#8369;' . number_format($v, 2);
	}
	function lwd_pct($a, $b) { return $b > 0 ? $a / $b * 100 : 0; }
}

// Collections
$collToday   = $d['coll_today'];
$collMtd     = $d['coll_mtd'];
$collPrevMtd = $d['coll_prev_mtd'];
$mtdDelta    = ($collPrevMtd['total'] > 0 && $collMtd['total'] > 0) ? ($collMtd['total'] - $collPrevMtd['total']) / $collPrevMtd['total'] * 100 : null;

// Latest billing period efficiency
$period = !empty($d['periods'][0]) ? $d['periods'][0] : null;
$prevPeriod = !empty($d['periods'][1]) ? $d['periods'][1] : null;
$eff = $period ? lwd_pct($period['paid'], $period['billed']) : 0;
$effPrev = $prevPeriod ? lwd_pct($prevPeriod['paid'], $prevPeriod['billed']) : null;
$effTone = $eff >= 95 ? 'success' : ($eff >= 85 ? 'warning' : 'danger');

// Receivables
$arTotal = 0; $arAccounts = 0;
foreach ($d['aging'] as $b) { $arTotal += $b['total']; $arAccounts += $b['accounts']; }

// Customers
$cust = $d['customers'];

// Tickets
$ticketLabels = array('Pending', 'Assigned', 'On going', 'Resolved', 'Un-Resolved', 'Resolved - Closed', 'Un-Resolved - Closed');
$ticketOpen = $d['tickets'][0] + $d['tickets'][1] + $d['tickets'][2] + $d['tickets'][3] + $d['tickets'][4];
$ticketAll = array_sum($d['tickets']);

// Leaking
$leak = $d['leaking'];

// Selected-year billing series
$yearBilled = array(); $yearPaid = array(); $yearUnpaid = array(); $yearEff = array(); $yearM3 = array();
$sumBilled = 0; $sumPaid = 0; $sumM3 = 0;
foreach ($d['by_month'] as $i => $r) {
	$yearBilled[] = round($r['billed'], 2);
	$yearPaid[]   = round($r['paid'], 2);
	$yearUnpaid[] = round($r['unpaid'], 2);
	$yearEff[]    = $r['billed'] > 0 ? round($r['paid'] / $r['billed'] * 100, 1) : null;
	$yearM3[]     = $r['consumed'];
	$sumBilled += $r['billed']; $sumPaid += $r['paid']; $sumM3 += $r['consumed'];
}

// Daily series
$dailyLabels = array(); $dailyTotals = array(); $dailyReceipts = array();
foreach ($d['daily'] as $r) {
	$dailyLabels[]   = date('M j', strtotime($r['date']));
	$dailyTotals[]   = round($r['total'], 2);
	$dailyReceipts[] = $r['receipts'];
}
$daily30 = array_sum($dailyTotals);

// Zones (sorted by unpaid for the chart)
$zones = $d['zones'];
usort($zones, function ($a, $b) { return $b['unpaid'] < $a['unpaid'] ? -1 : ($b['unpaid'] > $a['unpaid'] ? 1 : 0); });

// Consumption by category
$catColors = array('RESIDENTIAL' => '#2196F3', 'COMMERCIAL' => '#FF9800', 'GOVERNMENT' => '#4CAF50', 'BULKSALES' => '#9C27B0', 'UNCLASSIFIED' => '#9E9E9E');
$consDatasets = array();
foreach ($d['consumption'] as $cat => $series) {
	$consDatasets[] = array(
		'label' => ucwords(strtolower($cat)),
		'data' => array_values($series),
		'backgroundColor' => isset($catColors[$cat]) ? $catColors[$cat] : '#607D8B',
	);
}

// Collection breakdown: this month, or last month if nothing posted yet
$bd = $d['breakdown']; $bdLabel = 'This month';
if (empty($bd['channels']) && !empty($d['breakdown_prev']['channels'])) {
	$bd = $d['breakdown_prev'];
	$bdLabel = date('F Y', strtotime('first day of last month'));
}
$bdTotal = 0;
foreach ($bd['channels'] as $c) { $bdTotal += $c['total']; }

$mixTotal = array_sum(array_map(function ($r) { return (int)$r['total']; }, $d['mix']['category']));
?>
<style>
.lwd-dash{--lwd-radius:14px;--lwd-gap:12px;--lwd-blue:#1e6fd9;--lwd-ink:#24324a;--lwd-muted:#6b7a90;--lwd-line:#e8edf3}
.lwd-dash .lwd-head{display:flex;flex-direction:column;gap:10px;margin-bottom:var(--lwd-gap)}
.lwd-dash .lwd-title{font-size:1.35rem;font-weight:600;color:var(--lwd-ink);margin:0}
.lwd-dash .lwd-asof{color:var(--lwd-muted);font-size:.8rem}
.lwd-dash .lwd-tools{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.lwd-dash .lwd-tools select{width:auto;min-width:96px;border-radius:10px}
.lwd-dash .lwd-actions{display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;-webkit-overflow-scrolling:touch;scrollbar-width:none}
.lwd-dash .lwd-actions::-webkit-scrollbar{display:none}
.lwd-dash .lwd-chip{flex:0 0 auto;display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:999px;background:#fff;border:1px solid var(--lwd-line);color:var(--lwd-ink);font-size:.82rem;font-weight:500;white-space:nowrap;text-decoration:none!important;transition:all .15s}
.lwd-dash .lwd-chip:hover{border-color:var(--lwd-blue);color:var(--lwd-blue)}
.lwd-dash .lwd-chip.primary{background:var(--lwd-blue);border-color:var(--lwd-blue);color:#fff}

.lwd-dash .lwd-kpis{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:var(--lwd-gap);margin-bottom:var(--lwd-gap)}
.lwd-dash .lwd-kpi{position:relative;display:block;background:#fff;border-radius:var(--lwd-radius);padding:14px;border:1px solid var(--lwd-line);box-shadow:0 1px 2px rgba(16,24,40,.04);color:var(--lwd-ink);text-decoration:none!important;overflow:hidden;transition:transform .15s,box-shadow .15s}
.lwd-dash a.lwd-kpi:hover{transform:translateY(-2px);box-shadow:0 6px 18px rgba(16,24,40,.08)}
.lwd-dash .lwd-kpi .ico{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1rem;margin-bottom:10px}
.lwd-dash .lwd-kpi .lbl{font-size:.74rem;color:var(--lwd-muted);text-transform:uppercase;letter-spacing:.03em;font-weight:600;line-height:1.2}
.lwd-dash .lwd-kpi .val{font-size:1.2rem;font-weight:700;line-height:1.25;margin-top:2px;word-break:break-word}
.lwd-dash .lwd-kpi .sub{font-size:.75rem;color:var(--lwd-muted);margin-top:4px;line-height:1.3}
.lwd-dash .lwd-kpi .bar{height:6px;border-radius:6px;background:#eef2f7;margin-top:8px;overflow:hidden}
.lwd-dash .lwd-kpi .bar>span{display:block;height:100%;border-radius:6px}
.lwd-dash .t-blue{background:#e8f1fd;color:#1e6fd9}.lwd-dash .t-green{background:#e6f6ec;color:#1a8f47}
.lwd-dash .t-amber{background:#fff4e0;color:#c77700}.lwd-dash .t-red{background:#fdecec;color:#d23b3b}
.lwd-dash .t-teal{background:#e3f6f7;color:#11838c}.lwd-dash .t-purple{background:#f1eafc;color:#7046c2}
.lwd-dash .t-cyan{background:#e5f5fd;color:#0a83b8}.lwd-dash .t-slate{background:#eef1f5;color:#4a5a70}
.lwd-dash .up{color:#1a8f47;font-weight:600}.lwd-dash .down{color:#d23b3b;font-weight:600}

.lwd-dash .lwd-card{background:#fff;border-radius:var(--lwd-radius);border:1px solid var(--lwd-line);box-shadow:0 1px 2px rgba(16,24,40,.04);padding:14px;margin-bottom:var(--lwd-gap);min-width:0}
.lwd-dash .lwd-card h2{font-size:1rem;font-weight:600;color:var(--lwd-ink);margin:0}
.lwd-dash .lwd-card .hint{font-size:.76rem;color:var(--lwd-muted);margin:2px 0 0}
.lwd-dash .lwd-card-hdr{display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:10px;flex-wrap:wrap}
.lwd-dash .lwd-total{line-height:1.2}
.lwd-dash .lwd-total b{font-size:1rem;color:var(--lwd-ink)}
.lwd-dash .lwd-total small{color:var(--lwd-muted);margin-left:4px}
.lwd-dash .lwd-chart{position:relative;height:240px}
.lwd-dash .lwd-chart.tall{height:280px}
.lwd-dash .lwd-chart.short{height:200px}
.lwd-dash .lwd-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:8px;margin-bottom:10px}
.lwd-dash .lwd-stats div{background:#f7f9fc;border-radius:10px;padding:8px}
.lwd-dash .lwd-stats small{display:block;font-size:.68rem;color:var(--lwd-muted);text-transform:uppercase;font-weight:600}
.lwd-dash .lwd-stats b{font-size:.9rem;color:var(--lwd-ink)}

.lwd-dash .lwd-insights{list-style:none;margin:0;padding:0;display:grid;gap:8px}
.lwd-dash .lwd-insights li{display:flex;gap:10px;align-items:flex-start;padding:10px 12px;border-radius:10px;font-size:.85rem;line-height:1.4;color:var(--lwd-ink);border-left:4px solid}
.lwd-dash .lwd-insights li i{margin-top:2px;width:16px;text-align:center}
.lwd-dash .lwd-insights .good{background:#effaf3;border-color:#1a8f47}.lwd-dash .lwd-insights .good i{color:#1a8f47}
.lwd-dash .lwd-insights .warn{background:#fff8eb;border-color:#e09a00}.lwd-dash .lwd-insights .warn i{color:#c77700}
.lwd-dash .lwd-insights .bad{background:#fdf1f1;border-color:#d23b3b}.lwd-dash .lwd-insights .bad i{color:#d23b3b}
.lwd-dash .lwd-insights .info{background:#eef5fe;border-color:#1e6fd9}.lwd-dash .lwd-insights .info i{color:#1e6fd9}

.lwd-dash .lwd-grid{display:grid;grid-template-columns:minmax(0,1fr);gap:0 var(--lwd-gap)}
.lwd-dash .lwd-seg{display:inline-flex;background:#f1f4f8;border-radius:10px;padding:3px}
.lwd-dash .lwd-seg button{border:0;background:transparent;padding:5px 10px;border-radius:8px;font-size:.76rem;font-weight:600;color:var(--lwd-muted);cursor:pointer}
.lwd-dash .lwd-seg button.active{background:#fff;color:var(--lwd-blue);box-shadow:0 1px 2px rgba(0,0,0,.08)}

.lwd-dash .lwd-legend{list-style:none;padding:0;margin:10px 0 0;display:grid;gap:6px}
.lwd-dash .lwd-legend li{display:flex;align-items:center;gap:8px;font-size:.82rem;color:var(--lwd-ink)}
.lwd-dash .lwd-legend .sw{width:10px;height:10px;border-radius:3px;flex:0 0 auto}
.lwd-dash .lwd-legend .num{margin-left:auto;font-weight:600;text-align:right}
.lwd-dash .lwd-legend .num small{display:block;font-weight:400;color:var(--lwd-muted);font-size:.72rem}

.lwd-dash .lwd-bars{display:grid;gap:10px}
.lwd-dash .lwd-bars .row1{display:flex;justify-content:space-between;gap:8px;font-size:.82rem;color:var(--lwd-ink)}
.lwd-dash .lwd-bars .row1 span:first-child{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.lwd-dash .lwd-bars .row1 b{white-space:nowrap}
.lwd-dash .lwd-bars .track{height:8px;background:#eef2f7;border-radius:8px;overflow:hidden;margin-top:4px}
.lwd-dash .lwd-bars .track span{display:block;height:100%;border-radius:8px}
.lwd-dash .lwd-sub{font-size:.72rem;text-transform:uppercase;letter-spacing:.04em;color:var(--lwd-muted);font-weight:700;margin:14px 0 8px}

.lwd-dash .lwd-debtors{list-style:none;margin:0;padding:0}
.lwd-dash .lwd-debtors li{display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--lwd-line)}
.lwd-dash .lwd-debtors li:last-child{border-bottom:0}
.lwd-dash .lwd-debtors .rk{width:26px;height:26px;border-radius:50%;background:#f1f4f8;color:var(--lwd-muted);font-size:.75rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex:0 0 auto}
.lwd-dash .lwd-debtors .who{min-width:0;flex:1}
.lwd-dash .lwd-debtors .who b{display:block;font-size:.85rem;color:var(--lwd-ink);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.lwd-dash .lwd-debtors .who small{color:var(--lwd-muted);font-size:.74rem}
.lwd-dash .lwd-debtors .amt{text-align:right;white-space:nowrap}
.lwd-dash .lwd-debtors .amt b{display:block;font-size:.88rem;color:#d23b3b}
.lwd-dash .lwd-debtors .amt small{font-size:.72rem;color:var(--lwd-muted)}
.lwd-dash .lwd-empty{text-align:center;color:var(--lwd-muted);font-size:.85rem;padding:30px 10px}
.lwd-dash .lwd-empty i{display:block;font-size:1.8rem;margin-bottom:8px;opacity:.5}

@media (min-width:576px){
	.lwd-dash .lwd-kpi .val{font-size:1.4rem}
	.lwd-dash .lwd-chart{height:270px}
	.lwd-dash .lwd-chart.tall{height:320px}
}
@media (min-width:768px){
	.lwd-dash{--lwd-gap:16px}
	.lwd-dash .lwd-head{flex-direction:row;justify-content:space-between;align-items:flex-end}
	.lwd-dash .lwd-kpis{grid-template-columns:repeat(4,minmax(0,1fr))}
	.lwd-dash .lwd-kpi{padding:16px}
	.lwd-dash .lwd-card{padding:18px}
	.lwd-dash .lwd-total{text-align:right}
	.lwd-dash .lwd-total small{display:block;margin-left:0}
}
@media (min-width:992px){
	.lwd-dash .lwd-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
	.lwd-dash .lwd-grid .span-2{grid-column:1 / -1}
	.lwd-dash .lwd-chart.tall{height:340px}
}
@media (max-width:767.98px){
	.lwd-dash .lwd-head .lwd-actions{margin:0 -1rem;padding:0 1rem 4px}
}
@media print{
	.lwd-dash .lwd-actions,.lwd-dash .lwd-tools{display:none!important}
	.lwd-dash .lwd-card,.lwd-dash .lwd-kpi{break-inside:avoid;box-shadow:none}
}
</style>

<main id="js-page-content" role="main" class="page-content lwd-dash">
	<ol class="breadcrumb page-breadcrumb d-none d-md-flex">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>dashboard/">Home</a></li>
		<li class="breadcrumb-item active">Dashboard</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="lwd-head">
		<div>
			<h1 class="lwd-title"><i class="fal fa-chart-pie mr-1 text-primary"></i> Operations Dashboard</h1>
			<div class="lwd-asof">As of <?php echo date('l, F j, Y g:i A'); ?></div>
		</div>
		<div class="lwd-tools">
			<label for="yearSelector" class="mb-0 small text-muted fw-500">Report year</label>
			<select id="yearSelector" class="form-control form-control-sm">
				<?php foreach ((array)$available_years as $y): ?>
					<option value="<?php echo (int)$y; ?>" <?php echo ((int)$selected_year === (int)$y) ? 'selected' : ''; ?>><?php echo (int)$y; ?></option>
				<?php endforeach; ?>
			</select>
			<button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()" title="Print dashboard"><i class="fal fa-print"></i></button>
		</div>
	</div>

	<nav class="lwd-actions mb-3" aria-label="Quick actions">
		<a class="lwd-chip primary" href="<?php echo ADMIN_URL; ?>addpaymentcustomer/add"><i class="fal fa-cash-register"></i> Add Payment</a>
		<a class="lwd-chip" href="<?php echo ADMIN_URL; ?>addmetercustomerreading"><i class="fal fa-tachometer-alt"></i> Meter Reading</a>
		<a class="lwd-chip" href="<?php echo ADMIN_URL; ?>adddailyreport"><i class="fal fa-file-invoice"></i> Daily Collection Report</a>
		<a class="lwd-chip" href="<?php echo ADMIN_URL; ?>addcustomer"><i class="fal fa-users"></i> Customers</a>
		<a class="lwd-chip" href="<?php echo ADMIN_URL; ?>Leakingentry"><i class="fal fa-tint"></i> Leaking Ledger</a>
		<a class="lwd-chip" href="<?php echo ADMIN_URL; ?>technicalproblems"><i class="fal fa-tools"></i> Service Tickets</a>
	</nav>

	<!-- KPI cards -->
	<section class="lwd-kpis" aria-label="Key figures">
		<a class="lwd-kpi" href="<?php echo ADMIN_URL; ?>adddailyreport">
			<div class="ico t-green"><i class="fal fa-hand-holding-usd"></i></div>
			<div class="lbl">Collected today</div>
			<div class="val"><?php echo lwd_peso($collToday['total']); ?></div>
			<div class="sub"><?php echo number_format($collToday['receipts']); ?> official receipts</div>
		</a>
		<a class="lwd-kpi" href="<?php echo ADMIN_URL; ?>adddailyreport">
			<div class="ico t-blue"><i class="fal fa-coins"></i></div>
			<div class="lbl">Collected this month</div>
			<div class="val"><?php echo lwd_peso($collMtd['total']); ?></div>
			<div class="sub">
				<?php if ($mtdDelta !== null): ?>
					<span class="<?php echo $mtdDelta >= 0 ? 'up' : 'down'; ?>"><i class="fal fa-arrow-<?php echo $mtdDelta >= 0 ? 'up' : 'down'; ?>"></i> <?php echo number_format(abs($mtdDelta), 1); ?>%</span> vs same days last month
				<?php else: ?>
					<?php echo number_format($collMtd['receipts']); ?> receipts &middot; last month <?php echo lwd_peso_short($d['coll_prev_month']['total']); ?>
				<?php endif; ?>
			</div>
		</a>
		<div class="lwd-kpi">
			<div class="ico t-<?php echo $effTone === 'success' ? 'green' : ($effTone === 'warning' ? 'amber' : 'red'); ?>"><i class="fal fa-percentage"></i></div>
			<div class="lbl">Collection efficiency</div>
			<div class="val"><?php echo number_format($eff, 1); ?>%</div>
			<div class="bar"><span class="bg-<?php echo $effTone; ?>" style="width:<?php echo min(100, round($eff, 1)); ?>%"></span></div>
			<div class="sub">
				<?php echo $period ? $monthNames[(int)$period['m']] . ' ' . (int)$period['y'] . ' billing' : 'No billing yet'; ?>
				<?php if ($effPrev !== null): ?>
					&middot; <span class="<?php echo $eff >= $effPrev ? 'up' : 'down'; ?>"><?php echo ($eff >= $effPrev ? '+' : '&minus;') . number_format(abs($eff - $effPrev), 1); ?> pts</span>
				<?php endif; ?>
			</div>
		</div>
		<a class="lwd-kpi" href="#lwd-receivables">
			<div class="ico t-red"><i class="fal fa-file-invoice-dollar"></i></div>
			<div class="lbl">Unpaid water bills</div>
			<div class="val"><?php echo lwd_peso($arTotal); ?></div>
			<div class="sub"><?php echo number_format($arAccounts); ?> accounts &middot; before penalties</div>
		</a>
		<a class="lwd-kpi" href="<?php echo ADMIN_URL; ?>addcustomer">
			<div class="ico t-purple"><i class="fal fa-house-user"></i></div>
			<div class="lbl">Active connections</div>
			<div class="val"><?php echo number_format((int)$cust['active']); ?></div>
			<div class="sub">+<?php echo number_format((int)$cust['new_since']); ?> new this month<?php echo (int)$cust['inactive'] > 0 ? ' &middot; ' . number_format((int)$cust['inactive']) . ' inactive' : ''; ?></div>
		</a>
		<a class="lwd-kpi" href="<?php echo ADMIN_URL; ?>addmetercustomerreading">
			<div class="ico t-cyan"><i class="fal fa-water"></i></div>
			<div class="lbl">Water billed</div>
			<div class="val"><?php echo $period ? number_format($period['consumed']) : 0; ?> m&sup3;</div>
			<div class="sub">
				<?php if ($period && $period['bills'] > 0): ?>
					<?php echo $monthNames[(int)$period['m']]; ?> &middot; avg <?php echo number_format($period['consumed'] / $period['bills'], 1); ?> m&sup3; per account
				<?php else: ?>No readings yet<?php endif; ?>
			</div>
		</a>
		<a class="lwd-kpi" href="<?php echo ADMIN_URL; ?>Leakingentry">
			<div class="ico t-teal"><i class="fal fa-tint"></i></div>
			<div class="lbl">Leak repair receivable</div>
			<div class="val"><?php echo lwd_peso($leak['outstanding']); ?></div>
			<div class="sub"><?php echo number_format((int)$leak['open_accounts']); ?> open ledgers<?php echo (int)$leak['pending'] > 0 ? ' &middot; <span class="down">' . (int)$leak['pending'] . ' for approval</span>' : ''; ?></div>
		</a>
		<a class="lwd-kpi" href="<?php echo ADMIN_URL; ?>technicalproblems">
			<div class="ico t-amber"><i class="fal fa-tools"></i></div>
			<div class="lbl">Open service tickets</div>
			<div class="val"><?php echo number_format($ticketOpen); ?></div>
			<div class="sub"><?php echo number_format($d['tickets'][0]); ?> pending &middot; <?php echo number_format($d['tickets'][5]); ?> resolved &amp; closed</div>
		</a>
	</section>

	<!-- Insights -->
	<?php if (!empty($d['insights'])): ?>
	<section class="lwd-card" aria-label="Management insights">
		<div class="lwd-card-hdr">
			<div>
				<h2><i class="fal fa-lightbulb-on text-warning mr-1"></i> Management insights</h2>
				<p class="hint">Automatically summarised from today's figures.</p>
			</div>
		</div>
		<ul class="lwd-insights">
			<?php foreach ($d['insights'] as $ins): ?>
				<li class="<?php echo $ins['tone']; ?>"><i class="fal <?php echo $ins['icon']; ?>"></i><span><?php echo $ins['text']; ?></span></li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php endif; ?>

	<div class="lwd-grid">
		<!-- Billing vs collection -->
		<section class="lwd-card span-2">
			<div class="lwd-card-hdr">
				<div>
					<h2>Billing vs collection &middot; <?php echo (int)$selected_year; ?></h2>
					<p class="hint">Per billing month: amount billed, amount already paid, and the collection efficiency rate.</p>
				</div>
			</div>
			<div class="lwd-stats">
				<div><small>Billed</small><b><?php echo lwd_peso_short($sumBilled); ?></b></div>
				<div><small>Paid</small><b><?php echo lwd_peso_short($sumPaid); ?></b></div>
				<div><small>Efficiency</small><b><?php echo number_format(lwd_pct($sumPaid, $sumBilled), 1); ?>%</b></div>
			</div>
			<div class="lwd-chart tall"><canvas id="chartBilling" aria-label="Billing versus collection chart" role="img"></canvas></div>
		</section>

		<!-- Daily collections -->
		<section class="lwd-card span-2">
			<div class="lwd-card-hdr">
				<div>
					<h2>Daily collections &middot; last 30 days</h2>
					<p class="hint">Cash received per day. Peaks usually follow billing due dates.</p>
				</div>
				<div class="lwd-total">
					<b><?php echo lwd_peso($daily30); ?></b>
					<small><?php echo number_format(array_sum($dailyReceipts)); ?> receipts</small>
				</div>
			</div>
			<div class="lwd-chart"><canvas id="chartDaily" aria-label="Daily collections chart" role="img"></canvas></div>
		</section>

		<!-- Receivable aging -->
		<section class="lwd-card" id="lwd-receivables">
			<div class="lwd-card-hdr">
				<div>
					<h2>Unpaid bills by age</h2>
					<p class="hint">Accounts grouped by how many bills are still unpaid.</p>
				</div>
			</div>
			<div class="lwd-chart short"><canvas id="chartAging" aria-label="Receivable aging chart" role="img"></canvas></div>
			<?php
			$agingMeta = array(
				1 => array('1 unpaid bill (current)', '#f6b73c'),
				2 => array('2 unpaid bills (overdue)', '#f08a3c'),
				3 => array('3+ unpaid bills (for disconnection)', '#d23b3b'),
			);
			?>
			<ul class="lwd-legend">
				<?php foreach ($agingMeta as $k => $meta): ?>
				<li><span class="sw" style="background:<?php echo $meta[1]; ?>"></span><?php echo $meta[0]; ?>
					<span class="num"><?php echo lwd_peso($d['aging'][$k]['total']); ?><small><?php echo number_format($d['aging'][$k]['accounts']); ?> accounts</small></span></li>
				<?php endforeach; ?>
			</ul>
		</section>

		<!-- Zones -->
		<section class="lwd-card">
			<div class="lwd-card-hdr">
				<div>
					<h2>Zones</h2>
					<p class="hint" id="zoneHint">Unpaid bills per zone, highest first.</p>
				</div>
				<div class="lwd-seg" role="tablist">
					<button type="button" class="active" data-zone="unpaid">Unpaid</button>
					<button type="button" data-zone="connections">Connections</button>
				</div>
			</div>
			<div class="lwd-chart tall"><canvas id="chartZones" aria-label="Zone chart" role="img"></canvas></div>
		</section>

		<!-- Consumption -->
		<section class="lwd-card span-2">
			<div class="lwd-card-hdr">
				<div>
					<h2>Water consumption &middot; <?php echo (int)$selected_year; ?></h2>
					<p class="hint">Cubic meters billed per month by customer classification.</p>
				</div>
				<div class="lwd-total">
					<b><?php echo number_format($sumM3); ?> m&sup3;</b>
					<small>total for the year</small>
				</div>
			</div>
			<div class="lwd-chart"><canvas id="chartConsumption" aria-label="Water consumption chart" role="img"></canvas></div>
		</section>

		<!-- Customer mix -->
		<section class="lwd-card">
			<div class="lwd-card-hdr">
				<div>
					<h2>Customer profile</h2>
					<p class="hint">Active connections by classification and account type.</p>
				</div>
			</div>
			<div class="lwd-chart short"><canvas id="chartMix" aria-label="Customer classification chart" role="img"></canvas></div>
			<div class="lwd-sub">Account type</div>
			<div class="lwd-bars">
				<?php $typeColors = array('#1e6fd9', '#1a8f47', '#7046c2', '#c77700'); $i = 0; ?>
				<?php foreach ($d['mix']['type'] as $t): $pct = lwd_pct($t['total'], $mixTotal); ?>
				<div>
					<div class="row1"><span><?php echo htmlspecialchars($t['label']); ?></span><b><?php echo number_format($t['total']); ?> <small class="text-muted">(<?php echo number_format($pct, 1); ?>%)</small></b></div>
					<div class="track"><span style="width:<?php echo round($pct, 1); ?>%;background:<?php echo $typeColors[$i++ % 4]; ?>"></span></div>
				</div>
				<?php endforeach; ?>
			</div>
		</section>

		<!-- Collection breakdown -->
		<section class="lwd-card">
			<div class="lwd-card-hdr">
				<div>
					<h2>Collections by channel &amp; cashier</h2>
					<p class="hint"><?php echo htmlspecialchars($bdLabel); ?> &middot; <?php echo lwd_peso($bdTotal); ?></p>
				</div>
			</div>
			<?php if (empty($bd['channels'])): ?>
				<div class="lwd-empty"><i class="fal fa-receipt"></i>No collections recorded yet.</div>
			<?php else: ?>
				<div class="lwd-sub mt-0">Payment channel</div>
				<div class="lwd-bars">
					<?php foreach ($bd['channels'] as $c): $pct = lwd_pct($c['total'], $bdTotal); ?>
					<div>
						<div class="row1"><span><?php echo htmlspecialchars(ucfirst($c['label'])); ?> <small class="text-muted">&middot; <?php echo number_format($c['receipts']); ?> receipts</small></span><b><?php echo lwd_peso($c['total']); ?></b></div>
						<div class="track"><span class="bg-success" style="width:<?php echo round($pct, 1); ?>%"></span></div>
					</div>
					<?php endforeach; ?>
				</div>
				<div class="lwd-sub">Cashier</div>
				<div class="lwd-bars">
					<?php foreach ($bd['cashiers'] as $c): $pct = lwd_pct($c['total'], $bdTotal); ?>
					<div>
						<div class="row1"><span><?php echo htmlspecialchars($c['label']); ?> <small class="text-muted">&middot; <?php echo number_format($c['receipts']); ?></small></span><b><?php echo lwd_peso($c['total']); ?></b></div>
						<div class="track"><span class="bg-primary" style="width:<?php echo round($pct, 1); ?>%"></span></div>
					</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</section>

		<!-- Top unpaid accounts -->
		<section class="lwd-card">
			<div class="lwd-card-hdr">
				<div>
					<h2>Largest unpaid accounts</h2>
					<p class="hint">Top 10 by unpaid bill amount. Use for collection follow-up.</p>
				</div>
				<a href="<?php echo ADMIN_URL; ?>addpaymentcustomer/add" class="btn btn-xs btn-outline-primary">Collect</a>
			</div>
			<?php if (empty($d['top_unpaid'])): ?>
				<div class="lwd-empty"><i class="fal fa-check-circle"></i>No unpaid bills. Great job!</div>
			<?php else: ?>
			<ol class="lwd-debtors">
				<?php foreach ($d['top_unpaid'] as $i => $r): ?>
				<li>
					<span class="rk"><?php echo $i + 1; ?></span>
					<span class="who"><b><?php echo htmlspecialchars($r['full_name'] ? $r['full_name'] : $r['customer_id']); ?></b>
						<small><?php echo htmlspecialchars($r['customer_id']); ?><?php echo $r['zone'] ? ' &middot; ' . htmlspecialchars($r['zone']) : ''; ?></small></span>
					<span class="amt"><b><?php echo lwd_peso($r['amt']); ?></b><small><?php echo (int)$r['bills']; ?> bill<?php echo (int)$r['bills'] > 1 ? 's' : ''; ?></small></span>
				</li>
				<?php endforeach; ?>
			</ol>
			<?php endif; ?>
		</section>

		<!-- Tickets -->
		<section class="lwd-card">
			<div class="lwd-card-hdr">
				<div>
					<h2>Service tickets</h2>
					<p class="hint">Technical problems by status.</p>
				</div>
				<a href="<?php echo ADMIN_URL; ?>technicalproblems" class="btn btn-xs btn-outline-primary">Open</a>
			</div>
			<?php if ($ticketAll === 0): ?>
				<div class="lwd-empty"><i class="fal fa-clipboard-check"></i>No service tickets recorded.</div>
			<?php else: ?>
				<div class="lwd-chart short"><canvas id="chartTickets" aria-label="Service tickets chart" role="img"></canvas></div>
			<?php endif; ?>
		</section>
	</div>
</main>

<?php include('footer.php'); ?>
<?php include('messageboard_popup.php'); ?>

<script src="<?php echo base_url(); ?>sa4/js/statistics/chartjs/chartjs.bundle.js"></script>
<script type="text/javascript">
$(function () {
	if (typeof Chart === 'undefined') { return; }

	var mobile = window.matchMedia('(max-width: 575.98px)').matches;
	Chart.defaults.global.defaultFontFamily = "'Roboto', 'Helvetica Neue', Arial, sans-serif";
	Chart.defaults.global.defaultFontColor = '#6b7a90';
	Chart.defaults.global.defaultFontSize = mobile ? 10 : 12;
	Chart.defaults.global.maintainAspectRatio = false;
	Chart.defaults.global.legend.labels.boxWidth = 10;
	Chart.defaults.global.legend.labels.usePointStyle = true;

	function peso(v) { return '\u20B1' + Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
	function pesoShort(v) {
		v = Number(v || 0);
		if (Math.abs(v) >= 1e6) return '\u20B1' + (v / 1e6).toFixed(1) + 'M';
		if (Math.abs(v) >= 1e3) return '\u20B1' + (v / 1e3).toFixed(0) + 'K';
		return '\u20B1' + v;
	}
	var grid = { color: 'rgba(0,0,0,.05)', zeroLineColor: 'rgba(0,0,0,.1)', drawBorder: false };
	var months = <?php echo json_encode($monthShort); ?>;

	// Billing vs collection
	new Chart(document.getElementById('chartBilling'), {
		type: 'bar',
		data: {
			labels: months,
			datasets: [
				{ type: 'line', label: 'Efficiency %', data: <?php echo json_encode($yearEff); ?>, yAxisID: 'pct', borderColor: '#1a8f47', backgroundColor: '#1a8f47', fill: false, borderWidth: 2, pointRadius: 3, spanGaps: true },
				{ label: 'Billed', data: <?php echo json_encode($yearBilled); ?>, backgroundColor: 'rgba(30,111,217,.25)', borderColor: '#1e6fd9', borderWidth: 1, yAxisID: 'amt' },
				{ label: 'Paid', data: <?php echo json_encode($yearPaid); ?>, backgroundColor: '#1e6fd9', yAxisID: 'amt' },
				{ label: 'Unpaid', data: <?php echo json_encode($yearUnpaid); ?>, backgroundColor: '#f08a3c', yAxisID: 'amt', hidden: mobile }
			]
		},
		options: {
			tooltips: { mode: 'index', intersect: false, callbacks: { label: function (t, data) {
				var ds = data.datasets[t.datasetIndex];
				return ' ' + ds.label + ': ' + (ds.yAxisID === 'pct' ? (t.yLabel === null ? '—' : t.yLabel + '%') : peso(t.yLabel));
			} } },
			legend: { position: 'bottom' },
			scales: {
				xAxes: [{ gridLines: { display: false }, barPercentage: .8, categoryPercentage: .7 }],
				yAxes: [
					{ id: 'amt', position: 'left', gridLines: grid, ticks: { beginAtZero: true, callback: pesoShort, maxTicksLimit: 6 } },
					{ id: 'pct', position: 'right', gridLines: { display: false }, ticks: { min: 0, max: 100, stepSize: mobile ? 50 : 25, callback: function (v) { return v + '%'; } } }
				]
			}
		}
	});

	// Daily collections
	var dailyReceipts = <?php echo json_encode($dailyReceipts); ?>;
	var ctxDaily = document.getElementById('chartDaily').getContext('2d');
	var grad = ctxDaily.createLinearGradient(0, 0, 0, 260);
	grad.addColorStop(0, 'rgba(26,143,71,.35)');
	grad.addColorStop(1, 'rgba(26,143,71,0)');
	new Chart(ctxDaily, {
		type: 'line',
		data: {
			labels: <?php echo json_encode($dailyLabels); ?>,
			datasets: [{ label: 'Collected', data: <?php echo json_encode($dailyTotals); ?>, borderColor: '#1a8f47', backgroundColor: grad, borderWidth: 2, pointRadius: mobile ? 0 : 2, pointHoverRadius: 5, lineTension: .3, fill: true }]
		},
		options: {
			legend: { display: false },
			tooltips: { mode: 'index', intersect: false, callbacks: { label: function (t) {
				return [' ' + peso(t.yLabel), ' ' + dailyReceipts[t.index] + ' receipts'];
			} } },
			scales: {
				xAxes: [{ gridLines: { display: false }, ticks: { autoSkip: true, maxTicksLimit: mobile ? 6 : 15, maxRotation: 0 } }],
				yAxes: [{ gridLines: grid, ticks: { beginAtZero: true, callback: pesoShort, maxTicksLimit: 5 } }]
			}
		}
	});

	// Receivable aging
	new Chart(document.getElementById('chartAging'), {
		type: 'doughnut',
		data: {
			labels: ['1 unpaid bill', '2 unpaid bills', '3+ unpaid bills'],
			datasets: [{ data: [<?php echo round($d['aging'][1]['total'], 2); ?>, <?php echo round($d['aging'][2]['total'], 2); ?>, <?php echo round($d['aging'][3]['total'], 2); ?>], backgroundColor: ['#f6b73c', '#f08a3c', '#d23b3b'], borderWidth: 2 }]
		},
		options: {
			cutoutPercentage: 65,
			legend: { display: false },
			tooltips: { callbacks: { label: function (t, data) { return ' ' + data.labels[t.index] + ': ' + peso(data.datasets[0].data[t.index]); } } }
		}
	});

	// Zones
	var zones = <?php echo json_encode(array_map(function ($z) { return array('zone' => $z['zone'], 'unpaid' => round((float)$z['unpaid'], 2), 'connections' => (int)$z['connections'], 'accounts' => (int)$z['unpaid_accounts']); }, $zones)); ?>;
	var zoneMode = 'unpaid';
	function zoneSeries(mode) {
		var rows = zones.slice().sort(function (a, b) { return b[mode] - a[mode]; });
		return { labels: rows.map(function (r) { return r.zone; }), data: rows.map(function (r) { return r[mode]; }), rows: rows };
	}
	var zs = zoneSeries(zoneMode);
	var zoneChart = new Chart(document.getElementById('chartZones'), {
		type: 'horizontalBar',
		data: { labels: zs.labels, datasets: [{ label: 'Unpaid', data: zs.data, backgroundColor: '#d23b3b', barPercentage: .7 }] },
		options: {
			legend: { display: false },
			tooltips: { callbacks: { label: function (t) {
				var r = zs.rows[t.index];
				return zoneMode === 'unpaid'
					? [' Unpaid: ' + peso(r.unpaid), ' ' + r.accounts + ' accounts with unpaid bills', ' ' + r.connections + ' connections']
					: [' ' + r.connections.toLocaleString() + ' connections', ' Unpaid: ' + peso(r.unpaid)];
			} } },
			scales: {
				xAxes: [{ gridLines: grid, ticks: { beginAtZero: true, maxTicksLimit: 5, callback: function (v) { return zoneMode === 'unpaid' ? pesoShort(v) : v; } } }],
				yAxes: [{ gridLines: { display: false } }]
			}
		}
	});
	$('.lwd-seg [data-zone]').on('click', function () {
		zoneMode = $(this).data('zone');
		$(this).addClass('active').siblings().removeClass('active');
		zs = zoneSeries(zoneMode);
		zoneChart.data.labels = zs.labels;
		zoneChart.data.datasets[0].data = zs.data;
		zoneChart.data.datasets[0].backgroundColor = zoneMode === 'unpaid' ? '#d23b3b' : '#7046c2';
		zoneChart.update();
		$('#zoneHint').text(zoneMode === 'unpaid' ? 'Unpaid bills per zone, highest first.' : 'Active connections per zone, largest first.');
	});

	// Consumption
	new Chart(document.getElementById('chartConsumption'), {
		type: 'bar',
		data: { labels: months, datasets: <?php echo json_encode($consDatasets); ?> },
		options: {
			legend: { position: 'bottom' },
			tooltips: { mode: 'index', intersect: false, callbacks: { label: function (t, data) {
				return ' ' + data.datasets[t.datasetIndex].label + ': ' + Number(t.yLabel).toLocaleString() + ' m\u00B3';
			} } },
			scales: {
				xAxes: [{ stacked: true, gridLines: { display: false } }],
				yAxes: [{ stacked: true, gridLines: grid, ticks: { beginAtZero: true, maxTicksLimit: 5, callback: function (v) { return Number(v).toLocaleString(); } } }]
			}
		}
	});

	// Customer mix
	var mixCat = <?php echo json_encode($d['mix']['category']); ?>;
	var mixColors = { RESIDENTIAL: '#2196F3', COMMERCIAL: '#FF9800', GOVERNMENT: '#4CAF50', BULKSALES: '#9C27B0' };
	new Chart(document.getElementById('chartMix'), {
		type: 'doughnut',
		data: {
			labels: mixCat.map(function (r) { return r.label.charAt(0) + r.label.slice(1).toLowerCase(); }),
			datasets: [{ data: mixCat.map(function (r) { return +r.total; }), backgroundColor: mixCat.map(function (r) { return mixColors[r.label] || '#9E9E9E'; }), borderWidth: 2 }]
		},
		options: {
			cutoutPercentage: 60,
			legend: { position: mobile ? 'bottom' : 'right' },
			tooltips: { callbacks: { label: function (t, data) {
				var v = data.datasets[0].data[t.index], sum = data.datasets[0].data.reduce(function (a, b) { return a + b; }, 0);
				return ' ' + data.labels[t.index] + ': ' + v.toLocaleString() + ' (' + (sum ? (v / sum * 100).toFixed(1) : 0) + '%)';
			} } }
		}
	});

	<?php if ($ticketAll > 0): ?>
	new Chart(document.getElementById('chartTickets'), {
		type: 'doughnut',
		data: {
			labels: <?php echo json_encode($ticketLabels); ?>,
			datasets: [{ data: <?php echo json_encode(array_values($d['tickets'])); ?>, backgroundColor: ['#f6b73c', '#1e6fd9', '#7046c2', '#1a8f47', '#d23b3b', '#11838c', '#6b7a90'], borderWidth: 2 }]
		},
		options: { cutoutPercentage: 60, legend: { position: mobile ? 'bottom' : 'right' } }
	});
	<?php endif; ?>

	$('#yearSelector').on('change', function () {
		window.location.href = window.location.href.split('?')[0] + '?year=' + encodeURIComponent($(this).val());
	});
});
</script>
</body>
</html>
