<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../includes/app_start.php';

$totalEquipment = (int) $pdo->query('SELECT COUNT(*) FROM equipment')->fetchColumn();
$availableEquipment = (int) $pdo->query("SELECT COUNT(*) FROM equipment WHERE status = 'Available'")->fetchColumn();
$maintenanceEquipment = (int) $pdo->query("SELECT COUNT(*) FROM equipment WHERE status = 'Under Maintenance'")->fetchColumn();
$damagedEquipment = (int) $pdo->query("SELECT COUNT(*) FROM equipment WHERE status = 'Damaged'")->fetchColumn();

$recentStmt = $pdo->query(
    'SELECT e.*, c.category_name
     FROM equipment e
     LEFT JOIN categories c ON c.category_id = e.category_id
     ORDER BY e.created_at DESC
     LIMIT 8'
);
$recentEquipment = $recentStmt->fetchAll();
?>
<div class="page-head">
    <div>
        <h1 class="h3 mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Inventory overview and recently added equipment.</p>
    </div>
    <a class="btn btn-primary" href="<?= base_url('equipment/add.php') ?>">
        <i class="bi bi-plus-lg me-1"></i>Add Equipment
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="label">Total Equipment</div>
            <div class="value"><?= $totalEquipment ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="label">Available</div>
            <div class="value text-success"><?= $availableEquipment ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="label">Under Maintenance</div>
            <div class="value text-warning"><?= $maintenanceEquipment ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="label">Damaged</div>
            <div class="value text-danger"><?= $damagedEquipment ?></div>
        </div>
    </div>
</div>

<div class="content-panel">
    <h2 class="h5 mb-3">Recently Added Equipment</h2>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Asset No.</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Room</th>
                    <th>Status</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentEquipment as $item): ?>
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
                        <td><?= e($item['laboratory_room']) ?></td>
                        <td><span class="badge text-bg-<?= status_class($item['status']) ?>"><?= e($item['status']) ?></span></td>
                        <td><?= e(date('M d, Y', strtotime($item['created_at']))) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$recentEquipment): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No equipment records yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
