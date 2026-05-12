CREATE DATABASE IF NOT EXISTS cisc3003_finalexam_p2a
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cisc3003_finalexam_p2a;

CREATE TABLE IF NOT EXISTS form_submissions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    phone VARCHAR(40) NOT NULL,
    course VARCHAR(80) NOT NULL,
    study_year VARCHAR(40) NOT NULL,
    interests VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO form_submissions
    (full_name, email, phone, course, study_year, interests, message)
VALUES
    ('Sample Student', 'student@example.com', '853-12345678', 'CISC3003', 'Year 3', 'PHP,MySQL', 'This record demonstrates an SQL INSERT INTO statement.');
