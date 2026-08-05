<?php
	$sa4_page_icon = 'fal fa-chart-bar';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Adminreport';
	$sa4_loading_label = 'Adminreport';
	$sa4_dt_entity = 'adminreport';
	$sa4_panel_id = 'panel-adminreport';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="< ?php echo ADMIN_URL?>addledger/search/">Search Ledger</a></li>
		<li class="breadcrumb-item active">Journal Report</li>
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
				<div id="panel-adminreport" class="panel">
					<div class="panel-hdr">
						<h2>Adminreport <span class="fw-300"><i>Listing</i></span></h2>
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
													<tr style=" background: #00a1ff; color: #fff;">
														<th><input type="checkbox" class="ace" /></th>
														<th>Name</th>
														<th data-class="expand">Dated</th>
<th data-hide="expand">Remarks</th>
														<th data-hide="expand">Debit </th>
														<th data-hide="expand">Credit</th>
														
													</tr>
												</thead>
												<tbody>
												  <?php
														if(!empty($record)){
														$i = 1;
															foreach($record as $row){ 
													?>   
													<tr>
														<td class="center">
														<?php echo $i; ?>
														</td>
														<td><?php 
												        		  if($row['tableName'] == "addmetercustomer"){ echo "Meter Customer" ;}
																  if($row['tableName'] == "monthlycustomer" ){ echo "Monthly Customer"; }?>
														<?php if ($row['tableName'] == "addexpenses") { echo "Expenses"; }?></td>
														<td><?php echo date('Y-m-d', strtotime($row['create_date_time'])) ?></td>
														<td></td>
												        <td><?php 
												        		  if($row['tableName'] == "addmetercustomer" || $row['tableName'] == "monthlycustomer" )
																  {	
																	echo "$".$row['Debit'];
																  }
																  elseif($row['tableName'] == "addexpenses" && !empty($row['Debit']))
																  {
																	echo "$".$row['Debit'];
																  }
															?>
														</td>
														<td><?php if ($row['tableName'] == "addexpenses")
																  {	
																	echo "$".$row['Credit'];
																  }
															?>
														</td>
														
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

