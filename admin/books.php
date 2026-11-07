<?php

require __DIR__ . '/_init.php';
require_owner_page();

$year = (int) ($_GET['year'] ?? $_POST['year'] ?? date('Y'));
if (array_key_exists('month', $_GET) || array_key_exists('month', $_POST)) {
    $month = (int) ($_GET['month'] ?? $_POST['month'] ?? 0);
} elseif ($year === (int) date('Y')) {
    $month = (int) date('n');
} else {
    $month = 0;
}
$error = null;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $year = (int) ($_POST['year'] ?? $year);
        $month = (int) ($_POST['month'] ?? $month);
        $action = (string) ($_POST['action'] ?? '');
        if ($action === 'day') {
            $saved = parse_date((string) ($_POST['sale_date'] ?? ''));
            book_save_day($saved, (string) ($_POST['transfer'] ?? ''), (string) ($_POST['cash'] ?? ''));
            $year = (int) substr($saved, 0, 4);
            $month = (int) substr($saved, 5, 2);
            flash_set('Day sales saved.');
        } elseif ($action === 'partner') {
            $id = (int) ($_POST['id'] ?? 0);
            book_save_partner($id > 0 ? $id : null, (string) ($_POST['name'] ?? ''), (string) ($_POST['share_percent'] ?? ''));
            flash_set('Partner saved.');
        } elseif ($action === 'delete-partner') {
            book_delete_partner((int) ($_POST['id'] ?? 0));
            flash_set('Partner removed.');
        }
        redirect_to('books.php?year=' . $year . ($month >= 1 && $month <= 12 ? '&month=' . $month : ''));
    }
    $report = book_year_report($year);
    $days = $month >= 1 && $month <= 12 ? book_days_for_month($year, $month) : [];
} catch (AppError $errorObject) {
    $error = $errorObject->getMessage();
    $report = book_year_report($year);
    $days = [];
} catch (PDOException $errorObject) {
    $error = 'Database error. Start MySQL and import database/schema.sql.';
    $report = null;
    $days = [];
}

$editingDay = null;
if (isset($_GET['day']) && trim((string) $_GET['day']) !== '') {
    try {
        $editingDay = book_day_row((string) $_GET['day']);
        if ($editingDay === null) {
            $error = 'That day is not recorded yet. Enter it below.';
        }
    } catch (AppError $errorObject) {
        $error = $errorObject->getMessage();
    }
}
layout_header('Books');
$months = book_months();
$monthOrder = month_order($year);
$runningMonth = $year === (int) date('Y') ? (int) date('n') : 0;
?>
<?php page_head('Books', (string) (int) $year, '<a class="button ghost" href="reports.php?' . ($month >= 1 && $month <= 12 ? 'period=' . h(period_input($year, $month)) : 'year=' . (int) $year) . '">Monthly report</a>'); ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<?php if ($report): ?>
<form class="panel" method="get">
  <div class="row">
    <label>Year
      <input type="number" name="year" min="2000" max="2100" value="<?= (int) $year ?>" required>
    </label>
    <button type="submit">Show</button>
  </div>
</form>
<section class="cards">
  <article class="card"><span>Sales</span><strong><?= h(money_label($report['totals']['sales'])) ?></strong></article>
  <article class="card"><span>Expenses</span><strong><?= h(money_label($report['totals']['expenses'])) ?></strong></article>
  <article class="card profit"><span>Profit</span><strong><?= h(money_label($report['totals']['profit'])) ?></strong></article>
</section>
<nav class="filters">
  <?php foreach ($monthOrder as $number): ?>
    <?php
      $chip = $month === (int) $number ? 'current' : '';
      if ($runningMonth === (int) $number) {
          $chip = trim($chip . ' running');
      }
    ?>
    <a class="<?= h($chip) ?>" href="books.php?year=<?= (int) $year ?>&amp;month=<?= (int) $number ?>"><?= h($months[$number]) ?></a>
  <?php endforeach; ?>
