<?php

if (PHP_SAPI !== 'cli') {
    exit('Jalankan melalui terminal.');
}

require __DIR__ . '/../app/config/database.php';
require __DIR__ . '/../app/functions/user.php';

$total = hitungUser($pdo);

echo 'Total akun user: ' . $total . PHP_EOL;

// Gunakan satu akun per halaman untuk pengujian.
for ($halaman = 1; $halaman <= 2; $halaman++) {
    $daftar = ambilDaftarUser(
        $pdo,
        '',
        '',
        1,
        $halaman - 1
    );

    echo 'Halaman ' . $halaman . ': ';

    echo empty($daftar)
        ? 'Kosong'
        : $daftar[0]['username'];

    echo PHP_EOL;
}