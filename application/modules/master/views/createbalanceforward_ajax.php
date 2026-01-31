     <div class="row">
				<div class="pull-right">
												<?php
                                                    if(count($record) > 0){
                                                        foreach($record as $key => $row){ 
														$id=$row['id'];
														}
													}
                                                ?>  
                
				</div>
				</div>
				
				<!-- Statistics Widget Boxes -->
				<style>
					.stat-widget .huge {
						font-size: 40px;
						font-weight: bold;
					}
					.stat-widget .panel-body {
						padding: 15px;
					}
					.stat-widget .panel-footer {
						background-color: #f5f5f5;
						border-top: 1px solid #ddd;
						padding: 10px 15px;
					}
					.stat-widget i {
						color: rgba(255,255,255,0.8);
					}
					.stat-widget .panel-primary i {
						color: #337ab7;
					}
					.stat-widget .panel-success i {
						color: #5cb85c;
					}
					.stat-widget .panel-warning i {
						color: #f0ad4e;
					}
					.stat-widget .panel-danger i {
						color: #d9534f;
					}
				</style>
				<div class="row stat-widget" style="margin-bottom: 20px;">
					<div class="col-xs-12 col-sm-6 col-md-3">
						<div class="panel panel-primary">
							<div class="panel-body">
								<div class="row">
									<div class="col-xs-3">
										<i class="fa fa-users fa-3x"></i>
									</div>
									<div class="col-xs-9 text-right">
										<div class="huge"><?php echo isset($statistics['total']) ? number_format($statistics['total']) : 0; ?></div>
										<div>Total Members</div>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<span class="pull-left">All Status</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-6 col-md-3">
						<div class="panel panel-success">
							<div class="panel-body">
								<div class="row">
									<div class="col-xs-3">
										<i class="fa fa-check-circle fa-3x"></i>
									</div>
									<div class="col-xs-9 text-right">
										<div class="huge"><?php echo isset($statistics['active']) ? number_format($statistics['active']) : 0; ?></div>
										<div>Active Members</div>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<span class="pull-left">Status: Active</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-6 col-md-3">
						<div class="panel panel-warning">
							<div class="panel-body">
								<div class="row">
									<div class="col-xs-3">
										<i class="fa fa-pause-circle fa-3x"></i>
									</div>
									<div class="col-xs-9 text-right">
										<div class="huge"><?php echo isset($statistics['inactive']) ? number_format($statistics['inactive']) : 0; ?></div>
										<div>Inactive Members</div>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<span class="pull-left">Status: Inactive</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-6 col-md-3">
						<div class="panel panel-danger">
							<div class="panel-body">
								<div class="row">
									<div class="col-xs-3">
										<i class="fa fa-times-circle fa-3x"></i>
									</div>
									<div class="col-xs-9 text-right">
										<div class="huge"><?php echo isset($statistics['deactivated']) ? number_format($statistics['deactivated']) : 0; ?></div>
										<div>Deactivated Members</div>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<span class="pull-left">Status: Deactivated</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
	 
	                <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                                            <table  id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
												<thead>
													<tr>
														<th data-hide="sno">SNo</th>
													    <th data-hide="refno">Ref No</th>
														<th data-hide="customerid">Customer ID</th>
														<th data-hide="name">Customer Name</th>
														<th data-hide="address">Address</th>
														<th data-hide="zone">Zone</th>
														<th data-hide="previousreading">Previous Reading</th>
														<th data-hide="currentreading">Current Reading</th>
														<th data-hide="arrears">Arrears</th>
														<th data-hide="maintenancefee">Maintenance Fee</th>
														<th data-hide="billingperiod">Billing Period</th>
													</tr>
												</thead>
												<tbody>
												<?php
                                                    if(count($record) > 0){
                                                        $i=1;
                                                        foreach($record as $key => $row){ 
                                                ?>                                            
													<tr>
														<td><?php echo $i; ?></td>
														<td><?php echo stripslashes($row['refno']); ?></td>
														<td><?php echo stripslashes($row['customer_id']); ?></td>
														<td><?php echo stripslashes($row['first_name'].' '.$row['last_name']); ?></td>
														<td><?php echo stripslashes($row['address']); ?></td>
														<td><?php echo stripslashes($row['zone_name']); ?></td>
														<td><?php echo stripslashes($row['previous_reading']); ?></td>
														<td><?php echo stripslashes($row['current_reading']); ?></td>
														<td><?php echo stripslashes(number_format($row['arrears'], 2)); ?></td>
														<td><?php echo stripslashes(number_format($row['maintenance_fee'], 2)); ?></td>
														<td><?php echo stripslashes($row['month_name'].' '.$row['year']); ?></td>
													</tr>
													<?php  $i++;} ?>
                                                    <?php } else { ?>
													<tr>
														<td style="text-align:center;">No records found</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
													</tr>
													<?php } ?>
												</tbody>
                                                
											</table>
										</div>
										
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
			
		})

		</script>

