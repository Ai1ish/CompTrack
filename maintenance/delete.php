<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT equipment_id FROM maintenance WHERE maintenance_id = ?');
$stmt->execute([$id]);
$record = $stmt->fetch();

if ($record) {
    $stmt = $pdo->prepare('DELETE FROM maintenance WHERE maintenance_id = ?');
    $stmt->execute([$id]);
    log_activity($pdo, 'Deleted maintenance record', (int) $record['equipment_id']);
    set_flash('success', 'Maintenance record deleted successfully.');
}

redirect('maintenance/index.php');
