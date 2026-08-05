<?php
	$sa4_loading_label = 'Technical Problems';
	$sa4_dt_entity = 'technical problems';
	$sa4_panel_id = 'panel-technicalproblems';

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
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>technicalproblems">Technical Problems</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-tools"></i>
			Manage <span class="fw-300">Technical Problems</span>
		</h1>
		<div class="subheader-block d-lg-flex align-items-center">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>INCOME</small>
				</span>
				<span class="fw-500 fs-xl d-block color-primary-500">
					₱ <?php echo number_format($intotal, 2); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#886ab5" sparkHeight="32px" sparkBarWidth="5px" values="3,4,3,6,7,3,3,6,2,6,4"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>EXPENSE</small>
				</span>
				<span class="fw-500 fs-xl d-block color-danger-500">
					₱ <?php echo number_format($extotal, 2); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#fe6bb0" sparkHeight="32px" sparkBarWidth="5px" values="1,4,3,6,5,3,9,6,5,9,7"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>TOTAL CUSTOMER</small>
				</span>
				<span class="fw-500 fs-xl d-block color-success-500">
					<?php echo (int) (isset($count_id) ? $count_id : 0); ?>
				</span>
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
			<div id="panel-technicalproblems" class="panel">
				<div class="panel-hdr">
					<h2>Technical Problems <span class="fw-300"><i>Listing</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form method="post" action="<?php echo ADMIN_URL; ?>technicalproblems/multi_delete" id="sa4-list-form">
							<div class="row mb-3 align-items-end">
								<div class="col-sm-6 col-md-6">
									<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
										<i class="fal fa-trash-alt mr-1"></i> Delete Selected
									</button>
								</div>
								<div class="col-sm-6 col-md-6 text-right">
									<a href="<?php echo ADMIN_URL; ?>technicalproblems/add/" class="btn btn-success btn-sm waves-effect waves-themed">
										<i class="fal fa-plus mr-1"></i> Add
									</a>
								</div>
							</div>

							<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
								<thead class="bg-primary-600">
									<tr>
										<th style="width:30px;"><input type="checkbox" id="dt_select_all" /></th>
										<th>S No</th>
										<th>Customer ID</th>
										<th>Name</th>
										<th>Address</th>
										<th>Meter #</th>
										<th>Problems Summary</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if (count($records) > 0) {
										$i = 1;
										foreach ($records as $row) {
											$status = isset($row['status']) ? (int) $row['status'] : 0;
											switch ($status) {
												case 1:
													$status_label = 'Assigned';
													$status_class = 'badge-warning';
													break;
												case 2:
													$status_label = 'On Going';
													$status_class = 'badge-info';
													break;
												case 3:
													$status_label = 'Resolved';
													$status_class = 'badge-success';
													break;
												case 4:
													$status_label = 'Un-Resolved';
													$status_class = 'badge-danger';
													break;
												case 5:
													$status_label = 'Resolved - Closed';
													$status_class = 'badge-primary';
													break;
												case 6:
													$status_label = 'Un-Resolved - Closed';
													$status_class = 'badge-secondary';
													break;
												default:
													$status_label = 'Pending';
													$status_class = 'badge-danger';
													break;
											}
											$name = trim($row['lastname'].', '.$row['firstname'].' '.$row['middlename']);
									?>
									<tr>
										<td>
											<input type="checkbox" class="checkbox" name="delete_ids[]" value="<?php echo $row['id']; ?>" />
										</td>
										<td><?php echo $i; ?></td>
										<td><?php echo htmlspecialchars(stripslashes($row['customer_id'])); ?></td>
										<td><?php echo htmlspecialchars(stripslashes($name)); ?></td>
										<td><?php echo htmlspecialchars(stripslashes($row['address'])); ?></td>
										<td><?php echo htmlspecialchars(stripslashes($row['meter_number'])); ?></td>
										<td><?php echo htmlspecialchars(stripslashes(str_replace('\n', '', $row['problem_summary']))); ?></td>
										<td><span class="badge <?php echo $status_class; ?>"><?php echo $status_label; ?></span></td>
										<td>
											<div class="btn-group btn-group-sm" role="group">
												<a class="btn btn-outline-success" href="<?php echo ADMIN_URL; ?>technicalproblems/edit/<?php echo $row['id']; ?>" title="Edit" data-toggle="tooltip">
													<i class="fal fa-edit"></i>
												</a>
												<a class="btn btn-outline-danger" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL; ?>technicalproblems/delete/<?php echo $row['id']; ?>';}" title="Delete" data-toggle="tooltip">
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
</body>
</html>
