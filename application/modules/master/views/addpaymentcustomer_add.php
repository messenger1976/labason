<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/select2/select2.bundle.css">
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<?php
	$income1 = $this->comm_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->comm_model->get_income_monthlycustomer();
	extract($income2);
	$intotal = (isset($total1) ? (float)$total1 : 0) + (isset($total2) ? (float)$total2 : 0);

	$expense1 = $this->my_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->my_model->get_outcome_payroll();
	extract($expense2);
	$extotal = (isset($extotal1) ? (float)$extotal1 : 0) + (isset($extotal2) ? (float)$extotal2 : 0);

	$total_customer = $this->my_model->total_customer();
	extract($total_customer);
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addpaymentcustomer/">Meter Customer Bills</a></li>
		<li class="breadcrumb-item active">Add</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-wallet"></i>
			Manage <span class="fw-300">Customer Payment</span>
		</h1>
		<div class="subheader-block d-lg-flex align-items-center">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>INCOME</small>
				</span>
				<span class="fw-500 fs-xl d-block color-primary-500">
					₱ <?php echo number_format($intotal, 2); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#886ab5" sparkHeight="32px" sparkBarWidth="5px" values="3,4,3,6,7,3,3,6,2,6,4"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>EXPENSE</small>
				</span>
				<span class="fw-500 fs-xl d-block color-danger-500">
					₱ <?php echo number_format($extotal, 2); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#fe6bb0" sparkHeight="32px" sparkBarWidth="5px" values="1,4,3,6,5,3,9,6,5,9,7"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>TOTAL CUSTOMER</small>
				</span>
				<span class="fw-500 fs-xl d-block color-success-500">
					<?php echo (int) $count_id; ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#1dc9b7" sparkHeight="32px" sparkBarWidth="5px" values="2,5,3,7,4,6,3,8,5,4,6"></span>
		</div>
	</div>

	<?php if (!empty($msg)) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<?php echo $msg; ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-payment-add" class="panel">
				<div class="panel-hdr">
					<h2>
						Meter Customer Bills <span class="fw-300"><i>Add Payment</i></span>
					</h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<div class="row mb-3 align-items-end">
							<div class="col-md-7 col-lg-8 col-xl-9">
								<label class="form-label" for="search_box_id">Search Customer</label>
								<select class="form-control" id="search_box_id" name="search_box_id" data-placeholder="Type to search customer ID or name...">
									<option value=""></option>
									<?php
									if (!empty($record)) {
										foreach ($record as $value) {
											$cid = isset($value['customer_id']) ? $value['customer_id'] : '';
											$label = $cid . ' ==> ' . (isset($value['last_name']) ? $value['last_name'] : '') . ', ' . (isset($value['first_name']) ? $value['first_name'] : '') . ' ' . (isset($value['middle_name']) ? $value['middle_name'] : '');
									?>
									<option value="<?php echo htmlspecialchars($cid, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
									<?php
										}
									}
									?>
								</select>
								<?php echo form_error('search_box_id'); ?>
							</div>
							<div class="col-md-5 col-lg-4 col-xl-3">
								<label class="form-label d-none d-md-block">&nbsp;</label>
								<div class="d-flex flex-wrap align-items-center">
									<button type="button" id="btn_search_box" name="btn_search_box" class="btn btn-primary waves-effect waves-themed mr-2 mb-1">
										<i class="fal fa-search mr-1"></i> Search
									</button>
									<a href="<?php echo ADMIN_URL; ?>addpaymentcustomer" id="btn_search_cancel" class="btn btn-secondary waves-effect waves-themed mb-1">
										<i class="fal fa-arrow-left mr-1"></i> Back
									</a>
								</div>
							</div>
						</div>
													<form class="form-horizontal" role="form" name="myform" id="myform" method="POST" action="" enctype="multipart/form-data">

														<div id="names">
														</div>	
														<div class="col-12" id="meterincomeDiv" style="margin-top: 13px; margin-bottom:20px;"></div> 

														<input type="hidden" name="customer_id" id="customer_id" value="">
														<input type="hidden" name="fullname" id="fullname" value="">
														<input type="hidden" name="status_id" id="status_id" value="<?php echo $this->input->post('status_id'); ?>">
														<input type="hidden" name="refno" id="refno" value="">
														<input type="hidden" name="leaking_id" id="leaking_id" value="">
														<input type="hidden" name="due_date" id="due_date" value="">
														<input type="hidden" name="special_priviledge" id="special_priviledge" value="">
														<input type="hidden" name="base_amount" id="base_amount" value="">

														<div style="clear:both"></div>
														
										<div class="modal fade" id="myModalPay" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
												<div class="modal-content border-0 shadow-3">
													<div class="modal-header bg-primary-600 bg-primary-gradient">
														<h5 class="modal-title text-white" id="myModalLabel">
															<i class="fal fa-cash-register mr-2"></i>Cash Payment Module
														</h5>
														<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true"><i class="fal fa-times"></i></span>
														</button>
													</div>
													<div class="modal-body p-4">
														<div id="hideclass" style="display:none;">
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="form-label" for="or_num">OR/SI # <span class="text-danger">*</span></label>
																		<input type="text" class="form-control form-control-lg text-input" id="or_num" name="or_num" value="<?php echo $this->input->post('or_num'); ?>" required autocomplete="off" title="Editable. Must be unique for your teller; validated when you change the value and again on save."/>
																		<small class="form-text text-muted">Suggested next number loads automatically; duplicates for your login are not allowed.</small>
																		<small id="or_num_feedback" class="form-text text-danger"></small>
																		<?php echo form_error('or_num'); ?>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="form-label" for="transdate">Transaction Date <span class="text-danger">*</span></label>
																		<input type="text" class="form-control form-control-lg" id="transdate" name="transdate" value="<?php echo $this->input->post('transdate')!=''?$this->input->post('transdate'):Date('d-m-Y'); ?>" required/>
																		<?php echo form_error('transdate'); ?>
																	</div>
																</div>
															</div>

															<div id="total_setting_1">
																<div class="row mb-3">
																	<div class="col-md-12">
																		<div class="p-3 rounded border bg-faded">
																			<span class="d-block text-muted fs-sm mb-1">Current Bill Amount</span>
																			<div class="test_deep h2 mb-0 color-primary-500 fw-500">&#8369;&nbsp;<span id="deepmala">0</span></div>
																			<input type="hidden" class="form-control" id="paid_total_amount" name="paid_total_amount" value="<?php echo $this->input->post('paid_total_amount'); ?>" readonly="readonly"/>
																			<input type="hidden" class="form-control" id="time_format" name="time_format" value="WTME-<?php echo strtotime("now"); ?>" readonly="readonly"/>
																		</div>
																	</div>
																</div>
																<div style="display:none;">
																	<input type="text" class="form-control" id="current_reading" name="current_reading" value="<?php echo $this->input->post('current_reading'); ?>" readonly="readonly"/>
																	<input type="text" class="form-control" id="oldmeter" name="oldmeter" value="<?php echo $this->input->post('oldmeter'); ?>" readonly="readonly"/>
																	<input type="text" class="form-control" id="month_name" name="month_name" value="" readonly="readonly"/>
																	<input type="hidden" class="form-control" id="month" name="month" value="<?php echo $this->input->post('month'); ?>" readonly="readonly"/>
																	<input type="text" class="form-control" id="year" name="year" value="<?php echo $this->input->post('year'); ?>" readonly="readonly"/>
																	<input type="hidden" class="form-control" id="balence" name="balence" value="<?php echo $this->input->post('balence'); ?>" readonly="readonly"/>
																	<input type="hidden" class="form-control" id="final_total_amount" name="final_total_amount" value="<?php echo $this->input->post('final_total_amount'); ?>" readonly="readonly"/>
																	<input type="text" class="form-control" id="aftermeter" name="aftermeter" value="<?php echo $this->input->post('aftermeter'); ?>"/>
																	<input type="text" class="form-control" id="total_amount" name="total_amount" value="<?php echo $this->input->post('total_amount'); ?>" readonly="readonly"/>
																</div>
															</div>

															<div id="total_setting_2">
																<div class="row mb-3">
																	<div class="col-md-12">
																		<div class="p-3 rounded border bg-faded">
																			<span class="d-block text-muted fs-sm mb-1">Total Bill Amount</span>
																			<div class="test_deep h2 mb-0 color-primary-500 fw-500">&#8369;&nbsp;<span id="deepmala_total">0</span></div>
																			<input type="hidden" class="form-control" id="total_total_amount" name="total_total_amount" value="<?php echo $this->input->post('total_total_amount'); ?>" readonly="readonly"/>
																		</div>
																	</div>
																</div>
															</div>

															<div style="display:none;">
																<select name="ledger_id" id="ledger_id" class="form-control" required>
																	<?php foreach($ledger as $key => $value){?>
																	<option value="<?php echo $value['id'];?>"><?php echo $value['ledgerName'];?></option>
																	<?php } ?>
																</select>
																<select class="form-control" id="currency" name="currency" required>
																	<option value="PHP" selected>PHP</option>
																</select>
															</div>

															<div class="row" id="leaking_balance_div" style="display: none;">
																<div class="col-md-12">
																	<div class="form-group">
																		<label class="form-label" for="leaking_balance">Add: Balance</label>
																		<input type="text" class="form-control" id="leaking_balance" name="leaking_balance" value="0.00"/>
																		<input type="hidden" id="leaking_balance_total" name="leaking_balance_total" value="0"/>
																		<?php echo form_error('leaking_balance_total'); ?>
																	</div>
																</div>
															</div>

															<div class="row" id="leaking_discount_div">
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="form-label" for="leaking_percent">Less: Leaking Disc %</label>
																		<div class="input-group">
																			<input type="text" step="1" min="0" max="100" class="form-control text-input" id="leaking_percent" name="leaking_percent" value="0" readonly/>
																			<div class="input-group-append"><span class="input-group-text">%</span></div>
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="form-label" for="leaking_amount">Leaking Disc Amount</label>
																		<input type="text" class="form-control" id="leaking_amount" name="leaking_amount" value="0.00" readonly/>
																		<?php echo form_error('leaking_amount'); ?>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="form-label" for="vat_percent">Less: VAT %</label>
																		<div class="input-group">
																			<input type="text" step="1" min="0" max="100" class="form-control text-input" id="vat_percent" name="vat_percent" value="0"/>
																			<div class="input-group-append"><span class="input-group-text">%</span></div>
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="form-label" for="vat_amount">VAT Amount</label>
																		<input type="text" class="form-control" id="vat_amount" name="vat_amount" value="0.00" readonly/>
																		<input type="hidden" id="vat_base_amount" name="vat_base_amount" value="0"/>
																		<?php echo form_error('vat_amount'); ?>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="form-label" for="grand_total">Grand Total</label>
																		<input type="text" class="form-control form-control-lg fw-500" id="grand_total" name="grand_total" value="<?php echo $this->input->post('grand_total'); ?>" readonly/>
																		<?php echo form_error('grand_total'); ?>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="form-label" for="pay_amount">Tendered Amount <span class="text-danger">*</span></label>
																		<input type="text" class="form-control form-control-lg text-input" id="pay_amount" name="pay_amount" value="0.00" required/>
																		<?php echo form_error('pay_amount'); ?>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-6">
																	<div class="form-group mb-0">
																		<label class="form-label" for="change_amount">Change Amount</label>
																		<input type="text" class="form-control form-control-lg" id="change_amount" name="change_amount" value="0.00" readonly/>
																	</div>
																</div>
															</div>

															<div id="payment-save-progress" class="mt-4 d-none">
																<div class="d-flex justify-content-between fs-xs text-muted mb-1">
																	<span id="payment-save-progress-label">Saving payment…</span>
																	<span id="payment-save-progress-pct">0%</span>
																</div>
																<div class="progress progress-sm mb-0">
																	<div id="payment-save-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary-500" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
																</div>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<div class="pay_setting_1">
															<button type="button" class="btn btn-secondary waves-effect waves-themed btn_cancel_pay">Cancel</button>
															<button type="submit" class="btn btn-primary waves-effect waves-themed" name="add" id="add" value="Add">
																<i class="fal fa-check mr-1"></i> Add
															</button>
														</div>
														<div id="total_settin_pay">
															<button type="button" class="btn btn-secondary waves-effect waves-themed btn_cancel_pay">Cancel</button>
															<button type="submit" class="btn btn-primary waves-effect waves-themed" id="total_add" name="total_add" value="Add">
																<i class="fal fa-check mr-1"></i> Add
															</button>
														</div>
													</div>
												</div>
											</div>
										</div>	    

														
														</form>

					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<!-- Loading Modal Overlay -->
