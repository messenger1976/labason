
<!-- MAIN PANEL -->
		<div id="main" role="main" style="margin-left:0px;">

		

			<!-- MAIN CONTENT -->
			<div id="content">

				
				<!-- widget grid -->
				<section id="widget-grid" class="">

					
                <div class="row">

<!-- NEW COL START -->
<article class="col-sm-12 col-md-12 col-lg-12">
					
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						<!-- widget options:
							usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
							
							data-widget-colorbutton="false"	
							data-widget-editbutton="false"
							data-widget-togglebutton="false"
							data-widget-deletebutton="false"
							data-widget-fullscreenbutton="false"
							data-widget-custombutton="false"
							data-widget-collapsed="true" 
							data-widget-sortable="false"
							
						-->
						<!--<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>Customer Info</h2>				
							
						</header>-->

						<!-- widget div-->
						<div style="border-radius: 30px;">
							
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
								
							</div>
							<!-- end widget edit box -->
							
							<!-- widget content -->
							<div class="widget-body no-padding">
								
								<form id="checkout-form" class="smart-form" novalidate="novalidate">
                                    <section style="margin: 5px 15px;">
                                        <div class="row">
											<div class="form-group" style="width:100%;">
												<label class="label col col-2" style="text-align: center; font-size:larger; font-weight:bold; ">Search Customer</label>
												<div class="col col-8">
													<label class="input"> <i class="icon-prepend fa fa-search"></i>
														
														<select class="form-control" id="search_box_id" style="text-align: center; border-radius: 15px; border:1px solid black; " name="search_box_id">
															<option value="">--Select--</option>
															<?php
													
															foreach ($customer_listing as $key => $value) {
																?>
																<option value="<?php echo $value['id']; ?>" data-customer_id="<?php echo $value['customer_id']; ?>" data-fullname="<?php echo $value['last_name'] . ', ' . $value['first_name'] . ' ' . $value['middle_name']; ?>" data-meter_number="<?php echo $value['meter_number']; ?>" data-address="<?php echo $value['address']; ?>"
																data-cust_type_id="<?php echo $value['account_type']; ?>" data-special_priviledge="<?php echo $value['special_priviledge']; ?>"> <?php echo $value['customer_id'] . ' ==> ' . $value['last_name'] . ', ' . $value['first_name'] . ' ' . $value['middle_name']; ?></option>
															<?php }
															?>
														</select>
														
													</label>
													
												</div>
												<!--<input type="submit" class="form-control"  id="btn_search_box" name="btn_search_box" value = "Search" style="width: auto; float: left;  background: #3276b1; color:#fff;" />-->
											</div>
                                            
                                        </div>
										
                                    </section>
									<fieldset style="padding-top:5px;">
										<div class="row">
											<section class="col col-10">
												<label class="input"> <i class="icon-prepend fa fa-user"></i>
													<input type="text" name="fullname" id="fullname" style="border:1px solid black; border-radius: 15px; font-size: larger; font-weight: bold;background-color: yellow;" readonly>
                                                    <input type="hidden" name="customer_id" id="customer_id">
													<input type="hidden" name="cust_type_id" id="cust_type_id">
													<input type="hidden" name="refno" id="refno">
													<input type="hidden" name="special_priviledge" id="special_priviledge" value="0">
													
													
												</label>
											</section>
											
										</div>

										<div class="row">
											<section class="col col-6">
												<label class="input"> <i class="icon-prepend fa fa-tachometer"></i>
													<input type="text" name="meter_number" id="meter_number" style="border:1px solid black; border-radius: 15px; font-size: larger; font-weight: bold;background-color: yellow;" readonly>
												</label>
											</section>
											<section class="col col-6">
												<label class="input"> <i class="icon-prepend fa fa-home"></i>
													<input type="text" name="address" id="address" style="border:1px solid black; border-radius: 15px; font-size: larger; font-weight: bold;background-color: yellow;" readonly>
												</label>
											</section>
										</div>
									</fieldset>

									
									<fieldset style="padding-top:5px;">
										<div class="row">
											<div class="col-lg-12 controls">
												<div class="form-group" style="padding: 5px 15px;"> 
													<span class="input-group-addon"><strong>Billing Period :</strong></span>
													<input class="form-control" type="text" id="billing_period" name="billing_period" value="<?php echo $_SESSION['current_billingperiod']; ?>" style="background-color:yellow;font-size: larger; font-weight: bold; text-align: center;" readonly>
													<?php echo form_error('billing_period'); ?>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 controls">
												<div class="form-group" style="padding: 5px 15px;"> 
													<span class="input-group-addon"><strong>Previous Reading :</strong></span>
													<input class="form-control" type="text" id="previous_reading" name="previous_reading" style="background-color:yellow;font-size: larger; font-weight: bold; text-align: center;" readonly>
													
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 controls">
												<div class="form-group" style="padding: 5px 15px;"> 
													<span class="input-group-addon"><strong>Current Reading :</strong></span>
													<input class="form-control text-input" type="number" id="current_reading" name="current_reading" style="background-color:white;font-size: larger; font-weight: bold; text-align: center;">
													
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 controls">
												<div class="form-group" style="padding: 5px 15px;"> 
													<span class="input-group-addon"><strong>Consumed :</strong></span>
													<input class="form-control text-input" type="text" id="consumed" name="consumed" style="background-color:yellow;font-size: larger; font-weight: bold; text-align: center;" readonly>
													
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 controls">
												<div class="form-group" style="padding: 5px 15px;"> 
													<span class="input-group-addon"><strong>Current Billing :</strong></span>
													<input class="form-control text-input" type="text" id="current_bill" name="current_bill" style="background-color:yellow;font-size: larger; font-weight: bold; text-align: center;" readonly>
													
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 controls">
												<div class="form-group" style="padding: 5px 15px;"> 
													<span class="input-group-addon"><strong>SC Discount :</strong></span>
													<input class="form-control text-input" type="text" id="sc_discount" name="sc_discount" style="background-color:yellow;font-size: larger; font-weight: bold; text-align: center;" readonly>
													
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 controls">
												<div class="form-group" style="padding: 5px 15px;"> 
													<span class="input-group-addon"><strong>Arrears :</strong></span>
													<input class="form-control text-input" type="text" id="arrears" name="arrears" style="background-color:yellow;font-size: larger; font-weight: bold; text-align: center;" readonly>
													
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 controls">
												<div class="form-group" style="padding: 5px 15px;"> 
													<span class="input-group-addon"><strong>WM Maintenance Fee :</strong></span>
													<input class="form-control text-input" type="text" id="maintenance_fee" name="maintenance_fee" style="background-color:yellow;font-size: larger; font-weight: bold; text-align: center;" readonly>
													
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 controls">
												<div class="form-group" style="padding: 5px 15px;"> 
													<span class="input-group-addon"><strong>Amt before due date :</strong></span>
													<input class="form-control text-input" type="text" id="total_amount" name="total_amount" style="background-color:yellow;font-size: larger; font-weight: bold; text-align: center;" readonly>
													
												</div>
											</div>
										</div>
                                        <div class="row">
											<div class="col-lg-12 controls">
												<div class="form-group" style="padding: 5px 15px;"> 
													<span class="input-group-addon"><strong>Amt after due date :</strong></span>
													<input class="form-control text-input" type="text" id="penalty" name="penalty" style="background-color:yellow; font-size: larger; font-weight: bold; text-align: center;" readonly>
													
												</div>
											</div>
										</div>
										
										
                                        

										
									</fieldset>

									<footer style="border-bottom-left-radius: 30px; border-bottom-right-radius: 30px;">
										<button class="btn btn-primary" style="border-radius: 15px; border:1px solid black; font-size:small; font-weight: bold;" id="btn_print" name="btn_print" disabled>
											Print
										</button>
										<button type="submit" class="btn btn-primary" style="border-radius: 15px; border:1px solid black; font-size:small; font-weight: bold;" id="save" name="save" disabled>
											Save
										</button>
										<a class="btn btn-warning" id="btn_cancel" style="border-radius: 15px; border:1px solid black; font-size:small; font-weight: bold;" name="btn_cancel">Cancel</a>
									</footer>
								</form>

							</div>
							<!-- end widget content -->
							
						</div>
						<!-- end widget div -->
						
					</div>
				
				</article>
				<!-- END COL -->

                </div>
					

				</section>
				<!-- end widget grid -->

			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->
		

		<?php include('mobile_footer.php');?>

	

