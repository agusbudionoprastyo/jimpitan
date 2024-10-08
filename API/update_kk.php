<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['code_id']) && isset($input['kk_name'])) {
    $codeId = $input['code_id'];
    $kkName = $input['kk_name'];

    // Koneksi ke database (ganti dengan detail Anda)
    $host = 'localhost'; // biasanya localhost
    $db   = 'dafm5634_jimpitan'; // nama database
    $user = 'dafm5634_ag'; // username database
    $pass = 'Ag7us777__'; // password database
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);

        // Periksa apakah CODE_ID sudah ada
        $stmt = $pdo->prepare('SELECT * FROM master_kk WHERE CODE_ID = ?');
        $stmt->execute([$codeId]);
        
        if ($stmt->rowCount() > 0) {
            // Jika ada, lakukan update
            $stmt = $pdo->prepare('UPDATE master_kk SET KK_NAME = ? WHERE CODE_ID = ?');
            $stmt->execute([$kkName, $codeId]);
            echo json_encode(['status' => 'success', 'message' => 'Data updated successfully.']);
        } else {
            // Jika tidak ada, lakukan insert
            $stmt = $pdo->prepare('INSERT INTO master_kk (CODE_ID, KK_NAME) VALUES (?, ?)');
            $stmt->execute([$codeId, $kkName]);
            echo json_encode(['status' => 'success', 'message' => 'Data inserted successfully.']);
        }
    } catch (\PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
}
?>
