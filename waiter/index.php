<?php

require __DIR__ . '/../api/lib.php';

if (!is_cafe_network()) {
    http_response_code(403);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="en"><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">';
    echo '<title>Cafe Wi-Fi only</title>';
    echo '<body style="margin:0;min-height:100vh;display:grid;place-items:center;background:#111111;color:#f5f5f5;font-family:Segoe UI,sans-serif;padding:1.5rem;">';
    echo '<main style="max-width:24rem;"><h1 style="font-family:Georgia,serif;">Cafe Wi-Fi only</h1>';
    echo '<p>The waiter app works on the cafe Wi-Fi. Connect to that network and open this page again.</p></main>';
    echo '</body></html>';
    exit;
}

header('Content-Type: text/html; charset=utf-8');
readfile(__DIR__ . '/index.html');
