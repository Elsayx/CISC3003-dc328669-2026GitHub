CREATE DATABASE IF NOT EXISTS cisc3003_finalexam_p2b
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cisc3003_finalexam_p2b;

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    subject VARCHAR(160) NOT NULL,
    message TEXT NOT NULL,
    mail_status VARCHAR(40) NOT NULL DEFAULT 'pending',
    debug_message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO contact_messages
    (full_name, email, subject, message, mail_status, debug_message)
VALUES
    ('Sample Contact', 'contact@example.com', 'PHPMailer test', 'This sample record demonstrates saving form data before sending email.', 'sample', 'Import successful.');
