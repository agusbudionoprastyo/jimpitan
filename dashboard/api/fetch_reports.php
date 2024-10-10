<?php
// Include the database connection
require 'db.php';

header('Content-Type: application/json');

try {
    // Ambil data dari tabel report
    $sql = "SELECT report_id, jimpitan_date, nominal FROM report";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($reports);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>