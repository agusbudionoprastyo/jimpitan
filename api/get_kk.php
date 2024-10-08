<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include the connection file
require '../helper/connection.php';

// Get input data
$data = json_decode(file_get_contents("php://input"));

if (isset($data->code_id)) {
    $code_id = $data->code_id;

    // Get database connection
    $conn = getDatabaseConnection();

    // Prepare SQL statement
    $sql = "SELECT kk_name FROM master_kk WHERE code_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->execute([$code_id]);
        $kk_name = $stmt->fetchColumn();

        if ($kk_name) {
            echo json_encode(['success' => true, 'kk_name' => $kk_name]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'code_id not provided']);
}
?>
