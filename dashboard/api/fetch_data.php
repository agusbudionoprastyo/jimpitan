<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include the connection file
require '../../helper/connection.php';

// Get database connection
$conn = getDatabaseConnection();

// Prepare SQL statement
$sql = "SELECT * FROM master_kk";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->execute();
    $data = $stmt->fetchAll();
    echo json_encode($data);
    
    $stmt->close();
} else {
    echo json_encode(["error" => "Failed to prepare statement."]);
}

$conn = null; // Close the connection
?>