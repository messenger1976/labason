<!-- PAGE FOOTER -->
		
		<!-- END PAGE FOOTER -->
		
		<!--================================================== -->

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices).
		     The path MUST go through base_url(): a relative src resolves against the current URL, so on a deep
		     page like /master/mobile_payment/success/7 it asked for a file that does not exist, the server
		     answered with an HTML 404 page and the browser reported "Unexpected token '<'". -->
		<script data-pace-options='{ "restartOnRequestAfter": true }' src="<?php echo base_url();?>js/plugin/pace/pace.min.js"></script>

		<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local.
		     MUST be https: the app is served over https, and a http script is
		     blocked outright by the browser as mixed active content. -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="<?php echo base_url();?>js/libs/jquery-2.1.1.min.js"><\/script>');
			}
		</script>

		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="<?php echo base_url();?>js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>

		<!-- IMPORTANT: APP CONFIG -->
		<script src="<?php echo base_url();?>js/app.config.js"></script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
		<script src="<?php echo base_url();?>js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 

		<!-- BOOTSTRAP JS -->
		<script src="<?php echo base_url();?>js/bootstrap/bootstrap.min.js"></script>

		<!-- CUSTOM NOTIFICATION -->
		<script src="<?php echo base_url();?>js/notification/SmartNotification.min.js"></script>

		<!-- JARVIS WIDGETS -->
		<script src="<?php echo base_url();?>js/smartwidgets/jarvis.widget.min.js"></script>

		<!-- SPARKLINES -->
		<script src="<?php echo base_url();?>js/plugin/sparkline/jquery.sparkline.min.js"></script>

		<!-- browser msie issue fix -->
		<script src="<?php echo base_url();?>js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

		<!-- FastClick: For mobile devices -->
		<script src="<?php echo base_url();?>js/plugin/fastclick/fastclick.min.js"></script>

		<!-- FastClick: For mobile devices -->
		<script src="<?php echo base_url();?>js/plugin/fastclick/fastclick.min.js"></script>
		<script src="<?php echo base_url();?>js/select2.min.js"></script>
		<!--[if IE 8]>

		<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

		<![endif]-->

		<!-- Demo purpose only -->
		<script src="<?php echo base_url();?>js/demo.min.js"></script>

		<!-- MAIN APP JS FILE -->
		<script src="<?php echo base_url();?>js/app.min.js"></script>
		<?php
			$logged_in_user_name = trim((string) $this->session->userdata('name'));
			if ($logged_in_user_name === '') {
				$logged_in_user_name = trim((string) $this->session->userdata('username'));
			}
		?>
		<script>window.LOGGED_IN_USER_NAME = <?php echo json_encode($logged_in_user_name); ?>;</script>
		<script src="<?php echo base_url();?>js/logout-user-name.js?v=2"></script>

		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="<?php echo base_url();?>js/speech/voicecommand.min.js"></script>

		<!-- SmartChat UI : plugin -->
		<script src="<?php echo base_url();?>js/smart-chat-ui/smart.chat.ui.min.js"></script>
		<script src="<?php echo base_url();?>js/smart-chat-ui/smart.chat.manager.min.js"></script>
		
		<!-- PAGE RELATED PLUGIN(S) -->
		
		
		