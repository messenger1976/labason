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
							<li class="active">View</li>
						</ul><!-- .breadcrumb -->
						<div class="nav-search" id="nav-search">
						</div><!-- #nav-search -->
					</div>
					<div class="page-content">
                  
                        <h3 class="header smaller lighter blue">
                        <i><img src="<?php echo site_url();?>assets/images/categories.png" width="23" height="24" /></i>
                            Main Category
                            <small>
                                <i class="icon-double-angle-right"></i>
                                View
                            </small>
                        </h3>
						
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Main Category Name : </label>
										<div class="col-sm-9 mar-top"><?php echo stripslashes($record['name']); ?></div>
									</div>
									<div class="space-4"></div>

                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status : </label>
                                        <div class="col-sm-9 mar-top"><?php if($record['status']== 1){ echo "Active"; } elseif($record['status']== 0){ echo "De-Active"; } ?></div>
									</div>
									<div class="space-4"></div>
                                    <h3 class="header smaller lighter blue">
								Other Details
							</h3>
								<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Created Date : </label>
										<div class="col-sm-9">
                                            <?php echo date('d-M-Y',strtotime($record['create_date_time']));?>
										</div>
									</div>
									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Created Time : </label>
										<div class="col-sm-9">
                                            <?php echo date('h:i A',strtotime($record['create_date_time']));?>
										</div>
									</div>
									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Updated Date : </label>
										<div class="col-sm-9">
                                            <?php echo date('d-M-Y',strtotime($record['update_date_time']));?>
										</div>
									</div>
									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Updated Time : </label>
										<div class="col-sm-9">
                                            <?php echo date('h:i A',strtotime($record['update_date_time']));?>
										</div>
									</div>
									<div class="space-4"></div>
                                    <h3 class="header smaller lighter blue">
								<i><img src="<?php echo site_url();?>assets/images/seo-icon.png" width="23" height="24" /></i> SEO/Meta data
							</h3>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Page tittle : </label>
										<div class="col-sm-9">
											<?php echo (stripslashes(str_replace('\n','',$record['seo_title']))); ?>
										</div>
									</div>
									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Meta description : </label>
										<div class="col-sm-9">
											<?php echo (stripslashes(str_replace('\n','',$record['seo_description']))); ?>
										</div>
									</div>
									<div class="space-4"></div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Meta keywords : </label>
										<div class="col-sm-9">
											<?php echo (stripslashes(str_replace('\n','',$record['seo_keywords']))); ?>
										</div>
									</div>
									<div class="space-4"></div>
                                    
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<a href="<?php echo ADMIN_URL;?>main_category/edit/<?php echo $record['id']; ?>" class="btn btn-sm btn-primary">
												Edit
											</a>
											&nbsp; &nbsp; &nbsp;
											<a href="<?php echo ADMIN_URL;?>main_category/" class="btn btn-sm btn-danger">
												Cancel
											</a>
										</div>
									</div>
								</form>
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
		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo site_url();?>assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>
		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='<?php echo site_url();?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
	</body>
</html>
