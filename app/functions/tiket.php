<?php

require_once __DIR__ . '/nomor-tiket.php';

// Menyimpan tiket baru.
function tambahTiket(
    PDO $pdo,
    int $userId,
    string $nama,
    string $keluhan,
    string $DepartemenPelapor,
    string $urgensi
): void {
    $statement = $pdo->prepare(
        'INSERT INTO tickets
            (
                nomor_tiket,
                user_id,
                nama_pengguna,
                keluhan,
                departemen_pelapor,
                urgensi,
                prioritas
            )
        VALUES
            (
                :nomor_tiket,
                :user_id,
                :nama,
                :keluhan,
                :departemen_pelapor,
                :urgensi,
                :prioritas
            )'
    );

    // Maksimal lima percobaan jika nomor yang dibuat sudah digunakan.
    for ($percobaan = 1; $percobaan <= 5; $percobaan++) {
        $nomorTiket = buatNomorTiket();

        try {
            $statement->execute([
                'nomor_tiket' => $nomorTiket,
                'user_id' => $userId,
                'nama' => $nama,
                'keluhan' => $keluhan,
                'departemen_pelapor' => $DepartemenPelapor,
                'urgensi' => $urgensi,
                'prioritas' => $urgensi,
            ]);

            return;
        } catch (PDOException $error) {
            $kodeMySQL = (int) ($error->errorInfo[1] ?? 0);
            $pesanMySQL = (string) ($error->errorInfo[2] ?? '');

            $nomorDuplikat = $kodeMySQL === 1062
                && str_contains(
                    $pesanMySQL,
                    'uq_tickets_nomor_tiket'
                );

            // Kesalahan lain diteruskan ke action tambah tiket.
            if (!$nomorDuplikat || $percobaan === 5) {
                throw $error;
            }
        }
    }
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
        $where .= "
        AND (
            nomor_tiket LIKE :cari_nomor ESCAPE '!'
            OR keluhan LIKE :cari_keluhan ESCAPE '!'
        )
    ";

        $kataKunci = '%' . str_replace(
            ['!', '%', '_'],
            ['!!', '!%', '!_'],
            $pencarian
        ) . '%';

        $parameter['cari_nomor'] = $kataKunci;
        $parameter['cari_keluhan'] = $kataKunci;
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
        SELECT
            id,
            nomor_tiket,
            keluhan,
            departemen_pelapor,
            urgensi,
            prioritas,
            status,
            created_at,
            completed_at
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
    string|array $status = '',
    string $tampilan = 'semua',
    array $prioritas = []
): array {
    if (!in_array($tampilan, ['semua', 'aktif', 'riwayat'], true)) {
        throw new InvalidArgumentException(
            'Tampilan daftar tiket tidak valid.'
        );
    }

    // Terima status tunggal dari tautan lama maupun array checkbox.
    $daftarStatus = is_string($status)
        ? ($status === '' ? [] : [$status])
        : $status;

    foreach ($daftarStatus as $nilai) {
        if (
            !is_string($nilai)
            || !in_array($nilai, ['Baru', 'Diproses', 'Selesai'], true)
        ) {
            throw new InvalidArgumentException('Filter status tidak valid.');
        }
    }

    foreach ($prioritas as $nilai) {
        if (
            !is_string($nilai)
            || !in_array(
                $nilai,
                ['Rendah', 'Sedang', 'Tinggi', 'belum-ditentukan'],
                true
            )
        ) {
            throw new InvalidArgumentException('Filter prioritas tidak valid.');
        }
    }

    $daftarStatus = array_values(array_unique($daftarStatus));
    $daftarPrioritas = array_values(array_unique($prioritas));

    $kondisi = [];
    $parameter = [];

    // Pertahankan dukungan pemanggilan fungsi yang sudah ada.
    if ($tampilan === 'aktif') {
        $kondisi[] = "tickets.status IN ('Baru', 'Diproses')";
    } elseif ($tampilan === 'riwayat') {
        $kondisi[] = "tickets.status = 'Selesai'";
    }

    if ($pencarian !== '') {
        $kondisi[] = "
            (
                tickets.nomor_tiket LIKE :nomor_tiket ESCAPE '!'
                OR tickets.nama_pengguna LIKE :nama ESCAPE '!'
                OR tickets.keluhan LIKE :keluhan ESCAPE '!'
            )
        ";

        $kataKunci = '%' . str_replace(
            ['!', '%', '_'],
            ['!!', '!%', '!_'],
            $pencarian
        ) . '%';

        $parameter['nomor_tiket'] = $kataKunci;
        $parameter['nama'] = $kataKunci;
        $parameter['keluhan'] = $kataKunci;
    }

    if ($daftarStatus !== []) {
        $placeholderStatus = [];

        foreach ($daftarStatus as $indeks => $nilai) {
            $namaParameter = 'status_' . $indeks;
            $placeholderStatus[] = ':' . $namaParameter;
            $parameter[$namaParameter] = $nilai;
        }

        $kondisi[] = 'tickets.status IN ('
            . implode(', ', $placeholderStatus)
            . ')';
    }

    if ($daftarPrioritas !== []) {
        $kondisiPrioritas = [];
        $placeholderPrioritas = [];

        foreach ($daftarPrioritas as $indeks => $nilai) {
            if ($nilai === 'belum-ditentukan') {
                $kondisiPrioritas[] = 'tickets.prioritas IS NULL';
                continue;
            }

            $namaParameter = 'prioritas_' . $indeks;
            $placeholderPrioritas[] = ':' . $namaParameter;
            $parameter[$namaParameter] = $nilai;
        }

        if ($placeholderPrioritas !== []) {
            $kondisiPrioritas[] = 'tickets.prioritas IN ('
                . implode(', ', $placeholderPrioritas)
                . ')';
        }

        $kondisi[] = '(' . implode(' OR ', $kondisiPrioritas) . ')';
    }

    return [
        'where' => $kondisi !== []
            ? ' WHERE ' . implode(' AND ', $kondisi)
            : '',
        'parameter' => $parameter,
    ];
}

