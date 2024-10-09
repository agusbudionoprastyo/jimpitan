<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include the connection file
require '../../helper/connection.php';

// Get database connection
$conn = getDatabaseConnection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement
$sql = "SELECT * FROM master_kk";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch data
    $data = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($data);
    
    $stmt->close();
} else {
    echo json_encode(["error" => "Failed to prepare statement."]);
}

$conn->close();
?>
