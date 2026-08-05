<?php
	$sa4_loading_label = 'Leaking Entry Correction';
	$sa4_dt_entity = 'leaking records';
	$sa4_panel_id = 'panel-leakingentrycorrection';
	$sa4_dt_export_cols = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);

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

	$records = (isset($record) && is_array($record)) ? $record : array();
?>
<style>
	.setStatus { cursor: pointer; }
</style>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
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
			Manage <span class="fw-300">Leaking Entry Correction</span>
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

	<?php if ($this->session->flashdata('msg_succ')) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<strong>Success!</strong> <?php echo $this->session->flashdata('msg_succ'); ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-leakingentrycorrection" class="panel">
				<div class="panel-hdr">
					<h2>Leaking Entry Correction <span class="fw-300"><i>Listing</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form method="post" action="<?php echo ADMIN_URL; ?>Leakingentrycorrection/multi_delete" id="sa4-list-form">
							<div class="row mb-3 align-items-end">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
										<i class="fal fa-trash-alt mr-1"></i> Delete Selected
									</button>
								</div>
							</div>

							<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
								<thead>
									<tr>
										<th style="width:28px;"><input type="checkbox" id="dt_select_all" class="ace" /></th>
										<th style="width:60px;">S No</th>
										<th>Customer Name</th>
										<th>Customer ID</th>
										<th>Billing No.</th>
										<th>Billing Period</th>
										<th>Billing Amount</th>
										<th>Discount %</th>
										<th>Discount Amount</th>
										<th>Total Amount</th>
										<th>Balance Amount</th>
										<th>Payment Date</th>
										<th>Status</th>
										<th style="width:120px;">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (count($records) > 0) {
										$i = 1;
										foreach ($records as $row) {
											$total_payment = $this->my_model->get_total_payment($row['leaking_id'])['totalpayment'];
											$leaking_balance = $row['leaking_total_amount'] - $total_payment;
											$status = isset($row['leaking_status']) ? (int) $row['leaking_status'] : 0;
											$month_row = getMonthName($row['month']);
											$month_name = (is_array($month_row) && isset($month_row[0]->month_name)) ? $month_row[0]->month_name : $row['month'];
									?>
									<tr>
										<td><input type="checkbox" class="ace" name="delete_ids[]" value="<?php echo (int) $row['leaking_id']; ?>" /></td>
										<td><?php echo $i; ?></td>
										<td><?php echo htmlspecialchars(stripslashes($row['last_name'].', '.$row['first_name'].' '.$row['middle_name'])); ?></td>
										<td><?php echo htmlspecialchars(stripslashes($row['customer_id'])); ?></td>
										<td><?php echo htmlspecialchars(stripslashes($row['leaking_refno'])); ?></td>
										<td><?php echo htmlspecialchars(stripslashes($month_name.' '.$row['year'])); ?></td>
										<td class="text-right"><?php echo number_format((float) $row['leaking_bill_amount'], 2); ?></td>
										<td class="text-right"><?php echo htmlspecialchars(stripslashes($row['leaking_discount_percent'])); ?></td>
										<td class="text-right"><?php echo number_format((float) $row['leaking_discount_amount'], 2); ?></td>
										<td class="text-right"><?php echo number_format((float) $row['leaking_total_amount'], 2); ?></td>
										<td class="text-right"><?php echo number_format((float) $leaking_balance, 2); ?></td>
										<td><?php echo date('M j, Y', strtotime($row['leaking_date'])); ?></td>
										<td>
											<?php if ($status === 1) { ?>
											<span class="badge badge-danger setStatus" data-id="<?php echo (int) $row['leaking_id']; ?>" data-status="<?php echo $status; ?>">Pending</span>
											<?php } elseif ($status === 2) { ?>
											<span class="badge badge-info">Approved</span>
											<?php } elseif ($status === 4) { ?>
											<span class="badge badge-primary">Posted</span>
											<?php } elseif ($status === 5) { ?>
											<span class="badge badge-success">Full Paid</span>
											<?php } else { ?>
											<span class="badge badge-secondary">Denied</span>
											<?php } ?>
										</td>
										<td>
											<div class="btn-group btn-group-sm" role="group">
												<a href="javascript:void(0);" class="btn btn-outline-danger btn_delete" title="Delete" data-toggle="tooltip" data-leaking_id="<?php echo (int) $row['leaking_id']; ?>">
													<i class="fal fa-times"></i>
												</a>
												<a href="<?php echo ADMIN_URL; ?>Leakingentrycorrection/ledger/<?php echo (int) $row['leaking_id']; ?>" class="btn btn-outline-primary" title="Ledger" data-toggle="tooltip">
													<i class="fal fa-eye"></i>
												</a>
											</div>
										</td>
									</tr>
									<?php
											$i++;
										}
									}
									?>
								</tbody>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include('footer.php'); ?>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
