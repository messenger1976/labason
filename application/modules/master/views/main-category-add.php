<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item active">Page</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-th-list"></i>
			Manage <span class="fw-300">Main Category Add</span>
		</h1>
	</div>

	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr"><h2>Main Category Add</h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show"><div class="panel-content">
<div class="row">
							<div class="col-12">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" >
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Main Category Name : </label>
										<div class="col-sm-9">
											<input type="text" id="category_name" name="category_name" class="col-10 col-sm-5" required value="<?php echo stripslashes($this->input->post('category_name'));?>"/>
										</div>
									</div>
									<div class="space-4"></div>

                                    <div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status : </label>
									<div class="col-sm-9">
									<label>
									<input name="status" id="status" type="radio" class="checkbox" value="1" checked="checked" />
									<span class="lbl"> Active</span></label>
									<label>
									<input name="status" id="status" type="radio" value="0" class="checkbox" />
									<span class="lbl"> De-Active</span>
									</label>
									</div>
									</div>
									<div class="space-4"></div>
									
                                    <h3 class="header blue lighter smaller">
								<i><img src="<?php echo site_url();?>/assets/images/seo-icon.png" width="23" height="24" /></i> SEO Details</h3>
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
											<input type="text" name="seo_title" id="form-field-1" placeholder="" class="col-10 col-sm-5" value="<?php echo stripslashes(str_replace('\n','',$this->input->post('seo_title')));?>"/>
										</div>
									</div>
									<div class="space-4"></div>
                                    
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Meta description : </label>
										<div class="col-sm-9">
											<textarea id="form-field-11" name="seo_description" class="autosize-transition form-control"><?php echo stripslashes(str_replace('\n','',$this->input->post('seo_description')));?></textarea>
										</div>
									</div>
									<div class="space-4"></div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Meta keywords : </label>
										<div class="col-sm-9">
											<textarea id="form-field-11"  name="seo_keywords"  class="autosize-transition form-control"><?php echo stripslashes(str_replace('\n','',$this->input->post('seo_keywords')));?></textarea>
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
                                        <input type="submit" class="btn btn-sm btn-primary" name="submit" id="submit" value="Add">
											&nbsp; &nbsp; &nbsp;
                                            <a href="<?php echo ADMIN_URL;?>main_category" class="btn btn-sm btn-danger">Cancel</a>
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
			window.jQuery || document.write("<script src='<?php echo site_url();?>/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>
		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='<?php echo site_url();?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo site_url();?>/assets/js/bootstrap.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/typeahead-bs2.min.js"></script>
		<!-- ace scripts -->
		<script src="<?php echo site_url();?>/assets/js/ace-elements.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/ace.min.js"></script>
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
			window.jQuery || document.write("<script src='<?php echo site_url();?>/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>
<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='<?php echo site_url();?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
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

