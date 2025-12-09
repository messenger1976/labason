	<style>
	.select2-container{
		width: 100% !important;
		
	}
	.setStatus{
		cursor: pointer;
	}
	
	

</style>
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
					<li><a href="<?php echo ADMIN_URL?>">Home</a></li>
					<li><a href="<?php echo ADMIN_URL?>technicalproblems/">Technical Problems </a></li>
					<li>Edit</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa fa-pencil-square-o fa-fw"></i>edit <span>>  Technical Problems </span></h1>
					</div>
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
						<ul id="sparks" class="">
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
							     $expense1 = $this->comm_model->get_outcome_expenses();
							     extract($expense1);
								 $expense2 = $this->comm_model->get_outcome_payroll();
								 extract($expense2);
								 $extotal = $extotal1 + $extotal2;
							?>
							<li class="sparks-info">
								<h5> Expense <span class="txt-color-purple">PHP <?php print_r(number_format($extotal,2));?></span></h5>
								<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>
							<?php 
							     $total_customer = $this->comm_model->total_customer();
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
														<legend>Technical Problems-Edit </legend>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Customer-Id : </strong></span>
																<input class="form-control" type="text" id="customer_id" name="customer_id" value="<?php echo $record['customer_id']; ?>" readonly style="background-color: yellow;"/>
                                                                <?php echo form_error('customer_id'); ?>
															</div>
														</div>
													</div>
													<input type="hidden" name="technical_id" id="technical_id" value="<?php echo $record['id']; ?>">
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Customer Name : </strong></span>
																<input class="form-control" type="text" id="customer_name" name="customer_name" value="<?php echo $record['lastname'].', '.$record['firstname'].' '.$record['middlename']; ?>" readonly style="background-color: yellow;"/>
                                                                <?php echo form_error('customer_name'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Meter Number : </strong></span>
																<input class="form-control" type="text" id="meter_number" name="meter_number" value="<?php echo $record['meter_number']; ?>" readonly style="background-color: yellow;"/>
                                                                <?php echo form_error('meter_number'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Address : </strong></span>
																<input class="form-control" type="text" id="address" name="address" value="<?php echo $record['address']; ?>" readonly style="background-color: yellow;"/>
                                                                <?php echo form_error('address'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Problem Summary : </strong></span>
																<input class="form-control" type="text" id="problem_summary" name="problem_summary" value="<?php echo $record['problem_summary']; ?>" readonly style="background-color: yellow;"/>
                                                                <?php echo form_error('problem_summary'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Status : </strong></span>
																<select class="form-control" name="status" id="status" placeholder="Type text to search..." readonly style="background-color: yellow;">
																	<option value="0" id="pending" <?php echo $record['status']==0?'selected':''; ?>>Pending</option>	
																	<option value="1" id="assigned" <?php echo $record['status']==1?'selected':''; ?>>Assigned</option>	
																	<option value="2" id="ongoing" <?php echo $record['status']==2?'selected':''; ?>>On Going</option>	
																	<option value="3" id="resolved" <?php echo $record['status']==3?'selected':''; ?>>Resolved</option>
																	<option value="4" id="unresolved" <?php echo $record['status']==4?'selected':''; ?>>Un-Resolved</option>
																	<option value="5" id="resolved-closed" <?php echo $record['status']==5?'selected':''; ?>>Resolved - Closed</option>
																	<option value="6" id="unresolved-closed" <?php echo $record['status']==6?'selected':''; ?>>UnResolved - Closed</option>
																</select>
                                                                <?php echo form_error('status'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Reported By : </strong></span>
																<select class="form-control" name="reportedby" id="reportedby" readonly style="background-color: yellow;">
																		
																		<?php foreach($employee as $key => $emp){ ?>
																		<option value="<?php echo $emp['id'];?>" <?php echo $emp['id']==$record['reported_by_id']?'selected':''; ?>><?php echo strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle'];?></option>
																		<?php } ?>
																	</select>
                                                                <?php echo form_error('reportedby'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Reported Date:</strong></span>
																<input class="form-control"  type="text" id="reported_date" name="reported_date" readonly placeholder="DD-MM-YYYY" value="<?php echo $record['reported_date']!=''?date('d-m-Y',strtotime($record['reported_date'])):Date('d-m-Y'); ?>" readonly style="background-color: yellow;">
															</div>
														</div>
													</div>
													<div class="col-lg-12">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<span class="input-group-addon"><i class="icon-user"></i><strong>Problem Details:</strong></span>
																<!--<input type="text" id="address" name="address" class="col-xs-10 col-sm-10" value="<?php echo $record['problem_details']; ?>" required/>-->
																<textarea class="form-control" rows="5" cols="25" id="problem_details" name="problem_details" readonly style="background-color: yellow;"><?php echo $record['problem_details']; ?></textarea>
																<?php echo form_error('problem_details'); ?>
															</div>
														</div>
													</div>
													<div class="col-lg-12">
														<div class="col-lg-12 controls">
															<div class="form-group">
																<button class="btn btn-primary btn-xs" id="btn_messages"  data-toggle="modal" data-target="#myModal">Add Message</button>
															</div>
														</div>
													</div>

													<div class="col-lg-12">
														<table class="table table-striped table-bordered table-hover" width="100%">
															<thead>
																<tr>
																	<th width="10%">Date</th>
																	<th>Message</th>
																	<th width="10%">Status</th>
																	<th width="20%">Reported by</th>
																</tr>
															</thead>
															<tbody>
															<?php
															if(count($record_messages) > 0){
																$i=1;
																foreach($record_messages as $key => $row1){
																	?>
																	<tr>
																		<td><?php echo date('m/d/Y',strtotime($row1['technical_msg_reported_date'])); ?></td>
																		<td><?php echo $row1['technical_msg_text']; ?></td>
																		<td><span <?php 
														if($row1['technical_msg_status']== 1){ 
															echo " class='label bg-color-orange arrowed-in arrowed-in-right'"; 
														} elseif($row1['technical_msg_status']== 0){ 
															echo "class='label label-danger arrowed'"; 
														} elseif($row1['technical_msg_status']== 2){ 
															echo "class='label bg-color-green arrowed'"; 
														}elseif($row1['technical_msg_status']== 3){ 
															echo "class='label  label-success arrowed'"; 
														}elseif($row1['technical_msg_status']== 4){ 
															echo "class='label bg-color-pink arrowed'"; 
														}elseif($row1['technical_msg_status']== 5){ 
															echo "class='label bg-color-blue arrowed'"; 
														}elseif($row1['technical_msg_status']== 6){ 
															echo "class='label bg-color-redLight arrowed'"; 
														} ?>>
														<?php
																		if($row1['technical_msg_status']==0){
																			echo 'Pending';
																		}elseif($row1['technical_msg_status']==1){
																			echo 'Assigned';
																		}elseif($row1['technical_msg_status']==2){
																			echo 'On going';
																		}elseif($row1['technical_msg_status']==3){
																			echo 'Resolved';
																		}elseif($row1['technical_msg_status']==4){
																			echo 'Un-Resolved';
																		}elseif($row1['technical_msg_status']==5){
																			echo 'Resolved - Closed';
																		}elseif($row1['technical_msg_status']==6){
																			echo 'UnResolved - Closed';
																		}
																		echo '</span>';
//																		echo $row1['technical_msg_status']; 
																		?></td>
																		<td><?php echo $row1['technical_msg_reported_name']; ?></td>
																	</tr>
																	<?php
																}
															}else{
																echo '<tr><td colspan=4><center>No record found</center></td></tr>';
															}
															?>

																
																
															</tbody>
														</table>
													</div>
													</fieldset>
													
													
													
													<div class="form-actions">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>technicalproblems" class="btn btn-default">Cancel</a>
																<input type="submit" class="btn btn-primary" name="add" id="add" value="Edit">
															</div>
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
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->

		

		<!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="false">
									&times;
								</button>
								<h4 class="modal-title" id="myModalLabel">Add Message</h4>
							</div>
							<div class="modal-body">
								
                                <form name="frm_update" id="frm_update" action="" method="POST">
									<div class="row" id="source_type_div">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Status : </strong></span>
                                                <select class="form-control" name="msg_status" id="msg_status" placeholder="Type text to search..." required>
																	<option value="0" <?php echo $record['status']==0?'selected':''; ?>>Pending</option>	
																	<option value="1" <?php echo $record['status']==1?'selected':''; ?>>Assigned</option>	
																	<option value="2" <?php echo $record['status']==2?'selected':''; ?>>On Going</option>	
																	<option value="3" <?php echo $record['status']==3?'selected':''; ?>>Resolved</option>
																	<option value="4" <?php echo $record['status']==4?'selected':''; ?>>Un-Resolved</option>
																	<option value="5" <?php echo $record['status']==5?'selected':''; ?>>Resolved - Closed</option>
																	<option value="6" <?php echo $record['status']==6?'selected':''; ?>>UnResolved - Closed</option>
																</select>
												
												<?php echo form_error('msg_status'); ?>
                                            </div>
                                        </div>
                                    </div>
									
									
									
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Date : (*)</strong></span>
                                                <input class="form-control text-input" type="text" id="posted_date" name="posted_date" value="<?php echo $this->input->post('posted_date')!=''?$this->input->post('posted_date'):Date('d-m-Y'); ?>" required>
                                                <?php echo form_error('posted_date'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Messages : </strong></span>
												<textarea class="form-control text-input" type="text" id="msg_logs" name="msg_logs" rows="5"><?php echo $this->input->post('msg_logs')!=''?$this->input->post('msg_logs'):''; ?></textarea>
                                                
                                                <?php echo form_error('msg_logs'); ?>
                                            </div>
                                        </div>
                                    </div>

									<div class="row" id="source_type_div">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Reported By : </strong></span>
                                                <select class="form-control" name="msg_reportedby" id="msg_reportedby" required>
																		
																		<?php foreach($employee as $key => $emp){ ?>
																		<option value="<?php echo $emp['id'];?>"><?php echo strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle'];?></option>
																		<?php } ?>
																	</select>
												
												<?php echo form_error('msg_status'); ?>
                                            </div>
                                        </div>
                                    </div>

									



								</form>
				
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
									Cancel
								</button>
								<button type="submit" class="btn btn-sm btn-primary" id="btn_save" name="btn_save" value="add">
									Add
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

			/*$("#reported_date").datepicker({
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
			});*/
			$("#posted_date").datepicker({
				showAnim: null,
				dateFormat: 'dd-mm-yy',
				// showOn: 'both',
				buttonImage: '/images/calender.jpg',
				buttonImageOnly: true,
				firstDay: 1,
				nextText: '',
				prevText: '',
				//numberOfMonths: [1, 1],
				//defaultDate: new Date(curDate),
				//minDate: curDate,
				//maxDate: ''
			});

			$('#btn_messages').on('click', function(evt){
				evt.preventDefault();
			});

			$('#btn_save').on('click', function(evt){
				evt.preventDefault();
				var technical_id = $('#technical_id').val();
				var msg_status = $('#msg_status').val();
				var posted_date = $('#posted_date').val();
				var msg_logs = $('#msg_logs').val();
				var msg_reportedby = $('#msg_reportedby').val();
				var btn_save = $('#btn_save').val();
				var reportedby_name = $('#msg_reportedby').find('option:selected').text();

				const formData = new FormData();
				formData.append("technical_id",technical_id);
				formData.append("msg_status", msg_status);
				formData.append("posted_date", posted_date);
				formData.append("msg_logs", msg_logs);
				formData.append("msg_reportedby", msg_reportedby);
				formData.append("reportedby_name", reportedby_name);
				formData.append("btn_save", btn_save);
				
				

				$.ajax({
					url: '<?php echo ADMIN_URL;?>technicalproblems/add_messages/',
					type: 'POST',
					data: formData,
					contentType: false,
					processData: false,
					beforeSend: function() {
						showSpinner(); // Call this to show the spinner
					},
					success: function (response) {
						window.location.href='<?php echo ADMIN_URL;?>technicalproblems/edit/'+technical_id;
						//const result = JSON.parse(response);
						//console.log(result);
						//console.log(response);
						if(response!='success'){
							/*$.smallBox({
								title : "Saving Data",
								content : "Saving Data failed!",
								color : "#296191",
								timeout: 5000,
								icon : "fa fa-bell swing animated"
							});*/
							
							/*$.smallBox({
								title : "Saving Data",
								content : "Saving Data Successfully!",
								color : "#296191",
								timeout: 5000,
								icon : "fa fa-bell swing animated"
							}, function(){
								
							});*/

						}else{
							//console.log(response);
							//window.location.href='<?php echo ADMIN_URL;?>technicalproblems/edit/'+technical_id;
							
						}
					},
					error: function () {
						//alert("An error occurred while processing data.");
						$.smallBox({
								title : "Saving Data",
								content : "An error occurred while processing data.",
								color : "#296191",
								timeout: 5000,
								icon : "fa fa-bell swing animated"
							});
					}
				});
			});
		
		})

		</script>

		