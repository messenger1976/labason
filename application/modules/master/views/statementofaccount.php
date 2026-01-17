<!-- Content starts here - no header/sidebar -->
<div style="max-width: 1400px; margin: 0 auto; background: #fff; padding: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
		<div class="row" style="margin-bottom: 20px;">
			<div class="col-xs-12 text-right">
				<button class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
				<button class="btn btn-warning" id="resetPasswordBtn"><i class="fa fa-key"></i> Reset Password</button>
				<a href="<?php echo base_url();?>master/statementofaccount/search" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to Search</a>
			</div>
		</div>
		
		<!-- Card Design -->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card" style="border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
					<div class="card-body" style="padding: 20px;">
								<!-- Customer Information -->
								<div class="row" style="margin-bottom: 20px;">
									<div class="col-md-12">
										<div class="panel panel-default">
											<div class="panel-heading">
												<strong>Customer Information</strong>
											</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-md-6">
														<table class="table table-bordered">
															<tr>
																<td width="40%"><strong>Customer ID:</strong></td>
																<td><?php echo $customer_info['customer_id']; ?></td>
															</tr>
															<tr>
																<td><strong>Name:</strong></td>
																<td><?php echo strtoupper($customer_info['last_name'].', '.$customer_info['first_name'].' '.$customer_info['middle_name']); ?></td>
															</tr>
															<tr>
																<td><strong>Address:</strong></td>
																<td><?php echo strtoupper($customer_info['address']); ?></td>
															</tr>
															<tr>
																<td><strong>Zone:</strong></td>
																<td><?php echo isset($customer_info['zone']) ? strtoupper($customer_info['zone']) : 'N/A'; ?></td>
															</tr>
														</table>
													</div>
													<div class="col-md-6">
														<table class="table table-bordered">
															<tr>
																<td width="40%"><strong>Meter Number:</strong></td>
																<td><?php echo $customer_info['meter_number']; ?></td>
															</tr>
															<tr>
																<td><strong>Classification:</strong></td>
																<td><?php echo isset($customer_info['class_name']) ? $customer_info['class_name'] : 'N/A'; ?></td>
															</tr>
															<tr>
																<td><strong>Account Type:</strong></td>
																<td><?php echo isset($customer_info['cust_type_name']) ? $customer_info['cust_type_name'] : 'N/A'; ?></td>
															</tr>
															<tr>
																<td><strong>Current Balance:</strong></td>
																<td><strong class="text-danger">PHP <?php echo number_format($current_balance, 2); ?></strong></td>
															</tr>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<!-- Ledger Table -->
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
											<table class="table table-striped table-bordered table-hover" id="ledger_table" style="width: 100%; min-width: 800px;">
												<thead>
													<tr>
														<th style="text-align: center;">Date</th>
														<th style="text-align: center;">Ref No</th>
														<th style="text-align: left;">Description</th>
														<th style="text-align: right;">Debit (Billing)</th>
														<th style="text-align: right;">Credit (Payment)</th>
														<th style="text-align: right;">Balance</th>
													</tr>
												</thead>
												<tbody>
													<?php 
													if(!empty($ledger_entries)) {
														$sn = 1;
														foreach($ledger_entries as $entry) {
															$entry_date = date('d-m-Y', strtotime($entry['date']));
															$entry_date_sort = date('Y-m-d', strtotime($entry['date'])); // ISO format for sorting
															$debit = $entry['debit'] > 0 ? number_format($entry['debit'], 2) : '';
															$credit = $entry['credit'] > 0 ? number_format($entry['credit'], 2) : '';
															$balance = number_format($entry['balance'], 2);
															$balance_class = $entry['balance'] > 0 ? 'text-danger' : 'text-success';
													?>
													<tr>
														<td style="text-align: center;" data-order="<?php echo $entry_date_sort; ?>"><?php echo $entry_date; ?></td>
														<td style="text-align: center;"><?php echo $entry['refno']; ?></td>
														<td style="text-align: left;">
															<?php echo $entry['description']; ?>
															<?php if($entry['type'] == 'billing' && isset($entry['consumed'])) { ?>
																<br><small class="text-muted">
																	Reading: <?php echo $entry['previous_reading']; ?> - <?php echo $entry['reading']; ?> 
																	(Consumed: <?php echo $entry['consumed']; ?> cu.m)
																	<?php if(isset($entry['penalty']) && $entry['penalty'] > 0) { ?>
																		| Penalty: PHP <?php echo number_format($entry['penalty'], 2); ?>
																	<?php } ?>
																</small>
															<?php } ?>
														</td>
														<td style="text-align: right;"><?php echo $debit ? 'PHP '.$debit : '-'; ?></td>
														<td style="text-align: right;"><?php echo $credit ? 'PHP '.$credit : '-'; ?></td>
														<td style="text-align: right;" class="<?php echo $balance_class; ?>">
															<strong>PHP <?php echo $balance; ?></strong>
														</td>
													</tr>
													<?php 
														$sn++;
														}
													} else {
													?>
													<tr>
														<td colspan="6" style="text-align: center;">No transactions found for this customer.</td>
													</tr>
													<?php } ?>
												</tbody>
												<?php if(!empty($ledger_entries)) { ?>
												<tfoot>
													<tr>
														<th colspan="3" style="text-align: right;"><strong>Total:</strong></th>
														<th style="text-align: right;">
															<strong>PHP <?php 
																$total_debit = array_sum(array_column($ledger_entries, 'debit'));
																echo number_format($total_debit, 2); 
															?></strong>
														</th>
														<th style="text-align: right;">
															<strong>PHP <?php 
																$total_credit = array_sum(array_column($ledger_entries, 'credit'));
																echo number_format($total_credit, 2); 
															?></strong>
														</th>
														<th style="text-align: right;" class="<?php echo $current_balance > 0 ? 'text-danger' : 'text-success'; ?>">
															<strong>PHP <?php echo number_format($current_balance, 2); ?></strong>
														</th>
													</tr>
												</tfoot>
												<?php } ?>
											</table>
										</div>
									</div>
								</div>
								
					</div>
				</div>
			</div>
		</div>
