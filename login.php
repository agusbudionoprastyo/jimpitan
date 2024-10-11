<?php
session_start();
require 'helper/connection.php'; // Assuming this includes the getDatabaseConnection function

$error = ''; // Initialize the error variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user'; // Default to 'user'
    $redirect_option = $_POST['redirect_option'] ?? 'scan_app'; // Default to 'scan_app'

    try {
        $pdo = getDatabaseConnection();

        $stmt = $pdo->prepare('SELECT * FROM users WHERE user_name = ? AND role = ?');
        $stmt->execute([$user_name, $role]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // If the user is an admin, skip shift validation
            if ($role === 'admin' || in_array(date('l'), explode(',', $user['shift']))) {
                $_SESSION['user'] = $user;

                // Redirect based on the chosen option
                if ($redirect_option === 'scan_app') {
                    header('Location: index.php'); // Redirect to Scan App
                } else {
                    header('Location: /dashboard'); // Redirect to Dashboard
                }
                exit;
            } else {
                $error = 'Login gagal! Hari ini bukan jadwalmu jaga';
            }
        } else {
            $error = 'username atau password salah!';
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
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600;800&display=swap'>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<form action="login.php" method="POST">
<div class="screen-1">
    <h3>Scan App Login</h3>

    <div class="role-selection">
        <label>
            <input type="radio" name="role" value="user" checked> User
        </label>
        <label>
            <input type="radio" name="role" value="admin"> Admin
        </label>
    </div>

    <div class="redirect-option">
        <label>
            <input type="radio" name="redirect_option" value="scan_app" checked> Scan App
        </label>
        <label>
            <input type="radio" name="redirect_option" value="dashboard"> Dashboard
        </label>
    </div>

    <div class="email">
        <label for="user_name">User</label>
        <div class="sec-2">
            <ion-icon name="person-outline"></ion-icon>
            <input type="text" name="user_name" placeholder="********" required/>
        </div>
    </div>
    
    <div class="password">
        <label for="password">Password</label>
        <div class="sec-2">
            <ion-icon name="lock-closed-outline"></ion-icon>
            <input class="pas" type="password" name="password" placeholder="********" required/>
        </div>
    </div>
    
    <button class="login">Login</button>
    <div class="footer">
        <?php if ($error): ?>
            <div class="error-message" style="color: red; font-size: 12px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </div>
</div>
</form>
</body>
</html>
