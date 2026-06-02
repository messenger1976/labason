<!-- Content starts here - no header/sidebar -->
<div class="statement-container" style="max-width: 1400px; margin: 0 auto; background: #fff; padding: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
		<div class="row no-print action-buttons" style="margin-bottom: 20px;">
			<div class="col-xs-12 col-sm-12">
				<div class="btn-group-mobile">
					<button type="button" class="btn btn-primary btn-block-mobile" id="printStatementBtn"><i class="fa fa-print"></i> Print</button>
					<button class="btn btn-warning btn-block-mobile" id="resetPasswordBtn"><i class="fa fa-key"></i> Reset Password</button>
					<a href="<?php echo base_url();?>master/statementofaccount/search" class="btn btn-default btn-block-mobile"><i class="fa fa-arrow-left"></i> Back to Search</a>
				</div>
			</div>
		</div>
		
		<?php
			$printed_at = date('Y-m-d H:i:s');
			$prepared_name = 'MISHELLE P. MONDARTE';
			$prepared_title = 'Industrial Relations Management Officer C / Billing Officer';
			$verified_name = 'DARYL JAY T. VILLARIN';
			$verified_title = 'Administrative/General Services Officer B / HRMO/FO/BO';
			$approved_name = 'ENGR. ANASTACIA T. ROMANILLOS, CE';
			$approved_title = 'General Manager';

			if (isset($preparedby[0])) {
				$prepared_name = trim($preparedby[0]['first_name'].' '.$preparedby[0]['middle_name'].' '.$preparedby[0]['last_name']);
				$prepared_title = isset($preparedby[0]['jobtitle']) ? $preparedby[0]['jobtitle'] : $prepared_title;
			}
			if (isset($verifiedby[0])) {
				$verified_name = trim($verifiedby[0]['first_name'].' '.$verifiedby[0]['middle_name'].' '.$verifiedby[0]['last_name']);
				$verified_title = isset($verifiedby[0]['jobtitle']) ? $verifiedby[0]['jobtitle'] : $verified_title;
			}
			if (isset($approvedby[0])) {
				$approved_name = trim($approvedby[0]['first_name'].' '.$approvedby[0]['middle_name'].' '.$approvedby[0]['last_name']);
				$approved_title = isset($approvedby[0]['jobtitle']) ? $approvedby[0]['jobtitle'] : $approved_title;
			}
		?>

		<!-- Report Header (logo same as other reports) -->
		<div class="report-header">
			<div class="report-logo">
				<img src="<?php echo site_url();?>images/mroxas-logo-report.jpg" height="80px" alt="Labason Water District Logo">
			</div>
			<div class="report-title">STATEMENT OF ACCOUNT</div>
			<div class="report-subtitle">
				Customer ID: <?php echo htmlspecialchars($customer_info['customer_id'], ENT_QUOTES, 'UTF-8'); ?>
				&nbsp; | &nbsp; Date/Time printed: <?php echo htmlspecialchars($printed_at, ENT_QUOTES, 'UTF-8'); ?>
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
																<td><strong>Current Billing Balance:</strong></td>
																<td><strong class="text-danger">PHP <?php echo number_format($current_balance, 2); ?></strong></td>
															</tr>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<!-- Ledger Table (screen: DataTables) -->
								<div class="row">
									<div class="col-md-12">
										<div class="ledger-screen-only">
										<div class="table-responsive ledger-print-area" id="ledger_print_fit" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
											<table class="table table-striped table-bordered table-hover" id="ledger_table">
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
														<td style="text-align: right;">
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
										<!-- Print-only ledger (no DataTables — reliable in Firefox) -->
										<div class="ledger-print-only" id="ledger_print_only_wrap" aria-hidden="true"></div>
									</div>
								</div>

								<!-- Signatures (layout based on Leakingentry print_forms last page) -->
								<div class="signature-block page-break-inside-avoid">
									<div class="sig-row">
										<div class="sig-item">
											<div class="sig-label">Prepared by:</div>
											<div class="sig-line"><?php echo htmlspecialchars(strtoupper($prepared_name), ENT_QUOTES, 'UTF-8'); ?></div>
											<div class="sig-title"><?php echo htmlspecialchars($prepared_title, ENT_QUOTES, 'UTF-8'); ?></div>
										</div>
										<div class="sig-item">
											<div class="sig-label">Verified Correct:</div>
											<div class="sig-line"><?php echo htmlspecialchars(strtoupper($verified_name), ENT_QUOTES, 'UTF-8'); ?></div>
											<div class="sig-title"><?php echo htmlspecialchars($verified_title, ENT_QUOTES, 'UTF-8'); ?></div>
										</div>
										<div class="sig-item">
											<div class="sig-label">Approved:</div>
											<div class="sig-line"><?php echo htmlspecialchars(strtoupper($approved_name), ENT_QUOTES, 'UTF-8'); ?></div>
											<div class="sig-title"><?php echo htmlspecialchars($approved_title, ENT_QUOTES, 'UTF-8'); ?></div>
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

		var ledgerDataTable = $('#ledger_table').dataTable({
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

		var soaSavedPageLength = 25;

		function soaBuildPrintLedgerTable() {
			var $src = $('#ledger_table');
			var $wrap = $('#ledger_print_only_wrap');
			if (!$src.length || !$wrap.length) {
				return;
			}

			var $clone = $src.clone(false);
			$clone.attr('id', 'ledger_table_print');
			$clone.removeClass('dataTable no-footer');
			$clone.addClass('soa-ledger-print table table-striped table-bordered');

			$clone.find('th, td').each(function() {
				$(this).removeAttr('style').removeAttr('data-order');
			});
			$clone.find('tbody tr').removeClass('odd even');

			var $ths = $clone.find('thead th');
			if ($ths.length >= 6) {
				$ths.eq(0).text('Date');
				$ths.eq(1).text('Ref');
				$ths.eq(2).text('Description');
				$ths.eq(3).text('Debit');
				$ths.eq(4).text('Credit');
				$ths.eq(5).text('Balance');
			}

			$clone.prepend(
				'<colgroup>' +
				'<col class="col-date">' +
				'<col class="col-ref">' +
				'<col class="col-desc">' +
				'<col class="col-amt">' +
				'<col class="col-amt">' +
				'<col class="col-amt">' +
				'</colgroup>'
			);

			$wrap.empty().append($clone);
		}

		function soaClearPrintLedgerTable() {
			$('#ledger_print_only_wrap').empty();
		}

		window.printStatementOfAccount = function() {
			var settings = ledgerDataTable.fnSettings();
			soaSavedPageLength = settings._iDisplayLength;
			settings._iDisplayLength = -1;
			ledgerDataTable.fnDraw(false);
			setTimeout(function() {
				soaBuildPrintLedgerTable();
				window.print();
			}, 200);
		};

		window.soaRestoreLedgerAfterPrint = function() {
			soaClearPrintLedgerTable();
			var settings = ledgerDataTable.fnSettings();
			settings._iDisplayLength = soaSavedPageLength;
			ledgerDataTable.fnDraw(false);
		};

		$('#printStatementBtn').on('click', function(e) {
			e.preventDefault();
			printStatementOfAccount();
		});

		if (window.matchMedia) {
			window.matchMedia('print').addListener(function(mql) {
				if (!mql.matches && typeof soaRestoreLedgerAfterPrint === 'function') {
					soaRestoreLedgerAfterPrint();
				}
			});
		}
		if ('onafterprint' in window) {
			window.onafterprint = soaRestoreLedgerAfterPrint;
		}

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
	/* Report header + signatures (screen + print) */
	.report-header {
		text-align: center;
		margin: 0 0 15px 0;
	}
	.report-logo {
		margin-bottom: 5px;
	}
	.report-title {
		font-size: 16px;
		font-weight: bold;
		letter-spacing: 0.5px;
	}
	.report-subtitle {
		font-size: 12px;
		color: #555;
		margin-top: 2px;
	}

	.signature-block {
		margin-top: 25px;
		padding-top: 10px;
		border-top: 1px solid #ddd;
	}
	.sig-row {
		display: flex;
		gap: 20px;
		justify-content: space-between;
		flex-wrap: wrap;
	}
	.sig-item {
		flex: 1 1 250px;
		min-width: 250px;
	}
	.sig-label {
		font-size: 12px;
		margin-bottom: 28px;
	}
	.sig-line {
		border-top: 1px solid #000;
		padding-top: 3px;
		font-weight: bold;
		font-size: 12px;
		text-align: center;
	}
	.sig-title {
		font-size: 11px;
		text-align: center;
		margin-top: 2px;
	}

	/* Screen: horizontal scroll for wide ledger */
	#ledger_table {
		width: 100%;
		min-width: 800px;
	}

	.ledger-print-only {
		display: none;
	}

	@media print {
		/* Hide non-essential elements */
		#ribbon, .btn, .jarviswidget-editbox, .dt-toolbar, 
		button, .no-print, .dataTables_wrapper .dataTables_filter,
		.dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_info,
		.dataTables_wrapper .dataTables_paginate,
		.modal, #resetPasswordModal, .modal-backdrop, .modal-dialog, .modal-content {
			display: none !important;
		}
		
		/* Page setup */
		@page {
			size: A4 portrait;
			margin: 0.8cm 1cm;
		}
		
		/* Body and container */
		body {
			background: white !important;
			color: black !important;
			font-size: 10pt;
			line-height: 1.35;
		}

		/* Screen ledger hidden; print-only clone used (Firefox-safe) */
		.ledger-screen-only,
		.dataTables_wrapper {
			display: none !important;
		}

		.ledger-print-only {
			display: block !important;
			width: 100% !important;
			max-width: 100% !important;
			overflow: hidden !important;
		}

		.statement-container,
		.statement-container .card,
		.statement-container .card-body,
		.statement-container .row,
		.statement-container [class*="col-"] {
			width: 100% !important;
			max-width: 100% !important;
			box-sizing: border-box !important;
			overflow: visible !important;
		}

		#ledger_table_print.soa-ledger-print {
			min-width: 0 !important;
			max-width: 100% !important;
			width: 100% !important;
			table-layout: fixed !important;
			border-collapse: collapse !important;
			font-size: 7pt;
		}

		#ledger_table_print col.col-date { width: 11%; }
		#ledger_table_print col.col-ref { width: 9%; }
		#ledger_table_print col.col-desc { width: 40%; }
		#ledger_table_print col.col-amt { width: 13.33%; }

		#ledger_table_print thead {
			display: table-header-group;
		}

		#ledger_table_print tfoot {
			display: table-footer-group;
		}

		#ledger_table_print th,
		#ledger_table_print td {
			border: 1px solid #999 !important;
			padding: 2px 3px !important;
			vertical-align: top;
			line-height: 1.15;
			white-space: normal !important;
			word-break: break-word;
			overflow-wrap: break-word;
			box-sizing: border-box !important;
		}

		#ledger_table_print thead th {
			background: #f0f0f0 !important;
			font-size: 6.5pt;
			font-weight: bold;
			text-align: center !important;
		}

		#ledger_table_print thead th:nth-child(3) {
			text-align: left !important;
		}

		#ledger_table_print tbody td:nth-child(1),
		#ledger_table_print tbody td:nth-child(2) {
			text-align: center !important;
			font-size: 6.5pt;
		}

		#ledger_table_print tbody td:nth-child(3) {
			text-align: left !important;
			font-size: 6.5pt;
		}

		#ledger_table_print tbody td:nth-child(4),
		#ledger_table_print tbody td:nth-child(5),
		#ledger_table_print tbody td:nth-child(6),
		#ledger_table_print tfoot th {
			text-align: right !important;
			font-size: 6pt;
		}

		#ledger_table_print tbody tr:nth-child(odd) td {
			background: #f5f5f5 !important;
		}

		#ledger_table_print tfoot th {
			background: #e8e8e8 !important;
			font-weight: bold;
			border-top: 2px solid #666 !important;
		}

		#ledger_table_print tfoot th:first-child {
			text-align: right !important;
		}

		#ledger_table_print tfoot .text-success {
			color: #3c763d !important;
		}

		#ledger_table_print td:nth-child(3) small.text-muted {
			display: block;
			font-size: 5.5pt !important;
			line-height: 1.1;
			color: #555 !important;
		}
		
		div[style*="max-width"] {
			max-width: 100% !important;
			margin: 0 !important;
			padding: 0 !important;
			box-shadow: none !important;
		}
		
		/* Card and panel styling */
		.card {
			border: none !important;
			box-shadow: none !important;
			border-radius: 0 !important;
		}
		
		.card-body {
			padding: 10px 0 !important;
		}

		/* Ensure header image prints nicely */
		.report-header {
			margin-bottom: 10px !important;
		}
		.report-title {
			font-size: 14pt !important;
		}
		.report-subtitle {
			font-size: 10pt !important;
			color: #000 !important;
		}

		/* Signature block print tuning */
		.signature-block {
			border-top: 1px solid #000 !important;
			margin-top: 20px !important;
			padding-top: 8px !important;
		}
		.sig-label {
			margin-bottom: 35px !important;
			color: #000 !important;
		}
		.sig-line {
			border-top: 1px solid #000 !important;
		}
		
		.panel {
			border: 1px solid #000 !important;
			box-shadow: none !important;
			margin-bottom: 15px !important;
			page-break-inside: avoid;
		}
		
		.panel-heading {
			background: #f5f5f5 !important;
			border-bottom: 2px solid #000 !important;
			padding: 8px 12px !important;
			font-weight: bold;
			font-size: 12pt;
		}
		
		.panel-body {
			padding: 12px !important;
		}
		
		/* Table styling */
		.table {
			width: 100% !important;
			border-collapse: collapse !important;
			font-size: 9pt;
			margin-bottom: 10px !important;
		}
		
		.table thead {
			display: table-header-group;
		}
		
		.table tbody {
			display: table-row-group;
		}
		
		.table tfoot {
			display: table-footer-group;
		}
		
		.table th,
		.table td {
			border: 1px solid #000 !important;
			padding: 6px 8px !important;
			text-align: left;
			vertical-align: top;
		}
		
		.table th {
			background: #f0f0f0 !important;
			font-weight: bold;
			text-align: center;
			font-size: 9pt;
		}
		
		.table-bordered {
			border: 2px solid #000 !important;
		}
		
		.table-bordered th,
		.table-bordered td {
			border: 1px solid #000 !important;
		}
		
		.table-striped tbody tr:nth-child(odd) {
			background: #f9f9f9 !important;
		}
		
		/* Customer info tables */
		.table-bordered td {
			font-size: 10pt;
			padding: 6px 10px !important;
		}
		
		/* Text styling */
		strong {
			font-weight: bold;
		}
		
		.text-danger {
			color: #000 !important;
			font-weight: bold;
		}
		
		.text-success {
			color: #000 !important;
		}
		
		.text-muted {
			color: #666 !important;
		}
		
		/* Page breaks */
		.page-break-before {
			page-break-before: always;
		}
		
		.page-break-after {
			page-break-after: always;
		}
		
		.page-break-inside-avoid {
			page-break-inside: avoid;
		}
		
		/* Row styling */
		.row {
			margin: 0 !important;
		}
		
		[class*="col-"] {
			padding: 0 10px !important;
		}
		
		/* Remove shadows and effects */
		* {
			box-shadow: none !important;
			text-shadow: none !important;
		}
		
		/* Ensure colors print */
		-webkit-print-color-adjust: exact !important;
		print-color-adjust: exact !important;
		color-adjust: exact !important;
		
		/* Header for each page */
		thead {
			display: table-header-group;
		}
		
		/* Footer totals */
		tfoot th {
			background: #e0e0e0 !important;
			font-weight: bold;
			border-top: 2px solid #000 !important;
		}
		
		/* Small text adjustments */
		small {
			font-size: 8pt;
		}
		
		/* Ensure proper spacing */
		.margin-bottom-20 {
			margin-bottom: 15px !important;
		}
	}
	
	/* Mobile Responsive Styles */
	@media (max-width: 991px) {
		.statement-container {
			padding: 10px !important;
		}
		
		.card-body {
			padding: 15px !important;
		}
		
		.panel-body {
			padding: 12px !important;
		}
		
		.table-responsive {
			overflow-x: auto;
			-webkit-overflow-scrolling: touch;
			-ms-overflow-style: -ms-autohiding-scrollbar;
		}
		
		#ledger_table {
			min-width: 700px !important;
		}
	}
	
	@media (max-width: 768px) {
		.statement-container {
			padding: 8px !important;
		}
		
		.action-buttons .col-xs-12 {
			padding: 0 !important;
		}
		
		.btn-group-mobile {
			display: flex;
			flex-direction: column;
			gap: 8px;
		}
		
		.btn-block-mobile {
			width: 100% !important;
			margin-bottom: 0 !important;
			padding: 12px 16px !important;
			font-size: 14px !important;
			min-height: 44px; /* Better touch target */
		}
		
		.card-body {
			padding: 12px !important;
		}
		
		.panel {
			margin-bottom: 15px !important;
		}
		
		.panel-heading {
			padding: 10px 12px !important;
			font-size: 14px !important;
		}
		
		.panel-body {
			padding: 10px !important;
		}
		
		/* Customer info tables stack on mobile */
		.col-md-6 {
			width: 100% !important;
			margin-bottom: 15px !important;
			padding-left: 0 !important;
			padding-right: 0 !important;
		}

		.sig-row {
			flex-direction: column;
		}
		.sig-item {
			min-width: 100%;
		}
		
		.table-bordered {
			font-size: 12px;
		}
		
		.table-bordered td {
			padding: 8px 10px !important;
			font-size: 12px !important;
			word-break: break-word;
		}
		
		.table-responsive {
			overflow-x: auto;
			-webkit-overflow-scrolling: touch;
			border: none;
		}
		
		.table {
			font-size: 11px;
			margin-bottom: 0;
		}
		
		.table th,
		.table td {
			padding: 8px 6px !important;
			white-space: nowrap;
		}
		
		.table th {
			font-size: 10px !important;
			font-weight: bold;
		}
		
		#ledger_table {
			min-width: 800px !important;
		}
		
		/* Description column can wrap */
		.table td:nth-child(3) {
			white-space: normal !important;
			max-width: 200px;
			word-break: break-word;
		}
		
		/* Small text adjustments */
		small {
			font-size: 10px !important;
		}
	}
	
	@media (max-width: 480px) {
		.statement-container {
			padding: 5px !important;
		}
		
		.btn-block-mobile {
			padding: 14px 16px !important;
			font-size: 13px !important;
		}
		
		.panel-heading {
			padding: 8px 10px !important;
			font-size: 13px !important;
		}
		
		.panel-body {
			padding: 8px !important;
		}
		
		.table-bordered td {
			padding: 6px 8px !important;
			font-size: 11px !important;
		}
		
		.table {
			font-size: 10px;
		}
		
		.table th,
		.table td {
			padding: 6px 4px !important;
		}
		
		.table th {
			font-size: 9px !important;
		}
		
		#ledger_table {
			min-width: 700px !important;
		}
		
		/* Description column adjustments for very small screens */
		.table td:nth-child(3) {
			max-width: 150px;
			font-size: 9px !important;
		}
		
		/* Hide less important columns on very small screens */
		.table th:nth-child(2),
		.table td:nth-child(2) {
			display: none; /* Hide Ref No on very small screens */
		}
		
		small {
			font-size: 9px !important;
		}
		
		.text-right {
			text-align: right !important; /* Keep right alignment for numbers */
		}
	}
	
	/* Tablet styles */
	@media (min-width: 481px) and (max-width: 768px) {
		.btn-group-mobile {
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
			gap: 8px;
		}
		
		.btn-block-mobile {
			flex: 1 1 auto;
			min-width: 120px;
		}
	}
	
	/* Touch-friendly improvements */
	@media (hover: none) and (pointer: coarse) {
		.btn {
			min-height: 44px;
			min-width: 44px;
		}
		
		.table th,
		.table td {
			padding: 10px 8px !important;
		}
	}
</style>

</body>

</html>

