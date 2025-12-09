<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?PHP echo stripslashes(str_replace('\n','',$seo_tags['seo_title']));  ?></title>
<meta name="Description" content="<?php echo stripslashes(str_replace('\n','',$seo_tags['seo_description'])); ?>" />
<meta name="Keywords" content="<?php echo  stripslashes(str_replace('\n','',$seo_tags['seo_keywords'])); ?>" />
<link rel="icon" href="<?php echo site_url();?>images/<?PHP echo $logo['favicon']; ?>" type="x-icon" />
<script src="<?php echo site_url();?>jquery/jquery-1.9.0.js" type="text/javascript"></script>  
<!--//ticket styles-->
<style type="text/css">
	@media all {
		.page-break	{ display: none; }
	}
	@media print {
		.page-break	{ display: block; page-break-before: always; }
	}
	@page{
	size: auto; /* auto is the initial value */
	margin: 8px 10mm 0px 10mm; /* this affects the margin in the printer settings */
	}
	body
	{
			background-color:#FFFFFF;
			margin: 10px; /* this affects the margin on the content before sending to printer */
			color:#000; 
	}
	header nav, footer {
		display: none;
	}
	.main-border{ border:#999 solid 1px; padding:3px; }
	.maindiv22{ width:900px; margin:0px auto; }
	.headerleft{ width:297px;
				 float:left;
	}
	.headerright{ width:180px;
				  float:right;
				  margin-top:0px;
	}
	
	.topdiv{text-align:right; height:25px; line-height:25px; }
	
	.print-head{ color:#c71e1e;
				 font-weight:bold;
				 text-decoration:none;
				 font-size:12px;
	}
	.fontboldticket{ font-weight:bold; font-size:12px; color:#000;  }
	
	.singleline{ ;
				 border-top:#ccc solid 1px;
				 padding:3px;
				 margin-top:6px;
	}
	.fontbold {font-weight:bold; font-size:12px; color:#000; }
	.fontbold1 {font-weight:bold; font-size:12px; }
</style>
</head>
<body onLoad="window.print() ;">
	<div class="maindiv">
		<div id="wrapper">
		  <div style="clear:both">&nbsp;</div>
           </div>
		  <div id="mainbody">
			  <div style="clear:both">&nbsp;</div>
			  <div class="maindiv22">
				<div class="main-border" style="border:#000000 solid 1px;">
				   <!--<div class="headerleft" style="float:left; margin-right:20px">
				   	<img src="<?php echo site_url();?>images/<?php echo $logo['logo'];?>" width="235" height="70" />
				   </div>-->
					<div class="headerright" style="float:right;">
					<div style="color:#438eb9; width:157px; margin:0px auto; font-size:25px; font-weight:bold; border:#438eb9 solid 2px; -moz-border-radius:6px; -webkit-border-radius:6px; -moz-border-radius:6px; padding:10px; margin-bottom:5px;">Employee Invoice</div>
					<div style="font-weight:bold;">Date : <?php if(array_key_exists("date",$record)){ echo date('d-m-Y',strtotime($record['date']));}?>
					<br />
					Employee-Id : <?php if($record['Employee_id'] != ''){ echo stripslashes($record['Employee_id']); }else{echo stripslashes($record['Employee_id']); } ?>
					</div></div>
                         
<div>
<div>
<div>
<div style="font-size:20px; width:300px; color:#2995d1; border-bottom:#2995d1 solid 1px; padding-bottom:8px; font-weight:bold; padding:5px; margin-bottom:10px;">Admin Address</div>
<div>
<?php echo (stripslashes(str_replace('\n','',$address['content']))); ?>
<table width="100%" align="center" cellspacing="1" bgcolor="#CCCCCC">
<thead>
<tr>
<th height="30" align="center" valign="middle" bgcolor="#f2f2f2">																	
S No
</th>
<th height="30" align="center" valign="middle" bgcolor="#f2f2f2" class="hidden-480">Employee ID</th>
<th height="30" align="center" valign="middle" bgcolor="#f2f2f2" class="hidden-480">Employee Details</th>
<th height="30" align="center" valign="middle" bgcolor="#f2f2f2" class="hidden-480">Contact Details</th>
<th height="30" align="center" valign="middle" bgcolor="#f2f2f2" class="hidden-480">Amount</th>
<th height="30" align="center" valign="middle" bgcolor="#f2f2f2" class="hidden-480">Total</th>
<th height="30" align="center" valign="middle" bgcolor="#f2f2f2" class="hidden-480">Month</th>
<th height="30" align="center" valign="middle" bgcolor="#f2f2f2" class="hidden-480">Title</th>
<th height="30" align="center" valign="middle" bgcolor="#f2f2f2" class="hidden-480">Date</th>
</tr>
</thead>
<tbody>                                                      
<tr>
<td height="30" align="center" valign="middle" bgcolor="#FFFFFF">1</td>
<td height="30" align="center" valign="middle" bgcolor="#FFFFFF">
	<b>Employee-id: </b><?php echo stripslashes($record['Employee_id']); ?><br/>
</td>
<td height="30" align="center" valign="middle" bgcolor="#FFFFFF" class="hidden-480">
					<b>Name:</b><?php echo stripslashes($record['first_name'].' '.$record['middle_name'].' '.$record['last_name']); ?><br/>
					<b>Gender :</b> <?php echo stripslashes($record['gender']); ?><br/>
					<b>DOB :</b> <?php echo stripslashes($record['dob']); ?><br/>
					<b>Place Of Birth :</b> <?php echo stripslashes($record['place_of_birth']); ?><br/>
					<b>State :</b> <?php echo stripslashes($record['state']); ?><br/>
					<b>City :</b> <?php echo stripslashes($record['city']); ?><br/>
					<b>Job Title :</b> <?php echo stripslashes($record['job_title']); ?><br/>
</td>
<td height="30" align="center" valign="middle" bgcolor="#FFFFFF">
					<b>Mobile-1 :</b> <?php echo stripslashes($record['mobile1']); ?><br/>
					<b>Mobile-2 :</b> <?php echo stripslashes($record['mobile2']); ?><br/>
					<b>Email-Id :</b> <?php echo stripslashes($record['email_id']); ?><br/>	
					<b>Address :</b> <?php echo stripslashes($record['address']); ?><br/>	
</td>
<td height="30" align="center" valign="middle" bgcolor="#FFFFFF" class="hidden-480">
	<?php echo stripslashes($record['amount']); ?>
</td>
<td height="30" align="center" valign="middle" bgcolor="#FFFFFF"><?php echo stripslashes($record['total']); ?></td>
<td height="30" align="center" valign="middle" bgcolor="#FFFFFF"><?php echo $record['month']; ?></td>
<td height="30" align="center" valign="middle" bgcolor="#FFFFFF"><?php echo stripslashes($record['title']); ?></td>
<td height="30" align="center" valign="middle" bgcolor="#FFFFFF">
<?php
					echo date("M d, Y ", strtotime($record['date'])) ;
						
					?></td>
</tr>                                           
</tbody>
</table></div>
<div>&nbsp;</div>
<div>
<div style="clear:both;"></div>
<div class="singleline" style="background-color:#fff; border-top:none; margin-top:10px;"><div style="background-color:#438eb9; font-size:14px; font-weight:bold; color:#fff; text-align:center; -moz-border-radius:6px; -webkit-border-radius:6px; border-radius:6px; padding:3px;">Thank you very much for choosing us...</div>
</div>
</div>
</div> 
</div>
</div>
</div>
</div>  
</div>
</div>
</div>
<div style="clear:both">&nbsp;</div>
</div>
</div>
</body>
</html>