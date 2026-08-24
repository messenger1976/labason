<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>addjournalvoucher">Journal Voucher</a></li>
		<li class="breadcrumb-item active">Edit</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-book"></i>
			Journal Voucher <span class="fw-300">Edit</span>
		</h1>
	</div>
	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Edit not supported</h2>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<div class="alert alert-warning">
							Editing a Journal Voucher line-by-line is disabled because each voucher is a <strong>Debit + Credit pair</strong>.
							To correct an entry: delete both lines from the listing, then <a href="<?php echo ADMIN_URL; ?>addjournalvoucher/add/">Add</a> a new voucher.
						</div>
						<a href="<?php echo ADMIN_URL; ?>addjournalvoucher" class="btn btn-secondary">Back to listing</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
</body>
</html>
