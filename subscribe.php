<?php
// ------------------------------
// NEWSLETTER SUBSCRIPTION HANDLER
// Habib Institute of Languages & Technology
// ------------------------------

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

// 1. Collect form data
$email = trim($_POST['email'] ?? '');

// 2. Validate
$errors = [];

if ($email === '') {
    $errors[] = "Email is required.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
}

if (!empty($errors)) {
    $errorMsg = implode(" ", $errors);
}

// ---- SAVE SUBSCRIBER TO FILE ----
$savePath = __DIR__ . "/storage/subscribers.txt";

$entry = "Email: $email | Date: " . date("Y-m-d H:i:s") . "\n";

file_put_contents($savePath, $entry, FILE_APPEND);

// 3. Destination email
$to = "info@habibinstitute.com";
$subject = "New Newsletter Subscription";

// 4. Email body for admin
$body  = "A new user has subscribed to the newsletter:\n\n";
$body .= "Email: $email\n\n";
$body .= "-----------------------------------\n";
$body .= "Habib Institute Newsletter\n";

// 5. Headers
$headers  = "From: Habib Institute <info@habibinstitute.com>\r\n";
$headers .= "Reply-To: $email\r\n";

// 6. Send email to admin
$mailSent = false;

if (empty($errors)) {
    $mailSent = mail($to, $subject, $body, $headers);
}

// 7. Auto-reply to subscriber
if ($mailSent) {
    $autoSubject = "You're Subscribed – Habib Institute";
    $autoBody = "Hello,\n\n"
              . "Thank you for subscribing to the Habib Institute newsletter!\n"
              . "We'll keep you updated on new courses, offers, events, and learning materials.\n\n"
              . "For immediate support, WhatsApp us at: 0718 413 275.\n\n"
              . "Warm regards,\n"
              . "Habib Institute Team";

    $autoHeaders = "From: Habib Institute <info@habibinstitute.com>\r\n";

    @mail($email, $autoSubject, $autoBody, $autoHeaders);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscribed – Habib Institute</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>

<body>

<!-- SUCCESS / ERROR -->
<section class="page-header fade-in">
    <h1>
        <?php 
            if ($mailSent && empty($errors)) {
                echo "🎉 Subscription Successful!";
            } else {
                echo "Subscription Failed";
            }
        ?>
    </h1>

    <p>
        <?php 
            if ($mailSent && empty($errors)) {
                echo "Thank you for subscribing! You will now receive updates and announcements.";
            } else {
                echo "Sorry, your subscription could not be processed. " . ($errorMsg ?? "Please try again.");
            }
        ?>
    </p>
</section>

<section class="success-section fade-in">
    <div class="success-box">
        <a href="index.html" class="success-link">Back to Homepage</a> |
        <a href="students.html" class="success-link">Go to Student Portal</a> |
        <a href="contact.html" class="success-link">Contact Support</a>
    </div>
</section>

</body>
</html>
