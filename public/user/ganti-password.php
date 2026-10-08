<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('user', true);

$cssHalaman = '/assets/css/dashboard.css';

require __DIR__ . '/../../app/includes/header.php';
?>

<main class="container main-content">
    <div class="page-heading">
        <h2>Keamanan akun</h2>
        <p>Kelola password akun Anda.</p>

        <?php
        require __DIR__ . '/../../app/components/form-logout.php';
        ?>
    </div>

    <?php if ($_SESSION['akun']['must_change_password'] === 1): ?>
        <p class="notification" role="status">
            Anda wajib mengganti password sementara sebelum menggunakan aplikasi.
        </p>
    <?php endif; ?>

    <?php require __DIR__ . '/../../app/components/pesan.php'; ?>

    <?php
    require __DIR__ . '/../../app/components/form-ganti-password.php';
    ?>
</main>

<?php require __DIR__ . '/../../app/includes/footer.php'; ?>