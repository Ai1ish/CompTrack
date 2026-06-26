<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function base_url(string $path = ''): string
{
    return '/PHP%20PROJECT/CompTrack/' . ltrim($path, '/');
}

function redirect(string $path): never
{
    header('Location: ' . base_url($path));
    exit;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_login(): void
{
    if (!current_user()) {
        $_SESSION['flash_error'] = 'Please log in to continue.';
        redirect('auth/login.php');
    }
}

function require_admin(): void
{
    require_login();
    if (($_SESSION['user']['role'] ?? '') !== 'Administrator') {
        $_SESSION['flash_error'] = 'Administrator access is required.';
        redirect('dashboard/index.php');
    }
}

function flash(string $type): ?string
{
    $key = "flash_{$type}";
    if (!isset($_SESSION[$key])) {
        return null;
    }

    $message = $_SESSION[$key];
    unset($_SESSION[$key]);
    return $message;
}

function set_flash(string $type, string $message): void
{
    $_SESSION["flash_{$type}"] = $message;
}

function log_activity(PDO $pdo, string $action, ?int $equipmentId = null): void
{
    $userId = $_SESSION['user']['user_id'] ?? null;

    try {
        if ($userId !== null) {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE user_id = ?');
            $stmt->execute([$userId]);
            $userId = (int) $stmt->fetchColumn() > 0 ? (int) $userId : null;
        }

        $stmt = $pdo->prepare('INSERT INTO activity_logs (user_id, equipment_id, action) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $equipmentId, $action]);
    } catch (PDOException) {
        // Activity logs should never block the main user action.
    }
}

function valid_asset_number(string $assetNumber): bool
{
    return (bool) preg_match('/^[A-Z0-9][A-Z0-9\-_]{2,49}$/i', $assetNumber);
}

function status_options(): array
{
    return ['Available', 'In Use', 'Under Maintenance', 'Damaged', 'Retired'];
}

function selected(string $actual, string $expected): string
{
    return $actual === $expected ? 'selected' : '';
}

function status_class(string $status): string
{
    return match ($status) {
        'Available' => 'success',
        'In Use' => 'primary',
        'Under Maintenance' => 'warning',
        'Damaged' => 'danger',
        'Retired' => 'secondary',
        default => 'light',
    };
}

function save_equipment_image(array $file, ?string $currentPath = null): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return $currentPath;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Image upload failed. Please try again.');
    }

    if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
        throw new RuntimeException('Image must not be larger than 2 MB.');
    }

    $tmpName = $file['tmp_name'] ?? '';
    $mimeType = mime_content_type($tmpName);
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($extensions[$mimeType])) {
        throw new RuntimeException('Only JPG, PNG, and WebP images are allowed.');
    }

    $uploadDir = __DIR__ . '/../uploads/equipment';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    if (!is_writable($uploadDir)) {
        throw new RuntimeException('The uploads/equipment folder is not writable. Please allow write permission for Apache.');
    }

    $filename = 'equipment_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extensions[$mimeType];
    $destination = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($tmpName, $destination)) {
        throw new RuntimeException('Could not save uploaded image.');
    }

    if ($currentPath) {
        delete_equipment_image($currentPath);
    }

    return 'uploads/equipment/' . $filename;
}

function delete_equipment_image(?string $path): void
{
    if (!$path) {
        return;
    }

    $fullPath = realpath(__DIR__ . '/../' . $path);
    $uploadRoot = realpath(__DIR__ . '/../uploads/equipment');

    if ($fullPath && $uploadRoot && str_starts_with($fullPath, $uploadRoot) && is_file($fullPath)) {
        unlink($fullPath);
    }
}
