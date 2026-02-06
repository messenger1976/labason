<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
/**
 * Excel Helpers
 * Generates Excel (.xls) format using HTML table (Excel-compatible)
 * @author		System Development Team
 * @version		1.0
 * @package		Water Billing System
 * @subpackage  Excel Helpers
 */
// ------------------------------------------------------------------------

/**
 * Array to Excel (HTML format)
 *
 * Converts array data to Excel-compatible HTML table format
 * Excel can open HTML files with .xls extension
 *
 * @param array $array Data array (each element is a row)
 * @param string $download Filename for download (empty string to return HTML)
 * @return string|void Returns HTML string if $download is empty, otherwise outputs file
 */
if ( ! function_exists('array_to_excel'))
{
	function array_to_excel($array, $download = "")
	{
		if ($download != "")
		{	
			// Clean any previous output buffers
			while (ob_get_level()) {
				@ob_end_clean();
			}
			
			// Prevent any output before headers
			if (headers_sent($file, $line)) {
				die("Headers already sent in $file on line $line. Cannot send Excel file.");
			}
			
			// Set proper headers for Excel download
			$filename_clean = str_replace(array('"', "\r", "\n"), '', $download);
			// Ensure .xls extension
			if (substr(strtolower($filename_clean), -4) !== '.xls') {
				$filename_clean = preg_replace('/\.[^.]+$/', '', $filename_clean) . '.xls';
			}
			
			@header('Content-Type: application/vnd.ms-excel');
			@header('Content-Disposition: attachment; filename="' . $filename_clean . '"');
			@header('Content-Description: File Transfer');
			@header('Content-Transfer-Encoding: binary');
			@header('Cache-Control: must-revalidate, post-check=0, pre-check=0, private');
			@header('Pragma: private');
			@header('Expires: 0');
			@header('X-Content-Type-Options: nosniff');
			
			// Disable output buffering for streaming
			if (ob_get_level()) {
				@ob_end_clean();
			}
		}
		
		// Start HTML table with Excel-compatible format
		$html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
		$html .= '<head>';
		$html .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
		$html .= '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Sheet1</x:Name></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
		$html .= '<style>td { mso-number-format:"\@"; } .text { mso-number-format:"\@"; } .number { mso-number-format:"#,##0.00"; }</style>';
		$html .= '</head>';
		$html .= '<body>';
		$html .= '<table border="1" cellpadding="2" cellspacing="0">';
		
		$n = 0;
		foreach ($array as $line)
		{
			$n++;
			$html .= '<tr>';
			
			if (is_array($line)) {
				foreach ($line as $cell) {
					// Determine if cell is numeric (for formatting)
					$cell_value = $cell;
					// Remove formatting characters and check if numeric
					$cell_clean = str_replace(array(',', ' ', '$', '₱'), '', $cell_value);
					$is_numeric = is_numeric($cell_clean) && $cell_clean !== '' && $cell_value !== '';
					
					// Clean cell value for HTML
					$cell_value = htmlspecialchars($cell_value, ENT_QUOTES, 'UTF-8');
					
					// Apply number format if numeric (but keep original formatting)
					$class = $is_numeric ? 'number' : 'text';
					
					$html .= '<td class="' . $class . '">' . $cell_value . '</td>';
				}
			} else {
				// Single cell row
				$cell_value = htmlspecialchars($line, ENT_QUOTES, 'UTF-8');
				$html .= '<td class="text">' . $cell_value . '</td>';
			}
			
			$html .= '</tr>';
			
			// Flush output every 100 rows to prevent timeout
			if ($download != "" && $n % 100 == 0) {
				echo $html;
				$html = '';
				flush();
				if (function_exists('ob_flush')) {
					@ob_flush();
				}
			}
		}
		
		$html .= '</table>';
		$html .= '</body>';
		$html .= '</html>';
		
		if ($download != "")
		{
			// Output remaining HTML
			if ($html) {
				echo $html;
			}
			
			// Final flush
			flush();
			if (function_exists('ob_flush')) {
				@ob_flush();
			}
			
			// Exit to prevent any further output
			exit;
		}
		else
		{
			// Return HTML string
			return $html;
		}
	}
}

/* End of file excel_helper.php */
/* Location: ./application/helpers/excel_helper.php */
