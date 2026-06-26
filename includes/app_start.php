<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';
require_login();
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/navbar.php';
?>
<div class="app-shell">
    <?php require_once __DIR__ . '/sidebar.php'; ?>
    <div class="app-content">
        <main class="container-fluid py-4">
            <?php if ($message = flash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= e($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if ($message = flash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= e($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
