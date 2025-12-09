
 <script type="text/javascript">
function getPrint(){
	window.print();
  window.close(); // Attempts to close the window
}
</script>	

<!DOCTYPE html>
<html>
<head>
<title>Water Bill Notice (2.2in x 5in)</title>
<style>
@media print {
  @page {
    size: 2.3in 12in;
    margin: 0;
  }

  body {
    width: 2.3in;
    height: 12in;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-size: 10pt; /* Reduced font size to fit */
    font-family: monospace;
    line-height: 1.2; /* Reduced line height */
    letter-spacing: -0.1em; /* Decreases space between characters */
  }

  .header, .notice-title, .bill-details, .customer-info, .water-usage, .payment-info, .important-notice, .footer {
    text-align: left; /* Adjusted for narrow width */
    padding: 0mm; /* Reduced padding */
  }

  .header {
    text-align: center;
    border-bottom: 1px dashed #000;
  }

  .notice-title {
    text-align: center;
    font-weight: bold;
    font-size: 13pt; /* Slightly larger title */
    margin-top: 5px;
    
    
  }
.notice-subtitle{
  text-align: center;
  font-size: 10pt;
  margin-bottom: 5px;
}
  .bill-details strong {
    display: inline-block;
    width: 15mm; /* Reduced label width */
    text-align: left;
    font-weight: bold;
  }
.water-usage strong{
  width: 25mm;

}
.customer-info strong{
  width: 18mm;

}
.customer-info{
  margin-top: 5px;
  border-bottom: 1px dashed #000;
  border-top: 1px dashed #000;
  margin-bottom: 5px;
}

.water-usage{
  margin-top: 5px;
  border-bottom: 1px dashed #000;
  
  margin-bottom: 5px;
}
  .footer {
    text-align: center;
    
    margin-top: 5px;
    padding-bottom: 5px;
    border-bottom: 1px dashed #000;
  }

  .logo {
    width: 100%; /* Reduced logo size */
    height: auto;
    display: block;
    margin: 0 auto 3px;
  }
  .col-figure{
    float: right;
    margin-right: 2px;
  }
}

/* Optional screen styles (for preview) */
body {
  font-family: monospace;
  font-size: 10pt;
  line-height: 1.2;
  width: 2.3in;
  height: 12in;
  letter-spacing: -0.1em; /* Decreases space between characters */
}

.header {
  text-align: center;
  border-bottom: 1px dashed #000;
}
.notice-title {
  text-align: center;
  font-weight: bold;
  font-size: 13pt; /* Slightly larger title */
  margin-top: 5px;
  
}
.notice-subtitle{
  text-align: center;
  font-size: 10pt;
  margin-bottom: 5px;
}
.header, .notice-title, .bill-details, .customer-info, .water-usage, .important-notice, .footer {
  padding: 0mm;
}

.bill-details strong {
  display: inline-block;
  width: 15mm;
}
.water-usage strong{
  width: 25mm;

}

.customer-info strong{
  width: 18mm;

}
.customer-info{
  margin-top: 5px;
  border-bottom: 1px dashed #000;
  border-top: 1px dashed #000;
  margin-bottom: 5px;
}

.water-usage{
  margin-top: 5px;
  border-bottom: 1px dashed #000;
  
  margin-bottom: 5px;
}
.footer {
  text-align: center;
  
  margin-top: 5px;
  padding-bottom: 5px;
  border-bottom: 1px dashed #000;
}
.logo{
    max-width: 100%;
    height: auto;
}
.col-figure{
  float: right;
  margin-right: 2px;
}
</style>
</head>
<body onload="getPrint()">

<div class="header">
  <img src="<?php echo base_url();?>images/mroxas-logo-report-new.jpg" alt="Water District Logo" class="logo">
  <!--<p style="font-size: 7pt;">PRES. M.A. ROXAS WATER DIST.</p>
  <p style="font-size: 7pt;">Langatian, Pres. M. A. Roxas</p>
  <p style="font-size: 7pt;">TIN 004-315-023-00000</p>-->
</div>

<div class="notice-title">
  NOTICE OF COLLECTION</div>
<div class="notice-subtitle">
  For the month of <?php echo $record[0]['month_name'].' '.$record[0]['year'];?>
</div>

<div class="bill-details">
  <strong>DATE:</strong> <?php echo date('m-d-Y');?><br>
  <strong>TIME:</strong> <?php echo date('h:i:s A');?><br>
  <strong>PERIOD:</strong> <?php echo date('m/d/Y',strtotime($billing_period['bp_start_date']));?> to <?php echo date('m/d/Y',strtotime($billing_period['bp_end_date']));?>
</div>

<div class="customer-info">
  <strong>CUST NAME:</strong> <span class="col-figure"><?php echo strtoupper($record[0]['last_name']).', '.strtoupper($record[0]['first_name']).' '.strtoupper($record[0]['middle_name']);?></span> <br>
  <strong>ACCOUNT&nbsp;#:</strong> <span class="col-figure"><?php echo strtoupper($record[0]['customer_id']);?></span><br>
  <strong>ADDRESS:</strong> <span class="col-figure"><?php echo strtoupper($record[0]['address']);?></span><br>
  <strong>METER #:</strong> <span class="col-figure">43543444</span><br>
  <strong>BRAND:</strong> <span class="col-figure">EVER</span><br>
</div>

<div class="water-usage">
  <strong>PRESENT READING:</strong><span class="col-figure"> <?php echo $record[0]['reading'];?></span><br>
  <strong>PREVIOUS READING:</strong> <span class="col-figure"><?php echo $record[0]['previous_reading'];?></span><br>
  <strong>TOTAL USAGE:</strong> <span class="col-figure"><?php echo $record[0]['consumed'];?></span><br>
</div>
<div class="water-bill">
  <strong>WATER BILL:</strong> <span class="col-figure"><?php echo $record[0]['unit_price'];?></span><br>
  <strong>ARREARS:</strong> <span class="col-figure"><?php echo $record[0]['arrears'];?></span><br>
  <strong>DISCOUNT:</strong> <span class="col-figure"><?php echo $record[0]['sc_discount'];?></span><br>
  <strong>TOTAL DUE:</strong> <span class="col-figure"><?php echo $record[0]['amount'];?></span><br>
  <strong>AMOUNT AFTER DUE DATE:</strong> <span class="col-figure"><?php echo $record[0]['penalty'];?></span><br>
  
</div>

<div class="payment-info">
  You may pay your bill @ Roxas Water District Office
</div>
 --------------------------------------<br/>
<div class="important-notice">
  
 <center> FAILURE TO SETTLE THIS BILL ON OR BEFORE THE DATE BELOW<br>
  will lead to immediate disconnection of your water service without prior notice.</center><br/>
  <strong>DUE DATE:</strong> <span class="col-figure"><?php echo date('M d, Y',strtotime($billing_period['bp_due_date']));?></span><br>
  <strong>DISCONNECTION DATE:</strong> <span class="col-figure"><?php echo date('M d, Y',strtotime($billing_period['bp_disconnection_date']));?></span><br>
</div>

<div class="footer">
  ======================================<br>
  Recognize Your Continued Loyalty<br/><br/>
</div>

</body>
</html>