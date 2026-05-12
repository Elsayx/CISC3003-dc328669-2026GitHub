<?php
declare(strict_types=1);

header('Content-Type: application/json');

$email = trim((string) filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL));
if ($email === '') {
    echo json_encode(['valid' => false, 'available' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

require_once __DIR__ . '/connect.php';
$stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$exists = (bool) $stmt->get_result()->fetch_assoc();

echo json_encode([
    'valid' => true,
    'available' => !$exists,
    'message' => $exists ? 'This email is already registered.' : 'This email is available.'
]);
