<?php

if (PHP_SAPI !== 'cli') {
    exit('Jalankan melalui terminal.');
}

require __DIR__ . '/../app/config/database.php';
require __DIR__ . '/../app/functions/auth.php';

$username = readline('Username: ');
$password = readline('Password latihan (akan terlihat): ');

$akun = periksaLogin($pdo, $username, $password);

if ($akun === null) {
    echo 'Login ditolak: username atau password salah.' . PHP_EOL;
    exit;
}

echo 'Login berhasil.' . PHP_EOL;
echo 'Nama: ' . $akun['nama'] . PHP_EOL;
echo 'Peran: ' . $akun['role'] . PHP_EOL;