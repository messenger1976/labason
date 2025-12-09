<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "waterbilling";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["upload"])) {
    if (isset($_FILES["csv_file"]) && $_FILES["csv_file"]["error"] == 0) {
        $fileTmpPath = $_FILES["csv_file"]["tmp_name"];
        
        // Open the CSV file
        if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
            fgetcsv($handle); // Skip header row

            // Prepare UPDATE statement
            $stmt = $conn->prepare("UPDATE tbl_addcustomer_reading SET reading = ?, consumed = ? WHERE refno = ?");
            $stmt->bind_param("iii", $reading, $previous_reading, $refno);

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $refno = (int)$data[0];
                $customer_id = $data[1];
                $previous_reading = (int)$data[11]-(int)$data[10];
                $reading = (int)$data[11];

                $stmt->execute();
            }

            fclose($handle);
            echo "CSV file updated successfully!";
        } else {
            echo "Error opening file!";
        }
    } else {
        echo "Error: " . $_FILES["csv_file"]["error"];
    }
}

$conn->close();
?>
