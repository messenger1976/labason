<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Login Page</title>
		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php include("include.php");?>
	</head>
	<body class="animated fadeInDown">

		<header id="header">

			<div id="logo-group">
				<span id="logo"> <img src="img/logo.png" alt="SmartAdmin"> </span>
			</div>

		</header>

		<div id="main" role="main">

			<!-- MAIN CONTENT -->
			<div id="content" class="container">

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-3 hidden-xs hidden-sm">
					</div>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
						<div class="well no-padding">
						<form name="forgot-form" id="forgot-form" action="<?php echo site_url()?>/master/forgot_password" method="post" class="smart-form client-form">
							   <header>
									<?php if( $this->session->flashdata( 'message' ) ) : ?>
                                            <span style="color:#F00; font-size:12px; font-weight:bold; text-align:center;">
                                                <?php echo $this->session->flashdata( 'message' ); ?>
                                            </span> 
                                        <?php  else: ?>
											Enter your email and to receive instructions
                                        <?php endif; //print_r($this->session->all_userdata());?>
										<?php if(isset($error)) echo $error;?>
								</header>
								
								<fieldset>
									
									<section>
										<label class="label">Email</label>
										<label class="input"> <i class="icon-append fa fa-lock"></i>
											<input name="email" id="email" value="<?php echo set_value('email'); ?>" />
											<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your email</b> </label>
										<div class="note">
											<a href="<?php echo site_url()?>/master/">Back to Login</a>
										</div>
									</section>

								</fieldset>
								<footer>
									
									<input type="submit" name="forgot" id="submit" class="btn btn-primary" value="Send Me!" />
								</footer>
							</form>

						</div>
						
						
					</div>
				</div>
			</div>

		</div>

		<!--================================================== -->	
        
		<script type="text/javascript">
			runAllForms();

			$(function() {
				// Validation
				$("#forgot-form").validate({
					// Rules for form validation
					rules : {
						email : {
							required : true,
							email : true
						}
						
					},

					// Messages for form validation
					messages : {
						email : {
							required : 'Please enter your email address',
							email : 'Please enter a VALID email address'
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
	
	
	
	
