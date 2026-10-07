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

// Ambil pesan satu kali.
$pesanLogin = $_SESSION['pesan_login'] ?? '';
unset($_SESSION['pesan_login']);

$cssHalaman = '/assets/css/login.css';

require __DIR__ . '/../app/includes/header.php';
?>

<main class="container main-content">
    <?php if ($pesanLogin !== ''): ?>
        <p class="notification" role="alert">
            <?php
            echo htmlspecialchars(
                $pesanLogin,
                ENT_QUOTES,
                'UTF-8'
            );
            ?>
        </p>
    <?php endif; ?>

    <?php require __DIR__ . '/../app/components/form-login.php'; ?>
</main>

<?php require __DIR__ . '/../app/includes/footer.php'; ?>