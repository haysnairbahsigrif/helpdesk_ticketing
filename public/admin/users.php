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

// Terima checkbox dan tautan lama yang memakai status tunggal.
$statusMasukan = $_GET['status'] ?? [];

if (is_string($statusMasukan)) {
    $statusMasukan = $statusMasukan === ''
        ? []
        : [$statusMasukan];
}

if (!is_array($statusMasukan)) {
    http_response_code(400);
    exit('Filter status tidak valid.');
}

$pilihanStatusUser = [];

foreach ($statusMasukan as $nilaiStatusUser) {
    if (
        !is_string($nilaiStatusUser)
        || !in_array($nilaiStatusUser, ['aktif', 'nonaktif'], true)
    ) {
        http_response_code(400);
        exit('Filter status tidak valid.');
    }

    $pilihanStatusUser[] = $nilaiStatusUser;
}

$pilihanStatusUser = array_values(array_unique($pilihanStatusUser));

// Satu pilihan membatasi status.
// Dua pilihan atau kosong menampilkan semua status.
$status = count($pilihanStatusUser) === 1
    ? $pilihanStatusUser[0]
    : '';

$daftarDepartemen = [];

// Periksa pilihan departemen.
$departemenMasukan = $_GET['departemen'] ?? [];

if (!is_array($departemenMasukan)) {
    http_response_code(400);
    exit('Filter departemen tidak valid.');
}

$departmentIds = [];

foreach ($departemenMasukan as $idDepartemenMasukan) {
    $departmentId = is_string($idDepartemenMasukan)
        ? filter_var(
            $idDepartemenMasukan,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        )
        : false;

    if ($departmentId === false) {
        http_response_code(400);
        exit('ID departemen tidak valid.');
    }

    $departmentIds[] = $departmentId;
}

$departmentIds = array_values(array_unique($departmentIds));

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

// Siapkan nilai awal sebelum mengambil data dari database.
$daftarUser = [];
$daftarDepartemen = [];
$totalUser = 0;
$totalHalaman = 1;
$offset = 0;
$gagalMemuatUser = false;

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/user.php';
    require_once __DIR__ . '/../../app/functions/departemen.php';

    // Ambil daftar departemen untuk pilihan filter.
    $daftarDepartemen = ambilDaftarDepartemen($pdo);

    // Pastikan departemen yang dipilih tersedia di database.
    $idDepartemenTersedia = array_map(
        static fn(array $departemen): int => (int) $departemen['id'],
        $daftarDepartemen
    );

    if (array_diff($departmentIds, $idDepartemenTersedia) !== []) {
        http_response_code(400);
        exit('Departemen yang dipilih tidak tersedia.');
    }

    // Hitung akun sesuai pencarian, status, dan departemen.
    $totalUser = hitungUser(
        $pdo,
        $pencarian,
        $status,
        $departmentIds
    );

    $totalHalaman = max(
        1,
        (int) ceil($totalUser / $perHalaman)
    );

    // Gunakan halaman terakhir jika nomor halaman terlalu besar.
    $halaman = min($halaman, $totalHalaman);
    $offset = ($halaman - 1) * $perHalaman;

    // Ambil akun yang akan ditampilkan.
    $daftarUser = ambilDaftarUser(
        $pdo,
        $pencarian,
        $status,
        $perHalaman,
        $offset,
        $departmentIds
    );
} catch (PDOException $error) {
    error_log($error->getMessage());
    $gagalMemuatUser = true;
}

$menuAktif = 'users';
$judulHalaman = 'Kelola User';
$cssHalaman = '/assets/css/users.css';

require __DIR__ . '/../../app/includes/header-admin.php';
?>

<main class="admin-main admin-users-page">
    <section class="admin-hero" aria-labelledby="judul-users">
        <h1 id="judul-users">Kelola User</h1>
        <p>Kelola akun pengguna layanan IT Support.</p>
    </section>

    <div class="admin-content">
        <?php
        require __DIR__ . '/../../app/components/pesan.php';
        require __DIR__ . '/../../app/components/daftar-user.php';
        ?>
    </div>
</main>

<?php
require __DIR__ . '/../../app/includes/footer-admin.php';
?>