<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('user', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /user/ganti-password.php');
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
    exit('Permintaan tidak valid. Muat ulang halaman ganti password.');
}

// Ambil masukan tanpa trim: spasi bisa menjadi bagian password.
$passwordLama = $_POST['password_lama'] ?? '';
$passwordBaru = $_POST['password_baru'] ?? '';
$konfirmasi = $_POST['konfirmasi_password'] ?? '';

if (
    !is_string($passwordLama)
    || !is_string($passwordBaru)
    || !is_string($konfirmasi)
) {
    header('Location: /user/ganti-password.php?pesan=password-tidak-valid', true, 303);
    exit;
}

$panjangPassword = preg_match_all('/./us', $passwordBaru);

if (
    $passwordLama === ''
    || $panjangPassword === false
    || $panjangPassword < 12
    || strlen($passwordBaru) > 72
    || str_contains($passwordBaru, "\0")
) {
    header('Location: /user/ganti-password.php?pesan=password-tidak-valid', true, 303);
    exit;
}

if ($passwordBaru !== $konfirmasi) {
    header('Location: /user/ganti-password.php?pesan=password-konfirmasi-salah', true, 303);
    exit;
}

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/password.php';

    $hasil = gantiPasswordUser(
        $pdo,
        (int) $_SESSION['akun']['id'],
        $passwordLama,
        $passwordBaru
    );
} catch (PDOException $error) {
    error_log($error->getMessage());
    $hasil = 'password-gagal';
}

if ($hasil !== 'password-berhasil') {
    header('Location: /user/ganti-password.php?pesan=' . $hasil, true, 303);
    exit;
}

// Akhiri status login pada browser ini.
$_SESSION = [];
session_regenerate_id(true);

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$_SESSION['pesan_login'] = 'Password berhasil diganti. Silakan masuk dengan password baru.';

header('Location: /login.php', true, 303);
exit;