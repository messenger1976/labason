/*
 * Customer Statement of Account app shell behaviour: drawer, offline banner,
 * install prompt and the reset-password sheet. No billing figures here.
 */
(function () {
	'use strict';

	var shell = document.getElementById('app_shell');
	if (!shell) { return; }

	// ---- Drawer ----
	function setMenu(open) {
		shell.classList.toggle('menu-open', open);
	}
	Array.prototype.forEach.call(document.querySelectorAll('.js-app-menu'), function (btn) {
		btn.addEventListener('click', function () { setMenu(!shell.classList.contains('menu-open')); });
	});
	var backdrop = document.getElementById('app_nav_backdrop');
	if (backdrop) { backdrop.addEventListener('click', function () { setMenu(false); }); }
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') { setMenu(false); closeReset(); }
	});

	// ---- Offline banner ----
	var offline = document.getElementById('app_offline');
	function syncOnline() {
		if (offline) { offline.classList.toggle('show', navigator.onLine === false); }
	}
	window.addEventListener('online', syncOnline);
	window.addEventListener('offline', syncOnline);
	syncOnline();

	// ---- Install prompt ----
	var deferredPrompt = null;
	var installBtns = document.querySelectorAll('.js-app-install');
	window.addEventListener('beforeinstallprompt', function (e) {
		e.preventDefault();
		deferredPrompt = e;
		Array.prototype.forEach.call(installBtns, function (b) { b.style.display = ''; });
	});
	Array.prototype.forEach.call(installBtns, function (b) {
		b.addEventListener('click', function () {
			if (!deferredPrompt) { return; }
			deferredPrompt.prompt();
			deferredPrompt.userChoice.then(function () {
				deferredPrompt = null;
				Array.prototype.forEach.call(installBtns, function (x) { x.style.display = 'none'; });
			});
		});
	});

	// ---- Reset password ----
	var modal = document.getElementById('app_reset_modal');
	var form = document.getElementById('app_reset_form');
	var busy = false;

	function closeReset() {
		if (modal && !busy) { modal.classList.remove('show'); }
	}
	function openReset() {
		if (!modal) { return; }
		setMenu(false);
		form.reset();
		document.getElementById('app_reset_error').style.display = 'none';
		document.getElementById('app_reset_ok').style.display = 'none';
		document.getElementById('app_reset_save').disabled = false;
		modal.classList.add('show');
		setTimeout(function () { document.getElementById('app_new_password').focus(); }, 50);
	}
	Array.prototype.forEach.call(document.querySelectorAll('.js-app-reset'), function (b) {
		b.addEventListener('click', openReset);
	});
	if (modal) {
		modal.addEventListener('click', function (e) { if (e.target === modal) { closeReset(); } });
		modal.querySelector('.js-app-reset-cancel').addEventListener('click', closeReset);
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var pw = document.getElementById('app_new_password').value;
			var pw2 = document.getElementById('app_confirm_password').value;
			var err = document.getElementById('app_reset_error');
			var ok = document.getElementById('app_reset_ok');
			var save = document.getElementById('app_reset_save');
			err.style.display = 'none';
			if (!pw || pw.length < 3) {
				err.textContent = 'Password must be at least 3 characters long.';
				err.style.display = 'block';
				return;
			}
			if (pw !== pw2) {
				err.textContent = 'Passwords do not match. Please try again.';
				err.style.display = 'block';
				return;
			}
			busy = true;
			save.disabled = true;
			save.textContent = 'Resetting...';
			var body = new URLSearchParams();
			body.append('customer_id', shell.getAttribute('data-customer-id') || '');
			body.append('password', pw);
			fetch(shell.getAttribute('data-reset-url'), {
				method: 'POST',
				credentials: 'same-origin',
				headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body: body.toString()
			})
			.then(function (r) { return r.json(); })
			.then(function (json) {
				busy = false;
				if (json && json.success) {
					ok.textContent = json.message || 'Password reset.';
					ok.style.display = 'block';
					setTimeout(function () { window.location.href = shell.getAttribute('data-login-url'); }, 2000);
				} else {
					err.textContent = (json && json.message) || 'Failed to reset password. Please try again.';
					err.style.display = 'block';
					save.disabled = false;
					save.textContent = 'Reset password';
				}
			})
			.catch(function () {
				busy = false;
				err.textContent = 'An error occurred while resetting the password. Please try again.';
				err.style.display = 'block';
				save.disabled = false;
				save.textContent = 'Reset password';
			});
		});
	}

	// Shared formatter for page scripts.
	window.soaApp = {
		peso: function (v) {
			var n = parseFloat(v || 0);
			if (isNaN(n)) { n = 0; }
			return '\u20B1 ' + n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
		},
		esc: function (v) {
			if (v === null || v === undefined) { return ''; }
			return String(v).replace(/[&<>"']/g, function (c) {
				return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
			});
		}
	};
})();
