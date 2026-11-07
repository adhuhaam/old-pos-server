<?php

require __DIR__ . '/_init.php';
require_owner_page();

$raw = trim((string) ($_GET['date'] ?? ''));
$date = date('Y-m-d');
$error = null;
$report = null;
$sticky = null;
$super = is_superadmin_session();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $action = (string) ($_POST['action'] ?? '');
        if ($action === 'manual') {
            $saved = record_manual_sale(
                (string) ($_POST['sale_date'] ?? ''),
                (int) ($_POST['table_id'] ?? 0),
                (string) ($_POST['payment'] ?? ''),
                (int) ($_POST['customer_id'] ?? 0),
                (array) ($_POST['item_id'] ?? []),
                (array) ($_POST['qty'] ?? [])
            );
            flash_set('Sale added.');
            redirect_to('sales.php?date=' . $saved['date']);
        }
        if ($action === 'update') {
            $saved = update_recorded_sale(
                (int) ($_POST['sale_id'] ?? 0),
                (string) ($_POST['sale_date'] ?? ''),
                (int) ($_POST['table_id'] ?? 0),
                (string) ($_POST['payment'] ?? ''),
                (int) ($_POST['customer_id'] ?? 0),
                (array) ($_POST['item_id'] ?? []),
                (array) ($_POST['qty'] ?? [])
            );
            flash_set('Sale updated.');
            redirect_to('sales.php?date=' . $saved['date']);
        }
        if ($action === 'delete') {
            $day = delete_recorded_sale((int) ($_POST['sale_id'] ?? 0));
            flash_set('Sale deleted.');
            redirect_to('sales.php?date=' . $day);
        }
    }
    if ($raw !== '') {
        $date = parse_date($raw);
    }
    $report = sales_for_date($date);
} catch (AppError $errorObject) {
    $error = $errorObject->getMessage();
    $posted = trim((string) ($_POST['sale_date'] ?? ''));
    if ($posted !== '') {
        try {
            $date = parse_date($posted);
        } catch (Throwable $ignored) {
            $date = date('Y-m-d');
        }
    } elseif ($raw !== '') {
        try {
            $date = parse_date($raw);
        } catch (Throwable $ignored) {
            $date = date('Y-m-d');
        }
    }
    if (in_array((string) ($_POST['action'] ?? ''), ['manual', 'update'], true)) {
        $sticky = [
            'action' => (string) $_POST['action'],
            'sale_id' => (int) ($_POST['sale_id'] ?? 0),
            'date' => $date,
            'table_id' => (int) ($_POST['table_id'] ?? 0),
            'payment' => (string) ($_POST['payment'] ?? 'cash'),
            'customer_id' => (int) ($_POST['customer_id'] ?? 0),
            'lines' => [],
        ];
        $ids = (array) ($_POST['item_id'] ?? []);
        $qtys = (array) ($_POST['qty'] ?? []);
        $count = max(count($ids), count($qtys));
        for ($index = 0; $index < $count; $index++) {
            $sticky['lines'][] = [
                'item_id' => (int) ($ids[$index] ?? 0),
                'qty' => (int) ($qtys[$index] ?? 0),
            ];
        }
    }
    try {
        $report = sales_for_date($date);
    } catch (Throwable $ignored) {
        $report = null;
    }
} catch (PDOException $errorObject) {
    $error = 'Database error. Start MySQL and import database/schema.sql.';
}

$shown = format_date($date);
$prev = date('Y-m-d', strtotime($date . ' -1 day'));
$next = date('Y-m-d', strtotime($date . ' +1 day'));
$cafe = owner_cafe_name();
$ahead = $date > date('Y-m-d');

$itemOptions = '';
$tableOptions = '';
$customerOptions = '';
$editor = [];
if ($super) {
    $groups = [];
    foreach (list_items() as $item) {
        $groups[$item['category_name']][] = $item;
    }
    foreach ($groups as $categoryName => $group) {
        $itemOptions .= '<optgroup label="' . h($categoryName) . '">';
        foreach ($group as $item) {
            $itemOptions .= '<option value="' . (int) $item['id'] . '">' . h($item['name']) . ($item['active'] ? '' : ' · Hidden') . '</option>';
        }
        $itemOptions .= '</optgroup>';
    }
    foreach (list_tables() as $table) {
        $tableOptions .= '<option value="' . (int) $table['id'] . '">' . h($table['name']) . ($table['active'] ? '' : ' · Hidden') . '</option>';
    }
    foreach (list_customers() as $customer) {
        $customerOptions .= '<option value="' . (int) $customer['id'] . '">' . h($customer['name']) . '</option>';
    }
}

