<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>paymentmonthlycustomer/">Monthly Customer Bills</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>paymentmonthlycustomer/generate/">Monthly Customer Generate Bills</a></li>
		<li class="breadcrumb-item active">Add</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-wallet"></i>
			Manage <span class="fw-300">Paymentmonthlycustomer Add</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Paymentmonthlycustomer Add <span class="fw-300"><i>Details</i></span></h2>
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
								<div class="panel panel-default">
									
									
				
										<form class="form-horizontal" role="form" name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
										  	
											<?php if($msg != ''){?>
											<div class="alert alert-block alert-success">
												<button type="button" class="close" data-dismiss="alert">
												<i class="icon-remove"></i>
												</button>
												<p>
													<i class="icon-ok"></i>
													<?php echo $msg?$msg:'';?>
												</p>
											</div>
											<?php } ?>	
											
											<fieldset>
														<legend>Meter Customer Bills -Add </legend>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<span class="input-group-addon"><i class="icon-user"></i><strong>Search : </strong></span>
																<input  class="form-control"  id="search_box_id" name="name" id="name" required/>
																<?php echo form_error('name'); ?>
																</div>
															</div>
														</div>
														<input type="submit" class="form-control"  id="search_box_id" name="name" id="name" value = "Search" style="width: auto; float: right; margin-right: 502px; background: #3276b1; color:#fff;" />
															<p style ="color:#428BCA">Search by Customer-Id, Phone, Email</p>
														<div id="names">
														
														</div>	
															<input type="hidden" name="customer_id" id="customer_id" value="<?php echo $this->input->post('customer_id'); ?>">
															<input type="hidden" name="customer_tamy" id="customer_tamy" value="<?php echo $this->input->post('customer_tamy'); ?>">
															<input type="hidden" name="plan_name" id="plan_name" value="<?php echo $this->input->post('plan_name'); ?>">
															<input type="hidden" name="status" id="status" value="<?php echo $this->input->post('status'); ?>">
															<input type="hidden" name="startdate" id="startdate" value="<?php echo $this->input->post(startdate); ?>">
															<input type="hidden" name="enddate" id="enddate" value="<?php echo $this->input->post('enddate'); ?>">
															
														<div class="col-12" id="meterincomeDiv" style="margin-top: 13px; margin-bottom:20px;"></div> 
														<div style="clear:both"></div>
														<div id="hideclass" style="display:none; padding:20px; box-shadow: 0px 0px 3px 1px rgba(0,0,0,0.75);    margin: 14px;" >
															<div class="form-group" id="names">

															</div>
														
															<div class="form-group"style=" width: 60%;">
																<label class="col-md-4 control-label"> Amount : </label>
																<div class="col-md-4">
																	<div class="test_deep"><h1>&#8377;&nbsp;&nbsp;<span id="deepmala">0</span>/-</h1></div>
																	<input type="hidden"  class="form-control"  id="input_amount" name="input_amount"  value="<?php echo $this->input->post('input_amount'); ?>" readonly ="readonly"/>
																</div>
															</div>
															
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label"> Balance : </label>
																<div class="col-md-4">
																	<input type="text"  class="form-control"  id="balance_text" name="balance_text"  value="<?php echo $this->input->post('balance_text'); ?>" readonly ="readonly"/>
																	<?php echo form_error('balance'); ?>
																</div>
															</div>
															
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">Total Amount : </label>
																<div class="col-md-4">
																	<div class="test_deep"><h1>&#8377;&nbsp;&nbsp;<span id="deeksha">0</span>/-</h1></div>
																	<input type="hidden"  class="form-control"  id="final_total_amount" name="final_total_amount"  value="<?php echo $this->input->post('final_total_amount'); ?>" readonly ="readonly"/>
																    <input type="hidden"  class="form-control"  id="time_format" name="time_format"  value="WTMO-<?php echo strtotime("now"); ?>" readonly ="readonly"/>
																</div>
															</div>
															
															<!--<div class="form-group">
																<label class="col-md-4 control-label"> Month : </label>
																<div class="col-md-4">
																	<input  type="text" class="form-control" id="month_name" name="month_name" value="" readonly ="readonly"/>
																	<input  type="hidden" class="form-control" id="month" name="month" value="<?php echo $this->input->post('month'); ?>" readonly ="readonly"/>
																	
																	<?php echo form_error('month'); ?>
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-md-4 control-label">  Year : </label>
																<div class="col-md-4">
																	<input  type="text"  class="form-control"  id="year" name="year" value="<?php echo $this->input->post('year'); ?>" readonly ="readonly"/>
																	<?php echo form_error('year'); ?>
																</div>
															</div>-->
														<div class="form-group" style=" width: 60%;">
															<label class="col-md-4 control-label">Ledger : </label>
															<div class="col-md-4">
																<select name="ledger_id" id="ledger_id" class="form-control" required>
																	 <option value="">--Select--</option>
                                                                     <?php foreach($ledger as $key => $value){?>
																	  <option value="<?php echo $value['id'];?>"><?php echo $value['ledgerName'];?></option>
                                                                      <?php } ?>
																</select>
																<?php echo form_error('ledger_id'); ?>
															</div>
														</div>	
														<div class="form-group" style=" width: 60%;">
																		<label class="col-md-4 control-label"> Currency :</label>
																		<div class="col-md-4">
																			<select class="form-control"  id="currency" name="currency"  value="<?php echo $this->input->post('currency'); ?>" required/>                               
                                                                            <option value="">---Select---</option>
                                                                            <option value="USD">USD</option>
                                                                            <option value="Shilling Som">Shilling Som</option>
                                                                            </select>
																			<?php echo form_error('currency'); ?>
																		</div>
																	</div>
															
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label"> Pay Amount :</label>
																<div class="col-md-4">
																	<input  type="text"  class="form-control"  id="pay_amount" name="pay_amount"  value="<?php echo $this->input->post('pay_amount'); ?>" required/>
																	<?php echo form_error('pay_amount'); ?>
																</div>
															</div>
													
													</fieldset>
													</div>

														<div class="form-actions">
															<div class="row">
																<div class="col-md-12">
																	
																	 <a href="<?php echo ADMIN_URL;?>paymentmonthlycustomer" class="btn btn-secondary">Cancel</a>
																	<input type="submit" class="btn btn-primary" name="add" id="add" value="Add">
																</div>
															</div>
														</div>
										</form>
				
									</div>
								    
								
								</div>	
						</div>
					</div>
                    

						
					<!-- end row -->

				</section>
				<!-- end widget grid -->

					

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
<script>
$('#search_box_id').on('change', function() {
	
	var id = $(this).val();
	$.ajax({
                type: 'POST',
                url: '<?php echo ADMIN_URL;?>paymentmonthlycustomer/get_custmer_all_data/',
                data: {id: id},
                success: function(data) {
					$("#meterincomeDiv").html(data);
					$('#hideclass').show();
					
                }
            });
});	
//$("#meterincomeDiv").html(data);
var paybtnid = $(this).data('pay-val-id');

