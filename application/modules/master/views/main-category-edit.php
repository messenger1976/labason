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
							<li class="active">Edit</li>
						</ul><!-- .breadcrumb -->
					</div>
					<div class="page-content">
                    							<h3 class="header smaller lighter blue">
								<i><img src="<?php echo site_url();?>assets/images/categories.png" width="23" height="24" /></i>Main Category
                                <small>
									<i class="icon-double-angle-right"></i>
									Edit
								</small>
							</h3>
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
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Main Category Name : </label>
										<div class="col-sm-9">
											<input type="text" id="category_name" name="category_name" class="col-xs-10 col-sm-5" value="<?php echo stripslashes($record['name']); ?>" required/>
										</div>
									</div>

									<div class="space-4"></div>

                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status : </label>
                                        <div class="col-sm-9">
                                            <label>
                                                <input name="status" id="status" type="radio" class="ace" value="1" <?php if($record['status'] =='1'){ ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> Active</span></label><label>
                                                <input name="status" id="status" type="radio" class="ace" value="0" <?php if($record['status'] =='0'){ ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> De-Active</span>
                                            </label>
										</div>
									</div>
									<div class="space-4"></div>
                                    <h3 class="header blue lighter smaller">
								<i><img src="<?php echo site_url();?>assets/images/seo-icon.png" width="23" height="24" /></i> SEO Details</h3>
                            
                             <div id="accordion" class="accordion-style2">
                            <div style="display:none;">
												<h3 class="accordion-header"></h3>
												
											</div>
											<div class="group">
												<h3 class="accordion-header">SEO/Meta data</h3>
                            
                                    <div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Page tittle : </label>
										<div class="col-sm-9">
											<input type="text" name="seo_title" id="form-field-1" placeholder="" class="col-xs-10 col-sm-5" value="<?php echo (stripslashes(str_replace('\n','',$record['seo_title']))); ?>" />
										</div>
									</div>
									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Meta description : </label>
										<div class="col-sm-9">
											<textarea id="form-field-11" name="seo_description" class="autosize-transition form-control"><?php echo (stripslashes(str_replace('\n','',$record['seo_description']))); ?></textarea>
										</div>
									</div>
									<div class="space-4"></div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Meta keywords : </label>
										<div class="col-sm-9">
											<textarea id="form-field-11"  name="seo_keywords"  class="autosize-transition form-control"><?php echo (stripslashes(str_replace('\n','',$record['seo_keywords']))); ?></textarea>
										</div>
									</div>
									<div class="space-4"></div>
                                    </div>
                                    </div>
                                     <div class="group">
												<h3 class="accordion-header">SEO Standards ( On Page ) </h3>
                                                <div> 
                                    <?php echo $seo_stands; ?>
                                    </div>
                                    </div>
                                    
                                    </div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
                                        <input type="submit" class="btn btn-sm btn-primary" name="edit" id="edit" value="Save">
											&nbsp; &nbsp; &nbsp;
                                            <a href="<?php echo ADMIN_URL;?>main_category" class="btn btn-sm btn-danger">Cancel</a>
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
		<script src="<?php echo site_url();?>/assets/js/bootstrap.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/typeahead-bs2.min.js"></script>
		<!-- ace scripts -->
		<script src="<?php echo site_url();?>/assets/js/ace-elements.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/ace.min.js"></script>
		<!-- inline scripts related to this page -->
        <link rel="stylesheet" href="<?php echo site_url();?>/assets/css/jquery-ui-1.10.3.full.min.css" />       
		<script src="<?php echo site_url();?>/assets/js/jquery-ui-1.10.3.full.min.js"></script>
		<script type="text/javascript">
			jQuery(function($) {
				//jquery accordion
				$( "#accordion" ).accordion({
					collapsible: true ,
					heightStyle: "content",
					animate: 250,
					header: ".accordion-header"
				}).sortable({
					axis: "y",
					handle: ".accordion-header",
					stop: function( event, ui ) {
						// IE doesn't register the blur when sorting
						// so trigger focusout handlers to remove .ui-state-focus
						ui.item.children( ".accordion-header" ).triggerHandler( "focusout" );
					}
				});
			
			});
		</script>
	</body>
</html>
