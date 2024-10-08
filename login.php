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
    <form action="helper/auth.php" method="POST">
        <label for="user_name">User:</label>
        <input type="text" name="user_name" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
    </div>
</body>
</html>