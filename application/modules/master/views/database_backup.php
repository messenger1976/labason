<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<title> SmartAdmin </title>
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	</head>
	
<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">
				<span class="ribbon-button-alignment"> 
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
						<i class="fa fa-refresh"></i>
					</span> 
				</span>

				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li><a href="<?php echo base_url();?>index.php/master/dashboard">Home</a></li>
					<li><a href="<?php echo base_url();?>index.php/master/database_backup/"> Database Backup </a></li>
					<li>List View</li>
				</ol>
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-database"></i> Database <span>> Backup Management </span></h1>
					</div>
				</div>
				
				<!-- Success/Error Messages -->
				<?php if($this->session->flashdata('msg_succ')): ?>
					<div class="alert alert-success fade in">
						<button class="close" data-dismiss="alert">×</button>
						<i class="fa-fw fa fa-check"></i>
						<strong>Success!</strong> <?php echo $this->session->flashdata('msg_succ'); ?>
					</div>
				<?php endif; ?>
				
				<?php if($this->session->flashdata('msg_err')): ?>
					<div class="alert alert-danger fade in">
						<button class="close" data-dismiss="alert">×</button>
						<i class="fa-fw fa fa-times"></i>
						<strong>Error!</strong> <?php echo $this->session->flashdata('msg_err'); ?>
					</div>
				<?php endif; ?>
				
				<!-- widget grid -->
				<section id="widget-grid" class="">

					<!-- row -->
					<div class="row">
				
						<!-- NEW WIDGET START -->
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
								
								<header style="height: 42px;">
									<span class="widget-icon"> <i class="fa fa-database"></i> </span>
									<p style="padding: 5px 0 0 45px;font-size: 16px;"><strong>Database Backups</strong>
									</p>
									<div class="widget-toolbar" role="menu">
										<?php 
										$header_data = isset($data['header']) ? $data['header'] : array();
										$roleResponsible = isset($header_data['roleResponsible']['database_backup']) ? $header_data['roleResponsible']['database_backup'] : array();
										if($this->session->userdata('usertype') != 'subadmin' || (is_array($roleResponsible) && in_array('a', $roleResponsible))): ?>
											<a href="<?php echo base_url();?>index.php/master/database_backup/create" class="btn btn-primary btn-sm">
												<i class="fa fa-plus"></i> Create Backup
											</a>
										<?php endif; ?>
									</div>
								</header>
				
								<!-- widget div-->
								<div>
									<!-- widget content -->
									<div class="widget-body no-padding">
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
													    <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
															<?php 
															$header_data = isset($data['header']) ? $data['header'] : array();
															$roleResponsible = isset($header_data['roleResponsible']['database_backup']) ? $header_data['roleResponsible']['database_backup'] : array();
															if($this->session->userdata('usertype') != 'subadmin' || (is_array($roleResponsible) && in_array('l', $roleResponsible))): ?>
																<a class="blue" href="<?php echo base_url();?>index.php/master/database_backup/download/<?php echo $row['id']; ?>" title="Download">
																	<i class="fa fa-download"></i>
																</a>
															<?php endif; ?>
															
															<?php 
															if($this->session->userdata('usertype') != 'subadmin' || (is_array($roleResponsible) && in_array('a', $roleResponsible))): ?>
																<a class="green" href="<?php echo base_url();?>index.php/master/database_backup/restore/<?php echo $row['id']; ?>" title="Restore" onclick="return confirm('Are you sure you want to restore this backup? This will overwrite the current database!');">
																	<i class="fa fa-undo"></i>
																</a>
															<?php endif; ?>
															
															<?php 
															if($this->session->userdata('usertype') != 'subadmin' || (is_array($roleResponsible) && in_array('d', $roleResponsible))): ?>
																<a class="red" href="<?php echo base_url();?>index.php/master/database_backup/delete/<?php echo $row['id']; ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this backup?');">
																	<i class="fa fa-trash"></i>
																</a>
															<?php endif; ?>
														</div>
														<div class="visible-xs visible-sm hidden-md hidden-lg">
															<div class="inline position-relative">
																<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
																	<i class="icon-caret-down icon-only bigger-120"></i>
																</button>
																	
																<ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
																	<?php 
																	$header_data = isset($data['header']) ? $data['header'] : array();
																	$roleResponsible = isset($header_data['roleResponsible']['database_backup']) ? $header_data['roleResponsible']['database_backup'] : array();
																	if($this->session->userdata('usertype') != 'subadmin' || (is_array($roleResponsible) && in_array('l', $roleResponsible))): ?>
																		<li>
																			<a href="<?php echo base_url();?>index.php/master/database_backup/download/<?php echo $row['id']; ?>" class="tooltip-info" data-rel="tooltip" title="Download">
																				<span class="blue">
																					<i class="fa fa-download bigger-120"></i>
																				</span>
																			</a>
																		</li>
																	<?php endif; ?>
																	
																	<?php 
																	if($this->session->userdata('usertype') != 'subadmin' || (is_array($roleResponsible) && in_array('a', $roleResponsible))): ?>
																		<li>
																			<a href="<?php echo base_url();?>index.php/master/database_backup/restore/<?php echo $row['id']; ?>" class="tooltip-success" data-rel="tooltip" title="Restore" onclick="return confirm('Are you sure you want to restore this backup? This will overwrite the current database!');">
																				<span class="green">
																					<i class="fa fa-undo bigger-120"></i>
																				</span>
																			</a>
																		</li>
																	<?php endif; ?>
																	
																	<?php 
																	if($this->session->userdata('usertype') != 'subadmin' || (is_array($roleResponsible) && in_array('d', $roleResponsible))): ?>
																		<li>
																			<a href="<?php echo base_url();?>index.php/master/database_backup/delete/<?php echo $row['id']; ?>" class="tooltip-error" data-rel="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this backup?');">
																				<span class="red">
																					<i class="fa fa-trash bigger-120"></i>
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
									<!-- end widget content -->
								</div>
								<!-- end widget div -->
				
							</div>
							<!-- end widget -->
				
						</article>
						<!-- WIDGET END -->
				
					</div>
				
					<!-- end row -->

				</section>
				<!-- end widget grid -->

			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->
		

		<?php include('footer.php');?>

	</body>

</html>
<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url();?>js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script type="text/javascript">
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
		$(document).ready(function() {
			
			pageSetUp();
			
			/* BASIC */
				var responsiveHelper_dt_basic = undefined;
				var breakpointDefinition = {
					tablet : 1024,
					phone : 480
				};
	
				$('#dt_basic').dataTable({
					"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
					"autoWidth" : true,
			        "oLanguage": {
					    "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
					},
					"preDrawCallback" : function() {
						if (!responsiveHelper_dt_basic) {
							responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
						}
					},
					"rowCallback" : function(nRow) {
						responsiveHelper_dt_basic.createExpandIcon(nRow);
					},
					"drawCallback" : function(oSettings) {
						responsiveHelper_dt_basic.respond();
					}
				});
			
			/* END BASIC */
		
		})

		</script>

