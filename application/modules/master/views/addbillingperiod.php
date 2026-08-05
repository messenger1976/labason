<?php
	$income1 = $this->my_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->my_model->get_income_monthlycustomer();
	extract($income2);
	$intotal = (isset($total1) ? (float) $total1 : 0) + (isset($total2) ? (float) $total2 : 0);

	$expense1 = $this->my_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->my_model->get_outcome_payroll();
	extract($expense2);
	$extotal = (isset($extotal1) ? (float) $extotal1 : 0) + (isset($extotal2) ? (float) $extotal2 : 0);

	$total_customer = $this->my_model->total_customer();
	extract($total_customer);

	$zone = (isset($zone) && is_array($zone)) ? $zone : array();
	$billingperiod = (isset($billingperiod) && is_array($billingperiod)) ? $billingperiod : array();
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addbillingperiod">Schedule Billing Period</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-calendar-alt"></i>
			Manage <span class="fw-300">Billing Period</span>
		</h1>
		<div class="subheader-block d-lg-flex align-items-center">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>INCOME</small></span>
				<span class="fw-500 fs-xl d-block color-primary-500">₱ <?php echo number_format($intotal, 2); ?></span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#886ab5" sparkHeight="32px" sparkBarWidth="5px" values="3,4,3,6,7,3,3,6,2,6,4"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>EXPENSE</small></span>
				<span class="fw-500 fs-xl d-block color-danger-500">₱ <?php echo number_format($extotal, 2); ?></span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#fe6bb0" sparkHeight="32px" sparkBarWidth="5px" values="1,4,3,6,5,3,9,6,5,9,7"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50"><small>TOTAL CUSTOMER</small></span>
				<span class="fw-500 fs-xl d-block color-success-500"><?php echo (int) (isset($count_id) ? $count_id : 0); ?></span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#1dc9b7" sparkHeight="32px" sparkBarWidth="5px" values="2,5,3,7,4,6,3,8,5,4,6"></span>
		</div>
	</div>

	<?php if ($this->session->flashdata('msg_succ')) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<strong>Success!</strong> <?php echo $this->session->flashdata('msg_succ'); ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-billingperiod" class="panel">
				<div class="panel-hdr">
					<h2>Billing Period <span class="fw-300"><i>Search</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<div class="row mb-3">
							<div class="col-md-12 text-md-right">
								<button type="button" class="btn btn-primary btn-sm mb-1" id="search" onclick="getaddcustomer_generate();">
									<i class="fal fa-search mr-1"></i> Search
								</button>
								<a href="<?php echo ADMIN_URL; ?>addbillingperiod/add" class="btn btn-warning btn-sm mb-1">
									<i class="fal fa-plus mr-1"></i> Add Billing Period
								</a>
								<a href="<?php echo ADMIN_URL; ?>createbalanceforward/" class="btn btn-success btn-sm mb-1">
									<i class="fal fa-exchange mr-1"></i> Balance Forward
								</a>
								<a id="exporttoexcel" href="#" class="btn btn-primary btn-sm mb-1">
									<i class="fal fa-file-excel mr-1"></i> Export Excel for Mobile
								</a>
								<a href="<?php echo ADMIN_URL; ?>addbillingperiod/import" class="btn btn-info btn-sm mb-1">
									<i class="fal fa-file-import mr-1"></i> Import Excel from Mobile
								</a>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="zone">Zone</label>
									<select class="form-control" name="zone" id="zone">
										<option value="">--All--</option>
										<?php foreach ($zone as $value) { ?>
										<option value="<?php echo $value['id']; ?>"><?php echo htmlspecialchars($value['zone']); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="billingperiod">Billing Period</label>
									<select class="form-control" name="billingperiod" id="billingperiod">
										<option value="">--All--</option>
										<?php foreach ($billingperiod as $value) { ?>
										<option value="<?php echo htmlspecialchars($value['bp_period_month'].' '.$value['bp_period_year']); ?>">
											<?php echo htmlspecialchars($value['month_name'].' '.$value['bp_period_year']); ?>
										</option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>

						<div id="billingPeriodDiv" class="mt-3"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php include('footer.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/datagrid/datatables/datatables.bundle.js"></script>
</body>
</html>
<script type="text/javascript">
(function($) {
	if (typeof pageSetUp === 'function') { pageSetUp(); }
	if ($.fn.sparkline) {
		$('.sparklines').each(function() {
			var $el = $(this);
			$el.sparkline('html', {
				type: $el.attr('sparkType') || 'bar',
				barColor: $el.attr('sparkBarColor') || '#886ab5',
				height: $el.attr('sparkHeight') || '32px',
				barWidth: $el.attr('sparkBarWidth') || '5px'
			});
		});
	}

	function updateExportLink() {
		var zone = $('#zone').val() || '0';
		var bp = ($('#billingperiod').val() || '').trim();
		var month = '0';
		var year = '0';
		if (bp) {
			var parts = bp.split(/\s+/);
			month = parts[0] || '0';
			year = parts[1] || '0';
		}
		$('#exporttoexcel').attr('href',
			<?php echo json_encode(ADMIN_URL . 'addbillingperiod/fileDownloadBillingPeriodMobileSearch/'); ?> +
			encodeURIComponent(zone) + '/' + encodeURIComponent(month) + '/' + encodeURIComponent(year)
		);
	}
	updateExportLink();
	$('#zone, #billingperiod').on('change', updateExportLink);

	window.getaddcustomer_generate = function() {
		updateExportLink();
		if (typeof showSpinner === 'function') { showSpinner(); }
		$.ajax({
			type: 'POST',
			url: <?php echo json_encode(base_url() . 'master/addbillingperiod/addbillingperiod_search'); ?>,
			data: {
				zone: $('#zone').val(),
				billingperiod: $('#billingperiod').val()
			},
			complete: function(data) {
				$('#billingPeriodDiv').html($.trim(data.responseText));
				if ($.fn.DataTable) {
					if ($.fn.DataTable.isDataTable('#dt_basic')) {
						$('#dt_basic').DataTable().destroy();
					}
					if ($('#dt_basic').length) {
						$('#dt_basic').DataTable({
							responsive: true,
							pageLength: 25,
							lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
							order: [[1, 'asc']],
							columnDefs: [{ orderable: false, targets: [0, 9] }],
							language: {
								search: '',
								searchPlaceholder: 'Search billing periods...',
								lengthMenu: '_MENU_',
								info: 'Showing _START_ to _END_ of _TOTAL_ records',
								zeroRecords: 'No matching records'
							}
						});
					}
				}
				if (typeof hideSpinner === 'function') { hideSpinner(); }
			}
		});
	};

	$('#dt_select_all').on('change', function() {
		var checked = $(this).is(':checked');
		$('#dt_basic tbody input[name="delete_ids[]"]').prop('checked', checked);
	});
	$(document).on('change', '#dt_select_all', function() {
		var checked = $(this).is(':checked');
		$('#billingPeriodDiv input[name="delete_ids[]"]').prop('checked', checked);
	});

	function selectedIds() {
		var selectedItems = [];
		$('#billingPeriodDiv input[name="delete_ids[]"]:checked').each(function() {
			selectedItems.push($(this).val());
		});
		return selectedItems;
	}

	$(document).on('click', '#delete', function(evt) {
		evt.preventDefault();
		var selectedItems = selectedIds();
		if (!selectedItems.length) {
			if (typeof Swal !== 'undefined') {
				Swal.fire({ title: 'No selection', text: 'Select at least one checkbox...', icon: 'warning' });
			} else {
				alert('Select Atleast One Check Box... ');
			}
			return false;
		}
		var doDelete = function() {
			if (typeof showSpinner === 'function') { showSpinner(); }
			$.ajax({
				url: <?php echo json_encode(ADMIN_URL . 'addbillingperiod/multi_delete'); ?>,
				type: 'POST',
				data: { delete_ids: selectedItems },
				success: function() {
					if (typeof hideSpinner === 'function') { hideSpinner(); }
					$('#search').trigger('click');
				},
				error: function() {
					if (typeof hideSpinner === 'function') { hideSpinner(); }
					if (typeof Swal !== 'undefined') {
						Swal.fire({ title: 'Error', text: 'Error deleting records. Please try again.', icon: 'error' });
					} else {
						alert('Error deleting records. Please try again.');
					}
				}
			});
		};
		if (typeof window.sa4ConfirmAction === 'function') {
			window.sa4ConfirmAction({
				title: 'Are you sure?',
				text: 'Selected billing periods will be permanently deleted.',
				confirmButtonText: 'Yes, delete them!'
			}).then(function(ok) { if (ok) { doDelete(); } });
		} else if ((typeof sa4SwalConfirmed === 'function') ? false : confirm('Confirm Delete?')) {
			doDelete();
		}
	});

	$(document).on('click', '#close', function(evt) {
		evt.preventDefault();
		var selectedItems = selectedIds();
		if (!selectedItems.length) {
			alert('Select Atleast One Check Box... ');
			return false;
		}
		if (!confirm('Confirm Close?')) { return false; }
		if (typeof showSpinner === 'function') { showSpinner(); }
		$.ajax({
			url: <?php echo json_encode(ADMIN_URL . 'addbillingperiod/multi_close'); ?>,
			type: 'POST',
			data: { delete_ids: selectedItems },
			success: function() {
				if (typeof hideSpinner === 'function') { hideSpinner(); }
				$('#search').trigger('click');
			},
			error: function() {
				if (typeof hideSpinner === 'function') { hideSpinner(); }
			}
		});
	});

	$(document).on('click', '#open', function(evt) {
		evt.preventDefault();
		var selectedItems = selectedIds();
		if (!selectedItems.length) {
			alert('Select Atleast One Check Box... ');
			return false;
		}
		if (!confirm('Confirm Open?')) { return false; }
		if (typeof showSpinner === 'function') { showSpinner(); }
		$.ajax({
			url: <?php echo json_encode(ADMIN_URL . 'addbillingperiod/multi_open'); ?>,
			type: 'POST',
			data: { delete_ids: selectedItems },
			success: function() {
				if (typeof hideSpinner === 'function') { hideSpinner(); }
				$('#search').trigger('click');
			},
			error: function() {
				if (typeof hideSpinner === 'function') { hideSpinner(); }
			}
		});
	});
})(jQuery);
</script>
