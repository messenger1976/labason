<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
/**
 * CSV Helpers
 * @author		Spark(Mani)
 * @copyright	Copyright (c) 2014, Sparkinfosys.com
 * @version		2.0
 * @package		ECOm
 * @subpackage  Pdf Helpers
 * @link         
 * */
// ------------------------------------------------------------------------
//Helper: application/helpers/pdf_helper.php
function tcpdf()
{
    require_once('tcpdf/examples/lang/eng.php');
    require_once('tcpdf/tcpdf.php');
}
/* End of file pdf_helper.php */
/* Location: ./application/helpers/pdf_helper.php */