<?php
	// Match addcustomer KPI source (grand_total income, same expense/customer counts)
	$sa4_loading_label = 'Leaking Ledger';
	$sa4_dt_entity = 'leaking records';
	$sa4_panel_id = 'panel-leakingentry';

	$income1 = $this->customer_model->get_income_metercustomer();
	extract($income1);
	$income2 = $this->customer_model->get_income_monthlycustomer();
	extract($income2);
	$intotal = (isset($total1) ? (float)$total1 : 0) + (isset($total2) ? (float)$total2 : 0);

	$expense1 = $this->customer_model->get_outcome_expenses();
	extract($expense1);
	$expense2 = $this->customer_model->get_outcome_payroll();
	extract($expense2);
	$extotal = (isset($extotal1) ? (float)$extotal1 : 0) + (isset($extotal2) ? (float)$extotal2 : 0);

	$total_customer = $this->customer_model->total_customer();
	extract($total_customer);
?>
<style>
	.select2-container { width: 100% !important; }
	.setStatus { cursor: pointer; }
	.select2-dropdown { z-index: 9999 !important; }
	.select2-container--open { z-index: 9999 !important; }
	#myModal .modal-body { max-height: 70vh; overflow-y: auto; }
</style>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/select2/select2.bundle.css">
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>Leakingentry">Leaking Entry</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>

	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-tint"></i>
			Manage <span class="fw-300">Leaking Ledger</span>
		</h1>
		<div class="subheader-block d-lg-flex align-items-center">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>INCOME</small>
				</span>
				<span class="fw-500 fs-xl d-block color-primary-500">
					₱ <?php echo number_format($intotal, 2); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#886ab5" sparkHeight="32px" sparkBarWidth="5px" values="3,4,3,6,7,3,3,6,2,6,4"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>EXPENSE</small>
				</span>
				<span class="fw-500 fs-xl d-block color-danger-500">
					₱ <?php echo number_format($extotal, 2); ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#fe6bb0" sparkHeight="32px" sparkBarWidth="5px" values="1,4,3,6,5,3,9,6,5,9,7"></span>
		</div>
		<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
			<div class="d-inline-flex flex-column justify-content-center mr-3">
				<span class="fw-300 fs-xs d-block opacity-50">
					<small>TOTAL CUSTOMER</small>
				</span>
				<span class="fw-500 fs-xl d-block color-success-500">
					<?php echo (int) $count_id; ?>
				</span>
			</div>
			<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#1dc9b7" sparkHeight="32px" sparkBarWidth="5px" values="2,5,3,7,4,6,3,8,5,4,6"></span>
		</div>
	</div>

	<?php if ($this->session->flashdata('msg_succ')) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<strong>Success!</strong> <?php echo $this->session->flashdata('msg_succ'); ?>
	</div>
	<?php } ?>

	<section id="widget-grid" class="">
		<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
		<div class="row">
			<div class="col-xl-12">
				<div id="panel-leakingentry" class="panel">
					<div class="panel-hdr">
						<h2>Leaking <span class="fw-300"><i>Ledger</i></span></h2>
						<div class="panel-toolbar">
							<button type="button" class="btn btn-primary btn-sm waves-effect waves-themed mr-2" id="add_record">
								<i class="fal fa-plus mr-1"></i> Add Record
							</button>
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<form method="post" action="<?php echo ADMIN_URL; ?>leakingentry/multi_delete" id="sa4-list-form">
								<div class="row mb-3 align-items-end">
									<div class="col-sm-12">
										<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
											<i class="fal fa-trash-alt mr-1"></i> Delete Selected
										</button>
									</div>
								</div>
								<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
											
												<thead>			                
													<tr>
														<th data-hide="phone"><input type="checkbox" id="dt_select_all"/></th>
														<th data-hide="phone">S No</th>
														<th data-hide="expand">Customer Name</th>
														<th data-hide="expand">Customer ID</th>
                                                        <th data-hide="expand">Billing No.</th>
                                                        <th data-hide="expand">Billing Period</th>
                                                        <th data-hide="expand">Billing Amount</th>
                                                        <th data-hide="expand">Discount %</th>
														<th data-hide="expand">Discount Amount</th>
                                                        <th data-hide="expand">Total Amount</th>
                                                        <th data-hide="expand">Balance Amount</th>
                                                        <th data-hide="expand">Payment Date</th>
                                                        <th data-hide="expand">Status</th>
														<th data-hide="expand">Action</th>
													</tr>
												</thead>
												<tbody>
												  <?php
														if(count($record) > 0){
                                                        $i=1;
                                                        foreach($record as $key => $row){ 
															$total_payment = $this->my_model->get_total_payment($row['leaking_id'])['totalpayment'];
															$leaking_balance = $row['leaking_total_amount'] - $total_payment;
													?>   
													<tr>
														<td><input type="checkbox" class="ace" name="delete_ids[]" id="delete_ids[]" value="<?php echo $row['leaking_id'];?>" /></td>
														<td><?php echo $i; ?></td>
														<td><?php echo stripslashes($row['last_name'].', '.$row['first_name'].' '.$row['middle_name']); ?></td>
														<td><?php echo stripslashes($row['customer_id']); ?></td>
														<td><?php echo stripslashes($row['leaking_refno']); ?></td>
														<td><?php echo stripslashes(getMonthName($row['month'])[0]->month_name.' '.$row['year']); ?></td>
														<td align="right"><?php echo stripslashes(number_format($row['leaking_bill_amount'],2)); ?></td>
														<td align="right"><?php echo stripslashes($row['leaking_discount_percent']); ?></td>
														<td align="right"><?php echo stripslashes(number_format($row['leaking_discount_amount'],2)); ?></td>
														<td align="right"><?php echo stripslashes(number_format($row['leaking_total_amount'],2)); ?></td>
														<td align="right"><?php echo stripslashes(number_format($leaking_balance,2)); ?></td>
														<td>
															<?php
																$paydate = date('M j, Y',strtotime($row['leaking_date']));
																echo $paydate;
															?>
														</td>
														<td><?php 
															if($row['leaking_status']==1){
																echo '<label class="badge badge-danger setStatus" data-id="'.$row['leaking_id'].'" data-status="'.$row['leaking_status'].'">Pending</label>'; 
															}else if($row['leaking_status']==2){
																echo '<label class="badge badge-info">Approved</label>'; 

															}elseif($row['leaking_status']==4){
																echo '<label class="badge badge-primary">Posted</label>';
															}elseif($row['leaking_status']==5){
																echo '<label class="badge badge-success">Full Paid</label>';
															}else{
																echo '<label class="badge badge-secondary">Denied</label>'; 
															}
														
														?></td>
														<td>
														<!--<div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
																<a class="green" href="<?php echo ADMIN_URL;?>employee_logins/edit/<?php echo $row['id'];?>" title="Edit">
																	<i class="fal fa-edit"></i>
																</a>
                                                                <a class="red" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>employee_logins/delete/<?php echo $row['id'];?>';}" title="Delete">                                                                
																	<i class="fal fa-remove"></i>
																</a>														</div>-->
																<?php
															if($row['leaking_status']!='4' && $row['leaking_status']!='5'){
														?>		
																
																<a href="<?php echo ADMIN_URL;?>Leakingentry/edit/<?php echo $row['leaking_id'];?>" class="tooltip-success btn_edit" data-rel="tooltip" title="Edit" 
																data-leaking_id="<?php echo $row['leaking_id'];?>"
																data-fullname="<?php echo $row['customer_id'].'==>'.$row['last_name'].', '.$row['first_name'];?>"
																data-special_priviledge = "<?php echo $row['special_priviledge'];?>"
																data-billing_period="<?php echo getMonthName($row['month'])[0]->month_name.' '.$row['year'];?>"
																data-previous_reading="<?php echo $row['previous_reading'];?>"
																data-current_reading="<?php echo $row['reading'];?>"
																data-consumed="<?php echo $row['consumed'];?>"
																data-current_bill="<?php echo $row['unit_price'];?>"
																data-sc_discount="<?php echo $row['sc_discount'];?>"
																data-arrears="<?php echo $row['arrears'];?>"
																data-maintenance_fee="<?php echo $row['maintenance_fee'];?>"
																data-franchise_fee_percent="<?php echo $row['franchise_fee_percent'];?>"
																data-franchise_fee_amount="<?php echo $row['franchise_fee_amount'];?>"
																data-total_amount="<?php echo $row['amount'];?>"
																data-penalty="<?php echo $row['penalty'];?>"
																data-reading_date="<?php echo $row['date'];?>"
																data-bill_duedate="<?php echo date('d-m-Y',strtotime($row['leaking_bill_duedate']));?>"
																data-leaking_discount_percent="<?php echo $row['leaking_discount_percent'];?>"
																data-leaking_discount_amount="<?php echo $row['leaking_discount_amount'];?>"
																data-leaking_bill_amount="<?php echo $row['leaking_total_amount'];?>"
																data-leaking_date="<?php echo date('d-m-Y',strtotime($row['leaking_date']));?>"
																>
																	<span class="green">
																		<img src="<?php echo base_url();?>images/favicon/document-edit.gif">
																	</span>
																</a>

														
															<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?php echo ADMIN_URL;?>Leakingentry/delete/<?php echo $row['leaking_id'];?>';}" class="tooltip-error" data-rel="tooltip" title="Delete">
															<span class="red">
																<img src="<?php echo base_url();?>images/favicon/delete.png">
															</span>
														</a>
														
														<?php
															}
														?>
														
														<a href="<?php echo ADMIN_URL;?>Leakingentry/ledger/<?php echo $row['leaking_id'];?>" class="tooltip-success" data-rel="tooltip" title="Ledger">
																	<span class="blue">
																		<img src="<?php echo base_url();?>images/favicon/ledger.png">
																	</span>
																</a>
														<a href="<?php echo ADMIN_URL;?>Leakingentry/print_forms/<?php echo $row['leaking_id'];?>" target="_blank" class="tooltip-success" data-rel="tooltip" title="Print Forms">
																	<span class="orange">
																		<i class="fal fa-print"></i>
																	</span>
																</a>
													</td>

														
														
													</tr>
														<?php $i++;} }?>	
												</tbody>
											</table>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="myModalLabel">Add New Leaking Record</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								
                                <form name="frm_update" id="frm_update" action="" method="POST">
									<div class="row" id="customer_id_div">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Customer : </strong></span>
                                                <select name="customer_id" id="customer_id" placeholder="Type text to search..." required>
													<option value="">--Select--</option>	
													<?php
													
													foreach ($customer_listing as $key => $value) {
														?>
														<option value="<?php echo $value['customer_id'].'==>'.$value['special_priviledge']; ?>"> <?php echo $value['customer_id'] . ' ==> ' . $value['last_name'] . ', ' . $value['first_name'] . ' ' . $value['middle_name']; ?></option>
													<?php }
													?>
												</select>
												<input type="hidden" name="special_priviledge" id="special_priviledge" value="0"/>
												<?php echo form_error('customer_id'); ?>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row" id="billing_period_div">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Billing Period : </strong></span>
                                                <select class="form-control" type="text" id="billing_period" name="billing_period">
													<option value="">--Select--</option>
												</select>
												
                                                <?php echo form_error('billing_period'); ?>
                                            </div>
                                        </div>
                                    </div>

									<div class="row" id="customer_id_div_text">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Customer : </strong></span>
                                                <input type="text" name="customer_id_text" id="customer_id_text"  style="background-color:yellow;" class="form-control" readonly>
												
												
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row" id="billing_period_div_text">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Billing Period : </strong></span>
                                                <input type="text" name="billing_period_text" id="billing_period_text"  style="background-color:yellow;" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
									<input type="hidden" name="refno" id="refno"/>
									<input type="hidden" name="leaking_id" id="leaking_id"/>
									<input type="hidden" name="gross_amount" id="gross_amount" value="0.00"/>