</div>
<!-- End content wrapper -->

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" id="closeResetPasswordModal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="resetPasswordModalLabel">
					<i class="fa fa-key"></i> Reset Password
				</h4>
			</div>
			<div class="modal-body">
				<form id="resetPasswordForm">
					<input type="hidden" id="reset_customer_id" name="customer_id" value="<?php echo isset($customer_info['customer_id']) ? htmlspecialchars($customer_info['customer_id']) : ''; ?>">
					
					<div class="form-group">
						<label for="new_password">New Password <span class="text-danger">*</span></label>
						<input type="password" class="form-control" id="new_password" name="new_password" required autocomplete="off">
						<small class="help-block">Enter your new password (minimum 3 characters)</small>
					</div>
					
					<div class="form-group">
						<label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
						<input type="password" class="form-control" id="confirm_password" name="confirm_password" required autocomplete="off">
						<small class="help-block">Re-enter your new password to confirm</small>
					</div>
					
					<div id="reset_password_error" class="alert alert-danger" style="display:none;"></div>
					<div id="reset_password_success" class="alert alert-success" style="display:none;"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" id="cancelResetPasswordBtn">Cancel</button>
				<button type="button" class="btn btn-primary" id="saveResetPasswordBtn">Reset Password</button>
			</div>
		</div>
	</div>
</div>

<!-- PAGE RELATED PLUGIN(S) -->
<!-- Bootstrap JS (required for modals) -->
<script src="<?php echo base_url();?>js/bootstrap/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url();?>js/plugin/datatable-responsive/datatables.responsive.min.js"></script>

