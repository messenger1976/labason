<?php
/**
 * Test Script for Statement of Account API
 * 
 * This script demonstrates how to call the Statement of Account API
 * for customer ID: 11-7-12-01262
 * 
 * Usage: Open in browser or run via command line:
 * php test_statement_api.php
 */

// Configuration
$base_url = 'http://labason.com';
$customer_id = '11-7-12-01262'; // Change this to test different customers

// API Endpoint
$api_url = $base_url . '/master/statementofaccount_api/get/' . $customer_id;

// Function to make API call
function callAPI($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept: application/json'
    ));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return array(
        'response' => $response,
        'http_code' => $http_code,
        'error' => $error
    );
}

// Make API call
$result = callAPI($api_url);

// Check if running from command line or browser
$is_cli = php_sapi_name() === 'cli';

if ($is_cli) {
    // Command line output
    echo "=== Statement of Account API Test ===\n\n";
    echo "Customer ID: {$customer_id}\n";
    echo "API URL: {$api_url}\n";
    echo "HTTP Code: {$result['http_code']}\n\n";
    
    if (!empty($result['error'])) {
        echo "cURL Error: {$result['error']}\n";
    } else {
        $data = json_decode($result['response'], true);
        
        if ($data && isset($data['success'])) {
            if ($data['success']) {
                echo "✓ API Call Successful\n\n";
                
                $customer = $data['data']['customer_info'];
                echo "=== Customer Information ===\n";
                echo "Customer ID: " . $customer['customer_id'] . "\n";
                echo "Name: " . strtoupper($customer['last_name'] . ', ' . $customer['first_name'] . ' ' . $customer['middle_name']) . "\n";
                echo "Address: " . strtoupper($customer['address']) . "\n";
                echo "Zone: " . (isset($customer['zone']) ? $customer['zone'] : 'N/A') . "\n";
                echo "Meter Number: " . $customer['meter_number'] . "\n";
                echo "Classification: " . (isset($customer['class_name']) ? $customer['class_name'] : 'N/A') . "\n";
                echo "Account Type: " . (isset($customer['cust_type_name']) ? $customer['cust_type_name'] : 'N/A') . "\n\n";
                
                echo "=== Account Summary ===\n";
                echo "Current Balance: PHP " . number_format($data['data']['current_balance'], 2) . "\n";
                echo "Total Entries: " . $data['data']['entry_count'] . "\n";
                echo "Generated At: " . $data['data']['generated_at'] . "\n\n";
                
                echo "=== Recent Transactions (Last 5) ===\n";
                $entries = array_slice($data['data']['ledger_entries'], 0, 5);
                foreach ($entries as $entry) {
                    echo "\nDate: " . date('d-m-Y', strtotime($entry['date'])) . "\n";
                    echo "Type: " . ucfirst($entry['type']) . "\n";
                    echo "Ref No: " . $entry['refno'] . "\n";
                    echo "Description: " . $entry['description'] . "\n";
                    if ($entry['debit'] > 0) {
                        echo "Debit: PHP " . number_format($entry['debit'], 2) . "\n";
                    }
                    if ($entry['credit'] > 0) {
                        echo "Credit: PHP " . number_format($entry['credit'], 2) . "\n";
                    }
                    echo "Balance: PHP " . number_format($entry['balance'], 2) . "\n";
                }
                
                echo "\n=== Full JSON Response ===\n";
                echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
            } else {
                echo "✗ API Error: {$data['message']}\n";
                echo "\nFull Response:\n";
                echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
            }
        } else {
            echo "✗ Invalid JSON Response\n";
            echo "Response: {$result['response']}\n";
        }
    }
} else {
    // Browser output
    header('Content-Type: text/html; charset=utf-8');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Statement of Account API Test</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 1200px;
                margin: 20px auto;
                padding: 20px;
                background: #f5f5f5;
            }
            .container {
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            h1 {
                color: #333;
                border-bottom: 2px solid #007bff;
                padding-bottom: 10px;
            }
            .info-box {
                background: #e7f3ff;
                border-left: 4px solid #007bff;
                padding: 15px;
                margin: 15px 0;
            }
            .success {
                background: #d4edda;
                border-left: 4px solid #28a745;
                color: #155724;
            }
            .error {
                background: #f8d7da;
                border-left: 4px solid #dc3545;
                color: #721c24;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
            }
            th, td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            th {
                background: #007bff;
                color: white;
            }
            .balance {
                font-size: 24px;
                font-weight: bold;
                color: #dc3545;
            }
            .balance.positive {
                color: #28a745;
            }
            pre {
                background: #f4f4f4;
                padding: 15px;
                border-radius: 4px;
                overflow-x: auto;
            }
            .badge {
                display: inline-block;
                padding: 5px 10px;
                border-radius: 3px;
                font-size: 12px;
                font-weight: bold;
            }
            .badge-billing {
                background: #ffc107;
                color: #000;
            }
            .badge-payment {
                background: #28a745;
                color: white;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Statement of Account API Test</h1>
            
            <div class="info-box">
                <strong>Customer ID:</strong> <?php echo htmlspecialchars($customer_id); ?><br>
                <strong>API URL:</strong> <a href="<?php echo htmlspecialchars($api_url); ?>" target="_blank"><?php echo htmlspecialchars($api_url); ?></a><br>
                <strong>HTTP Code:</strong> <?php echo $result['http_code']; ?>
            </div>
            
            <?php
            if (!empty($result['error'])) {
                echo '<div class="error">';
                echo '<strong>cURL Error:</strong> ' . htmlspecialchars($result['error']);
                echo '</div>';
            } else {
                $data = json_decode($result['response'], true);
                
                if ($data && isset($data['success'])) {
                    if ($data['success']) {
                        $customer = $data['data']['customer_info'];
                        $balance = $data['data']['current_balance'];
                        $entries = $data['data']['ledger_entries'];
                        ?>
                        
                        <div class="success">
                            <strong>✓ API Call Successful</strong>
                        </div>
                        
                        <h2>Customer Information</h2>
                        <table>
                            <tr>
                                <th width="200">Field</th>
                                <th>Value</th>
                            </tr>
                            <tr>
                                <td><strong>Customer ID</strong></td>
                                <td><?php echo htmlspecialchars($customer['customer_id']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Name</strong></td>
                                <td><?php echo htmlspecialchars(strtoupper($customer['last_name'] . ', ' . $customer['first_name'] . ' ' . $customer['middle_name'])); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Address</strong></td>
                                <td><?php echo htmlspecialchars(strtoupper($customer['address'])); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Zone</strong></td>
                                <td><?php echo htmlspecialchars(isset($customer['zone']) ? $customer['zone'] : 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Meter Number</strong></td>
                                <td><?php echo htmlspecialchars($customer['meter_number']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Classification</strong></td>
                                <td><?php echo htmlspecialchars(isset($customer['class_name']) ? $customer['class_name'] : 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Account Type</strong></td>
                                <td><?php echo htmlspecialchars(isset($customer['cust_type_name']) ? $customer['cust_type_name'] : 'N/A'); ?></td>
                            </tr>
                        </table>
                        
                        <h2>Account Summary</h2>
                        <div class="info-box">
                            <div class="balance <?php echo $balance > 0 ? '' : 'positive'; ?>">
                                Current Balance: PHP <?php echo number_format($balance, 2); ?>
                            </div>
                            <p><strong>Total Entries:</strong> <?php echo $data['data']['entry_count']; ?></p>
                            <p><strong>Generated At:</strong> <?php echo $data['data']['generated_at']; ?></p>
                        </div>
                        
                        <h2>Ledger Entries</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Ref No</th>
                                    <th>Description</th>
                                    <th style="text-align: right;">Debit</th>
                                    <th style="text-align: right;">Credit</th>
                                    <th style="text-align: right;">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($entries as $entry) {
                                    echo '<tr>';
                                    echo '<td>' . date('d-m-Y', strtotime($entry['date'])) . '</td>';
                                    echo '<td><span class="badge badge-' . ($entry['type'] == 'billing' ? 'billing' : 'payment') . '">' . ucfirst($entry['type']) . '</span></td>';
                                    echo '<td>' . htmlspecialchars($entry['refno']) . '</td>';
                                    echo '<td>' . htmlspecialchars($entry['description']) . '</td>';
                                    echo '<td style="text-align: right;">' . ($entry['debit'] > 0 ? 'PHP ' . number_format($entry['debit'], 2) : '-') . '</td>';
                                    echo '<td style="text-align: right;">' . ($entry['credit'] > 0 ? 'PHP ' . number_format($entry['credit'], 2) : '-') . '</td>';
                                    echo '<td style="text-align: right;"><strong>PHP ' . number_format($entry['balance'], 2) . '</strong></td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        
                        <h2>Full JSON Response</h2>
                        <pre><?php echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
                        
                        <?php
                    } else {
                        echo '<div class="error">';
                        echo '<strong>✗ API Error:</strong> ' . htmlspecialchars($data['message']);
                        echo '</div>';
                        echo '<h2>Full Response</h2>';
                        echo '<pre>' . json_encode($data, JSON_PRETTY_PRINT) . '</pre>';
                    }
                } else {
                    echo '<div class="error">';
                    echo '<strong>✗ Invalid JSON Response</strong>';
                    echo '</div>';
                    echo '<h2>Raw Response</h2>';
                    echo '<pre>' . htmlspecialchars($result['response']) . '</pre>';
                }
            }
            ?>
        </div>
    </body>
    </html>
    <?php
}
?>
