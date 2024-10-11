<?php
session_start();
require 'helper/connection.php'; // Assuming this includes the getDatabaseConnection function

$error = ''; // Initialize the error variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $pdo = getDatabaseConnection();

        $stmt = $pdo->prepare('SELECT * FROM users WHERE user_name = ?');
        $stmt->execute([$user_name]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Get the current day of the week
            $currentDay = date('l'); // e.g., "Monday", "Tuesday", etc.

            // Check if the current day is in the user's shift
            $shifts = explode(',', $user['shift']); // Assuming shifts are stored as a comma-separated string

            if (in_array($currentDay, $shifts)) {
                $_SESSION['user'] = $user;
                header('Location: index.php'); // Redirect to the index page
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
    <!-- <div class="logo">
        <img src="icon-192x192.png" alt="Logo" />
    </div> -->
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
            <div class="error-message" style="color: red;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <a href="/dashboard" style="color: black; font-size: 14px; text-decoration: none;">Go to Dashboard</a>
    </div>
</div>
</form>
</body>
</html>