<script type="text/javascript">
	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	$(document).ready(function() {
		// pageSetUp() removed - not needed for this page
		
		/* BASIC */
		var responsiveHelper_dt_basic = undefined;
		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

		$('#ledger_table').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth" : true,
			"ordering": false, // Disable sorting - data is already sorted by PHP
			"order": [], // No initial sorting
			"responsive": true,
			"pageLength": 25,
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"columnDefs": [
				{ 
					"orderable": false, // Disable sorting on all columns
					"targets": "_all"
				}
			],
			"oLanguage": {
				"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
			},
			"preDrawCallback" : function() {
				// Responsive helper - only use if available
				if (typeof ResponsiveDatatablesHelper !== 'undefined') {
					if (!responsiveHelper_dt_basic) {
						responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#ledger_table'), breakpointDefinition);
					}
				}
			},
			"rowCallback" : function(nRow) {
				if (typeof ResponsiveDatatablesHelper !== 'undefined' && responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic.createExpandIcon(nRow);
				}
			},
			"drawCallback" : function(oSettings) {
				if (typeof ResponsiveDatatablesHelper !== 'undefined' && responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic.respond();
				}
			}
		});
		
		// Reset Password Button Click Handler
		$('#resetPasswordBtn').on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			// Ensure Bootstrap modal is available
			if(typeof $.fn.modal !== 'undefined') {
				$('#resetPasswordModal').modal({
					backdrop: 'static',
					keyboard: false,
					show: true
				});
			} else {
				alert('Bootstrap modal is not loaded. Please refresh the page.');
			}
		});
		
		// Prevent modal from closing on backdrop click or ESC key during processing
		var isProcessing = false;
		
		// Reset Password Modal Handling
		$('#resetPasswordModal').on('show.bs.modal', function () {
			// Reset processing flag
			isProcessing = false;
			// Clear form and messages when modal opens
			$('#resetPasswordForm')[0].reset();
			$('#reset_password_error').hide().text('');
			$('#reset_password_success').hide().text('');
			$('#saveResetPasswordBtn').prop('disabled', false).text('Reset Password');
		});
		
		$('#resetPasswordModal').on('hide.bs.modal', function (e) {
			if(isProcessing) {
				e.preventDefault();
				e.stopImmediatePropagation();
				return false;
			}
		});
		
		// Prevent closing via backdrop click
		$('#resetPasswordModal').on('click', function(e) {
			if($(e.target).hasClass('modal') && isProcessing) {
				e.preventDefault();
				e.stopImmediatePropagation();
				return false;
			}
		});
		
		// Prevent form submission on Enter key
		$('#resetPasswordForm').on('submit', function(e) {
			e.preventDefault();
			return false;
		});
		
		// Close button handler (X button)
		$('#closeResetPasswordModal').on('click', function(e) {
			if(!isProcessing) {
				$('#resetPasswordModal').modal('hide');
			}
		});
		
		// Cancel button handler
		$('#cancelResetPasswordBtn').on('click', function(e) {
			if(!isProcessing) {
				$('#resetPasswordModal').modal('hide');
			}
		});
		
		// Prevent Enter key from submitting form
		$('#resetPasswordForm input').on('keypress', function(e) {
			if(e.which == 13) {
				e.preventDefault();
				$('#saveResetPasswordBtn').click();
				return false;
			}
		});
		
		// Handle Reset Password Button Click
		$('#saveResetPasswordBtn').on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var newPassword = $('#new_password').val();
			var confirmPassword = $('#confirm_password').val();
			var customerId = $('#reset_customer_id').val();
			
			// Clear previous messages
			$('#reset_password_error').hide().text('');
			$('#reset_password_success').hide().text('');
			
			// Validate inputs
			if(!newPassword || newPassword.length < 3) {
				$('#reset_password_error').text('Password must be at least 3 characters long.').show();
				$('#new_password').focus();
				return false;
			}
			
			if(newPassword !== confirmPassword) {
				$('#reset_password_error').text('Passwords do not match. Please try again.').show();
				$('#confirm_password').focus();
				return false;
			}
			
			// Disable button and show loading
			isProcessing = true;
			$('#saveResetPasswordBtn').prop('disabled', true).text('Resetting...');
			
			// Submit via AJAX
			$.ajax({
				url: '<?php echo base_url();?>master/statementofaccount/reset_password',
				type: 'POST',
				dataType: 'json',
				data: {
					customer_id: customerId,
					password: newPassword
				},
				success: function(response) {
					isProcessing = false;
					if(response.success) {
						$('#reset_password_success').text(response.message).show();
						// Allow modal to close and redirect to search page after 2 seconds
						setTimeout(function() {
							$('#resetPasswordModal').modal('hide');
							window.location.href = '<?php echo base_url();?>master/statementofaccount/search';
						}, 2000);
					} else {
						$('#reset_password_error').text(response.message).show();
						$('#saveResetPasswordBtn').prop('disabled', false).text('Reset Password');
					}
				},
				error: function(xhr, status, error) {
					isProcessing = false;
					var errorMsg = 'An error occurred while resetting the password. Please try again.';
					if(xhr.responseJSON && xhr.responseJSON.message) {
						errorMsg = xhr.responseJSON.message;
					}
					$('#reset_password_error').text(errorMsg).show();
					$('#saveResetPasswordBtn').prop('disabled', false).text('Reset Password');
				}
			});
		});
	});
</script>

<style>
	@media print {
		#ribbon, .btn, .jarviswidget-editbox, .dt-toolbar {
			display: none !important;
		}
		.widget-body {
			padding: 0 !important;
		}
		.page-title {
			margin-bottom: 10px !important;
		}
	}
	
	/* Mobile Responsive Styles */
	@media (max-width: 768px) {
		.table-responsive {
			overflow-x: auto;
			-webkit-overflow-scrolling: touch;
		}
		.table {
			font-size: 12px;
		}
		.table th,
		.table td {
			padding: 8px 4px;
		}
		.panel-body {
			padding: 15px !important;
		}
		.table-bordered td {
			font-size: 11px;
		}
		.btn {
			padding: 8px 12px;
			font-size: 13px;
			margin-bottom: 5px;
		}
		h1.page-title {
			font-size: 18px;
		}
		h1.page-title span {
			display: block;
			font-size: 14px;
			margin-top: 5px;
		}
	}
	
	@media (max-width: 480px) {
		.table {
			font-size: 10px;
		}
		.table th,
		.table td {
			padding: 6px 2px;
		}
		.table-bordered td {
			font-size: 10px;
		}
		.btn {
			padding: 6px 10px;
			font-size: 12px;
			width: 100%;
			margin-bottom: 5px;
		}
		.text-right {
			text-align: left !important;
		}
		.col-md-6 {
			margin-bottom: 15px;
		}
	}
</style>

</body>

</html>

