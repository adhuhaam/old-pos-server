<?php

require __DIR__ . '/_init.php';
require_owner_page();

[$year, $month] = read_period();
$error = null;
$report = null;
$items = [];
$itemReport = null;
$itemError = null;
$itemId = (int) ($_GET['item'] ?? 0);
$fromRaw = trim((string) ($_GET['from'] ?? ''));
$toRaw = trim((string) ($_GET['to'] ?? ''));
$itemAsked = array_key_exists('item', $_GET);
[$monthStart, $monthEnd] = month_bounds($year, $month);
$fromInput = $monthStart;
$toInput = $monthEnd;
if ($fromRaw !== '') {
    try {
        $fromInput = parse_date($fromRaw);
    } catch (AppError $ignored) {
    }
}
if ($toRaw !== '') {
    try {
        $toInput = parse_date($toRaw);
    } catch (AppError $ignored) {
    }
} elseif ($itemAsked) {
    $toInput = '';
}

try {
    $report = book_month_report($year, $month);
    $items = list_items();
    if ($itemAsked) {
        if ($itemId <= 0) {
            throw new AppError('Choose an item.');
        }
        if ($fromRaw === '') {
            throw new AppError('Enter a date.');
        }
        $fromDate = parse_date($fromRaw);
        $toDate = $toRaw === '' ? $fromDate : parse_date($toRaw);
        $itemReport = book_item_sales($itemId, $fromDate, $toDate);
    }
} catch (AppError $errorObject) {
    if ($itemAsked && $report !== null) {
        $itemError = $errorObject->getMessage();
    } else {
        $error = $errorObject->getMessage();
    }
} catch (PDOException $errorObject) {
    $error = 'Database error. Start MySQL and import database/schema.sql.';
}

$itemGroups = [];
foreach ($items as $item) {
    $itemGroups[$item['category_name']][] = $item;
}

layout_header('Reports');
$months = book_months();
$prevMonth = $month === 1 ? 12 : $month - 1;
$prevYear = $month === 1 ? $year - 1 : $year;
$nextMonth = $month === 12 ? 1 : $month + 1;
$nextYear = $month === 12 ? $year + 1 : $year;
$runningPeriod = period_input((int) date('Y'), (int) date('n'));
$shownPeriod = period_input($year, $month);
$monthNav = '<div class="quick">';
if ($shownPeriod !== $runningPeriod) {
    $monthNav .= '<a class="button" href="reports.php?period=' . h($runningPeriod) . '">This month</a>';
}
$monthNav .= '<a class="button ghost" href="reports.php?period=' . h(period_input($prevYear, $prevMonth)) . '">Previous</a><a class="button ghost" href="reports.php?period=' . h(period_input($nextYear, $nextMonth)) . '">Next</a></div>';
?>
<?php page_head('Reports', isset($months[$month]) ? h($months[$month] . ' ' . $year) : '', $monthNav); ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<form class="panel" method="get">
  <div class="row">
    <label>Month
      <input type="month" name="period" required value="<?= h($shownPeriod) ?>">
    </label>
    <button type="submit">Show</button>
  </div>
</form>
<h2>Item sales</h2>
<p class="muted note">Choose an item and one date, or a start and end date. Open credit is counted on the day the bill was opened. Paid bills are counted on the day they were closed.</p>
<form class="panel" method="get">
  <input type="hidden" name="period" value="<?= h($shownPeriod) ?>">
  <div class="row">
    <label>Item
      <select name="item" required>
        <option value="">Choose an item</option>
        <?php foreach ($itemGroups as $categoryName => $group): ?>
          <optgroup label="<?= h($categoryName) ?>">
            <?php foreach ($group as $item): ?>
              <option value="<?= (int) $item['id'] ?>"<?= $itemId === (int) $item['id'] ? ' selected' : '' ?>><?= h($item['name']) ?><?= $item['active'] ? '' : ' · Hidden' ?></option>
            <?php endforeach; ?>
          </optgroup>
        <?php endforeach; ?>
      </select>
    </label>
    <label>From
      <input type="date" name="from" required value="<?= h($fromInput) ?>">
    </label>
    <label>To
      <input type="date" name="to" value="<?= h($toInput) ?>">
    </label>
    <button type="submit">Show item</button>
  </div>
