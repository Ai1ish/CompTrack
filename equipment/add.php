<?php
$pageTitle = 'Add Equipment';
require_once __DIR__ . '/../includes/app_start.php';

$categories = $pdo->query('SELECT * FROM categories ORDER BY category_name')->fetchAll();
$item = [
    'asset_number' => '',
    'equipment_name' => '',
    'category_id' => '',
    'brand' => '',
    'model' => '',
    'serial_number' => '',
    'laboratory_room' => '',
    'purchase_date' => '',
    'status' => 'Available',
    'remarks' => '',
    'image_path' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = array_merge($item, [
        'asset_number' => strtoupper(trim($_POST['asset_number'] ?? '')),
        'equipment_name' => trim($_POST['equipment_name'] ?? ''),
        'category_id' => trim($_POST['category_id'] ?? ''),
        'brand' => trim($_POST['brand'] ?? ''),
        'model' => trim($_POST['model'] ?? ''),
        'serial_number' => trim($_POST['serial_number'] ?? ''),
        'laboratory_room' => trim($_POST['laboratory_room'] ?? ''),
        'purchase_date' => trim($_POST['purchase_date'] ?? ''),
        'status' => trim($_POST['status'] ?? 'Available'),
        'remarks' => trim($_POST['remarks'] ?? ''),
    ]);

    if ($item['asset_number'] === '' || $item['equipment_name'] === '') {
        set_flash('error', 'Asset number and equipment name are required.');
    } elseif (!valid_asset_number($item['asset_number'])) {
        set_flash('error', 'Asset number must be 3-50 characters and may contain letters, numbers, hyphens, or underscores.');
    } elseif (!in_array($item['status'], status_options(), true)) {
        set_flash('error', 'Invalid equipment status.');
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM equipment WHERE asset_number = ?');
        $stmt->execute([$item['asset_number']]);

        if ((int) $stmt->fetchColumn() > 0) {
            set_flash('error', 'Asset number already exists.');
        } else {
            try {
                $item['image_path'] = save_equipment_image($_FILES['item_image'] ?? []);
                $stmt = $pdo->prepare(
                    'INSERT INTO equipment
                    (asset_number, equipment_name, category_id, brand, model, serial_number, laboratory_room, purchase_date, status, remarks, image_path)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([
                    $item['asset_number'],
                    $item['equipment_name'],
                    $item['category_id'] !== '' ? $item['category_id'] : null,
                    $item['brand'],
                    $item['model'],
                    $item['serial_number'],
                    $item['laboratory_room'],
                    $item['purchase_date'] !== '' ? $item['purchase_date'] : null,
                    $item['status'],
                    $item['remarks'],
                    $item['image_path'],
                ]);
                $equipmentId = (int) $pdo->lastInsertId();
                log_activity($pdo, 'Added equipment: ' . $item['asset_number'], $equipmentId);
                set_flash('success', 'Equipment added successfully.');
                redirect('equipment/list.php');
            } catch (RuntimeException $e) {
                set_flash('error', $e->getMessage());
            }
        }
    }
}
?>
<div class="page-head">
    <div>
        <h1 class="h3 mb-1">Add Equipment</h1>
        <p class="text-muted mb-0">Create a new laboratory asset record.</p>
    </div>
</div>
<?php require_once __DIR__ . '/form.php'; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
