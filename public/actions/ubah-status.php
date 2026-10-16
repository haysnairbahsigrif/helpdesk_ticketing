<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/tiket.php');
    exit;
}

// Periksa token formulir.
$token = $_POST['csrf_token'] ?? '';
$tokenSession = $_SESSION['csrf_token'] ?? '';

if (
    !is_string($token)
    || !is_string($tokenSession)
    || $tokenSession === ''
    || !hash_equals($tokenSession, $token)
) {
    http_response_code(403);
    exit('Permintaan tidak valid. Muat ulang halaman Daftar Tiket.');
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

// Periksa ID tiket dan status tujuan.
$idMasukan = $_POST['tiket_id'] ?? '';
$statusTujuan = $_POST['status'] ?? '';

$tiketId = is_string($idMasukan)
    ? filter_var(
        $idMasukan,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    )
    : false;

$statusDiizinkan = ['Diproses', 'Selesai'];

if (
    $tiketId === false
    || !is_string($statusTujuan)
    || !in_array($statusTujuan, $statusDiizinkan, true)
) {
    $pesan = 'status-tidak-valid';
} else {
    $solusi = null;
    $solusiValid = true;

    if ($statusTujuan === 'Selesai') {
        $solusiMasukan = $_POST['solusi'] ?? '';

        if (!is_string($solusiMasukan)) {
            $solusiValid = false;
        } else {
            $solusi = trim($solusiMasukan);
            $panjangSolusi = preg_match_all('/./us', $solusi);

            $solusiValid = $solusi !== ''
                && $panjangSolusi !== false
                && $panjangSolusi <= 5000;

            // Simpan input valid agar tidak hilang jika database gagal.
            if ($solusiValid) {
                $_SESSION['input_solusi_tiket'][$tiketId] = $solusi;
            }
        }
    }

    if (!$solusiValid) {
        $pesan = 'solusi-tidak-valid';
    } else {
        // Identitas admin berasal dari sesi, bukan formulir.
        $adminId = (int) $_SESSION['akun']['id'];

        try {
            require __DIR__ . '/../../app/config/database.php';
            require __DIR__ . '/../../app/functions/tiket.php';

            $berhasil = ubahStatusTiket(
                $pdo,
                $tiketId,
                $statusTujuan,
                $adminId,
                $solusi
            );

            $pesan = $berhasil
                ? 'status-berhasil'
                : 'status-transisi-ditolak';

            if ($berhasil) {
                unset($_SESSION['input_solusi_tiket'][$tiketId]);
            }
        } catch (PDOException $error) {
            error_log($error->getMessage());
            $pesan = 'status-gagal';
        }
    }
}

// Kembali ke halaman yang sama beserta pencarian dan filternya.
$parameterKembali['pesan'] = $pesan;

$urlKembali = '/admin/tiket.php?'
    . http_build_query($parameterKembali);

header('Location: ' . $urlKembali, true, 303);
exit;
