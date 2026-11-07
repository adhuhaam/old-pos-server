<?php

require __DIR__ . '/_init.php';
require_owner_page();

$year = (int) ($_GET['year'] ?? $_POST['year'] ?? date('Y'));
$error = null;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $action = (string) ($_POST['action'] ?? '');
        if ($action === 'person') {
            book_save_person((string) ($_POST['name'] ?? ''), (string) ($_POST['designation'] ?? ''));
            flash_set('Person added.');
        } elseif ($action === 'delete-person') {
            book_delete_person((int) ($_POST['id'] ?? 0));
            flash_set('Person removed.');
        } elseif ($action === 'salary') {
            book_save_person((string) ($_POST['name'] ?? ''), (string) ($_POST['designation'] ?? ''), (int) ($_POST['id'] ?? 0));
            book_save_salary((int) ($_POST['id'] ?? 0), $year, (array) ($_POST['amount'] ?? []));
            flash_set('Salary saved.');
        }
        redirect_to('salaries.php?year=' . $year);
    }
    $people = book_salary_grid($year);
} catch (AppError $errorObject) {
    $error = $errorObject->getMessage();
    $people = book_salary_grid($year);
} catch (PDOException $errorObject) {
    $error = 'Database error. Start MySQL and import database/schema.sql.';
    $people = [];
}

layout_header('Salaries');
$months = book_months();
$monthOrder = month_order($year);
$runningMonth = $year === (int) date('Y') ? (int) date('n') : 0;
?>
<?php page_head('Salaries', (string) (int) $year); ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<form class="panel" method="get">
  <div class="row">
    <label>Year
      <input type="number" name="year" min="2000" max="2100" value="<?= (int) $year ?>" required>
    </label>
    <button type="submit">Show</button>
  </div>
</form>
<?php if (can_manage()): ?>
<form class="panel" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="person">
  <input type="hidden" name="year" value="<?= (int) $year ?>">
  <div class="row">
    <label>Name <input name="name" required></label>
    <label>Designation <input name="designation"></label>
    <button type="submit">Add person</button>
  </div>
</form>
<?php endif; ?>
<?php foreach ($people as $person): ?>
  <form class="panel person" method="post"<?= can_manage() ? '' : ' onsubmit="return false"' ?>>
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="salary">
    <input type="hidden" name="year" value="<?= (int) $year ?>">
    <input type="hidden" name="id" value="<?= (int) $person['id'] ?>">
    <h2><?= h($person['name']) ?>
      <?php
      $yearCents = 0;
      foreach ($person['months'] as $amount) {
          $amount = trim((string) $amount);
          if ($amount !== '') {
              $yearCents += to_cents($amount);
          }
      }
      ?>
      <span class="muted"><?= h(money_label(from_cents($yearCents))) ?></span>
    </h2>
    <div class="row">
      <label>Name <input name="name" required value="<?= h($person['name']) ?>"></label>
      <label>Designation <input name="designation" value="<?= h($person['designation']) ?>"></label>
    </div>
    <div class="row">
      <?php foreach ($monthOrder as $number): ?>
        <label<?= $runningMonth === (int) $number ? ' class="running"' : '' ?>><?= h($months[$number]) ?>
          <input name="amount[<?= (int) $number ?>]" inputmode="decimal" value="<?= h(trim((string) $person['months'][$number]) === '' ? '' : number_format((float) $person['months'][$number], 2, '.', ',')) ?>">
        </label>
      <?php endforeach; ?>
    </div>
    <?php if (can_manage()): ?><button type="submit">Save <?= h($person['name']) ?></button><?php endif; ?>
  </form>
  <?php if (can_manage()): ?>
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="delete-person">
    <input type="hidden" name="year" value="<?= (int) $year ?>">
    <input type="hidden" name="id" value="<?= (int) $person['id'] ?>">
    <button class="ghost" type="submit">Remove <?= h($person['name']) ?></button>
  </form>
  <?php endif; ?>
<?php endforeach; ?>
<?php layout_footer(); ?>
