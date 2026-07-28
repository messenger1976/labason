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
			<i class="subheader-icon fal fa-wallet"></i>
			Manage <span class="fw-300">Addjournalvoucher Edit</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addjournalvoucher Edit <span class="fw-300"><i>Details</i></span></h2>
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
														<h5 class="mb-3">Journal Voucher-Edit </h5>
														<div class="form-group">
															<label class="col-md-4 control-label">Voucher No.: </label>
															<div class="col-md-4">
																<input class="form-control" type="text" id="voucherNo" name="voucherNo" value="<?php echo $record['voucherNo']; ?>" readonly/>
                                                                <?php echo form_error('voucherNo'); ?>
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label"> Date : </label>
															<div class="col-md-4">
																<input class="form-control" type="text" id="date" placeholder="dd-mm-yyyy" name="date" value="<?php echo $record['date'];?>"  />
															  <?php echo form_error('date'); ?>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-md-4 control-label"> Ledger 1 : </label>
															<div class="col-md-4">
																<select class="form-control" name="transaction_id" id="transaction_id" required>
                                                                <option value="">--Select--</option>
                                                                  <?php foreach($ledger as $key => $value){?>
																    <option value="<?php echo $value['ledgerName'];?>" <?php if($record['transaction_id'] == $value['ledgerName']){ ?> selected <?php } ?>> <?php echo $value['ledgerName'];?></option>
                                                                    <?php } ?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label"> Ledger 2 : </label>
															<div class="col-md-4">
																<select class="form-control" name="ledger_id" id="ledger_id" required>
                                                                <option value="">--Select--</option>
                                                                  <?php foreach($ledger as $key => $value){?>
																    <option value="<?php echo $value['ledgerName'];?>" <?php if($record['ledger_id'] == $value['ledgerName']){ ?> selected <?php } ?>> <?php echo $value['ledgerName'];?></option>
                                                                    <?php } ?>
																</select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-md-4 control-label"> Amount : </label>
															<div class="col-md-4">
																<input class="form-control" type="text" id="credit" name="credit"  value="<?php echo $record['credit']; ?>" required/>
                                                                <?php echo form_error('amount'); ?>
															</div>
														</div>
													</fieldset>
													<div class="form-group mt-3">
														<div class="row">
															<div class="col-md-12">
																 <a href="<?php echo ADMIN_URL;?>addexpenses" class="btn btn-secondary">Cancel</a>
																<input type="submit" class="btn btn-primary" name="edit" id="edit3" value="Edit">
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

