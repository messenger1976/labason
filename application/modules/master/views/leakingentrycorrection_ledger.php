<?php
	$sa4_loading_label = 'Leaking Ledger Details';
	$sa4_dt_entity = 'ledger details';
	$sa4_panel_id = 'panel-leakingentrycorrection-ledger';

	$income1 = $this->customer_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->customer_model->get_income_monthlycustomer();
	extract($income2);
	$intotal = (isset($total1) ? (float)$total1 : 0) + (isset($total2) ? (float)$total2 : 0);

	$expense1 = $this->customer_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->customer_model->get_outcome_payroll();
	extract($expense2);
	$extotal = (isset($extotal1) ? (float)$extotal1 : 0) + (isset($extotal2) ? (float)$extotal2 : 0);

	$total_customer = $this->customer_model->total_customer();
	extract($total_customer);

	$bill_amount = isset($record_ledger['leaking_total_amount']) ? (float)$record_ledger['leaking_total_amount'] : 0;
	$total_paid = isset($total_payment) ? (float)$total_payment : 0;
	$balance_amount = $bill_amount - $total_paid;
	$prev_balance_val = isset($record_ledger['leaking_balance']) ? $record_ledger['leaking_balance'] : $balance_amount;
?>
<style>
	#myModal .modal-body { max-height: 70vh; overflow-y: auto; }
</style>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>Leakingentrycorrection">Leaking Entry Correction</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-tint"></i>
			Manage <span class="fw-300">Leaking Ledger Details</span>
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
				<span class="fw-500 fs-xl d-block color-success-500"><?php echo (int) $count_id; ?></span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#1dc9b7" sparkHeight="32px" sparkBarWidth="5px" values="2,5,3,7,4,6,3,8,5,4,6"></span>
		</div>
	</div>

	<?php if ($this->session->flashdata('msg_succ')) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<strong>Success!</strong> <?php echo $this->session->flashdata('msg_succ'); ?>
	</div>
	<?php } ?>

	<section id="widget-grid" class="">
		<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
		<div class="row">
			<div class="col-xl-12">
				<div id="panel-leakingentrycorrection-ledger" class="panel">
					<div class="panel-hdr">
						<h2>Manage Leaking Ledger <span class="fw-300"><i>Details</i></span></h2>
						<div class="panel-toolbar">
							<a href="<?php echo ADMIN_URL; ?>Leakingentrycorrection" class="btn btn-danger btn-sm waves-effect waves-themed mr-1">
								<i class="fal fa-chevron-left mr-1"></i> Back Listing
							</a>
							<button type="button" class="btn btn-success btn-sm waves-effect waves-themed mr-1" id="print_statement">
								<i class="fal fa-print mr-1"></i> Print Statement
							</button>
							<?php if ($balance_amount != 0) { ?>
							<button type="button" class="btn btn-primary btn-sm waves-effect waves-themed mr-1" id="add_payment">
								<i class="fal fa-plus mr-1"></i> Add Payment
							</button>
							<?php } ?>
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<div class="mb-3 fs-md">
								<div><strong>Bill Amount:</strong> <u><?php echo number_format($bill_amount, 2); ?></u></div>
								<div><strong>Total Bill Payment:</strong> <u><?php echo number_format($total_paid, 2); ?></u></div>
								<div><strong>Balance:</strong> <u><?php echo number_format($balance_amount, 2); ?></u></div>
							</div>

							<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
								<thead>
									<tr>
										<th style="width:50px;">S No</th>
										<th style="width:100px;">Reference #</th>
										<th style="width:100px;">Payment Date</th>
										<th style="width:150px;">Total Amount</th>
										<th>Remarks</th>
										<th class="text-center" style="width:100px;">Action</th>
									</tr>
								</thead>
								<tbody>
								<?php
									if (!empty($record) && count($record) > 0) {
										$i = 1;
										foreach ($record as $key => $row) {
								?>
									<tr>
										<td><?php echo $i; ?></td>
										<td><?php echo stripslashes($row['leakingledgerdetails_source_type'].'#'.$row['leakingledgerdetails_or_number']); ?></td>
										<td><?php echo date('M j, Y', strtotime($row['leakingledgerdetails_transdate'])); ?></td>
										<td class="text-right"><?php echo number_format($row['leakingledgerdetails_amount'], 2); ?></td>
										<td><?php echo stripslashes($row['leakingledgerdetails_remarks']); ?></td>
										<td class="text-center">
											<span class="btn btn-outline-primary btn-xs"><i class="fal fa-check"></i> Posted</span>
										</td>
									</tr>
								<?php
											$i++;
										}
									}
								?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<!-- Add Payment Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Add New Payment</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_update" id="frm_update" action="" method="POST">
					<div class="form-group" id="source_type_div">
						<label class="form-label"><strong>Source Type :</strong></label>
						<select class="form-control" name="source_type" id="source_type" required>
							<option value="OR">OR</option>
							<option value="SI">SI</option>
						</select>
					</div>
					<div class="form-group">
						<label class="form-label"><strong>Reference # : (*)</strong></label>
						<input class="form-control" type="text" id="refno" name="refno" required>
					</div>
					<div class="form-group">
						<label class="form-label"><strong>Balance Amount :</strong></label>
						<input class="form-control text-input" type="text" id="prev_balance" name="prev_balance" value="<?php echo htmlspecialchars($prev_balance_val); ?>" style="background-color:yellow;" readonly>
					</div>
					<div class="form-group">
						<label class="form-label"><strong>Amount Tender: (*)</strong></label>
						<input class="form-control text-input" type="text" id="amount_pay" name="amount_pay" value="0.00" required>
					</div>
					<div class="form-group">
						<label class="form-label"><strong>Transaction Date : (*)</strong></label>
						<input class="form-control text-input" type="text" id="transdate" name="transdate" value="<?php echo date('d-m-Y'); ?>" required>
					</div>
					<div class="form-group">
						<label class="form-label"><strong>Remarks :</strong></label>
						<textarea class="form-control text-input" id="remarks" name="remarks" rows="5"></textarea>
					</div>
					<input type="hidden" name="leaking_id" id="leaking_id" value="<?php echo (int)$leaking_id; ?>"/>
					<input type="hidden" name="total_billing_amount" id="total_billing_amount" value="<?php echo htmlspecialchars($bill_amount); ?>"/>
					<input type="hidden" name="balance_amount" id="balance_amount" value="<?php echo htmlspecialchars($balance_amount); ?>"/>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-sm btn-primary" id="btn_save" name="btn_save" value="add">Save</button>
			</div>
		</div>
	</div>
