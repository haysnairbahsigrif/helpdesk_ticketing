<?php

/** @var bool $gagalMemuatTiket */
/** @var array $daftarTiket */
/** @var string $pencarian */
/** @var string $status */
/** @var int $offset */
/** @var int $totalTiket */
?>

<section class="panel ticket-list">
    <h2>Tiket saya</h2>

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
                Anda belum memiliki tiket.
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

        <?php foreach ($daftarTiket as $tiket): ?>
            <article class="ticket-item">
                <h3>Tiket #<?php echo (int) $tiket['id']; ?></h3>

                <p class="ticket-complaint"><?php
                                            echo htmlspecialchars(
                                                $tiket['keluhan'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            );
                                            ?></p>

                <p>
                    <strong>Status:</strong>
                    <?php
                    echo htmlspecialchars(
                        $tiket['status'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </p>

                <p class="ticket-date">
                    Dibuat:
                    <?php
                    echo htmlspecialchars(
                        $tiket['created_at'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </p>
            </article>
        <?php endforeach; ?>

        <?php
        require __DIR__ . '/pagination-tiket-user.php';
        ?>
    <?php endif; ?>
</section>