<?php
require 'vendor/autoload.php'; // Pastikan path ini sesuai dengan proyek Anda

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Membuat data dummy
$data = [
    ['Nama', 'Umur', 'Kota'],
    ['Alice', 25, 'Jakarta'],
    ['Bob', 30, 'Bandung'],
    ['Charlie', 22, 'Surabaya'],
];

// Membuat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Menulis data ke spreadsheet
foreach ($data as $rowIndex => $row) {
    foreach ($row as $colIndex => $value) {
        $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 1, $value);
    }
}

// Menyimpan file XLSX
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="report.xlsx"');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();