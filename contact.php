<?php
// ------------------------------
// CONTACT FORM HANDLER
// Habib Institute of Languages & Technology
// ------------------------------

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.html'); 
    exit;
}

// 1. Collect form fields
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

// 2. Validate
$errors = [];

if ($name === '')   $errors[] = "Name is required.";
if ($email === '')  $errors[] = "Email is required.";
if ($message === '') $errors[] = "Message is required.";

// If errors exist, prepare message
if (!empty($errors)) {
    $errorMsg = implode(" ", $errors);
}

// ---- SAVE MESSAGE TO FILE ----
$savePath = __DIR__ . "/storage/messages.txt";

$entry = "----- MESSAGE -----\n"
       . "Name: $name\n"
       . "Email: $email\n"
       . "Phone: $phone\n"
       . "Message: $message\n"
       . "Date: " . date("Y-m-d H:i:s") . "\n"
       . "----------------------\n\n";

file_put_contents($savePath, $entry, FILE_APPEND);

// 3. Email destination
$to      = "info@habibinstitute.com";  // Your official email
$subject = "New Contact Form Message from $name";

// 4. Email Body
$body  = "You received a new message from your website contact form:\n\n";
$body .= "Name:  $name\n";
$body .= "Email: $email\n";
$body .= "Phone: $phone\n\n";
$body .= "Message:\n$message\n\n";
$body .= "-----------------------------------\n";
$body .= "Website: https://habibinstitute.com\n";

// 5. Headers
$headers  = "From: Habib Institute <info@habibinstitute.com>\r\n";
$headers .= "Reply-To: $email\r\n";

// 6. Send message
$mailSent = false;

if (empty($errors)) {
    $mailSent = mail($to, $subject, $body, $headers);
}

// 7. Auto-reply to sender
if ($mailSent) {
    $autoSubject = "We received your message – Habib Institute";
    $autoBody = "Hello $name,\n\n"
              . "Thank you for reaching out to Habib Institute of Languages & Technology.\n"
              . "We have received your message and our team will respond shortly.\n\n"
              . "For urgent inquiries, please call or WhatsApp us at: 0718 413 275.\n\n"
              . "Best regards,\n"
              . "Habib Institute Admissions Team\n";

    $autoHeaders = "From: Habib Institute <info@habibinstitute.com>\r\n";

    @mail($email, $autoSubject, $autoBody, $autoHeaders);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Message Received – Habib Institute</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>

<body>

<!-- SUCCESS OR ERROR MESSAGE -->
<section class="page-header fade-in">
    <h1>
        <?php 
            if ($mailSent && empty($errors)) {
                echo "Thank You!";
            } else {
                echo "Message Not Sent";
            }
        ?>
    </h1>

    <p>
        <?php 
            if ($mailSent && empty($errors)) {
                echo "Your message has been delivered successfully. We’ll get back to you soon.";
            } else {
                echo "Sorry, your message could not be sent. " . ($errorMsg ?? "Please try again later.");
            }
        ?>
    </p>
</section>

<section class="success-section fade-in">
    <div class="success-box">
        <a href="index.html" class="success-link">Back to Home</a> |
        <a href="contact.html" class="success-link">Try Again</a>
    </div>
</section>

</body>
</html>
