<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php
        echo htmlspecialchars(
            $judulHalaman ?? 'Layanan Pengguna',
            ENT_QUOTES,
            'UTF-8'
        );
        ?> — Helpdesk Ticketing</title>

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/user-layout.css">
</head>

<body class="user-body">
    <header class="user-topbar">
        <div class="container user-topbar-content">
            <a class="user-brand" href="/user/">
                Helpdesk Ticketing
            </a>

            <div class="user-account">
                <span class="user-account-name">
                    <?php
                    echo htmlspecialchars(
                        $_SESSION['akun']['nama'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </span>

                <nav class="user-account-actions" aria-label="Menu akun">
                    <a href="/user/ganti-password.php">
                        Ganti Password
                    </a>

                    <?php
                    require __DIR__ . '/../components/form-logout.php';
                    ?>
                </nav>
            </div>
        </div>
    </header>