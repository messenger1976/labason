<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<title> SmartAdmin </title>
		<meta name="description" content="">
		<meta name="author" content="">
			
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- FAVICONS -->
		<link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">

		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

	</head>
	
<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">

				<span class="ribbon-button-alignment"> 
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
						<i class="fa fa-refresh"></i>
					</span> 
				</span>

				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li><a href="<?php echo ADMIN_URL?>">Home</a></li>
					<li><a href="<?php echo ADMIN_URL?>web_settings/"> Address </a></li>
					<li>Edit</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa fa-pencil-square-o fa-fw"></i>edit <span>>  Address </span></h1>
					</div>
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
						<ul id="sparks" class="">
							<li class="sparks-info">
							<?php 
							     $income1 = $this->my_model->get_income_metercustomer();
							     extract($income1);
								 $income2 = $this->my_model->get_income_monthlycustomer();
								 extract($income2);
								 $intotal = $total1 + $total2;
							?>
								<h5> Income <span class="txt-color-blue">PHP <?php print_r(number_format($intotal,2));?></span></h5>
								<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>
							<?php
							     $expense1 = $this->my_model->get_outcome_expenses();
							     extract($expense1);
								 $expense2 = $this->my_model->get_outcome_payroll();
								 extract($expense2);
								 $extotal = $extotal1 + $extotal2;
							?>
							<li class="sparks-info">
								<h5> Expense <span class="txt-color-purple">PHP <?php print_r(number_format($extotal,2));?></span></h5>
								<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>
							<?php 
							     $total_customer = $this->my_model->total_customer();
							     extract($total_customer); 
							?>
							<li class="sparks-info">
								<h5> Total Customer <span class="txt-color-greenDark">&nbsp;<?php print_r($count_id);?></span></h5>
								<div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>
						</ul>
					</div>
				</div>
				<!-- widget grid -->
				<section id="widget-grid" class="">

					<!-- row -->

					<div class="row">

						<!-- a blank row to get started -->
						<div class="col-sm-6 col-lg-12">
						

								<!-- your contents here -->
								<div class="panel panel-default">
									
									<div class="widget-body">
				
										<form class="form-horizontal" role="form" name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
										  	
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
											
											<fieldset>
														<legend>Address-Edit </legend>
														<div class="form-group">
															<label class="col-md-2 control-label">Customer-Id : </label>
															<div class="col-md-8"><textarea id="content" name="content" class="summernote"  rows="50" cols="80">
															<?php echo stripslashes(str_replace('\n','',$record['content'])); ?>
															</textarea>
															</div>
															</div>
														</div>
														

													</fieldset>
													
													
													
													<div class="form-actions">
														<div class="row">
															<div class="col-md-12">
																
																 <a href="<?php echo ADMIN_URL;?>web_settings" class="btn btn-default">Cancel</a>
																<input type="submit" class="btn btn-primary" name="add" id="add" value="Save">
															</div>
														</div>
													
										</form>
				
									</div>
								    
								
								</div>	
						</div>
					</div>
                    

						
					<!-- end row -->

				</section>
				<!-- end widget grid -->

					

				</section>
				<!-- end widget grid -->

			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->
		

		<?php include('footer.php');?>

	</body>

</html>

<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url();?>js/plugin/summernote/summernote.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/markdown/markdown.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/markdown/to-markdown.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/markdown/bootstrap-markdown.min.js"></script>
		

		<script type="text/javascript">
		
			// DO NOT REMOVE : GLOBAL FUNCTIONS!
			
			$(document).ready(function() {
				
				pageSetUp();

				/*
				 * SUMMERNOTE EDITOR
				 */
				
				$('.summernote').summernote({
					height: 200,
					toolbar: [
				    ['style', ['style']],
				    ['font', ['bold', 'italic', 'underline', 'clear']],
				    ['fontname', ['fontname']],
				    ['color', ['color']],
				    ['para', ['ul', 'ol', 'paragraph']],
				    ['height', ['height']],
				    ['table', ['table']],
				    ['insert', ['link', 'picture', 'hr']],
				    ['view', ['fullscreen', 'codeview', 'help']]

				  ]
				});
			
				/*
				 * MARKDOWN EDITOR
				 */

				$("#mymarkdown").markdown({
					autofocus:false,
					savable:true
				})
							
			
			})

		</script>

		<!-- Your GOOGLE ANALYTICS CODE Below -->
		<script type="text/javascript">
			var _gaq = _gaq || [];
				_gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
				_gaq.push(['_trackPageview']);
			
			(function() {
				var ga = document.createElement('script');
				ga.type = 'text/javascript';
				ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(ga, s);
			})();

		</script>