<?php
	$sa4_loading_label = 'Database Backup';
	$sa4_dt_entity = 'backups';
	$sa4_panel_id = 'panel-database-backup';
	$sa4_dt_export_cols = array(0, 1, 2, 3, 4);

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

	$backups = (isset($backups) && is_array($backups)) ? $backups : array();
	$roleResponsible = array();
	if (isset($header['roleResponsible']['database_backup']) && is_array($header['roleResponsible']['database_backup'])) {
		$roleResponsible = $header['roleResponsible']['database_backup'];
	}
	$is_subadmin = ($this->session->userdata('usertype') === 'subadmin');
	$can_list = !$is_subadmin || in_array('l', $roleResponsible, true);
	$can_add = !$is_subadmin || in_array('a', $roleResponsible, true);
	$can_delete = !$is_subadmin || in_array('d', $roleResponsible, true);
	$upload_max_label = isset($upload_max_label) ? $upload_max_label : '';
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>database_backup/">Database Backup</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-database"></i>
			Manage <span class="fw-300">Database Backup</span>
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
		<strong>Error!</strong> <?php echo $this->session->flashdata('msg_err'); ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-database-backup" class="panel">
				<div class="panel-hdr">
					<h2>Database Backup <span class="fw-300"><i>Listing</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<div class="row mb-3 align-items-end">
							<div class="col-sm-12 text-right">
								<?php if ($can_add) { ?>
								<button type="button" class="btn btn-primary btn-sm waves-effect waves-themed" data-toggle="modal" data-target="#uploadBackupModal">
									<i class="fal fa-upload mr-1"></i> Upload Backup
								</button>
								<a href="<?php echo ADMIN_URL; ?>database_backup/create" class="btn btn-success btn-sm waves-effect waves-themed">
									<i class="fal fa-plus mr-1"></i> Create Backup
								</a>
								<?php } ?>
							</div>
						</div>

						<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
							<thead>
								<tr>
									<th style="width:60px;">S No</th>
									<th>Filename</th>
									<th>File Size</th>
									<th>Created By</th>
									<th>Created At</th>
									<th style="width:140px;">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (count($backups) > 0) {
									$i = 1;
									foreach ($backups as $row) {
										$file_size = $this->my_model->format_file_size($row['filesize']);
										$row_id = (int) $row['id'];
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo htmlspecialchars($row['filename']); ?></td>
									<td><?php echo htmlspecialchars($file_size); ?></td>
									<td><?php echo htmlspecialchars($row['created_by']); ?></td>
									<td><?php echo date('Y-m-d H:i:s', strtotime($row['created_at'])); ?></td>
									<td>
										<div class="btn-group btn-group-sm" role="group">
											<?php if ($can_list) { ?>
											<a href="<?php echo ADMIN_URL; ?>database_backup/download/<?php echo $row_id; ?>" class="btn btn-outline-primary" title="Download" data-toggle="tooltip">
												<i class="fal fa-download"></i>
											</a>
											<?php } ?>
											<?php if ($can_add) { ?>
											<a href="javascript:void(0);" class="btn btn-outline-warning btn-restore-backup" title="Restore" data-toggle="tooltip"
												data-url="<?php echo ADMIN_URL; ?>database_backup/restore/<?php echo $row_id; ?>">
												<i class="fal fa-undo"></i>
											</a>
											<?php } ?>
											<?php if ($can_delete) { ?>
											<a href="javascript:void(0);" class="btn btn-outline-danger btn-delete-backup" title="Delete" data-toggle="tooltip"
												data-url="<?php echo ADMIN_URL; ?>database_backup/delete/<?php echo $row_id; ?>">
												<i class="fal fa-times"></i>
											</a>
											<?php } ?>
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
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php if ($can_add) { ?>
<div class="modal fade" id="uploadBackupModal" tabindex="-1" role="dialog" aria-labelledby="uploadBackupModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form action="<?php echo ADMIN_URL; ?>database_backup/upload_backup" method="post" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="uploadBackupModalLabel">Upload Backup</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"><i class="fal fa-times"></i></span>
					</button>
				</div>
				<div class="modal-body">
					<p class="text-muted mb-3">Upload a previously downloaded <code>.sql</code> dump. This only stores the file in the backup list; restore is a separate action.</p>
					<div class="form-group">
						<label class="form-label" for="backup_file">SQL backup file</label>
						<input type="file" class="form-control-file" id="backup_file" name="backup_file" accept=".sql,application/sql,text/plain" required>
					</div>
					<?php if ($upload_max_label !== '') { ?>
					<small class="form-text text-muted">Maximum upload size: <?php echo htmlspecialchars($upload_max_label); ?>.</small>
					<?php } ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">
						<i class="fal fa-upload mr-1"></i> Upload
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php } ?>

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include('footer.php'); ?>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
<script type="text/javascript">
$(document).ready(function() {
	$(document).on('click', '.btn-restore-backup', function(e) {
		e.preventDefault();
		var url = $(this).data('url');
		var go = function() { window.location = url; };
		if (typeof Swal !== 'undefined') {
			Swal.fire({
				title: 'Restore backup?',
				text: 'This will overwrite the current database!',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#fd7e14',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Yes, restore it',
				cancelButtonText: 'Cancel',
				reverseButtons: true
			}).then(function(result) {
				if ((typeof sa4SwalConfirmed === 'function' && sa4SwalConfirmed(result)) || result.isConfirmed || result.value) { go(); }
			});
		} else if (confirm('Are you sure you want to restore this backup? This will overwrite the current database!')) {
			go();
		}
	});

	$(document).on('click', '.btn-delete-backup', function(e) {
		e.preventDefault();
		var url = $(this).data('url');
		var go = function() { window.location = url; };
		if (typeof Swal !== 'undefined') {
			Swal.fire({
				title: 'Delete backup?',
				text: 'This backup file will be permanently removed.',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Yes, delete it',
				cancelButtonText: 'Cancel',
				reverseButtons: true
			}).then(function(result) {
				if ((typeof sa4SwalConfirmed === 'function' && sa4SwalConfirmed(result)) || result.isConfirmed || result.value) { go(); }
			});
		} else if (confirm('Are you sure you want to delete this backup?')) {
			go();
		}
	});
});
</script>
</body>
</html>
