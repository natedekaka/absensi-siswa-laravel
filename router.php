<?php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$publicPath = __DIR__ . '/public' . $uri;

if ($uri !== '/' && file_exists($publicPath)) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $publicPath);
    finfo_close($finfo);
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($publicPath));
    readfile($publicPath);
    return;
}

require_once __DIR__.'/public/index.php';
