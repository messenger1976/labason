<?php
$income1 = $this->comm_model->get_income_metercustomer();
extract($income1);
$income2 = $this->comm_model->get_income_monthlycustomer();
extract($income2);
$intotal = $total1 + $total2;
$total_customer = $this->my_model->total_customer();
extract($total_customer);
$leaking_total = isset($leaking['total']) ? $leaking['total'] : 0;
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>dashboard/">Home</a></li>
		<li class="breadcrumb-item active">Dashboard</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-chart-area"></i> Billing <span class="fw-300">Dashboard</span>
		</h1>
	</div>

	<div class="row">
		<div class="col-sm-6 col-xl-3">
			<a href="<?php echo ADMIN_URL; ?>addpaymentcustomer/add" class="text-white text-decoration-none">
				<div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g">
					<div>
						<h3 class="display-4 d-block l-h-n m-0 fw-500">
							₱ <?php echo number_format($intotal, 2); ?>
							<small class="m-0 l-h-n">Total Sales</small>
						</h3>
					</div>
					<i class="fal fa-chart-bar position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:6rem"></i>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-xl-3">
			<a href="<?php echo ADMIN_URL; ?>addcustomer" class="text-white text-decoration-none">
				<div class="p-3 bg-warning-400 rounded overflow-hidden position-relative text-white mb-g">
					<div>
						<h3 class="display-4 d-block l-h-n m-0 fw-500">
							<?php echo (int) $count_id; ?>
							<small class="m-0 l-h-n">Total Customers</small>
						</h3>
					</div>
					<i class="fal fa-user position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:6rem"></i>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-xl-3">
			<a href="<?php echo ADMIN_URL; ?>Leakingentry" class="text-white text-decoration-none">
				<div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g">
					<div>
						<h3 class="display-4 d-block l-h-n m-0 fw-500">
							₱ <?php echo number_format($leaking_total, 2); ?>
							<small class="m-0 l-h-n">Leaking Balances</small>
						</h3>
					</div>
					<i class="fal fa-tint position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4" style="font-size:6rem"></i>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-xl-3">
			<a href="<?php echo ADMIN_URL; ?>technicalproblems" class="text-white text-decoration-none">
				<div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g">
					<div>
						<h3 class="display-4 d-block l-h-n m-0 fw-500">
							<?php echo (int) $total_prbm; ?>
							<small class="m-0 l-h-n">Open Tickets</small>
						</h3>
					</div>
					<i class="fal fa-comments position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4" style="font-size:6rem"></i>
				</div>
			</a>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div id="panel-1" class="panel">
				<div class="panel-hdr">
					<h2>
						Sales <span class="fw-300"><i>Chart</i></span>
					</h2>
					<div class="panel-toolbar">
						<label class="mb-0 mr-2 fw-500">Year</label>
						<select id="yearSelector" class="form-control form-control-sm" style="width:auto;min-width:100px;">
							<?php if (isset($available_years) && !empty($available_years)): ?>
								<?php foreach ($available_years as $year): ?>
									<option value="<?php echo (int) $year; ?>" <?php echo (isset($selected_year) && (int)$selected_year === (int)$year) ? 'selected' : ''; ?>>
										<?php echo (int) $year; ?>
									</option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
							<?php endif; ?>
						</select>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<canvas id="barChart" height="80"></canvas>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div id="panel-2" class="panel">
				<div class="panel-hdr">
					<h2>
						Customer by <span class="fw-300"><i>Zone Area</i></span>
					</h2>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<canvas id="doughnutChart" height="160"></canvas>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div id="panel-3" class="panel">
				<div class="panel-hdr">
					<h2>
						My <span class="fw-300"><i>Tickets</i></span>
					</h2>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<canvas id="pieChart" height="160"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php include('footer.php'); ?>

