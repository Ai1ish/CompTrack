<nav class="navbar navbar-expand-lg navbar-dark app-navbar sticky-top">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand app-brand" href="<?= base_url('dashboard/index.php') ?>">
            <img src="<?= base_url('assets/images/white lugaw.png') ?>" alt="White Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="topNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item text-white-50 small px-lg-2">
                    <?= e(current_user()['fullname'] ?? '') ?> (<?= e(current_user()['role'] ?? '') ?>)
                </li>
                <li class="nav-item">
                    <a class="btn btn-sm btn-outline-light" href="<?= base_url('auth/logout.php') ?>">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
