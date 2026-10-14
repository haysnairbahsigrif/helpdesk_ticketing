<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('user');

$jumlahTiketAktif = 0;
$jumlahTiketSelesai = 0;
$gagalMemuatRingkasan = false;

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/tiket.php';

    $ringkasanUser = ambilRingkasanTiketUser(
        $pdo,
        (int) $_SESSION['akun']['id']
    );

    $jumlahTiketAktif = (int) $ringkasanUser['Baru']
        + (int) $ringkasanUser['Diproses'];

    $jumlahTiketSelesai = (int) $ringkasanUser['Selesai'];
} catch (PDOException $error) {
    error_log($error->getMessage());
    $gagalMemuatRingkasan = true;
}

$judulHalaman = 'Beranda';
$cssHalaman = '/assets/css/user-home.css';

require __DIR__ . '/../../app/includes/header-user.php';
?>

<main class="user-home">
    <div class="container">
        <section class="user-home-welcome" aria-labelledby="judul-beranda">
            <h1 id="judul-beranda">
                Halo, <?php
                echo htmlspecialchars(
                    $_SESSION['akun']['nama'],
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>!
            </h1>

            <p>
                Ada kendala IT? Buat laporan atau pantau tiket Anda di sini.
            </p>
        </section>

        <?php
        require __DIR__ . '/../../app/components/pesan.php';
        require __DIR__ . '/../../app/components/kartu-beranda-user.php';
        ?>
    </div>
</main>

<?php
require __DIR__ . '/../../app/includes/footer-user.php';
?>