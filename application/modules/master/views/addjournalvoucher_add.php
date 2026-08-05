<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addexpenses/">Expenses</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addexpenses/search/">Search Expenses</a></li>
		<li class="breadcrumb-item active">add</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-wallet"></i>
			Manage <span class="fw-300">Addjournalvoucher Add</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addjournalvoucher Add <span class="fw-300"><i>Details</i></span></h2>
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
														<h5 class="mb-3">Journal Voucher-Add </h5>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Voucher No. : </label>
																	<input class="form-control" type="text" id="" name="voucherNo"  readonly value="<?php echo $voucher; ?>"/>
																	<?php echo form_error('voucher'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Date :</label>
																	<input class="form-control" type="text" id="date" placeholder="dd-mm-yyyy" name="date" value="<?php echo ($this->input->post('date') != '')?date('d-m-Y',strtotime($this->input->post('date'))):date('d-m-Y');?>"  />
																	<!--<input type="text" name="date" id="date"  class="col-10 col-sm-10" placeholder="DD-MM-YYYY" value="<?php echo ($this->input->post('date') != '')?date('d-m-Y',strtotime($this->input->post('date'))):date('d-m-Y');?>" readonly="readonly"  required/>-->
																	<?php echo form_error('date'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Ledger 1 (Credit): </label>
																	<select name="transaction_id" id="transaction_id" class="form-control" required>
																	 <option value="">--Select--</option>
                                                                     <?php 
                                                                     //foreach($ledger as $value){
 																		for($j=0;$j<=$countledger1-1;$j++){
                                                                     	?>
																	  <option value="<?php echo $ledger1[$j]['id']." / ledger_id";?>"><?php echo $ledger1[$j]['ledgerName'];?></option>
                                                                      <?php }
                                                                      for($j=0;$j<=$countcustomer-1;$j++){
                                                                     	?>
																	  <option value="<?php echo $customer[$j]['id']." / customer_id";?>"><?php echo $customer[$j]['ledgerName'];?></option>
                                                                      <?php }
                                                                      for($j=0;$j<=$countemployee-1;$j++){
                                                                     	?>
																	  <option value="<?php echo $employee[$j]['id']." / employee_id";?>"><?php echo $employee[$j]['ledgerName'];?></option>
                                                                      <?php }
                                                                      for($j=0;$j<=$countexpenseType-1;$j++){
                                                                     	?>
																	  <option value="<?php echo $expenseType[$j]['id']." / expenses_type";?>"><?php echo $expenseType[$j]	['ledgerName'];?></option>
                                                                      <?php }
                                                                      // } ?>
																	</select>
																	<?php echo form_error('transaction_id'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Ledger 2 (Debit): </label>
																	<select name="ledger_id" id="ledger_id" class="form-control" required>
																	 <?php 
                                                                     //foreach($ledger as $value){
 																		for($j=0;$j<=$countledger1-1;$j++){
                                                                     	?>
																	  <option value="<?php echo $ledger1[$j]['id']." / ledger_id";?>"><?php echo $ledger1[$j]['ledgerName'];?></option>
                                                                      <?php }
                                                                      for($j=0;$j<=$countcustomer-1;$j++){
                                                                     	?>
																	  <option value="<?php echo $customer[$j]['id']." / customer_id";?>"><?php echo $customer[$j]['ledgerName'];?></option>
                                                                      <?php }
                                                                      for($j=0;$j<=$countemployee-1;$j++){
                                                                     	?>
																	  <option value="<?php echo $employee[$j]['id']." / employee_id";?>"><?php echo $employee[$j]['ledgerName'];?></option>
                                                                      <?php }
                                                                      for($j=0;$j<=$countexpenseType-1;$j++){
                                                                     	?>
																	  <option value="<?php echo $expenseType[$j]['id']." / expenses_type";?>"><?php echo $expenseType[$j]	['ledgerName'];?></option>
                                                                      <?php }
                                                                      // } ?>
																	</select>
																	<?php echo form_error('ledger_id'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Amount:</label>
																	<input class="form-control" type="text" id="credit" name="credit" value="<?php echo $this->input->post('credit'); ?>" required/>
																	<?php echo form_error('credit'); ?>
																</div>
															</div>
														</div>
													</fieldset>

													<div class="form-group mt-3">
														<div class="row">
															<div class="col-md-12">
																<a href="<?php echo ADMIN_URL;?>addexpenses" class="btn btn-secondary">Cancel</a>
																<input type="submit" class="btn btn-primary" name="add" id="add4" value="Add">
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
		
		function customer_type_values(){
			$("#showcustomers").hide();			
			if($("#customer_type").val()=='monthlycustomer'){
				$("#showcustomers").show();
			}
			else if($("#customer_type").val()=='metercustomer'){
				$("#showcustomers").hide();
			}
		}
	
		</script>
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
<script>
$('#add').click(function(){
    var expenseid = $('#expenses_id').val();
	var expensestype = $('#expenses_type').val();
	var quantity = $('#quantity').val();
	var date = $('#date').val();
	var amount = $('#amount').val();
	var invoi_id = $('#time_format').val();
	if(expenseid != '' && expensestype != '' && quantity != '' && date !='' && amount != ''){
				var url = '<?php echo ADMIN_URL;?>addexpenses/invoicereceipt_expense/'+expenseid+'/'+expensestype+'/'+quantity+'/'+date+'/'+invoi_id+'/'+amount;
				window.open( url , "popupWindow", "width=1024,height=600,scrollbars=yes");	
	}
});
</script>

