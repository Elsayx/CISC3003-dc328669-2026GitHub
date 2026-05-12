<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/php/helpers.php';
require_once __DIR__ . '/php/mailer.php';

$notice = '';
$debug = '';
$reset_link = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
    $notice = 'If this email exists, a secure reset link has been generated.';

    if ($email !== '') {
        require_once __DIR__ . '/connect.php';
        $stmt = $conn->prepare('SELECT id, full_name FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $token_hash = hash('sha256', $token);
            $stmt = $conn->prepare(
                'UPDATE users
                 SET reset_token_hash = ?, reset_expires_at = DATE_ADD(NOW(), INTERVAL 1 HOUR)
                 WHERE id = ?'
            );
            $stmt->bind_param('si', $token_hash, $user['id']);
            $stmt->execute();

            $reset_link = app_url('reset_password.php?token=' . urlencode($token));
            $body = "Hello {$user['full_name']},\n\nReset your password here:\n{$reset_link}\n\nThis link expires in 1 hour.";
            send_account_email($email, $user['full_name'], 'Reset your CISC3003 password', $body, $debug);
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario C - Forgot Password</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="container">
    <section class="card narrow">
        <h1>C.07 Secure Password Reset</h1>
        <?php if ($notice): ?>
            <div class="success">
                <p><?php echo h($notice); ?></p>
                <?php if ($reset_link): ?><p><strong>Local demo reset link:</strong> <a href="<?php echo h($reset_link); ?>"><?php echo h($reset_link); ?></a></p><?php endif; ?>
                <?php if ($debug): ?><p><?php echo h($debug); ?></p><?php endif; ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <label>Email address <input type="email" name="email" required></label>
            <button type="submit">Send Reset Link</button>
        </form>
        <p><a href="login.php">Back to login</a></p>
    </section>
</main>
<?php render_footer(); ?>
</body>
</html>
