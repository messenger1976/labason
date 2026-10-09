<?php
$soa_img = base_url() . 'img/soa/';
$soa_msg_text = trim(html_entity_decode(strip_tags((string) $msg), ENT_QUOTES, 'UTF-8'));
$soa_msg_type = (strpos((string) $msg, 'alert-danger') !== false) ? 'danger' : 'info';
$soa_customer_id = isset($_POST['customer_id']) ? htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8') : '';
?>
<div class="soa-auth">
	<header class="soa-auth-hero">
		<img class="soa-auth-logo" src="<?php echo $soa_img; ?>lwd-seal.png" alt="Labason Water District official seal" width="88" height="88">
		<h1>Labason Water District</h1>
		<p>View your water bill, payment history and balance anytime, on any device.</p>
		<ul class="soa-auth-points">
			<li><i class="fa fa-file-text-o" aria-hidden="true"></i>Your complete Statement of Account</li>
			<li><i class="fa fa-history" aria-hidden="true"></i>Every billing and payment in one place</li>
			<li><i class="fa fa-calendar-check-o" aria-hidden="true"></i>Current balance and due date at a glance</li>
		</ul>
	</header>

	<main class="soa-auth-body">
		<div class="soa-auth-card">
			<h2>Customer Login</h2>
			<p class="soa-auth-lead">Enter your Customer ID and password to view your Statement of Account.</p>

			<?php if ($soa_msg_text !== '') { ?>
			<div class="soa-alert soa-alert-<?php echo $soa_msg_type; ?>" role="alert">
				<i class="fa <?php echo $soa_msg_type === 'danger' ? 'fa-exclamation-circle' : 'fa-info-circle'; ?>" aria-hidden="true"></i>
				<span><?php echo htmlspecialchars($soa_msg_text, ENT_QUOTES, 'UTF-8'); ?></span>
			</div>
			<?php } ?>

			<div class="soa-alert soa-alert-warning" id="soaOffline" role="status" hidden>
				<i class="fa fa-wifi" aria-hidden="true"></i>
				<span>You are offline. Connect to the internet to log in.</span>
			</div>

			<form method="post" action="<?php echo base_url(); ?>master/statementofaccount/search" id="statementSearchForm" novalidate>
				<div class="soa-field">
					<label for="direct_customer_id">Customer ID</label>
					<div class="soa-input">
						<i class="fa fa-user" aria-hidden="true"></i>
						<input type="text" id="direct_customer_id" name="customer_id" placeholder="e.g. 0001234"
							required autofocus autocomplete="username" autocapitalize="none" autocorrect="off" spellcheck="false"
							value="<?php echo $soa_customer_id; ?>">
					</div>
				</div>

				<div class="soa-field">
					<label for="customer_password">Password</label>
					<div class="soa-input soa-input-password">
						<i class="fa fa-lock" aria-hidden="true"></i>
						<input type="password" id="customer_password" name="password" placeholder="Your password"
							required autocomplete="current-password">
						<button type="button" class="soa-password-toggle" id="soaTogglePassword"
							aria-label="Show password" aria-pressed="false">
							<i class="fa fa-eye" aria-hidden="true"></i>
						</button>
					</div>
				</div>

				<div class="soa-alert soa-alert-danger" id="soaFormError" role="alert" hidden>
					<i class="fa fa-exclamation-circle" aria-hidden="true"></i>
					<span></span>
				</div>

				<button type="submit" class="soa-btn" id="viewStatementBtn">
					<i class="fa fa-sign-in" aria-hidden="true"></i> <span>Login</span>
				</button>
			</form>

			<p class="soa-auth-help">
				<i class="fa fa-question-circle" aria-hidden="true"></i>
				Forgot your password or don't have one yet? Please visit or call the Labason Water District office.
			</p>
		</div>

		<div class="soa-auth-foot">
			<button type="button" class="soa-install" id="soaInstall" hidden>
				<i class="fa fa-download" aria-hidden="true"></i> Install the app on this device
			</button>
			<button type="button" class="soa-install" id="soaIosInstall" hidden>
				<i class="fa fa-download" aria-hidden="true"></i> Add to Home Screen
			</button>
			<div class="soa-ios-hint" id="soaIosHint" hidden>
				In Safari, tap <strong>Share</strong>, then <strong>Add to Home Screen</strong>.
			</div>
			<div>&copy; <?php echo date('Y'); ?> Labason Water District</div>
		</div>
	</main>
