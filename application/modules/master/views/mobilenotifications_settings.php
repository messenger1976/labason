
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
			<li>Settings</li>
		</ol>
	</div>
	<!-- END RIBBON -->

	<!-- MAIN CONTENT -->
	<div id="content">
		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-cog"></i> Settings <span>> ITEXMO API</span></h1>
			</div>
		</div>
		
		<!-- widget grid -->
		<section id="widget-grid" class="">
			<div class="row">
				
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-key"></i> ITEXMO API Configuration</h3>
						</div>
						<div class="panel-body">
							
							<?php if(isset($msg) && $msg != ''): ?>
							<div class="alert alert-block alert-success">
								<button type="button" class="close" data-dismiss="alert">
									<i class="icon-remove"></i>
								</button>
								<p>
									<i class="icon-ok"></i>
									<?php echo $msg; ?>
								</p>
							</div>
							<?php endif; ?>
							
							<form method="post" action="<?php echo ADMIN_URL;?>mobilenotifications/settings" class="form-horizontal">
								<input type="hidden" name="save_settings" value="1">
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Email: <span class="text-danger">*</span></label>
									<div class="col-sm-9">
										<input type="email" class="form-control" name="email" value="<?php echo isset($settings['email']) ? $settings['email'] : ''; ?>" required>
										<small class="help-block">Your ITEXMO account email address</small>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">API Code: <span class="text-danger">*</span></label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="api_code" value="<?php echo isset($settings['api_code']) ? $settings['api_code'] : ''; ?>" required>
										<small class="help-block">Your ITEXMO API Code. Get it from <a href="https://www.itexmo.com" target="_blank">itexmo.com</a></small>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">API Password: <span class="text-danger">*</span></label>
									<div class="col-sm-9">
										<input type="password" class="form-control" name="api_password" value="<?php echo isset($settings['api_password']) ? $settings['api_password'] : ''; ?>" required>
										<small class="help-block">Your ITEXMO API Password</small>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Sender ID:</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="sender_id" value="<?php echo isset($settings['sender_id']) ? $settings['sender_id'] : ''; ?>" maxlength="11">
										<small class="help-block">Optional. Your registered sender ID (max 11 characters)</small>
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-sm-offset-3 col-sm-9">
										<button type="submit" class="btn btn-primary">
											<i class="fa fa-save"></i> Save Settings
										</button>
										<button type="button" class="btn btn-info" id="testApiBtn">
											<i class="fa fa-plug"></i> Test API Connection
										</button>
										<a href="<?php echo ADMIN_URL;?>mobilenotifications" class="btn btn-default">
											<i class="fa fa-arrow-left"></i> Back to Notifications
										</a>
									</div>
								</div>
							</form>
							
							<hr>
							
							<div class="alert alert-info">
								<h4><i class="fa fa-info-circle"></i> How to Get ITEXMO API Credentials:</h4>
								<ol>
									<li>Visit <a href="https://www.itexmo.com" target="_blank">https://www.itexmo.com</a></li>
									<li>Register for an account or log in if you already have one</li>
									<li>Go to your dashboard and navigate to API Settings</li>
									<li>Copy your API Code and API Password</li>
									<li>Enter them in the form above and save</li>
								</ol>
								<p><strong>Note:</strong> Make sure you have sufficient credits in your ITEXMO account to send messages.</p>
							</div>
							
						</div>
					</div>
				</div>
				
			</div>
		</section>
	</div>
</div>

<?php include('footer.php');?>

<script>
// Ensure jQuery is loaded before executing
if (typeof jQuery === 'undefined') {
	setTimeout(function() {
		if (typeof jQuery !== 'undefined') {
			initTestApiScript();
		}
	}, 100);
} else {
	initTestApiScript();
}

function initTestApiScript() {
	var $ = jQuery;
	
	$(document).ready(function(){
		$('#testApiBtn').on('click', function(){
			var btn = $(this);
			btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Testing...');
			
			// Get current protocol
			var protocol = window.location.protocol;
			var host = window.location.host;
			var baseUrl = protocol + '//' + host + '/master/mobilenotifications/';
			
			$.ajax({
				url: baseUrl + 'test_api',
				type: 'GET',
				dataType: 'json',
				success: function(response){
					if(response.success){
						alert('SUCCESS: ' + response.message);
					} else {
						var errorMsg = 'ERROR: ' + response.message;
						if(response.response_code){
							errorMsg += '\nResponse Code: ' + response.response_code;
						}
						if(response.http_code){
							errorMsg += '\nHTTP Code: ' + response.http_code;
						}
						alert(errorMsg);
					}
				},
				error: function(xhr, status, error){
					var errorMsg = 'Connection Error: ' + error;
					if(xhr.responseText){
						try {
							var response = JSON.parse(xhr.responseText);
							if(response.message){
								errorMsg = response.message;
							}
						} catch(e) {
							// Not JSON, use default error
						}
					}
					alert(errorMsg);
				},
				complete: function(){
					btn.prop('disabled', false).html('<i class="fa fa-plug"></i> Test API Connection');
				}
			});
		});
	});
}
</script>

