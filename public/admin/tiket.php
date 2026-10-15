<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

require_once __DIR__ . '/../../app/functions/tanggal.php';

$cssHalaman = '/assets/css/admin-tickets.css';

require_once __DIR__ . '/../../app/functions/filter-tiket.php';

// Periksa pencarian.
$pencarian = $_GET['q'] ?? '';

if (!is_string($pencarian)) {
    http_response_code(400);
    exit('Pencarian tidak valid.');
}

$pencarian = trim($pencarian);
$panjangPencarian = preg_match_all('/./us', $pencarian);

if ($panjangPencarian === false || $panjangPencarian > 100) {
    http_response_code(400);
    exit('Pencarian maksimal 100 karakter dan harus berupa teks valid.');
}

// Status tunggal dari kartu dashboard juga tetap diterima.
try {
    $status = normalisasiPilihanFilter(
        $_GET['status'] ?? [],
        ['Baru', 'Diproses', 'Selesai']
    );

    $filterPrioritas = normalisasiPilihanFilter(
        $_GET['prioritas'] ?? [],
        ['Rendah', 'Sedang', 'Tinggi', 'belum-ditentukan']
    );
} catch (InvalidArgumentException $error) {
    http_response_code(400);
    exit('Pilihan filter tidak valid.');
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
        $tampilan,
        $filterPrioritas
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
        $tampilan,
        $filterPrioritas
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
        require __DIR__ . '/../../app/components/daftar-tiket-admin.php';
        ?>
    </div>
</main>

<?php
require __DIR__ . '/../../app/includes/footer-admin.php';
?>