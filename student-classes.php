<?php
session_start();

if (!isset($_SESSION['student_logged_in'])) {
    header("Location: student-login.php");
    exit;
}

$course = $_SESSION['student_course'];

$storageDir = __DIR__ . "/storage/classes";
$file = $storageDir . "/" . strtolower(str_replace(" ", "_", $course)) . ".txt";

$classes = [];

if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        list($title, $zoom, $day, $time, $teacher) = explode("|", $line);
        $classes[] = [
            "title"  => $title,
            "zoom"   => $zoom,
            "day"    => $day,
            "time"   => $time,
            "teacher"=> $teacher
        ];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Online Classes – Habib Institute</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>

<section class="page-header fade-in">
    <h1>Online Classes</h1>
    <p>Your course: <strong><?= htmlspecialchars($course) ?></strong></p>
</section>

<section class="services-wrapper fade-in">
    <div class="services-container">
        <?php if (empty($classes)): ?>
            <p>No online classes have been added yet.</p>
        <?php else: ?>
            <?php foreach ($classes as $c): ?>
                <div class="service-card">
                    <h3><?= htmlspecialchars($c['title']) ?></h3>
                    <p><strong>Day:</strong> <?= htmlspecialchars($c['day']) ?></p>
                    <p><strong>Time:</strong> <?= htmlspecialchars($c['time']) ?></p>
                    <p><strong>Teacher:</strong> <?= htmlspecialchars($c['teacher']) ?></p>

                    <a class="hero-btn" href="<?= htmlspecialchars($c['zoom']) ?>" target="_blank">
                        Join Live Class
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
