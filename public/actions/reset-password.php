<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/users.php');
    exit;
}

// Periksa token keamanan.
$token = $_POST['csrf_token'] ?? '';
$tokenSession = $_SESSION['csrf_token'] ?? '';

if (
    !is_string($token)
    || $tokenSession === ''
    || !hash_equals($tokenSession, $token)
) {
    http_response_code(403);
    exit('Permintaan tidak valid. Muat ulang halaman reset password.');
}

// Periksa akun tujuan.
$idMasukan = $_POST['user_id'] ?? '';

$userId = is_string($idMasukan)
    ? filter_var(
        $idMasukan,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    )
    : false;

if ($userId === false) {
    http_response_code(400);
    exit('ID user tidak valid.');
}

$alamatKembali = '/admin/reset-password.php?id=' . $userId;

// Periksa password sementara.
$password = $_POST['password_sementara'] ?? '';
$konfirmasi = $_POST['konfirmasi_password'] ?? '';

if (!is_string($password) || !is_string($konfirmasi)) {
    header(
        'Location: ' . $alamatKembali . '&pesan=reset-tidak-valid',
        true,
        303
    );
    exit;
}

$panjang = preg_match_all('/./us', $password);

if (
    $panjang === false
    || $panjang < 12
    || strlen($password) > 72
    || str_contains($password, "\0")
) {
    header(
        'Location: ' . $alamatKembali . '&pesan=reset-tidak-valid',
        true,
        303
    );
    exit;
}

if ($password !== $konfirmasi) {
    header(
        'Location: ' . $alamatKembali . '&pesan=reset-konfirmasi-salah',
        true,
        303
    );
    exit;
}

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/password.php';

    $berhasil = resetPasswordUser($pdo, $userId, $password);
} catch (PDOException $error) {
    error_log($error->getMessage());

    header(
        'Location: ' . $alamatKembali . '&pesan=reset-gagal',
        true,
        303
    );
    exit;
}

$pesan = $berhasil
    ? 'reset-berhasil'
    : 'reset-user-tidak-ditemukan';

header('Location: /admin/users.php?pesan=' . $pesan, true, 303);
exit;