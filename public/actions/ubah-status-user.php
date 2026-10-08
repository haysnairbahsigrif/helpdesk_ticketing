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
    || $tokenSession === ''
    || !hash_equals($tokenSession, $token)
) {
    http_response_code(403);
    exit('Permintaan tidak valid. Muat ulang daftar user.');
}

// Periksa ID dan status tujuan.
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

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/user.php';

    $berhasil = ubahStatusUser(
        $pdo,
        $userId,
        (int) $statusMasukan
    );
} catch (PDOException $error) {
    error_log($error->getMessage());

    header('Location: /admin/users.php?pesan=akun-status-gagal', true, 303);
    exit;
}

$pesan = $berhasil
    ? 'akun-status-berhasil'
    : 'akun-status-tidak-berubah';

header('Location: /admin/users.php?pesan=' . $pesan, true, 303);
exit;