<?php
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

	$settings = (isset($settings) && is_array($settings)) ? $settings : array();
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>mobilenotifications">Mobile Notifications</a></li>
		<li class="breadcrumb-item active">Settings</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-cog"></i>
			Movider SMS <span class="fw-300">Settings</span>
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

	<?php if (!empty($msg)) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<strong>Success!</strong> <?php echo htmlspecialchars($msg); ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Movider SMS <span class="fw-300"><i>Configuration</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form method="post" action="<?php echo ADMIN_URL; ?>mobilenotifications/settings">
							<input type="hidden" name="save_settings" value="1">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="api_key">API Key <span class="text-danger">*</span></label>
										<input type="text" class="form-control" id="api_key" name="api_key" value="<?php echo htmlspecialchars(isset($settings['api_key']) ? $settings['api_key'] : ''); ?>" required autocomplete="off">
										<span class="help-block">Required Movider API key.</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="sender_name">Sender Name</label>
										<input type="text" class="form-control" id="sender_name" name="sender_name" value="<?php echo htmlspecialchars(isset($settings['sender_name']) ? $settings['sender_name'] : ''); ?>" maxlength="11">
										<span class="help-block">Optional. Use the exact registered Movider sender name and capitalization.</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="api_secret">API Secret <span class="text-danger">*</span></label>
										<input type="password" class="form-control" id="api_secret" name="api_secret" value=""<?php echo empty($settings['api_secret']) ? ' required' : ''; ?> autocomplete="new-password">
										<span class="help-block">Required for first setup. Leave blank to keep the saved secret.</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="callback_method">Delivery Callback Method</label>
										<select class="form-control" id="callback_method" name="callback_method">
											<option value="POST"<?php echo (!isset($settings['callback_method']) || $settings['callback_method'] !== 'GET') ? ' selected' : ''; ?>>POST</option>
											<option value="GET"<?php echo (isset($settings['callback_method']) && $settings['callback_method'] === 'GET') ? ' selected' : ''; ?>>GET</option>
										</select>
										<span class="help-block">Used only when a callback URL is configured.</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="callback_url">Delivery Callback URL</label>
										<input type="url" class="form-control" id="callback_url" name="callback_url" value="<?php echo htmlspecialchars(isset($settings['callback_url']) ? $settings['callback_url'] : ''); ?>">
										<span class="help-block">Optional HTTPS webhook for delivery reports.</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="endpoint_override">Endpoint Override</label>
										<input type="url" class="form-control" id="endpoint_override" name="endpoint_override" value="<?php echo htmlspecialchars(isset($settings['endpoint_override']) ? $settings['endpoint_override'] : ''); ?>" placeholder="https://api.movider.co/v1/sms">
										<span class="help-block">Optional HTTPS endpoint for controlled testing. Defaults to Movider.</span>
									</div>
								</div>
							</div>
							<div class="form-group mb-0">
								<button type="submit" class="btn btn-primary">
									<i class="fal fa-save mr-1"></i> Save Settings
								</button>
								<button type="button" class="btn btn-info" id="testApiBtn">
									<i class="fal fa-check-circle mr-1"></i> Validate Settings
								</button>
								<a href="<?php echo ADMIN_URL; ?>mobilenotifications" class="btn btn-secondary">
									<i class="fal fa-arrow-left mr-1"></i> Back
								</a>
							</div>
						</form>

						<hr class="my-4">

						<div class="alert alert-info mb-0">
							<h5 class="alert-heading"><i class="fal fa-info-circle mr-1"></i> Movider API Setup</h5>
							<ol class="mb-2 pl-3">
								<li>Visit <a href="https://console.movider.co" target="_blank" rel="noopener">console.movider.co</a> and sign in.</li>
								<li>Copy your API key and API secret from the API settings.</li>
								<li>Register a sender name before using a custom sender.</li>
								<li>Enter them above and save</li>
							</ol>
							<p class="mb-0"><strong>Note:</strong> Recipients are sent to Movider in E.164 format, such as <code>+639171234567</code>.</p>
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
(function($) {
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

	var baseUrl = <?php echo json_encode(ADMIN_URL . 'mobilenotifications/'); ?>;

	$('#testApiBtn').on('click', function() {
		var $btn = $(this);
		$btn.prop('disabled', true).html('<i class="fal fa-spinner fa-spin"></i> Validating...');
		$.ajax({
			url: baseUrl + 'test_api',
			type: 'GET',
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					alert('SUCCESS: ' + response.message);
				} else {
					var errorMsg = 'ERROR: ' + response.message;
					if (response.response_code) { errorMsg += '\nResponse Code: ' + response.response_code; }
					if (response.http_code) { errorMsg += '\nHTTP Code: ' + response.http_code; }
					alert(errorMsg);
				}
			},
			error: function(xhr, status, error) {
				var errorMsg = 'Connection Error: ' + error;
				if (xhr.responseText) {
					try {
						var response = JSON.parse(xhr.responseText);
						if (response.message) { errorMsg = response.message; }
					} catch (e) {}
				}
				alert(errorMsg);
			},
			complete: function() {
				$btn.prop('disabled', false).html('<i class="fal fa-check-circle mr-1"></i> Validate Settings');
			}
		});
	});
})(jQuery);
</script>
