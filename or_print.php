<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print Bond Paper</title>
  <style>
    /* Define the exact size for bond paper (8.5 x 11 inches) */
    @media print {
      /* Ensure there's no margin added by the browser */
      @page {
        size: 8.5in 11in; /* Bond paper size */
        margin: 0; /* Remove default margins */
      }

      body {
        margin: 0; /* Remove body margin for proper alignment */
       
      }
      
      h1 {
        font-size: 18pt; /* Make headings smaller for print */
      }
      .print-section {
        width: 8.5in;
        height: 11in;
        background-image: url('http://localhost/waterbilling/images/orwaterbilling.jpg'); /* Replace with your image URL */
        /*background-size: cover;*/ /* Make the image cover the entire page */
        background-repeat: no-repeat; /* Prevent the image from repeating */
        background-position: center center; /* Center the image */
        -webkit-print-color-adjust: exact; /* Ensure colors are printed accurately */
        print-color-adjust: exact; /* Ensure compatibility with other browsers */
      }
    }
    body {
      margin: 0;
      padding: 0;
      
    }
    /* Normal view styles */
    .print-section {
      padding: 5px;
      background-color:transparent;
      border: 1px solid #ccc;
      width: 100%; /* Adjust for screen view */
      margin: auto;
      background-image: url('http://localhost/waterbilling/images/orwaterbilling.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
    }

    .no-print {
      display: block;
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="print-section">
    <h1>Printable Content</h1>
    <p>This layout is formatted to fit exactly on bond paper (8.5 x 11 inches).</p>
    <p>Adjust the styles as needed for your specific layout or design.</p>
  </div>

  <button class="no-print" onclick="printLayout()">Print Layout</button>

  <script>
    function printLayout() {
      window.print();
    }
    
  </script>
</body>
</html>
