/**
 * Export to PDF: open a modal with the Print HTML, then download PDF from that layout.
 */
(function (window, $) {
	'use strict';

	function withPreview(url) {
		if (!url) {
			return url;
		}
		return url + (url.indexOf('?') >= 0 ? '&' : '?') + 'preview=1';
	}

	function ensureModal() {
		if (document.getElementById('reportPdfPreviewModal')) {
			return;
		}
		var html =
			'<div class="modal fade" id="reportPdfPreviewModal" tabindex="-1" role="dialog" aria-labelledby="reportPdfPreviewTitle" aria-hidden="true">' +
				'<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document" style="max-width: 92%;">' +
					'<div class="modal-content">' +
						'<div class="modal-header">' +
							'<h5 class="modal-title" id="reportPdfPreviewTitle">Report preview</h5>' +
							'<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
								'<span aria-hidden="true">&times;</span>' +
							'</button>' +
						'</div>' +
						'<div class="modal-body p-0">' +
							'<div id="reportPdfProgressWrap" class="px-3 pt-3 pb-2">' +
								'<div class="progress" style="height: 18px;">' +
									'<div id="reportPdfProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: 10%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>' +
								'</div>' +
								'<div id="reportPdfProgressText" class="text-center small text-muted mt-2">Loading print layout...</div>' +
							'</div>' +
							'<iframe id="reportPdfPreviewFrame" title="Report preview" style="width:100%;height:70vh;border:0;background:#fff;"></iframe>' +
						'</div>' +
						'<div class="modal-footer">' +
							'<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
							'<button type="button" class="btn btn-danger" id="reportPdfDownloadBtn" disabled>' +
								'<i class="fal fa-file-pdf mr-1"></i> Download to PDF' +
							'</button>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>';
		$('body').append(html);

		$('#reportPdfPreviewModal').on('hidden.bs.modal', function () {
			$('#reportPdfPreviewFrame').attr('src', 'about:blank');
		});
	}

	function setProgress(pct, text) {
		var $bar = $('#reportPdfProgressBar');
		var $wrap = $('#reportPdfProgressWrap');
		$wrap.show();
		$bar.css('width', pct + '%').attr('aria-valuenow', pct);
		if (text) {
			$('#reportPdfProgressText').text(text);
		}
	}

	window.openReportPdfPreview = function (opts) {
		opts = opts || {};
		var printUrl = opts.printUrl;
		var filename = opts.filename || 'report.pdf';
		var captureUrl = opts.captureUrl || window.REPORT_PDF_CAPTURE_URL;
		if (!printUrl) {
			return;
		}
		if (typeof $ !== 'function') {
			window.location.href = printUrl;
			return;
		}

		ensureModal();
		var $modal = $('#reportPdfPreviewModal');
		var $frame = $('#reportPdfPreviewFrame');
		var $btn = $('#reportPdfDownloadBtn');
		var timer = null;
		var pct = 12;

		$btn.prop('disabled', true).data('filename', filename);
		setProgress(12, 'Loading print layout...');
		$frame.attr('src', 'about:blank');
		$modal.modal('show');

		clearInterval(timer);
		timer = setInterval(function () {
			if (pct < 88) {
				pct += 4;
				setProgress(pct);
			}
		}, 180);

		$frame.off('load.reportpdf').on('load.reportpdf', function () {
			var src = $frame.attr('src') || '';
			if (!src || src === 'about:blank') {
				return;
			}
			clearInterval(timer);
			setProgress(100, 'Layout ready');
			setTimeout(function () {
				$('#reportPdfProgressWrap').hide();
				$btn.prop('disabled', false);
			}, 250);
		});

		$frame.attr('src', withPreview(printUrl));

		$btn.off('click.reportpdf').on('click.reportpdf', function () {
			var iframe = $frame[0];
			var doc = iframe && (iframe.contentDocument || (iframe.contentWindow && iframe.contentWindow.document));
			if (!doc || !doc.documentElement) {
				alert('Preview is still loading.');
				return;
			}
			if (!captureUrl) {
				alert('PDF download is not configured.');
				return;
			}

			$btn.prop('disabled', true);
			setProgress(30, 'Creating PDF from this layout...');

			var html = '<!DOCTYPE html>\n' + doc.documentElement.outerHTML;
			var body = new FormData();
			body.append('html', html);
			body.append('filename', filename);

			fetch(captureUrl, { method: 'POST', body: body, credentials: 'same-origin' })
				.then(function (res) {
					var ct = (res.headers.get('content-type') || '').toLowerCase();
					if (!res.ok || ct.indexOf('pdf') === -1) {
						throw new Error('not-pdf');
					}
					setProgress(80, 'Saving file...');
					return res.blob();
				})
				.then(function (blob) {
					setProgress(100, 'Done');
					var a = document.createElement('a');
					a.href = URL.createObjectURL(blob);
					a.download = filename;
					document.body.appendChild(a);
					a.click();
					a.remove();
					setTimeout(function () {
						$('#reportPdfProgressWrap').hide();
						$btn.prop('disabled', false);
					}, 200);
				})
				.catch(function () {
					$('#reportPdfProgressWrap').hide();
					$btn.prop('disabled', false);
					if (iframe.contentWindow && typeof iframe.contentWindow.print === 'function') {
						iframe.contentWindow.print();
					} else {
						alert('Could not create the PDF. Please use Print instead.');
					}
				});
		});
	};
})(window, window.jQuery);