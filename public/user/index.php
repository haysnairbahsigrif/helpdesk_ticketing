<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('user');

$daftarTiket = [];
$gagalMemuatTiket = false;

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/tiket.php';

    $daftarTiket = ambilTiketUser(
        $pdo,
        (int) $_SESSION['akun']['id']
    );
} catch (PDOException $error) {
    error_log($error->getMessage());
    $gagalMemuatTiket = true;
}

$cssHalaman = '/assets/css/dashboard.css';

require __DIR__ . '/../../app/includes/header.php';
?>

<main class="container main-content">
    <div class="page-heading">
        <h2>Dashboard User</h2>
        <p>Kirim keluhan dan pantau status tiket Anda.</p>

        <?php
        require __DIR__ . '/../../app/components/form-logout.php';
        ?>
    </div>

    <?php require __DIR__ . '/../../app/components/pesan.php'; ?>

    <div class="ticket-layout">
        <?php require __DIR__ . '/../../app/components/form-tiket.php'; ?>

        <?php require __DIR__ . '/../../app/components/daftar-tiket.php'; ?>
    </div>
</main>

<?php require __DIR__ . '/../../app/includes/footer.php'; ?>