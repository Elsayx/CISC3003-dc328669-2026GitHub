<?php
declare(strict_types=1);

const STUDENT_NAME = 'Yang Xu';
const STUDENT_ID = 'dc328669';
const FOOTER_TEXT = 'CISC3003 Web Programming: ' . STUDENT_NAME . ' + ' . STUDENT_ID . ' + 2026';

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function render_footer(): void
{
    echo '<footer><p>' . h(FOOTER_TEXT) . '</p></footer>';
}

function app_url(string $path): string
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') === '443');
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    return $scheme . '://' . $host . $base . '/' . ltrim($path, '/');
}
