<?php
	$r = isset($record) ? $record : array();
	$status = isset($r['status']) ? (int) $r['status'] : 0;
	$type_label = $this->my_model->type_label(isset($r['adj_type']) ? $r['adj_type'] : '');
	$status_label = $this->my_model->status_label($status);
	$dr_name = '';
	$cr_name = '';
	if (!empty($ledgers)) {
		foreach ($ledgers as $L) {
			if ((int) $L['id'] === (int) (isset($r['dr_ledger_id']) ? $r['dr_ledger_id'] : 0)) {
				$dr_name = $L['ledgerName'];
			}
			if ((int) $L['id'] === (int) (isset($r['cr_ledger_id']) ? $r['cr_ledger_id'] : 0)) {
				$cr_name = $L['ledgerName'];
			}
		}
	}
	$badge = 'badge-warning';
	if ($status === 2) { $badge = 'badge-success'; }
	if ($status === 3) { $badge = 'badge-secondary'; }
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>aradjustment">AR Adjustment</a></li>
		<li class="breadcrumb-item active"><?php echo htmlspecialchars(isset($r['adj_no']) ? $r['adj_no'] : '', ENT_QUOTES, 'UTF-8'); ?></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-balance-scale-right"></i>
			AR Adjustment <span class="fw-300"><?php echo htmlspecialchars(isset($r['adj_no']) ? $r['adj_no'] : '', ENT_QUOTES, 'UTF-8'); ?></span>
			<span class="badge <?php echo $badge; ?> ml-2"><?php echo htmlspecialchars($status_label, ENT_QUOTES, 'UTF-8'); ?></span>
		</h1>
	</div>

	<?php if ($this->session->flashdata('msg_succ')) { ?>
	<div class="alert alert-success"><?php echo $this->session->flashdata('msg_succ'); ?></div>
	<?php } ?>
	<?php if ($this->session->flashdata('msg_err')) { ?>
	<div class="alert alert-danger"><?php echo $this->session->flashdata('msg_err'); ?></div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-8">
			<div class="panel">
				<div class="panel-hdr"><h2>Adjustment Details</h2></div>
				<div class="panel-container show">
					<div class="panel-content">
						<table class="table table-bordered">
							<tr><th width="35%">ADJ No</th><td><?php echo htmlspecialchars($r['adj_no'], ENT_QUOTES, 'UTF-8'); ?></td></tr>
							<tr><th>Customer</th>
								<td>
									<?php echo htmlspecialchars($r['customer_id'], ENT_QUOTES, 'UTF-8'); ?>
									<?php if (!empty($r['customer_name'])) { ?>
										— <?php echo htmlspecialchars($r['customer_name'], ENT_QUOTES, 'UTF-8'); ?>
									<?php } ?>
									<a class="btn btn-xs btn-outline-primary ml-2" target="_blank" href="<?php echo site_url('master/statementofaccount/index/'.$r['customer_id']); ?>">Open SOA</a>
								</td>
							</tr>
							<tr><th>Type</th><td><?php echo htmlspecialchars($type_label, ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper(htmlspecialchars($r['adj_direction'], ENT_QUOTES, 'UTF-8')); ?>)</td></tr>
							<tr><th>Amount</th><td><strong>PHP <?php echo number_format((float)$r['adj_amount'], 2); ?></strong></td></tr>
							<tr><th>Date</th><td><?php echo !empty($r['adj_date']) ? date('d-m-Y', strtotime($r['adj_date'])) : ''; ?></td></tr>
							<tr><th>Billing Period</th>
								<td>
									<?php
										$period = '';
										if (!empty($r['month']) && !empty($r['year'])) {
											$period = $r['month'].'/'.$r['year'];
										}
										echo $period !== '' ? htmlspecialchars($period, ENT_QUOTES, 'UTF-8') : '—';
										if (!empty($r['reading_refno'])) {
											echo ' &nbsp; Ref# '.htmlspecialchars($r['reading_refno'], ENT_QUOTES, 'UTF-8');
										}
									?>
								</td>
							</tr>
							<tr><th>Debit Ledger</th><td><?php echo htmlspecialchars($dr_name !== '' ? $dr_name : (string)$r['dr_ledger_id'], ENT_QUOTES, 'UTF-8'); ?></td></tr>
							<tr><th>Credit Ledger</th><td><?php echo htmlspecialchars($cr_name !== '' ? $cr_name : (string)$r['cr_ledger_id'], ENT_QUOTES, 'UTF-8'); ?></td></tr>
							<tr><th>Reason</th><td><?php echo nl2br(htmlspecialchars($r['reason'], ENT_QUOTES, 'UTF-8')); ?></td></tr>
							<tr><th>Remarks</th><td><?php echo nl2br(htmlspecialchars(isset($r['remarks']) ? $r['remarks'] : '', ENT_QUOTES, 'UTF-8')); ?></td></tr>
							<tr><th>Created</th><td><?php echo htmlspecialchars((isset($r['created_by_name'])?$r['created_by_name']:'').' '.($r['create_date_time']??''), ENT_QUOTES, 'UTF-8'); ?></td></tr>
							<?php if ($status >= 2) { ?>
							<tr><th>Posted</th><td><?php echo htmlspecialchars((isset($r['posted_by_name'])?$r['posted_by_name']:'').' '.($r['posted_date_time']??''), ENT_QUOTES, 'UTF-8'); ?></td></tr>
							<?php } ?>
							<?php if ($status === 3) { ?>
							<tr><th>Voided</th><td><?php echo htmlspecialchars((isset($r['voided_by_name'])?$r['voided_by_name']:'').' '.($r['voided_date_time']??''), ENT_QUOTES, 'UTF-8'); ?></td></tr>
							<?php } ?>
						</table>

						<div class="mt-3">
							<a href="<?php echo ADMIN_URL; ?>aradjustment" class="btn btn-secondary">Back</a>
							<?php if ($status === 1) { ?>
							<a href="<?php echo ADMIN_URL; ?>aradjustment/edit/<?php echo (int)$r['adj_id']; ?>" class="btn btn-primary">Edit Draft</a>
							<?php if (!empty($can_approve)) { ?>
							<a href="<?php echo ADMIN_URL; ?>aradjustment/post/<?php echo (int)$r['adj_id']; ?>" class="btn btn-success" onclick="return confirm('Post this adjustment to SOA and GL?');">Post</a>
							<?php } ?>
							<?php } ?>
							<?php if ($status === 2 && !empty($can_approve)) { ?>
							<a href="<?php echo ADMIN_URL; ?>aradjustment/void_entry/<?php echo (int)$r['adj_id']; ?>" class="btn btn-warning" onclick="return confirm('Void this posted adjustment?');">Void</a>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-4">
			<div class="panel">
				<div class="panel-hdr"><h2>Workflow</h2></div>
				<div class="panel-container show">
					<div class="panel-content">
						<ol class="pl-3 mb-0">
							<li><strong>Draft</strong> — Maker encodes customer, type, amount, reason, GL accounts.</li>
							<li><strong>Post</strong> — Approver posts: SOA line + balanced GL entry.</li>
							<li><strong>Void</strong> — Approver voids: reversing GL; removed from SOA (audit kept).</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
