<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('user', true);

$judulHalaman = 'Ganti Password';

$wajibGantiPassword =
    (int) $_SESSION['akun']['must_change_password'] === 1;

require __DIR__ . '/../../app/includes/header-user.php';
?>

<main class="user-main">
    <section class="user-hero" aria-labelledby="judul-ganti-password">
        <div class="container">
            <h1 id="judul-ganti-password">Keamanan Akun</h1>
            <p>Kelola password untuk melindungi akun Anda.</p>
        </div>
    </section>

    <div class="container user-content">
        <div class="user-password-content">
            <?php if ($wajibGantiPassword): ?>
                <p class="notification" role="status">
                    Anda wajib mengganti password sementara
                    sebelum menggunakan aplikasi.
                </p>
            <?php else: ?>
                <div class="panel user-back-panel">
                    <a href="/user/">
                        <span aria-hidden="true">←</span>
                        Kembali ke dashboard
                    </a>
                </div>
            <?php endif; ?>

            <?php
            require __DIR__ . '/../../app/components/pesan.php';
            require __DIR__ . '/../../app/components/form-ganti-password.php';
            ?>
        </div>
    </div>
</main>

<?php
require __DIR__ . '/../../app/includes/footer-user.php';
?>