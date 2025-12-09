
<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">

				<span class="ribbon-button-alignment"> 
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
						<i class="fa fa-refresh"></i>
					</span> 
				</span>

				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li><a href="<?php echo ADMIN_URL;?>dashboard">Home</a></li>
					<li><a href="<?php echo ADMIN_URL;?>addbillingperiod"> Schedule Billing Period </a></li>
					<li>List View</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">
			<?php
//$bp_current_array = $this->session->userdata('current_billingperiod'); 
//print_r($this->session->userdata('current_billingperiod'));
//print_r($_SESSION['current_billingperiod']);
//echo '<br/>';
//print_r($current_billingperiod);
//echo '<br/>';
//echo 'month:'.$bp_current_array[0]['bp_period_month'];
//exit;
									?>
				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> View <span>> Schedule Billing Period</span></h1>
					</div>
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
						<ul id="sparks" class="">
							<li class="sparks-info">
								<h5> Billing Period <span class="txt-color-blue">
									
								<select  class="form-control" name="header_billingperiod" id="header_billingperiod" class="col-lg-12" required>
									<option value="">--All--</option>
									<?php
									
									foreach($billingperiod as $key =>$value){ 
										$val_val = $value['bp_period_month'].' '.$value['bp_period_year'];
										$selected_val = '';
										if($_SESSION['current_billingperiod']==$val_val){
											$selected_val = 'selected';
										}
									?>
									<option value="<?php echo $value['bp_period_month'].' '.$value['bp_period_year']; ?>" <?php echo $selected_val;?>><?php echo $value['month_name'].' '.$value['bp_period_year'];?></option>
									<?php } ?>
								</select>								
								</span></h5>
							</li>
							<li class="sparks-info">
							<?php 
							     $income1 = $this->comm_model->get_income_metercustomer();
							     extract($income1);
								 $income2 = $this->comm_model->get_income_monthlycustomer();
								 extract($income2);
								 $intotal = $total1 + $total2;
							?>
								<h5> Income <span class="txt-color-blue">PHP <?php print_r(number_format($intotal,2));?></span></h5>
								<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>
							<?php
							     $expense1 = $this->my_model->get_outcome_expenses();
							     extract($expense1);
								 $expense2 = $this->my_model->get_outcome_payroll();
								 extract($expense2);
								 $extotal = $extotal1 + $extotal2;
							?>
							<li class="sparks-info">
								<h5> Expense <span class="txt-color-purple">PHP <?php print_r(number_format($extotal,2));?></span></h5>
								<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>
							<?php 
							     $total_customer = $this->my_model->total_customer();
							     extract($total_customer); 
							?>
							<li class="sparks-info">
								<h5> Total Customer <span class="txt-color-greenDark">&nbsp;<?php print_r($count_id);?></span></h5>
								<div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>
						</ul>
					</div>
				</div>
				<!-- widget grid -->
				<section id="widget-grid" class="">

					<!-- row -->
					<div class="row">
				
                    <!-- a blank row to get started -->
						<div class="col-sm-6 col-lg-12">
                            <!-- your contents here -->
                            <div class="panel panel-default">
                                
                                <div class="widget-body">
            
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
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->
		




				<!-- Modal -->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									&times;
								</button>
								<h4 class="modal-title" id="myModalLabel">Balance Forwarding</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12 controls">
										<div class="form-group">
											<label for="category">Current Billing Period</label>
											<select  class="form-control" name="currentbillingperiod" id="currentbillingperiod" class="col-lg-12" required>
												
												<?php foreach($billingperiod as $key =>$value){ ?>
												<option value="<?php echo $value['bp_period_month'].' '.$value['bp_period_year']; ?>"><?php echo $value['month_name'].' '.$value['bp_period_year'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									
								</div>
								
								<div class="row">
									<div class="col-md-12 controls">
										<div class="form-group">
											<label for="category">Next Billing Period</label>
											<select  class="form-control" name="forwardbillingperiod" id="forwardbillingperiod" class="col-lg-12" required>
												
												<?php foreach($billingperiod as $key =>$value){ ?>
												<option value="<?php echo $value['bp_period_month'].' '.$value['bp_period_year']; ?>"><?php echo $value['month_name'].' '.$value['bp_period_year'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									
								</div>
								<div class="row">
									<div class="col-md-12 controls">
										<div class="form-group">
											<label for="category">Zone</label>
											<select  class="form-control" name="zone_listing" id="zone_listing" class="col-lg-12" required>
												
												<?php foreach($zone_listing as $key =>$value){ ?>
												<option value="<?php echo $value['id']; ?>"><?php echo $value['zone'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									
								</div>
				
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">
									Cancel
								</button>
								<button type="button" class="btn btn-primary" id="btn_posting" data-dismiss="modal">
									Balance Posting
								</button>
							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->


		<?php include('footer.php');?>

	</body>

</html>
<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url();?>js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script type="text/javascript">
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
		$(document).ready(function() {
			
			pageSetUp();
			
			/* // DOM Position key index //
		
			l - Length changing (dropdown)
			f - Filtering input (search)
			t - The Table! (datatable)
			i - Information (records)
			p - Pagination (paging)
			r - pRocessing 
			< and > - div elements
			<"#id" and > - div with an id
			<"class" and > - div with a class
			<"#id.class" and > - div with an id and class
			
			Also see: http://legacy.datatables.net/usage/features
			*/	
	
			/* BASIC ;*/
				var responsiveHelper_dt_basic = undefined;
				var responsiveHelper_datatable_fixed_column = undefined;
				var responsiveHelper_datatable_col_reorder = undefined;
				var responsiveHelper_datatable_tabletools = undefined;
				
				var breakpointDefinition = {
					tablet : 1024,
					phone : 480
				};
	
				$('#dt_basic').dataTable({
					"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
					"autoWidth" : true,
			        "oLanguage": {
					    "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
					},
					"preDrawCallback" : function() {
						// Initialize the responsive datatables helper once.
						if (!responsiveHelper_dt_basic) {
							responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
						}
					},
					"rowCallback" : function(nRow) {
						responsiveHelper_dt_basic.createExpandIcon(nRow);
					},
					"drawCallback" : function(oSettings) {
						responsiveHelper_dt_basic.respond();
					}
				});
	
			/* END BASIC */
			
			/* COLUMN FILTER  */
		    var otable = $('#datatable_fixed_column').DataTable({
		    	//"bFilter": false,
		    	//"bInfo": false,
		    	//"bLengthChange": false
		    	//"bAutoWidth": false,
		    	//"bPaginate": false,
		    	//"bStateSave": true // saves sort state using localStorage
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
				"autoWidth" : true,
				"oLanguage": {
					"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
				},
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_fixed_column) {
						responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_fixed_column.respond();
				}		
			
		    });
		    
		    // custom toolbar
		    $("div.toolbar").html('<div class="text-right"><img src="img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');
		    	   
		    // Apply the filter
		    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
		    	
		        otable
		            .column( $(this).parent().index()+':visible' )
		            .search( this.value )
		            .draw();
		            
		    } );
		    /* END COLUMN FILTER */   
	    
			/* COLUMN SHOW - HIDE */
			$('#datatable_col_reorder').dataTable({
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
				"autoWidth" : true,
				"oLanguage": {
					"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
				},
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_col_reorder) {
						responsiveHelper_datatable_col_reorder = new ResponsiveDatatablesHelper($('#datatable_col_reorder'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_col_reorder.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_col_reorder.respond();
				}			
			});
			
			/* END COLUMN SHOW - HIDE */
	
			/* TABLETOOLS */
			$('#datatable_tabletools').dataTable({
				
				// Tabletools options: 
				//   https://datatables.net/extensions/tabletools/button_options
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
				"oLanguage": {
					"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
				},		
		        "oTableTools": {
		        	 "aButtons": [
		             "copy",
		             "csv",
		             "xls",
		                {
		                    "sExtends": "pdf",
		                    "sTitle": "SmartAdmin_PDF",
		                    "sPdfMessage": "SmartAdmin PDF Export",
		                    "sPdfSize": "letter"
		                },
		             	{
	                    	"sExtends": "print",
	                    	"sMessage": "Generated by SmartAdmin <i>(press Esc to close)</i>"
	                	}
		             ],
		            "sSwfPath": "js/plugin/datatables/swf/copy_csv_xls_pdf.swf"
		        },
				"autoWidth" : true,
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_tabletools) {
						responsiveHelper_datatable_tabletools = new ResponsiveDatatablesHelper($('#datatable_tabletools'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_tabletools.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_tabletools.respond();
				}
			});
			
			/* END TABLETOOLS */


			$('#btn_posting').on('click', function(evt){
				evt.preventDefault();

				if(confirm('Continue Posting?')==true){
						showSpinner(); // Call this to show the spinner
						var selectedItems = [];
						var billingperiodforward = $('#forwardbillingperiod').val();
						var currentbillingperiod = $('#currentbillingperiod').val();
						var zone_id = $('#zone_listing').val();
						

						/*$("input[name='delete_ids[]']:checked").each(function(){
							selectedItems.push($(this).val());
						});

						if(selectedItems.length === 0) {
							alert("Please select at least one checkbox.");
							return;
						}*/

						$.ajax({
							url: "<?php echo base_url();?>master/addbillingperiod/billingforwardposting", 
							type: "POST",
							data: {
								//delete_ids: selectedItems,
								billingperiodforward: billingperiodforward,
								currentbillingperiod: currentbillingperiod,
								zone_listing: zone_id
							},
							success: function(response){
								//alert(response);
								$('#search').trigger('click');
								setTimeout(hideSpinner, 1000); // Simulate loading for 3 seconds
							},
							error: function(xhr, status, error){
								console.log(error);
							}
						});
						
        				

						return true;
					}else{
						return false;
					}



				/*var checked_num = $('input[name="delete_ids[]"]:checked').length;
				if (checked_num == 0) {
					alert('Select Atleast One Check Box... ');
					return false;
				}else if (checked_num > 0){ 
					
				}*/


				
			});

		

			$('#header_billingperiod').on('change', function(evt){
				evt.preventDefault();
				var header_billing_period = $(this).val();
				//showSpinner();
				$.ajax({
            		type : "POST",
					url	: '<?php echo base_url();?>master/addbillingperiod/updated_headerbillingperiod',
					data	: "billing_period="+header_billing_period,
					complete: function(data){
						console.log(data);
						//if(data=='success'){
							location.reload();
							//window.location.replace(window.location.href);
							//window.location.href = '<?php echo ADMIN_URL;?>addbillingperiod';
						//}
					}
				});

				//alert($(this).val());
			});


			$('#exporttoexcel').on('click', function(evt){
				evt.preventDefault();
				//var membership_status = $("#membership_status").val();
				var zone = $("#zone").val();
				if(zone==''){
					zone = 'all';
				}
				if($("#billingperiod").val()==''){
					var billingmonth = 'all';
					var billingyear = 'all';
				}else{
					var billingperiod = $("#billingperiod").val().split(" ");
					var billingmonth = billingperiod[0];
					var billingyear = billingperiod[1];
				}
				
				//window.location.href = '<?php echo ADMIN_URL;?>addcustomer/fileDownloadunpaidSearch/'+membership_status+'/'+zone+'/'+billingperiod;
				
				//alert('<?php echo ADMIN_URL;?>addcustomer/fileDownloadunpaidSearch/'+membership_status+'/'+zone+'/'+billingperiod);
				window.open('<?php echo ADMIN_URL;?>addbillingperiod/fileDownloadBillingPeriodMobileSearch/'+zone+'/'+billingmonth+'/'+billingyear, '_blank');
			});

		})

		</script>

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