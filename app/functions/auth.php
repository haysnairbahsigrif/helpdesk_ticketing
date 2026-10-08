<?php

function periksaLogin(
    PDO $pdo,
    string $username,
    string $password
): ?array {
    $statement = $pdo->prepare(
        'SELECT
            id, nama, username, email, password_hash,
            role, is_active, session_version
        FROM users
        WHERE username = :username
        LIMIT 1'
    );

    $statement->execute([
        'username' => trim($username),
    ]);

    $akun = $statement->fetch();

    if (!$akun || (int) $akun['is_active'] !== 1) {
        return null;
    }

    if (!password_verify($password, $akun['password_hash'])) {
        return null;
    }

    return [
        'id' => $akun['id'],
        'nama' => $akun['nama'],
        'username' => $akun['username'],
        'email' => $akun['email'],
        'role' => $akun['role'],
        'session_version' => (int) $akun['session_version'],
    ];
}