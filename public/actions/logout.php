<?php

require __DIR__ . '/../../app/config/session.php';

// Logout harus melalui pengiriman formulir.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /login.php');
    exit;
}

// Periksa token keamanan.
$token = $_POST['csrf_token'] ?? '';
$tokenSession = $_SESSION['csrf_token'] ?? '';

if (
    !is_string($token)
    || $tokenSession === ''
    || !hash_equals($tokenSession, $token)
) {
    http_response_code(403);
    exit('Permintaan logout tidak valid. Muat ulang halaman.');
}

// Kosongkan data sesi.
$_SESSION = [];

// Hapus cookie sesi dari browser.
if (ini_get('session.use_cookies')) {
    $pengaturan = session_get_cookie_params();

    setcookie(session_name(), '', [
        'expires' => time() - 3600,
        'path' => $pengaturan['path'],
        'domain' => $pengaturan['domain'],
        'secure' => $pengaturan['secure'],
        'httponly' => $pengaturan['httponly'],
        'samesite' => $pengaturan['samesite'] ?? 'Lax',
    ]);
}

// Hapus sesi di server.
session_destroy();

header('Location: /login.php', true, 303);
exit;