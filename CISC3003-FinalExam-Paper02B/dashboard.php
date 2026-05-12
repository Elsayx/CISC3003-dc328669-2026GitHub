<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/php/helpers.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario B - Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
<main class="container dashboard">
    <section class="card">
        <h1>Welcome, <?php echo h($_SESSION['full_name'] ?? 'Student'); ?></h1>
        <p>You became a user on <?php echo h($_SESSION['created_at'] ?? date('Y-m-d')); ?>.</p>
        <div class="service-grid">
            <article><h2>Contact Form</h2><p>Send an email through PHPMailer.</p></article>
            <article><h2>Debug Panel</h2><p>Show SMTP or installation problems after redirect.</p></article>
            <article><h2>Message Storage</h2><p>Save every contact request in MySQL.</p></article>
        </div>
        <a class="button-link" href="index.php">Open contact form</a>
        <a class="button-link secondary" href="logout.php">Logout</a>
    </section>
</main>
<?php render_footer(); ?>
</body>
</html>
