<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/tambah-user.php');
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
    exit('Permintaan tidak valid. Muat ulang halaman tambah user.');
}

// Ambil masukan.
$nama = $_POST['nama'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password_sementara'] ?? '';

if (
    !is_string($nama)
    || !is_string($username)
    || !is_string($email)
    || !is_string($password)
) {
    header('Location: /admin/tambah-user.php?pesan=user-tidak-valid', true, 303);
    exit;
}

$nama = trim($nama);
$username = trim($username);
$email = trim($email);

$panjangNama = preg_match_all('/./us', $nama);
$panjangEmail = preg_match_all('/./us', $email);
$panjangPassword = preg_match_all('/./us', $password);

// Validasi dilakukan lagi di server.
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
    || $panjangPassword === false
    || $panjangPassword < 12
    || strlen($password) > 72
    || str_contains($password, "\0")
) {
    header('Location: /admin/tambah-user.php?pesan=user-tidak-valid', true, 303);
    exit;
}

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/user.php';

    tambahUser(
        $pdo,
        $nama,
        $username,
        $email === '' ? null : $email,
        $password
    );
} catch (PDOException $error) {
    error_log($error->getMessage());

    // MySQL 1062 berarti nilai unik sudah digunakan.
    $duplikat = (int) ($error->errorInfo[1] ?? 0) === 1062;

    $pesan = $duplikat ? 'user-duplikat' : 'user-gagal';

    header('Location: /admin/tambah-user.php?pesan=' . $pesan, true, 303);
    exit;
}

header('Location: /admin/users.php?pesan=user-berhasil', true, 303);
exit;