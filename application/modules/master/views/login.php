<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Login Page</title>
		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!-- #CSS Links -->
		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/font-awesome.min.css">

		<!-- SmartAdmin Styles : Caution! DO NOT change the order -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/smartadmin-production-plugins.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/smartadmin-production.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/smartadmin-skins.min.css">

		<!-- SmartAdmin RTL Support -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/smartadmin-rtl.min.css"> 

		<!-- We recommend you use "your_style.css" to override SmartAdmin
		     specific styles this will also ensure you retrain your customization with each SmartAdmin update.
		<link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->

		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/demo.min.css">

		<!-- #FAVICONS -->
		<link rel="shortcut icon" href="<?php echo base_url();?>img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="<?php echo base_url();?>img/favicon/favicon.ico" type="image/x-icon">

		<!-- #GOOGLE FONT -->
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
	</head>
	<body>

		<!--<header id="header">

			<div id="logo-group">
				<span id="logo"> <img src="img/logo.png" alt="SmartAdmin"> </span>
			</div>

			

		</header>--><br/><br/><br/><br/>

		<div id="main" role="main">

			<!-- MAIN CONTENT -->
			<div id="content" class="container">

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-3 hidden-xs hidden-sm">
					</div>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
						<div class="well no-padding">
						<form action="" method="post" id="login-form" class="smart-form client-form">
							   <header>
									<?php if( $this->session->flashdata( 'message' ) ) { ?>
                                            <span style="color:#F00; font-size:12px; font-weight:bold; text-align:center;">
                                                <?php echo $this->session->flashdata( 'message' ); ?>
                                            </span> 
                                        <?php  }else if( $this->session->flashdata( 'messages' ) ) { ?>
                                            <span style="color:green; font-size:12px; font-weight:bold; text-align:center;">
                                                <?php echo $this->session->flashdata( 'messages' ); ?>
                                            </span> 
                                        <?php  }else{ ?>
                                            Please Enter Your Information
                                        <?php } //print_r($this->session->all_userdata());?>
										<?php if(isset($error)) echo $error;?>
								</header>
								
								<fieldset>
									
									<section>
										<label class="label">Username</label>
										<label class="input"> <i class="icon-append fa fa-user"></i>
											<input type="text" name="username" id="username" value="<?php echo set_value('username'); ?>"  />
                                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i>Please enter username</b></label>
									</section>

									<section>
										<label class="label">Password</label>
										<label class="input"> <i class="icon-append fa fa-lock"></i>
											<input type="password" name="password" id="password" value="<?php echo set_value('password'); ?>" />
											<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your password</b> </label>
										<div class="note">
											<a href="<?php echo site_url()?>/master/forgot_password">Forgot password?</a>
										</div>
									</section>

									<section>
										<label class="checkbox">
											<input type="checkbox" name="remember" checked="">
											<i></i>Stay signed in</label>
									</section>
								</fieldset>
								<footer>
									
									<input type="submit" name="submit" id="submit" class="btn btn-primary" value="Login" />
								</footer>
							</form>

						</div>
						
						
					</div>
				</div>
			</div>

		</div>

		<!--================================================== -->	
        <!--================================================== -->	

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<!--<script src="<?php echo base_url();?>js/plugin/pace/pace.min.js"></script>-->

	    <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
	    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script> if (!window.jQuery) { document.write('<script src="js/libs/jquery-2.1.1.min.js"><\/script>');} </script>

	    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script> if (!window.jQuery.ui) { document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');} </script>

		<!-- IMPORTANT: APP CONFIG -->
		<script src="<?php echo base_url();?>js/app.config.js"></script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events 		
		<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

		<!-- BOOTSTRAP JS -->		
		<script src="<?php echo base_url();?>js/bootstrap/bootstrap.min.js"></script>

		<!-- JQUERY VALIDATE -->
		<script src="<?php echo base_url();?>js/plugin/jquery-validate/jquery.validate.min.js"></script>
		
		<!-- JQUERY MASKED INPUT -->
		<script src="<?php echo base_url();?>js/plugin/masked-input/jquery.maskedinput.min.js"></script>
		
		<!--[if IE 8]>
			
			<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
			
		<![endif]-->
		<!-- MAIN APP JS FILE -->
		<script src="<?php echo base_url();?>js/app.min.js"></script>
		
		<script type="text/javascript">
			runAllForms();

			$(function() {
				// Validation
				$("#login-form").validate({
					// Rules for form validation
					rules : {
						username : {
							required : true
						},
						password : {
							required : true,
							minlength : 3,
							maxlength : 20
						}
					},

					// Messages for form validation
					messages : {
						username : {
							required : 'Please enter username'
						},
						password : {
							required : 'Please enter your password'
						}
					},

					// Do not change code below
					errorPlacement : function(error, element) {
						error.insertAfter(element.parent());
					}
				});
			});
		</script>

	</body>
</html>
	
	
	
	
