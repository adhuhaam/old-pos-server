<?php

require __DIR__ . '/_init.php';
require_owner_page();

if ($_GET === [] && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $year = (int) date('Y');
    $month = (int) date('n');
} else {
    $periodRaw = trim((string) ($_REQUEST['period'] ?? ''));
    if (preg_match('/^(\d{4})-(\d{2})$/', $periodRaw, $periodMatch)) {
        $year = (int) $periodMatch[1];
        $month = (int) $periodMatch[2];
    } else {
        $year = (int) ($_REQUEST['year'] ?? date('Y'));
        if ($year < 2000 || $year > 2100) {
            $year = (int) date('Y');
        }
        $month = 0;
    }
}
$category = (string) ($_GET['category'] ?? '');
$error = null;
$rows = [];
$editing = null;

try {
    if (isset($_GET['id']) && $_GET['id'] !== '') {
        $editing = book_expense((int) $_GET['id']);
        if ($editing === null) {
            $error = 'Expense not found.';
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $action = (string) ($_POST['action'] ?? '');
        if ($action === 'delete') {
            book_delete_expense((int) ($_POST['id'] ?? 0));
            $category = (string) ($_POST['category'] ?? '');
            flash_set('Expense removed.');
        } elseif ($action === 'category') {
            book_save_category((string) ($_POST['name'] ?? ''));
            flash_set('Category added.');
        } elseif ($action === 'recurring') {
            $savedId = book_save_recurring(
                (int) ($_POST['id'] ?? 0) > 0 ? (int) $_POST['id'] : null,
                (string) ($_POST['category_id'] ?? ''),
                (string) ($_POST['kind'] ?? ''),
                (string) ($_POST['amount'] ?? ''),
                (string) ($_POST['note'] ?? '')
            );
            if ($month >= 1 && $month <= 12 && array_key_exists('month_amount', $_POST)) {
                book_save_recurring_month($savedId, $year, $month, (string) $_POST['month_amount']);
            }
            flash_set('Recurring expense saved.');
        } elseif ($action === 'delete-recurring') {
            book_delete_recurring((int) ($_POST['id'] ?? 0));
            flash_set('Recurring expense removed.');
        } else {
            $id = (int) ($_POST['id'] ?? 0);
            book_save_expense(
                (string) ($_POST['spent_on'] ?? ''),
                (string) ($_POST['category_id'] ?? ''),
                (string) ($_POST['kind'] ?? ''),
                (string) ($_POST['amount'] ?? ''),
                (string) ($_POST['note'] ?? ''),
                $id > 0 ? $id : null,
                isset($_FILES['proof']) ? $_FILES['proof'] : null,
                isset($_POST['remove_proof'])
            );
            $spent = parse_date((string) ($_POST['spent_on'] ?? ''));
            $year = (int) substr($spent, 0, 4);
            $month = (int) substr($spent, 5, 2);
            $category = (string) ($_POST['category'] ?? '');
            flash_set($id > 0 ? 'Expense updated.' : 'Expense saved.');
        }
        $back = $month >= 1 && $month <= 12 ? 'period=' . period_input($year, $month) : 'year=' . $year;
        redirect_to('expenses.php?' . $back . ($category !== '' ? '&category=' . urlencode($category) : ''));
    }
    $rows = book_expenses($year, $category, $month);
} catch (AppError $errorObject) {
    $error = $errorObject->getMessage();
    $rows = book_expenses($year, $category, $month);
    if (isset($_POST['id']) && (int) $_POST['id'] > 0) {
        $editing = book_expense((int) $_POST['id']);
    }
} catch (PDOException $errorObject) {
    $error = 'Database error. Start MySQL and import database/schema.sql.';
}

layout_header('Expenses');
$categories = book_categories();
$months = book_months();
$listedCents = 0;
foreach ($rows as $row) {
    $listedCents += to_cents((string) $row['amount']);
}
$periodField = $month >= 1 && $month <= 12 ? period_input($year, $month) : '';
$listQuery = $periodField !== '' ? 'period=' . $periodField : 'year=' . (int) $year;
$categoryQuery = $category !== '' ? '&amp;category=' . urlencode($category) : '';
$spentValue = $editing
    ? substr((string) $editing['spent_on'], 0, 10)
    : ($periodField !== '' && $periodField === period_input((int) date('Y'), (int) date('n'))
        ? date('Y-m-d')
        : ($periodField !== '' ? $periodField . '-01' : date('Y-m-d')));
$heading = ($month >= 1 && isset($months[$month]) ? $months[$month] . ' ' : '') . $year . ' · ' . money_label(from_cents($listedCents));
?>
<?php page_head('Expenses', h($heading)); ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<form class="panel" method="get">
  <div class="row">
    <label>Year
      <input type="number" name="year" min="2000" max="2100" value="<?= (int) $year ?>" required>
    </label>
    <label>Month
      <input type="month" name="period" value="<?= h($periodField) ?>">
    </label>
    <label>Category
      <select name="category">
        <option value="">All</option>
        <?php foreach ($categories as $key => $label): ?>
          <option value="<?= h($key) ?>"<?= $category === $key ? ' selected' : '' ?>><?= h($label) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <button type="submit">Show</button>
    <?php if ($month !== 0): ?><a class="button ghost" href="expenses.php?year=<?= (int) $year ?><?= $categoryQuery ?>">All year</a><?php endif; ?>
    <?php if ($periodField !== period_input((int) date('Y'), (int) date('n'))): ?><a class="button ghost" href="expenses.php?period=<?= h(period_input((int) date('Y'), (int) date('n'))) ?><?= $categoryQuery ?>">This month</a><?php endif; ?>
  </div>
</form>
<?php if (can_manage()): ?>
<form class="panel" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="category">
  <input type="hidden" name="year" value="<?= (int) $year ?>">
  <?php if ($periodField !== ''): ?><input type="hidden" name="period" value="<?= h($periodField) ?>"><?php endif; ?>
  <div class="row">
    <label>New category <input name="name" required></label>
    <button type="submit">Add category</button>
  </div>
</form>
<?php endif; ?>
<h2>Every month</h2>
<p class="muted">Add rent, salaries, and any other cost that repeats. It is counted in each month that has started. Leave this month blank to keep the usual amount, or enter 0 to skip that month. Supplies and utilities stay as dated expenses below. Sales on Books, Reports, and Today are the register bills plus the past days already saved.</p>
<?php
  $recurringRows = book_recurring_list();
  $monthAmounts = $month >= 1 && $month <= 12 ? book_recurring_month_amounts($year, $month) : [];
?>
<?php if ($recurringRows === []): ?>
  <p class="empty">No recurring expenses yet.</p>
<?php endif; ?>
<?php foreach ($recurringRows as $item): ?>
  <?php $monthValue = array_key_exists($item['id'], $monthAmounts) ? $monthAmounts[$item['id']] : ''; ?>
  <?php if (can_manage()): ?>
  <form class="panel" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="recurring">
    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
    <input type="hidden" name="year" value="<?= (int) $year ?>">
    <?php if ($periodField !== ''): ?><input type="hidden" name="period" value="<?= h($periodField) ?>"><?php endif; ?>
    <div class="row">
      <label>Category
        <select name="category_id" required>
          <?php foreach ($categories as $key => $label): ?>
            <option value="<?= h($key) ?>"<?= (string) $item['category_id'] === (string) $key ? ' selected' : '' ?>><?= h($label) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Kind <input name="kind" value="<?= h($item['kind']) ?>"></label>
      <label>Usual amount <input name="amount" inputmode="decimal" required value="<?= h($item['amount']) ?>"></label>
      <label>Note <input name="note" value="<?= h($item['note']) ?>"></label>
      <?php if ($month >= 1 && $month <= 12): ?>
        <label><?= h($months[$month]) ?> amount <input name="month_amount" inputmode="decimal" value="<?= h($monthValue) ?>" placeholder="<?= h($item['amount']) ?>"></label>
      <?php endif; ?>
      <button type="submit">Save</button>
    </div>
  </form>
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="delete-recurring">
    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
    <input type="hidden" name="year" value="<?= (int) $year ?>">
    <?php if ($periodField !== ''): ?><input type="hidden" name="period" value="<?= h($periodField) ?>"><?php endif; ?>
    <button class="ghost" type="submit">Remove <?= h($item['category_name']) ?><?= $item['kind'] !== '' ? ' · ' . h($item['kind']) : '' ?></button>
  </form>
  <?php else: ?>
  <article class="panel"><p><?= h($item['category_name']) ?><?= $item['kind'] !== '' ? ' · ' . h($item['kind']) : '' ?> · <?= h(money_label($item['amount'])) ?><?php if ($monthValue !== ''): ?> · this month <?= h(money_label($monthValue)) ?><?php endif; ?></p></article>
  <?php endif; ?>
<?php endforeach; ?>
<?php if (can_manage()): ?>
<form class="panel" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="recurring">
  <input type="hidden" name="year" value="<?= (int) $year ?>">
  <?php if ($periodField !== ''): ?><input type="hidden" name="period" value="<?= h($periodField) ?>"><?php endif; ?>
  <h2>Add a recurring expense</h2>
  <div class="row">
    <label>Category
      <select name="category_id" required>
        <?php foreach ($categories as $key => $label): ?>
          <option value="<?= h($key) ?>"><?= h($label) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Kind <input name="kind" placeholder="Rent"></label>
    <label>Usual amount <input name="amount" inputmode="decimal" required></label>
    <label>Note <input name="note"></label>
    <?php if ($month >= 1 && $month <= 12): ?>
      <label><?= h($months[$month]) ?> amount <input name="month_amount" inputmode="decimal" placeholder="Usual amount"></label>
    <?php endif; ?>
    <button type="submit">Add every month</button>
  </div>
</form>
<?php endif; ?>
<?php if (can_manage()): ?>
<form class="panel" method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <input type="hidden" name="year" value="<?= (int) $year ?>">
  <?php if ($periodField !== ''): ?><input type="hidden" name="period" value="<?= h($periodField) ?>"><?php endif; ?>
  <input type="hidden" name="category" value="<?= h($category) ?>">
  <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int) $editing['id'] ?>"><?php endif; ?>
  <h2><?= $editing ? 'Edit expense' : 'Add expense' ?></h2>
  <div class="row">
    <label>Date <input type="date" name="spent_on" required value="<?= h($spentValue) ?>"></label>
    <label>Category
      <select name="category_id" required>
        <?php foreach ($categories as $key => $label): ?>
          <option value="<?= h($key) ?>"<?= $editing && (string) $editing['category_id'] === (string) $key ? ' selected' : '' ?>><?= h($label) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Kind <input name="kind" value="<?= h((string) ($editing['kind'] ?? '')) ?>"></label>
    <label>Amount <input name="amount" inputmode="decimal" required value="<?= h($editing ? number_format((float) $editing['amount'], 2, '.', ',') : '') ?>"></label>
    <label>Note <input name="note" value="<?= h((string) ($editing['note'] ?? '')) ?>"></label>
    <label>Proof
      <input type="file" name="proof" accept="image/*,.pdf">
    </label>
    <button type="submit"><?= $editing ? 'Save changes' : 'Add expense' ?></button>
    <?php if ($editing): ?><a class="button ghost" href="expenses.php?<?= h($listQuery) ?><?= $categoryQuery ?>">Cancel</a><?php endif; ?>
  </div>
  <?php if ($editing && trim((string) $editing['proof']) !== ''): ?>
    <p class="muted">Current proof: <a href="media.php?proof=<?= (int) $editing['id'] ?>" target="_blank" rel="noopener">Open file</a></p>
    <label class="check"><input type="checkbox" name="remove_proof"> Remove the current proof</label>
  <?php else: ?>
    <p class="muted">Optional photo or PDF, useful for a supply receipt. Up to 8 MB.</p>
  <?php endif; ?>