<div id="datatable-loading-modal" class="dt-loading-modal" style="display: none;" aria-live="polite" aria-busy="true">
	<div class="dt-loading-backdrop"></div>
	<div class="dt-loading-card panel shadow-3">
		<div class="panel-hdr bg-primary-600 bg-primary-gradient">
			<h2 class="text-white">Loading <span class="fw-300">Customer Bills</span></h2>
		</div>
		<div class="panel-container show">
			<div class="panel-content text-center py-4 px-4">
				<div class="mb-3">
					<div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
						<span class="sr-only">Loading...</span>
					</div>
				</div>
				<h5 class="mb-1 fw-500" id="dt-loading-title">Searching customer</h5>
				<p class="text-muted mb-3 fs-sm" id="dt-loading-subtitle">Please wait while we fetch billing rows…</p>
				<div class="progress progress-lg mb-2">
					<div id="dt-loading-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary-500" role="progressbar" style="width: 8%;" aria-valuenow="8" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex justify-content-between fs-xs text-muted">
					<span>Event progress</span>
					<span id="dt-loading-percent">8%</span>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include('footer.php'); ?>
</body>
</html>
<style>
	.dt-loading-modal { position: fixed; inset: 0; z-index: 2000; display: none; align-items: center; justify-content: center; }
	.dt-loading-modal.is-visible { display: flex !important; }
	.dt-loading-backdrop { position: absolute; inset: 0; background: rgba(33, 37, 41, 0.45); backdrop-filter: blur(3px); }
	.dt-loading-card { position: relative; z-index: 1; width: min(420px, calc(100vw - 2rem)); margin: 0; border: 0; overflow: hidden; }
	.dt-loading-card .panel-hdr { border-bottom: 0; }
	.dt-loading-card .progress { height: 1rem; border-radius: 999px; background: rgba(136, 106, 181, 0.15); overflow: hidden; }
	.dt-loading-card .progress-bar { transition: width 0.25s ease; border-radius: 999px; }
	#panel-payment-add { position: relative; }
	#panel-payment-add.panel-loading::before {
		content: ''; position: absolute; top: 0; left: 0; height: 3px; width: 100%; z-index: 5;
		background: linear-gradient(90deg, transparent, var(--theme-primary, #886ab5), transparent);
		background-size: 40% 100%; animation: dt-panel-shimmer 1.1s linear infinite;
	}
	@keyframes dt-panel-shimmer { 0% { background-position: -40% 0; } 100% { background-position: 140% 0; } }
</style>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/formplugins/select2/select2.bundle.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/datagrid/datatables/datatables.bundle.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

<script type="text/javascript">
		
		function customer_type_values(){
			$("#showcustomers").hide();			
			if($("#customer_type").val()=='monthlycustomer'){
				$("#showcustomers").show();
			}
			else if($("#customer_type").val()=='metercustomer'){
				$("#showcustomers").hide();
			}
		}
		</script>
<script>
	var curDate = '<?php echo date('d-m-Y') ?>';
	var billingTable = null;
	var searchProgressTimer = null;
	var searchProgressValue = 8;

	function setSearchProgress(pct) {
		searchProgressValue = Math.max(0, Math.min(100, pct));
		$('#dt-loading-progress-bar').css('width', searchProgressValue + '%').attr('aria-valuenow', Math.round(searchProgressValue));
		$('#dt-loading-percent').text(Math.round(searchProgressValue) + '%');
	}

	function startSearchProgress() {
		clearInterval(searchProgressTimer);
		setSearchProgress(8);
		$('#dt-loading-title').text('Searching customer');
		$('#dt-loading-subtitle').text('Please wait while we fetch billing rows…');
		searchProgressTimer = setInterval(function() {
			if (searchProgressValue < 90) {
				setSearchProgress(searchProgressValue + Math.max(0.6, (90 - searchProgressValue) * 0.08));
			}
		}, 180);
	}

	function showSearchLoader() {
		$('#datatable-loading-modal').addClass('is-visible').show();
		$('#panel-payment-add').addClass('panel-loading');
		startSearchProgress();
	}

	function hideSearchLoader() {
		clearInterval(searchProgressTimer);
		setSearchProgress(100);
		$('#dt-loading-title').text('Almost done');
		$('#dt-loading-subtitle').text('Rendering billing rows…');
		setTimeout(function() {
			$('#datatable-loading-modal').removeClass('is-visible').fadeOut(180);
			$('#panel-payment-add').removeClass('panel-loading');
			setTimeout(function() { setSearchProgress(8); }, 250);
		}, 220);
	}

	function destroyBillingTable() {
		if ($.fn.DataTable && $.fn.DataTable.isDataTable('#dt_billing_rows')) {
			$('#dt_billing_rows').DataTable().clear().destroy();
		}
		billingTable = null;
	}

	function initBillingDataTable() {
		if (!$('#dt_billing_rows').length || !$('#dt_billing_rows tbody tr').length) {
			return;
		}
		destroyBillingTable();
		billingTable = $('#dt_billing_rows').DataTable({
			responsive: true,
			pageLength: 10,
			lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
			order: [[1, 'asc']],
			columnDefs: [
				{ orderable: false, searchable: false, targets: [0, 16] }
			],
			dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'<'mr-2'l>f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			language: {
				search: '',
				searchPlaceholder: 'Search billing rows...',
				lengthMenu: '_MENU_',
				info: 'Showing _START_ to _END_ of _TOTAL_ rows',
				infoEmpty: 'No billing rows found',
				zeroRecords: 'No matching billing rows',
				paginate: {
					first: '<i class="fal fa-chevron-double-left"></i>',
					last: '<i class="fal fa-chevron-double-right"></i>',
					next: '<i class="fal fa-chevron-right"></i>',
					previous: '<i class="fal fa-chevron-left"></i>'
				}
			},
			buttons: [
				{ extend: 'copyHtml5', text: '<i class="fal fa-copy mr-1"></i> Copy', className: 'btn-primary btn-sm mr-1', exportOptions: { columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15], format: { body: billingExportCell } } },
				{ extend: 'excelHtml5', text: '<i class="fal fa-file-excel mr-1"></i> Excel', className: 'btn-primary btn-sm mr-1', exportOptions: { columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15], format: { body: billingExportCell } } },
				{ extend: 'csvHtml5', text: '<i class="fal fa-file-csv mr-1"></i> CSV', className: 'btn-primary btn-sm mr-1', exportOptions: { columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15], format: { body: billingExportCell } } },
				{ extend: 'pdfHtml5', text: '<i class="fal fa-file-pdf mr-1"></i> PDF', className: 'btn-primary btn-sm mr-1', orientation: 'landscape', pageSize: 'A4', exportOptions: { columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15], format: { body: billingExportCell } } },
				{ extend: 'print', text: '<i class="fal fa-print mr-1"></i> Print', className: 'btn-primary btn-sm mr-1', exportOptions: { columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15], format: { body: billingExportCell } } }
			]
		});
	}

	function billingExportCell(data, row, column, node) {
		var text = $('<div>').html(data).text();
		return $.trim(text.replace(/\s+/g, ' '));
	}

