<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$fullname = '';
$email = '';
$username = '';
$role = 'Laboratory Staff';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'Laboratory Staff';
    $allowedRoles = ['Administrator', 'Laboratory Staff'];

    if ($fullname === '' || $email === '' || $username === '' || $password === '') {
        set_flash('error', 'Please complete all required fields.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('error', 'Please enter a valid email address.');
    } elseif (strlen($password) < 6) {
        set_flash('error', 'Password must be at least 6 characters.');
    } elseif ($password !== $confirmPassword) {
        set_flash('error', 'Passwords do not match.');
    } elseif (!in_array($role, $allowedRoles, true)) {
        set_flash('error', 'Invalid role selected.');
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);

        if ((int) $stmt->fetchColumn() > 0) {
            set_flash('error', 'Username or email already exists.');
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (fullname, email, username, password, role) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$fullname, $email, $username, password_hash($password, PASSWORD_DEFAULT), $role]);
            set_flash('success', 'Registration successful. You can now log in.');
            redirect('auth/login.php');
        }
    }
}

$pageTitle = 'Register';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="auth-page">
    <div class="auth-card">
        <div class="mb-4 text-center">
            <img class="auth-logo" src="<?= base_url('assets/images/comptrack-logo.png') ?>" alt="CompTrack Logo">
            <h1 class="h4 fw-bold mb-1">Create Account</h1>
            <p class="text-muted mb-0">Register authorized laboratory personnel.</p>
        </div>

        <?php if ($message = flash('error')): ?>
            <div class="alert alert-danger"><?= e($message) ?></div>
        <?php endif; ?>

        <form method="post" novalidate>
            <div class="mb-3">
                <label class="form-label" for="fullname">Full Name</label>
                <input class="form-control" id="fullname" name="fullname" value="<?= e($fullname) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" id="email" name="email" type="email" value="<?= e($email) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input class="form-control" id="username" name="username" value="<?= e($username) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="role">Role</label>
                <select class="form-select" id="role" name="role">
                    <option value="Laboratory Staff" <?= selected($role, 'Laboratory Staff') ?>>Laboratory Staff</option>
                    <option value="Administrator" <?= selected($role, 'Administrator') ?>>Administrator</option>
                </select>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" id="password" name="password" type="password" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="confirm_password">Confirm Password</label>
                    <input class="form-control" id="confirm_password" name="confirm_password" type="password" required>
                </div>
            </div>
            <button class="btn btn-primary w-100 mt-4" type="submit">
                <i class="bi bi-person-plus me-1"></i>Register
            </button>
        </form>
        <p class="text-center mt-3 mb-0">
            Already registered? <a href="<?= base_url('auth/login.php') ?>">Login</a>
        </p>
    </div>
</div>
</body>
</html>
