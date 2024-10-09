<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include the connection file
require '../../helper/connection.php';

try {
    // Get database connection
    $conn = getDatabaseConnection();

    // Prepare SQL statement
    $sql = "SELECT * FROM master_kk";
    $stmt = $conn->prepare($sql);

    // Execute the statement
    if ($stmt->execute()) {
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to execute statement."]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn = null; // Close the connection
}
?>
