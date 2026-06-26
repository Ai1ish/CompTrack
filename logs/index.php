<?php
$pageTitle = 'Activity Logs';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>
<div class="app-shell">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="app-content">
        <main class="container-fluid py-4">
            <?php if ($message = flash('success')): ?>
                <div class="alert alert-success"><?= e($message) ?></div>
            <?php endif; ?>
            <?php if ($message = flash('error')): ?>
                <div class="alert alert-danger"><?= e($message) ?></div>
            <?php endif; ?>
            <?php
            $stmt = $pdo->query(
                'SELECT l.*, u.fullname, u.role, e.asset_number
                 FROM activity_logs l
                 LEFT JOIN users u ON u.user_id = l.user_id
                 LEFT JOIN equipment e ON e.equipment_id = l.equipment_id
                 ORDER BY l.log_date DESC
                 LIMIT 300'
            );
            $logs = $stmt->fetchAll();
            ?>
            <div class="page-head">
                <div>
                    <h1 class="h3 mb-1">Activity Logs</h1>
                    <p class="text-muted mb-0">Recent user actions recorded by the system.</p>
                </div>
            </div>

            <div class="content-panel">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Role</th>
                                <th>Action</th>
                                <th>Asset No.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?= e(date('M d, Y h:i A', strtotime($log['log_date']))) ?></td>
                                    <td><?= e($log['fullname'] ?? 'System/Deleted User') ?></td>
                                    <td><?= e($log['role'] ?? '') ?></td>
                                    <td><?= e($log['action']) ?></td>
                                    <td><?= e($log['asset_number'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (!$logs): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">No activity logs yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
