<?php
	$records = (isset($record) && is_array($record)) ? $record : array();
	$statistics = (isset($statistics) && is_array($statistics)) ? $statistics : array();
?>
<div class="row">
	<div class="col-sm-6 col-xl-3">
		<div class="p-3 bg-primary-400 rounded overflow-hidden position-relative text-white mb-g">
			<div>
				<h3 class="display-4 d-block l-h-n m-0 fw-500"><?php echo number_format(isset($statistics['total']) ? $statistics['total'] : 0); ?></h3>
				<span class="opacity-70">Total Members</span>
			</div>
			<i class="fal fa-users position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:5rem"></i>
		</div>
	</div>
	<div class="col-sm-6 col-xl-3">
		<div class="p-3 bg-success-400 rounded overflow-hidden position-relative text-white mb-g">
			<div>
				<h3 class="display-4 d-block l-h-n m-0 fw-500"><?php echo number_format(isset($statistics['active']) ? $statistics['active'] : 0); ?></h3>
				<span class="opacity-70">Active Members</span>
			</div>
			<i class="fal fa-check-circle position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:5rem"></i>
		</div>
	</div>
	<div class="col-sm-6 col-xl-3">
		<div class="p-3 bg-warning-400 rounded overflow-hidden position-relative text-white mb-g">
			<div>
				<h3 class="display-4 d-block l-h-n m-0 fw-500"><?php echo number_format(isset($statistics['inactive']) ? $statistics['inactive'] : 0); ?></h3>
				<span class="opacity-70">Inactive Members</span>
			</div>
			<i class="fal fa-pause-circle position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:5rem"></i>
		</div>
	</div>
	<div class="col-sm-6 col-xl-3">
		<div class="p-3 bg-danger-400 rounded overflow-hidden position-relative text-white mb-g">
			<div>
				<h3 class="display-4 d-block l-h-n m-0 fw-500"><?php echo number_format(isset($statistics['deactivated']) ? $statistics['deactivated'] : 0); ?></h3>
				<span class="opacity-70">Deactivated Members</span>
			</div>
			<i class="fal fa-times-circle position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1" style="font-size:5rem"></i>
		</div>
	</div>
</div>

<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
	<thead class="bg-primary-600">
		<tr>
			<th>S No</th>
			<th>Ref No</th>
			<th>Customer ID</th>
			<th>Customer Name</th>
			<th>Address</th>
			<th>Zone</th>
			<th>Previous Reading</th>
			<th>Current Reading</th>
			<th>Arrears</th>
			<th>Maintenance Fee</th>
			<th>Billing Period</th>
		</tr>
	</thead>
	<tbody>
		<?php if (count($records) > 0) {
			$i = 1;
			foreach ($records as $row) {
		?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo htmlspecialchars(stripslashes($row['refno'])); ?></td>
			<td><?php echo htmlspecialchars(stripslashes($row['customer_id'])); ?></td>
			<td><?php echo htmlspecialchars(stripslashes(trim($row['first_name'].' '.$row['last_name']))); ?></td>
			<td><?php echo htmlspecialchars(stripslashes($row['address'])); ?></td>
			<td><?php echo htmlspecialchars(stripslashes($row['zone_name'])); ?></td>
			<td><?php echo htmlspecialchars(stripslashes($row['previous_reading'])); ?></td>
			<td><?php echo htmlspecialchars(stripslashes($row['current_reading'])); ?></td>
			<td><?php echo number_format((float) $row['arrears'], 2); ?></td>
			<td><?php echo number_format((float) $row['maintenance_fee'], 2); ?></td>
			<td><?php echo htmlspecialchars(stripslashes($row['month_name'].' '.$row['year'])); ?></td>
		</tr>
		<?php
				$i++;
			}
		} ?>
	</tbody>
</table>
