<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('date.timezone', 'Asia/Manila');	
/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

//define('ADMIN_URL', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/master/');
//define('ADMIN_CSS_URL', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/styles/admin/styles/');
//define('ADMIN_PCSS_URL', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/styles/admin/styles/');

//define('INCHARGE_URL', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/incharge/');


//define('ADMIN_JS_URL', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/js/admin/js/');
//define('ADMIN_IMG_URL', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/images/');

//define('SMALL_IMG_URL', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/images/products/70x70/');
//define('MEDIUM_IMG_URL', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/images/products/190x190/');
//define('LARGE_IMG_URL', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/images/products/280x280/');
//define('INNER_BANNER_NO_IMG', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/images/no-images/inner-banner.jpg');
//define('INNER_ADS_NO_IMG1', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/images/no-images/inner-ads-1.jpg');
//define('INNER_ADS_NO_IMG2', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/images/no-images/inner-ads-2.jpg');

//define('HOME_BANNER_NO_IMG', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/images/no-images/inner-banner.jpg');
//define('HOME_ADS_NO_IMG1', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/images/no-images/inner-ads-2.jpg');
//define('HOME_ADS_NO_IMG2', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/images/no-images/inner-ads-2.jpg');
//define('HOME_ADS_NO_IMG3', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/images/no-images/inner-ads-2.jpg');
//define('HOME_ADS_NO_IMG4', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/images/no-images/inner-ads-2.jpg');
//define('HOME_ADS_NO_IMG5', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/images/no-images/inner-ads-2.jpg');
//define('HOME_ADS_NO_IMG6', 'http://'.$_SERVER['SERVER_NAME'].'/beta/waterbillingsystem/images/no-images/inner-ads-2.jpg');
$isSecure = false;
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $isSecure = true;
}
elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
    $isSecure = true;
}
$REQUEST_PROTOCOL = $isSecure ? 'https' : 'http';
/* end code */

$webserveruri = $REQUEST_PROTOCOL.'://'.$_SERVER['SERVER_NAME'];

define('ADMIN_URL', $webserveruri.'/master/');
define('ADMIN_CSS_URL', $webserveruri.'/styles/admin/styles/');
define('ADMIN_PCSS_URL', $webserveruri.'/styles/admin/styles/');
define('INCHARGE_URL', $webserveruri.'/incharge/');
define('ADMIN_JS_URL', $webserveruri.'/js/admin/js/');
define('ADMIN_IMG_URL', $webserveruri.'/images/');
define('SMALL_IMG_URL', $webserveruri.'/images/products/70x70/');
define('MEDIUM_IMG_URL', $webserveruri.'/images/products/190x190/');
define('LARGE_IMG_URL', $webserveruri.'/images/products/280x280/');
define('INNER_BANNER_NO_IMG', $webserveruri.'/images/no-images/inner-banner.jpg');
define('INNER_ADS_NO_IMG1', $webserveruri.'/images/no-images/inner-ads-1.jpg');
define('INNER_ADS_NO_IMG2', $webserveruri.'/images/no-images/inner-ads-2.jpg');
define('HOME_BANNER_NO_IMG', $webserveruri.'/images/no-images/inner-banner.jpg');
define('HOME_ADS_NO_IMG1', $webserveruri.'/images/no-images/inner-ads-2.jpg');
define('HOME_ADS_NO_IMG2', $webserveruri.'/images/no-images/inner-ads-2.jpg');
define('HOME_ADS_NO_IMG3', $webserveruri.'/images/no-images/inner-ads-2.jpg');
define('HOME_ADS_NO_IMG4', $webserveruri.'/images/no-images/inner-ads-2.jpg');
define('HOME_ADS_NO_IMG5', $webserveruri.'/images/no-images/inner-ads-2.jpg');
define('HOME_ADS_NO_IMG6', $webserveruri.'/images/no-images/inner-ads-2.jpg');

define('ABSOLUTE_PATH',FCPATH);
define('QRCODE_PATH', ABSOLUTE_PATH.'uploads/qr_image/');


/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */