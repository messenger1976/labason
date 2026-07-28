<?php
/**
 * Shared SA4 DataTable init for client-side #dt_basic tables.
 * Optional: $sa4_dt_entity (label), $sa4_dt_export_cols (array of indexes), $sa4_panel_id
 */
if (!isset($sa4_dt_entity)) { $sa4_dt_entity = 'records'; }
if (!isset($sa4_panel_id)) { $sa4_panel_id = 'panel-listing'; }
$export_cols = isset($sa4_dt_export_cols) && is_array($sa4_dt_export_cols) ? $sa4_dt_export_cols : null;
$export_js = $export_cols ? json_encode(array_values($export_cols)) : 'null';
?>
<script src="<?php echo base_url(); ?>sa4/js/datagrid/datatables/datatables.bundle.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
<script type="text/javascript">
(function($) {
	if (typeof pageSetUp === 'function') { pageSetUp(); }

	var isInitialLoad = true;
	var progressTimer = null;
	var progressValue = 8;
	var panelSel = '#<?php echo preg_replace('/[^a-zA-Z0-9_-]/', '', $sa4_panel_id); ?>';
	var entityLabel = <?php echo json_encode($sa4_dt_entity); ?>;
	var exportCols = <?php echo $export_js; ?>;

	function setProgress(pct) {
		progressValue = Math.max(0, Math.min(100, pct));
		$('#dt-loading-progress-bar').css('width', progressValue + '%').attr('aria-valuenow', Math.round(progressValue));
		$('#dt-loading-percent').text(Math.round(progressValue) + '%');
	}
	function startProgress() {
		clearInterval(progressTimer);
		setProgress(8);
		$('#dt-loading-title').text('Fetching ' + entityLabel);
		$('#dt-loading-subtitle').text('Please wait while we prepare the listing…');
		progressTimer = setInterval(function() {
			if (progressValue < 90) {
				setProgress(progressValue + Math.max(0.6, (90 - progressValue) * 0.08));
			}
		}, 180);
	}
	function completeProgress(done) {
		clearInterval(progressTimer);
		setProgress(100);
		$('#dt-loading-title').text('Almost done');
		$('#dt-loading-subtitle').text('Rendering listing…');
		setTimeout(done, 220);
	}
	window.sa4ShowLoader = function() {
		$('#datatable-loading-modal').addClass('is-visible').show();
		$(panelSel).addClass('panel-loading');
		$('.dataTables_wrapper').addClass('processing');
		startProgress();
	};
	window.sa4HideLoader = function() {
		completeProgress(function() {
			$('#datatable-loading-modal').removeClass('is-visible').fadeOut(180);
			$(panelSel).removeClass('panel-loading');
			$('.dataTables_wrapper').removeClass('processing');
			setTimeout(function() { setProgress(8); }, 250);
		});
	};

	if (!$('#dt_basic').length) { return; }

	sa4ShowLoader();

	var btnExport = function(extend, icon, label) {
		var cfg = {
			extend: extend,
			text: '<i class="fal ' + icon + ' mr-1"></i> ' + label,
			className: 'btn-primary btn-sm mr-1'
		};
		if (exportCols) { cfg.exportOptions = { columns: exportCols }; }
		return cfg;
	};

	var table = $('#dt_basic').DataTable({
		responsive: true,
		stateSave: false,
		pageLength: 25,
		lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
		dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
		language: {
			processing: '',
			search: '',
			searchPlaceholder: 'Search ' + entityLabel + '...',
			lengthMenu: '_MENU_',
			info: 'Showing _START_ to _END_ of _TOTAL_ ' + entityLabel,
			infoEmpty: 'No ' + entityLabel + ' found',
			zeroRecords: 'No matching ' + entityLabel,
			paginate: {
				first: '<i class="fal fa-chevron-double-left"></i>',
				last: '<i class="fal fa-chevron-double-right"></i>',
				next: '<i class="fal fa-chevron-right"></i>',
				previous: '<i class="fal fa-chevron-left"></i>'
			}
		},
		buttons: [
			btnExport('copyHtml5', 'fa-copy', 'Copy'),
			btnExport('excelHtml5', 'fa-file-excel', 'Excel'),
			btnExport('csvHtml5', 'fa-file-csv', 'CSV'),
			btnExport('pdfHtml5', 'fa-file-pdf', 'PDF'),
			btnExport('print', 'fa-print', 'Print'),
			{
				text: '<i class="fal fa-sync mr-1"></i> Refresh',
				className: 'btn-primary btn-sm',
				action: function() { window.location.reload(); }
			}
		],
		drawCallback: function() {
			if (isInitialLoad) {
				isInitialLoad = false;
				sa4HideLoader();
			}
			if ($.fn.tooltip) { $('[data-toggle="tooltip"]').tooltip(); }
		}
	});

	$('#dt_select_all').on('change', function() {
		var checked = $(this).is(':checked');
		$('#dt_basic tbody input[name="delete_ids[]"]').prop('checked', checked);
	});
})(jQuery);

function deleteAllData() {
	var checked_num = $('input[name="delete_ids[]"]:checked').length;
	if (checked_num === 0) {
		alert('Select at least one checkbox...');
		return false;
	}
	return confirm('Confirm Delete?');
}
</script>
