<?php
	$sa4_page_icon = 'fal fa-wallet';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Addjournalvoucher';
	$sa4_loading_label = 'Addjournalvoucher';
	$sa4_dt_entity = 'addjournalvoucher';
	$sa4_panel_id = 'panel-addjournalvoucher';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addjournalvoucher/add/">Add Journal Voucher</a></li>
		<li class="breadcrumb-item"><a href="< ?php echo ADMIN_URL?>addexpenses/search/">Search Expenses</a></li>
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
				<div id="panel-addjournalvoucher" class="panel">
					<div class="panel-hdr">
						<h2>Addjournalvoucher <span class="fw-300"><i>Listing</i></span></h2>
						<div class="panel-toolbar">
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<form method="post" action="<?php echo ADMIN_URL;?>addexpenses/multi_delete" id="sa4-list-form">

								<div class="row mb-3 align-items-end">

									<div class="col-sm-6 col-md-6">
										<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
											<i class="fal fa-trash-alt mr-1"></i> Delete Selected
										</button>
									</div>

									<div class="col-sm-6 col-md-6 text-right">
										<a href="<?php echo ADMIN_URL?>addjournalvoucher/add/" class="btn btn-success btn-sm waves-effect waves-themed">
											<i class="fal fa-plus mr-1"></i> Add
										</a>
									</div>
								</div>
								<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<th><input type="checkbox" class="checkbox" /></th>
														<th data-hide="expand">Sr.No.</th>
														<th data-hide="expand">Voucher No.</th>
														<th data-hide="expand">Date</th>
														<!--<th data-hide="expand">Ledger 1</th>-->
														<th data-hide="expand">Ledger </th>
								                        <th data-hide="expand">Credit</th>
								                        <th data-hide="expand">Debit</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
												  <?php
														if(count($record) > 0){
															$i=1;
															foreach($record as $key => $row){ 
													?>   
													<tr>
														<td><input type="checkbox" class="checkbox" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['id'];?>" /></td>
														<td><?php echo $i; ?></td>
														<td><?php echo stripslashes($row['voucherNo']); ?></td>
														<td><?php echo $row['date'];?></td>
														<!--<td>< ?php 
														$pieces = explode(" / ", $row['transaction_id']);
														echo $pieces[0]; ?></td>-->
														<td>
														<?php if($row['tableName']=='transactions') {  // for transaction
														    $input =  $row['ledger_id'];
														  	if($row['ledger_id_for']=='employee_id'){
														 	$sql = "SELECT CONCAT(first_name ,' ',last_name)as empName FROM tbl_addemployee WHERE id=$input";
															$query = mysql_query($sql);
															$result = mysql_fetch_array($query); 
															echo $result['empName'];
														  	
														  	} else if($row['ledger_id_for']=='expenses_type'){ 
														 	$sql = "SELECT expensestype_name as eName FROM tbl_expensetype WHERE id=$input";
														    $query = mysql_query($sql); 
															$result = mysql_fetch_array($query); 
															echo $result['eName'];
														  	} else if($row['ledger_id_for']=='ledger_id'){
														 	$sql = "SELECT ledgerName as lName FROM tbl_ledgers WHERE id=$input";
														    $query = mysql_query($sql); 
															$result = mysql_fetch_array($query); 
															echo $result['lName'];
															} else if($row['ledger_id_for']=='customer_id'){
														 	$sql = "SELECT  CONCAT(first_name ,' ',last_name) as cName FROM tbl_addcustomer WHERE id=$input";
														    $query = mysql_query($sql); 
															$result = mysql_fetch_array($query); 
															echo $result['cName'];
														  	} //else echo "";

														  } else if($row['tableName']=='addexpenses') {  // for expenses
														  	if($row['ledger_id_for']=='expenses_type'){
														 	$sql = "SELECT expensestype_name as eName FROM tbl_expensetype WHERE id=".$row['ledger_id'];
														    $query = mysql_query($sql); 
															$result = mysql_fetch_array($query); 
															echo $result['eName'];
														  	} else if($row['ledger_id_for']=='ledger_id'){
														 	$sql = "SELECT ledgerName as lName FROM tbl_ledgers WHERE id=".$row['ledger_id'];
														    $query = mysql_query($sql); 
															$result = mysql_fetch_array($query); 
															echo $result['lName'];
															} //else echo "";

														  }else {  // for meter and monthly customers

														  	if($row['ledger_id_for']=='ledger_id'){ 
														 	$sql = "SELECT ledgerName as lName FROM tbl_ledgers WHERE id=".$row['ledger_id'];
														    $query = mysql_query($sql); 
															$result = mysql_fetch_array($query); 
															echo $result['lName'];
															} else if($row['ledger_id_for']=='customer_id'){  
														 	$sql = "SELECT  CONCAT(first_name,' ',last_name)as cName FROM tbl_addcustomer WHERE id='".$row['ledger_id']."'";
														    $query = mysql_query($sql); 
															$result = mysql_fetch_array($query); 
															echo $result['cName'];
														  	} //else echo " ";
													    }

														  ?>
														</td>
														<td><?php echo stripslashes($row['credit']); ?></td>
														<td><?php echo stripslashes($row['debit']); ?></td>
														<td>
														    <input type="hidden" name="id_<?php echo $i;?>" id="id_<?php echo $i;?>" value = "<?php echo $row['id'];?>">
														   <!-- <a href="#"><img src="< ?php echo base_url();?>images/favicon/icon-printer.gif" class="print_button" id="print_button< ?php echo $i;?>" data-print-val-id="< ?php echo $i; ?>"></a>-->
														    <div class=" action-buttons">
																<!--<a class="blue" href="< ?php echo ADMIN_URL;?>addexpenses/view/< ?php echo $row['id'];?>">
																	<img src="< ?php echo base_url();?>images/favicon/view_icon.gif">
																</a>	-->
																<!--<a class="green" href="< ?php echo ADMIN_URL;?>addjournalvoucher/edit/< ?php echo $row['id']; ?>">
																	<img src="< ?php echo base_url();?>images/favicon/document-edit.gif">
																</a>-->
																<a class="red" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>addjournalvoucher/delete/<?php echo $row['id'];?>';}" title="Delete">
																	<i  class="fal fa-times"></i>
																</a>
															</div>
															<div class="">
																<div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
																		<i class="fal fa-caret-down icon-only bigger-120"></i>
																	</button>
																		
																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow float-right dropdown-caret dropdown-close">
																		<li>
																			<a href="<?php echo ADMIN_URL;?>addexpenses/edit/<?php echo $row['id']; ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																					<span class="green">
																						<img src="<?php echo base_url();?>images/favicon/document-edit.gif">
																					</span>
																				</a>
																		</li>
																		<li>
																			<a class="blue" href="<?php echo ADMIN_URL;?>addexpenses/view/<?php echo $row['id'];?>">
																				<img src="<?php echo base_url();?>images/favicon/view_icon.gif">
																			</a>			
																		</li>
																		<li>
																			<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>addexpenses/delete/<?php echo $row['id'];?>';}" class="tooltip-error" data-rel="tooltip" title="Delete">
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

