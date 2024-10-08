<?php
session_start();
require 'connection.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = getDatabaseConnection(); // Get the database connection

    // Retrieve form data
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    // Prepare and execute query
    $stmt = $pdo->prepare('SELECT * FROM users WHERE user_name = ?');
    $stmt->execute([$user_name]);
    $user = $stmt->fetch();

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        // Password is correct
        $_SESSION['user'] = $user;
        header('Location: index.php'); // Redirect to a protected page
        exit;
    } else {
        // Invalid credentials
        $error = 'Invalid Username or Password';
    }
}
?>