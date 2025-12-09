
 <script type="text/javascript">
function getPrint(){
	window.print();
}
</script>	

	
	<html>	

	<head>
		<meta charset="utf-8" />
		<title><?PHP if(isset($host['host_name'])){echo ucfirst($host['host_name'])."-Admin";}else{?> Admin <?PHP } ?></title>
		<meta name="description" content="Static &amp; Dynamic Tables" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!-- basic styles -->
		<link href="<?php echo site_url();?>/assets/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/font-awesome.min.css" />

		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/ace-fonts.css" />
		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/ace.min.css" />
		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/ace-rtl.min.css" />
		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/ace-skins.min.css" />
		<script src="<?php echo site_url();?>/assets/js/ace-extra.min.js"></script>
	</head>	
		<body onLoad="getPrint();">
 <div class="">
                                    
										<script type="text/javascript">
                                        function deleteAllData(){ 
                                            var checked_num = $('input[name="delete_ids[]"]:checked').length;
                                            if (checked_num == 0) {
                                                alert('Select Atleast One Check Box... ');
                                                return false;
                                            }else if (checked_num > 0){ 
                                                if(confirm('Confirm Delete?')==true){
                                                    //$('#careers').submit();
                                                    return true;
                                                }else{
													return false;
												}
                                            }
                                        }
                                        </script>
										<SCRIPT language="javascript">
										$(function(){
										 
											// add multiple select / deselect functionality
											$("#selectall").click(function () {
												  $('.ace').attr('checked', this.checked);
											});
										 
											// if all checkbox are selected, check the selectall checkbox
											// and viceversa
											$(".ace").click(function(){
										 
												if($(".ace").length == $(".ace:checked").length) {
													$("#selectall").attr("checked", "checked");
												} else {
													$("#selectall").removeAttr("checked");
												}
										 
											});
										});
										</SCRIPT>
                                        <div class="pull-right">
                                          <div class="">
										   
                                          <div class="col-xs-4">
                                    <!--<a href="<?php echo ADMIN_URL; ?>news/fileDownload" class="add-link"><button class="btn btn-sm btn-primary" type="button">Export</button></a>--></div>
                                    <div class="col-xs-4">
                                    <!--<a href="#modal-form" role="button" class="blue" data-toggle="modal"><button class="btn btn-sm btn-primary" type="button">Import</button></a>--></div>
                                    <div class="col-xs-5 pull-right">
									
                                     
									
                                    
                                    </div>
                                    </div>
                                    </div>                                        
                                            <div class="space-20"></div>                                 
                                
                                     <div>&nbsp;</div>
									<?php if($this->session->flashdata('msg_succ') != ''){?>
                                    <div class="alert alert-block alert-success">
                                        <button type="button" class="close" data-dismiss="alert">
                                        <i class="icon-remove"></i>
                                        </button>
                                        <p>
                                            <i class="icon-ok"></i>
                                            <?php echo $this->session->flashdata('msg_succ')?$this->session->flashdata('msg_succ'):'';?>
                                        </p>
                                    </div>
                                    <?php } ?>
                                         <?php
										 echo (stripslashes(str_replace('\n','',$address['content'])));
										 ?>
										<div class="table-header">
									&nbsp;
                                           
										</div>

											                                       <div class="table-responsive">
                                            <table id="sample-table-2" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>SNo</th>
													    <th>Customer-id</th>
														<th>Name</th>																												
														<th>Gender</th>
														<th>Mobile1</th>
														<th>Mobile2</th>
														<th>Email_id </th>
														<th>Address </th>
														<th>Customer-Type </th>
														
														<th>Status</th>
														
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
														
														<td><span <?php if($row['status']== 1){ echo " class='label label-success arrowed-in arrowed-in-right'"; } elseif($row['status']== 0){ echo "class='label label-danger arrowed'"; } ?>><a href="JavaScript:if(confirm('Are you sure want to Chanage the Status?')==true){window.location='<?php echo ADMIN_URL;?>addcustomer/status/<?php echo $row['id']?>/<?php echo $row['status'];?>/<?php echo $this->uri->segment(3);?>';}" style="color:#FFF; text-decoration:none;"><?php if($row['status']== 1){ echo "Paid"; } elseif($row['status']== 0){ echo "Un-Paid"; } ?></a></span></td>    
														<!--<td><div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
															
																<a class="green" href="<?php echo ADMIN_URL;?>addcustomer/edit/<?php echo $row['id']; ?>" title="Edit">
																	<i class="icon-pencil bigger-130"></i>
																</a>
																<a class="blue" href="<?php echo ADMIN_URL;?>addcustomer/view/<?php echo $row['id'];?>">
																	<i class="icon-zoom-in bigger-130"></i>
																</a>
                                                                      
																
																			<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>addcustomer/delete/<?php echo $row['id'];?>';}" class="tooltip-error" data-rel="tooltip" title="Delete">
																				<span class="red">
																					<i class="icon-trash bigger-120"></i>
																				</span>
																			</a>
																																		
															</div>-->
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
										
										
		<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="icon-double-angle-up icon-only bigger-110"></i>
			</a>
		</div>
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo site_url();?>/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>
		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='<?php echo site_url();?>/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo site_url();?>/assets/js/bootstrap.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/typeahead-bs2.min.js"></script>
		<!-- page specific plugin scripts -->
		<script src="<?php echo site_url();?>/assets/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/jquery.dataTables.bootstrap.js"></script>
		<!-- ace scripts -->
		<script src="<?php echo site_url();?>/assets/js/ace-elements.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/ace.min.js"></script>
		<!-- inline scripts related to this page -->

	</body>
</html>
