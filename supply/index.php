<?php

require __DIR__ . '/../api/lib.php';

if (!is_cafe_network()) {
    http_response_code(403);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="en"><meta charset="utf-8"><title>Cafe network</title>';
    echo '<body style="margin:0;min-height:100vh;display:grid;place-items:center;background:#111111;color:#f5f5f5;font-family:Segoe UI,sans-serif;padding:1.5rem;">';
    echo '<p>Open this POS on the cafe Wi-Fi or over Tailscale.</p></body></html>';
    exit;
}

start_app_session();
$role = (string) ($_SESSION['role'] ?? '');
if ($role === '') {
    header('Location: ../login.php');
    exit;
}
if ($role !== 'supply') {
    header('Location: ' . (role_opens_admin($role) ? '../admin/supply.php' : '../login.php'));
    exit;
}

function supply_page_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return (string) $_SESSION['csrf'];
}

$error = null;
$week = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sent = (string) ($_POST['csrf'] ?? '');
    $token = supply_page_token();
    if ($sent === '' || !hash_equals($token, $sent)) {
        $error = 'The form expired. Reload the page and try again.';
    } elseif ((string) ($_POST['action'] ?? '') === 'logout') {
        logout_owner();
        header('Location: ../login.php');
        exit;
    } else {
        try {
            $lines = [];
            $posted = $_POST['lines'] ?? [];
            if (is_array($posted)) {
                foreach ($posted as $itemId => $entry) {
                    if (!is_array($entry)) {
                        continue;
                    }
                    $lines[] = [
                        'item_id' => (int) $itemId,
                        'qty' => (string) ($entry['qty'] ?? ''),
                        'price' => (string) ($entry['price'] ?? ''),
                    ];
                }
            }
            save_supply_quote((int) ($_SESSION['user_id'] ?? 0), $lines);
            $_SESSION['flash'] = 'Quote saved.';
            header('Location: index.php');
            exit;
        } catch (AppError $errorObject) {
            $error = $errorObject->getMessage();
        } catch (PDOException $errorObject) {
            $error = 'Database error. Start MySQL and import database/schema.sql.';
        }
    }
}

try {
    $week = supply_week_view();
} catch (Throwable $errorObject) {
    $error = $error === null ? 'Database error. Start MySQL and import database/schema.sql.' : $error;
    $week = [
        'week_label' => format_date(date('Y-m-d')),
        'status' => '',
        'lines' => [],
        'total' => null,
    ];
}

$flash = '';
if (!empty($_SESSION['flash'])) {
    $flash = (string) $_SESSION['flash'];
    unset($_SESSION['flash']);
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
  <title>Supplies · <?= h($cafe) ?></title>
  <link rel="manifest" href="../manifest.webmanifest">
  <link rel="icon" href="../icon-192.png">
  <link rel="stylesheet" href="../admin/style.css?v=30">
  <link rel="stylesheet" href="../admin/mobile.css?v=7" media="(max-width: 860px)">
</head>
<body class="login supply">
<main>
  <form class="panel" method="post">
    <input type="hidden" name="csrf" value="<?= h(supply_page_token()) ?>">
    <header>
      <h1><?= h($cafe) ?></h1>
      <p>Week of <?= h((string) $week['week_label']) ?></p>
    </header>
    <?php if ($flash !== ''): ?><p class="flash"><?= h($flash) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
    <?php if ($week['status'] === '' || $week['lines'] === []): ?>
      <p>No request for this week yet.</p>
    <?php elseif ($week['status'] === 'accepted'): ?>
      <p>This week is accepted.</p>
      <?php foreach ($week['lines'] as $line): ?>
        <p><strong><?= h($line['name']) ?></strong> <?= h($line['unit']) ?> · <?= h(number_format((float) $line['quote_qty'], 2, '.', '')) ?> · <?= h(money_label((string) $line['quote_price'])) ?></p>
      <?php endforeach; ?>
      <?php if ($week['total'] !== null): ?><p><strong><?= h(money_label((string) $week['total'])) ?></strong></p><?php endif; ?>
    <?php else: ?>
      <p><?= $week['status'] === 'quoted' ? 'Update the quantity and price until it is accepted.' : 'Enter the quantity and price you will supply.' ?></p>
      <?php foreach ($week['lines'] as $line): ?>
        <?php if ((float) $line['requested_qty'] <= 0) { continue; } ?>
        <label><?= h($line['name']) ?><?php if ($line['unit'] !== ''): ?> <span class="muted">(<?= h($line['unit']) ?>)</span><?php endif; ?> · requested <?= h(number_format((float) $line['requested_qty'], 2, '.', '')) ?>
          <input name="lines[<?= (int) $line['item_id'] ?>][qty]" inputmode="decimal" required placeholder="Quantity" value="<?= h($line['quote_qty'] === null ? '' : number_format((float) $line['quote_qty'], 2, '.', '')) ?>">
          <input name="lines[<?= (int) $line['item_id'] ?>][price]" inputmode="decimal" required placeholder="Price" value="<?= h($line['quote_price'] === null ? '' : number_format((float) $line['quote_price'], 2, '.', '')) ?>">
        </label>
      <?php endforeach; ?>
      <?php if ($week['total'] !== null): ?><p>Total <?= h(money_label((string) $week['total'])) ?></p><?php endif; ?>
      <button type="submit">Save quote</button>
    <?php endif; ?>
  </form>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= h(supply_page_token()) ?>">
    <input type="hidden" name="action" value="logout">
    <button class="ghost" type="submit">Log out</button>
  </form>
</main>
</body>
</html>
