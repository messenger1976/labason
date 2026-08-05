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
			Manage <span class="fw-300">Addexpenses Edit</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addexpenses Edit <span class="fw-300"><i>Details</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
<section id="widget-grid" class="">

					<!-- row -->

					<div class="row">

						<!-- a blank row to get started -->
						<div class="col-sm-6 col-lg-12">
						

								<!-- your contents here -->
								<form class="form-horizontal" role="form" name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
										  	
											<?php if($msg != ''){?>
											<div class="alert alert-success alert-dismissible fade show">
												<button type="button" class="close" data-dismiss="alert">
												<i class="fal fa-times"></i>
												</button>
												<p>
													<i class="fal fa-check"></i>
													<?php echo $msg?$msg:'';?>
												</p>
											</div>
											<?php } ?>	
											
											<fieldset>
														<h5 class="mb-3">Expenses-Edit </h5>
														<div class="form-group">
															<label class="col-md-2 control-label">Ledger : </label>
															<div class="col-md-4">
																<select name="ledger_id" id="ledger_id" class="form-control" required >
																	 <option value="">--Select--</option>
                                                                     <?php foreach($ledger as $key => $value){?>
																	  <option value="<?php echo $value['id'];?>" <?php if($record['ledger_id'] == $value['id']){echo 'selected';}?>><?php echo $value['ledgerName'];?></option>
                                                                      <?php } ?>
																</select>
																<?php echo form_error('ledger_id'); ?>
															</div>
														</div>
														<!--<div class="form-group">
															<label class="col-md-2 control-label">Account Subgroup Type : </label>
															<div class="col-md-4">
																<select name="account_id" id="account_id" class="form-control" required >
																	 <option value="">-Select-</option>
                                                                     <?php foreach($account as $key => $value){?>
																	  <option value="<?php echo $value['id'];?>" <?php if($record['account_id'] == $value['id']){echo 'selected';}?>><?php echo $value['account_name'];?></option>
                                                                      <?php } ?>
																</select>
																<?php echo form_error('account_id'); ?>
															</div>
														</div>-->
														<div class="form-group">
															<label class="col-md-2 control-label">Expenses-Id : </label>
															<div class="col-md-4">
																<input class="form-control" type="text" id="expenses_id" name="expenses_id" value="<?php echo $record['expenses_id']; ?>" readonly/>
                                                                <?php echo form_error('expenses_id'); ?>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-md-2 control-label"> Quantity :</label>
															<div class="col-md-4">
																 <select class="form-control" name="quantity" id="quantity" required/>
																 <option value="" <?php if($record['quantity']==''){?> selected="selected" <?php }?>>--Select--</option>
															     <?php for($i=1;$i<=10;$i++){ ?> 
																 <option value="<?php echo $i; ?>" <?php if($record['quantity']==$i){?> selected="selected" <?php }?>><?php echo $i; ?></option>
																 <?php } ?>
																 </select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-md-2 control-label"> Expenses : </label>
															<div class="col-md-4">
																<select class="form-control" name="expenses_type" id="expenses_type" required>
                                                                <option value="">--Select--</option>
                                                                  <?php foreach($expenses as $key => $value){?>
																    <option value="<?php echo $value['id'];?>" <?php if($record['expenses_type'] == $value['id']){ ?> selected <?php } ?>> <?php echo $value['expensestype_name'];?></option>
                                                                    <?php } ?>
																  <!--<option value="">--Select--</option>
                                                                  
																  <option value="Mushaar"<?php if($record['expenses_type']=='mushaar'){ ?> selected <?php } ?>>Mushaar</option>
																  <option value="Shidaal"<?php if($record['expenses_type']=='shidaal'){ ?> selected <?php } ?>>Shidaal</option>
																  <option value="spar partska"<?php if($record['expenses_type']=='sparpartska'){ ?> selected <?php } ?>>spar partska</option>
																  <option value="Stationary"<?php if($record['expenses_type']=='stationary'){ ?> selected <?php } ?>>Stationary</option>
																  <option value="Utility, Rent"<?php if($record['expenses_type']=='utilityrent'){ ?> selected <?php } ?>>Utility, Rent</option>
																  <option value="Nadafada"<?php if($record['expenses_type']=='nadafada'){ ?> selected <?php } ?>>Nadafada</option>
																  <option value="ather expenses"<?php if($record['expenses_type']=='atherexpenses'){ ?> selected <?php } ?>>ather expenses</option>-->
																</select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-md-2 control-label"> Amount : </label>
															<div class="col-md-4">
																<input class="form-control" type="text" id="amount" name="amount"  value="<?php echo $record['amount']; ?>" required/>
                                                                <?php echo form_error('amount'); ?>
															</div>
														</div>
														
													</fieldset>
													
													
													
													<div class="form-group mt-3">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>addexpenses" class="btn btn-secondary">Cancel</a>
																<input type="submit" class="btn btn-primary" name="edit" id="edit" value="Edit">
															</div>
														</div>
									</div>
								</div>
							</form>
								    
								
									
						
					</div>
                    

						
					<!-- end row -->

				</section>
			<!-- end widget grid -->
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
</body>
</html>
<script type="text/javascript">
var curDate = '<?php echo date('d-m-Y') ?>';	
function fun_calendor(field){
	$("#"+field).focus();
} 
$(document).ready(function(){
	$("#date").datepicker({
		showAnim: null,
		dateFormat: 'dd-mm-yy',
		// showOn: 'both',
		buttonImage: '<?php echo site_url();?>images/calender.jpg',
		buttonImageOnly: true,
		firstDay: 1,
		nextText: '',
		prevText: '',
		numberOfMonths: [1, 1],
		//defaultDate: new Date(curDate),
		//minDate: curDate,
		//maxDate: ''
	});
	
});

</script>

