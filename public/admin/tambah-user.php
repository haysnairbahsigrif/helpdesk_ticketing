<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

$daftarDepartemen = [];
$gagalMemuatDepartemen = false;

try {
    require __DIR__ . '/../../app/config/database.php';
    require_once __DIR__ . '/../../app/functions/Departemen.php';

    $daftarDepartemen = ambilDaftarDepartemen($pdo);
} catch (PDOException $error) {
    error_log($error->getMessage());
    $gagalMemuatDepartemen = true;
}

$menuAktif = 'users';
$judulHalaman = 'Tambah User';
$cssHalaman = '/assets/css/users.css';

require __DIR__ . '/../../app/includes/header-admin.php';
?>

<main class="admin-main">
    <section class="admin-hero" aria-labelledby="judul-tambah-user">
        <h1 id="judul-tambah-user">Tambah Akun User</h1>
        <p>Buat akun pengguna baru untuk layanan IT Support.</p>
    </section>

    <div class="admin-content">
        <div class="panel users-back-panel">
            <a href="/admin/users.php">
                <span aria-hidden="true">←</span>
                Kembali ke daftar user
            </a>
        </div>

        <?php
        require __DIR__ . '/../../app/components/pesan.php';
        require __DIR__ . '/../../app/components/form-user.php';
        ?>
    </div>
</main>

<?php
require __DIR__ . '/../../app/includes/footer-admin.php';
?>