</div>

<style>
	.soa-auth {
		--soa-brand: #0a6ba3;
		--soa-brand-dark: #063f66;
		--soa-bg: #f2f6f9;
		--soa-text: #1d2b36;
		--soa-muted: #5f7180;
		--soa-border: #d5e0e8;
		--soa-safe-top: env(safe-area-inset-top, 0px);
		--soa-safe-bottom: env(safe-area-inset-bottom, 0px);
		display: flex;
		flex-direction: column;
		min-height: 100vh;
		min-height: 100dvh;
		background: var(--soa-brand-dark);
		color: var(--soa-text);
		font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
		font-size: 15px;
	}

	html {
		font-size: 16px !important;
	}

	html, body.smart-style-0 {
		background: #063f66 !important;
	}

	#main, #content {
		margin: 0 !important;
		padding: 0 !important;
	}

	.soa-auth *, .soa-auth *::before, .soa-auth *::after {
		box-sizing: border-box;
	}

	.soa-auth [hidden] {
		display: none !important;
	}

	/* ---------- Hero (top band on phones, left panel on desktop) ---------- */

	.soa-auth-hero {
		display: flex;
		flex-direction: column;
		justify-content: flex-end;
		padding: calc(1.5rem + var(--soa-safe-top)) 1.5rem 3.5rem;
		color: #fff;
		background:
			linear-gradient(180deg, rgba(6, 63, 102, 0.55) 0%, rgba(6, 63, 102, 0.92) 100%),
			url('<?php echo $soa_img; ?>water-hero-sm.webp') center / cover no-repeat,
			var(--soa-brand-dark);
	}

	.soa-auth-logo {
		width: 4.5rem;
		height: 4.5rem;
		border: 3px solid #fff;
		border-radius: 50%;
		background: #fff;
		box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.3);
	}

	.soa-auth-hero h1 {
		margin: 1rem 0 0.35rem;
		font-size: 1.5rem;
		font-weight: 700;
		line-height: 1.2;
		letter-spacing: 0;
		color: #fff;
	}

	.soa-auth-hero p {
		margin: 0;
		max-width: 28rem;
		font-size: 0.92rem;
		opacity: 0.85;
	}

	.soa-auth-points {
		display: none;
	}

	/* ---------- Card ---------- */

	.soa-auth-body {
		position: relative;
		z-index: 1;
		flex: 1;
		margin-top: -2rem;
		padding: 0 1rem calc(1.5rem + var(--soa-safe-bottom));
	}

	.soa-auth-card {
		width: 100%;
		max-width: 28rem;
		margin: 0 auto;
		padding: 1.5rem;
		border-radius: 1rem;
		background: #fff;
		box-shadow: 0 1rem 2.5rem rgba(6, 40, 66, 0.25);
	}

	.soa-auth-card h2 {
		margin: 0 0 0.25rem;
		font-size: 1.3rem;
		font-weight: 700;
		color: var(--soa-brand-dark);
	}

	.soa-auth-lead {
		margin: 0 0 1.25rem;
		font-size: 0.9rem;
		color: var(--soa-muted);
	}

	.soa-field {
		margin-bottom: 1rem;
	}

	.soa-field label {
		display: block;
		margin-bottom: 0.4rem;
		font-size: 0.88rem;
		font-weight: 600;
		color: var(--soa-text);
	}

	.soa-input {
		position: relative;
	}

	.soa-input > .fa {
		position: absolute;
		top: 50%;
		left: 0.9rem;
		transform: translateY(-50%);
		font-size: 1rem;
		color: var(--soa-muted);
		pointer-events: none;
	}

	.soa-input input {
		display: block;
		width: 100%;
		height: 3rem;
		padding: 0 0.9rem 0 2.6rem;
		border: 1px solid var(--soa-border);
		border-radius: 0.6rem;
		background: #fff;
		color: var(--soa-text);
		font-size: 16px; /* 16px stops iOS from zooming into the field */
		-webkit-appearance: none;
		appearance: none;
		transition: border-color 0.15s ease, box-shadow 0.15s ease;
	}

	.soa-input input::placeholder {
		color: #9aa9b5;
	}

	.soa-input input:focus {
		border-color: var(--soa-brand);
		box-shadow: 0 0 0 0.2rem rgba(10, 107, 163, 0.2);
		outline: none;
	}

	.soa-input input.is-invalid {
		border-color: #c0392b;
	}

	.soa-input-password input {
		padding-right: 3rem;
	}

	.soa-password-toggle {
		position: absolute;
		top: 50%;
		right: 0.25rem;
		transform: translateY(-50%);
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 2.75rem;
		height: 2.75rem;
		padding: 0;
		border: 0;
		border-radius: 0.5rem;
		background: transparent;
		color: var(--soa-muted);
		font-size: 1.05rem;
		cursor: pointer;
	}

	.soa-password-toggle:focus-visible {
		outline: 2px solid var(--soa-brand);
	}

	.soa-btn {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 0.5rem;
		width: 100%;
		min-height: 3rem;
		margin-top: 0.5rem;
		padding: 0.75rem 1rem;
		border: 0;
		border-radius: 0.6rem;
		background: var(--soa-brand);
		color: #fff;
		font-size: 1rem;
		font-weight: 600;
		cursor: pointer;
		touch-action: manipulation;
		-webkit-tap-highlight-color: transparent;
		transition: background-color 0.15s ease, transform 0.1s ease;
	}

	.soa-btn:hover {
		background: #085b8b;
	}

	.soa-btn:active {
		transform: scale(0.98);
	}

	.soa-btn:focus-visible {
		outline: 3px solid rgba(10, 107, 163, 0.35);
		outline-offset: 2px;
	}

	.soa-btn[disabled] {
		opacity: 0.7;
		cursor: not-allowed;
	}

	.soa-alert {
		display: flex;
		align-items: flex-start;
		gap: 0.6rem;
		margin-bottom: 1rem;
		padding: 0.75rem 0.9rem;
		border-radius: 0.6rem;
		font-size: 0.88rem;
		line-height: 1.4;
	}

	.soa-alert .fa {
		margin-top: 0.15rem;
	}

	.soa-alert-danger {
		background: #fdecea;
		color: #a12a1f;
	}

	.soa-alert-info {
		background: #e6f2fa;
		color: #0b5784;
	}

	.soa-alert-warning {
		background: #fff4de;
		color: #8a5a00;
	}

	.soa-auth-help {
		margin: 1.25rem 0 0;
		padding-top: 1rem;
		border-top: 1px solid #edf1f4;
		font-size: 0.82rem;
		line-height: 1.5;
		color: var(--soa-muted);
		text-align: center;
	}

	.soa-auth-foot {
		max-width: 28rem;
		margin: 1rem auto 0;
		text-align: center;
		font-size: 0.78rem;
		color: rgba(255, 255, 255, 0.7);
	}

	.soa-install {
		margin-bottom: 0.5rem;
		padding: 0.4rem 0.75rem;
		border: 0;
		background: transparent;
		color: #fff;
		font-size: 0.85rem;
		font-weight: 600;
		cursor: pointer;
	}

	.soa-ios-hint {
		margin-bottom: 0.5rem;
	}

	/* ---------- Tablet ---------- */

	@media (min-width: 576px) {
		.soa-auth-hero {
			align-items: center;
			text-align: center;
			padding-bottom: 4rem;
		}

		.soa-auth-card {
			padding: 2rem;
		}
	}

	/* ---------- Desktop: hero left, form right ---------- */

	@media (min-width: 992px) {
		.soa-auth {
			flex-direction: row;
		}

		.soa-auth-hero {
			flex: 1 1 55%;
			align-items: flex-start;
			justify-content: center;
			text-align: left;
			padding: 3rem 4rem;
			background:
				linear-gradient(135deg, rgba(6, 63, 102, 0.6) 0%, rgba(6, 63, 102, 0.9) 100%),
				url('<?php echo $soa_img; ?>water-hero.webp') center / cover no-repeat,
				var(--soa-brand-dark);
		}

		.soa-auth-logo {
			width: 6rem;
			height: 6rem;
		}

		.soa-auth-hero h1 {
			font-size: 2.2rem;
		}

		.soa-auth-hero p {
			font-size: 1.05rem;
		}

		.soa-auth-points {
			display: block;
			margin: 2rem 0 0;
			padding: 0;
			list-style: none;
		}

		.soa-auth-points li {
			display: flex;
			align-items: center;
			gap: 0.75rem;
			margin-bottom: 0.85rem;
			font-size: 0.95rem;
		}

		.soa-auth-points .fa {
			display: inline-flex;
			flex-shrink: 0;
			align-items: center;
			justify-content: center;
			width: 2rem;
			height: 2rem;
			border-radius: 50%;
			background: rgba(255, 255, 255, 0.15);
		}

		.soa-auth-body {
			display: flex;
			flex: 1 1 45%;
			flex-direction: column;
			justify-content: center;
			margin: 0;
			padding: 3rem;
			background: var(--soa-bg);
		}

		.soa-auth-card {
			padding: 2.25rem;
			box-shadow: 0 0.5rem 2rem rgba(6, 40, 66, 0.1);
		}

		.soa-auth-foot {
			color: var(--soa-muted);
		}

		.soa-install {
			color: var(--soa-brand);
		}
	}

	@media (prefers-reduced-motion: reduce) {
		.soa-auth * {
			transition: none !important;
		}
	}
