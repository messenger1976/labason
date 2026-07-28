<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>addbillingperiod">Billing Period Listing</a></li>
		<li class="breadcrumb-item active">add</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-th-list"></i>
			Manage <span class="fw-300">Addbillingperiod Add</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addbillingperiod Add <span class="fw-300"><i>Details</i></span></h2>
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
														<legend>Billing Period-Add </legend>
														
														<div class="form-group col-lg-6">
                                                            <div class="col-lg-12 controls">
                                                                <div class="form-group">
                                                                    <span class="input-group-addon"><i class="icon-user"></i><strong>Zone : </strong></span>
                                                                    <select  class="form-control" name="zone" id="zone"  class="col-lg-12" required>
                                                                    <option value="">--Select--</option>
                                                                    <?php foreach($zone as $key =>$value){ ?>
                                                                    <option value="<?php echo $value['id']; ?>" <?php echo ($value['id']==$record['bp_zone_id'])?'selected':'';?>><?php echo $value['zone'];?></option>
                                                                    <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <div class="col-lg-6 controls">
                                                                <div class="form-group">
                                                                    <span class="input-group-addon"><i class="icon-user"></i><strong>Billing Period (Month): </strong></span>
                                                                    <select  class="form-control" name="billing_month" id="billing_month"  class="col-lg-12" required>
                                                                    <option value="">--Select--</option>
                                                                    <?php foreach($month as $key =>$value){ ?>
                                                                    <option value="<?php echo $value['month_id']; ?>" <?php echo ($value['month_id']==$record['bp_period_month'])?'selected':'';?>><?php echo $value['month_name'];?></option>
                                                                    <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 controls">
                                                                <div class="form-group">
                                                                    <span class="input-group-addon"><i class="icon-user"></i><strong>Billing Period (Year): </strong></span>
                                                                    <input type="text" name="billing_year" id="billing_year" value="<?php echo $record['bp_period_year']; ?>" class="form-control"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <div class="col-lg-12 controls">
                                                                <div class="form-group">
                                                                    <span class="input-group-addon"><i class="icon-user"></i><strong>Start Date: </strong></span>
                                                                    <input  type="text"  class="form-control"  id="start_date" name="start_date"  value="<?php echo ($this->input->post('start_date')!='')?$this->input->post('start_date'):date('d-m-Y',strtotime($record['bp_start_date'])); ?>" required/>
                                                                    <?php echo form_error('start_date'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <div class="col-lg-12 controls">
                                                                <div class="form-group">
                                                                    <span class="input-group-addon"><i class="icon-user"></i><strong>End Date: </strong></span>
                                                                    <input  type="text"  class="form-control"  id="end_date" name="end_date"  value="<?php echo !empty($this->input->post('end_date'))?$this->input->post('end_date'):date('d-m-Y',strtotime($record['bp_end_date'])); ?>" required/>
                                                                    <?php echo form_error('end_date'); ?>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="form-group col-lg-6">
                                                            <div class="col-lg-12 controls">
                                                                <div class="form-group">
                                                                    <span class="input-group-addon"><i class="icon-user"></i><strong>Due Date: </strong></span>
                                                                    <input  type="text"  class="form-control"  id="due_date" name="due_date"  value="<?php echo !empty($this->input->post('due_date'))?$this->input->post('due_date'):date('d-m-Y',strtotime($record['bp_due_date'])); ?>" required/>
                                                                    <?php echo form_error('due_date'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <div class="col-lg-12 controls">
                                                                <div class="form-group">
                                                                    <span class="input-group-addon"><i class="icon-user"></i><strong>Disconnection Date: </strong></span>
                                                                    <input  type="text"  class="form-control"  id="disconnect_date" name="disconnect_date"  value="<?php echo !empty($this->input->post('disconnect_date'))?$this->input->post('disconnect_date'):date('d-m-Y',strtotime($record['bp_disconnection_date'])); ?>" required/>
                                                                    <?php echo form_error('disconnect_date'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <div class="col-lg-12 controls">
                                                                <div class="form-group">
                                                                    <span class="input-group-addon"><i class="icon-user"></i><strong>Status: </strong></span>
                                                                    <select class="form-control" name="status" id="status">
                                                                            <option value="1" <?php echo ($record['bp_status']==1)?'selected':'';?>>Active</option>
                                                                            <option value="0" <?php echo ($record['bp_status']==0)?'selected':'';?>>Inactive</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
														
													</fieldset>

													<div class="form-actions">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>addbillingperiod" class="btn btn-secondary">Cancel</a>
                                                                 <?php
                                                                 if($record['bp_zone_id']>0){
?>
<input type="submit" class="btn btn-primary" name="edit" id="edit" value="Edit">
<?php
                                                                 }else{
                                                                    ?>
<input type="submit" class="btn btn-primary" name="add" id="add" value="Add">
                                                                    <?php
                                                                    

                                                                 }
                                                                 ?>
																
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

$(document).ready(function(){
	$("#start_date").datepicker({
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
	$("#end_date").datepicker({
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
    $("#disconnect_date").datepicker({
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

