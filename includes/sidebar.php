<?php $role = current_user()['role'] ?? ''; ?>
<aside class="app-sidebar">
    <a href="<?= base_url('dashboard/index.php') ?>"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
    <a href="<?= base_url('equipment/list.php') ?>"><i class="bi bi-hdd-network"></i><span>Equipment</span></a>
    <a href="<?= base_url('categories/index.php') ?>"><i class="bi bi-tags"></i><span>Categories</span></a>
    <a href="<?= base_url('maintenance/index.php') ?>"><i class="bi bi-tools"></i><span>Maintenance</span></a>
    <a href="<?= base_url('reports/index.php') ?>"><i class="bi bi-printer"></i><span>Reports</span></a>
    <?php if ($role === 'Administrator'): ?>
        <a href="<?= base_url('logs/index.php') ?>"><i class="bi bi-clock-history"></i><span>Activity Logs</span></a>
    <?php endif; ?>
</aside>
