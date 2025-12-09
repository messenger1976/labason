				<div class="main-content">

					<div class="breadcrumbs" id="breadcrumbs">

						<script type="text/javascript">

							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}

						</script>

						<ul class="breadcrumb">

							<li>

								<i class="icon-home home-icon"></i>

								<a href="#">Home</a>

							</li>

							<li>

								<a href="<?php echo ADMIN_URL;?>content">CMS Management</a>

							</li>
							<li>

								<a href="<?php echo ADMIN_URL;?>content/view/<?php echo stripslashes(str_replace('\n','',$record['id'])); ?>"><?php echo stripslashes(str_replace('\n','',$record['page_name'])); ?></a>

							</li>							

							<li class="active">View</li>

						</ul><!-- .breadcrumb -->

						<div class="nav-search" id="nav-search"></div><!-- #nav-search -->

					</div>

					<div class="page-content">

                        <h3 class="header smaller lighter blue">

                            <i><img src="<?php echo site_url();?>/assets/images/cms-icon.png" width="23" height="24" /></i> CMS Management - <?php echo stripslashes(str_replace('\n','',$record['page_name'])); ?>

                        </h3>

						<div class="row">

                        	 <div class="col-xs-3"><div class="loader"></div></div>

							<div class="col-xs-12">

								<!-- PAGE CONTENT BEGINS -->

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

								<form class="form-horizontal" role="form">

									<div class="form-group">

										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Page Name : </label>

										<div class="col-sm-9">

											<?php echo stripslashes(str_replace('\n','',$record['page_name'])); ?>

										</div>

									</div>

									<div class="space-4"></div>

                                    <div class="form-group">

										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Description : </label>

                                        <div class="col-sm-9">

                                           <?php echo stripslashes(str_replace('\n','',$record['content'])) ?>

										</div>

									</div>

								
                                 <div class="space-4"></div>
