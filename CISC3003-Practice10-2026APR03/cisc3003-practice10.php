<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>dc328023 Jiang Xingyu PHP</title>
</head>
<body>
    <h1>dc328023 Jiang Xingyu PHP</h1>

    <p>
        <?php
        $name = 'Jiang Xingyu';
        $hash = hash('sha256', $name);
        echo 'The SHA256 hash of "' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" is ' . $hash;
        ?>
    </p>

    <p>ASCII ART:</p>
    <pre>
    ************
    **        **
    **
    **
    **        **
    ************
    </pre>

    <p><a href="check.php">Click here to check the error setting</a></p>
    <p><a href="fail.php">Click here to cause a traceback</a></p>
</body>
</html>

