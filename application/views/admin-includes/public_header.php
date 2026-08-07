<?PHP 
date_default_timezone_set('Asia/Manila');
header("cache-Control: no-store, no-cache, must-revalidate");
header("cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");  
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
// Public header - no authentication required, no navigation
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<title>Statement of Account - Water Billing System</title>
		<meta name="description" content="Customer Statement of Account">
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

		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/demo.min.css">

		<!-- FAVICONS -->
		<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon">
		<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon">

		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

		<!-- PWA Meta Tags -->
		<meta name="theme-color" content="#5bc0de">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<meta name="apple-mobile-web-app-title" content="Statement of Account">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">

		<!-- PWA Manifest -->
		<link rel="manifest" href="<?php echo base_url();?>manifest.json">
		
		<!-- Register Service Worker for PWA -->
		<script>
			if ('serviceWorker' in navigator) {
				window.addEventListener('load', function() {
					navigator.serviceWorker.register('<?php echo base_url();?>sw.js')
						.then(function(registration) {
							console.log('ServiceWorker registration successful');
						})
						.catch(function(err) {
							console.log('ServiceWorker registration failed');
						});
				});
			}
		</script>

		<style>
			* {
				box-sizing: border-box;
			}
			body {
				background: #f5f5f5;
				padding: 0;
				margin: 0;
				font-family: 'Open Sans', sans-serif;
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
			}
			#main {
				margin: 0 !important;
				padding: 10px;
			}
			#content {
				margin: 0 !important;
				padding: 0;
			}
			
			/* Mobile Responsive */
			@media (max-width: 768px) {
				#main {
					padding: 5px;
				}
				.table-responsive {
					overflow-x: auto;
					-webkit-overflow-scrolling: touch;
				}
				.table {
					font-size: 12px;
				}
				.table th,
				.table td {
					padding: 8px 4px;
					white-space: nowrap;
				}
				.btn {
					padding: 10px 15px;
					font-size: 14px;
				}
				.panel {
					margin-bottom: 10px;
				}
				.panel-body {
					padding: 15px !important;
				}
				h1.page-title {
					font-size: 18px;
					margin-bottom: 10px;
				}
			}
			
			@media (max-width: 480px) {
				.table {
					font-size: 11px;
				}
				.table th,
				.table td {
					padding: 6px 3px;
				}
				.btn {
					padding: 8px 12px;
					font-size: 12px;
				}
				.panel-body {
					padding: 10px !important;
				}
			}

			@media print {
				html, body {
					background: #fff !important;
					margin: 0 !important;
					padding: 0 !important;
				}
				#main, #content {
					margin: 0 !important;
					padding: 0 !important;
					width: 100% !important;
					max-width: 100% !important;
				}
			}
		</style>
	</head>

	<body class="smart-style-0" style="background: #f5f5f5;">
		<!-- No header, no sidebar - just content -->
		
		<!-- Load jQuery first -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="<?php echo base_url();?>js/libs/jquery-2.1.1.min.js"><\/script>');
			}
		</script>
		
		<div id="main" role="main" style="margin: 0; padding: 20px;">
			<div id="content" style="margin: 0; padding: 0;">
