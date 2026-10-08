<?php

require __DIR__ . '/../../app/includes/cek-akses.php';
wajibRole('admin');

$idMasukan = $_GET['id'] ?? '';

$userId = is_string($idMasukan)
    ? filter_var(
        $idMasukan,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    )
    : false;

if ($userId === false) {
    http_response_code(400);
    exit('ID user tidak valid.');
}

try {
    require __DIR__ . '/../../app/config/database.php';
    require __DIR__ . '/../../app/functions/user.php';

    $user = cariUserById($pdo, $userId);
} catch (PDOException $error) {
    error_log($error->getMessage());

    http_response_code(503);
    exit('Data user belum dapat dimuat. Silakan coba lagi.');
}

if ($user === null) {
    http_response_code(404);
    exit('Akun user tidak ditemukan.');
}

$cssHalaman = '/assets/css/users.css';

require __DIR__ . '/../../app/includes/header.php';
?>

<main class="container main-content">
    <div class="users-heading">
        <h2>Pengelolaan user</h2>
        <a href="/admin/users.php">Kembali ke daftar user</a>
    </div>

    <?php require __DIR__ . '/../../app/components/pesan.php'; ?>

    <?php require __DIR__ . '/../../app/components/form-edit-user.php'; ?>
</main>

<?php require __DIR__ . '/../../app/includes/footer.php'; ?>