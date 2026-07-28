<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item active">Page</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-th-list"></i>
			Manage <span class="fw-300">Main Category View</span>
		</h1>
	</div>

	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr"><h2>Main Category View</h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show"><div class="panel-content">
<div class="row">
							<div class="col-12">
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
							<!-- /.col -->
						<!-- /.row -->
					<!-- /.page-content -->
				<!-- /.main-content -->
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

				</div></div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
</body>
</html>
<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>
<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo site_url();?>assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>
<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='<?php echo site_url();?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>

