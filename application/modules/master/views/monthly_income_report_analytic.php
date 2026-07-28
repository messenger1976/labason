<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>reports/monthly_income_report_analytic">Reports</a></li>
		<li class="breadcrumb-item active">Monthly Income Report Analytic</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-chart-bar"></i>
			Manage <span class="fw-300">Monthly Income Report Analytic</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Monthly Income Report Analytic <span class="fw-300"><i>Details</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
<section id="widget-grid" class="">
			<div class="row">
				<div class="col-sm-12 col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<strong>Monthly Income Report Analytic</strong>
						</div>
						
							<fieldset>
								<legend>Select period</legend>
								<div class="form-group col-lg-3">
									<label><strong>Month:</strong></label>
									<select class="form-control" name="month" id="month" required>
										<option value="">-- Select --</option>
										<?php for ($m = 1; $m <= 12; $m++) {
											$mn = getMonthName($m);
											$name = isset($mn[0]) ? $mn[0]->month_name : date('F', mktime(0,0,0,$m,1));
											$sel = ($m == date('n')) ? ' selected="selected"' : '';
											echo '<option value="'.$m.'"'.$sel.'>'.$name.'</option>';
										} ?>
									</select>
								</div>
								<div class="form-group col-lg-3">
									<label><strong>Year:</strong></label>
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
								<div class="form-group col-lg-4" style="padding-top: 25px;">
									<button type="button" class="btn btn-primary" id="btnPerform"><i class="fal fa-play"></i> Perform</button>
									<button type="button" class="btn btn-secondary" id="btnCancel"><i class="fal fa-times"></i> Cancel</button>
									<button type="button" class="btn btn-warning" id="btnPrint" title="Print report" style="margin-left: 8px;"><i class="fal fa-print"></i> Print</button>
									<button type="button" class="btn btn-success" id="btnExportExcel" title="Export to Excel" style="margin-left: 4px;"><i class="fal fa-file-excel-o"></i> Export to Excel</button>
								</div>
								<div style="clear:both"></div>
							</fieldset>

							<div id="reportContent" style="display:none; margin-top: 20px;">
								<div class="row">
									<div class="col-md-8">
										<div class="panel panel-default">
											<div class="panel-heading"><strong>Daily Income (Bar Chart)</strong></div>
											<div class="panel-body">
												<div id="barChartContainer" class="monthly-income-barchart" style="display:none;">
													<!-- Bar chart built by JS (CSS-based, no Chart.js) -->
												</div>
												<div id="barChartPlaceholder" class="alert alert-warning">Run report to see chart.</div>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="panel panel-default">
											<div class="panel-heading"><strong>Total Income</strong></div>
											<div class="panel-body text-center">
												<p class="lead" style="font-size: 28px; font-weight: bold; color: #3276b1;" id="totalIncomeDisplay">0.00</p>
											</div>
										</div>
									</div>
								</div>

								<div class="panel panel-default">
									<div class="panel-heading"><strong>Daily Income List</strong></div>
									<div class="panel-body">
										<div class="row" id="dailyListContainer">
											<!-- Filled by JS: 3 columns day ==>> amount -->
										</div>
									</div>
								</div>
							</div>

							<div id="reportPlaceholder" class="alert alert-info" style="margin-top: 20px;">
								Select Month and Year, then click <strong>Perform</strong> to view the report.
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
</body>
</html>
<script type="text/javascript">
(function() {

	function formatNumber(num) {
		return parseFloat(num).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}

	function buildDailyList(daily, daysInMonth) {
		var html = '';
		var col1 = '', col2 = '', col3 = '';
		for (var d = 1; d <= daysInMonth; d++) {
			var amt = (daily[d] !== undefined && daily[d] !== null) ? parseFloat(daily[d]) : 0;
			var line = d + ' ==>> ' + formatNumber(amt) + '<br>';
			if (d <= 10) col1 += line;
			else if (d <= 20) col2 += line;
			else col3 += line;
		}
		html = '<div class="col-md-4"><pre style="font-size: 12px; margin: 0;">' + col1 + '</pre></div>';
		html += '<div class="col-md-4"><pre style="font-size: 12px; margin: 0;">' + col2 + '</pre></div>';
		html += '<div class="col-md-4"><pre style="font-size: 12px; margin: 0;">' + col3 + '</pre></div>';
		return html;
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
		var totalSum = values.reduce(function(a,b){ return a+b; }, 0);
		var noData = (totalSum === 0);
		var html = '';
		if (noData) {
			html = '<p class="text-muted">No income data for this month. The chart will show bars when payments exist for the selected period.</p>';
		}
		html += '<div style="position:relative; padding-left: 50px;">';
		html += '<div class="y-axis-labels"><span>0</span><span>' + (maxVal >= 1000000 ? (maxVal/1000000).toFixed(0) + 'M' : (maxVal/1000).toFixed(0) + 'k') + '</span></div>';
		html += '<div class="chart-wrap">';
		for (var i = 0; i < values.length; i++) {
			var pct = maxVal > 0 ? (values[i] / maxVal) * 100 : 0;
			if (pct > 100) pct = 100;
			html += '<div class="bar-wrap"><div class="bar" style="height:' + pct + '%" title="Day ' + (i+1) + ': ' + formatNumber(values[i]) + '"></div><span class="bar-label">' + (i+1) + '</span></div>';
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
		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL;?>reports/getmonthlyincomereportanalytic',
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
		$('#btnPerform').on('click', runReport);
		$('#btnCancel').on('click', function() {
			$('#reportContent').hide();
			$('#reportPlaceholder').show();
			$('#barChartContainer').hide().empty();
			$('#barChartPlaceholder').show();
		});
		$('#btnPrint').on('click', function(evt) {
			evt.preventDefault();
			var month = $('#month').val();
			var year = $('#year').val();
			if (!month || !year) {
				alert('Please select Month and Year first.');
				return;
			}
			var url = '<?php echo ADMIN_URL;?>reports/monthly_income_report_printtopdf/' + month + '/' + year;
			var popup = window.open(url, 'MonthlyIncomePrint', 'width=800,height=600,resizable=yes,scrollbars=yes');
			if (!popup || popup.closed || typeof popup.closed === 'undefined') {
				alert('Popup was blocked. Please allow popups for this site.');
			}
		});
		$('#btnExportExcel').on('click', function(evt) {
			evt.preventDefault();
			var month = $('#month').val();
			var year = $('#year').val();
			if (!month || !year) {
				alert('Please select Month and Year first.');
				return;
			}
			window.location.href = '<?php echo ADMIN_URL;?>reports/monthly_income_exporttoexcel/' + month + '/' + year;
		});
	});
})();
</script>