<div class="row">
                        <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12">
                        
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
<?php 	if( ( array_key_exists('cms',$roleResponsible) && is_array($roleResponsible['cms']) && (in_array('e',$roleResponsible['cms'])) ) || ( $this->session->userdata('usertype') == 'admin' ) ){  ?>
                                        <a href="<?php echo ADMIN_URL;?>content/edit/<?php echo $record['id']; ?>" class="btn btn-sm btn-primary">

                                            Edit

                                        </a>
                                        &nbsp; &nbsp; &nbsp;
                                        <a href="<?php echo ADMIN_URL;?>content/" class="btn btn-sm btn-danger">
                                            Cancel
                                        </a>										
<?php } ?>										</div>

									</div>
                                    
                                        
                        </div>
                        
                        
                        </div>								

								</form>

							</div><!-- /.col -->

						</div><!-- /.row -->

					</div><!-- /.page-content -->

				</div><!-- /.main-content -->

				<div class="ace-settings-container" id="ace-settings-container">

					<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">

						<i class="icon-cog bigger-150"></i>

					</div>

					</div><!-- /#ace-settings-container -->

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

			if("ontouchend" in document) document.write("<script src='<?php echo site_url();?>/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");

		</script>

		<script src="<?php echo site_url();?>/assets/js/bootstrap.min.js"></script>

		<script src="<?php echo site_url();?>/assets/js/typeahead-bs2.min.js"></script>

        

        <!-- page specific plugin scripts -->

		<script src="<?php echo site_url();?>/assets/js/jquery-ui-1.10.3.custom.min.js"></script>

		<script src="<?php echo site_url();?>/assets/js/jquery.ui.touch-punch.min.js"></script>

		<script src="<?php echo site_url();?>/assets/js/markdown/markdown.min.js"></script>

		<script src="<?php echo site_url();?>/assets/js/markdown/bootstrap-markdown.min.js"></script>

		<script src="<?php echo site_url();?>/assets/js/jquery.hotkeys.min.js"></script>

		<script src="<?php echo site_url();?>/assets/js/bootstrap-wysiwyg.min.js"></script>

		<script src="<?php echo site_url();?>/assets/js/bootbox.min.js"></script>

		<!-- page specific plugin scripts -->

		<script src="<?php echo site_url();?>/assets/js/jquery.dataTables.min.js"></script>

		<script src="<?php echo site_url();?>/assets/js/jquery.dataTables.bootstrap.js"></script>

        <script src="<?php echo site_url();?>/assets/js/bootstrap-wysiwyg.min.js"></script>

		<!-- ace scripts -->

		<script src="<?php echo site_url();?>/assets/js/ace-elements.min.js"></script>

		<script src="<?php echo site_url();?>/assets/js/ace.min.js"></script>

		<!-- inline scripts related to this page -->

		<script type="text/javascript">

			jQuery(function($) {

				var oTable1 = $('#sample-table-2').dataTable( {

				"aoColumns": [

			      { "bSortable": false },

			      null, null,null, null, null,

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

        

        <script type="text/javascript">

			jQuery(function($){

	

	function showErrorAlert (reason, detail) {

		var msg='';

		if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }

		else {

			console.log("error uploading file", reason, detail);

		}

		$('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 

		 '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');

	}

	//$('#editor1').ace_wysiwyg();//this will create the default editor will all buttons

	//but we want to change a few buttons colors for the third style

	$('#editor1').ace_wysiwyg({

		toolbar:

		[

			'font',

			null,

			'fontSize',

			null,

			{name:'bold', className:'btn-info'},

			{name:'italic', className:'btn-info'},

			{name:'strikethrough', className:'btn-info'},

			{name:'underline', className:'btn-info'},

			null,

			{name:'insertunorderedlist', className:'btn-success'},

			{name:'insertorderedlist', className:'btn-success'},

			{name:'outdent', className:'btn-purple'},

			{name:'indent', className:'btn-purple'},

			null,

			{name:'justifyleft', className:'btn-primary'},

			{name:'justifycenter', className:'btn-primary'},

			{name:'justifyright', className:'btn-primary'},

			{name:'justifyfull', className:'btn-inverse'},

			null,

			{name:'createLink', className:'btn-pink'},

			{name:'unlink', className:'btn-pink'},

			null,

			{name:'insertImage', className:'btn-success'},

			null,

			'foreColor',

			null,

			{name:'undo', className:'btn-grey'},

			{name:'redo', className:'btn-grey'}

		],

		'wysiwyg': {

			fileUploadError: showErrorAlert

		}

	}).prev().addClass('wysiwyg-style2');

	

	$('#editor2').css({'height':'200px'}).ace_wysiwyg({

		toolbar_place: function(toolbar) {

			return $(this).closest('.widget-box').find('.widget-header').prepend(toolbar).children(0).addClass('inline');

		},

		toolbar:

		[

			'bold',

			{name:'italic' , title:'Change Title!', icon: 'icon-leaf'},

			'strikethrough',

			null,

			'insertunorderedlist',

			'insertorderedlist',

			null,

			'justifyleft',

			'justifycenter',

			'justifyright'

		],

		speech_button:false

	});

	$('[data-toggle="buttons"] .btn').on('click', function(e){

		var target = $(this).find('input[type=radio]');

		var which = parseInt(target.val());

		var toolbar = $('#editor1').prev().get(0);

		if(which == 1 || which == 2 || which == 3) {

			toolbar.className = toolbar.className.replace(/wysiwyg\-style(1|2)/g , '');

			if(which == 1) $(toolbar).addClass('wysiwyg-style1');

			else if(which == 2) $(toolbar).addClass('wysiwyg-style2');

		}

	});

	

	//Add Image Resize Functionality to Chrome and Safari

	//webkit browsers don't have image resize functionality when content is editable

	//so let's add something using jQuery UI resizable

	//another option would be opening a dialog for user to enter dimensions.

	if ( typeof jQuery.ui !== 'undefined' && /applewebkit/.test(navigator.userAgent.toLowerCase()) ) {

		

		var lastResizableImg = null;

		function destroyResizable() {

			if(lastResizableImg == null) return;

			lastResizableImg.resizable( "destroy" );

			lastResizableImg.removeData('resizable');

			lastResizableImg = null;

		}

		var enableImageResize = function() {

			$('.wysiwyg-editor')

			.on('mousedown', function(e) {

				var target = $(e.target);

				if( e.target instanceof HTMLImageElement ) {

					if( !target.data('resizable') ) {

						target.resizable({

							aspectRatio: e.target.width / e.target.height,

						});

						target.data('resizable', true);

						

						if( lastResizableImg != null ) {//disable previous resizable image

							lastResizableImg.resizable( "destroy" );

							lastResizableImg.removeData('resizable');

						}

						lastResizableImg = target;

					}

				}

			})

			.on('click', function(e) {

				if( lastResizableImg != null && !(e.target instanceof HTMLImageElement) ) {

					destroyResizable();

				}

			})

			.on('keydown', function() {

				destroyResizable();

			});

	    }

		

		enableImageResize();

		/**

		//or we can load the jQuery UI dynamically only if needed

		if (typeof jQuery.ui !== 'undefined') enableImageResize();

		else {//load jQuery UI if not loaded

			$.getScript($path_assets+"/js/jquery-ui-1.10.3.custom.min.js", function(data, textStatus, jqxhr) {

				if('ontouchend' in document) {//also load touch-punch for touch devices

					$.getScript($path_assets+"/js/jquery.ui.touch-punch.min.js", function(data, textStatus, jqxhr) {

						enableImageResize();

					});

				} else	enableImageResize();

			});

		}

		*/

	}

});

		</script>

        <!-- Loading script related to this page  -->

<script type="text/javascript">

$(window).load(function() {

			$(".loader").fadeOut("slow");

		})		

</script>

        

	</body>

</html>

