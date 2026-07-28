<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addmetercustomerreading_add/">Meter Customer Reading</a></li>
		<li class="breadcrumb-item active">Add</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user-friends"></i>
			Manage <span class="fw-300">Addmetercustomerreading Add</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addmetercustomerreading Add <span class="fw-300"><i>Details</i></span></h2>
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
											 <?php echo $this->session->flashdata('msg'); ?>
											
											<fieldset>
												<legend>Meter Customer Bills -Add </legend>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<span class="input-group-addon"><i class="icon-user"></i><strong>Search : </strong></span>
															<select class="form-control"  id="search_box_id" name="search_box_id" id="search_box_id" placeholder="Type text to search..." required>
																
																<?php
																$selected = $member_id;
																foreach ($record as $key => $value) {
																	?>
																	<option <?php echo ($selected ? ($selected == $value->member_id ? 'selected="selected"' : '') : ''); ?> value="<?php echo $value['customer_id']; ?>"> <?php echo $value['customer_id'] . ' ==> ' . $value['last_name'] . ', ' . $value['first_name'] . ' ' . $value['middle_name']; ?></option>
																<?php }
																?>
															</select>
															<!--<input  class="form-control"  id="search_box_id" name="name" id="name" required/>-->
																<?php echo form_error('search_box_id'); ?>
														</div>
													</div>
												</div>
													<input type="submit" class="form-control"  id="btn_search_box" name="btn_search_box" value = "Search" style="width: auto; float: left;  background: #3276b1; color:#fff;" />      
													<a href="<?php echo ADMIN_URL;?>addmetercustomerreading" id="btn_search_cancel" class="btn btn-secondary" name="btn_search_cancel" style="width: auto; float: left;margin-left: 10px;">Cancel</a>      
													                                                                                          
															
														</div>
														<div class="col-12" id="meterincomeDiv" style="margin-top: 13px; margin-bottom:20px;"></div> 
														<div style="clear:both"></div>
														<div id="hideclass" style="display:none; padding:20px; box-shadow: 0px 0px 3px 1px rgba(0,0,0,0.75);    margin: 14px;" >
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">  Previous Reading : </label>
																<div class="col-md-4">
																	<input  type="text" class="form-control text-input"  id="preview" name="preview" value="<?php echo $this->input->post('preview'); ?>" readonly ="readonly"/>
																	<input  type="hidden" class="form-control"  id="customer_id" name="customer_id" value="<?php echo $this->input->post('customer_id'); ?>" readonly ="readonly"/>
																	<?php echo form_error('preview'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">Current Reading : </label>
																<div class="col-md-4">
																	<input type="text text-input" step="1" class="form-control text-input"  id="current_meter" name="current_meter"  value="<?php echo $this->input->post('current_meter'); ?>" required/>
																</div>
															</div>
															<div class="form-group"style=" width: 60%;">
																<label class="col-md-4 control-label"> Cu. M. Consumed : </label>
																<div class="col-md-4">
																	<input type="text"  class="form-control"  id="different" name="different"  value="<?php echo $this->input->post('different'); ?>" readonly ="readonly"/>
																	<?php echo form_error('different'); ?>
																</div>
															</div>
															
															<div class="form-group" style=" width: 60%; display: none;">
																<label class="col-md-4 control-label"> Unit price : </label>
																<div class="col-md-4">
																	<input type="text" style="text-align:right;" class="form-control"  id="unit_price" name="unit_price"  value="<?php echo $this->input->post('unit_price'); ?>" readonly ="readonly"/>
																	<?php echo form_error('unit_price'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">  Current Bill : </label>
																<div class="col-md-4">
																	<input  type="text"  style="text-align:right;" class="form-control"  id="amount_pay" name="amount_pay" value="<?php echo $this->input->post('amount_pay'); ?>" readonly ="readonly"/>
																	<?php echo form_error('amount_pay'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">  Discount : </label>
																<div class="col-md-4">
																	<input  type="text"  style="text-align:right;" class="form-control text-input"  id="discount" name="discount" value="<?php echo $this->input->post('discount'); ?>"/>
																	<?php echo form_error('discount'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">  Previous Balance/Arrears : </label>
																<div class="col-md-4">
																	<input  type="text" style="text-align:right;" class="form-control text-input"  id="prev_balance" name="prev_balance" value="<?php echo $this->input->post('prev_balance'); ?>"/>
																	<?php echo form_error('prev_balance'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">  Amount Due If Paid on or before due date : </label>
																<div class="col-md-4">
																	<input  type="text"  style="text-align:right;" class="form-control"  id="total_amount" name="total_amount" value="<?php echo $this->input->post('total_amount'); ?>" readonly/>
																	<?php echo form_error('total_amount'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%; display: none;">
																<label class="col-md-4 control-label">Due Date :</label>
																<div class="col-md-4">
																	<input  type="text"  class="form-control"  id="due_date" name="due_date"  value="<?php echo $this->input->post('due_date'); ?>"/>
																	<?php echo form_error('due_date'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%;">
																<label class="col-md-4 control-label">  Amount Due If Paid after due date : </label>
																<div class="col-md-4">
																	<input  type="text"  style="text-align:right;" class="form-control"  id="amount_total_penalty" name="amount_total_penalty" value="<?php echo $this->input->post('amount_total_penalty'); ?>" readonly/>
																	<?php echo form_error('amount_total_penalty'); ?>
																</div>
															</div>
															<div class="form-group" style=" width: 60%; display:none;">
																<label class="col-md-4 control-label"> Billing Period : </label>
																<div class="col-md-2">
																	 <!--<select class="form-control" name="month" id="month">
																	<option value="">--Select--</option>
																		<?php foreach($addmonth as $key => $value){ ?>
																		 <option value="<?php  echo $value['month_id']; ?>"><?php  echo $value['month_name']; ?></option>
																		<?php } ?>
																	</select>-->
																	<input type="text" class="form-control" name="month" id="month" value="<?php echo $month; ?>"/>
																	<?php echo form_error('month'); ?>
																</div>
																<div class="col-md-2">
																<input type="text" class="form-control" name="year" id="year" value="<?php echo $year; ?>"/>
																</div>
															</div>
															<div class="form-group" style=" width: 60%; ">
																<label class="col-md-4 control-label">Reading Date :</label>
																<div class="col-md-4">
																	<input  type="text"  class="form-control"  id="date" name="date"  value="<?php echo $this->input->post('date'); ?>" required/>
																	<?php echo form_error('date'); ?>
																</div>
															</div>
															<input type="hidden" name="billing_period_id" id="billing_period_id"/>
													</fieldset>
													<div id="total_setting_2">
														<div class="form-actions">
															<div class="row">
																<div class="col-md-12">
																	
																	 <a href="<?php echo ADMIN_URL;?>addmetercustomerreading" class="btn btn-secondary">Cancel</a>
																	<input type="submit" class="btn btn-primary" id="add_button" name="add" id="add" value="Add">
																</div>
															</div>
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
$(document).ready(function(){
	$('#search_box_id').select2();
	$('#total_setting_2').hide();
});

$('#btn_search_box').on('click', function(event) {
	event.preventDefault();
	showSpinner(); // Call this to show the spinner
	let search_text = $("#search_box_id").val();
	const search_text_result = search_text.split("==>");
	var id = search_text_result[0];
	$.ajax({
		type: 'POST',
		url: '<?php echo ADMIN_URL;?>addmetercustomerreading/get_custmer_all_data/'+id,
		data: {id: id},
		success: function(data) {
			//console.log(data);
			$("#meterincomeDiv").html(data);
			//$('#hideclass').show();
			//$("#total_setting_2").show();
			$('#unit_price').val(amount_formatted(0));
			var unit_price = $('#unit_price').val();
				
			$("#amount_pay").val(amount_formatted(0));
			$("#different").val(0);
			$('#current_meter').val(0);
			
			
		}
	});
	setTimeout(hideSpinner, 1000); // Simulate loading for 3 seconds
	
});	
$(document).on('click','.pay_button',function(e){
	
	var cust = $('#customer').val();
	var pre = $('#preview_read').val();
	var unit = $('#unit_pr').val();
	var bp_id = $('#bp_id').val();
	var bp_period_month = $('#bp_period_month').val();
	var bp_period_year = $('#bp_period_year').val();

	$('#customer_id').val(cust);
	$('#preview').val(pre);
	$('#unit_price').val(unit);

	$('#billing_period_id').val(bp_id);
	$('#month').val(bp_period_month);
	$('#year').val(bp_period_year);
	//alert($('#billing_period_id').val());
	if(pre==0){
		$('#preview').removeAttr('readonly');
		//console.log('Wala:'+pre);
	}
	
});

$('#discount').on('blur', function(evt){
	evt.preventDefault();
	var unit_price = $('#unit_price').val();
	//var multiprice = parseInt(difer) * parseInt(unit_price);
	var multiprice = parseFloat(unit_price);
	var discount =$(this).val();
	
	total_amount = multiprice - discount;
	amount_total_penalty = 0;
	if($('#special_priviledge').val()==='0'){
		amount_total_penalty = (total_amount * 10)/100;
		amount_total_penalty = amount_total_penalty + total_amount;
	}else{
		amount_total_penalty = total_amount;
	}
	$('#discount').val(amount_formatted(discount));
	$("#amount_pay").val(amount_formatted(multiprice));
	$("#total_amount").val(amount_formatted(total_amount));
	$("#amount_total_penalty").val(amount_formatted(amount_total_penalty));	
});
$('#current_meter').on('blur', function() {
	var current_meter = $(this).val();
	var preview = $('#preview').val();
	var differances = parseFloat(current_meter) - parseFloat(preview);
	$("#different").val(differances);
	var difer = $("#different").val();
	const formData = new FormData();
	formData.append("cubic_meter_reading", difer);
	formData.append("customer_id", $('#customer_id').val());

	

	$.ajax({
		url: '<?php echo ADMIN_URL;?>addmetercustomerreading/get_cubic_meter_price/',
		type: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		success: function (response) {
			const result = JSON.parse(response);
			if (result.per_unit) {
				
				$('#unit_price').val(amount_formatted(result.per_unit));
				var unit_price = $('#unit_price').val();
				//var multiprice = parseInt(difer) * parseInt(unit_price);
				var multiprice = parseFloat(unit_price);
				var discount =0;
				if($('#cust_type_id').val()==3){
					discount = (multiprice * 5)/100;
				}
				total_amount = multiprice - discount;
				amount_total_penalty = 0;
				//console.log('SP:'+$('#special_priviledge').val());
				if($('#special_priviledge').val()==='0'){
					amount_total_penalty = (total_amount * 10)/100;
					amount_total_penalty = amount_total_penalty + total_amount;
				}else{
					amount_total_penalty = total_amount;
				}
				$('#discount').val(amount_formatted(discount));
				$("#amount_pay").val(amount_formatted(multiprice));
				$("#total_amount").val(amount_formatted(total_amount));
				$("#amount_total_penalty").val(amount_formatted(amount_total_penalty));
				

			} else {
				$('#unit_price').val(amount_formatted(0));
				var unit_price = $('#unit_price').val();
				
				$("#amount_pay").val(amount_formatted(0));
				alert("No Amount per cubic meter.");
			}
		},
		error: function () {
			alert("An error occurred while processing data.");
		}
	});

	
});
function amount_formatted(amount){
	const formatted = new Intl.NumberFormat('en-US', {
  		minimumFractionDigits: 2,
  		maximumFractionDigits: 2,
  		useGrouping: false, // No thousands separator
	}).format(amount);
	return formatted;
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
	$("#due_date").datepicker({
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

	$('.text-input').on('focus', function() {
  		$(this).select();
	});

});
</script>

