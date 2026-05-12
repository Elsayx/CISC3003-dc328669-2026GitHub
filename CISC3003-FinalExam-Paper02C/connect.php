<?php
declare(strict_types=1);

$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'cisc3003_finalexam_p2c';

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die('Database connection failed: ' . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8'));
}

$conn->set_charset('utf8mb4');
