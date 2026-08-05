<?php
	$sa4_page_icon = 'fal fa-chart-pie';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Share Profitloss View';
	$sa4_loading_label = 'Share Profitloss View';
	$sa4_dt_entity = 'share profitloss view';
	$sa4_panel_id = 'panel-share-profitloss-view';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addshareholder/add/">Add Share Holder</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addshareholder/search/">Search Share Holder</a></li>
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
				<div id="panel-share-profitloss-view" class="panel">
					<div class="panel-hdr">
						<h2>Share Profitloss View <span class="fw-300"><i>Listing</i></span></h2>
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
								</div>
								<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<th data-hide="phone">S No</th>
														<th data-hide="expand">Share Holder Name</th>
														<th data-hide="expand">From Date</th>
														<th data-hide="expand">To Date</th>
														<th data-hide="expand">Comission</th>
														<th data-hide="expand">Amount Earn</th>
													</tr>
												</thead>
												<tbody>
												  <?php
														if(count($record) > 0){
															$i=1;
															foreach($record as $key => $row){  //print_r($row);
													?>   
													<tr>
														
														<td><?php echo $i; ?></td>
														<td><?php echo stripslashes($row['firstname'].'&nbsp;'.$row['middlename'].'&nbsp;'.$row['lastname']); ?></td>
														<td><?php echo stripslashes($row['fromdate']); ?></td>
														<td><?php echo stripslashes($row['todate']); ?></td>
														<td><?php echo stripslashes($row['comission']); ?></td>
														<td><?php echo stripslashes($row['amount_earn']); ?></td>
														
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

