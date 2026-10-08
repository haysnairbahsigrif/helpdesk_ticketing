<?php

function ambilDaftarUser(
    PDO $pdo,
    string $pencarian = '',
    string $status = '',
    ?int $batas = null,
    int $offset = 0
): array {
    $filter = filterDaftarUser($pencarian, $status);

    $sql = 'SELECT id, nama, username, email, is_active
            FROM users '
        . $filter['kondisi']
        . ' ORDER BY id DESC';

    if ($batas !== null) {
        if ($batas < 1 || $offset < 0) {
            throw new InvalidArgumentException(
                'Batas harus positif dan offset tidak boleh negatif.'
            );
        }

        $sql .= ' LIMIT :batas OFFSET :offset';
    }

    $statement = $pdo->prepare($sql);

    foreach ($filter['parameter'] as $nama => $nilai) {
        $tipe = is_int($nilai) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $statement->bindValue(':' . $nama, $nilai, $tipe);
    }

    if ($batas !== null) {
        $statement->bindValue(':batas', $batas, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
    }

    $statement->execute();

    return $statement->fetchAll();
}

function tambahUser(
    PDO $pdo,
    string $nama,
    string $username,
    ?string $email,
    string $password
): void {
    $statement = $pdo->prepare(
        'INSERT INTO users
        (nama, username, email, password_hash, 
        role, is_active, must_change_password) 
        VALUES
        (:nama, :username, :email, :password_hash, 
        \'user\', 1, 1)'
    );

    $statement->execute([
        'nama' => $nama,
        'username' => $username,
        'email' => $email,
        'password_hash' => password_hash(
            $password,
            PASSWORD_DEFAULT
        ),
    ]);
}

function cariUserById(PDO $pdo, int $userId): ?array
{
    $statement = $pdo->prepare(
        'SELECT id, nama, username, email, is_active
        FROM users
        WHERE id = :id AND role = :role'
    );

    $statement->execute([
        'id' => $userId,
        'role' => 'user',
    ]);

    $user = $statement->fetch();

    return $user === false ? null : $user;
}

function ubahStatusUser(
    PDO $pdo,
    int $userId,
    int $statusAktif
): bool {
    if (!in_array($statusAktif, [0, 1], true)) {
        return false;
    }

    $sql = 'UPDATE users
            SET is_active = :status';

    if ($statusAktif === 0) {
        $sql .= ', session_version = session_version + 1';
    }

    $sql .= ' WHERE id = :id
            AND role = :role
            AND is_active <> :status_sekarang';

    $statement = $pdo->prepare($sql);

    $statement->execute([
        'status' => $statusAktif,
        'id' => $userId,
        'role' => 'user',
        'status_sekarang' => $statusAktif,
    ]);

    return $statement->rowCount() === 1;
}

function filterDaftarUser(
    string $pencarian,
    string $status
): array {
    $kondisi = 'WHERE role = :role';
    $parameter = ['role' => 'user'];

    if ($pencarian !== '') {
        $kondisi .= " AND (
            nama LIKE :nama ESCAPE '!'
            OR username LIKE :username ESCAPE '!'
            OR email LIKE :email ESCAPE '!'
        )";

        $teks = str_replace(
            ['!', '%', '_'],
            ['!!', '!%', '!_'],
            $pencarian
        );

        $parameter['nama'] = '%' . $teks . '%';
        $parameter['username'] = '%' . $teks . '%';
        $parameter['email'] = '%' . $teks . '%';
    }

    if ($status !== '') {
        $kondisi .= ' AND is_active = :is_active';
        $parameter['is_active'] = $status === 'aktif' ? 1 : 0;
    }

    return [
        'kondisi' => $kondisi,
        'parameter' => $parameter,
    ];
}

function hitungUser(
    PDO $pdo,
    string $pencarian = '',
    string $status = ''
): int {
    $filter = filterDaftarUser($pencarian, $status);

    $statement = $pdo->prepare(
        'SELECT COUNT(*) FROM users ' . $filter['kondisi']
    );

    $statement->execute($filter['parameter']);

    return (int) $statement->fetchColumn();
}

function ubahDataUser(
    PDO $pdo,
    int $userId,
    string $nama,
    string $username,
    ?string $email
): bool {
    $statement = $pdo->prepare(
        'UPDATE users
        SET nama = :nama,
            username = :username,
            email = :email
        WHERE id = :id AND role = :role'
    );

    $statement->execute([
        'nama' => $nama,
        'username' => $username,
        'email' => $email,
        'id' => $userId,
        'role' => 'user',
    ]);

    if ($statement->rowCount() > 0) {
        return true;
    }

    // Menyimpan data yang sama bukan kegagalan.
    return cariUserById($pdo, $userId) !== null;
}