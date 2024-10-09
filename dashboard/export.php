<?php
require 'vendor/autoload.php'; // Memuat autoload dari Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Koneksi ke Database
$host = 'localhost'; // Ganti dengan host database Anda
$dbname = 'dafm5634_jimpitan'; // Ganti dengan nama database Anda
$username = 'dafm5634_ag'; // Ganti dengan username database Anda
$password = 'Ag7us777__'; // Ganti dengan password database Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Mendapatkan tanggal awal dan akhir bulan
$startDate = new DateTime('first day of this month');
$endDate = new DateTime('last day of this month');
$endDateFormatted = $endDate->format('Y-m-d');

// Query untuk mengambil data
$sql = "SELECT report_id, jimpitan_date, nominal, collector
        FROM report
        WHERE jimpitan_date BETWEEN :start_date AND :end_date
        AND report_id = 'RT0700001'";
$stmt = $pdo->prepare($sql);
$stmt->execute(['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDateFormatted]);

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Membuat spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Menulis tanggal dari 1 hingga akhir bulan di B3 sampai AF3
$currentDate = clone $startDate;
$col = 2; // Kolom B

while ($currentDate <= $endDate) {
    $sheet->setCellValueByColumnAndRow($col++, 3, $currentDate->format('Y-m-d'));
    $currentDate->modify('+1 day');
}

// Menulis header di A4
$sheet->setCellValue('A4', 'Report ID');
$sheet->setCellValue('B4', 'Nominal');
$sheet->setCellValue('C4', 'Collector');

// Menulis data ke spreadsheet mulai dari baris ke-5
$rowNumber = 5; // Mulai dari baris ke-5
foreach ($data as $row) {
    $sheet->setCellValue('A' . $rowNumber, $row['report_id']);
    $sheet->setCellValue('B' . $rowNumber, $row['nominal']);
    $sheet->setCellValue('C' . $rowNumber, $row['collector']);
    $rowNumber++;
}

// Menyimpan file XLSX
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="report.xlsx"');
$writer->save('php://output');
exit;
?>
