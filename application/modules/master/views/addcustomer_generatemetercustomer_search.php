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
			Manage <span class="fw-300">Addcustomer Generatemetercustomer Search</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addcustomer Generatemetercustomer Search <span class="fw-300"><i>Details</i></span></h2>
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
												<legend>Generate-Customer-Search
												  <div  class="pull-right" style="padding-right:20px;">
													<input type="submit" class="btn btn-primary" name="search" id="search" value="search" onclick="getaddcustomer_generate();" style="margin-bottom: 5px;">
													<a href="<?php echo ADMIN_URL;?>addcustomer/fileDownloadCustomer/<?php if($this->input->post('zone')!=''){ echo $this->input->post('zone'); }else{ echo 0;} ?>/<?php if($this->input->post('mobile1')!=''){ echo $this->input->post('mobile1'); }else{ echo 0;} ?>"class="btn btn-sm btn-primary" style="margin-bottom: 4px;">Export Excel</a>
													<a href="<?php echo ADMIN_URL;?>addcustomer/filePrintCustomer/<?php if($this->input->post('zone')!=''){ echo $this->input->post('zone'); }else{ echo 0;} ?>/<?php if($this->input->post('mobile1')!=''){ echo $this->input->post('mobile1'); }else{ echo 0;} ?>"class="btn btn-sm btn-primary" style="margin-bottom: 4px;">Export pdf</a>
												</div>
												</legend>
													
													<div class="form-group col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
														   <span class="input-group-addon"><i class="icon-user"></i><strong>Zone : </strong></span>
																<select  class="form-control" name="zone" id="zone"  class="col-lg-12" required>
																<option value="">--Select--</option>
																<?php foreach($record as $key =>$value){ ?>
																 <option value="<?php echo $value['id']; ?>"><?php echo $value['zone'];?></option>
																 <?php } ?>
																</select>
															</div>
														</div>
													</div>
													<div class="form-group col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
														   <span class="input-group-addon"><i class="icon-user"></i><strong>Mobile1: </strong></span>
																<input  class="form-control" type="text" id="mobile1" name="mobile1"  value="<?php echo $this->input->post('mobile1'); ?>" required/>
                                                               <?php echo form_error('mobile1'); ?>
															</div>
														</div>
													</div>
															
													</div>
														
													
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
		minDate: curDate,
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
		minDate: curDate,
		//maxDate: ''
	});
});

</script>
<script type="text/javascript">
	
		function getaddcustomer_generate(){
			
			
			var zone = $("#zone").val();
			var mobile1 = $("#mobile1").val();
			
			$.ajax({
				
				type : "POST",
				url	: '<?php echo ADMIN_URL;?>addcustomer/addcustomer_generatemetercustomer_search',
				//data	: "customer_type="+customer_type+"&zone="+zone+"&fromdate="+fromdate+"&todate="+todate+",
				data	: "zone="+zone+"&mobile1="+mobile1,
				complete: function(data){
					var op = data.responseText.trim();
					//alert(op);
					$("#customerDiv").html(op);
				}
			});
		}
	
		</script>

