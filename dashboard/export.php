<?php

require 'vendor/autoload.php';

use Box\Spout\Common\Entity\Cell;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

// Buat writer untuk file XLSX
$writer = WriterEntityFactory::createXLSXWriter();
$writer->openToBrowser('hello_world.xlsx');

// Menulis header kolom
$headerRow = [
    WriterEntityFactory::createCell('No !'),
    WriterEntityFactory::createCell('Nama KK !'),
];

// Menulis baris header ke file
$writer->addRow($headerRow);

// Mengisi tanggal dari 1 hingga 31 Oktober 2024
$startDate = new DateTime('2024-10-01');
$endDate = new DateTime('2024-10-31');

$dateRow = [];
for ($date = $startDate; $date <= $endDate; $date->modify('+1 day')) {
    $dateRow[] = WriterEntityFactory::createCell($date->format('d/m/Y')); // Format tanggal
}

// Menulis baris tanggal ke file
$writer->addRow($dateRow);

// Menulis baris untuk "No" dan "Nama KK"
for ($i = 1; $i <= 31; $i++) {
    $dataRow = [
        WriterEntityFactory::createCell($i), // No
        WriterEntityFactory::createCell('Nama KK ' . $i), // Nama KK
    ];
    $writer->addRow($dataRow);
}

// Menutup writer
$writer->close();
