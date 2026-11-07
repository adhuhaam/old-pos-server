<?php

require __DIR__ . '/_init.php';
require_owner_page();

[$year, $month] = read_period();
$error = null;
$people = [];
$rows = [];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $action = (string) ($_POST['action'] ?? '');
        $userId = (int) ($_POST['user_id'] ?? 0);
        if ($action === 'rate') {
            save_hourly_rate($userId, (string) ($_POST['hourly_rate'] ?? ''));
            flash_set('Hourly rate saved.');
        } elseif ($action === 'salary') {
            $personId = (int) ($_POST['person_id'] ?? 0);
            $found = null;
            foreach (hr_month_rows($year, $month) as $row) {
                if ((int) $row['user']['id'] === $userId) {
                    $found = $row;
                }
            }
            if ($found === null) {
                throw new AppError('User not found.', 404);
            }
            if ($found['minutes'] <= 0) {
                throw new AppError('This month has no hours to save.');
            }
            if ($found['user']['hourly_rate'] === null || $found['user']['hourly_rate'] === '') {
                throw new AppError('Set an hourly rate first.');
            }
            book_put_month_salary($personId, $year, $month, (string) $found['pay']);
            flash_set('Saved into Salaries for ' . book_months()[$month] . ' ' . $year . '.');
        }
        redirect_to('hr.php?period=' . period_input($year, $month));
    }
    $people = book_people();
    $rows = hr_month_rows($year, $month);
} catch (AppError $errorObject) {
    $error = $errorObject->getMessage();
    try {
        $people = book_people();
        $rows = hr_month_rows($year, $month);
    } catch (Throwable $ignored) {
        $people = [];
        $rows = [];
    }
} catch (PDOException $errorObject) {
    $error = 'Database error. Start MySQL and import database/schema.sql.';
}

$months = book_months();
layout_header('Hours');
?>
<?php page_head('Hours', h($months[$month] . ' ' . $year)); ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<p class="muted note">Waiters clock in and out on the phone. An open shift counts until they clock out, and stops at 16 hours if they forget. Save into Salaries writes this month only for the person you pick.</p>
<form class="panel" method="get">
  <div class="row">
    <label>Month
      <input type="month" name="period" required value="<?= h(period_input($year, $month)) ?>">
    </label>
    <button type="submit">Show</button>
  </div>
</form>
<?php if ($rows === []): ?>
  <p class="empty">No active users.</p>
<?php endif; ?>
<?php foreach ($rows as $row): ?>
  <?php
    $user = $row['user'];
    $rate = $user['hourly_rate'];
    $rateValue = $rate === null || $rate === '' ? '' : number_format((float) $rate, 2, '.', ',');
    $picked = 0;
    foreach ($people as $person) {
        if (strcasecmp((string) $person['name'], (string) $user['name']) === 0) {
            $picked = (int) $person['id'];
        }
    }
  ?>
  <form class="panel person" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="year" value="<?= (int) $year ?>">
    <input type="hidden" name="month" value="<?= (int) $month ?>">
    <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
    <h2><?= h($user['name']) ?> <span class="muted"><?= $user['role'] === 'superadmin' ? 'Superadmin' : ($user['role'] === 'manager' ? 'Manager' : 'Waiter') ?></span></h2>
    <p><?= h(hr_minutes_label((int) $row['minutes'])) ?><?php if ($row['open']): ?> · Still open<?php endif; ?> · <?= h(money_label((string) $row['pay'])) ?></p>
    <?php if (can_manage()): ?>
    <div class="row">
      <label>Hourly rate
        <input name="hourly_rate" inputmode="decimal" value="<?= h($rateValue) ?>" placeholder="MVR per hour">
      </label>
      <button type="submit" name="action" value="rate">Save rate</button>
    </div>
    <?php if ($people === []): ?>
      <p class="muted">Add a person on Salaries before saving pay.</p>
    <?php else: ?>
      <div class="row">
        <label>Salaries person
          <select name="person_id">
            <?php foreach ($people as $person): ?>
              <option value="<?= (int) $person['id'] ?>"<?= $picked === (int) $person['id'] ? ' selected' : '' ?>><?= h($person['name']) ?><?= $person['designation'] !== '' ? ' · ' . h($person['designation']) : '' ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <button type="submit" name="action" value="salary">Save into Salaries</button>
      </div>
    <?php endif; ?>
    <?php else: ?>
      <p class="muted">Hourly rate <?= $rateValue === '' ? 'not set' : h(money_label($rateValue)) ?></p>
    <?php endif; ?>
    <?php if ($row['shifts'] !== []): ?>
      <table class="stack">
        <thead><tr><th>In</th><th>Out</th><th>Hours</th></tr></thead>
        <tbody>
        <?php foreach ($row['shifts'] as $shift): ?>
          <tr>
            <td data-label="In"><?= h(format_when($shift['in'])) ?></td>
            <td data-label="Out"><?= $shift['out'] === null ? 'Still open' : h(format_when($shift['out'])) ?></td>
            <td data-label="Hours"><?= h(hr_minutes_label((int) $shift['minutes'])) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </form>
<?php endforeach; ?>
<?php layout_footer(); ?>
