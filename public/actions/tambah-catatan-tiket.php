<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/tiket.php');
    exit;
}

$token = $_POST['csrf_token'] ?? '';
$tokenSession = $_SESSION['csrf_token'] ?? '';

if (
    !is_string($token)
    || !is_string($tokenSession)
    || $tokenSession === ''
    || !hash_equals($tokenSession, $token)
) {
    http_response_code(403);
    exit('Permintaan tidak valid. Muat ulang Daftar Tiket.');
}

require_once __DIR__ . '/../../app/functions/filter-tiket.php';

$pencarian = $_POST['q'] ?? '';

if (!is_string($pencarian)) {
    http_response_code(400);
    exit('Pencarian tidak valid.');
}

$pencarian = trim($pencarian);
$panjangPencarian = preg_match_all('/./us', $pencarian);

if ($panjangPencarian === false || $panjangPencarian > 100) {
    http_response_code(400);
    exit('Pencarian maksimal 100 karakter dan harus berupa teks valid.');
}

try {
    $filterStatus = normalisasiPilihanFilter(
        $_POST['filter_status'] ?? [],
        ['Baru', 'Diproses', 'Selesai']
    );

    $filterPrioritas = normalisasiPilihanFilter(
        $_POST['filter_prioritas'] ?? [],
        ['Rendah', 'Sedang', 'Tinggi', 'belum-ditentukan']
    );
} catch (InvalidArgumentException $error) {
    http_response_code(400);
    exit('Pilihan filter tidak valid.');
}

$halamanMasukan = $_POST['page'] ?? '1';
$idMasukan = $_POST['tiket_id'] ?? '';

$halaman = is_string($halamanMasukan)
    ? filter_var(
        $halamanMasukan,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    )
    : false;

$tiketId = is_string($idMasukan)
    ? filter_var(
        $idMasukan,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    )
    : false;

if ($halaman === false || $tiketId === false) {
    http_response_code(400);
    exit('Nomor halaman atau ID tiket tidak valid.');
}

$parameterKembali = [
    'page' => $halaman,
    'q' => $pencarian,
    'status' => $filterStatus,
    'prioritas' => $filterPrioritas,
];

$catatanMasukan = $_POST['catatan'] ?? '';
$catatan = is_string($catatanMasukan) ? trim($catatanMasukan) : '';
$panjangCatatan = preg_match_all('/./us', $catatan);

if (
    $catatan === ''
    || $panjangCatatan === false
    || $panjangCatatan > 5000
) {
    $pesan = 'catatan-tidak-valid';
} else {
    // Pertahankan input apabila penyimpanan gagal.
    $_SESSION['input_catatan_tiket'][$tiketId] = $catatan;

    try {
        require __DIR__ . '/../../app/config/database.php';
        require __DIR__ . '/../../app/functions/catatan-tiket.php';

        $berhasil = tambahCatatanTiket(
            $pdo,
            $tiketId,
            (int) $_SESSION['akun']['id'],
            $catatan
        );

        $pesan = $berhasil
            ? 'catatan-berhasil'
            : 'catatan-ditolak';

        if ($berhasil) {
            unset($_SESSION['input_catatan_tiket'][$tiketId]);
        }
    } catch (PDOException $error) {
        error_log($error->getMessage());
        $pesan = 'catatan-gagal';
    }
}

$parameterKembali['pesan'] = $pesan;

header(
    'Location: /admin/tiket.php?' . http_build_query($parameterKembali),
    true,
    303
);
exit;