<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

$menuAktif = 'dashboard';
$judulHalaman = 'Dashboard';
$cssHalaman = '/assets/css/admin-dashboard.css';

$ringkasan = [];
$gagalMemuatRingkasan = false;

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/dashboard.php';

    $ringkasan = ambilRingkasanDashboard($pdo);
} catch (PDOException $error) {
    error_log($error->getMessage());
    $gagalMemuatRingkasan = true;
}

require __DIR__ . '/../../app/includes/header-admin.php';
?>

<main class="admin-main">
    <section class="admin-hero" aria-labelledby="judul-dashboard">
        <h1 id="judul-dashboard">Dashboard IT Support</h1>
        <p>Ringkasan pengguna dan penanganan kendala IT.</p>
    </section>

    <div class="admin-content">
        <?php if ($gagalMemuatRingkasan): ?>
            <div class="notification" role="alert">
                Ringkasan belum dapat dimuat.
                Silakan muat ulang halaman.
            </div>
        <?php else: ?>
            <?php
            require __DIR__
                . '/../../app/components/ringkasan-dashboard.php';
            ?>
        <?php endif; ?>

        <section class="panel dashboard-intro">
            <h2>Pengelolaan IT Support</h2>
            <p>
                Buka menu Daftar Tiket untuk menangani keluhan
                atau Kelola User untuk mengelola akun pengguna.
            </p>
        </section>
    </div>
</main>

<?php
require __DIR__ . '/../../app/includes/footer-admin.php';
?>