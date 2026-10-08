<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/users.php');
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
    exit('Permintaan tidak valid. Muat ulang halaman edit user.');
}

// Periksa ID akun.
$idMasukan = $_POST['user_id'] ?? '';

$userId = is_string($idMasukan)
    ? filter_var(
        $idMasukan,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    )
    : false;

if ($userId === false) {
    http_response_code(400);
    exit('ID user tidak valid.');
}

$alamatKembali = '/admin/edit-user.php?id=' . $userId;

// Ambil dan periksa masukan.
$nama = $_POST['nama'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';

if (
    !is_string($nama)
    || !is_string($username)
    || !is_string($email)
) {
    header(
        'Location: ' . $alamatKembali . '&pesan=edit-user-tidak-valid',
        true,
        303
    );
    exit;
}

$nama = trim($nama);
$username = trim($username);
$email = trim($email);

$panjangNama = preg_match_all('/./us', $nama);
$panjangEmail = preg_match_all('/./us', $email);

if (
    $nama === ''
    || $panjangNama === false
    || $panjangNama > 100
    || preg_match('/\A[a-z0-9._-]{3,50}\z/', $username) !== 1
    || (
        $email !== ''
        && (
            $panjangEmail === false
            || $panjangEmail > 150
            || !filter_var($email, FILTER_VALIDATE_EMAIL)
        )
    )
) {
    header(
        'Location: ' . $alamatKembali . '&pesan=edit-user-tidak-valid',
        true,
        303
    );
    exit;
}

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/user.php';

    $berhasil = ubahDataUser(
        $pdo,
        $userId,
        $nama,
        $username,
        $email === '' ? null : $email
    );
} catch (PDOException $error) {
    error_log($error->getMessage());

    $duplikat = (int) ($error->errorInfo[1] ?? 0) === 1062;
    $pesan = $duplikat ? 'user-duplikat' : 'edit-user-gagal';

    header(
        'Location: ' . $alamatKembali . '&pesan=' . $pesan,
        true,
        303
    );
    exit;
}

$pesan = $berhasil
    ? 'edit-user-berhasil'
    : 'edit-user-tidak-ditemukan';

header('Location: /admin/users.php?pesan=' . $pesan, true, 303);
exit;