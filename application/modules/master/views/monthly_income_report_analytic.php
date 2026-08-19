<?php
	$income1 = $this->my_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->my_model->get_income_monthlycustomer();
	extract($income2);
	$intotal = (isset($total1) ? (float) $total1 : 0) + (isset($total2) ? (float) $total2 : 0);

	$expense1 = $this->my_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->my_model->get_outcome_payroll();
	extract($expense2);
	$extotal = (isset($extotal1) ? (float) $extotal1 : 0) + (isset($extotal2) ? (float) $extotal2 : 0);

	$total_customer = $this->my_model->total_customer();
	extract($total_customer);
?>
<style>
	.monthly-income-barchart { min-height: 280px; }
	.monthly-income-barchart .chart-wrap {
		display: flex;
		align-items: flex-end;
		justify-content: space-between;
		height: 220px;
		border-bottom: 1px solid #cfd7e3;
		border-left: 1px solid #cfd7e3;
		padding: 0 4px 0 0;
		gap: 2px;
	}
	.monthly-income-barchart .bar-wrap {
		flex: 1 1 0;
		height: 100%;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: flex-end;
		min-width: 0;
	}
	.monthly-income-barchart .bar {
		width: 100%;
		max-width: 18px;
		background: linear-gradient(180deg, #5b9bd5 0%, #2d6fa8 100%);
		border-radius: 3px 3px 0 0;
		min-height: 2px;
	}
	.monthly-income-barchart .bar-label {
		font-size: 10px;
		color: #6c757d;
		margin-top: 4px;
		line-height: 1;
	}
	.monthly-income-barchart .y-axis-labels {
		position: absolute;
		left: 0;
		top: 0;
		bottom: 18px;
		width: 44px;
		display: flex;
		flex-direction: column-reverse;
		justify-content: space-between;
		font-size: 11px;
		color: #6c757d;
		text-align: right;
		padding-right: 6px;
	}
	#totalIncomeDisplay {
		font-size: 2rem;
		font-weight: 700;
		color: #886ab5;
		margin: 0;
	}
	#dailyListContainer pre {
		font-size: 12px;
		margin: 0;
		background: transparent;
		border: 0;
		padding: 0;
		white-space: pre-wrap;
	}
