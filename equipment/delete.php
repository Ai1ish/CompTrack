<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT asset_number, image_path FROM equipment WHERE equipment_id = ?');
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    set_flash('error', 'Equipment record not found.');
    redirect('equipment/list.php');
}

$stmt = $pdo->prepare('DELETE FROM equipment WHERE equipment_id = ?');
$stmt->execute([$id]);
delete_equipment_image($item['image_path'] ?? null);
log_activity($pdo, 'Deleted equipment: ' . $item['asset_number']);
set_flash('success', 'Equipment deleted successfully.');
redirect('equipment/list.php');
