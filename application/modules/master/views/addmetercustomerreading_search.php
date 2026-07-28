<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>addcustomer">customer</a></li>
		<li class="breadcrumb-item active">search</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user-friends"></i>
			Manage <span class="fw-300">Addmetercustomerreading Search</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addmetercustomerreading Search <span class="fw-300"><i>Details</i></span></h2>
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
									
									
				
										<div class="form-horizontal" >
										  	
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
												<legend>Meter Customer-Search
												        <div class="pull-right" style="padding-right:20px;">
															
															
														</div>
												</legend>
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
												<input type="submit" class="btn btn-primary" name="search" id="search" value="search" style="margin-bottom: 5px;">
												<input type="button" class="btn btn-success" name="add_billing_period" id="add_billing_period" value="Add" style="margin-bottom: 5px; background-color:green; display:none;">
												<input type="hidden" name="record_id"	id="record_id"/>
												<input type="hidden" name="customer_id"	id="customer_id"/>
												<input type="hidden" name="cust_type_id" id="cust_type_id"/>
												<input type="hidden" name="special_priviledge" id="special_priviledge"/>
															
												
													
											</fieldset>

												
				
									</div>
								    
									
								</div>	
						</div>
						
						<div class="col-sm-6 col-lg-12" id="customerDiv" style="margin-top: 13px;"></div>	
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
<script type="text/javascript">
var curDate = '<?php echo date('d-m-Y') ?>';	
function fun_calendor(field){
	$("#"+field).focus();
} 
$(document).ready(function(){
	$('#search_box_id').select2();

	// Hide the Add Billing Period button and the datatable whenever the selected search item changes
	$('#search_box_id').on('change', function(){
		$('#add_billing_period').hide();
		// remove any existing datatable HTML and hide the container
		$('#customerDiv').empty().hide();
	});

	$('#search').on('click', function(evt){
		evt.preventDefault();
		let search_text = $("#search_box_id").val();
		const search_text_result = search_text.split("==>");
		var id = search_text_result[0];
		$('#customer_id').val(id);

		$.ajax({
			beforeSend: function() {
				showSpinner(); // Call this to show the spinner
			},
			type : "POST",
			url	: '<?php echo ADMIN_URL;?>addmetercustomerreading/getaddcustomersmetersearch',
			data	: "customer_id="+id,
			complete: function(data){
				var op = data.responseText.trim();
				// insert returned HTML into the container and ensure it's visible
				$("#customerDiv").html(op).show();

				// hide spinner now that content is inserted
				hideSpinner();

				// If the returned content contains any table element (datatable),
				// show the Add Billing Period button; otherwise hide it.
				if ($("#customerDiv").find("table").length > 0) {
					$("#add_billing_period").show();
				} else {
					$("#add_billing_period").hide();
				}
			}
		});
	});
	
/**
 * Reusable function to calculate franchise fee and totals
 * Date Modified: February 6, 2026
 * 
 * Purpose: Calculate franchise fee (based on % of current bill) and total amounts including maintenance fee and penalty.
 *          Franchise tax is ALWAYS based on the current bill. Senior citizen discount (if any) is applied
 *          when computing the total: total = current_bill - sc_discount + maintenance_fee + franchise_fee_amount.
 */
function recalculateFranchiseFeeAndTotals() {
	var unit_price = parseFloat($('#current_bill').val().replace(/,/g, '') || 0);
	var maintenance_fee = parseFloat($('#maintenance_fee').val().replace(/,/g, '') || 0);
	var multiprice = unit_price;
	var discount = parseFloat($('#sc_discount').val().replace(/,/g, '') || 0);
	
	// Get consumption value to check senior citizen discount eligibility
	var consumed = parseFloat($('#consumed').val() || 0);
	
	// Get franchise fee percentage from input field, or use default from global settings
	var franchise_fee_percentage = parseFloat($('#franchise_fee_percent').val().replace(/,/g, '') || 0);
	if (!franchise_fee_percentage || franchise_fee_percentage <= 0) {
		franchise_fee_percentage = <?php echo isset($franchise_fee_percentage) ? floatval($franchise_fee_percentage) : '2.00'; ?>;
		$('#franchise_fee_percent').val(franchise_fee_percentage);
	}
	
	/**
	 * Franchise Tax Calculation - Based on Current Bill
	 * Date Modified: February 6, 2026
	 * 
	 * Business Rule: Franchise tax is ALWAYS computed on the current bill amount.
	 *               Senior citizen discount (if applicable) is applied to the bill
	 *               when calculating the total, but franchise tax is based on the
	 *               current bill before any discount.
	 */
	var bill_amount_for_franchise = multiprice;  // Always use current bill for franchise tax
	var franchise_fee_amount = (bill_amount_for_franchise * franchise_fee_percentage) / 100;
	
	// Total: current bill - senior citizen discount (if any) + maintenance + franchise tax
	var total_amount = multiprice - discount;
	total_amount += parseFloat(maintenance_fee);
	total_amount += parseFloat(franchise_fee_amount);
	
	// Update franchise fee fields
	if($('#franchise_fee_percent').length) {
		$('#franchise_fee_percent').val(franchise_fee_percentage);
	}
	if($('#franchise_fee_amount').length) {
		$('#franchise_fee_amount').val(amount_formatted(franchise_fee_amount));
	}
	
	// Calculate penalty: 10% applied to (current_bill - sc_discount) only, then add maintenance + franchise
	var amount_total_penalty = 0;
	var compute_penalty = $('input[name="compute_penalty"]:checked').val() || '1';
	if($('#special_priviledge').val()==='0' && compute_penalty === '1'){
		var penalty_base = multiprice - discount;  // current_bill - sc_discount
		amount_total_penalty = (penalty_base * 10) / 100;
		amount_total_penalty = amount_total_penalty + penalty_base + maintenance_fee + franchise_fee_amount;
	}else{
		amount_total_penalty = total_amount;
	}
	
	// Update total amount and penalty fields
	if($('#total_amount').length) {
		$('#total_amount').val(amount_formatted(total_amount));
	}
	if($('#penalty').length) {
		$('#penalty').val(amount_formatted(amount_total_penalty));
	}
	if($('#amount_pay').length) {
		$('#amount_pay').val(amount_formatted(multiprice));
	}
}

$('#btn_save').on('click', function(evt){
	evt.preventDefault();
	var record_id = $('#record_id').val();
	const formData = new FormData();
	formData.append("customer_id", $('#customer_id').val());
	formData.append("previous_reading", $('#previous_reading').val());
	formData.append("current_reading", $('#current_reading').val());
	formData.append("consumed", $('#consumed').val());
	formData.append("current_bill", $('#current_bill').val());
	formData.append("sc_discount", $('#sc_discount').val());
	formData.append("arrears", $('#arrears').val());
	formData.append("total_amount", $('#total_amount').val());
	formData.append("penalty", $('#penalty').val());
	formData.append("maintenance_fee", $('#maintenance_fee').val());
	formData.append("franchise_fee_percent", $('#franchise_fee_percent').val());
	formData.append("franchise_fee_amount", $('#franchise_fee_amount').val());
	formData.append("reading_date", $('#reading_date').val());
	formData.append("customer_status", $('#customer_status').val());
	formData.append("compute_penalty", $('input[name="compute_penalty"]:checked').val() || '1');
	formData.append("edit", 'edit');

	$.ajax({
		url: '<?php echo ADMIN_URL;?>addmetercustomerreading/save_edit/'+record_id,
		type: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		success: function (response) {
			
			if (response=='success') {
				$('#search').trigger('click');
				//alert('Successfully Save');
				

			} 
		},
		error: function () {
			if (typeof Swal !== 'undefined') {
				Swal.fire({icon:'error', title:'Error', text: 'An error occurred while processing data.'});
			} else {
				alert("An error occurred while processing data.");
			}
		}
	});
});

$('#sc_discount').on('blur', function(evt){
	evt.preventDefault();
	recalculateFranchiseFeeAndTotals();
});

// Event listener for franchise_fee_percent field changes
$('#franchise_fee_percent').on('blur', function(evt){
	evt.preventDefault();
	recalculateFranchiseFeeAndTotals();
});

$('#maintenance_fee').on('blur', function(evt){
	evt.preventDefault();
	recalculateFranchiseFeeAndTotals();
});

$('input[name="compute_penalty"]').on('change', function(){
	recalculateFranchiseFeeAndTotals();
});

// Recalculate with new logic when Edit modal is shown (after row data is populated)
$(document).on('shown.bs.modal', '#myModal', function(){
	if ($('#current_bill').length && $('#frm_update').length) {
		recalculateFranchiseFeeAndTotals();
	}
});

$('#current_reading').on('blur', function() {
	var current_meter = $(this).val();
	var previous_reading = $('#previous_reading').val();
	var differences = parseFloat(current_meter) - parseFloat(previous_reading);
	$("#consumed").val(differences);
	var difer = $("#consumed").val();
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
				
				$('#current_bill').val(amount_formatted(result.per_unit));
				
				// Set initial franchise fee percentage if not set
				if(!$('#franchise_fee_percent').val() || $('#franchise_fee_percent').val() == '') {
					var default_franchise_fee_percentage = <?php echo isset($franchise_fee_percentage) ? floatval($franchise_fee_percentage) : '2.00'; ?>;
					$('#franchise_fee_percent').val(default_franchise_fee_percentage);
				}
				
				/**
				 * Senior Citizen Discount Calculation
				 * Date Modified: January 29, 2026
				 * Modified By: AI Assistant
				 * 
				 * Purpose: Apply senior citizen discount (5%) only if the customer is a senior citizen 
				 *          (account_type == 3) AND the consumption (difference between current_reading 
				 *          and previous_reading) is 30 cubic meters or less.
				 * 
				 * Reason: Business rule requirement - Senior citizens cannot avail discount if their 
				 *         consumption exceeds 30 cubic meters. This prevents abuse of the senior 
				 *         citizen discount privilege for excessive water consumption.
				 * 
				 * Previous Logic: Discount was applied to all senior citizens regardless of consumption amount.
				 * New Logic: Discount is only applied when consumption <= 30 cubic meters.
				 */
				var unit_price = parseFloat($('#current_bill').val().replace(/,/g, ''));
				var multiprice = unit_price;
				var consumed = parseFloat($('#consumed').val() || 0);
				if($('#cust_type_id').val()==3 && consumed <= 30){
					var discount = (multiprice * 5)/100;
					$('#sc_discount').val(amount_formatted(discount));
				} else {
					// Clear discount if consumption exceeds 30 cubic meters or not a senior citizen
					$('#sc_discount').val(amount_formatted(0));
				}
				
				// Recalculate franchise fee and totals
				recalculateFranchiseFeeAndTotals();
				

			} else {
				$('#current_bill').val(amount_formatted(0));
				var unit_price = $('#current_bill').val();
				
				$("#amount_pay").val(amount_formatted(0));
				if (typeof Swal !== 'undefined') {
					Swal.fire({icon:'warning', title:'No Amount', text: 'No Amount per cubic meter.'});
				} else {
					alert("No Amount per cubic meter.");
				}
			}
		},
		error: function () {
			if (typeof Swal !== 'undefined') {
				Swal.fire({icon:'error', title:'Error', text: 'An error occurred while processing data.'});
			} else {
				alert("An error occurred while processing data.");
			}
		}
	});

	
});



	$("#reading_date").datepicker({
		showAnim: null,
		dateFormat: 'dd-mm-yy',
		// showOn: 'both',
		buttonImage: '/images/calender.jpg',
		buttonImageOnly: true,
		firstDay: 1,
		nextText: '',
		prevText: '',
		numberOfMonths: [1, 1],
		defaultDate: new Date(curDate),
		//minDate: curDate,
		//maxDate: ''
	});
	$("#todate").datepicker({
		showAnim: null,
		dateFormat: 'dd-mm-yy',
		// showOn: 'both',
		buttonImage: '/images/calender.jpg',
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
$(document).ready(function(){
	// show Add Billing Period modal when button clicked
	$('#add_billing_period').on('click', function(){
		// clear previous values and enable inputs
		$('#frm_add_billing')[0].reset();
		$('#frm_add_billing').find('input,select').prop('disabled', false);
		$('#btn_edit_modal').text('Edit');
		$('#addBillingModal').modal('show');
	});

	// Reusable calculation for Add modal (same rules as edit)
	function recalculateFranchiseFeeAndTotalsAdd() {
		var unit_price = parseFloat($('#current_bill_add').val().replace(/,/g, '') || 0);
		var maintenance_fee = parseFloat($('#maintenance_fee_add').val().replace(/,/g, '') || 0);
		var multiprice = unit_price;
		var discount = parseFloat($('#sc_discount_add').val().replace(/,/g, '') || 0);
		var consumed = parseFloat($('#consumed_add').val() || 0);
		var franchise_fee_percentage = parseFloat($('#franchise_fee_percent_add').val().replace(/,/g, '') || 0);
		if (!franchise_fee_percentage || franchise_fee_percentage <= 0) {
			franchise_fee_percentage = <?php echo isset($franchise_fee_percentage) ? floatval($franchise_fee_percentage) : '2.00'; ?>;
			$('#franchise_fee_percent_add').val(franchise_fee_percentage);
		}
		// Franchise tax is always based on current bill; senior citizen discount applied to total
		var bill_amount_for_franchise = multiprice;
		var franchise_fee_amount = (bill_amount_for_franchise * franchise_fee_percentage) / 100;
		var total_amount = multiprice - discount;
		total_amount += parseFloat(maintenance_fee);
		total_amount += parseFloat(franchise_fee_amount);
		if($('#franchise_fee_amount_add').length) {
			$('#franchise_fee_amount_add').val(amount_formatted(franchise_fee_amount));
		}
		if($('#total_amount_add').length) {
			$('#total_amount_add').val(amount_formatted(total_amount));
		}
		// Calculate penalty: 10% applied to (current_bill - sc_discount) only, then add maintenance + franchise
		var amount_total_penalty = 0;
		if($('#special_priviledge').val()==='0'){
			var penalty_base = multiprice - discount;  // current_bill - sc_discount
			amount_total_penalty = (penalty_base * 10) / 100;
			amount_total_penalty = amount_total_penalty + penalty_base + maintenance_fee + franchise_fee_amount;
		}else{
			amount_total_penalty = total_amount;
		}
		if($('#penalty_add').length) {
			$('#penalty_add').val(amount_formatted(amount_total_penalty));
		}
	}

	// Bind events on Add modal fields to trigger recalculation
	$('#sc_discount_add, #franchise_fee_percent_add, #maintenance_fee_add').on('blur', function(){
		recalculateFranchiseFeeAndTotalsAdd();
	});

	// When current reading in Add modal loses focus, compute consumed and get unit price
	$('#current_reading_add').on('blur', function() {
		var current_meter = $(this).val();
		var previous_reading = $('#previous_reading_add').val() || 0;
		var differences = parseFloat(current_meter || 0) - parseFloat(previous_reading || 0);
		$("#consumed_add").val(differences);

		const formData = new FormData();
		formData.append("cubic_meter_reading", differences);
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
					$('#current_bill_add').val(amount_formatted(result.per_unit));
					if(!$('#franchise_fee_percent_add').val() || $('#franchise_fee_percent_add').val() == '') {
						var default_franchise_fee_percentage = <?php echo isset($franchise_fee_percentage) ? floatval($franchise_fee_percentage) : '2.00'; ?>;
						$('#franchise_fee_percent_add').val(default_franchise_fee_percentage);
					}
					var unit_price = parseFloat($('#current_bill_add').val().replace(/,/g, ''));
					var multiprice = unit_price;
					var consumed = parseFloat($('#consumed_add').val() || 0);
					if($('#cust_type_id').val()==3 && consumed <= 30){
						var discount = (multiprice * 5)/100;
						$('#sc_discount_add').val(amount_formatted(discount));
					} else {
						$('#sc_discount_add').val(amount_formatted(0));
					}
					recalculateFranchiseFeeAndTotalsAdd();
				} else {
					$('#current_bill_add').val(amount_formatted(0));
					$("#amount_pay").val(amount_formatted(0));
					if (typeof Swal !== 'undefined') {
						Swal.fire({icon:'warning', title:'No Amount', text: 'No Amount per cubic meter.'});
					} else {
						alert("No Amount per cubic meter.");
					}
				}
			},
			error: function () {
				if (typeof Swal !== 'undefined') {
					Swal.fire({icon:'error', title:'Error', text: 'An error occurred while processing data.'});
				} else {
					alert("An error occurred while processing data.");
				}
			}
		});
	});

	// Save button handler - insert into tbl_addcustomer_reading via controller
	$('#save_billing_period').on('click', function(){
		// Validate required inputs first (reuse earlier check)
		var missingField = null;
		$('#frm_add_billing').find('[required]').each(function(){
			var $el = $(this);
			var val = $el.val();
			if (val === null || $.trim(val) === '') {
				missingField = $el;
				return false;
			}
		});
		if (missingField) {
			var msg = 'Please fill the "' + (missingField.prev('.input-group-addon').text().trim() || missingField.attr('name')) + '" field.';
			if (typeof Swal !== 'undefined') {
				Swal.fire({icon:'warning', title:'Validation', text: msg});
			} else {
				alert(msg);
			}
			missingField.focus();
			return;
		}

		// Prepare FormData for insertion
		const data = new FormData();
		data.append('customer_id', $('#customer_id').val());
		data.append('billing_period', $('#billing_period_select').val());
		data.append('previous_reading', $('#previous_reading_add').val());
		data.append('current_reading', $('#current_reading_add').val());
		data.append('consumed', $('#consumed_add').val());
		data.append('current_bill', $('#current_bill_add').val());
		data.append('sc_discount', $('#sc_discount_add').val());
		data.append('arrears', $('#arrears_add').val());
		data.append('total_amount', $('#total_amount_add').val());
		data.append('penalty', $('#penalty_add').val());
		data.append('maintenance_fee', $('#maintenance_fee_add').val());
		data.append('franchise_fee_percent', $('#franchise_fee_percent_add').val());
		data.append('franchise_fee_amount', $('#franchise_fee_amount_add').val());
		data.append('reading_date', $('#reading_date_add').val());
		data.append('customer_status', $('#customer_status_add').val());
		data.append('add', 'add');

		$.ajax({
			url: '<?php echo ADMIN_URL;?>addmetercustomerreading/save_add',
			type: 'POST',
			data: data,
			contentType: false,
			processData: false,
			success: function(response){
				// expecting 'success' on successful insert
				if (response && response.trim() === 'success') {
					if (typeof Swal !== 'undefined') {
						Swal.fire({icon:'success', title:'Saved', text: 'Billing period successfully added.'});
					}
					$('#addBillingModal').modal('hide');
					$('#search').trigger('click');
				} else {
					var msg = response || 'An error occurred while saving.';
					if (typeof Swal !== 'undefined') {
						Swal.fire({icon:'error', title:'Error', text: msg});
					} else {
						alert(msg);
					}
				}
			},
			error: function(){
				if (typeof Swal !== 'undefined') {
					Swal.fire({icon:'error', title:'Error', text: 'An error occurred while processing data.'});
				} else {
					alert("An error occurred while processing data.");
				}
			}
		});
	});

	// Optional Edit button inside modal (toggles editable state)
	$('#btn_edit_modal').on('click', function(){
		// toggle disabled state of inputs
		var inputs = $('#frm_add_billing').find('input, select');
		var disabled = inputs.prop('disabled');
		inputs.prop('disabled', !disabled);
		$(this).text(disabled ? 'Edit' : 'Lock');
	});
});
</script>

