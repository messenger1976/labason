<?php
	$sa4_page_icon = 'fal fa-wallet';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Addledger';
	$sa4_loading_label = 'Addledger';
	$sa4_dt_entity = 'addledger';
	$sa4_panel_id = 'panel-addledger';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addledger/add/">add Ledger</a></li>
		<li class="breadcrumb-item"><a href="< ?php echo ADMIN_URL?>addledger/search/">Search Ledger</a></li>
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
				<div id="panel-addledger" class="panel">
					<div class="panel-hdr">
						<h2>Addledger <span class="fw-300"><i>Listing</i></span></h2>
						<div class="panel-toolbar">
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<form method="post" action="<?php echo ADMIN_URL;?>addledger/multi_delete" id="sa4-list-form">

								<div class="row mb-3 align-items-end">

									<div class="col-sm-6 col-md-6">
										<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
											<i class="fal fa-trash-alt mr-1"></i> Delete Selected
										</button>
									</div>

									<div class="col-sm-6 col-md-6 text-right">
										<a href="<?php echo ADMIN_URL?>addledger/add/" class="btn btn-success btn-sm waves-effect waves-themed">
											<i class="fal fa-plus mr-1"></i> Add
										</a>
									</div>
								</div>
								<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<th><input type="checkbox" class="checkbox" /></th>
														<th data-hide="phone">S No</th>
														<th data-class="expand"><i class="fal fa-fw fa-user text-muted"></i> Ledger Id</th>
														<th data-hide="expand"><i class="fal fa-fw fa-user text-muted"></i>Ledger Name </th>
														<th data-hide="expand">Account Subgroup Type</th>
														<th data-hide="expand">Balance</th>
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
														<td class="center">
															<label>
																<input type="checkbox" class="checkbox" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['id'];?>" />
																<span class="lbl"></span>
															</label>
														</td>
														<td><?php echo $i; ?></td>
														<td><?php echo stripslashes($row['id']); ?></td>
												        <td><?php echo stripslashes($row['ledgerName']); ?></td>
														<td><?php echo stripslashes($row['subName']); ?></td>
														<td><?php echo stripslashes($row['balance']); ?></td>
														<td>
														    <input type="hidden" name="ledgerid_<?php echo $i;?>" id="ledgerid_<?php echo $i;?>" value = "<?php echo $row['id'];?>">
															<input type="hidden" name="id_<?php echo $i;?>" id="id_<?php echo $i;?>" value = "<?php echo $row['id'];?>">
															
														    <div class=" action-buttons">
                                                           <!-- <a href="#"><img src="< ?php echo base_url();?>images/favicon/icon-printer.gif" class="print_button" id="print_button< ?php echo $i;?>" data-print-val-id="< ?php echo $i; ?>"></a>-->
																<a class="blue" href="<?php echo ADMIN_URL;?>addledger/view/<?php echo $row['id'];?>" title="view">
																	<i class="fal fa-info-circle"></i>
																</a>	
																<a class="green" href="<?php echo ADMIN_URL;?>addledger/edit/<?php echo $row['id']; ?>" title="Edit">
																	<i class="fal fa-edit"></i>
																</a>
																<a class="red" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>addledger/delete/<?php echo $row['id'];?>';}" title="Delete">
																	<i class="fal fa-times"></i>
																</a>
															</div>
															<div class="">
																<div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
																		<i class="fal fa-caret-down icon-only bigger-120"></i>
																	</button>
																		
																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow float-right dropdown-caret dropdown-close">
																		<li><a href="#"><img src="<?php echo base_url();?>images/favicon/icon-printer.gif" class="print_button" id="print_button<?php echo $i;?>" data-print-val-id="<?php echo $i; ?>"></a></li>
                                                                        <li>
																			<a href="<?php echo ADMIN_URL;?>addledger/edit/<?php echo $row['id']; ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																					<span class="green">
																						<img src="<?php echo base_url();?>images/favicon/document-edit.gif">
																					</span>
																				</a>
																				
																		</li>
																		<li>
																			<a class="blue" href="<?php echo ADMIN_URL;?>addledger/view/<?php echo $row['id'];?>">
																				<img src="<?php echo base_url();?>images/favicon/view_icon.gif">
																			</a>			
																		</li>
																		<li>
																			<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>addledger/delete/<?php echo $row['id'];?>';}" class="tooltip-error" data-rel="tooltip" title="Delete">
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

