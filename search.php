<?php
// Database connection details
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

include('global.php');

$host = $db['default']['hostname'];
$user = $db['default']['username'];
$pass = $db['default']['password'];
$db = $db['default']['database'];
// Connect to the database
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search term
$term = isset($_GET['term']) ? $_GET['term'] : '';

if (!empty($term)) {
    // Prepare and execute the query using prepared statements
    $stmt = $conn->prepare("
        SELECT customer_id, first_name, last_name 
        FROM tbl_addcustomer 
        WHERE customer_id LIKE CONCAT('%', ?, '%') OR last_name LIKE CONCAT('%', ?, '%')
        LIMIT 10
    ");
    $stmt->bind_param("ss", $term, $term);
    $stmt->execute();

    // Fetch the results
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'customer_id' => $row['customer_id'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
        ];
    }

    // Close the statement
    $stmt->close();

    // Return the results as JSON
    echo json_encode($data);
} else {
    echo json_encode([]);
}

// Close the connection
$conn->close();
?>
