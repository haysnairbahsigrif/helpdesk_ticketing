<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

// Periksa pencarian.
$pencarian = $_GET['q'] ?? '';

if (!is_string($pencarian)) {
    http_response_code(400);
    exit('Pencarian tidak valid.');
}

$pencarian = trim($pencarian);
$panjang = preg_match_all('/./us', $pencarian);

if ($panjang === false || $panjang > 100) {
    http_response_code(400);
    exit('Pencarian maksimal 100 karakter.');
}

// Periksa filter status.
$status = $_GET['status'] ?? '';

if (
    !is_string($status)
    || !in_array($status, ['', 'aktif', 'nonaktif'], true)
) {
    http_response_code(400);
    exit('Filter status tidak valid.');
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

// Tampilkan maksimal 10 akun per halaman.
$perHalaman = 10;

$daftarUser = [];
$totalUser = 0;
$totalHalaman = 1;
$gagalMemuatUser = false;

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/user.php';

    $totalUser = hitungUser($pdo, $pencarian, $status);

    $totalHalaman = max(
        1,
        (int) ceil($totalUser / $perHalaman)
    );

    // Jika halaman melampaui jumlah hasil, gunakan halaman terakhir.
    $halaman = min($halaman, $totalHalaman);
    $offset = ($halaman - 1) * $perHalaman;

    $daftarUser = ambilDaftarUser(
        $pdo,
        $pencarian,
        $status,
        $perHalaman,
        $offset
    );
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

    <?php require __DIR__ . '/../../app/components/form-cari-user.php'; ?>

    <?php require __DIR__ . '/../../app/components/daftar-user.php'; ?>

    <?php if (!$gagalMemuatUser): ?>
        <?php require __DIR__ . '/../../app/components/pagination-user.php'; ?>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/../../app/includes/footer.php'; ?>