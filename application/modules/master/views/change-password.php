<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item active">Change Password</li>
		<li class="position-absolute pos-top pos-right d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-key"></i>
			Change <span class="fw-300">Password</span>
		</h1>
	</div>
	<div class="row">
		<div class="col-xl-8">
			<div id="panel-change-password" class="panel">
				<div class="panel-hdr">
					<h2>Update <span class="fw-300"><i>Password</i></span></h2>
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
								<label class="form-label" for="cur_pwd">Current Password</label>
								<input class="form-control" type="password" id="cur_pwd" name="cur_pwd" value="<?php echo htmlspecialchars((string)$this->input->post('cur_pwd')); ?>" required>
								<?php echo form_error('cur_pwd'); ?>
							</div>
							<div class="form-group">
								<label class="form-label" for="new_pwd">New Password</label>
								<input class="form-control" type="password" id="new_pwd" name="new_pwd" value="<?php echo htmlspecialchars((string)$this->input->post('new_pwd')); ?>" required>
								<?php echo form_error('new_pwd'); ?>
							</div>
							<div class="form-group">
								<label class="form-label" for="conf_pwd">Confirm Password</label>
								<input class="form-control" type="password" id="conf_pwd" name="conf_pwd" value="<?php echo htmlspecialchars((string)$this->input->post('conf_pwd')); ?>" required>
								<?php echo form_error('conf_pwd'); ?>
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
