<section class="panel ticket-list">
    <h2>Seluruh tiket</h2>

    <?php if ($gagalMemuatTiket): ?>

        <p role="alert">
            Daftar tiket belum dapat dimuat. Silakan coba lagi.
        </p>

    <?php elseif (empty($daftarTiket)): ?>

        <p>Belum ada tiket masuk.</p>

    <?php else: ?>

        <?php foreach ($daftarTiket as $tiket): ?>
            <article class="ticket-item">
                <h3>Tiket #<?php echo (int) $tiket['id']; ?></h3>

                <p>
                    <strong>Pelapor:</strong>
                    <?php
                    echo htmlspecialchars(
                        $tiket['nama_pengguna'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </p>

                <p>
                    <strong>Email akun:</strong>
                    <?php
                    echo htmlspecialchars(
                        $tiket['email_pemilik'] ?? 'Belum terhubung ke akun',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </p>

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
                <?php require __DIR__ . '/form-status.php'; ?>
            </article>
        <?php endforeach; ?>

    <?php endif; ?>
</section>