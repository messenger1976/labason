<?php
/**
 * Global SweetAlert delete helpers (included from footer.php).
 * Intercepts legacy confirm()-based delete links and provides deleteAllData().
 * Note: SA4 ships SweetAlert2 v9 — use result.value (isConfirmed is v10+).
 */
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/notifications/sweetalert2/sweetalert2.bundle.css">
<script src="<?php echo base_url(); ?>sa4/js/notifications/sweetalert2/sweetalert2.bundle.js"></script>
<script type="text/javascript">
(function($) {
	'use strict';

	function sa4SwalAvailable() {
		return (typeof Swal !== 'undefined' && typeof Swal.fire === 'function');
	}

	/** SweetAlert2 v9 uses result.value; v10+ uses result.isConfirmed */
	window.sa4SwalConfirmed = function(result) {
		if (!result) { return false; }
		if (result.isConfirmed === true) { return true; }
		if (result.value === true) { return true; }
		if (typeof result.dismiss !== 'undefined') { return false; }
		return (typeof result.value !== 'undefined' && result.value !== false && result.value !== null);
	};

	window.sa4ConfirmAction = function(options) {
		var opts = $.extend({
			title: 'Are you sure?',
			text: 'This record will be permanently deleted.',
			icon: 'warning',
			confirmButtonText: 'Yes, delete it!',
			cancelButtonText: 'Cancel',
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6'
		}, options || {});

		if (sa4SwalAvailable()) {
			return Swal.fire({
				title: opts.title,
				text: opts.text,
				icon: opts.icon,
				showCancelButton: true,
				confirmButtonColor: opts.confirmButtonColor,
				cancelButtonColor: opts.cancelButtonColor,
				confirmButtonText: opts.confirmButtonText,
				cancelButtonText: opts.cancelButtonText,
				reverseButtons: true
			}).then(function(result) {
				return window.sa4SwalConfirmed(result);
			});
		}
		return $.Deferred().resolve(window.confirm(opts.text || opts.title)).promise();
	};

	window.sa4ExtractConfirmLocation = function(href) {
		if (!href || typeof href !== 'string') { return null; }
		var m = href.match(/window\.location\s*=\s*['"]([^'"]+)['"]/i);
		return m ? m[1] : null;
	};

	function isLegacyDeleteConfirmHref(href) {
		if (!href || typeof href !== 'string') { return false; }
		if (!/confirm\s*\(/i.test(href)) { return false; }
		if (/Confirm Delete/i.test(href)) { return true; }
		if (/\/delete\//i.test(href) && /[Dd]elete/i.test(href)) { return true; }
		return false;
	}

	function resolveDeleteUrl($el) {
		var url = $el.attr('data-sa4-delete-url');
		if (url) { return url; }
		if ($el.hasClass('btn-delete-row') || $el.hasClass('sa4-confirm-delete')) {
			url = $el.attr('data-url') || $el.attr('data-sa4-delete-url');
			if (url) { return url; }
		}
		var href = $el.attr('href') || '';
		if (isLegacyDeleteConfirmHref(href)) {
			return window.sa4ExtractConfirmLocation(href);
		}
		return null;
	}

	$(document).on('click', 'a.btn-delete-row, a.sa4-confirm-delete, a[data-sa4-delete-url], a[href*="Confirm Delete"]', function(e) {
		var $el = $(this);
		if ($el.data('sa4SkipConfirm')) { return; }

		var url = resolveDeleteUrl($el);
		if (!url) { return; }

		e.preventDefault();
		e.stopPropagation();

		window.sa4ConfirmAction({
			title: 'Are you sure?',
			text: 'This record will be permanently deleted.',
			confirmButtonText: 'Yes, delete it!'
		}).then(function(ok) {
			if (ok) { window.location.href = url; }
		});
	});

	window.deleteAllData = function() {
		var checked_num = $('input[name="delete_ids[]"]:checked').length;
		if (checked_num === 0) {
			if (sa4SwalAvailable()) {
				Swal.fire({
					title: 'No selection',
					text: 'Select at least one checkbox...',
					icon: 'warning'
				});
			} else {
				alert('Select at least one checkbox...');
			}
			return false;
		}

		window.sa4ConfirmAction({
			title: 'Are you sure?',
			text: 'Selected records will be permanently deleted.',
			confirmButtonText: 'Yes, delete them!'
		}).then(function(ok) {
			if (!ok) { return; }
			var $form = $('#sa4-list-form');
			if (!$form.length) {
				$form = $('form').has('input[name="delete_ids[]"]:checked').first();
			}
			if ($form.length) {
				$form.get(0).submit();
			}
		});
		return false;
	};
})(jQuery);
</script>
