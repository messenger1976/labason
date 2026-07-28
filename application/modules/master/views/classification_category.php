<?php
	$sa4_page_icon = 'fal fa-tags';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Classification Category';
	$sa4_loading_label = 'Classification Category';
	$sa4_dt_entity = 'classification category';
	$sa4_panel_id = 'panel-classification-category';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>dashboard">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>classification_category/add">Add Classification Category</a></li>
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
				<div id="panel-classification-category" class="panel">
					<div class="panel-hdr">
						<h2>Classification Category <span class="fw-300"><i>Listing</i></span></h2>
						<div class="panel-toolbar">
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<form method="post" action="<?php echo ADMIN_URL;?>classification_category/multi_delete" id="sa4-list-form">

								<div class="row mb-3 align-items-end">

									<div class="col-sm-6 col-md-6">
										<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
											<i class="fal fa-trash-alt mr-1"></i> Delete Selected
										</button>
									</div>

									<div class="col-sm-6 col-md-6 text-right">
										<a href="<?php echo ADMIN_URL?>classification_category/add/" class="btn btn-success btn-sm waves-effect waves-themed">
											<i class="fal fa-plus mr-1"></i> Add
										</a>
									</div>
								</div>
								<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<th data-hide="phone"><input type="checkbox"/></th>
														<th data-hide="phone">S No</th>
														<th data-hide="expand">Classification Category Name</th>
														<th data-hide="expand">Action</th>
													</tr>
												</thead>
												<tbody>
												  <?php
														if(count($record) > 0){
                                                        $i=1;
                                                        foreach($record as $key => $row){ 
													?>   
													<tr>
														<td><input type="checkbox" class="checkbox" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['class_cat_id'];?>" /></td>
														<td><?php echo $i; ?></td>
														<td><?php echo stripslashes($row['class_cat_name']); ?></td>
														<td>
														    <div class=" action-buttons">
																<a class="green" href="<?php echo ADMIN_URL;?>classification_category/edit/<?php echo $row['class_cat_id']; ?>" title="Edit">
																	<i class="fal fa-edit"></i>
																</a>
																<a class="red" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>classification_category/delete/<?php echo $row['class_cat_id'];?>';}" title="Delete">
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
																			<a href="<?php echo ADMIN_URL;?>classification_category/edit/<?php echo $row['class_cat_id']; ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																					<span class="green">
																						<img src="<?php echo base_url();?>images/favicon/document-edit.gif">
																					</span>
																			</a>
																		</li>
																		<li>
																				<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>classification_category/delete/<?php echo $row['class_cat_id'];?>';}" class="tooltip-error" data-rel="tooltip" title="Delete">
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

