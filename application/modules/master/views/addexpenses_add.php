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
			<i class="subheader-icon fal fa-money-bill"></i>
			Manage <span class="fw-300">Addexpenses Add</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addexpenses Add <span class="fw-300"><i>Details</i></span></h2>
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
														<h5 class="mb-3">Expenses-Add </h5>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">  
																	<label class="form-label">Ledger : </label>
																	<select name="ledger_id" id="ledger_id" class="form-control" required>
																	 <option value="">--Select--</option>
                                                                     <?php foreach($ledger as $key => $value){?>
																	  <option value="<?php echo $value['id'];?>"><?php echo $value['ledgerName'];?></option>
                                                                      <?php } ?>
																	</select>
																	<?php echo form_error('ledger_id'); ?>
																</div>
															</div>
														</div>
														<!--<div class="form-group">
															<label class="col-md-4 control-label">Account Subgroup Type : </label>
															<div class="col-md-4">
																<select name="account_id" id="account_id" class="form-control" required>
																	 <option value="">-Select-</option>
                                                                     <?php foreach($account as $key => $value){?>
																	  <option value="<?php echo $value['id'];?>">< ?php echo $value['account_name'];?></option>
                                                                      <?php } ?>
																</select>
																<?php echo form_error('account_id'); ?>
															</div>
														</div>-->
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Expenses-Id : </label>
																	<input class="form-control" type="text" id="" name="expenses_id"  readonly value="<?php echo $exp; ?>"/>
																	<?php echo form_error('expenses_id'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Expenses :</label>
																	<select class="form-control" name="expenses_type" id="expenses_type"  required>
																    <option value="">--Select--</option>
                                                                    <?php foreach($expenses as $key => $value){?>
																    <option value="<?php echo $value['id'];?>"> <?php echo $value['expensestype_name'];?></option>
                                                                    <?php } ?>
                                                                    <!--<option value="mushaar"> Mushaar</option>
																    <option value="shidaal">Shidaal</option>
																    <option value="spar partska">spar partska</option>
																	<option value="stationary">Stationary</option>
																	<option value="utility, rent">Utility, Rent</option>
																	<option value="nadafada">Nadafada</option>
																	<option value="ather expenses">ather expenses</option>-->
																	</select>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">  Quantity: </label>
																	<select class="form-control" name="quantity" id="quantity" required>
																		<option value="">--Select--</option>
																		<?php for($i=1;$i<=10;$i++){ ?> 
																		<option value="<?php echo $i; ?>" <?php if($this->input->post('quantity')==$i){?> selected="selected" <?php }?>><?php echo $i; ?></option>
																		<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Date : </label>
																	<input class="form-control" type="text" id="date" placeholder="dd-mm-yyyy" name="date" value="<?php echo ($this->input->post('date') != '')?date('d-m-Y',strtotime($this->input->post('date'))):date('d-m-Y');?>"  />
																	<!--<input type="text" name="date" id="date"  class="col-10 col-sm-10" placeholder="DD-MM-YYYY" value="<?php echo ($this->input->post('date') != '')?date('d-m-Y',strtotime($this->input->post('date'))):date('d-m-Y');?>" readonly="readonly"  required/>-->
																	<?php echo form_error('date'); ?>
																</div>
															</div>
														</div>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label"> Amount: </label>
																	<input class="form-control" type="text" id="amount" name="amount" value="<?php echo $this->input->post('amount'); ?>" required/>
																	<?php echo form_error('amount'); ?>
																	<input type="hidden"  class="form-control"  id="time_format" name="time_format"  value="WTEX-<?php echo strtotime("now"); ?>" readonly ="readonly"/>
																</div>
															</div>
														</div>
														
							
													</fieldset>

													<div class="form-group mt-3">
														<div class="row">
															<div class="col-md-12">
																<a href="<?php echo ADMIN_URL;?>addexpenses" class="btn btn-secondary">Cancel</a>
																<input type="submit" class="btn btn-primary" name="add" id="add" value="Add">
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

