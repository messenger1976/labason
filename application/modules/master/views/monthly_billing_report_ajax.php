<?php
	$records = (isset($record) && is_array($record)) ? $record : array();
	$cr = 0;
	$grand_total_penalty = 0;
	$grand_total_metered_sales = 0;
	$grand_total_cubic_meter = 0;
	$index = 1;
	$class_category = array();
	$no_of_customer_1 = 0;
	$no_of_customer_2 = 0;
	$no_of_customer_3 = 0;
	$no_of_customer_4 = 0;
	$no_of_consumption_1 = 0;
	$no_of_consumption_2 = 0;
	$no_of_consumption_3 = 0;
	$no_of_consumption_4 = 0;
	$metered_sales_1 = 0;
	$metered_sales_2 = 0;
	$metered_sales_3 = 0;
	$metered_sales_4 = 0;
	$penalty_1 = 0;
	$penalty_2 = 0;
	$penalty_3 = 0;
	$penalty_4 = 0;
	$current_date = date('Y-m-d');
?>
<div class="table-responsive">
	<table id="dt_monthly_billing" class="table table-bordered table-hover table-striped w-100">
		<thead class="bg-primary-600">
			<tr>
				<th>SN#</th>
				<th>Customer Name</th>
				<th>Customer ID</th>
				<th>Zone</th>
				<th>Category</th>
				<th>Meter Number</th>
				<th>Billing No.</th>
				<th class="text-center">Consumed</th>
				<th class="text-right">Metered Sales</th>
				<th class="text-right">Penalty Charges</th>
				<th>Due Date</th>
				<th>Payment Date</th>
				<th class="text-right">Total Amount</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($records) > 0) { ?>
				<?php foreach ($records as $row) {
					$pdate = isset($row['payment_date']) ? stripslashes($row['payment_date']) : '';
					$date = isset($row['due_date']) ? stripslashes($row['due_date']) : '';
					$penalty = 0;
					if ($pdate > $date) {
						$penalty = $row['penalty'] - $row['amount'];
					}

					if ($row['invoice_id'] == '') {
						$total_payment = $row['amount'];
						$date1 = $row['due_date'];
						if ($current_date > $date1) {
							$penalty = $row['penalty'] - $row['amount'];
							$total_payment = $row['penalty'];
						} else {
							$penalty = 0;
						}
					} else {
						$total_payment = $row['payment_amount'];
					}

					if ($row['invoice_id'] != '') {
						$status_label = 'Paid';
						$status_class = 'badge-success';
					} elseif (isset($row['customer_status']) && $row['customer_status'] == '2') {
						$status_label = 'Disconnected';
						$status_class = 'badge-danger';
					} elseif (isset($row['customer_status']) && $row['customer_status'] == '1' && ($row['reading'] == '' || is_null($row['reading']))) {
						$status_label = 'No Reading';
						$status_class = 'badge-warning';
					} else {
						$status_label = 'Un-Paid';
						$status_class = 'badge-danger';
					}

					$name = trim(trim($row['last_name']).', '.trim($row['first_name']).' '.trim($row['middle_name']));
				?>
				<tr>
					<td><?php echo $index; ?></td>
					<td><?php echo htmlspecialchars(stripslashes($name)); ?></td>
					<td><?php echo htmlspecialchars(stripslashes($row['customer_id'])); ?></td>
					<td><?php echo htmlspecialchars(stripslashes($row['zone'])); ?></td>
					<td><?php echo htmlspecialchars(stripslashes($row['class_cat_name'])); ?></td>
					<td><?php echo htmlspecialchars(stripslashes($row['meter_number'])); ?></td>
					<td><?php echo htmlspecialchars(sprintf('%07d', $row['refno'])); ?></td>
					<td class="text-center"><?php echo htmlspecialchars(stripslashes($row['consumed'])); ?></td>
					<td class="text-right"><?php echo number_format($row['amount'], 2); ?></td>
					<td class="text-right"><?php echo number_format($penalty, 2); ?></td>
					<td><?php echo $date !== '' ? date('m/d/Y', strtotime($date)) : ''; ?></td>
					<td><?php echo $pdate !== '' ? date('m/d/Y', strtotime($pdate)) : ''; ?></td>
					<td class="text-right"><?php echo number_format($total_payment, 2); ?></td>
					<td><span class="badge <?php echo $status_class; ?>"><?php echo $status_label; ?></span></td>
				</tr>
				<?php
					$cr += $total_payment;
					$grand_total_penalty += $penalty;
					$grand_total_metered_sales += $row['amount'];
					$grand_total_cubic_meter += $row['consumed'];
					$index++;

					$keyToSearch = 'class_cat_id';
					$valueToFind = $row['class_cat_id'];
					$fieldToUpdate = 'no_of_customer';
					$fieldToUpdate1 = 'no_of_consumption';
					$fieldToUpdate2 = 'metered_sales';
					$fieldToUpdate3 = 'penalty';
					$newArray = array(
						'class_cat_id' => $row['class_cat_id'],
						'class_cat_name' => $row['class_cat_name'],
					);
					$newArray1 = array(
						'no_of_customer' => 0,
						'no_of_consumption' => 0,
						'metered_sales' => 0,
						'penalty' => 0
					);

					if ($row['class_cat_id'] == '1') {
						$no_of_customer_1++;
						$no_of_consumption_1 += $row['consumed'];
						$metered_sales_1 += $row['amount'];
						$penalty_1 += $penalty;
						$newArray1 = array(
							'no_of_customer' => $no_of_customer_1,
							'no_of_consumption' => $no_of_consumption_1,
							'metered_sales' => $metered_sales_1,
							'penalty' => $penalty_1
						);
					} elseif ($row['class_cat_id'] == '2') {
						$no_of_customer_2++;
						$no_of_consumption_2 += $row['consumed'];
						$metered_sales_2 += $row['amount'];
						$penalty_2 += $penalty;
						$newArray1 = array(
							'no_of_customer' => $no_of_customer_2,
							'no_of_consumption' => $no_of_consumption_2,
							'metered_sales' => $metered_sales_2,
							'penalty' => $penalty_2
						);
					} elseif ($row['class_cat_id'] == '3') {
						$no_of_customer_3++;
						$no_of_consumption_3 += $row['consumed'];
						$metered_sales_3 += $row['amount'];
						$penalty_3 += $penalty;
						$newArray1 = array(
							'no_of_customer' => $no_of_customer_3,
							'no_of_consumption' => $no_of_consumption_3,
							'metered_sales' => $metered_sales_3,
							'penalty' => $penalty_3
						);
					} elseif ($row['class_cat_id'] == '4') {
						$no_of_customer_4++;
						$no_of_consumption_4 += $row['consumed'];
						$metered_sales_4 += $row['amount'];
						$penalty_4 += $penalty;
						$newArray1 = array(
							'no_of_customer' => $no_of_customer_4,
							'no_of_consumption' => $no_of_consumption_4,
							'metered_sales' => $metered_sales_4,
							'penalty' => $penalty_4
						);
					}

					$newArray = array_merge($newArray, $newArray1);
					$found = false;
					foreach ($class_category as &$class_category_key) {
						if (array_key_exists($keyToSearch, $class_category_key) && $class_category_key[$keyToSearch] === $valueToFind) {
							if (array_key_exists($fieldToUpdate, $class_category_key)) {
								if ($row['class_cat_id'] == '1') { $class_category_key[$fieldToUpdate] = $no_of_customer_1; }
								if ($row['class_cat_id'] == '2') { $class_category_key[$fieldToUpdate] = $no_of_customer_2; }
								if ($row['class_cat_id'] == '3') { $class_category_key[$fieldToUpdate] = $no_of_customer_3; }
								if ($row['class_cat_id'] == '4') { $class_category_key[$fieldToUpdate] = $no_of_customer_4; }
							}
							if (array_key_exists($fieldToUpdate1, $class_category_key)) {
								if ($row['class_cat_id'] == '1') { $class_category_key[$fieldToUpdate1] = $no_of_consumption_1; }
								if ($row['class_cat_id'] == '2') { $class_category_key[$fieldToUpdate1] = $no_of_consumption_2; }
								if ($row['class_cat_id'] == '3') { $class_category_key[$fieldToUpdate1] = $no_of_consumption_3; }
								if ($row['class_cat_id'] == '4') { $class_category_key[$fieldToUpdate1] = $no_of_consumption_4; }
							}
							if (array_key_exists($fieldToUpdate2, $class_category_key)) {
								if ($row['class_cat_id'] == '1') { $class_category_key[$fieldToUpdate2] = $metered_sales_1; }
								if ($row['class_cat_id'] == '2') { $class_category_key[$fieldToUpdate2] = $metered_sales_2; }
								if ($row['class_cat_id'] == '3') { $class_category_key[$fieldToUpdate2] = $metered_sales_3; }
								if ($row['class_cat_id'] == '4') { $class_category_key[$fieldToUpdate2] = $metered_sales_4; }
							}
							if (array_key_exists($fieldToUpdate3, $class_category_key)) {
								if ($row['class_cat_id'] == '1') { $class_category_key[$fieldToUpdate3] = $penalty_1; }
								if ($row['class_cat_id'] == '2') { $class_category_key[$fieldToUpdate3] = $penalty_2; }
								if ($row['class_cat_id'] == '3') { $class_category_key[$fieldToUpdate3] = $penalty_3; }
								if ($row['class_cat_id'] == '4') { $class_category_key[$fieldToUpdate3] = $penalty_4; }
							}
							$found = true;
						}
					}
					unset($class_category_key);

					if (!$found) {
						$class_category[] = $newArray;
					}
				} ?>
			<?php } else { ?>
				<tr>
					<td colspan="14" class="text-center py-4">No records found for the selected filters.</td>
				</tr>
			<?php } ?>
		</tbody>
		<?php if (count($records) > 0) { ?>
		<tfoot>
			<tr>
				<th colspan="7" class="text-right">GRAND TOTAL</th>
				<th class="text-center"><?php echo number_format($grand_total_cubic_meter, 0); ?></th>
				<th class="text-right"><?php echo number_format($grand_total_metered_sales, 2); ?></th>
				<th class="text-right"><?php echo number_format($grand_total_penalty, 2); ?></th>
				<th></th>
				<th></th>
				<th class="text-right"><?php echo number_format($cr, 2); ?></th>
				<th></th>
			</tr>
		</tfoot>
		<?php } ?>
	</table>
