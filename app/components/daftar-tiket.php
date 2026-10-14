<?php

/** @var bool $gagalMemuatTiket */
/** @var array $daftarTiket */
/** @var string $pencarian */
/** @var string $status */
/** @var int $offset */
/** @var int $totalTiket */
?>

<section class="panel ticket-list">
    <h2>
        <?php
        echo match ($tampilan ?? 'aktif') {
            'semua' => 'Semua tiket saya',
            'riwayat' => 'Riwayat tiket saya',
            default => 'Tiket aktif saya',
        };
        ?>
    </h2>

    <?php
    require __DIR__ . '/form-cari-tiket-user.php';
    ?>

    <?php if ($gagalMemuatTiket): ?>
        <p role="alert">
            Daftar tiket belum dapat dimuat. Silakan coba lagi.
        </p>

    <?php elseif (empty($daftarTiket)): ?>
        <p role="status">
            <?php if ($pencarian !== '' || $status !== ''): ?>
                Tidak ada tiket yang cocok dengan pencarian
                atau filter yang dipilih.
            <?php else: ?>
                <?php
                echo match ($tampilan ?? 'aktif') {
                    'semua' => 'Anda belum memiliki tiket.',
                    'riwayat' => 'Belum ada tiket Anda yang selesai.',
                    default => 'Anda tidak memiliki tiket aktif.',
                };
                ?>
            <?php endif; ?>
        </p>

    <?php else: ?>
        <p class="user-ticket-result">
            Menampilkan
            <?php echo $offset + 1; ?>–<?php
                                        echo $offset + count($daftarTiket);
                                        ?>
            dari <?php echo $totalTiket; ?> tiket.
        </p>

        <?php
        require __DIR__ . '/tabel-tiket-user.php';
        require __DIR__ . '/pagination-tiket-user.php';
        ?>
    <?php endif; ?>
</section>