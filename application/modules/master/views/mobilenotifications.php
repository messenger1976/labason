<?php
	$sa4_loading_label = 'Notifications';
	$sa4_dt_entity = 'notifications';
	$sa4_panel_id = 'panel-mobilenotifications';
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

	$notifications = (isset($notifications) && is_array($notifications)) ? $notifications : array();
	$billing_periods = (isset($billing_periods) && is_array($billing_periods)) ? $billing_periods : array();
	$zones = (isset($zones) && is_array($zones)) ? $zones : array();
	$stats = (isset($stats) && is_array($stats)) ? $stats : array();

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
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-mobile"></i>
			Manage <span class="fw-300">Mobile Notifications</span>
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
		<div class="col-sm-6 col-xl-3">
			<div class="p-3 bg-success-400 rounded overflow-hidden position-relative text-white mb-g">
				<div>
					<h3 class="display-4 d-block l-h-n m-0 fw-500"><?php echo (int) (isset($stats['total_success']) ? $stats['total_success'] : 0); ?></h3>
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
			<div class="p-3 bg-warning-400 rounded overflow-hidden position-relative text-white mb-g">
				<div>
					<h3 class="display-4 d-block l-h-n m-0 fw-500"><?php echo (int) (isset($stats['total_pending']) ? $stats['total_pending'] : 0); ?></h3>
					<span class="opacity-70">Pending</span>
				</div>
				<i class="fal fa-clock position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:6rem"></i>
			</div>
		</div>
		<div class="col-sm-6 col-xl-3">
			<div class="p-3 bg-info-400 rounded overflow-hidden position-relative text-white mb-g">
				<div>
					<h3 class="display-4 d-block l-h-n m-0 fw-500"><?php echo (int) (isset($stats['total_sent']) ? $stats['total_sent'] : 0); ?></h3>
					<span class="opacity-70">Total Messages</span>
				</div>
				<i class="fal fa-envelope position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:6rem"></i>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Send <span class="fw-300"><i>Notifications</i></span></h2>
					<div class="panel-toolbar">
						<a href="<?php echo ADMIN_URL; ?>mobilenotifications/settings" class="btn btn-sm btn-outline-info mr-1">
							<i class="fal fa-cog mr-1"></i> Settings
						</a>
						<a href="<?php echo ADMIN_URL; ?>mobilenotifications/dashboard" class="btn btn-sm btn-outline-secondary">
							<i class="fal fa-chart-bar mr-1"></i> Analytics
						</a>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<div class="row">
							<div class="col-md-3 mb-2">
								<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#billingModal">
									<i class="fal fa-file-alt mr-1"></i> Billing Statements
								</button>
							</div>
							<div class="col-md-3 mb-2">
								<button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#dueModal">
									<i class="fal fa-exclamation-triangle mr-1"></i> Due Accounts
								</button>
							</div>
							<div class="col-md-3 mb-2">
								<button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#disconnectionModal">
									<i class="fal fa-ban mr-1"></i> Disconnection Notices
								</button>
							</div>
							<div class="col-md-3 mb-2">
								<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#customModal">
									<i class="fal fa-comment mr-1"></i> Custom Message
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-mobilenotifications" class="panel">
				<div class="panel-hdr">
					<h2>Notification <span class="fw-300"><i>History</i></span></h2>
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
									<th>Message</th>
									<th>Status</th>
									<th>Sent At</th>
									<th style="width:90px;">Actions</th>
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
									$msg_preview = isset($notif['message']) ? $notif['message'] : '';
									if (strlen($msg_preview) > 50) {
										$msg_preview = substr($msg_preview, 0, 50) . '…';
									}
								?>
								<tr>
									<td><?php echo (int) $notif['id']; ?></td>
									<td><?php echo htmlspecialchars(isset($notif['customer_name']) ? $notif['customer_name'] : 'N/A'); ?></td>
									<td><?php echo htmlspecialchars(isset($notif['mobile_number']) ? $notif['mobile_number'] : ''); ?></td>
									<td><span class="badge badge-info"><?php echo htmlspecialchars($type_label); ?></span></td>
									<td><?php echo htmlspecialchars($msg_preview); ?></td>
									<td><span class="badge <?php echo $status_class; ?>"><?php echo $status_label; ?></span></td>
									<td><?php echo !empty($notif['sent_at']) ? date('M d, Y H:i', strtotime($notif['sent_at'])) : 'N/A'; ?></td>
									<td>
										<button type="button" class="btn btn-outline-info btn-sm view-notification" data-id="<?php echo (int) $notif['id']; ?>" title="View" data-toggle="tooltip">
											<i class="fal fa-eye"></i>
										</button>
									</td>
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

