<?php
$result = null;
$details = null;

if (isset($_GET['student_id'])) {
    $inputId = trim($_GET['student_id']);

    $filePath = __DIR__ . "/storage/student_ids.txt";
    if (file_exists($filePath) && $inputId !== '') {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $parts = explode("|", $line);
            if (count($parts) >= 7) {
                list($id, $name, $course, $phone, $issue, $expiry, $savedAt) = $parts;
                if (strcasecmp(trim($id), $inputId) === 0) {
                    $result = "valid";
                    $details = [
                        "id" => $id,
                        "name" => $name,
                        "course" => $course,
                        "phone" => $phone,
                        "issue" => $issue,
                        "expiry" => $expiry,
                        "savedAt" => $savedAt
                    ];
                    break;
                }
            }
        }

        if ($result === null) {
            $result = "invalid";
        }
    } else {
        $result = "invalid";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Student ID – Habib Institute</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>

<header class="header animated-header">
    <div class="logo">
        <a href="index.html">
            <img src="./IMG/my logo.png" alt="Habib Institute Logo" class="site-logo">
        </a>
    </div>
    <nav class="nav-menu">
        <ul>
            <li><a href="index.html">HOME</a></li>
            <li><a href="about.html">ABOUT US</a></li>
            <li><a href="services.html">OUR SERVICES</a></li>
            <li><a href="programs.html">PROGRAMS</a></li>
            <li><a href="pricing.html">PRICING</a></li>
            <li><a href="contact.html">CONTACT</a></li>
            <li><a href="students.html">STUDENT PORTAL</a></li>
        </ul>
    </nav>
    <div class="menu-icon" id="menu-toggle">☰</div>
</header>

<div class="mobile-menu" id="mobile-menu">
    <a href="index.html">HOME</a>
    <a href="about.html">ABOUT US</a>
    <a href="services.html">OUR SERVICES</a>
    <a href="programs.html">PROGRAMS</a>
    <a href="pricing.html">PRICING</a>
    <a href="contact.html">CONTACT</a>
    <a href="students.html">STUDENT PORTAL</a>
</div>

<section class="page-header fade-in">
    <h1>Verify Student ID</h1>
    <p>Enter a Student ID to confirm if it is registered with Habib Institute.</p>
</section>

<section class="contact-section fade-in" style="text-align:center;">
    <form method="get" class="contact-form" style="max-width:400px; margin:0 auto;">
        <input type="text" name="student_id" placeholder="Enter Student ID (e.g. HAB-2025-1234)" required>
        <button type="submit" class="contact-btn">Verify ID</button>
    </form>

    <?php if ($result === "valid" && $details): ?>
        <div class="success-box" style="margin-top:25px;">
            <h3 style="color:green;">✅ Valid Student ID</h3>
            <p><strong>Student ID:</strong> <?= htmlspecialchars($details['id']) ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($details['name']) ?></p>
            <p><strong>Course:</strong> <?= htmlspecialchars($details['course']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($details['phone']) ?></p>
            <p><strong>Issue Date:</strong> <?= htmlspecialchars($details['issue']) ?></p>
            <p><strong>Expiry Date:</strong> <?= htmlspecialchars($details['expiry']) ?></p>
            <p><strong>Recorded On:</strong> <?= htmlspecialchars($details['savedAt']) ?></p>
        </div>
    <?php elseif ($result === "invalid"): ?>
        <div class="success-box" style="margin-top:25px;">
            <h3 style="color:red;">❌ ID Not Found</h3>
            <p>The ID you entered does not exist in our records.</p>
        </div>
    <?php endif; ?>
</section>

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-col">
            <h3>Habib Institute</h3>
            <p>Empowering learners through languages, technology and career development.</p>
        </div>

        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="services.html">Services</a></li>
                <li><a href="programs.html">Programs</a></li>
                <li><a href="pricing.html">Pricing</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="students.html">Student Portal</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Contact</h4>
            <p>Phone: <a href="tel:+254718413275" class="social-link">+254 718 413 275</a></p>
            <p>Email: <a href="mailto:info@habibinstitute.com" class="social-link">info@habibinstitute.com</a></p>
            <p>Nairobi, Kenya</p>
            <h4>Socials</h4>
            <a href="https://www.tiktok.com/@habibinstitute254" target="_blank" class="social-link">TikTok</a>
        </div>
    </div>

    <div class="footer-bottom">
        © 2025 Habib Institute of Languages & Technology. All rights reserved.
    </div>
</footer>

<a class="whatsapp-float"
   href="https://wa.me/254718413275?text=Hello,+I+want+to+verify+a+student+ID."
   target="_blank">
    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg"
         class="whatsapp-icon">
</a>

</body>
</html>
