<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection settings
    $host = 'localhost';
    $db = 'dafm5634_jimpitan';
    $user = 'dafm5634_ag';
    $pass = 'Ag7us777__';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);

        // Retrieve form data
        $user_name = $_POST['user_name'];
        $password = $_POST['password'];

        // Prepare and execute query
        $stmt = $pdo->prepare('SELECT * FROM users WHERE user_name = ?');
        $stmt->execute([$user_name]); // Use the correct variable
        $user = $stmt->fetch();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Password is correct
            $_SESSION['user'] = $user;
            header('Location: dashboard.php'); // Redirect to a protected page
            exit;
        } else {
            // Invalid credentials
            $error = 'Invalid Username or Password';
        }
    } catch (PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="user_name">User:</label>
            <input type="text" name="user_name" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>