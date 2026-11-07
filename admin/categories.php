<?php

require __DIR__ . '/_init.php';
require_owner_page();

$error = null;
$editing = null;

try {
    if (isset($_GET['id']) && $_GET['id'] !== '') {
        foreach (list_categories() as $category) {
            if ($category['id'] === (int) $_GET['id']) {
                $editing = $category;
            }
        }
        if ($editing === null) {
            $error = 'Category not found.';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $action = (string) ($_POST['action'] ?? 'save');
        if ($action === 'delete') {
            delete_category((int) ($_POST['id'] ?? 0));
            flash_set('Category deleted.');
            redirect_to('categories.php');
        }
        $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null;
        save_category($id, (string) ($_POST['name'] ?? ''), (int) ($_POST['sort_order'] ?? 0));
        flash_set($id === null ? 'Category added.' : 'Category saved.');
        redirect_to('categories.php');
    }

    $categories = list_categories();
} catch (AppError $errorObject) {
    $error = $errorObject->getMessage();
    $categories = [];
    try {
        $categories = list_categories();
    } catch (Throwable $ignored) {
        $categories = [];
    }
} catch (PDOException $errorObject) {
    $error = 'Database error. Start MySQL and import database/schema.sql.';
    $categories = [];
}

$name = $editing['name'] ?? '';
$sort = (string) ($editing['sort_order'] ?? '0');
if ($error && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = (string) ($_POST['name'] ?? $name);
    $sort = (string) ($_POST['sort_order'] ?? $sort);
}

layout_header('Categories');
?>
<?php page_head('Categories', count($categories) . ' groups on the menu', '<a class="button ghost" href="items.php">Items</a>'); ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<?php if (can_manage()): ?>
<form class="panel" method="post">
  <?= csrf_field() ?>
  <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int) $editing['id'] ?>"><?php endif; ?>
  <div class="row">
    <label>Name
      <input name="name" maxlength="80" required value="<?= h($name) ?>">
    </label>
    <label>Sort order
      <input name="sort_order" type="number" min="0" max="9999" required value="<?= h($sort) ?>">
    </label>
  </div>
  <div class="actions">
    <button type="submit"><?= $editing ? 'Save category' : 'Add category' ?></button>
    <?php if ($editing): ?><a class="button ghost" href="categories.php">Cancel</a><?php endif; ?>
  </div>
</form>
<?php endif; ?>
<?php if ($categories === []): ?>
  <p class="empty">No categories yet.</p>
<?php else: ?>
  <div class="cat-board">
    <?php foreach ($categories as $index => $category): ?>
      <article class="cat-card<?= $editing && (int) $editing['id'] === (int) $category['id'] ? ' editing' : '' ?>" data-shade="<?= (int) $index % 4 ?>">
        <p class="muted">Menu</p>
        <h2><?= h($category['name']) ?></h2>
        <p class="count"><?= (int) $category['item_count'] ?><span>items · sort <?= (int) $category['sort_order'] ?></span></p>
        <div class="actions">
          <a class="button" href="items.php?category=<?= (int) $category['id'] ?>">Open</a>
          <?php if (can_manage()): ?>
          <a class="button ghost" href="categories.php?id=<?= (int) $category['id'] ?>">Edit</a>
          <form method="post" onsubmit="return confirm('Delete this category?');">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int) $category['id'] ?>">
            <button class="ghost" type="submit">Delete</button>
          </form>
          <?php endif; ?>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php layout_footer(); ?>
