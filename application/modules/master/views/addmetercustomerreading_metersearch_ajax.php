<?php
	$records = (isset($record) && is_array($record)) ? $record : array();
?>
<?php if (count($records) === 0) { ?>
<div class="alert alert-info mb-0" role="alert">
	No meter reading records found for this customer.
</div>
<?php } else { ?>
<table id="dt_basic" class="table table-bordered table-hover table-striped w-100">
	<thead class="bg-primary-600">
		<tr>
			<th style="width:60px;">S No</th>
			<th>ID</th>
			<th>Billing Period</th>
			<th>Billing Number</th>
			<th>Customer ID</th>
			<th>Name</th>
			<th>Previous Reading</th>
			<th>Current Reading</th>
			<th>Consumed</th>
			<th>Bill Amount</th>
			<th>WMMF</th>
			<th>Franchise Tax %</th>
			<th>Franchise Tax Amt</th>
			<th>SC Discount</th>
			<th>Total</th>
			<th>Penalty</th>
			<th>Arrears</th>
			<th>Reading Date</th>
			<th>Status</th>
			<th style="width:90px;">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$i = 1;
			foreach ($records as $row) {
		?>
		<tr>
			<td><?php echo $i; ?></td>
			<td class="id"><?php echo htmlspecialchars(stripslashes($row['id'])); ?></td>
			<td class="billing_period"><?php echo htmlspecialchars(stripslashes($row['month_name'].' '.$row['year'])); ?></td>
			<td class="refno"><?php echo htmlspecialchars(stripslashes($row['refno'])); ?></td>
			<td class="customer_id"><?php echo htmlspecialchars(stripslashes($row['customer_id'])); ?></td>
			<td class="fullname"><?php echo htmlspecialchars(stripslashes($row['last_name'].', '.$row['first_name'].' '.$row['middle_name'])); ?></td>
			<td class="previous_reading text-center"><?php echo htmlspecialchars(stripslashes($row['previous_reading'])); ?></td>
			<td class="current_reading text-center"><?php echo htmlspecialchars(stripslashes($row['reading'])); ?></td>
			<td class="consumed text-center"><?php echo htmlspecialchars(stripslashes($row['consumed'])); ?></td>
			<td class="current_bill text-right"><?php echo htmlspecialchars(stripslashes($row['unit_price'])); ?></td>
			<td class="maintenance_fee text-right"><?php echo number_format((float) $row['maintenance_fee'], 2); ?></td>
			<td class="franchise_fee_percent text-right"><?php echo number_format(isset($row['franchise_fee_percent']) ? (float) $row['franchise_fee_percent'] : 0, 2); ?></td>
			<td class="franchise_fee_amount text-right"><?php echo number_format(isset($row['franchise_fee_amount']) ? (float) $row['franchise_fee_amount'] : 0, 2); ?></td>
			<td class="sc_discount text-right"><?php echo htmlspecialchars(stripslashes($row['sc_discount'])); ?></td>
			<td class="total_amount text-right"><?php echo number_format((float) $row['amount'], 2); ?></td>
			<td class="penalty text-right"><?php echo number_format((float) $row['penalty'], 2); ?></td>
			<td class="arrears text-right"><?php echo number_format((float) $row['arrears'], 2); ?></td>
			<td class="reading_date text-center"><?php echo !empty($row['date']) ? date('d-m-Y', strtotime($row['date'])) : ''; ?></td>
			<td>
				<?php if ((int) $row['customer_status'] === 1) { ?>
				<span class="badge badge-success">Active</span>
				<?php } else { ?>
				<span class="badge badge-danger">Disconnected</span>
				<?php } ?>
			</td>
			<td>
				<div class="btn-group btn-group-sm" role="group">
					<a class="btn btn-outline-success btn_edit"
						href="javascript:void(0);"
						data-id="<?php echo htmlspecialchars(stripslashes($row['id'])); ?>"
						data-billing_period="<?php echo htmlspecialchars(stripslashes($row['month_name'].' '.$row['year'])); ?>"
						data-refno="<?php echo htmlspecialchars(stripslashes($row['refno'])); ?>"
						data-customerid="<?php echo htmlspecialchars(stripslashes($row['customer_id'])); ?>"
						data-fullname="<?php echo htmlspecialchars(stripslashes($row['last_name'].', '.$row['first_name'].' '.$row['middle_name'])); ?>"
						data-previous_reading="<?php echo htmlspecialchars(stripslashes($row['previous_reading'])); ?>"
						data-current_reading="<?php echo htmlspecialchars(stripslashes($row['reading'])); ?>"
						data-consumed="<?php echo htmlspecialchars(stripslashes($row['consumed'])); ?>"
						data-current_bill="<?php echo htmlspecialchars(stripslashes($row['unit_price'])); ?>"
						data-sc_discount="<?php echo htmlspecialchars(stripslashes($row['sc_discount'])); ?>"
						data-arrears="<?php echo htmlspecialchars(stripslashes($row['arrears'])); ?>"
						data-total_amount="<?php echo htmlspecialchars(stripslashes($row['amount'])); ?>"
						data-penalty="<?php echo htmlspecialchars(stripslashes($row['penalty'])); ?>"
						data-maintenance_fee="<?php echo htmlspecialchars(stripslashes($row['maintenance_fee'])); ?>"
						data-franchise_fee_percent="<?php echo htmlspecialchars(isset($row['franchise_fee_percent']) ? $row['franchise_fee_percent'] : '0.00'); ?>"
						data-franchise_fee_amount="<?php echo htmlspecialchars(isset($row['franchise_fee_amount']) ? $row['franchise_fee_amount'] : '0.00'); ?>"
						data-reading_date="<?php echo !empty($row['date']) ? date('d-m-Y', strtotime($row['date'])) : ''; ?>"
						data-account_type="<?php echo htmlspecialchars(stripslashes($row['account_type'])); ?>"
						data-special_priviledge="<?php echo htmlspecialchars(stripslashes($row['special_priviledge'])); ?>"
						data-customer_status="<?php echo htmlspecialchars(stripslashes($row['customer_status'])); ?>"
						data-compute_penalty="<?php echo isset($row['compute_penalty']) ? (int) $row['compute_penalty'] : 1; ?>"
						data-toggle="modal"
						data-target="#myModal"
						title="Edit">
						<i class="fal fa-edit"></i>
					</a>
				</div>
			</td>
		</tr>
		<?php
				$i++;
			}
		?>
	</tbody>
</table>
<?php } ?>
