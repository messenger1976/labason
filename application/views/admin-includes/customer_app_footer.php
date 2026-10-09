<?php
/*
 * Closes the customer app shell opened by customer_app_header.php.
 * Expects the same $app_active / $app_customer_id / $app_is_staff variables.
 */
$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
$app_active = isset($app_active) ? (string) $app_active : '';
$app_customer_id = isset($app_customer_id) ? (string) $app_customer_id : '';
$app_is_staff = !empty($app_is_staff);
$app_base = base_url() . 'master/statementofaccount/';
$app_cid = rawurlencode($app_customer_id);
$app_tabs = array(
	'dashboard' => array('Home', 'fa-tachometer', $app_base . 'index/' . $app_cid),
	'soa' => array('SOA', 'fa-file-text-o', $app_base . 'soa/' . $app_cid),
	'pay' => array('Pay', 'fa-qrcode', $app_base . 'pay/' . $app_cid),
);
?>
		</main>
	</div><!-- /.app-main -->

	<nav class="app-bottom-nav" aria-label="Quick navigation">
		<?php foreach ($app_tabs as $key => $tab) { ?>
		<a class="app-bottom-link<?php echo $app_active === $key ? ' active' : ''; ?>" href="<?php echo $h($tab[2]); ?>">
			<i class="fa <?php echo $tab[1]; ?>"></i><span><?php echo $h($tab[0]); ?></span>
		</a>
		<?php } ?>
		<button type="button" class="app-bottom-link js-app-menu"><i class="fa fa-bars"></i><span>Menu</span></button>
	</nav>

	<?php if (!$app_is_staff && $app_customer_id !== '') { ?>
	<div class="app-modal" id="app_reset_modal" role="dialog" aria-modal="true" aria-labelledby="app_reset_title">
		<div class="app-modal-sheet">
			<h3 id="app_reset_title"><i class="fa fa-key"></i> Reset password</h3>
			<p>Choose a new password for customer ID <?php echo $h($app_customer_id); ?>. You will be asked to sign in again.</p>
			<form id="app_reset_form" autocomplete="off">
				<div class="app-field">
					<label for="app_new_password">New password</label>
					<input type="password" id="app_new_password" minlength="3" required>
				</div>
				<div class="app-field">
					<label for="app_confirm_password">Confirm password</label>
					<input type="password" id="app_confirm_password" minlength="3" required>
				</div>
				<div class="app-alert bad" id="app_reset_error" style="display:none;"></div>
				<div class="app-alert ok" id="app_reset_ok" style="display:none;"></div>
				<div class="app-modal-actions">
					<button type="button" class="app-btn app-btn-outline js-app-reset-cancel">Cancel</button>
					<button type="submit" class="app-btn app-btn-primary" id="app_reset_save">Reset password</button>
				</div>
			</form>
		</div>
	</div>
	<?php } ?>
</div><!-- /.app-shell -->
<script src="<?php echo base_url(); ?>js/soa-app.js?v=1"></script>
</body>
</html>
