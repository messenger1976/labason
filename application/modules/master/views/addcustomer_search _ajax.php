     <div class="row">
				<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
												<?php
                                                    if(count($record) > 0){
                                                        foreach($record as $key => $row){ 
														$id=$row['id'];
														}
													}
                                                ?>
												
                  
				</div>
				</div>     
	 
	                                        <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
                                              <table  id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
												<thead>
													<tr>
														<th data-hide="phone">SNo</th>
													    <th data-hide="phone">Customer-id</th>
														<th data-hide="phone">Name</th>																												
														<th data-hide="phone">Gender</th>
														<th data-hide="phone">Mobile1</th>
														<th data-hide="phone">Mobile2</th>
														<th data-hide="phone">Email_id </th>
														<th data-hide="phone">Address </th>
														<th data-hide="phone">Customer-Type </th>
														
														<th data-hide="phone">Status</th>
														<th data-hide="phone">Action</th>
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
														<td><?php echo stripslashes($row['customer_id']); ?></td>
														<td><?php echo stripslashes($row['first_name']); ?></td>																												
														<td><?php echo stripslashes($row['gender']); ?></td>
														<td><?php echo stripslashes($row['mobile1']); ?></td>
														<td><?php echo stripslashes($row['mobile2']); ?></td>
														<td><?php echo stripslashes($row['email_id']); ?></td>
														<td><?php echo stripslashes($row['address']); ?></td>
														<td><?php echo stripslashes($row['customer_type']); ?></td>
														
														<td><span <?php if($row['status']== 1){ echo " class='label label-success arrowed-in arrowed-in-right'"; } elseif($row['status']== 0){ echo "class='label label-danger arrowed'"; } ?>><a href="JavaScript:if(confirm('Are you sure want to Chanage the Status?')==true){window.location='<?php echo ADMIN_URL;?>addcustomer/status/<?php echo $row['id']?>/<?php echo $row['status'];?>/<?php echo $this->uri->segment(3);?>';}" style="color:#FFF; text-decoration:none;"><?php if($row['status']== 1){ echo "Active"; } elseif($row['status']== 0){ echo "De-Active"; } ?></a></span></td>    
														<td>
														   <input type="hidden" name="id_<?php echo $i;?>" id="id_<?php echo $i;?>" value = "<?php echo $row['id'];?>">
														   <input type="hidden" name="customerid_<?php echo $i;?>" id="customerid_<?php echo $i;?>" value = "<?php echo $row['customer_id'];?>">
														   <input type="hidden" name="billingplansid_<?php echo $i;?>" id="billingplansid_<?php echo $i;?>" value = "<?php echo $row['billingplans'];?>">
															<a href="#" title="Print">
														    <i class="print_button fa fa-print" id="print_button<?php echo $i;?>" data-print-val-id="<?php echo $i ?>"></i>
															</a>
																<a class="blue" href="<?php echo ADMIN_URL;?>addcustomer/view/<?php echo $row['id'];?>" title="view">
																	<i class="fa fa-info-circle"></i>
																</a>
																<a class="green" href="<?php echo ADMIN_URL;?>addcustomer/edit/<?php echo $row['id']; ?>" title="Edit" title="Edit">
																	<i class="fa fa-edit"></i>
																</a>
																<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>addcustomer/delete/<?php echo $row['id'];?>';}" class="tooltip-error" data-rel="tooltip" title="Delete">
																	<i class="fa fa-remove"></i>
																</a>
																																		
															</div>
															<div class="visible-xs visible-sm hidden-md hidden-lg">
																<div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
																		<i class="icon-caret-down icon-only bigger-120"></i>
																	</button>
																</div>
															</div></td>
													</tr>
													<?php  $i++;} ?>
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
<script>
$(document).on('click','.print_button',function(e){
	var buttonid = $(this).attr('id');
	var paybtnid = $(this).data('print-val-id');
	var cusid = $('#id_'+paybtnid).val();
	var customer = $('#customerid_'+paybtnid).val();
	var biliingplans = $('#billingplansid_'+paybtnid).val();
	if(paybtnid != '' && customer != '' && biliingplans == ''){
				var url = '<?php echo ADMIN_URL;?>addcustomer/customer_register_form/'+cusid+'/'+customer+'/'+biliingplans;
				window.open( url , "popupWindow", "width=1024,height=600,scrollbars=yes");	
	}
	else if(paybtnid != '' && customer != '' && biliingplans != ''){
		    var url = '<?php echo ADMIN_URL;?>addcustomer/customer_register_form_monthly/'+cusid+'/'+customer+'/'+biliingplans;
			window.open( url , "popupWindow", "width=1024,height=600,scrollbars=yes");	
	}
});
</script>					