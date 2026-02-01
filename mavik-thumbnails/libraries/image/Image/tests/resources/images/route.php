<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$file = __DIR__ . $path;
if ($path !== '/' && file_exists($file)) {
    if (!isset($_SERVER['HTTP_RANGE'])) {
        return false;
    }
    $size = filesize($file);
    $fp = fopen($file, 'rb');
    preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
    $start = intval($matches[1]);
    $end = isset($matches[2]) ? intval($matches[2]) : $size - 1;
    if ($start >= $size || $end >= $size || $start > $end) {
        header("HTTP/1.1 416 Range Not Satisfiable");
        header("Content-Range: bytes */$size");
        exit;
    }
    $length = $end - $start + 1;

    header("HTTP/1.1 206 Partial Content");
    header("Content-Type: " . mime_content_type($file));
    header("Content-Range: bytes $start-$end/$size");
    header("Content-Length: $length");
    header("Accept-Ranges: bytes");

    fseek($fp, $start);
    echo fread($fp, $length);
    fclose($fp);
    exit;
}
if ($path !== '/' && !file_exists($file)) {
    http_response_code(404);
    echo "404 - Page not found";
    exit;
}
require_once 'index.html';