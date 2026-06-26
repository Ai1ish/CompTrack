<?php
$pageTitle = 'Edit Equipment';
require_once __DIR__ . '/../includes/app_start.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM equipment WHERE equipment_id = ?');
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    set_flash('error', 'Equipment record not found.');
    redirect('equipment/list.php');
}

$categories = $pdo->query('SELECT * FROM categories ORDER BY category_name')->fetchAll();

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
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM equipment WHERE asset_number = ? AND equipment_id <> ?');
        $stmt->execute([$item['asset_number'], $id]);

        if ((int) $stmt->fetchColumn() > 0) {
            set_flash('error', 'Asset number already exists.');
        } else {
            try {
                $item['image_path'] = save_equipment_image($_FILES['item_image'] ?? [], $item['image_path'] ?? null);
                $stmt = $pdo->prepare(
                    'UPDATE equipment
                     SET asset_number = ?, equipment_name = ?, category_id = ?, brand = ?, model = ?, serial_number = ?,
                         laboratory_room = ?, purchase_date = ?, status = ?, remarks = ?, image_path = ?
                     WHERE equipment_id = ?'
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
                    $id,
                ]);
                log_activity($pdo, 'Updated equipment: ' . $item['asset_number'], $id);
                set_flash('success', 'Equipment updated successfully.');
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
        <h1 class="h3 mb-1">Edit Equipment</h1>
        <p class="text-muted mb-0">Update asset details, room assignment, and status.</p>
    </div>
</div>
<?php require_once __DIR__ . '/form.php'; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
