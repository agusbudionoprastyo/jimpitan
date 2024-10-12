<?php
header('Content-Type: application/json');

// Include the connection file
require '../helper/connection.php';

// Get the raw POST data
$input = json_decode(file_get_contents('php://input'), true);

// Check if data is set
if (isset($input['report_id'], $input['jimpitan_date'], $input['nominal'], $input['collector'])) {
    $report_id = $input['report_id'];
    $jimpitan_date = $input['jimpitan_date'];
    $nominal = $input['nominal'];
    $collector = $input['collector'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO report (report_id, jimpitan_date, nominal, collector) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $report_id, $jimpitan_date, $nominal, $collector);

    // Execute the statement
    if ($stmt->execute()) {
        // Success response
        echo json_encode(['success' => true, 'message' => 'Data inserted successfully.']);
    } else {
        // Error response
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
} else {
    // Missing data response
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
}

// Close the database connection
$conn->close();
?>