</form>
<?php if ($itemError): ?><p class="error"><?= h($itemError) ?></p><?php endif; ?>
<?php if ($itemReport): ?>
  <?php
    $itemSpan = $itemReport['from'] === $itemReport['to']
        ? format_date($itemReport['from'])
        : format_date($itemReport['from']) . ' – ' . format_date($itemReport['to']);
  ?>
  <p class="muted"><?= h($itemReport['item_name']) ?> · <?= h($itemSpan) ?></p>
  <section class="cards">
    <article class="card"><span>Quantity</span><strong><?= (int) $itemReport['qty'] ?></strong></article>
    <article class="card"><span>Amount</span><strong><?= h(money_label($itemReport['amount'])) ?></strong></article>
    <article class="card"><span>Bills</span><strong><?= (int) $itemReport['bills'] ?></strong></article>
  </section>
  <?php if ($itemReport['days'] === []): ?>
    <p class="empty">No sales of this item in those dates.</p>
  <?php else: ?>
    <section class="sheet">
      <header class="sheet-head"><h2>By date</h2></header>
      <table class="stack">
        <thead><tr><th>Date</th><th>Quantity</th><th>Amount</th><th>Bills</th></tr></thead>
        <tbody>
        <?php foreach ($itemReport['days'] as $day): ?>
          <tr>
            <td data-label="Date"><?= h(format_date($day['date'])) ?></td>
            <td class="num" data-label="Quantity"><?= (int) $day['qty'] ?></td>
            <td class="num" data-label="Amount"><?= h(money_label($day['amount'])) ?></td>
            <td class="num" data-label="Bills"><?= (int) $day['bills'] ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <header class="sheet-head"><h2>Bills with this item</h2></header>
      <table class="stack">
        <thead><tr><th>When</th><th>Table</th><th>Customer</th><th>Payment</th><th>Quantity</th><th>Amount</th></tr></thead>
        <tbody>
        <?php foreach ($itemReport['sales'] as $sale): ?>
          <tr>
            <td data-label="When"><?= h(format_when($sale['when_at'])) ?></td>
            <td data-label="Table"><?= h($sale['table_name']) ?></td>
            <td data-label="Customer"><?= h($sale['customer_name']) ?></td>
            <td data-label="Payment"><?= h($sale['payment_label']) ?><?php if (!empty($sale['open_credit'])): ?> <span class="pay-pill credit">Open</span><?php elseif (!empty($sale['held'])): ?> <span class="pay-pill credit">Held</span><?php endif; ?></td>
            <td class="num" data-label="Quantity"><?= (int) $sale['qty'] ?></td>
            <td class="num" data-label="Amount"><?= h(money_label($sale['amount'])) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  <?php endif; ?>
<?php endif; ?>
<?php if ($report): ?>
<section class="cards">
  <article class="card"><span>Income</span><strong><?= h(money_label($report['income'])) ?></strong></article>
  <article class="card"><span>Expenses</span><strong><?= h(money_label($report['expenses'])) ?></strong></article>
  <article class="card profit"><span>Profit</span><strong><?= h(money_label($report['profit'])) ?></strong></article>
  <article class="card bank"><span>POS</span><strong><?= h(money_label($report['pos'])) ?></strong></article>
  <article class="card"><span>Recorded days</span><strong><?= h(money_label($report['recorded'])) ?></strong></article>
  <article class="card credit"><span>Credit/Hold this month</span><strong><?= h(money_label($report['credit_month'])) ?></strong></article>
  <article class="card credit"><span>Credit/Hold still open</span><strong><?= h(money_label($report['credit_open'])) ?></strong></article>
