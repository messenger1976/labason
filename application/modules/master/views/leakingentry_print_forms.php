<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LWD Official Forms Copy</title>
    <style>
        /* A4 Page setup to match Word original */
        @page {
            size: A4;
            margin: 15mm;
        }

        @media print {
            body { background: none; padding: 0; }
            .page { border: none !important; box-shadow: none !important; margin: 0 !important; page-break-after: always; }
        }

        body {
            font-family: "Arial", sans-serif;
            background-color: #525659;
            margin: 0;
            padding: 20px;
            color: black;
            line-height: 1.2;
        }

        .page {
            background: white;
            width: 210mm;
            height: 297mm;
            margin: 0 auto 20px auto;
            padding: 15mm 20mm;
            box-sizing: border-box;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            font-size: 11pt;
        }

        /* Header spacing aligned with report templates */
        .report-logo { text-align: center; margin: 0 0 2px 0; }
        .header { text-align: center; margin-bottom: 12px; position: relative; }
        .header p { margin: 0; font-size: 9pt; line-height: 1.15; }
        .header h2 { margin: 2px 0 0 0; font-size: 14pt; font-weight: bold; line-height: 1.1; }

        .meta-row { display: flex; justify-content: space-between; margin-bottom: 14px; font-weight: bold; }

        .form-title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 18px;
            text-decoration: none;
        }

        /* Field Alignment to match Word Tabs */
        .field-group { margin-bottom: 8px; display: flex; align-items: flex-end; }
        .label { min-width: 160px; font-weight: normal; }
        .fill { border-bottom: 1px solid black; flex-grow: 1; min-height: 1.1em; padding-bottom: 1px; }
        .fill.filled { border-bottom: none; font-weight: normal; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid black; padding: 4px; text-align: center; font-size: 10pt; }
        th { font-weight: bold; }

        /* Checklist */
        .check-item { margin: 5px 0; display: flex; align-items: center; }
        .box { width: 14px; height: 14px; border: 1px solid black; margin-right: 10px; display: inline-block; flex-shrink: 0; text-align: center; line-height: 14px; font-size: 11px; font-weight: bold; }
        .box.checked { background: #fff; }
        .inline-fill { display: inline-block; border-bottom: 1px solid black; padding: 0 4px 1px 4px; min-height: 1em; vertical-align: bottom; }

        /* Signatures */
        .sig-section { margin-top: 30px; }
        .sig-container { display: flex; justify-content: space-between; margin-top: 40px; }
        .sig-box { text-align: center; width: 45%; }
        .sig-line { border-top: 1px solid black; padding-top: 2px; font-weight: bold; font-size: 10pt; }
        .sig-sub { font-size: 8.5pt; display: block; }

        .ack-section { text-align: justify; margin-top: 20px; font-size: 10.5pt; line-height: 1.5; }
        
        /* BAM Specific Styles */
        .bam-header { display: flex; justify-content: space-between; align-items: flex-start; margin-top: 10px; }
    </style>
</head>
<body>
<?php
$member_name = '';
$member_address = '';
$account_number = '';
$meter_number = '';
$contact_number = '';
$present_reading = '';
$period_label = '';
$current_date = date('F j, Y');
$form_no = isset($form_no) ? $form_no : '';
$avg_consumption = '';
$discount_percent = '';

if (!empty($customer_info)) {
    $middle = trim(isset($customer_info['middle_name']) ? $customer_info['middle_name'] : '');
    $member_name = trim($customer_info['last_name'] . ', ' . $customer_info['first_name'] . ($middle !== '' ? ' ' . $middle : ''));
    $member_address = trim(isset($customer_info['address']) ? $customer_info['address'] : '');
    $account_number = isset($customer_info['customer_id']) ? $customer_info['customer_id'] : '';
    $meter_number = isset($customer_info['meter_number']) ? $customer_info['meter_number'] : '';
    $contact_number = '';
    if (!empty($customer_info['mobile1'])) {
        $contact_number = trim($customer_info['mobile1']);
    }
    if (!empty($customer_info['mobile2'])) {
        $contact_number .= ($contact_number !== '' ? ' / ' : '') . trim($customer_info['mobile2']);
    }
}
if (!empty($customer_reading)) {
    $period_label = '';
    if (!empty($customer_reading['month'])) {
        $month_row = getMonthName($customer_reading['month']);
        if (!empty($month_row[0]->month_name)) {
            $period_label = $month_row[0]->month_name . ' ' . $customer_reading['year'];
        }
    }
    $reading_val = isset($customer_reading['reading']) ? $customer_reading['reading'] : '';
    $present_reading = $reading_val . ($period_label !== '' ? ' (' . $period_label . ')' : '');
}
if (!empty($billing_history)) {
    $total_consumed = 0;
    $count = 0;
    foreach ($billing_history as $bill_row) {
        $total_consumed += (float)(isset($bill_row['consumed']) ? $bill_row['consumed'] : 0);
        $count++;
    }
    if ($count > 0) {
        $avg_consumption = number_format($total_consumed / $count, 2);
    }
}
if (!empty($record['leaking_discount_percent'])) {
    $discount_percent = $record['leaking_discount_percent'];
}

$signed_date = $current_date;
$or_number = '';
$or_date = '';
$inspection_date = '';
$leak_remarks = '';
$bam_explanation = '';
$bam_rows = array();
$avg_consumption_raw = is_numeric(str_replace(',', '', $avg_consumption)) ? (float)str_replace(',', '', $avg_consumption) : '';

if (!empty($record['leaking_date']) && $record['leaking_date'] !== '0000-00-00') {
    $signed_date = date('F j, Y', strtotime($record['leaking_date']));
    $inspection_date = date('F j, Y', strtotime($record['leaking_date']));
}
if (!empty($customer_reading['date']) && $customer_reading['date'] !== '0000-00-00' && $inspection_date === '') {
    $inspection_date = date('F j, Y', strtotime($customer_reading['date']));
}

if (!empty($ledger_details) && is_array($ledger_details)) {
    $payments = $ledger_details;
    usort($payments, function ($a, $b) {
        return strtotime($a['leakingledgerdetails_transdate']) - strtotime($b['leakingledgerdetails_transdate']);
    });
    $first_payment = $payments[0];
    if (!empty($first_payment['leakingledgerdetails_or_number'])) {
        $or_number = $first_payment['leakingledgerdetails_or_number'];
        if (!empty($first_payment['leakingledgerdetails_source_type'])) {
            $or_number = $first_payment['leakingledgerdetails_source_type'] . '#' . $or_number;
        }
    }
    if (!empty($first_payment['leakingledgerdetails_transdate']) && $first_payment['leakingledgerdetails_transdate'] !== '0000-00-00') {
        $or_date = date('F j, Y', strtotime($first_payment['leakingledgerdetails_transdate']));
    }
}

if ($period_label !== '' && !empty($customer_reading)) {
    $billed_cu = isset($customer_reading['consumed']) ? $customer_reading['consumed'] : '';
    $billed_amt = isset($customer_reading['amount']) ? (float)$customer_reading['amount'] : 0;
    if (!empty($record['leaking_bill_amount'])) {
        $billed_amt = (float)$record['leaking_bill_amount'];
    }
    $adj_amt = !empty($record['leaking_total_amount']) ? (float)$record['leaking_total_amount'] : $billed_amt;
    $adj_cu = $avg_consumption_raw !== '' ? $avg_consumption_raw : $billed_cu;
    $leak_remarks = 'Leak confirmed for billing period ' . $period_label
        . '. Consumption: ' . $billed_cu . ' cu.m.; billed amount: PHP ' . number_format($billed_amt, 2) . '.';
    if ($discount_percent !== '') {
        $leak_remarks .= ' Recommended adjustment: ' . $discount_percent . '% discount.';
    }
    $bam_rows[] = array(
        'period' => $period_label,
        'billed_cu' => $billed_cu,
        'billed_amt' => $billed_amt,
        'adj_cu' => $adj_cu,
        'adj_amt' => $adj_amt,
    );
    $bam_explanation = 'Billing adjustment due to water leakage. For ' . $period_label
        . ', consumption was ' . $billed_cu . ' cu.m. (PHP ' . number_format($billed_amt, 2) . ' as billed)';
    if ($avg_consumption !== '') {
        $bam_explanation .= ', adjusted to average consumption of ' . $avg_consumption . ' cu.m.';
    }
    if ($discount_percent !== '') {
        $bam_explanation .= ' with ' . $discount_percent . '% discount';
    }
    $bam_explanation .= '. Net leaking amount: PHP ' . number_format($adj_amt, 2) . '.';
}

if (!function_exists('leaking_print_inline')) {
    function leaking_print_inline($value, $width = 'auto', $min_width = '') {
        $value = htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
        $style = 'border-bottom: 1px solid black; padding: 0 4px 1px 4px; display: inline-block; min-height: 1em; vertical-align: bottom;';
        if ($width !== 'auto') {
            $style .= ' width: ' . $width . ';';
        }
        if ($min_width !== '') {
            $style .= ' min-width: ' . $min_width . ';';
        }
        return '<span style="' . $style . '">' . ($value !== '' ? $value : '&nbsp;') . '</span>';
    }
}
if (!function_exists('leaking_print_check_box')) {
    function leaking_print_check_box($checked) {
        $class = 'box' . ($checked ? ' checked' : '');
        $mark = $checked ? '&#10003;' : '&nbsp;';
        return '<div class="' . $class . '">' . $mark . '</div>';
    }
}
if (!function_exists('leaking_print_bar_fill')) {
    function leaking_print_bar_fill($value) {
        $value = htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
        $class = $value !== '' ? 'fill filled' : 'fill';
        return '<div class="' . $class . '">' . $value . '</div>';
    }
}
?>

    <div class="page">
        <div class="header">
            <div class="report-logo"><img src="<?php echo site_url();?>images/mroxas-logo-report.jpg" height="80px" alt="Labason Water District Logo"></div>
        </div>

        <div class="meta-row">
            <div>Date : <?php echo htmlspecialchars($current_date, ENT_QUOTES, 'UTF-8'); ?></div>
            <div>Form No.: <?php echo htmlspecialchars($form_no, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>

        <div class="form-title">BILLING ADJUSTMENT<br>REQUEST FORM</div>

        <div class="field-group"><span class="label">NAME</span>: <?php echo leaking_print_bar_fill($member_name); ?></div>
        <div class="field-group"><span class="label">ADDRESS</span>: <?php echo leaking_print_bar_fill($member_address); ?></div>
        <div class="field-group"><span class="label">ACCOUNT NUMBER</span>: <?php echo leaking_print_bar_fill($account_number); ?></div>
        <div class="field-group"><span class="label">METER NUMBER</span>: <?php echo leaking_print_bar_fill($meter_number); ?></div>
        <div class="field-group"><span class="label">CONTACT NUMBER</span>: <?php echo leaking_print_bar_fill($contact_number); ?></div>
        <div class="field-group"><span class="label">PRESENT READING</span>: <?php echo leaking_print_bar_fill($present_reading); ?></div>
        <div class="field-group"><span class="label">LEAK REPAIRED ON</span>: <?php echo leaking_print_bar_fill(''); ?></div>
        <div class="field-group"><span class="label">LEAK REPAIRED BY</span>: <?php echo leaking_print_bar_fill(''); ?></div>

        <p style="font-weight: bold; margin-bottom: 5px;">Supporting documents submitted:</p>
        <div class="check-item"><div class="box"></div> Water Complaint Investigation Report</div>
        <div class="check-item"><div class="box"></div> Consumer photocopy of Valid ID</div>
        <div class="check-item"><div class="box"></div> Signed Acknowledgement</div>
        <div class="check-item"><div class="box"></div> Others: _____________________________________________</div>

        <div class="sig-section">
            <div style="width: 350px; margin-top: 40px; text-align: center;">
                <?php if ($member_name !== '') { ?><div style="border-bottom: 1px solid black; padding-bottom: 2px;"><?php echo htmlspecialchars($member_name, ENT_QUOTES, 'UTF-8'); ?></div><?php } else { ?><div style="border-bottom: 1px solid black; height: 1.2em;"></div><?php } ?>
                <div style="margin-top: 4px;">Consumer's Name and Signature</div>
            </div>
        </div>

        <p style="font-weight: bold; margin-top: 30px; margin-bottom: 5px; text-align: center;">THREE (3) MONTHS BILLING RECORD</p>
        <table>
            <tr><th>Period</th><th>Cubic Meter Consumed</th><th>Amount</th></tr>
            <?php
            $billing_rows = !empty($billing_history) ? $billing_history : array();
            for ($i = 0; $i < 3; $i++) {
                $period_cell = '&nbsp;';
                $consumed_cell = '&nbsp;';
                $amount_cell = '';
                if (isset($billing_rows[$i])) {
                    $br = $billing_rows[$i];
                    $period_cell = htmlspecialchars(
                        (isset($br['month_name']) ? $br['month_name'] : '') . ' ' . (isset($br['year']) ? $br['year'] : ''),
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    $consumed_cell = htmlspecialchars(isset($br['consumed']) ? $br['consumed'] : '', ENT_QUOTES, 'UTF-8');
                    $amount_cell = number_format((float)(isset($br['amount']) ? $br['amount'] : 0), 2);
                }
                echo '<tr><td>' . $period_cell . '</td><td>' . $consumed_cell . '</td><td>₱' . $amount_cell . '</td></tr>';
            }
            ?>
        </table>
        <div class="field-group" style="width: 50%;"><span class="label">Average Consumption:</span> <?php echo leaking_print_bar_fill($avg_consumption); ?></div>

        <p style="font-weight: bold; margin-top: 25px; margin-bottom: 5px;">GENERAL MANAGER'S/AUTHORIZED REPRESENTATIVE DECISION</p>
        <div class="check-item" style="margin-left: 20px;"><div class="box"></div> Approved: Percentage Discount: <?php echo $discount_percent !== '' ? htmlspecialchars($discount_percent, ENT_QUOTES, 'UTF-8') : '____'; ?>%</div>
        <div class="check-item" style="margin-left: 20px;"><div class="box"></div> Disapproved</div>
        <div class="field-group" style="margin-left: 20px;"><span class="label" style="min-width: 160px;">Reason for Disapproval:</span> <div class="fill"></div></div>
        <div class="sig-box" style="margin: 25px auto 0 auto; width: 350px;">
            <div class="sig-line">ENGR. ANASTACIA T. ROMANILLOS</div>
            <span class="sig-sub">General Manager's/Authorized Representative</span>
        </div>
    </div>

    <div class="page">
        <div class="report-logo"><img src="<?php echo site_url();?>images/mroxas-logo-report.jpg" height="80px" alt="Labason Water District Logo"></div>
        <div class="form-title">ACKNOWLEDGEMENT</div>
        <div class="ack-section">
            I, <?php echo leaking_print_inline($member_name, '280px'); ?> under the Account Number <?php echo leaking_print_inline($account_number, '120px'); ?> hereby acknowledges that the abrupt increase in my billing is due to leakage in my water pipelines and/or fixtures. I do understand that the adjustment on billing which increased due to leakage is given only <strong>ONCE a YEAR</strong> per Board RESOLUTION 014 s. of 2024. Future increases in billing due to leakages shall be borne by the Undersigned.
            <br><br>
            I acknowledge further that repairs on leaking pipelines and fixtures shall be my responsibility and I will not ask for any billing adjustments in the future.
            <br><br>
            SIGNED THIS, <?php echo leaking_print_inline($signed_date, '200px'); ?>.
        </div>

        <div class="sig-container" style="margin-top: 60px;">
            <div class="sig-box">
                <div class="sig-line"><?php echo $member_name !== '' ? htmlspecialchars($member_name, ENT_QUOTES, 'UTF-8') : '&nbsp;'; ?></div>
                <span class="sig-sub">Consumer's Name and Signature</span>
            </div>
            <div class="sig-box">
                <div class="sig-line">MISHELLE P. MONDARTE</div>
                <span class="sig-sub">Billing Officer</span>
            </div>
        </div>
    </div>

    <div class="page">
        <div class="header">
            <div class="report-logo"><img src="<?php echo site_url();?>images/mroxas-logo-report.jpg" height="80px" alt="Labason Water District Logo"></div>
            <p>WATER SERVICE COMPLAINT INVESTIGATION FORM</p>
        </div>

        <div class="meta-row">
            <div>Paid under OR NO. ___________</div>
            <div>OR Date: <?php echo $or_date !== '' ? htmlspecialchars($or_date, ENT_QUOTES, 'UTF-8') : '____________'; ?></div>
        </div>

        <div class="field-group"><span class="label">NAME</span>: <?php echo leaking_print_bar_fill($member_name); ?></div>
        <div class="field-group"><span class="label">ADDRESS</span>: <?php echo leaking_print_bar_fill($member_address); ?></div>
        <div class="field-group"><span class="label">ACCOUNT NUMBER</span>: <?php echo leaking_print_bar_fill($account_number); ?></div>
        <div class="field-group"><span class="label">METER NUMBER</span>: <?php echo leaking_print_bar_fill($meter_number); ?></div>
        <div class="field-group"><span class="label">CONTACT NUMBER</span>: <?php echo leaking_print_bar_fill($contact_number); ?></div>
        <div class="field-group"><span class="label">PRESENT READING</span>: <?php echo leaking_print_bar_fill($present_reading); ?></div>

        <div style="margin-top: 30px;">
            <div class="check-item" style="font-weight: bold;"><?php echo leaking_print_check_box(true); ?> WITH LEAK</div>
            <div style="border: 1px solid black; min-height: 80px; margin-left: 25px; padding: 5px; font-size: 10pt;">Remarks: <?php echo $leak_remarks !== '' ? htmlspecialchars($leak_remarks, ENT_QUOTES, 'UTF-8') : ''; ?></div>
            
            <div class="check-item" style="font-weight: bold; margin-top: 20px;"><?php echo leaking_print_check_box(false); ?> WITHOUT LEAK</div>
            <div style="border: 1px solid black; height: 80px; margin-left: 25px; padding: 5px; font-size: 10pt;">Remarks:</div>
        </div>

        <div class="sig-container" style="margin-top: 60px;">
            <div class="sig-box">
                <div class="sig-line">&nbsp;</div>
                <span class="sig-sub">Investigator</span>
            </div>
            <div class="sig-box">
                <div class="sig-line">&nbsp;</div>
                <span class="sig-sub">Position</span>
            </div>
        </div>
        <div class="field-group" style="width: 300px; margin-top: 30px;"><span class="label" style="min-width: 140px;">Date of Inspection:</span> <?php echo leaking_print_bar_fill($inspection_date); ?></div>
    </div>

    <div class="page">
        <div class="header">
            <div class="report-logo"><img src="<?php echo site_url();?>images/mroxas-logo-report.jpg" height="80px" alt="Labason Water District Logo"></div>
            <p>BILLING ADJUSTMENT MEMO (BAM)</p>
        </div>

        <div class="bam-header">
            <div style="width: 70%;">
                <div class="field-group"><span class="label">Consumer</span>: <?php echo leaking_print_bar_fill($member_name); ?></div>
                <div class="field-group"><span class="label">Address</span>: <?php echo leaking_print_bar_fill($member_address); ?></div>
                <div class="field-group"><span class="label">Account No.</span>: <?php echo leaking_print_bar_fill($account_number); ?></div>
                <div class="field-group"><span class="label">Meter No.</span>: <?php echo leaking_print_bar_fill($meter_number); ?></div>
            </div>
            <div style="font-weight: bold;">BAM No.: <?php echo htmlspecialchars($form_no, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>

        <table style="margin-top: 20px;">
            <tr>
                <th rowspan="2">Period</th>
                <th colspan="2">As Billed</th>
                <th colspan="2">As Adjusted</th>
            </tr>
            <tr>
                <th>Cu.M.</th>
                <th>Amount</th>
                <th>Cu.M.</th>
                <th>Amount</th>
            </tr>
            <?php
            for ($b = 0; $b < 1; $b++) {
                $period_cell = '&nbsp;';
                $billed_cu = '&nbsp;';
                $billed_amt = '&nbsp;';
                $adj_cu = '&nbsp;';
                $adj_amt = '&nbsp;';
                if (isset($bam_rows[$b])) {
                    $br = $bam_rows[$b];
                    $period_cell = htmlspecialchars($br['period'], ENT_QUOTES, 'UTF-8');
                    $billed_cu = htmlspecialchars($br['billed_cu'], ENT_QUOTES, 'UTF-8');
                    $billed_amt = number_format((float)$br['billed_amt'], 2);
                    $adj_cu = htmlspecialchars($br['adj_cu'], ENT_QUOTES, 'UTF-8');
                    $adj_amt = number_format((float)$br['adj_amt'], 2);
                }
                echo '<tr><td>' . $period_cell . '</td><td>' . $billed_cu . '</td><td>' . $billed_amt . '</td><td>' . $adj_cu . '</td><td>' . $adj_amt . '</td></tr>';
            }
            ?>
        </table>

        <div style="margin-top: 20px;">
            <strong>Explanation:</strong>
            <div style="border: 1px solid black; min-height: 60px; padding: 5px; font-size: 10pt; margin-top: 4px;"><?php echo $bam_explanation !== '' ? htmlspecialchars($bam_explanation, ENT_QUOTES, 'UTF-8') : '&nbsp;'; ?></div>
        </div>

        <div class="sig-section" style="font-size: 9pt;">
            <p>Prepared by:</p>
            <div style="width: 280px;">
                <div class="sig-line" style="margin-top: 40px;">MISHELLE P. MONDARTE</div>
                <span class="sig-sub">Industrial Relations Management Officer C / Billing Officer</span>
            </div>

            <p style="margin-top: 20px;">Verified Correct:</p>
            <div style="width: 280px;">
                <div class="sig-line" style="margin-top: 40px;">DARYL JAY T. VILLARIN</div>
                <span class="sig-sub">Administrative/General Services Officer B / HRMO/FO/BO</span>
            </div>

            <p style="margin-top: 20px;">Approved:</p>
            <div style="width: 280px;">
                <div class="sig-line" style="margin-top: 40px;">ENGR. ANASTACIA T. ROMANILLOS, CE</div>
                <span class="sig-sub">General Manager</span>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() { window.print(); }, 250);
        };
    </script>
</body>
</html>
