<?php
	$sa4_page_icon = 'fal fa-th-list';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Responsibilities';
	$sa4_loading_label = 'Responsibilities';
	$sa4_dt_entity = 'responsibilities';
	$sa4_panel_id = 'panel-responsibilities';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>responsibilities/add/">Add Sub Admins</a></li>
		<li class="breadcrumb-item active">Roles & Responsibilities</li>
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
				<div id="panel-responsibilities" class="panel">
					<div class="panel-hdr">
						<h2>Responsibilities <span class="fw-300"><i>Listing</i></span></h2>
						<div class="panel-toolbar">
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<form method="post" action="<?php echo ADMIN_URL;?>addcustomer/multi_delete" id="sa4-list-form">

								<div class="row mb-3 align-items-end">

									<div class="col-sm-6 col-md-6">
										<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
											<i class="fal fa-trash-alt mr-1"></i> Delete Selected
										</button>
									</div>

									<div class="col-sm-6 col-md-6 text-right">
										<a href="<?php echo ADMIN_URL?>responsibilities/add/" class="btn btn-success btn-sm waves-effect waves-themed">
											<i class="fal fa-plus mr-1"></i> Add
										</a>
									</div>
								</div>
								<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<th data-hide="expand">SNo</th>
														<th data-hide="expand">Role Name</th>
														<th data-hide="expand">Status</th>
														<th data-hide="expand"h>Action</th>
																	
													</tr>
												</thead>
												<tbody>
												  <?php
														if(count($record) > 0){
															$i=1;
															foreach($record as $key => $row){ 
													?>   
													<tr>
													    <td><?php echo $i; ?></td>
														<td><?php echo stripslashes($row['role_name']); ?></td>
														<td>
															<?php if($row['status']== 1){ ?>
															<span <?php if($row['status']== 1){ echo " class='badge badge-success '"; } elseif($row['status']== 0){ echo "class='badge badge-danger '"; } ?>><a href="JavaScript:if(confirm('Are you sure want to Change the Status?')==true){window.location='<?php echo ADMIN_URL;?>responsibilities/status/<?php echo $row['id']?>/<?php echo $row['status'];?>/<?php echo $this->uri->segment(3);?>/<?php echo $this->uri->segment(4);?>';}" style="color:#FFF; text-decoration:none;">Active</a></span>
															<?php } else if($row['status']== 0){ ?>
															<span <?php if($row['status']== 1){ echo " class='badge badge-success '"; } elseif($row['status']== 0){ echo "class='badge badge-danger '"; } ?>><a href="JavaScript:if(confirm('Are you sure want to Change the Status?')==true){window.location='<?php echo ADMIN_URL;?>responsibilities/status/<?php echo $row['id']?>/<?php echo $row['status'];?>/<?php echo $this->uri->segment(3);?>/<?php echo $this->uri->segment(4);?>';}" style="color:#FFF; text-decoration:none;">De-Active</a></span>
															<?php } else if($row['status']== 2){ ?>
															<span <?php echo "class='badge badge-danger '"; ?>><a href="" style="color:#FFF; text-decoration:none;">In-complete</a></span>
															<?php } ?>
                 										</td>
                                                        <td>
																<div class=" action-buttons">

																	<a class="blue" href="<?php echo ADMIN_URL;?>responsibilities/view/<?php echo $row['id']; ?>/<?php echo $this->uri->segment(3);?>" title="View">
																		<i class="fal fa-info-circle"></i>
																	</a>           


																	<a class="green" href="<?php echo ADMIN_URL;?>responsibilities/edit/<?php echo $row['id']; ?>/<?php echo $this->uri->segment(3);?>" title="Edit">
																		<i class="fal fa-edit"></i>
																	</a>       


																	<a class="red" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>responsibilities/delete/<?php echo $row['id'];?>/<?php echo $this->uri->segment(3);?>';}" title="Delete">
																		<i class="fal fa-times"></i>
																	</a>

																</div>
															<div class="">
																<div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
																		<i class="fal fa-caret-down icon-only bigger-120"></i>
																	</button>
																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow float-right dropdown-caret dropdown-close">

																		<li>
																			<a href="<?php echo ADMIN_URL;?>responsibilities/view/<?php echo $row['id']; ?>/<?php echo $this->uri->segment(3);?>" class="tooltip-info" data-rel="tooltip" title="View">
																			<span class="blue">
																			<img src="<?php echo base_url();?>images/favicon/view_icon.gif">
																			</span>
																			</a>
																		</li>

																		<li>
																			<a href="<?php echo ADMIN_URL;?>responsibilities/edit/<?php echo $row['id']; ?>/<?php echo $this->uri->segment(3);?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																			<span class="green">
																			<img src="<?php echo base_url();?>images/favicon/document-edit.gif">
																			</span>
																			</a>
																		</li>

																		<li>
																			<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>responsibilities/delete/<?php echo $row['id'];?>/<?php echo $this->uri->segment(3);?>';}" class="tooltip-error" data-rel="tooltip" title="Delete">
																			<span class="red">
																			<img src="<?php echo base_url();?>images/favicon/delete.png">
																			</span>
																			</a>
																		</li>
															</ul>
																</div>
															</div>
														</td>														
													
													</tr>
														<?php $i++;} }?>	
												</tbody>
											</table>
							</form>
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