<script type="text/javascript">
$(document).ready(function() {
	$(document).on('click', '.btn_delete', function(e) {
		e.preventDefault();
		var leaking_id = $(this).data('leaking_id');
		var doDelete = function() {
			if (typeof Swal !== 'undefined') {
				Swal.fire({
					title: 'Deleting...',
					text: 'Please wait while we delete the record.',
					allowOutsideClick: false,
					allowEscapeKey: false,
					showConfirmButton: false,
					didOpen: function() { Swal.showLoading(); }
				});
			}
			$.ajax({
				url: '<?php echo ADMIN_URL; ?>Leakingentrycorrection/delete/' + leaking_id,
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					if (response && response.status === 'success') {
						if (typeof Swal !== 'undefined') {
							Swal.fire({
								title: 'Deleted!',
								text: response.message,
								icon: 'success',
								timer: 2000,
								timerProgressBar: true
							}).then(function() { window.location.reload(); });
						} else {
							alert(response.message || 'Deleted');
							window.location.reload();
						}
					} else {
						var msg = (response && response.message) ? response.message : 'Failed to delete the record.';
						if (typeof Swal !== 'undefined') {
							Swal.fire({ title: 'Error!', text: msg, icon: 'error' });
						} else {
							alert(msg);
						}
					}
				},
				error: function() {
					if (typeof Swal !== 'undefined') {
						Swal.fire({ title: 'Error!', text: 'An error occurred while deleting the record.', icon: 'error' });
					} else {
						alert('An error occurred while deleting the record.');
					}
				}
			});
		};

		if (typeof Swal !== 'undefined') {
			Swal.fire({
				title: 'Are you sure?',
				text: 'This will delete the leaking entry and all associated ledger details.',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Yes, delete it!',
				cancelButtonText: 'Cancel',
				reverseButtons: true
			}).then(function(result) {
				if ((typeof sa4SwalConfirmed === 'function' && sa4SwalConfirmed(result)) || result.isConfirmed || result.value) { doDelete(); }
			});
		} else if (confirm('Confirm Delete?')) {
			doDelete();
		}
	});

	$(document).on('click', '.setStatus', function(e) {
		e.preventDefault();
		var getStatus = $(this).data('status');
		var id = $(this).data('id');
		if (String(getStatus) !== '1') { return; }

		if (typeof Swal !== 'undefined') {
			Swal.fire({
				title: 'Approval Action',
				text: 'Approve or deny this pending leaking entry?',
				icon: 'question',
				showDenyButton: true,
				showCancelButton: true,
				confirmButtonText: 'Approved',
				denyButtonText: 'Denied',
				cancelButtonText: 'Cancel'
			}).then(function(result) {
				if ((typeof sa4SwalConfirmed === 'function' && sa4SwalConfirmed(result)) || result.isConfirmed || result.value) {
					window.location = '<?php echo ADMIN_URL; ?>Leakingentrycorrection/status/' + id + '/2';
				} else if (result.isDenied || result.value === false) {
					window.location = '<?php echo ADMIN_URL; ?>Leakingentrycorrection/status/' + id + '/3';
				}
			});
		} else if (typeof $.SmartMessageBox === 'function') {
			$.SmartMessageBox({
				title: 'Approval Action',
				content: 'Please select option below',
				buttons: '[Cancel][Denied][Approved]'
			}, function(ButtonPressed) {
				if (ButtonPressed === 'Approved') {
					window.location = '<?php echo ADMIN_URL; ?>Leakingentrycorrection/status/' + id + '/2';
				}
				if (ButtonPressed === 'Denied') {
					window.location = '<?php echo ADMIN_URL; ?>Leakingentrycorrection/status/' + id + '/3';
				}
			});
		}
	});
});
</script>
</body>
</html>
