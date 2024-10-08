<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['id_code']) && isset($input['user_name']) && isset($input['password']) && isset($input['shift'])) {
    $idCode = $input['id_code'];
    $userName = $input['user_name'];
    $password = password_hash($input['password'], PASSWORD_DEFAULT); // Hash the password
    $shift = $input['shift'];

    // Database connection (replace with your details)
    $host = 'localhost'; // usually localhost
    $db   = 'dafm5634_jimpitan'; // database name
    $user = 'dafm5634_ag'; // database username
    $pass = 'Ag7us777__'; // database password
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);

        // Check if id_code already exists
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id_code = ?');
        $stmt->execute([$idCode]);
        
        if ($stmt->rowCount() > 0) {
            // If exists, perform update
            $stmt = $pdo->prepare('UPDATE users SET user_name = ?, password = ?, shift = ? WHERE id_code = ?');
            $stmt->execute([$userName, $password, $shift, $idCode]);
            echo json_encode(['status' => 'success', 'message' => 'User data updated successfully.']);
        } else {
            // If not exists, perform insert
            $stmt = $pdo->prepare('INSERT INTO users (id_code, user_name, password, shift) VALUES (?, ?, ?, ?)');
            $stmt->execute([$idCode, $userName, $password, $shift]);
            echo json_encode(['status' => 'success', 'message' => 'User data inserted successfully.']);
        }
    } catch (\PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
}
?>
