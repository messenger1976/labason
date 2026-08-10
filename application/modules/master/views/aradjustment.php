<?php
	$status_badge = function ($status) {
		$status = (int) $status;
		if ($status === 2) {
			return '<span class="badge badge-success">Posted</span>';
		}
		if ($status === 3) {
			return '<span class="badge badge-secondary">Void</span>';
		}
		return '<span class="badge badge-warning">Draft</span>';
	};
	$sa4_page_icon = 'fal fa-balance-scale-right';
	$sa4_page_title = 'AR Adjustment';
	$sa4_page_subtitle = 'Accounts Receivable';
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>aradjustment">Accounting</a></li>
		<li class="breadcrumb-item active">AR Adjustment</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<?php include(dirname(__FILE__).'/partials/sa4_kpi_subheader.php'); ?>

	<?php if ($this->session->flashdata('msg_succ')) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<strong>Success!</strong> <?php echo $this->session->flashdata('msg_succ'); ?>
	</div>
	<?php } ?>
	<?php if ($this->session->flashdata('msg_err')) { ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<strong>Error!</strong> <?php echo $this->session->flashdata('msg_err'); ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-ar-adjustment-list" class="panel">
				<div class="panel-hdr">
					<h2>AR Adjustment <span class="fw-300"><i>Listing</i></span></h2>
					<div class="panel-toolbar">
						<a href="<?php echo ADMIN_URL; ?>aradjustment/add" class="btn btn-primary btn-sm waves-effect waves-themed mr-2">
							<i class="fal fa-plus mr-1"></i> New Adjustment
						</a>
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<p class="text-muted mb-3">
							Use this module to post <strong>Credit Notes</strong>, <strong>Write-offs</strong>, <strong>Debit Memos</strong>, or <strong>Billing Corrections</strong>
							that adjust the customer Statement of Account. Drafts are prepared by makers; posting requires <strong>AR Adjustment Approve</strong> permission.
						</p>
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped w-100" id="ar_adj_table">
								<thead>
									<tr>
										<th>ADJ No</th>
										<th>Date</th>
										<th>Customer</th>
										<th>Type</th>
										<th class="text-right">Amount</th>
										<th>Direction</th>
										<th>Status</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
								<?php if (!empty($records)) {
									foreach ($records as $row) {
										$adj_id = (int) $row['adj_id'];
										$type_label = $this->my_model->type_label($row['adj_type']);
								?>
									<tr>
										<td><strong><?php echo htmlspecialchars($row['adj_no'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
										<td><?php echo !empty($row['adj_date']) ? date('d-m-Y', strtotime($row['adj_date'])) : ''; ?></td>
										<td>
											<?php echo htmlspecialchars($row['customer_id'], ENT_QUOTES, 'UTF-8'); ?>
											<?php if (!empty($row['customer_name'])) { ?>
												<br><small class="text-muted"><?php echo htmlspecialchars($row['customer_name'], ENT_QUOTES, 'UTF-8'); ?></small>
											<?php } ?>
										</td>
										<td><?php echo htmlspecialchars($type_label, ENT_QUOTES, 'UTF-8'); ?></td>
										<td class="text-right">PHP <?php echo number_format((float) $row['adj_amount'], 2); ?></td>
										<td><?php echo strtoupper(htmlspecialchars($row['adj_direction'], ENT_QUOTES, 'UTF-8')); ?></td>
										<td><?php echo $status_badge($row['status']); ?></td>
										<td>
											<div class="btn-group btn-group-sm" role="group">
												<a class="btn btn-outline-info" href="<?php echo ADMIN_URL; ?>aradjustment/view/<?php echo $adj_id; ?>" title="View"><i class="fal fa-eye"></i></a>
												<?php if ((int) $row['status'] === 1) { ?>
												<a class="btn btn-outline-primary" href="<?php echo ADMIN_URL; ?>aradjustment/edit/<?php echo $adj_id; ?>" title="Edit"><i class="fal fa-edit"></i></a>
												<a class="btn btn-outline-danger" href="<?php echo ADMIN_URL; ?>aradjustment/delete/<?php echo $adj_id; ?>" title="Delete draft" onclick="return confirm('Delete this draft?');"><i class="fal fa-trash"></i></a>
												<?php } ?>
												<?php if (!empty($can_approve) && (int) $row['status'] === 1) { ?>
												<a class="btn btn-outline-success" href="<?php echo ADMIN_URL; ?>aradjustment/post/<?php echo $adj_id; ?>" title="Post" onclick="return confirm('Post this adjustment to SOA and GL?');"><i class="fal fa-check"></i></a>
												<?php } ?>
												<?php if (!empty($can_approve) && (int) $row['status'] === 2) { ?>
												<a class="btn btn-outline-warning" href="<?php echo ADMIN_URL; ?>aradjustment/void_entry/<?php echo $adj_id; ?>" title="Void" onclick="return confirm('Void this posted adjustment?');"><i class="fal fa-ban"></i></a>
												<?php } ?>
											</div>
										</td>
									</tr>
								<?php }
								} else { ?>
									<tr><td colspan="8" class="text-center text-muted">No AR adjustments yet.</td></tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/datagrid/datatables/datatables.bundle.js"></script>
<script>
$(document).ready(function(){
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
	if ($.fn.DataTable && $('#ar_adj_table tbody tr td[colspan]').length === 0) {
		$('#ar_adj_table').DataTable({
			responsive: true,
			order: [[0, 'desc']],
			pageLength: 25
		});
	}
});
</script>
