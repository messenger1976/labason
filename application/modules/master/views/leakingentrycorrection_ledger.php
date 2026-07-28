<?php
	$sa4_page_icon = 'fal fa-wallet';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Leakingentrycorrection Ledger';
	$sa4_loading_label = 'Leakingentrycorrection Ledger';
	$sa4_dt_entity = 'leakingentrycorrection ledger';
	$sa4_panel_id = 'panel-leakingentrycorrection-ledger';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>dashboard">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>Leakingentrycorrectioncorrection">Leaking Ledger Listing</a></li>
		<li class="breadcrumb-item active">Leaking Ledger Details Listing</li>
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
				<div id="panel-leakingentrycorrection-ledger" class="panel">
					<div class="panel-hdr">
						<h2>Leakingentrycorrection Ledger <span class="fw-300"><i>Listing</i></span></h2>
						<div class="panel-toolbar">
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<form method="post" action="<?php echo ADMIN_URL;?>Leakingentrycorrection/multi_delete" id="sa4-list-form">

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
														<!--<th data-hide="phone"><input type="checkbox"/></th>-->
														<th data-hide="phone" style="width:50px;">S No</th>
														<th data-hide="expand" style="width:100px;">Reference #</th>
														<!--<th data-hide="expand">Billing Period</th>-->
                                                        <th data-hide="expand" style="width:100px;">Payment Date</th>
                                                        <th data-hide="expand" style="width:150px;">Total Amount</th>
                                                        <th data-hide="expand">Remarks</th>
                                                        <th data-hide="expand" style="text-align:center;width: 100px;">Action</th>
													</tr>
												</thead>
												<tbody>
												  <?php
														if(count($record) > 0){
                                                        $i=1;
                                                        foreach($record as $key => $row){ 
													?>   
													<tr>
														<!--<td><input type="checkbox" class="ace" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['leakingledgerdetails_id'];?>" /></td>-->
														<td><?php echo $i; ?></td>
														<td><?php echo stripslashes($row['leakingledgerdetails_source_type'].'#'.$row['leakingledgerdetails_or_number']); ?></td>
														<!--<td><?php echo stripslashes(getMonthName($row['month'])[0]->month_name.' '.$row['year']); ?></td>-->
														<td>
															<?php
																$paydate = date('M j, Y',strtotime($row['leakingledgerdetails_transdate']));
																echo $paydate;
															?>
														</td>
														<td align="right"><?php echo stripslashes(number_format($row['leakingledgerdetails_amount'],2)); ?></td>
														<td align="left"><?php echo stripslashes($row['leakingledgerdetails_remarks']); ?></td>
														<td align="center"><a href="#" class="btn btn-outline btn-xs"><i class="fal fa-check-square-o"></i> Posted</a></td>
														
													

														
														
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

