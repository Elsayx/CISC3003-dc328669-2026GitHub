<?php
declare(strict_types=1);

$private_config = __DIR__ . '/smtp_private.php';
if (file_exists($private_config)) {
    return require $private_config;
}

return [
    'host' => 'smtp.gmail.com',
    'username' => 'your_um_email_or_gmail@example.com',
    'password' => 'your_gmail_app_password',
    'port' => 587,
    'encryption' => 'tls',
    'from_email' => 'your_um_email_or_gmail@example.com',
    'from_name' => 'CISC3003 Scenario B Contact Form',
    'to_email' => 'your_um_email_or_gmail@example.com',
    'to_name' => 'Yang Xu'
];
