<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/php/helpers.php';

$status = $_GET['status'] ?? '';
$debug = $_SESSION['contact_debug'] ?? '';
unset($_SESSION['contact_debug']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario B - Contact Form with PHPMailer</title>
    <link rel="stylesheet" href="css/styles.css">
    <script defer src="js/script.js"></script>
</head>
<body>
<main class="container">
    <section class="hero">
        <p class="eyebrow">Scenario B</p>
        <h1>Contact Form, PHPMailer and PRG Pattern</h1>
        <p>This page demonstrates client-side validation, PHPMailer configuration, mail debugging and Post/Redirect/Get.</p>
        <div class="actions">
            <a href="setup_database.php">Setup Database</a>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="php_file_list.php">PHP File List</a>
        </div>
    </section>

    <section class="card">
        <h2>B.01 Contact Form</h2>
        <?php if ($status === 'sent'): ?>
            <p class="success">Your message was saved and sent by PHPMailer.</p>
        <?php elseif ($status === 'debug'): ?>
            <div class="warning">
                <strong>Debug information:</strong>
                <p><?php echo h($debug); ?></p>
            </div>
        <?php elseif ($status === 'invalid'): ?>
            <p class="error">Please complete every field with valid information.</p>
        <?php endif; ?>

        <form id="contactForm" action="send.php" method="post" novalidate>
            <div class="grid">
                <label>
                    Full name
                    <input type="text" name="full_name" id="full_name" minlength="2" maxlength="120" required>
                </label>
                <label>
                    Email address
                    <input type="email" name="email" id="email" maxlength="160" required>
                </label>
            </div>
            <label>
                Subject
                <input type="text" name="subject" id="subject" minlength="3" maxlength="160" required>
            </label>
            <label>
                Message
                <textarea name="message" id="message" rows="7" minlength="10" required></textarea>
            </label>
            <button type="submit">Send Email</button>
            <p id="contactHint" class="hint" aria-live="polite"></p>
        </form>
    </section>
</main>
<?php render_footer(); ?>
</body>
</html>
