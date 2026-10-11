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
            <button
                type="button"
                class="admin-menu-parent admin-submenu-toggle"
                aria-expanded="<?php echo $submenuTiketTerbuka ? 'true' : 'false'; ?>"
                aria-controls="submenu-tiket">
                <span
                    class="sidebar-icon sidebar-icon--ticket"
                    aria-hidden="true"></span>

                <span>Daftar tiket</span>

                <span class="admin-submenu-arrow" aria-hidden="true"></span>
            </button>

            <div
                class="admin-submenu"
                id="submenu-tiket"
                <?php if (!$submenuTiketTerbuka): ?>
                hidden
                <?php endif; ?>>

                <a
                    href="/admin/tiket.php?view=semua"
                    <?php if (
                        $menuAktif === 'tiket'
                        && ($tampilan ?? '') === 'semua'
                    ): ?>
                    aria-current="page"
                    <?php endif; ?>>
                    Semua Tiket
                </a>

                <a
                    href="/admin/tiket.php?view=aktif"
                    <?php if (
                        $menuAktif === 'tiket'
                        && ($tampilan ?? '') === 'aktif'
                    ): ?>
                    aria-current="page"
                    <?php endif; ?>>
                    Tiket Aktif
                </a>

                <a
                    href="/admin/tiket.php?view=riwayat"
                    <?php if (
                        $menuAktif === 'tiket'
                        && ($tampilan ?? '') === 'riwayat'
                    ): ?>
                    aria-current="page"
                    <?php endif; ?>>
                    Riwayat Tiket
                </a>
            </div>
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