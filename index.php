<?php

$next = 'login.php';
try {
    require __DIR__ . '/api/lib.php';
    if (is_cafe_network()) {
        start_app_session();
        $role = (string) ($_SESSION['role'] ?? '');
        if ($role === 'supply') {
            $next = 'supply/';
        } elseif ($role !== '' && $role !== 'manager' && role_opens_admin($role)) {
            $next = 'admin/index.php';
        } elseif ($role === 'waiter') {
            $next = 'waiter/';
        }
    }
} catch (Throwable $ignored) {
    $next = 'login.php';
}

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
  <meta name="theme-color" content="#111111">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="Ithaa">
  <title>Ithaa Corner</title>
  <link rel="manifest" href="manifest.webmanifest">
  <link rel="icon" href="icon-192.png">
  <link rel="apple-touch-icon" href="apple-touch-icon.png">
  <style>body{margin:0;min-height:100vh;background:#111111}</style>
</head>
<body>
  <script src="pwa.js"></script>
  <script>
    (function () {
      var next = <?= json_encode($next) ?>;
      try {
        if (next === 'login.php' && localStorage.getItem('cafe_pos_token')) {
          next = 'waiter/';
        }
      } catch (error) {}
      location.replace(next);
    })();
  </script>
</body>
</html>