$(document).ready(function(){
	$('#search_box_id').select2({
		width: '100%',
		placeholder: $('#search_box_id').data('placeholder') || 'Type to search customer ID or name...',
		allowClear: true
	});
	$(".hideclass").hide();
	$(".pay_setting_1").hide();
	$("#total_setting_1").hide();
	$("#total_setting_2").hide();
	$("#total_settin_pay").hide();

	$('.btn_cancel_pay').on('click',function(evt){
		evt.preventDefault();
		hidePaymentSaveProgress();
		$('#myModalPay').modal('hide');
	});

	$('#myModalPay').on('hidden.bs.modal', function() {
		$(this).removeData('skipTransdatePenaltyRecalc');
		hidePaymentSaveProgress();
	});
	$('#myModalPay').on('show.bs.modal', function() {
		hidePaymentSaveProgress();
	});

	$(document).on('change', '#dt_billing_rows .my_check', function () {
		var id = $(this).attr('id');
		var presentVal = parseFloat($('#prsentamount_' + id).val()) || 0;
		var val = parseFloat($('#checkbox_cal').val()) || 0;
		var billVal = parseFloat($('#unit_price_' + id).val()) || 0;
		var bill_total = parseFloat($('#checkbox_cal_bill').val()) || 0;
		if ($(this).is(':checked')) {
			val += presentVal;
			bill_total += billVal;
		} else {
			val -= presentVal;
			bill_total -= billVal;
		}
		$('#checkbox_cal').val(val.toFixed(2));
		$('#checkbox_cal_bill').val(bill_total.toFixed(2));
	});
});

