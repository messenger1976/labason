<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addexpenses/">Expenses</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addexpenses/add/">Add Expenses</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-money-bill"></i>
			Manage <span class="fw-300">Addexpenses Search</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addexpenses Search <span class="fw-300"><i>Details</i></span></h2>
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
								<div class="form-horizontal" >
										  	
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
												<h5 class="mb-3">Expenses-Search
												<div class="float-right" style="padding-right:20px;">
													<?php if($this->uri->segment('3')=='search'){?>
																<input type="submit" class="btn btn-primary" name="search" id="search" value="search" onclick="getaddexpenses();" style=" margin-bottom : 5px;">
															<?php } if($this->uri->segment('3')=='bsearch'){ ?>
															    <input type="submit" class="btn btn-primary" name="search" id="search" value="search" onclick="getaddexpenses_balance();" style=" margin-bottom : 5px;">
															<?php } ?>
													<a href="<?php echo ADMIN_URL;?>addexpenses/fileDownloadajax/<?php echo $this->input->post('fromdate');?>/<?php echo $this->input->post('todate');?>"class="btn btn-sm btn-primary" style="margin-bottom: 4px;">Export Excel</a>
													<!--<a href="<?php echo ADMIN_URL;?>addexpenses/fileDownloadajax/<?php echo $id;?>"class="btn btn-sm btn-primary" style="margin-bottom: 4px;">Export pdf</a>-->
												</div>
												</h5>
													
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">
															<label class="form-label">From-Date:</label>
															<input class="form-control"  type="text" id="fromdate" name="fromdate"  placeholder="dd-mm-yyyy" value="">
														</div>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls">
														<div class="form-group">			
															<label class="form-label">To-Date:</label>
															<input class="form-control"  type="text" id="todate" name="todate"  placeholder="dd-mm-yyyy" value="">
														</div>
													</div>	
												</div>	
											</fieldset>

				
									</div>
								    
									
								</div>	
						</div>
						
						<div class="col-12 col-lg-12" id="expensesDiv"></div>
				        </div>
								
					
					                 
					
					</div>
                    

						
					<!-- end row -->

				</section>
			<!-- end widget grid -->
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
	
		function getaddexpenses(){
			
			
			var fromdate = $("#fromdate").val();
			var todate = $("#todate").val();
			
			$.ajax({
				
				type : "POST",
				url	: '<?php echo ADMIN_URL;?>addexpenses/getaddexpensessearch',
				//data	: "customer_type="+customer_type+"&zone="+zone+"&fromdate="+fromdate+"&todate="+todate+",
				data	: "fromdate="+fromdate+"&todate="+todate,
				complete: function(data){
					var op = data.responseText.trim();
					//alert(op);
					$("#expensesDiv").html(op);
				}
			});
		}
		
	function getaddexpenses_balance(){
			
			
			var fromdate = $("#fromdate").val();
			var todate = $("#todate").val();
			
			$.ajax({
				
				type : "POST",
				url	: '<?php echo ADMIN_URL;?>addexpenses/getaddexpensesbsearch',
				//data	: "customer_type="+customer_type+"&zone="+zone+"&fromdate="+fromdate+"&todate="+todate+",
				data	: "fromdate="+fromdate+"&todate="+todate,
				complete: function(data){
					var op = data.responseText.trim();
					//alert(op);
					$("#expensesDiv").html(op);
				}
			});
		}
	
		</script>

