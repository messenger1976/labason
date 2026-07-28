<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>dashboard">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>addbillingperiod">Schedule Billing Period</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-th-list"></i>
			Manage <span class="fw-300">Addbillingperiod</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Addbillingperiod <span class="fw-300"><i>Details</i></span></h2>
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
                                            <legend>Billing Period Search
                                            <div  class="pull-right" style="padding-right:20px;">
                                                <input type="submit" class="btn btn-primary" name="search" id="search" value="search" onclick="getaddcustomer_generate();" style="margin-bottom: 5px;">
                                                <a href="<?php echo ADMIN_URL.'addbillingperiod/add'; ?>"class="btn btn-sm btn-warning" style="margin-bottom: 5px;">Add Billing Period</a>
                                                <a class="btn btn-sm btn-success" name="balanceforward" id="balanceforward" value="Close" data-toggle="modal" data-target="#myModal">Balance Forward</a>
												<a id="exporttoexcel" href="<?php echo ADMIN_URL;?>addbillingperiod/fileDownloadunpaidSearch/<?php if($this->input->post('customer_type')!=''){ echo $this->input->post('customer_type'); }else{ echo 0;} ?>/<?php if($this->input->post('zone')!=''){ echo $this->input->post('zone'); }else{ echo 0;} ?>/<?php if($this->input->post('fromdate')!=''){ echo $this->input->post('fromdate'); }else{ echo 0;} ?>/<?php if($this->input->post('todate')!=''){ echo $this->input->post('todate'); }else{ echo 0;} ?>
																		" class="btn btn-sm btn-primary" style="margin-bottom: 4px;">Export Excel for Mobile</a>

																		<a href="<?php echo ADMIN_URL.'addbillingperiod/import'; ?>"class="btn btn-sm btn-info" style="margin-bottom: 5px;">Import Excel from Mobile</a>
                                            </div>
                                            </legend>
                                                
                                                <div class="form-group col-lg-6">
                                                    <div class="col-lg-12 controls">
                                                        <div class="form-group">
                                                    <span class="input-group-addon"><i class="icon-user"></i><strong>Zone : </strong></span>
                                                            <select  class="form-control" name="zone" id="zone"  class="col-lg-12" required>
                                                            <option value="">--All--</option>
                                                            <?php foreach($zone as $key =>$value){ ?>
                                                            <option value="<?php echo $value['id']; ?>"><?php echo $value['zone'];?></option>
                                                            <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <div class="col-lg-12 controls">
                                                        <div class="form-group">
                                                        <span class="input-group-addon"><i class="icon-user"></i><strong>Billing Period : </strong></span>
                                                            <select  class="form-control" name="billingperiod" id="billingperiod" class="col-lg-12" required>
                                                            <option value="">--All--</option>
                                                            <?php foreach($billingperiod as $key =>$value){ ?>
                                                            <option value="<?php echo $value['bp_period_month'].' '.$value['bp_period_year']; ?>"><?php echo $value['month_name'].' '.$value['bp_period_year'];?></option>
                                                            <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                                
                                                        
                                                </div>
                                                    
                                                
                                        </fieldset>

            
                                </div>
                                
                                
                            </div>	
                        </div>
                        <div class="col-sm-6 col-lg-12" id="billingPeriodDiv" style="margin-top: 13px;"></div>	
                        </div>
						
				
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
	
    function getaddcustomer_generate(){
        //alert('Hello');
        
        var zone = $("#zone").val();
        var billingperiod = $("#billingperiod").val();
        showSpinner(); // Call this to show the spinner
		
        $.ajax({
            
            type : "POST",
            url	: '<?php echo base_url();?>master/addbillingperiod/addbillingperiod_search',
            //data	: "customer_type="+customer_type+"&zone="+zone+"&fromdate="+fromdate+"&todate="+todate+",
            data	: "zone="+zone+"&billingperiod="+billingperiod,
            complete: function(data){
                var op = data.responseText.trim();
                //alert(op);
                $("#billingPeriodDiv").html(op);
            }
        });
        setTimeout(hideSpinner, 1000); // Simulate loading for 3 seconds
    }

</script>

