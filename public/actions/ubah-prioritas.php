<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/tiket.php');
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
    exit('Permintaan tidak valid. Muat ulang daftar tiket.');
}

// Periksa parameter untuk kembali ke daftar yang sama.
$pencarian = $_POST['q'] ?? '';
$filterStatus = $_POST['filter_status'] ?? '';
$tampilan = $_POST['view'] ?? 'aktif';
$halamanMasukan = $_POST['page'] ?? '1';

if (
    !is_string($pencarian)
    || !is_string($filterStatus)
    || !is_string($tampilan)
) {
    http_response_code(400);
    exit('Parameter daftar tiket tidak valid.');
}

$pencarian = trim($pencarian);
$panjangPencarian = preg_match_all('/./us', $pencarian);

$halaman = is_string($halamanMasukan)
    ? filter_var(
        $halamanMasukan,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    )
    : false;

if (
    $panjangPencarian === false
    || $panjangPencarian > 100
    || !in_array($filterStatus, ['', 'Baru', 'Diproses', 'Selesai'], true)
    || !in_array($tampilan, ['semua', 'aktif', 'riwayat'], true)
    || $halaman === false
) {
    http_response_code(400);
    exit('Pencarian, filter, atau halaman tidak valid.');
}

$parameterKembali = [
    'view' => $tampilan,
    'q' => $pencarian,
    'status' => $filterStatus,
    'page' => $halaman,
];

// Periksa masukan perubahan prioritas.
$idMasukan = $_POST['tiket_id'] ?? '';
$prioritasBaru = $_POST['prioritas'] ?? '';
$alasan = $_POST['alasan'] ?? '';

$tiketId = is_string($idMasukan)
    ? filter_var(
        $idMasukan,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    )
    : false;

if (
    $tiketId === false
    || !is_string($prioritasBaru)
    || !is_string($alasan)
) {
    $pesan = 'prioritas-tidak-valid';
} else {
    try {
        require __DIR__ . '/../../app/config/database.php';
        require_once __DIR__ . '/../../app/functions/prioritas.php';

        // Identitas admin berasal dari sesi, bukan dari formulir.
        $pesan = ubahPrioritasTiket(
            $pdo,
            $tiketId,
            (int) $_SESSION['akun']['id'],
            $prioritasBaru,
            $alasan
        );
    } catch (PDOException $error) {
        error_log($error->getMessage());
        $pesan = 'prioritas-gagal';
    }
}

$parameterKembali['pesan'] = $pesan;

header(
    'Location: /admin/tiket.php?' . http_build_query($parameterKembali),
    true,
    303
);
exit;