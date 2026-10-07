<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

$daftarTiket = [];
$gagalMemuatTiket = false;

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/tiket.php';

    $daftarTiket = ambilSemuaTiket($pdo);
} catch (PDOException $error) {
    error_log($error->getMessage());
    $gagalMemuatTiket = true;
}

require __DIR__ . '/../../app/includes/header.php';
?>

<main class="container main-content">
    <div class="page-heading">
        <h2>Dashboard Admin</h2>
        <p>Pantau laporan kendala IT seluruh pengguna.</p>
        <p>
            <a href="/admin/users.php">Kelola user</a>
        </p>

        <?php
        require __DIR__ . '/../../app/components/form-logout.php';
        ?>
    </div>

    <?php require __DIR__ . '/../../app/components/pesan.php'; ?>

    <?php
    require __DIR__ . '/../../app/components/daftar-tiket-admin.php';
    ?>
</main>

<?php require __DIR__ . '/../../app/includes/footer.php'; ?>