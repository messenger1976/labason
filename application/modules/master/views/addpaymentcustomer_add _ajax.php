<div class="">
	<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
		<?php
            if(count($record) > 0){
                foreach($record as $key => $row){ 
						$id=$row['id'];
				}
			}
        ?>
		<div class="row"></div>
    </div>
			
	<div style="padding:20px; box-shadow: 0px 0px 3px 1px rgba(0,0,0,0.75);">
		<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
					    <th></th>
						<th>S No</th>
						<th>Billing No.</th>
						<th>Billing Period</th>
						<th>Due Date</th>
						<th>Previous Reading</th>
						<th>Last Reading</th>
						<th>Consumed</th>
						<th>Bill Amount</th>
						<th>Discount</th>
						<th>Penalty</th>
						<th>WMMF</th>
						<th>Franchise Tax</th>
						<th>OR Number</th>
						<th>Date Paid</th>
						<th>Total Amount</th>
						
					    <th>Action</th>
					</tr>
				</thead>
				<tbody>
				   
					<?php
					    $qty= 0;
					    /*foreach($amountrate as $amountrat => $amount){
							$per_unitvalue = $amount['per_unit'];
						}*/
						//print_r($record);
						if(count($record) > 0){
							$i=1;
							foreach($record as $key => $row){
								$unit_price = $row['unit_price'];	
								
								$id = stripslashes($row['customer_id']);
								$mon_id = stripslashes($row['month']);
								$year = stripslashes($row['year']);
								$due_date = $row['bp_due_date']; 
								$special_priviledge = $row['special_priviledge'];
								$cur_date = date("Y-m-d");
								if($special_priviledge==0){
									if($cur_date>$due_date){
										$balance = $row['penalty']; 
										$penalty = $row['penalty'] - $row['amount'];
									}else{
										$balance = $row['amount']; 
										$penalty = 0;
									}
								}else{
									$balance = $row['amount']; 
									$penalty = 0;
								}
								
								
								$record_reading = $this->my_model->get_metercustomer_add_all_records($id,$mon_id,$year);
								//$record_reading = array();
								$result = count($record_reading);
								//echo 'count:'.$result.'<br/>';
								//print_r($record_reading);
								//if($result == 0){	
								?> 
							<tr>
								<td>
								<?php if($result == 0){
									$or_number_paid = '';
									$trans_date ='';
									?>   
								<input type="checkbox" name="checkbox[]" id="<?php echo $i;?>" value="<?php echo  $i;?>" class="my_check" > 
								<?php }else{
										$trans_date = date('M j, Y',strtotime($record_reading[0]['trans_date']));
										$or_number_paid = $record_reading[0]['or_number'];
										$due_date = strtotime('M j, Y',$due_date);
										

										if($special_priviledge==0){
											if($trans_date>$due_date){
												//$balance = $row['penalty']; 
												$balance = $record_reading[0]['amount'];
												$penalty = $balance - $unit_price;
												if($penalty<0){
													$penalty = 0;
												}
											}else{
												$balance = $record_reading[0]['amount'];
												$penalty = 0;
											}
										}else{
											$balance = $record_reading[0]['amount'];
											$penalty = 0;
										}

										
									?>
								<input type="checkbox" name="checkbox[]" id="<?php echo $i;?>" value="<?php echo  $i;?>" class="my_check" disabled>

								<?php } ?>
								</td>

								<td><?php echo $i; ?></td>
								<td><?php echo $row['refno']; ?></td>
								<td><?php echo stripslashes($row['month_name'].'&nbsp'.$row['year']); ?>
								    <input type="hidden" name="previousreading_<?php echo $i;?>" id="previousreading_<?php echo $i;?>" value = "<?php echo  $row['previous_reading'];?>">
								    <input type="hidden" name="monthid_<?php echo $i;?>" id="monthid_<?php echo $i;?>" value = "<?php echo  $row['month'];?>">
									<input type="hidden" name="month_<?php echo $i;?>" id="month_<?php echo $i;?>" value = "<?php echo $row['month_name'];?>">
									<input type="hidden" name="year_<?php echo $i;?>" id="year_<?php echo $i;?>" value = "<?php echo $row['year'];?>">
									<input type="hidden" name="status_<?php echo $i;?>" id="status_<?php echo $i;?>" value = "<?php echo $row['status'];?>">
									<input type="hidden" name="refno_<?php echo $i;?>" id="refno_<?php echo $i;?>" value = "<?php echo $row['refno'];?>">
									<input type="hidden" name="due_date_<?php echo $i;?>" id="due_date_<?php echo $i;?>" value = "<?php echo $row['bp_due_date'];?>">
									<input type="hidden" name="special_priviledge_<?php echo $i;?>" id="special_priviledge_<?php echo $i;?>" value = "<?php echo $row['special_priviledge'];?>">
									<input type="hidden" name="base_amount_<?php echo $i;?>" id="base_amount_<?php echo $i;?>" value = "<?php echo $row['amount'];?>">
								</td>
								<td><?php echo date('M j, Y',strtotime($row['bp_due_date'])); ?>
								<td align="center"><?php echo stripslashes($row['previous_reading']); ?>
								</td>
								<td align="center"><?php echo stripslashes($row['reading']); ?>
								    <input type="hidden" name="reading_<?php echo $i;?>" id="reading_<?php echo $i;?>" value = "<?php echo  $row['reading'];?>">
									<input type="hidden" name="consumedunit_<?php echo $i;?>" id="consumedunit_<?php echo $i;?>" value = "<?php echo  $row['consumed'];?>">
									<input type="hidden" name="sc_discount_<?php echo $i;?>" id="sc_discount_<?php echo $i;?>" value = "<?php echo  $row['sc_discount'];?>">
								</td>
								<td align="center"><?php echo stripslashes($row['consumed']); ?>
								<td align="right"><?php echo stripslashes($unit_price); ?>
								<input type="hidden" name="unit_price_<?php echo $i;?>" id="unit_price_<?php echo $i;?>" value = "<?php echo $unit_price;?>">
								</td>
								<td align="right"><?php echo stripslashes($row['sc_discount']); ?>
								</td>
								<td align="right"><?php echo stripslashes(number_format($penalty,2)); ?>
								</td>
								<td align="right"><?php echo stripslashes(number_format($row['maintenance_fee'],2)); ?>
								</td>
								<td align="right"><?php echo stripslashes(isset($row['franchise_fee_amount']) ? number_format($row['franchise_fee_amount'],2) : '0.00'); ?>
								</td>
								<td align="center"><?php echo stripslashes($or_number_paid); ?></td>
								<td align="center"><?php echo stripslashes($trans_date); ?></td>
								
								<td align="right"><?php echo $balance;?><input type="hidden" name="prsentamount_<?php echo $i;?>" id="prsentamount_<?php echo $i;?>" value = "<?php echo  $balance;?>">
								    <?php 
									   $count = $this->my_model->get_addcustomer_show_all_records($id,$mon_id,$year);
									   foreach($count as $key => $cou){?>
										<input type="hidden" name="oldbalance_<?php echo $i;?>" id="oldbalance_<?php echo $i;?>" value = "<?php echo  number_format($cou['balance'],2);?>">   
									<?php   }
									?>
								</td>
								
								<td>
								  <?php 
								  if($result == 0){?>
									<input class="btn btn-danger btn-xs pay_button" id="paybutton_<?php echo $i;?>" data-pay-val-id="<?php echo $i; ?>" type="button" name="pay" value="Unpaid">  
								  <?php }else{?>
								    <input class="btn btn-default btn-xs pay_button" id="paybutton_<?php echo $i;?>" data-pay-val-id="<?php echo $i; ?>" type="button" name="pay" value="Paid" <?php echo "disabled";?>>
								  <?php } ?>
								</td>
								
								
					</tr>	
					<?php $i++; 
				//} ?>
                    	
							<?php }?>
						
					<tr>
					    <th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<th align="right"><input type="text" name="checkbox_cal" id="checkbox_cal" value = "0" style="text-align:right;float:right;" readonly></th>
					    <th><input class="total_pay" id="total_pay"  type="button" name="total_pay" value="Total Pay" ></th>
						
					</tr>	
                    </form>						
					<?php	} else { ?>
					<tr>
							<div class="norecordes"> No Records Found</div>
					</tr>
						<?php }?>   														
				</tbody>
			</table>
			
			 <input type="hidden" name="customer_id_next" id="customer_id_next" value = "<?php echo $id;?>">
			 <input type="hidden" name="unit_d" id="unit_d" value = "<?php echo $per_unitvalue;?>">
			
		</div>
	</div>
</div>		
<!-- PAGE RELATED PLUGIN(S) -->

		<script type="text/javascript">
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
		$(document).ready(function() {
			
			pageSetUp();
			

		
		})

		</script>

											
		
<script>
$('.my_check').change(function () {
	var id = $(this).attr('id');
	var presentVal = parseFloat($('#prsentamount_' + id).val());
	var val = parseFloat($('#checkbox_cal').val());
    if ($(this).is(':checked')) {
		val+=presentVal;
		$('#checkbox_cal').val(val.toFixed(2));
		//$('.total_pay').prop("disabled", false); // Element(s) are now enabled.
	} else {
		val-=presentVal;
		$('#checkbox_cal').val(val.toFixed(2));
		//$('.total_pay').prop("disabled", true); // Element(s) are now enabled.
	}	
});

/*$('.my_check').change(function () {
	var id = $(this).attr('id');
    var values = $('#checkbox_1:checked').map(function() {
        return this.value;
    }).get().add("");
    $('#yourAge_1').val(values);
});
*/
</script>