<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>customerbalancemonitor">Customer balance monitor</a></li>
		<li class="breadcrumb-item active">Search</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user-friends"></i>
			Manage <span class="fw-300">Customerbalancemonitor</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Customerbalancemonitor <span class="fw-300"><i>Details</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
<div class="row">
					<div class="col-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="glyphicon glyphicon-list-alt"></i> Reports <span>&gt; Customer balance (excl. current period)</span></h1>
					</div>
					<div class="col-12 col-sm-5 col-md-5 col-lg-8">
						<ul id="sparks" class="">
							<li class="sparks-info">
							<?php
							     $income1 = $this->comm_model->get_income_metercustomer();
							     extract($income1);
								 $income2 = $this->comm_model->get_income_monthlycustomer();
								 extract($income2);
								 $intotal = $total1 + $total2;
							?>
								<h5> Income <span class="txt-color-blue">PHP <?php print_r(number_format($intotal,2));?></span></h5>
								<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm"></div>
							</li>
							<?php
							     $expense1 = $this->daily_model->get_outcome_expenses();
							     extract($expense1);
								 $expense2 = $this->daily_model->get_outcome_payroll();
								 extract($expense2);
								 $extotal = $extotal1 + $extotal2;
							?>
							<li class="sparks-info">
								<h5> Expense <span class="txt-color-purple">PHP <?php print_r(number_format($extotal,2));?></span></h5>
								<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm"></div>
							</li>
							<?php
							     $total_customer = $this->daily_model->total_customer();
							     extract($total_customer);
							?>
							<li class="sparks-info">
								<h5> Total Customer <span class="txt-color-greenDark">&nbsp;<?php print_r($count_id);?></span></h5>
								<div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm"></div>
							</li>
						</ul>
					</div>
				</div>

				<section id="widget-grid" class="">

					<div class="row">

						<div class="col-sm-6 col-lg-12">

							<div class="panel panel-default">

								

									<fieldset>
										<legend>
											Customer balance monitor
											<div class="pull-right" style="padding-right:20px;">
												<button type="button" class="btn btn-sm btn-primary" id="search" style="margin-bottom: 5px;">Display</button>
											</div>
										</legend>
										<p class="help-block" style="margin-left:12px;">
											Balance uses the same rules as <strong>Statement of Account</strong> (billings minus payments) but <strong>excludes the active billing period</strong> for the customer&rsquo;s zone (<code>bp_status = 1</code>, latest month/year). That period&rsquo;s bill is omitted; a payment that applies <em>only</em> to that period is omitted too. Mixed-period receipts are left as in SOA. Large zones may take a minute to load.
										</p>
										<div class="form-group col-lg-6">
											<div class="col-lg-12 controls">
												<div class="form-group">
													<span class="input-group-addon"><i class="icon-user"></i><strong>Status : </strong></span>
													<select class="form-control" name="status" id="status" required>
														<option value="">--All--</option>
														<option value="1">Active</option>
														<option value="0">Inactive</option>
														<option value="2">Disconnected</option>
													</select>
												</div>
											</div>
										</div>
										<div class="form-group col-lg-6">
											<div class="col-lg-12 controls">
												<div class="form-group">
													<span class="input-group-addon"><i class="icon-filter"></i><strong> Special privilege:</strong></span>
													<div style="padding: 10px 0;">
														<input type="checkbox" id="special_privilege" name="special_privilege" value="1" style="margin-right: 10px;">
														<label for="special_privilege" style="margin-bottom: 0;">Show only customers with special privilege</label>
													</div>
												</div>
											</div>
										</div>
										<div class="form-group col-lg-6">
											<div class="col-lg-12 controls">
												<div class="form-group">
													<span class="input-group-addon"><i class="icon-user"></i><strong> Zone:</strong></span>
													<select class="form-control" name="zone" id="zone" required>
														<option value="0">--All--</option>
														<?php foreach ($zone as $key => $value) { ?>
														<option value="<?php echo $value['id'];?>"><?php echo $value['zone'];?></option>
														<?php } ?>
													</select>
												</div>
											</div>
										</div>
										<div class="form-group col-lg-6">
											<div class="col-lg-12 controls">
												<div class="form-group">
													<span class="input-group-addon"><i class="icon-filter"></i><strong> Filter:</strong></span>
													<div style="padding: 10px 0;">
														<input type="checkbox" id="only_with_balance" name="only_with_balance" value="1" style="margin-right: 10px;">
														<label for="only_with_balance" style="margin-bottom: 0;">Show only customers with a non-zero balance</label>
													</div>
												</div>
											</div>
										</div>
										<div style="clear:both"></div>

										<div class="col-12" id="resultDiv" style="margin-top: 13px;"></div>

									</fieldset>

								</div>

							</div>

						</div>
					</div>

				</section>

			</div>

		</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
</body>
</html>


