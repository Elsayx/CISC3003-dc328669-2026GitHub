<?php
declare(strict_types=1);
require_once __DIR__ . '/php/helpers.php';

$files = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__));
foreach ($iterator as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }

    $relative = str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $file->getPathname());
    $relative = str_replace(DIRECTORY_SEPARATOR, '/', $relative);
    if (str_starts_with($relative, 'vendor/') || $relative === 'php/smtp_private.php') {
        continue;
    }
    $files[] = $relative;
}
sort($files);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My List of PHP files for Scenario B</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="container">
    <section class="card">
        <h1>My List of PHP files for Scenario B</h1>
        <p>Use these download links for the forum topic named exactly: <strong>My List of PHP files for Scenario B</strong>.</p>
        <ul>
            <?php foreach ($files as $file): ?>
                <li><a href="<?php echo h($file); ?>" download><?php echo h($file); ?></a></li>
            <?php endforeach; ?>
        </ul>
        <a class="button-link" href="index.php">Back to Scenario B</a>
    </section>
</main>
<?php render_footer(); ?>
</body>
</html>