<!-- Billing Statement Modal -->
<div class="modal fade" id="billingModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="fal fa-file-alt mr-1"></i> Send Billing Statements</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<div class="modal-body">
				<form id="billingForm">
					<div class="form-group">
						<label class="form-label" for="billing_period_id">Billing Period</label>
						<select class="form-control" name="billing_period_id" id="billing_period_id" required>
							<option value="">--Select Billing Period--</option>
							<?php foreach ($billing_periods as $period) { ?>
							<option value="<?php echo $period['bp_id']; ?>">
								<?php echo htmlspecialchars($period['month_name'].' '.$period['bp_period_year']); ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group mb-0">
						<label class="form-label" for="zone_id">Zone (Optional)</label>
						<select class="form-control" name="zone_id" id="zone_id">
							<option value="">--All Zones--</option>
							<?php foreach ($zones as $zone) { ?>
							<option value="<?php echo $zone['id']; ?>"><?php echo htmlspecialchars($zone['zone']); ?></option>
							<?php } ?>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="sendBillingBtn">Send Notifications</button>
			</div>
		</div>
	</div>
</div>

<!-- Due Accounts Modal -->
<div class="modal fade" id="dueModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="fal fa-exclamation-triangle mr-1"></i> Send Due Account Notifications</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<div class="modal-body">
				<form id="dueForm">
					<div class="form-group mb-0">
						<label class="form-label" for="days_before">Days Before Due Date</label>
						<input type="number" class="form-control" name="days_before" id="days_before" value="3" min="1" max="30" required>
						<span class="help-block">Send notification to accounts due within this many days</span>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-warning" id="sendDueBtn">Send Notifications</button>
			</div>
		</div>
	</div>
</div>

<!-- Disconnection Modal -->
<div class="modal fade" id="disconnectionModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="fal fa-ban mr-1"></i> Send Disconnection Notices</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<div class="modal-body">
				<form id="disconnectionForm">
					<div class="form-group mb-0">
						<label class="form-label" for="days_overdue">Days Overdue</label>
						<input type="number" class="form-control" name="days_overdue" id="days_overdue" value="30" min="1" max="365" required>
						<span class="help-block">Send notification to accounts overdue by this many days</span>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger" id="sendDisconnectionBtn">Send Notifications</button>
			</div>
		</div>
	</div>
</div>

<!-- Custom Message Modal -->
<div class="modal fade" id="customModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="fal fa-comment mr-1"></i> Send Custom Message</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<div class="modal-body">
				<form id="customForm">
					<div class="form-group">
						<label class="form-label" for="customer_id">Customer (Optional)</label>
						<select class="form-control" name="customer_id" id="customer_id">
							<option value="">--Select Customer--</option>
						</select>
					</div>
					<div class="form-group">
						<label class="form-label" for="mobile">Mobile Number <span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="mobile" id="mobile" required>
					</div>
					<div class="form-group mb-0">
						<label class="form-label" for="message">Message <span class="text-danger">*</span></label>
						<textarea class="form-control" name="message" id="message" rows="5" required maxlength="160"></textarea>
						<span class="help-block"><span id="charCount">0</span>/160 characters</span>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-success" id="sendCustomBtn">Send Message</button>
			</div>
		</div>
	</div>
</div>

<!-- View Notification Modal -->
<div class="modal fade" id="viewNotificationModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="fal fa-eye mr-1"></i> Notification Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<div class="modal-body" id="notificationDetails"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include('footer.php'); ?>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
</body>
</html>
<script type="text/javascript">
(function($) {
	var baseUrl = <?php echo json_encode(ADMIN_URL . 'mobilenotifications/'); ?>;

	$('#message').on('keyup', function() {
		$('#charCount').text($(this).val().length);
	});

	$.ajax({
		url: baseUrl + 'get_customers_list',
		type: 'GET',
		dataType: 'json',
		success: function(response) {
			if (response.success) {
				var options = '<option value="">--Select Customer--</option>';
				$.each(response.customers, function(i, customer) {
					options += '<option value="' + customer.customer_id + '" data-mobile="' + customer.mobile + '">' +
						$('<div>').text(customer.name + ' (' + customer.mobile + ')').html() + '</option>';
				});
				$('#customer_id').html(options);
			}
		}
	});

	$('#customer_id').on('change', function() {
		var mobile = $(this).find('option:selected').data('mobile');
		if (mobile) { $('#mobile').val(mobile); }
	});

	$('#sendBillingBtn').on('click', function() {
		if (!$('#billing_period_id').val()) {
			alert('Please select a billing period');
			return;
		}
		var $btn = $(this);
		$btn.prop('disabled', true).html('<i class="fal fa-spinner fa-spin"></i> Sending...');
		$.ajax({
			url: baseUrl + 'send_billing_statements',
			type: 'POST',
			data: $('#billingForm').serialize(),
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					alert('Sent: ' + response.sent + ', Failed: ' + response.failed + ' out of ' + response.total);
					location.reload();
				} else {
					alert('Error: ' + (response.message || 'Unknown error'));
				}
			},
			error: function() { alert('An error occurred. Please try again.'); },
			complete: function() { $btn.prop('disabled', false).html('Send Notifications'); }
		});
	});

	$('#sendDueBtn').on('click', function() {
		var $btn = $(this);
		$btn.prop('disabled', true).html('<i class="fal fa-spinner fa-spin"></i> Sending...');
		$.ajax({
			url: baseUrl + 'send_due_accounts',
			type: 'POST',
			data: $('#dueForm').serialize(),
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					alert('Sent: ' + response.sent + ', Failed: ' + response.failed + ' out of ' + response.total);
					location.reload();
				} else {
					alert('Error: ' + (response.message || 'Unknown error'));
				}
			},
			error: function() { alert('An error occurred. Please try again.'); },
			complete: function() { $btn.prop('disabled', false).html('Send Notifications'); }
		});
	});

	$('#sendDisconnectionBtn').on('click', function() {
		var $btn = $(this);
		$btn.prop('disabled', true).html('<i class="fal fa-spinner fa-spin"></i> Sending...');
		$.ajax({
			url: baseUrl + 'send_disconnection_notices',
			type: 'POST',
			data: $('#disconnectionForm').serialize(),
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					alert('Sent: ' + response.sent + ', Failed: ' + response.failed + ' out of ' + response.total);
					location.reload();
				} else {
					alert('Error: ' + (response.message || 'Unknown error'));
				}
			},
			error: function() { alert('An error occurred. Please try again.'); },
			complete: function() { $btn.prop('disabled', false).html('Send Notifications'); }
		});
	});

	$('#sendCustomBtn').on('click', function() {
		if (!$('#mobile').val() || !$('#message').val()) {
			alert('Please fill in mobile number and message');
			return;
		}
		var $btn = $(this);
		$btn.prop('disabled', true).html('<i class="fal fa-spinner fa-spin"></i> Sending...');
		$.ajax({
			url: baseUrl + 'send_custom_message',
			type: 'POST',
			data: $('#customForm').serialize(),
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					alert('Message sent successfully!');
					$('#customModal').modal('hide');
					$('#customForm')[0].reset();
					location.reload();
				} else {
					alert('Error: ' + (response.message || 'Unknown error'));
				}
			},
			error: function() { alert('An error occurred. Please try again.'); },
			complete: function() { $btn.prop('disabled', false).html('Send Message'); }
		});
	});

	$(document).on('click', '.view-notification', function() {
		var id = $(this).data('id');
		$.ajax({
			url: baseUrl + 'get_notification_details',
			type: 'POST',
			data: { id: id },
			dataType: 'json',
			success: function(response) {
				if (!response.success) { return; }
				var data = response.data;
				var statusClass = data.status == 'sent' ? 'success' : (data.status == 'failed' ? 'danger' : 'warning');
				var html = '<table class="table table-bordered">';
				html += '<tr><th style="width:30%">Customer</th><td>' + $('<div>').text(data.customer_name || 'N/A').html() + '</td></tr>';
				html += '<tr><th>Mobile Number</th><td>' + $('<div>').text(data.mobile_number || '').html() + '</td></tr>';
				html += '<tr><th>Type</th><td>' + $('<div>').text(data.message_type || '').html() + '</td></tr>';
				html += '<tr><th>Status</th><td><span class="badge badge-' + statusClass + '">' + $('<div>').text((data.status || '').toUpperCase()).html() + '</span></td></tr>';
				html += '<tr><th>Message</th><td>' + $('<div>').text(data.message || '').html() + '</td></tr>';
				html += '<tr><th>Sent At</th><td>' + $('<div>').text(data.sent_at || 'N/A').html() + '</td></tr>';
				html += '<tr><th>Created At</th><td>' + $('<div>').text(data.created_at || '').html() + '</td></tr>';
				if (data.itexmo_response) {
					html += '<tr><th>ITEXMO Response</th><td>' + $('<div>').text(data.itexmo_response).html() + '</td></tr>';
				}
				if (data.itexmo_code) {
					html += '<tr><th>ITEXMO Code</th><td>' + $('<div>').text(data.itexmo_code).html() + '</td></tr>';
				}
				html += '</table>';
				$('#notificationDetails').html(html);
				$('#viewNotificationModal').modal('show');
			}
		});
	});
})(jQuery);
</script>