</form>
<?php endif; ?>
<section class="sheet">
  <header class="sheet-head"><h2>Expense lines</h2></header>
<?php if ($rows === []): ?>
  <p class="empty">No expenses for this view.</p>
<?php else: ?>
  <div class="toolbar">
    <label class="find">Find
      <input data-find="#expense-list" placeholder="Category, kind, or note">
    </label>
  </div>
  <table class="stack">
    <thead><tr><th>Date</th><th>Category</th><th>Kind</th><th>Amount</th><th>Note</th><th>Proof</th><th></th></tr></thead>
    <tbody id="expense-list" data-list>
    <?php foreach ($rows as $row): ?>
      <?php $label = (string) ($row['category_name'] ?? $row['category']); ?>
      <tr data-text="<?= h($label . ' ' . $row['kind'] . ' ' . $row['note']) ?>">
        <td data-label="Date"><?= h(format_date($row['spent_on'])) ?></td>
        <td data-label="Category"><?= h($label) ?></td>
        <td data-label="Kind"><?= h($row['kind']) ?></td>
        <td class="num" data-label="Amount"><?= h(money_label((string) $row['amount'])) ?></td>
        <td data-label="Note"><?= h($row['note']) ?></td>
        <td data-label="Proof"><?php if (trim((string) ($row['proof'] ?? '')) !== ''): ?><a href="media.php?proof=<?= (int) $row['id'] ?>" target="_blank" rel="noopener">Open</a><?php endif; ?></td>
        <td class="actions" data-label="">
          <?php if (can_manage()): ?>
          <a class="button ghost" href="expenses.php?<?= h($listQuery) ?>&amp;id=<?= (int) $row['id'] ?><?= $categoryQuery ?>">Edit</a>
          <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="year" value="<?= (int) $year ?>">
            <?php if ($periodField !== ''): ?><input type="hidden" name="period" value="<?= h($periodField) ?>"><?php endif; ?>
            <input type="hidden" name="category" value="<?= h($category) ?>">
            <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
            <button class="ghost" type="submit">Remove</button>
          </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <p class="empty" data-none="#expense-list" hidden>Nothing matches that search.</p>
<?php endif; ?>
</section>
<?php layout_footer(); ?>
