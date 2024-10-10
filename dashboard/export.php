<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet->setCellValue('A3', 'No !');
$activeWorksheet->setCellValue('B3', 'Nama KK !');

// Start populating dates from column C (C3, D3, E3, ...)
$startDate = new DateTime('2024-10-01');
$endDate = new DateTime('2024-10-31');

// Set column headers for dates
$column = 3; // Column C is the 3rd column
for ($date = $startDate; $date <= $endDate; $date->modify('+1 day')) {
    $activeWorksheet->setCellValueByColumnAndRow($column, 3, $date->format('d/m/Y')); // Set date in row 3
    $column++;
}

// Set the headers to prompt a download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="hello_world.xlsx"');
header('Cache-Control: max-age=0');

// Create the writer and save the file to output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit; // Ensure no further output is sent
