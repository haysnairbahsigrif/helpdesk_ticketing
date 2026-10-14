<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('user');

$DepartemenUser = null;
$gagalMemuatDepartemen = false;

try {
    require __DIR__ . '/../../app/config/database.php';
    require_once __DIR__ . '/../../app/functions/Departemen.php';

    $DepartemenUser = ambilDepartemenUser(
        $pdo,
        (int) $_SESSION['akun']['id']
    );
} catch (PDOException $error) {
    error_log($error->getMessage());
    $gagalMemuatDepartemen = true;
}

$judulHalaman = 'Tambah Tiket';

require __DIR__ . '/../../app/includes/header-user.php';
?>

<main class="user-main">
    <section class="user-hero" aria-labelledby="judul-tambah-tiket">
        <div class="container">
            <h1 id="judul-tambah-tiket">Tambah Tiket</h1>
            <p>Jelaskan kendala IT yang Anda alami.</p>
        </div>
    </section>

    <div class="container user-content">
        <div class="user-page-actions">
            <a href="/user/">Kembali ke beranda</a>

            <a href="/user/tiket.php?view=semua">
                Lihat tiket saya
            </a>
        </div>

        <div class="user-ticket-form-container">
            <?php
            require __DIR__ . '/../../app/components/pesan.php';
            require __DIR__ . '/../../app/components/form-tiket.php';
            ?>
        </div>
    </div>
</main>

<?php
require __DIR__ . '/../../app/includes/footer-user.php';
?>