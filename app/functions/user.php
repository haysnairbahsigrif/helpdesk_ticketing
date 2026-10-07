<?php

function ambilDaftarUser(PDO $pdo): array
{
    $statement = $pdo->prepare(
        'SELECT id, nama, username, email, is_active
        FROM users
        WHERE role = :role
        ORDER BY id DESC'
    );

    $statement->execute([
        'role' => 'user',
    ]);

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
