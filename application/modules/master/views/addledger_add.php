<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>addledger/">Ledger</a></li>
		<li class="breadcrumb-item"><a href="< ?php echo ADMIN_URL?>addledger/search/">Search Ledger</a></li>
		<li class="breadcrumb-item active">add</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-wallet"></i>
			Manage <span class="fw-300">Addledger Add</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addledger Add <span class="fw-300"><i>Details</i></span></h2>
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
														<h5 class="mb-3">Ledger-Add </h5>
                                                     <div class="form-group col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<label class="form-label">Account Subgroup Type : </label>
																<select name="acount_group" id="acount_group" class="form-control" required>
																	 <option value="">--Select--</option>
                                                                     <?php foreach($account as $key => $value){?>
																	  <option value="<?php echo $value['id'];?>"><?php echo $value['account_name'];?></option>
                                                                      <?php } ?>
																</select>
																<?php echo form_error('acount_group'); ?>
															</div>
														</div>
													</div>
													<div class="form-group col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<label class="form-label">Name of Ledger : </label>
																<input class="form-control" type="text" id="ledgerName" name="ledgerName" value="<?php echo $this->input->post('ledgerName'); ?>" required/>
																<?php echo form_error('ledgerName'); ?>
                                                                <!--<span id="val_roll_img"></span>-->
															</div>
														</div>
													</div>
													<div class="form-group col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<label class="form-label">OP Balance : </label>
																<input class="form-control" type="text" id="balance" name="balance" value="<?php echo $this->input->post('balance'); ?>" required/>
																<?php echo form_error('balance'); ?>
                                                               <!-- <span id="val_roll_img"></span>-->
															</div>
														</div>
													</div>
													</fieldset>

													<div class="form-group mt-3">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>addledger" class="btn btn-secondary">Cancel</a>
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
	
});

</script>
<script>
$("#ledgerName").on('change',function(){
	var ledgerName = $(this).val();
	if(ledgerName!=''){
		$('#val_roll_img').removeAttr("class").text('').append('<img title="loading" src="<?php echo base_url();?>images/favicon/loading.gif">');
		
		$.ajax({
				type: 'POST',
				url: '<?php echo ADMIN_URL; ?>addledger/check_ledgerName/',
				data: { ledgerName:ledgerName},
				success: function(data) {
								//alert(data);
								if(data=='true'){
									$("#val_roll_img").text('').attr("class","badge badge-warning").text( "ledgerName '"+ ledgerName +" ' already exists try another !" );
									$("#ledgerName").val('').focus();
								} else {
									$("#val_roll_img").removeAttr("class").text('').attr("class","badge badge-success").text( "Ledger Name  '"+ ledgerName +" ' available !" );
								}
								
							}
				  });
				  
	} else {
		$('#val_roll_img').removeAttr("class").text('');
	}
	
});

</script>

