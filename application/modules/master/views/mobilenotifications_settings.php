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
			ITEXMO API <span class="fw-300">Settings</span>
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
					<h2>ITEXMO API <span class="fw-300"><i>Configuration</i></span></h2>
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
										<label class="form-label" for="email">Email <span class="text-danger">*</span></label>
										<input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars(isset($settings['email']) ? $settings['email'] : ''); ?>" required>
										<span class="help-block">Your ITEXMO account email address</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="sender_id">Sender ID</label>
										<input type="text" class="form-control" id="sender_id" name="sender_id" value="<?php echo htmlspecialchars(isset($settings['sender_id']) ? $settings['sender_id'] : ''); ?>" maxlength="11">
										<span class="help-block">Optional. Registered sender ID (max 11 characters)</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="api_code">API Code <span class="text-danger">*</span></label>
										<input type="text" class="form-control" id="api_code" name="api_code" value="<?php echo htmlspecialchars(isset($settings['api_code']) ? $settings['api_code'] : ''); ?>" required>
										<span class="help-block">Get it from <a href="https://www.itexmo.com" target="_blank" rel="noopener">itexmo.com</a></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="api_password">API Password <span class="text-danger">*</span></label>
										<input type="password" class="form-control" id="api_password" name="api_password" value="<?php echo htmlspecialchars(isset($settings['api_password']) ? $settings['api_password'] : ''); ?>" required>
										<span class="help-block">Your ITEXMO API Password</span>
									</div>
								</div>
							</div>
							<div class="form-group mb-0">
								<button type="submit" class="btn btn-primary">
									<i class="fal fa-save mr-1"></i> Save Settings
								</button>
								<button type="button" class="btn btn-info" id="testApiBtn">
									<i class="fal fa-plug mr-1"></i> Test API Connection
								</button>
								<a href="<?php echo ADMIN_URL; ?>mobilenotifications" class="btn btn-secondary">
									<i class="fal fa-arrow-left mr-1"></i> Back
								</a>
							</div>
						</form>

						<hr class="my-4">

						<div class="alert alert-info mb-0">
							<h5 class="alert-heading"><i class="fal fa-info-circle mr-1"></i> How to Get ITEXMO API Credentials</h5>
							<ol class="mb-2 pl-3">
								<li>Visit <a href="https://www.itexmo.com" target="_blank" rel="noopener">https://www.itexmo.com</a></li>
								<li>Register or log in to your account</li>
								<li>Open API Settings from your dashboard</li>
								<li>Copy your API Code and API Password</li>
								<li>Enter them above and save</li>
							</ol>
							<p class="mb-0"><strong>Note:</strong> Make sure you have sufficient credits in your ITEXMO account.</p>
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
		$btn.prop('disabled', true).html('<i class="fal fa-spinner fa-spin"></i> Testing...');
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
				$btn.prop('disabled', false).html('<i class="fal fa-plug mr-1"></i> Test API Connection');
			}
		});
	});
})(jQuery);
</script>
