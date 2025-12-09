<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection details
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'waterbilling1';

    // Connect to the database
    $conn = new mysqli($host, $user, $pass, $db);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the search term
    //$term = isset($_GET['term']) ? $_GET['term'] : '';

    $classification =$_POST['classification'];
    $rate = $_POST['rate'];
    $incre = $_POST['incre'];
    
    

    
    for ($i = $_POST['start']; $i <= $_POST['end']; $i++) {
        $sql = "SELECT id FROM tbl_amountrate WHERE classification_id = ? and cubic_meter = ?";
        

        if($_POST['incre']!=''){
            $rate += $incre;
        }
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $classification, $i);
        $stmt->execute();
        $stmt->store_result();




        if ($stmt->num_rows > 0) {
            // If ID exists, update the record
            $update_sql = "UPDATE tbl_amountrate SET per_unit = ?, status=1 WHERE classification_id = ? and cubic_meter = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("dii", $rate, $classification, $i);
        
            if ($update_stmt->execute()) {
                echo "Record updated successfully.";
            } else {
                echo "Error updating record: " . $conn->error;
            }
            $update_stmt->close();
        } else {
            // If ID does not exist, insert a new record
            $insert_sql = "INSERT INTO tbl_amountrate (classification_id, cubic_meter, per_unit, status) VALUES (?, ?, ?,'1')";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("iid", $classification, $i, $rate);
        
            if ($insert_stmt->execute()) {
                echo "Record inserted successfully.";
            } else {
                echo "Error inserting record: " . $conn->error;
            }
            $insert_stmt->close();
        }
// Close statement and connection

        echo "The value of i is: $i Rate: $rate Class: $classification Increment: $incre<br> ";
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Data Range</title>
</head>
<body>
    <h1>Generate Importa Data</h1>
    <form action="import_data.php" method="POST">
        <label for="classification">Classification:</label>
        <select name="classification">
            <option value="1">Residential 1/2</option>
            <option value="2">Residential 1/3</option>
            <option value="3">Residential 3/4</option>
            <option value="4">BARANGAY MINANG</option>
            <option value="5">Commercial 1/2</option>
            <option value="6">Commercial B</option>
            <option value="7">Commercial C</option>
            <option value="8">Government 1</option>
            <option value="9">Commercial 3/4</option>
            <option value="10">Commercial 1</option>
            <option value="11">Bulk Sales - PPA</option>
            <option value="12">Bulk Sales - Fire Dept</option>
        </select>
        <br><br>
        <label for="start">Start:</label>
        <input type="number" name="start" id="start" required><br><br>
        <label for="end">End:</label>
        <input type="number" name="end" id="end" required><br><br>
        <label for="rate">Rate:</label>
        <input type="number" name="rate" id="rate" step="0.01" placeholder="0.00" required><br><br>
        <label for="incre">incremental:</label>
        <input type="number" name="incre" id="incre" step="0.01" placeholder="0.00"><br><br>

        
        <input type="submit" value="Generate">
    </form>
</body>
</html>