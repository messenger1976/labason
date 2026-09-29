<?php
/**
 * PayMongo Setup — status screen.
 *
 * Shows whether online payments are ready to charge, the webhook URL to paste
 * into the PayMongo dashboard, the last recorded gateway failure and a
 * read-only "Test connection" button.
 *
 * Expects: $settings, $table_ready, $is_configured, $is_test_mode, $webhook_url,
 *          $masked, $last_error, $last_error_at
 */
$settings = isset($settings) && is_array($settings) ? $settings : array();
$table_ready = !empty($table_ready);
$is_configured = !empty($is_configured);
$is_test_mode = !empty($is_test_mode);
$webhook_url = isset($webhook_url) ? $webhook_url : '';
$masked = isset($masked) && is_array($masked) ? $masked : array();
$last_error = isset($last_error) ? trim((string) $last_error) : '';
$last_error_at = isset($last_error_at) ? (string) $last_error_at : '';
$enabled = isset($settings['enabled']) && (string) $settings['enabled'] === '1';
$min_amount = isset($settings['min_amount']) ? (float) $settings['min_amount'] : 20.00;
$expiry = isset($settings['link_expiry_minutes']) ? (int) $settings['link_expiry_minutes'] : 60;
$flash = $this->session->flashdata('msg_succ');
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item active">PayMongo Setup</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-qrcode"></i>
			PayMongo <span class="fw-300">Online Payment Setup</span>
		</h1>
		<div class="subheader-block d-lg-flex align-items-center">
			<a href="<?php echo ADMIN_URL; ?>paymongo_setup/edit" class="btn btn-primary waves-effect waves-themed">
				<i class="fal fa-edit mr-1"></i> Edit Settings
			</a>
		</div>
	</div>

	<?php if ($flash) { ?>
	<div class="alert alert-info alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fal fa-times"></i></span></button>
		<?php echo $flash; ?>
	</div>
	<?php } ?>

	<?php if (!$table_ready) { ?>
	<div class="alert alert-warning" role="alert">
		<i class="fal fa-exclamation-triangle mr-1"></i>
		<strong>The settings table does not exist yet.</strong>
		Run <code>sql/add_paymongo_settings.sql</code> against this database, then reload this page.
	</div>
	<?php } ?>

	<?php if ($last_error !== '') { ?>
	<div class="alert alert-danger" role="alert">
		<i class="fal fa-bug mr-1"></i>
		<strong>Last gateway failure<?php echo $last_error_at !== '' ? ' (' . htmlspecialchars($last_error_at, ENT_QUOTES, 'UTF-8') . ')' : ''; ?>:</strong>
		<div class="mt-1"><?php echo htmlspecialchars($last_error, ENT_QUOTES, 'UTF-8'); ?></div>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-lg-4 col-md-6">
			<div class="card mb-3 <?php echo $enabled ? 'border-success' : 'border-secondary'; ?>">
				<div class="card-body">
					<span class="fw-300 fs-xs d-block opacity-50"><small>ONLINE PAYMENTS</small></span>
					<span class="fw-500 fs-xl d-block <?php echo $enabled ? 'text-success' : 'text-muted'; ?>">
						<?php echo $enabled ? 'Enabled' : 'Disabled'; ?>
					</span>
					<small class="text-muted">Master switch for QR Ph payments (mobile and backend).</small>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6">
			<div class="card mb-3 <?php echo $is_test_mode ? 'border-warning' : 'border-info'; ?>">
				<div class="card-body">
					<span class="fw-300 fs-xs d-block opacity-50"><small>KEY MODE</small></span>
					<span class="fw-500 fs-xl d-block <?php echo $is_test_mode ? 'text-warning' : 'text-info'; ?>">
						<?php echo isset($masked['secret_key']) && $masked['secret_key'] !== ''
							? ($is_test_mode ? 'Test (sk_test_)' : 'Live (sk_live_)')
							: 'No key saved'; ?>
					</span>
					<small class="text-muted">Test mode simulates payments. Live mode moves real money.</small>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6">
			<div class="card mb-3 <?php echo $is_configured ? 'border-success' : 'border-danger'; ?>">
				<div class="card-body">
					<span class="fw-300 fs-xs d-block opacity-50"><small>READY TO CHARGE</small></span>
					<span class="fw-500 fs-xl d-block <?php echo $is_configured ? 'text-success' : 'text-danger'; ?>">
						<?php echo $is_configured ? 'Yes' : 'No'; ?>
					</span>
					<small class="text-muted">Needs the switch <em>on</em> and a secret key saved.</small>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xl-7">
			<div class="panel mb-3">
				<div class="panel-hdr">
					<h2>Credentials <span class="fw-300"><i>masked</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content p-0">
						<table class="table table-sm table-striped mb-0">
							<tbody>
								<tr>
									<th style="width:38%;">Public key</th>
									<td><code><?php echo $masked['public_key'] !== '' ? htmlspecialchars($masked['public_key'], ENT_QUOTES, 'UTF-8') : '— not set —'; ?></code></td>
								</tr>
								<tr>
									<th>Secret key</th>
									<td><code><?php echo $masked['secret_key'] !== '' ? htmlspecialchars($masked['secret_key'], ENT_QUOTES, 'UTF-8') : '— not set —'; ?></code></td>
								</tr>
								<tr>
									<th>Webhook secret</th>
									<td><code><?php echo $masked['webhook_secret'] !== '' ? htmlspecialchars($masked['webhook_secret'], ENT_QUOTES, 'UTF-8') : '— not set —'; ?></code></td>
								</tr>
								<tr>
									<th>Minimum charge</th>
									<td>₱ <?php echo number_format($min_amount, 2); ?> <small class="text-muted">(PayMongo's own floor is ₱20.00)</small></td>
								</tr>
								<tr>
									<th>Emailed link lifetime</th>
									<td><?php echo (int) $expiry; ?> minutes</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="panel-content border-top">
						<div class="d-flex flex-wrap align-items-center">
							<button type="button" id="btn_test_connection" class="btn btn-outline-primary waves-effect waves-themed mr-2 mb-1">
								<i class="fal fa-plug mr-1"></i> Test connection
							</button>
							<a href="<?php echo ADMIN_URL; ?>paymongo_setup/edit" class="btn btn-secondary waves-effect waves-themed mb-1">
								<i class="fal fa-edit mr-1"></i> Edit
							</a>
							<small class="text-muted ml-2">Testing only reads from PayMongo — it creates nothing.</small>
						</div>
						<div id="test_result" class="mt-2"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-5">
			<div class="panel mb-3">
				<div class="panel-hdr">
					<h2>Webhook <span class="fw-300"><i>PayMongo &rarr; this app</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<p class="mb-2">
							Paste this URL in <strong>PayMongo &rarr; Developers &rarr; Webhooks</strong>, subscribed to
							<code>payment.paid</code>.
						</p>
						<div class="input-group">
							<input type="text" class="form-control" id="webhook_url" value="<?php echo htmlspecialchars($webhook_url, ENT_QUOTES, 'UTF-8'); ?>" readonly>
							<div class="input-group-append">
								<button class="btn btn-outline-secondary" type="button" id="btn_copy_webhook">
									<i class="fal fa-copy"></i>
								</button>
							</div>
						</div>
						<div class="alert alert-warning mt-3 mb-0" role="alert">
							<i class="fal fa-info-circle mr-1"></i>
							<strong>Webhooks cannot reach a local XAMPP install.</strong>
							On localhost, settle a payment with the <em>Check status</em> button on the Online Payment or
							Payment pages. The webhook is the production path.
						</div>
					</div>
				</div>
			</div>

			<div class="panel mb-3">
				<div class="panel-hdr">
					<h2>Who can use this <span class="fw-300"><i>permission keys</i></span></h2>
				</div>
				<div class="panel-container show">
					<div class="panel-content p-0">
						<table class="table table-sm table-striped mb-0">
							<thead>
								<tr>
									<th>Key</th>
									<th>Where</th>
								</tr>
							</thead>
							<tbody>
								<tr><td><code>paymongo_setup</code></td><td>Settings &rarr; PayMongo Setup (this screen)</td></tr>
								<tr><td><code>onlinepayment</code></td><td>Finance &rarr; Online Payment</td></tr>
								<tr><td><code>mobile_payment</code></td><td>Mobile app &rarr; Payment</td></tr>
								<tr><td><code>online_payment_report</code></td><td>Reports &rarr; Online / QR Ph Payment</td></tr>
							</tbody>
						</table>
					</div>
					<div class="panel-content border-top">
						<small class="text-muted">
							Admin always has access. Tick the keys on a role in
							<a href="<?php echo ADMIN_URL; ?>responsibilities">Roles &amp; Responsibilities</a>.
							Keys need the columns from <code>sql/add_paymongo_permissions.sql</code>.
						</small>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<script>
	(function () {
		'use strict';

		var btnCopy = document.getElementById('btn_copy_webhook');
		if (btnCopy) {
			btnCopy.addEventListener('click', function () {
				var input = document.getElementById('webhook_url');
				if (!input) { return; }
				input.select();
				input.setSelectionRange(0, 99999);
				try {
					if (navigator.clipboard) {
						navigator.clipboard.writeText(input.value);
					} else {
						document.execCommand('copy');
					}
					btnCopy.innerHTML = '<i class="fal fa-check"></i>';
					setTimeout(function () { btnCopy.innerHTML = '<i class="fal fa-copy"></i>'; }, 1500);
				} catch (e) {
					/* selection is left in place so the operator can copy manually */
				}
			});
		}

		var btnTest = document.getElementById('btn_test_connection');
		if (btnTest) {
			btnTest.addEventListener('click', function () {
				var out = document.getElementById('test_result');
				btnTest.disabled = true;
				out.innerHTML = '<div class="text-muted"><i class="fal fa-spinner fa-spin mr-1"></i> Checking with PayMongo…</div>';

				fetch('<?php echo ADMIN_URL; ?>paymongo_setup/test_connection', {
					method: 'POST',
					headers: { 'X-Requested-With': 'XMLHttpRequest' },
					credentials: 'same-origin'
				})
				.then(function (r) { return r.json(); })
				.then(function (json) {
					var cls = json.ok ? 'alert-success' : 'alert-danger';
					out.innerHTML = '<div class="alert ' + cls + ' mb-0" role="alert">'
						+ '<i class="fal ' + (json.ok ? 'fa-check-circle' : 'fa-exclamation-triangle') + ' mr-1"></i> '
						+ json.message + '</div>';
				})
				.catch(function () {
					out.innerHTML = '<div class="alert alert-danger mb-0" role="alert">The check could not be completed in this browser. Reload and try again.</div>';
				})
				.then(function () { btnTest.disabled = false; });
			});
		}
	})();
</script>

<?php include('footer.php'); ?>
