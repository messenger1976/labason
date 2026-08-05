<?PHP 
date_default_timezone_set('Asia/Manila');
header("cache-Control: no-store, no-cache, must-revalidate");
header("cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");  
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
if(($this->session->userdata('username')=="")||($this->session->userdata('logged_in')=='')){
	redirect('/master/');
}
// Keep display name in sync with the logged-in account (employee_name or username)
$__uid = $this->session->userdata('userid');
$__uname = trim((string) $this->session->userdata('username'));
$__display_name = '';
if ((string) $__uid === '1' && strtolower((string) $this->session->userdata('usertype')) === 'admin') {
	$__display_name = trim((string) $this->session->userdata('name'));
	if ($__display_name === '') {
		if ($this->db->field_exists('name', 'tbl_admin_details')) {
			$__row = $this->db->select('name, username')
				->from('tbl_admin_details')
				->where('id', $__uid)
				->limit(1)
				->get()
				->row_array();
		} else {
			$__row = $this->db->select('username')
				->from('tbl_admin_details')
				->where('id', $__uid)
				->limit(1)
				->get()
				->row_array();
		}
		if (!empty($__row)) {
			$__display_name = isset($__row['name']) ? trim((string) $__row['name']) : '';
			if ($__display_name === '') {
				$__display_name = trim((string) $__row['username']);
			}
		}
	}
} else {
	$__row = $this->db->select('employee_name, username')
		->from('tbl_responsibilities_user')
		->where('id', $__uid)
		->limit(1)
		->get()
		->row_array();
	if (!empty($__row)) {
		$__display_name = trim((string) $__row['employee_name']);
		if ($__display_name === '') {
			$__display_name = trim((string) $__row['username']);
		}
	}
}
if ($__display_name === '') {
	$__display_name = trim((string) $this->session->userdata('name'));
}
if ($__display_name === '') {
	$__display_name = $__uname;
}
if ($__display_name !== '') {
	$this->session->set_userdata('name', $__display_name);
}
$__page_title = isset($title) ? $title : '';
$__admin_name = trim((string) $this->session->userdata('admininfo_name'));
if ($__admin_name === '') {
	$__admin_name = 'Billing System';
}
$__logout_user = $__display_name !== '' ? $__display_name : $__uname;
$__avatar_relative = 'assets/avatars/avatar.png';
$__avatar_dir = FCPATH . 'uploads/profile/';
$__avatar_key = preg_replace('/[^a-z0-9_-]/i', '', strtolower((string) $this->session->userdata('usertype'))) . '_' . (int) $__uid;
$__avatar_mtime = 0;
foreach (array('jpg', 'jpeg', 'png', 'gif', 'webp') as $__avatar_ext) {
	$__avatar_abs = $__avatar_dir . $__avatar_key . '.' . $__avatar_ext;
	if (is_file($__avatar_abs)) {
		$__avatar_relative = 'uploads/profile/' . $__avatar_key . '.' . $__avatar_ext;
		$__avatar_mtime = @filemtime($__avatar_abs);
		break;
	}
}
if (!$__avatar_mtime) {
	$__default_abs = FCPATH . $__avatar_relative;
	$__avatar_mtime = @filemtime($__default_abs) ?: time();
}
$__avatar_url = base_url($__avatar_relative) . '?v=' . $__avatar_mtime;
if (!isset($roleResponsible) || !is_array($roleResponsible)) {
	$roleResponsible = array();
}
$sa4 = base_url() . 'sa4/';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo htmlspecialchars($__admin_name, ENT_QUOTES, 'UTF-8'); ?><?php echo $__page_title !== '' ? ' - ' . htmlspecialchars($__page_title, ENT_QUOTES, 'UTF-8') : ''; ?></title>
		<meta name="description" content="<?php echo htmlspecialchars($__admin_name, ENT_QUOTES, 'UTF-8'); ?> Billing System">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="msapplication-tap-highlight" content="no">
		<meta name="mobile-web-app-capable" content="yes">

		<link id="vendorsbundle" rel="stylesheet" media="screen, print" href="<?php echo $sa4; ?>css/vendors.bundle.css">
		<link id="appbundle" rel="stylesheet" media="screen, print" href="<?php echo $sa4; ?>css/app.bundle.css">
		<link id="myskin" rel="stylesheet" media="screen, print" href="<?php echo $sa4; ?>css/skins/skin-master.css">
		<link rel="stylesheet" media="screen, print" href="<?php echo $sa4; ?>css/legacy-bridge.css">

		<link rel="shortcut icon" href="<?php echo base_url(); ?>img/pmroxas-logo.png" type="image/png">
		<link rel="icon" href="<?php echo base_url(); ?>img/pmroxas-logo.png" type="image/png">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>img/pmroxas-logo.png">

		<script>
			function showSpinner() {
				var el = document.getElementById("spinner-overlay");
				if (el) { el.style.display = "flex"; }
			}
			function hideSpinner() {
				var el = document.getElementById("spinner-overlay");
				if (el) { el.style.display = "none"; }
			}
		</script>
	</head>
	<body class="mod-bg-1 mod-nav-link mod-skin-light header-function-fixed nav-function-fixed">
		<script>
			'use strict';
			var classHolder = document.getElementsByTagName("BODY")[0],
				themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) : {},
				themeURL = themeSettings.themeURL || '',
				themeOptions = themeSettings.themeOptions || '';
			if (themeSettings.themeOptions) {
				// Restore saved layout + skin (light/dark)
				classHolder.className = themeSettings.themeOptions;
			} else {
				classHolder.className = 'mod-bg-1 mod-nav-link mod-skin-light header-function-fixed nav-function-fixed';
			}
			// Ensure one of the SA4 skins is always present (default light)
			if (classHolder.className.indexOf('mod-skin-dark') === -1 && classHolder.className.indexOf('mod-skin-light') === -1) {
				classHolder.className += ' mod-skin-light';
			}
			if (themeSettings.themeURL && !document.getElementById('mytheme')) {
				var cssfile = document.createElement('link');
				cssfile.id = 'mytheme';
				cssfile.rel = 'stylesheet';
				cssfile.href = themeURL;
				document.getElementsByTagName('head')[0].appendChild(cssfile);
			} else if (themeSettings.themeURL && document.getElementById('mytheme')) {
				document.getElementById('mytheme').href = themeSettings.themeURL;
			}
			var saveSettings = function() {
				themeSettings.themeOptions = String(classHolder.className).split(/[^\w-]+/).filter(function(item) {
					return /^(nav|header|footer|mod|display)-/i.test(item);
				}).join(' ');
				if (document.getElementById('mytheme')) {
					themeSettings.themeURL = document.getElementById('mytheme').getAttribute("href");
				}
				localStorage.setItem('themeSettings', JSON.stringify(themeSettings));
			};
			saveSettings();
			var resetSettings = function() {
				localStorage.setItem("themeSettings", "");
			};
		</script>

		<div id="spinner-overlay" class="spinner-overlay">
			<div class="spinner"></div>
		</div>

		<div class="page-wrapper">
			<div class="page-inner">
				<?php include __DIR__ . '/navigation.php'; ?>

				<div class="page-content-wrapper">
					<header class="page-header" role="banner">
						<div class="page-logo">
							<a href="<?php echo ADMIN_URL; ?>dashboard/" class="page-logo-link press-scale-down d-flex align-items-center position-relative">
								<img src="<?php echo base_url(); ?>img/pmroxas-logo.png" alt="Logo" aria-roledescription="logo" style="width:32px;height:32px;">
								<span class="page-logo-text mr-1"><?php echo htmlspecialchars($__admin_name, ENT_QUOTES, 'UTF-8'); ?></span>
							</a>
						</div>
						<div class="hidden-md-down dropdown-icon-menu position-relative">
							<a href="#" class="header-btn btn js-waves-off" data-action="toggle" data-class="nav-function-hidden" title="Hide Navigation">
								<i class="ni ni-menu"></i>
							</a>
							<ul>
								<li>
									<a href="#" class="btn js-waves-off" data-action="toggle" data-class="nav-function-minify" title="Minify Navigation">
										<i class="ni ni-minify-nav"></i>
									</a>
								</li>
								<li>
									<a href="#" class="btn js-waves-off" data-action="toggle" data-class="nav-function-fixed" title="Lock Navigation">
										<i class="ni ni-lock-nav"></i>
									</a>
								</li>
							</ul>
						</div>
						<div class="hidden-lg-up">
							<a href="#" class="header-btn btn press-scale-down" data-action="toggle" data-class="mobile-nav-on">
								<i class="ni ni-menu"></i>
							</a>
						</div>
						<div class="ml-auto d-flex align-items-center">
							<a href="#" class="header-icon" data-action="app-fullscreen" title="Full Screen">
								<i class="fal fa-expand"></i>
							</a>
							<div>
								<a href="#" data-toggle="dropdown" title="Account" class="header-icon d-flex align-items-center justify-content-center ml-2">
								<img src="<?php echo htmlspecialchars($__avatar_url, ENT_QUOTES, 'UTF-8'); ?>" class="profile-image rounded-circle" alt="User" style="width:32px;height:32px;object-fit:cover;">
								</a>
								<div class="dropdown-menu dropdown-menu-animated dropdown-menu-right">
									<div class="dropdown-header bg-trans-gradient d-flex flex-row py-4 rounded-top">
										<div class="d-flex flex-row align-items-center mt-1 mb-1 color-white">
											<span class="mr-2">
											<img src="<?php echo htmlspecialchars($__avatar_url, ENT_QUOTES, 'UTF-8'); ?>" class="rounded-circle profile-image" alt="User" style="width:40px;height:40px;object-fit:cover;">
											</span>
											<div class="info-card-text">
												<div class="fs-lg text-truncate text-truncate-lg"><?php echo htmlspecialchars($__logout_user, ENT_QUOTES, 'UTF-8'); ?></div>
												<span class="text-truncate text-truncate-md opacity-80"><?php echo htmlspecialchars((string)$this->session->userdata('usertype'), ENT_QUOTES, 'UTF-8'); ?></span>
											</div>
										</div>
									</div>
									<div class="dropdown-divider m-0"></div>
								<a href="<?php echo ADMIN_URL; ?>profile/" class="dropdown-item">
									<span>Profile</span>
								</a>
									<a href="<?php echo ADMIN_URL; ?>change_password/" class="dropdown-item">
										<span>Change Password</span>
									</a>
									<a href="<?php echo ADMIN_URL; ?>change_username/" class="dropdown-item">
										<span>Change Username</span>
									</a>
									<div class="dropdown-divider m-0"></div>
									<a href="javascript:void(0);" class="dropdown-item" id="btn-skin-light" data-action="toggle-replace" data-replaceclass="mod-skin-dark" data-class="mod-skin-light" data-themesave>
										<span><i class="fal fa-sun mr-1"></i> Light Mode</span>
										<span class="float-right"><i class="fal fa-check-circle text-success js-skin-light-check"></i></span>
									</a>
									<a href="javascript:void(0);" class="dropdown-item" id="btn-skin-dark" data-action="toggle-replace" data-replaceclass="mod-skin-light" data-class="mod-skin-dark" data-themesave>
										<span><i class="fal fa-moon mr-1"></i> Dark Mode</span>
										<span class="float-right"><i class="fal fa-check-circle text-success js-skin-dark-check d-none"></i></span>
									</a>
									<div class="dropdown-divider m-0"></div>
									<a class="dropdown-item fw-500 pt-3 pb-3" href="<?php echo site_url(); ?>master/logout" data-logout-user="<?php echo htmlspecialchars($__logout_user, ENT_QUOTES, 'UTF-8'); ?>">
										<span data-i18n="drpdwn.page-logout">Logout</span>
										<span class="float-right fw-n">&commat;<?php echo htmlspecialchars($__uname, ENT_QUOTES, 'UTF-8'); ?></span>
									</a>
								</div>
							</div>
						</div>
					</header>
					<!-- Page views render below; footer closes wrappers -->
