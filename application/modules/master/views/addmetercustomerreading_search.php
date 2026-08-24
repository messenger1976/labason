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

	$customers = (isset($record) && is_array($record)) ? $record : array();
	$billingperiod = (isset($billingperiod) && is_array($billingperiod)) ? $billingperiod : array();
	$franchise_fee_percentage = isset($franchise_fee_percentage) ? $franchise_fee_percentage : 2.00;
	$msg = isset($msg) ? $msg : '';
	$selected = isset($member_id) ? $member_id : '';
	$sa4_loading_label = 'Meter Readings';
	$sa4_dt_entity = 'meter readings';
	$sa4_panel_id = 'panel-mcr-edit-search';
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/select2/select2.bundle.css">
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<style>
	.mcr-readonly { background-color: #fff8dc !important; }
</style>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addmetercustomerreading">Meter Reading</a></li>
		<li class="breadcrumb-item active">Edit Search</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-tachometer"></i>
			Manage <span class="fw-300">Meter Customer Reading</span>
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

	<?php if ($msg != '') { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<?php echo $msg; ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-mcr-edit-search" class="panel">
				<div class="panel-hdr">
					<h2>Meter Customer Reading <span class="fw-300"><i>Edit Search</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<div class="row align-items-end mb-3">
							<div class="col-md-8">
								<div class="form-group mb-md-0">
									<label class="form-label" for="search_box_id">Search Customer</label>
									<select class="form-control" id="search_box_id" name="search_box_id" required>
										<option value="">Type text to search...</option>
										<?php foreach ($customers as $value) {
											$cid = isset($value['customer_id']) ? $value['customer_id'] : '';
											$label = $cid.' ==> '.(isset($value['last_name']) ? $value['last_name'] : '').', '.(isset($value['first_name']) ? $value['first_name'] : '').' '.(isset($value['middle_name']) ? $value['middle_name'] : '');
										?>
										<option value="<?php echo htmlspecialchars($cid); ?>" <?php echo ($selected && $selected == $cid) ? 'selected' : ''; ?>>
											<?php echo htmlspecialchars($label); ?>
										</option>
										<?php } ?>
									</select>
									<?php echo form_error('search_box_id'); ?>
								</div>
							</div>
							<div class="col-md-4 text-md-right">
								<button type="button" class="btn btn-primary btn-sm waves-effect waves-themed mb-1" name="search" id="search">
									<i class="fal fa-search mr-1"></i> Search
								</button>
								<button type="button" class="btn btn-success btn-sm waves-effect waves-themed mb-1" name="add_billing_period" id="add_billing_period" style="display:none;">
									<i class="fal fa-plus mr-1"></i> Add
								</button>
								<input type="hidden" name="record_id" id="record_id">
								<input type="hidden" name="customer_id" id="customer_id">
								<input type="hidden" name="cust_type_id" id="cust_type_id">
								<input type="hidden" name="special_priviledge" id="special_priviledge">
								<input type="hidden" name="amount_pay" id="amount_pay">
							</div>
						</div>

						<div id="customerDiv" class="mt-3"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>

<!-- Edit Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="myModalLabel">Edit Customer Meter Reading</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_update" id="frm_update" action="" method="POST">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="billing_period">Billing Period</label>
								<input class="form-control mcr-readonly" type="text" id="billing_period" name="billing_period" readonly>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="reading_date">Reading Date</label>
								<div class="input-group">
									<input class="form-control" type="text" id="reading_date" name="reading_date" readonly>
									<div class="input-group-append">
										<span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="previous_reading">Previous Reading</label>
								<input class="form-control" type="text" id="previous_reading" name="previous_reading" value="0" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="current_reading">Current Reading <span class="text-danger">*</span></label>
								<input class="form-control" type="text" id="current_reading" name="current_reading" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="consumed">Consumed</label>
								<input class="form-control mcr-readonly" type="text" id="consumed" name="consumed" readonly>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="current_bill">Current Bill</label>
								<input class="form-control" type="text" id="current_bill" name="current_bill">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="maintenance_fee">WM Maintenance Fee</label>
								<input class="form-control" type="text" id="maintenance_fee" name="maintenance_fee">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="franchise_fee_percent">Franchise Tax %</label>
								<input class="form-control" type="text" id="franchise_fee_percent" name="franchise_fee_percent">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="franchise_fee_amount">Franchise Tax Amount</label>
								<input class="form-control mcr-readonly" type="text" id="franchise_fee_amount" name="franchise_fee_amount" readonly>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="sc_discount">SC Discount</label>
								<input class="form-control" type="text" id="sc_discount" name="sc_discount">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="arrears">Arrears</label>
								<input class="form-control" type="text" id="arrears" name="arrears">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="total_amount">Amt before due date</label>
								<input class="form-control" type="text" id="total_amount" name="total_amount">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="penalty">Amt after due date</label>
								<input class="form-control" type="text" id="penalty" name="penalty">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label">Compute Penalty</label>
								<div class="frame-wrap pt-2">
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" class="custom-control-input" id="compute_penalty_yes" name="compute_penalty" value="1" checked>
										<label class="custom-control-label" for="compute_penalty_yes">Yes</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" class="custom-control-input" id="compute_penalty_no" name="compute_penalty" value="0">
										<label class="custom-control-label" for="compute_penalty_no">No</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="customer_status">Customer Status</label>
								<input class="form-control" type="text" id="customer_status" name="customer_status">
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="btn_save" data-dismiss="modal">
					<i class="fal fa-save mr-1"></i> Update
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Add Billing Period Modal -->
<div class="modal fade" id="addBillingModal" tabindex="-1" role="dialog" aria-labelledby="addBillingModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addBillingModalLabel">Add Meter Reading</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_add_billing" id="frm_add_billing" action="" method="POST">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="billing_period_select">Billing Period <span class="text-danger">*</span></label>
								<select class="form-control" id="billing_period_select" name="billing_period" required>
									<option value="">--Select--</option>
									<?php
									$seen_bp = array();
									foreach ($billingperiod as $bp) {
										$key = $bp['bp_period_year'].'-'.sprintf('%02d', (int) $bp['bp_period_month']);
										if (isset($seen_bp[$key])) { continue; }
										$seen_bp[$key] = true;
										$label = (isset($bp['month_name']) ? $bp['month_name'] : $bp['bp_period_month']).' '.$bp['bp_period_year'];
									?>
									<option value="<?php echo htmlspecialchars($key); ?>"><?php echo htmlspecialchars($label); ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="reading_date_add">Reading Date</label>
								<div class="input-group">
									<input class="form-control" type="text" id="reading_date_add" name="reading_date" value="<?php echo date('d-m-Y'); ?>" readonly>
									<div class="input-group-append">
										<span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="previous_reading_add">Previous Reading</label>
								<input class="form-control" type="text" id="previous_reading_add" name="previous_reading" value="0">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="current_reading_add">Current Reading <span class="text-danger">*</span></label>
								<input class="form-control" type="text" id="current_reading_add" name="current_reading" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="consumed_add">Consumed</label>
								<input class="form-control mcr-readonly" type="text" id="consumed_add" name="consumed" readonly>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="current_bill_add">Current Bill</label>
								<input class="form-control" type="text" id="current_bill_add" name="current_bill">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="maintenance_fee_add">WM Maintenance Fee</label>
								<input class="form-control" type="text" id="maintenance_fee_add" name="maintenance_fee" value="0">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="franchise_fee_percent_add">Franchise Tax %</label>
								<input class="form-control" type="text" id="franchise_fee_percent_add" name="franchise_fee_percent" value="<?php echo htmlspecialchars((string) $franchise_fee_percentage); ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="franchise_fee_amount_add">Franchise Tax Amount</label>
								<input class="form-control mcr-readonly" type="text" id="franchise_fee_amount_add" name="franchise_fee_amount" readonly>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="sc_discount_add">SC Discount</label>
								<input class="form-control" type="text" id="sc_discount_add" name="sc_discount" value="0">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="arrears_add">Arrears</label>
								<input class="form-control" type="text" id="arrears_add" name="arrears" value="0">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="total_amount_add">Amt before due date</label>
								<input class="form-control" type="text" id="total_amount_add" name="total_amount">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="penalty_add">Amt after due date</label>
								<input class="form-control" type="text" id="penalty_add" name="penalty">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="customer_status_add">Customer Status</label>
								<input class="form-control" type="text" id="customer_status_add" name="customer_status" value="1">
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" id="btn_edit_modal">Edit</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="save_billing_period">
					<i class="fal fa-save mr-1"></i> Save
				</button>
			</div>
		</div>
	</div>
</div>

<?php include('footer.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/formplugins/select2/select2.bundle.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/datagrid/datatables/datatables.bundle.js"></script>
<script type="text/javascript">
(function($) {
	var progressTimer = null;
	var progressValue = 8;
	var panelSel = '#panel-mcr-edit-search';
	var entityLabel = <?php echo json_encode($sa4_dt_entity); ?>;

	function setProgress(pct) {
		progressValue = Math.max(0, Math.min(100, pct));
		$('#dt-loading-progress-bar').css('width', progressValue + '%').attr('aria-valuenow', Math.round(progressValue));
		$('#dt-loading-percent').text(Math.round(progressValue) + '%');
	}
	function startProgress() {
		clearInterval(progressTimer);
		setProgress(8);
		$('#dt-loading-title').text('Fetching ' + entityLabel);
		$('#dt-loading-subtitle').text('Please wait while we prepare the listing…');
		progressTimer = setInterval(function() {
			if (progressValue < 90) {
				setProgress(progressValue + Math.max(0.6, (90 - progressValue) * 0.08));
			}
		}, 180);
	}
	function completeProgress(done) {
		clearInterval(progressTimer);
		setProgress(100);
		$('#dt-loading-title').text('Almost done');
		$('#dt-loading-subtitle').text('Rendering listing…');
		setTimeout(done, 220);
	}
	window.sa4ShowLoader = function() {
		$('#datatable-loading-modal').addClass('is-visible').show();
		$(panelSel).addClass('panel-loading');
		$('.dataTables_wrapper').addClass('processing');
		startProgress();
	};
	window.sa4HideLoader = function() {
		completeProgress(function() {
			$('#datatable-loading-modal').removeClass('is-visible').fadeOut(180);
			$(panelSel).removeClass('panel-loading');
			$('.dataTables_wrapper').removeClass('processing');
			setTimeout(function() { setProgress(8); }, 250);
		});
	};
})(jQuery);
</script>
</body>
</html>

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
<script type="text/javascript">
var curDate = '<?php echo date('d-m-Y') ?>';
function fun_calendor(field){
	$("#"+field).focus();
}
function safeMoneyVal(sel) {
	var v = $(sel).val();
	return parseFloat(String(v == null ? '0' : v).replace(/,/g, '') || 0);
}
function amount_formatted(amount){
	const formatted = new Intl.NumberFormat('en-US', {
  		minimumFractionDigits: 2,
  		maximumFractionDigits: 2,
  		useGrouping: false,
	}).format(amount);
	return formatted;
}
$(document).ready(function(){
	if (typeof pageSetUp === 'function') { pageSetUp(); }

	function initMeterSearchResults() {
		var $table = $('#customerDiv #dt_basic');
		if (!$table.length || !$.fn.DataTable) { return; }

		if ($.fn.DataTable.isDataTable($table)) {
			$table.DataTable().destroy();
		}

		var entityLabel = <?php echo json_encode($sa4_dt_entity); ?>;
		/* Derive from thead so columnDefs never invent phantom columns (DataTables _DT_CellIndex error). */
		var colCount = $table.find('thead th').length;
		var actionCol = colCount > 0 ? (colCount - 1) : 0;
		var exportCols = [];
		for (var c = 0; c < actionCol; c++) { exportCols.push(c); }
		var btnExport = function(extend, icon, label) {
			return {
				extend: extend,
				text: '<i class="fal ' + icon + ' mr-1"></i> ' + label,
				className: 'btn-primary btn-sm mr-1',
				exportOptions: { columns: exportCols }
			};
		};

		$table.DataTable({
			responsive: true,
			stateSave: false,
			pageLength: 25,
			lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
			order: [[0, 'asc']],
			columnDefs: [
				{ orderable: false, targets: [actionCol] }
			],
			dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			language: {
				processing: '',
				search: '',
				searchPlaceholder: 'Search ' + entityLabel + '...',
				lengthMenu: '_MENU_',
				info: 'Showing _START_ to _END_ of _TOTAL_ ' + entityLabel,
				infoEmpty: 'No ' + entityLabel + ' found',
				zeroRecords: 'No matching ' + entityLabel,
				paginate: {
					first: '<i class="fal fa-chevron-double-left"></i>',
					last: '<i class="fal fa-chevron-double-right"></i>',
					next: '<i class="fal fa-chevron-right"></i>',
					previous: '<i class="fal fa-chevron-left"></i>'
				}
			},
			buttons: [
				btnExport('copyHtml5', 'fa-copy', 'Copy'),
				btnExport('excelHtml5', 'fa-file-excel', 'Excel'),
				btnExport('csvHtml5', 'fa-file-csv', 'CSV'),
				btnExport('pdfHtml5', 'fa-file-pdf', 'PDF'),
				btnExport('print', 'fa-print', 'Print'),
				{
					text: '<i class="fal fa-sync mr-1"></i> Refresh',
					className: 'btn-primary btn-sm',
					action: function() { $('#search').trigger('click'); }
				}
			],
			drawCallback: function() {
				if ($.fn.tooltip) { $('[data-toggle="tooltip"]').tooltip(); }
			}
		});
	}

	// Bind Search first so sparkline/select2 failures cannot block it
	$('#search').off('click').on('click', function(evt){
		evt.preventDefault();
		var search_text = $("#search_box_id").val();
		if (search_text == null || String(search_text).trim() === '') {
			if (typeof Swal !== 'undefined') {
				Swal.fire({icon:'warning', title:'Select customer', text: 'Please select a customer first.'});
			} else {
				alert('Please select a customer first.');
			}
			return;
		}
		var id = String(search_text).split('==>')[0].trim();
		$('#customer_id').val(id);

		if (typeof sa4ShowLoader === 'function') {
			sa4ShowLoader();
		} else if (typeof showSpinner === 'function') {
			showSpinner();
		}

		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL; ?>addmetercustomerreading/getaddcustomersmetersearch',
			data: { customer_id: id },
			success: function(op) {
				$('#customerDiv').html(op).show();
				initMeterSearchResults();
				if ($('#customerDiv').find('table').length > 0) {
					$('#add_billing_period').show();
				} else {
					$('#add_billing_period').hide();
				}
			},
			error: function(xhr) {
				$('#customerDiv').html('<div class="alert alert-danger mb-0">Search failed. Please try again.</div>').show();
				$('#add_billing_period').hide();
				if (window.console) { console.error('Meter search failed', xhr && xhr.status, xhr && xhr.responseText); }
			},
			complete: function() {
				if (typeof sa4HideLoader === 'function') {
					sa4HideLoader();
				} else if (typeof hideSpinner === 'function') {
					hideSpinner();
				}
			}
		});
	});

	try {
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
	} catch (e) {}

	try {
		if ($.fn.select2) {
			$('#search_box_id').select2({
				width: '100%',
				placeholder: 'Type text to search...',
				allowClear: true
			});
		}
	} catch (e) {}

	// Hide the Add Billing Period button and the datatable whenever the selected search item changes
	$('#search_box_id').on('change', function(){
		$('#add_billing_period').hide();
		$('#customerDiv').empty();
	});

	// Edit button from AJAX results (delegated)
	$('#customerDiv').off('click', '.btn_edit').on('click', '.btn_edit', function(evt) {
		evt.preventDefault();
		var $btn = $(this);
		$('#record_id').val($btn.data('id'));
		$('#billing_period').val($btn.data('billing_period'));
		$('#previous_reading').val($btn.data('previous_reading'));
		$('#current_reading').val($btn.data('current_reading'));
		$('#consumed').val($btn.data('consumed'));
		$('#current_bill').val($btn.data('current_bill'));
		$('#sc_discount').val($btn.data('sc_discount'));
		$('#arrears').val($btn.data('arrears'));
		$('#total_amount').val($btn.data('total_amount'));
		$('#penalty').val($btn.data('penalty'));
		$('#maintenance_fee').val($btn.data('maintenance_fee'));
		$('#franchise_fee_percent').val($btn.data('franchise_fee_percent') || '2.00');
		$('#franchise_fee_amount').val($btn.data('franchise_fee_amount') || '0.00');
		$('#reading_date').val($btn.data('reading_date'));
		$('#cust_type_id').val($btn.data('account_type'));
		$('#special_priviledge').val($btn.data('special_priviledge'));
		$('#customer_status').val($btn.data('customer_status'));
		if (String($btn.data('compute_penalty')) === '0') {
			$('input[name="compute_penalty"][value="0"]').prop('checked', true);
		} else {
			$('input[name="compute_penalty"][value="1"]').prop('checked', true);
		}
	});
	
/**
 * Reusable function to calculate franchise fee and totals
 * Date Modified: February 6, 2026
 * 
 * Purpose: Calculate franchise fee (based on % of current bill) and total amounts including maintenance fee and penalty.
 *          Franchise tax is ALWAYS based on the current bill. Senior citizen discount (if any) is applied
 *          when computing the total: total = current_bill - sc_discount + maintenance_fee + franchise_fee_amount.
 */
function recalculateFranchiseFeeAndTotals() {
	var unit_price = safeMoneyVal('#current_bill');
	var maintenance_fee = safeMoneyVal('#maintenance_fee');
	var multiprice = unit_price;
	var discount = safeMoneyVal('#sc_discount');
	
	// Get consumption value to check senior citizen discount eligibility
	var consumed = parseFloat($('#consumed').val() || 0);
	
	// Get franchise fee percentage from input field, or use default from global settings
	var franchise_fee_percentage = safeMoneyVal('#franchise_fee_percent');
	if (!franchise_fee_percentage || franchise_fee_percentage <= 0) {
		franchise_fee_percentage = <?php echo isset($franchise_fee_percentage) ? floatval($franchise_fee_percentage) : '2.00'; ?>;
		$('#franchise_fee_percent').val(franchise_fee_percentage);
	}
	
	/**
	 * Franchise Tax Calculation - Based on Current Bill
	 * Date Modified: February 6, 2026
	 * 
	 * Business Rule: Franchise tax is ALWAYS computed on the current bill amount.
	 *               Senior citizen discount (if applicable) is applied to the bill
	 *               when calculating the total, but franchise tax is based on the
	 *               current bill before any discount.
	 */
	var bill_amount_for_franchise = multiprice;  // Always use current bill for franchise tax
	var franchise_fee_amount = (bill_amount_for_franchise * franchise_fee_percentage) / 100;
	
	// Total: current bill - senior citizen discount (if any) + maintenance + franchise tax
	var total_amount = multiprice - discount;
	total_amount += parseFloat(maintenance_fee);
	total_amount += parseFloat(franchise_fee_amount);
	
	// Update franchise fee fields
	if($('#franchise_fee_percent').length) {
		$('#franchise_fee_percent').val(franchise_fee_percentage);
	}
	if($('#franchise_fee_amount').length) {
		$('#franchise_fee_amount').val(amount_formatted(franchise_fee_amount));
	}
	
	// Calculate penalty: 10% applied to (current_bill - sc_discount) only, then add maintenance + franchise
	var amount_total_penalty = 0;
	var compute_penalty = $('input[name="compute_penalty"]:checked').val() || '1';
	if($('#special_priviledge').val()==='0' && compute_penalty === '1'){
		var penalty_base = multiprice - discount;  // current_bill - sc_discount
		amount_total_penalty = (penalty_base * 10) / 100;
		amount_total_penalty = amount_total_penalty + penalty_base + maintenance_fee + franchise_fee_amount;
	}else{
		amount_total_penalty = total_amount;
	}
	
	// Update total amount and penalty fields
	if($('#total_amount').length) {
		$('#total_amount').val(amount_formatted(total_amount));
	}
	if($('#penalty').length) {
		$('#penalty').val(amount_formatted(amount_total_penalty));
	}
	if($('#amount_pay').length) {
		$('#amount_pay').val(amount_formatted(multiprice));
	}
}

$('#btn_save').on('click', function(evt){
	evt.preventDefault();
	var record_id = $('#record_id').val();
	const formData = new FormData();
	formData.append("customer_id", $('#customer_id').val());
	formData.append("previous_reading", $('#previous_reading').val());
	formData.append("current_reading", $('#current_reading').val());
	formData.append("consumed", $('#consumed').val());
	formData.append("current_bill", $('#current_bill').val());
	formData.append("sc_discount", $('#sc_discount').val());
	formData.append("arrears", $('#arrears').val());
	formData.append("total_amount", $('#total_amount').val());
	formData.append("penalty", $('#penalty').val());
	formData.append("maintenance_fee", $('#maintenance_fee').val());
	formData.append("franchise_fee_percent", $('#franchise_fee_percent').val());
	formData.append("franchise_fee_amount", $('#franchise_fee_amount').val());
	formData.append("reading_date", $('#reading_date').val());
	formData.append("customer_status", $('#customer_status').val());
	formData.append("compute_penalty", $('input[name="compute_penalty"]:checked').val() || '1');
	formData.append("edit", 'edit');

	$.ajax({
		url: '<?php echo ADMIN_URL;?>addmetercustomerreading/save_edit/'+record_id,
		type: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		success: function (response) {
			
			if (response=='success') {
				$('#search').trigger('click');
				//alert('Successfully Save');
				

			} 
		},
		error: function () {
			if (typeof Swal !== 'undefined') {
				Swal.fire({icon:'error', title:'Error', text: 'An error occurred while processing data.'});
			} else {
				alert("An error occurred while processing data.");
			}
		}
	});
});

$('#sc_discount').on('blur', function(evt){
	evt.preventDefault();
	recalculateFranchiseFeeAndTotals();
});

// Event listener for franchise_fee_percent field changes
$('#franchise_fee_percent').on('blur', function(evt){
	evt.preventDefault();
	recalculateFranchiseFeeAndTotals();
});

$('#maintenance_fee').on('blur', function(evt){
	evt.preventDefault();
	recalculateFranchiseFeeAndTotals();
});

$('input[name="compute_penalty"]').on('change', function(){
	recalculateFranchiseFeeAndTotals();
});

// Recalculate with new logic when Edit modal is shown (after row data is populated)
$(document).on('shown.bs.modal', '#myModal', function(){
	if ($('#current_bill').length && $('#frm_update').length) {
		recalculateFranchiseFeeAndTotals();
	}
});

$('#current_reading').on('blur', function() {
	var current_meter = $(this).val();
	var previous_reading = $('#previous_reading').val();
	var differences = parseFloat(current_meter) - parseFloat(previous_reading);
	$("#consumed").val(differences);
	var difer = $("#consumed").val();
	const formData = new FormData();
	formData.append("cubic_meter_reading", difer);
	formData.append("customer_id", $('#customer_id').val());

	

	$.ajax({
		url: '<?php echo ADMIN_URL;?>addmetercustomerreading/get_cubic_meter_price/',
		type: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		success: function (response) {
			const result = JSON.parse(response);
			if (result.per_unit) {
				
				$('#current_bill').val(amount_formatted(result.per_unit));
				
				// Set initial franchise fee percentage if not set
				if(!$('#franchise_fee_percent').val() || $('#franchise_fee_percent').val() == '') {
					var default_franchise_fee_percentage = <?php echo isset($franchise_fee_percentage) ? floatval($franchise_fee_percentage) : '2.00'; ?>;
					$('#franchise_fee_percent').val(default_franchise_fee_percentage);
				}
				
				/**
				 * Senior Citizen Discount Calculation
				 * Date Modified: January 29, 2026
				 * Modified By: AI Assistant
				 * 
				 * Purpose: Apply senior citizen discount (5%) only if the customer is a senior citizen 
				 *          (account_type == 3) AND the consumption (difference between current_reading 
				 *          and previous_reading) is 30 cubic meters or less.
				 * 
				 * Reason: Business rule requirement - Senior citizens cannot avail discount if their 
				 *         consumption exceeds 30 cubic meters. This prevents abuse of the senior 
				 *         citizen discount privilege for excessive water consumption.
				 * 
				 * Previous Logic: Discount was applied to all senior citizens regardless of consumption amount.
				 * New Logic: Discount is only applied when consumption <= 30 cubic meters.
				 */
				var unit_price = parseFloat($('#current_bill').val().replace(/,/g, ''));
				var multiprice = unit_price;
				var consumed = parseFloat($('#consumed').val() || 0);
				if($('#cust_type_id').val()==3 && consumed <= 30){
					var discount = (multiprice * 5)/100;
					$('#sc_discount').val(amount_formatted(discount));
				} else {
					// Clear discount if consumption exceeds 30 cubic meters or not a senior citizen
					$('#sc_discount').val(amount_formatted(0));
				}
				
				// Recalculate franchise fee and totals
				recalculateFranchiseFeeAndTotals();
				

			} else {
				$('#current_bill').val(amount_formatted(0));
				var unit_price = $('#current_bill').val();
				
				$("#amount_pay").val(amount_formatted(0));
				if (typeof Swal !== 'undefined') {
					Swal.fire({icon:'warning', title:'No Amount', text: 'No Amount per cubic meter.'});
				} else {
					alert("No Amount per cubic meter.");
				}
			}
		},
		error: function () {
			if (typeof Swal !== 'undefined') {
				Swal.fire({icon:'error', title:'Error', text: 'An error occurred while processing data.'});
			} else {
				alert("An error occurred while processing data.");
			}
		}
	});

	
}); // end current_reading blur

}); // end document.ready (main search/edit handlers)

$(function() {
	var controls = {
		leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
		rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
	};
	if ($.fn.datepicker) {
		$('#reading_date, #reading_date_add').datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose: true,
			orientation: 'bottom left',
			templates: controls
		});
		$('#reading_date, #reading_date_add').each(function() {
			var $input = $(this);
			$input.closest('.input-group').find('.input-group-text').on('click', function() {
				$input.datepicker('show');
			});
		});
	}
});
</script>
<script type="text/javascript">
$(document).ready(function(){
	// show Add Billing Period modal when button clicked
	$('#add_billing_period').on('click', function(){
		// clear previous values and enable inputs
		$('#frm_add_billing')[0].reset();
		$('#frm_add_billing').find('input,select').prop('disabled', false);
		$('#btn_edit_modal').text('Edit');
		$('#addBillingModal').modal('show');
	});

	// Reusable calculation for Add modal (same rules as edit)
	function recalculateFranchiseFeeAndTotalsAdd() {
		var unit_price = safeMoneyVal('#current_bill_add');
		var maintenance_fee = safeMoneyVal('#maintenance_fee_add');
		var multiprice = unit_price;
		var discount = safeMoneyVal('#sc_discount_add');
		var consumed = parseFloat($('#consumed_add').val() || 0);
		var franchise_fee_percentage = safeMoneyVal('#franchise_fee_percent_add');
		if (!franchise_fee_percentage || franchise_fee_percentage <= 0) {
			franchise_fee_percentage = <?php echo isset($franchise_fee_percentage) ? floatval($franchise_fee_percentage) : '2.00'; ?>;
			$('#franchise_fee_percent_add').val(franchise_fee_percentage);
		}
		// Franchise tax is always based on current bill; senior citizen discount applied to total
		var bill_amount_for_franchise = multiprice;
		var franchise_fee_amount = (bill_amount_for_franchise * franchise_fee_percentage) / 100;
		var total_amount = multiprice - discount;
		total_amount += parseFloat(maintenance_fee);
		total_amount += parseFloat(franchise_fee_amount);
		if($('#franchise_fee_amount_add').length) {
			$('#franchise_fee_amount_add').val(amount_formatted(franchise_fee_amount));
		}
		if($('#total_amount_add').length) {
			$('#total_amount_add').val(amount_formatted(total_amount));
		}
		// Calculate penalty: 10% applied to (current_bill - sc_discount) only, then add maintenance + franchise
		var amount_total_penalty = 0;
		if($('#special_priviledge').val()==='0'){
			var penalty_base = multiprice - discount;  // current_bill - sc_discount
			amount_total_penalty = (penalty_base * 10) / 100;
			amount_total_penalty = amount_total_penalty + penalty_base + maintenance_fee + franchise_fee_amount;
		}else{
			amount_total_penalty = total_amount;
		}
		if($('#penalty_add').length) {
			$('#penalty_add').val(amount_formatted(amount_total_penalty));
		}
	}

	// Bind events on Add modal fields to trigger recalculation
	$('#sc_discount_add, #franchise_fee_percent_add, #maintenance_fee_add').on('blur', function(){
		recalculateFranchiseFeeAndTotalsAdd();
	});

	// When current reading in Add modal loses focus, compute consumed and get unit price
	$('#current_reading_add').on('blur', function() {
		var current_meter = $(this).val();
		var previous_reading = $('#previous_reading_add').val() || 0;
		var differences = parseFloat(current_meter || 0) - parseFloat(previous_reading || 0);
		$("#consumed_add").val(differences);

		const formData = new FormData();
		formData.append("cubic_meter_reading", differences);
		formData.append("customer_id", $('#customer_id').val());

		$.ajax({
			url: '<?php echo ADMIN_URL;?>addmetercustomerreading/get_cubic_meter_price/',
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			success: function (response) {
				const result = JSON.parse(response);
				if (result.per_unit) {
					$('#current_bill_add').val(amount_formatted(result.per_unit));
					if(!$('#franchise_fee_percent_add').val() || $('#franchise_fee_percent_add').val() == '') {
						var default_franchise_fee_percentage = <?php echo isset($franchise_fee_percentage) ? floatval($franchise_fee_percentage) : '2.00'; ?>;
						$('#franchise_fee_percent_add').val(default_franchise_fee_percentage);
					}
					var unit_price = parseFloat($('#current_bill_add').val().replace(/,/g, ''));
					var multiprice = unit_price;
					var consumed = parseFloat($('#consumed_add').val() || 0);
					if($('#cust_type_id').val()==3 && consumed <= 30){
						var discount = (multiprice * 5)/100;
						$('#sc_discount_add').val(amount_formatted(discount));
					} else {
						$('#sc_discount_add').val(amount_formatted(0));
					}
					recalculateFranchiseFeeAndTotalsAdd();
				} else {
					$('#current_bill_add').val(amount_formatted(0));
					$("#amount_pay").val(amount_formatted(0));
					if (typeof Swal !== 'undefined') {
						Swal.fire({icon:'warning', title:'No Amount', text: 'No Amount per cubic meter.'});
					} else {
						alert("No Amount per cubic meter.");
					}
				}
			},
			error: function () {
				if (typeof Swal !== 'undefined') {
					Swal.fire({icon:'error', title:'Error', text: 'An error occurred while processing data.'});
				} else {
					alert("An error occurred while processing data.");
				}
			}
		});
	});

	// Save button handler - insert into tbl_addcustomer_reading via controller
	$('#save_billing_period').on('click', function(){
		// Validate required inputs first (reuse earlier check)
		var missingField = null;
		$('#frm_add_billing').find('[required]').each(function(){
			var $el = $(this);
			var val = $el.val();
			if (val === null || $.trim(val) === '') {
				missingField = $el;
				return false;
			}
		});
		if (missingField) {
			var msg = 'Please fill the "' + (missingField.closest('.form-group').find('.form-label').first().text().trim() || missingField.attr('name')) + '" field.';
			if (typeof Swal !== 'undefined') {
				Swal.fire({icon:'warning', title:'Validation', text: msg});
			} else {
				alert(msg);
			}
			missingField.focus();
			return;
		}

		// Prepare FormData for insertion
		const data = new FormData();
		data.append('customer_id', $('#customer_id').val());
		data.append('billing_period', $('#billing_period_select').val());
		data.append('previous_reading', $('#previous_reading_add').val());
		data.append('current_reading', $('#current_reading_add').val());
		data.append('consumed', $('#consumed_add').val());
		data.append('current_bill', $('#current_bill_add').val());
		data.append('sc_discount', $('#sc_discount_add').val());
		data.append('arrears', $('#arrears_add').val());
		data.append('total_amount', $('#total_amount_add').val());
		data.append('penalty', $('#penalty_add').val());
		data.append('maintenance_fee', $('#maintenance_fee_add').val());
		data.append('franchise_fee_percent', $('#franchise_fee_percent_add').val());
		data.append('franchise_fee_amount', $('#franchise_fee_amount_add').val());
		data.append('reading_date', $('#reading_date_add').val());
		data.append('customer_status', $('#customer_status_add').val());
		data.append('add', 'add');

		$.ajax({
			url: '<?php echo ADMIN_URL;?>addmetercustomerreading/save_add',
			type: 'POST',
			data: data,
			contentType: false,
			processData: false,
			success: function(response){
				// expecting 'success' on successful insert
				if (response && response.trim() === 'success') {
					if (typeof Swal !== 'undefined') {
						Swal.fire({icon:'success', title:'Saved', text: 'Billing period successfully added.'});
					}
					$('#addBillingModal').modal('hide');
					$('#search').trigger('click');
				} else {
					var msg = response || 'An error occurred while saving.';
					if (typeof Swal !== 'undefined') {
						Swal.fire({icon:'error', title:'Error', text: msg});
					} else {
						alert(msg);
					}
				}
			},
			error: function(){
				if (typeof Swal !== 'undefined') {
					Swal.fire({icon:'error', title:'Error', text: 'An error occurred while processing data.'});
				} else {
					alert("An error occurred while processing data.");
				}
			}
		});
	});

	// Optional Edit button inside modal (toggles editable state)
	$('#btn_edit_modal').on('click', function(){
		// toggle disabled state of inputs
		var inputs = $('#frm_add_billing').find('input, select');
		var disabled = inputs.prop('disabled');
		inputs.prop('disabled', !disabled);
		$(this).text(disabled ? 'Edit' : 'Lock');
	});
});
</script>


