<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php
            echo htmlspecialchars(
                $judulHalaman ?? 'Admin',
                ENT_QUOTES,
                'UTF-8'
            );
            ?> — Helpdesk Ticketing</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/admin-layout.css">
    <?php if (isset($cssHalaman)): ?>
        <link
            rel="stylesheet"
            href="<?php
                    echo htmlspecialchars(
                        $cssHalaman,
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>">
    <?php endif; ?>
</head>

<body class="admin-body">
    <div class="admin-shell">
        <?php
        require __DIR__ . '/../components/sidebar-admin.php';
        ?>

        <button
            class="admin-overlay"
            type="button"
            aria-label="Tutup menu navigasi"
            hidden>
        </button>

        <div class="admin-workspace">
            <header class="admin-topbar">
                <button
                    class="admin-menu-toggle"
                    type="button"
                    aria-controls="admin-sidebar"
                    aria-expanded="false">
                    <span aria-hidden="true">☰</span>
                    <span>Menu</span>
                </button>

                <span class="admin-topbar-title">
                    Panel administrator
                </span>

                <div class="admin-account">
                    <span class="admin-account-name">
                        <?php
                        echo htmlspecialchars(
                            $_SESSION['akun']['nama'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>
                    </span>

                    <?php
                    require __DIR__ . '/../components/form-logout.php';
                    ?>
                </div>
            </header>