</style>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>reports/monthly_income_report_analytic">Reports</a></li>
		<li class="breadcrumb-item active">Monthly Income Report Analytic</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-chart-bar"></i>
			Manage <span class="fw-300">Monthly Income Report Analytic</span>
		</h1>
		<div class="subheader-block d-lg-flex align-items-center">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>INCOME</small>
				</span>
				<span class="fw-500 fs-xl d-block color-primary-500">
					₱ <?php echo number_format($intotal, 2); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#886ab5" sparkHeight="32px" sparkBarWidth="5px" values="3,4,3,6,7,3,3,6,2,6,4"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>EXPENSE</small>
				</span>
				<span class="fw-500 fs-xl d-block color-danger-500">
					₱ <?php echo number_format($extotal, 2); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#fe6bb0" sparkHeight="32px" sparkBarWidth="5px" values="1,4,3,6,5,3,9,6,5,9,7"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>TOTAL CUSTOMER</small>
				</span>
				<span class="fw-500 fs-xl d-block color-success-500">
					<?php echo (int) (isset($count_id) ? $count_id : 0); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#1dc9b7" sparkHeight="32px" sparkBarWidth="5px" values="2,5,3,7,4,6,3,8,5,4,6"></span>
		</div>
	</div>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-monthly-income" class="panel">
				<div class="panel-hdr">
					<h2>Monthly Income Report Analytic <span class="fw-300"><i>Search</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form name="monthly_income_form" id="monthly_income_form" method="post" action="javascript:void(0);">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="month">Month</label>
										<select class="form-control" name="month" id="month" required>
											<option value="">-- Select --</option>
											<?php for ($m = 1; $m <= 12; $m++) {
												$mn = getMonthName($m);
												$name = isset($mn[0]) ? $mn[0]->month_name : date('F', mktime(0, 0, 0, $m, 1));
												$sel = ($m == date('n')) ? ' selected="selected"' : '';
												echo '<option value="'.$m.'"'.$sel.'>'.htmlspecialchars($name).'</option>';
											} ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="year">Year</label>
										<select class="form-control" name="year" id="year" required>
											<?php
											$current_year = (int) date('Y');
											for ($y = $current_year - 5; $y <= $current_year + 1; $y++) {
												$sel = ($y == $current_year) ? ' selected="selected"' : '';
												echo '<option value="'.$y.'"'.$sel.'>'.$y.'</option>';
											}
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="form-group mb-0">
								<button type="button" class="btn btn-primary" id="btnPerform">
									<i class="fal fa-play mr-1"></i> Perform
								</button>
								<button type="button" class="btn btn-secondary" id="btnCancel">
									<i class="fal fa-times mr-1"></i> Cancel
								</button>
								<button type="button" class="btn btn-warning" id="btnPrint" title="Print report">
									<i class="fal fa-print mr-1"></i> Print
								</button>
								<button type="button" class="btn btn-danger" id="btnExportPdf" title="Export to PDF">
									<i class="fal fa-file-pdf mr-1"></i> Export to PDF
								</button>
								<button type="button" class="btn btn-success" id="btnExportExcel" title="Export to Excel">
									<i class="fal fa-file-excel mr-1"></i> Export to Excel
								</button>
							</div>
						</form>

						<div id="reportPlaceholder" class="alert alert-info mt-4 mb-0">
							Select Month and Year, then click <strong>Perform</strong> to view the report.
						</div>

						<div id="reportContent" class="mt-4" style="display:none;">
							<div class="row">
								<div class="col-lg-8">
									<div class="panel">
										<div class="panel-hdr">
											<h2>Daily Income <span class="fw-300"><i>Bar Chart</i></span></h2>
										</div>
										<div class="panel-container show">
											<div class="panel-content">
												<div id="barChartContainer" class="monthly-income-barchart" style="display:none;"></div>
												<div id="barChartPlaceholder" class="alert alert-warning mb-0">Run report to see chart.</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="panel">
										<div class="panel-hdr">
											<h2>Total Income</h2>
										</div>
										<div class="panel-container show">
											<div class="panel-content text-center py-4">
												<p id="totalIncomeDisplay">0.00</p>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="panel">
								<div class="panel-hdr">
									<h2>Daily Income <span class="fw-300"><i>List</i></span></h2>
								</div>
								<div class="panel-container show">
									<div class="panel-content">
										<div class="row" id="dailyListContainer"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
