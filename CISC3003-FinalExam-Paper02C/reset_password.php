<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/php/helpers.php';

$token = (string) ($_GET['token'] ?? $_POST['token'] ?? '');
$token_hash = $token !== '' ? hash('sha256', $token) : '';
$errors = [];
$success = false;
$valid_token = false;

if ($token_hash !== '') {
    require_once __DIR__ . '/connect.php';
    $stmt = $conn->prepare('SELECT id FROM users WHERE reset_token_hash = ? AND reset_expires_at > NOW() LIMIT 1');
    $stmt->bind_param('s', $token_hash);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $valid_token = (bool) $user;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid_token) {
    $password = (string) ($_POST['password'] ?? '');
    $confirm_password = (string) ($_POST['confirm_password'] ?? '');

    if (strlen($password) < 8) {
        $errors[] = 'Password must contain at least 8 characters.';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if ($errors === []) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare(
            'UPDATE users
             SET password_hash = ?, reset_token_hash = NULL, reset_expires_at = NULL
             WHERE id = ?'
        );
        $stmt->bind_param('si', $password_hash, $user['id']);
        $stmt->execute();
        $success = true;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario C - Reset Password</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="container">
    <section class="card narrow">
        <h1>Reset Password</h1>
        <?php if ($success): ?>
            <p class="success">Password updated. You can login with the new password.</p>
            <a class="button-link" href="login.php">Go to login</a>
        <?php elseif (!$valid_token): ?>
            <p class="error">Reset link is invalid or expired.</p>
        <?php else: ?>
            <?php if ($errors): ?><div class="error"><ul><?php foreach ($errors as $error): ?><li><?php echo h($error); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
            <form method="post">
                <input type="hidden" name="token" value="<?php echo h($token); ?>">
                <label>New password <input type="password" name="password" minlength="8" required></label>
                <label>Confirm password <input type="password" name="confirm_password" minlength="8" required></label>
                <button type="submit">Update Password</button>
            </form>
        <?php endif; ?>
    </section>
</main>
<?php render_footer(); ?>
</body>
</html>
