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
            <?php
            $punyaTautan = $kartu['url'] !== null;

            $kelasKartu = 'summary-card summary-card--'
                . $kartu['kelas'];
            ?>

            <?php if ($punyaTautan): ?>
                <a
                    class="<?php
                            echo htmlspecialchars($kelasKartu, ENT_QUOTES, 'UTF-8');
                            ?>"
                    href="<?php
                            echo htmlspecialchars($kartu['url'], ENT_QUOTES, 'UTF-8');
                            ?>">
                <?php else: ?>
                    <div
                        class="<?php
                                echo htmlspecialchars($kelasKartu, ENT_QUOTES, 'UTF-8');
                                ?>">
                    <?php endif; ?>

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

                    <?php if ($punyaTautan): ?>
                        <span class="summary-link-label">
                            Lihat daftar <span class="remix-icon remix-icon--right" aria-hidden="true"></span>
                        </span>
                    <?php else: ?>
                        <span class="summary-card-note">
                            Tiket aktif dan selesai
                        </span>
                    <?php endif; ?>

                    <?php if ($punyaTautan): ?>
                </a>
            <?php else: ?>
    </div>
<?php endif; ?>
<?php endforeach; ?>
</div>
</section>