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
            Dashboard
        </a>

        <a
            href="/admin/tiket.php"
            <?php if ($menuAktif === 'tiket'): ?>
                aria-current="page"
            <?php endif; ?>>
            Daftar tiket
        </a>

        <a
            href="/admin/users.php"
            <?php if ($menuAktif === 'users'): ?>
                aria-current="page"
            <?php endif; ?>>
            Kelola user
        </a>
    </nav>

    <div class="admin-sidebar-bottom">
        <p>Panel IT Support</p>
    </div>
</aside>