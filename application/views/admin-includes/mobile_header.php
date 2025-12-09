<?PHP 
header("cache-Control: no-store, no-cache, must-revalidate");
header("cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");  
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
//echo '<pre>'; print_r($this->session->all_userdata()); 
if(($this->session->userdata('logged_in')!='ECOM')||($this->session->userdata('username')=="")||($this->session->userdata('logged_in')=='')){
	redirect('/master/app_login');
}
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<title> Mobile - Utilities Billing System </title>
		<meta name="description" content="">
		<meta name="author" content="">
			
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/font-awesome.min.css">

		<!-- SmartAdmin Styles : Caution! DO NOT change the order -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/smartadmin-production-plugins.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/smartadmin-production.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/smartadmin-skins.min.css">

		<!-- SmartAdmin RTL Support  -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/smartadmin-rtl.min.css">

		<!-- We recommend you use "your_style.css" to override SmartAdmin
		     specific styles this will also ensure you retrain your customization with each SmartAdmin update.
		<link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->

		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/demo.min.css">

		<!-- FAVICONS -->
		<link rel="shortcut icon" href="<?php echo base_url();?>img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="<?php echo base_url();?>img/favicon/favicon.ico" type="image/x-icon">

		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

		<!-- Specifying a Webpage Icon for Web Clip 
			 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
		<link rel="apple-touch-icon" href="<?php echo base_url();?>img/splash/sptouch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url();?>img/splash/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url();?>img/splash/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url();?>img/splash/touch-icon-ipad-retina.png">
		
		<!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		
		<!-- Startup image for web apps -->
		<link rel="apple-touch-startup-image" href="<?php echo base_url();?>img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
		<link rel="apple-touch-startup-image" href="<?php echo base_url();?>img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
		<link rel="apple-touch-startup-image" href="<?php echo base_url();?>img/splash/iphone.png" media="screen and (max-device-width: 320px)">
		<style>
			/* Fullscreen overlay */
			.spinner-overlay {
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background-color: rgba(255, 255, 255, 0.8); /* Light overlay background */
				display: none;
				align-items: center;
				justify-content: center;
				z-index: 9999; /* Ensure it's above other content */
			}

			/* Spinner styles */
			.spinner {
				width: 50px;
				height: 50px;
				border: 5px solid #f3f3f3; /* Light border */
				border-top: 5px solid #3498db; /* Blue border */
				border-radius: 50%;
				animation: spin 1s linear infinite;
			}

			.input-group-addon:first-child {
				border-top-left-radius: 15px;
				border-bottom-left-radius: 15px;
			}
			.input-group-addon {
				border:1px solid black;
			}
			.form-control{
				border-top-right-radius: 15px !important;
				border-bottom-right-radius: 15px !important;
				border: 1px solid black;
			}
			.select2-selection{
				border-radius: 15px !important;
				border: 1px solid black;
				font-size:larger; font-weight: bold; background-color: darkslateblue; color: white; text-align: center;
			}
			.select2-container--open .select2-selection--single {
				border-radius: 15px !important;
				border: 1px solid black;
				font-size:larger; font-weight: bold; background-color: darkslateblue; color: white; text-align: center;
			}
			.select2-selection__arrow{
				background-color: darkslateblue;
			}
			/* Keyframes for spin animation */
			@keyframes spin {
				0% {
					transform: rotate(0deg);
				}
				100% {
					transform: rotate(360deg);
				}
			}

			.smart-style-1 aside{
				background: unset;
			}
			.smart-style-1 nav ul ul{
				background: unset;
			}
		</style>
		<script>
			// Function to show the spinner
			function showSpinner() {
				document.getElementById("spinner-overlay").style.display = "flex";
			}

			// Function to hide the spinner
			function hideSpinner() {
				document.getElementById("spinner-overlay").style.display = "none";
			}
			//showSpinner(); // Call this to show the spinner
			//setTimeout(hideSpinner, 3000); // Simulate loading for 3 seconds
		</script>
	</head>
	
	<body class="desktop-detected smart-style-1" style="background: url() blue;">
		<div id="spinner-overlay" class="spinner-overlay">
			<div class="spinner"></div>
		</div>
		<!-- HEADER -->
		<header id="header">
			<div id="logo-group">

				<!-- PLACE YOUR LOGO HERE -->
				<span id="logo" style="width:500px;"><h4 style="color:#fff">Mobile - Billing System</h4></span>
				<!-- END LOGO PLACEHOLDER -->

			
			</div>

			
<!-- pulled right: nav area -->
<div class="pull-right">
				
				<!-- collapse menu button -->
				<div id="hide-menu" class="btn-header pull-right">
					<span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
				</div>
				<!-- end collapse menu -->
				
				<!-- #MOBILE -->
				

				<!-- logout button -->
				<div id="logout" class="btn-header transparent pull-right">
					<span> <a href="<?php echo ADMIN_URL;?>mobile_dashboard/logout" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>
				</div>
				<!-- end logout button -->

				
				

				<!-- fullscreen button -->
				<div id="fullscreen1" class="btn-header transparent pull-right">
					<span> <a href="javascript:void(0);" data-action="launchFullscreen" id="clickfullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
				</div>
				<!-- end fullscreen button -->
				
				

				

			</div>
			<!-- end pulled right: nav area -->
			

		</header>
		<!-- END HEADER -->
		<?php //include("js.php"); ?>
		<?php include("mobile_navigation.php"); ?>