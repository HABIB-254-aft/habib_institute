<?php
$storageDir  = __DIR__ . "/storage";
$resetsFile  = $storageDir . "/password_resets.txt";
$studentsFile = $storageDir . "/students.txt";

$token  = $_GET['token'] ?? '';
$step   = 'request'; // request | form | done
$error  = '';
$emailForToken = '';

if ($token) {
    // Look up token
    if (file_exists($resetsFile)) {
        $lines = file($resetsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($savedToken, $savedEmail, $savedExp) = explode("|", $line);
            if (hash_equals($savedToken, $token)) {
                if (time() <= (int)$savedExp) {
                    $emailForToken = trim($savedEmail);
                    $step = 'form';
                } else {
                    $error = "This reset link has expired.";
                }
                break;
            }
        }
        if ($emailForToken === '' && $error === '') {
            $error = "Invalid or unknown reset link.";
        }
    } else {
        $error = "No reset requests found.";
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
        $emailForToken = '';
        $lines = file($resetsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $remainingTokens = [];
        foreach ($lines as $line) {
            list($savedToken, $savedEmail, $savedExp) = explode("|", $line);
            if (hash_equals($savedToken, $token) && time() <= (int)$savedExp) {
                $emailForToken = trim($savedEmail);
                // Do NOT keep this token (used)
            } else {
                $remainingTokens[] = $line;
            }
        }

        if ($emailForToken === '') {
            $error = "This reset link is invalid or expired.";
            $step  = 'request';
        } else {
            // Update students.txt
            if (file_exists($studentsFile)) {
                $students = file($studentsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $newStudents = [];
                $hash = password_hash($pass1, PASSWORD_BCRYPT);

                foreach ($students as $stuLine) {
                    $parts = explode("|", $stuLine);
                    if (count($parts) >= 7) {
                        list($sid, $sname, $semail, $sphone, $scourse, $spass, $createdAt) = $parts;
                        if (strcasecmp(trim($semail), $emailForToken) === 0) {
                            // Replace password hash
                            $stuLine = $sid . "|" . $sname . "|" . $semail . "|" . $sphone . "|" . $scourse . "|" . $hash . "|" . $createdAt;
                        }
                    }
                    $newStudents[] = $stuLine;
                }

                file_put_contents($studentsFile, implode("\n", $newStudents) . "\n");
                // Save remaining tokens (used one removed)
                file_put_contents($resetsFile, implode("\n", $remainingTokens) . "\n");

                $step = 'done';
            } else {
                $error = "No students file found.";
                $step  = 'request';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password – Habib Institute</title>
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
