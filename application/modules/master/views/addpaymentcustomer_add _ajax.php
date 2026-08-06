<div class="mt-3">
	<div class="panel-tag mb-2">
		Unpaid / billing rows for the selected customer. Use <strong>Pay</strong> on a row or select multiple then pay total.
	</div>
	<div class="frame-wrap">
		<table id="dt_billing_rows" class="table table-bordered table-hover table-striped table-sm w-100">
			<thead class="bg-primary-600 bg-primary-gradient">
				<tr>
					<th style="width:28px;"></th>
					<th>S No</th>
					<th>Billing No.</th>
					<th>Billing Period</th>
					<th>Due Date</th>
					<th>Previous Reading</th>
					<th>Last Reading</th>
					<th>Consumed</th>
					<th>Bill Amount</th>
					<th>Discount</th>
					<th>Penalty</th>
					<th>WMMF</th>
					<th>Franchise Tax</th>
					<th>OR Number</th>
					<th>Date Paid</th>
					<th>Total Amount</th>
					<th style="width:90px;">Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$qty = 0;
			$id = '';
			$per_unitvalue = isset($per_unitvalue) ? $per_unitvalue : '';
			if (!empty($record) && count($record) > 0) {
				$i = 1;
				foreach ($record as $key => $row) {
					$unit_price = $row['unit_price'];
					$display_bill_amount = $unit_price;
					$id = stripslashes($row['customer_id']);
					$mon_id = stripslashes($row['month']);
					$year = stripslashes($row['year']);
					$due_date = $row['bp_due_date'];
					$special_priviledge = $row['special_priviledge'];
					$compute_penalty = isset($row['compute_penalty']) ? (int)$row['compute_penalty'] : 1;
					$consumed = isset($row['consumed']) ? (float)$row['consumed'] : 0;
					$cur_date = date('Y-m-d');
					$or_number_paid = '';
					$trans_date = '';

					if ($consumed >= 0 && $special_priviledge == 0 && $compute_penalty == 1) {
						if ($cur_date > $due_date) {
							$balance = $row['penalty'];
							$penalty = $row['penalty'] - $row['amount'];
						} else {
							$balance = $row['amount'];
							$penalty = 0;
						}
					} else {
						$balance = $row['amount'];
						$penalty = 0;
					}

					$record_reading = $this->my_model->get_metercustomer_add_all_records($id, $mon_id, $year);
					$result = count($record_reading);
					$has_last_reading = isset($row['reading']) && trim((string)$row['reading']) !== '';

					if ($result != 0) {
						$trans_date_raw = $record_reading[0]['trans_date'];
						$date_paid_raw = !empty($record_reading[0]['create_date_time']) ? $record_reading[0]['create_date_time'] : $trans_date_raw;
						$dt_paid = new DateTime($date_paid_raw, new DateTimeZone('Asia/Manila'));
						$trans_date = $dt_paid->format('M j, Y h:i:s A');
						$or_number_paid = $record_reading[0]['or_number'];
						$due_date_ts = strtotime($due_date);
						$trans_date_ts = strtotime($trans_date_raw);
						$compute_penalty = isset($record_reading[0]['compute_penalty']) ? (int)$record_reading[0]['compute_penalty'] : 1;

						if ($consumed >= 0 && $special_priviledge == 0 && $compute_penalty == 1) {
							if ($trans_date_ts > $due_date_ts) {
								$balance = $record_reading[0]['amount'];
								$maintenance_fee = isset($row['maintenance_fee']) ? (float)$row['maintenance_fee'] : 0;
								$franchise_fee_amount = isset($row['franchise_fee_amount']) ? (float)$row['franchise_fee_amount'] : 0;
								$penalty = $balance - $unit_price - $maintenance_fee - $franchise_fee_amount;
								if ($penalty < 0) {
									$penalty = 0;
								}
								if ($penalty > 0 && $due_date_ts && $trans_date_ts) {
									$is_billing_period_arrears = (date('m', $trans_date_ts) != date('m', $due_date_ts)) || (date('Y', $trans_date_ts) != date('Y', $due_date_ts));
									if ($is_billing_period_arrears) {
										$display_bill_amount = $unit_price + $penalty;
										$penalty = 0;
									}
								}
							} else {
								$balance = $record_reading[0]['amount'];
								$penalty = 0;
							}
						} else {
							$balance = $record_reading[0]['amount'];
							$penalty = 0;
						}
					}
			?>
				<tr>
					<td>
						<?php if ($result == 0 && $has_last_reading) { ?>
						<input type="checkbox" name="checkbox[]" id="<?php echo $i; ?>" value="<?php echo $i; ?>" class="my_check">
						<?php } else { ?>
						<input type="checkbox" name="checkbox[]" id="<?php echo $i; ?>" value="<?php echo $i; ?>" class="my_check" disabled>
						<?php } ?>
					</td>
					<td><?php echo $i; ?></td>
					<td><?php echo htmlspecialchars($row['refno'], ENT_QUOTES, 'UTF-8'); ?></td>
					<td>
						<?php echo htmlspecialchars(stripslashes($row['month_name'] . ' ' . $row['year']), ENT_QUOTES, 'UTF-8'); ?>
						<input type="hidden" name="previousreading_<?php echo $i; ?>" id="previousreading_<?php echo $i; ?>" value="<?php echo $row['previous_reading']; ?>">
						<input type="hidden" name="monthid_<?php echo $i; ?>" id="monthid_<?php echo $i; ?>" value="<?php echo $row['month']; ?>">
						<input type="hidden" name="month_<?php echo $i; ?>" id="month_<?php echo $i; ?>" value="<?php echo $row['month_name']; ?>">
						<input type="hidden" name="year_<?php echo $i; ?>" id="year_<?php echo $i; ?>" value="<?php echo $row['year']; ?>">
						<input type="hidden" name="status_<?php echo $i; ?>" id="status_<?php echo $i; ?>" value="<?php echo $row['status']; ?>">
						<input type="hidden" name="refno_<?php echo $i; ?>" id="refno_<?php echo $i; ?>" value="<?php echo $row['refno']; ?>">
						<input type="hidden" name="due_date_<?php echo $i; ?>" id="due_date_<?php echo $i; ?>" value="<?php echo $row['bp_due_date']; ?>">
						<input type="hidden" name="special_priviledge_<?php echo $i; ?>" id="special_priviledge_<?php echo $i; ?>" value="<?php echo $row['special_priviledge']; ?>">
						<input type="hidden" name="base_amount_<?php echo $i; ?>" id="base_amount_<?php echo $i; ?>" value="<?php echo $row['amount']; ?>">
						<input type="hidden" name="compute_penalty_<?php echo $i; ?>" id="compute_penalty_<?php echo $i; ?>" value="<?php echo isset($compute_penalty) ? $compute_penalty : 1; ?>">
					</td>
					<td><?php echo date('M j, Y', strtotime($row['bp_due_date'])); ?></td>
					<td class="text-center"><?php echo htmlspecialchars(stripslashes($row['previous_reading']), ENT_QUOTES, 'UTF-8'); ?></td>
					<td class="text-center">
						<?php echo htmlspecialchars(stripslashes($row['reading']), ENT_QUOTES, 'UTF-8'); ?>
						<input type="hidden" name="reading_<?php echo $i; ?>" id="reading_<?php echo $i; ?>" value="<?php echo $row['reading']; ?>">
						<input type="hidden" name="consumedunit_<?php echo $i; ?>" id="consumedunit_<?php echo $i; ?>" value="<?php echo $row['consumed']; ?>">
						<input type="hidden" name="sc_discount_<?php echo $i; ?>" id="sc_discount_<?php echo $i; ?>" value="<?php echo $row['sc_discount']; ?>">
					</td>
					<td class="text-center"><?php echo htmlspecialchars(stripslashes($row['consumed']), ENT_QUOTES, 'UTF-8'); ?></td>
					<td class="text-right">
						<?php echo number_format($display_bill_amount, 2); ?>
						<input type="hidden" name="unit_price_<?php echo $i; ?>" id="unit_price_<?php echo $i; ?>" value="<?php echo $unit_price; ?>">
					</td>
					<td class="text-right"><?php echo htmlspecialchars(stripslashes($row['sc_discount']), ENT_QUOTES, 'UTF-8'); ?></td>
					<td class="text-right"><?php echo number_format($penalty, 2); ?></td>
					<td class="text-right"><?php echo number_format($row['maintenance_fee'], 2); ?></td>
					<td class="text-right"><?php echo isset($row['franchise_fee_amount']) ? number_format($row['franchise_fee_amount'], 2) : '0.00'; ?></td>
					<td class="text-center"><?php echo htmlspecialchars(stripslashes($or_number_paid), ENT_QUOTES, 'UTF-8'); ?></td>
					<td class="text-center"><?php echo htmlspecialchars(stripslashes($trans_date), ENT_QUOTES, 'UTF-8'); ?></td>
					<td class="text-right">
						<?php echo $balance; ?>
						<input type="hidden" name="prsentamount_<?php echo $i; ?>" id="prsentamount_<?php echo $i; ?>" value="<?php echo $balance; ?>">
						<?php
						$count = $this->my_model->get_addcustomer_show_all_records($id, $mon_id, $year);
						foreach ($count as $cou) {
						?>
						<input type="hidden" name="oldbalance_<?php echo $i; ?>" id="oldbalance_<?php echo $i; ?>" value="<?php echo number_format($cou['balance'], 2); ?>">
						<?php } ?>
					</td>
					<td>
						<?php if ($result == 0) { ?>
						<button type="button" class="btn btn-danger btn-xs waves-effect waves-themed pay_button" id="paybutton_<?php echo $i; ?>" data-pay-val-id="<?php echo $i; ?>"<?php echo $has_last_reading ? '' : ' disabled'; ?>>Unpaid</button>
						<?php } else { ?>
						<button type="button" class="btn btn-secondary btn-xs waves-effect waves-themed pay_button" id="paybutton_<?php echo $i; ?>" data-pay-val-id="<?php echo $i; ?>" disabled>Paid</button>
						<?php } ?>
					</td>
				</tr>
			<?php
					$i++;
				}
			}
			?>
			</tbody>
			<?php if (!empty($record) && count($record) > 0) { ?>
			<tfoot>
				<tr>
					<th colspan="15" class="text-right">Selected total</th>
					<th class="text-right">
						<input type="text" class="form-control form-control-sm text-right" name="checkbox_cal" id="checkbox_cal" value="0" readonly style="max-width:120px;margin-left:auto;">
						<input type="hidden" name="checkbox_cal_bill" id="checkbox_cal_bill" value="0">
					</th>
					<th>
						<button type="button" class="btn btn-primary btn-sm waves-effect waves-themed total_pay" id="total_pay" name="total_pay">Total Pay</button>
					</th>
				</tr>
			</tfoot>
			<?php } ?>
		</table>
		<?php if (empty($record) || count($record) == 0) { ?>
		<div class="alert alert-warning mb-0">No Records Found</div>
		<?php } ?>
		<input type="hidden" name="customer_id_next" id="customer_id_next" value="<?php echo htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8'); ?>">
		<input type="hidden" name="unit_d" id="unit_d" value="<?php echo htmlspecialchars((string)$per_unitvalue, ENT_QUOTES, 'UTF-8'); ?>">
	</div>
</div>