layout_header('Sales');
$addButton = $super ? '<button type="button" id="add-sale">Add a sale</button>' : '';
?>
<?php page_head('Sales', h($shown), $addButton); ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<p class="muted note">Open credit is listed on the day it was started. It stays out of the sales total until it is paid. After payment it sits inside cash or bank, and is marked Held.</p>
<p class="export-row">
  <a class="button" href="export.php?kind=sales&amp;date=<?= h(rawurlencode($date)) ?>">Export this day’s sales</a>
  <a class="button" href="export.php?kind=sales">Export all sales</a>
  <a class="button" href="export.php?kind=credit">Export credit</a>
</p>
<?php if ($ahead): ?><p class="muted note">This day is still ahead. A sale saved here counts on this day.</p><?php endif; ?>
<form class="daybar" method="get">
  <a class="step" href="sales.php?date=<?= h(rawurlencode($prev)) ?>" aria-label="Previous day">‹</a>
  <label>Date
    <input type="date" name="date" required value="<?= h($date) ?>">
  </label>
  <a class="step" href="sales.php?date=<?= h(rawurlencode($next)) ?>" aria-label="Next day">›</a>
  <button type="submit">Show</button>
</form>
<?php if ($report): ?>
  <section class="cards">
    <article class="card"><span>Tickets</span><strong><?= (int) $report['summary']['count'] ?></strong></article>
    <article class="card"><span>Total</span><strong><?= h(money_label($report['summary']['total'])) ?></strong></article>
    <article class="card cash"><span>Cash</span><strong><?= h(money_label($report['summary']['cash_total'])) ?></strong></article>
    <article class="card bank"><span>Bank</span><strong><?= h(money_label($report['summary']['online_total'])) ?></strong></article>
    <article class="card credit"><span>Still open</span><strong><?= h(money_label($report['summary']['credit_open'])) ?></strong></article>
    <?php
      $heldCents = 0;
      foreach ($report['sales'] as $sale) {
          if (!empty($sale['held'])) {
              $heldCents += to_cents((string) $sale['total']);
          }
      }
    ?>
    <?php if ($heldCents > 0): ?>
    <article class="card credit"><span>Held, inside total</span><strong><?= h(money_label(from_cents($heldCents))) ?></strong></article>
    <?php endif; ?>
  </section>
  <div class="toolbar">
    <label class="find">Find
      <input data-find="#sale-list" placeholder="Bill, table, or item">
    </label>
    <div class="filters">
      <button type="button" class="current" data-pay-set="#sale-list" data-value="all">All</button>
      <button type="button" data-pay-set="#sale-list" data-value="cash">Cash</button>
      <button type="button" data-pay-set="#sale-list" data-value="online">Bank</button>
      <button type="button" data-pay-set="#sale-list" data-value="credit">Credit/Hold</button>
    </div>
  </div>
  <?php if ($report['sales'] === [] && $report['credits'] === []): ?>
    <p class="empty">No sales on this day.<?php if ($super): ?> Add a sale when you need to record one.<?php endif; ?></p>
  <?php endif; ?>
  <div id="sale-list" data-list data-pay="all">
  <?php
    $cards = [];
    foreach ($report['credits'] as $sale) {
        $cards[] = $sale;
    }
    foreach ($report['sales'] as $sale) {
        $cards[] = $sale;
    }
    foreach ($cards as $sale):
        $names = [];
        foreach ($sale['lines'] as $line) {
            $names[] = $line['item_name'];
        }
        $serial = bill_number((int) $sale['id']);
        $pay = (string) ($sale['payment_method'] ?? 'credit');
        $locked = trim((string) ($sale['settlement_reference'] ?? '')) !== '';
        $haystack = trim($serial . ' ' . ($sale['customer_name'] ?? '') . ' ' . ($sale['table_name'] ?? '') . ' ' . implode(' ', $names));
        $when = $pay === 'credit' ? format_when((string) $sale['opened_at']) : format_when((string) ($sale['closed_at'] ?? ''));
        $title = trim((string) ($sale['customer_name'] ?? '')) !== '' && $pay === 'credit'
            ? (string) $sale['customer_name']
            : (string) ($sale['table_name'] ?? '');
        if ($super && !$locked) {
            $editor[(string) $sale['id']] = [
                'id' => (int) $sale['id'],
                'date' => $date,
                'table_id' => (int) ($sale['table_id'] ?? 0),
                'payment' => $pay,
                'customer_id' => (int) ($sale['customer_id'] ?? 0),
                'lines' => array_map(static function (array $line): array {
                    return ['item_id' => (int) ($line['item_id'] ?? 0), 'qty' => (int) $line['qty']];
                }, $sale['lines']),
            ];
        }
  ?>
    <article class="sale" data-text="<?= h($haystack) ?>" data-pay="<?= h($pay) ?>">
      <header>
        <strong>Bill <?= h($serial) ?><?php if ($title !== ''): ?> · <?= h($title) ?><?php endif; ?></strong>
        <span>
          <span class="pay-pill <?= h($pay) ?>"><?= h($pay === 'credit' ? 'Credit/Hold' : (string) ($sale['payment_label'] ?? payment_label($pay))) ?></span>
          <?php if ($pay === 'credit'): ?> <span class="pay-pill credit">Still open</span><?php endif; ?>
          <?php if (!empty($sale['held']) && $pay !== 'credit'): ?> <span class="pay-pill credit">Held</span><?php endif; ?>
          <?php if ($locked): ?> <span class="pay-pill"><?= h((string) $sale['settlement_reference']) ?></span><?php endif; ?>
          <?= h($when) ?> · <?= h(money_label($sale['total'])) ?>
          <?php if ($pay === 'credit' && $title !== (string) ($sale['table_name'] ?? '') && ($sale['table_name'] ?? '') !== ''): ?> · <?= h((string) $sale['table_name']) ?><?php endif; ?>
          <?php if ($pay === 'credit' && !empty($sale['customer_phone'])): ?> · <?= h((string) $sale['customer_phone']) ?><?php endif; ?>
          <?php if ($pay === 'credit' && array_key_exists('credit_limit', $sale) && $sale['credit_limit'] !== null): ?> · Limit <?= h(money_label($sale['credit_limit'])) ?><?php endif; ?>
          <?php if (!empty($sale['duration_label'])): ?> · <?= h((string) $sale['duration_label']) ?><?php endif; ?>
        </span>
      </header>
      <table>
        <tbody>
        <?php foreach ($sale['lines'] as $line): ?>
          <tr>
            <td><?= (int) $line['qty'] ?> × <?= h($line['item_name']) ?><?php $added = substr((string) ($line['added_at'] ?? ''), 0, 10); if ($added !== ''): ?> · <?= h(format_date($added)) ?><?php endif; ?></td>
            <td><?= h(money_label($line['unit_price'])) ?></td>
            <td class="num"><?= h(money_label($line['line_total'])) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?= sale_slip($sale) ?>
      <div class="sale-actions">
        <button type="button" class="ghost" data-view="<?= (int) $sale['id'] ?>">View receipt</button>
        <button type="button" class="ghost" data-print="<?= (int) $sale['id'] ?>">Print</button>
        <?php if ($super && !$locked): ?>
          <button type="button" class="ghost" data-edit="<?= (int) $sale['id'] ?>">Edit</button>
          <button type="button" class="ghost danger" data-delete="<?= (int) $sale['id'] ?>" data-bill="<?= h($serial) ?>">Delete</button>
        <?php elseif ($locked): ?>
          <span class="muted">Part of a settlement</span>
        <?php endif; ?>
      </div>
      <template id="bill-<?= (int) $sale['id'] ?>"><?= sale_bill_html($sale, $cafe) ?></template>
    </article>
  <?php endforeach; ?>
  </div>
  <p class="empty" data-none="#sale-list" hidden>Nothing matches that search.</p>
