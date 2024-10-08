<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Database connection
$servername = "localhost";
$username = "dafm5634_ag";
$password = "Ag7us777__";
$dbname = "dafm5634_jimpitan";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get input data
$data = json_decode(file_get_contents("php://input"));

if (isset($data->code_id)) {
    $code_id = $data->code_id;

    // Prepare SQL statement
    $sql = "SELECT kk_name FROM master_kk WHERE code_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $code_id);
        $stmt->execute();
        $stmt->bind_result($kk_name);
        $stmt->fetch();

        if ($kk_name) {
            echo json_encode(['success' => true, 'kk_name' => $kk_name]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'code_id not provided']);
}

$conn->close();
?>