<?php
/**
 * Customer dashboard - master/statementofaccount/index/{customer_id}.
 *
 * Rendered inside customer_app_header.php / customer_app_footer.php.
 * $dashboard is built by statementofaccount::_dashboard_payload() from the
 * existing models; this view only formats it and draws the charts.
 *
 * Expects: $customer_info, $dashboard
 */
$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
$peso = function ($v) { return '&#8369; ' . number_format((float) $v, 2); };
$fmt_date = function ($d) {
	$ts = $d !== '' && $d !== null ? strtotime($d) : false;
	return $ts ? date('M j, Y', $ts) : (string) $d;
};
$pct = function ($now, $before) {
	if ($before === null || (float) $before <= 0) { return null; }
	return round((((float) $now - (float) $before) / (float) $before) * 100, 1);
};

$d = isset($dashboard) && is_array($dashboard) ? $dashboard : array();
$cid = (string) $customer_info['customer_id'];
$first = trim((string) $customer_info['first_name']);
$name = strtoupper(trim($customer_info['last_name'] . ', ' . $customer_info['first_name'] . ' ' . $customer_info['middle_name']));
$base = base_url() . 'master/statementofaccount/';
$pay_url = $base . 'pay/' . rawurlencode($cid);
$soa_url = $base . 'soa/' . rawurlencode($cid);

$balance = (float) $d['balance'];
$latest = $d['latest_bill'];
$prev = $d['prev_bill'];
$last_year = $d['same_last_year'];
$last_pay = $d['last_payment'];
$past_due = $d['past_due'];

$hour = (int) date('G');
$greet = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');

$vs_prev = $latest && $prev ? $pct($latest['consumed'], $prev['consumed']) : null;
$vs_year = $latest && $last_year ? $pct($latest['consumed'], $last_year['consumed']) : null;
$vs_avg = $latest ? $pct($latest['consumed'], $d['avg_consumption_6']) : null;

