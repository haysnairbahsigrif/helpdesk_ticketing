<?php

require_once __DIR__ . '/../config/session.php';

function wajibRole(
    string $role,
    bool $izinkanGantiPassword = false
): void {
    if (!isset($_SESSION['akun'])) {
        header('Location: /login.php');
        exit;
    }

    // Simpan versi dari sesi login sebelum memperbarui data akun.
    $versiSession = (int) (
        $_SESSION['akun']['session_version'] ?? 0
    );

    try {
        require __DIR__ . '/../config/database.php';

        $statement = $pdo->prepare(
            'SELECT
                id, nama, username, email, role,
                is_active, must_change_password, session_version
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

    $pesanPenolakan = null;

    if (!$akun || (int) $akun['is_active'] !== 1) {
        $pesanPenolakan =
            'Akun tidak aktif atau tidak tersedia. Hubungi admin.';
    } elseif ($versiSession !== (int) $akun['session_version']) {
        $pesanPenolakan =
            'Sesi Anda sudah tidak berlaku. Silakan login kembali.';
    }

    if ($pesanPenolakan !== null) {
        $_SESSION = [];
        session_regenerate_id(true);

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['pesan_login'] = $pesanPenolakan;

        header('Location: /login.php', true, 303);
        exit;
    }

    // Perbarui data hanya setelah versi sesi dinyatakan sesuai.
    $_SESSION['akun'] = [
        'id' => $akun['id'],
        'nama' => $akun['nama'],
        'username' => $akun['username'],
        'email' => $akun['email'],
        'role' => $akun['role'],
        'must_change_password' => (int) $akun['must_change_password'],
        'session_version' => (int) $akun['session_version'],
    ];

    if ($akun['role'] !== $role) {
        http_response_code(403);
        exit('Akses ditolak. Akun Anda tidak memiliki izin untuk halaman ini.');
    }

    if (
        $akun['role'] === 'user'
        && (int) $akun['must_change_password'] === 1
        && !$izinkanGantiPassword
    ) {
        header('Location: /user/ganti-password.php', true, 303);
        exit;
    }
}