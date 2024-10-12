<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include the connection file
require '../helper/connection.php';

// Get input data
$data = json_decode(file_get_contents("php://input"));

// Pastikan semua data yang diperlukan ada
if (isset($data->report_id) && isset($data->jimpitan_date) && isset($data->nominal) && isset($data->collector)) {
    $report_id = $data->report_id;
    $jimpitan_date = $data->jimpitan_date;
    $nominal = $data->nominal;
    $collector = $data->collector;

    // Dapatkan koneksi database
    $conn = getDatabaseConnection();

    // Siapkan pernyataan SQL untuk penyisipan
    $sql = "INSERT INTO report (report_id, jimpitan_date, nominal, collector) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Eksekusi pernyataan
        $stmt->execute([$report_id, $jimpitan_date, $nominal, $collector]);
        
        // Respons sukses
        echo json_encode(['success' => true, 'message' => 'Data berhasil disisipkan']);
    } else {
        // Respons gagal untuk persiapan pernyataan
        echo json_encode(['success' => false, 'message' => 'Gagal menyiapkan pernyataan']);
    }
} else {
    // Respons jika data tidak lengkap
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
}
?>
