<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Login - Billing System</title>
	<meta name="description" content="Secure login">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="msapplication-tap-highlight" content="no">
	<?php $sa4 = base_url() . 'sa4/'; ?>
	<link id="vendorsbundle" rel="stylesheet" media="screen, print" href="<?php echo $sa4; ?>css/vendors.bundle.css">
	<link id="appbundle" rel="stylesheet" media="screen, print" href="<?php echo $sa4; ?>css/app.bundle.css">
	<link id="mytheme" rel="stylesheet" media="screen, print" href="#">
	<link id="myskin" rel="stylesheet" media="screen, print" href="<?php echo $sa4; ?>css/skins/skin-master.css">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>img/pmroxas-logo.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>img/pmroxas-logo.png">
	<link rel="icon" href="<?php echo base_url(); ?>img/pmroxas-logo.png" type="image/png">
	<link rel="shortcut icon" href="<?php echo base_url(); ?>img/pmroxas-logo.png" type="image/png">
	<link rel="stylesheet" media="screen, print" href="<?php echo $sa4; ?>css/fa-brands.css">
</head>
<body>
	<style>
		/* Auth pages have no sidebar — never reserve nav space */
		.page-wrapper.auth .page-content-wrapper {
			padding-left: 0 !important;
			margin-left: 0 !important;
		}
	</style>
	<script>
		'use strict';
		var classHolder = document.getElementsByTagName('BODY')[0],
			themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) : {};
		/*
		 * Do NOT apply saved admin layout classes (nav-function-fixed, etc.)
		 * on login — they add ~270px left padding for a sidebar that is not here.
		 * Theme color CSS is still applied via #mytheme when present.
		 */
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
								<img src="<?php echo base_url(); ?>img/pmroxas-logo.png" alt="Labason Water District" aria-roledescription="logo" style="width:32px;height:32px;">
								<span class="page-logo-text mr-1">LABASON WATER DISTRICT</span>
							</a>
						</div>
						<a href="<?php echo site_url(); ?>/master/forgot_password" class="btn-link text-white ml-auto">
							Forgot password?
						</a>
					</div>
				</div>
				<div class="flex-1" style="background: url(<?php echo $sa4; ?>img/svg/pattern-1.svg) no-repeat center bottom fixed; background-size: cover;">
					<div class="container py-4 py-lg-5 my-lg-5 px-4 px-sm-0">
						<div class="row">
							<div class="col col-md-6 col-lg-7 hidden-sm-down">
								<h2 class="fs-xxl fw-500 mt-4 text-white">
									Water Billing Administration
									<small class="h3 fw-300 mt-3 mb-5 text-white opacity-60">
										A modern control center for customers, meter readings, payments, leaking reports, and day-to-day operations — built for clarity, speed, and reliable billing workflows.
									</small>
								</h2>
								<a href="#js-login-form" class="fs-lg fw-500 text-white opacity-70">Learn more &gt;&gt;</a>
								<div class="d-sm-flex flex-column align-items-center justify-content-center d-md-block">
									<div class="px-0 py-1 mt-5 text-white fs-nano opacity-50">
										Find us on social media
									</div>
									<div class="d-flex flex-row opacity-70">
										<a href="javascript:void(0);" class="mr-2 fs-xxl text-white">
											<i class="fab fa-facebook-square"></i>
										</a>
										<a href="javascript:void(0);" class="mr-2 fs-xxl text-white">
											<i class="fab fa-twitter-square"></i>
										</a>
										<a href="javascript:void(0);" class="mr-2 fs-xxl text-white">
											<i class="fab fa-google-plus-square"></i>
										</a>
										<a href="javascript:void(0);" class="mr-2 fs-xxl text-white">
											<i class="fab fa-linkedin"></i>
										</a>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-md-6 col-lg-5 col-xl-4 ml-auto">
								<h1 class="text-white fw-300 mb-3 d-sm-block d-md-none">
									Secure login
								</h1>
								<div class="card p-4 rounded-plus bg-faded">
									<?php if ($this->session->flashdata('message')) { ?>
										<div class="alert alert-danger alert-dismissible fade show" role="alert">
											<?php echo $this->session->flashdata('message'); ?>
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true"><i class="fal fa-times"></i></span>
											</button>
										</div>
									<?php } else if ($this->session->flashdata('messages')) { ?>
										<div class="alert alert-success alert-dismissible fade show" role="alert">
											<?php echo $this->session->flashdata('messages'); ?>
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true"><i class="fal fa-times"></i></span>
											</button>
										</div>
									<?php } else { ?>
										<div class="text-center mb-3 text-muted">
											Please enter your information
										</div>
									<?php } ?>
									<?php if (isset($error) && $error) { echo '<div class="alert alert-danger" role="alert">' . $error . '</div>'; } ?>

									<form action="" method="post" id="js-login-form" novalidate>
										<div class="form-group">
											<label class="form-label" for="username">Username</label>
											<input type="text" name="username" id="username" class="form-control form-control-lg" placeholder="your username" value="<?php echo set_value('username'); ?>" required autocomplete="username" autofocus>
											<div class="invalid-feedback">No, you missed this one.</div>
											<div class="help-block">Your unique username to app</div>
											<?php echo form_error('username'); ?>
										</div>
										<div class="form-group">
											<label class="form-label" for="password">Password</label>
											<input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="password" value="<?php echo set_value('password'); ?>" required autocomplete="current-password">
											<div class="invalid-feedback">Sorry, you missed this one.</div>
											<div class="help-block">Your password</div>
											<?php echo form_error('password'); ?>
										</div>
										<div class="form-group text-left">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="remember" name="remember" value="1" checked>
												<label class="custom-control-label" for="remember">Remember me for the next 30 days</label>
											</div>
										</div>
										<div class="row no-gutters">
											<div class="col-lg-6 pr-lg-1 my-2">
												<a href="<?php echo site_url(); ?>/master/forgot_password" class="btn btn-info btn-block btn-lg">
													Forgot <i class="fal fa-key"></i>
												</a>
											</div>
											<div class="col-lg-6 pl-lg-1 my-2">
												<button type="submit" name="submit" id="js-login-btn" value="Login" class="btn btn-danger btn-block btn-lg">Secure login</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="position-absolute pos-bottom pos-left pos-right p-3 text-center text-white">
							<?php echo date('Y'); ?> &copy; Billing System by&nbsp;<span class="text-white opacity-40 fw-500">Water Billing Administration</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<p id="js-color-profile" class="d-none">
		<span class="color-primary-50"></span>
		<span class="color-primary-100"></span>
		<span class="color-primary-200"></span>
		<span class="color-primary-300"></span>
		<span class="color-primary-400"></span>
		<span class="color-primary-500"></span>
		<span class="color-primary-600"></span>
		<span class="color-primary-700"></span>
		<span class="color-primary-800"></span>
		<span class="color-primary-900"></span>
		<span class="color-info-50"></span>
		<span class="color-info-100"></span>
		<span class="color-info-200"></span>
		<span class="color-info-300"></span>
		<span class="color-info-400"></span>
		<span class="color-info-500"></span>
		<span class="color-info-600"></span>
		<span class="color-info-700"></span>
		<span class="color-info-800"></span>
		<span class="color-info-900"></span>
		<span class="color-danger-50"></span>
		<span class="color-danger-100"></span>
		<span class="color-danger-200"></span>
		<span class="color-danger-300"></span>
		<span class="color-danger-400"></span>
		<span class="color-danger-500"></span>
		<span class="color-danger-600"></span>
		<span class="color-danger-700"></span>
		<span class="color-danger-800"></span>
		<span class="color-danger-900"></span>
		<span class="color-warning-50"></span>
		<span class="color-warning-100"></span>
		<span class="color-warning-200"></span>
		<span class="color-warning-300"></span>
		<span class="color-warning-400"></span>
		<span class="color-warning-500"></span>
		<span class="color-warning-600"></span>
		<span class="color-warning-700"></span>
		<span class="color-warning-800"></span>
		<span class="color-warning-900"></span>
		<span class="color-success-50"></span>
		<span class="color-success-100"></span>
		<span class="color-success-200"></span>
		<span class="color-success-300"></span>
		<span class="color-success-400"></span>
		<span class="color-success-500"></span>
		<span class="color-success-600"></span>
		<span class="color-success-700"></span>
		<span class="color-success-800"></span>
		<span class="color-success-900"></span>
		<span class="color-fusion-50"></span>
		<span class="color-fusion-100"></span>
		<span class="color-fusion-200"></span>
		<span class="color-fusion-300"></span>
		<span class="color-fusion-400"></span>
		<span class="color-fusion-500"></span>
		<span class="color-fusion-600"></span>
		<span class="color-fusion-700"></span>
		<span class="color-fusion-800"></span>
		<span class="color-fusion-900"></span>
	</p>
	<script src="<?php echo $sa4; ?>js/vendors.bundle.js"></script>
	<script src="<?php echo $sa4; ?>js/app.bundle.js"></script>
	<script>
		$('#js-login-btn').on('click', function(event) {
			var form = $('#js-login-form');
			if (form[0].checkValidity() === false) {
				event.preventDefault();
				event.stopPropagation();
			}
			form.addClass('was-validated');
		});
	</script>
</body>
</html>
