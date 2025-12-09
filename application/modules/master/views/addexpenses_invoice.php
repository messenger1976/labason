		<link rel="stylesheet" href="<?php echo site_url();?>assets/css/datepicker.css" />
		<link rel="stylesheet" href="<?php echo site_url();?>assets/css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="<?php echo site_url();?>assets/css/daterangepicker.css" />
        <div class="main-content">
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home home-icon"></i>
								<a href="<?php echo ADMIN_URL;?>dashboard">Home</a>
							</li>
							<li>
								<a href="<?php echo ADMIN_URL;?>addexpenses">Add Expences</a>
							</li>
							<li class="active">Expences Invoice</li>
						</ul><!-- .breadcrumb -->
						<div class="nav-search" id="nav-search">
					</div><!-- #nav-search -->
					</div>
					<div class="page-content">
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-xs-12">
										<h3 class="header smaller lighter blue"><i><img src="<?php echo site_url();?>/assets/images/orders.png" width="23" height="24" /></i> Expences Invoice </h3>
										
                   <span style="font-weight:bold; color:#0F3;">   <?PHP echo $this->session->flashdata('msg_succ'); ?> </span>                 
                                        
 <div align="right" style="font-size:16px; text-decoration:none;"><img src="<?php echo site_url();?>/assets/images/print-icon.png" width="21" height="21" border="0" /> <a href="<?php echo site_url();?>master/addexpenses/printInvoice/<?php echo $this->uri->segment(4)?>" class="order-summary" style="font-size:12px;" target="_blank" >Print</a>
 
 <!--
 <img src="<?php echo site_url();?>/assets/images/email-invoice-icon.png" width="21" height="21" border="0" /></td>
    <td align="left" valign="middle"><a href="<?php echo site_url();?>master/orders/emailInvoice/<?php echo $this->uri->segment(4)?>"	class="order-summary" style="font-size:12px;">Email Invoice</a>
 
  <img src="<?php echo site_url();?>/assets/images/email-invoice-icon.png" width="21" height="21" border="0" /></td>
    <td align="left" valign="middle"><a href="<?php echo site_url();?>master/orders/invoice_sms/<?php echo $this->uri->segment(4)?>"	class="order-summary" style="font-size:12px;">SMS Invoice</a>
 -->
 </div>
  <div class="col-lg-12">
   <div class="clearfix form-actions">
 <div class="col-lg-9">

 <table>
 <tr>

					</tr>
					</table>
					</div>
					
					 <div class="col-lg-3">
 
 <table>
 <tr>
<b>Admin Address</b>
</tr><br>
   <tr><?php echo (stripslashes(str_replace('\n','',$address['content']))); ?></tr>
					</table>
					</div>
					</div>
					</div>
					
										
<div><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <!--<tr>
        <td align="left" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="50%" align="left" valign="top" bgcolor="#f5f5f5"><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
              <div class="col-lg-6">
			  <tr>
                <td align="left" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
                  <tr>
                   <td width="7%" height="25"><strong>Pay Status</strong></td>
                    <td width="4%" height="25"><strong>:</strong></td>
                  <td width="52%" height="25"><strong>  <?php
					if($record['status'] == '1'){
						echo "Paid";
					}if($record['status'] == '0'){
						echo "Un-Paid";
					}
					?>
					
					</strong></td>
					
                    </tr>
					
                  <tr>
                    <td height="25"><strong>Paid Date</strong></td>
                    <td height="25"><strong>:</strong></td>
                    <td height="25"><?php echo date("M d, Y ", strtotime($record['date'])); ?></td>
                    </tr> 
					 
                  </table>	<div>&nbsp </div>
		<div>&nbsp </div>
		<div>&nbsp </div></td>
                </tr></div>
              <tr>
                <td align="left" valign="top">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>-->
	
      <tr>
        <td align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dcdcdc">
              <thead>
                <tr class="table-heading">
                  <th width="6%" align="left" valign="middle" bgcolor="#ececec">S No</th>
                  <th width="22%" align="left" valign="middle" bgcolor="#ececec">Expences ID</th>
                  <th width="16%" align="left" valign="middle" bgcolor="#ececec">Expences Details</th>				  
                  <th width="8%" align="left" valign="middle" bgcolor="#ececec">Quantity</th>				  
                  <th width="8%" align="left" valign="middle" bgcolor="#ececec">Amount</th>
                  <th width="8%" align="left" valign="middle" class="center" bgcolor="#ececec">Total</th>
				  <th width="9%" align="left" valign="middle" class="center" bgcolor="#ececec">Date</th>
                 </tr>
                </thead>
              <tbody>  
                <tr class="odd gradeX">
                  <td align="left" valign="middle" bgcolor="#FFFFFF">1</td>
                  <td height="30" align="left" valign="middle" bgcolor="#FFFFFF" class="forgotpassword">
					<b>Expences-id: </b><?php echo stripslashes($record['expenses_id']); ?><br/>
                  </td>
                  <td height="30" align="left" valign="middle" bgcolor="#FFFFFF" class="forgotpassword">
					<b>Expences Type :</b> <?php echo stripslashes($record['expenses_type']); ?><br/>
                  </td>				  
                  <td align="left" valign="middle" bgcolor="#FFFFFF" >
					<b>Quantity :</b> <?php echo stripslashes($record['quantity']); ?><br/>						
				  </td>
                  <td align="right" valign="middle" bgcolor="#FFFFFF" class="center"><?php echo stripslashes($record['amount']); ?></td>
                  <td align="right" valign="middle" bgcolor="#FFFFFF" class="right">   
					<?php echo stripslashes($record['total']); ?>
				  </td>
                  <td align="right" valign="middle" bgcolor="#FFFFFF" class="right">
					<?php
					echo date("M d, Y ", strtotime($record['date'])) ;
						
					?>				  
				  </td>
                  </tr>  
                </tbody>
              </table></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</div>
</div>
        </div>
    </div>
							</div>
						</div>
					</div>
									</div>
								</div>
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
                    
                      
				</div><!-- /.main-content -->
			</div><!-- /.main-container-inner -->
			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="icon-double-angle-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->
		<!-- basic scripts -->
		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo site_url();?>/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>
		<!-- <![endif]-->
		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='<?php echo site_url();?>/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo site_url();?>/assets/js/bootstrap.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/typeahead-bs2.min.js"></script>
		<!-- ace scripts -->
		<script src="<?php echo site_url();?>/assets/js/ace-elements.min.js"></script>
		<script src="<?php echo site_url();?>/assets/js/ace.min.js"></script>
		<!-- inline scripts related to this page -->
		<script src="<?php echo site_url();?>assets/js/date-time/bootstrap-datepicker.min.js"></script>
		<script src="<?php echo site_url();?>assets/js/date-time/bootstrap-timepicker.min.js"></script>
		<script src="<?php echo site_url();?>assets/js/date-time/moment.min.js"></script>
		<script src="<?php echo site_url();?>assets/js/date-time/daterangepicker.min.js"></script>
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
				$('.date-picker').datepicker({autoclose:true}).next().on(ace.click_event, function(){
					$(this).prev().focus();
				});
				$('input[name=date-range-picker]').daterangepicker().prev().on(ace.click_event, function(){
					$(this).next().focus();
				});
				
				$('#timepicker1').timepicker({
					minuteStep: 1,
					showSeconds: true,
					showMeridian: false
				}).next().on(ace.click_event, function(){
					$(this).prev().focus();
				});
			})
		</script>
	</body>
</html>
