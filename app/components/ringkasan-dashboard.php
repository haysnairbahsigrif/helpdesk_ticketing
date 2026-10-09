<?php

/** @var array<string, int> $ringkasan */

$kartuRingkasan = [
    [
        'label' => 'Total User',
        'jumlah' => $ringkasan['total_user'],
        'keterangan' => 'Akun aktif dan nonaktif',
        'kelas' => 'user',
        'url' => '/admin/users.php',
    ],
    [
        'label' => 'Total Tiket',
        'jumlah' => $ringkasan['total_tiket'],
        'keterangan' => 'Seluruh laporan pengguna',
        'kelas' => 'total',
        'url' => '/admin/tiket.php',
    ],
    [
        'label' => 'Tiket Baru',
        'jumlah' => $ringkasan['tiket_baru'],
        'keterangan' => 'Menunggu penanganan',
        'kelas' => 'baru',
        'url' => '/admin/tiket.php?status=Baru',
    ],
    [
        'label' => 'Diproses',
        'jumlah' => $ringkasan['tiket_diproses'],
        'keterangan' => 'Sedang ditangani',
        'kelas' => 'diproses',
        'url' => '/admin/tiket.php?status=Diproses',
    ],
    [
        'label' => 'Selesai',
        'jumlah' => $ringkasan['tiket_selesai'],
        'keterangan' => 'Penanganan selesai',
        'kelas' => 'selesai',
        'url' => '/admin/tiket.php?status=Selesai',
    ],
];
?>

<section aria-label="Ringkasan pengguna dan tiket">
    <div class="summary-grid">
        <?php foreach ($kartuRingkasan as $kartu): ?>
            <a
                class="summary-card summary-card--<?php
                echo htmlspecialchars(
                    $kartu['kelas'],
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>"
                href="<?php
                echo htmlspecialchars(
                    $kartu['url'],
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>">

                <h2 class="summary-label">
                    <?php
                    echo htmlspecialchars(
                        $kartu['label'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </h2>

                <p class="summary-value">
                    <?php
                    echo number_format($kartu['jumlah'], 0, ',', '.');
                    ?>
                </p>

                <p class="summary-description">
                    <?php
                    echo htmlspecialchars(
                        $kartu['keterangan'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </p>

                <span class="summary-link-label">
                    Lihat daftar <span aria-hidden="true">→</span>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</section>