<?php

require __DIR__ . '/_init.php';
require_owner_page();

$error = null;
$currency = 'MVR';
$defaultLimit = '200.00';
$hasBank = false;

try {
    $currency = settings()['currency_symbol'];
    $storedLimit = settings()['default_credit_limit'] ?? null;
    $defaultLimit = $storedLimit === null || $storedLimit === '' ? '' : number_format((float) $storedLimit, 2, '.', '');
    $hasBank = bank_image_file() !== '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $fields = [
            'currency_symbol' => $_POST['currency_symbol'] ?? '',
        ];
        if (array_key_exists('default_credit_limit', $_POST)) {
            $fields['default_credit_limit'] = $_POST['default_credit_limit'];
        }
        update_settings($fields);
        if (can_manage() && array_key_exists('order_host', $_POST)) {
            save_order_host((string) $_POST['order_host']);
        }
        if (isset($_FILES['bank_image']) && is_array($_FILES['bank_image']) && (int) ($_FILES['bank_image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            save_bank_image($_FILES['bank_image']);
        }
        flash_set('Settings saved.');
        redirect_to('settings.php');
    }
} catch (AppError $errorObject) {
    $error = $errorObject->getMessage();
    $currency = (string) ($_POST['currency_symbol'] ?? $currency);
    $defaultLimit = array_key_exists('default_credit_limit', $_POST) ? (string) $_POST['default_credit_limit'] : $defaultLimit;
    $hasBank = bank_image_file() !== '';
} catch (PDOException $errorObject) {
    $error = 'Database error. Start MySQL and import database/schema.sql.';
}

layout_header('Settings');
?>
<?php page_head('Settings', h(owner_cafe_name())); ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<form class="panel" method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <div class="split">
    <div>
      <h2>Currency</h2>
      <label>Symbol
        <input name="currency_symbol" maxlength="8" required value="<?= h($currency) ?>"<?= can_manage() ? '' : ' disabled' ?>>
      </label>
      <p class="muted note">Prices on the waiter app and the receipts use this symbol. It is MVR for Ithaa Corner.</p>
      <h2>Defaults</h2>
      <label>New customer credit limit
        <input name="default_credit_limit" inputmode="decimal" value="<?= h($defaultLimit) ?>"<?= can_manage() ? '' : ' disabled' ?>>
      </label>
      <p class="muted note">A new customer starts with this credit limit. Leave it blank for no limit. Customers already saved keep their own limit.</p>
      <h2>Bank photo</h2>
      <?php if (can_manage()): ?>
      <label>QR or account photo
        <input type="file" name="bank_image" accept="image/*">
      </label>
      <p class="muted note">Waiters show this when a customer pays by bank. They can close the bill after the customer pays.</p>
      <button type="submit">Save settings</button>
      <?php endif; ?>
    </div>
    <div class="bank-frame">
      <?php if ($hasBank): ?>
        <img src="media.php?bank=1" alt="Bank photo">
      <?php else: ?>
        <p class="empty">No bank photo yet.</p>
      <?php endif; ?>
    </div>
  </div>
</form>
<?php
$orderHost = shop_order_host();
$qrTables = array_values(array_filter(list_tables(), static function (array $table): bool {
    return !empty($table['active']);
}));
?>
<section class="sheet" id="table-qr">
  <header class="sheet-head">
    <h2>Table QR</h2>
    <p>Print or save a card for each table. A phone on the cafe Wi-Fi can scan it to register, order, and pay.</p>
  </header>
  <?php if (can_manage()): ?>
  <form class="panel" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="currency_symbol" value="<?= h($currency) ?>">
    <label>Cafe Wi-Fi address
      <input name="order_host" maxlength="80" value="<?= h((string) (settings()['order_host'] ?? '')) ?>" placeholder="<?= h($orderHost !== '' ? $orderHost : '192.168.1.8') ?>">
    </label>
    <p class="muted note">This is the address of this computer on the cafe Wi-Fi. Phones use it when they scan a table card. Leave it blank to use the address this page can see.</p>
    <button type="submit">Save address</button>
  </form>
  <?php endif; ?>
  <?php if ($orderHost === ''): ?>
    <p class="empty">Enter the cafe Wi-Fi address before printing the cards.</p>
  <?php else: ?>
    <div class="qr-tools">
      <button type="button" id="print-qr">Print cards</button>
    </div>
    <div class="qr-grid">
      <?php foreach ($qrTables as $table): ?>
        <?php $link = table_order_url((int) $table['id']); ?>
        <article class="qr-card">
          <h3><?= h($table['name']) ?></h3>
          <img alt="QR for <?= h($table['name']) ?>" data-qr="<?= h($link) ?>">
          <p><?= h($cafeName = owner_cafe_name()) ?></p>
          <p class="muted">Scan to order</p>
          <button type="button" class="ghost qr-save" data-name="<?= h($table['name']) ?>">Save</button>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<script src="qrcode.js?v=1"></script>
<script>
(function () {
  document.querySelectorAll('[data-qr]').forEach(function (img) {
    var qr = qrcode(0, 'M');
    qr.addData(img.getAttribute('data-qr') || '');
    qr.make();
    img.src = qr.createDataURL(6, 2);
  });
  document.querySelectorAll('.qr-save').forEach(function (button) {
    button.addEventListener('click', function () {
      var card = button.closest('.qr-card');
      var img = card ? card.querySelector('img') : null;
      if (!img || !img.src) return;
      var link = document.createElement('a');
      link.href = img.src;
      link.download = (button.getAttribute('data-name') || 'table') + ' QR.gif';
      link.click();
    });
  });
  var printButton = document.getElementById('print-qr');
  if (printButton) printButton.addEventListener('click', function () { window.print(); });
})();
</script>
<?php layout_footer(); ?>
