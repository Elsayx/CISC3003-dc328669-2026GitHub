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
    <title>Scenario C - User Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
<main class="container dashboard">
    <section class="card">
        <p class="eyebrow">C.09 User Dashboard</p>
        <h1>Welcome, <?php echo h($_SESSION['full_name'] ?? 'Student'); ?></h1>
        <p>Email: <?php echo h($_SESSION['email'] ?? ''); ?></p>
        <p>You became a user on <?php echo h($_SESSION['created_at'] ?? date('Y-m-d')); ?>.</p>

        <div class="service-grid">
            <article><h2>Profile Service</h2><p>View your account email and registration date.</p></article>
            <article><h2>Security Service</h2><p>Use password reset by email when needed.</p></article>
            <article><h2>Activation Service</h2><p>Only activated users can access this dashboard.</p></article>
            <article><h2>Ajax Service</h2><p>Signup checks email availability before submission.</p></article>
        </div>

        <a class="button-link" href="forgot_password.php">Reset Password</a>
        <a class="button-link secondary" href="logout.php">Logout</a>
    </section>
</main>
<?php render_footer(); ?>
</body>
</html>
