<?php
/**
 * PayMongo Setup — settings form.
 *
 * A blank secret field keeps the stored value (so an operator can change the
 * minimum without retyping a key); "Remove" wipes it explicitly.
 *
 * Expects: $settings, $table_ready, $masked
 */
$settings = isset($settings) && is_array($settings) ? $settings : array();
$table_ready = !empty($table_ready);
$masked = isset($masked) && is_array($masked) ? $masked : array();

$enabled = isset($settings['enabled']) && (string) $settings['enabled'] === '1';
$mode = isset($settings['mode']) ? (string) $settings['mode'] : 'test';
$min_amount = isset($settings['min_amount']) ? (float) $settings['min_amount'] : 20.00;
$expiry = isset($settings['link_expiry_minutes']) ? (int) $settings['link_expiry_minutes'] : 60;
$prefix = isset($settings['description_prefix']) ? (string) $settings['description_prefix'] : 'LWD Bill Payment';
$note = isset($settings['receipt_note']) ? (string) $settings['receipt_note'] : '';

$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>paymongo_setup">PayMongo Setup</a></li>
		<li class="breadcrumb-item active">Edit</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-key"></i>
			PayMongo <span class="fw-300">Edit Settings</span>
		</h1>
	</div>

	<?php if (!$table_ready) { ?>
	<div class="alert alert-warning" role="alert">
		<i class="fal fa-exclamation-triangle mr-1"></i>
		<strong>The settings table does not exist yet.</strong>
		Run <code>sql/add_paymongo_settings.sql</code> first — saving will not work until then.
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-9">
			<div id="panel-paymongo-edit" class="panel mb-3">
				<div class="panel-hdr">
					<h2>Gateway <span class="fw-300"><i>credentials &amp; switches</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<form class="needs-validation" method="POST" action="" novalidate>
						<div class="panel-content">

							<div class="form-group row align-items-center">
								<label class="col-md-4 col-form-label fw-500">Enable online payments</label>
								<div class="col-md-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="enabled" name="enabled" value="1" <?php echo $enabled ? 'checked' : ''; ?>>
										<label class="custom-control-label" for="enabled">Allow QR Ph payments</label>
									</div>
									<small class="form-text text-muted">
										When off, QR Ph buttons hide on the mobile and Online Payment pages. Cash is unaffected.
									</small>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-4 col-form-label fw-500" for="mode">Mode</label>
								<div class="col-md-8">
									<select class="form-control" id="mode" name="mode">
										<option value="test" <?php echo $mode === 'test' ? 'selected' : ''; ?>>Test</option>
										<option value="live" <?php echo $mode === 'live' ? 'selected' : ''; ?>>Live</option>
									</select>
									<small class="form-text text-muted">
										Informational. In practice the saved <strong>secret key</strong> decides:
										<code>sk_test_</code> simulates, <code>sk_live_</code> moves real money.
									</small>
								</div>
							</div>

							<hr>

							<div class="form-group row">
								<label class="col-md-4 col-form-label fw-500" for="public_key">Public key</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="public_key" name="public_key" autocomplete="off"
										placeholder="<?php echo $masked['public_key'] !== '' ? $h($masked['public_key']) : 'pk_test_…'; ?>">
									<small class="form-text text-muted">Leave blank to keep the saved key.</small>
									<?php if ($masked['public_key'] !== '') { ?>
									<div class="custom-control custom-checkbox mt-1">
										<input type="checkbox" class="custom-control-input" id="clear_public_key" name="clear_public_key" value="1">
										<label class="custom-control-label text-danger" for="clear_public_key">Remove the saved public key</label>
									</div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-4 col-form-label fw-500" for="secret_key">Secret key <span class="text-danger">*</span></label>
								<div class="col-md-8">
									<input type="password" class="form-control" id="secret_key" name="secret_key" autocomplete="new-password"
										placeholder="<?php echo $masked['secret_key'] !== '' ? $h($masked['secret_key']) : 'sk_test_…'; ?>">
									<small class="form-text text-muted">
										Required for QR Ph to work. Never logged, never shown to a customer, never rendered in full.
									</small>
									<?php if ($masked['secret_key'] !== '') { ?>
									<div class="custom-control custom-checkbox mt-1">
										<input type="checkbox" class="custom-control-input" id="clear_secret_key" name="clear_secret_key" value="1">
										<label class="custom-control-label text-danger" for="clear_secret_key">Remove the saved secret key</label>
									</div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-4 col-form-label fw-500" for="webhook_secret">Webhook secret</label>
								<div class="col-md-8">
									<input type="password" class="form-control" id="webhook_secret" name="webhook_secret" autocomplete="new-password"
										placeholder="<?php echo $masked['webhook_secret'] !== '' ? $h($masked['webhook_secret']) : 'whsk_…'; ?>">
									<small class="form-text text-muted">
										From PayMongo → Developers → Webhooks. Used to verify the
										<code>Paymongo-Signature</code> header. Without it, signatures are not checked —
										payments are still verified against PayMongo before settling.
									</small>
									<?php if ($masked['webhook_secret'] !== '') { ?>
									<div class="custom-control custom-checkbox mt-1">
										<input type="checkbox" class="custom-control-input" id="clear_webhook_secret" name="clear_webhook_secret" value="1">
										<label class="custom-control-label text-danger" for="clear_webhook_secret">Remove the saved webhook secret</label>
									</div>
									<?php } ?>
								</div>
							</div>

							<hr>

							<div class="form-group row">
								<label class="col-md-4 col-form-label fw-500" for="min_amount">Minimum charge (₱)</label>
								<div class="col-md-8">
									<input type="number" step="0.01" min="20" class="form-control" id="min_amount" name="min_amount" value="<?php echo $h(number_format($min_amount, 2, '.', '')); ?>">
									<small class="form-text text-muted">
										PayMongo refuses anything below ₱20.00. A bill below this shows no QR button.
									</small>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-4 col-form-label fw-500" for="link_expiry_minutes">Emailed link lifetime (minutes)</label>
								<div class="col-md-8">
									<input type="number" min="5" max="1440" class="form-control" id="link_expiry_minutes" name="link_expiry_minutes" value="<?php echo (int) $expiry; ?>">
									<small class="form-text text-muted">How long a cashier's emailed payment link stays valid (5–1440).</small>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-4 col-form-label fw-500" for="description_prefix">Description prefix</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="description_prefix" name="description_prefix" value="<?php echo $h($prefix); ?>" maxlength="60">
									<small class="form-text text-muted">Shown on the PayMongo record, e.g. “LWD Bill Payment — 1234”.</small>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-4 col-form-label fw-500" for="receipt_note">Receipt note</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="receipt_note" name="receipt_note" value="<?php echo $h($note); ?>" maxlength="200">
									<small class="form-text text-muted">Printed at the foot of the 80mm receipt. Optional.</small>
								</div>
							</div>
						</div>

						<div class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
							<button type="submit" name="save" value="1" class="btn btn-primary waves-effect waves-themed">
								<i class="fal fa-save mr-1"></i> Save Settings
							</button>
							<a href="<?php echo ADMIN_URL; ?>paymongo_setup" class="btn btn-secondary waves-effect waves-themed ml-2">
								<i class="fal fa-arrow-left mr-1"></i> Cancel
							</a>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="col-xl-3">
			<div class="panel mb-3">
				<div class="panel-hdr">
					<h2>Checklist</h2>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<ol class="pl-3 mb-0">
							<li class="mb-2">Create a PayMongo account and copy the keys.</li>
							<li class="mb-2">Paste the <strong>secret key</strong> here and save.</li>
							<li class="mb-2">Turn <strong>Enable online payments</strong> on.</li>
							<li class="mb-2">Register the webhook URL (shown on the previous screen) and paste its <strong>webhook secret</strong>.</li>
							<li class="mb-2">Press <strong>Test connection</strong> to confirm the key is accepted.</li>
							<li>Start in <strong>Test</strong> mode, then swap to the live key when ready.</li>
						</ol>
					</div>
				</div>
			</div>

			<div class="alert alert-info mb-0" role="alert">
				<i class="fal fa-shield-alt mr-1"></i>
				Keys live in the database, not in this repository. Never paste a key into a commit, a ticket or a chat.
			</div>
		</div>
	</div>
</main>

<?php include('footer.php'); ?>