$trend_badge = function ($p) {
	if ($p === null) { return '<span class="app-badge muted">no data</span>'; }
	if (abs($p) < 0.05) { return '<span class="app-badge muted">same</span>'; }
	$up = $p > 0;
	return '<span class="app-badge ' . ($up ? 'bad' : 'payment') . '"><i class="fa fa-arrow-' . ($up ? 'up' : 'down') . '"></i> ' . abs($p) . '%</span>';
};
?>
<style>
	.dash-insight { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .6rem; }
	.dash-insight .cell { background: #f7fafc; border: 1px solid #eef2f6; border-radius: .6rem; padding: .65rem .7rem; }
	.dash-insight .k { font-size: .68rem; color: var(--app-muted); text-transform: uppercase; letter-spacing: .04em; font-weight: 600; }
	.dash-insight .v { font-size: 1.1rem; font-weight: 700; color: var(--app-head); margin-top: .1rem; }
	.dash-insight .s { font-size: .7rem; color: var(--app-muted); margin-top: .1rem; }
	.dash-legend { display: flex; gap: .9rem; flex-wrap: wrap; font-size: .72rem; color: var(--app-muted); margin-top: .5rem; }
	.dash-legend i { display: inline-block; width: .7rem; height: .7rem; border-radius: 2px; margin-right: .3rem; vertical-align: -1px; }
	.dash-days { font-size: .68rem; font-weight: 700; color: var(--app-bad); }
	.dash-quick { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .55rem; margin-bottom: .85rem; }
	.app-body .dash-quick a {
		display: flex; flex-direction: column; align-items: center; justify-content: center; gap: .3rem;
		background: #fff; border: 1px solid var(--app-border); border-radius: .7rem; padding: .7rem .3rem;
		color: var(--app-head); font-size: .74rem; font-weight: 600; text-align: center; box-shadow: var(--app-shadow); min-height: 4.2rem;
	}
	.app-body .dash-quick a .fa { font-size: 1.25rem; color: var(--app-brand); }
	@media (min-width: 992px) { .dash-quick { display: none; } }
</style>

<!-- Greeting + balance -->
<div class="app-card app-hero">
	<div class="app-card-body">
		<div class="hello"><?php echo $h($greet); ?><?php echo $first !== '' ? ', ' . $h(ucwords(strtolower($first))) : ''; ?></div>
		<div class="name"><?php echo $h($name); ?></div>
		<div class="meta">Customer ID <?php echo $h($cid); ?> &middot; Meter <?php echo $h($customer_info['meter_number']); ?><?php echo !empty($customer_info['zone']) ? ' &middot; ' . $h($customer_info['zone']) : ''; ?></div>
		<div class="bal-lbl">Outstanding balance</div>
		<div class="bal"><?php echo $peso($balance); ?></div>
		<div class="bal-sub">
			<?php if ($balance > 0.009) { ?>
				<span class="pill bad"><?php echo count($past_due) ? count($past_due) . ' past-due period' . (count($past_due) === 1 ? '' : 's') : 'Amount due'; ?></span>
			<?php } else { ?>
				<span class="pill ok"><i class="fa fa-check"></i> Your account is fully paid</span>
			<?php } ?>
			<?php if ($latest && !empty($latest['due_date'])) { ?>
				&nbsp;Latest bill due <?php echo $h($fmt_date($latest['due_date'])); ?>
			<?php } ?>
		</div>
		<div class="app-hero-actions">
			<?php if ($d['payable_total'] > 0.009) { ?>
			<a class="app-btn app-btn-light" href="<?php echo $h($pay_url); ?>"><i class="fa fa-qrcode"></i> Pay <?php echo $peso($d['payable_total']); ?></a>
			<?php } ?>
			<a class="app-btn app-btn-ghost" href="<?php echo $h($soa_url); ?>"><i class="fa fa-file-text-o"></i> View statement</a>
		</div>
	</div>
</div>

<!-- Quick actions (phones) -->
<div class="dash-quick">
	<a href="<?php echo $h($soa_url); ?>"><i class="fa fa-file-text-o"></i>Statement</a>
	<a href="<?php echo $h($pay_url); ?>"><i class="fa fa-qrcode"></i>Pay online</a>
	<a href="<?php echo $h($base . 'pdf/' . rawurlencode($cid)); ?>" target="_blank" rel="noopener"><i class="fa fa-file-pdf-o"></i>Download PDF</a>
</div>

<!-- KPIs -->
<div class="app-kpis">
	<div class="app-kpi <?php echo $d['payable_total'] > 0.009 ? 'tone-warn' : 'tone-ok'; ?>">
		<div class="lbl">Payable online</div>
		<div class="val"><?php echo $peso($d['payable_total']); ?></div>
		<div class="hint"><?php echo (int) $d['payable_count']; ?> unpaid period<?php echo (int) $d['payable_count'] === 1 ? '' : 's'; ?></div>
	</div>
	<div class="app-kpi <?php echo count($past_due) ? 'tone-bad' : 'tone-ok'; ?>">
		<div class="lbl">Past due</div>
		<div class="val <?php echo count($past_due) ? 'bad' : 'ok'; ?>"><?php echo $peso($d['past_due_total']); ?></div>
		<div class="hint"><?php echo count($past_due) ? count($past_due) . ' period' . (count($past_due) === 1 ? '' : 's') . ' overdue' : 'Nothing overdue'; ?></div>
	</div>
	<div class="app-kpi">
		<div class="lbl">Latest bill</div>
		<div class="val"><?php echo $latest ? $peso($latest['amount']) : '&mdash;'; ?></div>
		<div class="hint"><?php echo $latest ? $h($latest['period']) . ' &middot; ' . $h($latest['consumed']) . ' cu.m' : 'No billing yet'; ?></div>
	</div>
	<div class="app-kpi tone-ok">
		<div class="lbl">Last payment</div>
		<div class="val"><?php echo $last_pay ? $peso($last_pay['amount']) : '&mdash;'; ?></div>
		<div class="hint"><?php echo $last_pay ? $h($fmt_date($last_pay['date'])) . ' &middot; OR ' . $h($last_pay['refno']) : 'No payments yet'; ?></div>
	</div>
</div>

<div class="app-grid app-grid-2-1">
	<!-- Charges vs payments -->
	<div class="app-card">
		<div class="app-card-head">
			<h2><i class="fa fa-bar-chart"></i> Billing vs payments</h2>
			<small>Last 12 months</small>
		</div>
		<div class="app-card-body">
			<div class="app-chart"><canvas id="dash_chart_months" aria-label="Billing and payments per month"></canvas></div>
			<div class="dash-legend">
				<span><i style="background:#e35d6a"></i>Billed (debits)</span>
				<span><i style="background:#1dc9b7"></i>Paid &amp; credits</span>
			</div>
		</div>
	</div>

	<!-- Consumption insights -->
	<div class="app-card">
		<div class="app-card-head">
			<h2><i class="fa fa-tint"></i> Water use</h2>
			<small><?php echo $latest ? $h($latest['period']) : ''; ?></small>
		</div>
		<div class="app-card-body">
			<div class="dash-insight">
				<div class="cell">
					<div class="k">This period</div>
					<div class="v"><?php echo $latest ? $h($latest['consumed']) : '0'; ?> <small>cu.m</small></div>
					<div class="s"><?php echo $latest ? $h($latest['period']) : 'No reading yet'; ?></div>
				</div>
				<div class="cell">
					<div class="k">6-period average</div>
					<div class="v"><?php echo $h($d['avg_consumption_6']); ?> <small>cu.m</small></div>
					<div class="s">vs average <?php echo $trend_badge($vs_avg); ?></div>
				</div>
				<div class="cell">
					<div class="k">Previous period</div>
					<div class="v"><?php echo $prev ? $h($prev['consumed']) : '&mdash;'; ?> <small>cu.m</small></div>
					<div class="s"><?php echo $trend_badge($vs_prev); ?></div>
				</div>
				<div class="cell">
					<div class="k">Same month last year</div>
					<div class="v"><?php echo $last_year ? $h($last_year['consumed']) : '&mdash;'; ?> <small>cu.m</small></div>
					<div class="s"><?php echo $trend_badge($vs_year); ?></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="app-grid app-grid-2">
	<div class="app-card">
		<div class="app-card-head">
			<h2><i class="fa fa-line-chart"></i> Meter consumption</h2>
			<small>Last 12 billing periods</small>
		</div>
		<div class="app-card-body">
			<div class="app-chart"><canvas id="dash_chart_consumption" aria-label="Consumption per billing period"></canvas></div>
		</div>
	</div>
	<div class="app-card">
		<div class="app-card-head">
			<h2><i class="fa fa-area-chart"></i> Balance trend</h2>
			<small>Running balance</small>
		</div>
		<div class="app-card-body">
			<div class="app-chart"><canvas id="dash_chart_balance" aria-label="Running balance over time"></canvas></div>
		</div>
	</div>
</div>

<div class="app-grid app-grid-2">
	<!-- Past due report -->
	<div class="app-card">
		<div class="app-card-head">
			<h2><i class="fa fa-exclamation-triangle"></i> Past-due bills</h2>
			<small><?php echo count($past_due); ?> period<?php echo count($past_due) === 1 ? '' : 's'; ?></small>
		</div>
		<?php if (empty($past_due)) { ?>
		<div class="app-empty"><i class="fa fa-smile-o"></i>No past-due bills. Thank you for paying on time.</div>
		<?php } else { ?>
			<?php foreach ($past_due as $p) { ?>
			<div class="app-list-row">
				<div class="l">
					<div class="a"><?php echo $h($p['period']); ?></div>
					<div class="b">Due <?php echo $h($fmt_date($p['due_date'])); ?> &middot; <span class="dash-days"><?php echo (int) $p['days']; ?> day<?php echo (int) $p['days'] === 1 ? '' : 's'; ?> overdue</span></div>
					<div class="b"><?php echo $h($p['consumed']); ?> cu.m<?php echo $p['penalty'] >= 0.005 ? ' &middot; includes penalty ' . $peso($p['penalty']) : ''; ?></div>
				</div>
				<div class="r" style="color:var(--app-bad);"><?php echo $peso($p['amount_due']); ?></div>
			</div>
			<?php } ?>
			<div class="app-card-foot" style="display:flex;justify-content:space-between;align-items:center;gap:.5rem;">
				<span>Total past due <strong style="color:var(--app-bad);"><?php echo $peso($d['past_due_total']); ?></strong></span>
				<a class="app-btn app-btn-pay" style="min-height:2.3rem;padding:.35rem .85rem;" href="<?php echo $h($pay_url); ?>"><i class="fa fa-qrcode"></i> Pay now</a>
			</div>
		<?php } ?>
	</div>

	<!-- Recent payments -->
	<div class="app-card">
		<div class="app-card-head">
			<h2><i class="fa fa-history"></i> Recent payments</h2>
			<a href="<?php echo $h($soa_url); ?>" style="font-size:.75rem;font-weight:600;">See all</a>
		</div>
		<?php if (empty($d['recent_payments'])) { ?>
		<div class="app-empty"><i class="fa fa-inbox"></i>No payments recorded yet.</div>
		<?php } else { ?>
			<?php foreach ($d['recent_payments'] as $p) { ?>
			<div class="app-list-row">
				<div class="l">
					<div class="a">OR <?php echo $h($p['refno']); ?></div>
					<div class="b"><?php echo $h($fmt_date($p['date'])); ?></div>
				</div>
				<div class="r" style="color:var(--app-ok);"><?php echo $peso($p['amount']); ?></div>
			</div>
			<?php } ?>
		<?php } ?>
	</div>
</div>

<!-- Account totals -->
<div class="app-card">
	<div class="app-card-head"><h2><i class="fa fa-calculator"></i> Account summary</h2><small>All time</small></div>
	<div class="app-card-body">
		<div class="app-kv"><span class="k">Total billed</span><span class="v"><?php echo $peso($d['total_billed']); ?></span></div>
		<div class="app-kv"><span class="k">Total paid &amp; credits</span><span class="v"><?php echo $peso($d['total_paid']); ?></span></div>
		<div class="app-kv"><span class="k">Outstanding balance</span><span class="v" style="color:<?php echo $balance > 0.009 ? 'var(--app-bad)' : 'var(--app-ok)'; ?>"><?php echo $peso($balance); ?></span></div>
		<div class="app-kv"><span class="k">Classification</span><span class="v"><?php echo $h(isset($customer_info['class_name']) ? $customer_info['class_name'] : 'N/A'); ?></span></div>
		<div class="app-kv"><span class="k">Address</span><span class="v"><?php echo $h(strtoupper($customer_info['address'])); ?></span></div>
	</div>
	<div class="app-card-foot">
		Figures are taken from your Statement of Account and billing records. Updated <?php echo $h(date('M j, Y g:i A', strtotime($d['generated_at']))); ?>.
		The online payable amount may differ from the outstanding balance when a bill has no reading yet or a penalty applies after the due date.
	</div>
</div>

<script src="<?php echo base_url(); ?>sa4/js/statistics/chartjs/chartjs.bundle.js"></script>
<script>
(function () {
	'use strict';
	if (typeof Chart === 'undefined') { return; }

	var data = <?php echo json_encode(array(
		'months' => $d['months'],
		'consumption' => $d['consumption'],
		'trend' => $d['trend'],
	)); ?>;

	function peso(v) {
		var n = parseFloat(v || 0);
		return '\u20B1 ' + n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}
	function shortPeso(v) {
		var n = Math.abs(v) >= 1000 ? (v / 1000).toFixed(1) + 'k' : Math.round(v);
		return '\u20B1' + n;
	}

	Chart.defaults.global.defaultFontFamily = "'Open Sans', -apple-system, 'Segoe UI', Roboto, sans-serif";
	Chart.defaults.global.defaultFontColor = '#6c757d';
	Chart.defaults.global.defaultFontSize = 11;
	Chart.defaults.global.maintainAspectRatio = false;
	Chart.defaults.global.legend.display = false;

	var gridless = {
		xAxes: [{ gridLines: { display: false }, ticks: { maxRotation: 0, autoSkipPadding: 8 } }],
		yAxes: [{ gridLines: { color: '#edf2f6', zeroLineColor: '#dfe7ee', drawBorder: false }, ticks: { beginAtZero: true, maxTicksLimit: 5 } }]
	};

	var monthsEl = document.getElementById('dash_chart_months');
	if (monthsEl) {
		new Chart(monthsEl, {
			type: 'bar',
			data: {
				labels: data.months.map(function (m) { return m.label; }),
				datasets: [
					{ label: 'Billed', data: data.months.map(function (m) { return m.charges; }), backgroundColor: '#e35d6a', barPercentage: .8, categoryPercentage: .7 },
					{ label: 'Paid & credits', data: data.months.map(function (m) { return m.credits; }), backgroundColor: '#1dc9b7', barPercentage: .8, categoryPercentage: .7 }
				]
			},
			options: {
				scales: {
					xAxes: gridless.xAxes,
					yAxes: [{ gridLines: gridless.yAxes[0].gridLines, ticks: { beginAtZero: true, maxTicksLimit: 5, callback: shortPeso } }]
				},
				tooltips: { mode: 'index', intersect: false, callbacks: { label: function (t, d) { return d.datasets[t.datasetIndex].label + ': ' + peso(t.yLabel); } } }
			}
		});
	}

	var consEl = document.getElementById('dash_chart_consumption');
	if (consEl) {
		new Chart(consEl, {
			type: 'line',
			data: {
				labels: data.consumption.map(function (c) { return c.period.replace(/^(\w{3})\w*\s+\d{2}(\d{2})$/, '$1 $2'); }),
				datasets: [{
					label: 'Consumed (cu.m)',
					data: data.consumption.map(function (c) { return c.consumed; }),
					borderColor: '#0a6ba3', backgroundColor: 'rgba(10,107,163,.12)', pointBackgroundColor: '#0a6ba3',
					pointRadius: 3, borderWidth: 2, lineTension: .3, fill: true
				}]
			},
			options: {
				scales: gridless,
				tooltips: {
					mode: 'index', intersect: false,
					callbacks: {
						title: function (t) { return data.consumption[t[0].index].period; },
						label: function (t) { var c = data.consumption[t.index]; return t.yLabel + ' cu.m \u00B7 billed ' + peso(c.amount); }
					}
				}
			}
		});
	}

	var balEl = document.getElementById('dash_chart_balance');
	if (balEl) {
		new Chart(balEl, {
			type: 'line',
			data: {
				labels: data.trend.map(function (t) {
					var dt = new Date(String(t.date).replace(' ', 'T'));
					return isNaN(dt) ? t.date : dt.toLocaleDateString('en-PH', { month: 'short', year: '2-digit' });
				}),
				datasets: [{
					label: 'Balance',
					data: data.trend.map(function (t) { return t.balance; }),
					borderColor: '#ffc241', backgroundColor: 'rgba(255,194,65,.18)', pointBackgroundColor: '#e0a800',
					pointRadius: 2, borderWidth: 2, lineTension: .25, fill: true, steppedLine: false
				}]
			},
			options: {
				scales: {
					xAxes: gridless.xAxes,
					yAxes: [{ gridLines: gridless.yAxes[0].gridLines, ticks: { beginAtZero: true, maxTicksLimit: 5, callback: shortPeso } }]
				},
				tooltips: { mode: 'index', intersect: false, callbacks: { label: function (t) { return 'Balance: ' + peso(t.yLabel); } } }
			}
		});
	}
})();
</script>
