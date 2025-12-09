
<!-- MAIN PANEL -->
<div id="main" role="main">

	<!-- RIBBON -->
	<div id="ribbon">
		<span class="ribbon-button-alignment"> 
			<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh" rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
				<i class="fa fa-refresh"></i>
			</span> 
		</span>

		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<li><a href="<?php echo ADMIN_URL;?>dashboard">Home</a></li>
			<li><a href="<?php echo ADMIN_URL;?>mobilenotifications">Mobile Notifications</a></li>
			<li>Analytics Dashboard</li>
		</ol>
	</div>
	<!-- END RIBBON -->

	<!-- MAIN CONTENT -->
	<div id="content">
		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-bar-chart"></i> Analytics <span>> SMS Reports</span></h1>
			</div>
		</div>
		
		<!-- widget grid -->
		<section id="widget-grid" class="">
			<div class="row">
				
				<!-- Date Filter -->
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<form method="get" action="<?php echo ADMIN_URL;?>mobilenotifications/dashboard" class="form-inline">
								<div class="form-group">
									<label>From Date:</label>
									<input type="date" class="form-control" name="date_from" value="<?php echo $date_from; ?>" required>
								</div>
								<div class="form-group">
									<label>To Date:</label>
									<input type="date" class="form-control" name="date_to" value="<?php echo $date_to; ?>" required>
								</div>
								<button type="submit" class="btn btn-primary">
									<i class="fa fa-filter"></i> Filter
								</button>
								<a href="<?php echo ADMIN_URL;?>mobilenotifications" class="btn btn-default">
									<i class="fa fa-arrow-left"></i> Back
								</a>
							</form>
						</div>
					</div>
				</div>
				
				<!-- Statistics Cards -->
				<div class="col-sm-12 col-md-3">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-check-circle"></i> Total Sent</h3>
						</div>
						<div class="panel-body">
							<h2 class="text-center"><?php echo isset($stats['total_success']) ? $stats['total_success'] : 0; ?></h2>
							<p class="text-center text-muted">Successfully sent messages</p>
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 col-md-3">
					<div class="panel panel-danger">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-times-circle"></i> Total Failed</h3>
						</div>
						<div class="panel-body">
							<h2 class="text-center"><?php echo isset($stats['total_failed']) ? $stats['total_failed'] : 0; ?></h2>
							<p class="text-center text-muted">Failed messages</p>
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 col-md-3">
					<div class="panel panel-info">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-envelope"></i> Total Messages</h3>
						</div>
						<div class="panel-body">
							<h2 class="text-center"><?php echo isset($stats['total_sent']) ? $stats['total_sent'] : 0; ?></h2>
							<p class="text-center text-muted">All messages</p>
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 col-md-3">
					<div class="panel panel-success">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-percent"></i> Success Rate</h3>
						</div>
						<div class="panel-body">
							<h2 class="text-center">
								<?php 
								$total = isset($stats['total_sent']) ? $stats['total_sent'] : 0;
								$success = isset($stats['total_success']) ? $stats['total_success'] : 0;
								$rate = $total > 0 ? round(($success / $total) * 100, 2) : 0;
								echo $rate . '%';
								?>
							</h2>
							<p class="text-center text-muted">Delivery success rate</p>
						</div>
					</div>
				</div>
				
				<!-- Message Type Breakdown -->
				<div class="col-sm-12 col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-pie-chart"></i> Message Type Breakdown</h3>
						</div>
						<div class="panel-body">
							<table class="table table-bordered">
								<tr>
									<th>Type</th>
									<th>Count</th>
									<th>Percentage</th>
								</tr>
								<tr>
									<td>Billing Statements</td>
									<td><?php echo isset($stats['billing_count']) ? $stats['billing_count'] : 0; ?></td>
									<td>
										<?php 
										$total = isset($stats['total_sent']) ? $stats['total_sent'] : 1;
										$billing = isset($stats['billing_count']) ? $stats['billing_count'] : 0;
										echo $total > 0 ? round(($billing / $total) * 100, 2) : 0;
										?>%
									</td>
								</tr>
								<tr>
									<td>Due Accounts</td>
									<td><?php echo isset($stats['due_count']) ? $stats['due_count'] : 0; ?></td>
									<td>
										<?php 
										$due = isset($stats['due_count']) ? $stats['due_count'] : 0;
										echo $total > 0 ? round(($due / $total) * 100, 2) : 0;
										?>%
									</td>
								</tr>
								<tr>
									<td>Disconnection Notices</td>
									<td><?php echo isset($stats['disconnection_count']) ? $stats['disconnection_count'] : 0; ?></td>
									<td>
										<?php 
										$disconnect = isset($stats['disconnection_count']) ? $stats['disconnection_count'] : 0;
										echo $total > 0 ? round(($disconnect / $total) * 100, 2) : 0;
										?>%
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				
				<!-- Daily Chart -->
				<div class="col-sm-12 col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-line-chart"></i> Daily Message Statistics</h3>
						</div>
						<div class="panel-body">
							<canvas id="dailyChart" height="200"></canvas>
						</div>
					</div>
				</div>
				
				<!-- Notifications List -->
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-list"></i> Notification History (<?php echo date('M d, Y', strtotime($date_from)); ?> - <?php echo date('M d, Y', strtotime($date_to)); ?>)</h3>
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover">
									<thead>
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
										<?php if(!empty($notifications)): ?>
											<?php foreach($notifications as $notif): ?>
												<tr>
													<td><?php echo $notif['id']; ?></td>
													<td><?php echo isset($notif['customer_name']) ? $notif['customer_name'] : 'N/A'; ?></td>
													<td><?php echo $notif['mobile_number']; ?></td>
													<td>
														<span class="label label-info">
															<?php 
															$types = array(
																'billing_statement' => 'Billing',
																'due_account' => 'Due Account',
																'disconnection' => 'Disconnection',
																'custom' => 'Custom'
															);
															echo isset($types[$notif['message_type']]) ? $types[$notif['message_type']] : $notif['message_type'];
															?>
														</span>
													</td>
													<td>
														<?php if($notif['status'] == 'sent'): ?>
															<span class="label label-success">Sent</span>
														<?php elseif($notif['status'] == 'failed'): ?>
															<span class="label label-danger">Failed</span>
														<?php else: ?>
															<span class="label label-warning">Pending</span>
														<?php endif; ?>
													</td>
													<td><?php echo $notif['sent_at'] ? date('M d, Y H:i', strtotime($notif['sent_at'])) : 'N/A'; ?></td>
													<td><?php echo date('M d, Y H:i', strtotime($notif['created_at'])); ?></td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td colspan="7" class="text-center">No notifications found for the selected date range</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</section>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
$(document).ready(function(){
	// Daily Chart
	var dailyData = <?php echo json_encode($daily_stats); ?>;
	var dates = dailyData.map(function(item){ return item.date; });
	var sent = dailyData.map(function(item){ return item.sent; });
	var failed = dailyData.map(function(item){ return item.failed; });
	
	var ctx = document.getElementById('dailyChart').getContext('2d');
	var chart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: dates,
			datasets: [{
				label: 'Sent',
				data: sent,
				borderColor: 'rgb(75, 192, 192)',
				backgroundColor: 'rgba(75, 192, 192, 0.2)',
				fill: true
			}, {
				label: 'Failed',
				data: failed,
				borderColor: 'rgb(255, 99, 132)',
				backgroundColor: 'rgba(255, 99, 132, 0.2)',
				fill: true
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		}
	});
});
</script>

<?php include('footer.php');?>

