<?php

$tampilanNavigasiUser = $tampilan ?? '';

$pilihanNavigasiUser = [
    'semua' => 'Semua Tiket',
    'aktif' => 'Tiket Aktif',
    'riwayat' => 'Riwayat Tiket',
];

$diHalamanTiketUser = array_key_exists(
    $tampilanNavigasiUser,
    $pilihanNavigasiUser
);
?>

<nav class="user-ticket-navigation" aria-label="Navigasi tiket pengguna">
    <div class="container">
        <details
            class="user-ticket-menu"
            <?php if ($diHalamanTiketUser): ?>
                open
            <?php endif; ?>
        >
            <summary class="user-ticket-menu-toggle">
                <span>Daftar Tiket</span>
                <span class="user-ticket-menu-arrow" aria-hidden="true"></span>
            </summary>

            <div class="user-ticket-submenu">
                <?php foreach ($pilihanNavigasiUser as $nilai => $label): ?>
                    <a
                        href="<?php
                        echo htmlspecialchars(
                            '/user/tiket.php?' . http_build_query(['view' => $nilai]),
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>"
                        <?php if ($tampilanNavigasiUser === $nilai): ?>
                            aria-current="page"
                        <?php endif; ?>
                    >
                        <?php
                        echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
                        ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </details>
    </div>
</nav>