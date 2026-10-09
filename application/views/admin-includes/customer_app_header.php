<?php
date_default_timezone_set('Asia/Manila');
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

/*
 * Customer Statement of Account app shell (Labason Water District).
 * Pair with customer_app_footer.php. Expects (all optional):
 *   $page_title, $app_active ('dashboard'|'soa'|'pay'), $app_title, $app_subtitle,
 *   $app_brand_title (shows the logo header above the page), $app_customer_id,
 *   $app_customer_name, $app_is_staff, $app_shell_class
 */
$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };

$app_org = 'Labason Water District';
$app_org_short = 'LWD';
$app_seal = base_url() . 'img/soa/lwd-seal.png';
$app_logo = site_url() . 'images/mroxas-logo-report.jpg';

$page_title = isset($page_title) && trim((string) $page_title) !== '' ? trim((string) $page_title) : 'Statement of Account - Water Billing System';
$app_active = isset($app_active) ? (string) $app_active : '';
$app_title = isset($app_title) ? (string) $app_title : 'Statement of Account';
$app_subtitle = isset($app_subtitle) ? (string) $app_subtitle : $app_org;
$app_brand_title = isset($app_brand_title) ? (string) $app_brand_title : '';
$app_customer_id = isset($app_customer_id) ? (string) $app_customer_id : '';
$app_customer_name = isset($app_customer_name) ? (string) $app_customer_name : '';
$app_is_staff = !empty($app_is_staff);
$app_shell_class = isset($app_shell_class) ? (string) $app_shell_class : '';

$app_base = base_url() . 'master/statementofaccount/';
$app_cid = rawurlencode($app_customer_id);
$app_links = array(
	'dashboard' => $app_base . 'index/' . $app_cid,
	'soa' => $app_base . 'soa/' . $app_cid,
	'pay' => $app_base . 'pay/' . $app_cid,
	'pdf' => $app_base . 'pdf/' . $app_cid,
	'print' => $app_base . 'soa/' . $app_cid . '?print=1',
	'logout' => $app_base . 'logout',
);

$app_initials = '';
foreach (preg_split('/[\s,]+/', trim($app_customer_name)) as $part) {
	if ($part !== '' && strlen($app_initials) < 2) {
		$app_initials .= strtoupper(substr($part, 0, 1));
	}
}
if ($app_initials === '') { $app_initials = 'C'; }

$app_sw_path = parse_url(base_url(), PHP_URL_PATH);
$app_sw_scope = (is_string($app_sw_path) ? rtrim($app_sw_path, '/') : '') . '/master/statementofaccount/';

