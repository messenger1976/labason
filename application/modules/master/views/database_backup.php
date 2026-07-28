<?php
	$sa4_page_icon = 'fal fa-th-list';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Database Backup';
	$sa4_loading_label = 'Database Backup';
	$sa4_dt_entity = 'database backup';
	$sa4_panel_id = 'panel-database-backup';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo base_url();?>index.php/master/dashboard">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo base_url();?>index.php/master/database_backup/">Database Backup</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
<?php include(__DIR__ . '/partials/sa4_kpi_subheader.php'); ?>

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


									<div class="col-sm-6 col-md-6 text-right">
										<a href="<?php echo base_url();?>index.php/master/database_backup/create" class="btn btn-success btn-sm waves-effect waves-themed">
											<i class="fal fa-plus mr-1"></i> Create Backup
										</a>
									</div>
								</div>
								<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
										
											<thead>			                
												<tr>
													<th>SNo</th>
													<th>Filename</th>
													<th>File Size</th>
													<th>Created By</th>
													<th>Created At</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
											  <?php
													if(count($backups) > 0){
														$i=1;
														foreach($backups as $key => $row){ 
															$file_size = $this->my_model->format_file_size($row['filesize']);
												?>   
												<tr>
													<td><?php echo $i;?></td>
													<td><?php echo $row['filename'];?></td>
													<td><?php echo $file_size;?></td>
													<td><?php echo $row['created_by'];?></td>
													<td><?php echo date('Y-m-d H:i:s', strtotime($row['created_at']));?></td>
													<td>
													    <div class=" action-buttons">
															<?php 
															$header_data = isset($data['header']) ? $data['header'] : array();
															$roleResponsible = isset($header_data['roleResponsible']['database_backup']) ? $header_data['roleResponsible']['database_backup'] : array();
															if($this->session->userdata('usertype') != 'subadmin' || (is_array($roleResponsible) && in_array('l', $roleResponsible))): ?>
																<a class="blue" href="<?php echo base_url();?>index.php/master/database_backup/download/<?php echo $row['id']; ?>" title="Download">
																	<i class="fal fa-download"></i>
																</a>
															<?php endif; ?>
															
															<?php 
															if($this->session->userdata('usertype') != 'subadmin' || (is_array($roleResponsible) && in_array('a', $roleResponsible))): ?>
																<a class="green" href="<?php echo base_url();?>index.php/master/database_backup/restore/<?php echo $row['id']; ?>" title="Restore" onclick="return confirm('Are you sure you want to restore this backup? This will overwrite the current database!');">
																	<i class="fal fa-undo"></i>
																</a>
															<?php endif; ?>
															
															<?php 
															if($this->session->userdata('usertype') != 'subadmin' || (is_array($roleResponsible) && in_array('d', $roleResponsible))): ?>
																<a class="red" href="<?php echo base_url();?>index.php/master/database_backup/delete/<?php echo $row['id']; ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this backup?');">
																	<i class="fal fa-trash"></i>
																</a>
															<?php endif; ?>
														</div>
														<div class="">
															<div class="inline position-relative">
																<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
																	<i class="fal fa-caret-down icon-only bigger-120"></i>
																</button>
																	
																<ul class="dropdown-menu dropdown-only-icon dropdown-yellow float-right dropdown-caret dropdown-close">
																	<?php 
																	$header_data = isset($data['header']) ? $data['header'] : array();
																	$roleResponsible = isset($header_data['roleResponsible']['database_backup']) ? $header_data['roleResponsible']['database_backup'] : array();
																	if($this->session->userdata('usertype') != 'subadmin' || (is_array($roleResponsible) && in_array('l', $roleResponsible))): ?>
																		<li>
																			<a href="<?php echo base_url();?>index.php/master/database_backup/download/<?php echo $row['id']; ?>" class="tooltip-info" data-rel="tooltip" title="Download">
																				<span class="blue">
																					<i class="fal fa-download bigger-120"></i>
																				</span>
																			</a>
																		</li>
																	<?php endif; ?>
																	
																	<?php 
																	if($this->session->userdata('usertype') != 'subadmin' || (is_array($roleResponsible) && in_array('a', $roleResponsible))): ?>
																		<li>
																			<a href="<?php echo base_url();?>index.php/master/database_backup/restore/<?php echo $row['id']; ?>" class="tooltip-success" data-rel="tooltip" title="Restore" onclick="return confirm('Are you sure you want to restore this backup? This will overwrite the current database!');">
																				<span class="green">
																					<i class="fal fa-undo bigger-120"></i>
																				</span>
																			</a>
																		</li>
																	<?php endif; ?>
																	
																	<?php 
																	if($this->session->userdata('usertype') != 'subadmin' || (is_array($roleResponsible) && in_array('d', $roleResponsible))): ?>
																		<li>
																			<a href="<?php echo base_url();?>index.php/master/database_backup/delete/<?php echo $row['id']; ?>" class="tooltip-error" data-rel="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this backup?');">
																				<span class="red">
																					<i class="fal fa-trash bigger-120"></i>
																				</span>
																			</a>
																		</li>
																	<?php endif; ?>
																</ul>
															</div>
														</div>
													</td>
												</tr>
													<?php $i++;} 
													} else { ?>
												<tr>
													<td colspan="6" class="text-center">No backups found. Create your first backup!</td>
												</tr>
												<?php } ?>	
											</tbody>
										</table>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include('footer.php'); ?>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
</body>
</html>

