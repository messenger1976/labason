<?php
	$sa4_loading_label = 'System Activity';
	$sa4_dt_entity = 'activities';
	$sa4_panel_id = 'panel-system-activity';
	$sa4_dt_export_cols = array(0, 1, 2, 3, 4, 5, 6, 7, 8);

	$this->load->model('common_model', 'kpi_model');
	$income1 = $this->kpi_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->kpi_model->get_income_monthlycustomer();
	extract($income2);
	$intotal = (isset($total1) ? (float) $total1 : 0) + (isset($total2) ? (float) $total2 : 0);

	$expense1 = $this->kpi_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->kpi_model->get_outcome_payroll();
	extract($expense2);
	$extotal = (isset($extotal1) ? (float) $extotal1 : 0) + (isset($extotal2) ? (float) $extotal2 : 0);

	$total_customer = $this->kpi_model->total_customer();
	extract($total_customer);

	$records = (isset($records) && is_array($records)) ? $records : array();
	$filters = (isset($filters) && is_array($filters)) ? $filters : array();
	$categories = (isset($categories) && is_array($categories)) ? $categories : array();
	$modules = (isset($modules) && is_array($modules)) ? $modules : array();
	$users = (isset($users) && is_array($users)) ? $users : array();

	$query_parts = array();
	foreach ($filters as $fk => $fv) {
		if ($fv !== '' && $fv !== null) {
			$query_parts[$fk] = $fv;
		}
	}
	$query = http_build_query($query_parts);
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>system_activity/">System Activity</a></li>
		<li class="breadcrumb-item active">Audit Trail</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-history"></i>
			System <span class="fw-300">Activity</span>
			<small>Audit trail of logins, page views, and accounting transactions</small>
		</h1>
		<div class="subheader-block d-lg-flex align-items-center">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>INCOME</small></span>
				<span class="fw-500 fs-xl d-block color-primary-500">₱ <?php echo number_format($intotal, 2); ?></span>
			</div>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>EXPENSE</small></span>
				<span class="fw-500 fs-xl d-block color-danger-500">₱ <?php echo number_format($extotal, 2); ?></span>
			</div>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>TOTAL CUSTOMER</small></span>
				<span class="fw-500 fs-xl d-block color-success-500"><?php echo (int) (isset($count_id) ? $count_id : 0); ?></span>
			</div>
		</div>
	</div>

	<?php if ($this->session->flashdata('msg_succ')) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fal fa-times"></i></span></button>
		<strong>Success!</strong> <?php echo $this->session->flashdata('msg_succ'); ?>
	</div>
	<?php } ?>
	<?php if ($this->session->flashdata('msg_err')) { ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fal fa-times"></i></span></button>
		<strong>Error!</strong> <?php echo $this->session->flashdata('msg_err'); ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-system-activity" class="panel">
				<div class="panel-hdr">
					<h2>Activity <span class="fw-300"><i>Log</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form method="get" action="<?php echo ADMIN_URL; ?>system_activity/" class="mb-3">
							<div class="form-row">
								<div class="col-md-2 mb-2">
									<label class="form-label">From</label>
									<input type="date" name="date_from" class="form-control" value="<?php echo htmlspecialchars(isset($filters['date_from']) ? $filters['date_from'] : ''); ?>">
								</div>
								<div class="col-md-2 mb-2">
									<label class="form-label">To</label>
									<input type="date" name="date_to" class="form-control" value="<?php echo htmlspecialchars(isset($filters['date_to']) ? $filters['date_to'] : ''); ?>">
								</div>
								<div class="col-md-2 mb-2">
									<label class="form-label">Category</label>
									<select name="category" class="form-control">
										<option value="">All</option>
										<?php foreach ($categories as $cat) { ?>
										<option value="<?php echo htmlspecialchars($cat); ?>" <?php echo (isset($filters['category']) && $filters['category'] === $cat) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-2 mb-2">
									<label class="form-label">Module</label>
									<select name="module" class="form-control">
										<option value="">All</option>
										<?php foreach ($modules as $mod) { ?>
										<option value="<?php echo htmlspecialchars($mod); ?>" <?php echo (isset($filters['module']) && $filters['module'] === $mod) ? 'selected' : ''; ?>><?php echo htmlspecialchars($mod); ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-2 mb-2">
									<label class="form-label">User</label>
									<select name="user_id" class="form-control">
										<option value="">All</option>
										<?php foreach ($users as $u) { ?>
										<option value="<?php echo (int) $u['user_id']; ?>" <?php echo (isset($filters['user_id']) && (string) $filters['user_id'] === (string) $u['user_id']) ? 'selected' : ''; ?>>
											<?php echo htmlspecialchars($u['user_name'] !== '' ? $u['user_name'] : $u['username']); ?>
										</option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-2 mb-2">
									<label class="form-label">Reference</label>
									<input type="text" name="reference_no" class="form-control" placeholder="OR / Adj #" value="<?php echo htmlspecialchars(isset($filters['reference_no']) ? $filters['reference_no'] : ''); ?>">
								</div>
								<div class="col-md-2 mb-2">
									<label class="form-label">Action</label>
									<input type="text" name="action" class="form-control" value="<?php echo htmlspecialchars(isset($filters['action']) ? $filters['action'] : ''); ?>">
								</div>
								<div class="col-md-2 mb-2">
									<label class="form-label">IP</label>
									<input type="text" name="ip_address" class="form-control" value="<?php echo htmlspecialchars(isset($filters['ip_address']) ? $filters['ip_address'] : ''); ?>">
								</div>
								<div class="col-md-4 mb-2">
									<label class="form-label">Search</label>
									<input type="text" name="q" class="form-control" placeholder="Summary, user, URI…" value="<?php echo htmlspecialchars(isset($filters['q']) ? $filters['q'] : ''); ?>">
								</div>
								<div class="col-md-4 mb-2 d-flex align-items-end">
									<button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fal fa-filter mr-1"></i> Filter</button>
									<a href="<?php echo ADMIN_URL; ?>system_activity/" class="btn btn-secondary btn-sm mr-2">Reset</a>
									<a href="<?php echo ADMIN_URL; ?>system_activity/export?<?php echo htmlspecialchars($query); ?>" class="btn btn-success btn-sm"><i class="fal fa-file-excel mr-1"></i> CSV</a>
								</div>
							</div>
						</form>

						<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
							<thead>
								<tr>
									<th style="width:50px;">ID</th>
									<th>When</th>
									<th>User</th>
									<th>Category</th>
									<th>Action</th>
									<th>Module</th>
									<th>Reference</th>
									<th>Amount</th>
									<th>IP</th>
									<th>Summary</th>
									<th style="width:70px;">View</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (count($records) > 0) {
									foreach ($records as $row) {
										$row_id = (int) $row['id'];
										$amount = $row['amount'];
								?>
								<tr>
									<td><?php echo $row_id; ?></td>
									<td><?php echo htmlspecialchars($row['created_at']); ?></td>
									<td>
										<?php echo htmlspecialchars($row['user_name'] !== '' ? $row['user_name'] : $row['username']); ?>
										<br><small class="text-muted"><?php echo htmlspecialchars($row['usertype']); ?></small>
									</td>
									<td><span class="badge border border-primary text-primary"><?php echo htmlspecialchars($row['category']); ?></span></td>
									<td><?php echo htmlspecialchars($row['action']); ?></td>
									<td><?php echo htmlspecialchars($row['module']); ?></td>
									<td><?php echo htmlspecialchars($row['reference_no']); ?></td>
									<td><?php echo ($amount !== null && $amount !== '') ? number_format((float) $amount, 2) : ''; ?></td>
									<td><?php echo htmlspecialchars($row['ip_address']); ?></td>
									<td><?php echo htmlspecialchars($row['summary']); ?></td>
									<td>
										<a class="btn btn-outline-primary btn-sm" href="<?php echo ADMIN_URL; ?>system_activity/view/<?php echo $row_id; ?>" title="Detail" data-toggle="tooltip">
											<i class="fal fa-eye"></i>
										</a>
									</td>
								</tr>
								<?php
									}
								}
								?>
							</tbody>
						</table>
						<p class="text-muted mt-2 mb-0"><small>Showing up to 500 newest matching rows. Use filters or CSV export for auditor extracts (up to 5,000).</small></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include('footer.php'); ?>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
