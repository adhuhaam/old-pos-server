<?php

require __DIR__ . '/api/lib.php';

if (!is_cafe_network()) {
    http_response_code(403);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="en"><meta charset="utf-8"><title>Cafe network</title>';
    echo '<body style="margin:0;min-height:100vh;display:grid;place-items:center;background:#111111;color:#f5f5f5;font-family:Segoe UI,sans-serif;padding:1.5rem;">';
    echo '<p>Open this POS on the cafe Wi-Fi or over Tailscale.</p></body></html>';
    exit;
}

start_app_session();
$sitting = (string) ($_SESSION['role'] ?? '');
if ($sitting === 'manager') {
    logout_owner();
    start_app_session();
    $sitting = '';
}
if ($sitting !== '' && role_opens_admin($sitting)) {
    header('Location: admin/index.php');
    exit;
}
if ($sitting === 'supply') {
    header('Location: supply/index.php');
    exit;
}
if ($sitting !== '') {
    logout_owner();
    start_app_session();
}

$error = null;
$username = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    try {
        $sent = (string) ($_POST['csrf'] ?? '');
        $token = (string) ($_SESSION['csrf'] ?? '');
        if ($sent === '' || $token === '' || !hash_equals($token, $sent)) {
            throw new AppError('The form expired. Reload the page and try again.');
        }
        $user = authenticate_user($username, (string) ($_POST['password'] ?? ''));
        if (!role_uses_tailscale((string) $user['role']) && !is_shop_floor()) {
            throw new AppError('Waiters sign in on the cafe Wi-Fi.');
        }
        if ($user['role'] !== 'manager' && role_opens_admin($user['role'])) {
            login_session($user);
            header('Location: admin/index.php');
            exit;
        }
        if ($user['role'] === 'supply') {
            login_session($user);
            header('Location: supply/index.php');
            exit;
        }
        $access = issue_token($user['id']);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html lang="en"><meta charset="utf-8"><title>Sign in</title><script>';
        echo 'localStorage.setItem("cafe_pos_token",' . json_encode($access) . ');';
        echo 'location.replace("waiter/");';
        echo '</script></html>';
        exit;
    } catch (AppError $errorObject) {
        $error = $errorObject->getMessage();
    } catch (PDOException $errorObject) {
        $error = 'Database error. Start MySQL and import database/schema.sql.';
    }
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
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
  <meta name="theme-color" content="#e6e6e6">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="Ithaa">
  <title>Sign in · <?= h($cafe) ?></title>
  <link rel="manifest" href="manifest.webmanifest">
  <link rel="icon" href="icon-192.png">
  <link rel="apple-touch-icon" href="apple-touch-icon.png">
  <link rel="stylesheet" href="admin/style.css?v=17">
  <link rel="stylesheet" href="admin/mobile.css?v=2" media="(max-width: 860px)">
  <script>
    try {
      if (localStorage.getItem('cafe_pos_token')) location.replace('waiter/');
    } catch (error) {}
  </script>
</head>
<body class="login">
  <main>
    <form class="panel" method="post">
      <header>
        <h1><?= h($cafe) ?></h1>
        <p>Sign in to the register.</p>
      </header>
      <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
      <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">
      <label>
        <span>Username</span>
        <input name="username" autocomplete="username" required value="<?= h($username) ?>">
      </label>
      <label>
        <span>Password</span>
        <input type="password" name="password" autocomplete="current-password" required>
      </label>
      <button type="submit">Sign in</button>
      <button type="button" id="pwa-install" hidden>Install app</button>
    </form>
  </main>
  <script src="pwa.js"></script>
</body>
</html>