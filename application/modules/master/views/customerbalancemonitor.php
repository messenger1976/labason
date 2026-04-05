<!DOCTYPE html>

		<div id="main" role="main">

			<div id="ribbon">

				<span class="ribbon-button-alignment">
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
						<i class="fa fa-refresh"></i>
					</span>
				</span>

				<ol class="breadcrumb">
					<li><a href="<?php echo ADMIN_URL;?>">Home</a></li>
					<li><a href="<?php echo ADMIN_URL;?>customerbalancemonitor">Customer balance monitor</a></li>
					<li>Search</li>
				</ol>

			</div>

			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="glyphicon glyphicon-list-alt"></i> Reports <span>&gt; Customer balance (excl. current period)</span></h1>
					</div>
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
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

								<div class="widget-body">

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

										<div class="col-xs-12" id="resultDiv" style="margin-top: 13px;"></div>

									</fieldset>

								</div>

							</div>

						</div>
					</div>

				</section>

			</div>

		</div>

		<?php include('footer.php');?>

	</body>

</html>

<script src="<?php echo base_url();?>js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url();?>js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		pageSetUp();

		$('#search').on('click', function(evt) {
			evt.preventDefault();
			var zone = $("#zone").val();
			var status = $("#status").val();
			var special_privilege = $("#special_privilege").is(':checked') ? 1 : 0;
			var only_with_balance = $("#only_with_balance").is(':checked') ? 1 : 0;

			$("#resultDiv").html('<div class="alert alert-info">Loading balances (this may take a while)…</div>');

			$.ajax({
				type: "POST",
				url: "<?php echo ADMIN_URL;?>customerbalancemonitor/search",
				data: {
					zone: zone,
					status: status,
					special_privilege: special_privilege,
					only_with_balance: only_with_balance
				},
				success: function(response) {
					if (response && typeof response === 'string' && response.trim().length > 0) {
						$("#resultDiv").html(response.trim());
						var $tbl = $('#balance_monitor_table');
						if ($.fn.dataTable && $tbl.length && $tbl.find('tbody tr td[colspan]').length === 0) {
							$tbl.dataTable({
								"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>t<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
								"autoWidth": true,
								"order": [[5, "desc"]],
								"oLanguage": {
									"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
								}
							});
						}
					} else {
						$("#resultDiv").html('<div class="alert alert-warning">No data found.</div>');
					}
				},
				error: function(xhr) {
					$("#resultDiv").html('<div class="alert alert-danger"><strong>Error loading data.</strong> Status: ' + (xhr ? xhr.status : '') + '</div>');
				}
			});
		});
	});
</script>
