<?php
	$sa4_page_icon = 'fal fa-chart-bar';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Trialbalance';
	$sa4_loading_label = 'Trialbalance';
	$sa4_dt_entity = 'trialbalance';
	$sa4_panel_id = 'panel-trialbalance';
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
				<div id="panel-trialbalance" class="panel">
					<div class="panel-hdr">
						<h2>Trialbalance <span class="fw-300"><i>Listing</i></span></h2>
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
								                        <th data-hide="expand">Credit</th>
								                        <th data-hide="expand">Debit</th>
													</tr>
												</thead>
												
												<!--to echo data-->
												<tbody>
												  <?php
												//		if(count($record) > 0){
															$i=1;
															$group = array();
															//$group = array(array("id","name"));
															$subgroup = array();
															$ledger = array();
															$credit = array();
															$debit = array();
															foreach($record as $key => $row){ //$print_r($record);
													?>   
													<tr>
													<td><?php echo $i;//." / ".$row['id'];?></td>
													
														<td colspan="3">
														<?php 
															 $check = $row['idfor'];
															 $input = $row['lid'];
														  	if($check=='employee_id'){
														 	$sql = "SELECT CONCAT(e.first_name ,' ',e.last_name) as empName, e.account_id, sg.account_id,sg.id,sg.account_name,g.group_name,t.credit,t.debit FROM tbl_addemployee e inner join tbl_transactions t ON e.id='$input' inner join tbl_subaccountgroup sg ON sg.id=e.account_id inner join tbl_accountgroup g ON g.id=sg.account_id";
															$query = mysql_query($sql);
															$result = mysql_fetch_array($query); //print_r($result);
															//$group['id'] = $result['account_id'];
															//$group['name'] = $result['group_name'];
															$group[] = $result['account_id']." / ".$result['group_name'];
															echo $result['group_name'];?><br>&nbsp;<i class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i> <span style="margin-left:10px"><?php 
															//$subgroup['gid'] = $result['account_id'];
															//$subgroup['name'] = $result['account_name'];
															$subgroup[] = $result['account_id']." / ".$result['id']." / ".$result['account_name'];
															echo $result['account_name'];?></span>
															<br> <i style="margin-left:26px" class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i><span style="margin-left:10px"><?php 
															//$ledger['gid'] = $result['account_id'];
															//$ledger['sgid'] = $result['id'];
															//$ledger['name'] = $result['empName'];
															$ledger[] = $result['account_id']." / ".$result['id']." / ".$result['empName'];
															echo $result['empName'];?>
															</span> 
															<?php
														  	
														  	} else if($check=='expenses_type'){
														 	$sql = "SELECT e.expensestype_name, e.account_id, sg.account_id,sg.id,sg.account_name,g.group_name,t.credit,t.debit FROM tbl_expensetype e inner join tbl_transactions t ON e.id='$input' inner join tbl_subaccountgroup sg ON sg.id=e.account_id inner join tbl_accountgroup g ON g.id=sg.account_id";
														    $query = mysql_query($sql); 
															$result = mysql_fetch_array($query);
															//$group[] = $result['group_name'];
															//$group['id'] = $result['account_id'];
															//$group['name'] = $result['group_name'];
															$group[] = $result['account_id']." / ".$result['group_name'];
															echo $result['group_name'];?><br>&nbsp;<i class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i> <span style="margin-left:10px"><?php 
															//$subgroup[] = $result['account_name'];
														//	$subgroup['id'] = $result['account_id'];
														//	$subgroup['name'] = $result['account_name'];
															$subgroup[] = $result['account_id']." / ".$result['id']." / ".$result['account_name'];
															echo $result['account_name'];?></span>
															<br> <i style="margin-left:26px" class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i><span style="margin-left:10px"><?php 
															//$ledger[] = $result['expensestype_name'];
														//	$ledger['gid'] = $result['account_id'];
														//	$ledger['sgid'] = $result['id'];
														//	$ledger['name'] = $result['expensestype_name'];
															$ledger[] = $result['account_id']." / ".$result['id']." / ".$result['expensestype_name'];
															echo $result['expensestype_name'];?>
															</span> 
															<?php
														  	} else if($check=='ledger_id'){
														 	$sql = "SELECT l.ledgerName, l.account_id, sg.account_id,sg.account_name,sg.id,g.group_name,t.credit,t.debit FROM tbl_ledgers l inner join tbl_transactions t ON l.id='$input' inner join tbl_subaccountgroup sg ON sg.id=l.account_id inner join tbl_accountgroup g ON g.id=sg.account_id";
														    $query = mysql_query($sql); 
															$result = mysql_fetch_array($query); 
															//$group[] = $result['group_name'];
														//	$group['id'] = $result['account_id'];
														//	$group['name'] = $result['group_name'];
															$group[] = $result['account_id']." / ".$result['group_name'];
															echo $result['group_name'];?><br>&nbsp;<i class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i> <span style="margin-left:10px"><?php 
															//$subgroup[] = $result['account_name'];
														//	$subgroup['id'] = $result['account_id'];
														//	$subgroup['name'] = $result['account_name'];
															$subgroup[] = $result['account_id']." / ".$result['id']." / ".$result['account_name'];
															echo $result['account_name'];?></span>
															<br> <i style="margin-left:26px" class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i><span style="margin-left:10px"><?php 
															//$ledger[] = $result['ledgerName'];
														//	$ledger['gid'] = $result['account_id'];
														//	$ledger['sgid'] = $result['id'];
														//	$ledger['name'] = $result['ledgerName'];
															$ledger[] = $result['account_id']." / ".$result['id']." / ".$result['ledgerName'];
															echo $result['ledgerName'];?>
															</span> 
															<?php
															} else if($check=='customer_id'){
														 	
															 $sql = "SELECT CONCAT(c.first_name ,' ',c.last_name) as cName, c.account_id, sg.account_id,sg.id,sg.account_name,g.group_name,t.credit,t.debit FROM tbl_addcustomer c inner join tbl_transactions t ON c.id='$input' inner join tbl_subaccountgroup sg ON sg.id=c.account_id inner join tbl_accountgroup g ON g.id=sg.account_id group by g.id,sg.account_id";
															$query = mysql_query($sql);
															$result = mysql_fetch_array($query); //print_r($result);
															//$group[] = $result['group_name'];
														//	$group['id'] = $result['account_id'];
														//	$group['name'] = $result['group_name'];
															$group[] = $result['account_id']." / ".$result['group_name'];
															echo $result['group_name'];?><br>&nbsp;<i class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i> <span style="margin-left:10px"><?php 
															//$subgroup[] = $result['account_name'];
														//	$subgroup['id'] = $result['account_id'];
														//	$subgroup['name'] = $result['account_name'];
															$subgroup[] = $result['account_id']." / ".$result['id']." / ".$result['account_name'];
															echo $result['account_name'];?></span>
															<br> <i style="margin-left:26px" class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i><span style="margin-left:10px"><?php 
															//$ledger[] = $result['cName'];
														//	$ledger['gid'] = $result['account_id'];
														//	$ledger['sgid'] = $result['id'];
														//	$ledger['name'] = $result['cName'];
															$ledger[] = $result['account_id']." / ".$result['id']." / ".$result['cName'];
															echo $result['cName'];?>
															</span> 
															<?php
														  	} //else echo "";
														  	//print_r($result['account_id']." / ".$result['id']);exit;
														//  } 
														  ?>
														<span style="margin-left:100px"><?php 
														$credit[] = $result['account_id']." / ".$result['id']." / ".$row['totalCredit'];
														echo stripslashes($row['totalCredit']); ?></span>
														<span style="margin-left:100px"><?php 
														$debit[] = $result['account_id']." / ".$result['id']." / ".$row['totalDebit'];
														echo stripslashes($row['totalDebit']); ?></span>
															</div>
															</div>
														</td>
													</tr>
														<?php $i++; }

														// } 
														print_r($group);print_r($subgroup);print_r($ledger);//print_r($credit);print_r($debit);
														?>	
												</tbody>
												<tbody>
												  <?php
												//		if(count($record) > 0){
															$i=1;
															$uGroup = array_unique($group);
															//print_r($uGroup);//exit;
															foreach($uGroup as $row) { 
													?>   
													<tr>
													<td><?php echo $i;//." / ".$row['id'];?></td>
													
														<td colspan="3">
														<?php
															$gName = explode(" / ", $row);
															$grp = $gName[0];
															echo $gName[1]; ?>
														<?php	foreach($subgroup as $row2) { 
																$subName = explode(" / ", $row2);
																$subgrp = $subName[0]; 
																if($grp==$subgrp){ 
															?><br>&nbsp;<i class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i> 
																<span style="margin-left:10px"><?php echo $subName[2];?></span>
																<!--closing brase shift to 342-->
															
															<?php foreach($ledger as $row3)  {  
																$name = explode(" / ", $row3);
																$lgrp = $name[0];
																$lsubgrp = $name[1];
																$lName = $name[2];
																if($lgrp==$grp && $lsubgrp==$subgrp){  //echo "hi";
																?>
															<br> <i style="margin-left:26px" class="fal fa-level-up fa-rotate-90" aria-hidden="true"></i>
															<span style="margin-left:10px">
															<?php echo $lName;?>
															</span> 
															<span style="margin-left:100px">
															<?php } 
															}
															foreach ($credit as $row4) {
															$cred = explode(" / ", $row4);
															$cgrp = $cred[0];
															$csubgrp = $cred[1];
															$cCredit = $cred[2];
															if($cgrp==$grp && $csubgrp==$subgrp){
															echo stripslashes($cCredit); ?></span>
															<span style="margin-left:100px">
															<?php } 
														   }
															?>
															<?php 
															foreach ($debit as $row5) {
															$deb = explode(" / ", $row5);
															$dgrp = $deb[0];
															$dsubgrp = $deb[1];
															$dDredit = $deb[2];
															if($dgrp==$grp && $dsubgrp==$subgrp){
															echo stripslashes($dDredit); ?></span><br/>
															<?php } 
														      }
															 } // from line 305
														  } 
														  ?>
														
															</div>
															</div>
														</td>
													</tr>
														<?php $i++; }

														// } ?>	
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

