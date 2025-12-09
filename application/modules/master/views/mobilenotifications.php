
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
			<li>List View</li>
		</ol>
	</div>
	<!-- END RIBBON -->

	<!-- MAIN CONTENT -->
	<div id="content">
		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-mobile"></i> Mobile <span>> Notifications</span></h1>
			</div>
		</div>
		
		<!-- widget grid -->
		<section id="widget-grid" class="">
			<div class="row">
				
				<!-- Statistics Widgets -->
				<div class="col-sm-12 col-md-3">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-check-circle"></i> Total Sent</h3>
						</div>
						<div class="panel-body">
							<h2 class="text-center"><?php echo isset($stats['total_success']) ? $stats['total_success'] : 0; ?></h2>
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
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 col-md-3">
					<div class="panel panel-warning">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-clock-o"></i> Pending</h3>
						</div>
						<div class="panel-body">
							<h2 class="text-center"><?php echo isset($stats['total_pending']) ? $stats['total_pending'] : 0; ?></h2>
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
						</div>
					</div>
				</div>
				
				<!-- Action Buttons -->
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-paper-plane"></i> Send Notifications</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-3">
									<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#billingModal">
										<i class="fa fa-file-text"></i> Billing Statements
									</button>
								</div>
								<div class="col-md-3">
									<button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#dueModal">
										<i class="fa fa-exclamation-triangle"></i> Due Accounts
									</button>
								</div>
								<div class="col-md-3">
									<button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#disconnectionModal">
										<i class="fa fa-ban"></i> Disconnection Notices
									</button>
								</div>
								<div class="col-md-3">
									<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#customModal">
										<i class="fa fa-comment"></i> Custom Message
									</button>
								</div>
							</div>
							<div class="row" style="margin-top: 15px;">
								<div class="col-md-12 text-right">
									<a href="<?php echo ADMIN_URL;?>mobilenotifications/settings" class="btn btn-info">
										<i class="fa fa-cog"></i> Settings
									</a>
									<a href="<?php echo ADMIN_URL;?>mobilenotifications/dashboard" class="btn btn-default">
										<i class="fa fa-bar-chart"></i> Analytics Dashboard
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Notifications List -->
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-list"></i> Notification History</h3>
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover" id="notificationsTable">
									<thead>
										<tr>
											<th>ID</th>
											<th>Customer</th>
											<th>Mobile Number</th>
											<th>Type</th>
											<th>Message</th>
											<th>Status</th>
											<th>Sent At</th>
											<th>Actions</th>
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
													<td><?php echo substr($notif['message'], 0, 50) . '...'; ?></td>
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
													<td>
														<button type="button" class="btn btn-xs btn-info view-notification" data-id="<?php echo $notif['id']; ?>">
															<i class="fa fa-eye"></i> View
														</button>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td colspan="8" class="text-center">No notifications found</td>
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

<!-- Billing Statement Modal -->
<div class="modal fade" id="billingModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><i class="fa fa-file-text"></i> Send Billing Statements</h4>
			</div>
			<div class="modal-body">
				<form id="billingForm">
					<div class="form-group">
						<label>Billing Period:</label>
						<select class="form-control" name="billing_period_id" id="billing_period_id" required>
							<option value="">--Select Billing Period--</option>
							<?php 
							foreach($billing_periods as $period): ?>
								<option value="<?php echo $period['bp_id']; ?>">
									<?php echo $period['month_name'] . ' ' . $period['bp_period_year']; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label>Zone (Optional):</label>
						<select class="form-control" name="zone_id" id="zone_id">
							<option value="">--All Zones--</option>
							<?php 
							foreach($zones as $zone): ?>
								<option value="<?php echo $zone['id']; ?>"><?php echo $zone['zone']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="sendBillingBtn">Send Notifications</button>
			</div>
		</div>
	</div>
</div>

<!-- Due Accounts Modal -->
<div class="modal fade" id="dueModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Send Due Account Notifications</h4>
			</div>
			<div class="modal-body">
				<form id="dueForm">
					<div class="form-group">
						<label>Days Before Due Date:</label>
						<input type="number" class="form-control" name="days_before" id="days_before" value="3" min="1" max="30" required>
						<small class="help-block">Send notification to accounts due within this many days</small>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-warning" id="sendDueBtn">Send Notifications</button>
			</div>
		</div>
	</div>
