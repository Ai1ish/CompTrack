<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (current_user()) {
    redirect('dashboard/index.php');
}

$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        set_flash('error', 'Username and password are required.');
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1');
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'user_id' => (int) $user['user_id'],
                'fullname' => $user['fullname'],
                'username' => $user['username'],
                'role' => $user['role'],
            ];
            log_activity($pdo, 'Logged in');
            redirect('dashboard/index.php');
        }

        set_flash('error', 'Invalid username or password.');
    }
}

$pageTitle = 'Login';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="auth-page">
    <div class="auth-card">
        <div class="mb-4 text-center">
            <img class="auth-logo" src="<?= base_url('assets/images/comptrack-logo.png') ?>" alt="CompTrack Logo">
            <p class="text-muted mb-0">Computer Laboratory Inventory Management System</p>
        </div>

        <?php if ($message = flash('error')): ?>
            <div class="alert alert-danger"><?= e($message) ?></div>
        <?php endif; ?>
        <?php if ($message = flash('success')): ?>
            <div class="alert alert-success"><?= e($message) ?></div>
        <?php endif; ?>

        <form method="post" novalidate>
            <div class="mb-3">
                <label class="form-label" for="username">Username or Email</label>
                <input class="form-control" id="username" name="username" value="<?= e($username) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">
                <i class="bi bi-box-arrow-in-right me-1"></i>Login
            </button>
        </form>
        <p class="text-center mt-3 mb-0">
            No account yet? <a href="<?= base_url('auth/register.php') ?>">Register</a>
        </p>
    </div>
</div>
</body>
</html>
