<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>addmetercustomerreading/add">Meter Customer Reading Add</a></li>
		<li class="breadcrumb-item active">Edit</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user-friends"></i>
			Manage <span class="fw-300">Addmetercustomerreading Edit</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addmetercustomerreading Edit <span class="fw-300"><i>Details</i></span></h2>
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
														<legend>Meter Customer Reading-Edit </legend>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<span class="input-group-addon"><i class="icon-user"></i><strong>Customer-Id : </strong></span>
																<input  class="form-control"  type="text" id="customer_id" name="customer_id" value="<?php echo $record['customer_id']; ?>" required/>
																<?php echo form_error('customer_id'); ?>
														</div>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<span class="input-group-addon"><i class="icon-user"></i><strong> Month :</strong></span>
																<select name="month" id="month" class="form-control" required>
																	 <option value="">--Select--</option>
																	 <?php foreach($addmonth as $month => $value){?>
																	  <option value="<?php echo $value['month_id'];?>" 
																	    <?php if($record['month'] == $value['month_id']){echo 'selected';}?>>
																		<?php echo $value['month_name'];?>
																	 </option>
																	 <?php } ?>
																</select>
														</div>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<span class="input-group-addon"><i class="icon-user"></i><strong> Previous Reading : </strong></span>
																<input  class="form-control"  type="text" id="previous_reading" name="previous_reading" value="<?php echo $record['previous_reading']; ?>" readonly/>
																<?php echo form_error('previous_reading'); ?>
														</div>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<span class="input-group-addon"><i class="icon-user"></i><strong> Current Reading : </strong></span>
																<input  class="form-control"  type="text" id="reading" name="reading" value="<?php echo $record['reading']; ?>" required/>
																<?php echo form_error('reading'); ?>
														</div>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<span class="input-group-addon"><i class="icon-user"></i><strong> Difference : </strong></span>
																<input  class="form-control"  type="text" id="consumed" name="consumed" value="<?php echo $record['consumed']; ?>" readonly/>
																<?php echo form_error('consumed'); ?>
														</div>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<span class="input-group-addon"><i class="icon-user"></i><strong> Amount : </strong></span>
																<input  class="form-control"  type="text" id="amount" name="amount" value="<?php echo $record['amount']; ?>" readonly/>
																<?php echo form_error('amount'); ?>
														</div>
													</div>
												</div>		
													</fieldset>
													
													
													
													<div class="form-actions">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>addmetercustomerreading" class="btn btn-secondary">Cancel</a>
																<input type="submit" class="btn btn-primary" name="edit" id="edit" value="Edit">
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
var curDate = '<?php echo date('d-m-Y') ?>';	
function fun_calendor(field){
	$("#"+field).focus();
} 
$(document).ready(function(){

	$("#dob").datepicker({
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

	$('#reading').on('change', function() {
		var current_meter = $(this).val();
		var preview = $('#previous_reading').val();
		var differances = parseInt(current_meter) - parseInt(preview);
		$("#consumed").val(differances);
		var difer = $("#consumed").val();
		const formData = new FormData();
		formData.append("cubic_meter_reading", difer);

		

		$.ajax({
			url: '<?php echo ADMIN_URL;?>addmetercustomerreading/get_cubic_meter_price/',
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			success: function (response) {
				const result = JSON.parse(response);
				if (result.per_unit) {
					
					$('#amount').val(amount_formatted(result.per_unit));
					var unit_price = $('#amount').val();
					//var multiprice = parseInt(difer) * parseInt(unit_price);
					var multiprice = parseInt(unit_price);
					$("#amount_pay").val(amount_formatted(multiprice));
				} else {
					$('#amount').val(amount_formatted(0));
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

