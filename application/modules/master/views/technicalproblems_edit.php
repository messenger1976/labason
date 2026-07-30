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
	$record_messages = (isset($record_messages) && is_array($record_messages)) ? $record_messages : array();
	$employee = (isset($employee) && is_array($employee)) ? $employee : array();

	$status_map = array(
		0 => array('Pending', 'badge-danger'),
		1 => array('Assigned', 'badge-warning'),
		2 => array('On Going', 'badge-info'),
		3 => array('Resolved', 'badge-success'),
		4 => array('Un-Resolved', 'badge-danger'),
		5 => array('Resolved - Closed', 'badge-primary'),
		6 => array('UnResolved - Closed', 'badge-secondary'),
	);
?>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<style>
	.tp-readonly { background-color: #fff8dc !important; }
</style>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>technicalproblems/">Technical Problems</a></li>
		<li class="breadcrumb-item active">Edit</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-tools"></i>
			Manage <span class="fw-300">Technical Problems</span>
		</h1>
		<div class="subheader-block d-lg-flex align-items-center">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>INCOME</small>
				</span>
				<span class="fw-500 fs-xl d-block color-primary-500">
					₱ <?php echo number_format($intotal, 2); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#886ab5" sparkHeight="32px" sparkBarWidth="5px" values="3,4,3,6,7,3,3,6,2,6,4"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>EXPENSE</small>
				</span>
				<span class="fw-500 fs-xl d-block color-danger-500">
					₱ <?php echo number_format($extotal, 2); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#fe6bb0" sparkHeight="32px" sparkBarWidth="5px" values="1,4,3,6,5,3,9,6,5,9,7"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>TOTAL CUSTOMER</small>
				</span>
				<span class="fw-500 fs-xl d-block color-success-500">
					<?php echo (int) (isset($count_id) ? $count_id : 0); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#1dc9b7" sparkHeight="32px" sparkBarWidth="5px" values="2,5,3,7,4,6,3,8,5,4,6"></span>
		</div>
	</div>

	<?php if (!empty($msg)) { ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<?php echo $msg; ?>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-xl-12">
			<div id="panel-technicalproblems-edit" class="panel">
				<div class="panel-hdr">
					<h2>Technical Problems <span class="fw-300"><i>Edit</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<form name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
							<input type="hidden" name="technical_id" id="technical_id" value="<?php echo htmlspecialchars((string) (isset($record['id']) ? $record['id'] : '')); ?>">

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="customer_id">Customer ID</label>
										<input class="form-control tp-readonly" type="text" id="customer_id" name="customer_id" value="<?php echo htmlspecialchars(isset($record['customer_id']) ? $record['customer_id'] : ''); ?>" readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="customer_name">Customer Name</label>
										<input class="form-control tp-readonly" type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars(trim((isset($record['lastname']) ? $record['lastname'] : '').', '.(isset($record['firstname']) ? $record['firstname'] : '').' '.(isset($record['middlename']) ? $record['middlename'] : ''))); ?>" readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="meter_number">Meter Number</label>
										<input class="form-control tp-readonly" type="text" id="meter_number" name="meter_number" value="<?php echo htmlspecialchars(isset($record['meter_number']) ? $record['meter_number'] : ''); ?>" readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="address">Address</label>
										<input class="form-control tp-readonly" type="text" id="address" name="address" value="<?php echo htmlspecialchars(isset($record['address']) ? $record['address'] : ''); ?>" readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="problem_summary">Problem Summary</label>
										<input class="form-control tp-readonly" type="text" id="problem_summary" name="problem_summary" value="<?php echo htmlspecialchars(isset($record['problem_summary']) ? $record['problem_summary'] : ''); ?>" readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="status">Status</label>
										<select class="form-control tp-readonly" name="status" id="status" readonly>
											<option value="0" <?php echo (isset($record['status']) && (int)$record['status'] === 0) ? 'selected' : ''; ?>>Pending</option>
											<option value="1" <?php echo (isset($record['status']) && (int)$record['status'] === 1) ? 'selected' : ''; ?>>Assigned</option>
											<option value="2" <?php echo (isset($record['status']) && (int)$record['status'] === 2) ? 'selected' : ''; ?>>On Going</option>
											<option value="3" <?php echo (isset($record['status']) && (int)$record['status'] === 3) ? 'selected' : ''; ?>>Resolved</option>
											<option value="4" <?php echo (isset($record['status']) && (int)$record['status'] === 4) ? 'selected' : ''; ?>>Un-Resolved</option>
											<option value="5" <?php echo (isset($record['status']) && (int)$record['status'] === 5) ? 'selected' : ''; ?>>Resolved - Closed</option>
											<option value="6" <?php echo (isset($record['status']) && (int)$record['status'] === 6) ? 'selected' : ''; ?>>UnResolved - Closed</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="reportedby">Reported By</label>
										<select class="form-control tp-readonly" name="reportedby" id="reportedby" readonly>
											<?php foreach ($employee as $emp) { ?>
											<option value="<?php echo $emp['id']; ?>" <?php echo (isset($record['reported_by_id']) && $emp['id'] == $record['reported_by_id']) ? 'selected' : ''; ?>>
												<?php echo strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle']; ?>
											</option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="reported_date">Reported Date</label>
										<input class="form-control tp-readonly" type="text" id="reported_date" name="reported_date" readonly value="<?php echo !empty($record['reported_date']) ? date('d-m-Y', strtotime($record['reported_date'])) : date('d-m-Y'); ?>">
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label class="form-label" for="problem_details">Problem Details</label>
										<textarea class="form-control tp-readonly" rows="5" id="problem_details" name="problem_details" readonly><?php echo htmlspecialchars(isset($record['problem_details']) ? $record['problem_details'] : ''); ?></textarea>
									</div>
								</div>
							</div>

							<div class="form-group">
								<button type="button" class="btn btn-primary btn-sm" id="btn_messages" data-toggle="modal" data-target="#myModal">
									<i class="fal fa-comment-alt-plus mr-1"></i> Add Message
								</button>
							</div>

							<div class="table-responsive mb-3">
								<table class="table table-bordered table-hover table-striped w-100">
									<thead class="bg-primary-600">
										<tr>
											<th style="width:12%;">Date</th>
											<th>Message</th>
											<th style="width:16%;">Status</th>
											<th style="width:22%;">Reported by</th>
										</tr>
									</thead>
									<tbody>
										<?php if (count($record_messages) > 0) {
											foreach ($record_messages as $row1) {
												$ms = isset($row1['technical_msg_status']) ? (int) $row1['technical_msg_status'] : 0;
												$ms_label = isset($status_map[$ms]) ? $status_map[$ms][0] : 'Unknown';
												$ms_class = isset($status_map[$ms]) ? $status_map[$ms][1] : 'badge-secondary';
										?>
										<tr>
											<td><?php echo !empty($row1['technical_msg_reported_date']) ? date('m/d/Y', strtotime($row1['technical_msg_reported_date'])) : ''; ?></td>
											<td><?php echo htmlspecialchars(isset($row1['technical_msg_text']) ? $row1['technical_msg_text'] : ''); ?></td>
											<td><span class="badge <?php echo $ms_class; ?>"><?php echo $ms_label; ?></span></td>
											<td><?php echo htmlspecialchars(isset($row1['technical_msg_reported_name']) ? $row1['technical_msg_reported_name'] : ''); ?></td>
										</tr>
										<?php }
										} else { ?>
										<tr>
											<td colspan="4" class="text-center py-3">No record found</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>

							<div class="form-group mb-0">
								<a href="<?php echo ADMIN_URL; ?>technicalproblems" class="btn btn-secondary">
									<i class="fal fa-times mr-1"></i> Cancel
								</a>
								<button type="submit" class="btn btn-primary" name="add" id="add" value="Edit">
									<i class="fal fa-save mr-1"></i> Save
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="tpMsgModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="tpMsgModalLabel">Add Message</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<div class="modal-body">
				<form id="msg_form" method="post" action="javascript:void(0);">
					<input type="hidden" name="technical_id" value="<?php echo htmlspecialchars((string) (isset($record['id']) ? $record['id'] : '')); ?>">
					<input type="hidden" name="btn_save" id="btn_save" value="add">
					<input type="hidden" name="reportedby_name" id="reportedby_name" value="">

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="msg_status">Status</label>
								<select class="form-control" name="msg_status" id="msg_status" required>
									<option value="0">Pending</option>
									<option value="1">Assigned</option>
									<option value="2">On Going</option>
									<option value="3">Resolved</option>
									<option value="4">Un-Resolved</option>
									<option value="5">Resolved - Closed</option>
									<option value="6">UnResolved - Closed</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="posted_date">Posted Date</label>
								<div class="input-group">
									<input type="text" class="form-control" id="posted_date" name="posted_date" readonly placeholder="Select date" value="<?php echo date('d-m-Y'); ?>" required>
									<div class="input-group-append">
										<span class="input-group-text fs-xl">
											<i class="fal fa-calendar"></i>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="form-label" for="msg_reportedby">Reported By</label>
								<select class="form-control" name="msg_reportedby" id="msg_reportedby" required>
									<?php foreach ($employee as $emp) { ?>
									<option value="<?php echo $emp['id']; ?>" data-name="<?php echo htmlspecialchars(strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle']); ?>">
										<?php echo strtoupper($emp['first_name']).' '.strtoupper($emp['middle_name']).' '.strtoupper($emp['last_name']).' - '.$emp['jobtitle']; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group mb-0">
								<label class="form-label" for="msg_logs">Message</label>
								<textarea class="form-control" rows="4" name="msg_logs" id="msg_logs" required></textarea>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="btn_save_message">
					<i class="fal fa-save mr-1"></i> Save Message
				</button>
			</div>
		</div>
	</div>
</div>

<?php include('footer.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/statistics/sparkline/sparkline.bundle.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
</body>
</html>
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

	var controls = {
		leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
		rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
	};

	function syncReportedByName() {
		var name = $('#msg_reportedby option:selected').data('name') || $('#msg_reportedby option:selected').text();
		$('#reportedby_name').val($.trim(name));
	}
	syncReportedByName();
	$('#msg_reportedby').on('change', syncReportedByName);

	if ($.fn.datepicker) {
		$('#posted_date').datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose: true,
			orientation: 'bottom left',
			templates: controls
		});
		$('#posted_date').closest('.input-group').find('.input-group-text').on('click', function() {
			$('#posted_date').datepicker('show');
		});
	}

	$('#btn_save_message').on('click', function() {
		if (!$('#msg_logs').val().trim()) {
			alert('Please enter a message.');
			return;
		}
		syncReportedByName();
		var $btn = $(this);
		$btn.prop('disabled', true);

		$.ajax({
			type: 'POST',
			url: '<?php echo ADMIN_URL; ?>technicalproblems/add_messages',
			data: $('#msg_form').serialize(),
			success: function(resp) {
				if ($.trim(resp) === 'success') {
					window.location.reload();
				} else {
					alert('Failed to save message.');
					$btn.prop('disabled', false);
				}
			},
			error: function() {
				alert('Server error while saving message.');
				$btn.prop('disabled', false);
			}
		});
	});
});
</script>
