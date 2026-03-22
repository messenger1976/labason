<!-- MAIN PANEL -->
<div id="main" role="main">

	<div id="ribbon">
		<ol class="breadcrumb">
			<li><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
			<li><a href="<?php echo ADMIN_URL; ?>manual_or_series">Manual OR Series</a></li>
			<li>List</li>
		</ol>
	</div>

	<div id="content">
		<div class="row">
			<div class="col-xs-12">
				<h1 class="page-title txt-color-blueDark"><i class="fa fa-fw fa-list-ol"></i> Manual OR Series</h1>
				<p class="text-muted">Set the <strong>last used</strong> OR/SI number per series. The <strong>next</strong> receipt will be that value <strong>plus one</strong>. Coordinate with physical receipt books and avoid changing numbers while tellers are posting.</p>
			</div>
		</div>

		<?php if ($this->session->flashdata('msg_succ')): ?>
			<div class="alert alert-success fade in">
				<button class="close" data-dismiss="alert">&times;</button>
				<?php echo $this->session->flashdata('msg_succ'); ?>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('msg_err')): ?>
			<div class="alert alert-danger fade in">
				<button class="close" data-dismiss="alert">&times;</button>
				<?php echo $this->session->flashdata('msg_err'); ?>
			</div>
		<?php endif; ?>

		<section id="widget-grid" class="">
			<div class="row">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jarviswidget jarviswidget-color-darken">
						<header>
							<span class="widget-icon"><i class="fa fa-table"></i></span>
							<h2>OR document series</h2>
						</header>
						<div class="widget-body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Series</th>
											<th>Teller (Employee Logins)</th>
											<th>Last used OR #</th>
											<th>Next OR (preview)</th>
											<th>Update</th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($rows)): ?>
											<?php foreach ($rows as $row): ?>
												<?php
												$last = isset($row['doc_series_num']) ? (int) $row['doc_series_num'] : 0;
												$next = $last + 1;
												if (!empty($row['teller_user_id'])) {
													$label = 'Teller #' . (int) $row['teller_user_id'];
													$who = trim((isset($row['employee_name']) ? $row['employee_name'] : '') . (isset($row['username']) ? ' (' . $row['username'] . ')' : ''));
												} else {
													$label = 'Legacy / shared (doc_id ' . (int) $row['doc_id'] . ')';
													$who = '—';
												}
												?>
												<tr>
													<td><?php echo htmlspecialchars($label); ?></td>
													<td><?php echo $who !== '' ? htmlspecialchars($who) : '—'; ?></td>
													<td><?php echo sprintf('%07d', $last); ?></td>
													<td><strong><?php echo sprintf('%07d', $next); ?></strong></td>
													<td>
														<?php if (!empty($can_edit)): ?>
															<form method="post" action="" class="form-inline" style="display:inline;" onsubmit="return confirm('Update last used OR for this series?');">
																<input type="hidden" name="doc_id" value="<?php echo (int) $row['doc_id']; ?>" />
																<div class="form-group">
																	<input type="number" name="doc_series_num" class="form-control input-sm" min="0" step="1" value="<?php echo $last; ?>" style="width:120px;" required />
																</div>
																<button type="submit" name="save_series" value="1" class="btn btn-primary btn-sm">Save</button>
															</form>
														<?php else: ?>
															<span class="text-muted">View only</span>
														<?php endif; ?>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr><td colspan="5">No OR rows found (expected doc_name = OR in tbl_doc_series_number).</td></tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</article>
			</div>
		</section>
	</div>
</div>

<?php include('footer.php'); ?>
