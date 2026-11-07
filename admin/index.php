<?php

require __DIR__ . '/_init.php';
require_owner_page();

$today = date('Y-m-d');
try {
    $report = sales_for_date($today);
} catch (PDOException $error) {
    layout_header('Dashboard');
    echo '<p class="error">Database error. Start MySQL and import database/schema.sql.</p>';
    layout_footer();
    exit;
}

$summary = $report['summary'];
$spot = trading_overview($today, $today, false);
$chartYear = (int) ($_GET['year'] ?? date('Y'));
$thisYear = (int) date('Y');
if ($chartYear < 2020 || $chartYear > $thisYear) {
    $chartYear = $thisYear;
}
$yearReport = book_year_report($chartYear);
$monthNumber = (int) date('n');
$monthRow = $yearReport['months'][$monthNumber] ?? [];
$floorPack = ['tables' => [], 'pending_payments' => 0];
try {
    $floorPack = floor_tables();
} catch (Throwable $ignored) {
    $floorPack = ['tables' => [], 'pending_payments' => 0];
}
$floor = $floorPack['tables'] ?? [];
$openCount = 0;
$freeCount = 0;
$openCents = 0;
$waiting = 0;
foreach ($floor as $table) {
    if (empty($table['order_id'])) {
        $freeCount++;
        continue;
    }
    $openCount++;
    $openCents += to_cents((string) ($table['total'] ?? '0'));
    if (!empty($table['payment_waiting'])) {
        $waiting++;
    }
}
$credits = credit_sales();
$creditCents = 0;
foreach ($credits as $credit) {
    $creditCents += to_cents((string) $credit['total']);
}
$customers = list_customers();
$withCredit = 0;
foreach ($customers as $customer) {
    if ((int) $customer['open_bills'] > 0) {
        $withCredit++;
    }
}
$shifts = db()->query(
    'SELECT u.name, s.clock_in
     FROM shifts s
     JOIN users u ON u.id = s.user_id
     WHERE s.clock_out IS NULL
     ORDER BY s.clock_in'
)->fetchAll();
$selling = (int) db()->query('SELECT COUNT(*) FROM items WHERE active = 1')->fetchColumn();
$hiddenItems = (int) db()->query('SELECT COUNT(*) FROM items WHERE active = 0')->fetchColumn();
$categoryCount = (int) db()->query('SELECT COUNT(*) FROM categories')->fetchColumn();
$activeStaff = (int) db()->query('SELECT COUNT(*) FROM users WHERE active = 1')->fetchColumn();
$monthProfit = (string) ($monthRow['profit'] ?? '0');
$todayProfit = (string) ($spot['profit'] ?? '0');
$expenseCount = count($spot['expense_rows']);

function dash_glance(string $href, string $kicker, string $title, array $facts): void
{
    echo '<a class="glance" href="' . h($href) . '">';
    echo '<span class="glance-kicker">' . h($kicker) . '</span>';
    echo '<strong>' . h($title) . '</strong><ul>';
    foreach ($facts as $label => $value) {
        echo '<li><span>' . h((string) $label) . '</span><b>' . h((string) $value) . '</b></li>';
    }
    echo '</ul></a>';
}

