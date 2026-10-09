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

function buatFilterTiket(
    string $pencarian = '',
    string $status = ''
): array {
    $kondisi = [];
    $parameter = [];

    if ($pencarian !== '') {
        $kondisi[] = "
            (
                tickets.nama_pengguna LIKE :nama ESCAPE '!'
                OR tickets.keluhan LIKE :keluhan ESCAPE '!'
            )
        ";

        $kataKunci = '%' . str_replace(
            ['!', '%', '_'],
            ['!!', '!%', '!_'],
            $pencarian
        ) . '%';

        $parameter['nama'] = $kataKunci;
        $parameter['keluhan'] = $kataKunci;
    }

    if ($status !== '') {
        $kondisi[] = 'tickets.status = :status';
        $parameter['status'] = $status;
    }

    $where = $kondisi !== []
        ? ' WHERE ' . implode(' AND ', $kondisi)
        : '';

    return [
        'where' => $where,
        'parameter' => $parameter,
    ];
}

function hitungTiketAdmin(
    PDO $pdo,
    string $pencarian = '',
    string $status = ''
): int {
    $filter = buatFilterTiket($pencarian, $status);

    $statement = $pdo->prepare(
        'SELECT COUNT(*) FROM tickets' . $filter['where']
    );

    $statement->execute($filter['parameter']);

    return (int) $statement->fetchColumn();
}

function ambilSemuaTiket(
    PDO $pdo,
    string $pencarian = '',
    string $status = '',
    ?int $batas = null,
    int $offset = 0
): array {
    if ($offset < 0 || ($batas !== null && $batas < 1)) {
        throw new InvalidArgumentException(
            'Batas harus positif dan offset tidak boleh negatif.'
        );
    }

    $filter = buatFilterTiket($pencarian, $status);

    $sql = '
        SELECT
            tickets.id,
            tickets.nama_pengguna,
            tickets.keluhan,
            tickets.status,
            tickets.created_at,
            users.email AS email_pemilik
        FROM tickets
        LEFT JOIN users ON tickets.user_id = users.id
    ';

    $sql .= $filter['where'];
    $sql .= ' ORDER BY tickets.id DESC';

    if ($batas !== null) {
        $sql .= ' LIMIT :batas OFFSET :offset';
    }

    $statement = $pdo->prepare($sql);

    foreach ($filter['parameter'] as $nama => $nilai) {
        $statement->bindValue(
            ':' . $nama,
            $nilai,
            PDO::PARAM_STR
        );
    }

    if ($batas !== null) {
        $statement->bindValue(':batas', $batas, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
    }

    $statement->execute();

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
