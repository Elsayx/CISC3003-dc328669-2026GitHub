<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function send_contact_email(string $replyTo, string $name, string $subject, string $message, string &$debug): bool
{
    $autoload = __DIR__ . '/../vendor/autoload.php';
    if (!file_exists($autoload)) {
        $debug = 'PHPMailer is not installed yet. Run composer install in this project folder.';
        return false;
    }

    require_once $autoload;
    $config = require __DIR__ . '/smtp_config.php';

    if (str_contains($config['username'], 'example.com') || str_contains($config['password'], 'your_')) {
        $debug = 'SMTP config still contains placeholder values. Update php/smtp_config.php with a Gmail app password before sending.';
        return false;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = $config['encryption'];
        $mail->Port = (int) $config['port'];

        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($config['to_email'], $config['to_name']);
        $mail->addReplyTo($replyTo, $name);
        $mail->isHTML(false);
        $mail->Subject = '[Scenario B] ' . $subject;
        $mail->Body = "Sender: {$name} <{$replyTo}>\n\n{$message}";

        $mail->send();
        $debug = 'Message sent successfully by PHPMailer.';
        return true;
    } catch (Exception $exception) {
        $debug = 'PHPMailer error: ' . $mail->ErrorInfo . ' Exception: ' . $exception->getMessage();
        return false;
    }
}
