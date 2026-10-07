<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

$cssHalaman = '/assets/css/users.css';

require __DIR__ . '/../../app/includes/header.php';
?>

<main class="container main-content">
    <div class="users-heading">
        <h2>Tambah akun user</h2>
        <p>Akun baru dibuat oleh administrator.</p>
        <a href="/admin/users.php">Kembali ke daftar user</a>
    </div>

    <?php require __DIR__ . '/../../app/components/pesan.php'; ?>
    <?php require __DIR__ . '/../../app/components/form-user.php'; ?>
</main>

<?php require __DIR__ . '/../../app/includes/footer.php'; ?>