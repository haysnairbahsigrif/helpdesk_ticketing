<?php

require __DIR__ . '/../../app/config/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /login.php');
    exit;
}

unset(
    $_SESSION['pesan_login'],
    $_SESSION['jenis_pesan_login'],
    $_SESSION['posisi_pesan_login']
);

// Periksa token formulir.
$token = $_POST['csrf_token'] ?? '';
$tokenSession = $_SESSION['csrf_token'] ?? '';

if (
    !is_string($token)
    || $tokenSession === ''
    || !hash_equals($tokenSession, $token)
) {
    $_SESSION['pesan_login'] = 'Formulir kedaluwarsa. Silakan coba lagi.';
    $_SESSION['jenis_pesan_login'] = 'error';
    header('Location: /login.php', true, 303);
    exit;
}

// Ambil username dan password.
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!is_string($username) || !is_string($password)) {
    $_SESSION['pesan_login'] = 'Data login tidak valid.';
    $_SESSION['jenis_pesan_login'] = 'error';
    header('Location: /login.php', true, 303);
    exit;
}

$username = trim($username);

if ($username === '' || $password === '') {
    $_SESSION['pesan_login'] = 'Username dan password wajib diisi.';
    $_SESSION['jenis_pesan_login'] = 'error';
    header('Location: /login.php', true, 303);
    exit;
}

// Periksa akun.
try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/auth.php';

    $akun = periksaLogin($pdo, $username, $password);
} catch (PDOException $error) {
    error_log($error->getMessage());

    $_SESSION['pesan_login'] = 'Login belum dapat diproses. Coba lagi.';
    $_SESSION['jenis_pesan_login'] = 'error';
    header('Location: /login.php', true, 303);
    exit;
}

if ($akun === null) {
    $_SESSION['pesan_login'] = 'Login ditolak. Periksa username dan password, atau hubungi admin.';
    $_SESSION['jenis_pesan_login'] = 'error';
    $_SESSION['posisi_pesan_login'] = 'password';

    header('Location: /login.php', true, 303);
    exit;
}

// Simpan identitas dalam sesi baru.
session_regenerate_id(true);

$_SESSION['akun'] = $akun;
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

header('Location: /login.php', true, 303);
exit;