</style>

<script>
	(function () {
		var form = document.getElementById('statementSearchForm');
		var idInput = document.getElementById('direct_customer_id');
		var pwInput = document.getElementById('customer_password');
		var toggle = document.getElementById('soaTogglePassword');
		var btn = document.getElementById('viewStatementBtn');
		var formError = document.getElementById('soaFormError');
		var offline = document.getElementById('soaOffline');

		if (idInput.value) {
			pwInput.focus();
		}

		toggle.addEventListener('click', function () {
			var show = pwInput.type === 'password';
			pwInput.type = show ? 'text' : 'password';
			toggle.setAttribute('aria-pressed', show ? 'true' : 'false');
			toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
			toggle.querySelector('.fa').className = 'fa ' + (show ? 'fa-eye-slash' : 'fa-eye');
			pwInput.focus();
		});

		function showError(text, field) {
			formError.querySelector('span').textContent = text;
			formError.hidden = false;
			[idInput, pwInput].forEach(function (el) { el.classList.toggle('is-invalid', el === field); });
			field.focus();
		}

		[idInput, pwInput].forEach(function (el) {
			el.addEventListener('input', function () {
				el.classList.remove('is-invalid');
				formError.hidden = true;
			});
		});

		form.addEventListener('submit', function (e) {
			if (!navigator.onLine) {
				e.preventDefault();
				offline.hidden = false;
				return;
			}
			if (!idInput.value.trim()) {
				e.preventDefault();
				showError('Please enter your Customer ID.', idInput);
				return;
			}
			if (!pwInput.value) {
				e.preventDefault();
				showError('Please enter your password.', pwInput);
				return;
			}
			btn.disabled = true;
			btn.querySelector('span').textContent = 'Logging in…';
		});

		// A page restored from the back/forward cache must not keep the busy state.
		window.addEventListener('pageshow', function () {
			btn.disabled = false;
			btn.querySelector('span').textContent = 'Login';
		});

		function syncOnline() {
			offline.hidden = navigator.onLine;
		}
		window.addEventListener('online', syncOnline);
		window.addEventListener('offline', syncOnline);
		syncOnline();

		// ---------- PWA install ----------
		var installBtn = document.getElementById('soaInstall');
		var iosBtn = document.getElementById('soaIosInstall');
		var iosHint = document.getElementById('soaIosHint');
		var deferredPrompt = null;
		var standalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
		var isIos = /iphone|ipad|ipod/i.test(navigator.userAgent) ||
			(navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);

		window.addEventListener('beforeinstallprompt', function (e) {
			e.preventDefault();
			deferredPrompt = e;
			installBtn.hidden = false;
		});

		installBtn.addEventListener('click', function () {
			if (!deferredPrompt) return;
			deferredPrompt.prompt();
			deferredPrompt.userChoice.then(function () {
				deferredPrompt = null;
				installBtn.hidden = true;
			});
		});

		window.addEventListener('appinstalled', function () {
			installBtn.hidden = true;
		});

		if (isIos && !standalone) {
			iosBtn.hidden = false;
			iosBtn.addEventListener('click', function () {
				iosHint.hidden = !iosHint.hidden;
			});
		}
	})();
</script>

			</div>
		</div>
	</body>
</html>