function setPaymentProgress(pct) {
	pct = Math.max(0, Math.min(100, pct));
	$('#payment-save-progress-bar').css('width', pct + '%').attr('aria-valuenow', Math.round(pct));
	$('#payment-save-progress-pct').text(Math.round(pct) + '%');
	$('#payment-save-progress-label').text(pct >= 100 ? 'Payment saved — redirecting…' : 'Saving payment…');
}

function showPaymentSaveProgress(submitName) {
	$('#payment-save-progress').removeClass('d-none');
	// Preserve submit name/value — disabled buttons are not posted
	if (submitName) {
		var $flag = $('#payment_submit_flag');
		if (!$flag.length) {
			$flag = $('<input type="hidden" id="payment_submit_flag">').appendTo('#myform');
		}
		$flag.attr('name', submitName).val('Add');
	}
	clearInterval(window._payProgressTimer);
	var pct = 12;
	setPaymentProgress(pct);
	window._payProgressTimer = setInterval(function() {
		if (pct < 92) {
			pct += Math.max(0.8, (92 - pct) * 0.07);
			setPaymentProgress(pct);
		}
	}, 180);
	// Disable after submit has started so the POST still includes the button value
	setTimeout(function() {
		$('#add, #total_add').prop('disabled', true).addClass('disabled');
		$('.btn_cancel_pay').prop('disabled', true).addClass('disabled');
	}, 30);
}

