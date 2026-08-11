<?php
	$sa4_loading_label = 'Notifications';
	$sa4_dt_entity = 'notifications';
	$sa4_panel_id = 'panel-mn-dashboard';
	$sa4_dt_export_cols = array(0, 1, 2, 3, 4, 5, 6);

	$income1 = $this->comm_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->comm_model->get_income_monthlycustomer();
	extract($income2);
	$intotal = (isset($total1) ? (float) $total1 : 0) + (isset($total2) ? (float) $total2 : 0);

	$expense1 = $this->comm_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->comm_model->get_outcome_payroll();
	extract($expense2);
	$extotal = (isset($extotal1) ? (float) $extotal1 : 0) + (isset($extotal2) ? (float) $extotal2 : 0);

	$total_customer = $this->comm_model->total_customer();
	extract($total_customer);

	$stats = (isset($stats) && is_array($stats)) ? $stats : array();
	$notifications = (isset($notifications) && is_array($notifications)) ? $notifications : array();
	$daily_stats = (isset($daily_stats) && is_array($daily_stats)) ? $daily_stats : array();
	$date_from = isset($date_from) ? $date_from : date('Y-m-01');
	$date_to = isset($date_to) ? $date_to : date('Y-m-d');

	$total_msgs = isset($stats['total_sent']) ? (int) $stats['total_sent'] : 0;
	$success_msgs = isset($stats['total_success']) ? (int) $stats['total_success'] : 0;
	$success_rate = $total_msgs > 0 ? round(($success_msgs / $total_msgs) * 100, 2) : 0;
	$pct_base = $total_msgs > 0 ? $total_msgs : 1;

	$msg_types = array(
		'billing_statement' => 'Billing',
		'due_account' => 'Due Account',
		'disconnection' => 'Disconnection',
		'custom' => 'Custom',
	);
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>mobilenotifications">Mobile Notifications</a></li>
		<li class="breadcrumb-item active">Analytics</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-chart-bar"></i>
			SMS Analytics <span class="fw-300">Dashboard</span>
		</h1>
		<div class="subheader-block d-lg-flex align-items-center">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>INCOME</small></span>
				<span class="fw-500 fs-xl d-block color-primary-500">₱ <?php echo number_format($intotal, 2); ?></span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#886ab5" sparkHeight="32px" sparkBarWidth="5px" values="3,4,3,6,7,3,3,6,2,6,4"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>EXPENSE</small></span>
				<span class="fw-500 fs-xl d-block color-danger-500">₱ <?php echo number_format($extotal, 2); ?></span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#fe6bb0" sparkHeight="32px" sparkBarWidth="5px" values="1,4,3,6,5,3,9,6,5,9,7"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>TOTAL CUSTOMER</small></span>
				<span class="fw-500 fs-xl d-block color-success-500"><?php echo (int) (isset($count_id) ? $count_id : 0); ?></span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#1dc9b7" sparkHeight="32px" sparkBarWidth="5px" values="2,5,3,7,4,6,3,8,5,4,6"></span>
		</div>
	</div>

	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Date <span class="fw-300"><i>Filter</i></span></h2>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form method="get" action="<?php echo ADMIN_URL; ?>mobilenotifications/dashboard" class="form-inline">
							<div class="form-group mr-2 mb-2">
								<label class="form-label mr-2 mb-0" for="date_from">From</label>
								<input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo htmlspecialchars($date_from); ?>" required>
							</div>
							<div class="form-group mr-2 mb-2">
								<label class="form-label mr-2 mb-0" for="date_to">To</label>
								<input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo htmlspecialchars($date_to); ?>" required>
							</div>
							<button type="submit" class="btn btn-primary mb-2 mr-2">
								<i class="fal fa-filter mr-1"></i> Filter
							</button>
							<a href="<?php echo ADMIN_URL; ?>mobilenotifications" class="btn btn-secondary mb-2">
								<i class="fal fa-arrow-left mr-1"></i> Back
							</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6 col-xl-3">
			<div class="p-3 bg-primary-400 rounded overflow-hidden position-relative text-white mb-g">
				<div>
					<h3 class="display-4 d-block l-h-n m-0 fw-500"><?php echo $success_msgs; ?></h3>
					<span class="opacity-70">Total Sent</span>
				</div>
				<i class="fal fa-check-circle position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:6rem"></i>
			</div>
		</div>
		<div class="col-sm-6 col-xl-3">
			<div class="p-3 bg-danger-400 rounded overflow-hidden position-relative text-white mb-g">
				<div>
					<h3 class="display-4 d-block l-h-n m-0 fw-500"><?php echo (int) (isset($stats['total_failed']) ? $stats['total_failed'] : 0); ?></h3>
					<span class="opacity-70">Total Failed</span>
				</div>
				<i class="fal fa-times-circle position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:6rem"></i>
			</div>
		</div>
		<div class="col-sm-6 col-xl-3">
			<div class="p-3 bg-info-400 rounded overflow-hidden position-relative text-white mb-g">
				<div>
					<h3 class="display-4 d-block l-h-n m-0 fw-500"><?php echo $total_msgs; ?></h3>
					<span class="opacity-70">Total Messages</span>
				</div>
				<i class="fal fa-envelope position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:6rem"></i>
			</div>
		</div>
		<div class="col-sm-6 col-xl-3">
			<div class="p-3 bg-success-400 rounded overflow-hidden position-relative text-white mb-g">
				<div>
					<h3 class="display-4 d-block l-h-n m-0 fw-500"><?php echo $success_rate; ?>%</h3>
					<span class="opacity-70">Success Rate</span>
				</div>
				<i class="fal fa-percentage position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:6rem"></i>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xl-6">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Message Type <span class="fw-300"><i>Breakdown</i></span></h2>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<table class="table table-bordered table-striped mb-0">
							<thead class="bg-primary-600">
								<tr>
									<th>Type</th>
									<th>Count</th>
									<th>Percentage</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Billing Statements</td>
									<td><?php echo (int) (isset($stats['billing_count']) ? $stats['billing_count'] : 0); ?></td>
									<td><?php echo round(((isset($stats['billing_count']) ? (int)$stats['billing_count'] : 0) / $pct_base) * 100, 2); ?>%</td>
								</tr>
								<tr>
									<td>Due Accounts</td>
									<td><?php echo (int) (isset($stats['due_count']) ? $stats['due_count'] : 0); ?></td>
									<td><?php echo round(((isset($stats['due_count']) ? (int)$stats['due_count'] : 0) / $pct_base) * 100, 2); ?>%</td>
								</tr>
								<tr>
									<td>Disconnection Notices</td>
									<td><?php echo (int) (isset($stats['disconnection_count']) ? $stats['disconnection_count'] : 0); ?></td>
									<td><?php echo round(((isset($stats['disconnection_count']) ? (int)$stats['disconnection_count'] : 0) / $pct_base) * 100, 2); ?>%</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-6">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Daily Message <span class="fw-300"><i>Statistics</i></span></h2>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<canvas id="dailyChart" height="200"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-mn-dashboard" class="panel">
				<div class="panel-hdr">
					<h2>Notification History <span class="fw-300"><i><?php echo date('M d, Y', strtotime($date_from)); ?> – <?php echo date('M d, Y', strtotime($date_to)); ?></i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
							<thead class="bg-primary-600">
								<tr>
									<th>ID</th>
									<th>Customer</th>
									<th>Mobile Number</th>
									<th>Type</th>
									<th>Status</th>
									<th>Sent At</th>
									<th>Created At</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($notifications as $notif) {
									$type_key = isset($notif['message_type']) ? $notif['message_type'] : '';
									$type_label = isset($msg_types[$type_key]) ? $msg_types[$type_key] : $type_key;
									$status = isset($notif['status']) ? $notif['status'] : 'pending';
									if ($status === 'sent') {
										$status_class = 'badge-success';
										$status_label = 'Sent';
									} elseif ($status === 'failed') {
										$status_class = 'badge-danger';
										$status_label = 'Failed';
									} else {
										$status_class = 'badge-warning';
										$status_label = 'Pending';
									}
								?>
								<tr>
									<td><?php echo (int) $notif['id']; ?></td>
									<td><?php echo htmlspecialchars(isset($notif['customer_name']) ? $notif['customer_name'] : 'N/A'); ?></td>
									<td><?php echo htmlspecialchars(isset($notif['mobile_number']) ? $notif['mobile_number'] : ''); ?></td>
									<td><span class="badge badge-info"><?php echo htmlspecialchars($type_label); ?></span></td>
									<td><span class="badge <?php echo $status_class; ?>"><?php echo $status_label; ?></span></td>
									<td><?php echo !empty($notif['sent_at']) ? date('M d, Y H:i', strtotime($notif['sent_at'])) : 'N/A'; ?></td>
									<td><?php echo !empty($notif['created_at']) ? date('M d, Y H:i', strtotime($notif['created_at'])) : ''; ?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include('footer.php'); ?>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
</body>
</html>
<script type="text/javascript">
(function($) {
	var dailyData = <?php echo json_encode($daily_stats); ?> || [];
	var dates = dailyData.map(function(item) { return item.date; });
	var sent = dailyData.map(function(item) { return item.sent; });
	var failed = dailyData.map(function(item) { return item.failed; });

	if (document.getElementById('dailyChart') && typeof Chart !== 'undefined') {
		var ctx = document.getElementById('dailyChart').getContext('2d');
		new Chart(ctx, {
			type: 'line',
			data: {
				labels: dates,
				datasets: [{
					label: 'Sent',
					data: sent,
					borderColor: 'rgb(29, 201, 183)',
					backgroundColor: 'rgba(29, 201, 183, 0.2)',
					fill: true
				}, {
					label: 'Failed',
					data: failed,
					borderColor: 'rgb(253, 57, 122)',
					backgroundColor: 'rgba(253, 57, 122, 0.2)',
					fill: true
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					yAxes: [{ ticks: { beginAtZero: true } }]
				}
			}
		});
	}
})(jQuery);
</script>
