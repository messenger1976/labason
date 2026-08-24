<?php
	$sa4_page_icon = 'fal fa-book';
	$sa4_page_title = 'Journal Voucher';
	$sa4_page_subtitle = 'GL listing';
	$sa4_loading_label = 'Journal vouchers';
	$sa4_dt_entity = 'journalvoucher';
	$sa4_panel_id = 'panel-addjournalvoucher';
	$has_particulars = $this->db->field_exists('particulars', 'tbl_transactions');
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addjournalvoucher">Accounting</a></li>
		<li class="breadcrumb-item active">Journal Voucher</li>
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

	<section id="widget-grid" class="">
		<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
		<div class="row">
			<div class="col-xl-12">
				<div id="panel-addjournalvoucher" class="panel">
					<div class="panel-hdr">
						<h2>Journal Voucher <span class="fw-300"><i>Listing</i></span></h2>
						<div class="panel-toolbar">
							<a href="<?php echo ADMIN_URL; ?>addjournalvoucher/add/" class="btn btn-success btn-sm waves-effect waves-themed mr-2">
								<i class="fal fa-plus mr-1"></i> Add
							</a>
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<p class="text-muted mb-3">
								Manual GL journal vouchers (<code>JV-####</code>). Each voucher posts two lines (Debit + Credit).
								Does not appear on Daily Collection Report.
							</p>
							<form method="post" action="<?php echo ADMIN_URL; ?>addjournalvoucher/multi_delete" id="sa4-list-form">
								<div class="row mb-3 align-items-end">
									<div class="col-sm-6 col-md-6">
										<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
											<i class="fal fa-trash-alt mr-1"></i> Delete Selected
										</button>
									</div>
									<div class="col-sm-6 col-md-6 text-right">
										<a href="<?php echo ADMIN_URL; ?>addjournalvoucher/add/" class="btn btn-success btn-sm waves-effect waves-themed">
											<i class="fal fa-plus mr-1"></i> Add
										</a>
									</div>
								</div>
								<table id="dt_basic" class="table table-striped table-bordered table-hover w-100">
									<thead>
										<tr>
											<th style="width:28px;"><input type="checkbox" class="checkbox" /></th>
											<th>Sr.No.</th>
											<th>Voucher No.</th>
											<th>Date</th>
											<th>Ledger</th>
											<th class="text-right">Debit</th>
											<th class="text-right">Credit</th>
											<?php if ($has_particulars) { ?><th>Particulars</th><?php } ?>
											<th style="width:80px;">Action</th>
										</tr>
									</thead>
									<tbody>
									<?php
									if (!empty($record) && count($record) > 0) {
										$i = 1;
										foreach ($record as $row) {
											$party = $this->my_model->party_label(
												isset($row['ledger_id']) ? $row['ledger_id'] : '',
												isset($row['ledger_id_for']) ? $row['ledger_id_for'] : ''
											);
											$debit = isset($row['debit']) ? $row['debit'] : '';
											$credit = isset($row['credit']) ? $row['credit'] : '';
									?>
										<tr>
											<td><input type="checkbox" class="checkbox" name="delete_ids[]" value="<?php echo (int) $row['id']; ?>" /></td>
											<td><?php echo $i; ?></td>
											<td><?php echo htmlspecialchars(stripslashes($row['voucherNo']), ENT_QUOTES, 'UTF-8'); ?></td>
											<td><?php echo htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8'); ?></td>
											<td><?php echo htmlspecialchars($party, ENT_QUOTES, 'UTF-8'); ?></td>
											<td class="text-right"><?php echo htmlspecialchars((string) $debit, ENT_QUOTES, 'UTF-8'); ?></td>
											<td class="text-right"><?php echo htmlspecialchars((string) $credit, ENT_QUOTES, 'UTF-8'); ?></td>
											<?php if ($has_particulars) { ?>
											<td><?php echo htmlspecialchars(isset($row['particulars']) ? $row['particulars'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
											<?php } ?>
											<td>
												<a class="btn btn-outline-danger btn-sm" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL; ?>addjournalvoucher/delete/<?php echo (int) $row['id']; ?>';}" title="Delete">
													<i class="fal fa-times"></i>
												</a>
											</td>
										</tr>
									<?php
											$i++;
										}
									}
									?>
									</tbody>
								</table>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include('footer.php'); ?>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
<script type="text/javascript">
function deleteAllData() {
	if (!$('#sa4-list-form input[name="delete_ids[]"]:checked').length) {
		alert('Select at least one row.');
		return false;
	}
	return confirm('Delete selected journal lines?');
}
</script>
</body>
</html>
