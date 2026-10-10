<?php

require __DIR__ . '/../app/config/session.php';

// Akun yang sudah login diarahkan sesuai perannya.
$role = $_SESSION['akun']['role'] ?? null;

if ($role === 'admin') {
    header('Location: /admin/');
    exit;
}

if ($role === 'user') {
    header('Location: /user/');
    exit;
}

// Siapkan token untuk formulir login.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Ambil pesan untuk satu kali tampilan.
$pesanLogin = $_SESSION['pesan_login'] ?? '';
$jenisPesanLogin = $_SESSION['jenis_pesan_login'] ?? 'info';
$posisiPesanLogin = $_SESSION['posisi_pesan_login'] ?? 'atas';

unset(
    $_SESSION['pesan_login'],
    $_SESSION['jenis_pesan_login'],
    $_SESSION['posisi_pesan_login']
);

if (!is_string($pesanLogin)) {
    $pesanLogin = '';
}

if (!in_array($jenisPesanLogin, ['success', 'error', 'info'], true)) {
    $jenisPesanLogin = 'info';
}

$pesanLoginInline = '';

if (
    $jenisPesanLogin === 'error'
    && $posisiPesanLogin === 'password'
) {
    $pesanLoginInline = $pesanLogin;
    $pesanLogin = '';
}

$cssHalaman = '/assets/css/login.css';

require __DIR__ . '/../app/includes/header.php';
?>

<main class="container main-content">
    <?php
    require __DIR__ . '/../app/components/pesan-login.php';
    require __DIR__ . '/../app/components/form-login.php';
    ?>
</main>

<?php require __DIR__ . '/../app/includes/footer.php'; ?>