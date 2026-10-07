<?php

// Menyimpan tiket baru.
function tambahTiket(
    PDO $pdo,
    int $userId,
    string $nama,
    string $keluhan
): void {
    $sql = 'INSERT INTO tickets
                (user_id, nama_pengguna, keluhan)
            VALUES
                (:user_id, :nama, :keluhan)';

    $statement = $pdo->prepare($sql);

    $statement->execute([
        'user_id' => $userId,
        'nama' => $nama,
        'keluhan' => $keluhan,
    ]);
}

// Membaca tiket milik satu pengguna.
function ambilTiketUser(PDO $pdo, int $userId): array
{
    $statement = $pdo->prepare(
        'SELECT id, keluhan, status, created_at
        FROM tickets
        WHERE user_id = :user_id
        ORDER BY id DESC'
    );

    $statement->execute([
        'user_id' => $userId,
    ]);

    return $statement->fetchAll();
}

function ambilSemuaTiket(PDO $pdo): array
{
    $statement = $pdo->query(
        'SELECT
            tickets.id,
            tickets.nama_pengguna,
            tickets.keluhan,
            tickets.status,
            tickets.created_at,
            users.email AS email_pemilik
        FROM tickets
        LEFT JOIN users ON tickets.user_id = users.id
        ORDER BY tickets.id DESC'
    );

    return $statement->fetchAll();
}

function ubahStatusTiket(
    PDO $pdo,
    int $tiketId,
    string $status
): bool {
    $statement = $pdo->prepare(
        'UPDATE tickets
        SET status = :status
        WHERE id = :id'
    );

    $statement->execute([
        'status' => $status,
        'id' => $tiketId,
    ]);

    if ($statement->rowCount() > 0) {
        return true;
    }

    // Status yang dipilih mungkin sama dengan status sebelumnya.
    // Periksa apakah tiketnya memang ada.
    $cek = $pdo->prepare('SELECT id FROM tickets WHERE id = :id');
    $cek->execute(['id' => $tiketId]);

    return $cek->fetchColumn() !== false;
}