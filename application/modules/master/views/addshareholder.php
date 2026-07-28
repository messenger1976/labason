<?php
	$sa4_page_icon = 'fal fa-chart-pie';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Addshareholder';
	$sa4_loading_label = 'Addshareholder';
	$sa4_dt_entity = 'addshareholder';
	$sa4_panel_id = 'panel-addshareholder';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addshareholder/add/">Share Add</a></li>
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
				<div id="panel-addshareholder" class="panel">
					<div class="panel-hdr">
						<h2>Addshareholder <span class="fw-300"><i>Listing</i></span></h2>
						<div class="panel-toolbar">
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<form method="post" action="<?php echo ADMIN_URL;?>addshareholder/multi_delete" id="sa4-list-form">

								<div class="row mb-3 align-items-end">

									<div class="col-sm-6 col-md-6">
										<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
											<i class="fal fa-trash-alt mr-1"></i> Delete Selected
										</button>
									</div>

									<div class="col-sm-6 col-md-6 text-right">
										<a href="<?php echo ADMIN_URL?>addshareholder/add/" class="btn btn-success btn-sm waves-effect waves-themed">
											<i class="fal fa-plus mr-1"></i> Add
										</a>
									</div>
								</div>
								<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<th data-hide="phone"><input type="checkbox" class="checkbox" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['id'];?>" /></th>
														<th data-hide="phone">S No</th>
														<th data-hide="expand">Name</th>
														<th data-hide="expand">Gender</th>
														<th data-hide="expand">Date of birth</th>
														<th data-hide="expand">Place of birth</th>
														<th data-hide="expand">Address</th>
														<th data-hide="expand">Phone No</th>
														<th data-hide="expand">E-mail</th>
														<th data-hide="expand">Amount of Share</th>
														<th data-hide="expand">Comission</th>
														<th data-hide="expand">Date</th>
														<th data-hide="expand">Status</th>
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
														<td><label>
																<input type="checkbox" class="checkbox" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['id'];?>" />
																<span class="lbl"></span>
															</label>
														</td>
														<td><?php echo $i; ?></td>
													    <td><?php echo stripslashes($row['firstname'].'&nbsp;'.$row['middlename'].'&nbsp;'.$row['lastname']); ?></td>
                                                        <td><?php echo stripslashes($row['gender']); ?></td>
														<td><?php echo stripslashes($row['dob']); ?></td>
														<td><?php echo stripslashes($row['placeofbirth']); ?></td>
														<td><?php echo stripslashes($row['address']); ?></td>
														<td><?php echo stripslashes($row['phone_number']); ?></td>
														<td><?php echo stripslashes($row['email']); ?></td>
														<td><?php echo stripslashes($row['amounttoshare']); ?></td>
														<td><?php echo $row['comission']; ?></td>
														<td><?php echo date('d-M-Y',strtotime($row['date'])); ?></td>
														<td><span <?php if($row['status']== 1){ 
														         echo " class='badge badge-success '"; 
															  } elseif($row['status']== 0){ 
															     echo "class='badge badge-danger '";
															  } ?>>
															  <a href="JavaScript:if(confirm('Are you sure want to Chanage the Status?')==true){
																  window.location='<?php echo ADMIN_URL;?>addshareholder/status/<?php echo $row['id']?>/<?php echo $row['status'];?>';
															  }" style="color:#FFF; text-decoration:none;">
															  <?php if($row['status']== 1){ 
															  echo "Active"; } elseif($row['status']== 0)
															  { echo "Deactive"; } ?>
															  </a>
															  </span></td>
                                                        
														<td>
															<div class=" action-buttons">
															    <a class="blue" href="<?php echo ADMIN_URL;?>addshareholder/view/<?php echo $row['id'];?>" title="view">
																	<i class="fal fa-info-circle"></i>
																</a>	
																<a class="green" href="<?php echo ADMIN_URL;?>addshareholder/edit/<?php echo $row['id']; ?>" title="Edit">
																	<i class="fal fa-edit"></i>
																</a>
																<a class="red" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>addshareholder/delete/<?php echo $row['id'];?>';}" title="Delete">
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
																			<a href="<?php echo ADMIN_URL;?>addshareholder/edit/<?php echo $row['id']; ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																					<span class="green">
																						<img src="<?php echo base_url();?>images/favicon/document-edit.gif">
																					</span>
																				</a>
																			</li>
																			<li>
																				<a class="blue" href="<?php echo ADMIN_URL;?>addshareholder/view/<?php echo $row['id'];?>">
																					<img src="<?php echo base_url();?>images/favicon/view_icon.gif">
																				</a>			
																			</li>
																			<li>
																				<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>addshareholder/delete/<?php echo $row['id'];?>';}" class="tooltip-error" data-rel="tooltip" title="Delete">
																					<span class="red">
																						<img src="<?php echo base_url();?>images/favicon/delete.png">
																					</span>
																				</a>
																			</li>
																		</ul>
																	</div>
																</div></td>
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