layout_header('Dashboard');
page_head('Dashboard', h(format_date($today)), '');
?>
<section class="glances">
  <?php
  dash_glance('tables.php', 'Floor', $openCount === 0 ? 'All free' : $openCount . ' open', [
      'Open bills' => money_label(from_cents($openCents)),
      'Free tables' => (string) $freeCount,
      'Proof waiting' => (string) max($waiting, (int) ($floorPack['pending_payments'] ?? 0)),
  ]);
  dash_glance('sales.php', 'Sales today', money_label($summary['total']), [
      'Tickets' => (string) (int) $summary['count'],
      'Cash' => money_label($summary['cash_total']),
      'Bank' => money_label($summary['online_total']),
  ]);
  dash_glance('reports.php', 'Result today', (to_cents($todayProfit) < 0 ? 'Loss ' : 'Left ') . money_label($todayProfit), [
      'Sales' => money_label($spot['sales']),
      'Expenses' => money_label($spot['expenses']),
      'Credit/Hold' => money_label($spot['credits']),
  ]);
  dash_glance('books.php?year=' . $thisYear . '&month=' . $monthNumber, format_month($today), (to_cents($monthProfit) < 0 ? 'Loss ' : 'Left ') . money_label($monthProfit), [
      'Sales' => money_label((string) ($monthRow['sales'] ?? '0')),
      'Expenses' => money_label((string) ($monthRow['expenses'] ?? '0')),
      'Salary' => money_label((string) ($monthRow['salary'] ?? '0')),
  ]);
  dash_glance('customers.php', 'Credit/Hold', money_label(from_cents($creditCents)), [
      'Open bills' => (string) count($credits),
      'Customers' => (string) $withCredit,
      'All customers' => (string) count($customers),
  ]);
  $shiftFacts = ['Active staff' => (string) $activeStaff];
  if ($shifts === []) {
      $shiftFacts = ['Clocked in' => 'None', 'Active staff' => (string) $activeStaff];
  } else {
      $shown = array_slice($shifts, 0, 2);
      foreach ($shown as $shift) {
          $shiftFacts[(string) $shift['name']] = format_when((string) $shift['clock_in']);
      }
      if (count($shifts) > 2) {
          $shiftFacts['More'] = (string) (count($shifts) - 2);
      }
  }
  dash_glance('hr.php', 'On shift', (string) count($shifts), $shiftFacts);
  dash_glance('items.php', 'Menu', (string) $selling . ' selling', [
      'Hidden' => (string) $hiddenItems,
      'Categories' => (string) $categoryCount,
  ]);
  dash_glance('expenses.php', 'Expenses today', money_label($spot['expenses']), [
      'Entries' => (string) $expenseCount,
      'This month' => money_label((string) ($monthRow['expenses'] ?? '0')),
  ]);
  ?>
</section>
<section class="sheet overview">
  <header class="sheet-head"><h2><?= h(format_month($today)) ?></h2><p>Sales are paid bills plus recorded income. Salary, rent, utilities, supplies, and other costs use the date they were entered.</p></header>
  <div class="cards">
    <article class="card"><span>Sales</span><strong><?= h(money_label((string) ($monthRow['sales'] ?? '0'))) ?></strong></article>
    <article class="card"><span>Supplies</span><strong><?= h(money_label((string) ($monthRow['supplies'] ?? '0'))) ?></strong></article>
    <article class="card"><span>Salary</span><strong><?= h(money_label((string) ($monthRow['salary'] ?? '0'))) ?></strong></article>
    <article class="card"><span>Rent</span><strong><?= h(money_label((string) ($monthRow['rent'] ?? '0'))) ?></strong></article>
    <article class="card"><span>Utilities</span><strong><?= h(money_label((string) ($monthRow['utilities'] ?? '0'))) ?></strong></article>
    <article class="card"><span>Other</span><strong><?= h(money_label((string) ($monthRow['other'] ?? '0'))) ?></strong></article>
  </div>
  <h2>Year</h2>
  <p class="muted">Sales, expenses, and what is left, for each month. Tap a month for the breakdown.</p>
  <div class="year-nav">
    <?php if ($chartYear > 2020): ?><a class="button ghost" href="index.php?year=<?= $chartYear - 1 ?>">Previous year</a><?php else: ?><span></span><?php endif; ?>
    <strong><?= $chartYear ?></strong>
    <?php if ($chartYear < $thisYear): ?><a class="button ghost" href="index.php?year=<?= $chartYear + 1 ?>">Next year</a><?php else: ?><span></span><?php endif; ?>
  </div>
  <div class="legend"><span><i class="sale"></i>Sales</span><span><i class="cost"></i>Expenses</span><span><i class="left"></i>Left</span></div>
  <?= year_line_chart($chartYear, $yearReport['months']) ?>
  <script>
    (function () {
      var root = document.getElementById('year-chart');
      if (!root) return;
      var tip = root.querySelector('.chart-tip');
      var fields = ['month', 'sales', 'pos', 'recorded', 'expenses', 'supplies', 'salary', 'rent', 'utilities', 'other', 'left'];
      function show(button) {
        root.querySelectorAll('.chart-cols button.on').forEach(function (node) { node.classList.remove('on'); });
        button.classList.add('on');
        fields.forEach(function (key) {
          var node = tip.querySelector('[data-tip="' + key + '"]');
          if (node) node.textContent = button.getAttribute('data-' + key) || '';
        });
      }
      root.querySelectorAll('.chart-cols button').forEach(function (button) {
        button.addEventListener('pointerenter', function () { show(button); });
        button.addEventListener('focus', function () { show(button); });
        button.addEventListener('click', function () { show(button); });
      });
      var current = root.querySelector('.chart-cols button.on') || root.querySelector('.chart-cols button');
      if (current) show(current);
    })();
  </script>
