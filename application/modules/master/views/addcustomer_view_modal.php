<div class="customer-view-modal">
	<table id="user" class="table table-bordered table-striped" style="clear: both">
		<tbody>
			<tr>
				<td>User Photo : </td>
				<td>
					<div class="image" style="width:150px; height:150px;">
						<img id="blah" src="<?php echo ADMIN_IMG_URL;?>upload/<?php echo $image['file']; ?>" style="width:150px; height:150px;"/>
					</div>
				</td>
			</tr>
			<tr>
				<td style="width:25%;">Account Subgroup Type : </td>
				<td style="width:75%"><?php echo stripslashes(str_replace('\n','',$record['subName'])); ?></td>
			</tr>
			<tr>
				<td style="width:25%;">Customer-Id : </td>
				<td style="width:75%"><?php echo stripslashes(str_replace('\n','',$record['customer_id'])); ?></td>
			</tr>
			<tr>
				<td>First Name :</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['first_name'])); ?></td>
			</tr>
			<tr>
				<td>Middle Name :</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['middle_name'])); ?></td>
			</tr>
			<tr>
				<td>Last Name :</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['last_name'])); ?></td>
			</tr>
			<tr>
				<td>DOB :</td>
				<td><?php echo $record['DOB']; ?></td>
			</tr>
			<tr>
				<td>Gender:</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['gender'])); ?></td>
			</tr>
			<tr>
				<td>State:</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['state'])); ?></td>
			</tr>
			<tr>
				<td>Place of birth :</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['place_of_birth'])); ?></td>
			</tr>
			<tr>
				<td>Address:</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['city'])); ?></td>
			</tr>
			<tr>
				<td>City: </td>
				<td><?php echo stripslashes(str_replace('\n','',$record['mobile1'])); ?></td>
			</tr>
			<tr>
				<td>Mobile1:</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['mobile1'])); ?></td>
			</tr>
			<tr>
				<td>Mobile2:</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['mobile2'])); ?></td>
			</tr>
			<tr>
				<td> Email-Id:</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['email_id'])); ?></td>
			</tr>
			<tr>
				<td>Line Number:</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['line_number'])); ?></td>
			</tr>
			<tr>
				<td> Zone:</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['zones'])); ?></td>
			</tr>
			<tr>
				<td>Customer-Type:</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['customer_type'])); ?></td>
			</tr>
			<tr>
				<td>Reference person:</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['referenceperson'])); ?></td>
			</tr>
			<tr>
				<td>Billing-plans:</td>
				<td><?php echo stripslashes(str_replace('\n','',$record['billingplans_name'])); ?></td>
			</tr>
			<tr>
				<td>Status:</td>
				<td><?php if($record['status']=='0'){ ?>Deactive <?php } if($record['status']=='1'){ ?>Active <?php } ?></td>
			</tr>
		</tbody>
	</table>
	
	<div class="clearfix form-actions" style="margin-top: 20px;">
		<div class="col-md-offset-3 col-md-9">
			<a href="<?php echo ADMIN_URL;?>addcustomer/edit/<?php echo $record['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
			<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>
