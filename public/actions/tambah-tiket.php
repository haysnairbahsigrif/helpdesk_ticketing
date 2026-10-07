<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('user');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /user/');
    exit;
}

// Periksa token keamanan formulir.
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

// Periksa keluhan.
$keluhan = $_POST['keluhan'] ?? '';

if (!is_string($keluhan)) {
    header('Location: /user/?pesan=data-tidak-valid', true, 303);
    exit;
}

$keluhan = trim($keluhan);

if ($keluhan === '') {
    header('Location: /user/?pesan=wajib-diisi', true, 303);
    exit;
}

$panjangKeluhan = preg_match_all('/./us', $keluhan);

if ($panjangKeluhan === false || $panjangKeluhan > 5000) {
    header('Location: /user/?pesan=data-tidak-valid', true, 303);
    exit;
}

// Identitas pemilik berasal dari session login.
$userId = (int) $_SESSION['akun']['id'];
$nama = $_SESSION['akun']['nama'];

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/tiket.php';

    tambahTiket($pdo, $userId, $nama, $keluhan);
} catch (PDOException $error) {
    error_log($error->getMessage());

    header('Location: /user/?pesan=gagal', true, 303);
    exit;
}

header('Location: /user/?pesan=berhasil', true, 303);
exit;