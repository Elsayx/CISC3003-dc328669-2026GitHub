<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/php/helpers.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim((string) filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_SPECIAL_CHARS));
    $email = trim((string) filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
    $password = (string) ($_POST['password'] ?? '');

    if ($full_name === '') {
        $errors[] = 'Full name is required.';
    }
    if ($email === '') {
        $errors[] = 'A valid email is required.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must contain at least 8 characters.';
    }

    if ($errors === []) {
        require_once __DIR__ . '/connect.php';
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $full_name, $email, $password_hash);

        if ($stmt->execute()) {
            $_SESSION['flash'] = 'Registration successful. Please login.';
            header('Location: login.php');
            exit;
        }

        $errors[] = 'Registration failed. The email may already exist.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario A - Register</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="container">
    <section class="card narrow">
        <h1>Register</h1>
        <?php if ($errors): ?>
            <div class="error"><ul><?php foreach ($errors as $error): ?><li><?php echo h($error); ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <form method="post">
            <label>Full name <input type="text" name="full_name" required></label>
            <label>Email <input type="email" name="email" required></label>
            <label>Password <input type="password" name="password" minlength="8" required></label>
            <button type="submit">Create Account</button>
        </form>
        <p><a href="login.php">Already registered? Login</a></p>
    </section>
</main>
<?php render_footer(); ?>
</body>
</html>
