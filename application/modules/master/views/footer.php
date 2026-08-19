					<footer class="page-footer" role="contentinfo">
						<div class="d-flex align-items-center flex-1 text-muted">
							<span class="hidden-md-down fw-700">
								<?php echo htmlspecialchars(trim((string)$this->session->userdata('admininfo_name')) ?: 'Billing System', ENT_QUOTES, 'UTF-8'); ?>
								&copy; <?php echo date('Y'); ?>
								<span class="hidden-sm-down"> · <?php echo date('Y-m-d H:i:s'); ?> (<?php echo date_default_timezone_get(); ?>)</span>
							</span>
						</div>
					</footer>
					<!-- Color profile refs used by SA4 app.bundle.js (rgb2hex) -->
					<p id="js-color-profile" class="d-none">
						<span class="color-primary-50"></span>
						<span class="color-primary-100"></span>
						<span class="color-primary-200"></span>
						<span class="color-primary-300"></span>
						<span class="color-primary-400"></span>
						<span class="color-primary-500"></span>
						<span class="color-primary-600"></span>
						<span class="color-primary-700"></span>
						<span class="color-primary-800"></span>
						<span class="color-primary-900"></span>
						<span class="color-info-50"></span>
						<span class="color-info-100"></span>
						<span class="color-info-200"></span>
						<span class="color-info-300"></span>
						<span class="color-info-400"></span>
						<span class="color-info-500"></span>
						<span class="color-info-600"></span>
						<span class="color-info-700"></span>
						<span class="color-info-800"></span>
						<span class="color-info-900"></span>
						<span class="color-danger-50"></span>
						<span class="color-danger-100"></span>
						<span class="color-danger-200"></span>
						<span class="color-danger-300"></span>
						<span class="color-danger-400"></span>
						<span class="color-danger-500"></span>
						<span class="color-danger-600"></span>
						<span class="color-danger-700"></span>
						<span class="color-danger-800"></span>
						<span class="color-danger-900"></span>
						<span class="color-warning-50"></span>
						<span class="color-warning-100"></span>
						<span class="color-warning-200"></span>
						<span class="color-warning-300"></span>
						<span class="color-warning-400"></span>
						<span class="color-warning-500"></span>
						<span class="color-warning-600"></span>
						<span class="color-warning-700"></span>
						<span class="color-warning-800"></span>
						<span class="color-warning-900"></span>
						<span class="color-success-50"></span>
						<span class="color-success-100"></span>
						<span class="color-success-200"></span>
						<span class="color-success-300"></span>
						<span class="color-success-400"></span>
						<span class="color-success-500"></span>
						<span class="color-success-600"></span>
						<span class="color-success-700"></span>
						<span class="color-success-800"></span>
						<span class="color-success-900"></span>
						<span class="color-fusion-50"></span>
						<span class="color-fusion-100"></span>
						<span class="color-fusion-200"></span>
						<span class="color-fusion-300"></span>
						<span class="color-fusion-400"></span>
						<span class="color-fusion-500"></span>
						<span class="color-fusion-600"></span>
						<span class="color-fusion-700"></span>
						<span class="color-fusion-800"></span>
						<span class="color-fusion-900"></span>
					</p>
				</div>
			</div>
		</div>
		<!-- END Page Wrapper -->

		<nav class="shortcut-menu d-none d-sm-block">
			<input type="checkbox" class="menu-open" name="menu-open" id="menu_open" />
			<label for="menu_open" class="menu-open-button">
				<span class="app-shortcut-icon d-block"></span>
			</label>
			<a href="#" class="menu-item btn" data-toggle="tooltip" data-placement="left" title="Scroll Top" data-action="scroll-top">
				<i class="fal fa-arrow-up"></i>
			</a>
			<a href="<?php echo site_url(); ?>master/logout" class="menu-item btn" data-toggle="tooltip" data-placement="left" title="Logout">
				<i class="fal fa-sign-out"></i>
			</a>
			<a href="#" class="menu-item btn" data-action="app-fullscreen" data-toggle="tooltip" data-placement="left" title="Full Screen">
				<i class="fal fa-expand"></i>
			</a>
			<a href="#" class="menu-item btn" data-action="app-print" data-toggle="tooltip" data-placement="left" title="Print page">
				<i class="fal fa-print"></i>
			</a>
		</nav>

		<script src="<?php echo base_url(); ?>sa4/js/vendors.bundle.js"></script>
		<script>
			// SA4 rgb2hex crashes when .css('color') is undefined; harden it
			(function () {
				var orig = window.rgb2hex;
				if (typeof orig === 'function') {
					window.rgb2hex = function (rgb) {
						if (!rgb || typeof rgb !== 'string') {
							return null;
						}
						try {
							return orig(rgb);
						} catch (e) {
							return null;
						}
					};
				}
			})();
		</script>
		<script src="<?php echo base_url(); ?>sa4/js/app.bundle.js"></script>
		<?php include(__DIR__ . '/partials/sa4_swal_delete.js.php'); ?>
		<?php
			$logged_in_user_name = trim((string) $this->session->userdata('name'));
			if ($logged_in_user_name === '') {
				$logged_in_user_name = trim((string) $this->session->userdata('username'));
			}
		?>
		<script>window.LOGGED_IN_USER_NAME = <?php echo json_encode($logged_in_user_name); ?>;</script>
		<script>window.REPORT_PDF_CAPTURE_URL = <?php echo json_encode(rtrim(ADMIN_URL, '/').'/reports/downloadpreviewpdf'); ?>;</script>
		<?php if (file_exists(FCPATH . 'js/logout-user-name.js')) { ?>
		<script src="<?php echo base_url(); ?>js/logout-user-name.js?v=2"></script>
		<?php } ?>
		<?php if (file_exists(FCPATH . 'js/report-pdf-preview.js')) { ?>
		<script src="<?php echo base_url(); ?>js/report-pdf-preview.js?v=1"></script>
		<?php } ?>
		<script>
			// Compatibility stub for legacy SmartAdmin 1.8 page scripts
			if (typeof window.pageSetUp !== 'function') {
				window.pageSetUp = function () {};
			}
			(function ($) {
				if ($('#js-page-content').length && typeof $.fn.smartPanel === 'function') {
					// Keep only pink close; do not inject collapse/fullscreen/ellipsis (avoids doubles)
					$('#js-page-content').smartPanel({
						closeButton: true,
						fullscreenButton: false,
						collapseButton: false,
						lockedButton: false,
						refreshButton: false,
						colorButton: false,
						resetButton: false,
						customButton: false
					});
				}

				function syncSkinChecks() {
					var isDark = document.body.classList.contains('mod-skin-dark');
					$('.js-skin-light-check').toggleClass('d-none', isDark);
					$('.js-skin-dark-check').toggleClass('d-none', !isDark);
				}

				syncSkinChecks();
				$('#btn-skin-light, #btn-skin-dark').on('click', function () {
					setTimeout(function () {
						syncSkinChecks();
						if (typeof saveSettings === 'function') {
							saveSettings();
						}
					}, 50);
				});
			})(jQuery);
		</script>
