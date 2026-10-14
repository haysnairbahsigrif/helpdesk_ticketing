<section class="panel ticket-list">
    <h2>
        <?php
        echo match ($tampilan ?? 'aktif') {
            'semua' => 'Seluruh tiket',
            'riwayat' => 'Riwayat tiket',
            default => 'Daftar tiket aktif',
        };
        ?>
    </h2>

    <?php if ($gagalMemuatTiket): ?>

        <p role="alert">
            Daftar tiket belum dapat dimuat. Silakan coba lagi.
        </p>

    <?php elseif (empty($daftarTiket)): ?>

        <p>Belum ada tiket masuk.</p>

    <?php else: ?>

        <?php require __DIR__ . '/tabel-tiket-admin.php'; ?>

    <?php endif; ?>

    <?php if (!$gagalMemuatTiket && !empty($daftarTiket)): ?>
        <?php require __DIR__ . '/pagination-tiket.php'; ?>
    <?php endif; ?>
</section>