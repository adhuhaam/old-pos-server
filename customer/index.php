<?php

require __DIR__ . '/../api/lib.php';

if (!is_shop_floor()) {
    http_response_code(403);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="en"><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>Cafe Wi-Fi</title><body style="margin:0;min-height:100vh;display:grid;place-items:center;background:#f4f1ea;color:#1a1a1a;font-family:Georgia,serif;padding:1.5rem;text-align:center;">';
    echo '<p>Open this on the cafe Wi-Fi.</p></body></html>';
    exit;
}

$cafe = 'Ithaa Corner';
try {
    $name = trim((string) (settings()['cafe_name'] ?? ''));
    if ($name !== '') {
        $cafe = $name;
    }
} catch (Throwable $ignored) {
    $cafe = 'Ithaa Corner';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#f4f1ea">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-title" content="<?= h($cafe) ?>">
  <title><?= h($cafe) ?></title>
  <link rel="icon" href="../icon-192.png">
  <link rel="apple-touch-icon" href="../apple-touch-icon.png">
  <link rel="stylesheet" href="app.css?v=1">
</head>
<body>
  <div id="app"></div>
  <script>window.CAFE_NAME = <?= json_encode($cafe, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
  <script src="app.js?v=2"></script>
</body>
</html>
