<?php
$pageTitle = 'Add Maintenance';
require_once __DIR__ . '/../includes/app_start.php';

$equipmentOptions = $pdo->query('SELECT equipment_id, asset_number, equipment_name FROM equipment ORDER BY asset_number')->fetchAll();
$record = [
    'equipment_id' => $_GET['equipment_id'] ?? '',
    'maintenance_date' => date('Y-m-d'),
    'technician' => '',
    'description' => '',
    'cost' => '',
    'next_schedule' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $record = [
        'equipment_id' => trim($_POST['equipment_id'] ?? ''),
        'maintenance_date' => trim($_POST['maintenance_date'] ?? ''),
        'technician' => trim($_POST['technician'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'cost' => trim($_POST['cost'] ?? ''),
        'next_schedule' => trim($_POST['next_schedule'] ?? ''),
    ];

    if ($record['equipment_id'] === '' || $record['maintenance_date'] === '') {
        set_flash('error', 'Equipment and maintenance date are required.');
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO maintenance (equipment_id, maintenance_date, technician, description, cost, next_schedule)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $record['equipment_id'],
            $record['maintenance_date'],
            $record['technician'],
            $record['description'],
            $record['cost'] !== '' ? $record['cost'] : 0,
            $record['next_schedule'] !== '' ? $record['next_schedule'] : null,
        ]);
        log_activity($pdo, 'Added maintenance record', (int) $record['equipment_id']);
        set_flash('success', 'Maintenance record added successfully.');
        redirect('maintenance/index.php');
    }
}
?>
<div class="page-head">
    <div>
        <h1 class="h3 mb-1">Add Maintenance</h1>
        <p class="text-muted mb-0">Record repair work, costs, schedules, and technician notes.</p>
    </div>
</div>
<?php require_once __DIR__ . '/form.php'; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
