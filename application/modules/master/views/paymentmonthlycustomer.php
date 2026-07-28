<?php
	$sa4_page_icon = 'fal fa-wallet';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Paymentmonthlycustomer';
	$sa4_loading_label = 'Paymentmonthlycustomer';
	$sa4_dt_entity = 'paymentmonthlycustomer';
	$sa4_panel_id = 'panel-paymentmonthlycustomer';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>paymentmonthlycustomer/add/">Add Monthly Customer Bills</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>paymentmonthlycustomer/generate/">Monthly Customer Generate Bills</a></li>
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
				<div id="panel-paymentmonthlycustomer" class="panel">
					<div class="panel-hdr">
						<h2>Paymentmonthlycustomer <span class="fw-300"><i>Listing</i></span></h2>
						<div class="panel-toolbar">
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<form method="post" action="<?php echo ADMIN_URL;?>paymentmonthlycustomer/multi_delete" id="sa4-list-form">

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
														<th>
														   <input type="checkbox"/>
														</th>
														<th data-hide="phone">S No</th>
														<th data-class="expand"><i class="fal fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Customer id</th>
														<th data-hide="expand"><i class="fal fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i>Name </th>
														<th data-hide="expand">Plan-Name</th>
														<th data-hide="expand">Days</th>
														<th data-hide="expand">Amount</th>
														<th data-hide="expand">Paid Amount</th>
														<th data-hide="expand">Last month Balance</th>
														<th data-hide="expand">Total Un Paid Amount</th>
                                                        <th data-hide="expand">Currency</th>
														<th data-hide="expand">Status</th>
														<th data-hide="expand">Action</th>
													</tr>
												</thead>
												<tbody>
												  <?php
														if(count($record) > 0){
															$i=1;
															foreach($record as $key => $row){ //print_r($row);
													?>    
													<tr>
														<td class="center">
															<label>
																<input type="checkbox" class="ace" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['id'];?>" />
																<span class="lbl"></span>
															</label>
														</td>
														<td><?php echo $i; ?></td>
														<td><a href="<?php echo ADMIN_URL;?>paymentmonthlycustomer/get_customer_invoice/<?php echo $row['id']; ?>"><?php echo stripslashes($row['customer_id']); ?></td>
													   <td><?php echo stripslashes($row['name']); ?></td>
                                                    
                                                       <td><?php echo stripslashes($row['plname']); ?></td>
													   <td><?php echo stripslashes($row['days']); ?></td>
														<td><?php echo stripslashes($row['amount']); ?></td>
														<td><?php echo stripslashes($row['paidamount']); ?></td>
														<td><?php echo stripslashes($row['balance']); ?></td>
														<td><?php echo stripslashes($row['total']); ?></td>
                                                        <td><?php echo stripslashes($row['currency']); ?></td>
														<!--<td><?php echo stripslashes($row['year']); ?></td>
														<td><?php echo stripslashes($row['month']); ?></td>-->
														<td><span 
														     <?php if($row['balance']== 0){ 
															        echo " class='badge badge-success '"; 
																   } elseif($row['balance'] != 0){ 
																      echo "class='badge badge-danger '"; 
																   } ?>>
																   <?php if($row['balance']== 0){?>
																	
																	   <?php if($row['balance']== 0){ 
																	           echo "Paid"; 
																	   } ?>
																  
																   <?php }else {?>
																   <a href="JavaScript:if(confirm('Are you sure want to Chanage the Status?')==true){
																	   window.location='<?php echo ADMIN_URL;?>paymentmonthlycustomer/status/<?php echo $row['id']?>/<?php echo $row['balance'];?>/<?php echo $row['customer_id'];?>';
																	   }" style="color:#FFF; text-decoration:none;">
																	   <?php if($row['balance']== 0){ 
																	       echo "Paid"; 
																	   } elseif($row['balance']!= 0)
																	   { 
																	     echo "Partial-Paid";
																	   } ?>
																   </a>
																   <?php } ?>
														    </span></td>
                                                        <td>
                                                        <input type="hidden" name="customerid_<?php echo $i;?>" id="customerid_<?php echo $i;?>" value = "<?php echo $row['customer_id'];?>">														
														<input type="hidden" name="invoiceid_<?php echo $i;?>" id="invoiceid_<?php echo $i;?>" value = "<?php echo $row['id'];?>">
														<!--<input class="print_button" id="print_button<?php echo $i;?>" data-print-val-id="<?php echo $i; ?>" type="button" name="print" value="Print">-->
														<a href="#" title="Print">
														<i class="print_button fal fa-print" id="print_button<?php echo $i;?>" data-print-val-id="<?php echo $i ?>"></i>
														</a>
																<!--<a class="green" href="<?php echo ADMIN_URL;?>paymentmonthlycustomer/edit/<?php echo $row['id']; ?>">
																	<i class="icon-pencil bigger-130"></i>
																</a>-->
																<a class="red" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>paymentmonthlycustomer/delete/<?php echo $row['id'];?>/<?php echo $row['id_generate'];?>';}" title="Delete">
																	<i class="fal fa-remove"></i>
																</a>
															<div class="visible-xs visible-sm hidden-md hidden-lg">
																<div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
																		<img src="<?php echo base_url();?>images/favicon/delete.png">
																	</button>
																	
																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">

																		<li>
																			<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>addpaymentcustomer/delete/<?php echo $row['id'];?>/<?php echo $row['id_generate'];?>';}" class="tooltip-error" data-rel="tooltip" title="Delete">
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

