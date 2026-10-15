<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

require_once __DIR__ . '/../../app/functions/tanggal.php';

$cssHalaman = '/assets/css/admin-tickets.css';

// Periksa pencarian dan filter.
$pencarian = $_GET['q'] ?? '';
$status = $_GET['status'] ?? '';

if (!is_string($pencarian) || !is_string($status)) {
    http_response_code(400);
    exit('Parameter pencarian tidak valid.');
}

$pencarian = trim($pencarian);
$panjangPencarian = preg_match_all('/./us', $pencarian);

if ($panjangPencarian === false || $panjangPencarian > 100) {
    http_response_code(400);
    exit('Pencarian harus berupa teks valid, maksimal 100 karakter.');
}

if (!in_array($status, ['', 'Baru', 'Diproses', 'Selesai'], true)) {
    http_response_code(400);
    exit('Filter status tidak valid.');
}

// Admin menggunakan satu daftar untuk seluruh tiket.
// Variabel ini masih digunakan oleh fungsi dan komponen yang ada.
$tampilan = 'semua';

// Periksa nomor halaman.
$halamanMasukan = $_GET['page'] ?? '1';

$halaman = is_string($halamanMasukan)
    ? filter_var(
        $halamanMasukan,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    )
    : false;

if ($halaman === false) {
    http_response_code(400);
    exit('Nomor halaman tidak valid.');
}

$perHalaman = 10;

$daftarTiket = [];
$riwayatPrioritasPerTiket = [];
$gagalMemuatRiwayatPrioritas = false;
$totalTiket = 0;
$totalHalaman = 1;
$offset = 0;
$gagalMemuatTiket = false;

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/tiket.php';

    $totalTiket = hitungTiketAdmin(
        $pdo,
        $pencarian,
        $status,
        $tampilan
    );

    $totalHalaman = max(
        1,
        (int) ceil($totalTiket / $perHalaman)
    );

    // Gunakan halaman terakhir jika nomor halaman terlalu besar.
    $halaman = min($halaman, $totalHalaman);
    $offset = ($halaman - 1) * $perHalaman;

    $daftarTiket = ambilSemuaTiket(
        $pdo,
        $pencarian,
        $status,
        $perHalaman,
        $offset,
        $tampilan
    );
} catch (PDOException $error) {
    error_log($error->getMessage());
    $gagalMemuatTiket = true;
}

$menuAktif = 'tiket';

$judulHalaman = 'Daftar Tiket';

$deskripsiHalaman =
    'Cari laporan pengguna dan pantau penanganannya melalui filter tiket.';

if (!$gagalMemuatTiket && $daftarTiket !== []) {
    try {
        require_once __DIR__ . '/../../app/functions/prioritas.php';

        $daftarIdTiket = [];

        foreach ($daftarTiket as $tiketDaftar) {
            $daftarIdTiket[] = (int) $tiketDaftar['id'];
        }

        $riwayatPrioritasPerTiket = ambilRiwayatPrioritasAdmin(
            $pdo,
            $daftarIdTiket
        );
    } catch (PDOException $error) {
        error_log($error->getMessage());
        $gagalMemuatRiwayatPrioritas = true;
    }
}

require __DIR__ . '/../../app/includes/header-admin.php';
?>

<main class="admin-main">
    <section class="admin-hero" aria-labelledby="judul-tiket">
        <h1 id="judul-tiket"><?php
                                echo htmlspecialchars($judulHalaman, ENT_QUOTES, 'UTF-8');
                                ?></h1>

        <p><?php
            echo htmlspecialchars($deskripsiHalaman, ENT_QUOTES, 'UTF-8');
            ?></p>
    </section>

    <div class="admin-content">
        <?php
        require __DIR__ . '/../../app/components/pesan.php';
        require __DIR__ . '/../../app/components/form-cari-tiket.php';
        ?>

        <div id="daftar-tiket">
            <?php if ($gagalMemuatTiket): ?>
                <div class="notification" role="alert">
                    Daftar tiket belum dapat dimuat.
                    Silakan muat ulang halaman.
                </div>

            <?php elseif ($daftarTiket === []): ?>
                <div class="notification" role="status">
                    <?php if ($pencarian !== '' || $status !== ''): ?>
                        Tidak ada tiket yang cocok dengan pencarian
                        atau filter yang dipilih.
                    <?php else: ?>
                        <?php
                        echo 'Belum ada tiket yang masuk.';
                        ?>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <p class="ticket-result-count">
                    Menampilkan
                    <?php echo $offset + 1; ?>–<?php
                                                echo $offset + count($daftarTiket);
                                                ?>
                    dari <?php echo $totalTiket; ?> tiket.
                </p>

                <?php
                require __DIR__
                    . '/../../app/components/daftar-tiket-admin.php';
                ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
require __DIR__ . '/../../app/includes/footer-admin.php';
?>