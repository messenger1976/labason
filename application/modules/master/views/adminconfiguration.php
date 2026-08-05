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

	$record = (isset($record) && is_array($record)) ? $record : array();
	if (!empty($record)) { extract($record); }

	$safe = function($v) {
		return htmlspecialchars(stripslashes(str_replace('\n', '', isset($v) ? $v : '')), ENT_QUOTES, 'UTF-8');
	};
	$logo_file = isset($file) ? $file : '';
	$admin_id = isset($adminid) ? (int) $adminid : 0;
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addcustomer/adminconfiguration">Admin Configuration</a></li>
		<li class="breadcrumb-item active">View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-cog"></i>
			Manage <span class="fw-300">Admin Configuration</span>
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
	<?php if ($this->session->flashdata('msg_err')) { ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<?php echo $this->session->flashdata('msg_err'); ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-adminconfiguration" class="panel">
				<div class="panel-hdr">
					<h2>Admin Configuration <span class="fw-300"><i>Details</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<?php if (empty($record)) { ?>
						<div class="alert alert-warning mb-0" role="alert">
							No admin configuration found.
						</div>
						<?php } else { ?>
						<div class="row">
							<div class="col-md-4 mb-3">
								<div class="text-center">
									<img id="blah" class="img-fluid border border-faded p-2" style="max-height:260px; object-fit:contain;"
										src="<?php echo ADMIN_IMG_URL; ?>logo/<?php echo htmlspecialchars($logo_file); ?>"
										alt="Admin Logo">
								</div>
							</div>
							<div class="col-md-8">
								<table class="table table-bordered table-striped mb-0">
									<tbody>
										<tr>
											<th style="width:35%;">Name</th>
											<td><?php echo $safe(isset($name) ? $name : ''); ?></td>
										</tr>
										<tr>
											<th>Info Email</th>
											<td><?php echo $safe(isset($email) ? $email : ''); ?></td>
										</tr>
										<tr>
											<th>Contact Email</th>
											<td><?php echo $safe(isset($email) ? $email : ''); ?></td>
										</tr>
										<tr>
											<th>Established on</th>
											<td><?php echo $safe(isset($established) ? $established : ''); ?></td>
										</tr>
										<tr>
											<th>Phone Number</th>
											<td><?php echo $safe(isset($contact1) ? $contact1 : ''); ?></td>
										</tr>
										<tr>
											<th>Contact Person</th>
											<td><?php echo $safe(isset($contactperson) ? $contactperson : ''); ?></td>
										</tr>
										<tr>
											<th>Contact Person Mobile</th>
											<td><?php echo $safe(isset($contactpersonphone) ? $contactpersonphone : ''); ?></td>
										</tr>
										<tr>
											<th>Website</th>
											<td><?php echo $safe(isset($website) ? $website : ''); ?></td>
										</tr>
										<tr>
											<th>Address</th>
											<td><?php echo $safe(isset($address1) ? $address1 : ''); ?></td>
										</tr>
										<tr>
											<th>About</th>
											<td><?php echo $safe(isset($about) ? $about : ''); ?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>

						<div class="row mt-3">
							<div class="col-md-12">
								<a href="<?php echo ADMIN_URL; ?>" class="btn btn-secondary waves-effect waves-themed">
									<i class="fal fa-times mr-1"></i> Cancel
								</a>
								<?php if ($admin_id > 0) { ?>
								<a href="<?php echo ADMIN_URL; ?>addcustomer/editadminconfiguration/<?php echo $admin_id; ?>" class="btn btn-primary waves-effect waves-themed">
									<i class="fal fa-edit mr-1"></i> Edit
								</a>
								<?php } ?>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
<script type="text/javascript">
$(document).ready(function() {
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
});
</script>
</body>
</html>
