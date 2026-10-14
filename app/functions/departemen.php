<?php

function ambilDepartemenUser(PDO $pdo, int $userId): ?string
{
    $statement = $pdo->prepare(
        'SELECT departments.nama
        FROM users
        LEFT JOIN departments ON users.department_id = departments.id
        WHERE users.id = :user_id
        AND users.role = :role'
    );

    $statement->execute([
        'user_id' => $userId,
        'role' => 'user',
    ]);

    $namaDepartemen = $statement->fetchColumn();

    return is_string($namaDepartemen) ? $namaDepartemen : null;
}

function ambilDaftarDepartemen(PDO $pdo): array
{
    $statement = $pdo->query(
        'SELECT id, nama
        FROM departments
        ORDER BY nama'
    );

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function DepartemenTersedia(PDO $pdo, int $departmentId): bool
{
    $statement = $pdo->prepare(
        'SELECT id
        FROM departments
        WHERE id = :id'
    );

    $statement->execute([
        'id' => $departmentId,
    ]);

    return $statement->fetchColumn() !== false;
}
