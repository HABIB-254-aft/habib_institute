<?php
// Handle forgot password form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: forgot-password.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
if ($email === '') {
    header("Location: forgot-password.php?status=sent");
    exit;
}

require_once __DIR__ . '/config/database.php';

// Check if email exists in database
$emailExists = false;
try {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
    $stmt->execute([$email]);
    $emailExists = $stmt->fetch() !== false;
} catch (PDOException $e) {
    error_log("Password reset check error: " . $e->getMessage());
}

// For security: even if email not found, we behave the same externally
if ($emailExists) {
    // Generate token & expiry
    $token   = bin2hex(random_bytes(16));
    $expires = time() + 3600; // 1 hour from now

    // Save token to database
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("INSERT INTO password_resets (token, email, expires_at, created_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([$token, $email, $expires, date("Y-m-d H:i:s")]);
    } catch (PDOException $e) {
        error_log("Password reset save error: " . $e->getMessage());
    }

    // Build reset link
    $resetLink = "https://" . $_SERVER['HTTP_HOST'] . "/reset-password.php?token=" . urlencode($token);

    // Send email
    $to      = $email;
    $subject = "Password Reset – Habib Institute";
    $body    = "Hello,\n\n"
             . "We received a request to reset your Habib Institute student account password.\n"
             . "If this was you, click the link below to reset your password:\n\n"
             . $resetLink . "\n\n"
             . "This link will expire in 1 hour.\n\n"
             . "If you did not request this, you can ignore this email.\n\n"
             . "Habib Institute of Languages & Technology\n";

    $headers = "From: Habib Institute <info@habibinstitute.com>\r\n";

    @mail($to, $subject, $body, $headers);
}

// Always redirect to same status for security
header("Location: forgot-password.php?status=sent");
exit;