function hidePaymentSaveProgress() {
	clearInterval(window._payProgressTimer);
	window._payProgressTimer = null;
	$('#payment-save-progress').addClass('d-none');
	setPaymentProgress(0);
	$('#payment_submit_flag').remove();
	$('#add, #total_add').prop('disabled', false).removeClass('disabled');
	$('.btn_cancel_pay').prop('disabled', false).removeClass('disabled');
}
$('#btn_search_box').on('click', function(evt) {
	evt.preventDefault();
	
	let search_text = $("#search_box_id").val();
	if (!search_text) {
		alert('Please select a customer');
		return;
	}
	const search_text_result = String(search_text).split("==>");
	var id = $.trim(search_text_result[0]);
	$('#customer_id').val(id);
	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addpaymentcustomer/get_custmer_name/',
		data: {id: id},
		beforeSend: function() {
  		},
		success: function(data) {
			$("#names").html(data);
			var fullname = document.querySelector('#first_name').value;
			$('#fullname').val(fullname);
		},
		complete: function() {
		}
	});

	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addpaymentcustomer/get_custmer_all_data/',
		data: {id: id},
		beforeSend: function() {
			showSearchLoader();
  		},
		success: function(data) {
			destroyBillingTable();
			$("#meterincomeDiv").html(data);
			initBillingDataTable();
		},
		complete: function() {
			hideSearchLoader();
		}
	});
	$('#or_num').val('');
	$('#deepmala').text('0.00');
	$('#deepmala_total').text('0.00');
	$('#hideclass').hide();
	
	
});	



$(document).on('click','.pay_button',function(e){
	// Single-row Unpaid: bill amount already includes server-side penalty; do not re-derive from transaction date
	$('#myModalPay').data('skipTransdatePenaltyRecalc', true);
	$('#myModalPay').modal('show');
	$('#hideclass').show();
	$(".pay_setting_1").show();
	$("#total_setting_1").show();
	$("#total_setting_2").hide();
	$("#total_settin_pay").hide();
	
	var buttonid = $(this).attr('id');
	var paybtnid = $(this).data('pay-val-id');
	var amount = $('#prsentamount_'+paybtnid).val();
	var bill_amount = $('#unit_price_'+paybtnid).val();
	var reading = $('#reading_'+paybtnid).val();
	var monthid = $('#monthid_'+paybtnid).val();
	var month = $('#month_'+paybtnid).val();
	var year = $('#year_'+paybtnid).val();
	var status = $('#status_'+paybtnid).val();
	var sc_discount = $('#sc_discount_'+paybtnid).val();
	var refno = $('#refno_'+paybtnid).val();

	var balanace_name = $('#balanceid_'+paybtnid).val();
	var pay_roll_id = $('#hid_payamount_roll').val();
	var year = $('#year_'+paybtnid).val();
	var customer = $('#customer_id').val();
	var customer_id = $('#cust_id').val();
	var lastamount = $('#prsentreadingamount_'+paybtnid).val();
	var lastreading = $('#previousreading_'+paybtnid).val();
	var due_date = $('#due_date_'+paybtnid).val();
	var special_priviledge = $('#special_priviledge_'+paybtnid).val();
	var base_amount = $('#base_amount_'+paybtnid).val();
	
	var new_total = parseInt(balanace_name)+parseInt(lastamount);
	
	// Store due date and special privilege for penalty calculation
	$('#due_date').val(due_date);
	$('#special_priviledge').val(special_priviledge);
	$('#base_amount').val(base_amount);
	
	$('#deepmala').text(amount);
	$("#paid_total_amount").val(amount);
	$('#total_total_amount').val(0);
	$("#grand_total").val(amount);
	$('#vat_base_amount').val(bill_amount==''?0:bill_amount);
	
	$("#current_reading").val(reading);
	$('#month_name').val(month);
	$('#month').val(monthid);
	$('#year').val(year);
	
	$('#customer_id').val(customer_id);
	$('#status_id').val(status);
	$('#oldmeter').val(lastreading);
	$('#balence').val(balanace_name);
	
	$('#vat_percent').val('');
	$('#vat_amount').val('0.00');
	$('#vat_base_amount').val(bill_amount==''?0:bill_amount);
	$('#leaking_percent').val('');
	$('#leaking_amount').val('0.00');
	$('#pay_amount').val('0.00');
	$('#change_amount').val('0.00');
	$('#leaking_id').val('');
	
	// Set transaction date to current date
	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
	var yyyy = today.getFullYear();
	var currentDate = dd + '-' + mm + '-' + yyyy;
	$('#transdate').val(currentDate);

	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addpaymentcustomer/get_or_number/',
		success: function(data) {
			$('#or_num').val($.trim(data));
			runOrNumberRemoteCheck(true);
		}
	});

	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addpaymentcustomer/get_leaking_balance/',
		data: {customer_id: customer_id},
		success: function(data) {
			var resultdata = [];
			try {
				resultdata = (typeof data === 'string') ? JSON.parse(data) : data;
			} catch (e) {
				resultdata = [];
			}
			if (!$.isArray(resultdata) || !resultdata.length || !resultdata[0]) {
				$("#leaking_balance_div").hide();
				$("#leaking_discount_div").show();
				$('#leaking_id').val('');
				$('#leaking_balance').val('0');
				$('#leaking_balance_total').val('0');
				return;
			}
			var leaking_balance = parseFloat(resultdata[0]['leaking_balance']) || 0;
			$('#leaking_id').val(resultdata[0]['leaking_id'] || '');
			if(leaking_balance>0){
				$("#leaking_balance_div").show();
				$("#leaking_discount_div").hide();
				
				$('#leaking_balance').val(leaking_balance);
				$('#leaking_balance_total').val(leaking_balance);
				amount = parseFloat(amount)+parseFloat(leaking_balance);
				$("#grand_total").val(amount.toFixed(2));
			}else{
				$("#leaking_balance_div").hide();
				$("#leaking_discount_div").show();
				
				$('#leaking_balance').val('0');
				$('#leaking_balance_total').val('0');
			}
			
		}
	});


	if(refno!=''){
		$('#refno').val(refno);
		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL;?>addpaymentcustomer/chk_leakingentry/'+refno,
			data: {id: customer_id, month: month, year: year},
			success: function(data) {
				var resultdata = [];
				try {
					resultdata = (typeof data === 'string') ? JSON.parse(data) : data;
				} catch (e) {
					resultdata = [];
				}
				if($.isArray(resultdata) && resultdata.length && resultdata[0] && resultdata[0]['leaking_id']!==undefined){
					$('#leaking_id').val(resultdata[0]['leaking_id']);
					$('#leaking_percent').val(resultdata[0]['discount_percent']);
					$('#leaking_amount').val(resultdata[0]['discount_amount']);
					$("#grand_total").val(resultdata[0]['total_amount']);
				}
				
				//console.log(resultdata);
			}
		});
	}
	

	$('#year').val(year);
	//alert(customer);
});

