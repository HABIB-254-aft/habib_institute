<?php
require_once __DIR__ . '/config/database.php';

$token  = $_GET['token'] ?? '';
$step   = 'request'; // request | form | done
$error  = '';
$emailForToken = '';

if ($token) {
    // Look up token in database
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);
        $reset = $stmt->fetch();
        
        if ($reset) {
            if (time() <= (int)$reset['expires_at']) {
                $emailForToken = trim($reset['email']);
                $step = 'form';
            } else {
                $error = "This reset link has expired.";
            }
        } else {
            $error = "Invalid or unknown reset link.";
        }
    } catch (PDOException $e) {
        $error = "Error processing reset link.";
        error_log("Password reset lookup error: " . $e->getMessage());
    }
} else {
    $error = "Missing reset token.";
}

// Handle form submission for new password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token  = $_POST['token'] ?? '';
    $pass1  = $_POST['password'] ?? '';
    $pass2  = $_POST['password_confirm'] ?? '';

    if ($pass1 === '' || $pass2 === '') {
        $error = "Please fill both password fields.";
        $step = 'form';
    } elseif ($pass1 !== $pass2) {
        $error = "Passwords do not match.";
        $step = 'form';
    } else {
        // Re-validate token and get email
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
            $stmt->execute([$token]);
            $reset = $stmt->fetch();
            
            if ($reset && time() <= (int)$reset['expires_at']) {
                $emailForToken = trim($reset['email']);
                
                // Update password in database
                $hash = password_hash($pass1, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE students SET password_hash = ? WHERE email = ?");
                $stmt->execute([$hash, $emailForToken]);
                
                // Delete used token
                $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
                $stmt->execute([$token]);
                
                $step = 'done';
            } else {
                $error = "This reset link is invalid or expired.";
                $step  = 'request';
            }
        } catch (PDOException $e) {
            $error = "Error updating password.";
            error_log("Password reset update error: " . $e->getMessage());
            $step = 'request';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password – Habib Institute</title>
    <link rel="icon" type="image/png" href="./IMG/my logo.png">
    <link rel="apple-touch-icon" href="./IMG/my logo.png">
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>

<section class="page-header fade-in">
    <h1>Reset Password</h1>
    <p>Set a new password for your student account.</p>
</section>

<section class="contact-section fade-in" style="max-width:420px; margin:auto;">

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($step === 'done'): ?>
        <p style="color:green;">Your password has been updated successfully.</p>
        <p><a href="student-login.php">Click here to login</a></p>

    <?php elseif ($step === 'form' && $emailForToken): ?>
        <p>Resetting password for: <strong><?= htmlspecialchars($emailForToken) ?></strong></p>

        <form method="post" class="contact-form">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <label>New Password</label>
            <input type="password" name="password" required>

            <label>Confirm New Password</label>
            <input type="password" name="password_confirm" required>

            <button type="submit" class="contact-btn">Update Password</button>
        </form>

    <?php else: ?>
        <p>Invalid or expired reset link. Please request a new one.</p>
        <p><a href="forgot-password.php">Request Reset Again</a></p>
    <?php endif; ?>

</section>

</body>
</html>
