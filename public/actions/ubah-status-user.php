<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/users.php');
    exit;
}

// Periksa token formulir.
$token = $_POST['csrf_token'] ?? '';
$tokenSession = $_SESSION['csrf_token'] ?? '';

if (
    !is_string($token)
    || !is_string($tokenSession)
    || $tokenSession === ''
    || !hash_equals($tokenSession, $token)
) {
    http_response_code(403);
    exit('Permintaan tidak valid. Muat ulang daftar user.');
}

// Periksa pencarian halaman asal.
$pencarian = $_POST['q'] ?? '';

if (!is_string($pencarian)) {
    http_response_code(400);
    exit('Parameter pencarian tidak valid.');
}

$pencarian = trim($pencarian);
$panjangPencarian = preg_match_all('/./us', $pencarian);

if ($panjangPencarian === false || $panjangPencarian > 100) {
    http_response_code(400);
    exit('Pencarian harus berupa teks valid, maksimal 100 karakter.');
}

// Terima pilihan checkbox dan formulir lama dengan status tunggal.
$filterStatusMasukan = $_POST['filter_status'] ?? [];

if (is_string($filterStatusMasukan)) {
    $filterStatusMasukan = $filterStatusMasukan === ''
        ? []
        : [$filterStatusMasukan];
}

if (!is_array($filterStatusMasukan)) {
    http_response_code(400);
    exit('Filter status tidak valid.');
}

$filterStatus = [];

foreach ($filterStatusMasukan as $nilaiFilterStatus) {
    if (
        !is_string($nilaiFilterStatus)
        || !in_array($nilaiFilterStatus, ['aktif', 'nonaktif'], true)
    ) {
        http_response_code(400);
        exit('Filter status tidak valid.');
    }

    $filterStatus[] = $nilaiFilterStatus;
}

$filterStatus = array_values(array_unique($filterStatus));

// Periksa nomor halaman asal.
$halamanMasukan = $_POST['page'] ?? '1';

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

// Periksa ID akun dan status tujuan.
$idMasukan = $_POST['user_id'] ?? '';
$statusMasukan = $_POST['is_active'] ?? '';

$userId = is_string($idMasukan)
    ? filter_var(
        $idMasukan,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    )
    : false;

if (
    $userId === false
    || !is_string($statusMasukan)
    || !in_array($statusMasukan, ['0', '1'], true)
) {
    http_response_code(400);
    exit('ID atau status akun tidak valid.');
}

// Perbarui status akun.
try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/user.php';

    $berhasil = ubahStatusUser(
        $pdo,
        $userId,
        (int) $statusMasukan
    );

    $pesan = $berhasil
        ? 'akun-status-berhasil'
        : 'akun-status-tidak-berubah';
} catch (PDOException $error) {
    error_log($error->getMessage());
    $pesan = 'akun-status-gagal';
}

// Kembali ke daftar dengan pilihan sebelumnya.
$parameterKembali = [
    'page' => $halaman,
    'pesan' => $pesan,
];

if ($pencarian !== '') {
    $parameterKembali['q'] = $pencarian;
}

if ($filterStatus !== []) {
    $parameterKembali['status'] = $filterStatus;
}

$urlKembali = '/admin/users.php?'
    . http_build_query($parameterKembali);

header('Location: ' . $urlKembali, true, 303);
exit;
