<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item active">Change Username</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user"></i>
			Change <span class="fw-300">Username</span>
		</h1>
	</div>
	<div class="row">
		<div class="col-xl-8">
			<div id="panel-change-username" class="panel">
				<div class="panel-hdr">
					<h2>Update <span class="fw-300"><i>Username</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<?php if (!empty($msg)) { ?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
							<?php echo $msg; ?>
						</div>
						<?php } ?>
						<form class="needs-validation" name="myform" id="myform" method="post" action="" enctype="multipart/form-data" novalidate>
							<div class="form-group">
								<label class="form-label" for="cur_username">Current Username</label>
								<input class="form-control" type="text" id="cur_username" name="cur_username" value="<?php echo htmlspecialchars((string)$this->input->post('cur_username')); ?>" required>
								<?php echo form_error('cur_username'); ?>
							</div>
							<div class="form-group">
								<label class="form-label" for="new_username">New Username</label>
								<input class="form-control" type="text" id="new_username" name="new_username" value="<?php echo htmlspecialchars((string)$this->input->post('new_username')); ?>" required>
								<?php echo form_error('new_username'); ?>
							</div>
							<div class="form-group">
								<label class="form-label" for="conf_username">Confirm Username</label>
								<input class="form-control" type="text" id="conf_username" name="conf_username" value="<?php echo htmlspecialchars((string)$this->input->post('conf_username')); ?>" required>
								<?php echo form_error('conf_username'); ?>
							</div>
							<div class="form-group mb-0">
								<a href="<?php echo ADMIN_URL; ?>dashboard/" class="btn btn-secondary">Cancel</a>
								<button type="submit" class="btn btn-primary" name="add" id="add" value="save">Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
</body>
</html>
