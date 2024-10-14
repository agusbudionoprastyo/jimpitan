<?php
session_start(); // Start the session
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include the connection file
require 'db.php'; // Ensure this points to your actual DB connection file

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Pengguna tidak terautentikasi']);
    exit; // Stop execution if the user is not authenticated
}

// Get input data
$data = json_decode(file_get_contents("php://input"), true);

// Validate input data
$coa_code = isset($data['kode']) ? $data['kode'] : null; // Assuming 'kode' is used for COA code
$date_trx = isset($data['tanggal']) ? $data['tanggal'] : null;
$Disc_trx = isset($data['keterangan']) ? $data['keterangan'] : null;
$reff = isset($data['reff']) ? $data['reff'] : null;
$debet = isset($data['debit']) && $data['debit'] !== '' ? $data['debit'] : 0; // Default to 0 if not set
$kredit = isset($data['kredit']) && $data['kredit'] !== '' ? $data['kredit'] : 0; // Default to 0 if not set

// Basic validation
if (empty($coa_code) || empty($date_trx) || empty($reff) || empty($Disc_trx)) {
    echo json_encode(['success' => false, 'message' => 'Semua field harus diisi.']);
    exit;
}

// Insert data into the kas_umum table
try {
    // Prepare the SQL statement
    $stmt = $db->prepare("INSERT INTO kas_umum (coa_code, date_trx, Disc_trx, reff, debet, kredit) VALUES (?, ?, ?, ?, ?, ?)");
    
    // Execute the statement with data
    $stmt->execute([$coa_code, $date_trx, $Disc_trx, $reff, $debet, $kredit]);
    
    // Respond with success
    echo json_encode(['success' => true, 'message' => 'Data berhasil disimpan.']);
} catch (PDOException $e) {
    // Handle any database errors
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
}
