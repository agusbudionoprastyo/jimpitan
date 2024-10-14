<?php
session_start(); // Start the session
header('Content-Type: application/json');

// Include the connection file
require 'db.php'; // Ensure this points to your actual DB connection file

// Get input data
$data = json_decode(file_get_contents("php://input"), true);

// Validate input data
$coa_code = isset($data['kode']) ? $data['kode'] : null; // COA code
$date_trx = isset($data['tanggal']) ? $data['tanggal'] : null; // Transaction date
$Disc_trx = isset($data['keterangan']) ? $data['keterangan'] : null; // Description
$reff = isset($data['reff']) ? $data['reff'] : null; // Reference
$debet = !empty($data['debit']) ? $data['debit'] : 0; // Default to 0 if not set
$kredit = !empty($data['kredit']) ? $data['kredit'] : 0; // Default to 0 if not set

// Basic validation for required fields
if (empty($coa_code) || empty($date_trx) || empty($Disc_trx)) {
    echo json_encode(['success' => false, 'message' => 'Semua field harus diisi.']);
    exit;
}

// Insert data into the kas_umum table
try {
    // Prepare the SQL statement
    $stmt = $pdo->prepare("INSERT INTO kas_umum (coa_code, date_trx, Disc_trx, reff, debet, kredit) VALUES (?, ?, ?, ?, ?, ?)");
    
    // Execute the statement with data
    $stmt->execute([$coa_code, $date_trx, $Disc_trx, $reff, $debet, $kredit]);
    
    // Respond with success
    echo json_encode(['success' => true, 'message' => 'Data berhasil disimpan.']);
} catch (PDOException $e) {
    // Handle any database errors
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
}
