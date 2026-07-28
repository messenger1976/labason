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
			Manage <span class="fw-300">Addcustomer Unpaidsearch</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addcustomer Unpaidsearch <span class="fw-300"><i>Details</i></span></h2>
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
												<legend>Un-PaidCustomer-Search   
												    <div class="pull-right" style="padding-right:20px;">
														<input type="submit" class="btn btn-primary" name="search" id="search" value="search" onclick="getaddcustomer_unpaid();" style="margin-bottom: 5px;">
														<a id="exporttoexcel" href="<?php echo ADMIN_URL;?>addcustomer/fileDownloadunpaidSearch/<?php if($this->input->post('customer_type')!=''){ echo $this->input->post('customer_type'); }else{ echo 0;} ?>/<?php if($this->input->post('zone')!=''){ echo $this->input->post('zone'); }else{ echo 0;} ?>/<?php if($this->input->post('fromdate')!=''){ echo $this->input->post('fromdate'); }else{ echo 0;} ?>/<?php if($this->input->post('todate')!=''){ echo $this->input->post('todate'); }else{ echo 0;} ?>
																		" class="btn btn-sm btn-primary" style="margin-bottom: 4px;">Export Excel</a>
														<a href="<?php echo ADMIN_URL;?>addcustomer/filePrintunpaidSerch/<?php if($this->input->post('customer_type')!=''){ echo $this->input->post('customer_type'); }else{ echo 0;} ?>/<?php if($this->input->post('zone')!=''){ echo $this->input->post('zone'); }else{ echo 0;} ?>/<?php if($this->input->post('fromdate')!=''){ echo $this->input->post('fromdate'); }else{ echo 0;} ?>/<?php if($this->input->post('todate')!=''){ echo $this->input->post('todate'); }else{ echo 0;} ?>
																		"class="btn btn-sm btn-primary" style="margin-bottom: 4px;">Export Pdf</a>
                                                   </div>									
	
												
												</legend>
													<div class="form-group col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group"> 
																<span class="input-group-addon"><i class="icon-user"></i><strong>Membership Status: </strong></span>
																<select class="form-control" name="membership_status" id="membership_status" required>
																	<option value="all">--All--</option>
																	 <option value="1">Member</option>
																	  <option value="0">Non-Member</option>
																 </select>
															</div>
														</div>
													</div>
													<div class="form-group col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group"> 
																<span class="input-group-addon"><i class="icon-user"></i><strong>Zone: </strong></span>
																<select class="form-control" name="zone" id="zone">
																<option value="all">--All--</option>
																	<?php foreach($zone as $key => $value){ ?>
																	 <option value="<?php echo $value['id'];?>"><?php echo $value['zone'];?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
													</div>
													<div class="form-group col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
															<span class="input-group-addon"><i class="icon-user"></i><strong>Billing Period : </strong></span>
																<select  class="form-control" name="billingperiod" id="billingperiod">
																	<option value="all">--All--</option>
																	<?php foreach($billingperiod as $key =>$value){ ?>
																	<option value="<?php echo $value['bp_period_month'].' '.$value['bp_period_year']; ?>"><?php echo $value['month_name'].' '.$value['bp_period_year'];?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
													
													</div>
													<!--<div class="form-group col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>From-Date:</strong></span>
																<input class="form-control"  type="text" id="fromdate" name="fromdate"  placeholder="dd-mm-yyyy" value="">
															</div>
														</div>
													</div>
													<div class="form-group col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>To-Date:</strong></span>
																<input class="form-control"  type="text" id="todate" name="todate"  placeholder="dd-mm-yyyy" value="">
															</div>
														</div>
													</div>	-->
															
													
													
											</fieldset>
																
				
									</div>
								    
									
								</div>	
						</div>
						
						<div class="col-12" id="unpaidcustomerDiv" style="margin-top: 13px;"></div>	
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
	
		function getaddcustomer_unpaid(){
			
			var membership_status = $("#membership_status").val();
			var zone = $("#zone").val();
			//var fromdate = $("#fromdate").val();
			//var todate = $("#todate").val();
			var billingperiod = $("#billingperiod").val();

			$.ajax({
				
				type : "POST",
				url	: '<?php echo ADMIN_URL;?>addcustomer/getaddcustomersunpaidsearch',
				//data	: "customer_type="+customer_type+"&zone="+zone+"&fromdate="+fromdate+"&todate="+todate+",
				data	: "membership_status="+membership_status+"&zone="+zone+"&billingperiod="+billingperiod,
				complete: function(data){
					var op = data.responseText.trim();
					//alert(op);
					$("#unpaidcustomerDiv").html(op);
				}
			});
		}
	
		$('#exporttoexcel').on('click', function(evt){
			evt.preventDefault();
			var membership_status = $("#membership_status").val();
			var zone = $("#zone").val();
			if($("#billingperiod").val()=='all'){
				var billingmonth = 'all';
				var billingyear = 'all';
			}else{
				var billingperiod = $("#billingperiod").val().split(" ");
				var billingmonth = billingperiod[0];
				var billingyear = billingperiod[1];
			}
			
			//window.location.href = '<?php echo ADMIN_URL;?>addcustomer/fileDownloadunpaidSearch/'+membership_status+'/'+zone+'/'+billingperiod;
			
			//alert('<?php echo ADMIN_URL;?>addcustomer/fileDownloadunpaidSearch/'+membership_status+'/'+zone+'/'+billingperiod);
			window.open('<?php echo ADMIN_URL;?>addcustomer/fileDownloadunpaidSearch/'+membership_status+'/'+zone+'/'+billingmonth+'/'+billingyear, '_blank');
		});
		
		</script>

