<?php
	$sa4_page_icon = 'fal fa-wallet';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Paymentmonthlycustomer Generate';
	$sa4_loading_label = 'Paymentmonthlycustomer Generate';
	$sa4_dt_entity = 'paymentmonthlycustomer generate';
	$sa4_panel_id = 'panel-paymentmonthlycustomer-generate';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>paymentmonthlycustomer/">Monthly Customer Bills</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>paymentmonthlycustomer/add/">Add Monthly Customer Bills</a></li>
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
				<div id="panel-paymentmonthlycustomer-generate" class="panel">
					<div class="panel-hdr">
						<h2>Paymentmonthlycustomer Generate <span class="fw-300"><i>Listing</i></span></h2>
						<div class="panel-toolbar">
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							

								<div class="row mb-3 align-items-end">


									<div class="col-sm-6 col-md-6 text-right">
										<a href="<?php echo ADMIN_URL?>" class="btn btn-success btn-sm waves-effect waves-themed">
											<i class="fal fa-plus mr-1"></i> Add
										</a>
									</div>
								</div>
								<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<th><input type="checkbox" class="ace" /></th>
														<th data-hide="phone">S No</th>
														<th data-hide="expand">Year</th>
														<th data-hide="expand">Month</th>
													</tr>
												</thead>
												<tbody>
												  <?php
														if(count($generate) > 0){
                                                        $i=1;
                                                        foreach($generate as $key => $row){ 
													?>   
													<tr>
													    <td class="center">
															<label>
																<input type="checkbox" class="ace" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['id'];?>" />
																<span class="lbl"></span>
															</label>
														</td>
														<td><?php echo $i; ?></td>
														<td><?php echo stripslashes($row['year']); ?></td>
													   <td><?php echo stripslashes($row['month']); ?></td>
													</tr>
														<?php $i++;} }?>	
												</tbody>
											</table>
							
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

