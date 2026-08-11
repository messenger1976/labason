<?php
	$record = isset($record) && is_array($record) ? $record : array();
	$is_edit = !empty($record);
	$action_label = $is_edit ? 'Edit Draft' : 'New Draft';
	$v = function ($key, $default = '') use ($record, $is_edit) {
		if ($this->input->post($key) !== null && $this->input->post($key) !== false) {
			return $this->input->post($key);
		}
		if ($is_edit && isset($record[$key])) {
			return $record[$key];
		}
		return $default;
	};
	$adj_date_val = $v('adj_date', date('Y-m-d'));
	// Normalize to dd-mm-yyyy for SA4 datepicker
	if ($adj_date_val && strpos($adj_date_val, '-') !== false && strlen($adj_date_val) === 10) {
		$p = explode('-', $adj_date_val);
		if (count($p) === 3 && strlen($p[0]) === 4) {
			// Y-m-d → d-m-Y
			$adj_date_val = $p[2].'-'.$p[1].'-'.$p[0];
		}
	} else {
		$adj_date_val = date('d-m-Y');
	}

	$sa4_page_icon = 'fal fa-balance-scale-right';
	$sa4_page_title = 'AR Adjustment';
	$sa4_page_subtitle = $action_label;
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/select2/select2.bundle.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>aradjustment">AR Adjustment</a></li>
		<li class="breadcrumb-item active"><?php echo htmlspecialchars($action_label, ENT_QUOTES, 'UTF-8'); ?></li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<?php include(dirname(__FILE__).'/partials/sa4_kpi_subheader.php'); ?>

	<?php if (!empty($msg)) { ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<strong>Error!</strong> <?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-ar-adjustment-form" class="panel">
				<div class="panel-hdr">
					<h2>
						AR Adjustment <span class="fw-300"><i><?php echo htmlspecialchars($action_label, ENT_QUOTES, 'UTF-8'); ?></i></span>
					</h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form method="post" action="" id="ar_adjustment_form" autocomplete="off">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="customer_id">Customer ID <span class="text-danger">*</span></label>
										<input type="text" class="form-control" name="customer_id" id="customer_id" required
											value="<?php echo htmlspecialchars($v('customer_id'), ENT_QUOTES, 'UTF-8'); ?>"
											placeholder="Type customer ID, name, or meter #">
										<small class="help-block text-muted">Pick from suggestions after typing at least 2 characters.</small>
										<div id="cust_suggest" class="list-group mt-1 shadow-sm" style="display:none; max-height:220px; overflow:auto; position:relative; z-index:5;"></div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="adj_type">Adjustment Type <span class="text-danger">*</span></label>
										<?php $atype = $v('adj_type', 'credit_note'); ?>
										<select name="adj_type" id="adj_type" class="form-control" required>
											<option value="credit_note" <?php echo $atype==='credit_note'?'selected':''; ?>>Credit Note (reduce SOA)</option>
											<option value="write_off" <?php echo $atype==='write_off'?'selected':''; ?>>Write-off (reduce SOA)</option>
											<option value="billing_correction" <?php echo $atype==='billing_correction'?'selected':''; ?>>Billing Correction (reduce SOA)</option>
											<option value="debit_memo" <?php echo $atype==='debit_memo'?'selected':''; ?>>Debit Memo (increase SOA)</option>
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="adj_amount">Amount (PHP) <span class="text-danger">*</span></label>
										<input type="number" step="0.01" min="0.01" class="form-control" name="adj_amount" id="adj_amount" required
											value="<?php echo htmlspecialchars($v('adj_amount'), ENT_QUOTES, 'UTF-8'); ?>" placeholder="0.00">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="adj_date">Adjustment Date <span class="text-danger">*</span></label>
										<div class="input-group">
											<input type="text" class="form-control" name="adj_date" id="adj_date" required readonly
												value="<?php echo htmlspecialchars($adj_date_val, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Select date">
											<div class="input-group-append">
												<span class="input-group-text fs-xl">
													<i class="fal fa-calendar"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="month">Billing Month (optional)</label>
										<?php $sel_m = $v('month'); ?>
										<select name="month" id="month" class="form-control">
											<option value="">—</option>
											<?php if (!empty($months)) { foreach ($months as $m) { ?>
											<option value="<?php echo (int) $m['month_id']; ?>" <?php echo ((string)$sel_m === (string)$m['month_id']) ? 'selected' : ''; ?>>
												<?php echo htmlspecialchars($m['month_name'], ENT_QUOTES, 'UTF-8'); ?>
											</option>
											<?php } } ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="year">Billing Year (optional)</label>
										<input type="number" class="form-control" name="year" id="year" min="2000" max="2100"
											value="<?php echo htmlspecialchars($v('year'), ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. 2026">
									</div>
								</div>
							</div>

							<div class="alert alert-info alert-dismissible fade show" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true"><i class="fal fa-times"></i></span>
								</button>
								<strong>Recommended Chart of Accounts</strong>
								<ul class="mb-0 mt-2 pl-3">
									<li><strong>Credit Note / Billing Correction:</strong> Dr <em>Sales Returns and Allowances - Billing Credits</em> · Cr <em>Accounts Receivable - Water Customers</em></li>
									<li><strong>Write-off</strong> (e.g. ₱25.20 shortfall): Dr <em>Bad Debts Expense - AR Write-off</em> · Cr <em>Accounts Receivable - Water Customers</em></li>
									<li><strong>Debit Memo:</strong> Dr <em>Accounts Receivable - Water Customers</em> · Cr <em>Billing Adjustment Income</em></li>
								</ul>
								<small class="d-block mt-2">Changing Adjustment Type auto-fills Debit/Credit ledgers. You can still override before saving.</small>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="reading_refno">Reading Ref No (optional)</label>
										<input type="text" class="form-control" name="reading_refno" id="reading_refno"
											value="<?php echo htmlspecialchars($v('reading_refno'), ENT_QUOTES, 'UTF-8'); ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label">GL pairing hint</label>
										<div id="gl_pair_hint" class="form-control-plaintext text-primary fw-500 px-0">—</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="dr_ledger_id">Debit GL Ledger <span class="text-danger">*</span></label>
										<?php $dr = (int) $v('dr_ledger_id'); ?>
										<select name="dr_ledger_id" id="dr_ledger_id" class="form-control select2-ledger" required>
											<option value="">— Select —</option>
											<?php if (!empty($ledgers)) { foreach ($ledgers as $L) { ?>
											<option value="<?php echo (int) $L['id']; ?>" <?php echo $dr === (int)$L['id'] ? 'selected' : ''; ?>>
												<?php echo htmlspecialchars($L['ledgerName'], ENT_QUOTES, 'UTF-8'); ?>
											</option>
											<?php } } ?>
										</select>
										<small class="help-block text-muted" id="dr_hint">Expense / allowance / AR depending on type.</small>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="cr_ledger_id">Credit GL Ledger <span class="text-danger">*</span></label>
										<?php $cr = (int) $v('cr_ledger_id'); ?>
										<select name="cr_ledger_id" id="cr_ledger_id" class="form-control select2-ledger" required>
											<option value="">— Select —</option>
											<?php if (!empty($ledgers)) { foreach ($ledgers as $L) { ?>
											<option value="<?php echo (int) $L['id']; ?>" <?php echo $cr === (int)$L['id'] ? 'selected' : ''; ?>>
												<?php echo htmlspecialchars($L['ledgerName'], ENT_QUOTES, 'UTF-8'); ?>
											</option>
											<?php } } ?>
										</select>
										<small class="help-block text-muted" id="cr_hint">Usually Accounts Receivable for credit-side reductions.</small>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="reason">Reason <span class="text-danger">*</span></label>
										<textarea name="reason" id="reason" class="form-control" rows="3" required placeholder="e.g. Dec 2025 underpayment shortfall PHP 25.20 — billing correction / write-off"><?php echo htmlspecialchars($v('reason'), ENT_QUOTES, 'UTF-8'); ?></textarea>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="remarks">Remarks</label>
										<textarea name="remarks" id="remarks" class="form-control" rows="3"><?php echo htmlspecialchars($v('remarks'), ENT_QUOTES, 'UTF-8'); ?></textarea>
									</div>
								</div>
							</div>

							<div class="row mt-2">
								<div class="col-12">
									<button type="submit" name="save_draft" value="1" class="btn btn-primary waves-effect waves-themed">
										<i class="fal fa-save mr-1"></i> Save Draft
									</button>
									<a href="<?php echo ADMIN_URL; ?>aradjustment" class="btn btn-secondary waves-effect waves-themed">
										<i class="fal fa-arrow-left mr-1"></i> Cancel
									</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/formplugins/select2/select2.bundle.js"></script>
<script>
$(document).ready(function(){
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

	var controls = {
		leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
		rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
	};
	if ($.fn.datepicker) {
		$('#adj_date').datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose: true,
			orientation: 'bottom left',
			templates: controls
		});
		$('#adj_date').closest('.input-group').find('.input-group-text').on('click', function() {
			$('#adj_date').datepicker('show');
		});
	}

	if ($.fn.select2) {
		$('.select2-ledger').select2({
			width: '100%',
			placeholder: '— Select —',
			allowClear: true
		});
	}

	var $box = $('#cust_suggest');
	var timer = null;
	var glDefaults = <?php echo json_encode(isset($gl_defaults) ? $gl_defaults : array()); ?>;
	var hadPostedDr = <?php echo ((int)$v('dr_ledger_id') > 0) ? 'true' : 'false'; ?>;
	var hadPostedCr = <?php echo ((int)$v('cr_ledger_id') > 0) ? 'true' : 'false'; ?>;
	var userTouchedGl = hadPostedDr || hadPostedCr;

	function applyGlDefaults(force) {
		var t = $('#adj_type').val();
		var cfg = glDefaults[t];
		if (!cfg) {
			$('#gl_pair_hint').text('—');
			return;
		}
		$('#gl_pair_hint').text(cfg.hint || '—');
		if (force || !userTouchedGl) {
			if (cfg.dr) { $('#dr_ledger_id').val(String(cfg.dr)).trigger('change'); }
			if (cfg.cr) { $('#cr_ledger_id').val(String(cfg.cr)).trigger('change'); }
		}
	}

	$('#adj_type').on('change', function(){
		userTouchedGl = false;
		applyGlDefaults(true);
	});
	$('#dr_ledger_id, #cr_ledger_id').on('change', function(){
		userTouchedGl = true;
	});
	applyGlDefaults(false);

	$('#customer_id').on('keyup', function(){
		var q = $(this).val();
		clearTimeout(timer);
		if(!q || q.length < 2){ $box.hide().empty(); return; }
		timer = setTimeout(function(){
			$.getJSON('<?php echo ADMIN_URL; ?>aradjustment/search_customer', {q:q}, function(rows){
				if(!rows || !rows.length){ $box.hide().empty(); return; }
				var html = '';
				$.each(rows, function(_, r){
					var name = ((r.last_name||'') + ', ' + (r.first_name||'') + ' ' + (r.middle_name||'')).trim();
					html += '<a href="javascript:void(0);" class="list-group-item list-group-item-action cust-pick" data-id="'+r.customer_id+'">'
						+ '<strong>'+r.customer_id+'</strong> — '+name
						+ (r.meter_number ? ' <small class="text-muted">(Meter '+r.meter_number+')</small>' : '')
						+ '</a>';
				});
				$box.html(html).show();
			});
		}, 250);
	});
	$(document).on('click', '.cust-pick', function(){
		$('#customer_id').val($(this).data('id'));
		$box.hide().empty();
	});
});
</script>
