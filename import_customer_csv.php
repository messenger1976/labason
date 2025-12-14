<?php
// CSV Import Script for tbl_addcustomer
// Usage: Run this script via browser or command line

// Database connection details
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'labasonwd';

// CSV file path
$csv_file = 'C:\\Users\\Admin\\Documents\\labason-members-zone8.csv';

// Connect to the database
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to handle special characters
$conn->set_charset("utf8");

// Function to convert date from DD/MM/YYYY to YYYY-MM-DD
function convertDate($date_str) {
    if (empty($date_str) || trim($date_str) == '') {
        return null;
    }
    
    // Handle different date formats
    $date_str = trim($date_str);
    
    // Try DD/MM/YYYY format
    if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $date_str, $matches)) {
        $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
        $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
        $year = $matches[3];
        return "$year-$month-$day";
    }
    
    // If already in YYYY-MM-DD format, return as is
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_str)) {
        return $date_str;
    }
    
    // Try strtotime as fallback
    $timestamp = strtotime($date_str);
    if ($timestamp !== false) {
        return date('Y-m-d', $timestamp);
    }
    
    return null;
}

// Function to parse name from last_name field
function parseName($name_str) {
    $name_str = trim($name_str);
    
    // Remove quotes if present
    $name_str = trim($name_str, '"');
    
    // Check if name contains comma (Last, First format)
    if (strpos($name_str, ',') !== false) {
        $parts = explode(',', $name_str, 2);
        $last_name = trim($parts[0]);
        $first_name = isset($parts[1]) ? trim($parts[1]) : '';
        
        // Handle cases like "Dalogdog,Cresencio Jr." where there's no space after comma
        if (empty($first_name) && isset($parts[1])) {
            $first_name = trim($parts[1]);
        }
    } else {
        // If no comma, treat entire string as last_name
        $last_name = $name_str;
        $first_name = '';
    }
    
    return array(
        'first_name' => $first_name,
        'last_name' => $last_name
    );
}

// Zone will be read from CSV, but we'll validate it exists in the database

// Default values
$default_classification = 1; // You may need to adjust this
$default_status = 1; // Active
$default_customer_type = 'metercustomer'; // or 'monthlycustomer'
$default_account_type = 1;
$default_billingplans = 1;

// Open CSV file
if (!file_exists($csv_file)) {
    die("Error: CSV file not found at: $csv_file\n");
}

$file = fopen($csv_file, 'r');
if ($file === false) {
    die("Error: Could not open CSV file\n");
}

// Read header row
$header = fgetcsv($file);
if ($header === false) {
    die("Error: Could not read CSV header\n");
}

// Map CSV columns to indices
$col_map = array();
foreach ($header as $index => $col_name) {
    $col_name = trim($col_name);
    $col_map[$col_name] = $index;
}

// Verify required columns exist
$required_cols = array('last_name', 'customer_id', 'meter_number', 'meter_brand', 'date_installed', 'zone');
foreach ($required_cols as $col) {
    if (!isset($col_map[$col])) {
        die("Error: Required column '$col' not found in CSV\n");
    }
}

// Statistics
$imported = 0;
$updated = 0;
$skipped = 0;
$errors = array();

