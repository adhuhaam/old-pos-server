<?php

require __DIR__ . '/_init.php';
require_owner_page();

$error = null;
$userId = (int) ($_SESSION['user_id'] ?? 0);
$me = $userId > 0 ? user_by_id($userId) : null;
$name = $me['name'] ?? '';

try {
    if ($me === null) {
        throw new AppError('Sign in required.', 401);
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $name = (string) ($_POST['name'] ?? '');
        update_own_account(
            $userId,
            $name,
            (string) ($_POST['current_password'] ?? ''),
            (string) ($_POST['password'] ?? ''),
            null
        );
        flash_set('Account saved.');
        redirect_to('account.php');
    }
} catch (AppError $errorObject) {
    $error = $errorObject->getMessage();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = (string) ($_POST['name'] ?? $name);
    }
} catch (PDOException $errorObject) {
    $error = 'Database error. Start MySQL and import database/schema.sql.';
}

layout_header('Account');
?>
<?php page_head('Account', 'Your name and password'); ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<?php if ($me): ?>
<form class="panel" method="post">
  <?= csrf_field() ?>
  <div class="row">
    <label>Name
      <input name="name" maxlength="80" required value="<?= h($name) ?>">
    </label>
    <label>Username
      <input value="<?= h($me['username']) ?>" disabled>
    </label>
  </div>
  <div class="row">
    <label>Current password
      <input type="password" name="current_password" autocomplete="current-password">
    </label>
    <label>New password
      <input type="password" name="password" autocomplete="new-password">
    </label>
  </div>
  <p class="muted">Leave the password fields empty to keep the current password. A new password signs other phones out. This browser stays signed in.</p>
  <button type="submit">Save account</button>
</form>
<?php endif; ?>
<?php layout_footer(); ?>
