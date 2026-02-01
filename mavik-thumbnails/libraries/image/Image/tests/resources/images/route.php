<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$file = __DIR__ . $path;
if ($path !== '/' && file_exists($file)) {
    return false;
}
if ($path !== '/' && !file_exists($file)) {
    http_response_code(404);
    echo "404 - Page not found";
    exit;
}
require_once 'index.html';