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

        /* Header mimicry */
        .header { text-align: center; margin-bottom: 20px; position: relative; }
        .header p { margin: 1px 0; font-size: 9pt; }
        .header h2 { margin: 2px 0; font-size: 14pt; font-weight: bold; }

        .meta-row { display: flex; justify-content: space-between; margin-bottom: 20px; font-weight: bold; }

        .form-title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 25px;
            text-decoration: none;
        }

        /* Field Alignment to match Word Tabs */
        .field-group { margin-bottom: 8px; display: flex; align-items: flex-end; }
        .label { min-width: 160px; font-weight: normal; }
        .fill { border-bottom: 1px solid black; flex-grow: 1; height: 1.1em; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid black; padding: 4px; text-align: center; font-size: 10pt; }
        th { font-weight: bold; }

        /* Checklist */
        .check-item { margin: 5px 0; display: flex; align-items: center; }
        .box { width: 14px; height: 14px; border: 1px solid black; margin-right: 10px; display: inline-block; }

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

    <div class="page">
        <div class="header">
            <p>Republic of the Philippines</p>
            <h2>LABASON WATER DISTRICT</h2>
            <p>Imelda, Labason, Zamboanga del Norte</p>
            <p>E-mail: labasonwaterdistrict@yahoo.com | Hotline Number: 09510861434</p>
        </div>

        <div class="meta-row">
            <div>Date : ___________________</div>
            <div>Form No.: _________</div>
        </div>

        <div class="form-title">BILLING ADJUSTMENT<br>REQUEST FORM</div>

        <div class="field-group"><span class="label">NAME</span>: <div class="fill"></div></div>
        <div class="field-group"><span class="label">ADDRESS</span>: <div class="fill"></div></div>
        <div class="field-group"><span class="label">ACCOUNT NUMBER</span>: <div class="fill"></div></div>
        <div class="field-group"><span class="label">METER NUMBER</span>: <div class="fill"></div></div>
        <div class="field-group"><span class="label">CONTACT NUMBER</span>: <div class="fill"></div></div>
        <div class="field-group"><span class="label">PRESENT READING</span>: <div class="fill"></div></div>
        <div class="field-group"><span class="label">LEAK REPAIRED ON</span>: <div class="fill"></div></div>
        <div class="field-group"><span class="label">LEAK REPAIRED BY</span>: <div class="fill"></div></div>

        <p style="font-weight: bold; margin-bottom: 5px;">Supporting documents submitted:</p>
        <div class="check-item"><div class="box"></div> Water Complaint Investigation Report</div>
        <div class="check-item"><div class="box"></div> Consumer photocopy of Valid ID</div>
        <div class="check-item"><div class="box"></div> Signed Acknowledgement</div>
        <div class="check-item"><div class="box"></div> Others: _____________________________________________</div>

        <div class="sig-section">
            <div style="width: 250px; border-top: 1px solid black; margin-top: 40px; text-align: center;">
                Consumer's Name and Signature
            </div>
        </div>

        <p style="font-weight: bold; margin-top: 30px; margin-bottom: 5px; text-align: center;">THREE (3) MONTHS BILLING RECORD</p>
        <table>
            <tr><th>Period</th><th>Cubic Meter Consumed</th><th>Amount</th></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td><td>₱</td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td><td>₱</td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td><td>₱</td></tr>
        </table>
        <div class="field-group" style="width: 50%;"><span class="label">Average Consumption:</span> <div class="fill"></div></div>

        <p style="font-weight: bold; margin-top: 25px; margin-bottom: 5px;">GENERAL MANAGER'S/AUTHORIZED REPRESENTATIVE DECISION</p>
        <div class="check-item" style="margin-left: 20px;"><div class="box"></div> Approved: Percentage Discount: ____%</div>
        <div class="check-item" style="margin-left: 20px;"><div class="box"></div> Disapproved</div>
        <div class="field-group" style="margin-left: 20px;"><span class="label" style="min-width: 160px;">Reason for Disapproval:</span> <div class="fill"></div></div>
        <div class="sig-box" style="margin: 25px auto 0 auto; width: 350px;">
            <div class="sig-line">ENGR. ANASTACIA T. ROMANILLOS</div>
            <span class="sig-sub">General Manager's/Authorized Representative</span>
        </div>
    </div>

    <div class="page">
        <div class="form-title">ACKNOWLEDGEMENT</div>
        <div class="ack-section">
            I, <span style="display:inline-block; width: 280px; border-bottom: 1px solid black;"></span> under the Account Number <span style="display:inline-block; width: 120px; border-bottom: 1px solid black;"></span> hereby acknowledges that the abrupt increase in my billing is due to leakage in my water pipelines and/or fixtures. I do understand that the adjustment on billing which increased due to leakage is given only <strong>ONCE a YEAR</strong> per Board RESOLUTION 014 s. of 2024. Future increases in billing due to leakages shall be borne by the Undersigned.
            <br><br>
            I acknowledge further that repairs on leaking pipelines and fixtures shall be my responsibility and I will not ask for any billing adjustments in the future.
            <br><br>
            SIGNED THIS, <span style="display:inline-block; width: 200px; border-bottom: 1px solid black;"></span>.
        </div>

        <div class="sig-container" style="margin-top: 60px;">
            <div class="sig-box">
                <div class="sig-line">&nbsp;</div>
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
            <p>Republic of the Philippines</p>
            <h2>LABASON WATER DISTRICT</h2>
            <p>WATER SERVICE COMPLAINT INVESTIGATION FORM</p>
        </div>

        <div class="meta-row">
            <div>Paid under OR NO. ________</div>
            <div>OR Date: ____________</div>
        </div>

        <div class="field-group"><span class="label">NAME</span>: <div class="fill"></div></div>
        <div class="field-group"><span class="label">ADDRESS</span>: <div class="fill"></div></div>
        <div class="field-group"><span class="label">ACCOUNT NUMBER</span>: <div class="fill"></div></div>
        <div class="field-group"><span class="label">METER NUMBER</span>: <div class="fill"></div></div>
        <div class="field-group"><span class="label">CONTACT NUMBER</span>: <div class="fill"></div></div>
        <div class="field-group"><span class="label">PRESENT READING</span>: <div class="fill"></div></div>

        <div style="margin-top: 30px;">
            <div class="check-item" style="font-weight: bold;"><div class="box"></div> WITH LEAK</div>
            <div style="border: 1px solid black; height: 80px; margin-left: 25px; padding: 5px;">Remarks:</div>
            
            <div class="check-item" style="font-weight: bold; margin-top: 20px;"><div class="box"></div> WITHOUT LEAK</div>
            <div style="border: 1px solid black; height: 80px; margin-left: 25px; padding: 5px;">Remarks:</div>
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
        <div class="field-group" style="width: 300px; margin-top: 30px;"><span class="label" style="min-width: 140px;">Date of Inspection:</span> <div class="fill"></div></div>
    </div>

    <div class="page">
        <div class="header">
            <p>Republic of the Philippines</p>
            <h2>LABASON WATER DISTRICT</h2>
            <p>BILLING ADJUSTMENT MEMO (BAM)</p>
        </div>

        <div class="bam-header">
            <div style="width: 70%;">
                <div class="field-group"><span class="label">Consumer</span>: <div class="fill"></div></div>
                <div class="field-group"><span class="label">Address</span>: <div class="fill"></div></div>
                <div class="field-group"><span class="label">Account No.</span>: <div class="fill"></div></div>
                <div class="field-group"><span class="label">Meter No.</span>: <div class="fill"></div></div>
            </div>
            <div style="font-weight: bold;">BAM No.: ________</div>
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
            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
        </table>

        <div style="margin-top: 20px;">
            <strong>Explanation:</strong>
            <div style="border-bottom: 1px solid black; height: 60px;"></div>
        </div>

        <div class="sig-section" style="font-size: 9pt;">
            <p>Prepared by:</p>
            <div style="width: 280px;">
                <div class="sig-line">MISHELLE P. MONDARTE</div>
                <span class="sig-sub">Industrial Relations Management Officer C / Billing Officer</span>
            </div>

            <p style="margin-top: 20px;">Verified Correct:</p>
            <div style="width: 280px;">
                <div class="sig-line">DARYL JAY T. VILLARIN</div>
                <span class="sig-sub">Administrative/General Services Officer B / HRMO/FO/BO</span>
            </div>

            <p style="margin-top: 20px;">Approved:</p>
            <div style="width: 280px;">
                <div class="sig-line">ENGR. ANASTACIA T. ROMANILLOS, CE</div>
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