</section>
<?php render_floor($floor, true); ?>
<section class="sheet">
  <header class="sheet-head"><h2>Credit/Hold</h2><p>Open credit bills still on a customer.</p></header>
<?php if ($credits === []): ?>
  <p class="empty">No credit bills.</p>
<?php else: ?>
  <?php foreach (array_slice($credits, 0, 6) as $credit): ?>
    <article class="sale">
      <header>
        <strong><?= h((string) $credit['customer_name']) ?></strong>
        <span><?= h((string) $credit['table_name']) ?> · <?= h(format_when((string) $credit['opened_at'])) ?> · <?= h(money_label((string) $credit['total'])) ?></span>
      </header>
    </article>
  <?php endforeach; ?>
  <?php if (count($credits) > 6): ?>
    <p><a class="button" href="customers.php">All <?= (int) count($credits) ?> credit bills</a></p>
  <?php endif; ?>
<?php endif; ?>
</section>
<section class="sheet">
  <header class="sheet-head"><h2>Latest sales</h2><p>Bills paid today.</p></header>
<?php if ($report['sales'] === []): ?>
  <p class="empty">No sales yet today.</p>
<?php else: ?>
  <?php foreach (array_slice($report['sales'], 0, 8) as $sale): ?>
    <article class="sale">
      <header>
        <strong><?= h($sale['table_name']) ?></strong>
        <span><span class="pay-pill <?= h((string) $sale['payment_method']) ?>"><?= h($sale['payment_label']) ?></span><?php if (!empty($sale['held'])): ?> <span class="pay-pill credit">Held</span><?php endif; ?><?php if (!empty($sale['settlement_reference'])): ?> <span class="pay-pill"><?= h($sale['settlement_reference']) ?></span><?php endif; ?> <?= h(format_when($sale['closed_at'])) ?> · <?= h(money_label($sale['total'])) ?><?php if (!empty($sale['customer_name'])): ?> · <?= h($sale['customer_name']) ?><?php endif; ?><?php if (!empty($sale['duration_label'])): ?> · <?= h($sale['duration_label']) ?><?php endif; ?></span>
      </header>
      <p class="lines">
        <?php
        $bits = [];
        foreach ($sale['lines'] as $line) {
            $bits[] = (int) $line['qty'] . ' × ' . $line['item_name'];
        }
        echo h(implode(', ', $bits));
        ?>
      </p>
      <?= sale_slip($sale) ?>
    </article>
  <?php endforeach; ?>
  <?php if (count($report['sales']) > 8): ?>
    <p><a class="button" href="sales.php">All <?= (int) count($report['sales']) ?> sales</a></p>
  <?php endif; ?>
<?php endif; ?>
</section>
<?php layout_footer(); ?>