/*$(#pay_amount_total).on('change', function() {
	var id = $(this).val();
	//$("#pay_amount_pay").val(id);
})	;*/


$(document).on('click','.total_pay',function(e){
	
	var total = $('#checkbox_cal').val();
	var total_bill_amount = $('#checkbox_cal_bill').val();
	
	var customer = $('#customer_id').val();
	$('#customer_id').val(customer);
	$('#deepmala_total').text(total);
	$('#total_total_amount').val(0);
	$("#paid_total_amount").val(total);
	$("#grand_total").val(total);
	$('#vat_base_amount').val(total_bill_amount==''?0:total_bill_amount);
	$("#total_setting_1").hide();
	$("#total_setting_2").show();
	$("#total_settin_pay").show();
	$('#myModalPay').data('skipTransdatePenaltyRecalc', false);
	$('#myModalPay').modal('show');
	$("#hideclass").show();
	$(".pay_setting_1").hide();

	$('#vat_percent').val('');
	$('#vat_amount').val('0.00');
	$('#vat_base_amount').val(total_bill_amount==''?0:total_bill_amount);
	$('#leaking_percent').val('');
	$('#leaking_amount').val('0.00');
	$('#pay_amount').val('0.00');
	$('#change_amount').val('0.00');
	
	// Set transaction date to current date
	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
	var yyyy = today.getFullYear();
	var currentDate = dd + '-' + mm + '-' + yyyy;
	$('#transdate').val(currentDate);

	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addpaymentcustomer/get_or_number/',
		success: function(data) {
			$('#or_num').val($.trim(data));
			runOrNumberRemoteCheck(true);
		}
	});

	//$('#year').val(year);
	//checkValues();
});

$('#aftermeter').on('blur', function() {
	
	var id = $(this).val();
	var oldmeter = $("#oldmeter").val();
	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addpaymentcustomer/get_calculation/',
		data: {id: id, oldmeter: oldmeter},
		success: function(data) {
			//alert(data);
			$("#total_amount").val(data);
			
		}
	});

});	

$('#vat_percent').on('blur', function() {
	var leaking_balance = $('#leaking_balance').val()==''?0:$('#leaking_balance').val();
	var taxpercent = $('#vat_percent').val()==''?0:$('#vat_percent').val();
	var vat_base_amount = parseFloat($('#vat_base_amount').val() || 0);
	var total_total_amount = $('#total_total_amount').val();
	var paid_total_amount = parseFloat($("#paid_total_amount").val() || 0);
	var leaking_amount = parseFloat($("#leaking_amount").val() || 0);
	var taxdeduct = 0;
	
	// Recalculate penalty if needed based on transaction date
	recalculatePenaltyIfNeeded();

	if(total_total_amount!=0){
		if(leaking_amount>0){
			total_total_amount-=leaking_amount;
		}
		if(vat_base_amount < 0){ vat_base_amount = 0; }
		taxdeduct = (vat_base_amount * taxpercent)/100;
		$('#vat_amount').val(taxdeduct.toFixed(2));
		var grand_total =  total_total_amount - taxdeduct + parseFloat(leaking_balance);
		$("#grand_total").val(grand_total.toFixed(2));

	}else{
		// Use updated paid_total_amount (which may include penalty)
		paid_total_amount = parseFloat($("#paid_total_amount").val() || 0);
		if(leaking_amount>0){
			paid_total_amount-=leaking_amount;
		}
		if(vat_base_amount < 0){ vat_base_amount = 0; }
		taxdeduct = (vat_base_amount * taxpercent)/100;
		$('#vat_amount').val(taxdeduct.toFixed(2));
		var grand_total =  paid_total_amount - taxdeduct + parseFloat(leaking_balance);
		$("#grand_total").val(grand_total.toFixed(2));
	}
	
});	

$('#leaking_percent').on('blur', function() {
	var leakingpercent = $('#leaking_percent').val();
	var total_total_amount = $('#total_total_amount').val();
	var paid_total_amount = parseFloat($("#paid_total_amount").val() || 0);
	var vat_amount = parseFloat($('#vat_amount').val() || 0);
	
	// Recalculate penalty if needed based on transaction date
	recalculatePenaltyIfNeeded();
	paid_total_amount = parseFloat($("#paid_total_amount").val() || 0);
	
	var leakingdeduct = 0;
	if(total_total_amount!=0){
		leakingdeduct = (total_total_amount * leakingpercent)/100;
		$('#leaking_amount').val(leakingdeduct.toFixed(2));
		var grand_total =  total_total_amount - leakingdeduct;
		$("#grand_total").val(grand_total.toFixed(2));

	}else{
		leakingdeduct = (paid_total_amount * leakingpercent)/100;
		$('#leaking_amount').val(leakingdeduct.toFixed(2));
		var grand_total =  paid_total_amount - leakingdeduct - vat_amount;
		$("#grand_total").val(grand_total.toFixed(2));
	}
	
});	