</nav>
<?php if ($month >= 1 && $month <= 12 && isset($report['months'][$month])): ?>
  <?php
    $spot = $report['months'][$month];
    $recorded = from_cents(to_cents((string) $spot['transfer']) + to_cents((string) $spot['cash']));
  ?>
  <section class="month-spot">
    <header>
      <h2><?= h($months[$month] . ' ' . $year) ?></h2>
      <?php if ($runningMonth === $month): ?><span class="pay-pill">This month</span><?php endif; ?>
    </header>
    <div class="cards">
      <article class="card"><span>POS</span><strong><?= h(money_label($spot['pos'])) ?></strong></article>
      <article class="card"><span>Recorded</span><strong><?= h(money_label($recorded)) ?></strong></article>
      <article class="card"><span>Sales</span><strong><?= h(money_label($spot['sales'])) ?></strong></article>
      <article class="card"><span>Expenses</span><strong><?= h(money_label($spot['expenses'])) ?></strong></article>
      <article class="card profit"><span>Profit</span><strong><?= h(money_label($spot['profit'])) ?></strong></article>
    </div>
    <p class="muted split-note">Transfer <?= h(money_label($spot['transfer'])) ?> · Cash <?= h(money_label($spot['cash'])) ?> · Supplies <?= h(money_label($spot['supplies'])) ?> · Salary <?= h(money_label($spot['salary'])) ?> · Rent <?= h(money_label($spot['rent'])) ?> · Utilities <?= h(money_label($spot['utilities'])) ?> · Other <?= h(money_label($spot['other'])) ?></p>
    <?php
      $previousDays = 0;
      foreach ($days as $day) {
          if (($day['origin'] ?? '') === 'previous') {
              $previousDays++;
          }
      }
    ?>
    <?php if ($previousDays > 0): ?><p class="muted split-note">Previous book: <?= (int) $previousDays ?> dated sales from the old sheet. They stay in transfer and cash, apart from register bills.</p><?php endif; ?>
  </section>
<?php endif; ?>
<section class="sheet">
  <header class="sheet-head"><h2>Year</h2><p>Sales are register bills plus the past days saved here. Expenses are dated costs, salaries, and the recurring amounts saved on Expenses.</p></header>
  <div class="books">
  <table>
    <thead>
      <tr>
        <th>Month</th><th>POS</th><th>Transfer</th><th>Cash</th><th>Sales</th>
        <th>Supplies</th><th>Salary</th><th>Rent</th><th>Utilities</th><th>Other</th><th>Profit</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($monthOrder as $number): ?>
      <?php $row = $report['months'][$number]; ?>
      <tr class="<?= trim(($month === (int) $number ? 'current' : '') . ($runningMonth === (int) $number ? ' running' : '')) ?>">
        <td><a href="books.php?year=<?= (int) $year ?>&amp;month=<?= (int) $number ?>"><?= h($months[$number]) ?></a></td>
        <td class="num"><?= h($row['pos']) ?></td>
        <td class="num"><?= h($row['transfer']) ?></td>
        <td class="num"><?= h($row['cash']) ?></td>
        <td class="num"><?= h($row['sales']) ?></td>
        <td class="num"><?= h($row['supplies']) ?></td>
        <td class="num"><?= h($row['salary']) ?></td>
        <td class="num"><?= h($row['rent']) ?></td>
        <td class="num"><?= h($row['utilities']) ?></td>
        <td class="num"><?= h($row['other']) ?></td>
        <td class="num"><?= h($row['profit']) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</section>