</section>
<p class="muted note">Income is register bills plus the past days saved in Books. Recurring expenses are counted for each month that has started. Credit/Hold this month is listed on the day the bill was opened. It is added to income in the month it is paid, as cash or bank.</p>
<section class="sheet">
<header class="sheet-head"><h2>Expenses</h2></header>
<table class="stack">
  <thead><tr><th>Category</th><th>Amount</th></tr></thead>
  <tbody>
    <tr><td data-label="Category">Salaries</td><td class="num" data-label="Amount"><?= h(money_label($report['salaries'])) ?></td></tr>
    <?php foreach ($report['categories'] as $category): ?>
      <tr>
        <td data-label="Category"><?= h($category['name']) ?></td>
        <td class="num" data-label="Amount"><?= h(money_label($category['total'])) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</section>
<section class="sheet">
<header class="sheet-head"><h2>Daily sales</h2></header>
<?php if ($report['days'] === []): ?>
  <p class="empty">No sales this month.</p>
<?php else: ?>
  <table class="stack">
    <thead><tr><th>Date</th><th>Cash</th><th>Bank</th><th>Credit/Hold</th></tr></thead>
    <tbody>
    <?php foreach ($report['days'] as $day): ?>
      <tr>
        <td data-label="Date"><?= h(format_date($day['date'])) ?></td>
        <td class="num" data-label="Cash"><?= h(money_label($day['cash'])) ?></td>
        <td class="num" data-label="Bank"><?= h(money_label($day['bank'])) ?></td>
        <td class="num" data-label="Credit/Hold"><?= h(money_label($day['credit'])) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
</section>
<section class="sheet">
<header class="sheet-head"><h2>Sales</h2></header>
<?php if ($report['sales'] === []): ?>
  <p class="empty">No POS sales this month.</p>
<?php else: ?>
  <table class="stack">
    <thead><tr><th>When</th><th>Table</th><th>Customer</th><th>Payment</th><th>Amount</th></tr></thead>
    <tbody>
    <?php foreach ($report['sales'] as $sale): ?>
      <tr>
        <td data-label="When"><?= h(format_when($sale['when_at'])) ?></td>
        <td data-label="Table"><?= h($sale['table_name']) ?></td>
        <td data-label="Customer"><?= h($sale['customer_name']) ?></td>
        <td data-label="Payment"><?= h($sale['payment_label']) ?><?php if (!empty($sale['open_credit'])): ?> <span class="pay-pill credit">Open</span><?php elseif (!empty($sale['held'])): ?> <span class="pay-pill credit">Held</span><?php endif; ?></td>
        <td class="num" data-label="Amount"><?= h(money_label($sale['total'])) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
</section>
<section class="sheet">
<header class="sheet-head"><h2>Expense lines</h2></header>
<?php if ($report['expense_rows'] === []): ?>
  <p class="empty">No expenses this month.</p>
<?php else: ?>
  <table class="stack">
    <thead><tr><th>Date</th><th>Category</th><th>Kind</th><th>Amount</th><th>Note</th><th>Proof</th></tr></thead>
    <tbody>
    <?php foreach ($report['expense_rows'] as $row): ?>
      <tr>
        <td data-label="Date"><?= h(format_date($row['spent_on'])) ?></td>
        <td data-label="Category"><?= h($row['category_name']) ?></td>
        <td data-label="Kind"><?= h($row['kind']) ?></td>
        <td class="num" data-label="Amount"><?= h(money_label((string) $row['amount'])) ?></td>
        <td data-label="Note"><?= h($row['note']) ?></td>
        <td data-label="Proof"><?php if (trim((string) ($row['proof'] ?? '')) !== ''): ?><a href="media.php?proof=<?= (int) $row['id'] ?>" target="_blank" rel="noopener">Open</a><?php endif; ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
</section>
<?php endif; ?>
<?php layout_footer(); ?>
