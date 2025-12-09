				<div class="main-content">
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home home-icon"></i>
								<a href="<?php echo DASHBOARD_URL;?>">Home</a>
							</li>
							<li>
								<a href="<?php echo ADMIN_URL;?>main_category">Main Category</a>
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
										<h3 class="header smaller lighter blue"><i><img src="<?php echo site_url();?>assets/images/categories.png" width="23" height="24" /></i>Main Category</h3>
										<form method="post" action="<?php echo ADMIN_URL;?>main_category/multi_delete">
<div class="row">
<div class="col-xs-8">
</div>
<div class="col-xs-4">
<div class="row">
<div class="col-xs-4"></div>
<div class="col-xs-4"></div>
<div class="col-xs-4">
<a href="<?php echo ADMIN_URL;?>main_category/add/"><button class="btn btn-sm btn-primary" type="button">ADD</button></a>
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
									<div class="table-header">&nbsp;</div>
										<div class="table-responsive">
										<table id="sample-table-2" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th width="">SNo</th>
														<th width="">Main Category Name</th>


														<th width="">Status</th>
														<th width="">Action</th>
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
													<td><?php echo stripslashes($row['name']); ?></td>


													<td><span <?php if($row['status']== 1){ echo " class='label label-success arrowed-in arrowed-in-right'"; } elseif($row['status']== 0){ echo "class='label label-danger arrowed'"; } ?>><a href="JavaScript:if(confirm('Are you sure want to Chanage the Status?')==true){window.location='<?php echo ADMIN_URL;?>main_category/status/<?php echo $row['id']?>/<?php echo $row['status'];?>';}" style="color:#FFF; text-decoration:none;"><?php if($row['status']== 1){ echo "Active"; } elseif($row['status']== 0){ echo "De-Active"; } ?></a></span></td>
													<td>
														<div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
																<a class="blue" href="<?php echo ADMIN_URL;?>main_category/view/<?php echo $row['id']; ?>" title="View">
																	<i class="icon-zoom-in bigger-130"></i>
																</a>
																<a class="green" href="<?php echo ADMIN_URL;?>main_category/edit/<?php echo $row['id']; ?>" title="Edit">
																	<i class="icon-pencil bigger-130"></i>
																</a>
																<a class="red" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>main_category/delete/<?php echo $row['id'];?>';}" title="Delete">
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
																			<a href="<?php echo ADMIN_URL;?>main_category/view/<?php echo $row['id']; ?>" class="tooltip-info" data-rel="tooltip" title="View">
																				<span class="blue">
																					<i class="icon-zoom-in bigger-120"></i>
																				</span>
																			</a>
																		</li>
																		<li>
																			<a href="<?php echo ADMIN_URL;?>main_category/edit/<?php echo $row['id']; ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="icon-edit bigger-120"></i>
																				</span>
																			</a>
																		</li>
																		<li>
																			<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>main_category/delete/<?php echo $row['id'];?>';}" class="tooltip-error" data-rel="tooltip" title="Delete">
																				<span class="red">
																					<i class="icon-trash bigger-120"></i>
																				</span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
													</td>
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
		</div><!-- /.main-container -->
		<!-- basic scripts -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo site_url();?>assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>
		<!-- <![endif]-->
		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?php echo site_url();?>assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='<?php echo site_url();?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo site_url();?>assets/js/bootstrap.min.js"></script>
		<script src="<?php echo site_url();?>assets/js/typeahead-bs2.min.js"></script>
		<!-- page specific plugin scripts -->
		<script src="<?php echo site_url();?>assets/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo site_url();?>assets/js/jquery.dataTables.bootstrap.js"></script>
		<!-- ace scripts -->
		<script src="<?php echo site_url();?>assets/js/ace-elements.min.js"></script>
		<script src="<?php echo site_url();?>assets/js/ace.min.js"></script>
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
				var oTable1 = $('#sample-table-2').dataTable( {
				"aoColumns": [
			      { "bSortable": true },
			      	null,null,
				  { "bSortable": false }
				] } );
				
				$('table th input:checkbox').on('click' , function(){
					var that = this;
					$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function(){
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});
						
				});
			
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
			})
		</script>
</body>
</html>
