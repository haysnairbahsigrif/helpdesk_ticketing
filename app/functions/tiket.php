<?php

function tambahTiket(PDO $pdo, string $nama, string $keluhan): void
{
    $sql = 'INSERT INTO tickets (nama_pengguna, keluhan)
            VALUES (:nama, :keluhan)';

    $statement = $pdo->prepare($sql);

    $statement->execute([
        'nama' => $nama,
        'keluhan' => $keluhan,
    ]);
}