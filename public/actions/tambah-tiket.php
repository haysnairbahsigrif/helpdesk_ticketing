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
    || !is_string($tokenSession)
    || $tokenSession === ''
    || !hash_equals($tokenSession, $token)
) {
    http_response_code(403);
    exit('Permintaan tidak valid. Kembali ke dashboard dan muat ulang halaman.');
}

// Hapus sisa input sementara dari pengiriman sebelumnya.
unset($_SESSION['input_tiket']);

// Periksa keluhan.
$keluhanMasukan = $_POST['keluhan'] ?? '';

if (!is_string($keluhanMasukan)) {
    header('Location: /user/?pesan=data-tidak-valid', true, 303);
    exit;
}

// Simpan teks asli untuk ditampilkan kembali jika terjadi kegagalan.
$_SESSION['input_tiket'] = [
    'keluhan' => $keluhanMasukan,
];

$keluhan = trim($keluhanMasukan);

if ($keluhan === '') {
    header('Location: /user/?pesan=wajib-diisi', true, 303);
    exit;
}

$panjangKeluhan = preg_match_all('/./us', $keluhan);

if ($panjangKeluhan === false || $panjangKeluhan > 5000) {
    header('Location: /user/?pesan=data-tidak-valid', true, 303);
    exit;
}

// Identitas pemilik berasal dari sesi login.
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

// Tiket berhasil disimpan, kosongkan input sementara.
unset($_SESSION['input_tiket']);

header('Location: /user/?pesan=berhasil', true, 303);
exit;