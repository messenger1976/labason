<?php
	$mon_id = '';
	$year = '';
	if (!empty($record) && is_array($record)) {
		foreach ($record as $row) {
			$id = $row['id'];
		}
	}
	$ut_pr = array('per_unit' => 0);
	if (!empty($get_unit_price)) {
		foreach ($get_unit_price as $key => $value) {
			$ut_pr = $value;
		}
	}
?>
<div class="mcr-customer-result">
	<div class="table-responsive mb-3">
		<table class="table table-bordered table-hover table-striped w-100 mb-0">
			<thead class="bg-primary-50">
				<tr>
					<th>Customer ID</th>
					<th>Name</th>
					<th>Zone</th>
					<th>Meter #</th>
					<th>Account Type</th>
					<th>Classification</th>
					<th>Previous Reading</th>
					<th>Month</th>
				</tr>
			</thead>
			<tbody>
			<?php if (!empty($record) && is_array($record)) {
				foreach ($record as $row) { ?>
				<tr>
					<td>
						<?php echo htmlspecialchars($row['customer_id']); ?>
						<input type="hidden" id="customer" name="customer" value="<?php echo htmlspecialchars($row['customer_id']); ?>">
						<input type="hidden" id="unit_pr" name="unit_pr" value="<?php echo htmlspecialchars($ut_pr['per_unit']); ?>">
					</td>
					<td><?php echo htmlspecialchars(trim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'])); ?></td>
					<td><?php echo htmlspecialchars($row['zone']); ?></td>
					<td><?php echo htmlspecialchars($row['meter_number']); ?></td>
					<td>
						<?php echo htmlspecialchars($row['cust_type_name']); ?>
						<input type="hidden" id="cust_type_id" name="cust_type_id" value="<?php echo htmlspecialchars($row['cust_type_id']); ?>">
						<input type="hidden" id="special_priviledge" name="special_priviledge" value="<?php echo htmlspecialchars($row['special_priviledge']); ?>">
					</td>
					<td><?php echo htmlspecialchars($row['class_name']); ?></td>
					<td><?php
						if (!empty($last_reading) && is_array($last_reading)) {
							foreach ($last_reading as $last_read) {
								$mon_id = $last_read['month'];
								$year = $last_read['year'];
								echo htmlspecialchars($last_read['reading']);
								echo '<input type="hidden" id="preview_read" name="preview_read" value="' . htmlspecialchars($last_read['reading']) . '">';
							}
						} else {
							echo '0';
							echo '<input type="hidden" id="preview_read" name="preview_read" value="0">';
						}
					?></td>
					<td><?php
						if (!empty($last_reading) && is_array($last_reading)) {
							$get_month = $this->my_model->get_month_name($mon_id);
							foreach ($get_month as $gmon) {
								echo htmlspecialchars($gmon['month_name'] . ' ' . $year);
							}
						} else {
							echo 'No Month';
						}
					?></td>
				</tr>
			<?php }
			} ?>
			</tbody>
		</table>
	</div>

	<?php if (!empty($get_billing_period) && !empty($record)) {
		$row = $record[0];
	?>
	<div class="p-3 rounded border bg-faded mb-3">
		<input type="hidden" name="bp_id" id="bp_id" value="<?php echo htmlspecialchars($get_billing_period['bp_id']); ?>">
		<input type="hidden" name="bp_period_month" id="bp_period_month" value="<?php echo htmlspecialchars($get_billing_period['bp_period_month']); ?>">
		<input type="hidden" name="bp_period_year" id="bp_period_year" value="<?php echo htmlspecialchars($get_billing_period['bp_period_year']); ?>">
		<div class="row">
			<div class="col-md-6 col-lg-4 mb-2 mb-lg-0">
				<span class="d-block text-muted fs-xs text-uppercase">Zone</span>
				<span class="fw-500"><?php echo htmlspecialchars($row['zone']); ?></span>
			</div>
			<div class="col-md-6 col-lg-4 mb-2 mb-lg-0">
				<span class="d-block text-muted fs-xs text-uppercase">Billing Period</span>
				<span class="fw-500"><?php echo htmlspecialchars($get_billing_period['month_name'] . ' ' . $get_billing_period['bp_period_year']); ?></span>
			</div>
			<div class="col-md-6 col-lg-4 mb-2 mb-lg-0">
				<span class="d-block text-muted fs-xs text-uppercase">Start Date</span>
				<span class="fw-500"><?php echo date('M j, Y', strtotime($get_billing_period['bp_start_date'])); ?></span>
			</div>
			<div class="col-md-6 col-lg-4 mb-2 mb-lg-0">
				<span class="d-block text-muted fs-xs text-uppercase">End Date</span>
				<span class="fw-500"><?php echo date('M j, Y', strtotime($get_billing_period['bp_end_date'])); ?></span>
			</div>
			<div class="col-md-6 col-lg-4 mb-2 mb-lg-0">
				<span class="d-block text-muted fs-xs text-uppercase">Due Date</span>
				<span class="fw-500"><?php echo date('M j, Y', strtotime($get_billing_period['bp_due_date'])); ?></span>
			</div>
			<div class="col-md-6 col-lg-4">
				<span class="d-block text-muted fs-xs text-uppercase">Disconnection Date</span>
				<span class="fw-500"><?php echo date('M j, Y', strtotime($get_billing_period['bp_disconnection_date'])); ?></span>
			</div>
		</div>
	</div>

	<button type="button" class="btn btn-success waves-effect waves-themed pay_button" id="add_meter_reading" name="add_meter_reading">
		<i class="fal fa-plus mr-1"></i> Add Meter Reading
	</button>
	<?php } ?>
</div>

<script type="text/javascript">
(function() {
	var bp_month = '<?php echo $mon_id; ?>';
	var bp_year = '<?php echo $year; ?>';
	var bp_current_month = '<?php echo !empty($get_billing_period['bp_period_month']) ? $get_billing_period['bp_period_month'] : ''; ?>';
	var bp_current_year = '<?php echo !empty($get_billing_period['bp_period_year']) ? $get_billing_period['bp_period_year'] : ''; ?>';

	var hideclass = document.getElementById('hideclass');
	var total_setting_2 = document.getElementById('total_setting_2');

	if (hideclass && total_setting_2) {
		if (bp_month == bp_current_month && bp_year == bp_current_year) {
			hideclass.style.display = 'none';
			total_setting_2.style.display = 'none';
		} else {
			hideclass.style.display = 'block';
			total_setting_2.style.display = 'block';
		}
	}
})();
</script>
