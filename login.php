<?php
session_start();
require 'helper/connection.php'; // Assuming this includes the getDatabaseConnection function

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $pdo = getDatabaseConnection();

        $stmt = $pdo->prepare('SELECT * FROM users WHERE user_name = ?');
        $stmt->execute([$user_name]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header('Location: index.php'); // Redirect to the index page
            exit;
        } else {
            $error = 'Invalid username or password.';
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
    <!-- stylesCss -->
        <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    <div class="login-page">
        <div class="form">  
            <form class="login-form" action="login.php" method="POST">
                <input type="text" name="user_name" placeholder="username" required>
                <input type="password" name="password" placeholder="password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>