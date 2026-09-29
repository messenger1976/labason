<?php
/**
 * Online / QR Ph payment history.
 *
 * Covers every state, not just paid — a lapsed or failed code is exactly what an
 * operator needs to see, and a pending one is what they need to chase.
 *
 * Expects: $ready, $filters, $rows, $total, $page, $limit, $totals
 */
$ready = !empty($ready);
$rows = isset($rows) && is_array($rows) ? $rows : array();
$filters = isset($filters) && is_array($filters) ? $filters : array();
$totals = isset($totals) && is_array($totals) ? $totals : array();
$total = isset($total) ? (int) $total : 0;
$page = isset($page) ? (int) $page : 1;
$limit = isset($limit) && (int) $limit > 0 ? (int) $limit : 25;
$pages = $limit > 0 ? (int) ceil($total / $limit) : 1;
$flash = $this->session->flashdata('msg_succ');

$h = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
$status_options = array('all' => 'All statuses', 'pending' => 'Pending', 'paid' => 'Paid', 'expired' => 'Expired', 'failed' => 'Failed', 'cancelled' => 'Cancelled');
$context_options = array('all' => 'All sources', 'mobile' => 'Mobile app', 'desktop' => 'Backend', 'customer_link' => 'Customer link');
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>onlinepayment">Online Payment (QR Ph)</a></li>
		<li class="breadcrumb-item active">Payment History</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-history"></i>
			Online Payment <span class="fw-300">History</span>
		</h1>
		<div class="subheader-block d-lg-flex align-items-center">
			<a href="<?php echo ADMIN_URL; ?>onlinepayment" class="btn btn-primary waves-effect waves-themed">
				<i class="fal fa-plus mr-1"></i> New Payment
			</a>
		</div>
	</div>

	<?php if ($flash) { ?>
	<div class="alert alert-info alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fal fa-times"></i></span></button>
		<?php echo $flash; ?>
	</div>
	<?php } ?>

	<?php if (!$ready) { ?>
	<div class="alert alert-warning" role="alert">
		<i class="fal fa-exclamation-triangle mr-1"></i>
		<strong>Not installed yet.</strong> Run <code>sql/add_online_payments.sql</code> first.
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-lg-3 col-md-6">
			<div class="card mb-3 border-success">
				<div class="card-body">
					<span class="fw-300 fs-xs d-block opacity-50"><small>COLLECTED</small></span>
					<span class="fw-500 fs-xl d-block text-success">&#8369; <?php echo number_format(isset($totals['paid_amount']) ? $totals['paid_amount'] : 0, 2); ?></span>
					<small class="text-muted"><?php echo (int) (isset($totals['paid_count']) ? $totals['paid_count'] : 0); ?> paid</small>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="card mb-3 border-warning">
				<div class="card-body">
					<span class="fw-300 fs-xs d-block opacity-50"><small>OUTSTANDING</small></span>
					<span class="fw-500 fs-xl d-block text-warning">&#8369; <?php echo number_format(isset($totals['open_amount']) ? $totals['open_amount'] : 0, 2); ?></span>
					<small class="text-muted"><?php echo (int) (isset($totals['open_count']) ? $totals['open_count'] : 0); ?> pending / expired</small>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="card mb-3">
				<div class="card-body">
					<span class="fw-300 fs-xs d-block opacity-50"><small>ATTEMPTS</small></span>
					<span class="fw-500 fs-xl d-block"><?php echo (int) (isset($totals['count']) ? $totals['count'] : 0); ?></span>
					<small class="text-muted">in this filter</small>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="card mb-3">
				<div class="card-body">
					<span class="fw-300 fs-xs d-block opacity-50"><small>ATTEMPTED VALUE</small></span>
					<span class="fw-500 fs-xl d-block">&#8369; <?php echo number_format(isset($totals['attempted_amount']) ? $totals['attempted_amount'] : 0, 2); ?></span>
					<small class="text-muted">all statuses</small>
				</div>
			</div>
		</div>
	</div>

	<div class="panel mb-3">
		<div class="panel-hdr">
			<h2>Filter</h2>
		</div>
		<div class="panel-container show">
			<form method="GET" action="<?php echo ADMIN_URL; ?>onlinepayment/manage">
				<div class="panel-content">
					<div class="row">
						<div class="col-md-3 mb-2">
							<label class="form-label" for="f_q">Reference / customer</label>
							<input type="text" class="form-control" id="f_q" name="q" value="<?php echo $h(isset($filters['q']) ? $filters['q'] : ''); ?>">
						</div>
						<div class="col-md-2 mb-2">
							<label class="form-label" for="f_status">Status</label>
							<select class="form-control" id="f_status" name="status">
								<?php foreach ($status_options as $key => $label) { ?>
								<option value="<?php echo $h($key); ?>" <?php echo (isset($filters['status']) && $filters['status'] === $key) ? 'selected' : ''; ?>><?php echo $h($label); ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-2 mb-2">
							<label class="form-label" for="f_context">Source</label>
							<select class="form-control" id="f_context" name="context">
								<?php foreach ($context_options as $key => $label) { ?>
								<option value="<?php echo $h($key); ?>" <?php echo (isset($filters['context']) && $filters['context'] === $key) ? 'selected' : ''; ?>><?php echo $h($label); ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-2 mb-2">
							<label class="form-label" for="f_from">From</label>
							<input type="date" class="form-control" id="f_from" name="date_from" value="<?php echo $h(isset($filters['date_from']) ? $filters['date_from'] : ''); ?>">
						</div>
						<div class="col-md-2 mb-2">
							<label class="form-label" for="f_to">To</label>
							<input type="date" class="form-control" id="f_to" name="date_to" value="<?php echo $h(isset($filters['date_to']) ? $filters['date_to'] : ''); ?>">
						</div>
						<div class="col-md-1 mb-2">
							<label class="form-label d-none d-md-block">&nbsp;</label>
							<button type="submit" class="btn btn-primary btn-block"><i class="fal fa-filter"></i></button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="panel mb-3">
		<div class="panel-hdr">
			<h2>Payments <span class="fw-300"><i><?php echo number_format($total); ?> found</i></span></h2>
		</div>
		<div class="panel-container show">
			<div class="panel-content p-0">
				<?php if (empty($rows)) { ?>
				<div class="p-3 text-muted">No online payments match this filter.</div>
				<?php } else { ?>
				<div class="table-responsive">
					<table class="table table-sm table-striped table-hover mb-0">
						<thead class="bg-primary-600 bg-primary-gradient">
							<tr>
								<th>Reference</th>
								<th>Customer</th>
								<th class="text-right">Amount</th>
								<th>Status</th>
								<th>Source</th>
								<th>Created</th>
								<th>Emailed to</th>
								<th style="width:250px;">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($rows as $row) {
								$status = isset($row['status']) ? $row['status'] : 'pending';
								$status_class = $this->my_model->status_class($status);
								$is_pending = ($status === 'pending');
							?>
							<tr>
								<td>
									<code><?php echo $h($row['reference_no']); ?></code>
									<?php if (!empty($row['payment_id'])) { ?>
									<div class="small text-muted"><?php echo $h($row['payment_id']); ?></div>
									<?php } ?>
								</td>
								<td>
									<a href="<?php echo ADMIN_URL; ?>addpaymentcustomer/get_monthly_customer_invoice/<?php echo (int) (isset($row['id']) ? $row['id'] : 0); ?>" class="d-none"></a>
									<?php echo $h($row['customer_id']); ?>
								</td>
								<td class="text-right fw-700">&#8369; <?php echo number_format((float) $row['amount'], 2); ?></td>
								<td><span class="badge badge-<?php echo $h($status_class); ?>"><?php echo $h($this->my_model->status_label($status)); ?></span></td>
								<td><small class="text-muted"><?php echo $h($this->my_model->status_label(isset($row['context']) ? $row['context'] : '')); ?></small></td>
								<td><small><?php echo $h(isset($row['create_date_time']) ? $row['create_date_time'] : ''); ?></small></td>
								<td><small class="text-muted"><?php echo $h(isset($row['emailed_to']) && $row['emailed_to'] !== '' ? $row['emailed_to'] : '—'); ?></small></td>
								<td>
									<div class="btn-group btn-group-sm" role="group">
										<?php if ($is_pending) { ?>
										<form method="POST" action="<?php echo ADMIN_URL; ?>onlinepayment/check_status/<?php echo (int) $row['id']; ?>" class="d-inline">
											<button type="submit" class="btn btn-outline-primary" title="Check with PayMongo and settle if paid">
												<i class="fal fa-sync"></i>
											</button>
										</form>
										<form method="POST" action="<?php echo ADMIN_URL; ?>onlinepayment/cancel/<?php echo (int) $row['id']; ?>" class="d-inline"
											onsubmit="return confirm('Cancel <?php echo $h($row['reference_no']); ?>? The customer&rsquo;s code will stop working.');">
											<button type="submit" class="btn btn-outline-danger" title="Cancel this QR">
												<i class="fal fa-ban"></i>
											</button>
										</form>
										<?php } ?>
										<?php if ($status !== 'paid' && $status !== 'cancelled') { ?>
										<button type="button" class="btn btn-outline-info op_resend" data-id="<?php echo (int) $row['id']; ?>" title="Email the payment link again">
											<i class="fal fa-envelope"></i>
										</button>
										<?php } ?>
										<button type="button" class="btn btn-outline-secondary op_link" data-id="<?php echo (int) $row['id']; ?>" title="Copy the customer payment link">
											<i class="fal fa-link"></i>
										</button>
										<?php if ($status === 'paid') { ?>
										<a href="<?php echo ADMIN_URL; ?>onlinepayment/receipt/<?php echo (int) $row['id']; ?>" target="_blank" class="btn btn-outline-success" title="Print 80mm receipt">
											<i class="fal fa-print"></i>
										</a>
										<?php } ?>
									</div>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<?php } ?>
			</div>
			<?php if ($pages > 1) { ?>
			<div class="panel-content border-top">
				<nav>
					<ul class="pagination pagination-sm mb-0">
						<?php for ($p = 1; $p <= $pages; $p++) {
							if ($pages > 12 && $p > 3 && $p < ($pages - 2) && abs($p - $page) > 1) {
								if ($p === 4) { echo '<li class="page-item disabled"><span class="page-link">…</span></li>'; }
								continue;
							}
							$qs = $_GET;
							$qs['page'] = $p;
						?>
						<li class="page-item <?php echo $p === $page ? 'active' : ''; ?>">
							<a class="page-link" href="<?php echo ADMIN_URL; ?>onlinepayment/manage?<?php echo $h(http_build_query($qs)); ?>"><?php echo $p; ?></a>
						</li>
						<?php } ?>
					</ul>
				</nav>
			</div>
			<?php } ?>
		</div>
	</div>
</main>

<script type="text/javascript">
(function ($) {
	'use strict';

	function note(kind, text) {
		var el = $('#op_manage_msg');
		if (!el.length) {
			el = $('<div id="op_manage_msg" class="alert alert-' + kind + ' mt-2 mb-0">' + $('<div>').text(text).html() + '</div>');
			$('.panel.mb-3').first().before(el);
		} else {
			el.attr('class', 'alert alert-' + kind + ' mt-2 mb-0').html($('<div>').text(text).html());
		}
	}

	$(document).on('click', '.op_link', function () {
		var id = $(this).data('id');
		$.ajax({
			type: 'GET',
			dataType: 'json',
			url: '<?php echo ADMIN_URL; ?>onlinepayment/link/' + id
		}).done(function (json) {
			if (!json || !json.ok) {
				note('danger', (json && json.message) || 'Unable to create a link.');
				return;
			}
			try {
				if (navigator.clipboard) { navigator.clipboard.writeText(json.link); }
				else { window.prompt('Copy this link', json.link); }
				note('success', 'Payment link copied to the clipboard.');
			} catch (e) {
				window.prompt('Copy this link', json.link);
			}
		}).fail(function () {
			note('danger', 'Unable to create a link.');
		});
	});

	$(document).on('click', '.op_resend', function () {
		var id = $(this).data('id');
		var email = window.prompt('Email the payment link to:', '');
		if (email === null) { return; }
		var form = $('<form method="POST"></form>')
			.attr('action', '<?php echo ADMIN_URL; ?>onlinepayment/resend/' + id)
			.append($('<input type="hidden" name="email">').val(email));
		$('body').append(form);
		form.submit();
	});
})(jQuery);
</script>

<?php include('footer.php'); ?>
