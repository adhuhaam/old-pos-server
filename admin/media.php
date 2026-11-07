<?php

require __DIR__ . '/_init.php';
require_owner_page();

$file = '';
$mime = 'application/octet-stream';
if (isset($_GET['bank'])) {
    $file = bank_image_file();
    $mime = 'image/jpeg';
} elseif (isset($_GET['item'])) {
    $file = item_photo_file((int) $_GET['item']);
    $mime = 'image/jpeg';
} elseif (isset($_GET['slip'])) {
    $file = slip_file((int) $_GET['slip']);
    $mime = 'image/jpeg';
} elseif (isset($_GET['proof'])) {
    $file = proof_file((int) $_GET['proof']);
    $mime = [
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
        'pdf' => 'application/pdf',
    ][strtolower(pathinfo($file, PATHINFO_EXTENSION))] ?? 'application/octet-stream';
}

if ($file === '') {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'File not found.';
    exit;
}

header('Content-Type: ' . $mime);
header('X-Content-Type-Options: nosniff');
header('Content-Disposition: inline; filename="' . basename($file) . '"');
header('Cache-Control: private, no-store');
header('Content-Length: ' . (string) filesize($file));
readfile($file);
