<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('user');

require_once __DIR__ . '/../../app/functions/tanggal.php';

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

// Periksa kelompok tiket yang ingin ditampilkan.
$tampilan = $_GET['view'] ?? 'semua';

if (
    !is_string($tampilan)
    || !in_array($tampilan, ['semua', 'aktif', 'riwayat'], true)
) {
    http_response_code(400);
    exit('Tampilan tiket tidak valid.');
}

// Riwayat selalu berisi tiket selesai.
// Filter Selesai tidak digunakan pada daftar tiket aktif.
if (
    $tampilan === 'riwayat'
    || ($tampilan === 'aktif' && $status === 'Selesai')
) {
    $status = '';
}

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
$ringkasanUser = [];
$totalTiket = 0;
$totalHalaman = 1;
$offset = 0;
$gagalMemuatTiket = false;

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/tiket.php';

    $userId = (int) $_SESSION['akun']['id'];

    // Ringkasan seluruh tiket milik user.
    $ringkasanUser = ambilRingkasanTiketUser($pdo, $userId);

    // Jumlah tiket yang cocok dengan pencarian dan filter.
    $totalTiket = hitungTiketUser(
        $pdo,
        $userId,
        $pencarian,
        $status,
        $tampilan
    );

    $totalHalaman = max(
        1,
        (int) ceil($totalTiket / $perHalaman)
    );

    $halaman = min($halaman, $totalHalaman);
    $offset = ($halaman - 1) * $perHalaman;

    $daftarTiket = ambilTiketUser(
        $pdo,
        $userId,
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

$judulHalaman = match ($tampilan) {
    'semua' => 'Semua Tiket Saya',
    'riwayat' => 'Riwayat Tiket Saya',
    default => 'Tiket Aktif Saya',
};

$cssHalaman = '/assets/css/ticket-detail.css';

require __DIR__ . '/../../app/includes/header-user.php';
?>

<main class="user-main">
    <section class="user-hero" aria-labelledby="judul-tiket-user">
        <div class="container">
            <h1 id="judul-tiket-user">
                <?php
                echo htmlspecialchars(
                    $judulHalaman,
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>
            </h1>

            <p>Pantau laporan dan perkembangan penanganan tiket Anda.</p>
        </div>
    </section>

    <div class="container user-content">
        <div class="user-page-actions">
            <a href="/user/">Kembali ke beranda</a>

            <a href="/user/tambah-tiket.php" class="button-primary">
                Tambah tiket
            </a>
        </div>

        <?php
        require __DIR__ . '/../../app/components/pesan.php';
        require __DIR__ . '/../../app/components/daftar-tiket.php';
        ?>
    </div>
</main>

<?php
require __DIR__ . '/../../app/includes/footer-user.php';
?>