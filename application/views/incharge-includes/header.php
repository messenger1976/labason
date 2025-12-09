<?PHP 
header("cache-Control: no-store, no-cache, must-revalidate");
header("cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
if(($this->session->userdata('logged_in')!='INCH')||($this->session->userdata('username')=="")||($this->session->userdata('logged_in')=='')){
	redirect('/incharge/');
}
?>    
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Incharge</title>
		<meta name="description" content="Static &amp; Dynamic Tables" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!-- basic styles -->
		<link href="<?php echo site_url();?>/assets/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/font-awesome.min.css" />
        <link rel="stylesheet" href="<?php echo site_url();?>/assets/css/style.css" />
        
		<!--[if IE 7]>
		  <link rel="stylesheet" href="<?php echo site_url();?>/assets/css/font-awesome-ie7.min.css" />
		<![endif]-->
		<!-- page specific plugin styles -->
		<!-- fonts -->
		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/ace-fonts.css" />
		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/ace.min.css" />
		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/ace-rtl.min.css" />
		<link rel="stylesheet" href="<?php echo site_url();?>/assets/css/ace-skins.min.css" />
		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="<?php echo site_url();?>/assets/css/ace-ie.min.css" />
		<![endif]-->
		<!-- inline styles related to this page -->
		<!-- ace settings handler -->
		<script src="<?php echo site_url();?>/assets/js/ace-extra.min.js"></script>
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="<?php echo site_url();?>/assets/js/html5shiv.js"></script>
		<script src="<?php echo site_url();?>/assets/js/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="navbar navbar-default" id="navbar">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>
			<div class="navbar-container" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="#" class="navbar-brand">
						<small>
							<i class="icon-leaf"></i>
							Incharge Panel
						</small>
					</a><!-- /.brand -->
				</div><!-- /.navbar-header -->
                <div class="navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="<?php echo site_url();?>/assets/avatars/avatar.png" alt="AdminPhoto" />
								<span class="user-info">
									<small>Welcome,</small>
									<?php echo $_SERVER['SERVER_NAME'];?>
								</span>
								<i class="icon-caret-down"></i>
							</a>
							<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

                            	
								<li>
									<a href="<?php echo site_url()?>incharge/change_password">
										<i><img src="<?php echo site_url();?>/assets/images/change-password.png" width="23" height="24" /></i>
										Change Password
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="<?php echo site_url()?>incharge/logout">
										<i class="icon-off"></i>
										Logout
									</a>
								</li>
							</ul>
						</li>
					</ul><!-- /.ace-nav -->
				</div>
                
                <div class="navbar-header pull-right" role="navigation" style="padding-right:22px;">
					<ul class="nav ace-nav">
						<li class="light-blue">
							<a href="<?php echo site_url()?>" target="_blank">
								<img class="nav-user-photo" src="<?php echo site_url();?>/assets/images/shop.jpg" alt="AdminPhoto" />
								<span class="user-info" style="margin-top:7px;">
								View Live Site
						
								</span>
							</a>
							
						</li>
					</ul><!-- /.ace-nav -->
				</div>
                
                <div class="navbar-header pull-right" role="navigation" style="padding-right:22px;">
					<ul class="nav ace-nav">
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="<?php echo site_url();?>/assets/images/last-login.jpg" alt="AdminPhoto" />
								<span class="" style="margin-top:7px;">
Last Logged in : <?PHP  
	if($record_info['login_time']!=""){
		 echo date("d-M-Y",strtotime($record_info['login_time']))." (".date("h:i A",strtotime($record_info['login_time'])).") &nbsp;"; }?>
		</span>
							</a>
							
						</li>
					</ul><!-- /.ace-nav -->
				</div>
                
                
                <!-- /.navbar-header -->
			</div><!-- /.container -->
		</div>
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>
			<div class="main-container-inner">
				<a class="menu-toggler" id="menu-toggler" href="#">
					<span class="menu-text"></span>
				</a>
				<div class="sidebar" id="sidebar">
					
					<div class="sidebar-shortcuts" id="sidebar-shortcuts">
						<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
							<span style="font-size:20px;">Features</span>
						</div>
						<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
							<span class="btn btn-success"></span>
							<span class="btn btn-info"></span>
							<span class="btn btn-warning"></span>
							<span class="btn btn-danger"></span>
						</div>
					</div><!-- #sidebar-shortcuts -->
					<ul class="nav nav-list">


						<li class="<?php if($this->uri->segment(2)=='orders') echo 'active';?>" >
							<a href="<?php echo INCHARGE_URL;?>orders">
								<span class="icon-dashboard blue"></span>
								<span class="menu-text"> Orders </span>
							</a>
						</li>
						

						
					</ul><!-- /.nav-list -->
			  <div class="sidebar-collapse" id="sidebar-collapse" style="border-bottom:none;">
						<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
					</div>
					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
					</script>
				</div>
