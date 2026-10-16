<?php

/** @var array $tiket */

$solusiTiket = trim($tiket['solusi'] ?? '');
$namaAdminPenyelesai = trim($tiket['nama_admin_penyelesai'] ?? '');

if ($namaAdminPenyelesai === '') {
    $namaAdminPenyelesai = ($tiket['completed_by'] ?? null) === null
        ? 'Belum tercatat'
        : 'Admin tidak tersedia';
}
?>

<section class="ticket-dialog-handling ticket-resolution">
    <h3>Solusi penyelesaian</h3>

    <?php if ($solusiTiket !== ''): ?>
        <p class="ticket-resolution-text"><?php
            echo htmlspecialchars(
                $solusiTiket,
                ENT_QUOTES | ENT_SUBSTITUTE,
                'UTF-8'
            );
        ?></p>
    <?php else: ?>
        <p class="ticket-resolution-empty">
            Solusi penyelesaian belum tercatat untuk tiket ini.
        </p>
    <?php endif; ?>

    <dl class="ticket-resolution-meta">
        <div>
            <dt>Diselesaikan oleh</dt>
            <dd>
                <?php
                echo htmlspecialchars(
                    $namaAdminPenyelesai,
                    ENT_QUOTES | ENT_SUBSTITUTE,
                    'UTF-8'
                );
                ?>
            </dd>
        </div>

        <div>
            <dt>Tanggal selesai</dt>
            <dd>
                <?php
                echo htmlspecialchars(
                    formatTanggalTiket(
                        $tiket['completed_at'] ?? null,
                        true
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>
            </dd>
        </div>
    </dl>
</section>