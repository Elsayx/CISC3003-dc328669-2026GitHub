<?php
declare(strict_types=1);
require_once __DIR__ . '/php/helpers.php';

$messages = [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql_file = __DIR__ . '/db/database.sql';
    $sql = file_get_contents($sql_file);

    $conn = new mysqli('localhost', 'root', '');
    if ($conn->connect_error) {
        $error = 'Database connection failed: ' . $conn->connect_error;
    } elseif ($sql === false) {
        $error = 'Cannot read db/database.sql.';
    } elseif ($conn->multi_query($sql)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());

        if ($conn->errno) {
            $error = 'SQL error: ' . $conn->error;
        } else {
            $messages[] = 'Scenario A database and tables were created successfully.';
        }
    } else {
        $error = 'SQL import failed: ' . $conn->error;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario A - Setup Database</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="container">
    <section class="card narrow">
        <h1>A.09 Database Setup</h1>
        <p>This page runs <code>db/database.sql</code> to create the Scenario A database and tables in MySQL.</p>
        <?php if ($error): ?><p class="error"><?php echo h($error); ?></p><?php endif; ?>
        <?php foreach ($messages as $message): ?><p class="success"><?php echo h($message); ?></p><?php endforeach; ?>
        <form method="post">
            <button type="submit">Create Scenario A Database</button>
        </form>
        <p><a href="index.php">Back to Scenario A</a></p>
    </section>
</main>
<?php render_footer(); ?>
</body>
</html>
