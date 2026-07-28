<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Forgot Password - Billing System</title>
	<meta name="description" content="Forgot password">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
	<?php $sa4 = base_url() . 'sa4/'; ?>
	<link id="vendorsbundle" rel="stylesheet" media="screen, print" href="<?php echo $sa4; ?>css/vendors.bundle.css">
	<link id="appbundle" rel="stylesheet" media="screen, print" href="<?php echo $sa4; ?>css/app.bundle.css">
	<link id="mytheme" rel="stylesheet" media="screen, print" href="#">
	<link id="myskin" rel="stylesheet" media="screen, print" href="<?php echo $sa4; ?>css/skins/skin-master.css">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>img/pmroxas-logo.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>img/pmroxas-logo.png">
	<link rel="icon" href="<?php echo base_url(); ?>img/pmroxas-logo.png" type="image/png">
	<link rel="shortcut icon" href="<?php echo base_url(); ?>img/pmroxas-logo.png" type="image/png">
	<style>
		.page-wrapper.auth .page-content-wrapper {
			padding-left: 0 !important;
			margin-left: 0 !important;
		}
	</style>
</head>
<body>
	<script>
		'use strict';
		var classHolder = document.getElementsByTagName('BODY')[0],
			themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) : {};
		classHolder.className = '';
		if (themeSettings.themeURL && document.getElementById('mytheme')) {
			document.getElementById('mytheme').href = themeSettings.themeURL;
		}
	</script>
	<div class="page-wrapper auth">
		<div class="page-inner bg-brand-gradient">
			<div class="page-content-wrapper bg-transparent m-0">
				<div class="height-10 w-100 shadow-lg px-4 bg-brand-gradient">
					<div class="d-flex align-items-center container p-0">
						<div class="page-logo width-mobile-auto m-0 align-items-center justify-content-center p-0 bg-transparent bg-img-none shadow-0 height-9 border-0">
							<a href="<?php echo site_url(); ?>/master/" class="page-logo-link press-scale-down d-flex align-items-center">
								<img src="<?php echo $sa4; ?>img/logo.png" alt="Billing">
								<span class="page-logo-text mr-1">Billing System</span>
							</a>
						</div>
						<a href="<?php echo site_url(); ?>/master/" class="btn-link text-white ml-auto">Back to Login</a>
					</div>
				</div>
				<div class="flex-1" style="background: url(<?php echo $sa4; ?>img/svg/pattern-1.svg) no-repeat center bottom fixed; background-size: cover;">
					<div class="container py-4 py-lg-5 my-lg-5 px-4 px-sm-0">
						<div class="row">
							<div class="col-xl-12">
								<h2 class="fs-xxl fw-500 text-white text-center">
									Forgot password?
									<small class="h3 fw-300 mt-3 mb-5 text-white opacity-60 d-block">
										Enter your email to receive reset instructions.
									</small>
								</h2>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mx-auto">
								<div class="card p-4 rounded-plus bg-faded">
									<?php if ($this->session->flashdata('message')) { ?>
										<div class="alert alert-danger"><?php echo $this->session->flashdata('message'); ?></div>
									<?php } ?>
									<?php if (isset($error) && $error) { echo '<div class="alert alert-danger">' . $error . '</div>'; } ?>
									<form name="forgot-form" id="forgot-form" action="<?php echo site_url(); ?>/master/forgot_password" method="post" novalidate>
										<div class="form-group">
											<label class="form-label" for="email">Email</label>
											<input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Email address" value="<?php echo set_value('email'); ?>" required>
										</div>
										<button type="submit" name="forgot" id="submit" class="btn btn-danger btn-block btn-lg">Send Me!</button>
									</form>
								</div>
							</div>
						</div>
						<div class="position-absolute pos-bottom pos-left pos-right p-3 text-center text-white">
							<?php echo date('Y'); ?> &copy; Billing System
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<p id="js-color-profile" class="d-none"><span class="color-primary-500"></span></p>
	<script src="<?php echo $sa4; ?>js/vendors.bundle.js"></script>
	<script src="<?php echo $sa4; ?>js/app.bundle.js"></script>
</body>
</html>
