<?php
	$records = (isset($record) && is_array($record)) ? $record : array();
	$seg3 = $this->uri->segment(3);
?>
<div class="row mb-3">
	<div class="col-lg-12">
		<button type="button" class="btn btn-sm btn-primary" id="delete">
			<i class="fal fa-trash-alt mr-1"></i> Delete All
		</button>
		<button type="button" class="btn btn-sm btn-danger" id="close">
			<i class="fal fa-lock mr-1"></i> Close
		</button>
		<button type="button" class="btn btn-sm btn-success" id="open">
			<i class="fal fa-lock-open mr-1"></i> Open
		</button>
	</div>
</div>

<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
	<thead class="bg-primary-600">
		<tr>
			<th style="width:30px;"><input type="checkbox" id="dt_select_all" /></th>
			<th>S No</th>
			<th>Zone</th>
			<th>Billing Period</th>
			<th>Start Date</th>
			<th>End Date</th>
			<th>Due Date</th>
			<th>Disconnection Date</th>
			<th>Status</th>
			<th style="width:120px;">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php if (count($records) > 0) {
			$i = 1;
			foreach ($records as $row) {
				$status = isset($row['bp_status']) ? (int) $row['bp_status'] : 0;
				$status_url = ADMIN_URL.'addbillingperiod/status/'.$row['bp_id'].'/'.$status.'/'.$seg3;
		?>
		<tr>
			<td>
				<input type="checkbox" class="checkbox" name="delete_ids[]" value="<?php echo $row['bp_id']; ?>" />
			</td>
			<td><?php echo $i; ?></td>
			<td><?php echo htmlspecialchars(stripslashes($row['zone_name'])); ?></td>
			<td><?php echo htmlspecialchars(stripslashes($row['month_name'].' '.$row['bp_period_year'])); ?></td>
			<td><?php echo htmlspecialchars(stripslashes($row['bp_start_date'])); ?></td>
			<td><?php echo htmlspecialchars(stripslashes($row['bp_end_date'])); ?></td>
			<td><?php echo htmlspecialchars(stripslashes($row['bp_due_date'])); ?></td>
			<td><?php echo htmlspecialchars(stripslashes($row['bp_disconnection_date'])); ?></td>
			<td>
				<?php if ($status === 1) { ?>
				<a href="JavaScript:if(confirm('Are you sure want to Change the Status?')==true){window.location='<?php echo $status_url; ?>';}" class="badge badge-success" style="color:#fff;text-decoration:none;">Active</a>
				<?php } else { ?>
				<a href="JavaScript:if(confirm('Are you sure want to Change the Status?')==true){window.location='<?php echo $status_url; ?>';}" class="badge badge-danger" style="color:#fff;text-decoration:none;">De-Active</a>
				<?php } ?>
			</td>
			<td>
				<div class="btn-group btn-group-sm" role="group">
					<a class="btn btn-outline-success" href="<?php echo ADMIN_URL; ?>addbillingperiod/edit/<?php echo $row['bp_id']; ?>" title="Edit" data-toggle="tooltip">
						<i class="fal fa-edit"></i>
					</a>
					<a class="btn btn-outline-danger" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL; ?>addbillingperiod/delete/<?php echo $row['bp_id']; ?>';}" title="Delete" data-toggle="tooltip">
						<i class="fal fa-times"></i>
					</a>
				</div>
			</td>
		</tr>
		<?php
				$i++;
			}
		} ?>
	</tbody>
</table>
