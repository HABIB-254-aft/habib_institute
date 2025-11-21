<?php
session_start();
require_once "admin_config.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $ADMIN_USERNAME && password_verify($password, $ADMIN_PASSWORD_HASH)) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login – Habib Institute</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>

<section class="page-header fade-in">
    <h1>Admin Login</h1>
    <p>Secure administrator access</p>
</section>

<section class="contact-section fade-in" style="max-width:380px; margin:auto;">
    <form method="post" class="contact-form">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <?php if ($error): ?>
            <p style="color:red; font-size:14px;"><?= $error ?></p>
        <?php endif; ?>

        <button type="submit" class="contact-btn">Login</button>
    </form>
</section>

</body>
</html>
