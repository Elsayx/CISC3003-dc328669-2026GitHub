<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function send_account_email(string $toEmail, string $toName, string $subject, string $body, string &$debug): bool
{
    $autoload = __DIR__ . '/../vendor/autoload.php';
    if (!file_exists($autoload)) {
        $debug = 'PHPMailer is not installed yet. Run composer install in this project folder. Local demo body: ' . $body;
        return false;
    }

    require_once $autoload;
    $config = require __DIR__ . '/smtp_config.php';

    if (str_contains($config['username'], 'example.com') || str_contains($config['password'], 'your_')) {
        $debug = 'SMTP config contains placeholder values. Update php/smtp_config.php. Local demo body: ' . $body;
        return false;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = $config['encryption'];
        $mail->Port = (int) $config['port'];

        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();

        $debug = 'Email sent successfully by PHPMailer.';
        return true;
    } catch (Exception $exception) {
        $debug = 'PHPMailer error: ' . $mail->ErrorInfo . ' Exception: ' . $exception->getMessage();
        return false;
    }
}
