<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>addbillingperiod/import">Billing Period</a></li>
		<li class="breadcrumb-item active">import</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-th-list"></i>
			Manage <span class="fw-300">Addbillingperiod Import</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addbillingperiod Import <span class="fw-300"><i>Details</i></span></h2>
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
											

											<?php if($this->session->flashdata('msg_succ') != ''){?>
											<div class="alert alert-block alert-success">
												<button type="button" class="close" data-dismiss="alert">
												<i class="icon-remove"></i>
												</button>
												<p>
													<i class="icon-ok"></i>
													<?php echo $this->session->flashdata('msg_succ')?$this->session->flashdata('msg_succ'):'';?>
												</p>
											</div>
											<?php } ?>

											<fieldset>
												<legend>Import Billing Period
												        <div class="pull-right" style="padding-right:20px;">
															
															
														</div>
												</legend>

                                                <form action="<?php echo ADMIN_URL;?>addbillingperiod/upload" method="post" enctype="multipart/form-data">
                                                    <div class="form-group col-lg-6">
                                                        <div class="col-lg-12 controls">
                                                            <div class="form-group"> 
                                                                <span class="input-group-addon"><i class="icon-user"></i><strong>Import File : </strong></span>
                                                                <input class="form-control" type="file" name="csv_file" accept=".csv" id="csv_file" required>
                                                                <?php echo form_error('customer_id'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--<div class="form-group col-lg-6">
                                                        <div class="col-lg-12 controls">
                                                            <div class="form-group"> 
                                                                <span class="input-group-addon"><i class="icon-user"></i><strong>Reading Date : </strong></span>
                                                                <input class="form-control" type="date" name="reading_date" accept=".csv" id="reading_date" required>
                                                                <?php echo form_error('reading_date'); ?>
                                                            </div>
                                                        </div>
                                                    </div>-->
                                                                
                                                    <div class="form-group col-lg-12">
                                                        <div class="col-lg-12 controls">
                                                            <div class="form-group"> 
                                                                <input type="submit" class="btn btn-primary" name="import" id="import" value="Import" style="margin-bottom: 5px;">
                                                            </div>
                                                        </div>
                                                    </div>	
												</form>	
											</fieldset>

												
				
									</div>
								    
									
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
<script type="text/javascript">
var curDate = '<?php echo date('d-m-Y') ?>';	
function fun_calendor(field){
	$("#"+field).focus();
} 
$(document).ready(function(){

    $('#import').on('click', function(){
		showSpinner();
	});


	$("#fromdate").datepicker({
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

</script>
<script type="text/javascript">
	
		function getaddcustomer_meter(){
			
			var customer_id = $("#customer_id").val();
			var zone = $("#zone").val();
			var fromdate = $("#fromdate").val();
			var todate = $("#todate").val();
			$.ajax({
				beforeSend: function() {
                    showSpinner(); // Call this to show the spinner
                },
				type : "POST",
				url	: '<?php echo ADMIN_URL;?>addmetercustomerreading/getaddcustomersmetersearch',
				data	: "customer_id="+customer_id+"&zone="+zone+"&fromdate="+fromdate+"&todate="+todate,
				complete: function(data){
					var op = data.responseText.trim();
					//alert(op);
					$("#customerDiv").html(op);
                    hideSpinner();
				}
			});
		}
	
		</script>

