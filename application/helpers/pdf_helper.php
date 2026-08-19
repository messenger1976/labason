<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
/**
 * PDF Helpers
 * @author		Spark(Mani)
 * @copyright	Copyright (c) 2014, Sparkinfosys.com
 * @version		2.0
 * @package		ECOm
 * @subpackage  Pdf Helpers
 * */
// ------------------------------------------------------------------------
function tcpdf()
{
    require_once('tcpdf/examples/lang/eng.php');
    require_once('tcpdf/tcpdf.php');
}

/**
 * Render a print view as HTML (browser print) or as an automatic PDF download.
 *
 * Export to PDF uses Chrome/Edge headless print-to-PDF. CSS and images are inlined
 * first so the PDF matches Print (file:// cannot load localhost stylesheets/logo).
 */
if ( ! function_exists('send_print_or_pdf'))
{
	function send_print_or_pdf($view, $data, $filename = '')
	{
		$CI =& get_instance();
		$download = !empty($CI->downloadPdf);
		if (!is_array($data)) {
			$data = array();
		}
		$data['pdf_export'] = $download;
		if ($download) {
			$html = $CI->load->view($view, $data, true);
			$fn = ($filename !== '') ? $filename : (isset($CI->pdfFilename) ? $CI->pdfFilename : 'report.pdf');
			output_report_html_pdf($html, $fn);
			return;
		}
		$CI->load->view($view, $data);
	}
}

if ( ! function_exists('report_pdf_safe_filename'))
{
	function report_pdf_safe_filename($filename)
	{
		$safe = preg_replace('/[^A-Za-z0-9._-]/', '_', $filename);
		if ($safe === '') {
			$safe = 'report';
		}
		if (strtolower(substr($safe, -4)) !== '.pdf') {
			$safe .= '.pdf';
		}
		return $safe;
	}
}

if ( ! function_exists('report_pdf_browser_bin'))
{
	function report_pdf_browser_bin()
	{
		$candidates = array(
			'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
			'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
			'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
			'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
		);
		foreach ($candidates as $p) {
			if (is_file($p)) {
				return $p;
			}
		}
		return '';
	}
}

if ( ! function_exists('report_pdf_rmdir'))
{
	function report_pdf_rmdir($dir)
	{
		if (!is_dir($dir)) {
			return;
		}
		$items = @scandir($dir);
		if ($items === false) {
			return;
		}
		foreach ($items as $item) {
			if ($item === '.' || $item === '..') {
				continue;
			}
			$path = $dir.DIRECTORY_SEPARATOR.$item;
			if (is_dir($path)) {
				report_pdf_rmdir($path);
			} else {
				@unlink($path);
			}
		}
		@rmdir($dir);
	}
}

if ( ! function_exists('report_pdf_local_path'))
{
	function report_pdf_local_path($url)
	{
		$path = parse_url($url, PHP_URL_PATH);
		if ($path === null || $path === false || $path === '') {
			$path = $url;
		}
		$path = urldecode($path);
		$path = str_replace('\\', '/', $path);
		$path = ltrim($path, '/');
		if ($path === '') {
			return '';
		}

		$rel = str_replace('/', DIRECTORY_SEPARATOR, $path);
		$stripped = preg_replace('#^[^/\\\\]+[\\\\/]#', '', $rel);
		$candidates = array(
			FCPATH.$rel,
			FCPATH.$stripped,
		);
		if (!empty($_SERVER['DOCUMENT_ROOT'])) {
			$candidates[] = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\').DIRECTORY_SEPARATOR.$rel;
		}

		foreach ($candidates as $p) {
			$real = @realpath($p);
			if ($real && is_file($real)) {
				$root = @realpath(FCPATH);
				$doc = !empty($_SERVER['DOCUMENT_ROOT']) ? @realpath($_SERVER['DOCUMENT_ROOT']) : false;
				if ($root && strpos($real, $root) === 0) {
					return $real;
				}
				if ($doc && strpos($real, $doc) === 0) {
					return $real;
				}
			}
		}
		return '';
	}
}

if ( ! function_exists('report_pdf_mime'))
{
	function report_pdf_mime($path)
	{
		$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
		$map = array(
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif',
			'webp' => 'image/webp',
			'svg' => 'image/svg+xml',
			'css' => 'text/css',
		);
		return isset($map[$ext]) ? $map[$ext] : 'application/octet-stream';
	}
}

/**
 * Embed stylesheets and images so Chrome print-to-PDF does not depend on localhost URLs.
 */
if ( ! function_exists('report_pdf_inline_assets'))
{
	function report_pdf_inline_assets($html)
	{
		$html = preg_replace_callback(
			'/<link\b[^>]*href=["\']([^"\']+)["\'][^>]*>/i',
			function ($m) {
				$href = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
				if (!preg_match('/\.css(\?|#|$)/i', $href)) {
					return $m[0];
				}
				$file = report_pdf_local_path($href);
				if ($file === '') {
					return $m[0];
				}
				$css = @file_get_contents($file);
				if ($css === false) {
					return $m[0];
				}
				return '<style type="text/css">'.$css.'</style>';
			},
			$html
		);

		$html = preg_replace_callback(
			'/(<img\b[^>]*\ssrc=["\'])([^"\']+)(["\'])/i',
			function ($m) {
				$src = html_entity_decode($m[2], ENT_QUOTES, 'UTF-8');
				if (stripos($src, 'data:') === 0) {
					return $m[0];
				}
				$file = report_pdf_local_path($src);
				if ($file === '') {
					return $m[0];
				}
				$bin = @file_get_contents($file);
				if ($bin === false) {
					return $m[0];
				}
				$mime = report_pdf_mime($file);
				return $m[1].'data:'.$mime.';base64,'.base64_encode($bin).$m[3];
			},
			$html
		);

		return $html;
	}
}

