<?php
$pageTitle = 'Equipment';
require_once __DIR__ . '/../includes/app_start.php';

$keyword = trim($_GET['q'] ?? '');
$categoryId = trim($_GET['category_id'] ?? '');
$status = trim($_GET['status'] ?? '');
$room = trim($_GET['room'] ?? '');
$dateAdded = trim($_GET['date_added'] ?? '');

$categories = $pdo->query('SELECT * FROM categories ORDER BY category_name')->fetchAll();
$rooms = $pdo->query("SELECT DISTINCT laboratory_room FROM equipment WHERE laboratory_room IS NOT NULL AND laboratory_room <> '' ORDER BY laboratory_room")->fetchAll();

$where = [];
$params = [];

if ($keyword !== '') {
    $where[] = '(e.asset_number LIKE ? OR e.equipment_name LIKE ? OR e.brand LIKE ? OR e.model LIKE ? OR e.serial_number LIKE ?)';
    $search = "%{$keyword}%";
    array_push($params, $search, $search, $search, $search, $search);
}
if ($categoryId !== '') {
    $where[] = 'e.category_id = ?';
    $params[] = $categoryId;
}
if ($status !== '') {
    $where[] = 'e.status = ?';
    $params[] = $status;
}
if ($room !== '') {
    $where[] = 'e.laboratory_room = ?';
    $params[] = $room;
}
if ($dateAdded !== '') {
    $where[] = 'DATE(e.created_at) = ?';
    $params[] = $dateAdded;
}

$sql = 'SELECT e.*, c.category_name FROM equipment e LEFT JOIN categories c ON c.category_id = e.category_id';
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY e.created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$equipment = $stmt->fetchAll();
?>
<div class="page-head">
    <div>
        <h1 class="h3 mb-1">Equipment</h1>
        <p class="text-muted mb-0">Search, filter, and manage laboratory assets.</p>
    </div>
    <a class="btn btn-primary" href="<?= base_url('equipment/add.php') ?>">
        <i class="bi bi-plus-lg me-1"></i>Add Equipment
    </a>
</div>

<div class="content-panel mb-3">
    <form class="row g-3" method="get">
        <div class="col-md-3">
            <label class="form-label" for="q">Keyword</label>
            <input class="form-control" id="q" name="q" value="<?= e($keyword) ?>" placeholder="Asset, name, brand">
        </div>
        <div class="col-md-2">
            <label class="form-label" for="category_id">Category</label>
            <select class="form-select" id="category_id" name="category_id">
                <option value="">All</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['category_id'] ?>" <?= selected($categoryId, (string) $category['category_id']) ?>><?= e($category['category_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label" for="status">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="">All</option>
                <?php foreach (status_options() as $option): ?>
                    <option value="<?= e($option) ?>" <?= selected($status, $option) ?>><?= e($option) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label" for="room">Room</label>
            <select class="form-select" id="room" name="room">
                <option value="">All</option>
                <?php foreach ($rooms as $roomOption): ?>
                    <option value="<?= e($roomOption['laboratory_room']) ?>" <?= selected($room, $roomOption['laboratory_room']) ?>><?= e($roomOption['laboratory_room']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label" for="date_added">Date Added</label>
            <input class="form-control" id="date_added" name="date_added" type="date" value="<?= e($dateAdded) ?>">
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button class="btn btn-outline-primary w-100" type="submit"><i class="bi bi-search"></i></button>
        </div>
    </form>
</div>

<div class="content-panel">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Asset No.</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Brand/Model</th>
                    <th>Room</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipment as $item): ?>
                    <tr>
                        <td>
                            <?php if (!empty($item['image_path'])): ?>
                                <img class="item-thumb" src="<?= base_url($item['image_path']) ?>" alt="<?= e($item['equipment_name']) ?>">
                            <?php else: ?>
                                <span class="item-thumb item-thumb-empty"><i class="bi bi-image"></i></span>
                            <?php endif; ?>
                        </td>
                        <td><?= e($item['asset_number']) ?></td>
                        <td><?= e($item['equipment_name']) ?></td>
                        <td><?= e($item['category_name'] ?? 'Uncategorized') ?></td>
                        <td><?= e(trim(($item['brand'] ?? '') . ' ' . ($item['model'] ?? ''))) ?></td>
                        <td><?= e($item['laboratory_room']) ?></td>
                        <td><span class="badge text-bg-<?= status_class($item['status']) ?>"><?= e($item['status']) ?></span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="<?= base_url('equipment/view.php?id=' . (int) $item['equipment_id']) ?>"><i class="bi bi-eye"></i></a>
                            <a class="btn btn-sm btn-outline-primary" href="<?= base_url('equipment/edit.php?id=' . (int) $item['equipment_id']) ?>"><i class="bi bi-pencil"></i></a>
                            <a class="btn btn-sm btn-outline-danger" data-confirm="Delete this equipment record?" href="<?= base_url('equipment/delete.php?id=' . (int) $item['equipment_id']) ?>"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$equipment): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">No equipment found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
