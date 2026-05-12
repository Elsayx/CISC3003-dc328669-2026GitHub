<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/php/helpers.php';

$message = 'Activation link is invalid or expired.';
$success = false;
$token = (string) ($_GET['token'] ?? '');

if ($token !== '') {
    require_once __DIR__ . '/connect.php';
    $token_hash = hash('sha256', $token);
    $stmt = $conn->prepare(
        'SELECT id FROM users
         WHERE activation_token_hash = ? AND activation_expires_at > NOW()
         LIMIT 1'
    );
    $stmt->bind_param('s', $token_hash);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        $stmt = $conn->prepare(
            'UPDATE users
             SET is_active = 1, activation_token_hash = NULL, activation_expires_at = NULL
             WHERE id = ?'
        );
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $success = true;
        $message = 'Your email has been confirmed. You can login now.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario C - Activate Account</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="container">
    <section class="card narrow">
        <h1>C.08 Email Confirmation</h1>
        <p class="<?php echo $success ? 'success' : 'error'; ?>"><?php echo h($message); ?></p>
        <a class="button-link" href="login.php">Go to Login</a>
    </section>
</main>
<?php render_footer(); ?>
</body>
</html>
