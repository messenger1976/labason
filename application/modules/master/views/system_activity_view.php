<?php
	$record = (isset($record) && is_array($record)) ? $record : array();
	$details = '';
	if (!empty($record['details_json'])) {
		$decoded = json_decode($record['details_json'], true);
		if (json_last_error() === JSON_ERROR_NONE) {
			$details = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		} else {
			$details = $record['details_json'];
		}
	}
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>system_activity/">System Activity</a></li>
		<li class="breadcrumb-item active">Detail #<?php echo (int) $record['id']; ?></li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-history"></i>
			Activity <span class="fw-300">Detail</span>
		</h1>
	</div>

	<div class="row">
		<div class="col-xl-8">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Event <span class="fw-300"><i>#<?php echo (int) $record['id']; ?></i></span></h2>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<table class="table table-striped table-bordered mb-0">
							<tr><th style="width:180px;">When</th><td><?php echo htmlspecialchars($record['created_at']); ?></td></tr>
							<tr><th>User</th><td><?php echo htmlspecialchars($record['user_name']); ?> (<?php echo htmlspecialchars($record['username']); ?> / <?php echo htmlspecialchars($record['usertype']); ?>)</td></tr>
							<tr><th>Category / Action</th><td><?php echo htmlspecialchars($record['category']); ?> / <?php echo htmlspecialchars($record['action']); ?></td></tr>
							<tr><th>Module</th><td><?php echo htmlspecialchars($record['module']); ?> — <?php echo htmlspecialchars($record['controller']); ?>::<?php echo htmlspecialchars($record['method']); ?></td></tr>
							<tr><th>URI</th><td><?php echo htmlspecialchars($record['http_method'].' '.$record['uri']); ?></td></tr>
							<tr><th>Entity</th><td><?php echo htmlspecialchars($record['entity_type']); ?> <?php echo htmlspecialchars($record['entity_id']); ?></td></tr>
							<tr><th>Reference</th><td><?php echo htmlspecialchars($record['reference_no']); ?></td></tr>
							<tr><th>Amount</th><td><?php echo ($record['amount'] !== null && $record['amount'] !== '') ? number_format((float) $record['amount'], 2) : '—'; ?></td></tr>
							<tr><th>Status</th><td><?php echo htmlspecialchars($record['status_before']); ?> → <?php echo htmlspecialchars($record['status_after']); ?></td></tr>
							<tr><th>Summary</th><td><?php echo htmlspecialchars($record['summary']); ?></td></tr>
							<tr><th>IP / Session</th><td><?php echo htmlspecialchars($record['ip_address']); ?> / <?php echo htmlspecialchars($record['session_id']); ?></td></tr>
							<tr><th>User Agent</th><td><small><?php echo htmlspecialchars($record['user_agent']); ?></small></td></tr>
						</table>
						<div class="mt-3">
							<label class="form-label">Details (sanitized)</label>
							<pre class="p-3 bg-faded border rounded" style="max-height:360px; overflow:auto; white-space:pre-wrap;"><?php echo htmlspecialchars($details !== '' ? $details : '(none)'); ?></pre>
						</div>
						<a href="<?php echo ADMIN_URL; ?>system_activity/" class="btn btn-secondary mt-2">Back to list</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
