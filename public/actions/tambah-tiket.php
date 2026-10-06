<?php

// 1. Pastikan permintaan berasal dari pengiriman formulir.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /');
    exit;
}

// 2. Ambil data formulir.
$nama = $_POST['nama_pengguna'] ?? '';
$keluhan = $_POST['keluhan'] ?? '';

// 3. Pastikan masukan berupa teks.
if (!is_string($nama) || !is_string($keluhan)) {
    header('Location: /?pesan=data-tidak-valid', true, 303);
    exit;
}

// 4. Hapus spasi di awal dan akhir.
$nama = trim($nama);
$keluhan = trim($keluhan);

// 5. Periksa kolom wajib.
if ($nama === '' || $keluhan === '') {
    header('Location: /?pesan=wajib-diisi', true, 303);
    exit;
}

// 6. Periksa panjang nama.
$panjangNama = preg_match_all('/./us', $nama);

if ($panjangNama === false || $panjangNama > 100) {
    header('Location: /?pesan=nama-terlalu-panjang', true, 303);
    exit;
}

// 7. Hubungkan database dan simpan tiket.
try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/tiket.php';

    tambahTiket($pdo, $nama, $keluhan);
} catch (PDOException $error) {
    error_log($error->getMessage());

    header('Location: /?pesan=gagal', true, 303);
    exit;
}

// 8. Kembali ke halaman utama setelah berhasil.
header('Location: /?pesan=berhasil', true, 303);
exit;
