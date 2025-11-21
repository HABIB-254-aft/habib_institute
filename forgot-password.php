<?php
$status = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password – Habib Institute</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>

<section class="page-header fade-in">
    <h1>Forgot Password</h1>
    <p>Enter your registered email and we will send a reset link if it exists in our system.</p>
</section>

<section class="contact-section fade-in" style="max-width:420px; margin:auto;">
    <?php if ($status === 'sent'): ?>
        <p style="color:green;">
            If this email is registered, a reset link has been sent.
            Please check your inbox (and spam folder).
        </p>
    <?php elseif ($status === 'error'): ?>
        <p style="color:red;">
            Something went wrong while sending the reset email. Please try again later.
        </p>
    <?php endif; ?>

    <form action="forgot-password-send.php" method="post" class="contact-form">
        <label>Email Address</label>
        <input type="email" name="email" required>

        <button type="submit" class="contact-btn">Send Reset Link</button>
    </form>

    <div style="margin-top:14px; text-align:center;">
        <a href="student-login.php">Back to Login</a>
    </div>
</section>

</body>
</html>
