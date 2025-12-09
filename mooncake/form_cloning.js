
/*
 * MoonCake v1.3.1 - Form Cloning Demo JS
 *
 * This file is part of MoonCake, an Admin template build for sale at ThemeForest.
 * For questions, suggestions or support request, please mail me at maimairel@yahoo.com
 *
 * Development Started:
 * July 28, 2012
 * Last Update:
 * December 07, 2012
 *
 */

;(function( $, window, document, undefined ) {
			
	$(document).ready(function() {
		if( $.fn.sheepIt ) {

			$('#input_cloning').sheepIt({
				separator: '', 
				maxFormsCount: 5
			});

			$('#oinput_cloning').sheepIt({
				separator: '', 
				maxFormsCount: 5
			});
			$('#ainput_cloning').sheepIt({
				separator: '', 
				maxFormsCount: 5
			});
		}
	});
	
	
}) (jQuery, window, document);