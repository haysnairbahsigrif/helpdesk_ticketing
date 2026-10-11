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
function buatFilterTiketUser(
    int $userId,
    string $pencarian = '',
    string $status = '',
    string $tampilan = 'semua'
): array {
    if (!in_array($tampilan, ['semua', 'aktif', 'riwayat'], true)) {
        throw new InvalidArgumentException('Tampilan tiket tidak valid.');
    }

    // Semua tampilan wajib dibatasi pada pemilik tiket.
    $where = ' WHERE user_id = :user_id';

    $parameter = [
        'user_id' => $userId,
    ];

    if ($tampilan === 'aktif') {
        $where .= " AND status IN ('Baru', 'Diproses')";
    } elseif ($tampilan === 'riwayat') {
        $where .= " AND status = 'Selesai'";
    }

    if ($pencarian !== '') {
        $where .= " AND keluhan LIKE :pencarian ESCAPE '!'";

        $parameter['pencarian'] = '%' . str_replace(
            ['!', '%', '_'],
            ['!!', '!%', '!_'],
            $pencarian
        ) . '%';
    }

    if ($status !== '') {
        $where .= ' AND status = :status';
        $parameter['status'] = $status;
    }

    return [
        'where' => $where,
        'parameter' => $parameter,
    ];
}

function hitungTiketUser(
    PDO $pdo,
    int $userId,
    string $pencarian = '',
    string $status = '',
    string $tampilan = 'semua'
): int {
    $filter = buatFilterTiketUser(
        $userId,
        $pencarian,
        $status,
        $tampilan
    );

    $statement = $pdo->prepare(
        'SELECT COUNT(*) FROM tickets' . $filter['where']
    );

    $statement->execute($filter['parameter']);

    return (int) $statement->fetchColumn();
}

function ambilTiketUser(
    PDO $pdo,
    int $userId,
    string $pencarian = '',
    string $status = '',
    ?int $batas = null,
    int $offset = 0,
    string $tampilan = 'semua'
): array {
    if ($offset < 0 || ($batas !== null && $batas < 1)) {
        throw new InvalidArgumentException(
            'Batas harus positif dan offset tidak boleh negatif.'
        );
    }

    $filter = buatFilterTiketUser(
        $userId,
        $pencarian,
        $status,
        $tampilan
    );

    $sql = '
        SELECT id, keluhan, status, created_at
        FROM tickets
    ';

    $sql .= $filter['where'];
    $sql .= ' ORDER BY id DESC';

    if ($batas !== null) {
        $sql .= ' LIMIT :batas OFFSET :offset';
    }

    $statement = $pdo->prepare($sql);

    foreach ($filter['parameter'] as $nama => $nilai) {
        $statement->bindValue(
            ':' . $nama,
            $nilai,
            is_int($nilai) ? PDO::PARAM_INT : PDO::PARAM_STR
        );
    }

    if ($batas !== null) {
        $statement->bindValue(':batas', $batas, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
    }

    $statement->execute();

    return $statement->fetchAll();
}

function ambilRingkasanTiketUser(PDO $pdo, int $userId): array
{
    $statement = $pdo->prepare(
        "SELECT
            COUNT(*) AS total,
            COUNT(CASE WHEN status = 'Baru' THEN 1 END) AS baru,
            COUNT(CASE WHEN status = 'Diproses' THEN 1 END) AS diproses,
            COUNT(CASE WHEN status = 'Selesai' THEN 1 END) AS selesai
        FROM tickets
        WHERE user_id = :user_id"
    );

    $statement->execute([
        'user_id' => $userId,
    ]);

    $hasil = $statement->fetch(PDO::FETCH_ASSOC);

    return [
        'total' => (int) $hasil['total'],
        'Baru' => (int) $hasil['baru'],
        'Diproses' => (int) $hasil['diproses'],
        'Selesai' => (int) $hasil['selesai'],
    ];
}

function buatFilterTiket(
    string $pencarian = '',
    string $status = '',
    string $tampilan = 'semua'
): array {
    if (!in_array($tampilan, ['semua', 'aktif', 'riwayat'], true)) {
        throw new InvalidArgumentException(
            'Tampilan daftar tiket tidak valid.'
        );
    }

    $kondisi = [];
    $parameter = [];

    // Batasi data berdasarkan bagian yang dibuka.
    if ($tampilan === 'aktif') {
        $kondisi[] = "tickets.status IN ('Baru', 'Diproses')";
    } elseif ($tampilan === 'riwayat') {
        $kondisi[] = "tickets.status = 'Selesai'";
    }

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
    string $status = '',
    string $tampilan = 'semua'
): int {
    $filter = buatFilterTiket($pencarian, $status, $tampilan);
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
    int $offset = 0,
    string $tampilan = 'semua'
): array {
    if ($offset < 0 || ($batas !== null && $batas < 1)) {
        throw new InvalidArgumentException(
            'Batas harus positif dan offset tidak boleh negatif.'
        );
    }

    $filter = buatFilterTiket($pencarian, $status, $tampilan);

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
    $statusAsal = match ($status) {
        'Diproses' => 'Baru',
        'Selesai' => 'Diproses',
        default => null,
    };

    if ($statusAsal === null) {
        return false;
    }

    $statement = $pdo->prepare(
        'UPDATE tickets
        SET status = :status_tujuan
        WHERE id = :id
        AND status = :status_asal'
    );

    $statement->execute([
        'status_tujuan' => $status,
        'id' => $tiketId,
        'status_asal' => $statusAsal,
    ]);

    return $statement->rowCount() === 1;
}
