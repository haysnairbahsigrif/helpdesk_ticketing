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

require_once __DIR__ . '/../../app/functions/filter-tiket.php';

// Periksa pencarian.
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

// Periksa filter daftar, terpisah dari status tujuan tiket.
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

// Periksa halaman asal.
$halamanMasukan = $_POST['page'] ?? '1';

$halaman = is_string($halamanMasukan)
    ? filter_var(
        $halamanMasukan,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    )
    : false;

if ($halaman === false) {
    http_response_code(400);
    exit('Nomor halaman tidak valid.');
}

$parameterKembali = [
    'page' => $halaman,
];

if ($pencarian !== '') {
    $parameterKembali['q'] = $pencarian;
}

if ($filterStatus !== []) {
    // Satu status tetap menghasilkan URL seperti ?status=Baru.
    $parameterKembali['status'] = count($filterStatus) === 1
        ? $filterStatus[0]
        : $filterStatus;
}

if ($filterPrioritas !== []) {
    $parameterKembali['prioritas'] = $filterPrioritas;
}

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

if ($tiketId !== false) {
    $parameterKembali['buka_tiket'] = $tiketId;
}

header(
    'Location: /admin/tiket.php?' . http_build_query($parameterKembali),
    true,
    303
);
exit;