<?php

require __DIR__ . '/_init.php';
require_owner_page();

$error = null;
$editing = null;
$items = [];
$categories = [];

try {
    $categories = list_categories();
    $items = list_items();

    if (isset($_GET['id']) && $_GET['id'] !== '') {
        foreach ($items as $item) {
            if ($item['id'] === (int) $_GET['id']) {
                $editing = $item;
            }
        }
        if ($editing === null) {
            $error = 'Item not found.';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $action = (string) ($_POST['action'] ?? 'save');
        if ($action === 'move') {
            move_item((int) ($_POST['id'] ?? 0), (int) ($_POST['direction'] ?? 0));
            flash_set('Menu order saved.');
            redirect_to('items.php');
        }
        if ($action === 'toggle') {
            $target = null;
            foreach ($items as $item) {
                if ($item['id'] === (int) ($_POST['id'] ?? 0)) {
                    $target = $item;
                }
            }
            if ($target === null) {
                throw new AppError('Item not found.', 404);
            }
            save_item($target['id'], [
                'name' => $target['name'],
                'category_id' => $target['category_id'],
                'price' => $target['price'],
                'active' => $target['active'] ? '0' : '1',
            ]);
            flash_set($target['active'] ? 'Item hidden from the waiter menu.' : 'Item is on the menu again.');
            redirect_to('items.php');
        }

        $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null;
        $savedId = save_item($id, [
            'name' => $_POST['name'] ?? '',
            'category_id' => $_POST['category_id'] ?? 0,
            'price' => $_POST['price'] ?? '',
            'active' => isset($_POST['active']) ? '1' : '0',
        ]);
        $hasPhoto = isset($_FILES['photo']) && is_array($_FILES['photo']) && (int) ($_FILES['photo']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
        if ($hasPhoto) {
            save_item_photo($savedId, $_FILES['photo']);
        } elseif (isset($_POST['remove_photo'])) {
            clear_item_photo($savedId);
        }
        flash_set($id === null ? 'Item added.' : 'Item saved.');
        redirect_to('items.php');
    }
} catch (AppError $errorObject) {
    $error = $errorObject->getMessage();
    try {
        $categories = list_categories();
        $items = list_items();
    } catch (Throwable $ignored) {
        $categories = [];
        $items = [];
    }
} catch (PDOException $errorObject) {
    $error = 'Database error. Start MySQL and import database/schema.sql.';
}

$name = $editing['name'] ?? '';
$price = $editing['price'] ?? '';
$categoryId = (int) ($editing['category_id'] ?? ($categories[0]['id'] ?? 0));
$active = $editing['active'] ?? true;
if ($error && $_SERVER['REQUEST_METHOD'] === 'POST' && (($_POST['action'] ?? 'save') === 'save')) {
    $name = (string) ($_POST['name'] ?? $name);
    $price = (string) ($_POST['price'] ?? $price);
    $categoryId = (int) ($_POST['category_id'] ?? $categoryId);
    $active = isset($_POST['active']);
}

layout_header('Items');
$focusCategory = (int) ($_GET['category'] ?? 0);
$showing = 0;
foreach ($items as $item) {
    if (!empty($item['active'])) {
        $showing++;
    }
}
?>
<?php page_head('Items', $showing . ' on the menu · ' . count($items) . ' saved', '<a class="button ghost" href="categories.php">Categories</a>'); ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<?php if ($categories === []): ?>
  <p class="empty">Add a category before adding items.</p>
<?php else: ?>
<?php if (can_manage()): ?>
  <form class="panel" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int) $editing['id'] ?>"><?php endif; ?>
    <h2><?= $editing ? 'Edit ' . h($name) : 'New item' ?></h2>
    <div class="row">
      <label>Name
        <input name="name" maxlength="120" required value="<?= h($name) ?>" placeholder="Black Coffee Hard">
      </label>
      <label>Category
        <select name="category_id">
          <?php foreach ($categories as $category): ?>
            <option value="<?= (int) $category['id'] ?>"<?= $category['id'] === $categoryId ? ' selected' : '' ?>><?= h($category['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Price
        <input name="price" inputmode="decimal" required value="<?= h($price) ?>" placeholder="10.00">
      </label>
    </div>
    <label>Photo
      <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
    </label>
    <?php if ($editing && !empty($editing['photo'])): ?>
      <img class="item-photo" src="media.php?item=<?= (int) $editing['id'] ?>" alt="">
      <label class="check">
        <input type="checkbox" name="remove_photo" value="1">
        Remove the current photo
      </label>
    <?php endif; ?>
    <label class="check">
      <input type="checkbox" name="active" value="1"<?= $active ? ' checked' : '' ?>>
      Show on the waiter menu
    </label>
    <div class="actions">
      <button type="submit"><?= $editing ? 'Save item' : 'Add item' ?></button>
      <?php if ($editing): ?><a class="button ghost" href="items.php">Cancel</a><?php endif; ?>
    </div>
  </form>
<?php endif; ?>
<?php endif; ?>
<?php if ($items === []): ?>
  <p class="empty">No items yet.</p>
<?php else: ?>
  <?php
  $byCategory = [];
  foreach ($items as $item) {
      $byCategory[(int) $item['category_id']][] = $item;
  }
  ?>
  <div class="toolbar">
    <label class="find">Find an item
      <input data-find="#item-list" placeholder="Name">
    </label>
  </div>
  <div class="filters">
    <button type="button" class="<?= $focusCategory === 0 ? 'current' : '' ?>" data-cat-btn="#item-list" data-value="">All</button>
    <?php foreach ($categories as $category): ?>
      <button type="button" class="<?= $focusCategory === (int) $category['id'] ? 'current' : '' ?>" data-cat-btn="#item-list" data-value="<?= (int) $category['id'] ?>"><?= h($category['name']) ?> · <?= (int) $category['item_count'] ?></button>
    <?php endforeach; ?>
  </div>
  <div class="filters menu-state">
    <button type="button" class="current" data-state-set="#item-list" data-value="">Any</button>
    <button type="button" data-state-set="#item-list" data-value="on">On menu</button>
    <button type="button" data-state-set="#item-list" data-value="off">Hidden</button>
  </div>
  <div id="item-list" class="menu-board" data-list<?= $focusCategory > 0 ? ' data-cat="' . (int) $focusCategory . '"' : '' ?>>
    <?php $shade = 0; ?>
    <?php foreach ($categories as $category): ?>
      <?php $group = $byCategory[(int) $category['id']] ?? []; ?>
      <?php if ($group === []) { continue; } ?>
      <section class="menu-group" data-group="<?= (int) $category['id'] ?>" data-shade="<?= (int) ($shade % 4) ?>">
      <?php $shade++; ?>
        <h2><?= h($category['name']) ?> <span class="muted"><?= count($group) ?></span></h2>
        <div class="menu-grid">
          <?php foreach ($group as $index => $item): ?>
            <article class="item-card<?= !empty($editing) && $editing['id'] === $item['id'] ? ' editing' : '' ?><?= $item['active'] ? '' : ' off-menu' ?>" data-text="<?= h($item['name'] . ' ' . $item['category_name']) ?>" data-cat="<?= (int) $item['category_id'] ?>" data-state="<?= $item['active'] ? 'on' : 'off' ?>">
              <?php if (!empty($item['photo'])): ?><img class="item-photo" src="media.php?item=<?= (int) $item['id'] ?>" alt=""><?php endif; ?>
              <header>
                <strong><?= h($item['name']) ?></strong>
                <span><?= h(money_label($item['price'])) ?></span>
              </header>
              <p class="menu-flag"><?= $item['active'] ? 'On menu' : 'Hidden' ?></p>
              <?php if (can_manage()): ?>
              <div class="actions">
                <a class="button ghost" href="items.php?id=<?= (int) $item['id'] ?>">Edit</a>
                <form method="post">
                  <?= csrf_field() ?>
                  <input type="hidden" name="action" value="toggle">
                  <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                  <button class="ghost" type="submit"><?= $item['active'] ? 'Hide' : 'Show' ?></button>
                </form>
                <?php if ($index > 0): ?>
                  <form method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="move">
                    <input type="hidden" name="direction" value="-1">
                    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                    <button class="ghost" type="submit" aria-label="Move earlier">Up</button>
                  </form>
                <?php endif; ?>
                <?php if ($index < count($group) - 1): ?>
                  <form method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="move">
                    <input type="hidden" name="direction" value="1">
                    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                    <button class="ghost" type="submit" aria-label="Move later">Down</button>
                  </form>
                <?php endif; ?>
              </div>
              <?php endif; ?>
            </article>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endforeach; ?>
  </div>
  <p class="empty" data-none="#item-list" hidden>Nothing matches that search.</p>
<?php endif; ?>
<?php layout_footer(); ?>
