<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

$daftarUser = [];
$gagalMemuatUser = false;

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/user.php';

    $daftarUser = ambilDaftarUser($pdo);
} catch (PDOException $error) {
    error_log($error->getMessage());
    $gagalMemuatUser = true;
}

$cssHalaman = '/assets/css/users.css';

require __DIR__ . '/../../app/includes/header.php';
?>

<main class="container main-content">
    <div class="users-heading">
        <h2>Pengelolaan user</h2>
        <p>Akun pengguna layanan IT Support.</p>

        <a href="/admin/">Kembali ke dashboard</a>
        <p>
            <a href="/admin/tambah-user.php">Tambah user</a>
        </p>
    </div>

    <?php require __DIR__ . '/../../app/components/pesan.php'; ?>
    <?php require __DIR__ . '/../../app/components/daftar-user.php'; ?>
</main>

<?php require __DIR__ . '/../../app/includes/footer.php'; ?>