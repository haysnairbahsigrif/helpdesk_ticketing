<?php

/** @var string $pencarian */
/** @var array<int, string> $status */
/** @var array<int, string> $filterPrioritas */
/** @var bool $gagalMemuatTiket */
/** @var array $daftarTiket */
/** @var int $offset */
/** @var int $totalTiket */
?>

<section
    class="panel ticket-list"
    id="daftar-tiket"
    aria-labelledby="judul-daftar-tiket"
>
    <h2 id="judul-daftar-tiket">Seluruh tiket</h2>

    <?php require __DIR__ . '/form-cari-tiket.php'; ?>

    <?php if ($gagalMemuatTiket): ?>
        <p role="alert">
            Daftar tiket belum dapat dimuat.
            Silakan muat ulang halaman.
        </p>

    <?php elseif (empty($daftarTiket)): ?>
        <p role="status">
            <?php if (
                $pencarian !== ''
                || $status !== []
                || $filterPrioritas !== []
            ): ?>
                Tidak ada tiket yang cocok dengan pencarian
                atau filter yang dipilih.
            <?php else: ?>
                Belum ada tiket yang masuk.
            <?php endif; ?>
        </p>

    <?php else: ?>
        <p class="ticket-result-count">
            Menampilkan
            <?php echo $offset + 1; ?>–<?php
            echo $offset + count($daftarTiket);
            ?>
            dari <?php echo $totalTiket; ?> tiket.
        </p>

        <?php
        require __DIR__ . '/tabel-tiket-admin.php';
        require __DIR__ . '/pagination-tiket.php';
        ?>
    <?php endif; ?>
</section>