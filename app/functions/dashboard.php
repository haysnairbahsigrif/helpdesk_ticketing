<?php

function ambilRingkasanDashboard(PDO $pdo): array
{
    $sql = "
        SELECT
            (
                SELECT COUNT(*)
                FROM users
                WHERE role = 'user'
            ) AS total_user,
            COUNT(*) AS total_tiket,
            COUNT(CASE WHEN status = 'Baru' THEN 1 END) AS tiket_baru,
            COUNT(CASE WHEN status = 'Diproses' THEN 1 END) AS tiket_diproses,
            COUNT(CASE WHEN status = 'Selesai' THEN 1 END) AS tiket_selesai
        FROM tickets
    ";

    $statement = $pdo->query($sql);
    $hasil = $statement->fetch(PDO::FETCH_ASSOC);

    return [
        'total_user' => (int) $hasil['total_user'],
        'total_tiket' => (int) $hasil['total_tiket'],
        'tiket_baru' => (int) $hasil['tiket_baru'],
        'tiket_diproses' => (int) $hasil['tiket_diproses'],
        'tiket_selesai' => (int) $hasil['tiket_selesai'],
    ];
}