<?php

require __DIR__ . '/_init.php';
require_owner_page();

$error = null;
$editing = null;
$users = [];
$roles = [];
$actorId = (int) ($_SESSION['user_id'] ?? 0);

try {
    if (isset($_GET['id']) && $_GET['id'] !== '') {
        $editing = user_by_id((int) $_GET['id']);
        if ($editing === null) {
            $error = 'User not found.';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $action = (string) ($_POST['action'] ?? 'save');
        if ($action === 'delete') {
            delete_user((int) ($_POST['id'] ?? 0), $actorId);
            flash_set('User deleted.');
            redirect_to('users.php');
        }
        if ($action === 'save-role') {
            $saved = save_role(
                (string) ($_POST['slug'] ?? ''),
                (string) ($_POST['role_name'] ?? ''),
                is_array($_POST['areas'] ?? null) ? $_POST['areas'] : []
            );
            flash_set('Role saved.');
            redirect_to('users.php?role=' . rawurlencode($saved));
        }
        if ($action === 'delete-role') {
            delete_role((string) ($_POST['slug'] ?? ''));
            flash_set('Role deleted.');
            redirect_to('users.php');
        }
        $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null;
        save_user(
            $id,
            (string) ($_POST['name'] ?? ''),
            (string) ($_POST['username'] ?? ''),
            (string) ($_POST['password'] ?? ''),
            (string) ($_POST['role'] ?? 'waiter'),
            isset($_POST['active'])
        );
        flash_set($id === null ? 'User added.' : 'User saved.');
        redirect_to('users.php');
    }

    $users = list_users();
    $roles = list_roles();
} catch (AppError $errorObject) {
    $error = $errorObject->getMessage();
    try {
        $users = list_users();
        $roles = list_roles();
    } catch (Throwable $ignored) {
        $users = [];
        $roles = $roles ?? [];
    }
} catch (PDOException $errorObject) {
    $error = 'Database error. Start MySQL and import database/schema.sql.';
}

$name = $editing['name'] ?? '';
$username = $editing['username'] ?? '';
$role = $editing['role'] ?? 'waiter';
$active = $editing['active'] ?? true;
if ($error && $_SERVER['REQUEST_METHOD'] === 'POST' && (($_POST['action'] ?? 'save') === 'save')) {
    $name = (string) ($_POST['name'] ?? $name);
    $username = (string) ($_POST['username'] ?? $username);
    $role = (string) ($_POST['role'] ?? $role);
    $active = isset($_POST['active']);
}

layout_header('Users');
?>
<h1>Users</h1>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<?php if (can_manage()): ?>
<form class="panel" method="post">
  <?= csrf_field() ?>
  <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int) $editing['id'] ?>"><?php endif; ?>
  <div class="row">
    <label>Name
      <input name="name" maxlength="80" required value="<?= h($name) ?>">
    </label>
    <label>Username
      <input name="username" maxlength="40" required value="<?= h($username) ?>" autocomplete="off">
    </label>
    <label><?= $editing ? 'New password' : 'Password' ?>
      <input type="password" name="password" autocomplete="new-password" <?= $editing ? '' : 'required' ?>>
    </label>
    <label>Role
      <select name="role">
        <?php foreach ($roles as $choice): ?>
          <?php if ($choice['slug'] === 'superadmin' && !can_manage()) { continue; } ?>
          <option value="<?= h($choice['slug']) ?>"<?= $role === $choice['slug'] ? ' selected' : '' ?>><?= h($choice['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
  </div>
  <label class="check">
    <input type="checkbox" name="active" value="1"<?= $active ? ' checked' : '' ?>>
    Can sign in
  </label>
  <div class="actions">
    <button type="submit"><?= $editing ? 'Save user' : 'Add user' ?></button>
    <?php if ($editing): ?><a class="button ghost" href="users.php">Cancel</a><?php endif; ?>
  </div>
</form>
<?php endif; ?>
<section class="sheet">
  <header class="sheet-head"><h2>People</h2><p>A role with an admin area opens this site. Other roles open the phone app. Expenses and supplies are a phone tab only when that role includes them.</p></header>
<?php if ($users === []): ?>
  <p class="empty">No users yet.</p>
<?php else: ?>
  <table class="stack">
    <thead><tr><th>Name</th><th>Username</th><th>Role</th><th>Access</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($users as $user): ?>
      <tr>
        <td data-label="Name"><?= h($user['name']) ?></td>
        <td data-label="Username"><?= h($user['username']) ?></td>
        <td data-label="Role"><?= h(role_label((string) $user['role'])) ?></td>
        <td class="<?= $user['active'] ? 'on' : 'off' ?>" data-label="Access"><?= $user['active'] ? 'Active' : 'Off' ?></td>
        <td class="actions" data-label="">
          <?php if (can_manage()): ?>
          <a class="button ghost" href="users.php?id=<?= (int) $user['id'] ?>">Edit</a>
          <?php if ((int) $user['id'] !== $actorId): ?>
          <form method="post" onsubmit="return confirm('Delete this user?');">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
            <button class="ghost" type="submit">Delete</button>
          </form>
          <?php endif; ?>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
</section>
<?php
$editingRole = null;
if (isset($_GET['role']) && $_GET['role'] !== '') {
    $editingRole = role_by_slug((string) $_GET['role']);
}
$roleName = $editingRole['name'] ?? '';
$roleSlug = $editingRole['slug'] ?? '';
$roleAreas = $editingRole['areas'] ?? [];
if ($error && ($_POST['action'] ?? '') === 'save-role') {
    $roleName = (string) ($_POST['role_name'] ?? $roleName);
    $roleSlug = (string) ($_POST['slug'] ?? $roleSlug);
    $roleAreas = is_array($_POST['areas'] ?? null) ? $_POST['areas'] : [];
}
?>
<?php if (can_manage()): ?>
<section class="sheet">
  <header class="sheet-head"><h2>Roles</h2><p>A superadmin chooses what each role can open. Waiters stay on the phone. The expenses tab is only there when the role includes it. Set customer credit limits is the phone switch for changing a limit. Supply staff sign in over Tailscale and price the weekly request. Superadmin keeps the whole site.</p></header>
  <form class="panel" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="save-role">
    <input type="hidden" name="slug" value="<?= h($roleSlug) ?>">
    <label>Role name
      <input name="role_name" maxlength="80" required value="<?= h($roleName) ?>"<?= $roleSlug === 'superadmin' ? ' disabled' : '' ?>>
    </label>
    <?php if ($roleSlug === 'superadmin'): ?>
      <p class="muted">Superadmin keeps full access.</p>
    <?php else: ?>
      <?php foreach (access_catalog() as $group => $choices): ?>
        <h2><?= h($group) ?></h2>
        <?php foreach ($choices as $key => $label): ?>
          <label class="check">
            <input type="checkbox" name="areas[]" value="<?= h($key) ?>"<?= in_array($key, $roleAreas, true) ? ' checked' : '' ?>>
            <?= h($label) ?>
          </label>
        <?php endforeach; ?>
      <?php endforeach; ?>
    <?php endif; ?>
    <?php if ($roleSlug !== 'superadmin'): ?>
      <div class="actions">
        <button type="submit"><?= $roleSlug !== '' ? 'Save role' : 'Add role' ?></button>
        <?php if ($roleSlug !== ''): ?><a class="button ghost" href="users.php">Cancel</a><?php endif; ?>
      </div>
    <?php endif; ?>
  </form>
  <table class="stack">
    <thead><tr><th>Role</th><th>Access</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($roles as $choice): ?>
      <tr>
        <td data-label="Role"><?= h($choice['name']) ?></td>
        <td data-label="Access"><?= $choice['slug'] === 'superadmin' ? 'Full access' : h((string) count($choice['areas'])) . ' areas' ?></td>
        <td class="actions" data-label="">
          <?php if ($choice['slug'] !== 'superadmin'): ?>
            <a class="button ghost" href="users.php?role=<?= h(rawurlencode($choice['slug'])) ?>">Edit</a>
          <?php endif; ?>
          <?php if (!$choice['built_in']): ?>
            <form method="post" onsubmit="return confirm('Delete this role?');">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="delete-role">
              <input type="hidden" name="slug" value="<?= h($choice['slug']) ?>">
              <button class="ghost" type="submit">Delete</button>
            </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</section>
<?php endif; ?>
<?php layout_footer(); ?>