</body>
</html>
<script type="text/javascript">
(function() {
	function formatNumber(num) {
		return parseFloat(num).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}

	function buildDailyList(daily, daysInMonth) {
		var col1 = '', col2 = '', col3 = '';
		for (var d = 1; d <= daysInMonth; d++) {
			var amt = (daily[d] !== undefined && daily[d] !== null) ? parseFloat(daily[d]) : 0;
			var line = d + ' ==>> ' + formatNumber(amt) + '<br>';
			if (d <= 10) col1 += line;
			else if (d <= 20) col2 += line;
			else col3 += line;
		}
		return ''
			+ '<div class="col-md-4"><pre>' + col1 + '</pre></div>'
			+ '<div class="col-md-4"><pre>' + col2 + '</pre></div>'
			+ '<div class="col-md-4"><pre>' + col3 + '</pre></div>';
	}

	function renderBarChart(containerId, daily, daysInMonth) {
		var container = document.getElementById(containerId);
		var placeholder = document.getElementById('barChartPlaceholder');
		if (!container) return;

		var values = [];
		for (var d = 1; d <= daysInMonth; d++) {
			var v = daily[d] !== undefined ? daily[d] : (daily[String(d)] !== undefined ? daily[String(d)] : 0);
			values.push(parseFloat(v) || 0);
		}

		var maxVal = Math.max.apply(null, values);
		if (maxVal <= 0) maxVal = 1;
		var totalSum = values.reduce(function(a, b) { return a + b; }, 0);
		var noData = (totalSum === 0);

		var html = '';
		if (noData) {
			html = '<p class="text-muted">No income data for this month. The chart will show bars when payments exist for the selected period.</p>';
		}
		html += '<div style="position:relative; padding-left: 50px;">';
		html += '<div class="y-axis-labels"><span>0</span><span>' + (maxVal >= 1000000 ? (maxVal / 1000000).toFixed(0) + 'M' : (maxVal / 1000).toFixed(0) + 'k') + '</span></div>';
		html += '<div class="chart-wrap">';
		for (var i = 0; i < values.length; i++) {
			var pct = maxVal > 0 ? (values[i] / maxVal) * 100 : 0;
			if (pct > 100) pct = 100;
			html += '<div class="bar-wrap"><div class="bar" style="height:' + pct + '%" title="Day ' + (i + 1) + ': ' + formatNumber(values[i]) + '"></div><span class="bar-label">' + (i + 1) + '</span></div>';
		}
		html += '</div></div>';

		container.innerHTML = html;
		container.style.display = 'block';
		if (placeholder) placeholder.style.display = 'none';
	}

	function runReport() {
		var month = $('#month').val();
		var year = $('#year').val();
		if (!month || !year) {
			alert('Please select Month and Year.');
			return;
		}

		$('#reportPlaceholder').hide();
		$('#reportContent').show();
		$('#totalIncomeDisplay').text('Loading…');
		$('#dailyListContainer').html('<div class="col-12 text-center text-muted py-3"><i class="fal fa-spinner fa-spin mr-1"></i> Loading…</div>');

		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL; ?>reports/getmonthlyincomereportanalytic',
			data: { month: month, year: year },
			dataType: 'json',
			success: function(res) {
				if (!res || typeof res !== 'object') {
					alert('Invalid response from server.');
					return;
				}
				var daily = res.daily || {};
				var total = parseFloat(res.total) || 0;
				var daysInMonth = parseInt(res.days_in_month, 10) || 31;
				if (daysInMonth < 28 || daysInMonth > 31) daysInMonth = 31;
				$('#totalIncomeDisplay').text(formatNumber(total));
				$('#dailyListContainer').html(buildDailyList(daily, daysInMonth));
				renderBarChart('barChartContainer', daily, daysInMonth);
			},
			error: function(xhr, status, err) {
				alert('Error loading report data.');
				console.error(err);
			}
		});
	}

	$(document).ready(function() {
		if (typeof pageSetUp === 'function') { pageSetUp(); }
		if ($.fn.sparkline) {
			$('.sparklines').each(function() {
				var $el = $(this);
				$el.sparkline('html', {
					type: $el.attr('sparkType') || 'bar',
					barColor: $el.attr('sparkBarColor') || '#886ab5',
					height: $el.attr('sparkHeight') || '32px',
					barWidth: $el.attr('sparkBarWidth') || '5px'
				});
			});
		}

		$('#btnPerform').on('click', runReport);
		$('#btnCancel').on('click', function() {
			$('#reportContent').hide();
			$('#reportPlaceholder').show();
			$('#barChartContainer').hide().empty();
			$('#barChartPlaceholder').show();
			$('#totalIncomeDisplay').text('0.00');
			$('#dailyListContainer').empty();
		});
		$('#btnPrint').on('click', function(evt) {
			evt.preventDefault();
			var month = $('#month').val();
			var year = $('#year').val();
			if (!month || !year) {
				alert('Please select Month and Year first.');
				return;
			}
			var url = '<?php echo ADMIN_URL; ?>reports/monthly_income_report_printtopdf/' + month + '/' + year;
			var popup = window.open(url, 'MonthlyIncomePrint', 'width=800,height=600,resizable=yes,scrollbars=yes');
			if (!popup || popup.closed || typeof popup.closed === 'undefined') {
				alert('Popup was blocked. Please allow popups for this site.');
			}
		});
		$('#btnExportPdf').on('click', function(evt) {
			evt.preventDefault();
			var month = $('#month').val();
			var year = $('#year').val();
			if (!month || !year) {
				alert('Please select Month and Year first.');
				return;
			}
			if (typeof openReportPdfPreview === 'function') {
				openReportPdfPreview({
					printUrl: '<?php echo ADMIN_URL; ?>reports/monthly_income_report_printtopdf/' + month + '/' + year,
					filename: 'Monthly_Income_Report.pdf'
				});
				return;
			}
			window.location.href = '<?php echo ADMIN_URL; ?>reports/monthly_income_exporttopdf/' + month + '/' + year;
		});
		$('#btnExportExcel').on('click', function(evt) {
			evt.preventDefault();
			var month = $('#month').val();
			var year = $('#year').val();
			if (!month || !year) {
				alert('Please select Month and Year first.');
				return;
			}
			window.location.href = '<?php echo ADMIN_URL; ?>reports/monthly_income_exporttoexcel/' + month + '/' + year;
		});
	});
})();
</script>
