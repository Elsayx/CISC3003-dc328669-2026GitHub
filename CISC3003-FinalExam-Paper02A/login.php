<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/php/helpers.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
    $password = (string) ($_POST['password'] ?? '');

    if ($email !== '' && $password !== '') {
        require_once __DIR__ . '/connect.php';
        $stmt = $conn->prepare('SELECT id, full_name, password_hash, created_at FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['created_at'] = $user['created_at'];
            header('Location: dashboard.php');
            exit;
        }
    }

    $error = 'Invalid email or password.';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario A - Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="container">
    <section class="card narrow">
        <h1>Login</h1>
        <?php if (!empty($_SESSION['flash'])): ?>
            <p class="success"><?php echo h($_SESSION['flash']); unset($_SESSION['flash']); ?></p>
        <?php endif; ?>
        <?php if ($error): ?><p class="error"><?php echo h($error); ?></p><?php endif; ?>
        <form method="post">
            <label>Email <input type="email" name="email" required></label>
            <label>Password <input type="password" name="password" required></label>
            <button type="submit">Login</button>
        </form>
        <p><a href="register.php">Create an account</a></p>
    </section>
</main>
<?php render_footer(); ?>
</body>
</html>