<script src="<?php echo base_url(); ?>sa4/js/statistics/chartjs/chartjs.bundle.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var barData = {
		labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
		datasets: [
			{
				label: "Unpaid",
				backgroundColor: 'rgba(255, 99, 132, 0.5)',
				borderColor: 'rgba(255, 99, 132, 1)',
				borderWidth: 1,
				data: [
					<?php echo number_format(isset($JanTotalUnpaid['total']) ? $JanTotalUnpaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($FebTotalUnpaid['total']) ? $FebTotalUnpaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($MarchTotalUnpaid['total']) ? $MarchTotalUnpaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($AprilTotalUnpaid['total']) ? $AprilTotalUnpaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($MayTotalUnpaid['total']) ? $MayTotalUnpaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($JuneTotalUnpaid['total']) ? $JuneTotalUnpaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($JulyTotalUnpaid['total']) ? $JulyTotalUnpaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($AugTotalUnpaid['total']) ? $AugTotalUnpaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($SepTotalUnpaid['total']) ? $SepTotalUnpaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($OctTotalUnpaid['total']) ? $OctTotalUnpaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($NovTotalUnpaid['total']) ? $NovTotalUnpaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($DecTotalUnpaid['total']) ? $DecTotalUnpaid['total'] : 0, 0, '.', ''); ?>
				]
			},
			{
				label: "Total Collection",
				backgroundColor: 'rgba(75, 192, 192, 0.5)',
				borderColor: 'rgba(75, 192, 192, 1)',
				borderWidth: 1,
				data: [
					<?php echo number_format(isset($JanTotalPaid['total']) ? $JanTotalPaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($FebTotalPaid['total']) ? $FebTotalPaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($MarchTotalPaid['total']) ? $MarchTotalPaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($AprilTotalPaid['total']) ? $AprilTotalPaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($MayTotalPaid['total']) ? $MayTotalPaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($JuneTotalPaid['total']) ? $JuneTotalPaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($JulyTotalPaid['total']) ? $JulyTotalPaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($AugTotalPaid['total']) ? $AugTotalPaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($SepTotalPaid['total']) ? $SepTotalPaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($OctTotalPaid['total']) ? $OctTotalPaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($NovTotalPaid['total']) ? $NovTotalPaid['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($DecTotalPaid['total']) ? $DecTotalPaid['total'] : 0, 0, '.', ''); ?>
				]
			},
			{
				label: "Total Collectables",
				backgroundColor: 'rgba(54, 162, 235, 0.5)',
				borderColor: 'rgba(54, 162, 235, 1)',
				borderWidth: 1,
				data: [
					<?php echo number_format(isset($JanTotal['total']) ? $JanTotal['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($FebTotal['total']) ? $FebTotal['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($MarchTotal['total']) ? $MarchTotal['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($AprilTotal['total']) ? $AprilTotal['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($MayTotal['total']) ? $MayTotal['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($JuneTotal['total']) ? $JuneTotal['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($JulyTotal['total']) ? $JulyTotal['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($AugTotal['total']) ? $AugTotal['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($SepTotal['total']) ? $SepTotal['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($OctTotal['total']) ? $OctTotal['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($NovTotal['total']) ? $NovTotal['total'] : 0, 0, '.', ''); ?>,
					<?php echo number_format(isset($DecTotal['total']) ? $DecTotal['total'] : 0, 0, '.', ''); ?>
				]
			}
		]
	};

	new Chart(document.getElementById("barChart").getContext("2d"), {
		type: 'bar',
		data: barData,
		options: {
			responsive: true,
			scales: {
				y: { beginAtZero: true }
			}
		}
	});

	new Chart(document.getElementById("pieChart").getContext("2d"), {
		type: 'pie',
		data: {
			labels: ['Pending', 'Assigned', 'On going', 'Resolved', 'Un-Resolved', 'Resolved - Closed', 'UnResolved - Closed'],
			datasets: [{
				label: 'My Tickets',
				data: [
					<?php echo isset($ticket0['count_id']) ? (int)$ticket0['count_id'] : 0; ?>,
					<?php echo isset($ticket1['count_id']) ? (int)$ticket1['count_id'] : 0; ?>,
					<?php echo isset($ticket2['count_id']) ? (int)$ticket2['count_id'] : 0; ?>,
					<?php echo isset($ticket3['count_id']) ? (int)$ticket3['count_id'] : 0; ?>,
					<?php echo isset($ticket4['count_id']) ? (int)$ticket4['count_id'] : 0; ?>,
					<?php echo isset($ticket5['count_id']) ? (int)$ticket5['count_id'] : 0; ?>,
					<?php echo isset($ticket6['count_id']) ? (int)$ticket6['count_id'] : 0; ?>
				],
				backgroundColor: ['#ff6384', '#36a2eb', '#5959cc', '#ffcd56', '#236c35', '#661e52', '#3cc0e1'],
				hoverOffset: 4
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: { position: 'top' },
				title: { display: true, text: 'My Tickets Chart' }
			}
		}
	});

	<?php
	$zoneLabels = array();
	$zoneData = array();
	$zoneColors = array();
	$colorPalette = array(
		'rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)', 'rgba(60, 28, 89, 1)',
		'rgb(75, 192, 192)', 'rgb(153, 102, 255)', 'rgb(255, 159, 64)', 'rgb(199, 199, 199)',
		'rgb(83, 102, 255)', 'rgb(255, 99, 255)', 'rgb(99, 255, 132)'
	);
	if (isset($zones) && !empty($zones)) {
		foreach ($zones as $index => $zone) {
			$zoneLabels[] = $zone['zone'];
			$zoneData[] = (int) $zone['count_id'];
			$zoneColors[] = $colorPalette[$index % count($colorPalette)];
		}
	}
	?>
	new Chart(document.getElementById("doughnutChart").getContext("2d"), {
		type: 'doughnut',
		data: {
			labels: <?php echo json_encode($zoneLabels); ?>,
			datasets: [{
				label: 'Customer Zone Area',
				data: <?php echo json_encode($zoneData); ?>,
				backgroundColor: <?php echo json_encode($zoneColors); ?>,
				hoverOffset: 8
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: { position: 'top' },
				title: { display: true, text: 'Zone Chart' }
			}
		}
	});

	$('#yearSelector').on('change', function() {
		var selectedYear = $(this).val();
		var currentUrl = window.location.href.split('?')[0];
		window.location.href = currentUrl + '?year=' + selectedYear;
	});
});
</script>
</body>
</html>