$('#leaking_balance').on('blur', function() {
	var leaking_balance = parseFloat($('#leaking_balance').val() || 0);
	var total_total_amount = parseFloat($('#total_total_amount').val() || 0);
	var paid_total_amount = parseFloat($("#paid_total_amount").val() || 0);
	var vat_amount = parseFloat($('#vat_amount').val() || 0);
	var leaking_amount = parseFloat($('#leaking_amount').val() || 0);
	
	// Recalculate penalty if needed based on transaction date
	recalculatePenaltyIfNeeded();
	paid_total_amount = parseFloat($("#paid_total_amount").val() || 0);

	if(total_total_amount!=0){
		var grand_total = total_total_amount + leaking_balance - vat_amount - leaking_amount;
		$("#grand_total").val(Number.isNaN(grand_total.toFixed(2))? 0 : grand_total.toFixed(2));
	}else{
		var grand_total = paid_total_amount + leaking_balance - vat_amount - leaking_amount;
		$("#grand_total").val(Number.isNaN(grand_total.toFixed(2))? 0 : grand_total.toFixed(2));
	}	

});



function parseOrSiDigits(val) {
	var d = String(val || '').replace(/\D/g, '');
	var n = parseInt(d, 10);
	return (isNaN(n) || n < 1) ? 0 : n;
}

function markOrNumValidity(ok, msg) {
	var $g = $('#or_num').closest('.form-group');
	var $fb = $('#or_num_feedback');
	$('#or_num').data('or-valid', ok ? '1' : '0');
	$fb.text(msg || '');
	$g.removeClass('has-error');
	if (!ok && msg) {
		$g.addClass('has-error');
	}
}

function runOrNumberRemoteCheck(asyncFlag) {
	var n = parseOrSiDigits($('#or_num').val());
	if (n <= 0) {
		markOrNumValidity(false, 'Enter a valid OR/SI number (digits only).');
		return $.Deferred().reject().promise();
	}
	return $.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL; ?>addpaymentcustomer/check_or_number/' + n,
		async: asyncFlag !== false
	}).done(function(data) {
		if (String(data) === '1') {
			markOrNumValidity(false, 'This OR/SI # is already used for your teller account.');
		} else {
			markOrNumValidity(true, '');
		}
	}).fail(function() {
		markOrNumValidity(false, 'Could not validate OR/SI. Try again.');
	});
}

$(document).on('blur', '#or_num', function() {
	runOrNumberRemoteCheck(true);
});

$('#pay_amount').on('blur', function() {
	var change_amount = $("#grand_total").val() - $(this).val();
	$('#change_amount').val(change_amount.toFixed(2));
	/*if($(this).val()>0){
		$("#add").prop("disabled", false);
		$("#total_add").prop("disabled", false);
	}else{
		$("#add").prop("disabled", true);
		$("#total_add").prop("disabled", true);
	}*/
});	


$('#add').on('click',function(evt){
	var pay_amount = parseFloat($('#pay_amount').val());
	var leaking_id = $('#leaking_id').val();
	var blocked = false;

	if(leaking_id!='' && pay_amount<=0){
		evt.preventDefault();
		blocked = true;
		if (typeof $.smallBox === 'function') {
			$.smallBox({
				title : "TENDER AMOUNT field required",
				content : "Tender amount should be greater then zero.",
				color : "#D30000",
				timeout: 8000,
				icon : "fa fa-exclamation-circle swing animated"
			});
		} else {
			alert('Tender amount should be greater than zero.');
		}
	}
	if(leaking_id=='' && pay_amount<=0){
		evt.preventDefault();
		blocked = true;
		if (typeof $.smallBox === 'function') {
			$.smallBox({
				title : "TENDER AMOUNT field required",
				content : "Tender amount should be greater then zero.",
				color : "#D30000",
				timeout: 8000,
				icon : "fa fa-exclamation-circle swing animated"
			});
		} else {
			alert('Tender amount should be greater than zero.');
		}
	}

	var orN = parseOrSiDigits($('#or_num').val());
	if (orN <= 0) {
		evt.preventDefault();
		blocked = true;
		markOrNumValidity(false, 'Enter a valid OR/SI number (digits only).');
		if (typeof $.smallBox === 'function') {
			$.smallBox({
				title : "OR/SI #",
				content : "Enter a valid OR/SI number.",
				color : "#D30000",
				timeout: 8000,
				icon : "fa fa-exclamation swing animated"
			});
		} else {
			alert('Enter a valid OR/SI number.');
		}
		return false;
	}
	var orDup = false;
	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL; ?>addpaymentcustomer/check_or_number/' + orN,
		async: false,
		success: function(data) { orDup = (String(data) === '1'); }
	});
	if (orDup) {
		evt.preventDefault();
		blocked = true;
		markOrNumValidity(false, 'This OR/SI # is already used for your teller account.');
		if (typeof $.smallBox === 'function') {
			$.smallBox({
				title : "OR/SI NUMBER",
				content : "This OR/SI number is already used for your teller account.",
				color : "#D30000",
				timeout: 8000,
				icon : "fa fa-exclamation swing animated"
			});
		} else {
			alert('This OR/SI number is already used for your teller account.');
		}
		return false;
	}
	markOrNumValidity(true, '');
	if (!blocked && !evt.isDefaultPrevented()) {
		showPaymentSaveProgress('add');
	}
});


