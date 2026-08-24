<?php
	$has_particulars = !empty($has_particulars);
	$post_date = $this->input->post('date');
	$date_val = $post_date !== null && $post_date !== ''
		? htmlspecialchars($post_date, ENT_QUOTES, 'UTF-8')
		: date('d-m-Y');
	$amount_val = $this->input->post('credit') !== null
		? htmlspecialchars($this->input->post('credit'), ENT_QUOTES, 'UTF-8')
		: '';
	$particulars_val = $this->input->post('particulars') !== null
		? htmlspecialchars($this->input->post('particulars'), ENT_QUOTES, 'UTF-8')
		: '';
	$sel_debit = $this->input->post('transaction_id');
	$sel_credit = $this->input->post('ledger_id');

	$render_ledger_options = function ($selected) use ($ledger1, $customer, $employee, $expenseType) {
		$html = '<option value="">--Select--</option>';
		$groups = array(
			'Chart of Accounts' => $ledger1,
			'Customers' => $customer,
			'Employees' => $employee,
			'Expense Types' => $expenseType,
		);
		foreach ($groups as $label => $rows) {
			if (empty($rows)) {
				continue;
			}
			$html .= '<optgroup label="'.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'">';
			foreach ($rows as $row) {
				$suffix = ($label === 'Customers') ? 'customer_id'
					: (($label === 'Employees') ? 'employee_id'
					: (($label === 'Expense Types') ? 'expenses_type' : 'ledger_id'));
				$val = $row['id'].' / '.$suffix;
				$sel = ((string) $selected === (string) $val) ? ' selected' : '';
				$html .= '<option value="'.htmlspecialchars($val, ENT_QUOTES, 'UTF-8').'"'.$sel.'>'
					.htmlspecialchars($row['ledgerName'], ENT_QUOTES, 'UTF-8').'</option>';
			}
			$html .= '</optgroup>';
		}
		return $html;
	};
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addjournalvoucher">Journal Voucher</a></li>
		<li class="breadcrumb-item active">Add</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-book"></i>
			Journal Voucher <span class="fw-300">Add</span>
		</h1>
	</div>

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
			<div class="panel">
				<div class="panel-hdr">
					<h2>Journal Voucher <span class="fw-300"><i>Details</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<p class="text-muted mb-3">
							Posts a balanced GL pair (Debit + Credit). Put the reason in <strong>Particulars</strong>
							(e.g. OR number and customer ID for a cash refund).
							This does <strong>not</strong> change Daily Collection Report.
						</p>
						<form class="form-horizontal" role="form" name="myform" id="myform" method="post" action="" autocomplete="off">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="voucherNo">Voucher No.</label>
										<input class="form-control" type="text" id="voucherNo" name="voucherNo" readonly
											value="<?php echo htmlspecialchars($voucher, ENT_QUOTES, 'UTF-8'); ?>"/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="date">Date <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="date" name="date" placeholder="dd-mm-yyyy" required
											value="<?php echo $date_val; ?>"/>
										<small class="form-text text-muted">Posting date (dd-mm-yyyy).</small>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="transaction_id">Debit ledger <span class="text-danger">*</span></label>
										<select name="transaction_id" id="transaction_id" class="form-control" required>
											<?php echo $render_ledger_options($sel_debit); ?>
										</select>
										<small class="form-text text-muted">Account that increases Debit.</small>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="ledger_id">Credit ledger <span class="text-danger">*</span></label>
										<select name="ledger_id" id="ledger_id" class="form-control" required>
											<?php echo $render_ledger_options($sel_credit); ?>
										</select>
										<small class="form-text text-muted">Account that increases Credit (e.g. Cash for cash-out).</small>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="credit">Amount <span class="text-danger">*</span></label>
										<input class="form-control" type="text" id="credit" name="credit" required
											value="<?php echo $amount_val; ?>" placeholder="0.00"/>
									</div>
								</div>
								<?php if ($has_particulars) { ?>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="particulars">Particulars / narration</label>
										<textarea class="form-control" id="particulars" name="particulars" rows="3"
											placeholder="e.g. Refund overcollection OR 12345 — customer account"><?php echo $particulars_val; ?></textarea>
										<small class="form-text text-muted">Cite OR # and customer ID when posting a refund.</small>
									</div>
								</div>
								<?php } else { ?>
								<div class="col-md-6">
									<div class="alert alert-warning mb-0">
										Particulars column is missing. Run <code>sql/add_jv_particulars.sql</code> then reload this page.
									</div>
								</div>
								<?php } ?>
							</div>
							<div class="mt-3">
								<a href="<?php echo ADMIN_URL; ?>addjournalvoucher" class="btn btn-secondary">Cancel</a>
								<button type="submit" class="btn btn-primary" name="add" id="add" value="Add">
									<i class="fal fa-check mr-1"></i> Save Journal Voucher
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
<script src="<?php echo base_url(); ?>sa4/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	if ($.fn.datepicker) {
		$('#date').datepicker({
			todayHighlight: true,
			autoclose: true,
			format: 'dd-mm-yyyy',
			orientation: 'bottom left'
		});
	}
	$('#myform').on('submit', function() {
		var d = $.trim($('#transaction_id').val());
		var c = $.trim($('#ledger_id').val());
		if (d && c && d === c) {
			alert('Debit and Credit ledgers must be different.');
			return false;
		}
		var amt = parseFloat($('#credit').val());
		if (!(amt > 0)) {
			alert('Enter an amount greater than zero.');
			return false;
		}
	});
});
</script>
</body>
</html>
