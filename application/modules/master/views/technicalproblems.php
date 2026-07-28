<?php
	$sa4_page_icon = 'fal fa-th-list';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Technicalproblems';
	$sa4_loading_label = 'Technicalproblems';
	$sa4_dt_entity = 'technicalproblems';
	$sa4_panel_id = 'panel-technicalproblems';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>technicalproblems/add/">Add  Technical Problems</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
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
				<div id="panel-technicalproblems" class="panel">
					<div class="panel-hdr">
						<h2>Technicalproblems <span class="fw-300"><i>Listing</i></span></h2>
						<div class="panel-toolbar">
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<form method="post" action="<?php echo ADMIN_URL;?>technicalproblems/multi_delete" id="sa4-list-form">

								<div class="row mb-3 align-items-end">

									<div class="col-sm-6 col-md-6">
										<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
											<i class="fal fa-trash-alt mr-1"></i> Delete Selected
										</button>
									</div>

									<div class="col-sm-6 col-md-6 text-right">
										<a href="<?php echo ADMIN_URL?>" class="btn btn-success btn-sm waves-effect waves-themed">
											<i class="fal fa-plus mr-1"></i> Add
										</a>
									</div>
								</div>
								<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<th style="width:10px;"><input type="checkbox" class="ace" /></th>
														<th data-hide="expand" style="width:30px;">S No</th>
														<th data-hide="expand" style="width:100px;">Customer-Id</th>
														<th data-hide="expand">Name</th>
														<th data-hide="expand">address</th>
														<th data-hide="expand">Meter #</th>
													    <th data-hide="expand">Problems Summary</th>
							                            <th data-hide="expand"  style="width:50px;">Status</th>
														<th data-hide="expand" style="width:30px;">Action</th>
													</tr>
												</thead>
												<tbody>
												  <?php
														if(count($record) > 0){
															$i=1;
															foreach($record as $key => $row){ 
													?>   
													<tr>
														<td><input type="checkbox" class="ace" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['id'];?>" /></td>
														<td><?php echo $i; ?></td>
														<td><?php echo stripslashes($row['customer_id']); ?></td>
													    <td><?php echo stripslashes($row['lastname'].', '.$row['firstname'].' '.$row['middlename']); ?></td>
													    <td><?php echo stripslashes($row['address']); ?></td>
													    <td><?php echo stripslashes($row['meter_number']); ?></td>
													    <td><?php echo stripslashes(str_replace('\n','',$row['problem_summary'])); ?>	</td>
														<!--<?php $consumed_units=$row['aftermeter']-$row['oldmeter'];?>
														<td><?php echo stripslashes($consumed_units); ?></td>
														<td><?php echo stripslashes($amountrate['amountrate']); ?></td>
														<td><?php echo stripslashes($row['amount']); ?></td>
														<td><?php echo stripslashes($row['balance']); ?></td>
														<?php $total=$row['amount']+$row['balance'];?>
														<td><?php echo stripslashes($total); ?></td>-->
														<td><span 
														<?php 
														if($row['status']== 1){ 
															echo " class='label bg-color-orange '"; 
														} elseif($row['status']== 0){ 
															echo "class='badge badge-danger '"; 
														} elseif($row['status']== 2){ 
															echo "class='label bg-color-green '"; 
														}elseif($row['status']== 3){ 
															echo "class='label  label-success '"; 
														}elseif($row['status']== 4){ 
															echo "class='label bg-color-pink '"; 
														}elseif($row['status']== 5){ 
															echo "class='label bg-color-blue '"; 
														}elseif($row['status']== 6){ 
															echo "class='label bg-color-redLight '"; 
														} ?>>

														<?php if($row['status']== 1){
															echo 'Assigned';
														}elseif($row['status']== 2){ 
															echo "On Going"; 
														}elseif($row['status']== 3){ 
															echo "Resolved"; 
														}elseif($row['status']== 4){ 
															echo "Un-Resolved"; 
														}elseif($row['status']== 5){ 
															echo "Resolved - Closed"; 
														}elseif($row['status']== 6){ 
															echo "Un-Resolved - Closed"; 
														}elseif($row['status']== 0){ 
															echo "Pending"; 
														}
														?>
														
													
														</span></td>
                                                        <td>
														    <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
																<!--<a class="blue" href="<?php echo ADMIN_URL;?>addcustomer/view/<?php echo $row['id'];?>">
																	<img src="<?php echo base_url();?>images/favicon/view_icon.gif">-->
																</a>	
																<a class="green" href="<?php echo ADMIN_URL;?>technicalproblems/edit/<?php echo $row['id']; ?>" title="Edit">
																	<i class="fal fa-edit"></i>
																</a>
																<a class="red" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>technicalproblems/delete/<?php echo $row['id'];?>';}" title="Delete">
																	<i class="fal fa-remove"></i>
																</a>
															</div>
															<div class="visible-xs visible-sm hidden-md hidden-lg">
																<div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
																		<i class="icon-caret-down icon-only bigger-120"></i>
																	</button>
																		
																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
																		<li>
																			<a href="<?php echo ADMIN_URL;?>technicalproblems/edit/<?php echo $row['id']; ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																					<span class="green">
																						<img src="<?php echo base_url();?>images/favicon/document-edit.gif">
																					</span>
																				</a>
																		</li>
																		<!--<li>
																			<a class="blue" href="<?php echo ADMIN_URL;?>technicalproblems/view/<?php echo $row['id'];?>">
																				<img src="<?php echo base_url();?>images/favicon/view_icon.gif">
																			</a>			
																		</li>-->
																		<li>
																			<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>technicalproblems/delete/<?php echo $row['id'];?>';}" class="tooltip-error" data-rel="tooltip" title="Delete">
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

