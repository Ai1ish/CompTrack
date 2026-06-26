<?php
$pageTitle = 'Maintenance';
require_once __DIR__ . '/../includes/app_start.php';

$stmt = $pdo->query(
    'SELECT m.*, e.asset_number, e.equipment_name
     FROM maintenance m
     INNER JOIN equipment e ON e.equipment_id = m.equipment_id
     ORDER BY m.maintenance_date DESC, m.maintenance_id DESC'
);
$records = $stmt->fetchAll();
?>
<div class="page-head">
    <div>
        <h1 class="h3 mb-1">Maintenance</h1>
        <p class="text-muted mb-0">Repair history, schedules, and technician remarks.</p>
    </div>
    <a class="btn btn-primary" href="<?= base_url('maintenance/add.php') ?>">
        <i class="bi bi-plus-lg me-1"></i>Add Maintenance
    </a>
</div>

<div class="content-panel">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Equipment</th>
                    <th>Technician</th>
                    <th>Description</th>
                    <th>Cost</th>
                    <th>Next Schedule</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?= e($record['maintenance_date']) ?></td>
                        <td><?= e($record['asset_number'] . ' - ' . $record['equipment_name']) ?></td>
                        <td><?= e($record['technician']) ?></td>
                        <td><?= e($record['description']) ?></td>
                        <td><?= e(number_format((float) $record['cost'], 2)) ?></td>
                        <td><?= e($record['next_schedule']) ?></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="<?= base_url('maintenance/edit.php?id=' . (int) $record['maintenance_id']) ?>"><i class="bi bi-pencil"></i></a>
                            <a class="btn btn-sm btn-outline-danger" data-confirm="Delete this maintenance record?" href="<?= base_url('maintenance/delete.php?id=' . (int) $record['maintenance_id']) ?>"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$records): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No maintenance records yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
