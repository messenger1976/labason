<?php
	$sa4_loading_label = 'Responsibilities';
	$sa4_dt_entity = 'roles';
	$sa4_panel_id = 'panel-responsibilities';
	$sa4_dt_export_cols = array(0, 1, 2);

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
	$seg3 = $this->uri->segment(3);
	$seg4 = $this->uri->segment(4);
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>responsibilities/">Roles &amp; Responsibilities</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user-shield"></i>
			Manage <span class="fw-300">Roles &amp; Responsibilities</span>
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
			<div id="panel-responsibilities" class="panel">
				<div class="panel-hdr">
					<h2>Roles &amp; Responsibilities <span class="fw-300"><i>Listing</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<div class="row mb-3 align-items-end">
							<div class="col-sm-12 text-right">
								<a href="<?php echo ADMIN_URL; ?>responsibilities/add/" class="btn btn-success btn-sm waves-effect waves-themed">
									<i class="fal fa-plus mr-1"></i> Add
								</a>
							</div>
						</div>

						<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
							<thead class="bg-primary-600">
								<tr>
									<th style="width:70px;">S No</th>
									<th>Role Name</th>
									<th style="width:120px;">Status</th>
									<th style="width:140px;">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php if (count($records) > 0) {
									$i = 1;
									foreach ($records as $row) {
										$status = isset($row['status']) ? (int) $row['status'] : 0;
										$status_url = ADMIN_URL.'responsibilities/status/'.$row['id'].'/'.$status.'/'.$seg3.'/'.$seg4;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo htmlspecialchars(stripslashes($row['role_name'])); ?></td>
									<td>
										<?php if ($status === 1) { ?>
										<a href="JavaScript:if(confirm('Are you sure want to Change the Status?')==true){window.location='<?php echo $status_url; ?>';}" class="badge badge-success" style="color:#fff;text-decoration:none;">Active</a>
										<?php } elseif ($status === 0) { ?>
										<a href="JavaScript:if(confirm('Are you sure want to Change the Status?')==true){window.location='<?php echo $status_url; ?>';}" class="badge badge-danger" style="color:#fff;text-decoration:none;">De-Active</a>
										<?php } else { ?>
										<span class="badge badge-warning">In-complete</span>
										<?php } ?>
									</td>
									<td>
										<div class="btn-group btn-group-sm" role="group">
											<a class="btn btn-outline-info" href="<?php echo ADMIN_URL; ?>responsibilities/view/<?php echo $row['id']; ?>" title="View" data-toggle="tooltip">
												<i class="fal fa-eye"></i>
											</a>
											<a class="btn btn-outline-success" href="<?php echo ADMIN_URL; ?>responsibilities/edit/<?php echo $row['id']; ?>" title="Edit" data-toggle="tooltip">
												<i class="fal fa-edit"></i>
											</a>
											<a class="btn btn-outline-danger" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL; ?>responsibilities/delete/<?php echo $row['id']; ?>';}" title="Delete" data-toggle="tooltip">
												<i class="fal fa-times"></i>
											</a>
										</div>
									</td>
								</tr>
								<?php
										$i++;
									}
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include('footer.php'); ?>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
</body>
</html>