// Process each row
$row_num = 1; // Start at 1 since we already read the header
while (($row = fgetcsv($file)) !== false) {
    $row_num++;
    
    // Skip empty rows
    if (empty(array_filter($row))) {
        continue;
    }
    
    // Get values from CSV
    $last_name_field = isset($row[$col_map['last_name']]) ? trim($row[$col_map['last_name']]) : '';
    $customer_id = isset($row[$col_map['customer_id']]) ? trim($row[$col_map['customer_id']]) : '';
    $meter_number = isset($row[$col_map['meter_number']]) ? trim($row[$col_map['meter_number']]) : '';
    $meter_brand = isset($row[$col_map['meter_brand']]) ? trim($row[$col_map['meter_brand']]) : '';
    $date_installed_str = isset($row[$col_map['date_installed']]) ? trim($row[$col_map['date_installed']]) : '';
    $zone_from_csv = isset($row[$col_map['zone']]) ? trim($row[$col_map['zone']]) : '';
    
    // Skip if customer_id is empty
    if (empty($customer_id)) {
        $skipped++;
        $errors[] = "Row $row_num: Skipped - customer_id is empty";
        continue;
    }
    
    // Parse name
    $name_parts = parseName($last_name_field);
    $first_name = $name_parts['first_name'];
    $last_name = $name_parts['last_name'];
    
    // Convert date (CSV already has YYYY-MM-DD format, but we'll validate it)
    $date_installed = null;
    if (!empty($date_installed_str)) {
        // Check if already in YYYY-MM-DD format
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_installed_str)) {
            $date_installed = $date_installed_str;
        } else {
            // Try to convert from other formats
            $date_installed = convertDate($date_installed_str);
        }
    }
    
    // Get zone ID from CSV (it's already a number)
    $zone_id = !empty($zone_from_csv) ? intval($zone_from_csv) : 1;
    
    // Validate zone exists in database
    $zone_check_sql = "SELECT id FROM tbl_zone WHERE id = ?";
    $zone_check_stmt = $conn->prepare($zone_check_sql);
    $zone_check_stmt->bind_param("i", $zone_id);
    $zone_check_stmt->execute();
    $zone_check_result = $zone_check_stmt->get_result();
    if (!$zone_check_result || $zone_check_result->num_rows == 0) {
        $skipped++;
        $errors[] = "Row $row_num: Zone ID $zone_id not found in database";
        $zone_check_stmt->close();
        continue;
    }
    $zone_check_stmt->close();
    
    // Check if customer already exists
    $check_sql = "SELECT id FROM tbl_addcustomer WHERE customer_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $customer_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $exists = $check_result->num_rows > 0;
    $check_stmt->close();
    
    // Get current date/time
    $dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
    $created_date = $dt_date->format("Y-m-d H:i:s");
    $create_date = $dt_date->format("Y-m-d");
    
    if ($exists) {
        // Update existing record
        $update_sql = "UPDATE tbl_addcustomer SET 
            first_name = ?,
            last_name = ?,
            meter_number = ?,
            meter_brand = ?,
            date_installed = ?,
            zone = ?,
            update_date_time = ?
            WHERE customer_id = ?";
        
        $update_stmt = $conn->prepare($update_sql);
        // Handle NULL date_installed
        $date_installed_for_update = $date_installed;
        $update_stmt->bind_param("sssssiss", 
            $first_name,
            $last_name,
            $meter_number,
            $meter_brand,
            $date_installed_for_update,
            $zone_id,
            $created_date,
            $customer_id
        );
        
        if ($update_stmt->execute()) {
            $updated++;
        } else {
            $skipped++;
            $errors[] = "Row $row_num: Update failed - " . $conn->error;
        }
        $update_stmt->close();
    } else {
        // Insert new record
        $insert_sql = "INSERT INTO tbl_addcustomer (
            account_id,
            customer_id,
            first_name,
            middle_name,
            last_name,
            meter_number,
            meter_brand,
            date_installed,
            zone,
            classification,
            status,
            customer_type,
            account_type,
            billingplans,
            create_date,
            create_date_time
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $middle_name = ''; // Not in CSV
        $insert_stmt = $conn->prepare($insert_sql);
        // Type string for 16 parameters - building explicitly to ensure correct count:
        // 1.i(account_id) 2.s(customer_id) 3.s(first_name) 4.s(middle_name) 5.s(last_name) 
        // 6.s(meter_number) 7.s(meter_brand) 8.s(date_installed) 9.i(zone) 10.i(classification) 
        // 11.i(status) 12.s(customer_type) 13.i(account_type) 14.i(billingplans) 15.s(create_date) 16.s(create_date_time)
        // Count: i(1) + s(7) + i(3) + s(1) + i(2) + s(2) = 16 characters
        // Building: "i" + "sssssss" + "iii" + "s" + "ii" + "ss"
        $type_string = "i" . str_repeat("s", 7) . str_repeat("i", 3) . "s" . str_repeat("i", 2) . str_repeat("s", 2);
        $insert_stmt->bind_param($type_string,
            $default_account_type,     // 1. account_id (i)
            $customer_id,              // 2. customer_id (s)
            $first_name,               // 3. first_name (s)
            $middle_name,              // 4. middle_name (s)
            $last_name,                // 5. last_name (s)
            $meter_number,             // 6. meter_number (s)
            $meter_brand,              // 7. meter_brand (s)
            $date_installed,           // 8. date_installed (s)
            $zone_id,                  // 9. zone (i)
            $default_classification,   // 10. classification (i)
            $default_status,           // 11. status (i)
            $default_customer_type,   // 12. customer_type (s)
            $default_account_type,     // 13. account_type (i)
            $default_billingplans,     // 14. billingplans (i)
            $create_date,              // 15. create_date (s)
            $created_date              // 16. create_date_time (s)
        );
        
        if ($insert_stmt->execute()) {
            $imported++;
        } else {
            $skipped++;
            $errors[] = "Row $row_num: Insert failed - " . $conn->error;
        }
        $insert_stmt->close();
    }
}

fclose($file);
$conn->close();

// Display results
$is_cli = (php_sapi_name() === 'cli');

if ($is_cli) {
    // Command line output
    echo "\n";
    echo "========================================\n";
    echo "CSV Import Results\n";
    echo "========================================\n";
    echo "File: $csv_file\n";
    echo "Total rows processed: " . ($row_num - 1) . "\n";
    echo "New records imported: $imported\n";
    echo "Existing records updated: $updated\n";
    echo "Skipped/Errors: $skipped\n";
    echo "\n";
    
    if (!empty($errors)) {
        echo "Errors/Warnings:\n";
        echo "----------------\n";
        foreach ($errors as $error) {
            echo "- $error\n";
        }
        echo "\n";
    }
    
    echo "Import completed!\n";
    echo "========================================\n";
} else {
    // HTML output
    echo "<h2>CSV Import Results</h2>";
    echo "<p><strong>File:</strong> $csv_file</p>";
    echo "<p><strong>Total rows processed:</strong> " . ($row_num - 1) . "</p>";
    echo "<p><strong>New records imported:</strong> $imported</p>";
    echo "<p><strong>Existing records updated:</strong> $updated</p>";
    echo "<p><strong>Skipped/Errors:</strong> $skipped</p>";
    
    if (!empty($errors)) {
        echo "<h3>Errors/Warnings:</h3>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
    
    echo "<p><strong>Import completed!</strong></p>";
}
?>