</div>

<!-- Disconnection Modal -->
<div class="modal fade" id="disconnectionModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><i class="fa fa-ban"></i> Send Disconnection Notices</h4>
			</div>
			<div class="modal-body">
				<form id="disconnectionForm">
					<div class="form-group">
						<label>Days Overdue:</label>
						<input type="number" class="form-control" name="days_overdue" id="days_overdue" value="30" min="1" max="365" required>
						<small class="help-block">Send notification to accounts overdue by this many days</small>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger" id="sendDisconnectionBtn">Send Notifications</button>
			</div>
		</div>
	</div>
</div>

<!-- Custom Message Modal -->
<div class="modal fade" id="customModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><i class="fa fa-comment"></i> Send Custom Message</h4>
			</div>
			<div class="modal-body">
				<form id="customForm">
					<div class="form-group">
						<label>Customer (Optional):</label>
						<select class="form-control" name="customer_id" id="customer_id">
							<option value="">--Select Customer--</option>
						</select>
					</div>
					<div class="form-group">
						<label>Mobile Number: <span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="mobile" id="mobile" required>
					</div>
					<div class="form-group">
						<label>Message: <span class="text-danger">*</span></label>
						<textarea class="form-control" name="message" id="message" rows="5" required maxlength="160"></textarea>
						<small class="help-block"><span id="charCount">0</span>/160 characters</small>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-success" id="sendCustomBtn">Send Message</button>
			</div>
		</div>
	</div>
</div>

<!-- View Notification Modal -->
<div class="modal fade" id="viewNotificationModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><i class="fa fa-eye"></i> Notification Details</h4>
			</div>
			<div class="modal-body" id="notificationDetails">
				<!-- Content loaded via AJAX -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<?php include('footer.php');?>

<script>
// Ensure jQuery is loaded before executing
if (typeof jQuery === 'undefined') {
	// If jQuery is not loaded, wait a bit and try again
	setTimeout(function() {
		if (typeof jQuery !== 'undefined') {
			initNotificationsScript();
		}
	}, 100);
} else {
	initNotificationsScript();
}

