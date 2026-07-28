<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addexpenses/">Expenses</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addexpenses/search/">Search Expenses</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-money-bill"></i>
			Manage <span class="fw-300">Addexpenses View</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addexpenses View <span class="fw-300"><i>Details</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
<table id="user" class="table table-bordered table-striped" style="clear: both">
											<tbody>
												<tr>
													<td style="width:25%;">Ledger : </td>
													<td style="width:75%"><?php echo stripslashes(str_replace('\n','',$record['ledgerName']));?></td>
												</tr>
												<!--<tr>
													<td style="width:25%;">Account Subgroup Type : </td>
													<td style="width:75%">< ?php echo stripslashes(str_replace('\n','',$record['subName']));?></td>
												</tr>-->
												<tr>
													<td style="width:25%;">Expenses-Id: </td>
													<td style="width:75%"><?php echo stripslashes(str_replace('\n','',$record['expenses_id']));?></td>
												</tr>
												<tr>
													<td>Expenses:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['expenses_type']));?></td>
												</tr>
												<tr>
													<td>Amount :</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['amount'])); ?></td>
												</tr>
												<tr>
													<td>Date :</td>
													<td><?php echo date('d-M-Y',strtotime($record['create_date_time']));?></td>
												</tr>
												<tr>
													<td>Quantity :</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['quantity'])); ?></td>
												</tr>
				
												<tr>
													<td>Total:</td>
													<td><?php echo stripslashes(str_replace('\n','',$record['total'])); ?></td>
												</tr>
												
											</tbody>
										</table>
				
									</div>
					</div>
				</div>
			</div>
		</div>
	
</main>
<?php include('footer.php'); ?>
</body>
</html>