$app_nav = array(
	'dashboard' => array('Dashboard', 'fa-tachometer', 'Home'),
	'soa' => array('Statement of Account', 'fa-file-text-o', 'SOA'),
	'pay' => array('Online Pay', 'fa-qrcode', 'Pay'),
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $h($page_title); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta name="description" content="<?php echo $h($app_org); ?> customer Statement of Account">
	<meta name="theme-color" content="#063f66">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="default">
	<meta name="apple-mobile-web-app-title" content="<?php echo $h($app_org_short); ?> Statement">
	<link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>img/soa/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url(); ?>img/soa/icon-192.png">
	<link rel="manifest" href="<?php echo base_url(); ?>manifest.json">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/soa-app.css?v=1">
	<script>
		if ('serviceWorker' in navigator) {
			window.addEventListener('load', function () {
				navigator.serviceWorker.register('<?php echo base_url(); ?>sw.js', { scope: '<?php echo $app_sw_scope; ?>' }).catch(function () {});
			});
		}
	</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script>
		if (!window.jQuery) {
			document.write('<script src="<?php echo base_url(); ?>js/libs/jquery-2.1.1.min.js"><\/script>');
		}
	</script>
</head>
<body class="app-body">
<div class="app-shell <?php echo $h($app_shell_class); ?>" id="app_shell"
	data-reset-url="<?php echo $h($app_base . 'reset_password'); ?>"
	data-login-url="<?php echo $h($app_base . 'search'); ?>"
	data-customer-id="<?php echo $h($app_customer_id); ?>">

	<aside class="app-nav" id="app_nav" aria-label="Main menu">
		<div class="app-nav-brand">
			<img src="<?php echo $h($app_seal); ?>" alt="">
			<div>
				<div class="app-nav-title"><?php echo $h($app_org); ?></div>
				<div class="app-nav-sub">Customer Statement of Account</div>
			</div>
		</div>
		<?php if ($app_customer_id !== '') { ?>
		<div class="app-nav-user">
			<div class="n"><?php echo $h($app_customer_name !== '' ? $app_customer_name : 'Customer'); ?></div>
			<div class="i">ID <?php echo $h($app_customer_id); ?><?php echo $app_is_staff ? ' &middot; staff view' : ''; ?></div>
		</div>
		<?php } ?>
		<nav class="app-nav-list">
			<div class="app-nav-section">Menu</div>
			<?php foreach ($app_nav as $key => $item) { ?>
			<a class="app-nav-link<?php echo $app_active === $key ? ' active' : ''; ?>" href="<?php echo $h($app_links[$key]); ?>">
				<i class="fa <?php echo $item[1]; ?>"></i><span><?php echo $h($item[0]); ?></span>
			</a>
			<?php } ?>
			<div class="app-nav-section">Statement</div>
			<a class="app-nav-link" href="<?php echo $h($app_links['print']); ?>"><i class="fa fa-print"></i><span>Print statement</span></a>
			<a class="app-nav-link" href="<?php echo $h($app_links['pdf']); ?>" target="_blank" rel="noopener"><i class="fa fa-file-pdf-o"></i><span>Download PDF</span></a>
			<div class="app-nav-section">Account</div>
			<?php if (!$app_is_staff) { ?>
			<button type="button" class="app-nav-link js-app-reset"><i class="fa fa-key"></i><span>Reset password</span></button>
			<?php } ?>
			<button type="button" class="app-nav-link js-app-install" style="display:none;"><i class="fa fa-download"></i><span>Install app</span></button>
			<?php if (!$app_is_staff) { ?>
			<a class="app-nav-link" href="<?php echo $h($app_links['logout']); ?>"><i class="fa fa-sign-out"></i><span>Sign out</span></a>
			<?php } ?>
		</nav>
		<div class="app-nav-foot">&copy; <?php echo date('Y'); ?> <?php echo $h($app_org); ?></div>
	</aside>
	<button type="button" class="app-nav-backdrop" id="app_nav_backdrop" aria-label="Close menu"></button>

	<div class="app-main">
		<header class="app-topbar">
			<button type="button" class="app-icon-btn app-lg-hide js-app-menu" aria-label="Open menu"><i class="fa fa-bars"></i></button>
			<img class="app-topbar-seal app-lg-hide" src="<?php echo $h($app_seal); ?>" alt="">
			<div class="app-topbar-title">
				<div class="t"><?php echo $h($app_title); ?></div>
				<div class="s"><?php echo $h($app_subtitle); ?></div>
			</div>
			<?php if ($app_is_staff) { ?><span class="app-staff-chip">Staff view</span><?php } ?>
			<?php if ($app_customer_id !== '') { ?>
			<span class="app-avatar" title="<?php echo $h($app_customer_name); ?>"><?php echo $h($app_initials); ?></span>
			<?php } ?>
		</header>
		<div class="app-offline" id="app_offline"><i class="fa fa-wifi"></i> You are offline. Figures shown may be out of date.</div>
		<main class="app-content">
			<?php if ($app_brand_title !== '') { ?>
			<div class="app-brand-head">
				<img src="<?php echo $h($app_logo); ?>" alt="<?php echo $h($app_org); ?> logo">
				<div class="t"><?php echo $h($app_brand_title); ?></div>
			</div>
			<?php } ?>
