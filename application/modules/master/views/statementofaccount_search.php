<!-- Content starts here - no header/sidebar -->
<div style="max-width: 1200px; margin: 0 auto; background: #fff; padding: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); min-height: 100vh;">
		
		<!-- Card Design -->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3">
				<div class="card" style="border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; background: #fff;">
					<div class="card-body" style="padding: 30px 20px;">
								<?php if($msg != ''){ ?>
								<div class="alert alert-block alert-danger">
									<button type="button" class="close" data-dismiss="alert">
										<i class="icon-remove"></i>
									</button>
									<p>
										<i class="icon-warning-sign"></i>
										<?php echo $msg; ?>
									</p>
								</div>
								<?php } ?>
								
								<!-- Customer ID Input -->
								<div class="panel panel-primary" style="border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
									<div class="panel-body" style="padding: 30px 20px;">
										<h3 style="margin-top: 0; margin-bottom: 25px; color: #31708f; text-align: center; font-size: 24px;">
											<i class="fa fa-id-card"></i> Enter Customer ID
										</h3>
										<div class="form-group" style="margin-bottom: 20px;">
											<label class="control-label" style="font-weight: 600; margin-bottom: 12px; font-size: 16px; display: block;">
												Customer ID <span class="text-danger">*</span>
											</label>
											<div class="input-group input-group-lg" style="width: 100%;">
												<span class="input-group-addon" style="background: #5bc0de; color: #fff; border: 1px solid #5bc0de; min-width: 50px;">
													<i class="fa fa-id-card"></i>
												</span>
												<input type="text" class="form-control" id="direct_customer_id" name="direct_customer_id" 
													placeholder="Enter Customer ID" 
													style="height: 50px; font-size: 16px; border: 1px solid #5bc0de; -webkit-appearance: none;" 
													autofocus required autocomplete="off" inputmode="text">
											</div>
											<small class="help-block" style="margin-top: 10px; color: #777; font-size: 14px; text-align: center; display: block;">
												<i class="fa fa-info-circle"></i> Type your customer ID and press Enter or tap the button
											</small>
										</div>
										<div class="form-group" style="margin-top: 25px;">
											<button type="button" class="btn btn-primary btn-lg btn-block" onclick="goToStatement()" style="padding: 15px; font-size: 18px; font-weight: 600; -webkit-tap-highlight-color: rgba(0,0,0,0.1);">
												<i class="fa fa-search"></i> View Statement
											</button>
										</div>
									</div>
								</div>
								
					</div>
				</div>
			</div>
		</div>
</div>
<!-- End content wrapper -->

<!-- PAGE RELATED PLUGIN(S) -->
<script type="text/javascript">
	// Wait for jQuery to be loaded
	(function() {
		function initScripts() {
			if (typeof jQuery === 'undefined') {
				setTimeout(initScripts, 100);
				return;
			}
			
			// DO NOT REMOVE : GLOBAL FUNCTIONS!
			jQuery(document).ready(function($) {
				// Enable Enter key on customer ID input
				$('#direct_customer_id').on('keypress', function(e) {
					if(e.which == 13) {
						e.preventDefault();
						goToStatement();
					}
				});
				
				// Focus on input field when page loads
				$('#direct_customer_id').focus();
			});
		}
		
		initScripts();
	})();
	
	function goToStatement() {
		var customerId = document.getElementById('direct_customer_id').value.trim();
		if(customerId == '') {
			alert('Please enter a Customer ID');
			document.getElementById('direct_customer_id').focus();
			return false;
		}
		window.location.href = '<?php echo base_url();?>master/statementofaccount/index/' + customerId;
	}
</script>

<style>
	/* Modern Dashlite-style Design - Mobile Friendly */
	.panel {
		border-radius: 8px;
		transition: all 0.3s ease;
	}
	.panel:hover {
		box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
	}
	.panel-primary {
		border-color: #5bc0de;
		background: #fff;
	}
	.form-control {
		border-radius: 4px;
		border: 1px solid #ddd;
		transition: border-color 0.3s ease;
		-webkit-appearance: none;
		-moz-appearance: none;
		appearance: none;
	}
	.form-control:focus {
		border-color: #5bc0de;
		box-shadow: 0 0 0 0.2rem rgba(91, 192, 222, 0.25);
		outline: none;
	}
	.input-group-addon {
		border-radius: 4px 0 0 4px;
		border: 1px solid #ddd;
	}
	.btn {
		border-radius: 4px;
		transition: all 0.3s ease;
		-webkit-tap-highlight-color: rgba(0,0,0,0.1);
		touch-action: manipulation;
	}
	.btn:hover, .btn:active {
		transform: translateY(-1px);
		box-shadow: 0 2px 4px rgba(0,0,0,0.2);
	}
	.btn-primary {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		border: none;
	}
	.help-block {
		margin-top: 8px;
		font-size: 12px;
		color: #777;
	}
	.alert {
		border-radius: 6px;
		border: none;
	}
	.card {
		background: #fff;
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 2px 8px rgba(0,0,0,0.1);
		transition: box-shadow 0.3s ease;
	}
	.card:hover {
		box-shadow: 0 4px 12px rgba(0,0,0,0.15);
	}
	.card-body {
		background: #fff;
	}
	
	/* Mobile Optimizations */
	@media (max-width: 768px) {
		.panel-body {
			padding: 20px 15px !important;
		}
		h3 {
			font-size: 20px !important;
		}
		.input-group-lg .form-control {
			height: 48px;
			font-size: 16px; /* Prevents zoom on iOS */
		}
		.btn-lg {
			padding: 12px;
			font-size: 16px;
		}
		.help-block {
			font-size: 13px;
		}
	}
	
	@media (max-width: 480px) {
		.panel-body {
			padding: 15px 10px !important;
		}
		h3 {
			font-size: 18px !important;
			margin-bottom: 20px !important;
		}
		.input-group-lg .form-control {
			height: 46px;
			font-size: 16px;
		}
		.btn-lg {
			padding: 12px;
			font-size: 16px;
		}
		label {
			font-size: 14px;
		}
	}
	
	/* Touch-friendly */
	@media (hover: none) and (pointer: coarse) {
		.btn {
			min-height: 44px; /* Minimum touch target size */
		}
		input, select, textarea {
			min-height: 44px;
		}
	}
</style>

</body>

</html>

