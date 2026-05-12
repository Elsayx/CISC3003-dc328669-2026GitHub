<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/php/helpers.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario C - Login and Registration System</title>
    <link rel="stylesheet" href="css/styles.css">
    <script defer src="js/script.js"></script>
</head>
<body>
<main class="container">
    <section class="auth-shell">
        <div class="intro">
            <p class="eyebrow">Scenario C</p>
            <h1>Signup, Login, Activation and Password Reset</h1>
            <p>This project demonstrates server-side validation, JavaScript validation, Ajax email checking, secure password reset and email activation.</p>
            <div class="toggle-row">
                <a class="button-link" href="setup_database.php">Setup Database</a>
                <a class="button-link" href="php_file_list.php">PHP File List</a>
                <button type="button" class="tab-button active" data-target="signupPanel">Sign Up</button>
                <button type="button" class="tab-button" data-target="signinPanel">Sign In</button>
            </div>
        </div>

        <section id="signupPanel" class="panel active">
            <h2>C.01 Signup Page</h2>
            <form id="signupForm" action="register.php" method="post" novalidate>
                <label>Full name <input type="text" name="full_name" id="signupName" minlength="2" maxlength="120" required></label>
                <label>Email <input type="email" name="email" id="signupEmail" maxlength="160" required></label>
                <p id="emailStatus" class="hint" aria-live="polite"></p>
                <label>Password <input type="password" name="password" id="signupPassword" minlength="8" required></label>
                <label>Confirm password <input type="password" name="confirm_password" id="confirmPassword" minlength="8" required></label>
                <button type="submit">Create Account</button>
                <p id="signupHint" class="hint" aria-live="polite"></p>
            </form>
        </section>

        <section id="signinPanel" class="panel">
            <h2>C.04 Login Page</h2>
            <form action="login.php" method="post">
                <label>Email <input type="email" name="email" required></label>
                <label>Password <input type="password" name="password" required></label>
                <button type="submit">Login</button>
            </form>
            <p><a href="forgot_password.php">Forgot password?</a></p>
        </section>
    </section>
</main>
<?php render_footer(); ?>
</body>
</html>
