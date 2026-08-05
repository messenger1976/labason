<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addcustomer/add/">Add Customer</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL?>addcustomer/search/">Search Customer</a></li>
		<li class="breadcrumb-item active">List View</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user-friends"></i>
			Manage <span class="fw-300">Month Customer Invoice</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Month Customer Invoice <span class="fw-300"><i>Details</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
<section id="widget-grid" class="">

					<!-- row -->
					<div class="row">
				
						<div class="col-sm-6 col-lg-12">

							<!-- your contents here -->
							<div class="panel panel-default">
								<div class="panel-body status">
									<div class="who clearfix">
										Pay Status 	: 	<b><?php
															if($record['status'] == '1'){
																echo "Paid";
															}if($record['status'] == '0'){
																echo "Un-Paid";
															}
															?></b><br/><br/>
										<span class="name">Paid Date : 	<?php echo date("M d, Y ", strtotime($record['date'])); ?></span>
										<span class="name">Cashier/Teller : 	<?php echo strtoupper($record['employee_name']); ?></span><br/>
										<span class="name">Company Address : 	<?php echo (stripslashes(str_replace('\n','',$address['content']))); ?></span>
									</div>
									
									<div class="image">
									
									        <table width="100%" border="0" cellpadding="5" cellspacing="5" bgcolor="#dcdcdc">
												  <thead>
													<tr class="table-heading">
													  <th width="6%" align="left" valign="middle" bgcolor="#ececec">OR #</th>
													  <th width="22%" align="left" valign="middle" bgcolor="#ececec">Customer Info</th>
													  <th width="16%" align="left" valign="middle" bgcolor="#ececec">Meter Details</th>
													  <th width="12%" align="left" valign="middle" class="center" bgcolor="#ececec">Current Bill</th>	
													  		  
													  <th width="8%" align="left" valign="middle" bgcolor="#ececec">Gross Amount</th>
													 
													  <th width="8%" align="left" valign="middle" class="center" bgcolor="#ececec">Leaking Disc</th>
													  <th width="8%" align="left" valign="middle" class="center" bgcolor="#ececec">VAT</th>
													  <th width="9%" align="left" valign="middle" class="center" bgcolor="#ececec">Total</th>
													  
													  <th width="9%" align="left" valign="middle" class="center" bgcolor="#ececec">Transaction Date</th>
													 </tr>
													</thead>
												  <tbody>  
													<tr class="odd gradeX">
													  <td align="left" valign="middle" bgcolor="#FFFFFF"><?php echo stripslashes(sprintf('%07d',$record['or_number'])); ?></td>
													  <td height="30" align="left" valign="middle" bgcolor="#FFFFFF" class="forgotpassword">
														<b>Customer-id: </b><?php echo stripslashes($record['customer_id']); ?><br/>
														<b>Customer Name: </b><?php echo stripslashes(strtoupper($record['name'])); ?><br/>
														<b>Zone: </b><?php echo stripslashes(strtoupper($record['zonename'])); ?><br/>
													  </td>
													  <td height="30" align="left" valign="middle" bgcolor="#FFFFFF" class="forgotpassword">
														<b>Previous Reading : </b><?php echo stripslashes($record['oldmeter']); ?><br/>
														<b>Current Reading : </b> <?php echo stripslashes($record['aftermeter']); ?><br/>
														<b>Consumed : </b> <?php echo stripslashes($record['consumedunits']); ?><br/>
													  </td>			
													  <?php
													  $penaltyamount = $record['per_unit']-$record['amount'];
													  ?>	  
													  <td align="left" valign="middle" bgcolor="#FFFFFF" >
														<b>Amount :</b> <?php echo stripslashes(number_format($record['unit_price'],2)); ?><br/>
														<b>SC Discount :</b> (<?php echo stripslashes(number_format($record['sc_discount'],2)); ?>)<br/>
														<b>Maintenance Fee :</b> <?php echo stripslashes(isset($record['maintenance_fee']) ? number_format($record['maintenance_fee'],2) : '0.00'); ?><br/>
														<b>Franchise Tax (<?php echo stripslashes(isset($record['franchise_fee_percent']) ? number_format($record['franchise_fee_percent'],2) : '0.00'); ?>%) :</b> <?php echo stripslashes(isset($record['franchise_fee_amount']) ? number_format($record['franchise_fee_amount'],2) : '0.00'); ?><br/>	
														<b>Plus Penalty :</b> <?php echo stripslashes(number_format($penaltyamount,2)); ?><br/>					
													  </td>
													  
													  <td align="left" valign="middle" bgcolor="#FFFFFF" class="right">   
														<?php echo stripslashes(number_format($record['amount']+$penaltyamount,2)); ?>
													  </td>
													  
													  <td align="left" valign="middle" bgcolor="#FFFFFF" class="center">
													    (<?php echo stripslashes(number_format($record['leaking_amount'],2)); ?>)
													   </td>
									                   <td align="left" valign="middle" bgcolor="#FFFFFF" class="center">
													    (<?php echo stripslashes(number_format($record['vat_amount'],2)); ?>)
													   </td>				  
													  <td align="left" valign="middle" bgcolor="#FFFFFF" class="right">
														<?php echo number_format($record['grand_total'],2); ?><br>			  
													  </td>
													 
													  <td align="left" valign="middle" bgcolor="#FFFFFF" class="right">
														<b>Transaction Date:</b> <?php echo date("M d, Y ", strtotime($record['date'])) ;?><br/>
														<b>Due Date:</b> <?php echo isset($record['bp_due_date']) && $record['bp_due_date'] != '' ? date("M d, Y ", strtotime($record['bp_due_date'])) : 'N/A'; ?>			  
													  </td>
													  </tr>  
													</tbody>
												  </table>
									
                                    </div>									 
							

					</div>
					
					<!-- end row -->

				</section>
				<!-- end widget grid -->
				<a href="<?php echo ADMIN_URL;?>addpaymentcustomer" class="btn btn-primary">Back</a>

			</div>
			
				
					</div>
				
					<!-- end row -->

					

				</section>
				<!-- end widget grid -->
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include('footer.php'); ?>
</body>
</html>


