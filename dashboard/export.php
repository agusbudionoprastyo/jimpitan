<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Buat objek Spreadsheet baru
$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet->setCellValue('A3', 'No !');
$activeWorksheet->setCellValue('B3', 'Nama KK !');

// Mulai mengisi tanggal dari kolom C (C3, D3, E3, ...)
$startDate = new DateTime('2024-10-01');
$endDate = new DateTime('2024-10-31');

// Atur header kolom untuk tanggal
$column = 3; // Kolom C adalah kolom ke-3
for ($date = $startDate; $date <= $endDate; $date->modify('+1 day')) {
    $activeWorksheet->setCellValueByColumnAndRow($column, 3, $date->format('d/m/Y')); // Set tanggal di baris 3
    $column++;
}

// Set header untuk mengunduh file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="hello_world.xlsx"');
header('Cache-Control: max-age=0');

// Buat writer dan simpan file ke output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit; // Pastikan tidak ada output lain yang dikirim
