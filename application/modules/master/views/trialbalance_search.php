<?php
	$sa4_page_icon = 'fal fa-chart-bar';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Trialbalance Search';
	$sa4_loading_label = 'Trialbalance Search';
	$sa4_dt_entity = 'trialbalance search';
	$sa4_panel_id = 'panel-trialbalance-search';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addjournalvoucher/add/">Add Journal Voucher</a></li>
		<li class="breadcrumb-item"><a href="< ?php echo ADMIN_URL?>trialbalance/search/">Search Trial Balance</a></li>
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
				<div id="panel-trialbalance-search" class="panel">
					<div class="panel-hdr">
						<h2>Trialbalance Search <span class="fw-300"><i>Listing</i></span></h2>
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
										<a href="<?php echo ADMIN_URL?>" class="btn btn-success btn-sm waves-effect waves-themed">
											<i class="fal fa-plus mr-1"></i> Add
										</a>
									</div>
								</div>
								<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<th colspan="4" style="text-align:center">Trial Balance</th>
													</tr>
													<tr>
													    <th>Sr.No.</th>
														<th>Perticular</th>
														<!--<th data-hide="expand">Sr.No.</th>
														<th data-hide="expand">Voucher No.</th>
														<th data-hide="expand">Date</th>
														<th data-hide="expand">Ledger 1</th>
														<th data-hide="expand">Ledger </th>-->
								                        <th data-hide="expand">Credit</th>
								                        <th data-hide="expand">Debit</th>
													</tr>
												</thead>
												<tbody>
												  <?php
														if(count($record) > 0){
															$i=1;
															foreach($record as $key => $row){ 
													?>   
													<tr>
													<td><?php echo $i;//." / ".$row['id'];?></td>
													<!--	<td><input type="checkbox" class="ace" name="delete_ids[]" id="delete_ids[]" value="< ?php echo $row['id'];?>" /></td>
														<td>< ?php echo $i; ?></td>
														<td>< ?php echo stripslashes($row['voucherNo']); ?></td>
														<td>< ?php echo $row['date'];?></td>-->
														<!--<td>< ?php 
														$pieces = explode(" / ", $row['transaction_id']);
														echo $pieces[0]; ?></td>-->
														<td>
														<?php if($row['tableName']=='transactions') {  // for transaction
														//$pieces2 = explode(" / ", $row['ledger_id']);
															$check = $row['ledger_id_for'];
															$input = $row['ledger_id'];
														  	if($check=='employee_id'){
														 	$sql = "SELECT CONCAT(e.first_name ,' ',e.last_name) as empName, e.account_id, sg.account_id,sg.account_name,g.group_name,t.credit,t.debit FROM tbl_addemployee e inner join tbl_transactions t ON e.id='$input' inner join tbl_subaccountgroup sg ON sg.id=e.account_id inner join tbl_accountgroup g ON g.id=sg.account_id";
															$query = mysql_query($sql);
															$result = mysql_fetch_array($query); //print_r($result);
															//echo $result['group_name']."===>".$result['account_name']."===>".$result['empName'];
															echo $result['group_name'];?><br>&nbsp;<i class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i> <span style="margin-left:10px"><?php echo $result['account_name'];?></span>
															<br> <i style="margin-left:26px" class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i><span style="margin-left:10px"><?php echo $result['empName'];?>
															</span> 
															<?php
														  	
														  	} else if($check=='expenses_type'){
														 	$sql = "SELECT e.expensestype_name, e.account_id, sg.account_id,sg.account_name,g.group_name,t.credit,t.debit FROM tbl_expensetype e inner join tbl_transactions t ON e.id='$input' inner join tbl_subaccountgroup sg ON sg.id=e.account_id inner join tbl_accountgroup g ON g.id=sg.account_id";
														    $query = mysql_query($sql); 
															$result = mysql_fetch_array($query); 
															//echo $result['group_name']."===>".$result['account_name']."===>".$result['expensestype_name'];
															echo $result['group_name'];?><br>&nbsp;<i class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i> <span style="margin-left:10px"><?php echo $result['account_name'];?></span>
															<br> <i style="margin-left:26px" class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i><span style="margin-left:10px"><?php echo $result['expensestype_name'];?>
															</span> 
															<?php
														  	} else if($check=='ledger_id'){
														 	$sql = "SELECT l.ledgerName, l.account_id, sg.account_id,sg.account_name,g.group_name,t.credit,t.debit FROM tbl_ledgers l inner join tbl_transactions t ON l.id='$input' inner join tbl_subaccountgroup sg ON sg.id=l.account_id inner join tbl_accountgroup g ON g.id=sg.account_id";
														    $query = mysql_query($sql); 
															$result = mysql_fetch_array($query); 
															//echo $result['group_name']."===>".$result['account_name']."===>".$result['ledgerName'];
															echo $result['group_name'];?><br>&nbsp;<i class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i> <span style="margin-left:10px"><?php echo $result['account_name'];?></span>
															<br> <i style="margin-left:26px" class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i><span style="margin-left:10px"><?php echo $result['ledgerName'];?>
															</span> 
															<?php
															} else if($check=='customer_id'){
														 	
															$sql = "SELECT CONCAT(c.first_name ,' ',c.last_name) as cName, c.account_id, sg.account_id,sg.account_name,g.group_name,t.credit,t.debit FROM tbl_addcustomer c inner join tbl_transactions t ON c.id='$input' inner join tbl_subaccountgroup sg ON sg.id=c.account_id inner join tbl_accountgroup g ON g.id=sg.account_id";
															$query = mysql_query($sql);
															$result = mysql_fetch_array($query); //print_r($result);
															//echo $result['group_name']."===>".$result['account_name']."===>".$result['cName'];
															echo $result['group_name'];?><br>&nbsp;<i class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i> <span style="margin-left:10px"><?php echo $result['account_name'];?></span>
															<br> <i style="margin-left:26px" class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i><span style="margin-left:10px"><?php echo $result['cName'];?>
															</span> 
															<?php
														  	} //else echo "";

														  } else if($row['tableName']=='addexpenses') {  // for expenses
														  	if($row['ledger_id_for']=='expenses_type'){
														  		$input = $row['ledger_id'];
														 		$sql = "SELECT e.expensestype_name, e.account_id, sg.account_id,sg.account_name,g.group_name,t.credit,t.debit FROM tbl_expensetype e inner join tbl_transactions t ON e.id=$input inner join tbl_subaccountgroup sg ON sg.id=e.account_id inner join tbl_accountgroup g ON g.id=sg.account_id";
														    $query = mysql_query($sql); 
															$result = mysql_fetch_array($query); 
															//echo $result['group_name']."===>".$result['account_name']."===>".$result['expensestype_name'];
															echo $result['group_name'];?><br>&nbsp;<i class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i> <span style="margin-left:10px"><?php echo $result['account_name'];?></span>
															<br> <i style="margin-left:26px" class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i><span style="margin-left:10px"><?php echo $result['expensestype_name'];?>
															</span> 
															<?php
														  	} else if($row['ledger_id_for']=='ledger_id'){
														  		$input = $row['ledger_id'];
														 	$sql = "SELECT l.ledgerName, l.account_id, sg.account_id,sg.account_name,g.group_name,t.credit,t.debit FROM tbl_ledgers l inner join tbl_transactions t ON l.id=$input inner join tbl_subaccountgroup sg ON sg.id=l.account_id inner join tbl_accountgroup g ON g.id=sg.account_id";
														    $query = mysql_query($sql); 
															$result = mysql_fetch_array($query); 
															//echo $result['group_name']."===>".$result['account_name']."===>".$result['ledgerName'];
															echo $result['group_name'];?><br>&nbsp;<i class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i> <span style="margin-left:10px"><?php echo $result['account_name'];?></span>
															<br> <i style="margin-left:26px" class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i><span style="margin-left:10px"><?php echo $result['ledgerName'];?>
															</span> 
															<?php 
															} //else echo "";

														  }else {  // for meter and monthly customers

														  	if($row['ledger_id_for']=='ledger_id'){ 
														 		$input = $row['ledger_id'];
														 	$sql = "SELECT l.ledgerName, l.account_id, sg.account_id,sg.account_name,g.group_name,t.credit,t.debit FROM tbl_ledgers l inner join tbl_transactions t ON l.id=$input inner join tbl_subaccountgroup sg ON sg.id=l.account_id inner join tbl_accountgroup g ON g.id=sg.account_id";
														    $query = mysql_query($sql); 
															$result = mysql_fetch_array($query); 
															echo $result['group_name'];?><br>&nbsp;<i class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i> <span style="margin-left:10px"><?php echo $result['account_name'];?></span>
															<br> <i style="margin-left:26px" class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i><span style="margin-left:10px"><?php echo $result['ledgerName'];?>
															</span> 
															<?php } else if($row['ledger_id_for']=='customer_id'){  
														 	$input = $row['ledger_id'];
														 	$sql = "SELECT CONCAT(c.first_name ,' ',c.last_name) as cName, c.account_id, sg.account_id,sg.account_name,g.group_name,t.credit,t.debit FROM tbl_addcustomer c inner join tbl_transactions t ON c.id='$input' inner join tbl_subaccountgroup sg ON sg.id=c.account_id inner join tbl_accountgroup g ON g.id=sg.account_id";
															$query = mysql_query($sql);
															$result = mysql_fetch_array($query); //print_r($result);
															echo $result['group_name'];?><br>&nbsp;<i class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i> <span style="margin-left:10px"><?php echo $result['account_name'];?></span>
															<br> <i style="margin-left:26px" class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i><span style="margin-left:10px"><?php echo $result['cName'];?></span> <?php
														  	} //else echo " ";
													    }

														  ?>
														</td>
														<td><br/></br><?php echo stripslashes($row['totalCredit']); ?></td>
														<td><br/></br><?php echo stripslashes($row['totalDebit']); ?></td>
															</div>
															
															</div>
														</td>
													</tr>
														<?php $i++; } }?>	
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