</div>

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include('footer.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
<script type="text/javascript">
$(document).ready(function() {
	if ($.fn.datepicker) {
		$('#transdate').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true,
			todayHighlight: true,
			orientation: 'bottom auto'
		});
	}

	$('.text-input').on('focus', function() {
		$(this).select();
	});

	$('#add_payment').on('click', function(evt) {
		evt.preventDefault();
		$('#myModal').modal('show');
		$('#myModalLabel').text('Add New Payment');
		$('#remarks').val('');
		$('#refno').val('');
		$('#amount_pay').val('0.00');
		$('#btn_save').text('Save');
		$('#btn_save').val('add');
		$('#btn_save').prop('disabled', false);
	});

	$('#btn_save').on('click', function(evt) {
		evt.preventDefault();
		var leaking_id = $('#leaking_id').val();
		var formData = new FormData();
		formData.append('refno', $('#refno').val());
		formData.append('leaking_id', leaking_id);
		formData.append('transdate', $('#transdate').val());
		formData.append('prev_balance', $('#prev_balance').val());
		formData.append('amount_pay', $('#amount_pay').val());
		formData.append('source_type', $('#source_type').val());
		formData.append('balance_amount', $('#balance_amount').val());
		formData.append('total_billing_amount', $('#total_billing_amount').val());
		formData.append('remarks', $('#remarks').val());
		formData.append('btn_save', $('#btn_save').val());

		$.ajax({
			url: '<?php echo ADMIN_URL; ?>Leakingentrycorrection/add_payment/',
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			beforeSend: function() {
				if (typeof showSpinner === 'function') { showSpinner(); }
			},
			success: function(response) {
				if (response == 'success') {
					window.location = '<?php echo ADMIN_URL; ?>Leakingentrycorrection/ledger/' + leaking_id;
				} else {
					alert('Saving payment failed.');
				}
			},
			error: function() {
				alert('An error occurred while processing data.');
			}
		});
	});

	$(document).on('click', '#print_statement', function(e) {
		e.preventDefault();
		var leaking_id = <?php echo (int)$leaking_id; ?>;
		var url = '<?php echo ADMIN_URL; ?>Leakingentrycorrection/soa_statement/' + leaking_id;
		window.open(url, 'popupWindowSOA', 'width=1024,height=600,scrollbars=yes');
	});
});
</script>
</body>
</html>
