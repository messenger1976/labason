<?php
	$sa4_page_icon = 'fal fa-receipt';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'OR Correction';
	$sa4_loading_label = 'OR Correction';
	$sa4_dt_entity = 'OR corrections';
	$sa4_panel_id = 'panel-or-correction';
	$sa4_show_transdate = true;
	$sa4_dt_export_cols = array(0, 1, 2, 3, 4, 5, 6);

	$income1 = $this->my_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->my_model->get_income_monthlycustomer();
	extract($income2);
	$sa4_kpi_income = (isset($total1) ? (float) $total1 : 0) + (isset($total2) ? (float) $total2 : 0);

	$expense1 = $this->my_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->my_model->get_outcome_payroll();
	extract($expense2);
	$sa4_kpi_expense = (isset($extotal1) ? (float) $extotal1 : 0) + (isset($extotal2) ? (float) $extotal2 : 0);

	$total_customer = $this->my_model->total_customer();
	extract($total_customer);
	$sa4_kpi_customers = isset($count_id) ? (int) $count_id : 0;

	$records = (isset($record) && is_array($record)) ? $record : array();
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>dashboard">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>or_correction">OR Transaction</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
<?php include(__DIR__ . '/partials/sa4_kpi_subheader.php'); ?>

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
			<div id="panel-or-correction" class="panel">
				<div class="panel-hdr">
					<h2>Manage OR Correction <span class="fw-300"><i>Listing</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
							<thead class="bg-primary-600">
								<tr>
									<th style="width:70px;">S No</th>
									<th>ID</th>
									<th>OR Number</th>
									<th>Transaction Date</th>
									<th>Customer ID</th>
									<th>Customer Name</th>
									<th>Grand Total</th>
									<th style="width:120px;">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (count($records) > 0) {
									$i = 1;
									foreach ($records as $row) {
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo htmlspecialchars(stripslashes($row['id'])); ?></td>
									<td><?php echo htmlspecialchars(stripslashes($row['or_number'])); ?></td>
									<td><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
									<td><?php echo htmlspecialchars(stripslashes($row['customer_id'])); ?></td>
									<td><?php echo htmlspecialchars(stripslashes($row['name'])); ?></td>
									<td class="text-right"><?php echo number_format((float) $row['grand_total'], 2); ?></td>
									<td>
										<div class="btn-group btn-group-sm" role="group">
											<a class="btn btn-outline-success" href="<?php echo ADMIN_URL; ?>or_correction/edit/<?php echo rawurlencode($row['or_number']); ?>" title="Edit" data-toggle="tooltip">
												<i class="fal fa-edit"></i>
											</a>
											<a class="btn btn-outline-danger" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL; ?>or_correction/delete/<?php echo rawurlencode($row['or_number']); ?>';}" title="Delete" data-toggle="tooltip">
												<i class="fal fa-times"></i>
											</a>
										</div>
									</td>
								</tr>
								<?php
										$i++;
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include('footer.php'); ?>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var controls = {
		leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
		rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
	};

	if ($.fn.datepicker && $('#header_transdate').length) {
		$('#header_transdate').datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose: true,
			orientation: 'bottom left',
			templates: controls
		});
		$('#header_transdate').closest('.input-group').find('.input-group-text').on('click', function() {
			$('#header_transdate').datepicker('show');
		});
		$('#header_transdate').on('changeDate', function() {
			var header_trans_date = $(this).val();
			if (typeof sa4ShowLoader === 'function') { sa4ShowLoader(); }
			$.ajax({
				type: 'POST',
				url: '<?php echo ADMIN_URL; ?>addbillingperiod/updated_headertransdate',
				data: { trans_date: header_trans_date },
				complete: function() {
					location.reload();
				}
			});
		});
	}
});
</script>
</body>
</html>
