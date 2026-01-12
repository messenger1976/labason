<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<title> SmartAdmin </title>
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
	</head>
	
<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">
				<span class="ribbon-button-alignment"> 
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
						<i class="fa fa-refresh"></i>
					</span> 
				</span>

				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li><a href="<?php echo ADMIN_URL?>">Home</a></li>
					<li><a href="<?php echo ADMIN_URL?>global_settings/"> Global Settings </a></li>
					<li>Edit</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa fa-pencil-square-o fa-fw"></i> Edit <span>> Global Settings </span></h1>
					</div>
				</div>
				<!-- widget grid -->
				<section id="widget-grid" class="">

					<!-- row -->
					<div class="row">

						<!-- a blank row to get started -->
						<div class="col-sm-6 col-lg-12">
							<!-- your contents here -->
							<div class="panel panel-default">
								
								<div class="widget-body">
				
									<form class="form-horizontal" role="form" name="myform" id="myform" method="post" action="" enctype="multipart/form-data">
									  	
										<?php if($msg != ''){?>
										<div class="alert alert-block alert-danger">
											<button type="button" class="close" data-dismiss="alert">
											<i class="icon-remove"></i>
											</button>
											<p>
												<i class="icon-warning"></i>
												<?php echo $msg?$msg:'';?>
											</p>
										</div>
										<?php } ?>	
										
										<fieldset>
											<legend>Global Settings - Edit</legend>
											
											<div class="form-group">
												<label class="col-md-2 control-label">Code: <span class="text-danger">*</span></label>
												<div class="col-md-8">
													<input type="text" id="code" name="code" class="form-control" value="<?php echo isset($record['code']) ? stripslashes($record['code']) : ''; ?>" required readonly>
													<span class="help-block">Setting code (cannot be changed)</span>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-md-2 control-label">Description: <span class="text-danger">*</span></label>
												<div class="col-md-8">
													<textarea id="description" name="description" class="form-control" rows="3" required><?php echo isset($record['description']) ? stripslashes($record['description']) : ''; ?></textarea>
													<span class="help-block">Description of this setting</span>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-md-2 control-label">Value: <span class="text-danger">*</span></label>
												<div class="col-md-8">
													<input type="number" id="value" name="value" class="form-control" step="0.01" min="0" value="<?php echo isset($record['value']) ? $record['value'] : '0'; ?>" required>
													<span class="help-block">Numeric value for this setting</span>
												</div>
											</div>

										</fieldset>
										
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<a href="<?php echo ADMIN_URL;?>global_settings" class="btn btn-default">Cancel</a>
													<input type="submit" class="btn btn-primary" name="add" id="add" value="Save">
												</div>
											</div>
										</div>
										
									</form>
				
								</div>
							</div>	
						</div>
					</div>
					<!-- end row -->

				</section>
				<!-- end widget grid -->

			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->
		

		<?php include('footer.php');?>

	</body>

</html>
