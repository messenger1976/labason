               <?php if(!empty($record)){ ?>
			   <!--<div class="row">
					<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
						 <div class="col-lg-12" style="text-align: right">
							<a href="<?php echo ADMIN_URL;?>addexpenses/fileDownloadajaxbsearch/<?php echo $this->input->post('fromdate');?>/<?php echo $this->input->post('todate');?>"class="btn btn-sm btn-primary" style="margin-bottom: 4px;">Export Excel</a>
						 </div>
					</div>
			   </div>     -->
					 
					 <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                        <table  id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<tr>
									<th>SNo</th>
									<th>Date</th>
									<th>Debit-Description</th>
									<th>Debit</th>
								</tr>
							</thead>
							<tbody>
							<?php $i=1;
								foreach($record as $key => $row){ 
							?>                                            
								<tr>
									<td><?php echo $i; ?></td>
									 
									  <td><?php echo date('d-M-Y',strtotime($row['date']));?></td>														
									  <td><?php echo stripslashes($row['expenses_type']); ?></td>
									<td><?php echo $row['expenses_debit'];?></td>
								</tr>
								<?php  $i++; } ?>
							</tbody>
							
						</table>
					</div>
				<?php } ?>
				<?php if(!empty($payrols)){ ?>
			   <!--<div class="row">
					<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
						 <div class="col-lg-12" style="text-align: right">
							<a href="<?php echo ADMIN_URL;?>addexpenses/fileDownloadajaxbpayrols/<?php echo $this->input->post('fromdate');?>/<?php echo $this->input->post('todate');?>"class="btn btn-sm btn-primary" style="margin-bottom: 4px;">Export Excel</a>
						 </div>
					</div>
			   </div>   -->  
					 
					 <div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="false">
                       <table  id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<tr>
									<th>SNo</th>
									<th>Date</th>
									<th>Debit-Description</th>
									<th>Debit</th>
								</tr>
							</thead>
							<tbody>
							<?php $i=1; 
								foreach($payrols as $key => $value){ 
							?>                                            
								<tr>
									<td><?php echo $i; ?></td>
									 
									  <td><?php echo date('d-M-Y',strtotime($value['date']));?></td>														
									  <td><?php echo stripslashes($value['expenses_type']); ?></td>
									<td><?php echo $value['expenses_debit'];?></td>
								</tr>
								<?php  $i++; } ?>
							</tbody>
							
						</table>
					</div>
				<?php } ?>
				<?php if(!empty($meter)){ ?>
			   <div class="row">
					<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
						 <div class="col-lg-12" style="text-align: right">
							<a href="<?php echo ADMIN_URL;?>addexpenses/fileDownloadajaxbmeter/<?php echo $this->input->post('fromdate');?>/<?php echo $this->input->post('todate');?>"class="btn btn-sm btn-primary" style="margin-bottom: 4px;">Export Excel</a>
						 </div>
					</div>
			   </div>     
					 
					 <div class="jarviswidget jarviswidget-color-darken" id="wid-id-2" data-widget-editbutton="false">
                       <table  id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<tr>
									<th>SNo</th>
									<th>Date</th>
									<th>Credit-Description</th>
									<th>Credit</th>
								</tr>
							</thead>
							<tbody>
							<?php $i=1;
								foreach($meter as $key => $val){ 
							?>                                            
								<tr>
									<td><?php echo $i; ?></td>
									 
									  <td><?php echo date('d-M-Y',strtotime($val['date']));?></td>														
									<td>Customer Meter Bill Payments</td>
									<td><?php echo $val['expenses_cradit'];?></td>
								</tr>
								<?php  $i++; } ?>
							</tbody>
							
						</table>
					</div>
				<?php } ?>
				<?php if(!empty($monthly)){ ?>
			   <!--<div class="row">
					<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
						 <div class="col-lg-12" style="text-align: right">
							<a href="<?php echo ADMIN_URL;?>addexpenses/fileDownloadajaxbmonthly/<?php echo $this->input->post('fromdate');?>/<?php echo $this->input->post('todate');?>"class="btn btn-sm btn-primary" style="margin-bottom: 4px;">Export Excel</a>
						 </div>
					</div>
			   </div>    --> 
					 
					 <div class="jarviswidget jarviswidget-color-darken" id="wid-id-3" data-widget-editbutton="false">
                       <table  id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<tr>
									<th>SNo</th>
									<th>Date</th>
									<th>Credit-Description</th>
									<th>Credit</th>
								</tr>
							</thead>
							<tbody>
							<?php $i=1;
								foreach($monthly as $key => $vl){ 
							?>                                            
								<tr>
									<td><?php echo $i; ?></td>
									 
									  <td><?php echo date('d-M-Y',strtotime($vl['date']));?></td>														
									<td>Customer Monthly Bill Payments</td>
									<td><?php echo $vl['expenses_cradit'];?></td>
								</tr>
								<?php  $i++; } ?>
							</tbody>
							
						</table>
					</div>
				<?php } ?>
				
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
		
		})

		</script>

		<!-- Your GOOGLE ANALYTICS CODE Below -->
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
			_gaq.push(['_trackPageview']);
			
			(function() {
			var ga = document.createElement('script');
			ga.type = 'text/javascript';
			ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ga, s);
			})();
		</script>																																			
										
												