</div>

<?php if (count($records) > 0) { ?>
<div class="mt-4">
	<h5 class="mb-3">Breakdown of Metered Sales</h5>
	<div class="table-responsive">
		<table class="table table-bordered table-striped w-100">
			<thead class="bg-primary-600">
				<tr>
					<th>Category</th>
					<th class="text-center">No. of Consumer</th>
					<th class="text-center">Consumption</th>
					<th class="text-right">Amount</th>
					<th class="text-right">Penalty</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$grand_no_of_customer = 0;
					$grand_no_of_consumption = 0;
					$grand_metered_sales = 0;
					$grand_penalty = 0;
					foreach ($class_category as $person) {
						if ($person['class_cat_name'] == '') { continue; }
						echo '<tr>';
						echo '<td>'.htmlspecialchars($person['class_cat_name']).'</td>';
						echo '<td class="text-center">'.(int) $person['no_of_customer'].'</td>';
						echo '<td class="text-center">'.htmlspecialchars($person['no_of_consumption']).'</td>';
						echo '<td class="text-right">'.number_format($person['metered_sales'], 2).'</td>';
						echo '<td class="text-right">'.number_format($person['penalty'], 2).'</td>';
						echo '</tr>';
						$grand_no_of_customer += $person['no_of_customer'];
						$grand_no_of_consumption += $person['no_of_consumption'];
						$grand_metered_sales += $person['metered_sales'];
						$grand_penalty += $person['penalty'];
					}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th>GRAND TOTAL</th>
					<th class="text-center"><?php echo (int) $grand_no_of_customer; ?></th>
					<th class="text-center"><?php echo htmlspecialchars($grand_no_of_consumption); ?></th>
					<th class="text-right"><?php echo number_format($grand_metered_sales, 2); ?></th>
					<th class="text-right"><?php echo number_format($grand_penalty, 2); ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<?php } ?>
