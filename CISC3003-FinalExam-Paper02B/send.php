<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/php/helpers.php';
require_once __DIR__ . '/php/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$full_name = trim((string) filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_SPECIAL_CHARS));
$email = trim((string) filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
$subject = trim((string) filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS));
$message = trim((string) filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS));

if ($full_name === '' || $email === '' || strlen($subject) < 3 || strlen($message) < 10) {
    header('Location: index.php?status=invalid');
    exit;
}

$debug = '';
$sent = send_contact_email($email, $full_name, $subject, $message, $debug);
$mail_status = $sent ? 'sent' : 'debug';

require_once __DIR__ . '/connect.php';
$stmt = $conn->prepare(
    'INSERT INTO contact_messages (full_name, email, subject, message, mail_status, debug_message)
     VALUES (?, ?, ?, ?, ?, ?)'
);
$stmt->bind_param('ssssss', $full_name, $email, $subject, $message, $mail_status, $debug);
$stmt->execute();
$stmt->close();
$conn->close();

$_SESSION['contact_debug'] = $debug;
header('Location: index.php?status=' . ($sent ? 'sent' : 'debug'));
exit;
