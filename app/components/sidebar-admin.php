<?php

/** @var string $menuAktif */
$menuAktif = $menuAktif ?? '';
?>

<aside class="admin-sidebar" id="admin-sidebar">
    <a class="admin-brand" href="/admin/">
        Helpdesk<span>Ticketing</span>
    </a>

    <p class="admin-menu-label">MENU UTAMA</p>

    <nav class="admin-navigation" aria-label="Navigasi admin">
        <a
            href="/admin/"
            <?php if ($menuAktif === 'dashboard'): ?>
            aria-current="page"
            <?php endif; ?>>
            <span
                class="sidebar-icon sidebar-icon--dashboard"
                aria-hidden="true"></span>
            <span>Dashboard</span>
        </a>

        <?php $submenuTiketTerbuka = $menuAktif === 'tiket'; ?>

        <div class="admin-menu-group">
            <a
                href="/admin/tiket.php"
                <?php if ($menuAktif === 'tiket'): ?>
                aria-current="page"
                <?php endif; ?>>
                <span
                    class="sidebar-icon sidebar-icon--ticket"
                    aria-hidden="true"></span>
                <span>Daftar tiket</span>
            </a>
        </div>

        <a
            href="/admin/users.php"
            <?php if ($menuAktif === 'users'): ?>
            aria-current="page"
            <?php endif; ?>>
            <span
                class="sidebar-icon sidebar-icon--users"
                aria-hidden="true"></span>
            <span>Kelola user</span>
        </a>
    </nav>

    <div class="admin-sidebar-bottom">
        <p>Panel IT Support</p>
    </div>
</aside>