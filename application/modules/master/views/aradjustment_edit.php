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
	if ($adj_date_val && strpos($adj_date_val, '-') !== false && strlen($adj_date_val) === 10 && substr($adj_date_val, 2, 1) === '-') {
		// d-m-Y → Y-m-d for input[type=date]
		$p = explode('-', $adj_date_val);
		if (strlen($p[0]) === 2) {
			$adj_date_val = $p[2].'-'.$p[1].'-'.$p[0];
		}
	}
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>aradjustment">AR Adjustment</a></li>
		<li class="breadcrumb-item active"><?php echo $action_label; ?></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-balance-scale-right"></i>
			AR Adjustment <span class="fw-300"><?php echo $action_label; ?></span>
		</h1>
	</div>

	<?php if (!empty($msg)) { ?>
	<div class="alert alert-danger"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-10">
			<div class="panel">
				<div class="panel-hdr"><h2><?php echo $action_label; ?></h2></div>
				<div class="panel-container show">
					<div class="panel-content">
						<form method="post" action="" class="form-horizontal" autocomplete="off">
							<div class="form-row">
								<div class="form-group col-md-4">
									<label class="form-label">Customer ID <span class="text-danger">*</span></label>
									<input type="text" class="form-control" name="customer_id" id="customer_id" required
										value="<?php echo htmlspecialchars($v('customer_id'), ENT_QUOTES, 'UTF-8'); ?>"
										placeholder="e.g. 11-7-12-01041">
									<small class="text-muted">Type customer ID, name, or meter # then pick from suggestions.</small>
									<div id="cust_suggest" class="list-group mt-1" style="display:none; max-height:220px; overflow:auto;"></div>
								</div>
								<div class="form-group col-md-4">
									<label class="form-label">Adjustment Type <span class="text-danger">*</span></label>
									<?php $atype = $v('adj_type', 'credit_note'); ?>
									<select name="adj_type" id="adj_type" class="form-control" required>
										<option value="credit_note" <?php echo $atype==='credit_note'?'selected':''; ?>>Credit Note (reduce SOA)</option>
										<option value="write_off" <?php echo $atype==='write_off'?'selected':''; ?>>Write-off (reduce SOA)</option>
										<option value="billing_correction" <?php echo $atype==='billing_correction'?'selected':''; ?>>Billing Correction (reduce SOA)</option>
										<option value="debit_memo" <?php echo $atype==='debit_memo'?'selected':''; ?>>Debit Memo (increase SOA)</option>
									</select>
								</div>
								<div class="form-group col-md-4">
									<label class="form-label">Amount (PHP) <span class="text-danger">*</span></label>
									<input type="number" step="0.01" min="0.01" class="form-control" name="adj_amount" required
										value="<?php echo htmlspecialchars($v('adj_amount'), ENT_QUOTES, 'UTF-8'); ?>">
								</div>
							</div>

							<div class="form-row">
								<div class="form-group col-md-4">
									<label class="form-label">Adjustment Date <span class="text-danger">*</span></label>
									<input type="date" class="form-control" name="adj_date" required value="<?php echo htmlspecialchars($adj_date_val, ENT_QUOTES, 'UTF-8'); ?>">
								</div>
								<div class="form-group col-md-4">
									<label class="form-label">Billing Month (optional)</label>
									<?php $sel_m = $v('month'); ?>
									<select name="month" class="form-control">
										<option value="">—</option>
										<?php if (!empty($months)) { foreach ($months as $m) { ?>
										<option value="<?php echo (int) $m['month_id']; ?>" <?php echo ((string)$sel_m === (string)$m['month_id']) ? 'selected' : ''; ?>>
											<?php echo htmlspecialchars($m['month_name'], ENT_QUOTES, 'UTF-8'); ?>
										</option>
										<?php } } ?>
									</select>
								</div>
								<div class="form-group col-md-4">
									<label class="form-label">Billing Year (optional)</label>
									<input type="number" class="form-control" name="year" min="2000" max="2100"
										value="<?php echo htmlspecialchars($v('year'), ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. 2025">
								</div>
							</div>

							<div class="alert alert-info">
								<strong>Recommended Chart of Accounts for this entry</strong>
								<ul class="mb-0 mt-2 pl-3">
									<li><strong>Credit Note / Billing Correction:</strong> Dr <em>Sales Returns and Allowances - Billing Credits</em> · Cr <em>Accounts Receivable - Water Customers</em></li>
									<li><strong>Write-off</strong> (e.g. ₱25.20 shortfall): Dr <em>Bad Debts Expense - AR Write-off</em> · Cr <em>Accounts Receivable - Water Customers</em></li>
									<li><strong>Debit Memo:</strong> Dr <em>Accounts Receivable - Water Customers</em> · Cr <em>Billing Adjustment Income</em></li>
								</ul>
								<small class="d-block mt-2">Changing Adjustment Type auto-fills Debit/Credit ledgers. You can still override before saving.</small>
							</div>

							<div class="form-row">
								<div class="form-group col-md-4">
									<label class="form-label">Reading Ref No (optional)</label>
									<input type="text" class="form-control" name="reading_refno"
										value="<?php echo htmlspecialchars($v('reading_refno'), ENT_QUOTES, 'UTF-8'); ?>">
								</div>
								<div class="form-group col-md-4">
									<label class="form-label">Debit GL Ledger <span class="text-danger">*</span></label>
									<?php $dr = (int) $v('dr_ledger_id'); ?>
									<select name="dr_ledger_id" id="dr_ledger_id" class="form-control" required>
										<option value="">— Select —</option>
										<?php if (!empty($ledgers)) { foreach ($ledgers as $L) { ?>
										<option value="<?php echo (int) $L['id']; ?>" <?php echo $dr === (int)$L['id'] ? 'selected' : ''; ?>>
											<?php echo htmlspecialchars($L['ledgerName'], ENT_QUOTES, 'UTF-8'); ?>
										</option>
										<?php } } ?>
									</select>
									<small class="text-muted" id="dr_hint">Expense / allowance / AR depending on type.</small>
								</div>
								<div class="form-group col-md-4">
									<label class="form-label">Credit GL Ledger <span class="text-danger">*</span></label>
									<?php $cr = (int) $v('cr_ledger_id'); ?>
									<select name="cr_ledger_id" id="cr_ledger_id" class="form-control" required>
										<option value="">— Select —</option>
										<?php if (!empty($ledgers)) { foreach ($ledgers as $L) { ?>
										<option value="<?php echo (int) $L['id']; ?>" <?php echo $cr === (int)$L['id'] ? 'selected' : ''; ?>>
											<?php echo htmlspecialchars($L['ledgerName'], ENT_QUOTES, 'UTF-8'); ?>
										</option>
										<?php } } ?>
									</select>
									<small class="text-muted" id="cr_hint">Usually Accounts Receivable for credit-side reductions.</small>
								</div>
							</div>

							<div class="form-group">
								<label class="form-label">GL pairing hint</label>
								<div id="gl_pair_hint" class="form-control-plaintext text-primary font-weight-bold">—</div>
							</div>

							<div class="form-group">
								<label class="form-label">Reason <span class="text-danger">*</span></label>
								<textarea name="reason" class="form-control" rows="3" required placeholder="e.g. Dec 2025 underpayment shortfall PHP 25.20 — billing correction / write-off"><?php echo htmlspecialchars($v('reason'), ENT_QUOTES, 'UTF-8'); ?></textarea>
							</div>
							<div class="form-group">
								<label class="form-label">Remarks</label>
								<textarea name="remarks" class="form-control" rows="2"><?php echo htmlspecialchars($v('remarks'), ENT_QUOTES, 'UTF-8'); ?></textarea>
							</div>

							<div class="form-group">
								<button type="submit" name="save_draft" value="1" class="btn btn-primary">
									<i class="fal fa-save"></i> Save Draft
								</button>
								<a href="<?php echo ADMIN_URL; ?>aradjustment" class="btn btn-secondary">Cancel</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
<script>
$(document).ready(function(){
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
			if (cfg.dr) { $('#dr_ledger_id').val(String(cfg.dr)); }
			if (cfg.cr) { $('#cr_ledger_id').val(String(cfg.cr)); }
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
