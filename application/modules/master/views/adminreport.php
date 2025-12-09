<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<title> SmartAdmin </title>
		<meta name="description" content="">
		<meta name="author" content="">
			
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- FAVICONS -->
		<link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">

		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

	</head>
	
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
					<!--<li><a href="< ?php echo ADMIN_URL?>addledger/search/">Search Ledger</a></li>-->
					<li>Journal Report</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Home <span>> Journal Report  </span></h1>
					</div>
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
						<ul id="sparks" class="">
							<li class="sparks-info">
							<?php 
							     $income1 = $this->my_model->get_income_metercustomer();
							     extract($income1);
								 $income2 = $this->my_model->get_income_monthlycustomer();
								 extract($income2);
								 $intotal = $total1 + $total2; 
							?>
								<h5> My Income <span class="txt-color-blue">$<?php print_r($intotal);?></span></h5>
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
								<h5> My Expense <span class="txt-color-purple">$<?php print_r($extotal);?></span></h5>
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
				
						<!-- NEW WIDGET START -->
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
								
								<header style="height: 42px;">
									<span class="widget-icon"> <i class="fa fa-users"></i> </span>
									<p style="padding: 5px 0 0 45px;font-size: 16px;"><strong>Admin Report</strong>
									</p>
								</header>
				
								<!-- widget div-->
								<div>
				
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
				
									</div>
										<!-- widget content -->
										<div class="widget-body no-padding">
										<div class="form-group col-lg-6">
													<div class="col-lg-12 controls" style="display: inline-flex;"> 
														<div class="form-group" style="margin-bottom: 0 !important; margin-top: 5px; padding: 0; width: 40%;">
													<form action="<?php echo ADMIN_URL ;?>addledger/reportdisplaysearch" method="POST" style="display: inline-flex;" >	
														<span class="input-group-addon" style=" min-width: 85px;"><strong>From-Date:</strong></span>
															<input class="form-control"  type="text" id="fromdate" name="fromdate"  placeholder="dd-mm-yyyy" value="" required>
														</div>
														<div class="form-group" style="margin-bottom: 0 !important; margin-left: 5px;margin-top: 5px; padding: 0; width: 40%;">			
															<span class="input-group-addon" style=" min-width: 40px;"><strong>To-Date:</strong></span>
															<input class="form-control"  type="text" id="todate" name="todate"  placeholder="dd-mm-yyyy" value="" required>
														</div>
														<input type="submit" class="btn btn-primary" name="search" id="search" value="search" style="margin-top: 5px; margin-top: 5px; padding: 5px; height: 31px; font-size: 14px;">
												</form>
													</div>	
												</div>
																			<!--div id="reportsearch"></div -->
										    <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr style=" background: #00a1ff; color: #fff;">
														<th><input type="checkbox" class="ace" /></th>
														<th>Name</th>
														<th data-class="expand">Dated</th>
<th data-hide="expand">Remarks</th>
														<th data-hide="expand">Debit </th>
														<th data-hide="expand">Credit</th>
														
													</tr>
												</thead>
												<tbody>
												  <?php
														if(!empty($record)){
														$i = 1;
															foreach($record as $row){ 
													?>   
													<tr>
														<td class="center">
														<?php echo $i; ?>
														</td>
														<td><?php 
												        		  if($row['tableName'] == "addmetercustomer"){ echo "Meter Customer" ;}
																  if($row['tableName'] == "monthlycustomer" ){ echo "Monthly Customer"; }?>
														<?php if ($row['tableName'] == "addexpenses") { echo "Expenses"; }?></td>
														<td><?php echo date('Y-m-d', strtotime($row['create_date_time'])) ?></td>
														<td></td>
												        <td><?php 
												        		  if($row['tableName'] == "addmetercustomer" || $row['tableName'] == "monthlycustomer" )
																  {	
																	echo "$".$row['Debit'];
																  }
																  elseif($row['tableName'] == "addexpenses" && !empty($row['Debit']))
																  {
																	echo "$".$row['Debit'];
																  }
															?>
														</td>
														<td><?php if ($row['tableName'] == "addexpenses")
																  {	
																	echo "$".$row['Credit'];
																  }
															?>
														</td>
														
													</tr>
													
														<?php $i++;} }?>	
												</tbody>
											</table>

										</div>
										<!-- end widget content -->
								</div>
								<!-- end widget div -->
							</div>
							<!-- end widget -->
				
						</article>
						<!-- WIDGET END -->
				
					</div>
				
					<!-- end row -->

					

				</section>
				<!-- end widget grid -->

			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->
		

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
	
    var empid = $('#id_'+paybtnid).val();
	var employee_id = $('#employeeid_'+paybtnid).val();
	if(empid != '' && employee_id != ''){
				var url = '<?php echo ADMIN_URL;?>addledger/ledger_register_form/'+empid+'/'+employee_id;
				window.open( url , "popupWindow", "width=1024,height=600,scrollbars=yes");	
	}
	
});
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
	
		function getreportsearch(){
			
			var fromdate = $("#fromdate").val();
			var todate = $("#todate").val();
			
			$.ajax({
				
				type : "POST",
				url	: '<?php echo ADMIN_URL;?>addledger/reportdisplaysearch',
				//data	: "customer_type="+customer_type+"&zone="+zone+"&fromdate="+fromdate+"&todate="+todate+",
				data	: "fromdate="+fromdate+"&todate="+todate,
				complete: function(data){
					var op = data.responseText.trim();
					//$("#dt_basic").hide();
					$( "#dt_basic" ).empty();
					$("#dt_basic").html(op);
				}
			});
		}
<?php /*
  <script id="source" language="javascript" type="text/javascript">

  $(function () 
  {
    //-----------------------------------------------------------------------
    // 2) Send a http request with AJAX http://api.jquery.com/jQuery.ajax/
    //-----------------------------------------------------------------------
    $.ajax({                                      
      url: 'api.php',                  //the script to call to get data          
      data: "",                        //you can insert url argumnets here to pass to api.php
                                       //for example "id=5&parent=6"
      dataType: 'json',                //data format      
      success: function(data)          //on recieve of reply
      {
        var id = data[0];              //get id
        var vname = data[1];           //get name
        //--------------------------------------------------------------------
        // 3) Update html content
        //--------------------------------------------------------------------
        $('#output').html("<b>id: </b>"+id+"<b> name: </b>"+vname); //Set output element html
        //recommend reading up on jquery selectors they are awesome 
        // http://api.jquery.com/category/selectors/
      } 
    });
  }); 

  </script>	 */?>	
	/*function getaddexpenses_balance(){
			
			
			var fromdate = $("#fromdate").val();
			var todate = $("#todate").val();
			
			$.ajax({
				
				type : "POST",
				url	: '<?php echo ADMIN_URL;?>addledger/reportdisplaysearch',
				//data	: "customer_type="+customer_type+"&zone="+zone+"&fromdate="+fromdate+"&todate="+todate+",
				data	: "fromdate="+fromdate+"&todate="+todate,
				complete: function(data){
					var op = data.responseText.trim();
					//alert(op);
					$("#expensesDiv").html(op);
				}
			});
		}
	*/
		</script>	
<style>
.dt-toolbar {width: auto;}
div.dataTables_filter label{ display: inline-flex;}
</style>		