$('#total_add').on('click',function(evt){
	var pay_amount = parseFloat($('#pay_amount').val());
	var blocked = false;

	if(pay_amount<=0){
		blocked = true;
		if (typeof $.smallBox === 'function') {
			$.smallBox({
				title : "TENDER AMOUNT field required",
				content : "Tender amount should be greater then zero.",
				color : "#D30000",
				timeout: 8000,
				icon : "fa fa-exclamation-circle swing animated"
			});
		} else {
			alert('Tender amount should be greater than zero.');
		}
		evt.preventDefault();
	}

	var orNb = parseOrSiDigits($('#or_num').val());
	if (orNb <= 0) {
		evt.preventDefault();
		blocked = true;
		markOrNumValidity(false, 'Enter a valid OR/SI number (digits only).');
		if (typeof $.smallBox === 'function') {
			$.smallBox({
				title : "OR/SI #",
				content : "Enter a valid OR/SI number.",
				color : "#D30000",
				timeout: 8000,
				icon : "fa fa-exclamation swing animated"
			});
		} else {
			alert('Enter a valid OR/SI number.');
		}
		return false;
	}
	var orDupB = false;
	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL; ?>addpaymentcustomer/check_or_number/' + orNb,
		async: false,
		success: function(data) { orDupB = (String(data) === '1'); }
	});
	if (orDupB) {
		evt.preventDefault();
		blocked = true;
		markOrNumValidity(false, 'This OR/SI # is already used for your teller account.');
		if (typeof $.smallBox === 'function') {
			$.smallBox({
				title : "OR/SI NUMBER",
				content : "This OR/SI number is already used for your teller account.",
				color : "#D30000",
				timeout: 8000,
				icon : "fa fa-exclamation swing animated"
			});
		} else {
			alert('This OR/SI number is already used for your teller account.');
		}
		return false;
	}
	markOrNumValidity(true, '');
	if (!blocked && !evt.isDefaultPrevented()) {
		showPaymentSaveProgress('total_add');
	}

});

$("#transdate").datepicker({
	format: 'dd-mm-yyyy',
	autoclose: true,
	todayHighlight: true,
	orientation: 'bottom auto'
});

// Function to recalculate penalty based on transaction date
function recalculatePenaltyIfNeeded() {
	if ($('#myModalPay').data('skipTransdatePenaltyRecalc')) {
		return parseFloat($("#paid_total_amount").val() || 0);
	}
	var trans_date = $('#transdate').val();
	var due_date = $('#due_date').val();
	var special_priviledge = $('#special_priviledge').val();
	var base_amount = parseFloat($('#base_amount').val()) || 0;
	
	if(trans_date && due_date && base_amount > 0) {
		// Convert dates to comparable format (YYYY-MM-DD)
		var trans_date_parts = trans_date.split('-');
		var trans_date_formatted = trans_date_parts[2] + '-' + trans_date_parts[1] + '-' + trans_date_parts[0];
		var due_date_formatted = due_date.split(' ')[0]; // Already in YYYY-MM-DD format
		
		var penalty = 0;
		var new_amount = base_amount;
		
		// Check if transaction date is greater than due date and special privilege is 0
		if(special_priviledge == 0 && trans_date_formatted > due_date_formatted) {
			// Apply 10% penalty
			penalty = (base_amount * 10) / 100;
			new_amount = base_amount + penalty;
		} else {
			// No penalty
			new_amount = base_amount;
		}
		
		// Update the amount display
		$('#deepmala').text(new_amount.toFixed(2));
		$("#paid_total_amount").val(new_amount.toFixed(2));
		
		return new_amount;
	}
	return parseFloat($("#paid_total_amount").val() || 0);
}

// Recalculate penalty when transaction date changes
$('#transdate').on('blur change', function() {
	if ($('#myModalPay').data('skipTransdatePenaltyRecalc')) {
		return;
	}
	var leaking_balance = parseFloat($('#leaking_balance').val() || 0);
	var vat_amount = parseFloat($('#vat_amount').val() || 0);
	var leaking_amount = parseFloat($('#leaking_amount').val() || 0);
	
	// Recalculate penalty
	var new_amount = recalculatePenaltyIfNeeded();
	
	// Recalculate grand total
	var grand_total = new_amount;
	
	// Apply leaking discount if applicable
	if(leaking_amount > 0) {
		grand_total = grand_total - leaking_amount;
	}
	
	// Apply VAT if applicable
	if(vat_amount > 0) {
		grand_total = grand_total - vat_amount;
	}
	
	// Add leaking balance if applicable
	if(leaking_balance > 0) {
		grand_total = grand_total + leaking_balance;
	}
	
	$("#grand_total").val(grand_total.toFixed(2));
	
	// Update change amount if pay amount is already entered
	var pay_amount = parseFloat($('#pay_amount').val() || 0);
	if(pay_amount > 0) {
		var change_amount = pay_amount - grand_total;
		$('#change_amount').val(change_amount.toFixed(2));
	}
});

	$('.text-input').on('focus', function() {
  		$(this).select();
	});

	function checkValues() {
		// Get all inputs with the name 'myArray[]'
		const inputs = document.querySelectorAll('input[name="checkbox[]"]');
		
		// Collect their values into an array
		const values = Array.from(inputs).map(input => input.value);
		
		// Log the values
		console.log(values);
		
		// Display values on the page
		alert("Array values: " + values.join(", "));
	}

	function check_or_number(ornumber){
		var returnorval=0;
		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL;?>addpaymentcustomer/check_or_number/'+ornumber,
			success: function(data) {
				returnorval=data;
				console.log('return val:'+returnorval);
				return returnorval;
			}
		});
		
		return returnorval;
	}

	function compute_all(){

	}
</script>

