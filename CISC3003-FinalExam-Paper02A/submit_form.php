<?php
declare(strict_types=1);

require_once __DIR__ . '/php/helpers.php';

$errors = [];
$saved = false;
$submitted = [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$full_name = trim((string) filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_SPECIAL_CHARS));
$email = trim((string) filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
$phone = trim((string) filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS));
$course = trim((string) filter_input(INPUT_POST, 'course', FILTER_SANITIZE_SPECIAL_CHARS));
$study_year = trim((string) filter_input(INPUT_POST, 'study_year', FILTER_SANITIZE_SPECIAL_CHARS));
$message = trim((string) filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS));
$agree = filter_input(INPUT_POST, 'agree', FILTER_SANITIZE_SPECIAL_CHARS);
$interests = filter_input(INPUT_POST, 'interests', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?: [];
$allowed_interests = ['HTML', 'CSS', 'PHP', 'MySQL'];
$clean_interests = array_values(array_intersect($allowed_interests, array_map('strval', $interests)));

if ($full_name === '' || strlen($full_name) < 2) {
    $errors[] = 'Full name is required.';
}
if ($email === '') {
    $errors[] = 'A valid email address is required.';
}
if ($phone === '') {
    $errors[] = 'Phone number is required.';
}
if (!in_array($course, ['CISC3003', 'CISC3004', 'COMP'], true)) {
    $errors[] = 'Please choose a valid course.';
}
if (!in_array($study_year, ['Year 1', 'Year 2', 'Year 3', 'Year 4'], true)) {
    $errors[] = 'Please choose a valid study year.';
}
if ($clean_interests === []) {
    $errors[] = 'Please choose at least one interest.';
}
if (strlen($message) < 10) {
    $errors[] = 'Message must contain at least 10 characters.';
}
if ($agree !== 'yes') {
    $errors[] = 'You must confirm the declaration.';
}

if ($errors === []) {
    require_once __DIR__ . '/connect.php';
    $interest_text = implode(',', $clean_interests);

    $stmt = $conn->prepare(
        'INSERT INTO form_submissions (full_name, email, phone, course, study_year, interests, message)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->bind_param('sssssss', $full_name, $email, $phone, $course, $study_year, $interest_text, $message);
    $saved = $stmt->execute();

    if (!$saved) {
        $errors[] = 'Database insert failed: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

$submitted = compact('full_name', 'email', 'phone', 'course', 'study_year', 'message');
$submitted['interests'] = implode(', ', $clean_interests);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario A - Submitted Form Data</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="container">
    <section class="card">
        <h1>A.05-A.08 PHP Processing Result</h1>
        <?php if ($saved): ?>
            <div class="success">
                <h2>Form data saved successfully.</h2>
                <p>The PHP script validated the input using filter functions and inserted the data with a prepared statement.</p>
            </div>
            <dl class="summary">
                <?php foreach ($submitted as $label => $value): ?>
                    <dt><?php echo h(ucwords(str_replace('_', ' ', $label))); ?></dt>
                    <dd><?php echo h($value); ?></dd>
                <?php endforeach; ?>
            </dl>
        <?php else: ?>
            <div class="error">
                <h2>Please correct the following errors.</h2>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo h($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <a class="button-link" href="index.php">Back to Scenario A form</a>
    </section>
</main>
<?php render_footer(); ?>
</body>
</html>
