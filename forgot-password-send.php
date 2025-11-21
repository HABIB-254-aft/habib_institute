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

// Paths
$storageDir = __DIR__ . "/storage";
if (!is_dir($storageDir)) mkdir($storageDir, 0755, true);

$studentsFile = $storageDir . "/students.txt";
$resetsFile   = $storageDir . "/password_resets.txt";

// Check if email exists in students file
$emailExists = false;
if (file_exists($studentsFile)) {
    $lines = file($studentsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = explode("|", $line);
        if (count($parts) >= 3) {
            $semail = $parts[2];
            if (strcasecmp(trim($semail), $email) === 0) {
                $emailExists = true;
                break;
            }
        }
    }
}

// For security: even if email not found, we behave the same externally
if ($emailExists) {
    // Generate token & expiry
    $token   = bin2hex(random_bytes(16));
    $expires = time() + 3600; // 1 hour from now

    // Save token | email | expiryTimestamp
    $line = $token . "|" . $email . "|" . $expires . "\n";
    file_put_contents($resetsFile, $line, FILE_APPEND);

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
