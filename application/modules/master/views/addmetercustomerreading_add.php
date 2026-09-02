<?php
	$customers = (isset($record) && is_array($record)) ? $record : array();
	$msg = isset($msg) ? $msg : '';
	$selected = isset($member_id) ? $member_id : '';
	$month = isset($month) ? $month : '';
	$year = isset($year) ? $year : date('Y');

	$sa4_page_icon = 'fal fa-tachometer';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Meter Customer Reading';
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/select2/select2.bundle.css">
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<style>
	.mcr-readonly { background-color: #fff8dc !important; }
	#hideclass.mcr-reading-panel {
		display: none;
		margin-top: 1.25rem;
		padding: 1.25rem;
		border: 1px solid rgba(0, 0, 0, 0.08);
		border-radius: 4px;
		background: #fafafa;
	}
</style>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addmetercustomerreading">Meter Reading</a></li>
		<li class="breadcrumb-item active">Add</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<?php include(dirname(__FILE__) . '/partials/sa4_kpi_subheader.php'); ?>

	<?php if ($msg != '') { ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<?php echo $msg; ?>
	</div>
	<?php } ?>
	<?php echo $this->session->flashdata('msg'); ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-mcr-add" class="panel">
				<div class="panel-hdr">
					<h2>Meter Customer Reading <span class="fw-300"><i>Add</i></span></h2>
					<div class="panel-toolbar">
						<a href="<?php echo ADMIN_URL; ?>addmetercustomerreading" class="btn btn-secondary btn-sm waves-effect waves-themed mr-2">
							<i class="fal fa-arrow-left mr-1"></i> Back to List
						</a>
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
							<div class="row align-items-end mb-3">
								<div class="col-md-8 col-lg-9">
									<div class="form-group mb-md-0">
										<label class="form-label" for="search_box_id">Search Customer <span class="text-danger">*</span></label>
										<select class="form-control" id="search_box_id" name="search_box_id" required>
											<option value="">Type text to search...</option>
											<?php foreach ($customers as $value) {
												$cid = isset($value['customer_id']) ? $value['customer_id'] : '';
												$label = $cid . ' ==> ' . (isset($value['last_name']) ? $value['last_name'] : '') . ', ' . (isset($value['first_name']) ? $value['first_name'] : '') . ' ' . (isset($value['middle_name']) ? $value['middle_name'] : '');
											?>
											<option value="<?php echo htmlspecialchars($cid); ?>" <?php echo ($selected && $selected == $cid) ? 'selected' : ''; ?>>
												<?php echo htmlspecialchars($label); ?>
											</option>
											<?php } ?>
										</select>
										<?php echo form_error('search_box_id'); ?>
									</div>
								</div>
								<div class="col-md-4 col-lg-3 text-md-right">
									<button type="button" class="btn btn-primary btn-sm waves-effect waves-themed mb-1" id="btn_search_box" name="btn_search_box">
										<i class="fal fa-search mr-1"></i> Search
									</button>
									<a href="<?php echo ADMIN_URL; ?>addmetercustomerreading" id="btn_search_cancel" class="btn btn-secondary btn-sm waves-effect waves-themed mb-1" name="btn_search_cancel">
										<i class="fal fa-times mr-1"></i> Cancel
									</a>
								</div>
							</div>

							<div id="meterincomeDiv" class="mt-2 mb-3"></div>

							<div id="hideclass" class="mcr-reading-panel">
								<h5 class="mb-3 fw-500">
									<i class="fal fa-tachometer-alt mr-1 color-primary-500"></i> Reading Details
								</h5>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label" for="preview">Previous Reading</label>
											<input type="text" class="form-control mcr-readonly text-input" id="preview" name="preview" value="<?php echo $this->input->post('preview'); ?>" readonly="readonly">
											<input type="hidden" id="customer_id" name="customer_id" value="<?php echo $this->input->post('customer_id'); ?>">
											<?php echo form_error('preview'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label" for="current_meter">Current Reading <span class="text-danger">*</span></label>
											<input type="text" step="1" class="form-control text-input" id="current_meter" name="current_meter" value="<?php echo $this->input->post('current_meter'); ?>" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label" for="different">Cu. M. Consumed</label>
											<input type="text" class="form-control mcr-readonly" id="different" name="different" value="<?php echo $this->input->post('different'); ?>" readonly="readonly">
											<?php echo form_error('different'); ?>
										</div>
									</div>
									<div class="col-md-6" style="display: none;">
										<div class="form-group">
											<label class="form-label" for="unit_price">Unit Price</label>
											<input type="text" class="form-control mcr-readonly text-right" id="unit_price" name="unit_price" value="<?php echo $this->input->post('unit_price'); ?>" readonly="readonly">
											<?php echo form_error('unit_price'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label" for="amount_pay">Current Bill</label>
											<input type="text" class="form-control mcr-readonly text-right" id="amount_pay" name="amount_pay" value="<?php echo $this->input->post('amount_pay'); ?>" readonly="readonly">
											<?php echo form_error('amount_pay'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label" for="discount">Discount</label>
											<input type="text" class="form-control text-input text-right" id="discount" name="discount" value="<?php echo $this->input->post('discount'); ?>">
											<?php echo form_error('discount'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label" for="prev_balance">Previous Balance / Arrears</label>
											<input type="text" class="form-control text-input text-right" id="prev_balance" name="prev_balance" value="<?php echo $this->input->post('prev_balance'); ?>">
											<?php echo form_error('prev_balance'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label" for="total_amount">Amount Due (on or before due date)</label>
											<input type="text" class="form-control mcr-readonly text-right" id="total_amount" name="total_amount" value="<?php echo $this->input->post('total_amount'); ?>" readonly>
											<?php echo form_error('total_amount'); ?>
										</div>
									</div>
									<div class="col-md-6" style="display: none;">
										<div class="form-group">
											<label class="form-label" for="due_date">Due Date</label>
											<input type="text" class="form-control" id="due_date" name="due_date" value="<?php echo $this->input->post('due_date'); ?>">
											<?php echo form_error('due_date'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label" for="amount_total_penalty">Amount Due (after due date)</label>
											<input type="text" class="form-control mcr-readonly text-right" id="amount_total_penalty" name="amount_total_penalty" value="<?php echo $this->input->post('amount_total_penalty'); ?>" readonly>
											<?php echo form_error('amount_total_penalty'); ?>
										</div>
									</div>
									<div class="col-md-6" style="display: none;">
										<div class="form-group">
											<label class="form-label">Billing Period</label>
											<div class="row">
												<div class="col-6">
													<input type="text" class="form-control" name="month" id="month" value="<?php echo htmlspecialchars($month); ?>">
													<?php echo form_error('month'); ?>
												</div>
												<div class="col-6">
													<input type="text" class="form-control" name="year" id="year" value="<?php echo htmlspecialchars($year); ?>">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label" for="date">Reading Date <span class="text-danger">*</span></label>
											<div class="input-group">
												<input type="text" class="form-control" id="date" name="date" value="<?php echo $this->input->post('date'); ?>" required readonly>
												<div class="input-group-append">
													<span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
												</div>
											</div>
											<?php echo form_error('date'); ?>
										</div>
									</div>
								</div>
								<input type="hidden" name="billing_period_id" id="billing_period_id">
							</div>

							<div id="total_setting_2" class="mt-3" style="display:none;">
								<a href="<?php echo ADMIN_URL; ?>addmetercustomerreading" class="btn btn-secondary waves-effect waves-themed">
									<i class="fal fa-times mr-1"></i> Cancel
								</a>
								<button type="submit" class="btn btn-primary waves-effect waves-themed" name="add" id="add_button" value="Add">
									<i class="fal fa-save mr-1"></i> Add
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php include('footer.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/formplugins/select2/select2.bundle.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
</body>
</html>
<script type="text/javascript">
$(document).ready(function() {
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
	$('#search_box_id').select2({
		placeholder: 'Type text to search...',
		allowClear: true,
		width: '100%'
	});
	$('#total_setting_2').hide();

	var dateControls = {
		leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
		rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
	};
	if ($.fn.datepicker) {
		$('#date').datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose: true,
			orientation: 'bottom left',
			templates: dateControls
		});
		$('#date').closest('.input-group').find('.input-group-text').on('click', function() {
			$('#date').datepicker('show');
		});
	}

	$('.text-input').on('focus', function() {
		$(this).select();
	});
});

$('#btn_search_box').on('click', function(event) {
	event.preventDefault();
	showSpinner();
	var search_text = $('#search_box_id').val();
	if (!search_text) {
		hideSpinner();
		return;
	}
	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL; ?>addmetercustomerreading/get_custmer_all_data/' + search_text,
		data: { id: search_text },
		success: function(data) {
			$('#meterincomeDiv').html(data);
			$('#unit_price').val(amount_formatted(0));
			$('#amount_pay').val(amount_formatted(0));
			$('#different').val(0);
			$('#current_meter').val(0);
		}
	});
	setTimeout(hideSpinner, 1000);
});

$(document).on('click', '.pay_button', function(e) {
	var cust = $('#customer').val();
	var pre = $('#preview_read').val();
	var unit = $('#unit_pr').val();
	var bp_id = $('#bp_id').val();
	var bp_period_month = $('#bp_period_month').val();
	var bp_period_year = $('#bp_period_year').val();

	$('#customer_id').val(cust);
	$('#preview').val(pre);
	$('#unit_price').val(unit);
	$('#billing_period_id').val(bp_id);
	$('#month').val(bp_period_month);
	$('#year').val(bp_period_year);

	if (pre == 0) {
		$('#preview').removeAttr('readonly').removeClass('mcr-readonly');
	}
});

$('#discount').on('blur', function(evt) {
	evt.preventDefault();
	var unit_price = $('#unit_price').val();
	var multiprice = parseFloat(unit_price);
	var discount = $(this).val();

	var total_amount = multiprice - discount;
	var amount_total_penalty = 0;
	if ($('#special_priviledge').val() === '0') {
		amount_total_penalty = (total_amount * 10) / 100;
		amount_total_penalty = amount_total_penalty + total_amount;
	} else {
		amount_total_penalty = total_amount;
	}
	$('#discount').val(amount_formatted(discount));
	$('#amount_pay').val(amount_formatted(multiprice));
	$('#total_amount').val(amount_formatted(total_amount));
	$('#amount_total_penalty').val(amount_formatted(amount_total_penalty));
});

$('#current_meter').on('blur', function() {
	var current_meter = $(this).val();
	var preview = $('#preview').val();
	var differances = parseFloat(current_meter) - parseFloat(preview);
	$('#different').val(differances);
	var difer = $('#different').val();
	var formData = new FormData();
	formData.append('cubic_meter_reading', difer);
	formData.append('customer_id', $('#customer_id').val());

	$.ajax({
		url: '<?php echo ADMIN_URL; ?>addmetercustomerreading/get_cubic_meter_price/',
		type: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		success: function(response) {
			var result = JSON.parse(response);
			if (result.per_unit) {
				$('#unit_price').val(amount_formatted(result.per_unit));
				var unit_price = $('#unit_price').val();
				var multiprice = parseFloat(unit_price);
				var discount = 0;
				if ($('#cust_type_id').val() == 3) {
					discount = (multiprice * 5) / 100;
				}
				var total_amount = multiprice - discount;
				var amount_total_penalty = 0;
				if ($('#special_priviledge').val() === '0') {
					amount_total_penalty = (total_amount * 10) / 100;
					amount_total_penalty = amount_total_penalty + total_amount;
				} else {
					amount_total_penalty = total_amount;
				}
				$('#discount').val(amount_formatted(discount));
				$('#amount_pay').val(amount_formatted(multiprice));
				$('#total_amount').val(amount_formatted(total_amount));
				$('#amount_total_penalty').val(amount_formatted(amount_total_penalty));
			} else {
				$('#unit_price').val(amount_formatted(0));
				$('#amount_pay').val(amount_formatted(0));
				alert('No Amount per cubic meter.');
			}
		},
		error: function() {
			alert('An error occurred while processing data.');
		}
	});
});

function amount_formatted(amount) {
	return new Intl.NumberFormat('en-US', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2,
		useGrouping: false
	}).format(amount);
}
</script>
