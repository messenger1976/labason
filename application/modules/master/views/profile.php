<?php
$profile_name = trim((string) ($profile['name'] ?? ''));
$profile_username = trim((string) ($profile['username'] ?? ''));
$profile_user_type = trim((string) ($profile['user_type'] ?? ''));
$profile_role_name = trim((string) ($profile['role_name'] ?? ''));
$profile_email = trim((string) ($profile['email'] ?? ''));
$profile_mobile = trim((string) ($profile['mobile'] ?? ''));
$profile_created = trim((string) ($profile['created_date_time'] ?? ''));
$profile_status = (string) ($profile['status'] ?? '');
$profile_id = (int) ($profile['id'] ?? 0);
$profile_avatar_url = trim((string) ($profile['avatar_url'] ?? base_url('assets/avatars/avatar.png')));

$status_label = $profile_status === '1' ? 'Active' : ($profile_status === '0' ? 'Inactive' : 'Unknown');
$account_type_label = $profile_user_type !== '' ? ucfirst($profile_user_type) : 'Unknown';
?>
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item active">Profile</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user-circle"></i>
			My <span class="fw-300">Profile</span>
		</h1>
	</div>

	<div class="row">
		<div class="col-xl-4 col-lg-5">
			<div class="panel" id="panel-profile-summary">
				<div class="panel-hdr">
					<h2>Account <span class="fw-300"><i>Summary</i></span></h2>
				</div>
				<div class="panel-container show">
					<div class="panel-content text-center">
						<?php if ($this->session->flashdata('msg_succ')) { ?>
						<div class="alert alert-success alert-dismissible fade show text-left" role="alert">
							<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
							<?php echo htmlspecialchars((string) $this->session->flashdata('msg_succ'), ENT_QUOTES, 'UTF-8'); ?>
						</div>
						<?php } ?>
						<?php if ($this->session->flashdata('msg_error')) { ?>
						<div class="alert alert-danger alert-dismissible fade show text-left" role="alert">
							<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
							<?php echo htmlspecialchars((string) $this->session->flashdata('msg_error'), ENT_QUOTES, 'UTF-8'); ?>
						</div>
						<?php } ?>

						<img src="<?php echo htmlspecialchars($profile_avatar_url, ENT_QUOTES, 'UTF-8'); ?>" class="rounded-circle shadow-2 mb-3" alt="Profile" style="width:96px;height:96px;object-fit:cover;">
						<h3 class="mb-1"><?php echo htmlspecialchars($profile_name !== '' ? $profile_name : 'Unknown User', ENT_QUOTES, 'UTF-8'); ?></h3>
						<div class="text-muted mb-2">@<?php echo htmlspecialchars($profile_username !== '' ? $profile_username : 'unknown', ENT_QUOTES, 'UTF-8'); ?></div>
						<div class="mb-3">
							<span class="badge badge-info mr-1"><?php echo htmlspecialchars($account_type_label, ENT_QUOTES, 'UTF-8'); ?></span>
							<?php if ($profile_role_name !== '') { ?>
							<span class="badge badge-secondary"><?php echo htmlspecialchars($profile_role_name, ENT_QUOTES, 'UTF-8'); ?></span>
							<?php } ?>
						</div>
						<form method="post" action="" enctype="multipart/form-data" class="mb-3 text-left">
							<div class="form-group text-left">
								<label class="form-label" for="avatar_file">Upload Profile Image</label>
								<input type="file" class="form-control" id="avatar_file" name="avatar_file" accept=".jpg,.jpeg,.png,.gif,.webp">
								<small class="form-text text-muted">Accepted: JPG, PNG, GIF, WEBP. Max size: 4 MB.</small>
							</div>
							<button type="submit" class="btn btn-outline-info btn-sm btn-block" name="upload_avatar" value="1">Upload Avatar</button>
						</form>
						<div class="d-flex flex-wrap justify-content-center">
							<a href="<?php echo ADMIN_URL; ?>change_username/" class="btn btn-outline-primary btn-sm mr-2 mb-2">Change Username</a>
							<a href="<?php echo ADMIN_URL; ?>change_password/" class="btn btn-primary btn-sm mb-2">Change Password</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-8 col-lg-7">
			<div class="panel" id="panel-profile-details">
				<div class="panel-hdr">
					<h2>Profile <span class="fw-300"><i>Details</i></span></h2>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<div class="row">
							<div class="col-md-6 mb-3">
								<label class="form-label text-muted">Full Name</label>
								<div class="fs-lg"><?php echo htmlspecialchars($profile_name !== '' ? $profile_name : 'Not set', ENT_QUOTES, 'UTF-8'); ?></div>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label text-muted">Username</label>
								<div class="fs-lg"><?php echo htmlspecialchars($profile_username !== '' ? $profile_username : 'Not set', ENT_QUOTES, 'UTF-8'); ?></div>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label text-muted">Account Type</label>
								<div class="fs-lg"><?php echo htmlspecialchars($account_type_label, ENT_QUOTES, 'UTF-8'); ?></div>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label text-muted">Role</label>
								<div class="fs-lg"><?php echo htmlspecialchars($profile_role_name !== '' ? $profile_role_name : 'Not assigned', ENT_QUOTES, 'UTF-8'); ?></div>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label text-muted">Contact</label>
								<div class="fs-lg"><?php echo htmlspecialchars($profile_mobile !== '' ? $profile_mobile : 'Not set', ENT_QUOTES, 'UTF-8'); ?></div>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label text-muted">Email / Login</label>
								<div class="fs-lg"><?php echo htmlspecialchars($profile_email !== '' ? $profile_email : 'Not set', ENT_QUOTES, 'UTF-8'); ?></div>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label text-muted">User ID</label>
								<div class="fs-lg"><?php echo htmlspecialchars((string) $profile_id, ENT_QUOTES, 'UTF-8'); ?></div>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label text-muted">Status</label>
								<div class="fs-lg"><?php echo htmlspecialchars($status_label, ENT_QUOTES, 'UTF-8'); ?></div>
							</div>
							<?php if ($profile_created !== '') { ?>
							<div class="col-md-12 mb-1">
								<label class="form-label text-muted">Created</label>
								<div class="fs-lg"><?php echo htmlspecialchars($profile_created, ENT_QUOTES, 'UTF-8'); ?></div>
							</div>
							<?php } ?>
						</div>

						<hr>

						<form method="post" action="" class="mt-3">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="full_name">Full Name</label>
										<input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($profile_name, ENT_QUOTES, 'UTF-8'); ?>" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label" for="contact">Contact</label>
										<input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($profile_mobile, ENT_QUOTES, 'UTF-8'); ?>">
									</div>
								</div>
								<div class="col-12">
									<button type="submit" class="btn btn-success" name="save_profile" value="1">Save Profile</button>
								</div>
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
