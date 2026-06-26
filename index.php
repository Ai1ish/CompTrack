<?php
require_once __DIR__ . '/includes/functions.php';

if (current_user()) {
    redirect('dashboard/index.php');
}

redirect('auth/login.php');
