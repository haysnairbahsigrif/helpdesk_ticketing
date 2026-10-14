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
    exit('Permintaan tidak valid. Kembali ke halaman Tambah Tiket dan muat ulang halaman.');
}

// Hapus sisa input sementara dari pengiriman sebelumnya.
unset($_SESSION['input_tiket']);

// Periksa keluhan.
$keluhanMasukan = $_POST['keluhan'] ?? '';

if (!is_string($keluhanMasukan)) {
    header('Location: /user/?pesan=data-tidak-valid', true, 303);
    exit;
}

$urgensiMasukan = $_POST['urgensi'] ?? '';

$urgensiValid = is_string($urgensiMasukan)
    && in_array($urgensiMasukan, ['Rendah', 'Sedang', 'Tinggi'], true);

$_SESSION['input_tiket'] = [
    'keluhan' => $keluhanMasukan,
    'urgensi' => $urgensiValid ? $urgensiMasukan : '',
];

if (!$urgensiValid) {
    header(
        'Location: /user/tambah-tiket.php?pesan=urgensi-tidak-valid',
        true,
        303
    );
    exit;
}

$urgensi = $urgensiMasukan;

$keluhan = trim($keluhanMasukan);

if ($keluhan === '') {
    header(
        'Location: /user/tambah-tiket.php?pesan=wajib-diisi',
        true,
        303
    );
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
    require_once __DIR__ . '/../../app/functions/tiket.php';
    require_once __DIR__ . '/../../app/functions/Departemen.php';

    // Ambil Departemen dari database berdasarkan akun yang login.
    $DepartemenPelapor = ambilDepartemenUser($pdo, $userId);

    if ($DepartemenPelapor === null) {
        header(
            'Location: /user/tambah-tiket.php?pesan=Departemen-belum-diatur',
            true,
            303
        );
        exit;
    }

    tambahTiket(
        $pdo,
        $userId,
        $nama,
        $keluhan,
        $DepartemenPelapor,
        $urgensi
    );
} catch (PDOException $error) {
    error_log($error->getMessage());

    header(
        'Location: /user/tambah-tiket.php?pesan=gagal',
        true,
        303
    );
    exit;
}

// Tiket berhasil disimpan, kosongkan input sementara.
unset($_SESSION['input_tiket']);

header(
    'Location: /user/tiket.php?view=semua&pesan=berhasil',
    true,
    303
);
exit;
