<?php

require __DIR__ . '/_init.php';
require_owner_page();

$error = null;
$customers = [];
$pending = [];
$focus = null;
$focusId = (int) ($_GET['id'] ?? $_POST['focus'] ?? 0);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $action = (string) ($_POST['action'] ?? '');
        if ($action === 'create') {
            if (!is_superadmin_session()) {
                throw new AppError('Only a superadmin can add a customer.', 403);
            }
            $created = save_customer((string) ($_POST['name'] ?? ''), (string) ($_POST['phone'] ?? ''));
            $limit = trim((string) ($_POST['credit_limit'] ?? ''));
            if ($limit !== '') {
                save_credit_limit($created, $limit);
            }
            flash_set('Customer added.');
            redirect_to('customers.php?id=' . $created);
        } elseif ($action === 'update') {
            if (!is_superadmin_session()) {
                throw new AppError('Only a superadmin can edit a customer.', 403);
            }
            $edited = (int) ($_POST['id'] ?? 0);
            update_customer($edited, (string) ($_POST['name'] ?? ''), (string) ($_POST['phone'] ?? ''));
            flash_set('Customer saved.');
            redirect_to('customers.php?id=' . $edited);
        } elseif ($action === 'approve' || $action === 'reject') {
            if (!is_superadmin_session()) {
                throw new AppError('Only a superadmin can check a payment here.', 403);
            }
            $saleId = (int) ($_POST['sale_id'] ?? 0);
            if ($action === 'approve') {
                approve_customer_payment($saleId);
                flash_set('Payment approved.');
            } else {
                reject_customer_payment($saleId);
                flash_set('Payment sent back.');
            }
            redirect_to('customers.php' . ($focusId > 0 ? '?id=' . $focusId : ''));
        } else {
            if (!is_superadmin_session()) {
                throw new AppError('Only a superadmin can set a credit limit.', 403);
            }
            save_credit_limit((int) ($_POST['id'] ?? 0), (string) ($_POST['credit_limit'] ?? ''));
            flash_set('Credit limit saved.');
            redirect_to('customers.php?id=' . (int) ($_POST['id'] ?? 0));
        }
    }
    $customers = list_customers();
    $pending = pending_customer_payments();
} catch (AppError $errorObject) {
    $error = $errorObject->getMessage();
    try {
        $customers = list_customers();
        $pending = pending_customer_payments();
    } catch (Throwable $ignored) {
        $customers = [];
        $pending = [];
    }
} catch (PDOException $errorObject) {
    $error = 'Database error. Start MySQL and import database/schema.sql.';
}

if ($focusId > 0) {
    foreach ($customers as $customer) {
        if ((int) $customer['id'] === $focusId) {
            $focus = $customer;
            break;
        }
    }
}

$openCents = 0;
$pendingCount = count($pending);
foreach ($customers as $customer) {
    $openCents += to_cents((string) $customer['open_total']);
}

$focusBills = $focus ? customer_bill_rows((int) $focus['id']) : [];

layout_header('Customers');
?>
<?php page_head('Customers', count($customers) . ' customers · ' . h(money_label(from_cents($openCents))) . ' still open', '<a class="button" href="export.php?kind=customers">Export customers</a>'); ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<?php if (is_superadmin_session()): ?>
<form class="panel" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="create">
  <h2>New customer</h2>
  <div class="row">
    <label>Name <input name="name" maxlength="80" required value="<?= h((string) ($_POST['name'] ?? '')) ?>"></label>
    <label>Phone <input name="phone" inputmode="tel" required value="<?= h((string) ($_POST['phone'] ?? '')) ?>"></label>
    <label>Credit limit <input name="credit_limit" inputmode="decimal" placeholder="Settings default" value="<?= h((string) ($_POST['credit_limit'] ?? (new_customer_credit_limit() ?? ''))) ?>"></label>
    <button type="submit">Add customer</button>
  </div>
</form>
<?php endif; ?>

<?php if ($pending): ?>
<section class="sheet">
  <header class="sheet-head">
    <h2>Payments to check <span class="muted"><?= $pendingCount ?></span></h2>
    <p>The customer paid by bank and sent a photo. Approving marks the bill paid.</p>
  </header>
  <div class="pay-grid">
    <?php foreach ($pending as $payment): ?>
      <article class="pay-card">
        <p class="bill-no">Bill <?= h($payment['bill_no']) ?> · <?= h($payment['table_name']) ?></p>
        <strong><?= h($payment['customer_name']) ?></strong>
        <p class="muted"><?= h($payment['customer_phone']) ?> · <?= $payment['status'] === 'credit' ? 'Credit/Hold' : 'Open' ?></p>
        <p><strong><?= h(money_label($payment['total'])) ?></strong></p>
        <?php if ($payment['has_slip']): ?>
          <a class="slip" href="media.php?slip=<?= (int) $payment['id'] ?>" target="_blank" rel="noopener"><img src="media.php?slip=<?= (int) $payment['id'] ?>" alt="Payment photo"></a>
        <?php else: ?>
          <p class="off">No payment photo</p>
        <?php endif; ?>
        <?php if (is_superadmin_session()): ?>
        <div class="pay-actions">
          <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="approve">
            <input type="hidden" name="sale_id" value="<?= (int) $payment['id'] ?>">
            <input type="hidden" name="focus" value="<?= (int) $payment['customer_id'] ?>">
            <button type="submit">Approve</button>
          </form>
          <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="reject">
            <input type="hidden" name="sale_id" value="<?= (int) $payment['id'] ?>">
            <input type="hidden" name="focus" value="<?= (int) $payment['customer_id'] ?>">
            <button class="ghost" type="submit">Send back</button>
          </form>
        </div>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php if ($focus): ?>
