<?php
$pageTitle = 'Equipment Details';
require_once __DIR__ . '/../includes/app_start.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare(
    'SELECT e.*, c.category_name
     FROM equipment e
     LEFT JOIN categories c ON c.category_id = e.category_id
     WHERE e.equipment_id = ?'
);
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    set_flash('error', 'Equipment record not found.');
    redirect('equipment/list.php');
}

$maintenanceStmt = $pdo->prepare('SELECT * FROM maintenance WHERE equipment_id = ? ORDER BY maintenance_date DESC');
$maintenanceStmt->execute([$id]);
$maintenanceRecords = $maintenanceStmt->fetchAll();
?>
<div class="page-head">
    <div>
        <h1 class="h3 mb-1"><?= e($item['equipment_name']) ?></h1>
        <p class="text-muted mb-0"><?= e($item['asset_number']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-primary" href="<?= base_url('equipment/edit.php?id=' . $id) ?>"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a class="btn btn-primary" href="<?= base_url('maintenance/add.php?equipment_id=' . $id) ?>"><i class="bi bi-tools me-1"></i>Add Maintenance</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="content-panel">
            <h2 class="h5 mb-3">Asset Information</h2>
            <?php if (!empty($item['image_path'])): ?>
                <img class="item-detail-image mb-3" src="<?= base_url($item['image_path']) ?>" alt="<?= e($item['equipment_name']) ?>">
            <?php else: ?>
                <div class="item-detail-empty mb-3">
                    <i class="bi bi-image"></i>
                    <span>No item picture uploaded</span>
                </div>
            <?php endif; ?>
            <dl class="row mb-0">
                <dt class="col-sm-5">Category</dt><dd class="col-sm-7"><?= e($item['category_name'] ?? 'Uncategorized') ?></dd>
                <dt class="col-sm-5">Brand</dt><dd class="col-sm-7"><?= e($item['brand']) ?></dd>
                <dt class="col-sm-5">Model</dt><dd class="col-sm-7"><?= e($item['model']) ?></dd>
                <dt class="col-sm-5">Serial Number</dt><dd class="col-sm-7"><?= e($item['serial_number']) ?></dd>
                <dt class="col-sm-5">Laboratory Room</dt><dd class="col-sm-7"><?= e($item['laboratory_room']) ?></dd>
                <dt class="col-sm-5">Purchase Date</dt><dd class="col-sm-7"><?= e($item['purchase_date']) ?></dd>
                <dt class="col-sm-5">Status</dt><dd class="col-sm-7"><span class="badge text-bg-<?= status_class($item['status']) ?>"><?= e($item['status']) ?></span></dd>
                <dt class="col-sm-5">Remarks</dt><dd class="col-sm-7"><?= nl2br(e($item['remarks'])) ?></dd>
            </dl>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="content-panel">
            <h2 class="h5 mb-3">Maintenance History</h2>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Technician</th>
                            <th>Description</th>
                            <th>Cost</th>
                            <th>Next Schedule</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($maintenanceRecords as $record): ?>
                            <tr>
                                <td><?= e($record['maintenance_date']) ?></td>
                                <td><?= e($record['technician']) ?></td>
                                <td><?= e($record['description']) ?></td>
                                <td><?= e(number_format((float) $record['cost'], 2)) ?></td>
                                <td><?= e($record['next_schedule']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$maintenanceRecords): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">No maintenance records yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