<h2>Partners</h2>
<p class="muted">Shares now total <?= h($report['share_total']) ?>%. Profit share uses the year profit.</p>
<?php foreach ($report['partners'] as $partner): ?>
  <?php if (can_manage()): ?>
  <form class="panel" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="year" value="<?= (int) $year ?>">
    <input type="hidden" name="month" value="<?= (int) $month ?>">
    <input type="hidden" name="action" value="partner">
    <input type="hidden" name="id" value="<?= (int) $partner['id'] ?>">
    <div class="row">
      <label>Name <input name="name" required value="<?= h($partner['name']) ?>"></label>
      <label>Share % <input name="share_percent" required value="<?= h($partner['share_percent']) ?>"></label>
      <p class="muted"><?= h(money_label($partner['share'])) ?></p>
      <button type="submit">Save</button>
    </div>
  </form>
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="year" value="<?= (int) $year ?>">
    <input type="hidden" name="month" value="<?= (int) $month ?>">
    <input type="hidden" name="action" value="delete-partner">
    <input type="hidden" name="id" value="<?= (int) $partner['id'] ?>">
    <button class="ghost" type="submit">Remove <?= h($partner['name']) ?></button>
  </form>
  <?php else: ?>
  <article class="panel"><p><?= h($partner['name']) ?> · <?= h($partner['share_percent']) ?>% · <?= h(money_label($partner['share'])) ?></p></article>
  <?php endif; ?>
<?php endforeach; ?>
<?php if (can_manage()): ?>
<form class="panel" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="year" value="<?= (int) $year ?>">
  <input type="hidden" name="month" value="<?= (int) $month ?>">
  <input type="hidden" name="action" value="partner">
  <div class="row">
    <label>New partner <input name="name" required></label>
    <label>Share % <input name="share_percent" required value="0"></label>
    <button type="submit">Add partner</button>
  </div>
</form>
<?php endif; ?>

<h2><?= $editingDay ? 'Edit ' . h(format_date((string) $editingDay['sale_date'])) : ($month >= 1 && $month <= 12 ? h($months[$month] . ' ' . $year . ' recorded days') : 'Record a day') ?></h2>
<p class="muted">Saving a date that already exists updates that day. Leave both amounts empty, or enter 0, to clear it. POS bills stay on the POS sales.</p>
<?php if (can_manage()): ?>
<form class="panel" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="year" value="<?= (int) $year ?>">
  <input type="hidden" name="month" value="<?= (int) $month ?>">
  <input type="hidden" name="action" value="day">
  <div class="row">
    <label>Date <input type="date" name="sale_date" required value="<?= h($editingDay ? substr((string) $editingDay['sale_date'], 0, 10) : ($month >= 1 && $month <= 12 ? ($runningMonth === $month ? date('Y-m-d') : sprintf('%04d-%02d-01', $year, $month)) : date('Y-m-d'))) ?>"></label>
    <label>Transfer <input name="transfer" inputmode="decimal" value="<?= h($editingDay ? number_format((float) $editingDay['transfer'], 2, '.', ',') : '') ?>"></label>
    <label>Cash <input name="cash" inputmode="decimal" value="<?= h($editingDay ? number_format((float) $editingDay['cash'], 2, '.', ',') : '') ?>"></label>
    <button type="submit"><?= $editingDay ? 'Save changes' : 'Save day' ?></button>
  </div>
</form>
<?php endif; ?>
<?php if ($days): ?>
  <section class="sheet">
  <header class="sheet-head"><h2>Recorded days</h2></header>
  <table class="stack">
    <thead><tr><th>Date</th><th>Transfer</th><th>Cash</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($days as $day): ?>
      <tr>
        <td data-label="Date"><?= h(format_date($day['sale_date'])) ?><?php if (($day['origin'] ?? '') === 'previous'): ?> <span class="pay-pill">Previous book</span><?php endif; ?></td>
        <td class="num" data-label="Transfer"><?= h(money_label((string) $day['transfer'])) ?></td>
        <td class="num" data-label="Cash"><?= h(money_label((string) $day['cash'])) ?></td>
        <td class="actions" data-label=""><?php if (can_manage()): ?><a class="button ghost" href="books.php?year=<?= (int) $year ?>&amp;month=<?= (int) $month ?>&amp;day=<?= h(rawurlencode(format_date((string) $day['sale_date']))) ?>">Edit</a><?php endif; ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </section>
<?php endif; ?>
<?php endif; ?>
<?php layout_footer(); ?>