$(document).on('click','.pay_button',function(e){
	
	var buttonid = $(this).attr('id');
	var paybtnid = $(this).data('pay-val-id');
	
	var customer = $('#cusomer_id').val();
    var amount = $('#amount').val();
	var balance = $('#balance').val();
	var balanace_name = $('#balanceid_'+paybtnid).val();
	var plan_id = $('#plan').val();
	var status_name = $('#status_n').val();
	var startdate = $('#startdate_n').val();
	var enddate = $('#enddate_n').val();
	var monthname = $('#month_'+paybtnid).val();
	var monthid = $('#monthid_'+paybtnid).val();
	var year = $('#year_'+paybtnid).val();
	var customername = $("#cusomer_tam").val();
	
	
	var total_amount = parseInt(amount)+parseInt(balance);
	
	$('#customer_id').val(customer);
	$('#status').val(status_name);
	$('#deepmala').text(amount);
	$("#input_amount").val(amount);
	$("#balance_text").val(balance);
	$('#deeksha').text(total_amount);
	$('#final_total_amount').val(total_amount);
	$('#plan_name').val(plan_id);
	$('#balence').val(balanace_name);
	$('#startdate').val(startdate);
	$('#enddate').val(enddate);
	$('#customer_tamy').val(customername);
	$('#month_name').val(monthname);
	$('#month').val(monthid);
	$('#year').val(year);
	
	//$("#process_payment_amount_text").html("testing <b>1 2 3</b>");
	
	 e.preventDefault();

        $("body, html").animate({ 
            scrollTop: $( $('#custom-payment-block') ).offset().top 
        }, 600);
	
});

$('#search_box_id').on('change', function() {
	
	var id = $(this).val();
	$.ajax({
                type: 'POST',
                url: '<?php echo ADMIN_URL;?>paymentmonthlycustomer/get_custmer_name/',
                data: {id: id},
                success: function(data) {
					$("#names").html(data);
				}
            });
});
$('#aftermeter').on('change', function() {
	
	var id = $(this).val();
	var oldmeter = $("#oldmeter").val();
	$.ajax({
                type: 'POST',
                url: '<?php echo ADMIN_URL;?>addpaymentcustomer/get_calculation/',
                data: {id: id, oldmeter: oldmeter},
                success: function(data) {
					//alert(data);
					$("#total_amount").val(data);
					
                }
            });

});	

$('#add').click(function(){
	var name = $('#first_name').val();
	var address = $('#address').val();
	var startdate_n = $('#startdate_n').val();
	var enddate_n = $('#enddate_n').val();
	var planname = $('#planname').val();
	var days = $('#days').val();
	var pay_amount = $('#pay_amount').val();
	var amount = $('#amount').val();
	var customer = $('#customer_tamy').val();
	var invoi_id = $('#time_format').val();
	var currency = $('#currency').val();
	if(customer != '' && currency !='' && pay_amount !=''){
				var url = '<?php echo ADMIN_URL;?>paymentmonthlycustomer/monthlyreceipt_single/'+customer+'/'+name+'/'+planname+'/'+days+'/'+amount+'/'+startdate_n+'/'+enddate_n+'/'+pay_amount+'/'+invoi_id+'/'+address+'/'+currency;
				window.open( url , "popupWindow", "width=1024,height=600,scrollbars=yes");	
	}
});

</script>