function initNotificationsScript() {
	var $ = jQuery;
	// Get current protocol (http or https)
	var protocol = window.location.protocol;
	var host = window.location.host;
	var baseUrl = protocol + '//' + host + '/master/mobilenotifications/';
	
	$(document).ready(function(){
	// Character counter for custom message
	$('#message').on('keyup', function(){
		$('#charCount').text($(this).val().length);
	});
	
	// Load customers for custom message
	$.ajax({
		url: baseUrl + 'get_customers_list',
		type: 'GET',
		dataType: 'json',
		success: function(response){
			if(response.success){
				var options = '<option value="">--Select Customer--</option>';
				$.each(response.customers, function(i, customer){
					options += '<option value="' + customer.customer_id + '" data-mobile="' + customer.mobile + '">' + customer.name + ' (' + customer.mobile + ')</option>';
				});
				$('#customer_id').html(options);
			}
		}
	});
	
	// Auto-fill mobile when customer is selected
	$('#customer_id').on('change', function(){
		var mobile = $(this).find('option:selected').data('mobile');
		if(mobile){
			$('#mobile').val(mobile);
		}
	});
	
	// Send Billing Statements
	$('#sendBillingBtn').on('click', function(){
		var formData = $('#billingForm').serialize();
		if(!$('#billing_period_id').val()){
			alert('Please select a billing period');
			return;
		}
		
		$(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');
		
		$.ajax({
			url: baseUrl + 'send_billing_statements',
			type: 'POST',
			data: formData,
			dataType: 'json',
			success: function(response){
				if(response.success){
					alert('Sent: ' + response.sent + ', Failed: ' + response.failed + ' out of ' + response.total);
					location.reload();
				} else {
					alert('Error: ' + response.message);
				}
			},
			error: function(){
				alert('An error occurred. Please try again.');
			},
			complete: function(){
				$('#sendBillingBtn').prop('disabled', false).html('Send Notifications');
			}
		});
	});
	
	// Send Due Accounts
	$('#sendDueBtn').on('click', function(){
		var formData = $('#dueForm').serialize();
		$(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');
		
		$.ajax({
			url: baseUrl + 'send_due_accounts',
			type: 'POST',
			data: formData,
			dataType: 'json',
			success: function(response){
				if(response.success){
					alert('Sent: ' + response.sent + ', Failed: ' + response.failed + ' out of ' + response.total);
					location.reload();
				} else {
					alert('Error: ' + response.message);
				}
			},
			error: function(){
				alert('An error occurred. Please try again.');
			},
			complete: function(){
				$('#sendDueBtn').prop('disabled', false).html('Send Notifications');
			}
		});
	});
	
	// Send Disconnection Notices
	$('#sendDisconnectionBtn').on('click', function(){
		var formData = $('#disconnectionForm').serialize();
		$(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');
		
		$.ajax({
			url: baseUrl + 'send_disconnection_notices',
			type: 'POST',
			data: formData,
			dataType: 'json',
			success: function(response){
				if(response.success){
					alert('Sent: ' + response.sent + ', Failed: ' + response.failed + ' out of ' + response.total);
					location.reload();
				} else {
					alert('Error: ' + response.message);
				}
			},
			error: function(){
				alert('An error occurred. Please try again.');
			},
			complete: function(){
				$('#sendDisconnectionBtn').prop('disabled', false).html('Send Notifications');
			}
		});
	});
	
	// Send Custom Message
	$('#sendCustomBtn').on('click', function(){
		var formData = $('#customForm').serialize();
		if(!$('#mobile').val() || !$('#message').val()){
			alert('Please fill in mobile number and message');
			return;
		}
		
		$(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');
		
		$.ajax({
			url: baseUrl + 'send_custom_message',
			type: 'POST',
			data: formData,
			dataType: 'json',
			success: function(response){
				if(response.success){
					alert('Message sent successfully!');
					$('#customModal').modal('hide');
					$('#customForm')[0].reset();
					location.reload();
				} else {
					alert('Error: ' + response.message);
				}
			},
			error: function(){
				alert('An error occurred. Please try again.');
			},
			complete: function(){
				$('#sendCustomBtn').prop('disabled', false).html('Send Message');
			}
		});
	});
	
	// View Notification Details
	$('.view-notification').on('click', function(){
		var id = $(this).data('id');
		$.ajax({
			url: baseUrl + 'get_notification_details',
			type: 'POST',
			data: {id: id},
			dataType: 'json',
			success: function(response){
				if(response.success){
					var data = response.data;
					var html = '<table class="table table-bordered">';
					html += '<tr><th width="30%">Customer:</th><td>' + (data.customer_name || 'N/A') + '</td></tr>';
					html += '<tr><th>Mobile Number:</th><td>' + data.mobile_number + '</td></tr>';
					html += '<tr><th>Type:</th><td>' + data.message_type + '</td></tr>';
					html += '<tr><th>Status:</th><td><span class="label label-' + (data.status == 'sent' ? 'success' : (data.status == 'failed' ? 'danger' : 'warning')) + '">' + data.status.toUpperCase() + '</span></td></tr>';
					html += '<tr><th>Message:</th><td>' + data.message + '</td></tr>';
					html += '<tr><th>Sent At:</th><td>' + (data.sent_at || 'N/A') + '</td></tr>';
					html += '<tr><th>Created At:</th><td>' + data.created_at + '</td></tr>';
					if(data.itexmo_response){
						html += '<tr><th>ITEXMO Response:</th><td>' + data.itexmo_response + '</td></tr>';
					}
					if(data.itexmo_code){
						html += '<tr><th>ITEXMO Code:</th><td>' + data.itexmo_code + '</td></tr>';
					}
					html += '</table>';
					$('#notificationDetails').html(html);
					$('#viewNotificationModal').modal('show');
				}
			}
		});
	});
	});
}
</script>

