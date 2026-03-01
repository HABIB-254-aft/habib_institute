<?php
// ------------------------------
// APPLICATION FORM HANDLER
// Habib Institute of Languages & Technology
// ------------------------------

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: students.html');
    exit;
}

// 1. Collect form fields
$name       = trim($_POST['name'] ?? '');
$email      = trim($_POST['email'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$course     = trim($_POST['course'] ?? '');
$message    = trim($_POST['message'] ?? '');

// 2. Validate
$errors = [];

if ($name === '')   $errors[] = "Name is required.";
if ($email === '')  $errors[] = "Email is required.";
if ($phone === '')  $errors[] = "Phone number is required.";
if ($course === '') $errors[] = "Course selection is required.";

// Combined error message if needed
if (!empty($errors)) {
    $errorMsg = implode(" ", $errors);
}

// 3. Destination email
$to      = "info@habibinstitute.com";
$subject = "New Course Application – $name";

// 4. Email Body (Admin)
$body  = "A new student has applied through the website:\n\n";
$body .= "Name:  $name\n";
$body .= "Email: $email\n";
$body .= "Phone: $phone\n";
$body .= "Selected Course: $course\n\n";
$body .= "Additional Message:\n$message\n\n";
$body .= "-----------------------------------\n";
$body .= "Habib Institute of Languages & Technology\n";
$body .= "Website Application Form\n";

// 5. Headers
$headers  = "From: Habib Institute <info@habibinstitute.com>\r\n";
$headers .= "Reply-To: $email\r\n";

// ---- SAVE APPLICATION TO DATABASE ----
require_once __DIR__ . '/config/database.php';

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO applications (name, email, phone, course, message, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $course, $message, date("Y-m-d H:i:s")]);
} catch (PDOException $e) {
    error_log("Application save error: " . $e->getMessage());
}

// 6. Send email
$mailSent = false;

if (empty($errors)) {
    $mailSent = mail($to, $subject, $body, $headers);
}

// 7. Auto–reply to student
if ($mailSent) {
    $autoSubject = "Your Application Has Been Received – Habib Institute";
    $autoBody = "Hello $name,\n\n"
              . "Thank you for applying to the Habib Institute of Languages & Technology.\n"
              . "We have received your application for the following course:\n\n"
              . "Course: $course\n\n"
              . "Our admissions team will contact you shortly with next steps.\n"
              . "For quick support, WhatsApp us at: 0718 413 275.\n\n"
              . "Regards,\n"
              . "Habib Institute Admissions Office\n";

    $autoHeaders = "From: Habib Institute <info@habibinstitute.com>\r\n";

    @mail($email, $autoSubject, $autoBody, $autoHeaders);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Received – Habib Institute</title>
    <link rel="icon" type="image/png" href="./IMG/my logo.png">
    <link rel="apple-touch-icon" href="./IMG/my logo.png">
    <link rel="stylesheet" href="./CSS/style.css">
</head>

<body>

<!-- SUCCESS OR ERROR MESSAGE -->
<section class="page-header fade-in">
    <h1>
        <?php 
            if ($mailSent && empty($errors)) {
                echo "🎉 Application Submitted!";
            } else {
                echo "Application Failed";
            }
        ?>
    </h1>

    <p>
        <?php 
            if ($mailSent && empty($errors)) {
                echo "Thank you for applying! Our team will contact you shortly.";
            } else {
                echo "Sorry, your application could not be submitted. " . ($errorMsg ?? "Please try again.");
            }
        ?>
    </p>
</section>

<section class="success-section fade-in">
    <div class="success-box">
        <a href="index.html" class="success-link">Return to Homepage</a> |
        <a href="students.html" class="success-link">Back to Student Portal</a> |
        <a href="contact.html" class="success-link">Contact Support</a>
    </div>
</section>

</body>
</html>
