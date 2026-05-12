CREATE DATABASE IF NOT EXISTS cisc3003_finalexam_p2c
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cisc3003_finalexam_p2c;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 0,
    activation_token_hash VARCHAR(64) NULL,
    activation_expires_at DATETIME NULL,
    reset_token_hash VARCHAR(64) NULL,
    reset_expires_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users
    (full_name, email, password_hash, is_active)
VALUES
    ('Activated Demo User', 'demo@example.com', '$2y$10$caWwG7K2JqULlOmmRd2JXepi8rEdghE/S5TFL2uGFovHr9V7Ef9c2', 1);
