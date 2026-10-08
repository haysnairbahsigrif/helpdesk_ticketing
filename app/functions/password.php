<?php

function gantiPasswordUser(
    PDO $pdo,
    int $userId,
    string $passwordLama,
    string $passwordBaru
): string {
    $statement = $pdo->prepare(
        'SELECT password_hash
        FROM users
        WHERE id = :id AND is_active = 1'
    );

    $statement->execute(['id' => $userId]);
    $akun = $statement->fetch();

    if (!$akun || !password_verify($passwordLama, $akun['password_hash'])) {
        return 'password-lama-salah';
    }

    if (password_verify($passwordBaru, $akun['password_hash'])) {
        return 'password-sama';
    }

    $hashBaru = password_hash($passwordBaru, PASSWORD_DEFAULT);

    $update = $pdo->prepare(
        'UPDATE users
        SET password_hash = :hash_baru,
            must_change_password = 0
        WHERE id = :id
        AND is_active = 1
        AND password_hash = :hash_lama'
    );

    $update->execute([
        'hash_baru' => $hashBaru,
        'id' => $userId,
        'hash_lama' => $akun['password_hash'],
    ]);

    return $update->rowCount() === 1
        ? 'password-berhasil'
        : 'password-gagal';
}

function resetPasswordUser(
    PDO $pdo,
    int $userId,
    string $passwordSementara
): bool {
    $hash = password_hash(
        $passwordSementara,
        PASSWORD_DEFAULT
    );

    $statement = $pdo->prepare(
        'UPDATE users
        SET password_hash = :password_hash,
        must_change_password = 1,
        session_version = session_version + 1
        WHERE id = :id AND role = :role'
    );

    $statement->execute([
        'password_hash' => $hash,
        'id' => $userId,
        'role' => 'user',
    ]);

    return $statement->rowCount() === 1;
}