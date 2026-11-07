<?php

require __DIR__ . '/_init.php';
require_owner_page();

$error = null;
$items = [];
$week = [
    'week_label' => format_date(supply_week_start()),
    'status' => '',
    'expense_id' => null,
    'total' => null,
    'lines' => [],
];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        if (!is_superadmin_session()) {
            throw new AppError('Only a superadmin can change supplies.', 403);
        }
        $action = (string) ($_POST['action'] ?? '');
        if ($action === 'item') {
            $active = (string) ($_POST['active'] ?? '1') !== '0';
            save_supply_item((int) ($_POST['id'] ?? 0), (string) ($_POST['name'] ?? ''), (string) ($_POST['unit'] ?? ''), $active);
            flash_set('Item saved.');
        } elseif ($action === 'accept') {
            accept_supply_week((int) ($_SESSION['user_id'] ?? 0));
            flash_set('Quote accepted.');
        } elseif ($action === 'sendback') {
            send_back_supply_week();
            flash_set('Quote sent back.');
        }
        redirect_to('supply.php');
    }
    $items = supply_items(false);
    $week = supply_week_view();
} catch (AppError $errorObject) {
    $error = $errorObject->getMessage();
    try {
        $items = supply_items(false);
        $week = supply_week_view();
    } catch (Throwable $ignored) {
        $items = [];
    }
} catch (PDOException $errorObject) {
    $error = 'Database error. Start MySQL and import database/schema.sql.';
}

$statusLabel = 'No request this week.';
if ($week['status'] === 'requested') {
    $statusLabel = 'Requested. Waiting for supply staff to enter quantity and price.';
} elseif ($week['status'] === 'quoted') {
    $statusLabel = 'Priced. Waiting for approval.';
} elseif ($week['status'] === 'accepted') {
    $statusLabel = 'Accepted and saved as a Supply expense.';
}

layout_header('Supplies');
?>
<?php page_head('Supplies', 'Week of ' . h((string) $week['week_label'])); ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>

<?php if (is_superadmin_session()): ?>
<form class="panel" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="item">
  <h2>New item</h2>
  <div class="row">
    <label>Name <input name="name" maxlength="80" required></label>
    <label>Unit <input name="unit" maxlength="20" placeholder="kg, pcs" required></label>
    <button type="submit">Add item</button>
  </div>
</form>
<?php endif; ?>

<section class="sheet">
  <header class="sheet-head">
    <h2>Weekly items</h2>
    <p>These goods repeat every week. The price is entered each week.</p>
  </header>
  <?php if (!$items): ?>
    <p class="empty">No weekly supply items yet.</p>
  <?php elseif (is_superadmin_session()): ?>
    <?php foreach ($items as $item): ?>
    <form class="customer-edit" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="item">
      <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
      <label>Name <input name="name" maxlength="80" required value="<?= h($item['name']) ?>"></label>
      <label>Unit <input name="unit" maxlength="20" required value="<?= h($item['unit']) ?>"></label>
      <label>Showing
        <input type="hidden" name="active" value="0">
        <input type="checkbox" name="active" value="1"<?= $item['active'] ? ' checked' : '' ?>>
      </label>
      <button type="submit">Save</button>
    </form>
    <?php endforeach; ?>
  <?php else: ?>
  <div class="books">
  <table class="stack">
    <thead>
      <tr>
        <th>Item</th>
        <th>Unit</th>
        <th>Showing</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td data-label="Item"><strong><?= h($item['name']) ?></strong></td>
          <td data-label="Unit"><?= h($item['unit']) ?></td>
          <td data-label="Showing"><?= $item['active'] ? 'Yes' : 'Hidden' ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <?php endif; ?>
</section>

<section class="sheet">
  <header class="sheet-head">
    <h2>This week</h2>
    <p><?= h($statusLabel) ?><?php if ($week['total'] !== null): ?> · <?= h(money_label($week['total'])) ?><?php endif; ?><?php if (!empty($week['expense_id'])): ?> · <a href="expenses.php?id=<?= (int) $week['expense_id'] ?>">Open expense</a><?php endif; ?></p>
  </header>
  <?php if (!$week['lines'] || $week['status'] === ''): ?>
    <p class="empty">No request for this week yet.</p>
  <?php else: ?>
  <div class="books">
  <table class="stack">
    <thead>
      <tr>
        <th>Item</th>
        <th class="num">Requested</th>
        <th class="num">Supply qty</th>
        <th class="num">Price</th>
        <th class="num">Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($week['lines'] as $line): ?>
        <?php if ($week['status'] === 'requested' && (float) $line['requested_qty'] <= 0) { continue; } ?>
        <tr>
          <td data-label="Item"><strong><?= h($line['name']) ?></strong><?php if ($line['unit'] !== ''): ?> <span class="muted"><?= h($line['unit']) ?></span><?php endif; ?></td>
          <td class="num" data-label="Requested"><?= h(number_format((float) $line['requested_qty'], 2, '.', '')) ?></td>
          <td class="num" data-label="Supply qty"><?= $line['quote_qty'] === null ? '—' : h(number_format((float) $line['quote_qty'], 2, '.', '')) ?></td>
          <td class="num" data-label="Price"><?= $line['quote_price'] === null ? '—' : h(money_label($line['quote_price'])) ?></td>
          <td class="num" data-label="Amount"><?= $line['line_total'] === null ? '—' : h(money_label($line['line_total'])) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <?php if (is_superadmin_session() && $week['status'] === 'quoted'): ?>
  <div class="pay-actions">
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="accept">
      <button type="submit">Accept</button>
    </form>
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="sendback">
      <button class="ghost" type="submit">Send back</button>
    </form>
  </div>
  <?php endif; ?>
  <?php endif; ?>
</section>
<?php
layout_footer();
