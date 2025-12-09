				<div class="main-content">
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home home-icon"></i>
								<a href="<?php echo ADMIN_URL;?>dashboard">Home</a>
							</li>
                           
							<li>
								<a href="<?php echo ADMIN_URL;?>practise">practise</a>
							</li>
							<li class="active">List View</li>
						</ul><!-- .breadcrumb -->
						<div class="nav-search" id="nav-search">
						</div><!-- #nav-search -->
					</div>
					<div class="page-content">
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-xs-12">
										<h3 class="header smaller lighter blue"><i><img src="<?php echo site_url();?>/assets/images/scroll-icon1.png" width="23" height="24" /></i> practise Scrolling</h3>
										<form method="post" action="<?php echo ADMIN_URL;?>practise/multi_delete">
                                        
                                        <div class="row">
                                        <div class="col-xs-8">
                                        	<input type="submit" class="btn btn-sm btn-primary" name="add" id="add" value="Delete All" onClick="return deleteAllData();" />
                                         </div>
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
                                        <div class="col-xs-4">
                                          <div class="row">
                                          <div class="col-xs-4">
                                    <!--<a href="<?php echo ADMIN_URL; ?>news/fileDownload" class="add-link"><button class="btn btn-sm btn-primary" type="button">Export</button></a>--></div>
                                    <div class="col-xs-4">
                                    <!--<a href="#modal-form" role="button" class="blue" data-toggle="modal"><button class="btn btn-sm btn-primary" type="button">Import</button></a>--></div>
                                    <div class="col-xs-4">
                                    <a href="<?php echo ADMIN_URL;?>practise/add/"><button class="btn btn-sm btn-primary" type="button">ADD</button></a>
                                    </div>
                                    </div>
                                    </div>                                        
                                                                             
                                     </div>
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
 
										<div class="table-header">
									&nbsp;
                                           
										</div>
										<div class="table-responsive">
                                            <table id="sample-table-2" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">
															<label>
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>
															</label>
														</th>
														<th>SNo</th>
														<th>Title</th>
														<th>Description</th>
														<th>Position</th>
														<th>Status</th>
														<th>Date & Time</th>
														<th>Action</th>
														<th>image</th>
													</tr>
												</thead>
												<tbody>
												<?php
                                                    if(count($record) > 0){
                                                        $i=1;
                                                        foreach($record as $key => $row){ 
                                                ?>                                            
													<tr>
														<td class="center">
															<label>
																<input type="checkbox" class="ace" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['id'];?>" />
																<span class="lbl"></span>
															</label>
														</td>
														<td><?php echo $i; ?></td>
														<td><?php echo stripslashes($row['title']); ?></td>
														<td><?php echo stripslashes(str_replace('\n','',$row['description'])); ?>	</td>
														<td><?php echo $row['position']; ?>	</td>
														<td><span <?php if($row['status']== 1){ echo " class='label label-success arrowed-in arrowed-in-right'"; } elseif($row['status']== 0){ echo "class='label label-danger arrowed'"; } ?>><a href="JavaScript:if(confirm('Are you sure want to Chanage the Status?')==true){window.location='<?php echo ADMIN_URL;?>practise/status/<?php echo $row['id']?>/<?php echo $row['status'];?>';}" style="color:#FFF; text-decoration:none;"><?php if($row['status']== 1){ echo "Active"; } elseif($row['status']== 0){ echo "De-Active"; } ?></a></span></td>
                                                        
                                                        <td><?php echo date('d-m-Y h:i A',strtotime($row['create_date_time']));?></td>
														<td><div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                        
																<a class="green" href="<?php echo ADMIN_URL;?>practise/edit/<?php echo $row['id']; ?>">
																	<i class="icon-pencil bigger-130"></i>
																</a>
																<a class="red" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>practise/delete/<?php echo $row['id'];?>';}">
																	<i class="icon-trash bigger-130"></i>
																</a>
															</div>
															<div class="visible-xs visible-sm hidden-md hidden-lg">
																<div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
																		<i class="icon-caret-down icon-only bigger-120"></i>
																	</button>
																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
																		<li>
																			<a href="<?php echo ADMIN_URL;?>practise/edit/<?php echo $row['id']; ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="icon-edit bigger-120"></i>
																				</span>
																			</a>
																		</li>
																		<li>
																			<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>practise/delete/<?php echo $row['id'];?>';}" class="tooltip-error" data-rel="tooltip" title="Delete">
																				<span class="red">
																					<i class="icon-trash bigger-120"></i>
																				</span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div></td>
															<td align="center" valign="middle">
                                                        <?php echo $row['image'] == '' ? '<img src=" '.site_url().'/images/no-image.jpg" width="50" height="50" style="border:#CCC solid 1px; padding:2px; -moz-border-radius:6px; border-radius:6px; -webkit-border-radius:6px;" />' : '<img src="'.ADMIN_IMG_URL.'practise/'.$row['image'].'" height="70" width="100" style="border:#CCC solid 1px; padding:2px; -moz-border-radius:6px; border-radius:6px; -webkit-border-radius:6px;" />' ?>   </td>
													</tr>
													
													<?php  $i++;} ?>
                                                    <?php } ?>
												</tbody>
                                                
											</table>
										</div>
                                        
                                      </form>    
									</div>
								</div>
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div><!-- /.main-content -->
			</div><!-- /.main-container-inner -->
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
		<script type="text/javascript">
			jQuery(function($) {
				var oTable1 = $('#sample-table-2').dataTable( {
				"aoColumns": [
			      { "bSortable": false },
			      null, null,null, null,null,null,null,
				  { "bSortable": false }
				] } );

			})
		</script>
	</body>
</html>
