<?php

/** @var array $tiket */
/** @var array $riwayatPrioritasPerTiket */
/** @var bool $gagalMemuatRiwayatPrioritas */

$riwayatTiket = $riwayatPrioritasPerTiket[(int) $tiket['id']] ?? [];
?>

<section class="ticket-dialog-handling">
    <h3>Riwayat perubahan prioritas</h3>

    <?php if ($gagalMemuatRiwayatPrioritas): ?>
        <p role="alert">
            Riwayat belum dapat dimuat. Silakan muat ulang halaman.
        </p>

    <?php elseif ($riwayatTiket === []): ?>
        <p>Belum ada perubahan prioritas yang tercatat.</p>

    <?php else: ?>
        <ol class="priority-history">
            <?php foreach ($riwayatTiket as $perubahan): ?>
                <li class="priority-history-item">
                    <p class="priority-history-change">
                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $perubahan['prioritas_lama']
                                    ?? 'Belum ditentukan',
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>
                            <span
                                class="remix-icon remix-icon--right"
                                role="img"
                                aria-label="menjadi"></span>
                            <?php
                            echo htmlspecialchars(
                                $perubahan['prioritas_baru'],
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>
                        </strong>
                    </p>

                    <p class="priority-history-meta">
                        Oleh
                        <?php
                        echo htmlspecialchars(
                            $perubahan['nama_admin'] ?? 'Admin tidak tersedia',
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>
                        ·
                        <?php
                        echo htmlspecialchars(
                            formatTanggalTiket(
                                $perubahan['created_at'],
                                true
                            ),
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>
                    </p>

                    <p class="priority-history-reason"><?php
                                                        echo htmlspecialchars(
                                                            $perubahan['alasan'],
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        );
                                                        ?></p>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>
</section>