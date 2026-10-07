<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/');
    exit;
}

// Periksa token formulir.
$token = $_POST['csrf_token'] ?? '';
$tokenSession = $_SESSION['csrf_token'] ?? '';

if (
    !is_string($token)
    || $tokenSession === ''
    || !hash_equals($tokenSession, $token)
) {
    http_response_code(403);
    exit('Permintaan tidak valid. Kembali ke dashboard dan muat ulang halaman.');
}

// Periksa ID tiket dan status.
$idMasukan = $_POST['tiket_id'] ?? '';
$status = $_POST['status'] ?? '';

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
    || !is_string($status)
    || !in_array($status, $statusDiizinkan, true)
) {
    header('Location: /admin/?pesan=status-tidak-valid', true, 303);
    exit;
}

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/tiket.php';

    $berhasil = ubahStatusTiket($pdo, $tiketId, $status);
} catch (PDOException $error) {
    error_log($error->getMessage());

    header('Location: /admin/?pesan=status-gagal', true, 303);
    exit;
}

$pesan = $berhasil ? 'status-berhasil' : 'tiket-tidak-ditemukan';

header('Location: /admin/?pesan=' . $pesan, true, 303);
exit;