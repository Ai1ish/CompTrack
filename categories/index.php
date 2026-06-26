<?php
$pageTitle = 'Categories';
require_once __DIR__ . '/../includes/app_start.php';

$editing = null;
$editId = (int) ($_GET['edit'] ?? 0);
if ($editId > 0) {
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE category_id = ?');
    $stmt->execute([$editId]);
    $editing = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $categoryName = trim($_POST['category_name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($categoryName === '') {
        set_flash('error', 'Category name is required.');
    } elseif ($categoryId > 0) {
        $stmt = $pdo->prepare('UPDATE categories SET category_name = ?, description = ? WHERE category_id = ?');
        $stmt->execute([$categoryName, $description, $categoryId]);
        log_activity($pdo, 'Updated category: ' . $categoryName);
        set_flash('success', 'Category updated successfully.');
        redirect('categories/index.php');
    } else {
        $stmt = $pdo->prepare('INSERT INTO categories (category_name, description) VALUES (?, ?)');
        $stmt->execute([$categoryName, $description]);
        log_activity($pdo, 'Added category: ' . $categoryName);
        set_flash('success', 'Category added successfully.');
        redirect('categories/index.php');
    }
}

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    $stmt = $pdo->prepare('SELECT category_name FROM categories WHERE category_id = ?');
    $stmt->execute([$deleteId]);
    $category = $stmt->fetch();

    if ($category) {
        $stmt = $pdo->prepare('DELETE FROM categories WHERE category_id = ?');
        $stmt->execute([$deleteId]);
        log_activity($pdo, 'Deleted category: ' . $category['category_name']);
        set_flash('success', 'Category deleted successfully.');
    }
    redirect('categories/index.php');
}

$categories = $pdo->query(
    'SELECT c.*, COUNT(e.equipment_id) AS equipment_count
     FROM categories c
     LEFT JOIN equipment e ON e.category_id = c.category_id
     GROUP BY c.category_id
     ORDER BY c.category_name'
)->fetchAll();
?>
<div class="page-head">
    <div>
        <h1 class="h3 mb-1">Categories</h1>
        <p class="text-muted mb-0">Manage equipment groupings such as computers, peripherals, and network devices.</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="content-panel">
            <h2 class="h5 mb-3"><?= $editing ? 'Edit Category' : 'Add Category' ?></h2>
            <form method="post">
                <input type="hidden" name="category_id" value="<?= (int) ($editing['category_id'] ?? 0) ?>">
                <div class="mb-3">
                    <label class="form-label" for="category_name">Category Name</label>
                    <input class="form-control" id="category_name" name="category_name" value="<?= e($editing['category_name'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"><?= e($editing['description'] ?? '') ?></textarea>
                </div>
                <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i>Save</button>
                <?php if ($editing): ?>
                    <a class="btn btn-outline-secondary" href="<?= base_url('categories/index.php') ?>">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="content-panel">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Equipment Count</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= e($category['category_name']) ?></td>
                                <td><?= e($category['description']) ?></td>
                                <td><?= (int) $category['equipment_count'] ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="<?= base_url('categories/index.php?edit=' . (int) $category['category_id']) ?>"><i class="bi bi-pencil"></i></a>
                                    <a class="btn btn-sm btn-outline-danger" data-confirm="Delete this category?" href="<?= base_url('categories/index.php?delete=' . (int) $category['category_id']) ?>"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$categories): ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">No categories yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
