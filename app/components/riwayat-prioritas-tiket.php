<?php

/** @var array $tiket */
/** @var array $riwayatPrioritasPerTiket */
/** @var bool $gagalMemuatRiwayatPrioritas */

$riwayatTiket = $riwayatPrioritasPerTiket[(int) $tiket['id']] ?? [];
?>

<details
    class="ticket-disclosure"
    <?php if ($gagalMemuatRiwayatPrioritas): ?>open<?php endif; ?>>

    <summary>
        <span
            class="remix-icon remix-icon--right ticket-disclosure-arrow"
            aria-hidden="true"></span>

        <span>
            Riwayat prioritas
            <?php if (!$gagalMemuatRiwayatPrioritas): ?>
                (<?php echo count($riwayatTiket); ?>)
            <?php endif; ?>
        </span>
    </summary>

    <div class="ticket-disclosure-body">
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
                                $perubahan['nama_admin']
                                    ?? 'Admin tidak tersedia',
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
    </div>
</details>