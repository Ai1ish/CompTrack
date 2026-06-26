<?php
$pageTitle = 'Reports';
require_once __DIR__ . '/../includes/app_start.php';

$reportType = $_GET['type'] ?? 'all';
$allowedTypes = ['all', 'damaged', 'maintenance'];
if (!in_array($reportType, $allowedTypes, true)) {
    $reportType = 'all';
}

$counts = $pdo->query('SELECT status, COUNT(*) AS total FROM equipment GROUP BY status')->fetchAll();

if ($reportType === 'damaged') {
    $stmt = $pdo->query(
        "SELECT e.*, c.category_name
         FROM equipment e
         LEFT JOIN categories c ON c.category_id = e.category_id
         WHERE e.status = 'Damaged'
         ORDER BY e.created_at DESC"
    );
    $rows = $stmt->fetchAll();
} elseif ($reportType === 'maintenance') {
    $stmt = $pdo->query(
        'SELECT m.*, e.asset_number, e.equipment_name, e.laboratory_room
         FROM maintenance m
         INNER JOIN equipment e ON e.equipment_id = m.equipment_id
         ORDER BY m.maintenance_date DESC'
    );
    $rows = $stmt->fetchAll();
} else {
    $stmt = $pdo->query(
        'SELECT e.*, c.category_name
         FROM equipment e
         LEFT JOIN categories c ON c.category_id = e.category_id
         ORDER BY e.asset_number'
    );
    $rows = $stmt->fetchAll();
}
?>
<div class="page-head no-print">
    <div>
        <h1 class="h3 mb-1">Reports</h1>
        <p class="text-muted mb-0">Generate printable inventory, damaged item, and maintenance reports.</p>
    </div>
    <button class="btn btn-primary" onclick="window.print()"><i class="bi bi-printer me-1"></i>Print</button>
</div>

<div class="content-panel">
    <div class="print-only mb-4">
        <h1 class="h3 mb-1">CompTrack Inventory Report</h1>
        <p class="mb-0">Generated <?= e(date('F d, Y h:i A')) ?></p>
    </div>

    <form class="row g-3 align-items-end no-print mb-4" method="get">
        <div class="col-md-4">
            <label class="form-label" for="type">Report Type</label>
            <select class="form-select" id="type" name="type">
                <option value="all" <?= selected($reportType, 'all') ?>>All Equipment</option>
                <option value="damaged" <?= selected($reportType, 'damaged') ?>>Damaged Items</option>
                <option value="maintenance" <?= selected($reportType, 'maintenance') ?>>Maintenance Records</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-primary w-100" type="submit"><i class="bi bi-file-earmark-text me-1"></i>Generate</button>
        </div>
    </form>

    <div class="row g-3 mb-4">
        <?php foreach ($counts as $count): ?>
            <div class="col-sm-6 col-lg-3">
                <div class="border rounded p-3">
                    <div class="text-muted small"><?= e($count['status']) ?></div>
                    <div class="h4 mb-0"><?= (int) $count['total'] ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="table-responsive">
        <?php if ($reportType === 'maintenance'): ?>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Asset No.</th>
                        <th>Equipment</th>
                        <th>Room</th>
                        <th>Technician</th>
                        <th>Description</th>
                        <th>Cost</th>
                        <th>Next Schedule</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['maintenance_date']) ?></td>
                            <td><?= e($row['asset_number']) ?></td>
                            <td><?= e($row['equipment_name']) ?></td>
                            <td><?= e($row['laboratory_room']) ?></td>
                            <td><?= e($row['technician']) ?></td>
                            <td><?= e($row['description']) ?></td>
                            <td><?= e(number_format((float) $row['cost'], 2)) ?></td>
                            <td><?= e($row['next_schedule']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$rows): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">No records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php else: ?>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Asset No.</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Serial</th>
                        <th>Room</th>
                        <th>Status</th>
                        <th>Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['asset_number']) ?></td>
                            <td><?= e($row['equipment_name']) ?></td>
                            <td><?= e($row['category_name'] ?? 'Uncategorized') ?></td>
                            <td><?= e($row['brand']) ?></td>
                            <td><?= e($row['model']) ?></td>
                            <td><?= e($row['serial_number']) ?></td>
                            <td><?= e($row['laboratory_room']) ?></td>
                            <td><?= e($row['status']) ?></td>
                            <td><?= e(date('Y-m-d', strtotime($row['created_at']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$rows): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">No records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
