<?php
// Include the database connection
require 'db.php';

header('Content-Type: application/json');

try {
    // Ambil data dari tabel report
    $sql = "SELECT 
    report_id,
    SUM(CASE WHEN DAY(jimpitan_date) = 1 THEN nominal END) AS '1',
    SUM(CASE WHEN DAY(jimpitan_date) = 2 THEN nominal END) AS '2',
    SUM(CASE WHEN DAY(jimpitan_date) = 3 THEN nominal END) AS '3',
    SUM(CASE WHEN DAY(jimpitan_date) = 4 THEN nominal END) AS '4',
    SUM(CASE WHEN DAY(jimpitan_date) = 5 THEN nominal END) AS '5',
    SUM(CASE WHEN DAY(jimpitan_date) = 6 THEN nominal END) AS '6',
    SUM(CASE WHEN DAY(jimpitan_date) = 7 THEN nominal END) AS '7',
    SUM(CASE WHEN DAY(jimpitan_date) = 8 THEN nominal END) AS '8',
    SUM(CASE WHEN DAY(jimpitan_date) = 9 THEN nominal END) AS '9',
    SUM(CASE WHEN DAY(jimpitan_date) = 10 THEN nominal END) AS '10',
    SUM(CASE WHEN DAY(jimpitan_date) = 11 THEN nominal END) AS '11',
    SUM(CASE WHEN DAY(jimpitan_date) = 12 THEN nominal END) AS '12',
    SUM(CASE WHEN DAY(jimpitan_date) = 13 THEN nominal END) AS '13',
    SUM(CASE WHEN DAY(jimpitan_date) = 14 THEN nominal END) AS '14',
    SUM(CASE WHEN DAY(jimpitan_date) = 15 THEN nominal END) AS '15',
    SUM(CASE WHEN DAY(jimpitan_date) = 16 THEN nominal END) AS '16',
    SUM(CASE WHEN DAY(jimpitan_date) = 17 THEN nominal END) AS '17',
    SUM(CASE WHEN DAY(jimpitan_date) = 18 THEN nominal END) AS '18',
    SUM(CASE WHEN DAY(jimpitan_date) = 19 THEN nominal END) AS '19',
    SUM(CASE WHEN DAY(jimpitan_date) = 20 THEN nominal END) AS '20',
    SUM(CASE WHEN DAY(jimpitan_date) = 21 THEN nominal END) AS '21',
    SUM(CASE WHEN DAY(jimpitan_date) = 22 THEN nominal END) AS '22',
    SUM(CASE WHEN DAY(jimpitan_date) = 23 THEN nominal END) AS '23',
    SUM(CASE WHEN DAY(jimpitan_date) = 24 THEN nominal END) AS '24',
    SUM(CASE WHEN DAY(jimpitan_date) = 25 THEN nominal END) AS '25',
    SUM(CASE WHEN DAY(jimpitan_date) = 26 THEN nominal END) AS '26',
    SUM(CASE WHEN DAY(jimpitan_date) = 27 THEN nominal END) AS '27',
    SUM(CASE WHEN DAY(jimpitan_date) = 28 THEN nominal END) AS '28',
    SUM(CASE WHEN DAY(jimpitan_date) = 29 THEN nominal END) AS '29',
    SUM(CASE WHEN DAY(jimpitan_date) = 30 THEN nominal END) AS '30',
    SUM(CASE WHEN DAY(jimpitan_date) = 31 THEN nominal END) AS '31',
    SUM(nominal) AS total-- Menambahkan total nominal untuk semua tanggal
FROM 
    report
WHERE 
    jimpitan_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01') 
    AND jimpitan_date <= LAST_DAY(CURDATE())
GROUP BY 
    report_id;
";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($reports);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>