function hitungTiketAdmin(
    PDO $pdo,
    string $pencarian = '',
    string|array $status = '',
    string $tampilan = 'semua',
    array $prioritas = []
): int {
    $filter = buatFilterTiket(
        $pencarian,
        $status,
        $tampilan,
        $prioritas
    );

    $statement = $pdo->prepare(
        'SELECT COUNT(*) FROM tickets' . $filter['where']
    );

    $statement->execute($filter['parameter']);

    return (int) $statement->fetchColumn();
}

function ambilSemuaTiket(
    PDO $pdo,
    string $pencarian = '',
    string|array $status = '',
    ?int $batas = null,
    int $offset = 0,
    string $tampilan = 'semua',
    array $prioritas = []
): array {
    if ($offset < 0 || ($batas !== null && $batas < 1)) {
        throw new InvalidArgumentException(
            'Batas harus positif dan offset tidak boleh negatif.'
        );
    }

    $filter = buatFilterTiket(
        $pencarian,
        $status,
        $tampilan,
        $prioritas
    );

    $sql = '
    SELECT
        tickets.id,
        tickets.nomor_tiket,
        tickets.nama_pengguna,
        tickets.departemen_pelapor,
        tickets.keluhan,
        tickets.urgensi,
        tickets.prioritas,
        tickets.status,
        tickets.created_at,
        tickets.completed_at,
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
    string $status,
    int $adminId,
    ?string $solusi = null
): bool {
    $statusAsal = match ($status) {
        'Diproses' => 'Baru',
        'Selesai' => 'Diproses',
        default => null,
    };

    if ($statusAsal === null || $tiketId < 1 || $adminId < 1) {
        return false;
    }

    $parameter = [
        'status_tujuan' => $status,
        'id' => $tiketId,
        'status_asal' => $statusAsal,
        'admin_id' => $adminId,
    ];

    if ($status === 'Selesai') {
        $solusi = trim($solusi ?? '');
        $panjangSolusi = preg_match_all('/./us', $solusi);

        if (
            $solusi === ''
            || $panjangSolusi === false
            || $panjangSolusi > 5000
        ) {
            return false;
        }

        $sql = '
            UPDATE tickets
            SET status = :status_tujuan,
                solusi = :solusi,
                completed_by = :completed_by,
                completed_at = CURRENT_TIMESTAMP
            WHERE id = :id
              AND status = :status_asal
              AND EXISTS (
                  SELECT 1
                  FROM users
                  WHERE users.id = :admin_id
                    AND users.role = \'admin\'
                    AND users.is_active = 1
              )
        ';

        $parameter['solusi'] = $solusi;
        $parameter['completed_by'] = $adminId;
    } else {
        $sql = '
            UPDATE tickets
            SET status = :status_tujuan
            WHERE id = :id
              AND status = :status_asal
              AND EXISTS (
                  SELECT 1
                  FROM users
                  WHERE users.id = :admin_id
                    AND users.role = \'admin\'
                    AND users.is_active = 1
              )
        ';
    }

    $statement = $pdo->prepare($sql);
    $statement->execute($parameter);

    return $statement->rowCount() === 1;
}
