<?php

require __DIR__ . '/_init.php';
require_owner_page();

$error = null;
$editing = null;
$tables = [];
$areas = [];

try {
    if (isset($_GET['id']) && $_GET['id'] !== '') {
        foreach (list_tables() as $table) {
            if ($table['id'] === (int) $_GET['id']) {
                $editing = $table;
            }
        }
        if ($editing === null) {
            $error = 'Table not found.';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $action = (string) ($_POST['action'] ?? 'save');
        if ($action === 'delete') {
            delete_table((int) ($_POST['id'] ?? 0));
            flash_set('Table deleted.');
            redirect_to('tables.php');
        }
        if ($action === 'area') {
            if (!can_manage()) {
                throw new AppError('Only a superadmin can edit areas.', 403);
            }
            $areaId = isset($_POST['area_id']) && $_POST['area_id'] !== '' ? (int) $_POST['area_id'] : null;
            save_floor_area($areaId, (string) ($_POST['area_name'] ?? ''), (int) ($_POST['sort_order'] ?? 0));
            flash_set($areaId === null ? 'Area added.' : 'Area saved.');
            redirect_to('tables.php');
        }
        if ($action === 'area-delete') {
            if (!can_manage()) {
                throw new AppError('Only a superadmin can edit areas.', 403);
            }
            delete_floor_area((int) ($_POST['area_id'] ?? 0));
            flash_set('Area deleted.');
            redirect_to('tables.php');
        }
        $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null;
        $postedArea = isset($_POST['area_id']) && $_POST['area_id'] !== '' ? (int) $_POST['area_id'] : null;
        save_table(
            $id,
            (string) ($_POST['name'] ?? ''),
            (int) ($_POST['sort_order'] ?? 0),
            isset($_POST['active']),
            $postedArea,
            true
        );
        flash_set($id === null ? 'Table added.' : 'Table saved.');
        redirect_to('tables.php');
    }

    $tables = list_tables();
    $areas = list_floor_areas();
} catch (AppError $errorObject) {
    $error = $errorObject->getMessage();
    try {
        $tables = list_tables();
        $areas = list_floor_areas();
    } catch (Throwable $ignored) {
        $tables = [];
        $areas = [];
    }
} catch (PDOException $errorObject) {
    $error = 'Database error. Start MySQL and import database/schema.sql.';
}

$name = $editing ? table_seat_label((string) $editing['name'], (string) ($editing['area_name'] ?? '')) : '';
$sort = (string) ($editing['sort_order'] ?? '0');
$active = $editing['active'] ?? true;
$areaId = (int) ($editing['area_id'] ?? 0);
if ($error && $_SERVER['REQUEST_METHOD'] === 'POST' && (($_POST['action'] ?? 'save') === 'save')) {
    $name = (string) ($_POST['name'] ?? $name);
    $sort = (string) ($_POST['sort_order'] ?? $sort);
    $active = isset($_POST['active']);
    $areaId = (int) ($_POST['area_id'] ?? 0);
}

layout_header('Tables');
$live = [];
try {
    foreach (floor_tables()['tables'] as $table) {
        $live[$table['id']] = $table;
    }
} catch (Throwable $ignored) {
    $live = [];
}
$floor = [];
foreach ($tables as $table) {
    $floor[] = isset($live[$table['id']]) ? array_merge($table, $live[$table['id']]) : $table;
}
$openCount = 0;
foreach ($floor as $table) {
    if (!empty($table['order_id'])) {
        $openCount++;
    }
}
?>
<?php page_head('Tables', (int) $openCount . ' open · ' . count($tables) . ' on the floor'); ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<?php render_floor($floor); ?>
<?php if (can_manage()): ?>
<section class="sheet">
  <header class="sheet-head">
    <h2>Areas</h2>
    <p>Tables are grouped into these boxes on the floor. Rename an area and its tables keep the same seat number.</p>
  </header>
  <?php foreach ($areas as $area): ?>
  <form class="customer-edit" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="area">
    <input type="hidden" name="area_id" value="<?= (int) $area['id'] ?>">
    <label>Name <input name="area_name" maxlength="40" required value="<?= h($area['name']) ?>"></label>
    <label>Sort <input name="sort_order" type="number" min="0" max="9999" required value="<?= (int) $area['sort_order'] ?>"></label>
    <button type="submit">Save</button>
  </form>
  <form class="area-delete" method="post" onsubmit="return confirm('Delete this area?');">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="area-delete">
    <input type="hidden" name="area_id" value="<?= (int) $area['id'] ?>">
    <button class="ghost" type="submit">Delete</button>
  </form>
  <?php endforeach; ?>
  <form class="customer-edit" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="area">
    <label>New area <input name="area_name" maxlength="40" required placeholder="Garden"></label>
    <label>Sort <input name="sort_order" type="number" min="0" max="9999" required value="<?= count($areas) + 1 ?>"></label>
    <button type="submit">Add area</button>
  </form>
</section>
<form class="panel" method="post">
  <?= csrf_field() ?>
  <h2><?= $editing ? 'Edit table' : 'Add a table' ?></h2>
  <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int) $editing['id'] ?>"><?php endif; ?>
  <div class="row">
    <label>Area
      <select name="area_id">
        <option value="">Other</option>
        <?php foreach ($areas as $area): ?>
          <option value="<?= (int) $area['id'] ?>"<?= $areaId === (int) $area['id'] ? ' selected' : '' ?>><?= h($area['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Seat
      <input name="name" maxlength="40" required value="<?= h($name) ?>" placeholder="5">
    </label>
    <label>Sort order
      <input name="sort_order" type="number" min="0" max="9999" required value="<?= h($sort) ?>">
    </label>
  </div>
  <label class="check">
    <input type="checkbox" name="active" value="1"<?= $active ? ' checked' : '' ?>>
    Show on the waiter floor
  </label>
  <div class="actions">
    <button type="submit"><?= $editing ? 'Save table' : 'Add table' ?></button>
    <?php if ($editing): ?><a class="button ghost" href="tables.php">Cancel</a><?php endif; ?>
  </div>
</form>
<?php endif; ?>
<section class="sheet">
  <header class="sheet-head"><h2>All tables</h2></header>
<?php if ($tables === []): ?>
  <p class="empty">No tables yet.</p>
<?php else: ?>
  <div class="toolbar">
    <label class="find">Find a table
      <input data-find="#table-list" placeholder="Name">
    </label>
  </div>
  <table class="stack">
    <thead><tr><th>Area</th><th>Name</th><th>Sort</th><th>Floor</th><th>Bill</th><th></th></tr></thead>
    <tbody id="table-list" data-list>
    <?php foreach ($tables as $table): ?>
      <tr data-text="<?= h($table['name'] . ' ' . ($table['order_id'] ? 'open' : 'free') . ' ' . ($table['active'] ? 'showing' : 'hidden')) ?>">
        <td data-label="Area"><?= h($table['area_name'] !== '' ? $table['area_name'] : 'Other') ?></td>
        <td data-label="Name"><?= h($table['name']) ?></td>
        <td data-label="Sort"><?= (int) $table['sort_order'] ?></td>
        <td class="<?= $table['active'] ? 'on' : 'off' ?>" data-label="Floor"><?= $table['active'] ? 'Showing' : 'Hidden' ?></td>
        <td data-label="Bill"><?= $table['order_id'] ? 'Open' : 'Free' ?></td>
        <td class="actions" data-label="">
          <?php if (can_manage()): ?>
          <a class="button ghost" href="tables.php?id=<?= (int) $table['id'] ?>">Edit</a>
          <form method="post" onsubmit="return confirm('Delete this table?');">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int) $table['id'] ?>">
            <button class="ghost" type="submit">Delete</button>
          </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <p class="empty" data-none="#table-list" hidden>Nothing matches that search.</p>
<?php endif; ?>
</section>
<?php layout_footer(); ?>