<section id="leaking_option">
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Previous Reading : </strong></span>
                                                <input class="form-control" type="text" id="previous_reading" name="previous_reading" style="background-color:yellow;" value="0" readonly>
                                                <?php echo form_error('previous_reading'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Current Reading : <i style="color:red;">*</i></strong></span>
                                                <input class="form-control" type="text" id="current_reading" name="current_reading" style="background-color:yellow;" readonly>
                                                <?php echo form_error('current_reading'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Consumed : </strong></span>
                                                <input class="form-control" type="text" id="consumed" name="consumed" style="background-color:yellow;" readonly>
                                                <?php echo form_error('consumed'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Current Bill : </strong></span>
                                                <input class="form-control" type="text" id="current_bill" name="current_bill" style="background-color:yellow;" readonly>
                                                <?php echo form_error('current_bill'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>SC Discount : </strong></span>
                                                <input class="form-control" type="text" id="sc_discount" name="sc_discount" style="background-color:yellow;" readonly>
                                                <?php echo form_error('sc_discount'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Arrears : </strong></span>
                                                <input class="form-control" type="text" id="arrears" name="arrears" style="background-color:yellow;" readonly>
                                                <?php echo form_error('arrears'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>WM Maintenance Fee : </strong></span>
												<input class="form-control" type="text" id="maintenance_fee" name="maintenance_fee" style="background-color:yellow;" readonly>
												<?php echo form_error('maintenance_fee'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>Franchise Tax % : </strong></span>
												<input class="form-control" type="text" id="franchise_fee_percent" name="franchise_fee_percent" style="background-color:yellow;" readonly>
												<?php echo form_error('franchise_fee_percent'); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 controls">
											<div class="form-group"> 
												<span class="input-group-addon"><strong>Franchise Tax Amount : </strong></span>
												<input class="form-control" type="text" id="franchise_fee_amount" name="franchise_fee_amount" style="background-color:yellow;" readonly>
												<?php echo form_error('franchise_fee_amount'); ?>
											</div>
										</div>
									</div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Amt before due date : </strong></span>
                                                <input class="form-control" type="text" id="total_amount" name="total_amount" style="background-color:yellow;" readonly>
                                                <?php echo form_error('arrears'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Amt after due date : </strong></span>
                                                <input class="form-control" type="text" id="penalty" name="penalty" style="background-color:yellow;" readonly>
                                                <?php echo form_error('arrears'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Reading date : </strong></span>
                                                <input class="form-control" type="text" id="reading_date" name="reading_date" style="background-color:yellow;" readonly>
                                                <?php echo form_error('reading_date'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Due date : </strong></span>
                                                <input class="form-control" type="text" id="due_date" name="due_date" style="background-color:yellow;" readonly>
                                                <?php echo form_error('due_date'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Payment Date : </strong></span>
                                                <input class="form-control" type="text" id="payment_date" name="payment_date" style="background-color:white;" value="<?php echo date('d-m-Y');?>" required>
                                                <?php echo form_error('payment_date'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Leaking Disc(%) : </strong></span>
                                                <input class="form-control" type="text" id="leaking_percent" name="leaking_percent" style="background-color:white;" required>
                                                <?php echo form_error('leaking_percent'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Leaking Disc(Amt) : </strong></span>
                                                <input class="form-control" type="text" id="leaking_amount" name="leaking_amount" style="background-color:yellow;" readonly>
                                                <?php echo form_error('leaking_percent'); ?>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-lg-12 controls">
                                            <div class="form-group"> 
                                                <span class="input-group-addon"><strong>Bill Amount : </strong></span>
                                                <input class="form-control" type="text" id="bill_amount" name="bill_amount" style="background-color:yellow;" readonly>
                                                <?php echo form_error('bill_amount'); ?>
                                            </div>
                                        </div>
                                    </div>

</section>


								</form>
				
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
									Cancel
								</button>
								<button type="submit" class="btn btn-sm btn-primary" id="btn_save" name="btn_save" value="add">
									Add
								</button>
							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include('footer.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/formplugins/select2/select2.bundle.js"></script>
<script src="<?php echo base_url(); ?>sa4/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
<script type="text/javascript">
$(document).ready(function() {
	if ($.fn.datepicker) {
		$("#payment_date").datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true,
			todayHighlight: true,
			orientation: 'bottom auto'
		});
	}

// Function to initialize Select2 properly for modals
			function initCustomerSelect2() {
				// Destroy existing Select2 instance if it exists
				if ($('#customer_id').hasClass('select2-hidden-accessible')) {
					$('#customer_id').select2('destroy');
				}
				// Initialize Select2 with proper configuration for modals
				$('#customer_id').select2({
					dropdownParent: $('#myModal'),
					width: '100%',
					placeholder: 'Type text to search...'
				});
			}
			
			// Initialize Select2 when modal is shown to fix search input issue
			$('#myModal').on('shown.bs.modal', function () {
				// Only initialize if customer dropdown is visible
				if ($('#customer_id_div').is(':visible')) {
					initCustomerSelect2();
				}
			});
			
			// Initialize Select2 on page load (will be reinitialized when modal opens)
			$('#customer_id').select2({
				width: '100%',
				placeholder: 'Type text to search...'
			});
			
			$('#customer_id').on('change', function(evt){
				evt.preventDefault();
				var customer_id = $(this).val().split('==>')[0];

				var $special_priviledge = $(this).val().split('==>')[1];
				$('#special_priviledge').val($special_priviledge);

				var cust_id = customer_id;
				$('#leaking_option').hide();
				if(cust_id){
					// Send an AJAX request to the backend
					$.ajax({
						url: '<?php echo ADMIN_URL; ?>Leakingentry/get_customer_meter_reading', // Backend PHP script
						type: 'POST',
						data: { customer_id: cust_id },
						dataType: 'json',
						success: function(response) {
							// Clear the child dropdown
							$('#billing_period').empty().append('<option value="">--Select--</option>');

							// Populate the child dropdown with the response data
							if (response.length > 0) {
								$.each(response, function(index, item) {
									if(item.status==0){
										$('#billing_period').append('<option value="' + item.id+' '+item.month+' '+item.year+ '">' + item.month_name+' '+item.year+ '</option>');
									}
									
								});
							}
						},
						error: function(xhr, status, error) {
							console.error('AJAX Error: ' + status + error);
						}
					});
				}else{
					// If no parent is selected, clear the child dropdown
					$('#billing_period').empty().append('<option value="">--Select--</option>');
					//$('#leaking_option').hide();
				}
			});
			
			$('#billing_period').on('change', function(evt){
				evt.preventDefault();
				var meterreading_id = $(this).val();
				if(meterreading_id){
					// Send an AJAX request to the backend
					$.ajax({
						url: '<?php echo ADMIN_URL; ?>Leakingentry/get_customer_meter_reading_detail', // Backend PHP script
						type: 'POST',
						data: { meterreading_id: meterreading_id },
						dataType: 'json',
						success: function(response) {
							
							// Populate the child dropdown with the response data
							if (response) {
								console.log(response);
								const sqlDate = response.bp_due_date;
								const parts = sqlDate.split('-');//y-m-d
								//const jsDate = new Date(parts[0], parts[1] - 1, parts[2]); // Month is 0-indexed
								const duedate = parts[2]+'-'+ parts[1]+'-'+ parts[0];
								$('#leaking_option').show();
								$('#previous_reading').val(response.previous_reading);
								$('#current_reading').val(response.reading);
								$('#consumed').val(response.consumed);
								$('#current_bill').val(response.unit_price);
								$('#sc_discount').val(response.sc_discount);
								$('#arrears').val(response.arrears);
								$('#maintenance_fee').val(response.maintenance_fee);
								$('#franchise_fee_percent').val(response.franchise_fee_percent);
								$('#franchise_fee_amount').val(response.franchise_fee_amount);
								$('#total_amount').val(response.amount);
								$('#penalty').val(response.penalty);
								$('#reading_date').val(response.date);
								$('#due_date').val(duedate);
								$('#refno').val(response.refno);
								
							}
						},
						error: function(xhr, status, error) {
							console.error('AJAX Error: ' + status + error);
						}
					});
				}else {
					// If no parent is selected, clear the child dropdown
					//$('#billing_period').empty().append('<option value="">--Select--</option>');
					$('#leaking_option').hide();
				}
			});
			
            $('#add_record').on('click', function(evt){
                evt.preventDefault();
				$('#myModal').modal('show');
				$('#leaking_option').hide();
                $('#myModalLabel').text('Add New Leaking Record');
                $('#btn_save').text('Save');
				$('#btn_save').val('add');
                $('#btn_save').prop('disabled', true);

				$('#customer_id_div').show();
				$('#billing_period_div').show();

				$('#customer_id_div_text').hide();
				$('#billing_period_div_text').hide();
				$('#maintenance_fee').val('0.00');
				$('#franchise_fee_percent').val('0.00');
				$('#franchise_fee_amount').val('0.00');
				
				// Reset customer dropdown
				$('#customer_id').val('').trigger('change');
            });

			$(document).on('click', '.btn_edit', function(evt){
				evt.preventDefault();
				$fullname = $(this).data('fullname');
				$billing_period = $(this).data('billing_period');
				$leaking_id = $(this).data('leaking_id');
				$special_priviledge = $(this).data('special_priviledge');
				$customer_id = $fullname.split('==>')[0];
				//$customer_name = $fullname.split('==>')[1];	
				$previous_reading = $(this).data('previous_reading');
				$current_reading = $(this).data('current_reading');
				$consumed = $(this).data('consumed');
				$current_bill = $(this).data('current_bill');
				$sc_discount = $(this).data('sc_discount');
				$arrears = $(this).data('arrears');
				$maintenance_fee = $(this).data('maintenance_fee');
				$franchise_fee_percent = $(this).data('franchise_fee_percent');
				$franchise_fee_amount = $(this).data('franchise_fee_amount');
				$total_amount = $(this).data('total_amount');
				$penalty = $(this).data('penalty');
				$reading_date = $(this).data('reading_date');
				$bill_duedate = $(this).data('bill_duedate');
				$leaking_discount_percent = $(this).data('leaking_discount_percent');
				$leaking_discount_amount = $(this).data('leaking_discount_amount');
				$leaking_bill_amount = $(this).data('leaking_bill_amount');
				$leaking_date = $(this).data('leaking_date');

				$('#myModal').modal('show');
				$('#leaking_option').show();
				$('#myModalLabel').text('Edit Leaking Record');
				$('#btn_save').text('Update');
				$('#btn_save').val('edit');
				$('#btn_save').prop('disabled', false);

				$('#customer_id_div').hide();
				$('#billing_period_div').hide();
				$('#customer_id_div_text').show();
				$('#billing_period_div_text').show();

				$('#customer_id_text').val($fullname);
				$('#special_priviledge').val($special_priviledge);
				$('#billing_period_text').val($billing_period);
				$('#leaking_id').val($leaking_id);
				$('#previous_reading').val($previous_reading);
				$('#current_reading').val($current_reading);
				$('#consumed').val($consumed);
				$('#current_bill').val($current_bill);
				$('#sc_discount').val($sc_discount);
				$('#arrears').val($arrears);
				$('#maintenance_fee').val($maintenance_fee);
				$('#franchise_fee_percent').val($franchise_fee_percent);
				$('#franchise_fee_amount').val($franchise_fee_amount);
				$('#total_amount').val($total_amount);
				$('#penalty').val($penalty);
				$('#reading_date').val($reading_date);
				$('#due_date').val($bill_duedate);
				$('#leaking_percent').val($leaking_discount_percent);
				$('#leaking_amount').val($leaking_discount_amount);
				$('#bill_amount').val($leaking_bill_amount);
				$('#payment_date').val($leaking_date);

			});


			$('#payment_date').on('change', function(evt){
				evt.preventDefault();
				computeDiscount();
				
			});
			$('#leaking_percent').on('blur', function(evt){
				evt.preventDefault();
				computeDiscount();

			});

			$('#btn_save').on('click', function(evt){
				evt.preventDefault();
				var refno = $('#refno').val();
				//var $customer_id = $(this).val().split('==>')[0];
				var customer_id = $('#customer_id').val().split('==>')[0];
				var leaking_percent = $('#leaking_percent').val();
				var payment_date = $('#payment_date').val();
				var leaking_amount = $('#leaking_amount').val();
				var bill_amount = $('#bill_amount').val();
				var gross_amount = $('#gross_amount').val();
				var leaking_id = $('#leaking_id').val();
				var due_date = $('#due_date').val();
				var btn_save = $('#btn_save').val();

				const formData = new FormData();
				formData.append("refno",refno);
				formData.append("customer_id", customer_id);
				formData.append("leaking_percent", leaking_percent);
				formData.append("payment_date", payment_date);
				formData.append("leaking_amount", leaking_amount);
				formData.append("bill_amount", bill_amount);
				formData.append("gross_amount", gross_amount);
				formData.append("due_date", due_date);
				formData.append("btn_save", btn_save);
				formData.append("leaking_status", 1);
				if(leaking_id){
					formData.append("leaking_id", leaking_id);
				}

				$.ajax({
					url: '<?php echo ADMIN_URL;?>Leakingentry/add/',
					type: 'POST',
					data: formData,
					contentType: false,
					processData: false,
					beforeSend: function() {
						showSpinner(); // Call this to show the spinner
					},
					success: function (response) {
						//const result = JSON.parse(response);
						if (response=='success') {
							window.location='<?php echo ADMIN_URL;?>Leakingentry';
							/*$.smallBox({
								title : "Saving Data",
								content : "Saving Data Successfully!",
								color : "#296191",
								timeout: 5000,
								icon : "fa fa-bell swing animated"
							}, function(){
								
							});*/

						}else{
							$.smallBox({
								title : "Saving Data",
								content : "Saving Data failed!",
								color : "#296191",
								timeout: 5000,
								icon : "fa fa-bell swing animated"
							});
						}
					},
					error: function () {
						alert("An error occurred while processing data.");
						$.smallBox({
								title : "Saving Data",
								content : "An error occurred while processing data.",
								color : "#296191",
								timeout: 5000,
								icon : "fa fa-bell swing animated"
							});
					}
				});

			});

			$(".setStatus").click(function(e) {
				e.preventDefault();
				var getStatus = $(this).data('status');
				var id = $(this).data('id');
				if (getStatus != 1) { return; }

				var approveUrl = '<?php echo ADMIN_URL;?>Leakingentry/status/'+id+'/2';
				var denyUrl = '<?php echo ADMIN_URL;?>Leakingentry/status/'+id+'/3';

				if (typeof $.SmartMessageBox === 'function') {
					$.SmartMessageBox({
						title : "Approval Action",
						content : "Please select option below",
						buttons : '[Cancel][Denied][Approved]'
					}, function(ButtonPressed) {
						if (ButtonPressed === "Approved") {
							window.location = approveUrl;
						}
						if (ButtonPressed === "Denied") {
							window.location = denyUrl;
						}
					});
				} else {
					var choice = window.prompt('Type Approved or Denied (Cancel to abort):', 'Approved');
					if (!choice) { return; }
					choice = String(choice).trim().toLowerCase();
					if (choice === 'approved') {
						window.location = approveUrl;
					} else if (choice === 'denied') {
						window.location = denyUrl;
					}
				}
			});
			//$('#customer_id').select2();

});

function parseDmyString(dateStr) {
  			const [day, month, year] = dateStr.split('-');
  			return new Date(year, month - 1, day); // month - 1 because months are 0-indexed
		}

		function computeDiscount(){
			var leakval = $('#leaking_percent').val();
			if(leakval){
				$('#btn_save').prop('disabled', false);
				var discountBase = parseFloat(String($('#current_bill').val() || '0').replace(/,/g, ''));
				var btnMode = $('#btn_save').val();
				if(btnMode === 'add'){
					var duedate = $('#due_date').val();
					var paymentdate = $('#payment_date').val();
					var date1 = parseDmyString(duedate);
					var date2 = parseDmyString(paymentdate);
					var amountBeforeDue = parseFloat(String($('#total_amount').val() || '0').replace(/,/g, ''));
					var amountAfterDue = parseFloat(String($('#penalty').val() || '0').replace(/,/g, ''));
					var currentBill = date2 <= date1 ? amountBeforeDue : amountAfterDue;
					$('#gross_amount').val(currentBill.toFixed(2));
					var addLeakingDisc = (discountBase * leakval)/100;
					var addBillAmount = currentBill - addLeakingDisc;
					if(addBillAmount < 0){ addBillAmount = 0; }
					$('#leaking_amount').val(addLeakingDisc.toFixed(2));
					$('#bill_amount').val(addBillAmount.toFixed(2));
					return;
				}
				var duedate = $('#due_date').val();
				var paymentdate = $('#payment_date').val();
				var special_priviledge = $('#special_priviledge').val();
				var date1 = parseDmyString(duedate);
				var date2 = parseDmyString(paymentdate);
				 console.log('special Previous:'+special_priviledge);
				if(date1 < date2 && special_priviledge==0){
					$('#gross_amount').val($('#penalty').val());
					var leakingdisc = (discountBase * leakval)/100;
					var billamount = $('#penalty').val() - leakingdisc;
					if(billamount < 0){ billamount = 0; }
					$('#leaking_amount').val(leakingdisc.toFixed(2));
					$('#bill_amount').val(billamount.toFixed(2));
				}else{
					$('#gross_amount').val($('#total_amount').val());
					var leakingdisc = (discountBase * leakval)/100;
					var billamount = $('#total_amount').val() - leakingdisc;
					if(billamount < 0){ billamount = 0; }
					$('#leaking_amount').val(leakingdisc.toFixed(2));
					$('#bill_amount').val(billamount.toFixed(2));
				}
			}else{
				
				$('#btn_save').prop('disabled', true);
			}
		}

		
		
</script>
</body>
</html>