<section class="sheet">
  <header class="sheet-head">
    <h2><?= h($focus['name']) ?></h2>
    <p><?= h($focus['phone']) ?> · <?= (int) $focus['open_bills'] ?> credit/hold · <?= h(money_label($focus['open_total'])) ?> still open<?php if ((int) $focus['pending_bills'] > 0): ?> · <?= (int) $focus['pending_bills'] ?> waiting<?php endif; ?></p>
  </header>
  <div class="focus-limit">
    <?php if (is_superadmin_session()): ?>
    <?php
      $editName = (string) $focus['name'];
      $editPhone = (string) $focus['phone'];
      if ($error && (string) ($_POST['action'] ?? '') === 'update' && (int) ($_POST['id'] ?? 0) === (int) $focus['id']) {
          $editName = (string) ($_POST['name'] ?? '');
          $editPhone = (string) ($_POST['phone'] ?? '');
      }
    ?>
    <form class="customer-edit" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" value="<?= (int) $focus['id'] ?>">
      <label>Name <input name="name" maxlength="80" required value="<?= h($editName) ?>"></label>
      <label>Phone <input name="phone" inputmode="tel" required value="<?= h($editPhone) ?>"></label>
      <button type="submit">Save customer</button>
    </form>
    <form class="limit-form" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int) $focus['id'] ?>">
      <label>Credit limit
        <input name="credit_limit" inputmode="decimal" placeholder="No limit" value="<?= $focus['credit_limit'] === null ? '' : h(number_format((float) $focus['credit_limit'], 2, '.', ',')) ?>">
      </label>
      <button type="submit">Save limit</button>
    </form>
    <?php else: ?>
      <p>Credit limit: <?= $focus['credit_limit'] === null ? 'No limit' : h(money_label($focus['credit_limit'])) ?></p>
    <?php endif; ?>
    <p class="muted">Left: <?= $focus['left'] === null ? 'No limit' : h(money_label($focus['left'])) ?></p>
  </div>
  <?php if ($focusBills): ?>
  <div class="books">
  <table class="stack">
    <thead>
      <tr>
        <th>Bill</th>
        <th>State</th>
        <th class="num">Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($focusBills as $bill): ?>
        <?php
          $state = 'Paid';
          if ($bill['pay_review'] === 'pending') {
              $state = 'Waiting for approval';
          } elseif ($bill['status'] === 'credit') {
              $state = 'Credit/Hold';
          } elseif ($bill['status'] === 'open') {
              $state = 'Open';
          } elseif ($bill['payment_label'] !== '') {
              $state = $bill['payment_label'];
          }
        ?>
        <tr>
          <td data-label="Bill"><strong>Bill <?= h($bill['bill_no']) ?></strong><div class="muted"><?= h($bill['table_name']) ?> · <?= (int) $bill['item_count'] ?> items</div></td>
          <td data-label="State"><?= h($state) ?><?php if ($bill['has_slip']): ?> · <a href="media.php?slip=<?= (int) $bill['id'] ?>" target="_blank" rel="noopener">Photo</a><?php endif; ?></td>
          <td class="num" data-label="Amount"><?= h(money_label($bill['total'])) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <?php else: ?>
    <p class="empty">No bills for this customer.</p>
  <?php endif; ?>
</section>
<?php endif; ?>

<section class="sheet">
  <header class="sheet-head">
    <h2>Accounts</h2>
    <p>Leave the limit blank for no cap. Use amounts like 1,000.00.</p>
  </header>
  <label class="find">Find
    <input data-find="#customers" placeholder="Name or phone">
  </label>
  <div class="books">
  <table class="stack" id="customers" data-list>
    <thead>
      <tr>
        <th>Customer</th>
        <th class="num">Still open</th>
        <th class="num">Left</th>
        <th>Credit limit</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($customers as $customer): ?>
        <?php
          $over = $customer['credit_limit'] !== null && to_cents((string) $customer['left']) < 0;
          $limitValue = $customer['credit_limit'] === null
            ? ''
            : number_format((float) $customer['credit_limit'], 2, '.', ',');
        ?>
        <tr class="<?= $over ? 'over' : '' ?>" data-text="<?= h($customer['name'] . ' ' . $customer['phone']) ?>">
          <td data-label="Customer">
            <strong><a href="customers.php?id=<?= (int) $customer['id'] ?>"><?= h($customer['name']) ?></a></strong>
            <div class="muted"><?= h($customer['phone']) ?> · <?= (int) $customer['open_bills'] ?> open<?php if ((int) $customer['pending_bills'] > 0): ?> · payment to check<?php endif; ?></div>
          </td>
          <td class="num" data-label="Still open"><?= h(money_label($customer['open_total'])) ?></td>
          <td class="num" data-label="Left"><?= $customer['left'] === null ? 'No limit' : h(money_label($customer['left'])) ?></td>
          <td data-label="Credit limit">
            <?php if (is_superadmin_session()): ?>
            <form class="limit-form" method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $customer['id'] ?>">
              <input name="credit_limit" inputmode="decimal" placeholder="No limit" value="<?= h($limitValue) ?>">
              <button type="submit">Save</button>
            </form>
            <?php else: ?>
              <?= $limitValue === '' ? 'No limit' : h($limitValue) ?>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <p class="empty" data-none="#customers" hidden>No customer matches.</p>
</section>
<?php if (!$customers): ?><p class="empty">No customers yet.</p><?php endif; ?>
<?php layout_footer(); ?>
