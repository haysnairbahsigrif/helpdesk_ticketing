<?php

require_once __DIR__ . '/../config/session.php';

function wajibRole(string $role): void
{
    if (!isset($_SESSION['akun'])) {
        header('Location: /login.php');
        exit;
    }

    // Periksa kondisi akun terbaru dari database.
    try {
        require __DIR__ . '/../config/database.php';

        $statement = $pdo->prepare(
            'SELECT id, nama, username, email, role, is_active
            FROM users
            WHERE id = :id'
        );

        $statement->execute([
            'id' => (int) $_SESSION['akun']['id'],
        ]);

        $akun = $statement->fetch();
    } catch (PDOException $error) {
        error_log($error->getMessage());

        http_response_code(503);
        exit('Layanan sementara tidak tersedia. Silakan coba lagi.');
    }

    // Cabut status login jika akun hilang atau dinonaktifkan.
    if (!$akun || (int) $akun['is_active'] !== 1) {
        unset($_SESSION['akun']);
        session_regenerate_id(true);

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['pesan_login'] = 'Akun tidak aktif atau tidak tersedia. Hubungi admin.';

        header('Location: /login.php');
        exit;
    }

    // Perbarui data session berdasarkan akun terbaru.
    $_SESSION['akun'] = [
        'id' => $akun['id'],
        'nama' => $akun['nama'],
        'username' => $akun['username'],
        'email' => $akun['email'],
        'role' => $akun['role'],
    ];

    if ($akun['role'] !== $role) {
        http_response_code(403);
        exit('Akses ditolak. Akun Anda tidak memiliki izin untuk halaman ini.');
    }
}