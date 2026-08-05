<?php
	$record = (isset($record) && is_array($record)) ? $record : array();
	$image = (isset($image) && is_array($image)) ? $image : array();
	$photo_file = !empty($image['file']) ? $image['file'] : 'a.png';
	$photo_url = ADMIN_IMG_URL . 'upload/' . $photo_file;

	$v = function($key, $fallback = '') use ($record) {
		if (!isset($record[$key]) || $record[$key] === null || $record[$key] === '') {
			return $fallback;
		}
		return htmlspecialchars(stripslashes(str_replace('\n', '', (string) $record[$key])), ENT_QUOTES, 'UTF-8');
	};

	$full_name = trim(preg_replace('/\s+/', ' ', $v('first_name') . ' ' . $v('middle_name') . ' ' . $v('last_name')));
	if ($full_name === '') {
		$full_name = 'Customer';
	}

	$status_raw = isset($record['status']) ? (string) $record['status'] : '';
	$status_html = ($status_raw === '1')
		? '<span class="badge badge-success">Active</span>'
		: (($status_raw === '0') ? '<span class="badge badge-danger">Inactive</span>' : '—');

	$customer_type_label = $v('customer_type', '—');
	if ($customer_type_label === 'metercustomer') {
		$customer_type_label = 'Meter Customer';
	} elseif ($customer_type_label === 'monthlycustomer') {
		$customer_type_label = 'Monthly Customer';
	}

	$gender_label = $v('gender', '—');
	if ($gender_label !== '—') {
		$gender_label = ucfirst(strtolower($gender_label));
	}

	$rows = array(
		array('Customer ID', $v('customer_id', '—')),
		array('Account Subgroup', $v('subName', '—')),
		array('First Name', $v('first_name', '—')),
		array('Middle Name', $v('middle_name', '—')),
		array('Last Name', $v('last_name', '—')),
		array('DOB', !empty($record['DOB']) ? htmlspecialchars((string) $record['DOB'], ENT_QUOTES, 'UTF-8') : '—'),
		array('Gender', $gender_label),
		array('Place of Birth', $v('place_of_birth', '—')),
		array('Address', $v('address', '—')),
		array('City', $v('city', '—')),
		array('Province', $v('state', '—')),
		array('Mobile 1', $v('mobile1', '—')),
		array('Mobile 2', $v('mobile2', '—')),
		array('Email', $v('email_id', '—')),
		array('Line Number', $v('line_number', '—')),
		array('Zone', $v('zones', '—')),
		array('Payment Type', $customer_type_label),
		array('Reference Person', $v('referenceperson', '—')),
		array('Billing Plans', $v('billingplans_name', '—')),
		array('Status', $status_html),
	);
?>
<div class="customer-view-modal">
	<div class="d-flex align-items-center mb-3 pb-3 border-bottom border-faded">
		<img
			src="<?php echo htmlspecialchars($photo_url, ENT_QUOTES, 'UTF-8'); ?>"
			alt="Customer photo"
			class="border border-faded rounded mr-3"
			style="width:88px; height:88px; object-fit:cover;"
			onerror="this.src='<?php echo ADMIN_IMG_URL; ?>upload/a.png';"
		>
		<div>
			<div class="fs-lg fw-500"><?php echo $full_name; ?></div>
			<div class="text-muted"><?php echo $v('customer_id', '—'); ?></div>
			<div class="mt-1"><?php echo $status_html; ?></div>
		</div>
	</div>

	<table class="table table-sm table-striped table-bordered mb-0">
		<tbody>
			<?php foreach ($rows as $row) { ?>
			<tr>
				<th class="w-25 text-muted fw-500 bg-faded"><?php echo htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8'); ?></th>
				<td><?php echo $row[1]; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