<?php endif; ?>
<div id="print-bill"></div>
<?php if ($super): ?>
<dialog id="sale-dialog" class="modal">
  <form method="post" id="sale-form">
    <?= csrf_field() ?>
    <input type="hidden" name="action" id="sale-action" value="manual">
    <input type="hidden" name="sale_id" id="sale-id" value="">
    <div class="modal-bar">
      <h2 id="sale-title">Add a sale</h2>
      <button type="button" class="ghost" data-close>Close</button>
    </div>
    <div class="modal-body">
      <p class="muted">Prices come from the menu. Cash and bank count on the day you choose, including a future day. Credit/Hold stays open until it is paid. Saving an existing bill records its items on that day.</p>
      <div class="row">
        <label>Day
          <input type="date" name="sale_date" id="sale-date" required value="<?= h($date) ?>" min="2020-01-01" max="<?= h(date('Y-m-d', strtotime('+2 years'))) ?>">
        </label>
        <label>Table
          <select name="table_id" id="sale-table" required>
            <option value="">Choose a table</option>
            <?= $tableOptions ?>
          </select>
        </label>
        <label>Payment
          <select name="payment" id="sale-payment" required>
            <option value="cash">Cash</option>
            <option value="online">Bank</option>
            <option value="credit">Credit/Hold</option>
          </select>
        </label>
        <label>Customer
          <select name="customer_id" id="sale-customer">
            <option value="">None</option>
            <?= $customerOptions ?>
          </select>
        </label>
      </div>
      <div id="sale-lines"></div>
      <button type="button" class="ghost" id="add-line">Add item</button>
      <div class="modal-actions">
        <button type="button" class="ghost" data-close>Cancel</button>
        <button type="submit" id="sale-save">Save sale</button>
      </div>
    </div>
  </form>
