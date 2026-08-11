<?php
	$income1 = $this->comm_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->comm_model->get_income_monthlycustomer();
	extract($income2);
	$intotal = (isset($total1) ? (float) $total1 : 0) + (isset($total2) ? (float) $total2 : 0);

	$expense1 = $this->comm_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->comm_model->get_outcome_payroll();
	extract($expense2);
	$extotal = (isset($extotal1) ? (float) $extotal1 : 0) + (isset($extotal2) ? (float) $extotal2 : 0);

	$total_customer = $this->comm_model->total_customer();
	extract($total_customer);

	$rows = (isset($rows) && is_array($rows)) ? $rows : array();
	$can_edit = !empty($can_edit);
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>manual_or_series">Manual OR Series</a></li>
		<li class="breadcrumb-item active">List</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-list-ol"></i>
			Manage <span class="fw-300">Manual OR Series</span>
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

	<?php if ($this->session->flashdata('msg_err')) { ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<?php echo $this->session->flashdata('msg_err'); ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>OR Document <span class="fw-300"><i>Series</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<p class="text-muted mb-3">
							Set the <strong>last used</strong> OR/SI number per series. The <strong>next</strong> receipt will be that value <strong>plus one</strong>.
							Coordinate with physical receipt books and avoid changing numbers while tellers are posting.
						</p>

						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped w-100">
								<thead class="bg-primary-600">
									<tr>
										<th>Series</th>
										<th>Teller (Employee Logins)</th>
										<th>Last used OR #</th>
										<th>Next OR (preview)</th>
										<th style="width:220px;">Update</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($rows)) {
										foreach ($rows as $row) {
											$last = isset($row['doc_series_num']) ? (int) $row['doc_series_num'] : 0;
											$next = $last + 1;
											if (!empty($row['teller_user_id'])) {
												$label = 'Teller #' . (int) $row['teller_user_id'];
												$who = trim(
													(isset($row['employee_name']) ? $row['employee_name'] : '') .
													(isset($row['username']) ? ' ('.$row['username'].')' : '')
												);
											} else {
												$label = 'Legacy / shared (doc_id ' . (int) $row['doc_id'] . ')';
												$who = '—';
											}
									?>
									<tr>
										<td><?php echo htmlspecialchars($label); ?></td>
										<td><?php echo $who !== '' ? htmlspecialchars($who) : '—'; ?></td>
										<td><?php echo sprintf('%07d', $last); ?></td>
										<td><strong><?php echo sprintf('%07d', $next); ?></strong></td>
										<td>
											<?php if ($can_edit) { ?>
											<form method="post" action="" class="form-inline d-inline-flex align-items-center" onsubmit="return confirm('Update last used OR for this series?');">
												<input type="hidden" name="doc_id" value="<?php echo (int) $row['doc_id']; ?>">
												<input type="number" name="doc_series_num" class="form-control form-control-sm mr-2" min="0" step="1" value="<?php echo $last; ?>" style="width:120px;" required>
												<button type="submit" name="save_series" value="1" class="btn btn-primary btn-sm">
													<i class="fal fa-save mr-1"></i> Save
												</button>
											</form>
											<?php } else { ?>
											<span class="text-muted">View only</span>
											<?php } ?>
										</td>
									</tr>
									<?php
										}
									} else { ?>
									<tr>
										<td colspan="5" class="text-center py-3">No OR rows found (expected doc_name = OR in tbl_doc_series_number).</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php include('footer.php'); ?>
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
});
</script>