if ( ! function_exists('report_pdf_public_url'))
{
	function report_pdf_public_url($relPath)
	{
		$https = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off');
		$scheme = $https ? 'https' : 'http';
		$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
		$script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
		$base = rtrim(dirname($script), '/');
		if (substr($base, -4) === '.php') {
			$base = rtrim(dirname($base), '/');
		}
		if ($base === '/' || $base === '\\' || $base === '.') {
			$base = '';
		}
		return $scheme.'://'.$host.$base.'/'.ltrim(str_replace('\\', '/', $relPath), '/');
	}
}

if ( ! function_exists('report_pdf_via_chromium'))
{
	function report_pdf_via_chromium($html, $destPdf)
	{
		$bin = report_pdf_browser_bin();
		if ($bin === '' || !function_exists('exec')) {
			return false;
		}

		@set_time_limit(180);

		$html = preg_replace('/\sonLoad\s*=\s*["\']window\.print\(\)["\']/i', '', $html);
		$html = report_pdf_inline_assets($html);

		$id = 'wbpdf_'.preg_replace('/[^A-Za-z0-9]/', '', uniqid('', true));
		$webDir = FCPATH.'assets'.DIRECTORY_SEPARATOR.'tmp_pdf';
		if (!is_dir($webDir) && !@mkdir($webDir, 0755, true)) {
			$webDir = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);
		}
		$htmlFile = $webDir.DIRECTORY_SEPARATOR.$id.'.html';
		$profile = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$id.'_profile';
		if (!@mkdir($profile, 0700, true) && !is_dir($profile)) {
			return false;
		}
		if (file_put_contents($htmlFile, $html) === false) {
			report_pdf_rmdir($profile);
			return false;
		}

		$htmlUrl = report_pdf_public_url('assets/tmp_pdf/'.$id.'.html');
		if (strpos(str_replace('\\', '/', $htmlFile), 'assets/tmp_pdf') === false
			&& strpos(str_replace('\\', '/', $htmlFile), 'assets\\tmp_pdf') === false) {
			$htmlUrl = 'file:///'.str_replace('\\', '/', $htmlFile);
		}

		$cmd = escapeshellarg($bin)
			.' --headless=new --disable-gpu --no-first-run --no-default-browser-check --disable-extensions'
			.' --no-pdf-header-footer'
			.' --hide-scrollbars'
			.' --virtual-time-budget=20000'
			.' --user-data-dir='.escapeshellarg($profile)
			.' --print-to-pdf='.escapeshellarg($destPdf)
			.' '.escapeshellarg($htmlUrl);

		@exec($cmd, $out, $code);

		$ok = false;
		$deadline = microtime(true) + 60;
		while (microtime(true) < $deadline) {
			if (is_file($destPdf) && filesize($destPdf) > 80) {
				$fh = @fopen($destPdf, 'rb');
				if ($fh) {
					$magic = fread($fh, 4);
					fclose($fh);
					if ($magic === '%PDF') {
						$ok = true;
						break;
					}
				}
			}
			usleep(200000);
		}

		@unlink($htmlFile);
		report_pdf_rmdir($profile);
		return $ok;
	}
}

/**
 * Auto-download a PDF that matches Print. Falls back to window.print() if Chrome/Edge is unavailable.
 */
if ( ! function_exists('output_report_html_pdf'))
{
	function output_report_html_pdf($html, $filename, $options = array())
	{
		$safe = report_pdf_safe_filename($filename);
		$destPdf = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'wbpdf_'.preg_replace('/[^A-Za-z0-9]/', '', uniqid('', true)).'.pdf';

		if (report_pdf_via_chromium($html, $destPdf)) {
			while (ob_get_level()) {
				@ob_end_clean();
			}
			header('Content-Type: application/pdf');
			header('Content-Length: '.filesize($destPdf));
			header('Content-Disposition: attachment; filename="'.$safe.'"');
			header('Cache-Control: private, max-age=0, must-revalidate');
			header('Pragma: public');
			readfile($destPdf);
			@unlink($destPdf);
			exit;
		}

		$title = htmlspecialchars($safe, ENT_QUOTES, 'UTF-8');
		if (preg_match('/<title\b[^>]*>.*?<\/title>/is', $html)) {
			$html = preg_replace('/<title\b[^>]*>.*?<\/title>/is', '<title>'.$title.'</title>', $html, 1);
		} elseif (preg_match('/<head\b[^>]*>/i', $html)) {
			$html = preg_replace('/<head\b[^>]*>/i', '$0<title>'.$title.'</title>', $html, 1);
		}
		if (!preg_match('/onLoad\s*=\s*["\']window\.print\(\)["\']/i', $html)) {
			$html = preg_replace('/<body\b([^>]*)>/i', '<body$1 onLoad="window.print()">', $html, 1);
		}
		$hint = '<div id="pdf-export-hint" style="font-family:Arial,sans-serif;font-size:12px;background:#fff3cd;border:1px solid #ffc107;padding:8px 12px;margin:0 0 8px 0;">Automatic PDF download needs Google Chrome or Microsoft Edge on this computer. In the print dialog, set Destination to <strong>Save as PDF</strong>.</div>
<style>@media print { #pdf-export-hint { display: none !important; } }</style>
';
		if (preg_match('/<body\b[^>]*>/i', $html)) {
			$html = preg_replace('/<body\b[^>]*>/i', '$0'.$hint, $html, 1);
		} else {
			$html = $hint.$html;
		}

		while (ob_get_level()) {
			@ob_end_clean();
		}
		header('Content-Type: text/html; charset=UTF-8');
		echo $html;
		exit;
	}
}
/* End of file pdf_helper.php */
/* Location: ./application/helpers/pdf_helper.php */
