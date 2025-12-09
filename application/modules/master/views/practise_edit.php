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
								<a href="<?php echo ADMIN_URL;?>practise">practise Scrolling</a>
							</li>
							<li>
								<?php echo stripslashes($record['title']); ?>
							</li>
							<li class="active">Edit</li>
						</ul><!-- .breadcrumb -->
					</div>
					<div class="page-content">
							<h3 class="header smaller lighter blue">
								<img src="<?php echo site_url();?>/assets/images/scroll-icon1.png" width="23" height="24" /></i> practise Scrolling
							</h3>
						<div class="row">
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
								<form class="form-horizontal" role="form" name="myform" id="myform" method="post" action="<?php echo ADMIN_URL;?>practise/edit/<?php echo $record['id']; ?>" enctype="multipart/form-data">
                                
								    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Title : </label>
										<div class="col-sm-9">
											<input type="text" id="title" name="title" class="col-xs-10 col-sm-5" value="<?php echo stripslashes($record['title']); ?>" required/>
                                            <?php echo form_error('title'); ?>
										</div>
									</div>
									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Description : </label>
										<div class="col-sm-9">
											<div class="wysiwyg-editor" id="editor"><?php echo stripslashes(str_replace('\n','',$record['description'])); ?></div>
                                            <input type="hidden" name="description" value="" />
                                            <?php echo form_error('description'); ?>
										</div>
									</div>
									<div class="space-4"></div>
								    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Position : </label>
										<div class="col-sm-9">
                                            <select name="position" id="position" class="col-xs-10 col-sm-5" required>
                                            <option value="" <?php if($record['position']==''){?> selected="selected" <?php }?>>--Select--</option>
                                           <?php for($i=1;$i<=10;$i++){ ?> 
                                            <option value="<?php echo $i; ?>" <?php if($record['position']==$i){?> selected="selected" <?php }?>><?php echo $i; ?></option>
                                            <?php } ?>
                                            </select>
                                            <?php echo form_error('position'); ?>
										</div>
									</div>
									<div class="space-4"></div>
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status : </label>
                                        <div class="col-sm-9">
                                            <label>
                                                <input name="status" id="status" type="radio" class="ace" value="1" <?php if($record['status']=='1'){?> checked="checked" <?php }?> />
                                                <span class="lbl"> Active</span>
                                                <input name="status" id="status" type="radio" class="ace" value="0" <?php if($record['status']=='0'){?> checked="checked" <?php }?>/>
                                                <span class="lbl"> De-Active</span>
                                            </label>
										</div>
									</div>
									 <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> practise Image: </label>
										<div class="col-sm-9">
                                         <div class="row">
							<div class="col-xs-12">
                            <div class="form-group">
										<div class="col-sm-5">
                                        <input type="file" id="id-input-file-2" name="image" class="col-xs-10 col-sm-12" />
                                            <div style="color:#000">Note : <span style="color:#F00">Width=183px, Height=100px .</span></div><div style="color:#000"></div>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
										</div>
									</div>
									
									<div class="space-4"></div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
                                        <input type="submit" class="btn btn-sm btn-primary" name="edit" id="edit" value="Save">
											&nbsp; &nbsp; &nbsp;
                                            <a href="<?php echo ADMIN_URL;?>practise" class="btn btn-sm btn-danger">Cancel</a>
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
		</div>
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo site_url();?>/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>
		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='<?php echo site_url();?>/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo site_url();?>/assets/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/jquery.dataTables.bootstrap.js"></script>
		<script src="<?php echo site_url();?>/assets/js/bootstrap.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/bootbox.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/ace-elements.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/ace.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/chosen.jquery.min.js"></script>
        <script src="<?php echo site_url();?>/assets/js/bootstrap-wysiwyg.min.js"></script>
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
				$('#editor').ace_wysiwyg({
					toolbar:
					[
						{
							name:'font',
							title:'Custom tooltip',
							values:['Some Special Font!','Arial','Verdana','Comic Sans MS','Custom Font!']
						},
						null,
						{
							name:'fontSize',
							title:'Custom tooltip',
							values:{1 : 'Size#1 Text' , 2 : 'Size#1 Text' , 3 : 'Size#3 Text' , 4 : 'Size#4 Text' , 5 : 'Size#5 Text'} 
						},
						null,
						{name:'bold', title:'Custom tooltip'},
						{name:'italic', title:'Custom tooltip'},
						{name:'strikethrough', title:'Custom tooltip'},
						{name:'underline', title:'Custom tooltip'},
						null,
						'insertunorderedlist',
						'insertorderedlist',
						'outdent',
						'indent',
						null,
						{name:'justifyleft'},
						{name:'justifycenter'},
						{name:'justifyright'},
						{name:'justifyfull'},
						null,
						{
							name:'createLink',
							placeholder:'Custom PlaceHolder Text',
							button_class:'btn-purple',
							button_text:'Custom TEXT'
						},
						{name:'unlink'},
						null,
						{
							name:'insertImage',
							placeholder:'Custom PlaceHolder Text',
							button_class:'btn-inverse',
							//choose_file:false,//hide choose file button
							button_text:'Set choose_file:false to hide this',
							button_insert_class:'btn-pink',
							button_insert:'Insert Image'
						},
						null,
						{
							name:'foreColor',
							title:'Custom Colors',
							values:['red','green','blue','navy','orange'],
							/**
								You change colors as well
							*/
						},
						/**null,
						{
							name:'backColor'
						},*/
						null,
						{name:'undo'},
						{name:'redo'},
						null,
						'viewSource'
					],
					//speech_button:false,//hide speech button on chrome
					
					'wysiwyg': {
						hotKeys : {} //disable hotkeys
					}
					
				}).prev().addClass('wysiwyg-style2');
				
				//handle form onsubmit event to send the wysiwyg's content to server
				$('#myform').on('submit', function(){
					
					//put the editor's html content inside the hidden input to be sent to server
					$('input[name=description]' , this).val($('#editor').html());
					
					//but for now we will show it inside a modal box
					//$('#modal-wysiwyg-editor').modal('show');
					//$('#wysiwyg-editor-value').css({'width':'99%', 'height':'200px'}).val($('#editor').html());
					
					return true;
				});
				$('#id-input-file-1 , #id-input-file-2').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false //| true | large
					//whitelist:'gif|png|jpg|jpeg'
					//blacklist:'exe|php'
					//onchange:''
					//
				});
				
				
			})
		</script>
	</body>
</html>
