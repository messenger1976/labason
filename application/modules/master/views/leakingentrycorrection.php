<?php
	$sa4_page_icon = 'fal fa-th-list';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Leakingentrycorrection';
	$sa4_loading_label = 'Leakingentrycorrection';
	$sa4_dt_entity = 'leakingentrycorrection';
	$sa4_panel_id = 'panel-leakingentrycorrection';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>dashboard">Home</a></li>
		<li class="breadcrumb-item active">Leaking Ledger Listing</li>
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
				<div id="panel-leakingentrycorrection" class="panel">
					<div class="panel-hdr">
						<h2>Leakingentrycorrection <span class="fw-300"><i>Listing</i></span></h2>
						<div class="panel-toolbar">
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<form method="post" action="<?php echo ADMIN_URL;?>leakingentrycorrection/multi_delete" id="sa4-list-form">

								<div class="row mb-3 align-items-end">

									<div class="col-sm-6 col-md-6">
										<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
											<i class="fal fa-trash-alt mr-1"></i> Delete Selected
										</button>
									</div>

									<div class="col-sm-6 col-md-6 text-right">
										<a href="<?php echo ADMIN_URL;?>dashboard" class="btn btn-success btn-sm waves-effect waves-themed">
											<i class="fal fa-plus mr-1"></i> Add
										</a>
									</div>
								</div>
								<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<th data-hide="phone"><input type="checkbox"/></th>
														<th data-hide="phone">S No</th>
														<th data-hide="expand">Customer Name</th>
														<th data-hide="expand">Customer ID</th>
                                                        <th data-hide="expand">Billing No.</th>
                                                        <th data-hide="expand">Billing Period</th>
                                                        <th data-hide="expand">Billing Amount</th>
                                                        <th data-hide="expand">Discount %</th>
														<th data-hide="expand">Discount Amount</th>
                                                        <th data-hide="expand">Total Amount</th>
                                                        <th data-hide="expand">Balance Amount</th>
                                                        <th data-hide="expand">Payment Date</th>
                                                        <th data-hide="expand">Status</th>
														<th data-hide="expand">Action</th>
													</tr>
												</thead>
												<tbody>
												  <?php
														if(count($record) > 0){
                                                        $i=1;
                                                        foreach($record as $key => $row){ 
															$total_payment = $this->my_model->get_total_payment($row['leaking_id'])['totalpayment'];
															$leaking_balance = $row['leaking_total_amount'] - $total_payment;
													?>   
													<tr>
														<td><input type="checkbox" class="ace" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['leaking_id'];?>" /></td>
														<td><?php echo $i; ?></td>
														<td><?php echo stripslashes($row['last_name'].', '.$row['first_name'].' '.$row['middle_name']); ?></td>
														<td><?php echo stripslashes($row['customer_id']); ?></td>
														<td><?php echo stripslashes($row['leaking_refno']); ?></td>
														<td><?php echo stripslashes(getMonthName($row['month'])[0]->month_name.' '.$row['year']); ?></td>
														<td align="right"><?php echo stripslashes(number_format($row['leaking_bill_amount'],2)); ?></td>
														<td align="right"><?php echo stripslashes($row['leaking_discount_percent']); ?></td>
														<td align="right"><?php echo stripslashes(number_format($row['leaking_discount_amount'],2)); ?></td>
														<td align="right"><?php echo stripslashes(number_format($row['leaking_total_amount'],2)); ?></td>
														<td align="right"><?php echo stripslashes(number_format($leaking_balance,2)); ?></td>
														<td>
															<?php
																$paydate = date('M j, Y',strtotime($row['leaking_date']));
																echo $paydate;
															?>
														</td>
														<td><?php 
															if($row['leaking_status']==1){
																echo '<label class="badge badge-danger setStatus" data-id="'.$row['leaking_id'].'" data-status="'.$row['leaking_status'].'">Pending</label>'; 
															}else if($row['leaking_status']==2){
																echo '<label class="badge badge-info">Approved</label>'; 

															}elseif($row['leaking_status']==4){
																echo '<label class="label label-primary">Posted</label>';
															}elseif($row['leaking_status']==5){
																echo '<label class="badge badge-success">Full Paid</label>';
															}else{
																echo '<label class="label label-default">Denied</label>'; 
															}
														
														?></td>
														<td>
														<a href="javascript:void(0);" class="tooltip-error btn_delete" data-rel="tooltip" title="Delete" data-leaking_id="<?php echo $row['leaking_id'];?>">
															<span class="red">
																<img src="<?php echo base_url();?>images/favicon/delete.png">
															</span>
														</a>
														
														<a href="<?php echo ADMIN_URL;?>Leakingentrycorrection/ledger/<?php echo $row['leaking_id'];?>" class="tooltip-success" data-rel="tooltip" title="Ledger">
															<span class="blue">
																<img src="<?php echo base_url();?>images/favicon/ledger.png">
															</span>
														</a>
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

