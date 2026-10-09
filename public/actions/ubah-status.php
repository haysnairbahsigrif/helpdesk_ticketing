<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/tiket.php');
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
    exit('Permintaan tidak valid. Muat ulang halaman Daftar Tiket.');
}

// Periksa pencarian dan filter untuk halaman tujuan.
$pencarian = $_POST['q'] ?? '';
$filterStatus = $_POST['filter_status'] ?? '';

if (!is_string($pencarian) || !is_string($filterStatus)) {
    http_response_code(400);
    exit('Parameter pencarian atau filter tidak valid.');
}

$pencarian = trim($pencarian);
$panjangPencarian = preg_match_all('/./us', $pencarian);

if ($panjangPencarian === false || $panjangPencarian > 100) {
    http_response_code(400);
    exit('Pencarian harus berupa teks valid, maksimal 100 karakter.');
}

if (
    !in_array(
        $filterStatus,
        ['', 'Baru', 'Diproses', 'Selesai'],
        true
    )
) {
    http_response_code(400);
    exit('Filter status tidak valid.');
}

// Periksa halaman asal.
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

// Siapkan parameter untuk kembali ke daftar tiket.
$parameterKembali = ['page' => $halaman];

if ($pencarian !== '') {
    $parameterKembali['q'] = $pencarian;
}

if ($filterStatus !== '') {
    $parameterKembali['status'] = $filterStatus;
}

// Periksa ID tiket dan status tujuan.
$idMasukan = $_POST['tiket_id'] ?? '';
$statusTujuan = $_POST['status'] ?? '';

$tiketId = is_string($idMasukan)
    ? filter_var(
        $idMasukan,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    )
    : false;

$statusDiizinkan = ['Baru', 'Diproses', 'Selesai'];

if (
    $tiketId === false
    || !is_string($statusTujuan)
    || !in_array($statusTujuan, $statusDiizinkan, true)
) {
    $pesan = 'status-tidak-valid';
} else {
    try {
        require __DIR__ . '/../../app/config/database.php';
        require __DIR__ . '/../../app/functions/tiket.php';

        $berhasil = ubahStatusTiket(
            $pdo,
            $tiketId,
            $statusTujuan
        );

        $pesan = $berhasil
            ? 'status-berhasil'
            : 'tiket-tidak-ditemukan';
    } catch (PDOException $error) {
        error_log($error->getMessage());
        $pesan = 'status-gagal';
    }
}

// Kembali ke halaman yang sama beserta pencarian dan filternya.
$parameterKembali['pesan'] = $pesan;

$urlKembali = '/admin/tiket.php?'
    . http_build_query($parameterKembali);

header('Location: ' . $urlKembali, true, 303);
exit;
