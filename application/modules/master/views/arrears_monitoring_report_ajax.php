<div class="row">
	<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
		<?php
            if(count($record) > 0){
                foreach($record as $key => $row){ 
					$id = isset($row['id']) ? $row['id'] : '';
				}
			}
        ?>
	</div>
</div>     
	 <div class="table-responsive">
	 
        <table  class="table table-bordered">
			<thead>
				<tr>
					<th data-hide="phone">SN#</th>
					<th data-hide="phone">Customer ID</th>
					<th data-hide="phone">Customer Name</th>
					<th data-hide="phone">Meter Number</th>
					<th data-hide="phone">Zone</th>
					<th data-hide="phone">Aging Amount</th>
					<th data-hide="phone">Current Billing Period Arrears</th>
					<th data-hide="phone">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
                    if(count($record) > 0){
						$index = 1;
						$grand_total_aging   = 0;
						$grand_total_arrears = 0;
                        foreach($record as $key => $row){ 
						$aging_amount = isset($row['total_balance']) ? (float) $row['total_balance'] : 0;
						$current_arr  = isset($row['current_arrears']) ? (float) $row['current_arrears'] : 0;
						$is_mismatch  = abs($aging_amount - $current_arr) > 0.009;
						$row_style    = $is_mismatch ? "background-color:#d9534f;color:#ffffff;" : "";
				?>                                            
					<tr style="<?php echo $row_style; ?>">
						<td><?php echo $index; ?></td>
						<td><?php echo stripslashes($row['customer_id']); ?></td>
						<td><?php echo stripslashes(trim($row['last_name']).', '.trim($row['first_name']).' '.trim($row['middle_name'])); ?></td>
						<td><?php echo stripslashes($row['meter_number']); ?></td>
						<td><?php echo stripslashes($row['zone']); ?></td>
						<td align='right'><?php echo number_format($aging_amount, 2); ?></td>
						<td align='right' class="current-arrears-cell"><?php echo number_format($current_arr, 2); ?></td>
						<td align='center'>
							<?php if($is_mismatch){ ?>
							<button
								type="button"
								class="btn btn-xs btn-danger btn-update-arrears"
								data-customer-id="<?php echo htmlspecialchars($row['customer_id'], ENT_QUOTES, 'UTF-8'); ?>"
								data-aging-amount="<?php echo number_format($aging_amount, 2, '.', ''); ?>"
							>Update Arrears</button>
							<?php } else { ?>
							-
							<?php } ?>
						</td>
					</tr>
				<?php
					$grand_total_aging   += $aging_amount;
					$grand_total_arrears += $current_arr;
					$index++;
			} ?>
                 <?php } ?>

				<tr>
					<th style="text-align:right" colspan="5">GRAND TOTAL</th>
					<th style="text-align:right"><?php echo number_format(isset($grand_total_aging) ? $grand_total_aging : 0, 2);?></th>
					<th style="text-align:right"><?php echo number_format(isset($grand_total_arrears) ? $grand_total_arrears : 0, 2);?></th>
					<th></th>
				</tr>
                
               
			</tbody>
       </table>

	</div>
	
										
	<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url();?>js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script type="text/javascript">
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
		$(document).ready(function() {
			
			pageSetUp();
			
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			
			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};
	
			$('#dt_basic').dataTable({
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
					"t"+
					"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
				"autoWidth" : true,
		        "oLanguage": {
				    "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
				},
				"preDrawCallback" : function() {
					if (!responsiveHelper_dt_basic) {
						responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_dt_basic.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_dt_basic.respond();
				}
			});
		
		})

		$(document).off('click.arrearsupdate', '.btn-update-arrears').on('click.arrearsupdate', '.btn-update-arrears', function(){
			var $btn = $(this);
			var customerId = $btn.data('customer-id');
			var agingAmount = $btn.data('aging-amount');
			var $row = $btn.closest('tr');

			Swal.fire({
				title: "Update arrears?",
				text: "This will replace current billing period arrears with Aging Amount (" + parseFloat(agingAmount).toFixed(2) + ").",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d9534f",
				confirmButtonText: "Yes, update it",
				cancelButtonText: "Cancel"
			}).then(function(result){
				if(!result.isConfirmed){
					return;
				}

				$.ajax({
					type: "POST",
					url: "<?php echo ADMIN_URL;?>reports/updatearrearsmonitoring",
					dataType: "json",
					data: {
						customer_id: customerId,
						aging_amount: agingAmount
					},
					success: function(resp){
						if(resp && resp.success){
							$row.find('.current-arrears-cell').text(parseFloat(resp.current_arrears).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));
							$row.css({"background-color":"", "color":""});
							$btn.closest('td').html('<span class="label label-success">Updated</span>');
							Swal.fire({ icon: "success", title: "Updated!", text: "Current Billing Period Arrears was updated." });
						}else{
							Swal.fire({ icon: "error", title: "Failed", text: resp && resp.message ? resp.message : "Unable to update arrears." });
						}
					},
					error: function(){
						Swal.fire({ icon: "error", title: "Failed", text: "Server error while updating arrears." });
					}
				});
			});
		});

</script>
