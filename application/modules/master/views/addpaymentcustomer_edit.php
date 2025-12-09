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
								<a href="<?php echo ADMIN_URL;?>addpaymentcustomer">Meter Customer Bills</a>
							</li>
							<li class="active">Edit</li>
						</ul><!-- .breadcrumb -->
					</div>
					<div class="page-content">
							<h3 class="header smaller lighter blue">
							Meter Customer Bills
							</h3>
						<div class="row">
							<form class="form-horizontal" role="form" name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
                      <div class="col-xs-12">
							<div class="col-xs-5">
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
                                
								<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="form-field-1">Customer-Id: </label>
										<div class="col-sm-8">
											 <select name="name" id="name" class="col-xs-10 col-sm-10" required onchange="getcustomeroldmeter(this.value);">
                                            <option value="">--Select--</option>
											<?php foreach($addcustomer as $key => $value){ ?>
											 <option value="<?php  echo $value['customer_id']; ?>" <?php if($record['customer_id']==$value['customer_id']){ ?> selected <?php } ?>><?php  echo $value['customer_id']; ?></option>
											<?php } ?>
											</select>
										</div>
									</div>
								   
									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="form-field-1"> <!-- Old Meter--> Aqrin Hore: </label>
										<div class="col-sm-8">
											<input type="text" id="oldmeter" name="oldmeter" class="col-xs-10 col-sm-10" value="<?php echo $record['oldmeter']; ?>" required/>
                                            <?php echo form_error('oldmeter'); ?>
										</div>
									</div>
								
									
									  
									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="form-field-1"> <!-- After Meter--> Aqrin dambe : </label>
										<div class="col-sm-8">
											<input type="text" id="aftermeter" name="aftermeter" class="col-xs-10 col-sm-10" value="<?php echo $record['aftermeter']; ?>" required/>
                                            <?php echo form_error('aftermeter'); ?>
										</div>
									</div>

									  
										
									

								</div><!-- /.col -->
							</div>
							<div class="col-xs-12">
							<div class="clearfix form-actions">
								<div class="space-4"></div>
										<div class="col-md-offset-3 col-md-9">
                                        <input type="submit" class="btn btn-sm btn-primary" name="add" id="add" value="Edit">
											&nbsp; &nbsp; &nbsp;
                                            <a href="<?php echo ADMIN_URL;?>addpaymentcustomer" class="btn btn-sm btn-danger">Cancel</a>
										</div>
									</div></div>
						</div><!-- /.row -->
						</form>
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
				
			})
		</script>
<script type="text/javascript">
function getcustomeroldmeter(id){
//alert(id);
	$.ajax({
	   type : "POST",   
	   url	: '<?php echo ADMIN_URL;?>addpaymentcustomer/getoldmeter',      
	   data	: "id="+id,
	   complete: function(data){  
		  var op = data.responseText.trim();
       //alert(op);
		 $("#oldmeter").val(op);  
	   }
	});
}		
</script>		
	</body>
</html>
