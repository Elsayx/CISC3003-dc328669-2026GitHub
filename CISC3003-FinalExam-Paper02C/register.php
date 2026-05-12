<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/php/helpers.php';
require_once __DIR__ . '/php/mailer.php';

$errors = [];
$created = false;
$activation_link = '';
$debug = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim((string) filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_SPECIAL_CHARS));
    $email = trim((string) filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
    $password = (string) ($_POST['password'] ?? '');
    $confirm_password = (string) ($_POST['confirm_password'] ?? '');

    if ($full_name === '' || strlen($full_name) < 2) {
        $errors[] = 'Full name must contain at least 2 characters.';
    }
    if ($email === '') {
        $errors[] = 'A valid email is required.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must contain at least 8 characters.';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if ($errors === []) {
        require_once __DIR__ . '/connect.php';
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            $errors[] = 'This email is already registered.';
        }
    }

    if ($errors === []) {
        $token = bin2hex(random_bytes(32));
        $token_hash = hash('sha256', $token);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            'INSERT INTO users (full_name, email, password_hash, activation_token_hash, activation_expires_at)
             VALUES (?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 1 DAY))'
        );
        $stmt->bind_param('ssss', $full_name, $email, $password_hash, $token_hash);
        $created = $stmt->execute();

        if ($created) {
            $activation_link = app_url('activate.php?token=' . urlencode($token));
            $body = "Hello {$full_name},\n\nPlease activate your CISC3003 Scenario C account:\n{$activation_link}\n\nThis link expires in 24 hours.";
            send_account_email($email, $full_name, 'Activate your CISC3003 account', $body, $debug);
        } else {
            $errors[] = 'Account creation failed. Please try again.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario C - Register</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="container">
    <section class="card narrow">
        <h1>C.02-C.03 Signup Result</h1>
        <?php if ($created): ?>
            <div class="success">
                <p>Account created. Please confirm your email before login.</p>
                <p><strong>Local demo activation link:</strong> <a href="<?php echo h($activation_link); ?>"><?php echo h($activation_link); ?></a></p>
                <p><?php echo h($debug); ?></p>
            </div>
            <p><a class="button-link" href="login.php">Go to login</a></p>
        <?php else: ?>
            <div class="error"><ul><?php foreach ($errors as $error): ?><li><?php echo h($error); ?></li><?php endforeach; ?></ul></div>
            <p><a href="index.php">Back to signup</a></p>
        <?php endif; ?>
    </section>
</main>
<?php render_footer(); ?>
</body>
</html>
