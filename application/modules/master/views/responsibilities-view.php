<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>responsibilities/">Sub Admins</a></li>
		<li class="breadcrumb-item active">Roles & Responsibilities</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-th-list"></i>
			Manage <span class="fw-300">Responsibilities View</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Responsibilities View <span class="fw-300"><i>Details</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
<section id="widget-grid" class="">

					<!-- row -->

					<div class="row">

						<!-- a blank row to get started -->
						<div class="col-sm-6 col-lg-12">
						

								<!-- your contents here -->
								<form class="form-horizontal" role="form" name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
										  	
											<?php if($msg != ''){?>
											<div class="alert alert-success alert-dismissible fade show">
												<button type="button" class="close" data-dismiss="alert">
												<i class="fal fa-times"></i>
												</button>
												<p>
													<i class="fal fa-check"></i>
													<?php echo $msg?$msg:'';?>
												</p>
											</div>
											<?php } ?>	
											
											<fieldset>
														<h5 class="mb-3">Roles &amp; Permissions </h5>
														<div class="form-group col-lg-6">
															<div class="col-lg-12 controls">
																<div class="form-group">
																	<label class="form-label">Role Name : </label>
																	<input class="form-control" type="text" id="role_name" name="role_name" value="<?php echo stripslashes($record['role_name']); ?>" required  maxlength="50" readonly/>
																	<?php echo form_error('customer_id'); ?>
																</div>
															</div>

															
														</div>
														
														<div style="clear:both;"></div>
														<?php include(dirname(__FILE__).'/responsibilities-permissions.php'); ?>
													</fieldset>

													<div class="form-group mt-3">
														<div class="row">
															<div class="col-md-12">
																<?php 	if( ( array_key_exists('admin',$roleResponsible) && is_array($roleResponsible['admin']) && (in_array('e',$roleResponsible['admin'])) ) || ( $this->session->userdata('usertype') == 'admin' ) ){  ?>
					                                               <a href="<?php echo ADMIN_URL;?>responsibilities/edit/<?php echo $record['id']; ?>" class="btn btn-sm btn-primary">Edit </a>
                                                                <?php } ?>

																 <a href="<?php echo ADMIN_URL;?>responsibilities/" class="btn btn-secondary">Cancel</a>
																
															</div>
														</div>
									</div>
								</div>
							</form>
								    
								
									
						
					</div>
                    

						
					<!-- end row -->

				</section>
			<!-- end widget grid -->
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
</body>
</html>
<script type="text/javascript">
		
		function customer_type_values(){
			$("#showcustomers").hide();			
			if($("#customer_type").val()=='monthlycustomer'){
				$("#showcustomers").show();
			}
			else if($("#customer_type").val()=='metercustomer'){
				$("#showcustomers").hide();
			}
		}
	
		</script>
<script>
function fun_methods(value){
	if($("#module"+value).prop('checked') == true){ 
		$("."+value).prop('checked', false);
		$("."+value).show();
	}else if($("#module"+value).prop('checked') == false){ 
		$("."+value).prop('checked', false);
		$("."+value).hide();
	}
}
</script>