<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url();?>js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script type="text/javascript">
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		var btn_save_flag = 0;
		$(document).ready(function() {
			
			pageSetUp();
			
			$('#search_box_id').select2();		
			
			
			
			$(document).on('change',"#search_box_id",function(evt){
				
				evt.preventDefault();
				var id = $(this).val();
				var cust_fullname = $(this).find(':selected').data('fullname');
				$('#fullname').val(cust_fullname);
				var cust_meter_number = $(this).find(':selected').data('meter_number');
				$('#meter_number').val(cust_meter_number);
				var cust_address = $(this).find(':selected').data('address');
				$('#address').val(cust_address);
				var customer_id = $(this).find(':selected').data('customer_id');
				var special_priviledge = $(this).find(':selected').data('special_priviledge');
				$('#special_priviledge').val(special_priviledge);
				var bp_month = <?php echo $_SESSION['bp_month']; ?>;
				var bp_year = <?php echo $_SESSION['bp_year']; ?>;
				
				if(id){
					// Send an AJAX request to the backend
					$.ajax({
						url: 'mobile_dashboard/get_customer_meter_reading', // Backend PHP script
						type: 'POST',
						data: { customer_id: customer_id,
							bp_month: bp_month,
							bp_year: bp_year
						},
						beforeSend: function() {
							// Show a loading spinner or message if needed
							showSpinner();
						},
						dataType: 'json',
						success: function(response) {
							// Clear the child dropdown
							//$('#billing_period').empty().append('<option value="">--Select--</option>');

							// Populate the child dropdown with the response data
							if (response.length > 0) {
								$.each(response, function(index, item) {
									$('#previous_reading').val(item.previous_reading);
									$('#arrears').val(item.arrears);
									$('#refno').val(item.refno);
									$('#maintenance_fee').val(item.maintenance_fee);
									//$("#save").attr("disabled", "disabled");
									
									
									if(item.reading>0){
										$('#current_reading').val(item.reading);
										$('#current_reading').attr('readonly', true);
										btn_save_flag = 1;
										$("#btn_print").removeAttr("disabled");
										compute_all();
										//$("#save").attr("disabled", "disabled");
										//$('#consumed').val(item.consumed);

									}else{
										btn_save_flag = 0;
										//$("#save").attr("disabled", "disabled");
										$("#btn_print").attr("disabled", "disabled");
										$('#current_reading').val('');
										$('#current_reading').attr('readonly', false);
										$("#current_reading").css("background-color", "white");
										$('#consumed').val('');
										$('#current_bill').val('');
										$('#sc_discount').val('');
										$('#arrears').val('');
										$('#total_amount').val('');
										$('#penalty').val('');
										//$('#maintenance_fee').val('');
										setTimeout(function() {
											$('#current_reading').focus();
										}, 50);
									}
									
									
									
								});
							}
						},
						error: function(xhr, status, error) {
							console.error('AJAX Error: ' + status + error);
						},
						complete: function() {
							// Hide the loading spinner or message
							hideSpinner();
						}
					});
				}else{
					$('#fullname').val('');
					$('#meter_number').val('');
					$('#address').val('');
					$('#previous_reading').val('');
					$('#current_reading').val('');
					$('#consumed').val('');
					$('#current_bill').val('');
					$('#sc_discount').val('');
					$('#arrears').val('');
					$('#total_amount').val('');
					$('#penalty').val('');
					$('#maintenance_fee').val('');
					$('#save').attr("disabled", "disabled");
					// If no parent is selected
					// , clear the child dropdown
					//$('#billing_period').empty().append('<option value="">--Select--</option>');
					//$('#leaking_option').hide();
				}
			});
			

		
			$('#current_reading').on('change', function(evt) {
				event.preventDefault();
				compute_all();
			});

			$('#current_reading').keypress(function(event) {
				if (event.which == 13) { // Check if the pressed key is Enter (keyCode 13)
					event.preventDefault(); // Prevent the default action (like submitting a form)
					// Your code to execute when Enter is pressed
					compute_all();
					$("#save").focus();
					
				}
			});

			$('#save').on('click', function(evt) {
				
				var current_reading = $('#current_reading').val();
				var previous_reading = $('#previous_reading').val();
				if(current_reading == '' || current_reading == '0' || previous_reading == ''){
					evt.preventDefault();
					//alert('Please enter the current reading and previous reading.');
					$.smallBox({
						title : "Field required",
						content : "Please enter the current reading and previous reading.",
						color : "#D30000",
						timeout: 8000,
						icon : "fa fa-exclamation-circle swing animated"
					});
					return false;
				}else{
					evt.preventDefault();
					$.SmartMessageBox({
						title : "Saving Action",
						content : "Are you sure you want to save this record?",
						buttons : '[No][Yes]'
					}, function(ButtonPressed) {
						if (ButtonPressed === "No") {
							// Do nothing, just close the message box
						} else if (ButtonPressed === "Yes") {
							// Perform the save action here
							//alert('Record saved successfully.');
							//$("#checkout-form").submit();
							var customer_id = $('#search_box_id').find(':selected').data('customer_id');
							var refno = $('#refno').val();
							var previous_reading = $('#previous_reading').val();
							var current_reading = $('#current_reading').val();
							var maintenance_fee = $('#maintenance_fee').val();
							var billing_month = <?php echo $_SESSION['bp_month']; ?>;
							var billing_year = <?php echo $_SESSION['bp_year']; ?>;
							var reading_date = '<?php echo date('Y-m-d'); ?>';
							
							const formData = new FormData();
							formData.append("refno", refno);
							formData.append("customer_id", customer_id);
							formData.append("previous_reading", previous_reading);
							formData.append("current_reading", current_reading);
							formData.append("billing_month", billing_month);
							formData.append("billing_year", billing_year);
							formData.append("reading_date", reading_date);
							formData.append("maintenance_fee", maintenance_fee);

							$.ajax({
								url: '<?php echo ADMIN_URL;?>mobile_dashboard/add_record/',
								type: 'POST',
								data: formData,
								contentType: false,
								processData: false,
								beforeSend: function() {
									// Show a loading spinner or message if needed
									showSpinner();
								},
								success: function (response) {
									const result = JSON.parse(response);
									//console.log(result);
									//console.log(response);
									if (result[0].msg=="success") {
										//alert("Record saved successfully.");
										$.smallBox({
											title : "Record saved successfully",
											content : "Record saved successfully.",
											color : "#739E73",
											timeout: 8000,
											icon : "fa fa-check swing animated"
										});
										$('#fullname').val('');
										$('#meter_number').val('');
										$('#address').val('');
										$('#previous_reading').val('');
										$('#current_reading').val('');
										$('#consumed').val('');
										$('#current_bill').val('');
										$('#sc_discount').val('');
										$('#arrears').val('');
										$('#total_amount').val('');
										$('#penalty').val('');
										$('#maintenance_fee').val('');
										$("#save").attr("disabled", "disabled");
									} else {
										//alert("Error saving record.");
										$.smallBox({
											title : "Error saving record",
											content : "Error saving record.",
											color : "#D30000",
											timeout: 8000,
											icon : "fa fa-exclamation-circle swing animated"
										});
									}
								},
								error: function () {
									//alert("An error occurred while processing data.");
									$("#save").attr("disabled", "disabled");
									$.smallBox({
										title : "Error Query Data",
										content : "An error occurred while processing data.",
										color : "#D30000",
										timeout: 8000,
										icon : "fa fa-exclamation-circle swing animated"
									});
								},
								complete: function() {
									// Hide the loading spinner or message
									hideSpinner();
								}
							});

						} else {
							// Handle other button presses if needed
							
						}
						
						
						
			
					});

				}

				
			});

			//$('#fullscreen').trigger()('click');
			var fullscreenButton = document.querySelector('[data-action="launchFullscreen"]'); // Adjust selector if needed
			var clickcheck =0;
			$(document).on('click', function() {
				if (clickcheck==0) {
					// This might be blocked by the browser
					fullscreenButton.click();
					clickcheck = 1;
				}
			});

			$(document).on('click', '#btn_print', function(event) {
				event.preventDefault();
				var customer_id = $('#search_box_id').find(':selected').data('customer_id');
				var refno = $('#refno').val();
				var previous_reading = $('#previous_reading').val();
				var current_reading = $('#current_reading').val();
				var billing_month = <?php echo $_SESSION['bp_month']; ?>;
				var billing_year = <?php echo $_SESSION['bp_year']; ?>;
				var reading_date = '<?php echo date('Y-m-d'); ?>';
				
				//window.open('<?php echo ADMIN_URL;?>mobile_dashboard/print_receipt/'+customer_id+'/'+refno+'/'+previous_reading+'/'+current_reading+'/'+billing_month+'/'+billing_year+'/'+reading_date, '_blank');
				window.open('<?php echo ADMIN_URL;?>mobile_dashboard/print_receipt/'+customer_id+'/'+billing_month+'/'+billing_year, '_blank');
				//window.open('<?php echo ADMIN_URL;?>mobile_dashboard/print_receipt/', '_blank');
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

		function compute_all(){
			var current_meter = $('#current_reading').val();
			var previous_reading = $('#previous_reading').val();
			var maintenance_fee = parseFloat($('#maintenance_fee').val());
			var differences = parseFloat(current_meter) - parseFloat(previous_reading);

			differences = isNaN(differences) ? 0 : differences;
			$("#consumed").val(differences);
			var difer = $("#consumed").val();
			var customer_id = $('#search_box_id').find(':selected').data('customer_id');
			var cust_type_id = $('#search_box_id').find(':selected').data('cust_type_id');
			$('#cust_type_id').val(cust_type_id);
			const formData = new FormData();
			formData.append("cubic_meter_reading", difer);
			formData.append("customer_id", customer_id);

			$.ajax({
				url: '<?php echo ADMIN_URL;?>addmetercustomerreading/get_cubic_meter_price/',
				type: 'POST',
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					// Show a loading spinner or message if needed
					showSpinner();
				},
				success: function (response) {
					const result = JSON.parse(response);
					if (result.per_unit) {
						
						$('#current_bill').val(amount_formatted(result.per_unit));
						var unit_price = $('#current_bill').val();
						//var multiprice = parseInt(difer) * parseInt(unit_price);
						var multiprice = parseFloat(unit_price);
						var discount =0;
						if($('#cust_type_id').val()==3){
							discount = (multiprice * 5)/100;
						}
						total_amount = multiprice - discount;
						total_amount = total_amount??0;
						total_amount+=maintenance_fee;
						amount_total_penalty = 0;
						//console.log('SP:'+$('#special_priviledge').val());
						if($('#special_priviledge').val()==='0'){
							amount_total_penalty = (total_amount * 10)/100;
							amount_total_penalty = amount_total_penalty + total_amount;
						}else{
							amount_total_penalty = total_amount;
						}
						$('#sc_discount').val(amount_formatted(discount));
						$("#amount_pay").val(amount_formatted(multiprice));
						$("#total_amount").val(amount_formatted(total_amount));
						$("#penalty").val(amount_formatted(amount_total_penalty));
						if(btn_save_flag==1){
								
							$("#save").attr("disabled", "disabled");
							$("#current_reading").css("background-color", "yellow");
							//$("#current_reading").css("color", "#fff");
						}else{
							$("#save").removeAttr("disabled");
							$("#current_reading").css("background-color", "white");
							//$("#save").css("color", "#fff");
						}
						//$("#save").removeAttr("disabled");
						

					} else {
						$('#current_bill').val(amount_formatted(0));
						var unit_price = $('#current_bill').val();
						$("#save").attr("disabled", "disabled");
						$("#amount_pay").val(amount_formatted(0));
						//alert("No Amount per cubic meter.");
						$.smallBox({
							title : "Current Bill field required",
							content : "No Amount per cubic meter.",
							color : "#D30000",
							timeout: 8000,
							icon : "fa fa-exclamation-circle swing animated"
						});
					}
				},
				error: function () {
					//alert("An error occurred while processing data.");
					$("#save").attr("disabled", "disabled");
					$.smallBox({
						title : "Error Query Data",
						content : "An error occurred while processing data.",
						color : "#D30000",
						timeout: 8000,
						icon : "fa fa-exclamation-circle swing animated"
					});
				},
				complete: function() {
					// Hide the loading spinner or message
					hideSpinner();
				}
			});
		}
		$('.text-input').on('focus', function() {
			$(this).select();
		});

		$(document).on('click', '#btn_cancel', function(event) {
			$('#fullname').val('');
			$('#meter_number').val('');
			$('#address').val('');
			$('#previous_reading').val('');
			$('#current_reading').val('');
			$('#consumed').val('');
			$('#current_bill').val('');
			$('#sc_discount').val('');
			$('#arrears').val('');
			$('#maintenance_fee').val('');
			$('#total_amount').val('');
			$('#penalty').val('');
			$('#save').attr("disabled", "disabled");
			event.preventDefault();
		});

		

		</script>
