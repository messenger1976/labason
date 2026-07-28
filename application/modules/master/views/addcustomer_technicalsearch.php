<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addcustomer/">Customer</a></li>
		<li class="breadcrumb-item active">Search</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user-friends"></i>
			Manage <span class="fw-300">Addcustomer Technicalsearch</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addcustomer Technicalsearch <span class="fw-300"><i>Details</i></span></h2>
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
												<legend>Technical-Search
												<div class="pull-right">
													<input type="submit" class="btn btn-primary" name="search" id="search" value="search" onclick="getaddcustomer_technical();" style="margin-bottom: 5px;">
													<a href="<?php echo ADMIN_URL;?>addcustomer/fileDownloadajaxtechnicalsearch/<?php echo $this->input->post('customer_type');?>"class="btn btn-sm btn-primary" style="margin-bottom: 4px;">Export Excel</a>
													<!--<a href="<?php echo ADMIN_URL;?>addcustomer/tables"class="btn btn-sm btn-primary" style="margin-bottom: 4px;">Export pdf</a>-->
												</div>
												</legend>
												<div class="form-group col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>SelectCustomer Type : </strong></span>
																 <select class="form-control" name="customer_type" id="customer_type" required>
																	<option value="">--Select--</option>
																	 <option value="monthlycustomer">monthlycustomer</option>
																	  <option value="metercustomer">metercustomer</option>
																  </select>
															</div>
														</div>
													</div>
														
													
											</fieldset>

																
													
				
									</div>
								    
									
								</div>	
						</div>
						
						<div class="col-12" id="customerDiv" style="margin-top: 13px;"></div>	
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
	
		function getaddcustomer_technical(){
			
			var customer_type = $("#customer_type").val();
			
			
			$.ajax({
				
				type : "POST",
				url	: '<?php echo ADMIN_URL;?>addcustomer/getaddcustomerstechnicalsearch',
				//data	: "customer_type="+customer_type+"&zone="+zone+"&fromdate="+fromdate+"&todate="+todate+",
				data	: "customer_type="+customer_type,
				complete: function(data){
					var op = data.responseText.trim();
					//alert(op);
					$("#customerDiv").html(op);
				}
			});
		}
	
		

</script>

