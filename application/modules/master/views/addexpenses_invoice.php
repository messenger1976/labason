<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addexpenses">Add Expenses</a></li>
		<li class="breadcrumb-item active">Expenses Invoice</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-file-invoice"></i>
			Expenses <span class="fw-300">Invoice</span>
		</h1>
	</div>

	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Expenses Invoice <span class="fw-300"><i>Details</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<?php if ($this->session->flashdata('msg_succ')) { ?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
							<?php echo $this->session->flashdata('msg_succ'); ?>
						</div>
						<?php } ?>

						<div class="mb-3 text-right">
							<a href="<?php echo site_url(); ?>master/addexpenses/printInvoice/<?php echo $this->uri->segment(4); ?>" class="btn btn-sm btn-primary" target="_blank">
								<i class="fal fa-print mr-1"></i> Print
							</a>
							<a href="<?php echo ADMIN_URL; ?>addexpenses/" class="btn btn-sm btn-secondary">Back</a>
						</div>

						<div class="row mb-3">
							<div class="col-md-8"></div>
							<div class="col-md-4">
								<strong>Admin Address</strong>
								<div><?php echo stripslashes(str_replace('\n', '', $address['content'])); ?></div>
							</div>
						</div>

						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>S No</th>
									<th>Expenses ID</th>
									<th>Expenses Details</th>
									<th>Quantity</th>
									<th>Amount</th>
									<th>Total</th>
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td><strong>Expenses-id:</strong> <?php echo stripslashes($record['expenses_id']); ?></td>
									<td><strong>Expenses Type:</strong> <?php echo stripslashes($record['expenses_type']); ?></td>
									<td><?php echo stripslashes($record['quantity']); ?></td>
									<td class="text-right"><?php echo stripslashes($record['amount']); ?></td>
									<td class="text-right"><?php echo stripslashes($record['total']); ?></td>
									<td><?php echo date('M d, Y ', strtotime($record['date'])); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
</body>
</html>