</dialog>
<template id="line-template">
  <div class="line-row">
    <label>Item
      <select name="item_id[]">
        <option value="">Choose</option>
        <?= $itemOptions ?>
      </select>
    </label>
    <label>Qty
      <input name="qty[]" type="number" min="1" max="99" value="1">
    </label>
    <button type="button" class="ghost" data-remove-line>Remove</button>
  </div>
</template>
<dialog id="delete-dialog" class="modal">
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="sale_id" id="delete-id" value="">
    <div class="modal-bar"><h2>Delete bill</h2></div>
    <div class="modal-body">
      <p>Delete bill <strong id="delete-label"></strong>? This removes it from sales.</p>
      <div class="modal-actions">
        <button type="button" class="ghost" data-close>Cancel</button>
        <button type="submit" class="danger">Delete</button>
      </div>
    </div>
  </form>
</dialog>
<script type="application/json" id="sale-editor"><?= json_encode((object) $editor, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
<?php if ($sticky): ?>
<script type="application/json" id="sale-sticky"><?= json_encode($sticky, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
<?php endif; ?>
<?php endif; ?>
<dialog id="receipt-dialog" class="modal bill-modal">
  <div class="modal-bar">
    <h2>Receipt</h2>
    <button type="button" class="ghost" data-close>Close</button>
  </div>
  <div class="modal-body" id="receipt-view"></div>
  <div class="modal-actions pad">
    <button type="button" class="ghost" data-close>Close</button>
    <button type="button" id="receipt-print">Print</button>
  </div>
</dialog>
<script>
(function () {
  var receipt = document.getElementById('receipt-dialog');
  var view = document.getElementById('receipt-view');
  var printRoot = document.getElementById('print-bill');
  var editorNode = document.getElementById('sale-editor');
  var editor = editorNode ? JSON.parse(editorNode.textContent || '{}') : {};
  var saleDialog = document.getElementById('sale-dialog');
  var deleteDialog = document.getElementById('delete-dialog');
  var lineTemplate = document.getElementById('line-template');
  var linesBox = document.getElementById('sale-lines');

  function billNode(id) {
    return document.getElementById('bill-' + id);
  }

  function showReceipt(id) {
    var node = billNode(id);
    if (!node || !receipt || !view) return;
    view.innerHTML = node.innerHTML;
    receipt.showModal();
  }

  function printReceipt(id) {
    var node = billNode(id);
    if (!node || !printRoot) return;
    printRoot.innerHTML = node.innerHTML;
    window.print();
  }

  document.querySelectorAll('[data-view]').forEach(function (button) {
    button.addEventListener('click', function () { showReceipt(button.getAttribute('data-view')); });
  });
  document.querySelectorAll('[data-print]').forEach(function (button) {
    button.addEventListener('click', function () { printReceipt(button.getAttribute('data-print')); });
  });
  var printButton = document.getElementById('receipt-print');
  if (printButton) {
    printButton.addEventListener('click', function () {
      if (!printRoot || !view) return;
      printRoot.innerHTML = view.innerHTML;
      window.print();
    });
  }
  document.querySelectorAll('[data-close]').forEach(function (button) {
    button.addEventListener('click', function () {
      var dialog = button.closest('dialog');
      if (dialog) dialog.close();
    });
  });
  [receipt, saleDialog, deleteDialog].forEach(function (dialog) {
    if (!dialog) return;
    dialog.addEventListener('click', function (event) {
      if (event.target === dialog) dialog.close();
    });
  });

  function addLine(itemId, qty) {
    if (!lineTemplate || !linesBox || linesBox.children.length >= 8) return;
    var row = lineTemplate.content.firstElementChild.cloneNode(true);
    var select = row.querySelector('select');
    var input = row.querySelector('input');
    if (select && itemId) select.value = String(itemId);
    if (input) input.value = String(qty || 1);
    row.querySelector('[data-remove-line]').addEventListener('click', function () {
      if (linesBox.children.length < 2) return;
      row.remove();
    });
    linesBox.appendChild(row);
  }

  function syncCustomer() {
    var payment = document.getElementById('sale-payment');
    var customer = document.getElementById('sale-customer');
    if (!payment || !customer) return;
    customer.required = payment.value === 'credit';
  }

  function fillSale(data, title, action) {
    if (!saleDialog) return;
    document.getElementById('sale-title').textContent = title;
    document.getElementById('sale-action').value = action;
    document.getElementById('sale-id').value = data.sale_id || data.id || '';
    document.getElementById('sale-date').value = data.date || '';
    document.getElementById('sale-table').value = String(data.table_id || '');
    document.getElementById('sale-payment').value = data.payment || 'cash';
    document.getElementById('sale-customer').value = String(data.customer_id || '');
    linesBox.innerHTML = '';
    var rows = data.lines && data.lines.length ? data.lines : [{ item_id: '', qty: 1 }];
    rows.forEach(function (line) { addLine(line.item_id, line.qty); });
    syncCustomer();
    saleDialog.showModal();
  }

  var paymentPick = document.getElementById('sale-payment');
  if (paymentPick) paymentPick.addEventListener('change', syncCustomer);

  var add = document.getElementById('add-sale');
  if (add) {
    add.addEventListener('click', function () {
      fillSale({ date: document.querySelector('.daybar input[name="date"]').value, payment: 'cash', lines: [{ item_id: '', qty: 1 }] }, 'Add a sale', 'manual');
    });
  }
  var addLineButton = document.getElementById('add-line');
  if (addLineButton) addLineButton.addEventListener('click', function () { addLine('', 1); });
  document.querySelectorAll('[data-edit]').forEach(function (button) {
    button.addEventListener('click', function () {
      var id = button.getAttribute('data-edit');
      var data = editor[id];
      if (!data) return;
      fillSale(data, 'Edit bill ' + String(id).padStart(4, '0'), 'update');
    });
  });
  document.querySelectorAll('[data-delete]').forEach(function (button) {
    button.addEventListener('click', function () {
      if (!deleteDialog) return;
      document.getElementById('delete-id').value = button.getAttribute('data-delete');
      document.getElementById('delete-label').textContent = button.getAttribute('data-bill') || '';
      deleteDialog.showModal();
    });
  });
  var stickyNode = document.getElementById('sale-sticky');
  if (stickyNode) {
    var sticky = JSON.parse(stickyNode.textContent || '{}');
    fillSale(sticky, sticky.action === 'update' ? 'Edit bill' : 'Add a sale', sticky.action || 'manual');
  }
})();
</script>
<?php layout_footer(); ?>
