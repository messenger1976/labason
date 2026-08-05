<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>dashboard">Home</a></li>
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL;?>addcustomer">Add Customers</a></li>
		<li class="breadcrumb-item active">Customers Invoice</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon fal fa-user-friends"></i>
			Manage <span class="fw-300">Customer Invoice</span>
		</h1>
	</div>


	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>Customer Invoice <span class="fw-300"><i>Details</i></span></h2>
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

						<!-- a blank row to get started -->
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
										<span class="name">Paid Date : 	<?php echo date("M d, Y ", strtotime($record['create_date'])); ?></span><br/>
										<span class="from">Customer Type : 	<?php echo $record['customer_type']; ?></span>
									</div>
									
									<div class="image">
									
									        <table width="100%" border="0" cellpadding="5" cellspacing="5" bgcolor="#dcdcdc">
												  <thead>
													<tr class="table-heading">
													  <th width="6%" align="left" valign="middle" bgcolor="#ececec">S No</th>
													  <th width="22%" align="left" valign="middle" bgcolor="#ececec">Customer ID</th>
													  <th width="16%" align="left" valign="middle" bgcolor="#ececec">Customer Details</th>
													  <th width="12%" align="left" valign="middle" class="center" bgcolor="#ececec">Contact Details</th>				  
													  <th width="8%" align="left" valign="middle" bgcolor="#ececec">Customer Type</th>
													  <th width="8%" align="left" valign="middle" class="center" bgcolor="#ececec">Reference Name</th>
													  <th width="9%" align="left" valign="middle" class="center" bgcolor="#ececec">Created Date</th>
													 </tr>
													</thead>
												  <tbody>  
													<tr class="odd gradeX">
													  <td align="left" valign="middle" bgcolor="#FFFFFF">1</td>
													  <td height="30" align="left" valign="middle" bgcolor="#FFFFFF" class="forgotpassword">
														<b>Customer-id: </b><?php echo stripslashes($record['customer_id']); ?><br/>
													  </td>
													  <td height="30" align="left" valign="middle" bgcolor="#FFFFFF" class="forgotpassword">
														<b>Name:</b><?php echo stripslashes($record['first_name'].' '.$record['middle_name'].' '.$record['last_name']); ?><br/>
														<b>Gender :</b> <?php echo stripslashes($record['gender']); ?><br/>
														<b>DOB :</b> <?php echo stripslashes($record['DOB']); ?><br/>
														<b>Place Of Birth :</b> <?php echo stripslashes($record['place_of_birth']); ?><br/>
														<b>State :</b> <?php echo stripslashes($record['state']); ?><br/>
														<b>City :</b> <?php echo stripslashes($record['city']); ?><br/>
														<b>Zone :</b> <?php echo stripslashes($record['zones']); ?><br/>
														<b>Line Number :</b> <?php echo stripslashes($record['line_number']); ?><br/>
													  </td>				  
													  <td align="left" valign="middle" bgcolor="#FFFFFF" >
														<b>Mobile-1 :</b> <?php echo stripslashes($record['mobile1']); ?><br/>
														<b>Mobile-2 :</b> <?php echo stripslashes($record['mobile2']); ?><br/>
														<b>Email-Id :</b> <?php echo stripslashes($record['email_id']); ?><br/>	
														<b>Address :</b> <?php echo stripslashes($record['address']); ?><br/>						
													  </td>
													  <td align="left" valign="middle" bgcolor="#FFFFFF" class="right">   
														<?php echo stripslashes($record['customer_type']); ?>
														<?php if($record['customer_type'] == 'monthlycustomer'){echo'<b>Billng Plan:</b>'; echo stripslashes($record['billingplans_name']);} ?>
													  </td>
									                   <td align="left" valign="middle" bgcolor="#FFFFFF" class="center"><?php echo stripslashes($record['referenceperson']); ?></td>				  
													  <td align="left" valign="middle" bgcolor="#FFFFFF" class="right">
														<?php
														echo date("M d, Y ", strtotime($record['create_date'])) ;
															
														?>				  
													  </td>
													  </tr>  
													</tbody>
												  </table>
									
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


