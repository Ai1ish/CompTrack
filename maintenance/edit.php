<?php
$pageTitle = 'Edit Maintenance';
require_once __DIR__ . '/../includes/app_start.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM maintenance WHERE maintenance_id = ?');
$stmt->execute([$id]);
$record = $stmt->fetch();

if (!$record) {
    set_flash('error', 'Maintenance record not found.');
    redirect('maintenance/index.php');
}

$equipmentOptions = $pdo->query('SELECT equipment_id, asset_number, equipment_name FROM equipment ORDER BY asset_number')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $record = array_merge($record, [
        'equipment_id' => trim($_POST['equipment_id'] ?? ''),
        'maintenance_date' => trim($_POST['maintenance_date'] ?? ''),
        'technician' => trim($_POST['technician'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'cost' => trim($_POST['cost'] ?? ''),
        'next_schedule' => trim($_POST['next_schedule'] ?? ''),
    ]);

    if ($record['equipment_id'] === '' || $record['maintenance_date'] === '') {
        set_flash('error', 'Equipment and maintenance date are required.');
    } else {
        $stmt = $pdo->prepare(
            'UPDATE maintenance
             SET equipment_id = ?, maintenance_date = ?, technician = ?, description = ?, cost = ?, next_schedule = ?
             WHERE maintenance_id = ?'
        );
        $stmt->execute([
            $record['equipment_id'],
            $record['maintenance_date'],
            $record['technician'],
            $record['description'],
            $record['cost'] !== '' ? $record['cost'] : 0,
            $record['next_schedule'] !== '' ? $record['next_schedule'] : null,
            $id,
        ]);
        log_activity($pdo, 'Updated maintenance record', (int) $record['equipment_id']);
        set_flash('success', 'Maintenance record updated successfully.');
        redirect('maintenance/index.php');
    }
}
?>
<div class="page-head">
    <div>
        <h1 class="h3 mb-1">Edit Maintenance</h1>
        <p class="text-muted mb-0">Update repair history and upcoming schedule details.</p>
    </div>
</div>
<?php require_once __DIR__ . '/form.php'; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
